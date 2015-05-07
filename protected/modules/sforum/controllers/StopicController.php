<?php

class StopicController extends SforumbaseController
{
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id);
		
		$post = $this->saveReply($model);
		
		$this->render('view',array(
			'model'=>$model,
			'post' => $post,
		));
		
		$model->of_views++;
		$model->save();
	}
	
	protected function saveReply($topic) {
		$post = new Spost();
		
		if( Yii::app()->user->isGuest )
		{
			if( $this->module->ananymousComments ) {
				$post = new CommentForm();
			}
			else {
				return $post;
			}
		}
		else {
		}
		
		$name = get_class($post);
		
		if(isset($_POST[$name]) && is_array($_POST[$name])) {
			
			SforumActiveRecord::populateFromPOST($post, array('id'));
			$post->stopic_id = $topic->id;
			$post->sforum_id = $topic->sforum_id;
			
			if( $post->validate() && $post->save() ) {
				if(isset($post->topic) && $post->topic)
					$page = ceil( $post->topic->of_replies / $this->module->commentsPerPage );
				else
					$page = ceil( $topic->of_replies / $this->module->commentsPerPage );
				$this->redirect($this->createUrl('view', array('id' => $topic->id, 'Spost_page' => $page)) . '#a-' . $post->id );
			}
		}
		
		return $post;
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$this->createModel('Stopic');
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$this->updateModel($id, 'Stopic');
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
		$dataProvider=new CActiveDataProvider('Stopic');
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
		return $this->loadModelGeneric('Stopic', $id);
	}
	
	public function actionComments() {
		
		$dataProvider=new CActiveDataProvider('Spost', array(
			'criteria'=>array(
				'with' => array('topic', 'forum'),
				'order' => "t.status ASC, t.id ASC",
			),
			'pagination'=>array(
				'pageSize'=> 100,
			),
		));
		
		$this->render('comments',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	public function actionApproveComments() {
		
		if( isset($_POST['Spost']) && isset($_POST['Spost']['id']) ) {
			$model = $this->loadModelGeneric('Spost', $_POST['Spost']['id']);
			
			if($model && $model->status == 0) {
				$model->status = 1;
				$model->save();
				
				Yii::app()->user->setFlash('success', "Comment has been approved");
			}
		}
		
		
		$this->redirect( $this->createUrl('comments', array()) );
	}
	
	public function actionDeleteComment() {
		
		if( isset($_POST['Spost']) && isset($_POST['Spost']['id']) ) {
			$model = $this->loadModelGeneric('Spost', $_POST['Spost']['id']);
			
			if($model) {
				$model->delete();
				Yii::app()->user->setFlash('success', "Comment has been deleted");
			}
		}
		
		
		$this->redirect( $this->createUrl('comments', array()) );
	}
	
	

}
