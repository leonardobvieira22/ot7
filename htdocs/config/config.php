<?PHP
if (!function_exists('is_https')) {
	function is_https()
	{
		if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
			return true;
		} elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
			return true;
		} elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
			return true;
		}

		return false;
	}
}

$is_https = is_https();

if ($is_https) {
	$base_url = "https://" . $_SERVER['HTTP_HOST'];
	$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
} else {
	$base_url = "http://" . $_SERVER['HTTP_HOST'];
	$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
}

/** SERVER URLS */
/** @var array $config */
$config['base_url'] = $base_url;
$config['site']['base_url'] = $base_url;
$config['site']['realurl'] = "http://181.215.45.238/"; // Put the real url for your website without www DO NOT FORGET FROM / AT THE END
$config['site']['realurlwww'] = "http://181.215.45.238/"; // Put the real url for your website with www IF IT IS A SUBDOMINUM PUT THE MSM URL OF THE REAL URL
$config['site']['testurl'] = "http://181.215.45.238/"; // Put the url you use to test your site (LOCALHOST)
/** END SERVER URLS */


/** SERVER PATHS */
if ($config['base_url'] == $config['site']['realurl'] || $config['base_url'] == $config['site']['realurlwww']) {
	$config['site']['serverPath'] = "/opt/otserver/ot7/Global/"; // SERVER PATH IN PRODUCTION
} else {
	$config['site']['serverPath'] = "/opt/otserver/ot7/Global/"; // SERVERPATH LOCALHOST
}
/** END SERVER PATHS */


/** ENABLE SHOP */
$config['site']['shopEnabled'] = true;


/** ABERTURA PARA SERVIDOR */
$config['site']['start'] = 'Sep 21, 2020 18:00:00';


/** GOOGLE RECAPTCHA VALUES */
$config['site']['gRecaptchaSecret'] = "";
$config['site']['gRecaptchaSiteKey'] = "";

/** WIDGETS CONFIG */
$config['site']['widget_rank'] = true;
$config['site']['widget_supportButton'] = true;
$config['site']['widget_buycharButton'] = false;
$config['site']['widget_PremiumBox'] = true;
$config['site']['widget_Serverinfobox'] = true;
$config['site']['widget_Serverinfoboxfloat'] = true;
$config['site']['widget_NetworksBox'] = false;
$config['site']['widget_CurrentPollBox'] = false;
$config['site']['widget_CastleWarBox'] = false;

/** WIDGETS 'widget_rank' TOP LVL CONFIGS */
$config['site']['top_lvl_qtd'] = 5; // 1 -- 5
$config['site']['top_lvl_goku_isActive'] = true; // true - false
$config['site']['top_lvl_out_anim'] = true; // true - false

# Social Networks
$config['social']['status'] = false;
$config['social']['facebook'] = "https://www.instagram.com/epicwarserver/"; // Link to your facebook page
$config['social']['fbapiversion'] = "";
$config['social']['fbapilink'] = "";
$config['social']['fbpageid'] = "";
$config['social']['accessToken'] = "";
$config['social']['twitter'] = "";
$config['social']['twitter'] = "";
$config['social']['twittercreator'] = "";
$config['social']['fbappid'] = "";

# Using Ajax Field Validation, this is important if you want to use ajax check in your create account.
$config['site']['sqlHost'] = "localhost";
$config['site']['sqlUser'] = "otserver";
$config['site']['sqlPass'] = "Civic123@";
$config['site']['sqlBD'] = "otserver";

# Characters animatedOutfits php
$config['site']['animatedOutfits_url'] = 'http://127.0.0.1:8090/AnimatedOutfits/animoutfit.php?';
$config['site']['outfit_images_url'] = '/outfit.php?';
$config['site']['icons_images_url'] = '/images/icons_damage/';
$config['site']['item_images_extension'] = '.png';
$config['site']['flag_images_url'] = '/images/flags/';
$config['site']['flag_images_extension'] = '.png';


