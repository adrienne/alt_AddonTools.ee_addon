<?php

/* -- QueryPath Interface ----------------------------------------------------------------------- */

class Querypath_ee {

    /**
	 * Constructor
	 */
	public function __construct() {
		$this->EE =& get_instance();
        require_once(dirname(__FILE__).'/QueryPath/QueryPath.php');
        }

    public function qp($document = NULL, $string = NULL, $options = array()) {
        return new QueryPath($document, $string, $options);
        }
        
    public function qphtml($document = NULL, $selector = NULL, $options = array()) {
        $options += array(
                'ignore_parser_warnings' => TRUE,
                'convert_to_encoding' => 'ISO-8859-1',
                'convert_from_encoding' => 'auto',
                'use_parser' => 'html',
                );
        return @$this->qp($document, $selector, $options);
        }

}
