<?php
require_once '../Autoloader.php';
Autoloader::register();

$session = Session::getInstance();

require_once __DIR__ . '/vendor/facebook/php-sdk-v4/src/Facebook/autoload.php';

$fbcontroleur = App::getFacebookControler();
$fb = new Facebook\Facebook([
    'app_id' => $fbcontroleur->getAppId(),
    'app_secret' => $fbcontroleur->getAppSecret(),
    'default_graph_version' => $fbcontroleur->getDefaultGraphVersion()
]);


$helper = $fb->getRedirectLoginHelper();
try {
    $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}
// OAuth 2.0 client handler
$oAuth2Client = $fb->getOAuth2Client();

// Exchanges a short-lived access token for a long-lived one
$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);

if (isset($accessToken)) {
    // Logged in!
    $_SESSION['facebook_access_token'] = (string) $accessToken;

    // Now you can redirect to another page and use the
    // access token from $_SESSION['facebook_access_token']
}

try {
    $response = $fb->get('/me?fields=id,first_name,last_name,email', $accessToken);
    $userNode = $response->getGraphUser();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if($fbcontroleur->register($userNode->getEmail(), $userNode->getFirstName(), $userNode->getLastName(), $userNode->getId()))
{
    $fbcontroleur->login($session, $userNode->getId());
    App::redirect('../home');
}
else
{
    $fbcontroleur->login($session, $userNode->getId());
    App::redirect('../home');
}
