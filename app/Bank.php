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
class Bank {
    
    public static function setBank($method, $request, $userid, $branchid){
        if($method == 0){
            $bank = array(
                'name'          => $request->name,
                'accountname'   => $request->accountname,
                'accountno'     => $request->accountno,
                'note'          => $request->note,
                'status'        => $request->status,
                'ppn'           => $request->ppn,
                'created_at'    => Date('Y-m-d h:i:s'),
                'updated_at'    => Date('Y-m-d h:i:s'),
                'created_by'    => $userid,
                'updated_by'    => $userid,
                'branchid'      => $branchid
            );
        }else{
            $bank = array(
                'name'          => $request->name,
                'accountname'   => $request->accountname,
                'accountno'     => $request->accountno,
                'note'          => $request->note,
                'status'        => $request->status,
                'ppn'           => $request->ppn,
                'updated_at'    => Date('Y-m-d h:i:s'),
                'updated_by'    => $userid,
            );
        }

        return $bank;
    }
    
    public static function insertBank($bank){
        DB::table('bank')->insert($bank);
    }

    public static function updateBank($id, $bank){
        DB::table('bank')
            ->where('id', $id)
            ->update($bank);
    }

    public static function getBankById($id){
        $bank = DB::table('bank')
                    ->where('id', $id)
                    ->get();
        
        return $bank;
    }

    public static function getDropDownBank(){
        $bank = DB::table('bank')
                    ->get();
        
        return $bank;
    }

    public static function getDataTableBank(){
        $bank = DB::table('bank')->get()
            ;
        
        return $bank;
    }

    public static function isUsedBank($id){
        $bank = DB::table('invoicemas')
                    ->where('bankid', $id)
                    ->get();
        
        if(count($bank) > 0){
            return true;
        }else{
            return false;
        }
    }

    public static function deleteBank($id){
        DB::table('bank')
            ->where('id', $id)
            ->delete();
    }

}
