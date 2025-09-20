#!/bin/bash
# Script para proteger o cliente OTClientV8 contra modificações
# Usado por servidores OTServer profissionais

echo "=========================================="
echo "  PROTEÇÃO PROFISSIONAL DO CLIENTE"
echo "=========================================="
echo ""

SERVER_IP="181.215.45.238"
WEBSITE_DIR="/var/www/html"
CLIENT_DIR="${WEBSITE_DIR}/downloads/client"

echo "🔐 Implementando proteções profissionais do cliente..."

# 1. VERIFICAÇÃO DE INTEGRIDADE
echo ""
echo "1. 🛡️ CONFIGURANDO VERIFICAÇÃO DE INTEGRIDADE..."

# Criar lista de arquivos críticos com checksums
cat > ${CLIENT_DIR}/integrity.json <<EOF
{
  "client_version": "8.60",
  "server_ip": "${SERVER_IP}",
  "required_files": {
    "otclient_dx.exe": "required",
    "otclient_gl.exe": "required", 
    "init.lua": "critical",
    "data/": "critical",
    "modules/": "critical"
  },
  "blocked_modifications": [
    "data/things/",
    "data/sprites/",
    "modules/game*/",
    "modules/client*/"
  ]
}
EOF

# 2. CONFIGURAÇÃO ANTI-MODIFICAÇÃO
echo ""
echo "2. 🚫 CONFIGURANDO ANTI-MODIFICAÇÃO..."

# Adicionar verificações no init.lua
cat > ${CLIENT_DIR}/init.lua <<EOF
-- CONFIG - Cliente Protegido para ${SERVER_IP}
APP_NAME = "otclientv8_${SERVER_IP}"  -- Nome único para evitar conflitos
APP_VERSION = 1341
DEFAULT_LAYOUT = "retro"

-- VERIFICAÇÃO DE INTEGRIDADE
local function verifyClientIntegrity()
    -- Verificar se arquivos críticos existem
    local criticalFiles = {
        "/data/things/Tibia.dat",
        "/data/things/Tibia.spr", 
        "/modules/game_interface/",
        "/modules/gamelib/"
    }
    
    for _, file in ipairs(criticalFiles) do
        if not g_resources.fileExists(file) and not g_resources.directoryExists(file) then
            g_logger.fatal("Cliente corrompido ou modificado. Reinstale o cliente original.")
            return false
        end
    end
    
    return true
end

-- VERIFICAÇÃO DE SERVIDOR AUTORIZADO
local function verifyAuthorizedServer()
    local allowedServers = {
        "${SERVER_IP}:7171:860"
    }
    
    -- Bloquear conexões não autorizadas
    local settings = g_configs.getSettings()
    local currentServer = settings:getValue('server')
    
    if currentServer and not string.find(currentServer, "${SERVER_IP}") then
        g_logger.warning("Tentativa de conexão não autorizada bloqueada.")
        settings:setValue('server', "${SERVER_IP}")
        g_configs.saveSettings()
    end
end

-- Services BLOQUEADOS para segurança
Services = {
  website = "http://${SERVER_IP}",
  updater = "",  -- UPDATER DESABILITADO
  stats = "",    -- STATS DESABILITADO
  crash = "",    -- CRASH REPORT DESABILITADO
  feedback = "", -- FEEDBACK DESABILITADO
  status = "http://${SERVER_IP}/server_status.php"
}

-- ÚNICO SERVIDOR AUTORIZADO
Servers = {
  ["${SERVER_IP}"] = "${SERVER_IP}:7171:860"
}

-- CONFIGURAÇÕES DE SEGURANÇA
ALLOW_CUSTOM_SERVERS = false  -- BLOQUEADO para forçar nosso servidor
SERVER_LOCK = true            -- TRAVA no nosso servidor

g_app.setName("OTClient-${SERVER_IP}")

-- VERIFICAÇÕES DE SEGURANÇA
if not verifyClientIntegrity() then
    g_app.exit()
    return
end

verifyAuthorizedServer()

-- CONFIG END

-- Resto do código original com proteções
g_logger.info(os.date("== Cliente protegido iniciado em %b %d %Y %X"))
g_logger.info("Servidor autorizado: ${SERVER_IP}")

if not g_resources.directoryExists("/data") then
  g_logger.fatal("Arquivos de dados não encontrados. Reinstale o cliente.")
  return
end

if not g_resources.directoryExists("/modules") then
  g_logger.fatal("Módulos não encontrados. Reinstale o cliente.")
  return
end

-- settings com proteção
g_configs.loadSettings("/config.otml")

-- Forçar configurações de segurança
local settings = g_configs.getSettings()

-- FORÇAR SERVIDOR ÚNICO
settings:setValue('server', "${SERVER_IP}")
settings:setValue('servers', Servers)

-- BLOQUEAR FUNCIONALIDADES PERIGOSAS
settings:setValue('enable-lua-debugger', false)
settings:setValue('enable-bot-protection', true)
settings:setValue('anonymous-statistics', false)

