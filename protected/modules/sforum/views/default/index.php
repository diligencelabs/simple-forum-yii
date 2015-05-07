<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	'Forums',
);


?>

<?php
foreach($categories as $category) {
	$this->renderPartial('/default/_forum_category_row', array(
		'category' => $category
	));
}
?>