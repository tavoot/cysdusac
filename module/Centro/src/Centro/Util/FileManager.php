<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Centro\Util;

/**
 * Description of FileManager
 *
 * @author nando
 */
class FileManager {

    // directorios de generacion de archivos y directorios
    const PATH_CONFIG = 'public/apprelaciger/data/config/';
    const PATH_CENTROS = 'public/apprelaciger/data/centros/';
    

    public static function addCentroFolder($centro_id) {
        $result = true;
        
        // carpetas a crear para un centro
        // canal
        if(!file_exists(self::PATH_CENTROS."$centro_id/canal")) {
            $result = mkdir(self::PATH_CENTROS."$centro_id/canal", 0777, true);
            if(!$result) return $result;
        }
        //estadistica
        if(!file_exists(self::PATH_CENTROS."$centro_id/estadistica/canales")) {
            $result = mkdir(self::PATH_CENTROS."$centro_id/estadistica/canales", 0777, true);
            if(!$result) return $result;
        }
        // img
        if(!file_exists(self::PATH_CENTROS."$centro_id/img")) {
            $result = mkdir(self::PATH_CENTROS."$centro_id/img", 0777, true);
            if(!$result) return $result;
        }
        
        // archivo estadistico de detalle del centro
        $result = touch(self::PATH_CENTROS."$centro_id/estadistica/detalle.php");
        
        return $result;
    }
    
    public static function removeCentroFolder($centro_id) {
        
    }
    
    
    public static function initRelacigerDirectory() {
        $result = true;
        
        // carpetas de la estructura principal
        if(!file_exists(self::PATH_CENTROS)) {
            $result = mkdir(self::PATH_CENTROS, 0777, true);
            if(!$result) return $result;
        }
        if(!file_exists(self::PATH_CONFIG)) {
            $result = mkdir(self::PATH_CONFIG, 0777, true);
            if(!$result) return $result;
        }
        
        // carpeta de centro relaciger va por default
        if(!file_exists(self::PATH_CENTROS.'relaciger/estadisticas')) {
            $result = mkdir(self::PATH_CENTROS.'relaciger/estadisticas', 0777, true);
            if(!$result) return $result;
        }
        if(!file_exists(self::PATH_CENTROS.'relaciger/img')) {
            $result = mkdir(self::PATH_CENTROS.'relaciger/img', 0777, true);
            if(!$result) return $result;
        }
        
        // archivos estadisticos relaciger
        touch(self::PATH_CENTROS.'relaciger/estadisticas/descripcion.php');
        touch(self::PATH_CENTROS.'relaciger/estadisticas/mision.php');
        touch(self::PATH_CENTROS.'relaciger/estadisticas/vision.php');
        // archivos de configuracion
        touch(self::PATH_CONFIG.'centros.xml');
        touch(self::PATH_CONFIG.'control.xml');
        touch(self::PATH_CONFIG.'relaciger.xml');
        
        return $result;
    }
    
    
    public static function addCanalFile($centro_id, $canal_secuencia) {
        $result = true;
        
        $result = touch(self::PATH_CENTROS."$centro_id/estadistica/canales/canal_$canal_secuencia.php");
        return $result;
    }
    
    
}
