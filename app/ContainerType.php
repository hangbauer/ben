<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;
use DB;

/**
 * Description of Pelanggan
 *
 * @author hang
 */
class ContainerType {
        
    public static function getDropDownContainerType(){
        $containerType = DB::table('containerType')
                    ->get();
        
        return $containerType;
    }
    
}
