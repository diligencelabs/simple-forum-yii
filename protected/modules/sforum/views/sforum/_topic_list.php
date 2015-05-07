<div class="forum-view-buttons">
	<input type="button" value=" Create Topic " onclick="window.location='<?=$this->createUrl('/sforum/stopic/create', array('Stopic[sforum_id]' => $forum->id))?>';"/> 
	<!--
	<input type="button" value=" Create Poll " onclick="window.location='<?=$this->createUrl('/sforum/stopic/createpoll', array('Stopic[sforum_id]' => $forum->id))?>';"/>
	-->
	
	<input type="button" value=" Edit Forum " onclick="window.location='<?=$this->createUrl('/sforum/sforum/update', array('id' => $forum->id))?>';"/> 
	
	<?php if( SforumUtils::isAdmin() ): ?>
	<?php
	$url = $this->createUrl('sforum/delete', array('id' => $forum->id));
	echo SforumUtils::confirmDelete($url, $forum->name, $forum->id, $this->createUrl('/sforum/default/index'), 'Delete Forum');
	?>
	<?php endif; ?>
</div>

<div class="forum-topic-replies-div">
<?php
$dataProvider = $forum->topics();

$this->widget('zii.widgets.CListView', array(
	'itemsTagName' => 'table',
	'itemsCssClass' => 'forum-topic-replies-table',
    'dataProvider'=>$dataProvider,
    'itemView'=>'_topic_list_row',
	'emptyText' => '<p>&nbsp;</p>',
	'summaryText' => 'Displaying {start} - {end} of {count} topics',
    'sortableAttributes'=>array(
		'name', 'of_replies', 'of_views'
    ),
));
?>
</div>