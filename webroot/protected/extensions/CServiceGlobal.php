<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: Nov 19, 2010
 * Time: 3:07:45 PM
 * To change this template use File | Settings | File Templates.
 */
class CServiceGlobal
{

   const SYMBOL_ENTOURAGE_TYPE_COMMENT = '|';
   const TYPE_COMMENT_CONFIRMED = 'confirmed';
   const TYPE_COMMENT_LINK_INFO = 'link info';
   const TYPE_COMMENT_UPDATE = 'update';
   const MAX_REPEAT_SOAP_READ = 3;
   const AMOUNT_TYPE_OTHER__LIST_FOR_IN = '(24)';
   const COMMAND_NAME_TEST = 'test';
   const COMMAND_NAME_GET_PAYMENT = 'get_payment';
   const COMMAND_NAME_CREATE_LINK_FOR_INCOME = 'create_link_income';
   const COMMAND_NAME_READ_ADVERTISER_DATA = 'read_advertiser_data';
   const COMMAND_NAME_READ_CUBE_TRANSACTION = 'read_cube_transaction';
   const COMMAND_NAME_READ_AFF_TRANSACTION = 'read_aff_transaction';
   const COMMAND_NAME_RECREATE_TABLE = 'recreatetable';
   const CSS_CLASS_FOR_ROW_SUM = 'summ';
   const CSS_CLASS_ATTENTION = 'attention';
   const CSS_CLASS_MY_BOLD = 'my-bold';
   const CSS_CLASS_SPY = 'spy';
   const DEFAULT_SYSTEM_DATA_FORMAT = 'Y-m-d';
   const PREFIX_FOR_TEMP_FILSE = 'tmd_download';
   const LENGTH_PASSWORD = 8;
   /** @var bool | string */
   static public $tempName = false;
   /** @var resource | bool */
   static public $tempFile = false;

   /**
    * @throws CException
    * create temp file
    */
   static function createTempFile()
   {
      self::$tempName = tempnam(Yii::app()->runtimePath, self::PREFIX_FOR_TEMP_FILSE);
      if (self::$tempName === false)
      {
         throw new CException('System can not create temp file');
      }
      self::$tempFile = @fopen(self::$tempName, 'w+');
      if (self::$tempFile === false)
      {
         throw new CException('System can not open file: ' . self::$tempName . ' to write mode.');
      }
   }

   /**
    * @param $buffer
    * @throws CException
    * write buffer to temp file
    */
   static function writeTempFile($buffer)
   {
      if (false === fwrite(self::$tempFile, $buffer))
      {
         throw new CException('System can not to write to file: ' . self::$tempName . '.');
      }
   }

   /**
    * @throws CException
    * to close temp file
    */
   static function closeTempFile()
   {
      if (false === fclose(self::$tempFile))
      {
         throw new CException('System can not to close file: ' . self::$tempName . '.');
      }
   }

   /**
    * @param $name
    * @throws CException
    * @throws CHttpException
    * send temp file like name to client browser.
    */
   static function sendTempFile($name)
   {
      if (file_exists(self::$tempName))
      {
         Yii::app()->getRequest()->sendFile($name, @file_get_contents(self::$tempName));
         self::unlinkTempFile();
      }
      else
      {
         throw new CException('System do not have file: ' . self::$tempName . ' for to send like ' . $name);
      }
   }

   /**
    * @throws CException
    * to delete temp file
    */
   static function unlinkTempFile()
   {
      if (unlink(self::$tempName))
      {
         self::$tempName = false;
      }
      else
      {
         throw new CException('System can not to delete file: ' . self::$tempName . '.');
      }
   }

   /**
    * @param array $target
    * @param int|string $source
    */
   static function addToArrayOnce($source, &$target)
   {
      if (!in_array($source, $target))
      {
         $target[] = $source;
      }
   }

   /**
    * @param int $index
    * @param CActiveRecord $record
    */
   static function writeObjectToTempFileJSON($index, $record)
   {
      $pre_object = array('line_from_file' => $index, 'type_line' => get_class($record)) + $record->getAttributes();
      $object = (object)$pre_object;
      self::writeTempFile(json_encode($object) . PHP_EOL);
   }
   /**
    * @param string $text
    * @param boolean $debug
    */
   static function echoIfDebug($text, $debug)
   {
      if ($debug)
      {
         echo $text . PHP_EOL;
      }
   }


