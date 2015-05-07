<?php
if($index == 0):
?>
<tr>
	<th width="70%">
	<?=Stopic::model()->getAttributeLabel('name')?>
	</th>
	<th>
	<?=Stopic::model()->getAttributeLabel('of_replies')?>
	</th>
	<th>
	<?=Stopic::model()->getAttributeLabel('of_views')?>
	</th>
</tr>
<?php
echo "<br/>";
endif;
?>
<tr>
	<td><a href="<?=$this->createUrl('stopic/view', array('id'=>$data->id))?>">
	<?=CHtml::encode($data->name)?>
	</a>
	<!-- <br/> -->
	<span class="author-info">
		by <?php echo CHtml::encode($data->created_by_name); ?>, 
		<?php echo SforumUtils::displayDateTime($data->created_on); ?>
	</span>
	</td>
	<td>
	<?=CHtml::encode($data->of_replies)?>
	</td>
	<td>
	<?=CHtml::encode($data->of_views)?>
	</td>
</tr>