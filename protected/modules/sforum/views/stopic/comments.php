<h1>Approve Comments</h1>

<?php

$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_comments_admin_list_row',
	'emptyText' => '<p>&nbsp;</p>',
	'summaryText' => 'Displaying {start} - {end} of {count} comments',
    'sortableAttributes'=>array(
    ),
));

?>