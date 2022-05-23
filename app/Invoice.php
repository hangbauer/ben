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
class Invoice {
    
    public static function setInvoice($method, $request, $userid, $branchid){
        $invoiceMas = array();
        $invoiceDtl = array();
        $amount = 0;
        
        if($method == 0){
            for($i = 0; $i < count($request->invoicedtlid); $i++){
                $invoiceDtl[] = array(
                    'domasid'       => $request->domasid[$i],
                    'amount'        => (float)str_replace(",", "", $request->amount[$i]),
                    // 'note'          => $request->note[$i],
                    'created_at'    => Date('Y-m-d h:i:s'),
                    'updated_at'    => Date('Y-m-d h:i:s'),                    
                    'created_by'    => $userid,
                    'updated_by'    => $userid,
                    'branchid'      => $branchid
                );

                $amount += (float)str_replace(",", "", $request->amount[$i]);
            }
        }else{
            for($i = 0; $i < count($request->invoicedtlid); $i++){
                $invoiceDtl[] = array(
                    'id'            => $request->invoicedtlid[$i],
                    'domasid'       => $request->domasid[$i],
                    'amount'        => (float)str_replace(",", "", $request->amount[$i]),
                    // 'note'          => $request->note[$i],
                    'updated_at'    => Date('Y-m-d h:i:s'),                    
                    'updated_by'    => $userid,
                    'branchid'      => $branchid
                );

                $amount += (float)str_replace(",", "", $request->amount[$i]);
            }
        }
        
        if($method == 0){
            $invoiceMas = array(
                'invoiceno'     => $request->invoiceno,
                'senderid'      => $request->senderid,
                'recipientid'   => $request->recipientid,
                'invoicedate'   => Date('Y-m-d', strtotime($request->invoicedate)),
                'duedate'       => Date('Y-m-d', strtotime($request->duedate)),
                'ppnpercent'    => $request->ppnpercent,
                'bankid'        => $request->bankid,
                'invoicetypeid' => $request->invoicetypeid,
                'note'          => $request->note,
                'amount'        => $amount + ($request->ppnpercent * ($amount / 100)) + (float)str_replace(",", "", $request->insurance),
                'ppnamount'     => ($request->ppnpercent * ($amount / 100)),
                'insurance'     => (float)str_replace(",", "", $request->insurance),
                'quarantine'    => (float)str_replace(",", "", $request->quarantine),
                'status'        => 0,      
                'created_at'    => Date('Y-m-d h:i:s'),
                'updated_at'    => Date('Y-m-d h:i:s'),
                'created_by'    => $userid,
                'updated_by'    => $userid,
                'branchid'      => $branchid
            );
        }else{
            $invoiceMas = array(
                'invoiceno'     => $request->invoiceno,
                'senderid'      => $request->senderid,
                'recipientid'   => $request->recipientid,
                'invoicedate'   => Date('Y-m-d', strtotime($request->invoicedate)),
                'duedate'       => Date('Y-m-d', strtotime($request->duedate)),
                'ppnpercent'    => $request->ppnpercent,
                'bankid'        => $request->bankid,
                'invoicetypeid' => $request->invoicetypeid,
                'note'          => $request->note,      
                'amount'        => $amount + ($request->ppnpercent * ($amount / 100)) + (float)str_replace(",", "", $request->insurance),
                'ppnamount'     => ($request->ppnpercent * ($amount / 100)),
                'insurance'     => (float)str_replace(",", "", $request->insurance),
                'quarantine'    => (float)str_replace(",", "", $request->quarantine),
                'updated_at'    => Date('Y-m-d h:i:s'),
                'updated_by'    => $userid,
                'branchid'      => $branchid
            );
        }

        $invoice = array(
            'invoiceMas' => $invoiceMas,
            'invoiceDtl' => $invoiceDtl,
        );

        return $invoice;
    }
    
    public static function insertInvoice($invoice){
        
        DB::beginTransaction();

        try {
            
            $invoiceMasId = DB::table('invoicemas')
                    ->insertGetId($invoice['invoiceMas'])
                    ;

            $arrInvoiceMas = array('invoicemasid' => $invoiceMasId);

            for($i = 0; $i < count($invoice['invoiceDtl']); $i++){
                $invoice['invoiceDtl'][$i] = array_merge($invoice['invoiceDtl'][$i], $arrInvoiceMas);
            }
            
            DB::table('invoicedtl')
                    ->insert($invoice['invoiceDtl'])
                    ;            
            
        } catch(\Exception $e){
            DB::rollback();
            throw $e;
        }
        
        DB::commit();
        
        return $invoiceMasId;
    }

