<?php
/* @var $this DubbingController */
/* @var $model Dubbing */
/* @var $dataProvider CDataProvider */
/* @var $columns array */

$this->breadcrumbs=array(
	Yii::t('mytr','Dubbing')=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>Yii::t('mytr','List Dubbing'), 'url'=>array('index')),
	array('label'=>Yii::t('mytr','Create Dubbing'), 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#dubbing-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
CServiceGlobal::setBeginPrint();
?>

<h1><?php echo $this->titleForAction ?></h1>
<?php
if($this->getAction()->id === 'report'){
   CServiceGlobal::setStyleSum();
	if (empty($_REQUEST['type']))
	{
		echo CHtml::link('(Catégorie, date)', $this->createUrl('/dubbing/report', array('type' => 'category'))) . '&nbsp;';
		echo CHtml::link('(Comparaison)', $this->createUrl('/dubbing/report', array('type' => 'category','comparison'=>'yes')));
	}
	else
	{
		echo CHtml::link('(Date, catégorie)', $this->createUrl('/dubbing/report')) . '&nbsp;';
		echo CHtml::link('(Comparaison)', $this->createUrl('/dubbing/report',array('comparison'=>'yes')));
	}
	if(Yii::app()->user->getState(Controller::PAGE_STATE) == Controller::PAGE_ALL)
	{
		echo CHtml::link('(Par page)', $this->createUrl('/dubbing/report',array(Controller::PAGE_STATE=>Controller::PAGE_DEFAULT)));
	}
	else
	{
		echo CHtml::link('(Sans page)', $this->createUrl('/dubbing/report', array(Controller::PAGE_STATE => Controller::PAGE_ALL)));
	}
}
?>
<p <?php echo 'class=' . Controller::CLASS_NOT_PRINT; ?>>You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'dubbing-grid',
	'dataProvider' => $dataProvider,
	'filter' => $model,
	'columns' => $columns,
   'formatter' => new MyCFormatter(),
   'rowCssClassExpression' => 'CServiceGlobal::getCssClassForRow($row,$data,$this)'
));
CServiceGlobal::setEndPrint()
?>
