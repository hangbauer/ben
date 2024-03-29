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
class Report {

    public static function getLoadingListData($request, $userid, $branchid){
        $departDate = $request->datefr == NULL ? '' : Date('Y-m-d', strtotime($request->datefr));
        $shipId = $request->shipid == NULL ? 0 : $request->shipid;
        $voyage = $request->voyage == NULL ? '' : $request->voyage;

        $sql = "CALL sprRptLoadingList(:departdate, :shipid, :voyage)";

        $result = DB::select($sql, array(
            'departdate' => $departDate,
            'shipid' => $shipId,
            'voyage' => $voyage,
            ));
        
        return $result;
    }

    public static function getDeliveryOrderData($request, $userid, $branchid){
        $departDate = $request->datefr == NULL ? '' : Date('Y-m-d', strtotime($request->datefr));
        $shipId = $request->shipid == NULL ? 0 : $request->shipid;
        $recipientId = $request->recipientid == NULL ? 0 : $request->recipientid;
        $paymentTypeId = $request->paymenttypeid == NULL ? 0 : $request->paymenttypeid;
        $containerName = $request->containername == NULL ? '' : $request->containername;
        
        $sql = "CALL sprRptDeliveryOrder(:departdate, :shipid, :recipientid, :paymenttypeid, :containername)";

        $result = DB::select($sql, array(
            'departdate' => $departDate,
            'shipid' => $shipId,
            'recipientid' => $recipientId,
            'paymenttypeid' => $paymentTypeId,
            'containername' => $containerName
            ));

        return $result;
    }

    public static function getInvoiceData($request, $userid, $branchid){
        $invoiceMasId = $request->invoicemasid == NULL ? 0 : $request->invoicemasid;

        $sql = "CALL sprRptInvoice(:invoicemasid)";

        $result = DB::select($sql, array(
            'invoicemasid' => $invoiceMasId,
            ));

        return $result;
    }

    public static function getDeliveryOrderReportData($request, $userid, $branchid){
        $departDate = $request->datefr == NULL ? '' : Date('Y-m-d', strtotime($request->datefr));
        $shipId = $request->shipid == NULL ? 0 : $request->shipid;
        $recipientId = $request->recipientid == NULL ? 0 : $request->recipientid;
        $status = $request->status == NULL ? '0' : $request->status;
        $voyage = $request->voyage == NULL ? '' : $request->voyage;
        $senderId = $request->senderid == NULL ? 0 : $request->senderid;
        $receiptNo = $request->receiptno == NULL ? '' : $request->receiptno;

        $sql = "CALL sprRptDeliveryOrderReport(:departdate, :shipid, :recipientid, :status, :voyage, :senderid, :receiptno)";

        $result = DB::select($sql, array(
            'departdate' => $departDate,
            'shipid' => $shipId,
            'recipientid' => $recipientId,
            'status' => $status,
            'voyage' => $voyage,
            'senderid' => $senderId,
            'receiptno' => $receiptNo,
            ));

        return $result;
    }

    public static function getInvoiceReportData($request, $userid, $branchid){
        $dateFr = $request->datefr == NULL ? '' : Date('Y-m-d', strtotime($request->datefr));
        $dateTo = $request->dateto == NULL ? '' : Date('Y-m-d', strtotime($request->dateto));
        $senderId = $request->senderid == NULL ? 0 : $request->senderid;
        $recipientId = $request->recipientid == NULL ? 0 : $request->recipientid;
        $status = $request->status == NULL ? '0' : $request->status;

        $sql = "CALL sprRptInvoiceReport(:datefr, :dateto, :senderid, :recipientid, :status)";

        $result = DB::select($sql, array(
            'datefr' => $dateFr,
            'dateto' => $dateTo,
            'senderid' => $senderId,
            'recipientid' => $recipientId,
            'status' => $status,
            ));

        return $result;
    }

    public static function getContainerListData($request, $userid, $branchid){
        $dateFr = $request->datefr == NULL ? '' : Date('Y-m-d', strtotime($request->datefr));
        $dateTo = $request->dateto == NULL ? '' : Date('Y-m-d', strtotime($request->dateto));
        $shipId = $request->shipid == NULL ? 0 : $request->shipid;
        $voyage = $request->voyage == NULL ? '' : $request->voyage;
        $recId = $request->recipientid == NULL ? 0 : $request->recipientid;
        $sendId = $request->senderid == NULL ? 0 : $request->senderid;

        $sql = "CALL sprRptContainerList(:datefr, :dateto, :shipid, :voyage, :recid, :sendid)";

        $result = DB::select($sql, array(
            'datefr' => $dateFr,
            'dateto' => $dateTo,
            'shipid' => $shipId,
            'voyage' => $voyage,
            'recid' => $recId,
            'sendid' => $sendId,
            ));
        
        return $result;
    }
}