# Config Shop
$outfits_list = array();
$loyalty_title = array(
	50 => 'Scout',
	100 => 'Sentinel',
	200 => 'Steward',
	400 => 'Warden',
	1000 => 'Squire',
	2000 => 'Warrior',
	3000 => 'Keeper',
	4000 => 'Guardian',
	5000 => 'Sage
'
);
$config['shop']['newitemdays'] = 12;

# Character Former name, time in days to show the former names
$config['site']['formerNames'] = 10;
$config['site']['formerNames_amount'] = 10;

# PAGE: characters.php
$config['site']['quests'] = array(
	"Demon Helmet" => 2213,
	"The Dream Courts" => 23000,
	"Pits Of Inferno" => 10544,
	"The Secret Library" => 22399,
	"The Annihilator" => 2215,
	"The First Dragon" => 14018,
	"Wrath Of The Emperor" => 12374
);

# PAGE: whoisonline.php
$config['site']['private-servlist.com_server_id'] = 0;

# Account Maker Config
$config['site']['encryptionType'] = 'sha1';
$config['site']['useServerConfigCache'] = false;
$towns_list = array(
    1 => 'Venore', 
    2 => 'Thais', 
    3 => 'Kazordoon', 
    4 => 'Carlin', 
    5 => 'Ab\'Dendriel', 
    6 => 'Rookgaard',        
    7 => 'Liberty Bay', 
    8 => 'Port Hope', 
    9 => 'Ankrahmun', 
    10 => 'Darashia', 
    11 => 'Edron', 
    12 => 'Svargrond', 
    13 => 'Yalahar', 
    14 => 'Farmine', 
    28 => 'Gray Beach',    
    33 => 'Rathleton',
    29 => 'Roshamuul',
	30 => 'Rookgaard Tutorial Island',
	34 => 'Krailos',
	51 => 'Dawnport',
	65 => 'Issavi',
);

$vocations_list = [
	1 => "Sorcerer",
	2 => "Druid",
	3 => "Paladin",
	4 => "Knight",
	5 => "Master Sorcerer",
	6 => "Elder Druid",
	7 => "Royal Paladin",
	8 => "Elite Knight",
	10 => "ALL"
];
$highscores_list = [
	//		1 => "Achievements",
	2 => "Axe Fighting",
	3 => "Club Fighting",
	4 => "Distance Fighting",
	5 => "Experience Points",
	6 => "Fishing",
	7 => "First Fighting",
	//		8 => "Loyalty Points",
	9 => "Magic Level",
	10 => "Shielding",
	11 => "Sword Fighting"
];
# Create Account Options
$config['site']['one_email'] = true;
$config['site']['create_account_verify_mail'] = false;
$config['site']['verify_code'] = true;
$config['site']['email_days_to_change'] = 3;
$config['site']['newaccount_premdays'] = 0;
$config['site']['send_register_email'] = true;
$config['site']['flash_client_enabled'] = false;

# Create Character Options
$config['site']['newchar_vocations'] = array(1 => 'Sorcerer Sample', 2 => 'Druid Sample', 3 => 'Paladin Sample', 4 => 'Knight Sample');
$config['site']['newchar_towns'] = array(2);
$config['site']['max_players_per_account'] = 7;

# Emails Config
$config['site']['send_emails'] = true;
$config['site']['mail_address'] = "####@gmail.com";
$config['site']['mail_senderName'] = "";
$config['site']['smtp_enabled'] = true;
$config['site']['smtp_host'] = "ssl://smtp.gmail.com";
$config['site']['smtp_port'] = 465;
$config['site']['smtp_auth'] = true;
$config['site']['smtp_user'] = "####@gmail.com";
$config['site']['smtp_pass'] = "####";
$config['site']['smtp_secure'] = true;

