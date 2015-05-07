<?php
/* @var $this SforumController */
/* @var $model Sforum */

$this->breadcrumbs=array(
	'Sforums'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Sforum', 'url'=>array('index')),
	array('label'=>'Create Sforum', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('sforum-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Sforums</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'sforum-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		'description',
		'image',
		'status',
		'of_posts',
		/*
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
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
