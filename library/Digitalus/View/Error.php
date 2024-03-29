<?php

/**
 * Digitalus CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://digitalus-media.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@digitalus-media.com so we can send you a copy immediately.
 *
 * @category   Digitalus CMS
 * @package   Digitalus_Core_Library
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: Error.php Tue Dec 25 21:29:34 EST 2007 21:29:34 forrest lyman $
 */

class Digitalus_View_Error
{
    /**
     * the storage
     *
     * @var zend_session_namespace
     */
    private $_ns;

    /**
     * the errors stack
     *
     * @var array
     */
    private $_errors;
    
    private static $_instance;

    /**
     * set up the session namespace and load the errors stack
     *
     */
    private function __construct()
    {
        $this->_ns = new Zend_Session_Namespace('errors');
        if (isset($this->_ns->errors)) {
            $this->_errors = $this->_ns->errors;
        }
    }
 
	public static function getInstance()
	{
		if(self::$_instance === null)
			self::$_instance = new self;
		return self::$_instance;
	}
	
    /**
     * clear the errors stack
     *
     */
    public function clear()
    {
        unset($this->_errors);
        $this->_updateNs();
    }

    /**
     * add an error to the stack
     *
     * @param string $error
     */
    public function add($error)
    {
        $this->_errors[] = $error;
        $this->_updateNs();
    }

    /**
     * check to see if any errors are set
     *
     * @return bool
     */
    public function hasErrors()
    {
    	return (count($this->_errors) > 0);
//        if (count($this->_errors) > 0) 
//            return true;
//        else 
//        	return false;
        
    }

    /**
     * get the errors stack
     *
     * @return string
     */
    public function get()
    {
        return $this->_errors;
    }

    /**
     * update the storage
     *
     */
    private function _updateNs()
    {
        if (isset($this->_errors)) {
            $this->_ns->errors = $this->_errors;
        } else {
            unset($this->_ns->errors);
        }
    }
}