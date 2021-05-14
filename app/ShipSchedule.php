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
class ShipSchedule {

    public static function setShipSchedule($method, $request, $userid, $branchid){
        $doMas = array();
        $shipSchedule = array();

        for($i = 0; $i < count($request->domasid); $i++){
            $doMas[] = array(
                'id'            => $request->domasid[$i],
                'updated_at'    => Date('Y-m-d h:i:s'),
                'updated_by'    => $userid
            );
        }

        if($method == 0){
            $shipSchedule = array(
                'shipid'        => $request->shipid,
                'note'          => $request->note,
                'departdate'    => Date('Y-m-d', strtotime($request->departdate)),
                'destination'   => $request->destination,
                'voyage'        => $request->voyage,
                'depart'        => $request->depart,
                'created_at'    => Date('Y-m-d h:i:s'),
                'updated_at'    => Date('Y-m-d h:i:s'),
                'created_by'    => $userid,
                'updated_by'    => $userid,
                'branchid'      => $branchid
            );
        }else{
            $shipSchedule = array(
                'shipid'        => $request->shipid,
                'note'          => $request->note,
                'departdate'    => Date('Y-m-d', strtotime($request->departdate)),
                'destination'   => $request->destination,
                'voyage'        => $request->voyage,
                'depart'        => $request->depart,
                'updated_at'    => Date('Y-m-d h:i:s'),
                'updated_by'    => $userid,
            );
        }

        $gab = array(
            'shipSchedule' => $shipSchedule,
            'doMas' => $doMas,
        );

        return $gab;
    }

    public static function insertShipSchedule($gab){
        DB::beginTransaction();

        try {

            $shipScheduleId = DB::table('shipschedule')
                    ->insertGetId($gab['shipSchedule'])
                    ;

            $arrDoMas = array('shipscheduleid' => $shipScheduleId);

            for($i = 0; $i < count($gab['doMas']); $i++){
                $gab['doMas'][$i] = array_merge($gab['doMas'][$i], $arrDoMas);

                DB::table('domas')
                    ->where('id', $gab['doMas'][$i]['id'])
                    ->update($gab['doMas'][$i])
                    ;
            }
        } catch(\Exception $e){
            DB::rollback();
            throw $e;
        }

        DB::commit();

        return $shipScheduleId;
    }

    public static function updateShipSchedule($id, $gab){

        DB::beginTransaction();

        try {

            DB::table('shipschedule')
                ->where('id', $id)
                ->update($gab['shipSchedule'])
                ;

            DB::update('UPDATE domas SET shipscheduleid = NULL WHERE shipscheduleid=?', array($id));

            for($i = 0; $i < count($gab['doMas']); $i++){
                if($gab['doMas'][$i]['id'] != '0'){
                    DB::update('UPDATE domas SET shipscheduleid = ? WHERE id=?',
                        array($id, $gab['doMas'][$i]['id']));
                }
            }


        } catch(\Exception $e){
            DB::rollback();
            throw $e;
        }

        DB::commit();
    }

    public static function getShipScheduleById($id){
        $shipSchedule = DB::table('shipschedule')
                    ->where('id', $id)
                    ->get();

        return $shipSchedule;
    }

    public static function getDoMasByShipScheduleId($id){
        // $doMas = DB::table('domas')
        //             ->where('shipscheduleid', $id)
        //             ->get();

        $doMas = DB::select('SELECT domas.*, sender.name AS sendername, recipient.name AS recname FROM domas INNER JOIN sender ON domas.senderid = sender.id
            INNER JOIN recipient ON domas.recipientid = recipient.id WHERE shipscheduleid = ?', array($id));

        return $doMas;
    }

    public static function getDropDownShipSchedule(){
        $shipSchedule = DB::table('shipschedule')
                    ->get();

        return $shipSchedule;
    }

    public static function getDataTableShipSchedule(){
        $shipSchedule = DB::select('SELECT a.*, b.name AS shipname FROM shipschedule a INNER JOIN ship b ON a.shipid = b.id')
            ;

        return $shipSchedule;
    }

    public static function searchTableList($request){
        $departDate = $request->departdate == NULL ? '' : Date('Y-m-d', strtotime($request->departdate));
        $shipId = $request->shipid == NULL ? 0 : $request->shipid;
        $destination = $request->destination == NULL ? '' : $request->destination;
        $note = $request->note == NULL ? '' : $request->note;

        $sql = "SELECT mas.*, ship.name AS shipname
            FROM shipschedule mas
            INNER JOIN ship ON ship.id = mas.shipid
            WHERE (mas.departdate = :departdate1 OR :departdate2 = '')
                AND (mas.shipid = :shipid1 OR :shipid2 = 0)
                AND (mas.destination LIKE CONCAT(:destination1,'%') OR :destination2 = '')
                AND (mas.note LIKE CONCAT(:note1,'%') OR :note2 = '')
            ORDER BY mas.departdate
            "
            ;

        $listShipSchedule = DB::select($sql, array(
            'departdate1' => $departDate, 'departdate2' => $departDate,
            'shipid1' => $shipId, 'shipid2' => $shipId,
            'destination1' => $destination, 'destination2' => $destination,
            'note1' => $note, 'note2' => $note,
        ));

        return $listShipSchedule;
    }

    public static function isUsedShipSchedule($id){
        $shipschedule = DB::table('domas')
                    ->where('shipscheduleid', $id)
                    ->get();

        if(count($shipschedule) > 0){
            return true;
        }else{
            return false;
        }
    }

    public static function deleteShipSchedule($id){
        DB::table('shipschedule')
            ->where('id', $id)
            ->delete();
    }


}
