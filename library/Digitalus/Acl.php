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
 * @version    $Id: Acl.php Tue Dec 25 21:39:35 EST 2007 21:39:35 forrest lyman $
 */

class Digitalus_Acl extends Zend_Acl
{

    protected $resourceList; //the resources list is the public array and is not used internally
    /**
     * load the acl resources and set up permissions
     *
     */
    public function __construct()
    {
    	$objGroups = new Model_Groups();
    	$rsGroups = $objGroups->fetchAll(null,'level');    	
    	
    	foreach ($rsGroups as $rowGroup)
    	{
    		$this->addRole(new Zend_Acl_Role($rowGroup->role));
    	}    	
    	
//        $this->addRole(new Zend_Acl_Role('admin'));
//        $this->addRole(new Zend_Acl_Role('superadmin'));

        $this->loadResources();
        $this->loadCurrentUsersPermissions();

        //load common resources
        $this->add(new Zend_Acl_Resource('admin_auth'));

        //everybody
        $this->allow(null, 'admin_auth');

        //grant the super admin access to everything
//        $this->allow(Model_Parameters::getParam('group_admin_system'));
        $this->allow(Model_User::SUPERUSER_ROLE);

    }

    public function loadResources()
    {
    	// Co the luu cache bien $this->resourceList de xu ly tot hon
    	$cache = Zend_Registry::get('cache');
        if ($this->resourceList = $cache->load(md5('resources_list'))) {
            return;
        }

        $front = Zend_Controller_Front::getInstance();
        $ctlPaths = $front->getControllerDirectory();

        //set the path to all of the modules
        foreach ($ctlPaths as $module => $path)  
        {
            if ($module != 'public' && $module != 'front') 
            {
                //clear the resource list items
                $resourceListItems = null;
                
                //load the module resource
                $this->add(new Zend_Acl_Resource($module));
                    
                $module_acl = new Model_ModuleAcls($module);
                $xml = $module_acl->toXml();
                if(empty($xml)) continue;
                    
                $controllers = $xml->children();
                foreach ($controllers as $controller) 
                {
	                $controllerName = (string)$controller->attributes()->name;
                    $controllerActions = $controller->children();
                    if (count($controllerActions) > 0) 
                    {
                    	foreach ($controllerActions as $action) 
                    	{
                        	//load each action separately
                            $actionName = (string)$action;
                            $key = $module . '_' . $controllerName . '_' . $actionName;
                            $this->add(new Zend_Acl_Resource($key), $module);

                            //add the action to the public resource list
                            $resourceListItems[$controllerName][] = $actionName;
                        }
                    } 
                    else 
                    {
                    	//set the resource at the controller level
                        $key = $module . '_' . $controllerName;
                        $this->add(new Zend_Acl_Resource($key), $module);

                        //add the controller to the public resource list
                        $resourceListItems[$controllerName] = null;
                    }
                }
				$this->resourceList[$module] = $resourceListItems;
            }
        }
        $cache->save($this->resourceList,md5('resources_list'));
    }

    public function getResourceList()
    {
        return $this->resourceList;
    }

    public function loadCurrentUsersPermissions() {
        $user = new Model_User();
        $currentUser = $user->getCurrentUser();
        $permissions = $user->getCurrentUsersAclResources();

        if ($permissions) {
            foreach ($permissions as $key => $value) {
                if ($this->has($key)) {
                    if ($value == 1) {
                        $this->allow($currentUser->role,$key);
                    } else {
                        $this->deny($currentUser->role,$key);
                    }
                }
            }
        }
    }

    private function _getResourceFromRow($row)
    {
        if ($row->admin_section == 'module') {
            return 'mod_' . $row->controller;
        } else {
            return $row->controller;
        }
    }
}