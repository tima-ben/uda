<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MyCsvReader
 *
 * @author Administrator
 */
Class MyJsonReader implements Iterator
{
   /**
    * @var int current number for Iterator
    */
   private $position;
   /**
    * @var string url address or file name
    */
   private $source;
   /**
    * @var string
    */
   private $type;
   /**
    * @var string $_str
    */
   private $_str;
   /**
    * @var string[] $_data
    */
   private $_data;
   /**
    * @var boolean
    */
   private $withLineMd5 = false;

   /**
    * @var string[] $_names
    */
   private $_names=array('json data always without names');
   /**
    * @var bool $withNames
    */
   private $withNames;
   /**
    * @var int $countNames
    */
   private $countNames;
   /**
    * @var bool $sourceWithNames
    */
   private $sourceWithNames=false;
   /**
    * @var int $startNames
    */
   private $startNames;
   /**
    * @var int $startData
    */
   private $startData;
   /**
    * @var int $longData
    */
   private $longData = 0;
   /**
    * @var int $endOffData
    */
   private $endOffData = 0;

   public $delimiter = ',';
   public $enclosure = '"';
   public $escape = '\\';

   /**
    * Constructor
    * @param bool $source_with_names default is 'true'
    * @param string $data_str
    * @param string $delimiter
    * @param string $enclosure
    * @param string $escape
    * @return self
    */
   public function __construct($source_with_names = false, $data_str = null, $delimiter = ',', $enclosure = '"', $escape = '\\')
   {
      $this->delimiter = $delimiter;
      $this->enclosure = $enclosure;
      $this->escape = $escape;
      $this->position = 0;
      if($this->sourceWithNames !== false)
      {
         $this->sourceWithNames = $source_with_names;
      }
      if ($this->sourceWithNames)
      {
         $this->startNames = 1;
         $this->startData = 2;
      }
      else
      {
         $this->startNames = 0;
         $this->startData = 1;
      }
      if (!is_null($data_str))
      {
         $this->_str = $data_str;
         $this->LoadSource();
      }
   }

   function MD5()
   {
      return md5($this->_str);
   }

   function CheckMD5($md5)
   {
      if ($this->MD5() === strtolower($md5))
      {
         return true;
      }
      else
      {
         return false;
      }
   }

   public function CheckIsEmpty()
   {
      if (is_array($this->_data) and count($this->_data) > 0)
      {
         return false;
      }
      else
      {
         return true;
      }
   }

   public function GetSource()
   {
      if (!is_null($this->_str))
      {
         return $this->_str;
      }
      else
      {
         return '';
      }
   }

   function LoadSource()
   {
      $data = explode(PHP_EOL, $this->_str);
      $current_line = 1;
      if ($this->sourceWithNames)
      {
         for ($i = 1; $i <= $this->startNames; $i++)
         {
            $name = array_shift($data);
            $current_line++;
            if ($this->startNames === $i)
            {
               $this->SetNames(str_getcsv($name, $this->delimiter, $this->enclosure, $this->escape));
               break;
            }
         }
      }
      for ($i = $current_line; $i <= $this->startData; $i++)
      {
         if ($i !== $this->startData)
         {
            $tmp = array_shift($data);
         }
      }
      if ($this->endOffData !== 0 or $this->longData !== 0)
      {
         if ($this->endOffData !== 0)
         {
            $delete_from_end = $this->endOffData;
         }
         else
         {
            $delete_from_end = count($data) - $this->longData;
         }
      }
      else
      {
         $delete_from_end = 0;
      }
      for ($i = 0; $i < $delete_from_end; $i++)
      {
         $tmp = array_pop($data);
      }
      $this->_data = $data;
   }

   function GetData()
   {
      if (is_null($this->_data))
      {
         $this->LoadSource();
      }
      return $this->_data;
   }

   function GetNames()
   {
      $a_ret = $this->_names;
      if ($this->withLineMd5)
      {
         $a_ret[] = 'md5';
      }

      return $a_ret;
   }

   function CleanEmpty()
   {
      $change = false;
      foreach ($this->_data as $key => $str)
      {
         $change = false;
         if ('' == trim($str))
         {
            unset($this->_data[$key]);
            $change = true;
         }
         else
         {
            if ($this->withNames)
            {
               if (count(str_getcsv($str, $this->delimiter, $this->enclosure, $this->escape)) !== $this->countNames)
               {
                  unset($this->_data[$key]);
                  $change = true;
               }
            }
         }
      }
      if ($change)
      {
         $this->_data = array_merge($this->_data);
      }
   }

   function SetSource($url, $filename = NULL)
   {
      if (is_null($filename))
      {
         $this->source = $url;
         $this->type = 'url';
      }
      else
      {
         if (!is_null($filename))
         {
            $this->source = $filename;
            $this->type = 'file';
         }
         else
         {
            $this->source = $url;
            $this->type = 'url';
         }
      }
      $this->_str = file_get_contents($this->source);
      if ($this->_str)
      {
         if ('url' === $this->type and !is_null($filename))
         {
            file_put_contents($filename, $this->_str);
         }
         return true;
      }
      else
      {
         return false;
      }
   }

   public function SetNames($names)
   {
      if (is_array($names))
      {
         $this->withNames = true;
         $this->_names = $names;
         $this->countNames = count($this->_names);
         return true;
      }
      else
      {
         if (is_string($names))
         {
            $names = explode($this->delimiter, $names);
            $this->withNames = true;
            $this->_names = $names;
            $this->countNames = count($this->_names);
            return true;
         }
         else
         {
            return false;
         }
      }
   }

   public function SetLineMD5($value = true)
   {
      $this->withLineMd5 = $value;
   }

   public function SetEndOffData($count)
   {
      $this->endOffData = $count;
      $this->longData = 0;
   }

   public function SetLongData($count)
   {
      $this->longData = $count;
      $this->endOffData = 0;
   }

   public function current()
   {
      // TODO: Implement current() method.
      $value = str_getcsv($this->_data[$this->position], $this->delimiter, $this->enclosure, $this->escape);
      if ($this->withNames)
      {
         if (count($value) === $this->countNames)
         {
            $value = array_combine($this->_names, $value);
         }
      }
      if ($this->withLineMd5)
      {
         $value['md5'] = md5($this->_data[$this->position]);
      }
      return $value;
   }

   /**
    * @return int|mixed
    */
   public function key()
   {
      // TODO: Implement key() method.
      return $this->position;
   }

   /**
    *
    */
   public function next()
   {
      // TODO: Implement next() method.
      $this->position++;
   }

   /**
    *
    */
   public function rewind()
   {
      // TODO: Implement rewind() method.
      $this->position = 0;
   }

   /**
    * @return bool
    */
   public function valid()
   {
      // TODO: Implement valid() method.
      return isset($this->_data[$this->position]);
   }

   /**
    * @return int
    */
   public function count()
   {
      return count($this->_data);
   }
}

?>
