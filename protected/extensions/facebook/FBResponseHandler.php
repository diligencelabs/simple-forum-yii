<?php
/**
* Facebook Response Handler
*/
class FBResponseHandler extends CAction {
	public function run()
    {
		$user = null;
		try {
			Yii::app()->fbconnect->processOAuthResponse();
			$user = Yii::app()->fbconnect->getUserInfo();
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
		$model = User::model()->find("external_id=:external_id AND external_app='facebook'", array(':external_id' => $user->id));
		
		if($model)
			return $model;
		
		$model = new User();
		$model->name = isset($user->name)?$user->name:$user->email;
		$model->username = 'facebook:' . $user->id;
		$model->email = $user->email;
		$model->user_type = 'C';
		$model->password = md5(uniqid('u', true));
		$model->salt = md5(uniqid('p', true));
		$model->external_app = 'facebook';
		$model->external_id = $user->id;
		
		
		if( $model->save(false) ) {
			return $model;
		}
		
		throw new CHttpException(404, 'Error: unable to save user record');
		
		//return null;
	}
}
/*

stdClass Object
(
    [id] => 2353534534545
    [name] => xxxx yyyy
    [first_name] => xxxx
    [last_name] => yyyy
    [link] => http://www.facebook.com/PPPPPPPP
    [username] => PPPPPPPP
    [work] => Array
        (
            [0] => stdClass Object
                (
                    [employer] => stdClass Object
                        (
                            [id] => 345345346546546
                            [name] => PPPPPPPP
                        )

                )

        )

    [gender] => male
    [email] => xxxxx@example.com
    [timezone] => 5.5
    [locale] => en_US
    [verified] => 1
    [updated_time] => 2012-09-11T18:51:16+0000
)

*/