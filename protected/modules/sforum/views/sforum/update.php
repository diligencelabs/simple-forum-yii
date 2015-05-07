<?php
/* @var $this SforumController */
/* @var $model Sforum */

$this->breadcrumbs=array(
	'Sforums'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Sforum', 'url'=>array('index')),
	array('label'=>'Create Sforum', 'url'=>array('create')),
	array('label'=>'View Sforum', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Sforum', 'url'=>array('admin')),
);
?>

<h1>Update: <?php echo CHtml::encode($model->name); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>