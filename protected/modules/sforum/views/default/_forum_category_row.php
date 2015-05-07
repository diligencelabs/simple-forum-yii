<?php
//print_r($category); die;
?>
<div class="sforum-list-row">
	<div class="sforum-category-row">
	<table width="100%" border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td>
		<?php
		echo CHtml::encode($category->name);
		?>
		</td>
		<td width="150">&nbsp;
			<?php if(SforumUtils::isAdmin()): ?>
			<a href="<?=$this->createUrl('sforum/create', array('Sforum[parent_id]' => $category->id));?>">+ Add Forum</a>
			 | 
			<a href="<?=$this->createUrl('scategory/update', array('id' => $category->id));?>">Edit</a>
			<?php endif; ?>
		</td>
	<tr>
	</table>
	</div>
	<div class="sforum-forum-table-div">
	<?php
	
	if( $category->forums ) {
		$this->renderPartial('/default/_forum_forum_table', array(
			'forums' => $category->forums
		));
	}
	else {
		echo "no forums found.";
	}
	?>
	</div>
</div>