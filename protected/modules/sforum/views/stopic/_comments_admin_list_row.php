<div id="a-<?=$data->id?>-div" class="forum-topic-reply-row <?php if($index%2 == 0) echo 'even-reply'; else echo 'odd-reply'; ?>">
	<h3 class="first"><a class="link" href="<?=$this->createUrl('stopic/view', array('id' => $data->topic->id));?>">Re: <?php echo CHtml::encode($data->topic->name); ?></a></h3>
	<span class="author-info">
		<?php echo CHtml::encode($data->created_by_name); ?>, 
		<?php echo SforumUtils::displayDateTime($data->created_on); ?>, 
		<?php echo CHtml::encode($data->ip); ?>
	</span>
	<p><?php echo SforumUtils::post($data->body); ?></p>
	
	<span style="">
		<?php 
		echo CHtml::beginForm($this->createUrl('approvecomments'), 'post', array('style' => 'margin:0;padding:0;display:inline !important;'));
		
		echo CHtml::activeHiddenField($data, 'id');
		
		$htmlOpts = array();
		if( $data->status==1 ) {
			$htmlOpts['disabled'] = 'disabled';
		}
		echo CHtml::submitButton(' '.($data->status==1?'Approved':'Approve') . ' ', $htmlOpts ); 
		
		echo CHtml::endForm();
		?>
	</span>
	
	<span style="">
		<?php 
		echo CHtml::beginForm($this->createUrl('deletecomment'), 'post', array('style' => 'margin:0;padding:0;display:inline !important;'));
		echo CHtml::activeHiddenField($data, 'id');
		
		$htmlOpts = array();
		echo CHtml::submitButton(' Delete ', $htmlOpts ); 
		
		echo CHtml::endForm();
		?>
	</span>
</div>