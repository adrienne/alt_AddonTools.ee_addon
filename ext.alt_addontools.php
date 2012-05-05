<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    define("ADDONTOOLS_VERSION",        "0.1");
    define("ADDONTOOLS_DOCS_URL",       "https://github.com/adrienne/alt_AddonTools.ee_addon/tree/master/");
    define("ADDONTOOLS_ADDON_ID",       "ALT AddonTools");
    define("ADDONTOOLS_DESCRIPTION",    "Adds useful tools to ExpressionEngine, for addon developers");

/**
 * @package ALT AddonTools
 * @version 0.1
 * @author Adrienne L. Travis
**/
class Alt_addontools_ext {

	var $settings	    = array();
	var $name			= ADDONTOOLS_ADDON_ID;
	var $version		= ADDONTOOLS_VERSION;
	var $description	= ADDONTOOLS_DESCRIPTION;
	var $settings_exist	= 'n';  // If $settings_exist = 'y' then a settings page will be shown in the ExpressionEngine admin
	var $docs_url		= ADDONTOOLS_DOCS_URL;

	public function __construct($settings='') {
        $this->EE       =& get_instance();
        $this->settings = $settings;
        $this->settings['console_enabled'] = ($this->EE->config->item('debug') == 2) ? TRUE : FALSE;
        
		if(isset($this->EE->session->cache['ALT_AddonTools']) === FALSE){
			$this->EE->session->cache['ALT_AddonTools'] = array(
                'log_init' => FALSE
                );
            }
        } // end public function __construct($settings)

/**
 * Activates extension; sets up records in exp_extensions table with hooks
 * 
 * @return boolean
**/
	function activate_extension() {
    
        // Delete old hooks
        $this->EE->db->query("DELETE FROM exp_extensions WHERE class = '". __CLASS__ ."'");

		// Add new hooks
        $ext_template = array(
            'class'    => __CLASS__,
            'settings' => '',
            'priority' => 1,
            'version'  => $this->version,
            'enabled'  => 'y'
            );
        
        $extensions = array(
            array('hook'=>'sessions_start', 'method'=>'bootstrap_libraries'),
          //  array('hook'=>'sessions_start', 'method'=>'log_queries')
            );
        
        foreach($extensions as $extension) {
            $ext = array_merge($ext_template, $extension);
            $this->EE->db->insert('exp_extensions', $ext);
            }  

		return TRUE;
        } // end function activate_extension() 

 
       
/**
 * Bootstrap libraries into global EE object
 * 
 * @param  object $session [description]
 * @return void
**/
    public function bootstrap_libraries($session) {
        $this->_add_console_logging($session);
        $this->_add_querypath($session);
        $this->_add_twig($session);
        }


        
/**
 * [_add_console_logging description]
 * 
 * @param  object $session [description]
 * @return void 
**/
    private function _add_console_logging($session) { 
        require_once(dirname(__FILE__).'/libraries/Consolelog_ee/Consolelog_ee.php');
        $this->EE->logconsole = new Consolelog_ee;
        if($this->settings['console_enabled']) {
            $this->EE->logconsole->enabled = TRUE;
            if(!$this->EE->session->cache['ALT_AddonTools']['log_init']) {
        //      $this->EE->logconsole->clinfo("Console Logging is enabled!");
                $this->EE->session->cache['ALT_AddonTools']['log_init'] = TRUE;
                }
            }
        else {
            $this->EE->logconsole->enabled = FALSE;
            }
        
        } // end function add_console_logging($session)
        
    private function _add_querypath($session) {
        require_once(dirname(__FILE__).'/libraries/Querypath_ee/Querypath_ee.php');
        $this->EE->querypath = new Querypath_ee;
        }

    private function _add_twig($session) {
        require_once(dirname(__FILE__).'/libraries/Twig_ee/Twig_ee.php');
        $this->EE->twig = new Twig_ee;

        }
        
	public function log_queries() { 

        } // end function log_queries()
    
/**
 * Manual says this function is required
 * 
 * @param  string $current currently installed version
 * @return none
 */
    function update_extension($current = '') {
    
        } // end function update_extension($current)

/**
 * Removes extension record from exp_extensions table when disabling extension
 * 
 * @return none
 */
	function disable_extension() {
		// Delete old hooks
        $this->EE->db->query("DELETE FROM exp_extensions WHERE class = '". __CLASS__ ."'");
        } // end function disable_extension()

    } // end class Alt_debugtools_ext
?>