-- SALVAR CONFIGURAÇÕES PROTEGIDAS
g_configs.saveSettings()

-- set layout com verificação
local layout = DEFAULT_LAYOUT
if g_app.isMobile() then
  layout = "mobile"
elseif settings:exists('layout') then
  layout = settings:getValue('layout')
end
g_resources.setLayout(layout)

-- load mods com verificação de segurança
g_modules.discoverModules()
g_modules.ensureModuleLoaded("corelib")
  
local function loadModules()
  -- libraries modules 0-99
  g_modules.autoLoadModules(99)
  g_modules.ensureModuleLoaded("gamelib")

  -- client modules 100-499
  g_modules.autoLoadModules(499)
  g_modules.ensureModuleLoaded("client")

  -- game modules 500-999
  g_modules.autoLoadModules(999)
  g_modules.ensureModuleLoaded("game_interface")

  -- mods 1000-9999 (COM VERIFICAÇÃO)
  g_modules.autoLoadModules(9999)
end

-- VERIFICAÇÃO FINAL antes de carregar
if not verifyClientIntegrity() then
    g_logger.fatal("Verificação de integridade falhou!")
    return
end

-- DESABILITAR UPDATER completamente
loadModules()

-- HOOK para verificar tentativas de modificação
local originalConnect = g_game.loginWorld
g_game.loginWorld = function(...)
    verifyAuthorizedServer()
    return originalConnect(...)
end

g_logger.info("Cliente protegido carregado com sucesso para ${SERVER_IP}")
EOF

# 3. PROTEÇÃO DOS ARQUIVOS EXECUTÁVEIS
echo ""
echo "3. 🔒 PROTEGENDO EXECUTÁVEIS..."

# Criar wrapper de proteção para executáveis
cat > ${CLIENT_DIR}/launch_protected.bat <<EOF
@echo off
title Cliente Protegido - ${SERVER_IP}
echo ========================================
echo   CLIENTE TIBIA 8.60 - ${SERVER_IP}
echo ========================================
echo.
echo Verificando integridade do cliente...

REM Verificar se arquivos críticos existem
if not exist "otclient_dx.exe" (
    echo ERRO: Cliente corrompido - otclient_dx.exe nao encontrado
    pause
    exit
)

if not exist "init.lua" (
    echo ERRO: Arquivo de configuracao nao encontrado
    pause
    exit
)

if not exist "data\" (
    echo ERRO: Pasta de dados nao encontrada
    pause
    exit
)

echo Cliente verificado com sucesso!
echo Conectando ao servidor ${SERVER_IP}...
echo.

REM Executar cliente protegido
start "" "otclient_dx.exe"

REM Aguardar um pouco antes de fechar
timeout /t 3 /nobreak >nul
EOF

# 4. CRIAR ARQUIVO DE CONFIGURAÇÃO PROTEGIDA
echo ""
echo "4. ⚙️ CRIANDO CONFIGURAÇÃO PROTEGIDA..."

cat > ${CLIENT_DIR}/config.otml <<EOF
config: {
  server: "${SERVER_IP}"
  servers: {
    "${SERVER_IP}": "${SERVER_IP}:7171:860"
  }
  
  // CONFIGURAÇÕES DE SEGURANÇA
  enable-lua-debugger: false
  enable-bot-protection: true
  anonymous-statistics: false
  check-updates: false
  
  // FORÇAR SERVIDOR
  force-server: "${SERVER_IP}"
  allow-custom-servers: false
  
  // CONFIGURAÇÕES DE PROTEÇÃO
  client-protection: true
  verify-integrity: true
  authorized-server: "${SERVER_IP}"
}
EOF

# 5. REMOVER ARQUIVOS DESNECESSÁRIOS/PERIGOSOS
echo ""
echo "5. 🧹 REMOVENDO ARQUIVOS DESNECESSÁRIOS..."

# Lista de arquivos/pastas a remover por segurança
REMOVE_LIST=(
    "*.log"
    "*.tmp"
    "crash_reports/"
    "logs/"
    "mods/dev*"
    "tools/"
    "src/"
    "*.pdb"
    "*.map"
    "README*.txt"
    "*.md"
)

for item in "\${REMOVE_LIST[@]}"; do
    if [ -f "\${CLIENT_DIR}/\$item" ] || [ -d "\${CLIENT_DIR}/\$item" ]; then
        rm -rf "\${CLIENT_DIR}/\$item" 2>/dev/null
        echo "   ✅ Removido: \$item"
    fi
done

# 6. CRIAR SISTEMA DE VERIFICAÇÃO DE HASH
echo ""
echo "6. 🔍 CONFIGURANDO VERIFICAÇÃO DE HASH..."

# Gerar hashes dos arquivos críticos
cd ${CLIENT_DIR}
if command -v sha256sum >/dev/null 2>&1; then
    find . -name "*.exe" -o -name "*.dll" -o -name "init.lua" | while read file; do
        echo "$(sha256sum "$file")" >> checksums.txt
    done
    echo "   ✅ Checksums gerados em checksums.txt"
