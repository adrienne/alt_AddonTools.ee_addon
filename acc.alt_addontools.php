<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 

/**
 * Developer Reference Accessory (part of ALT_AddonTools package)
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Accessory
 * @author		Adrienne L. Travis
 * @link		
 */
 
class Alt_addontools_acc {
	
	public $name			= 'Developer Ref';
	public $id				= 'alt_addontools';
	public $version			= '1.0';
	public $description		= 'A handy tab with developer reference info! <br />(The tab is visible to Superadmins only.)';
	public $sections		= array();
	
	function __construct()
	{
		$this->EE =& get_instance();

        $sec = 'other';

        $this->init($sec);
        
		//Hide the tab on all pages if QueryPath isn't around or if the user isn't a superadmin
        if(!isset($this->EE->querypath) || 1 != ($this->EE->session->userdata['group_id'])) {
            $this->sections[] = '<script type="text/javascript" charset="utf-8">$("#accessoryTabs a.alt_addontools").parent().remove();</script>';
            }

	}
	
	public function init($sec) {
		$mystyles = $this->_get_styles($sec);
		$this->EE->cp->add_to_head($mystyles);
		
		$myscripts = $this->_get_scripts($sec);
		$this->EE->cp->add_to_foot($myscripts);

        
		} // end public init()
	
	/**
	 * Set Sections
	 * This function must exist, but can be empty
	 */
	public function set_sections() {
        if($this->EE->querypath) {
            $this->sections['EE Standard Global Variables'] = $this->_get_ee_ref('globalvars');
            $this->sections['EE Constants'] = $this->_get_ee_ref('constants');
            $this->sections['EE Hidden Config Variables'] = $this->_get_ee_ref('configvars');
            }


		} // end public set_sections()
	
	// ----------------------------------------------------------------
	
	private function _get_ee_ref($mytype) {	
        $returndata = '<div class="innerAcc">';
        
        if('constants' == $mytype) {
            $myurl = "http://expressionengine.com/user_guide/development/constants.html";
            $mylist = $this->EE->querypath->qphtml($myurl)
                ->find('#constants-reference .section')->children("h2,ul");
        
            foreach($mylist as $ele) {
                if($ele->is("h2")) {
                    $returndata .= "<h3>".$ele->branch()->contents()->xml()."</h3>\n";
                    }
                else {
                    $returndata .= $ele->branch()->xml()."\n";
                    }
                }
            }
        else if('configvars' == $mytype) {
            $myurl = "http://expressionengine.com/user_guide/general/hidden_configuration_variables.html";
            $mylist = $this->EE->querypath->qphtml($myurl)
                ->find('#contents')->children("li");
            $returndata .= "<ul>";
            foreach($mylist as $ele) {
                $returndata .= str_replace('</a>',' <strong class="arrow">&#8594;</strong></a>',
                            str_replace('href="#','title="Info at EE Documentation site (opens new window)" target="blank" href="'.$myurl.'#',$ele->branch()->xml())
                            );
                $returndata .= "\n";
                }
            $returndata .= "</ul>";
            }
            
        else if('globalvars' == $mytype) {
            $myurl = "http://expressionengine.com/user_guide/templates/globals/single_variables.html";
            $mylist = $this->EE->querypath->qphtml($myurl)
                ->find('#contents')->children("li");
            $returndata .= "<ul>";
            foreach($mylist as $ele) {
                $returndata .= str_replace('</a>',' <strong class="arrow">&#8594;</strong></a>',
                            str_replace('href="#','title="Info at EE Documentation site (opens new window)" target="blank" href="'.$myurl.'#',$ele->branch()->xml())
                            );
                $returndata .= "\n";
                }
            $returndata .= "</ul>";
            $returndata .= '<p style="max-width: 200px !important;"><strong>Also note</strong> the <a href="http://expressionengine.com/user_guide/templates/globals/single_variables.html#id2" target="blank">
                            alternative syntax for member variables <strong class="arrow">&#8594;</strong></a> if you are using them inside other tags!</p>';
            }
        
        $returndata .= "</div>";
        return $returndata;
        } // end private _get_ee_ref($mytype)
        
        
    // ----------------------------------------------------------------
	
	private function _get_styles($mysec) {
		$out = '<style type="text/css">'."\n";
        $out .= "#alt_addontools .accessorySection .innerAcc { max-height: 250px !important; overflow-y: auto !important; }";
        $out .= "#alt_addontools .accessorySection .innerAcc * { white-space: normal !important; }";
        $out .= "#alt_addontools .accessorySection li { margin: 0 !important; padding: 4px 0 !important; }";
        $out .= "#alt_addontools .accessorySection h3 { padding: 3px !important; background-color: #e1e1e1 !important; color: #222228 !important; margin: 5px 16px 5px 0 !important; }";
        $out .= "#alt_addontools .accessorySection strong { color: #ffffff !important; }";
        $out .= "#alt_addontools .accessorySection strong.arrow { font-family: Impact, Haettenschweiler, Charcoal, sans-serif !important; font-size: 18px !important; line-height: 13px !important; color: #ffffff !important; }";
        $out .= "</style>\n";
		
		return $out;
		} // end private _get_styles()
		
	// ----------------------------------------------------------------	
	
	private function _get_scripts($mysec) {
		$out = '
		<script type="text/javascript">
            </script>
            ';
		
		return $out;
		} // end private _get_scripts()
		





}
 
/* End of file acc.alt_quickfixes.php */
/* Location: /system/expressionengine/third_party/alt_quickfixes/acc.alt_quickfixes.php */