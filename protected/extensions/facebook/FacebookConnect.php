<?php
/*
* facebook integration component
* 
*/

class FacebookConnect extends CApplicationComponent {
	public $returnUrl;
	
	//Facebook App ID/API Key
	private $_appId;
	//App Secret
	private $_appSecret;
	//A comma separated list of additional permissions which you would like the user to grant your app.
	private $_scope;
	private $_authToken;
	private $_authCode;
	
	private $_assetsUrl;
	
	public static $oAuthUrl = 'https://www.facebook.com/dialog/oauth?';
	public static $GrapthAuthUrl = 'https://graph.facebook.com/oauth/access_token?';
	public static $GrapthOpsUrl = 'https://graph.facebook.com/me?';
	
	/**
	* getters/setters
	*/
	public function setAppId( $id ) {
		$this->_appId = $id;
	}
	public function getAppId() {
		return $this->_appId;
	}
	
	/**
	* getters/setters
	*/
	public function setScope( $scope ) {
		$this->_scope = $scope;
	}
	public function getScope() {
		return $this->_scope;
	}
	
	/**
	* setters
	*/
	public function setAppSecret( $secret ) {
		$this->_appSecret = $secret;
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
	* get login button
	*/
	public function loginButton() {
		return '<a href="' . $this->getOAuthUrl() . '" id="facebook-login-btn" class="facebook-login-btn"><img src="' . $this->assetsUrl . '/fb_connect.png" border="0" alt="Facebook Login" title="Facebook Login"/></a>'; 
	}
	
	/**
	* get login button
	*/
	protected function getOAuthUrl() {
		return self::$oAuthUrl . 'client_id=' . $this->appId . '&redirect_uri=' . urlencode($this->returnUrl) . '&scope=' . $this->scope . '&state=' . $this->getState();
	}
	
	/**
	* get state id
	*/
	protected function getState() {
		$id = uniqid('fb', true);
		
		$session=new CHttpSession;
		$session->open();
		$session['fb_state']=$id;
		
		return $id;
	}
	
	/**
	* check state id
	*/
	protected function checkState( $id ) {
		$session=new CHttpSession;
		$session->open();
		
		return ( isset($session['fb_state']) && $session['fb_state'] === $id)? true: false;
	}
	
	/**
	* check state id
	*/
	public function processOAuthResponse() {
		foreach($_GET  as $k => $v) {
			if(!is_array($v)) {
				$v = str_replace('#_=_', '', $v);
				$_GET[$k] = $v;
			}
		}
		
		$state = isset($_GET['state'])?$_GET['state']:null;
		$code = isset($_GET['code'])?$_GET['code']:null;
		
		$error_reason = isset($_GET['error_reason'])?$_GET['error_reason']:null;
		$error = isset($_GET['error'])?$_GET['error']:null;
		$error_description = isset($_GET['error_description'])?$_GET['error_description']:null;
		
		if(!$code) {
			throw new CException('Access error: ' . $error_description);
		}
		if( $this->checkState($state) == false ) {
			throw new CException('Not a valid request!');
		}
		
		$this->_authCode = $code;
		
		if( !$this->getAuthToken() ) {
			throw new CException('Error validating verification code.');
		}
	}
	
	/**
	* get asste url
	*/
	public function getAuthToken() {
        if( $this->_authToken === null ) {
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $this->getAuthTokenUrl() );
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
			$response = curl_exec($ch);
			
			$curl_errno = curl_errno($ch);
			$curl_error = curl_error($ch);

			curl_close($ch);
			
			$params = null;
			parse_str( trim($response), $params);
			
			if( isset($params['access_token']) )
				return $this->_authToken = $params['access_token'];
			return null;
		}
		
        return $this->_authToken;
    }
	
	/**
	* get login button
	*/
	protected function getAuthTokenUrl() {
		return self::$GrapthAuthUrl . 'client_id=' . $this->appId . '&client_secret=' . $this->_appSecret . '&code=' . urlencode($this->_authCode) . '&redirect_uri=' . urlencode($this->returnUrl);
	}
	
	/**
	* get asste url
	*/
	public function getUserInfo() {
        if( $this->_authToken === null ) {
            throw new CException('Error no auth token found.');
		}
		
		$graph_url = self::$GrapthOpsUrl . "access_token="  . $this->_authToken;

		$user = json_decode(file_get_contents($graph_url));
		//echo("Hello " . $user->name);
		
        return $user;
    }
	
}