<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Util;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
/**
 * Description of ApplicationAcl
 *
 * @author nando
 */
class ApplicationAcl extends Acl {
    //put your code here
    
    const ROL_DEFULT = 'invitado';
    
    public function __construct($config) {
        
        if(!isset($config['acl']['roles']) || !isset($config['acl']['resources'])) {
            throw new \Exception('Config de ACL invalido');
        }
        
        $roles = $config['acl']['roles'];
        $resources = $config['acl']['resources'];
        
        if(!isset($roles[self::ROL_DEFULT])) {
            $roles[self::ROL_DEFULT] = '';
        }
        
        $this->__addRole($roles)->__addResources($resources);
        
    }
    
    
    protected function __addRole($roles) {
        foreach ($roles as $rol => $padres) {
            if(!$this->hasRole($rol)) {
                if(empty($padres)) {
                    $padres = array();
                } else {
                    $padres = explode(',', $padres);
                }
            }
            
            $this->addRole(new Role($rol), $padres);
        }
        
        return $this;
    }
    
    
    protected function __addResources($resources) {
        foreach ($resources as $permissions => $controllers){
            foreach ($controllers as $controller => $actions){
                if(!$this->hasResource($controller)){
                    $this->addResource(new Resource($controller));
                }
                
                foreach ($actions as $action => $rol) {
                    if($permissions === 'allow'){
                        $this->allow($rol, $controller, $action);
                    } elseif ($permissions === 'deny') {
                        $this->deny($rol, $controller, $action);
                    } else {
                        throw new \Exception('Tipo de permiso no definido '.$permissions);
                    }
                }
            }
            
        }
        
        return $this;
    }
    
    
}