   static function setBeginPrint()
   {
      echo '<div id="print_this_div"><!-- for printed -->';
   }

   /**
    * @return string
    */
   static function whoAndWhen()
   {
      return ' ' . date('Y-m-d H:i:s') . ' ' . Yii::app()->user->name;
   }

   static function typeCommentToString($type)
   {
      str_replace(self::SYMBOL_ENTOURAGE_TYPE_COMMENT, '', $type);
      return self::SYMBOL_ENTOURAGE_TYPE_COMMENT . $type . self::SYMBOL_ENTOURAGE_TYPE_COMMENT;
   }

   /**
    * @param string $type
    * @param string $source
    * @return string
    */
   static function deleteCommentFromDescription($type, $source)
   {
      $value_for_return = $source;
      $tmp = explode(self::typeCommentToString($type), $source);

      if (count($tmp) == 2)
      {
         $value_for_analise = $tmp[1];
         $value_for_return = $tmp[0];
         $tmp = explode(self::SYMBOL_ENTOURAGE_TYPE_COMMENT, $value_for_analise);
         if (count($tmp) > 1)
         {
            unset($tmp[0]);
            $value_for_return .= self::SYMBOL_ENTOURAGE_TYPE_COMMENT . implode(self::SYMBOL_ENTOURAGE_TYPE_COMMENT, $tmp);
         }
      }
      return $value_for_return;
   }

   static function setEndPrint()
   {
      echo Yii::app()->GetController()->widget('application.extensions.print.printWidget',
         array('cssFile' => 'print.css',
            'printedElement' => '#print_this_div',
         ),
         true
      );
      echo PHP_EOL . '</div><!-- for printed -->' . PHP_EOL;
   }

   /**
    * @static
    * @param Controller $view
    * @param string $name_dialog
    * @param string $width
    * @param bool $print
    */
   public static function addDialogView($view, $name_dialog = 'view_dialog', $width = '500px', $print = false)
   {
      $tmp = explode('_', $name_dialog);
      $css_class = $tmp[0];
      Yii::app()->getClientScript()->registerScript($name_dialog, '
            jQuery("body").delegate("a.' . $css_class . '","click",function(){
                window.last_url=jQuery(this).attr("href")
                jQuery.ajax({
                    "dataType":"html",
                    "type":"POST",
                    "cache":true,
                    "success":function(response){
                        $("#' . $name_dialog . '").html(response);
                        $("#' . $name_dialog . '").dialog("open");
                    },
                    "url":jQuery(this).attr("href"),
                });
                return false;
            });'
      );
      $buttons = array();
      if ($print)
      {
         $buttons[Yii::t('button', 'PRINT')] = 'js:function(){ win=window.open(window.last_url,"_blank");}';
      }
      $buttons[Yii::t('button', 'CLOSE')] = 'js:function(){ $(this).dialog("close"); }';
      $view->beginWidget('zii.widgets.jui.CJuiDialog',
         array(
            'id' => $name_dialog,
            'options' => array(
               'autoOpen' => 'js:false',
               'closeOnEscape' => 'js:true',
               'title' => Yii::t('campaign', 'More details'),
               'width' => $width,
               'modal' => 'js:true',
               'buttons' => $buttons,
            ),
            'htmlOptions' => array('style' => 'display:none', 'class' => Controller::CSS_CLASS_NO_PRINT),
         ), true);
      $view->endWidget();
   }


   public static function sendMail($users = 'admin', $subject = 'Test', $message = 'Test message')
   {
      /** @var $e_mail User */
      $a_to = array();
      if (!is_array($users))
      {
         $users = array($users);
      }
      foreach ($users as $user)
      {
         $e_mail = User::model()->findByAttributes(array('name_login' => $user));
         if (!is_null($e_mail))
         {
            $a_to[] = $e_mail->e_mail;
         }
      }
      $to = implode(', ', $a_to);
      $header = 'From: ' . Yii::app()->name . "<eduard@evonames.com>\n\r";
      mail($to, $subject, $message, $header);
   }

   static public function setStyleSum()
   {
      $style = '
        <style type="text/css">
           tr.' . self::CSS_CLASS_FOR_ROW_SUM . ' td {
               font-weight: bold;
               background: #C0C3EF;
           }
        </style>';
      echo $style;
   }

