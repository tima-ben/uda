<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of BenXMLReader class
 * @version 0.1 form 5 may 2010
 * @package classes.ben
 * @author Eduard Balantsev
 * @copyright &copy; Eduard Balantsev 2010 classes.ben
 */
class BenXMLReader
{

    /**
     *
     * @var XMLReader
     */
    private $_xml = null;
    private $xmlValid = false;
    private $_runDepth = 0;
    private $_tmpFiles = array();

    /**
     *
     * @var string name of xml file
     */
    private $xmlFile = null;

    /**
     *
     * @var string name of xmlSchema file
     */
    private $xmlSchema = null;

    /**
     *
     * @var boolean $isError flag for error
     */
    private $isError = false;
    /**
     *
     * @var array() erorr message;
     */
    private $errorsMsg =array();

    /**
     *
     * @var string content xml for parsing
     */
    private $xmlContent = null;
    public  $md5XmlContent = null;

    /**
     *
     * @var string content xmlSchema dont used yet
     */
    private $xmlSchemaContent = null;
    public  $md5XmlSchemaContent = null;

    /**
     *
     * @param string $file name of the XML files
     * @param string $schema name of the schema files (xsd) schema of www.w3.org/2001/SMLSchema
     * @return BenXMLReader
     * @assert () =='asdas'
     * @assert ('sss') == 'sss'
     * @assert ('123','112') === '111','222'
     */
    public function  __construct($file=null,$schema=null)
    {
        $this->_runDepth++;
        $this->_xml = new XMLReader();
        if (!is_null($file) or !is_null($schema))
        {
            if (!is_null($file))
            {
                $this->setXmlFile($file);
            }
            if (!is_null($schema))
            {
                $this->setXmlSchema($schema);
            }
            $this->xmlValidation();
        }
        $this->_runDepth--;
    }

    public  function cleanTmpFile()
    {
        while (!empty($this->_tmpFiles))
        {
            unlink(array_pop($this->_tmpFiles));
        }

    }
public function  __destruct()
    {
        $this->cleanTmpFile();
    }
    /**
     * setup inside properties and install private variables
     * @param void
     * @return void
     */
    protected function init()
    {
        set_error_handler(array($this,'exceptions_error_handler'));
        try
        {
            $this->_xml->XML($this->xmlContent);
        }
        catch ( ErrorException $e )
        {
            $this->isError = true;
            $this->errorsMsg[] = $e->getMessage();
        }
        try
        {
            $this->_xml->setSchema($this->xmlSchema);
        }
        catch (ErrorException $e)
        {
            $this->isError = true;
            $this->errorsMsg[] = $e->getMessage();
        }
        restore_error_handler();
    }

    /**
     * display content xml
     * @param void
     * @return void
     */
    public function printXml()
    {
        if($this->isError)
        {
            print_r($this->errorsMsg);
            return;
        }
        if(!is_null($this->xmlContent))
        {

            $this->_xml->read();
            do
            {
                $this->dumpCurentXMLElement();
            } while($this->_xml->read());
        }
        else
        {
            echo 'is Empty';
        }
    }
    /**
     * setup property xmlFile
     * @param string $file_name name of the XML files
     * @return boolean true if allright and false if exist error
     */
    public function setXmlFile($file_name=null)
    {
        $this->_runDepth++;
        if (1>=$this->_runDepth)
        {
            $this->isError = false;
            $this->errorsMsg = array();
        }
        if(is_null($file_name))
        {
            $this->xmlFile = null;
            $this->setXmlContent(null);
            $this->xmlValid = false;
        }
        else
        {
            $this->isError = true;
            if(file_exists($file_name))
            {
                $this->xmlFile = $file_name;
                $this->setXmlContent(file_get_contents($this->xmlFile));
                $this->isError = false;
                if(1>=$this->_runDepth)
                {
                    $this->xmlValidation();
                }
            }
            else
            {
                $this->_runDepth--;
                $error_comment = 'File ' . $file_name . ' not exist';
                $this->errorsMsg[] = $error_comment;
                throw new Exception($error_comment);
            }
        }
        $this->_runDepth--;
        return !$this->isError;
    }
    /**
     * setup property xmlSchema
     * @param string $schema_name
     * @return boolean true if allright and false if exist error
     * @assert() == ''
     * @assert('sdfsdfsd') == 'sssdsd'
     */
    public function setXmlSchema($schema_name=null)
    {
        $this->_runDepth++;
        if (1>=$this->_runDepth)
        {
            $this->isError = false;
            $this->errorsMsg = array();
        }
        if(is_null($schema_name))
        {
            $this->xmlSchema = null;
            $this->setXmlSchemaContent(null);
        }
        else
        {
            $this->isError = true;
            if(file_exists($schema_name))
            {
                $this->xmlSchema = $schema_name;
                $this->setXmlSchemaContent(file_get_contents($this->xmlSchema));
                $this->isError = false;
            }
            else
            {
                $this->_runDepth--;
                $error_comment = 'File ' . $file_name . ' not exist';
                $this->errorsMsg[] = $error_comment;
                throw new Exception($error_comment);
            }
        }
        if(!is_null($this->xmlContent) and 1>=$this->_runDepth)
        {
            $this->xmlValidation();
        }
        $this->_runDepth--;
        return !$this->isError;
    }

