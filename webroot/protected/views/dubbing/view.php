<?php
/* @var $this DubbingController */
/* @var $model Dubbing */

$this->breadcrumbs=array(
	'Dubbings'=>array('index'),
	$model->id_dubbing,
);

$this->menu=array(
	array('label'=>'List Dubbing', 'url'=>array('index')),
	array('label'=>'Create Dubbing', 'url'=>array('create')),
	array('label'=>'Update Dubbing', 'url'=>array('update', 'id'=>$model->id_dubbing)),
	array('label'=>'Delete Dubbing', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_dubbing),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Dubbing', 'url'=>array('admin')),
);
?>

<h1>View Dubbing #<?php echo $model->id_dubbing; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_dubbing',
		'id_category',
		'date_dubbing',
		'money_art',
		'money_direct',
		'time_direct',
		'time_direct_maison',
	),
)); ?>