    public static function updateInvoice($id, $invoice){
        DB::beginTransaction();

        try {
            
            DB::table('invoicemas')
                ->where('id', $id)
                ->update($invoice['invoiceMas'])
                ;

            $invoiceDtlId = array();
            
            for($i = 0; $i < count($invoice['invoiceDtl']); $i++){
                if($invoice['invoiceDtl'][$i]['id'] != '0'){
                    $invoiceDtlId[] = $invoice['invoiceDtl'][$i]['id'];
                }
            }

            if(!empty($invoiceDtlId)){
                DB::table('invoicedtl')
                        ->where('invoicemasid', $id)
                        ->whereNotIn('id', $invoiceDtlId)
                        ->delete()
                        ;
            }else{
                DB::table('invoicedtl')
                        ->where('invoicemasid', $id)
                        ->delete()
                        ;
            }
            
            for($i = 0; $i < count($invoice['invoiceDtl']); $i++){
                if($invoice['invoiceDtl'][$i]['id'] != '0'){
                    DB::table('invoicedtl')
                        ->where('invoicemasid', $id)
                        ->where('id', $invoice['invoiceDtl'][$i]['id'])
                        ->update($invoice['invoiceDtl'][$i])
                        ;
                }else{
                    $invoice['invoiceDtl'][$i] = array_merge($invoice['invoiceDtl'][$i], array(
                        'invoicemasid' => $id,
                        'created_at' => Date('Y-m-d'),
                        'created_by' => $invoice['invoiceDtl'][$i]['updated_by']
                    ));

                    DB::table('invoicedtl')
                        ->insert($invoice['invoiceDtl'][$i])
                        ;
                }
            }
            
        } catch(\Exception $e){
            DB::rollback();
            throw $e;
        }
        
        DB::commit();
    }

    public static function getInvoiceMasById($id){
        $invoiceMas = DB::table('invoicemas')
                    ->where('id', $id)
                    ->get();
        
        return $invoiceMas;
    }

