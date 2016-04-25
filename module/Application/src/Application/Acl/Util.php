<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Util;

class Util {
    public static function getRole($rol) {
        switch ($rol) {
            case 1 : //estudiante
                return 'estudiante'; // guest
            case 2 : //docente
                return 'docente';
            case 3 : //administrativo
                return 'administrativo';
            case 4 :
                return 'aspirante';

            default :
                return Acl::DEFAULT_ROLE; // visitante
        }
    }

}
