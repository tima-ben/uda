<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
   const CLASS_NOT_PRINT = 'printWidgetScreenCover';
   const LAYOUTS_COLUMN_1 = 'column1';
   const LAYOUTS_COLUMN_2 = 'column2';
   const LAYOUTS_DEFAULT = self::LAYOUTS_COLUMN_1;
   const PAGE_20 = 20;
   const PAGE_25 = 25;
   const PAGE_30 = 30;
   const PAGE_ALL = 'all';
   const PAGE_DEFAULT = self::PAGE_20;
   const PAGE_STATE = 'page';
   /**
    * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	* meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	*/
   public $layout = self::LAYOUTS_DEFAULT;
   /**
	* @var array context menu items. This property will be assigned to {@link CMenu::items}.
	*/
   public $menu=array();
   /**
	* @var array the breadcrumbs of the current page. The value of this property will
	* be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	* for more details on how to specify this property.
	*/
   public $breadcrumbs=array();
   public $titleForAction='Index';
   public function init()
   {
      parent::init();
      Yii::app()->setLanguage('fr');
   }
}