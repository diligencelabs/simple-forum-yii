<?php
/* @var $this SforumController */
/* @var $model Sforum */

$this->breadcrumbs=array(
	'Sforums'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Sforum', 'url'=>array('index')),
	array('label'=>'Create Sforum', 'url'=>array('create')),
	array('label'=>'Update Sforum', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Sforum', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Sforum', 'url'=>array('admin')),
);
?>

<h1>View Sforum #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'description',
		'image',
		'status',
		'of_posts',
		'of_topics',
		'created_by',
		'created_by_name',
		'modified_by',
		'modified_by_name',
		'created_on',
		'modified_on',
		'last_post_id',
		'last_topic_id',
		'type',
	),
)); ?>