   static public function setStyleBold()
   {
      $style = '
        <style type="text/css">
           tr.' . self::CSS_CLASS_MY_BOLD . ' td {
               font-weight: bold ;
           }
        </style>';
      echo $style;
   }

   static public function setStyleAttention()
   {
      $style = '
        <style type="text/css">
           tr.' . CServiceGlobal::CSS_CLASS_ATTENTION . ' td {
               font-weight: bold ;
               background: yellow ;
           }
        </style>';
      echo $style;
   }

   static public function setSendToBaseAjax()
   {
      Yii::app()->getClientScript()->registerScript('send_to_db', '
            jQuery("body").delegate("a.send","click",function(){
                jQuery.ajax({
                    "dataType":"html",
                    "type":"POST",
                    "cache":true,
                    "success":function(response){
                        $("#my-flash").html(response);
                        $("#my-flash").removeClass("hide");
                        $("#my-flash").animate({opacity: 1.0}, 20000,function() {$("#my-flash").addClass("hide");});
                    },
                    "url":jQuery(this).attr("href"),
                });
                return false;
            });'
      );
   }

   /**
    * @param int|null $time
    * @return string
    */
   static public function getLastPayoutDate($time = null)
   {
      if (empty($time))
      {
         $time = time();
      }
      $last_payout = '';
      $time_last_month = strtotime('-1 month', $time);
      $last_payouts[] = strtotime(date('Y-m-01', $time_last_month));
      $last_payouts[] = strtotime(date('Y-m-15', $time_last_month));
      $last_payouts[] = strtotime(date('Y-m-01', $time));
      $last_payouts[] = strtotime(date('Y-m-15', $time));
      foreach ($last_payouts as $index => $last_payout_day)
      {
         switch (date('w', $last_payout_day))
         {
            case '0': //Sunday
               $last_payouts[$index] = strtotime(' +1 day', $last_payout_day);
               break;
            case '6': //Saturday
               $last_payouts[$index] = strtotime(' +2 days', $last_payout_day);
               break;
            default :
         }
      }
      foreach ($last_payouts as $last_payout_day)
      {
         if ($time >= $last_payout_day)
         {
            $last_payout = date(CServiceGlobal::DEFAULT_SYSTEM_DATA_FORMAT, $last_payout_day);
         }
         else
         {
            break;
         }
      }
      return $last_payout;
   }

   /**
    * @param int|null $time
    * @return string
    */
   static public function getNextPayoutDate($time = null)
   {
      if (empty($time))
      {
         $time = time();
      }
      $next_payout = '';
      $time_next_month = strtotime('1 month', $time);
      $last_payouts[] = strtotime(date('Y-m-01', $time));
      $last_payouts[] = strtotime(date('Y-m-15', $time));
      $last_payouts[] = strtotime(date('Y-m-01', $time_next_month));
      foreach ($last_payouts as $index => $next_payout_day)
      {
         switch (date('w', $next_payout_day))
         {
            case '0': //Sunday
               $last_payouts[$index] = strtotime(' +1 day', $next_payout_day);
               break;
            case '6': //Saturday
               $last_payouts[$index] = strtotime(' +2 days', $next_payout_day);
               break;
            default :
         }
      }
      foreach ($last_payouts as $last_payout_day)
      {
         if ($time < $last_payout_day)
         {
            $next_payout = date(CServiceGlobal::DEFAULT_SYSTEM_DATA_FORMAT, $last_payout_day);
            break;
         }
         else
         {
            continue;
         }
      }
      return $next_payout;
   }

   static public function GetDateStartEnd($date)
   {
      $period = array('start_date' => '', 'end_date' => '', 'next' => '');
      $current_date = time();
      $time = strtotime($date);
      if (strtotime('-18 months', $current_date) > $time)
      {
         $period['start_date'] = date('Y-m-', $time) . '01';
         $period['end_date'] = date('Y-m-d', strtotime('-1 day', strtotime('+ 1 month', strtotime($period['start_date']))));
         $period['next'] = date('Y-m-d', strtotime('+ 1 month', strtotime($period['start_date'])));
      }
      else
      {
         $period['start_date'] = $period['end_date'] = date('Y-m-d', $time);
         $period['next'] = date('Y-m-d', strtotime('+ 1 day', strtotime($period['start_date'])));
      }
      return $period;
   }

