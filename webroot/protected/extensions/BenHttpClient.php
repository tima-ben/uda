<?php
/**
 * @version 0.1
 * @package classes.ben
 * @author Eduard Balantsev
 * @copyright &copy; Eduard Balantsev 2010 classes.ben
 */

/**
 * Curl based HTTP Client
 * Simple but effective OOP wrapper around Curl php lib.
 * Contains common methods needed
 * for getting data from url, setting referrer, credentials,
 * sending post data, managing cookies, etc.
 *
 * Samle usage:
 * $curl = &new BenHttpClient();
 * $useragent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
 * $curl->setUserAgent($useragent);
 * $curl->storeCookies("/tmp/cookies.txt");
 * $post_data = array('login' => 'pera', 'password' => 'joe');
 * $html_data = $curl->send_post_data(http://www.foo.com/login.php, $post_data);
 */
class BenHttpClient
{
    /**
     * Curl handler
     * @access private
     * @var resource
     */
    private $_ch ;
    private $_aHeader;
    private $_sHeader;

    /**
     * Contain last error message if error occured
     * @access private
     * @var string
     */
    private $errorMsg;


    /**
     * constructor
     * @access public
     */
    public function  __construct()
    {
        $this->init();
    }
    /**
     * destructor
     * @access public
     */
    public function  __destruct()
    {
        $this->close();
    }
    /**
     * Close curl session and free resource
     * Usually no need to call this function directly
     * in case you do you have to call init() to recreate curl
     * @access private
     */
    private function close()
    {
        curl_close($this->_ch);
    }
    /**
     * fetch data from target URL
     * return data returned from url or false if error occured
     * @param string url
     * @param string ip address to bind (default null)
     * @param int timeout in sec for complete curl operation (default 5)
     * @return string data
     * @access public
     */
    function getBody($url, $ip=null, $timeout=5)
    {
        // set url to post to
        curl_setopt($this->_ch, CURLOPT_URL,$url);

        //set method to get
        curl_setopt($this->_ch, CURLOPT_HTTPGET,true);

        // return into a variable rather than displaying it
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($this->_ch,CURLINFO_HEADER_OUT,true);
        //bind to specific ip address if it is sent trough arguments
        if($ip)
        {
            curl_setopt($this->_ch,CURLOPT_INTERFACE,$ip);
        }

        //set curl function timeout to $timeout
        curl_setopt($this->_ch, CURLOPT_TIMEOUT, $timeout);

        //and finally send curl request
        $result = curl_exec($this->_ch);
        if(curl_errno($this->_ch))
        {
            return false;
        }
        else
        {
            return $result;
        }
    }
    /**
     * Get last URL info
     * usefull when original url was redirected to other location
     * @access public
     * @return string url
     */
    public function getEffectiveUrl()
    {
        return curl_getinfo($this->_ch, CURLINFO_EFFECTIVE_URL);
    }
    /**
     * Return last error message and error number
     * @return string error msg
     * @access public
     */
    public function getErrorMsg()
    {
        $err = "Error number: " .curl_errno($this->_ch) ."\n";
        $err .="Error message: " .curl_error($this->_ch)."\n";

        return $err;
    }
    /**
     * full HEADER last response
     * @param boolean $type_array
     * @return string|array if $type_array = true return type array else return type string
     */
    public function getHeader($type_array=true)
    {
        if ($type_array)
        {
            return $this->_aHeader;
        }
        else
        {
            return $this->_sHeader;
        }
    }
    /**
     * Get http response code
     * @access public
     * @return int
     */
    public function getHttpResponseCode()
    {
        return curl_getinfo($this->_ch, CURLINFO_HTTP_CODE);
    }
    /**
     * return result work cURL function curl_getinfo()
     * @param integer|null $opt
     * @return string|array
     */
    public function getResponseHeaders($opt=null)
    {
        if (is_null($opt))
        {
            return curl_getinfo($this->_ch);
        }
        else
        {
            return curl_getinfo($this->_ch,$opt);
        }
    }
    /**
     * Set to receive output headers in all output functions
     * @param boolean true to include all response headers with output, false otherwise
     * @access public
     */
    public function includeResponseHeaders($value=true)
    {
        curl_setopt($this->_ch, CURLOPT_HEADER, $value);
    }

