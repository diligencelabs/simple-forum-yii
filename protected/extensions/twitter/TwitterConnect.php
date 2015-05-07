<?php
/*
* Twitter integration component
* 
*/

require_once __DIR__ . '/tmhOAuth/tmhOAuth.php';
require_once __DIR__ . '/tmhOAuth/tmhUtilities.php';

class TwitterConnect extends CApplicationComponent {
	public $returnUrl;
	
	//Twitter App ID/API Key
	private $_consumerKey;
	//App Secret
	private $_consumerSecret;
	//A comma separated list of additional permissions which you would like the user to grant your consumer.
	//private $_scope;
	//private $_authToken;
	//private $_authCode;
	
	private $_assetsUrl;
	
	/*
	public static $OAuthVersion = '1.0';
	public static $OAuthSignatureMethod = 'HMAC-SHA1';
	public static $RequestTokenURL = 'https://api.twitter.com/oauth/request_token';
	public static $AuthorizeURL = 'https://api.twitter.com/oauth/authorize';
	public static $AccessTokenURL = 'https://api.twitter.com/oauth/access_token';
	*/
	
	public $tmhOAuth;
	
	public function init() {
		parent::init();
		
		$this->tmhOAuth = new tmhOAuth(array(
			'consumer_key'    => $this->consumerKey,
			'consumer_secret' => $this->_consumerSecret,
		));
	}
	
	/**
	* getters/setters
	*/
	public function setConsumerKey( $id ) {
		$this->_consumerKey = $id;
	}
	public function getConsumerKey() {
		return $this->_consumerKey;
	}
	
	/**
	* setters
	*/
	public function setConsumerSecret( $secret ) {
		$this->_consumerSecret = $secret;
	}
	
	
	/**
	* get asste url
	*/
	public function getAssetsUrl() {
        if ($this->_assetsUrl === null)
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish( __DIR__ .'/assets');
        return $this->_assetsUrl;
    }
	
	/**
	* get state
	*/
	protected function getState() {
		$id = uniqid('tw', true);
		
		$session=new CHttpSession;
		$session->open();
		$session['twitter-state']=$id;
		
		return $id;
	}
	
	/**
	* check state
	*/
	protected function checkState( $id ) {
		$session=new CHttpSession;
		$session->open();
		
		return ( isset($session['twitter-state']) && $session['twitter-state'] === $id)? true: false;
	}
	
	/**
	* clear state
	*/
	protected function clearState( ) {
		$session=new CHttpSession;
		$session->open();
		
		unset($session['twitter-state']);
	}
	
	/**
	* get login button
	*/
	public function loginButton() {
		$url = $this->returnUrl;
		if(strpos($url, '?') === false)
			$url .= '?';
		else
			$url .= '&';
		$url .= 'login=' . $this->getState();
		
		return '<a href="'.$url.'" id="twitter-login-btn" class="twitter-login-btn"><img src="' . $this->assetsUrl . '/twitterlogin.png"  border="0" alt="Twitter Login" title="Twitter Login"/></a>'; 
	}
	
	public function outputError() {
		throw new CException('There was an error: ' . $this->tmhOAuth->response['response']);
	}
	
	// Step 1: Request a temporary token
	public function requestToken() {
		$code = $this->tmhOAuth->request(
			'POST',
			$this->tmhOAuth->url('oauth/request_token', ''),
			array(
				'oauth_callback' => $this->returnUrl
			)
		);

		if( $code == 200 ) {
			$session=new CHttpSession;
			$session->open();
			$session['twitter-oauth'] = $this->tmhOAuth->extract_params($this->tmhOAuth->response['response']);
			
			
			$this->authorize( $session['twitter-oauth'] );
		} else {
			$this->outputError();
		}
	}
	
	// Step 2: Direct the user to the authorize web page
	protected function authorize( $oauth ) {
		
		$authurl = $this->tmhOAuth->url("oauth/authorize", '') .  "?oauth_token={$oauth['oauth_token']}";
		header("Location: $authurl");

		// in case the redirect doesn't fire
		echo '<p>To complete the OAuth flow please visit URL: <a href="'. $authurl . '">' . $authurl . '</a></p>';
		exit;
	}
	
	/**
	* RESPONSE HANDLER
	* Step 3: This is the code that runs when Twitter redirects the user to the callback. 
	*         Exchange the temporary token for a permanent access token
	*/
	public function handleResponse() {
		
		if( isset($_GET['login']) && $this->checkState($_GET['login']) ) {
			$this->clearState();
			$this->requestToken();
			exit;
		}
		
		$session=new CHttpSession;
		$session->open();
		
		if( !isset($session['twitter-oauth']) ) {
			throw new CException('There was an error: no oauth_token found');
		}
		
		if( !isset($_REQUEST['oauth_verifier']) ) {
			throw new CException('There was an error: no oauth_verifier found');
		}
		
		$oauth = $session['twitter-oauth'];
		
		$this->tmhOAuth->config['user_token']  = $oauth['oauth_token'];
		$this->tmhOAuth->config['user_secret'] = $oauth['oauth_token_secret'];

		$code = $this->tmhOAuth->request(
			'POST',
			$this->tmhOAuth->url('oauth/access_token', ''),
			array(
				'oauth_verifier' => $_REQUEST['oauth_verifier']
			)
		);

		if( $code == 200 ) {
			$session['twitter-access_token'] = $this->tmhOAuth->extract_params( $this->tmhOAuth->response['response'] );
			
			unset($session['twitter-oauth']);
			
			return $session['twitter-access_token'];
		} else {
			$this->outputError();
		}
		
		return null;
	}
	
	public function tweatMessage( $msg ) {
		// $session['twitter-access_token']   will be used here
	}
}