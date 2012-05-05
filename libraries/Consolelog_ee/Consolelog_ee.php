<?php

/* -- FireLogger Interface ---------------------------------------------------------------------- */
define('FIRELOGGER_NO_CONFLICT',TRUE);  // We do NOT want the helper functions available in the global namespace,
                                        // so we have to define this constant BEFORE including FireLogger.
                                      
class Consolelog_ee {

    public $enabled = FALSE;
    public $uagent = FALSE;
    public $logger_instance = FALSE;
    protected $loggers = array(
        'FIREFOX' => 'FireLogger.php',
        'CHROME' => 'ChromePHP.php'
        );
    
    /**
	 * Constructor
	 */
	public function __construct() {
	//	$this->EE =& get_instance();

        if(stripos($_SERVER["HTTP_USER_AGENT"],'Firefox') > -1) {
            $this->uagent = 'FIREFOX';
            }
        else if(stripos($_SERVER["HTTP_USER_AGENT"],'Chrome') > -1) {
            $this->uagent = 'CHROME';
            }
            
        if($this->uagent) {
            require_once(dirname(__FILE__).'/LoggingLibraries/'.$this->loggers[$this->uagent]);

            if($this->uagent == 'FIREFOX') {
                $this->logger_instance =  FireLogger::$default;
                }
            else if($this->uagent == 'CHROME') {
                $this->logger_instance =& ChromePhp::getInstance();
                }
            }
        }
        
    public function cllog(/*fmt, obj1, obj2, ...*/) {
        if($this->enabled && $this->logger_instance) {
            $args = func_get_args();
            $this->_dologging('log',$args);
            }
        }
    public function clwarn(/*fmt, obj1, obj2, ...*/) {
        if($this->enabled && $this->logger_instance) {
            $args = func_get_args();
            $this->_dologging('warning',$args);
            }
        }
        
    public function clerror(/*fmt, obj1, obj2, ...*/) {
        if($this->enabled && $this->logger_instance) {
            $args = func_get_args();
            $this->_dologging('error',$args);
            }
        }
        
    public function clinfo(/*fmt, obj1, obj2, ...*/) {
        if($this->enabled && $this->logger_instance) {
            $args = func_get_args();
            $this->_dologging('info',$args);
            }
        }
        
    public function clcritical(/*fmt, obj1, obj2, ...*/) {
        if($this->enabled && $this->logger_instance) {
            $args = func_get_args();
            $this->_dologging('critical',$args);
            }
        }

    private function _dologging($type,$args) {
        if($this->uagent == 'FIREFOX') {
            if('log' != $type) { 
                array_unshift($args, $type);
                }
            }
        else if($this->uagent == 'CHROME') {
            $type = ('critical' == $type) ? 'error' : $type; // ChromePHP doesn't handle criticals
            array_push($args,$type);
            if(count($args) == 2) {
                // this method needs three arguments, so push a blank label if there's not one
                array_unshift($args,' ');
                }
            }
        call_user_func_array(array($this->logger_instance, 'log'), $args);
        }

}
