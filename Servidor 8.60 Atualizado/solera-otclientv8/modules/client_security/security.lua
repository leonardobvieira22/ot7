-- Client Security Module
local security = {}

-- Checksum dos arquivos críticos
local CRITICAL_FILES = {
    ["/data/things/860/Tibia.spr"] = true,
    ["/data/things/860/Tibia.dat"] = true,
    ["/modules/game_interface/gameinterface.lua"] = true,
    ["/modules/client_entergame/entergame.lua"] = true
}

-- Lista de processos suspeitos
local SUSPICIOUS_PROCESSES = {
    "cheatengine",
    "ollydbg",
    "ida",
    "wireshark",
    "fiddler"
}

-- Verifica integridade dos arquivos
function security.verifyFileIntegrity()
    for file, _ in pairs(CRITICAL_FILES) do
        if not g_resources.fileExists(file) then
            return false, "Arquivo crítico ausente: " .. file
        end
        
        -- Adiciona verificação de tamanho do arquivo
        local fileSize = g_resources.fileSize(file)
        if fileSize < 1000 then -- Arquivos muito pequenos são suspeitos
            return false, "Arquivo corrompido: " .. file
        end
    end
    return true
end

-- Anti-debugging e anti-tampering
function security.antiDebug()
    if g_app.isRunningInDebugger() then
        return false, "Debugger detectado"
    end
    return true
end

-- Verifica processos suspeitos
function security.checkSuspiciousProcesses()
    if g_platform.isWindows() then
        local handle = io.popen("tasklist")
        if handle then
            local result = handle:read("*a")
            handle:close()
            
            for _, process in ipairs(SUSPICIOUS_PROCESSES) do
                if result:lower():find(process) then
                    return false, "Processo suspeito detectado: " .. process
                end
            end
        end
    end
    return true
end

-- Verifica modificações de memória
function security.checkMemoryModifications()
    -- Implementa verificações básicas de memória
    local baseAddr = g_app.getBaseAddress()
    if baseAddr ~= g_app.getExpectedBaseAddress() then
        return false, "Modificação de memória detectada"
    end
    return true
end

-- Sistema de heartbeat para verificação contínua
function security.startHeartbeat()
    local function heartbeat()
        local ok, msg = security.verifyFileIntegrity()
        if not ok then
            g_game.disconnect()
            g_logger.fatal("Violação de segurança: " .. msg)
            return
        end
        
        ok, msg = security.antiDebug()
        if not ok then
            g_game.disconnect()
            g_logger.fatal("Violação de segurança: " .. msg)
            return
        end
        
        ok, msg = security.checkSuspiciousProcesses()
        if not ok then
            g_game.disconnect()
            g_logger.fatal("Violação de segurança: " .. msg)
            return
        end
        
        ok, msg = security.checkMemoryModifications()
        if not ok then
            g_game.disconnect()
            g_logger.fatal("Violação de segurança: " .. msg)
            return
        end
    end
    
    -- Executa verificações a cada 30 segundos
    g_clock.scheduleEvent(heartbeat, 30000, true)
end

-- Inicializa o módulo de segurança
function security.init()
    connect(g_game, { onGameStart = security.startHeartbeat })
    
    -- Verifica integridade inicial
    local ok, msg = security.verifyFileIntegrity()
    if not ok then
        g_logger.fatal("Falha na verificação inicial: " .. msg)
        g_app.exit()
        return false
    end
    
    return true
end

-- Termina o módulo de segurança
function security.terminate()
    disconnect(g_game, { onGameStart = security.startHeartbeat })
end

return security
