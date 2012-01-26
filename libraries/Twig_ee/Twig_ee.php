<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Twig_ee {
	
	private $EE;
	private $_template_dirs = FALSE;
	private $_cache_dir = NULL;
	private $_debug = TRUE;

	public $twig;
	
	public function __construct() {
		$this->EE =& get_instance();

		$basetemplates = array(
            1 => PATH_THIRD,
            2 => PATH_THEMES
            );
        $paramtemplates = (isset($this->EE->templating->template_locations) && is_array($this->EE->templating->template_locations)) 
            ? $this->EE->templating->template_locations 
            : array();
        
        $this->_template_dirs = array_merge($basetemplates,$paramtemplates);
        
        require(dirname(__FILE__).'/Twig/Autoloader.php');
		Twig_Autoloader::register();
		
		if($this->_template_dirs) {
			$loader = new Twig_Loader_Filesystem($this->_template_dirs);
            }
        else {
            die("Nothing found to load!");
            }
		
		$this->twig = new Twig_Environment($loader, array(
                'cache' => $this->_cache_dir,
                'debug' => $this->_debug
                )
			);
		$escaper = new Twig_Extension_Escaper(true);
		$this->twig->addExtension($escaper);

		require_once 'filters/Twig_filters.php';
		$this->twig->addFilter('date', new Twig_Filter_Function('date_filter'));
        }
	
	public function render($template, $data, $return = FALSE) {
		$output = $this->twig->loadTemplate($template);
		log_message('debug', sprintf('Twig rendering template %s', $template));

		if ($return) {
			return $output->render($data);
            }
		else {
			$output->display($data);
            }
        }
	
	public function set_cache_dir($dir = '') {
		if ($dir) {
			$this->_cache_dir = $dir;
            }
        }
	
	public function get_cache_dir() {
		return $this->_cache_dir;
        }
        
} // END class Twig_ee

/* End of file Twig.php */
/* Location: ./system/libraries/Twig.php */
