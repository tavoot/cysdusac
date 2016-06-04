<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Validator;

use Zend\Validator\AbstractValidator;

/**
 * Description of ImageDimension
 *
 * @author nando
 */
class ImageDimension extends AbstractValidator {
    
    const DIMENSION = 'dimension';
    
    protected $messageTemplates = array(
        self::DIMENSION => "La imagen debe tener la misma dimension de alto y ancho",
    );
    
    public function isValid($fileInput) {
        
        $imageInfo = getimagesize($fileInput);
        
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        
        if($width != $height) {
            $this->error(self::DIMENSION);
            return false;
        }
        
        return true;
    }


}
