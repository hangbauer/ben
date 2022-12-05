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
class DeliveryOrder {

    public static function setDeliveryOrder($method, $request, $userid, $branchid){
        $doDtl = array();
        $doMas = array();

        if($method == 0){
            for($i = 0; $i < count($request->itemname); $i++){
                $doDtl[] = array(
                    'itemname'      => $request->itemname[$i],
                    'itemorder'     => (float)str_replace(",", "", $request->itemorder[$i]),
                    'itemunit'      => $request->itemunit[$i],
                    'volume'        => $request->volume[$i],
                    'note'          => '',
                    'created_at'    => Date('Y-m-d h:i:s'),
                    'updated_at'    => Date('Y-m-d h:i:s'),
                    'created_by'    => $userid,
                    'updated_by'    => $userid,
                    'branchid'      => $branchid
                );
            }
        }else{
            for($i = 0; $i < count($request->itemname); $i++){
                $doDtl[] = array(
                    'id'            => $request->dtlid[$i],
                    'itemname'      => $request->itemname[$i],
                    'itemorder'     => (float)str_replace(",", "", $request->itemorder[$i]),
                    'itemunit'      => $request->itemunit[$i],
                    'volume'        => $request->volume[$i],
                    'note'          => '',
                    'updated_at'    => Date('Y-m-d h:i:s'),
                    'updated_by'    => $userid,
                    'branchid'      => $branchid
                );
            }
        }

        if($method == 0){
            $doMas = array(
                'receiptno'     => $request->receiptno,
                'dodate'        => Date('Y-m-d', strtotime($request->dodate)),
                'paymenttypeid' => $request->paymenttypeid == '' ? NULL : $request->paymenttypeid,
                'senderid'      => $request->senderid == '' ? NULL : $request->senderid,
                'recipientid'   => $request->recipientid == '' ? NULL : $request->recipientid,
                'termid'        => $request->termid == '' ? NULL : $request->termid,
                'fullcontainerflag' => isset($request->fullcontainerflag) ? 1 : 0,
                'containername' => $request->containername,
                'containertypeid' => $request->containertypeid == '' ? NULL : $request->containertypeid,
                'seal'          => $request->seal,
                'note'          => $request->note,
                'carflag'       => $request->carflag,
                'created_at'    => Date('Y-m-d h:i:s'),
                'updated_at'    => Date('Y-m-d h:i:s'),
                'created_by'    => $userid,
                'updated_by'    => $userid,
                'branchid'      => $branchid
            );
        }else{
            $doMas = array(
                'receiptno'     => $request->receiptno,
                'dodate'        => Date('Y-m-d', strtotime($request->dodate)),
                'paymenttypeid' => $request->paymenttypeid == '' ? NULL : $request->paymenttypeid,
                'senderid'      => $request->senderid == '' ? NULL : $request->senderid,
                'recipientid'   => $request->recipientid == '' ? NULL : $request->recipientid,
                'termid'        => $request->termid == '' ? NULL : $request->termid,
                'fullcontainerflag' => isset($request->fullcontainerflag) ? 1 : 0,
                'containername' => $request->containername,
                'containertypeid' => $request->containertypeid == '' ? NULL : $request->containertypeid,
                'seal'          => $request->seal,
                'note'          => $request->note,
                'carflag'       => $request->carflag,
                'updated_at'    => Date('Y-m-d h:i:s'),
                'updated_by'    => $userid,

            );
        }

        $do = array(
            'doMas' => $doMas,
            'doDtl' => $doDtl,
        );

        return $do;
    }

    public static function insertDeliveryOrder($do){

        DB::beginTransaction();

        try {

            $doMasId = DB::table('domas')
                    ->insertGetId($do['doMas'])
                    ;

            $arrDoMas = array('domasid' => $doMasId);

            for($i = 0; $i < count($do['doDtl']); $i++){
                $do['doDtl'][$i] = array_merge($do['doDtl'][$i], $arrDoMas);
            }

            DB::table('dodtl')
                    ->insert($do['doDtl'])
                    ;

        } catch(\Exception $e){
            DB::rollback();
            throw $e;
        }

        DB::commit();

        return $doMasId;
    }

    public static function updateDeliveryOrder($id, $do){
        DB::beginTransaction();

        try {

            DB::table('domas')
                ->where('id', $id)
                ->update($do['doMas'])
                ;

            $doDtlId = array();

            for($i = 0; $i < count($do['doDtl']); $i++){
                if($do['doDtl'][$i]['id'] != '0'){
                    $doDtlId[] = $do['doDtl'][$i]['id'];
                }
            }

            if(!empty($doDtlId)){
                DB::table('dodtl')
                        ->where('domasid', $id)
                        ->whereNotIn('id', $doDtlId)
                        ->delete()
                        ;
            }else{
                DB::table('dodtl')
                        ->where('domasid', $id)
                        ->delete()
                        ;
            }

            for($i = 0; $i < count($do['doDtl']); $i++){
                if($do['doDtl'][$i]['id'] != '0'){
                    DB::table('dodtl')
                        ->where('domasid', $id)
                        ->where('id', $do['doDtl'][$i]['id'])
                        ->update($do['doDtl'][$i])
                        ;
                }else{
                    $do['doDtl'][$i] = array_merge($do['doDtl'][$i], array(
                        'domasid' => $id,
                        'created_at' => Date('Y-m-d'),
                        'created_by' => $do['doDtl'][$i]['updated_by']
                    ));

                    DB::table('dodtl')
                        ->insert($do['doDtl'][$i])
                        ;
                }
            }

        } catch(\Exception $e){
            DB::rollback();
            throw $e;
        }

        DB::commit();
    }

