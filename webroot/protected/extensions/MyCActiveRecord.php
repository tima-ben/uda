<?php

abstract class  MyCActiveRecord extends CActiveRecord
{
   const BOTTOM_NAME_UPDATE = 'update';
   const BOTTOM_NAME_CREATE = 'create';
   const BOTTOM_NAME_DELETE = 'delete';
   const BOTTOM_NAME_ADMIN = 'admin';
   const BOTTOM_NAME_LIST = 'list';
   const BOTTOM_NAME_INDEX = 'index';
   const BOTTOM_NAME_VIEW = 'view';
   const BOTTOM_NAME_SPACE = 'space';
   static $nameObject = '';

   /**
    * @param string $scenario
    * @throws CException
    */
   public function __construct($scenario = 'insert')
   {
      if (!defined('static::NAME_GRID_VIEW'))
      {
         throw new CException('Constant NAME_GRID_VIEW is not defined on subclass ' . get_class($this));
      }
      parent::__construct($scenario);
   }

   /**
    * @param  self $model
    * @param string $name
    * @param bool $grand
    * @return string
    */
   public static function getLink($model,$name,$grand=false)
   {
      if($grand)
      {
         $button_size=Controller::BUTTON_SIZE_24;
      }
      else
      {
         $button_size=Controller::$button_size;
      }
      $ret='';
      if(!empty($name))
      {
         if (self::BOTTOM_NAME_SPACE === $name)
         {
            $ret = '&nbsp';
         }
         elseif (empty($model))
         {
            switch($name)
            {
               case self::BOTTOM_NAME_CREATE:
                  $ret=CHtml::link(CHtml::image('/images/'.$button_size.'/document_new.gif'),
                     array('create'),
                     array('title' => 'Create new')
                  );
                  break;
               case self::BOTTOM_NAME_LIST:
                  $ret=CHtml::link(CHtml::image('/images/'.$button_size.'/table.gif'),
                     array('list'),
                     array('title' => 'List')
                  );
                  break;
               case self::BOTTOM_NAME_ADMIN:
                  $ret=CHtml::link(CHtml::image('/images/'.$button_size.'/table.gif'),
                     array('admin'),
                     array('title' => 'Manage')
                  );
                  break;
               case self::BOTTOM_NAME_INDEX:
                  $ret=CHtml::link(CHtml::image('/images/'.$button_size.'/document2.gif'),
                     array('index'),
                     array('title' => 'List')
                  );
                  break;
            }
         }
         elseif (is_string($model) and !empty($model))
         {
            switch($name)
            {
               case self::BOTTOM_NAME_CREATE:
                  $ret=CHtml::link(CHtml::image('/images/'.$button_size.'/document_new.gif'),
                     array($model::CONTROLLER_NAME . '/create'),
                     array('title' => 'Create new ' . $model)
                  );
                  break;
               case self::BOTTOM_NAME_ADMIN:
                  $ret=CHtml::link(CHtml::image('/images/'.$button_size.'/table.gif'),
                     array($model::CONTROLLER_NAME .'/admin'),
                     array('title' => 'Manage ' . $model)
                  );
                  break;
               case self::BOTTOM_NAME_LIST:
                  $ret=CHtml::link(CHtml::image('/images/'.$button_size.'/table.gif'),
                     array($model::CONTROLLER_NAME .'/list'),
                     array('title' => 'List ' . $model)
                  );
                  break;
               case self::BOTTOM_NAME_INDEX:
                  $ret=CHtml::link(CHtml::image('/images/'.$button_size.'/document2.gif'),
                     array($model::CONTROLLER_NAME .'/index'),
                     array('title' => 'List ' . $model)
                  );
                  break;
            }
         }
         elseif (is_object($model))
         {
            switch ($name)
            {
               case self::BOTTOM_NAME_CREATE:
                  $ret = CHtml::link(CHtml::image('/images/' . $button_size . '/document_new.gif'),
                     array('create'),
                     array('title' => 'Create new' . (empty($model::$nameObject) ? '' : (' ' . $model::$nameObject)))
                  );
                  break;
               case self::BOTTOM_NAME_ADMIN:
                  $ret = CHtml::link(CHtml::image('/images/' . $button_size . '/table.gif'),
                     array('admin'),
                     array('title' => 'Manage' . (empty($model::$nameObject) ? '' : (' ' . $model::$nameObject)))
                  );
                  break;
               case self::BOTTOM_NAME_INDEX:
                  $ret = CHtml::link(CHtml::image('/images/' . $button_size . '/document2.gif'),
                     array('index'),
                     array('title' => 'List' . (empty($model::$nameObject) ? '' : (' ' . $model::$nameObject)))
                  );
                  break;
               case self::BOTTOM_NAME_DELETE:
                  $ret=CHtml::link(CHtml::image('/images/'.$button_size.'/trash.gif'),
                     '#',
                     array(
                        'submit'=>array('delete','id'=>$model->getPrimaryKey()),
                        'confirm'=>'Are you sure you want to delete this item?',
                        'title' => 'Delete this' . (empty($model::$nameObject) ? '' : (' ' . $model::$nameObject)))
                  );
                  break;
               case self::BOTTOM_NAME_VIEW:
                  $ret=CHtml::link(CHtml::image('/images/'.$button_size.'/search.gif'),
                     array('view','id'=>$model->getPrimaryKey()),
                     array('title' => 'View this' . (empty($model::$nameObject) ? '' : (' ' . $model::$nameObject)))
                  );
                  break;
               case self::BOTTOM_NAME_UPDATE:
                  $ret=CHtml::link(CHtml::image('/images/'.$button_size.'/write2.gif'),
                     array('update','id'=>$model->getPrimaryKey()),
                     array('title' => 'Update this' . (empty($model::$nameObject) ? '' : (' ' . $model::$nameObject)))
                  );
                  break;
            }
         }
      }
      return $ret;
   }

   /**
    * @param string $name
    * @param bool $grand
    * @return array customized attribute labels (name=>label)
    */
   public function getLinkBottom($name,$grand=false)
   {
      return $this::getLink($this,$name,$grand);
   }

   /**
    * @return string
    * @throws CException
    */
   static public function getJSForSearch()
   {
      if (defined('static::NAME_GRID_VIEW'))
      {
         $script_code = "$('.search-button').click(function(){ $('.search-form').toggle(); return false; });
            $('#close-search').click(function(){ $('.search-form').hide(); return false;});
            $('.search-form form').submit(function(){	$.fn.yiiGridView.update('" . static::NAME_GRID_VIEW . "', { data: $(this).serialize()}); return false; });";
      }
      else
      {
         throw new CException('Constant NAME_GRID_VIEW is not defined on subclass' . __CLASS__);
      }
      return $script_code;
   }

}
