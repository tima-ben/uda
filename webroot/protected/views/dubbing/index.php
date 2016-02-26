<?php
/* @var $this DubbingController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Dubbings',
);

$this->menu=array(
	array('label'=>'Create Dubbing', 'url'=>array('create')),
	array('label'=>'Manage Dubbing', 'url'=>array('admin')),
);
?>

<h1>Dubbings</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
