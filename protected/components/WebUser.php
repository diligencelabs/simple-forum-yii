<?php

class WebUser extends CWebUser {
	private $_user;
	
	public function getIsAdmin() {
		if(Yii::app()->user->isGuest) {
			return false;
		}
		if( !$this->_user && Yii::app()->user->id ) {
			$this->_user = \User::model()->find('id=:id', array(':id' => Yii::app()->user->id));
		}
		if(!$this->_user)
			return false;
		
		if($this->_user->user_type == 'A')
			return true;
		
		return false;
	}
}