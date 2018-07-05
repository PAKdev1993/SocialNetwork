<?php

require_once __DIR__ . '/vendor/facebook/php-sdk-v4/src/Facebook/autoload.php';
\core\Session\Session::getInstance();

$fbcontroleur = \app\App::getFacebookAPIManager();

$domain = "http://localhost";
$login_callback = "/test/facebook-login/FacebookLogin-callback";
$register_callback = "/test/facebook-login/FacebookRegister-callback";
$redirect_login_url = $domain . $login_callback .'.php';
$redirect_register_url = $domain . $register_callback .'.php';

$fb = new Facebook\Facebook([
'app_id' => $fbcontroleur->getAppId(),
'app_secret' => $fbcontroleur->getAppSecret(),
'default_graph_version' => $fbcontroleur->getDefaultGraphVersion()
]);

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email', 'user_likes']; // optional
$loginUrl = $helper->getLoginUrl($redirect_login_url, $permissions);



