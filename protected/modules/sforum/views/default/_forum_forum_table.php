<table class="sforum-forum-table" border=0 cellpadding=0 cellspacing=0 width="100%">
<?php
foreach( $forums as $forum) {
	$this->renderPartial('/default/_forum_forum_row', array(
		'forum' => $forum
	));
}
?>
</table>