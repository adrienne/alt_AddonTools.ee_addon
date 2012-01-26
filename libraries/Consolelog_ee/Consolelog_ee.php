<?php

/* -- FireLogger Interface ---------------------------------------------------------------------- */
define('FIRELOGGER_NO_CONFLICT',TRUE);  // We do NOT want the helper functions available in the global namespace,
                                        // so we have to define this constant BEFORE including FireLogger.
                                      
class Consolelog_ee {

    public $enabled = FALSE;
    public $uagent = FALSE;
    protected $loggers = array(
        'FIREFOX' => 'FireLogger.php',
        'CHROME' => 'ChromePHP.php'
        );
    private $FL;
    /**
	 * Constructor
	 */
	public function __construct() {
		$this->EE =& get_instance();

        if(stripos($_SERVER["HTTP_USER_AGENT"],'Firefox') > -1) {
            $this->uagent = 'FIREFOX';
            }
        else if(stripos($_SERVER["HTTP_USER_AGENT"],'Chrome') > -1) {
            // $this->uagent = 'CHROME';
            }
            
        if($this->uagent) {
            require_once(dirname(__FILE__).'/LoggingLibraries/'.$this->loggers[$this->uagent]);
            }
        
        }

    public function cllog(/*fmt, obj1, obj2, ...*/) {
            if($this->enabled && $this->uagent) {
            $args = func_get_args();
            call_user_func_array(array(FireLogger::$default, 'log'), $args);
            }
        }
    public function clwarn(/*fmt, obj1, obj2, ...*/) {
        if($this->enabled && $this->uagent) {
            $args = func_get_args();
            array_unshift($args, 'warning');
            call_user_func_array(array(FireLogger::$default, 'log'), $args);
            }
        }
        
    public function clerror(/*fmt, obj1, obj2, ...*/) {
        if($this->enabled && $this->uagent) {
            $args = func_get_args();
            array_unshift($args, 'error');
            call_user_func_array(array(FireLogger::$default, 'log'), $args);
            }
        }
        
    public function clinfo(/*fmt, obj1, obj2, ...*/) {
        if($this->enabled && $this->uagent) {
            $args = func_get_args();
            array_unshift($args, 'info');
            call_user_func_array(array(FireLogger::$default, 'log'), $args);
            }
        }
        
    public function clcritical(/*fmt, obj1, obj2, ...*/) {
        if($this->enabled && $this->uagent) {
            $args = func_get_args();
            array_unshift($args, 'critical');
            call_user_func_array(array(FireLogger::$default, 'log'), $args);
            }
        }

}
