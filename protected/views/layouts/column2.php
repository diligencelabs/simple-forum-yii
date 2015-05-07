<?php $this->beginContent('/layouts/main'); ?>
<div class="container">
	<div class="span-18">
		<div id="content">
			<?php
			Yii::import('sforum.components.SforumUtils');
			echo SforumUtils::showFlash();
			?>
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
	<div class="span-6 last">
		<div id="sidebar">
			<?php if(!Yii::app()->user->isGuest) $this->widget('UserMenu'); ?>
		</div><!-- sidebar -->
	</div>
</div>
<?php $this->endContent(); ?>