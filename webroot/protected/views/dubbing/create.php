<?php
/* @var $this DubbingController */
/* @var $model Dubbing */

$this->breadcrumbs=array(
	'Dubbings'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Dubbing', 'url'=>array('index')),
	array('label'=>'Manage Dubbing', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->titleForAction; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>