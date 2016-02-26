<?php
/* @var $this CategoryController */
/* @var $data Category */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_category')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_category), array('view', 'id'=>$data->id_category)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tag_category')); ?>:</b>
	<?php echo CHtml::encode($data->tag_category); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name_category')); ?>:</b>
	<?php echo CHtml::encode($data->name_category); ?>
	<br />


</div>