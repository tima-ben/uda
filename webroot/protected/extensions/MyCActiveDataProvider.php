<?php
/**
 * CActiveDataProvider implements a data provider based on ActiveRecord.
 *
 * CActiveDataProvider provides data in terms of ActiveRecord objects which are
 * of class {@link modelClass}. It uses the AR {@link CActiveRecord::findAll} method
 * to retrieve the data from database. The {@link criteria} property can be used to
 * specify various query options, such as conditions, sorting, pagination, etc.
 *
 * CActiveDataProvider may be used in the following way:
 * <pre>
 * $dataProvider=new CActiveDataProvider('Post', array(
 *     'criteria'=>array(
 *         'condition'=>'status=1 AND tags LIKE :tags',
 *         'params'=>array(':tags'=>$_GET['tags']),
 *         'with'=>array('author'),
 *     ),
 *     'pagination'=>array(
 *         'pageSize'=>20,
 *     ),
 * ));
 * // $dataProvider->getData() will return a list of Post objects
 * </pre>
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CActiveDataProvider.php 1656 2010-01-03 14:20:04Z qiang.xue $
 * @package system.web
 * @since 1.1
 */
class  MyCActiveDataProvider extends CActiveDataProvider
{
	protected function calculateTotalItemCount()
	{
        //return CActiveRecord::model($this->modelClass)->count($this->getCriteria()); original
        return count(CActiveRecord::model($this->modelClass)->findAll($this->getCriteria())); //my corected
	}
}
