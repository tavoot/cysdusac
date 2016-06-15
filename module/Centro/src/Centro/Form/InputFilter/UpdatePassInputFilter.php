<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Form\InputFilter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\NotEmpty;

/**
 * Description of UpdatePassInputFilter
 *
 * @author nando
 */
class UpdatePassInputFilter implements InputFilterAwareInterface {
    
    protected $inputFilter;
    
    public function getInputFilter() {
        if(!$this->inputFilter) {
            
            $inputFilter = new InputFilter();
            
            $inputFilter->add(array(
                'name' => 'pass_actual',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'setMessages' => array(
                                NotEmpty::IS_EMPTY => 'Campo obligatorio',
                            ),
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 4 ,
                            'max' => 15,
                            'setMessages' => array(
                                'stringLengthTooShort' => 'El password debe poseer al menos 4 caracteres',
                                'stringLengthTooLong' => 'El password ingresado es mayor al limite permitido',
                            ),
                        ),
                        'break_chain_on_failure' => true,
                    ),
                ),
            ));
            
            $inputFilter->add(array(
                'name' => 'pass_nuevo',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'setMessages' => array(
                                NotEmpty::IS_EMPTY => 'Campo obligatorio',
                            ),
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 4 ,
                            'max' => 15,
                            'setMessages' => array(
                                'stringLengthTooShort' => 'El password debe poseer al menos 4 caracteres',
                                'stringLengthTooLong' => 'El password ingresado es mayor al limite permitido',
                            ),
                        ),
                        'break_chain_on_failure' => true,
                    ),
                ),
            ));
            
            $inputFilter->add(array(
                'name' => 'pass_confirmado',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'setMessages' => array(
                                NotEmpty::IS_EMPTY => 'Campo obligatorio',
                            ),
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'Identical',
                        'options' => array(
                            'token' => 'pass_nuevo',
                            'message' => 'El valor ingresado no coincide',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                ),
            ));
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

//put your code here
}
