<?php
/* @var $this SforumController */
/* @var $model Sforum */
/* @var $form CActiveForm */
?>

<div class="reply-form">

	<h4>Post a Reply</h4>

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'scomment-form',
		'enableAjaxValidation'=>false,
	));
	echo $form->hiddenField($model, 'stopic_id', array());
	echo $form->hiddenField($model, 'sforum_id', array());
	?>
	
	<div class="row">
		<?php echo SforumUtils::textArea($model,'body',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'body'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->