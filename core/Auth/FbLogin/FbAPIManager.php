<?php

namespace core\Auth\FbLogin;

use Facebook;

class FbAPIManager
{
    private $app_id = '1111899488869682';
    private $app_secret = '733fbe2f54c63e584819ec13c452d78d';
    private $default_graph_version = 'v2.6';

    static $fb; //facebook object

    public function __construct()
    {
        require_once 'vendor/facebook/php-sdk-v4/src/Facebook/autoload.php';
        self::$fb = new \Facebook\Facebook([
            'app_id' => $this->app_id,
            'app_secret' => $this->app_secret,
            'default_graph_version' => $this->default_graph_version
        ]);
    }

    public function getAppId()
    {
        return $this->app_id;
    }

    public function getAppSecret()
    {
        return $this->app_secret;
    }

    public function getDefaultGraphVersion()
    {
        return $this->default_graph_version;
    }

    public function getLoginUrl()
    {
        $domain = "http://localhost";
        $login_callback = "/site/core/Auth/FbLogin/FacebookLogin-callback";
        $redirect_url = $domain . $login_callback .'.php';

        $helper = self::$fb->getRedirectLoginHelper();
        $permissions = ['email', 'user_likes']; // optional

        return $helper->getLoginUrl($redirect_url, $permissions);
    }
}