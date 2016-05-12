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

class Usuario implements InputFilterAwareInterface {

    public $id;
    public $tipo;
    public $usuario;
    public $password;
    public $email;
    public $pais;
    
    public $tipo_valor;
    public $ucusuario_id; //usuariocentrousuario__id
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->tipo = (!empty($data['tipo'])) ? $data['tipo'] : null;
        $this->usuario = (!empty($data['usuario'])) ? $data['usuario'] : null;
        $this->password = (!empty($data['password'])) ? $data['password'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->pais = (!empty($data['pais'])) ? $data['pais'] : null;
        
        $this->tipo_valor = (!empty($data['valor'])) ? $data['valor'] : null;
        $this->ucusuario_id = (!empty($data['usuario_id'])) ? $data['usuario_id'] : null;
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
                'name' => 'usuario',
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
                            'min' => 5,
                            'max' => 50,
                            'setMessages' => array(
                                'stringLengthTooShort' => 'El nombre de usuario debe poseer al menos 5 caracteres',
                                'stringLengthTooLong' => 'El nombre de usuario ingresado es mayor al limite permitido',
                            ),
                        ),
                        'break_chain_on_failure' => true,
                    ),
                ),
            ));
            
            $inputFilter->add(array(
                'name' => 'password',
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
                ),
            ));
            
            $inputFilter->add(array(
                'name' => 'pais',
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
                            'min' => 4,
                            'max' => 50,
                            'setMessages' => array(
                                'stringLengthTooShort' => 'La cadena ingresada debe poseer al menos 4 caracteres',
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