   /**
    * @static
    * @param $date
    * @param bool $string
    * @return int|string
    */
   static public function GetFirstDayMonth($date, $string = true)
   {
      $time = strtotime($date);
      if ($string)
      {
         $return_date = date('Y-m-01', $time);
      }
      else
      {
         $return_date = strtotime(date('Y-m-01', $time));
      }
      return $return_date;
   }

   /**
    * @static
    * @param string $date
    * @param bool $string if it = true function return string value if =false integer
    * @return int|string
    */
   static public function GetLastDayMonth($date, $string = true)
   {
      $time = strtotime($date);
      if ($string)
      {
         $return_date = date('Y-m-t', $time);
      }
      else
      {
         $return_date = strtotime(date('Y-m-t', $time));
      }
      return $return_date;
   }

   static public function checkJsonData($data)
   {
      $return = false;
      if ($data)
      {
         $data = json_decode($data);
         switch (json_last_error())
         {
            case JSON_ERROR_DEPTH:
               CServiceGlobal::showMessage('JSON data - Maximum stack depth exceeded', MyFlash::TYPE_MESSAGE_ERROR);
               break;
            case JSON_ERROR_CTRL_CHAR:
               CServiceGlobal::showMessage('JSON data - Unexpected control character found', MyFlash::TYPE_MESSAGE_ERROR);
               break;
            case JSON_ERROR_SYNTAX:
               CServiceGlobal::showMessage('JSON data - Syntax error, malformed JSON', MyFlash::TYPE_MESSAGE_ERROR);
               break;
            case JSON_ERROR_NONE:
               $return = $data;
               break;
            default :
               CServiceGlobal::showMessage('JSON data - unhandled error', MyFlash::TYPE_MESSAGE_ERROR);
         }
      }
      else
      {
         echo 'The result is NULL';
      }
      return $return;
   }

   static public function showMessage($message, $type = 'ok')
   {
      if (COMMAND)
      {
         echo strtoupper($type) . ': ' . $message . PHP_EOL;
      }
      else
      {
         Yii::app()->getUser()->setFlash($type, $message);
      }
   }

   static public function startCommand($name, $add_parameters = '')
   {
      $output = array();
      /** @var User $user */
      $user = User::model()->findByAttributes(array('name_login' => Yii::app()->user->id));
      $e_mail = (isset($user) and strlen($user->e_mail) > 0) ? $user->e_mail : Yii::app()->params['adminEmail'];
      switch ($name)
      {
         case self::COMMAND_NAME_TEST:
            exec('ls -al | mail -s " test command ls -al " ' . $e_mail . ' &', $output, $return_var);
            break;
         case self::COMMAND_NAME_GET_PAYMENT:
            exec('php -f command.php readpayment u=' . Yii::app()->user->getName() . ' >/dev/null &', $output, $return_var);
            break;
         case self::COMMAND_NAME_RECREATE_TABLE:
            exec('php -f command.php recreatetable u=' . Yii::app()->user->getName() . ' >/dev/null &', $output, $return_var);
            break;
         case self::COMMAND_NAME_CREATE_LINK_FOR_INCOME:
            exec('php -f command.php linkbankincome a c | mail -s "Link bank transaction " ' . $e_mail . ' >/dev/null &', $output, $return_var);
            break;
         case self::COMMAND_NAME_READ_ADVERTISER_DATA:
            exec('php -f command.php readsoapadvdata ' . $add_parameters, $output_local, $return_var);
            $output = $output_local;
            exec('php -f command.php createtransaction ' . $add_parameters, $output_local, $return_var);
            $output += $output_local;
            break;
         case self::COMMAND_NAME_READ_AFF_TRANSACTION:
            exec('php -f command.php readsoaptransaction ' . $add_parameters, $output_local, $return_var);
            $output = $output_local;
            break;
         case self::COMMAND_NAME_READ_CUBE_TRANSACTION:
            exec('php -f command.php readcubetransaction ' . $add_parameters, $output_local, $return_var);
            $output = $output_local;
            break;
         default:
            self::showMessage('not is default command', 'notice');
            break;
      }
      return print_r($output, true);
   }

