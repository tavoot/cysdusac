<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Model\Data;


use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\NotEmpty;


class Contacto
{
     public $id;
     public $nombre;
     public $email;
     public $telefono;
     public $puesto;
     public $centro_id;
     
     //public $centro_siglas;
     
    protected $inputFilter;

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->nombre  = (!empty($data['nombre'])) ? $data['nombre'] : null;
         $this->email  = (!empty($data['email'])) ? $data['email'] : null;
         $this->telefono  = (!empty($data['telefono'])) ? $data['telefono'] : null;
         $this->puesto = (!empty($data['puesto'])) ? $data['puesto'] : null;
         $this->centro_id = (!empty($data['centro_id'])) ? $data['centro_id'] : null;
         
         //$this->centro_siglas= (!empty($data['siglas'])) ? $data['siglas'] : null;
         
     }
     
     public function getArrayCopy() {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'id',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ));
            
            $inputFilter->add(array(
                'name' => 'centro_id',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'nombre',
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
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 75,
                            'setMessages' => array(
                                'stringLengthTooLong' => 'La cadena ingresada es mayor al limite permitido',
                            ),
                        ),
                        'break_chain_on_failure' => true,
                    ),
                 ),
            ));

            $inputFilter->add(array(
                'name' => 'email',
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
                        'name' => 'EmailAddress',
                        'options' => array(
                            'message' => 'Direccion de email no valida',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 50,
                            'setMessages' => array(
                                'stringLengthTooLong' => 'La cadena del correo ingresada es mayor al limite permitido',
                            ),
                        ),
                        'break_chain_on_failure' => true,
                    ),
                 ),
            ));
            
            $inputFilter->add(array(
                'name' => 'telefono',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                         'name'    => 'StringLength',
                         'options' => array(
                             'encoding' => 'UTF-8',
                             'min'      => 1,
                             'max'      => 15,
                             'setMessages' => array(
                                'stringLengthTooLong' => 'El numero excede la cantidad de digitos permitidos (15)',
                            ),
                         ),
                         'break_chain_on_failure' => true,
                     ),
                     array(
                         'name'    => 'Digits',
                         'options' => array(
                             'message' => 'El campo solo recibe valores numericos',
                         ),
                         'break_chain_on_failure' => true,
                     ),
                 ),
            ));
            
            $inputFilter->add(array(
                'name' => 'puesto',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 45,
                            'setMessages' => array(
                                'stringLengthTooLong' => 'La cadena ingresada es mayor al limite permitido',
                            ),
                        ),
                        'break_chain_on_failure' => true,
                    ),
                 ),
            ));
            
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

 }