    /**
     * Init Curl session
     * @access protected
     */
    protected function init()
    {
        // initialize curl handle
        $this->_ch = curl_init();
        // set Header read functtion
        curl_setopt($this->_ch, CURLOPT_HEADERFUNCTION , array($this,'readHeader'));
        //set various options

        //set error in case http return code bigger than 300
        curl_setopt($this->_ch, CURLOPT_FAILONERROR, true);

        // allow redirects
        curl_setopt($this->_ch, CURLOPT_FOLLOWLOCATION, true);

        // set ecoding default , cURL get responsabilite
        curl_setopt($this->_ch,CURLOPT_ENCODING , '');

        // set response with out HEADER
        curl_setopt($this->_ch, CURLOPT_HEADER, false);

        // do not veryfy ssl
        // this is important for windows
        // as well for being able to access pages with non valid cert
        curl_setopt($this->_ch, CURLOPT_SSL_VERIFYPEER, 0);
    }

    /**
     * function for callback for read headers
     * @param resource $ch
     * @param string $header
     * @return integer count read byte.
     */
    private function readHeader($ch,$header)
    {
        $this->_aHeader[] = $header;
        $this->_sHeader .= $header;
        return strlen($header);
    }
    /**
     * Save body from target URL
     * and store it directly to file
     * @param string url
     * @param resource value stream resource(ie. fopen)
     * @param string ip address to bind (default null)
     * @param int timeout in sec for complete curl operation (default 10)
     * @return boolean true on success false othervise
     * @access public
     */
    public function saveBodyIntoFile($url, $fp, $ip=null, $timeout=10)
    {
        // set url to post to
        curl_setopt($this->_ch, CURLOPT_URL,$url);

        //set method to get
        curl_setopt($this->_ch, CURLOPT_HTTPGET, true);

        // store data into file rather than displaying it
        curl_setopt($this->_ch, CURLOPT_FILE, $fp);

        //bind to specific ip address if it is sent trough arguments
        if($ip)
        {
            curl_setopt($this->_ch, CURLOPT_INTERFACE, $ip);
        }

        //set curl function timeout to $timeout
        curl_setopt($this->_ch, CURLOPT_TIMEOUT, $timeout);

        //and finally send curl request
        $result = curl_exec($this->_ch);

        if(curl_errno($this->_ch))
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    /**
     * Set custom cookie
     * @param string cookie
     * @access public
     */
    function setCookie($cookie)
    {
        curl_setopt ($this->_ch, CURLOPT_COOKIE, $cookie);
    }
    /**
     * Set username/pass for basic http auth
     * @param string user
     * @param string pass
     * @access public
     */
    public function setHtthAuth($username,$password)
    {
        curl_setopt($this->_ch, CURLOPT_USERPWD, "$username:$password");
    }
    /**
     * Set proxy to use for each curl request
     * @param string proxy
     * @access public
     */
    function setProxy($proxy)
    {
        curl_setopt($this->_ch, CURLOPT_PROXY, $proxy);
    }

    /**
     * Set referrer
     * @param string referrer url
     * @access public
     */
    public function setReferrer($referrer_url)
    {
        curl_setopt($this->_ch, CURLOPT_REFERER, $referrer_url);
    }
    /**
     * Set client's useragent
     * @param string user agent
     * @access public
     */
    public function setUserAgent($useragent)
    {
        curl_setopt($this->_ch, CURLOPT_USERAGENT, $useragent);
    }






    /**
     * Send post data to target URL
     * return data returned from url or false if error occured
     * @param string url
     * @param mixed post data (assoc array ie. $foo['post_var_name'] = $value or as string like var=val1&var2=val2)
     * @param string ip address to bind (default null)
     * @param int timeout in sec for complete curl operation (default 10)
     * @return string data
     * @access public
     */
    function send_post_data($url, $postdata, $ip=null, $timeout=10)
    {
        //set various curl options first

        // set url to post to
        curl_setopt($this->_ch, CURLOPT_URL,$url);

        // return into a variable rather than displaying it
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER,true);

        //bind to specific ip address if it is sent trough arguments
        if($ip)
        {
            curl_setopt($this->_ch,CURLOPT_INTERFACE,$ip);
        }

        //set curl function timeout to $timeout
        curl_setopt($this->_ch, CURLOPT_TIMEOUT, $timeout);

        //set method to post
        curl_setopt($this->_ch, CURLOPT_POST, true);


        //generate post string
        $post_array = array();
        if(is_array($postdata))
        {
            foreach($postdata as $key=>$value)
            {
                $post_array[] = urlencode($key) . "=" . urlencode($value);
            }

            $post_string = implode("&",$post_array);

            if($this->debug)
            {
                echo "Url: $url\nPost String: $post_string\n";
            }
        }
        else
        {
            $post_string = $postdata;
        }

        // set post string
        curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $post_string);


