<?php

class SforumActiveRecord extends CActiveRecord {
	private $_old = null;
	
	protected function nonRequestFields() {
		return array();
	}
	
	public final function excludesFromRequest() {
	
		return array_merge($this->nonRequestFields(), array(
				'created_by' => 1,
				'created_by_name' => 1,
				'modified_by' => 1,
				'modified_by_name' => 1,
				'created_on' => 1,
				'modified_on' => 1,
			)
		);
	}
	
	public function getOld() {
		if($this->_old == null && isset($this->id) && $this->id ) {
			$this->_old = CActiveRecord::model(get_class($this))->findByPk($this->id);
		}
		
		return $this->_old;
	}
	
	/**
	 * @overridden
	 *
	 * This method is invoked before saving a record (after validation, if any). 
	 * if it returns false, then normal save() process will be stopped.
	 *
	 * @return bool whether the saving should be executed.
	 */
	protected function beforeSave()
	{
		if( $this->id !== null && !$this->id )
			unset($this->id);
		
		#run raw DML statements and still have auditing details
		if( $this->getIsNewRecord() ) {
			$this->created_on = \SforumUtils::time();
			
			if( \Yii::app()->hasComponent('user') ) {
				if(\Yii::app()->user->getId() !== null ) {
					$this->created_by = \Yii::app()->user->getId();
				}
				
				if(\Yii::app()->user->getName() !== null && !$this->created_by_name ) {
					$this->created_by_name = \Yii::app()->user->getName();
				}
			}
		}
		
		$this->modified_on = \SforumUtils::time();
		
		if( \Yii::app()->hasComponent('user') ) {
			if(\Yii::app()->user->getId() !== null ) {
				$this->modified_by = \Yii::app()->user->getId();
			}
			
			if(\Yii::app()->user->getName() !== null && !$this->modified_by_name ) {
				$this->modified_by_name = \Yii::app()->user->getName();
			}
		}
		
		$this->nullifyEmptyFields();
		
		return parent::beforeSave();
	}
	
	protected function nullifyEmptyFields()
	{
		foreach( $this->getTableSchema()->columns as $column) {
			if( $column->dbType == 'date' || $column->dbType == 'datetime' || $column->dbType == 'time' || $column->dbType == 'timestamp'|| $column->dbType == 'float' || $column->dbType == 'double' || $column->dbType == 'decimal' || $column->dbType == 'numeric' )
			{
				if( isset( $this->{$column->name} ) || strlen($this->{$column->name}) == 0 ) {
					unset( $this->{$column->name} );
				}
			}
		}
	}
	
	/**
	*  populate From POST
	*/
	public static function populateFromPOST( $model, $exclude=array() ) {
		$name = get_class($model);
		return self::requestPopulate($model, isset($_POST[$name])?$_POST[$name]:array(), $exclude);
	}
	
	/**
	*  populate From REQUEST
	*/
	public static function populateFromRequest( $model, $exclude=array() ) {
		$name = get_class($model);
		return self::requestPopulate($model, isset($_REQUEST[$name])?$_REQUEST[$name]:array(), $exclude);
	}
	
	/**
	* Populate  model from Request, and it excludes some fields.
	* you can use $model->attributes = $_POST['ModelName'],  but this won't work
	* if you set values, field by field, something like .... $model->created_by = $_POST['ModelName']['created_by']
	*
	* @param $model CModel
	* @param $request Array of values $_POST['ModelName']
	* @param $exclude Array of fields that should be excluded from the population
	* @return  model
	*/
	protected static function requestPopulate( $model, $request, $exclude=array() ) {
		if( !is_array($request) || !$request )
			return $model;
		
		$excludes = array();
		if( $exclude )
			$excludes = array_combine($exclude, $exclude);
		
		$excludes = array_merge( $model->excludesFromRequest(), $excludes );
		
		foreach( $request as $key => $value ) {
			if( isset($excludes[$key]) )
				continue;
			$model->$key = $value;
		}
		
		return $model;
	}
}