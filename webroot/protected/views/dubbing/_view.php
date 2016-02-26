<?php
/* @var $this DubbingController */
/* @var $data Dubbing */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_dubbing')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_dubbing), array('view', 'id'=>$data->id_dubbing)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tag_produser')); ?>:</b>
	<?php echo CHtml::encode($data->tag_produser); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_category')); ?>:</b>
	<?php echo CHtml::encode($data->id_category); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_dubbing')); ?>:</b>
	<?php echo CHtml::encode($data->date_dubbing); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('money_art')); ?>:</b>
	<?php echo CHtml::encode($data->money_art); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('money_direct')); ?>:</b>
	<?php echo CHtml::encode($data->money_direct); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_direct')); ?>:</b>
	<?php echo CHtml::encode($data->time_direct); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_direct_maison')); ?>:</b>
	<?php echo CHtml::encode($data->time_direct_maison); ?>
	<br />


</div>