        //and finally send curl request
        $result = curl_exec($this->_ch);

        if(curl_errno($this->_ch))
        {
            return false;
        }
        else
        {
            return $result;
        }
    }


    /**
     * Send multipart post data to the target URL
     * return data returned from url or false if error occured
     * (contribution by vule nikolic, vule@dinke.net)
     * @param string url
     * @param array assoc post data array ie. $foo['post_var_name'] = $value
     * @param array assoc $file_field_array, contains file_field name = value - path pairs
     * @param string ip address to bind (default null)
     * @param int timeout in sec for complete curl operation (default 30 sec)
     * @return string data
     * @access public
     */
    function send_multipart_post_data($url, $postdata, $file_field_array=array(), $ip=null, $timeout=30)
    {
        //set various curl options first

        // set url to post to
        curl_setopt($this->_ch, CURLOPT_URL, $url);

        // return into a variable rather than displaying it
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);

        //bind to specific ip address if it is sent trough arguments
        if($ip)
        {
            curl_setopt($this->_ch,CURLOPT_INTERFACE,$ip);
        }

        //set curl function timeout to $timeout
        curl_setopt($this->_ch, CURLOPT_TIMEOUT, $timeout);

        //set method to post
        curl_setopt($this->_ch, CURLOPT_POST, true);

        // disable Expect header
        // hack to make it working
        $headers = array("Expect: ");
        curl_setopt($this->_ch, CURLOPT_HTTPHEADER, $headers);

        // initialize result post array
        $result_post = array();

        //generate post string
        $post_array = array();
        $post_string_array = array();
        if(!is_array($postdata))
        {
            return false;
        }

        foreach($postdata as $key=>$value)
        {
            $post_array[$key] = $value;
            $post_string_array[] = urlencode($key)."=".urlencode($value);
        }

        $post_string = implode("&",$post_string_array);

        // set post string
        //curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post_string);


        // set multipart form data - file array field-value pairs
        if(!empty($file_field_array))
        {
            foreach($file_field_array as $var_name => $var_value)
            {
                if(strpos(PHP_OS, "WIN") !== false) $var_value = str_replace("/", "\\", $var_value); // win hack
                $file_field_array[$var_name] = "@".$var_value;
            }
        }

        // set post data
        $result_post = array_merge($post_array, $file_field_array);
        curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $result_post);


        //and finally send curl request
        $result = curl_exec($this->_ch);

        if(curl_errno($this->_ch))
        {
            return false;
        }
        else
        {
            return $result;
        }
    }

    /**
     * Set file location where cookie data will be stored and send on each new request
     * @param string absolute path to cookie file (must be in writable dir)
     * @access public
     */
    function storeCookies($cookie_file)
    {
        // use cookies on each request (cookies stored in $cookie_file)
        curl_setopt ($this->_ch, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt ($this->_ch, CURLOPT_COOKIEFILE, $cookie_file);
    }



}
?>