# PAGE: accountmanagement.php
$config['site']['send_mail_when_change_password'] = true;
$config['site']['send_mail_when_generate_reckey'] = true;
$config['site']['email_time_change'] = 7;
$config['site']['daystodelete'] = 7;

# PAGE: guilds.php
$config['site']['guild_need_level'] = 1;
$config['site']['guild_need_pacc'] = false;
$config['site']['guild_image_size_kb'] = 50;
$config['site']['guild_description_chars_limit'] = 2000;
$config['site']['guild_description_lines_limit'] = 6;
$config['site']['guild_motd_chars_limit'] = 250;

# PAGE: adminpanel.php
$config['site']['access_admin_panel'] = 5;
$config['site']['ticket_reply_access'] = 5;

# PAGE: latestnews.php
$config['site']['news_limit'] = 6;

# PAGE: killstatistics.php
$config['site']['last_deaths_limit'] = 40;

# PAGE: team.php
$config['site']['groups_support'] = array(2, 3, 4, 5, 6);

# PAGE: highscores.php INACTIVE
$config['site']['groups_hidden'] = array(3, 4, 5, 6);
$config['site']['accounts_hidden'] = array(1);

# PAGE: lostaccount.php
$config['site']['email_lai_sec_interval'] = 180;

/** LANDPAGE CONFIG */
$config['site']['landpage_isactive'] = true;
$config['site']['landpage_title'] = "";
$config['site']['landpage_timeout'] = 60 * 10080; // Time in seconds 1 * 60 = 1 minute
$config['site']['landpage_description'] = ""; // Type text here to appear on the landpage
$config['site']['landpage_max_notices'] = 3; // Maximum number of news items displayed on the landpage.
$config['site']['landpage_youtube'] = ""; // youtube video id


/** OUIBOUNCE - DISPLAY A MODAL WHEN REMOVING THE MOUSE FROM THE SCREEN */
$config['site']['ouibounce_isActive'] = true;


/** HIGH SCORES CONFIG */
$config['site']['h_limit'] = 25; // Limit players per page
$config['site']['h_limitOffset'] = 200; // Limits the maximum number of players in the rank
$config['site']['h_group_acc_show'] = "1,2,3,6"; // Select the class groups that will appear in the rank

/** INFO_BAR TIBIA NEW LIKE */
$config['site']['info_bar_active'] = true;
$config['site']['info_bar_cast'] = true;
$config['site']['info_bar_twitch'] = true;
$config['site']['info_bar_youtube'] = true;
$config['site']['info_bar_forum'] = true;
$config['site']['info_bar_online'] = true;

/**
	* DONATE CONFIG LIKE PAYABLE OLD_CONFIG
	* (50 * 10) = R $ 5.00 // 50 = TIBIA COINS COUNT 1 to 1 ratio
*/

$config['donate']['offers'] = [
	/** id =>[PRICE=>COINS]*/
	0 => [(2500) => 60],
	1 => [(4500) => 150],
	2 => [(8000) => 330],
	3 => [(11500) => 440], //10% discount
	4 => [(14500) => 560], //10% discount
	5 => [(22500) => 920], //20% discount
	// 9 => [24500 => 5000]
];


$proporcao_preco = (array_keys($config['donate']['offers'][intval(0)])[0] / 100);
$proporcao_qnt = array_values($config['donate']['offers'][intval(0)])[0];

$config['donate']['proporcao'] = $proporcao_preco / $proporcao_qnt;
$config['donate']['show_proporcao'] = false;

/**
 * configure your active payment method with this
 * true = ACTIVE
 * false = INACTIVE
 */
$config['paymentsMethods'] = [
	'pagseguro' => true,
	'paypal' => false,
	'mercadoPago' => true,
	'transfer' => false,
	'picpay' => false
];

/** PICPAY CONFIGS */
$config['picpay']['user'] = ''; //User no having @

