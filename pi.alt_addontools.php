<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

/**
 * alt_UtilityLib_sample Plugin
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Plugin
 * @author		Adrienne L. Travis
 * @link		
 */

$plugin_info = array(
	'pi_name'		=> 'Alt_addontools',
	'pi_version'	=> '1.0',
	'pi_author'		=> 'Adrienne L. Travis',
	'pi_author_url'	=> '',
	'pi_description'=> 'Utilities',
	'pi_usage'		=> Alt_addontools::usage()
);


class Alt_addontools {

	public $return_data;
    
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();

 $url = 'http://www.locusmag.com/Resources/ForthcomingBooks.html';
   
   $myarr = array();
   $mymonth = 'none';
$this->EE->logconsole->clinfo("Console Logging is enabled!");

$me = $this->EE->querypath->qphtml($url)
  ->find(':root .contentresource')->children("p,ul");
  
    foreach ($me as $ele) {
        if($ele->is("ul")) {
            $me2 = $ele->children("li");
            foreach ($me2 as $ele2) {
                $data = array(
                    'title' => trim($ele2->branch()->find("b")->innerHTML()),
                    'author' => trim($ele2->branch()->contents()->html()," +"),
                    'publisher' => trim($ele2->branch()->find(".biblio")->text(),"() "),
                    );
               
                $myarr[$mymonth][] = $data;
                }
            
            }
        else {
        $mymonth = $ele->find("b")->text();
        }
        }
        
    $url2 = "http://eetesting.local/test.html";
    $mystr = '';
    
    $qp2 = $this->EE->querypath->qphtml($url2,"#lipsum")->children("p,ul,ol,div,dl,section,aside,header,footer");
    foreach($qp2 as $ele) {
        $myid = $ele->attr("id");
        if('' == $myid || !$myid) {
            $mystr .= $ele->attr("id",uniqid())->html();
            } 
        else {
            $mystr .= $ele->html();
            }
        }
     
    
    $data = array('books' => $myarr);
    $mystr2 = $this->EE->twig->render('alt_addontools/views/test.html',$data,TRUE);

    $this->return_data = $mystr.$mystr2;
  
	}
   
   /* === quick & dirty PHP pretty-printer for arrays ========================================================== */

public function pp($arr){
    $retStr = "<dl>\n";
    if (is_array($arr)){
        foreach ($arr as $key=>$val){
            if (is_array($val)){
		$retStr .= "<dt>" . $key . " => </dt>\n<dd>" . $this->pp($val) . "</dd>\n";
		}
	    else{
		$retStr .= "<dt>" . $key . " => </dt>\n<dd>" . $val . "</dd>\n";
		}
        }
    }
    $retStr .= "</dl>\n";
    return $retStr;
}
   

	// ----------------------------------------------------------------
	
	/**
	 * Plugin Usage
	 */
	public static function usage()
	{
		ob_start();
?>

 Since you did not provide instructions on the form, make sure to put plugin documentation here.
<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}


/* End of file pi.alt_utilities.php */
/* Location: /system/expressionengine/third_party/alt_utilities/pi.alt_utilities.php */