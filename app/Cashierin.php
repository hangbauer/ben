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
class Cashierin {
    
    public static function setCashierin($method, $request, $userid, $branchid){
        $cashierinDtl = array();
        $cashierinMas = array();
        $amount = 0;

        if($method == 0){
            for($i = 0; $i < count($request->amount); $i++){
                if((float)str_replace(",", "", $request->amount[$i]) > 0){
                    $cashierinDtl[] = array(
                        'invoicemasid'  => $request->invoicemasid[$i],
                        'amount'        => (float)str_replace(",", "", $request->amount[$i]),
                        'note'          => $request->dtlnote[$i],
                        'created_at'    => Date('Y-m-d h:i:s'),
                        'updated_at'    => Date('Y-m-d h:i:s'),
                        'created_by'    => $userid,
                        'updated_by'    => $userid,
                        'branchid'      => $branchid
                    );

                    $amount += (float)str_replace(",", "", $request->amount[$i]);
                }
            }
        }else{
            for($i = 0; $i < count($request->amount); $i++){
                $cashierinDtl[] = array(
                    'id'            => $request->dtlid[$i],
                    'invoicemasid'  => $request->invoicemasid[$i],
                    'amount'        => (float)str_replace(",", "", $request->amount[$i]),
                    'note'          => $request->dtlnote[$i],
                    'updated_at'    => Date('Y-m-d h:i:s'),
                    'updated_by'    => $userid,
                    'branchid'      => $branchid
                );

                $amount += (float)str_replace(",", "", $request->amount[$i]);
            }
        }

        if($method == 0){
            $cashierinMas = array(
                'cashierinno'   => getNumber('CAI', 'cashierinmas', 'cashierinno', $branchid),
                'cashierindate' => Date('Y-m-d', strtotime($request->cashierindate)),
                'senderid'      => $request->senderid,
                'recipientid'   => $request->recipientid,
                'invoicetypeid' => $request->invoicetypeid,
                'bankid'        => $request->bankid,
                'amount'        => $amount,                
                'note'          => $request->note,
                'created_at'    => Date('Y-m-d h:i:s'),
                'updated_at'    => Date('Y-m-d h:i:s'),
                'created_by'    => $userid,
                'updated_by'    => $userid,
                'branchid'      => $branchid
            );
        }else{
            $cashierinMas = array(
                'cashierindate' => Date('Y-m-d', strtotime($request->cashierindate)),
                'senderid'      => $request->senderid,
                'recipientid'   => $request->recipientid,
                'invoicetypeid' => $request->invoicetypeid,
                'bankid'        => $request->bankid,
                'amount'        => $amount,                
                'note'          => $request->note,
                'updated_at'    => Date('Y-m-d h:i:s'),
                'updated_by'    => $userid,

            );
        }

        $cashierin = array(
            'cashierinMas' => $cashierinMas,
            'cashierinDtl' => $cashierinDtl,
        );

        return $cashierin;
    }
    
    public static function insertCashierin($cashierin){
        DB::beginTransaction();

        try {
            
            $cashierinMasId = DB::table('cashierinmas')
                ->insertGetId($cashierin['cashierinMas'])
                ;

            $arrCashierinMas = array('cashierinmasid' => $cashierinMasId);

            for($i = 0; $i < count($cashierin['cashierinDtl']); $i++){
                $cashierin['cashierinDtl'][$i] = array_merge($cashierin['cashierinDtl'][$i], $arrCashierinMas);            
            }
            
            DB::table('cashierindtl')
                ->insert($cashierin['cashierinDtl'])
                ;            

            for($i = 0; $i < count($cashierin['cashierinDtl']); $i++){
                self::updateInvoicePaidAmount($cashierin['cashierinDtl'][$i]['invoicemasid']);
            }
            
        } catch(\Exception $e){
            DB::rollback();
            throw $e;
        }
        
        DB::commit();
        
        return $cashierinMasId;
    }

    public static function updateCashierin($id, $cashierin){
        DB::beginTransaction();

        try {
            
            DB::table('cashierinmas')
                ->where('id', $id)
                ->update($cashierin['cashierinMas'])
                ;

            $cashierinDtlId = array();
            
            for($i = 0; $i < count($cashierin['cashierinDtl']); $i++){
                if($cashierin['cashierinDtl'][$i]['id'] != '0'){
                    $cashierinDtlId[] = $cashierin['cashierinDtl'][$i]['id'];
                }
            }

            if(!empty($cashierinDtlId)){
                DB::table('cashierindtl')
                        ->where('cashierinmasid', $id)
                        ->whereNotIn('id', $cashierinDtlId)
                        ->delete()
                        ;
            }else{
                DB::table('cashierindtl')
                        ->where('cashierinmasid', $id)
                        ->delete()
                        ;
            }
            
            for($i = 0; $i < count($cashierin['cashierinDtl']); $i++){
                if($cashierin['cashierinDtl'][$i]['id'] != '0'){
                    DB::table('cashierindtl')
                        ->where('cashierinmasid', $id)
                        ->where('id', $cashierin['cashierinDtl'][$i]['id'])
                        ->update($cashierin['cashierinDtl'][$i])
                        ;
                }else{
                    $cashierin['cashierinDtl'][$i] = array_merge($cashierin['cashierinDtl'][$i], array(
                        'cashierinmasid' => $id,
                        'created_at' => Date('Y-m-d'),
                        'created_by' => $cashierin['cashierinDtl'][$i]['updated_by']
                    ));

                    DB::table('cashierindtl')
                        ->insert($cashierin['cashierinDtl'][$i])
                        ;
                }

                self::updateInvoicePaidAmount($cashierin['cashierinDtl'][$i]['invoicemasid']);
            }
            
        } catch(\Exception $e){
            DB::rollback();
            throw $e;
        }
        
        DB::commit();
    }