/** Bank transfer data */
$config['banktransfer'] = [
	//		EXAMPLE TO ADD MORE
	//		0 => [
	//				'bank' => '',
	//				'agency' => '',
	//				'account' => '',
	//				'name' => '',
	//				'operation' => '',
	//				'email' => '',
	//				'acctype' => ''
	//		],
	//		1 => [
	//				'bank' => '',
	//				'agency' => '',
	//				'account' => '',
	//				'name' => '',
	//				'operation' => '',
	//				'email' => '',
	//				'acctype' => ''
	//		]
];

/** PAGSEGURO FIXED */
$config['pagseguro']['testing'] = false;
$config['pagseguro']['lightbox'] = true;
$config['pagseguro']['tokentest'] = "";

/** PAGSEGURO CONFIGS 1997fa69-7bb2-419e-ad3a-ab38868a5c12a86421734645a6815c43d763d631ba895d93-3ec3-4075-bd87-c821c24365af*/
$config['pagseguro']['email'] = //*"alissonrenna@gmail.com";
$config['pagseguro']['token'] = //*"da834849-c3f0-4463-a515-b303ecfe59770d41409e438f813edc84b995fb68c0e13f09-c630-428a-b8f6-d17b9a822712";
$config['pagseguro']['produtoNome'] = 'Tibia Coins';
$config['pagseguro']['urlRedirect'] = $config['base_url'];
$config['pagseguro']['urlNotification'] = 'http://localhost/retpagseguro.php';
$config['pagseguro']['host'] = 'localhost'; // host banco de dados
$config['pagseguro']['database'] = 'global'; // nome do banco de dados
$config['pagseguro']['databaseUser'] = 'root'; // usuario banco de dados
$config['pagseguro']['databasePass'] = 'Quente0011@'; // senha banco de dados

$config['pagseguro']['offers'] = [
	25000 => 60,
	45000 => 150,
	80000 => 330,
	115000 => 440,
	145000 => 560,
	225000 => 960
];

// /** PayPal configs */
$config['paypal']['email'] = "alissonrenna@gmail.com";
$config['paypal']['sandboxemail'] = "alissonrenna@gmail.com";
$config['paypal']['itemName'] = "Tibia Coins";
$config['paypal']['notify_url'] = $config['base_url'] . "paypal_ipn.php";
$config['paypal']['currency'] = "BRL";
// /** SETUP LIVE OR TESTING YOUR IMPLEMENT */
$config['paypal']['env'] = "production"; // sandbox | production
// /** PRODUCTION IDS */
$config['paypal']['clientID'] = "44ZF2AC33P84A";
$config['paypal']['clientSecretID'] = "";
// /** SANDBOX IDS */
$config['paypal']['sandboxClientID'] = "44ZF2AC33P84A";
$config['paypal']['sandboxClientSecretID'] = "";
// /** ##PayPal configs */
/** PayPal configs *
$config['paypal']['email'] = "@gmail.com";
$config['paypal']['sandboxemail'] = "sb-vuwmo1072234@business.example.com";
$config['paypal']['itemName'] = "Tibia Coins";
$config['paypal']['notify_url'] = $config['base_url'] . "paypal_ipn.php";
$config['paypal']['currency'] = "BRL";
/** SETUP LIVE OR TESTING YOUR IMPLEMENT */
$config['paypal']['env'] = "production"; // sandbox | production
/** PRODUCTION IDS *
$config['paypal']['clientID'] = "AeugcJqNoDkBMsasAZEA5RT3tUlUk16e1CWEdlKVwX4nC-WR9DqH6v2G9DgeDHoSdzcdf7dUFfZj9YzG";
$config['paypal']['clientSecretID'] = "EK-7Z_QzqdoD9Znzh-JaBb4GuTEHO2NDF4g62A1qypag_UfoBdGtGAzLtDQK-TICFxTfG9KNMhTBhFQI";
/** SANDBOX IDS *
$config['paypal']['sandboxClientID'] = "AZwa4diF_v3paZRmk-_1IqoDMTGHHj0v9YWy4oADBnyC6IMWjxVvcfQv0jRjfrXmnRbSMiNNSGRGfsbE";
$config['paypal']['sandboxClientSecretID'] = "ELInO_e3JRBv7NnlasFwEP7FVVQAW4mBtTi290vqVE_XZABVeWze5BH_w1xrmvQA5Gp1Eg1dPKlFGVDV";
/** ##PayPal configs*/

