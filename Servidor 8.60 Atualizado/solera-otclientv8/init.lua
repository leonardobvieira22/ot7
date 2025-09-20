-- CONFIG
APP_NAME = "otclientv8"  -- important, change it, it's name for config dir and files in appdata
APP_VERSION = 1341       -- client version for updater and login to identify outdated client
DEFAULT_LAYOUT = "retro" -- on android it's forced to "mobile", check code bellow

-- If you don't use updater or other service, set it to updater = ""
Services = {
  website = "https://181.215.45.238", -- website url
  updater = "https://181.215.45.238/api/updater.php",
  stats = "https://181.215.45.238/api/stats.php",
  crash = "https://181.215.45.238/api/crash.php",
  feedback = "https://181.215.45.238/api/feedback.php",
  status = "https://181.215.45.238/api/status.php"
}

-- Servers accept http login url, websocket login url or ip:port:version
Servers = {
  OTServer = "181.215.45.238:7171:860"
}

ALLOW_CUSTOM_SERVERS = false -- disable custom servers for security
USE_NEW_ENERGAME = true -- uses entergamev2 based on websockets for better security

-- Basic anti-tampering
local function verifyClientIntegrity()
  local files = {
    "/data/things/860/Tibia.spr",
    "/data/things/860/Tibia.dat",
    "/modules/game_interface/gameinterface.lua",
    "/modules/client_entergame/entergame.lua"
  }
  
  for _, file in ipairs(files) do
    if not g_resources.fileExists(file) then
      g_logger.fatal("Client file integrity check failed. Please redownload the client.")
      g_app.exit()
      return false
    end
  end
  return true
end

g_app.setName("OTServer")

-- print first terminal message
g_logger.info(os.date("== application started at %b %d %Y %X"))
g_logger.info(g_app.getName() .. ' ' .. g_app.getVersion() .. ' rev ' .. g_app.getBuildRevision() .. ' (' .. g_app.getBuildCommit() .. ') made by ' .. g_app.getAuthor() .. ' built on ' .. g_app.getBuildDate() .. ' for arch ' .. g_app.getBuildArch())

if not verifyClientIntegrity() then
  return
end

if not g_resources.directoryExists("/data") then
  g_logger.fatal("Data dir doesn't exist.")
end

if not g_resources.directoryExists("/modules") then
  g_logger.fatal("Modules dir doesn't exist.")
end

-- settings
g_configs.loadSettings("/config.otml")

-- set layout
local settings = g_configs.getSettings()
local layout = DEFAULT_LAYOUT
if g_app.isMobile() then
  layout = "mobile"
elseif settings:exists('layout') then
  layout = settings:getValue('layout')
end
g_resources.setLayout(layout)

-- load mods
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

  -- mods 1000-9999
  g_modules.autoLoadModules(9999)
end

-- report crash
if type(Services.crash) == 'string' and Services.crash:len() > 4 and g_modules.getModule("crash_reporter") then
  g_modules.ensureModuleLoaded("crash_reporter")
end

loadModules()