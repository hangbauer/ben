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
class Recipient {

    public static function setRecipient($method, $request, $userid, $branchid){
        if($method == 0){
            $recipient = array(
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
            $recipient = array(
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

        return $recipient;
    }

    public static function insertRecipient($recipient){
        DB::table('recipient')->insert($recipient);
    }

    public static function updateRecipient($id, $recipient){
        DB::table('recipient')
            ->where('id', $id)
            ->update($recipient);
    }

    public static function getRecipientById($id){
        $recipient = DB::table('recipient')
                    ->where('id', $id)
                    ->get();

        return $recipient;
    }

    public static function getDropDownRecipient(){
        $recipient = DB::table('recipient')
                    ->orderBy('name')
                    ->get()
                    ;

        return $recipient;
    }

    public static function getDataTableRecipient(){
        $recipient = DB::table('recipient')->get()
            ;

        return $recipient;
    }

    public static function isUsedRecipient($id){
        $recipient = DB::table('domas')
                    ->where('recipientid', $id)
                    ->get();

        if(count($recipient) > 0){
            return true;
        }else{
            return false;
        }
    }

    public static function deleteRecipient($id){
        DB::table('recipient')
            ->where('id', $id)
            ->delete();
    }

    public static function getDropDownTitle(){
        $title = DB::table('title')
                    ->get();

        return $title;
    }
}
