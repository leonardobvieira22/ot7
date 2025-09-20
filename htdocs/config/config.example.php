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
$config['site']['realurl'] = "http://your-domain.com/"; // Put the real url for your website without www DO NOT FORGET FROM / AT THE END
$config['site']['realurlwww'] = "http://your-domain.com/"; // Put the real url for your website with www IF IT IS A SUBDOMINUM PUT THE MSM URL OF THE REAL URL
$config['site']['testurl'] = "http://localhost/"; // Put the url you use to test your site (LOCALHOST)
/** END SERVER URLS */


/** SERVER PATHS */
if ($config['base_url'] == $config['site']['realurl'] || $config['base_url'] == $config['site']['realurlwww']) {
	$config['site']['serverPath'] = "/path/to/server/"; // SERVER PATH IN PRODUCTION
} else {
	$config['site']['serverPath'] = "/path/to/local/server/"; // SERVERPATH LOCALHOST
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
$config['social']['facebook'] = ""; // Link to your facebook page
$config['social']['fbapiversion'] = "";
$config['social']['fbapilink'] = "";
$config['social']['fbpageid'] = "";
$config['social']['accessToken'] = "";
$config['social']['twitter'] = "";
$config['social']['twittercreator'] = "";
$config['social']['fbappid'] = "";

# Database Configuration
$config['site']['sqlHost'] = "localhost";
$config['site']['sqlUser'] = "your_user";
$config['site']['sqlPass'] = "your_password";
$config['site']['sqlBD'] = "your_database";

# Characters animatedOutfits php
$config['site']['animatedOutfits_url'] = 'http://127.0.0.1:8090/AnimatedOutfits/animoutfit.php?';
$config['site']['outfit_images_url'] = '/outfit.php?';
$config['site']['icons_images_url'] = '/images/icons_damage/';
$config['site']['item_images_extension'] = '.png';
$config['site']['flag_images_url'] = '/images/flags/';
$config['site']['flag_images_extension'] = '.png';

# Email Configuration
$config['site']['send_emails'] = true;
$config['site']['mail_address'] = "your@email.com";
$config['site']['mail_senderName'] = "Your Server Name";
$config['site']['smtp_enabled'] = true;
$config['site']['smtp_host'] = "smtp.your-provider.com";
$config['site']['smtp_port'] = 465;
$config['site']['smtp_auth'] = true;
$config['site']['smtp_user'] = "your@email.com";
$config['site']['smtp_pass'] = "your_password";
$config['site']['smtp_secure'] = true;

# Payment Methods Configuration
$config['paymentsMethods'] = [
	'pagseguro' => true,
	'paypal' => false,
	'mercadoPago' => true,
	'transfer' => false,
	'picpay' => false
];

# PagSeguro Configuration
$config['pagseguro']['testing'] = false;
$config['pagseguro']['lightbox'] = true;
$config['pagseguro']['email'] = "your@email.com";
$config['pagseguro']['token'] = "your_token";
$config['pagseguro']['produtoNome'] = 'Tibia Coins';
$config['pagseguro']['urlRedirect'] = $config['base_url'];
$config['pagseguro']['urlNotification'] = $config['base_url'] . 'retpagseguro.php';

# PayPal Configuration
$config['paypal']['email'] = "your@email.com";
$config['paypal']['itemName'] = "Tibia Coins";
$config['paypal']['notify_url'] = $config['base_url'] . "paypal_ipn.php";
$config['paypal']['currency'] = "BRL";
$config['paypal']['env'] = "production"; // sandbox | production
$config['paypal']['clientID'] = "your_client_id";
$config['paypal']['clientSecretID'] = "your_secret";

# Mercado Pago Configuration
$config['mp']['CLIENT_ID'] = "your_client_id";
$config['mp']['CLIENT_SECRET'] = "your_client_secret";
$config['mp']['sandboxMode'] = false;

# Layout Configuration
$config['site']['layout'] = 'tibiacom';
$config['site']['vdarkborder'] = '#505050';
$config['site']['darkborder'] = '#D4C0A1';
$config['site']['lightborder'] = '#F1E0C6';
$config['site']['download_page'] = false;
$config['site']['serverinfo_page'] = true;
$config['site']['cssVersion'] = "?vs=3.0.6";

# Other Configurations
include_once('config.local.php'); // Include local overrides
