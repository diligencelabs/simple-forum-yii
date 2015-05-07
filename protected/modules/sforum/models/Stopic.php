<?php

/**
 * This is the model class for table "stopics".
 *
 * The followings are the available columns in table 'stopics':
 * @property integer $id
 * @property integer $created_by
 * @property string $created_by_name
 * @property integer $modified_by
 * @property string $modified_by_name
 * @property integer $created_on
 * @property integer $modified_on
 * @property integer $sforum_id
 * @property string $name
 * @property string $description
 * @property string $image
 * @property integer $status
 * @property integer $of_posts
 * @property integer $last_post_id
 * @property integer $type
 * @property integer $of_views
 * @property integer $of_replies
 * @property integer $has_attachment
 * @property integer $first_post_id
 */
class Stopic extends SforumActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Stopic the static model class
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
		return 'stopics';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sforum_id, name', 'required'),
			array('created_by, modified_by, created_on, modified_on, sforum_id, status, of_posts, last_post_id, type, of_views, of_replies, has_attachment, first_post_id', 'numerical', 'integerOnly'=>true),
			array('created_by_name, modified_by_name', 'length', 'max'=>100),
			array('name, image', 'length', 'max'=>255),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, created_by, created_by_name, modified_by, modified_by_name, created_on, modified_on, sforum_id, name, description, image, status, of_posts, last_post_id, type, of_views, of_replies, has_attachment, first_post_id', 'safe', 'on'=>'search'),
		);
	}
	
	protected function beforeSave() {
	
		$this->status = 1;
		
		return parent::beforeSave();
	}
	
	protected function afterSave()
	{
		$count = false;
		if( isset($this->status) && $this->status == 1 && ( $this->isNewRecord || ($this->old && $this->old->status != $this->status) ) ) {
			$count = true;
		}
		
		if($count && $this->forum) {
			$this->forum->of_topics++;
			$this->forum->save();
		}
		return parent::afterSave();
	}
	
	protected function afterDelete()
	{
		$count = false;
		if( isset($this->status) && $this->status == 1 ) {
			$count = true;
		}
		
		if($count && $this->forum) {
			$this->forum->of_topics--;
			if($this->forum->of_topics <= 0)
				$this->forum->of_topics = 0;
			$this->forum->save();
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
			'posts' => array(self::HAS_MANY, 'Spost', 'stopic_id'),
			'owner' => array(self::BELONGS_TO, 'User', 'created_by'),
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
			'created_by_name' => 'Created By',
			'modified_by' => 'Modified By',
			'modified_by_name' => 'Modified By',
			'created_on' => 'Created On',
			'modified_on' => 'Modified On',
			'sforum_id' => 'Sforum',
			'name' => 'Topic',
			'description' => 'Description',
			'image' => 'Image',
			'status' => 'Status',
			'of_posts' => 'Of Posts',
			'last_post_id' => 'Last Post',
			'type' => 'Type',
			'of_views' => '# Of Views',
			'of_replies' => '# Of Replies',
			'has_attachment' => 'Has Attachment',
			'first_post_id' => 'First Post',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('of_posts',$this->of_posts);
		$criteria->compare('last_post_id',$this->last_post_id);
		$criteria->compare('type',$this->type);
		$criteria->compare('of_views',$this->of_views);
		$criteria->compare('of_replies',$this->of_replies);
		$criteria->compare('has_attachment',$this->has_attachment);
		$criteria->compare('first_post_id',$this->first_post_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function comments() {
		return new CActiveDataProvider('Spost', array(
			'criteria'=>array(
				'condition' => "t.stopic_id=:stopic_id AND t.sforum_id=:sforum_id AND t.status=1",
				'params' => array(
					':stopic_id' => $this->id,
					':sforum_id' => $this->sforum_id,
				),
				'with' => array('topic', 'forum'),
				'order' => "t.id ASC",
			),
			'pagination'=>array(
				'pageSize'=> Yii::app()->controller->module->commentsPerPage,
			),
		));
	}
}