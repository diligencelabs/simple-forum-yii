<?php
/* @var $this SforumController */
/* @var $model Sforum */
/* @var $form CActiveForm */
?>
<div class="reply-form" style="padding: 5px;">

	<h4>Post a Reply</h4>

	<?php 
	$form=$this->beginWidget('CActiveForm', array(
		'id'=>'comment-form',
		'enableClientValidation'=>false,
		'clientOptions'=>array(
			'validateOnSubmit'=>false,
		),
	));
	?>
	<?php //echo $form->errorSummary($model); ?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
	</div>
	<div class="row">
		<?php echo $form->textField($model,'name', array('size' => 40, 'maxlength' => '100')); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
	</div>
	<div class="row">
		<?php echo $form->textField($model,'email', array('size' => 40, 'maxlength' => '100')); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'body'); ?>
	</div>
	<div class="row">
		<?php echo SforumUtils::textArea($model,'body',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'body'); ?>
	</div>

	<?php if(CCaptcha::checkRequirements()): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha'); ?>
		<?php echo $form->textField($model,'verifyCode'); ?>
		</div>
		<div class="hint">Please enter the letters as they are shown in the image above.
		( Letters are not case-sensitive )</div>
		<?php echo $form->error($model,'verifyCode'); ?>
	</div>
	<?php endif; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