/** MERCADO PAGO CONFIGS */
$config['mp']['CLIENT_ID'] = "7012621725888462";
$config['mp']['CLIENT_SECRET'] = "4W9BSlRO1v5zwCHxy0zw9WL8l8qBZQ3d";
$config['mp']['SANDBOX_CLIENT_ID'] = "7012621725888462";
$config['mp']['SANDBOX_CLIENT_SECRET'] = "4W9BSlRO1v5zwCHxy0zw9WL8l8qBZQ3d";
$config['mp']['sandboxMode'] = false; // true | false
$config['sale']['productName'] = "Tibia Coins";
$config['sale']['subProductName'] = "Coins";
/** ##MERCADO PAGO CONFIGS */

/** LAYOUT CONFIGS */
//$config['site']['layout'] = 'med'; //Layout Name
$config['site']['layout'] = 'tibiacom'; //Layout Name
//$config['site']['layout'] = 'materialize_template'; //Layout Name
$config['site']['vdarkborder'] = '#505050';
$config['site']['darkborder'] = '#D4C0A1';
$config['site']['lightborder'] = '#F1E0C6';
$config['site']['download_page'] = false;
$config['site']['serverinfo_page'] = true;
$config['site']['cssVersion'] = "?vs=3.0.6";

/** MULTIPLE REQ CONFIGS */
$config['site']['max_req_tries'] = 3;
$config['site']['timeout_time'] = 1; //TIME IN MINUTES

/** SELL CHARACTERS ACCOUNT CONFIGURE */
$config['sell']['account_seller_id'] = 2;
$config['site']['max_price_coin'] = 10000;
$config['site']['max_price_gold'] = 100000000;
$config['site']['sell_by_gold'] = false;
$config['site']['min_lvl_to_sell'] = 1;
/** SALE TAXES PERCENT 0-100 */
$config['site']['percent_sellchar_sale'] = 5;

/** Promoção configuration */
$config['site']['promo_isactive'] = false;
$config['site']['promo_imagename'] = '_promo.png';

/** SELL CHARACTERS VARIABLES LOAD */
$config['site']['Outfits_path'] = $config['site']['serverPath'] . "data/XML/outfits.xml";
$config['site']['Mounts_path'] = $config['site']['serverPath'] . "data/XML/mounts.xml";
$config['site']['Itens_path'] = $config['site']['serverPath'] . "data/items/items.xml";

$config['site']['enablelogs'] = false;
$config['site']['logsdir'] = '/home/logs/';
$config["site"]["sqlDatabase"] = "otserver";
$config["site"]["mysqlHost"] = "localhost";
$config["site"]["mysqlUser"] = "otserver";
$config["site"]["mysqlPass"] = "Civic123@";
$config["site"]["mysqlDatabase"] = "otserver";
$config["site"]["mysqlPort"] = "3306";



// Configurações do servidor (lidas do config.lua)
$config['server'] = array();
$config['server']['mysqlHost'] = 'localhost';
$config['server']['mysqlUser'] = 'otserver';
$config['server']['mysqlPass'] = 'Civic123@';
$config['server']['mysqlDatabase'] = 'otserver';
$config['server']['mysqlPort'] = '3306';




// Configurações do servidor (MySQL e outras)
$config['server']['serverName'] = "OT7 Server";
$config['server']['statusTimeout'] = "5";
$config['server']['ip'] = "181.215.45.238";
$config['server']['statusProtocolPort'] = "7171";
