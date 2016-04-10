<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CentroController extends AbstractActionController
{
    protected $centroTable;
    
    public function indexAction()
    {
        return new ViewModel(array(
             'centros' => $this->getCentroTable()->fetchAll(),
         ));
    }
    
    public function getCentroTable()
     {
         if (!$this->centroTable) {
             $sm = $this->getServiceLocator();
             $this->centroTable = $sm->get('Centro\Model\Logic\CentroTable');
         }
         return $this->centroTable;
     }
}