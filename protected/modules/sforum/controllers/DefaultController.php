<?php

class DefaultController extends SforumbaseController
{
	public function actionIndex()
	{
		$categories = Sforum::model()->with('forums')->findAll(array(
			'condition' => 't.parent_id=0',
			'order' => 't.ordering',
		));
		
		$this->render('index', array(
			'categories' => $categories
		));
	}
}