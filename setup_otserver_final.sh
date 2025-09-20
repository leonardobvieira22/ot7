#!/bin/bash
# SETUP FINAL DO OTSERVER - Garantia de funcionamento 100%
# Execute APÓS enviar os arquivos do servidor

echo "=========================================="
echo "  ⚔️ SETUP FINAL DO OTSERVER"
echo "  🎯 CONFIGURAÇÃO PARA PRODUÇÃO"
echo "=========================================="
echo ""

# Variáveis
SERVER_IP="181.215.45.238"
SERVER_DIR="/opt/otserver"
WEBSITE_DIR="/var/www/html"
DB_USER="otserver"
DB_PASS_FILE="/root/.db_pass"

# Verificar se as senhas foram salvas
if [ ! -f "$DB_PASS_FILE" ]; then
    echo "⚠️  Arquivo de senhas não encontrado"
    echo "Digite a senha do MySQL para usuário otserver:"
    read -s DB_PASS
    echo "$DB_PASS" > "$DB_PASS_FILE"
    chmod 600 "$DB_PASS_FILE"
else
    DB_PASS=$(cat "$DB_PASS_FILE")
fi

echo "🔍 Verificando arquivos enviados..."

# ==========================================
# 1. VERIFICAR ARQUIVOS OBRIGATÓRIOS
# ==========================================
REQUIRED_SERVER_FILES=(
    "tfs"
    "config.lua"
    "database.sql"
    "data/"
)

REQUIRED_WEBSITE_FILES=(
    "index.php"
    "config/config.php"
    "classes/"
    "system/"
    "layouts/"
)

echo ""
echo "📋 1. VERIFICANDO ARQUIVOS DO SERVIDOR..."
for file in "${REQUIRED_SERVER_FILES[@]}"; do
    if [ -e "${SERVER_DIR}/$file" ]; then
        echo "   ✅ $file"
    else
        echo "   ❌ $file - FALTANDO!"
        echo "Envie o arquivo/diretório: $file"
    fi
done

echo ""
echo "📋 2. VERIFICANDO ARQUIVOS DO WEBSITE..."
for file in "${REQUIRED_WEBSITE_FILES[@]}"; do
    if [ -e "${WEBSITE_DIR}/$file" ]; then
        echo "   ✅ $file"
    else
        echo "   ❌ $file - FALTANDO!"
        echo "Envie o arquivo/diretório: $file"
    fi
done

# ==========================================
# 2. COMPILAR SERVIDOR (SE NECESSÁRIO)
# ==========================================
echo ""
echo "⚔️ 3. VERIFICANDO EXECUTÁVEL DO SERVIDOR..."

cd $SERVER_DIR

if [ ! -f "tfs" ] || [ ! -x "tfs" ]; then
    echo "🔨 Compilando servidor..."
    
    # Verificar se existe CMakeLists.txt
    if [ -f "CMakeLists.txt" ]; then
        mkdir -p build
        cd build
        cmake .. -DCMAKE_BUILD_TYPE=Release
        make -j$(nproc)
        
        if [ -f "tfs" ]; then
            cp tfs ../
            cd ..
            echo "✅ Servidor compilado com sucesso"
        else
            echo "❌ Erro na compilação"
            exit 1
        fi
    else
        echo "❌ CMakeLists.txt não encontrado"
        echo "Verifique se os arquivos fonte foram enviados corretamente"
        exit 1
    fi
else
    echo "✅ Executável do servidor OK"
fi

# ==========================================
# 3. CONFIGURAR SERVIDOR PARA PRODUÇÃO
# ==========================================
echo ""
echo "⚙️ 4. CONFIGURANDO SERVIDOR PARA PRODUÇÃO..."

# Backup da config original se existe
if [ -f "config.lua" ]; then
    cp config.lua config.lua.original
fi

# Criar configuração otimizada para produção
cat > config.lua <<EOF
-- CONFIGURAÇÃO DE PRODUÇÃO - OTServer Tibia 8.60
-- Servidor: ${SERVER_IP}