    public static function getInvoiceDtlByInvoiceMasID($id){
        // $invoiceDtl = DB::table('invoicedtl')
        //             ->where('invoicemasid', $id)
        //             ->get();

        $invoiceDtl = DB::select('
            SELECT dtl.*, domas.receiptno, domas.containername, domas.seal, con.name as conname, rec.name AS recname 
            FROM invoicedtl dtl 
            INNER JOIN domas ON domas.id = dtl.domasid
            JOIN containertype con ON domas.containertypeid = con.id 
            JOIN recipient rec ON domas.recipientid = rec.id
            WHERE dtl.invoicemasid = ?
            ', array($id))
        ;
        
        return $invoiceDtl;
    }

    public static function getInvoiceDtlByByShipScheduleId($id){        
        $invoiceDtl = DB::select('
            SELECT dtl.* 
            FROM invoicedtl dtl 
            INNER JOIN domas do ON do.id = dtl.domasid          
            WHERE do.shipscheduleid = ?
            ', array($id))
        ;
        
        return $invoiceDtl;
    }

    public static function getDoMasByShipScheduleId($id){
        $doMas = DB::table('domas')
                    ->where('shipscheduleid', $id)
                    ->get();
        
        return $doMas;
    }

    public static function getDropDownShipSchedule(){
        $shipSchedule = DB::table('shipschedule')
                    ->get();
        
        return $shipSchedule;
    }

    public static function getDataTableInvoice(){
        $invoice = DB::select('
            SELECT mas.*, sender.name AS sendername, recipient.name AS recipientname 
            FROM invoicemas mas 
            LEFT JOIN sender ON sender.id = mas.senderid 
            LEFT JOIN recipient ON recipient.id = mas.recipientid
            ')
            ;
        
        return $invoice;
    }

    public static function getInvoiceNo($request){        
        $sql = "SELECT count(*) AS count
            FROM invoicemas mas 
            WHERE 
                MONTH(mas.invoicedate) = MONTH(CURRENT_DATE())
                AND YEAR(mas.invoicedate) = YEAR(CURRENT_DATE())
            "
            ;

        $invoiceMas = DB::select($sql);

        $count =  (int)$invoiceMas[0]->count + 1;

        $prefix = str_pad($count, 4, '0', STR_PAD_LEFT);

        $invoiceNo = $prefix . '/BEN-JKT/' . Date('m') . '/' . Date('Y');

        return $invoiceNo;
    }

    public static function getUnpaidList($request){                
        if($request->invoicetypeid == '0'){
            $sql = "SELECT inv.* 
                FROM invoicemas inv 
                WHERE (inv.senderid = :senderid) AND inv.status = 0"
                ;

            $listInvoice = DB::select($sql, array(
                'senderid' => $request->senderid,      
            ));
        }else{
            $sql = "SELECT inv.* 
                FROM invoicemas inv 
                WHERE (inv.recipientid = :recipientid) AND inv.status = 0"
                ;

            $listInvoice = DB::select($sql, array(
                'recipientid' => $request->recipientid,           
            ));
        }

        return $listInvoice;
    }

    public static function searchTableList($request){
        $invoiceNo = $request->invoiceno == NULL ? '' : $request->invoiceno;
        $invoiceDate = $request->invoicedate == NULL ? '' : Date('Y-m-d', strtotime($request->invoicedate));
        $senderId = $request->senderid == NULL ? 0 : $request->senderid;
        $recipientId = $request->recipientid == NULL ? 0 : $request->recipientid;
        $note = $request->note == NULL ? '' : $request->note;
        $departDate = $request->departdate == NULL ? '' : Date('Y-m-d', strtotime($request->departdate));
        $shipId = $request->shipid == NULL ? 0 : $request->shipid;
        $destination = $request->destination == NULL ? '' : $request->destination;
        
        $sql = "SELECT DISTINCT mas.*, sender.name AS sendername, recipient.name AS recipientname 
            FROM invoicemas mas 
            INNER JOIN invoicedtl dtl ON dtl.invoicemasid = mas.id
            INNER JOIN domas ON domas.id = dtl.domasid
            INNER JOIN shipschedule sh ON sh.id = domas.shipscheduleid
            LEFT JOIN sender ON sender.id = mas.senderid 
            LEFT JOIN recipient ON recipient.id = mas.recipientid 
            WHERE (mas.invoiceno LIKE CONCAT(:invoiceno1,'%') OR :invoiceno2 = '') 
                AND (mas.invoicedate = :invoicedate1 OR :invoicedate2 = '') 
                AND (mas.senderid = :senderid1 OR :senderid2 = 0) 
                AND (mas.recipientid = :recipientid1 OR :recipientid2 = 0) 
                AND (mas.note LIKE CONCAT(:note1,'%') OR :note2 = '') 
                AND (sh.destination LIKE CONCAT(:destination1,'%') OR :destination2 = '') 
                AND (sh.departdate = :departdate1 OR :departdate2 = '') 
                AND (sh.shipid = :shipid1 OR :shipid2 = 0) 
            ORDER BY mas.invoicedate DESC
            "
            ;

        $listInvoice = DB::select($sql, array(
            'invoiceno1' => $invoiceNo, 'invoiceno2' => $invoiceNo,
            'invoicedate1' => $invoiceDate, 'invoicedate2' => $invoiceDate,
            'senderid1' => $senderId, 'senderid2' => $senderId,
            'recipientid1' => $recipientId, 'recipientid2' => $recipientId,
            'note1' => $note, 'note2' => $note,
            'destination1' => $destination, 'destination2' => $destination,
            'departdate1' => $departDate, 'departdate2' => $departDate,
            'shipid1' => $shipId, 'shipid2' => $shipId,
        ));

        return $listInvoice;
    }

    public static function isUsedInvoice($id){
        $invoice = DB::table('cashierindtl')
                    ->where('invoicemasid', $id)
                    ->get();
        
        if(count($invoice) > 0){
            return true;
        }else{
            return false;
        }
    }

    public static function deleteInvoice($id){
        DB::table('invoicedtl')
            ->where('invoicemasid', $id)
            ->delete();

        DB::table('invoicemas')
            ->where('id', $id)
            ->delete();
    }
}