    public static function getCashierinMasByID($id){
        $cashierinMas = DB::table('cashierinmas')
                    ->where('id', $id)
                    ->get();
        
        return $cashierinMas;
    }

    public static function getCashierinDtlByCashierinMasID($id){
        $cashierinDtl = DB::table('cashierindtl')
                    ->join('invoicemas', 'cashierindtl.invoicemasid', '=', 'invoicemas.id')
                    ->where('cashierinmasid', $id)
                    ->select('cashierindtl.*', 'invoicemas.invoiceno', 'invoicemas.invoicedate', 'invoicemas.note', 'invoicemas.amount')
                    ->get();
        
        return $cashierinDtl;
    }

    public static function updateInvoicePaidAmount($invoicemasId){
        $sql = "UPDATE invoicemas 
            INNER JOIN 
            (
                SELECT invoicemas.id, SUM(IFNULL(cashierindtl.amount,0)) AS amount FROM invoicemas 
                LEFT JOIN cashierindtl ON cashierindtl.invoicemasid = invoicemas.id 
                WHERE invoicemas.id = :invoicemasid 
                GROUP BY invoicemas.id
            ) x ON x.id = invoicemas.id 
            SET invoicemas.paidamount = x.amount, invoicemas.status = CASE WHEN invoicemas.amount = x.amount THEN 2 ELSE 0 END ";
        DB::update($sql, array('invoicemasid' => $invoicemasId));
    }

    public static function searchTableList($request){
        $cashierinNo = $request->cashierinno == NULL ? '' : $request->cashierinno;
        $cashierinDate = $request->cashierindate == NULL ? '' : Date('Y-m-d', strtotime($request->cashierindate));
        $bankId = $request->bankid == NULL ? 0 : $request->bankid;
        $senderId = $request->senderid == NULL ? 0 : $request->senderid;
        $recipientId = $request->recipientid == NULL ? 0 : $request->recipientid;
        $note = $request->note == NULL ? '' : $request->note;
        
        $sql = "SELECT mas.*, sender.name AS sendername, recipient.name AS recipientname, bank.name AS bankname 
            FROM cashierinmas mas 
            LEFT JOIN sender ON sender.id = mas.senderid 
            LEFT JOIN recipient ON recipient.id = mas.recipientid 
            LEFT JOIN bank ON bank.id = mas.bankid 
            WHERE (mas.cashierinno LIKE CONCAT(:cashierinno1,'%') OR :cashierinno2 = '') 
                AND (mas.cashierindate = :cashierindate1 OR :cashierindate2 = '') 
                AND (mas.bankid = :bankid1 OR :bankid2 = 0) 
                AND (mas.senderid = :senderid1 OR :senderid2 = 0) 
                AND (mas.recipientid = :recipientid1 OR :recipientid2 = 0) 
                AND (mas.note LIKE CONCAT(:note1,'%') OR :note2 = '') 
            ORDER BY mas.cashierindate DESC
            "
            ;

        $listCashierin = DB::select($sql, array(
            'cashierinno1' => $cashierinNo, 'cashierinno2' => $cashierinNo,
            'cashierindate1' => $cashierinDate, 'cashierindate2' => $cashierinDate,
            'bankid1' => $bankId, 'bankid2' => $bankId,
            'senderid1' => $senderId, 'senderid2' => $senderId,
            'recipientid1' => $recipientId, 'recipientid2' => $recipientId,
            'note1' => $note, 'note2' => $note,
        ));

        return $listCashierin;
    }

    public static function deleteCashierin($id){
        $cashierindtl = self::getCashierinDtlByCashierinMasID($id);
        
        for($i = 0; $i < count($cashierindtl); $i++){
            self::updateInvoicePaidAmount($cashierindtl[$i]->invoicemasid);
        }

        DB::table('cashierindtl')
            ->where('cashierinmasid', $id)
            ->delete();

        DB::table('cashierinmas')
            ->where('id', $id)
            ->delete();


    }

}
