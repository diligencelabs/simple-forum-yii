<?php
/* @var $this SforumController */
/* @var $model Sforum */

$this->breadcrumbs=array(
	'Forums'=>array('default/index'),
	$model->name,
);

?>
<?php
/* @var $this SforumController */
/* @var $model Sforum */

if( $model->sforum_id && $model->forum) {
	$this->breadcrumbs=array(
		'Forums'=>array('default/index'),
		$model->forum->name => array('sforum/view', 'id' => $model->sforum_id),
		$model->name,
	);
}
else {
	$this->breadcrumbs=array(
		'Forums'=>array('default/index'),
		$model->name,
	);
}

?>

<div class="forum-detail-view">
	
	<div class="forum-topic-view">
		<h1><?php echo CHtml::encode($model->name); ?></h1>
	</div>
	
	<div class="forum-topic-replies">
		<div class="forum-topic-reply-row odd-reply">
			<h3 class="first"><a class="link" href="#a-0"><?php echo CHtml::encode($model->name); ?></a></h3>
			<span class="author-info">
				<?php echo CHtml::encode($model->created_by_name); ?>, 
				<?php echo SforumUtils::displayDateTime($model->created_on); ?>
			</span>
			<p><?php echo SforumUtils::post($model->description); ?></p>
		</div>
		<?php
		$this->renderPartial('_posts_list', array(
			'topic' => $model,
		));
		?>
	</div>
	
	<div class="forum-topic-reply-form">
		<?php
		if( Yii::app()->user->isGuest )
		{
			if( $this->module->ananymousComments ) {
				$this->renderPartial('_reply_form_ananymous', array(
					'topic' => $model,
					'model' => $post,
				));
			}
		}
		else
		{
			$this->renderPartial('_reply_form', array(
				'topic' => $model,
				'model' => $post,
			));
		}
			
		?>
	</div>
</div>
<script>
if(window.location.hash.indexOf('#a-') != -1) {
	 $(window.location.hash + '-div').effect("highlight", {}, 5000);
}
</script>