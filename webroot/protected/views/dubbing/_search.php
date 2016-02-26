<?php
/* @var $this DubbingController */
/* @var $model Dubbing */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id_dubbing'); ?>
		<?php echo $form->textField($model,'id_dubbing'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tag_produser'); ?>
		<?php echo $form->textField($model,'tag_produser'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_category'); ?>
		<?php echo $form->dropDownList($model,'id_category',Category::listForDropDown(),array('empty' => 'Select')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_dubbing'); ?>
		<?php echo $form->textField($model,'date_dubbing'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'money_art'); ?>
		<?php echo $form->textField($model,'money_art',array('size'=>12,'maxlength'=>12)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'money_direct'); ?>
		<?php echo $form->textField($model,'money_direct',array('size'=>12,'maxlength'=>12)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'time_direct'); ?>
		<?php echo $form->textField($model,'time_direct',array('size'=>12,'maxlength'=>12)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'time_direct_maison'); ?>
		<?php echo $form->textField($model,'time_direct_maison',array('size'=>12,'maxlength'=>12)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->