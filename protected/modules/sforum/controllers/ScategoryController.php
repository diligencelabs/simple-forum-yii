<?php

class ScategoryController extends SforumbaseController
{
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$_REQUEST['Sforum']['ordering'] = mt_rand(1, 999);
		
		$this->createModel('Sforum', array(
			'beforeSave' => function($model) {
				$model->parent_id = 0;
			},
			'afterSave' => function($model) {
				$this->safeRedirect(array('default/index'));
			},
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$this->updateModel($id, 'Sforum', array(
			'afterSave' => function($model) {
				$this->safeRedirect(array('default/index'));
			},
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->performDelete($id);
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Sforum');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = $this->loadModelGeneric('Sforum', $id);
		$model->parent_id = 0;
		
		return $model;
	}

}