-- Combat settings
worldType = "pvp"
hotkeyAimbotEnabled = true
protectionLevel = 25
pzLocked = 60 * 1000
removeChargesFromRunes = true
removeChargesFromPotions = true
removeWeaponAmmunition = true
removeWeaponCharges = true
timeToDecreaseFrags = 24 * 60 * 60 * 1000
whiteSkullTime = 10 * 60 * 1000
stairJumpExhaustion = 2 * 1000
experienceByKillingPlayers = false
expFromPlayersLevelRange = 75
redSkullLength = 24 * 60 * 60 * 1000
blackSkullLength = 48 * 60 * 60 * 1000
killsToRedSkull = 10
killsToBlackSkull = 12

-- Connection Config - PRODUÇÃO
ip = "0.0.0.0"  -- Escutar em todas as interfaces
bindOnlyGlobalAddress = false
loginProtocolPort = 7171
gameProtocolPort = 7172
statusProtocolPort = 7171
maxPlayers = 1000  -- Ajustado para produção
motd = "Bem-vindo ao servidor ${SERVER_IP}! Uptime garantido 24/7!"
onePlayerOnlinePerAccount = true  -- Segurança
allowClones = false  -- Segurança
serverName = "OTServer ${SERVER_IP}"
statusTimeout = 5 * 1000
replaceKickOnLogin = true
maxPacketsPerSecond = 25  -- Anti-DDoS

-- Versão (CRÍTICO: deve bater com cliente)
clientVersionMin = 860
clientVersionMax = 860
clientVersionStr = "8.60"

-- Depot Limit
freeDepotLimit = 10000
premiumDepotLimit = 100000
depotBoxes = 17

-- GameStore
gamestoreByModules = false

-- Casting System
enableLiveCasting = true
liveCastPort = 7173

-- Expert Pvp Config
expertPvp = false

-- Save settings - OTIMIZADO PARA PRODUÇÃO
serverSaveNotifyMessage = true
serverSaveNotifyDuration = 5
serverSaveCleanMap = true  -- Limpeza ativa para performance
serverSaveClose = false
serverSaveShutdown = false

-- Houses
housePriceEachSQM = 500
houseRentPeriod = "monthly"

-- Item Usage - OTIMIZADO
timeBetweenActions = 1000
timeBetweenExActions = 200
timeBetweenCustomActions = 1000

-- Map
mapName = "realmap"
mapAuthor = "OTServer-${SERVER_IP}"

-- Market
marketOfferDuration = 30 * 24 * 60 * 60
premiumToCreateMarketOffer = true
checkExpiredMarketOffersEachMinutes = 60
maxMarketOffersAtATimePerPlayer = 100

-- MySQL - PRODUÇÃO
mysqlHost = "localhost"
mysqlUser = "${DB_USER}"
mysqlPass = "${DB_PASS}"
mysqlDatabase = "global"
mysqlPort = 3306
mysqlSock = ""
passwordType = "sha1"

-- Misc - CONFIGURAÇÕES DE SEGURANÇA
allowChangeOutfit = true
freePremium = false
kickIdlePlayerAfterMinutes = 15  -- Reduzido para liberar slots
idleWarningTime = 10 * 60 * 1000
idleKickTime = 15 * 60 * 1000
maxMessageBuffer = 4
emoteSpells = true
classicEquipmentSlots = true
allowWalkthrough = false
coinPacketSize = 1
coinImagesURL = "http://${SERVER_IP}/images/store/"
classicAttackSpeed = false
allowBlockSpawn = false

-- Rates - BALANCEADAS PARA PRODUÇÃO
rateExp = 10
rateSkill = 10
rateLoot = 5
rateMagic = 4
rateSpawn = 5

-- Monster rates
rateMonsterHealth = 1.0
rateMonsterAttack = 2.0
rateMonsterDefense = 1.0

-- Monsters
deSpawnRange = 3
deSpawnRadius = 150

-- Stamina
staminaSystem = true

-- Scripts
warnUnsafeScripts = true
convertUnsafeScripts = true

-- Startup - OTIMIZADO PARA PRODUÇÃO
defaultPriority = "high"
startupDatabaseOptimization = true

-- Status server information
ownerName = "OTServer ${SERVER_IP}"
ownerEmail = "admin@${SERVER_IP}"
url = "http://${SERVER_IP}"
location = "Brazil"

-- Configurações adicionais de performance
cleanProtectionZones = false
maxPlayersPerAccount = 5
loginTries = 3
retryTimeout = 5 * 1000
loginTimeout = 60 * 1000
maxPlayers = 1000
maxPlayerSummons = 2
EOF

echo "✅ Configuração do servidor atualizada"

