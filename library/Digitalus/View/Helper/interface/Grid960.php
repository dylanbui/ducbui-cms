<?php
/**
 *
 * @author forrest lyman
 * @version
 */
require_once 'Zend/View/Interface.php';

/**
 * Grid960 helper
 *
 * @uses viewHelper Digitalus_View_Helper_Controls
 */
class Digitalus_View_Helper_Interface_Grid960 {

    /**
     * @var Zend_View_Interface
     */
    public $view;

    public $container = null;
    public $containerClass = 'container';
    public $unitClass = 'grid';

    /**
     * this class wraps a grid960 container.
     * you create the container in the constructor
     * then add units to it at will.
     * @param int $containerWidth
     */
    public function grid960($columns, $before = 0, $after = 0)
    {
        $div = '<div />';
        $this->container = simplexml_load_string($div);
        $class = $this->makeClass($columns,$this->containerClass, $before, $after, false, false);
        $this->container->addAttribute('class', $class);
        $this->container->addAttribute('id', 'wrapper');
        return $this;
    }

    public function startRow($id, $columns, $parent = null, $before = 0, $after = 0)
    {
        $unit = $this->_addUnit($id, $columns, $parent, $before, $after, true, false);
        return $unit;
    }

    public function addUnit($id, $columns, $parent = null, $before = 0, $after = 0)
    {
        $unit = $this->_addUnit($id, $columns, $parent, $before, $after, false, false);
        return $unit;

    }

    public function endRow($id, $columns, $parent = null, $before = 0, $after = 0)
    {
        $unit = $this->_addUnit($id, $columns, $parent, $before, $after, false, true);
        $this->_clear($parent);
        return $unit;
    }

    public function populate($element, $content, $wrapper = 'div')
    {
        $element->addChild($wrapper, $content);
    }

    protected function _addUnit($id, $columns, $parent = null, $before = 0, $after = 0, $first = false, $last = false)
    {
        if ($parent == null) {
            $div = $this->container->addChild('div');
        } else {
            $div = $parent->addChild('div');
        }

        $class = $this->makeClass($columns, $this->unitClass, $before, $after, $first, $last);

        //load the content from the placeholder
        $placeholderKey = $id . '_content';
        $content = $this->view->placeholder($placeholderKey);

        if (!empty($content)) {
            $innerContent = $div->addChild('div', $content);
            $innerContent->addAttribute('class', 'innerContent');
        } else {
            $spacer = $div->addChild('div');
            $spacer->addAttribute('class', 'spacer');
        }

        $div->addAttribute('class', $class);
        $div->addAttribute('id', $id);
        return $div;
    }

    protected function _clear($parent = null)
    {
        if ($parent == null) {
            $clear = $this->container->addChild('br');
        } else {
            $clear = $parent->addChild('br');
        }

        $clear->addAttribute('class', 'clear');
    }

    public function render()
    {
        // TODO: this will decode things that are meant to be encoded
        return html_entity_decode($this->container->asXml());
    }

    public function makeClass($columns, $type = null, $before = 0, $after = 0, $first = false, $last = false)
    {
        $class = array();

        if ($type != null) {
            $baseClass = $type;
        } else {
            $baseClass = $this->unitClass;
        }

        $class[] = $baseClass . '_' . $columns;

        if ($first == true) {
            $class[] = 'alpha';
        } elseif ($last == true) {
            $class[] = 'omega';
        }

        if ($before > 0) {
            $class[] = 'prefix_' . $before;
        }

        if ($after > 0) {
            $class[] = 'suffix_' . $after;
        }

        return implode(' ', $class);

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