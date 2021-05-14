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
class Sender {

    public static function setSender($method, $request, $userid, $branchid){
        if($method == 0){
            $sender = array(
                'title'         => $request->title,
                'name'          => $request->name,
                'address'       => $request->address,
                'city'          => $request->city,
                'phone'         => $request->phone,
                'email'         => $request->email,
                'note'          => $request->note,
                'status'        => $request->status,
                'created_at'    => Date('Y-m-d h:i:s'),
                'updated_at'    => Date('Y-m-d h:i:s'),
                'created_by'    => $userid,
                'updated_by'    => $userid,
                'branchid'      => $branchid
            );
        }else{
            $sender = array(
                'title'         => $request->title,
                'name'          => $request->name,
                'address'       => $request->address,
                'city'          => $request->city,
                'phone'         => $request->phone,
                'email'         => $request->email,
                'note'          => $request->note,
                'status'        => $request->status,
                'updated_at'    => Date('Y-m-d h:i:s'),
                'updated_by'    => $userid,
            );
        }

        return $sender;
    }

    public static function insertSender($sender){
        DB::table('sender')->insert($sender);
    }

    public static function updateSender($id, $sender){
        DB::table('sender')
            ->where('id', $id)
            ->update($sender);
    }

    public static function getSenderById($id){
        $sender = DB::table('sender')
                    ->where('id', $id)
                    ->get();

        return $sender;
    }

    public static function getDropDownSender(){
        $sender = DB::table('sender')
                    ->orderBy('name')
                    ->get()
                    ;

        return $sender;
    }

    public static function getDataTableSender(){
        $sender = DB::table('sender')->get()
            ;

        return $sender;
    }

    public static function isUsedSender($id){
        $sender = DB::table('domas')
                    ->where('senderid', $id)
                    ->get();

        if(count($sender) > 0){
            return true;
        }else{
            return false;
        }
    }

    public static function deleteSender($id){
        DB::table('sender')
            ->where('id', $id)
            ->delete();
    }

    public static function getDropDownTitle(){
        $title = DB::table('title')
                    ->get();

        return $title;
    }
}
