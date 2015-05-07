<?php

/**
 * This is the model class for table "sposts".
 *
 * The followings are the available columns in table 'sposts':
 * @property integer $id
 * @property integer $created_by
 * @property string $created_by_name
 * @property integer $modified_by
 * @property string $modified_by_name
 * @property integer $created_on
 * @property integer $modified_on
 * @property integer $sforum_id
 * @property integer $stopic_id
 * @property string $body
 * @property string $ip
 * @property integer $status
 * @property integer $has_attachment
 */
class Spost extends SforumActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Spost the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sposts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sforum_id, stopic_id, body', 'required'),
			array('created_by, modified_by, created_on, modified_on, sforum_id, stopic_id, status, has_attachment', 'numerical', 'integerOnly'=>true),
			array('created_by_name, modified_by_name', 'length', 'max'=>100),
			array('ip', 'length', 'max'=>255),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, created_by, created_by_name, modified_by, modified_by_name, created_on, modified_on, sforum_id, stopic_id, body, ip, status, has_attachment', 'safe', 'on'=>'search'),
		);
	}
	
	protected function nonRequestFields() {
		return array(
			'status' => 1,
			'has_attachment' => 1,
			'ip' => 1,
		);
	}
	
	protected function beforeSave() {
	
		if($this->isNewRecord) {
			$this->status = 0;
			
			if(isset($_SERVER['REMOTE_ADDR'])) $this->ip = $_SERVER['REMOTE_ADDR'];
			
			if( SforumUtils::isAdmin() || (Yii::app()->controller && Yii::app()->controller->module && Yii::app()->controller->module->autoApproveComments) ) {
				$this->status = 1;
			}
		}
		
		return parent::beforeSave();
	}
	
	protected function afterSave()
	{
		$count = false;
		if( isset($this->status) && $this->status == 1 && ( $this->isNewRecord || ($this->old && $this->old->status != $this->status) ) ) {
			$count = true;
		}
		
		if( SforumUtils::isAdmin() )
			$count = true;
		
		if($count && $this->forum) {
			$this->forum->of_posts++;
			$this->forum->save();
		}
		
		if($count && $this->topic) {
			$this->topic->of_replies++;
			$this->topic->save();
		}
		
		$this->alertUsers();
		
		return parent::afterSave();
	}
	
	protected function alertUsers() {
		if($this->topic && $this->topic->owner) {
			if( $this->topic->owner->email ) {
				$subject = ($this->isNewRecord?'New Post Created':'Post updated') . ': ' . CHtml::encode($this->topic->name);
				$body = $subject . "\n\n";
				$body .= SforumUtils::post($this->body) . "\n\n";
				$body .= 'IP Address: ' . $this->ip . "\n\n";
				$body .= 'Email: ' . $this->email . "\n\n";
				
				$page = 0;
				if( Yii::app()->controller && Yii::app()->controller->module ) {
					$page = ceil( $this->topic->of_replies / Yii::app()->controller->module->commentsPerPage );
				}
				
				if( Yii::app()->request ) {
					$body .= 'Link: ' 
					. Yii::app()->request->getHostInfo() . '/' 
					. Yii::app()->createUrl('sforum/stopic/view', array('id' => $this->topic->id)) 
					. '&Spost_page=' . $page 
					. '#a-' . $this->id;
				}
				
				SforumUtils::sendMail($this->topic->owner->email, $subject, $body );
			}
		}
	}
	
	protected function afterDelete()
	{
		$count = false;
		if( isset($this->status) && $this->status == 1 ) {
			$count = true;
		}
		
		if($count && $this->forum) {
			$this->forum->of_posts--;
			if($this->forum->of_posts <= 0)
				$this->forum->of_posts = 0;
			$this->forum->save();
		}
		
		if($count && $this->topic) {
			$this->topic->of_replies--;
			if($this->topic->of_replies <= 0)
				$this->topic->of_replies = 0;
			$this->topic->save();
		}
		
		return parent::afterDelete();
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'forum' => array(self::BELONGS_TO, 'Sforum', 'sforum_id'),
			'topic' => array(self::BELONGS_TO, 'Stopic', 'stopic_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'created_by' => 'Created By',
			'created_by_name' => 'Created By Name',
			'modified_by' => 'Modified By',
			'modified_by_name' => 'Modified By Name',
			'created_on' => 'Created On',
			'modified_on' => 'Modified On',
			'sforum_id' => 'Sforum',
			'stopic_id' => 'Stopic',
			'body' => 'Reply',
			'ip' => 'Ip',
			'status' => 'Status',
			'has_attachment' => 'Has Attachment',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_by_name',$this->created_by_name,true);
		$criteria->compare('modified_by',$this->modified_by);
		$criteria->compare('modified_by_name',$this->modified_by_name,true);
		$criteria->compare('created_on',$this->created_on);
		$criteria->compare('modified_on',$this->modified_on);
		$criteria->compare('sforum_id',$this->sforum_id);
		$criteria->compare('stopic_id',$this->stopic_id);
		$criteria->compare('body',$this->body,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('has_attachment',$this->has_attachment);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
}