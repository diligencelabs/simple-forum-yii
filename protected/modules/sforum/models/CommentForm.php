<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class CommentForm extends CFormModel
{
	public $name;
	public $email;
	//public $subject;
	public $body;
	public $verifyCode;
	public $stopic_id;
	public $sforum_id;
	
	public $id;
	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('name, email, body', 'required'),
			array('email', 'email'),
			array('name', 'length', 'max'=>100),
			// verifyCode needs to be entered correctly
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'verifyCode'=>'Verification Code',
		);
	}
	
	public function save() {
		$post = new Spost();
		
		$post->stopic_id = $this->stopic_id;
		$post->sforum_id = $this->sforum_id;
		$post->body = $this->body;
		$post->email = $this->email;
		
		$post->created_by_name = $this->name;
		$post->modified_by_name = $this->name;
		
		$this->id = $post->save(false);
		
		return $this->id = $post->id;
	}
	
	public function excludesFromRequest() {
		return array();
	}
}