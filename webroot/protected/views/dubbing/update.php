<?php
/* @var $this DubbingController */
/* @var $model Dubbing */

$this->breadcrumbs=array(
	'Dubbings'=>array('index'),
	$model->id_dubbing=>array('view','id'=>$model->id_dubbing),
	'Update',
);

$this->menu=array(
	array('label'=>'List Dubbing', 'url'=>array('index')),
	array('label'=>'Create Dubbing', 'url'=>array('create')),
	array('label'=>'View Dubbing', 'url'=>array('view', 'id'=>$model->id_dubbing)),
	array('label'=>'Manage Dubbing', 'url'=>array('admin')),
);
?>

<h1><?php echo $this->titleForAction . ' ' .$model->id_dubbing; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>