<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Acl;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class AppAcl extends ZendAcl {

    const DEFAULT_ROL = 'invitado';

    public function __construct($config) {

        //validacion de definicion de roles y recursos
        if (!isset($config['acl']['roles']) || !isset($config['acl']['resources'])) {
            throw new \Exception("Invalid Acl config");
        }



        $roles = $config['acl']['roles'];
        $resources = $config['acl']['resources'];

        if (!isset($roles[self::DEFAULT_ROL])) {
            $roles[self::DEFAULT_ROL] = '';
        }


        $this->_addRoles($roles)->_addResources($resources);
    }

    /*
     * @param array $roles
     * 
     */

    protected function _addRoles($roles) {
        foreach ($roles as $name => $parent) {
            if (!$this->hasRole($name)) {
                if (empty($parent)) {
                    $parent = array();
                } else {
                    $parent = explode(',', $parent);
                }
            }

            $this->addRole(new Role($name), $parent);
        }
        return $this;
    }

    /*
     * @param array $resources
     * 
     */

    protected function _addResources($resources) {
        foreach ($resources as $permission => $controllers) {
            foreach ($controllers as $controller => $actions) {
                if ($controller === 'all') {
                    $controller = null;
                } else {
                    if (!$this->hasResource($controller)) {
                        $this->addResource(new Resource($controller));
                    }
                }

                foreach ($actions as $action => $role) {
                    if ($action === 'all') {
                        $action = null;
                    }

                    if ($permission === 'allow') {
                        $this->allow($role, $controller, $action);
                    } elseif ($permission === 'deny') {
                        $this->deny($role, $controller, $action);
                    } else {
                        throw new \Exception('No valid permission defined: ' . $permission);
                    }
                }
            }
        }
        return $this;
    }

}