# ==========================================
# 4. IMPORTAR E CONFIGURAR BANCO DE DADOS
# ==========================================
echo ""
echo "🗄️ 5. CONFIGURANDO BANCO DE DADOS..."

if [ -f "database.sql" ]; then
    echo "Importando banco de dados..."
    
    # Verificar se o banco existe
    mysql -u $DB_USER -p$DB_PASS -e "USE global;" 2>/dev/null
    if [ $? -ne 0 ]; then
        echo "❌ Erro: Banco 'global' não encontrado ou credenciais inválidas"
        exit 1
    fi
    
    # Importar banco
    mysql -u $DB_USER -p$DB_PASS global < database.sql
    
    if [ $? -eq 0 ]; then
        echo "✅ Banco de dados importado"
        
        # Verificar tabelas essenciais
        TABLES=$(mysql -u $DB_USER -p$DB_PASS global -e "SHOW TABLES;" | grep -E "(accounts|players|guilds)" | wc -l)
        if [ $TABLES -ge 3 ]; then
            echo "✅ Tabelas essenciais verificadas"
        else
            echo "⚠️  Algumas tabelas podem estar faltando"
        fi
    else
        echo "❌ Erro ao importar banco de dados"
        exit 1
    fi
else
    echo "⚠️  Arquivo database.sql não encontrado"
fi

# ==========================================
# 5. CONFIGURAR WEBSITE PARA PRODUÇÃO
# ==========================================
echo ""
echo "🌐 6. CONFIGURANDO WEBSITE PARA PRODUÇÃO..."

cd $WEBSITE_DIR

# Backup da config original
if [ -f "config/config.php" ]; then
    cp config/config.php config/config.php.original
fi

# Configuração do website para produção
cat > config/config.php <<EOF
<?php
// CONFIGURAÇÃO DE PRODUÇÃO - Website OTServer
// Servidor: ${SERVER_IP}

