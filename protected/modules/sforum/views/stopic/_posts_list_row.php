<div id="a-<?=$data->id?>-div" class="forum-topic-reply-row <?php if($index%2 == 0) echo 'even-reply'; else echo 'odd-reply'; ?>">
	<h3 class="first"><a class="link" name="a-<?=$data->id?>">Re: <?php echo CHtml::encode($data->topic->name); ?></a></h3>
	<span class="author-info">
		<?php echo CHtml::encode($data->created_by_name); ?>, 
		<?php echo SforumUtils::displayDateTime($data->created_on); ?>
	</span>
	<p><?php echo SforumUtils::post($data->body); ?></p>
</div>