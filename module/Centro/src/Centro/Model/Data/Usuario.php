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

class Usuario implements InputFilterAwareInterface {

    public $id;
    public $tipo;
    public $usuario;
    public $password;
    public $email;
    public $pais;
    public $tipo_valor;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->tipo = (!empty($data['tipo'])) ? $data['tipo'] : null;
        $this->usuario = (!empty($data['usuario'])) ? $data['usuario'] : null;
        $this->password = (!empty($data['password'])) ? $data['password'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->pais = (!empty($data['pais'])) ? $data['pais'] : null;
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
                'name' => 'usuario',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 100,
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}
