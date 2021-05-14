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
class Ship {
    
    public static function setShip($method, $request, $userid, $branchid){
        if($method == 0){
            $ship = array(
                'name'          => $request->name,
                'note'          => $request->note,
                'status'        => $request->status,
                'created_at'    => Date('Y-m-d h:i:s'),
                'updated_at'    => Date('Y-m-d h:i:s'),
                'created_by'    => $userid,
                'updated_by'    => $userid,
                'branchid'      => $branchid
            );
        }else{
            $ship = array(
                'name'          => $request->name,
                'note'          => $request->note,
                'status'        => $request->status,
                'updated_at'    => Date('Y-m-d h:i:s'),
                'updated_by'    => $userid,
            );
        }

        return $ship;
    }
    
    public static function insertShip($ship){
        DB::table('ship')->insert($ship);
    }

    public static function updateShip($id, $ship){
        DB::table('ship')
            ->where('id', $id)
            ->update($ship);
    }

    public static function getShipById($id){
        $ship = DB::table('ship')
                    ->where('id', $id)
                    ->get();
        
        return $ship;
    }

    public static function getDropDownShip(){
        $ship = DB::table('ship')
                    ->get();
        
        return $ship;
    }

    public static function getDataTableShip(){
        $ship = DB::table('ship')->get()
            ;
        
        return $ship;
    }

    public static function isUsedShip($id){
        $ship = DB::table('shipschedule')
                    ->where('shipid', $id)
                    ->get();
        
        if(count($ship) > 0){
            return true;
        }else{
            return false;
        }
    }

    public static function deleteShip($id){
        DB::table('ship')
            ->where('id', $id)
            ->delete();
    }

}
