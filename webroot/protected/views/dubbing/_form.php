<?php
/* @var $this DubbingController */
/* @var $model Dubbing */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'dubbing-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'tag_produser'); ?>
		<?php echo $form->textField($model,'tag_produser',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'tag_produser'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_category'); ?>
		<?php echo $form->dropDownList($model,'id_category',Category::listForDropDown(),array('empty' => 'Select')); ?>
		<?php echo $form->error($model,'id_category'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date_dubbing'); ?>
		<?php echo $form->textField($model,'date_dubbing'); ?>
		<?php echo $form->error($model,'date_dubbing'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'money_art'); ?>
		<?php echo $form->textField($model,'money_art',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'money_art'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'money_direct'); ?>
		<?php echo $form->textField($model,'money_direct',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'money_direct'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time_direct'); ?>
		<?php echo $form->textField($model,'time_direct',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'time_direct'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time_direct_maison'); ?>
		<?php echo $form->textField($model,'time_direct_maison',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'time_direct_maison'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('name' => 'enter')); ?>
      <?php
      if($model->isNewRecord)
         echo CHtml::submitButton('Create & New',array('name' => 'next'));
      ?>

   </div>

<?php $this->endWidget(); ?>

</div><!-- form -->