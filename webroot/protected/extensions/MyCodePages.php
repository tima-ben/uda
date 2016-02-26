<?php
/**
 * MyCodePages class file.
 *
 * @author >
 * @link
 * @copyright
 * @license
 */

/**
 * MyCodePages provides a set of commonly used data formatting methods.
 *
 * The formatting methods provided by CFormatter are all named in the form of <code>formatXyz</code>.
 * The behavior of some of them may be configured via the properties of CFormatter. For example,
 * by configuring {@link dateFormat}, one may control how {@link formatDate} formats the value into a date string.
 *
 * For convenience, CFormatter also implements the mechanism of calling formatting methods with their shortcuts (called types).
 * In particular, if a formatting method is named <code>formatXyz</code>, then its shortcut method is <code>xyz</code>
 * (case-insensitive). For example, calling <code>$formatter->date($value)</code> is equivalent to calling
 * <code>$formatter->formatDate($value)</code>.
 *
 * Currently, the following types are recognizable:
 * <ul>
 * <li>raw: the attribute value will not be changed at all.</li>
 * <li>text: the attribute value will be HTML-encoded when rendering.</li>
 * <li>ntext: the {@link formatNtext} method will be called to format the attribute value as a HTML-encoded plain text with newlines converted as the HTML &lt;br /&gt; tags.</li>
 * <li>html: the attribute value will be purified and then returned.</li>
 * <li>date: the {@link formatDate} method will be called to format the attribute value as a date.</li>
 * <li>time: the {@link formatTime} method will be called to format the attribute value as a time.</li>
 * <li>datetime: the {@link formatDatetime} method will be called to format the attribute value as a date with time.</li>
 * <li>boolean: the {@link formatBoolean} method will be called to format the attribute value as a boolean display.</li>
 * <li>number: the {@link formatNumber} method will be called to format the attribute value as a number display.</li>
 * <li>email: the {@link formatEmail} method will be called to format the attribute value as a mailto link.</li>
 * <li>image: the {@link formatImage} method will be called to format the attribute value as an image tag where the attribute value is the image URL.</li>
 * <li>url: the {@link formatUrl} method will be called to format the attribute value as a hyperlink where the attribute value is the URL.</li>
 * </ul>
 *
 * By default, {@link CApplication} registers {@link CFormatter} as an application component whose ID is 'format'.
 * Therefore, one may call <code>Yii::app()->format->boolean(1)</code>.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CFormatter.php 1678 2010-01-07 21:02:00Z qiang.xue $
 * @package system.utils
 * @since 1.1.0
 */
class MyCodePages
{
	static private $_listCodePage = array(
        1=>'UTF-8',
        2=>'WINDOWS-1251',
        3=>'WINDOWS-1250',
    );
    static private $_init=false;
    static private $_listCodeName = array();
    static private $_listNameCode = array();
    static private function init()
    {
        if (!self::$_init)
        {
            foreach(self::$_listCodePage as $code=>$name)
            {
                self::$_listCodeName[$code]=$name;
                self::$_listNameCode[$name]=$code;
            }
            self::$_init=true;
        }
    }
    static public function GetListCodeName()
    {
        if(!self::$_init)
        {
            self::init();
        }
        return self::$_listCodeName;
    }
    static public function GetCodeByName($name)
    {
        if(!self::$_init)
        {
            self::init();
        }
        return self::$_listNameCode[$name];
    }
    static public function GetNameByCode($code)
    {
        if(!self::$_init)
        {
            self::init();
        }
        return self::$_listCodeName[$code];
    }

}
