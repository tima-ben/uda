<?php
/**
 * Description of BenMapArray class
 * @version 0.1 form 20 may 2010
 * @package classes.ben
 * @author Eduard Balantsev
 * @copyright &copy; Eduard Balantsev 2010 classes.ben
 */
class BenMapArray
{
    /**
     *
     * @var array $_map
     * @var string $_element_separator it is default value for separate off thes elements
     * @var string $_key_separator it is default value for separate of keys in the element
     */
    private $_map               = array();
    private $_element_separator = ',';
    private $_key_separator     = '=';
    private $_source            = array();
    /**
     *
     * @param string|null $map string like 't_key1=s_key1,t_key2=skey2,...'
     * @param string|null $element_separator
     * @param string|null $key_separator
     */
    public function  __construct($map=null,$element_separator=null,$key_separator=null)
    {
        if(!is_null($map))
        {
            $this->init($map,$element_separator,$key_separator);
        }
    }
    /**
     *
     * @param string $map string like 't_key1=s_key1,t_key2=skey2,...'
     * @param string|null $element_separator
     * @param string|null $key_separator
     */
    protected function  init($map,$element_separator=null,$key_separator=null)
    {
        if(is_null($element_separator))
        {
            $element_separator = $this->_element_separator;
        }
        if(is_null($key_separator))
        {
            $key_separator = $this->_key_separator;
        }
        $elements = split($element_separator,$map);
        foreach ($elements as $element)
        {
            $keys = split($key_separator, $element);
            $this->_map['target'][$keys[0]]=$keys[1];
            $this->_map['source'][$keys[1]]=$keys[0];
        }
    }

    public function getValue($key)
    {
        return $this->_source[$this->_map['target'][$key]];
    }
    /**
     *
     * @param array $arry_for_test
     * @param string $side it's can be 'target' or 'source' if else return 'false'
     * @return boolean true if map enclose all keys in the array, if else return 'false'
     */
    public function mapEncloseArray($arry_for_test,$side='source')
    {
        $result = true;
        if ('target' !== $side and 'source' !== $side)
        {
            return false;
        }
        foreach ($arry_for_test as $key=>$value)
        {
            if(!isset($this->_map[$side][$key]))
            {
                $result = false;
                break;
            }
        }
        return $result;
    }
    /**
     *
     * @param array $array_for_test
     * @param string $side it's can be 'target' or 'source' if else return 'false'
     * @return boolean  true if array enclose all keys in the map, if else return 'false'
     */
    public function arrayEncloseMap($array_for_test,$side='target')
    {
        $result = true;
        if ('target' !== $side and 'source' !== $side)
        {
            return false;
        }
        foreach ($this->_map[$side] as $key=>$value)
        {
            if(!isset($array_for_test[$key]))
            {
                $result = false;
                break;
            }
        }
        return $result;
    }
    /**
     * bind $target all elements from $source used this map
     *
     * @param array $target target array
     * @param array $source soursce array
     * @param boolean $strict default false
     * @return boolean
     * true if success or false <br/>
     * <b>Note.</b><br/>
     * if $strict = true this function return false, if key is in this map and it is not in the source
     */
    public function bindUseMap(&$target,&$source,$strict=false)
    {
        $_target=$target;
        $result=true;
        foreach ($this->_map['target'] as $key => $value)
        {
            if (isset($source[$value]))
            {
                $target[$key] = $source[$value];
            }
            else
            {
                if ($strict)
                {
                    $target = $_target;
                    $result = false;
                    break;
                }
            }
        }
        return $result;
    }
    /**
     * set map from string like 't_key1=s_key1,t_key2=skey2,...'
     * @param string $map string like 't_key1=s_key1,t_key2=skey2,...'
     * @param string|null $element_separator
     * @param string|null $key_separator
     */
    public function setMap($map,$element_separator=null,$key_separator=null)
    {
        $this->init($map,$element_separator,$key_separator);
    }

    public function setSource($source)
    {
        $this->_source = $source;
    }

    public function printMap()
    {
        print_r($this->_map);
    }
}
?>