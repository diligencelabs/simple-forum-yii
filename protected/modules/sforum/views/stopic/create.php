<?php
/* @var $this SforumController */
/* @var $model Sforum */

if( $model->sforum_id && $model->forum) {
	$this->breadcrumbs=array(
		'Forums'=>array('default/index'),
		$model->forum->name => array('sforum/view', 'id' => $model->sforum_id),
		'Create Topic',
	);
}
else {
	$this->breadcrumbs=array(
		'Forums'=>array('default/index'),
		'Create Topic',
	);
}

?>

<h1>Create Topic</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>