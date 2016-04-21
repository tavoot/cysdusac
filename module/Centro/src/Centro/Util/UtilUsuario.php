<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Util;

use Zend\Crypt\BlockCipher;

/**
 * Description of UtilUsuario
 *
 * @author nando
 */
class UtilUsuario {
    //put your code here
    
    protected $config;
    
    
    public function __construct($config) {
        $this->config = $config;
    }

    public function cifrar($password){
        $config = $this->config;
        
        $cipher = BlockCipher::factory($config['adapter'], $config['parameters']);
        $cipher->setSalt($config['parameters']['salt']);
        $cipher->setKey($config['key']);
        $resultado = $cipher->encrypt($password);
        
        return $resultado;
    }
    
    public function decifrar($password){
        $config = $this->config;
        
        $cipher = BlockCipher::factory($config['adapter'], $config['parameters']);
        $cipher->setSalt($config['parameters']['salt']);
        $cipher->setKey($config['key']);
        $resultado = $cipher->decrypt($password);
        
        return $resultado;
    }
    
    public function getCadenaAleatoria($length=10,$uc=true,$n=true,$sc=true){
        $source='abcdefghijklmnopqrstuvwxyz';
        if($uc==1){ $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; }
        if($n==1) { $source .= '1234567890'; }
        if($sc==1){ $source .= '|@#~$%()=^*+[]{}-_'; }
	if($length>0){
	    $result = "";
	    $source = str_split($source,1);
	    for($i=1; $i<=$length; $i++){
	        mt_srand((double)microtime() * 1000000);
	        $num = mt_rand(1,count($source));
	        $result .= $source[$num-1];
	    }
	 
	}
	return $result;
    }
}
