<?php



namespace Application\Util;

use Zend\Mvc\Controller\AbstractActionController;
use Centro\Util\TableProvider;

class MyActionController extends AbstractActionController {
    
    /**
     *
     * @var TableProvider 
     */
    protected $tableProvider; 


    /**
     * 
     * @return TableProvider
     */
    protected function getTableProvider(){
        
        if(!$this->tableProvider){
            $this->tableProvider=new TableProvider($this->getServiceLocator());
        }
       
        return $this->tableProvider;
    }
    
}
