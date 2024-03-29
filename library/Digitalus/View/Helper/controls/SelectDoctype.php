<?php
/**
 * SelectDoctype helper
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
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id:$
 * @link       http://www.digitaluscms.com
 * @since      Release 1.5.0
 */

/**
 * @see Zend_View_Interface
 */
require_once 'Zend/View/Interface.php';

/**
 * SelectDoctype helper
 *
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    Release: @package_version@
 * @link       http://www.digitaluscms.com
 * @since      Release 1.5.0
 * @uses       viewHelper Digitalus_View_Helper_Controls
 */
class Digitalus_View_Helper_Controls_SelectDoctype
{
    /**
     * @var Zend_View_Interface
     */
    public $view;

    /**
     *
     */
    public function selectDoctype($name, $value, $attr = null)
    {
        $data = array(
            'XHTML1_TRANSITIONAL' => 'XHTML1_TRANSITIONAL',
            'XHTML11'             => 'XHTML11',
            'XHTML1_STRICT'       => 'XHTML1_STRICT',
            'XHTML1_FRAMESET'     => 'XHTML1_FRAMESET',
            'XHTML_BASIC1'        => 'XHTML_BASIC1',
            'HTML4_STRICT'        => 'HTML4_STRICT',
            'HTML4_LOOSE'         => 'HTML4_LOOSE',
            'HTML4_FRAMESET'      => 'HTML4_FRAMESET'
        );

        return $this->view->formSelect($name, $value, $attr, $data);
    }

    /**
     * Sets the view field
     * @param $view Zend_View_Interface
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }
}
