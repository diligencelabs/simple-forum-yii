<?php
/* @var $this SforumController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Sforums',
);

$this->menu=array(
	array('label'=>'Create Sforum', 'url'=>array('create')),
	array('label'=>'Manage Sforum', 'url'=>array('admin')),
);
?>

<h1>Sforums</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