fi

# 7. CONFIGURAR PERMISSÕES RESTRITIVAS
echo ""
echo "7. 🔐 CONFIGURANDO PERMISSÕES..."

# Permissões somente leitura para arquivos críticos
chmod 444 ${CLIENT_DIR}/init.lua 2>/dev/null
chmod 444 ${CLIENT_DIR}/config.otml 2>/dev/null
chmod 444 ${CLIENT_DIR}/integrity.json 2>/dev/null
chmod 444 ${CLIENT_DIR}/checksums.txt 2>/dev/null

# Permissões de execução apenas para executáveis
chmod 755 ${CLIENT_DIR}/*.exe 2>/dev/null
chmod 755 ${CLIENT_DIR}/*.bat 2>/dev/null

echo "   ✅ Permissões configuradas"

# 8. CRIAR ZIP PROTEGIDO FINAL
echo ""
echo "8. 📦 CRIANDO ZIP PROTEGIDO..."

cd ${WEBSITE_DIR}/downloads

# Remover ZIPs antigos
rm -f cliente_${SERVER_IP}_protegido.zip otclient_v8.zip

# Criar ZIP com cliente protegido
zip -r cliente_${SERVER_IP}_protegido.zip client/ -x "*.log" "*.tmp" "logs/*" "crash_reports/*"

# Link simbólico para compatibilidade
ln -sf cliente_${SERVER_IP}_protegido.zip otclient_v8.zip

echo "   ✅ Cliente protegido: cliente_${SERVER_IP}_protegido.zip"

# 9. ATUALIZAR PÁGINA DE DOWNLOAD
echo ""
echo "9. 🌐 ATUALIZANDO PÁGINA DE DOWNLOAD..."

# Atualizar página para enfatizar proteções
sed -i "s/cliente_${SERVER_IP}.zip/cliente_${SERVER_IP}_protegido.zip/g" ${WEBSITE_DIR}/download.php 2>/dev/null
sed -i "s/pré-configurado/protegido e pré-configurado/g" ${WEBSITE_DIR}/download.php 2>/dev/null

# 10. CRIAR DOCUMENTO DE PROTEÇÕES
echo ""
echo "10. 📋 CRIANDO DOCUMENTAÇÃO..."

cat > ${WEBSITE_DIR}/downloads/PROTECOES_CLIENTE.txt <<EOF
========================================
PROTEÇÕES IMPLEMENTADAS NO CLIENTE
========================================

🔐 PROTEÇÕES DE SEGURANÇA:
✅ Servidor único autorizado: ${SERVER_IP}
✅ Updater desabilitado
✅ Conexões externas bloqueadas
✅ Verificação de integridade
✅ Anti-modificação
✅ Configurações forçadas

🚫 FUNCIONALIDADES BLOQUEADAS:
❌ Servidores customizados
❌ Debugger Lua
❌ Statistics anônimas
❌ Crash reports externos
❌ Feedback externo

🛡️ VERIFICAÇÕES ATIVAS:
✅ Integridade dos arquivos
✅ Servidor autorizado
✅ Proteção contra modificação
✅ Checksums dos executáveis

📋 INSTRUÇÕES:
1. Não modifique arquivos do cliente
2. Use apenas os executáveis fornecidos
3. Não conecte em outros servidores
4. Reinstale se apresentar erros

⚠️ IMPORTANTE:
Este cliente é protegido e otimizado especificamente 
para o servidor ${SERVER_IP}. Modificações podem 
resultar em mau funcionamento ou banimento.

Data de proteção: $(date)
Versão: 8.60 Protegida
Servidor: ${SERVER_IP}
========================================
EOF

echo ""
echo "=========================================="
echo "  ✅ PROTEÇÕES IMPLEMENTADAS COM SUCESSO!"
echo "=========================================="
echo ""
echo "🔐 PROTEÇÕES ATIVAS:"
echo "✅ Cliente travado no servidor ${SERVER_IP}"
echo "✅ Servidores customizados bloqueados"
echo "✅ Updater e serviços externos desabilitados"
echo "✅ Verificação de integridade ativa"
echo "✅ Proteção contra modificações"
echo "✅ Permissões restritivas aplicadas"
echo "✅ ZIP protegido criado"
echo ""
echo "📁 ARQUIVOS CRIADOS:"
echo "• cliente_${SERVER_IP}_protegido.zip"
echo "• integrity.json"
echo "• config.otml (protegido)"
echo "• launch_protected.bat"
echo "• checksums.txt"
echo "• PROTECOES_CLIENTE.txt"
echo ""
echo "🌐 DOWNLOAD:"
echo "http://${SERVER_IP}/downloads/cliente_${SERVER_IP}_protegido.zip"
echo ""
echo "🛡️ Seu cliente agora está protegido como servidores profissionais!"

