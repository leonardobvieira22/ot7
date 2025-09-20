function onUpdateDatabase()
	print("> Updating database to version 26 (fix player_misc and blessings columns)")
	
	-- Verificar se a coluna 'info' existe na tabela player_misc
	local result = db.storeQuery("SHOW COLUMNS FROM `player_misc` LIKE 'info'")
	if result then
		result:free()
		print(">> Column 'info' already exists in player_misc table")
	else
		-- Adicionar a coluna 'info' se não existir (sem valor padrão para BLOB)
		db.query("ALTER TABLE `player_misc` ADD COLUMN `info` blob NOT NULL")
		print(">> Added missing 'info' column to player_misc table")
	end
	
	-- Corrigir o tipo da coluna blessings para comportar valores maiores
	db.query("ALTER TABLE `players` MODIFY COLUMN `blessings` tinyint(4) unsigned NOT NULL DEFAULT 0")
	print(">> Modified 'blessings' column type to tinyint(4) unsigned")
	
	-- Limpar valores inválidos na coluna blessings (maiores que 255)
	db.query("UPDATE `players` SET `blessings` = 0 WHERE `blessings` > 255 OR `blessings` < 0")
	print(">> Cleaned invalid blessings values")
	
	-- Inserir registros faltantes na tabela player_misc
	db.query("INSERT IGNORE INTO `player_misc` (`player_id`, `info`) SELECT `id`, '{}' FROM `players` WHERE `id` NOT IN (SELECT `player_id` FROM `player_misc`)")
	print(">> Added missing player_misc records")
	
	return true
end
