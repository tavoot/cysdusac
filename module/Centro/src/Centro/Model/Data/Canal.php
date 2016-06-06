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

class Canal implements InputFilterAwareInterface {

     public $id;
     public $tipo;
     public $titulo;
     public $descripcion;
     public $enlace;
     public $lenguaje;
     public $centro_id;
     public $habilitado;
     public $tipo_valor;
     public $secuencia;
     protected $inputFilter;
     

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->tipo  = (!empty($data['tipo'])) ? $data['tipo'] : null;
         $this->titulo  = (!empty($data['titulo'])) ? $data['titulo'] : null;
         $this->descripcion  = (!empty($data['descripcion'])) ? $data['descripcion'] : null;
         $this->enlace  = (!empty($data['enlace'])) ? $data['enlace'] : null;
         $this->lenguaje  = (!empty($data['lenguaje'])) ? $data['lenguaje'] : null;
         $this->centro_id  = (!empty($data['centro_id'])) ? $data['centro_id'] : null;
         $this->secuencia = (isset($data['secuencia'])) ? $data['secuencia'] : null;
         $this->habilitado = (isset($data['habilitado'])) ? $data['habilitado'] : null;
         $this->tipo_valor = (!empty($data['valor'])) ? $data['valor'] : null;
         
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
                'name' => 'tipo',
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
                'name'     => 'titulo',
                'required' => true,
                'filters'  => array(
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
                            'max'      => 200,
                            'setMessages' => array(
                                'stringLengthTooLong' => 'La cadena ingresada es mayor al limite permitido',
                            ),
                        ),
                        'break_chain_on_failure' => true,
                    ),
                ),
             ));
             
            $inputFilter->add(array(
                'name'     => 'enlace',
                'required' => true,
                'filters'  => array(
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
                            'max'      => 150,
                            'setMessages' => array(
                                'stringLengthTooLong' => 'Cadena de caracteres mayor al limite permitido',
                            ),
                        ),
                        'break_chain_on_failure' => true,
                    ),
                ),
            ));
            
            $inputFilter->add(array(
                'name'     => 'descripcion',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 500,
                            'setMessages' => array(
                                'stringLengthTooLong' => 'Cadena de caracteres mayor al limite permitido',
                            ),
                        ),
                        'break_chain_on_failure' => true,
                    ),
                ),
            ));
            
            $inputFilter->add(array(
                'name'     => 'lenguaje',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'Regex',
                        'options' => array(
                             'pattern' => '/[a-z]{2}|[a-z]{2}-[a-z]{2}/',
                             'message' => 'Formato no valido, permitido solo 2 letras (es) o con su variante (es-gt)',
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