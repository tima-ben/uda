<?php
/* @var $this CategoryController */
/* @var $model Category */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'category-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'tag_category'); ?>
		<?php echo $form->textField($model,'tag_category',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'tag_category'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name_category'); ?>
		<?php echo $form->textField($model,'name_category',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'name_category'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->