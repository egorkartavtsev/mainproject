<?php

class ControllerCommonCreateXML extends Controller {
    public function index() {
//        exit(var_dump(is_file(DIR_SYSTEM . '../vendor/autoload.php')));
        //$vk = new VK\Client\VKApiClient();
        $oauth = new VK\OAuth\VKOAuth();
        $client_id = 6405567;
        $redirect_uri = 'http://xn--174-5cdagf5b9cdset.xn--p1ai';
        $display = VK\OAuth\VKOAuthDisplay::PAGE; 
        $scope = array(VK\OAuth\Scopes\VKOAuthUserScope::WALL, VK\OAuth\Scopes\VKOAuthUserScope::GROUPS); 
//        $state = '616d99ba616d99ba616d99ba2a610c24'; 
        $state = '8p4qDeXeb4zAO8oVaR6k'; 

        $browser_url = $oauth->getAuthorizeUrl(VK\OAuth\VKOAuthResponseType::CODE, $client_id, $redirect_uri, $display, $scope, $state);
        $this->response->redirect($browser_url);
        exit(var_dump($browser_url));
    }
}