   static public function SetEditAjax($name_update, $class = 'edit')
   {
      Yii::app()->getClientScript()->registerScript('send_to_db', '
           jQuery("body").delegate("a.' . $class . '","click",function(){
               w=window.open(jQuery(this).attr("href"),"_blank","location=no,menubar=no,fullscreen=no,height="+screen.height*0.9+",width="+screen.width*0.9);
               window.update_after_close=function(){ $.fn.yiiGridView.update("' . $name_update . '");};
               return false;
           });'
      );
   }

   static public function SetCloseWindow()
   {
      Yii::app()->getClientScript()->registerScript('close_window',
         'window.onunload=function(){ window.opener.focus(); window.opener.update_after_close();};'
      );
   }

   static public function compareMoneyForFilter($var, $multiply = 100)
   {
      $single = substr($var, 0, 2);
      $ret = null;
      switch ($single)
      {
         case '<>':
         case '>=':
         case '<=':
            $ret = (int)(substr($var, 2) * $multiply);
            break;
         default:
            break;
      }
      if (is_null($ret))
      {
         $single = substr($var, 0, 1);
         switch ($single)
         {
            case '=':
            case '>':
            case '<':
               $ret = (int)(substr($var, 1) * $multiply);
               break;
            default:
               break;
         }
      }
      if (is_null($ret))
      {
         $single = '';
         $ret = (int)($var * $multiply);
      }
      return $single . $ret;
   }

   static public function listOfYear()
   {
      $res = array();
      for ($i = 2006; $i <= date('Y'); $i++)
      {
         $res[$i] = $i;
      }
      return $res;
   }

   static public function arrayToUrl($params, $value_delimiter = '=', $param_delimiter = '&')
   {
      $return_value = '';
      $add_to_url = array();
      if (!empty($params))
      {
         foreach ($params as $key => $value)
         {
            $add_to_url[] = urlencode($key) . $value_delimiter . urlencode($value);
         }
         $return_value = implode($param_delimiter, $add_to_url);
      }
      return $return_value;
   }

   /**
    * @param string|array|CDbCriteria $condition
    * @return CDbCriteria
    */
   static public function checkAndCreateCondition($condition)
   {
      if (empty($condition))
      {
         $criteria = new CDbCriteria();
      }
      elseif (is_string($condition))
      {
         $criteria = new CDbCriteria(array('condition' => $condition));
      }
      elseif (is_array($condition))
      {
         $criteria = new CDbCriteria($condition);
      }
      elseif (is_object($condition) and get_class($condition) == 'CDbCriteria')
      {
         $criteria = $condition;
      }
      else
      {
         CServiceGlobal::showMessage('Type parameter \'condition\' is ' . gettype($condition) . '.', MyFlash::TYPE_MESSAGE_NOTICE);
         $criteria = new CDbCriteria();
      }
      return $criteria;
   }

   /**
    * @param int $x
    * @param int $max
    * @return int
    */
   static public function functionMaxMin($x, $max = 2)
   {
      $min = 0;
      $x = (int)$x;
      $max = (int)$max;
      return min(max($min, $x), $max);
   }

   /**
    * @param string $class_name
    * @param  null|string|Object $model
    * @param array $list_bottoms
    * @param bool $grand
    * @return string
    */
   static public function linkForTitle($class_name, $model, $list_bottoms, $grand = Controller::BUTTON_GRAND)
   {

      $ret_string = '';
      if (class_exists($class_name) and method_exists($class_name, 'getLink'))
      {
         foreach ($list_bottoms as $bottom)
         {
            $ret_string .= $class_name::getLink($model, $bottom, $grand);
         }
      }
      return $ret_string;
   }

   /**
    * @return string
    */
   static public function getButtonsForSearch()
   {
      $script_code = CHtml::submitButton('Search') . ' ' . CHtml::button('Close search', array('id' => 'close-search'));
      return $script_code;
   }

   static function randomPassword()
   {
      $alphabet = 'abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789';
      $pass = array(); //remember to declare $pass as an array
      $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
      for ($i = 0; $i < self::LENGTH_PASSWORD; $i++)
      {
         $n = rand(0, $alphaLength);
         $pass[] = $alphabet[$n];
      }
      return implode($pass); //turn the array into a string
   }

}