    public static function getDeliveryOrderById($id){
        $deliveryOrder = DB::table('deliveryorder')
                    ->where('id', $id)
                    ->get();

        return $deliveryOrder;
    }

    public static function getDropDownDeliveryOrder(){
        $deliveryOrder = DB::table('domas')
                    ->get();

        return $deliveryOrder;
    }

    public static function getDoMasById($id){
        $doMas = DB::table('domas')
                    ->where('id', $id)
                    ->get();

        return $doMas;
    }

    public static function getDoDtlByDoMasID($id){
        $doDtl = DB::table('dodtl')
                    ->where('domasid', $id)
                    ->get();

        return $doDtl;
    }

    public static function getDataTableDeliveryOrder(){
        $deliveryOrder = DB::select(
            'SELECT a.*, b.name AS sendername, c.name AS recipientname
            FROM domas a
            INNER JOIN sender b ON a.senderid = b.id
            INNER JOIN recipient c ON a.recipientid = c.id
            ')
            ;

        return $deliveryOrder;
    }

    public static function searchList($request){
        $sql = "SELECT mas.*, sender.name AS sendername, recipient.name AS recname, invmas.invoiceno
            FROM domas mas
            LEFT JOIN sender ON sender.id = mas.senderid
            LEFT JOIN recipient ON recipient.id = mas.recipientid
            LEFT JOIN invoicedtl invdtl ON invdtl.domasid = mas.id
            LEFT JOIN invoicemas invmas ON invmas.id = invdtl.invoicemasid
            WHERE IFNULL(shipscheduleid,'') = ''
            ORDER BY mas.containername
            "
            ;

        $listDeliveryOrder = DB::select($sql);

        return $listDeliveryOrder;
    }

    public static function searchListInv($request){
        if($request->invoicetypeid == '0'){
            // $sql = "SELECT mas.*,con.name AS conname,rec.name AS recname
            //     FROM domas mas 
            //     JOIN containertype con ON mas.containertypeid = con.id 
            //     JOIN recipient rec ON mas.recipientid = rec.id
            //     JOIN shipschedule sch ON sch.id = mas.shipscheduleid
            //     LEFT JOIN invoicedtl dtl ON dtl.domasid = mas.id
            //     WHERE IFNULL(mas.shipscheduleid,'') <> ''
            //     AND IFNULL(dtl.domasid,'') = ''
            //     AND mas.senderid = ?
            //     AND sch.shipid = ?
            //     AND sch.departdate = ?
            //     "
            //     ;
            
            $sql = "SELECT mas.*,con.name AS conname,rec.name AS recname
                FROM domas mas 
                JOIN containertype con ON mas.containertypeid = con.id 
                JOIN recipient rec ON mas.recipientid = rec.id
                JOIN shipschedule sch ON sch.id = mas.shipscheduleid
                LEFT JOIN invoicedtl dtl ON dtl.domasid = mas.id
                WHERE IFNULL(mas.shipscheduleid,'') <> ''                
                AND mas.senderid = ?
                AND sch.shipid = ?
                AND sch.departdate = ?
                "
                ;

            $listDeliveryOrder = DB::select($sql, array($request->senderid, $request->shipid, $request->departdate));
        }else{
            // $sql = "SELECT mas.*,con.name AS conname,rec.name AS recname
            //     FROM domas mas 
            //     JOIN containertype con ON mas.containertypeid = con.id 
            //     JOIN recipient rec ON mas.recipientid = rec.id
            //     JOIN shipschedule sch ON sch.id = mas.shipscheduleid
            //     LEFT JOIN invoicedtl dtl ON dtl.domasid = mas.id
            //     WHERE IFNULL(mas.shipscheduleid,'') <> ''
            //     AND IFNULL(dtl.domasid,'') = ''
            //     AND mas.recipientid = ?
            //     AND sch.shipid = ?
            //     AND sch.departdate = ?
            //     "
            //     ;

            $sql = "SELECT mas.*,con.name AS conname,rec.name AS recname
                FROM domas mas 
                JOIN containertype con ON mas.containertypeid = con.id 
                JOIN recipient rec ON mas.recipientid = rec.id
                JOIN shipschedule sch ON sch.id = mas.shipscheduleid
                LEFT JOIN invoicedtl dtl ON dtl.domasid = mas.id
                WHERE IFNULL(mas.shipscheduleid,'') <> ''                
                AND mas.recipientid = ?
                AND sch.shipid = ?
                AND sch.departdate = ?
                "
                ;

            $listDeliveryOrder = DB::select($sql, array($request->recipientid, $request->shipid, $request->departdate));
        }


        return $listDeliveryOrder;
    }

    public static function searchTableList($request){
        $receiptNo = $request->receiptno == NULL ? '' : $request->receiptno;
        $doDate = $request->dodate == NULL ? '' : Date('Y-m-d', strtotime($request->dodate));
        $senderId = $request->senderid == NULL ? 0 : $request->senderid;
        $recipientId = $request->recipientid == NULL ? 0 : $request->recipientid;
        $note = $request->note == NULL ? '' : $request->note;
        $containername = $request->containername == NULL ? '' : $request->containername;
        $tipe = $request->tipe == NULL ? '' : $request->tipe;
        
        if($tipe == ''){
            $sql = "SELECT mas.*, sender.name AS sendername, recipient.name AS recipientname
                FROM domas mas
                LEFT JOIN sender ON sender.id = mas.senderid
                LEFT JOIN recipient ON recipient.id = mas.recipientid
                WHERE (mas.receiptno LIKE CONCAT(:receiptno1,'%') OR :receiptno2 = '')
                    AND (mas.dodate = :dodate1 OR :dodate2 = '')
                    AND (mas.senderid = :senderid1 OR :senderid2 = 0)
                    AND (mas.recipientid = :recipientid1 OR :recipientid2 = 0)
                    AND (mas.note LIKE CONCAT(:note1,'%') OR :note2 = '')
                    AND (mas.containername LIKE CONCAT(:containername1,'%') OR :containername2 = '')
                ORDER BY mas.dodate DESC             
                "
                ;
        }else{
            $sql = "SELECT mas.*, sender.name AS sendername, recipient.name AS recipientname
                FROM domas mas
                LEFT JOIN sender ON sender.id = mas.senderid
                LEFT JOIN recipient ON recipient.id = mas.recipientid
                WHERE (mas.receiptno LIKE CONCAT(:receiptno1,'%') OR :receiptno2 = '')
                    AND (mas.dodate = :dodate1 OR :dodate2 = '')
                    AND (mas.senderid = :senderid1 OR :senderid2 = 0)
                    AND (mas.recipientid = :recipientid1 OR :recipientid2 = 0)
                    AND (mas.note LIKE CONCAT(:note1,'%') OR :note2 = '')
                    AND (mas.containername LIKE CONCAT(:containername1,'%') OR :containername2 = '')
                    AND mas.shipscheduleid IS NULL
                ORDER BY mas.dodate DESC
                "
                ;
        }        

        $listDeliveryOrder = DB::select($sql, array(
            'receiptno1' => $receiptNo, 'receiptno2' => $receiptNo,
            'dodate1' => $doDate, 'dodate2' => $doDate,
            'senderid1' => $senderId, 'senderid2' => $senderId,
            'recipientid1' => $recipientId, 'recipientid2' => $recipientId,
            'note1' => $note, 'note2' => $note,
            'containername1' => $containername, 'containername2' => $containername,
        ));

        return $listDeliveryOrder;
    }

    public static function isUsedDeliveryOrder($id){
        $domas = DB::table('invoicedtl')
                    ->where('domasid', $id)
                    ->get();

        if(count($domas) > 0){
            return true;
        }else{
            return false;
        }
    }

    public static function deleteDeliveryOrder($id){
        DB::table('dodtl')
            ->where('domasid', $id)
            ->delete();

        DB::table('domas')
            ->where('id', $id)
            ->delete();
    }

    public static function updateSplit($request){	        	
        for($i = 0; $i < count($request['subdtl-id']); $i++){
			if($request['subdtl-invoicetypeid'][$i] == '0'){
				if($request['subdtl-id'][$i] != '0'){
					$sql = "UPDATE dodtl 
						SET itemordersender = :itemordersender, note = :note
						WHERE 
							domasid = :domasid
							AND id = :id
						";
					DB::update($sql, array(
						'itemordersender' => $request['subdtl-itemordersender'][$i],
                        'note' => $request['subdtl-note'][$i],
						'domasid' => $request['subdtl-domasid'][$i],
						'id' => $request['subdtl-id'][$i],
					));
				}
			}else{
				if($request['subdtl-id'][$i] != '0'){
					$sql = "UPDATE dodtl 
						SET itemorderrecipient = :itemorderrecipient, note = :note
						WHERE 
							domasid = :domasid
							AND id = :id
						";
					DB::update($sql, array(
						'itemorderrecipient' => $request['subdtl-itemorderrecipient'][$i],
                        'note' => $request['subdtl-note'][$i],
						'domasid' => $request['subdtl-domasid'][$i],
						'id' => $request['subdtl-id'][$i],
					));
				}
			}
        }        
    }

}
