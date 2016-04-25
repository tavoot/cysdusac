<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Adapter;

use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;
use Zend\Db\Adapter\Adapter as DbAdapter;

/**
 * Description of UsuarioAdapter
 *
 * @author nando
 */
class UsuarioAdapter extends AuthAdapter {
    //put your code here
    
   
    protected $tablaNombre = 'usuario';
    protected $columnaUsuario = 'usuario';
    protected $columnaPassword = 'password';


    public function __construct(DbAdapter $zendDb) {
        parent::__construct($zendDb);
        
        $this->setTableName($this->tablaNombre);
        $this->setIdentityColumn($this->columnaUsuario);
        $this->setCredentialColumn($this->columnaPassword);
        
    }
    
    public function setDatos($usuario, $password) {
        $this->setIdentity($usuario);
        $this->setCredential($password);
    }
    
    
}
