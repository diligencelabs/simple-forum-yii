<?php
/* @var $this SforumController */
/* @var $model Sforum */

$this->breadcrumbs=array(
	'Forums'=>array('default/index'),
	$model->name,
);

?>

<div class="forum-detail-view">
	
	<div class="forum-topic-view">
		<h1><?php echo CHtml::encode($model->name); ?></h1>
		<p><?php echo CHtml::encode($model->description); ?></p>
	</div>
	
	<div class="forum-topic-replies">
	<?php
	$this->renderPartial('_topic_list', array(
		'forum' => $model,
	));
	?>
	</div>
</div>

