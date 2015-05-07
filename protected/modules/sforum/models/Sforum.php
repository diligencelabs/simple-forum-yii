<?php

/**
 * This is the model class for table "sforums".
 *
 * The followings are the available columns in table 'sforums':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $image
 * @property integer $status
 * @property integer $of_posts
 * @property integer $of_topics
 * @property integer $created_by
 * @property string $created_by_name
 * @property integer $modified_by
 * @property string $modified_by_name
 * @property integer $created_on
 * @property integer $modified_on
 * @property integer $last_post_id
 * @property integer $last_topic_id
 * @property integer $type
 */
class Sforum extends SforumActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Sforum the static model class
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
		return 'sforums';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		$categories = array_keys( self::getCategoryList() );
		$categories[] = 0;
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('status, of_posts, of_topics, created_by, modified_by, created_on, modified_on, last_post_id, last_topic_id, type, parent_id, ordering', 'numerical', 'integerOnly'=>true),
			array('name, image', 'length', 'max'=>255),
			array('created_by_name, modified_by_name', 'length', 'max'=>100),
			array('description', 'safe'),
			array('parent_id', 'in', 'range' => $categories, 'allowEmpty' => true ),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, description, image, status, of_posts, of_topics, created_by, created_by_name, modified_by, modified_by_name, created_on, modified_on, last_post_id, last_topic_id, type', 'safe', 'on'=>'search'),
		);
	}
	
	protected function nonRequestFields() {
		return array(
			'of_posts' => 1,
			'of_topics' => 1,
			'last_post_id' => 1,
			'last_topic_id' => 1,
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'forums' => array(self::HAS_MANY, 'Sforum', 'parent_id'),
			'category' => array(self::HAS_ONE, 'Sforum', 'parent_id'),
			'topics' => array(self::HAS_MANY, 'Stopic', 'sforum_id', 'order' => 'topics.id desc')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'description' => 'Description',
			'image' => 'Image',
			'status' => 'Status',
			'of_posts' => 'Of Posts',
			'of_topics' => 'Of Topics',
			'created_by' => 'Created By',
			'created_by_name' => 'Created By Name',
			'modified_by' => 'Modified By',
			'modified_by_name' => 'Modified By Name',
			'created_on' => 'Created On',
			'modified_on' => 'Modified On',
			'last_post_id' => 'Last Post',
			'last_topic_id' => 'Last Topic',
			'type' => 'Type',
			'parent_id' => 'Forum Category',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('of_posts',$this->of_posts);
		$criteria->compare('of_topics',$this->of_topics);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_by_name',$this->created_by_name,true);
		$criteria->compare('modified_by',$this->modified_by);
		$criteria->compare('modified_by_name',$this->modified_by_name,true);
		$criteria->compare('created_on',$this->created_on);
		$criteria->compare('modified_on',$this->modified_on);
		$criteria->compare('last_post_id',$this->last_post_id);
		$criteria->compare('last_topic_id',$this->last_topic_id);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public static function getCategoryList() {
		static $list = array();
		
		if( !$list ) {
			$cats = self::getCategories();
			foreach( $cats as $category) {
				$list[$category->id] = $category->name;
			}
		}
		
		return $list;
	}
	
	public static function getCategories() {
		static $list = array();
		if( !$list ) {
			$list = \Sforum::model()->findAll('parent_id=0');
		}
		
		return $list;
	}
	
	public function topics() {
		$dataProvider = new CActiveDataProvider('Stopic', array(
			'criteria'=>array(
				'condition' => "t.sforum_id=:sforum_id",
				'params' => array(
					':sforum_id' => $this->id,
				),
				'with' => array('forum'),
			),
			'pagination'=>array(
				'pageSize'=> Yii::app()->controller->module->topicsPerPage,
			),
		));
		
		$dataProvider->sort->defaultOrder='t.id ASC';
		
		return $dataProvider;
	}
}