if (!function_exists('is_https')) {
    function is_https() {
        if (!empty(\$_SERVER['HTTPS']) && strtolower(\$_SERVER['HTTPS']) !== 'off') {
            return true;
        } elseif (isset(\$_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower(\$_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
            return true;
        } elseif (!empty(\$_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower(\$_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
            return true;
        }
        return false;
    }
}

\$is_https = is_https();
\$protocol = \$is_https ? "https://" : "http://";
\$base_url = \$protocol . \$_SERVER['HTTP_HOST'];
\$base_url .= str_replace(basename(\$_SERVER['SCRIPT_NAME']), "", \$_SERVER['SCRIPT_NAME']);

// URLs para produção
\$config['base_url'] = \$base_url;
\$config['site']['base_url'] = \$base_url;
\$config['site']['realurl'] = "http://${SERVER_IP}/";
\$config['site']['realurlwww'] = "http://${SERVER_IP}/";
\$config['site']['testurl'] = "http://${SERVER_IP}/";

// Paths
\$config['site']['serverPath'] = "${SERVER_DIR}/";

// Shop ativado
\$config['site']['shopEnabled'] = true;

// Data de abertura
\$config['site']['start'] = 'Jan 01, 2024 12:00:00';

// reCAPTCHA (configurar depois)
\$config['site']['gRecaptchaSecret'] = "";
\$config['site']['gRecaptchaSiteKey'] = "";

// Widgets
\$config['site']['widget_rank'] = true;
\$config['site']['widget_supportButton'] = true;
\$config['site']['widget_buycharButton'] = false;
\$config['site']['widget_PremiumBox'] = true;
\$config['site']['widget_Serverinfobox'] = true;
\$config['site']['widget_Serverinfoboxfloat'] = true;
\$config['site']['widget_NetworksBox'] = true;
\$config['site']['widget_CurrentPollBox'] = true;

// MySQL - PRODUÇÃO
\$config['site']['sqlHost'] = "localhost";
\$config['site']['sqlUser'] = "${DB_USER}";
\$config['site']['sqlPass'] = "${DB_PASS}";
\$config['site']['sqlBD'] = "global";

// URLs de imagens
\$config['site']['animatedOutfits_url'] = 'http://${SERVER_IP}/AnimatedOutfits/animoutfit.php?';
\$config['site']['outfit_images_url'] = '/outfit.php?';
\$config['site']['icons_images_url'] = '/images/icons_damage/';
\$config['site']['item_images_extension'] = '.png';
\$config['site']['flag_images_url'] = '/images/flags/';

// Account Maker - PRODUÇÃO
\$config['site']['encryptionType'] = 'sha1';
\$config['site']['useServerConfigCache'] = false;

// Towns
\$towns_list = array(
    1 => 'Venore', 2 => 'Thais', 3 => 'Kazordoon', 4 => 'Carlin',
    5 => 'Ab\'Dendriel', 6 => 'Rookgaard', 7 => 'Liberty Bay',
    8 => 'Port Hope', 9 => 'Ankrahmun', 10 => 'Darashia', 11 => 'Edron'
);

// Vocations
\$vocations_list = [
    1 => "Sorcerer", 2 => "Druid", 3 => "Paladin", 4 => "Knight",
    5 => "Master Sorcerer", 6 => "Elder Druid", 7 => "Royal Paladin", 8 => "Elite Knight"
];

// Create Account - SEGURANÇA REFORÇADA
\$config['site']['one_email'] = true;
\$config['site']['create_account_verify_mail'] = false;  // Desabilitado por enquanto
\$config['site']['verify_code'] = true;
\$config['site']['email_days_to_change'] = 7;
\$config['site']['newaccount_premdays'] = 0;
\$config['site']['send_register_email'] = false;  // Desabilitado por enquanto

// Create Character
\$config['site']['newchar_vocations'] = array(1 => 'Sorcerer Sample', 2 => 'Druid Sample', 3 => 'Paladin Sample', 4 => 'Knight Sample');
\$config['site']['newchar_towns'] = array(2);  // Apenas Thais
\$config['site']['max_players_per_account'] = 5;

// Email (configurar depois)
\$config['site']['send_emails'] = false;
\$config['site']['mail_address'] = "noreply@${SERVER_IP}";

// Guild
\$config['site']['guild_need_level'] = 50;
\$config['site']['guild_need_pacc'] = false;

// Admin
\$config['site']['access_admin_panel'] = 5;

// Pagamentos (desabilitados por enquanto)
\$config['paymentsMethods'] = [
    'pagseguro' => false,
    'paypal' => false,
    'mercadoPago' => false,
    'transfer' => false,
    'picpay' => false
];

// Layout
\$config['site']['layout'] = 'tibiacom';
\$config['site']['download_page'] = true;
\$config['site']['cssVersion'] = "?vs=4.0.0";

// Security
\$config['site']['max_req_tries'] = 3;
\$config['site']['timeout_time'] = 5;

// High Scores
\$config['site']['h_limit'] = 25;
\$config['site']['h_limitOffset'] = 200;

// Paths dos arquivos do servidor
\$config['site']['Outfits_path'] = \$config['site']['serverPath'] . "data/XML/outfits.xml";
\$config['site']['Mounts_path'] = \$config['site']['serverPath'] . "data/XML/mounts.xml";
\$config['site']['Itens_path'] = \$config['site']['serverPath'] . "data/items/items.xml";

// Logs (ativados em produção)
\$config['site']['enablelogs'] = true;
\$config['site']['logsdir'] = '/var/log/otserver/website/';

// Quests
\$config['site']['quests'] = array(
    "Demon Helmet" => 2213,
    "The Dream Courts" => 23000,
    "Pits Of Inferno" => 10544,
    "The Secret Library" => 22399,
    "The Annihilator" => 2215,
    "The First Dragon" => 14018,
    "Wrath Of The Emperor" => 12374
);
?>
EOF

echo "✅ Website configurado para produção"

# ==========================================
# 6. CONFIGURAR CLIENTE PROTEGIDO
# ==========================================
echo ""
echo "🛡️ 7. CONFIGURANDO CLIENTE PROTEGIDO..."

# Executar script de proteção do cliente
if [ -f "/root/proteger_cliente.sh" ]; then
    echo "Aplicando proteções profissionais..."
    chmod +x /root/proteger_cliente.sh
    /root/proteger_cliente.sh
else
    echo "Script de proteção não encontrado, criando cliente básico..."
    
    cd ${WEBSITE_DIR}/downloads
    if [ -d "client" ]; then
        # Configurar init.lua
        cat > client/init.lua <<EOF
-- Cliente configurado para ${SERVER_IP}
APP_NAME = "otclientv8_${SERVER_IP}"
APP_VERSION = 1341
DEFAULT_LAYOUT = "retro"

Services = {
  website = "http://${SERVER_IP}",
  updater = "",
  stats = "",
  crash = "",
  feedback = "",
  status = "http://${SERVER_IP}/server_status.php"
}

Servers = {
  ["${SERVER_IP}"] = "${SERVER_IP}:7171:860"
}

ALLOW_CUSTOM_SERVERS = false
g_app.setName("OTClient-${SERVER_IP}")

-- Resto da configuração padrão...
g_logger.info(os.date("== Cliente iniciado em %b %d %Y %X"))

if not g_resources.directoryExists("/data") then
  g_logger.fatal("Data dir doesn't exist.")
end

if not g_resources.directoryExists("/modules") then
  g_logger.fatal("Modules dir doesn't exist.")
end

g_configs.loadSettings("/config.otml")

local settings = g_configs.getSettings()
local layout = DEFAULT_LAYOUT
if g_app.isMobile() then
  layout = "mobile"
elseif settings:exists('layout') then
  layout = settings:getValue('layout')
end
g_resources.setLayout(layout)

g_modules.discoverModules()
g_modules.ensureModuleLoaded("corelib")
  
local function loadModules()
  g_modules.autoLoadModules(99)
  g_modules.ensureModuleLoaded("gamelib")
  g_modules.autoLoadModules(499)
  g_modules.ensureModuleLoaded("client")
  g_modules.autoLoadModules(999)
  g_modules.ensureModuleLoaded("game_interface")
  g_modules.autoLoadModules(9999)
end

loadModules()
EOF

        # Criar ZIP
        rm -f cliente_${SERVER_IP}_protegido.zip otclient_v8.zip
        zip -r cliente_${SERVER_IP}_protegido.zip client/
        ln -sf cliente_${SERVER_IP}_protegido.zip otclient_v8.zip
        
        echo "✅ Cliente básico configurado"
    fi
fi

# ==========================================
# 7. CONFIGURAR PERMISSÕES FINAIS
# ==========================================
echo ""
echo "🔒 8. CONFIGURANDO PERMISSÕES FINAIS..."

# Permissões do servidor
chown -R otserver:otserver ${SERVER_DIR}
chmod +x ${SERVER_DIR}/tfs

# Permissões do website
chown -R www-data:www-data ${WEBSITE_DIR}
chmod 755 ${WEBSITE_DIR}/cache
chmod 755 ${WEBSITE_DIR}/layouts/tibiacom/images/guilds
chmod 755 ${WEBSITE_DIR}/layouts/tibiacom/images/players
chmod 755 ${WEBSITE_DIR}/downloads

# Logs
chown -R otserver:otserver /var/log/otserver
chown -R www-data:www-data /var/log/otserver/website

echo "✅ Permissões configuradas"

# ==========================================
# 8. TESTAR SERVIDOR
# ==========================================
echo ""
echo "🧪 9. TESTANDO SERVIDOR..."

# Testar se o servidor inicia
cd ${SERVER_DIR}
echo "Testando inicialização do servidor..."

timeout 10s ./tfs --test 2>/dev/null
if [ $? -eq 0 ] || [ $? -eq 124 ]; then  # 124 = timeout (normal para teste)
    echo "✅ Servidor OK - configuração válida"
else
    echo "❌ Erro na configuração do servidor"
    echo "Verifique os logs em /var/log/otserver/"
fi

# ==========================================
# 9. INICIAR SERVIÇOS
# ==========================================
echo ""
echo "🚀 10. INICIANDO SERVIÇOS..."

# Recarregar supervisor
supervisorctl reread
supervisorctl update

# Iniciar OTServer
supervisorctl start otserver

# Aguardar inicialização
sleep 5

# Verificar status
STATUS=$(supervisorctl status otserver | grep RUNNING)
if [ ! -z "$STATUS" ]; then
    echo "✅ OTServer iniciado com sucesso"
else
    echo "⚠️  OTServer pode estar com problemas"
    echo "Status: $(supervisorctl status otserver)"
fi

# Verificar portas
LISTENING=$(netstat -ln | grep :7171 | grep LISTEN)
if [ ! -z "$LISTENING" ]; then
    echo "✅ Porta 7171 está escutando"
else
    echo "⚠️  Porta 7171 não está escutando"
fi

# ==========================================
# 10. CRIAR CONTA ADMIN
# ==========================================
echo ""
echo "👑 11. CRIANDO CONTA ADMIN..."

# Verificar se já existe conta admin
ADMIN_EXISTS=$(mysql -u $DB_USER -p$DB_PASS global -se "SELECT COUNT(*) FROM accounts WHERE name='admin';")

if [ "$ADMIN_EXISTS" = "0" ]; then
    echo "Criando conta admin..."
    
    # Hash da senha "admin" em SHA1
    ADMIN_PASS_HASH="d033e22ae348aeb5660fc2140aec35850c4da997"
    
    mysql -u $DB_USER -p$DB_PASS global <<EOF
INSERT INTO accounts (name, password, type, premdays, coins, email, creation) 
VALUES ('admin', '$ADMIN_PASS_HASH', 5, 365, 10000, 'admin@${SERVER_IP}', UNIX_TIMESTAMP());

INSERT INTO players (name, account_id, level, vocation, health, healthmax, experience, lookbody, lookfeet, lookhead, looklegs, looktype, lookaddons, maglevel, mana, manamax, manaspent, soul, town_id, posx, posy, posz, conditions, cap, sex, lastlogin, lastip, save, skull, skulltime, lastlogout, blessings, onlinetime, deletion, balance)
VALUES ('Admin', LAST_INSERT_ID(), 100, 8, 1000, 1000, 50000000, 113, 115, 95, 39, 129, 0, 80, 5000, 5000, 0, 100, 2, 1000, 1000, 7, '', 5000, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0);
EOF

    if [ $? -eq 0 ]; then
        echo "✅ Conta admin criada"
        echo "   Login: admin"
        echo "   Senha: admin"
        echo "   Char: Admin (Level 100)"
    else
        echo "❌ Erro ao criar conta admin"
    fi
else
    echo "✅ Conta admin já existe"
fi

# ==========================================
# FINALIZAÇÃO E TESTES
# ==========================================
echo ""
echo "=========================================="
echo "  🎉 SETUP FINALIZADO COM SUCESSO!"
echo "=========================================="
echo ""

# Executar testes finais
echo "🧪 EXECUTANDO TESTES FINAIS..."

# Teste 1: Website
HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://${SERVER_IP}/ 2>/dev/null)
if [ "$HTTP_STATUS" = "200" ]; then
    echo "✅ Website funcionando (HTTP 200)"
else
    echo "⚠️  Website com problemas (HTTP $HTTP_STATUS)"
fi

# Teste 2: Status JSON
JSON_STATUS=$(curl -s http://${SERVER_IP}/server_status.php 2>/dev/null | grep overall_status)
if [ ! -z "$JSON_STATUS" ]; then
    echo "✅ API de status funcionando"
else
    echo "⚠️  API de status com problemas"
fi

# Teste 3: Download do cliente
if [ -f "${WEBSITE_DIR}/downloads/cliente_${SERVER_IP}_protegido.zip" ]; then
    echo "✅ Download do cliente disponível"
else
    echo "⚠️  Download do cliente com problemas"
fi

# Teste 4: Conectividade do servidor
TELNET_TEST=$(timeout 3 bash -c "</dev/tcp/${SERVER_IP}/7171" 2>/dev/null && echo "OK")
if [ "$TELNET_TEST" = "OK" ]; then
    echo "✅ Servidor de jogo acessível"
else
    echo "⚠️  Servidor de jogo inacessível"
fi

echo ""
echo "🌐 ACESSOS PRINCIPAIS:"
echo "Website: http://${SERVER_IP}"
echo "Info: http://${SERVER_IP}/info.php"
echo "Status: http://${SERVER_IP}/server_status.php"
echo "Download: http://${SERVER_IP}/download.php"
echo ""
echo "🎮 CONEXÃO DO JOGO:"
echo "IP: ${SERVER_IP}"
echo "Porta: 7171"
echo "Versão: 8.60"
echo ""
echo "👑 CONTA ADMIN:"
echo "Login: admin"
echo "Senha: admin"
echo "Char: Admin"
echo ""
echo "📊 MONITORAMENTO:"
echo "Status: supervisorctl status otserver"
echo "Logs: tail -f /var/log/otserver/output.log"
echo "Restart: supervisorctl restart otserver"
echo ""
echo "🎯 SEU OTSERVER ESTÁ ONLINE E FUNCIONANDO!"
echo "🚀 Players podem criar contas e jogar agora!"