    /**
     * setup property xmlContent
     * @param string $xml_content content xml files for parsing
     * @return void
     */
    public function setXmlContent($xml_content=null)
    {
        $this->_runDepth++;
        $this->xmlContent = $xml_content;
        $this->md5XmlContent = is_null($xml_content) ? null : md5($this->xmlContent);
        if (1>=$this->_runDepth)
        {
            $this->isError = false;
            $this->errorsMsg = array();
            $this->xmlFile=null;
            if(is_null($xml_content))
            {
                $this->xmlValid = false;
            }
            else
            {
                $this->xmlValidation();
            }
        }

        $this->_runDepth--;
        return !$this->isError;
    }
    /**
     * setup property xmlSchemaContent
     * @param string $schema_content content xml files for parsing
     * @return void
     */
    public function setXmlSchemaContent($schema_content=null)
    {
        $this->_runDepth++;
        $this->xmlSchemaContent = $schema_content;
        $this->md5XmlSchemaContent = is_null($schema_content) ? null :  md5($this->xmlSchemaContent);
        if(1>=$this->_runDepth)
        {
            if(!is_null($schema_content))
            {
                $name_dir=sys_get_temp_dir();
                $file_name=tempnam($name_dir,'schema');
                unlink($file_name);
                $file_name .='.xsd';
                file_put_contents($file_name, $schema_content);
                $this->xmlSchema = $file_name;
                $this->_tmpFiles[] = $file_name;
            }
            else
            {
                $this->xmlSchema = null;
            }
            $this->xmlValid = false;
            if(!is_null($this->xmlContent))
            {
                $this->xmlValidation();
            }
        }
        $this->_runDepth--;
    }
    /**
     * new error_handler function
     * @param integer $severity
     * @param string $message
     * @param string $filename
     * @param integer $lineno
     * @return void
     */
    public function exceptions_error_handler($severity, $message, $filename, $lineno)
    {
       //  print_r(error_get_last());
        if (error_reporting() == 0)
        {
            return;
        }
         //echo $severity .' ' .$filename . ' ' . $lineno . "\n";
        if (error_reporting() & $severity)
        {

            throw new ErrorException($message, 0, $severity, $filename, $lineno);
        }
    }
    protected function xmlValidation()
    {
        $this->_runDepth++;
        $this->xmlValid=false;
        $this->init();
        $last_msg=' ';
        set_error_handler(array($this,'exceptions_error_handler'));
        do
        {
            try
            {
               $go = $this->_xml->read();
            }
            catch ( ErrorException $e )
            {
                $this->isError = true;
                if($last_msg === $e->getMessage())
                {
                    $go=false;
                }
                else
                {
                    $this->errorsMsg[] = $e->getMessage();
                    $last_msg = $e->getMessage();
                }
            }
        } while ($go);
        restore_error_handler();
        if(!$this->isError)
        {
            $this->init();
            $this->xmlValid=true;
        }
        $this->_runDepth--;
    }
    /**
     * @param void
     * @return array()
     */
    public function xmlToArray()
    {
        $result=array();
        $attributs=array();
        if ($this->xmlValid)
        {
            $this->init();
        }
        else
        {
            return $result;
        }
        $this->_xml->read();
        do
        {
            switch ($this->_xml->nodeType)
            {
                case XMLReader::ELEMENT:
                    if($this->_xml->hasAttributes)
                    {
                        $key = $this->_xml->localName;
                        $attributs = $this->readAttributes($this->_xml);
                    }
                    break;
                case XMLReader::CDATA:
                    $attributs[$key] = $this->_xml->value;
                    break;
                case XMLReader::END_ELEMENT:
                    if(!empty ($attributs))
                    {
                        $result[] = $attributs;
                        $attributs = array();
                    }
                    break;
                default :
            }
        } while ($this->_xml->read());
        return $result;
    }
    /**
     *
     * @param XMLReader $xml
     * @return array()
     */
    protected function readAttributes($xml)
    {
        $attr=array();
        $xml->moveToFirstAttribute();
        do
        {
            $attr[$xml->localName] = $xml->value;
        } while ($xml->moveToNextAttribute());
        return $attr;
    }
    /**
     * Display Curent XML Element (_xml)
     * @param void
     * @return void
     */
    protected function dumpCurentXMLElement()
    {
        $node_types = array (
                0=>"No node type",
                1=>"Start element",
                2=>"Attribute node",
                3=>"Text node",
                4=>"CDATA node",
                5=>"Entity Reference node",
                6=>"Entity Declaration node",
                7=>"Processing Instruction node",
                8=>"Comment node",
                9=>"Document node",
                10=>"Document Type node",
                11=>"Document Fragment node",
                12=>"Notation node",
                13=>"Whitespace node",
                14=>"Significant Whitespace node",
                15=>"End Element",
                16=>"End Entity",
                17=>"XML Declaration node"
        );
        $o = $this->_xml;
        echo "----------->\n";
        echo "attributeCount = " . $o->attributeCount . "\n";
//        echo "baseURI = " . $o->baseURI . "\n";
        echo "depth = " . $o->depth . "\n";
        echo "hasAttributes = " . ( $o->hasAttributes ? 'TRUE' : 'FALSE' ) . "\n";
        echo "hasValue = " . ( $o->hasValue ? 'TRUE' : 'FALSE' ) . "\n";
        echo "isDefault = " . ( $o->isDefault ? 'TRUE' : 'FALSE' ) . "\n";
        echo "isEmptyElement = " . ( @$o->isEmptyElement ? 'TRUE' : 'FALSE' ) . "\n";
        echo "localName = " . $o->localName . "\n";
        echo "name = " . $o->name . "\n";
        echo "namespaceURI = " . $o->namespaceURI . "\n";
        echo "nodeType = " . $o->nodeType . ' - ' . $node_types[$o->nodeType] . "\n";
        echo "prefix = " . $o->prefix . "\n";
        echo "value = " . $o->value . "\n";
        echo "xmlLang = " . $o->xmlLang . "\n";
    }

    public function getTestProperty($name)
    {
        if(property_exists(__CLASS__,$name))
        {
            return $this->$name;
        }
        else
        {
            return array($name=>'not exist');
        }
    }
}
?>