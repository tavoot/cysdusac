<?php

namespace Centro\Util;

use Zend\ServiceManager\ServiceManager;
use Zend\Db\TableGateway\TableGateway;

class TableProvider {

    const CAMBIO_TABLE = 'Centro\Model\Logic\CambioTable';
    const CANAL_TABLE = 'Centro\Model\Logic\CanalTable';
    const CATALOGO_TIPO = 'Centro\Model\Logic\CatalogoTipoTable';
    const CATALOGO_VALOR = 'Centro\Model\Logic\CatalogoValorTable';
    const CENTRO = 'Centro\Model\Logic\CentroTable';
    const CENTRO_CONTACTO = 'Centro\Model\Logic\CentroContactoTable';
    const CONTACTO = 'Centro\Model\Logic\ContactoTable';
    const ITEM = 'Centro\Model\Logic\ItemTable';
    
    const USUARIO_CENTRO = 'Centro\Model\Logic\UsuarioCentroTable';
    const VERSION = 'Centro\Model\Logic\VersionTable';
    
    
    
    /**
     *
     * @var array 
     */
    protected $table;

    /**
     *
     * @var ServiceManager
     */
    protected $sm;

    public function __construct(ServiceManager $sm) {
        $this->sm = $sm;
        $this->table = array();
    }

    /**
     * 
     * @param type $key
     * @return TableGateway
     */
    public function getTable($key) {
        if (!array_key_exists($key, $this->table)) {
            $this->table[$key] = $this->sm->get($key);
        }
        return $this->table[$key];
    }
    
    /**
     * 
     * @return ServiceManager
     */
    public function getServiceManager(){
        return $this->sm;
    }

}
