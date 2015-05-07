<?php
/**
* Twitter Response Handler
*/

class TwitterResponseHandler extends CAction {
	public function run()
    {
		$user = null;
		try {
			$user = Yii::app()->twconnect->handleResponse();
		}
		catch(Exception $ex) {
			throw new CHttpException(404, $ex->getMessage());
		}
		
		$this->processUser( $user );
    }
	
	public function processUser($user) {
		//print_r( $user ); die;
		
		$model = $this->saveToDatabase( $user );
		
		$identity = new UserIdentity( $model->name, 'none');
		$identity->setId( $model->id );
		Yii::app()->user->login( $identity );
		
		$this->controller->redirect(Yii::app()->user->returnUrl);
	}
	
	public function saveToDatabase($user) {
		$model = User::model()->find("external_id=:external_id AND external_app='twitter'", array(':external_id' => $user['user_id']));
		
		if($model)
			return $model;
		
		$model = new User();
		$model->name = $user['screen_name'];
		$model->username = 'twitter:' . $user['user_id'];
		$model->email = $model->name . '@twitter.com';
		$model->user_type = 'C';
		$model->password = md5(uniqid('u', true));
		$model->salt = md5(uniqid('p', true));
		$model->external_app = 'twitter';
		$model->external_id = $user['user_id'];
		
		
		if( $model->save(false) ) {
			return $model;
		}
		
		throw new CHttpException(404, 'Error: unable to save user record');
		
		//return null;
	}
}
