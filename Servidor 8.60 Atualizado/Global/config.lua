-- Combat settings
-- NOTE: valid values for worldType are: "pvp", "no-pvp" and "pvp-enforced"
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

-- Connection Config
-- NOTE: maxPlayers set to 0 means no limit
ip = "181.215.45.238"
bindOnlyGlobalAddress = true
loginProtocolPort = 7171
gameProtocolPort = 7172
statusProtocolPort = 7171
maxPlayers = 2000
motd = "Bem-vindo ao OTServer!"
onePlayerOnlinePerAccount = true
allowClones = false
serverName = "OTServer Global"
statusTimeout = 5 * 1000
replaceKickOnLogin = true
maxPacketsPerSecond = 300

-- Version Manual
clientVersionMin = 860
clientVersionMax = 861
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

-- Server Save
serverSaveNotifyMessage = true
serverSaveNotifyDuration = 5
serverSaveCleanMap = false
serverSaveClose = false
serverSaveShutdown = false

-- Houses
housePriceEachSQM = 500
houseRentPeriod = "monthly"

-- Item Usage
timeBetweenActions = 1200
timeBetweenExActions = 200
timeBetweenCustomActions = 1000

-- Map
-- NOTE: set mapName WITHOUT .otbm at the end
mapName = "realmap"
mapAuthor = "OTServer"

-- Market
marketOfferDuration = 30 * 24 * 60 * 60
premiumToCreateMarketOffer = true
checkExpiredMarketOffersEachMinutes = 60
maxMarketOffersAtATimePerPlayer = 100

-- MySQL
mysqlHost = "localhost"
mysqlUser = "otserver"
mysqlPass = "Civic123@"
mysqlDatabase = "otserver"
mysqlPort = 3306
mysqlSock = ""
passwordType = "sha1"

-- Misc.
allowChangeOutfit = true
freePremium = false
kickIdlePlayerAfterMinutes = 15
idleWarningTime = 10 * 60 * 1000
idleKickTime = 15 * 60 * 1000
maxMessageBuffer = 4
emoteSpells = true
classicEquipmentSlots = true
allowWalkthrough = false
coinPacketSize = 1
coinImagesURL = "https://181.215.45.238/images/store/"
classicAttackSpeed = false
allowBlockSpawn = false

-- Rates
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

-- Startup
defaultPriority = "high"
startupDatabaseOptimization = true

-- Status server information
ownerName = "OTServer Admin"
ownerEmail = "admin@otserver.com"
url = "https://181.215.45.238"
location = "Brazil"