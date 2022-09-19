DROP PROCEDURE IF EXISTS `sprRptLoadingList`;
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sprRptLoadingList`(IN departDate VARCHAR(10), IN intShipId INT, IN strVoyage VARCHAR(100))
BEGIN
	SELECT 
		ship.name AS shipname, shipsc.departdate, shipsc.destination, shipsc.note AS shipscnote, shipsc.voyage, 
		shipsc.depart,
        mas.containername, mas.seal, rec.name AS recname, term.name AS termname,
        dtl.itemname, dtl.itemorder, dtl.itemunit, dtl.volume, dtl.note AS dtlnote, contype.name AS contypename,        
        sen.name AS sendname,invmas.invoiceno
    FROM shipschedule shipsc 
	INNER JOIN domas mas ON mas.shipscheduleid = shipsc.id 
	INNER JOIN dodtl dtl ON dtl.domasid = mas.id 
    INNER JOIN ship ON shipsc.shipid = ship.id 
    INNER JOIN recipient rec ON rec.id = mas.recipientid 
    INNER JOIN term ON term.id = mas.termid
    INNER JOIN containertype contype ON contype.id = mas.containertypeid    
    INNER JOIN sender sen on mas.senderid = sen.id
    LEFT JOIN invoicedtl invdtl on mas.id = invdtl.domasid
    LEFT JOIN invoicemas invmas on invdtl.invoicemasid = invmas.id
    WHERE 
		('' = departDate OR shipsc.departdate = departDate) 
        AND (0 = intShipId OR shipsc.shipid = intShipId) 
        AND ('' = strVoyage OR shipsc.voyage = strVoyage)
	ORDER BY ship.name, departdate, mas.containername
    ;
END$$
DELIMITER ;




DROP procedure IF EXISTS `sprRptDeliveryOrder`;
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sprRptDeliveryOrder`(IN departDate VARCHAR(10), IN intShipId INT, IN intRecipientId INT, IN intPaymentTypeId INT)
BEGIN
	SELECT
		mas.receiptno, mas.dodate, mas.containername, mas.seal, mas.note, mas.recipientid,
        dtl.itemname, dtl.itemorder, dtl.itemunit, dtl.volume, dtl.note AS dtlnote,
        ship.name AS shipname, shipsc.departdate, shipsc.id AS shipscid, 
        rec.name AS recname, rec.address AS recaddress, contype.name AS contypename                
	FROM domas mas 
    INNER JOIN dodtl dtl ON dtl.domasid = mas.id
    INNER JOIN shipschedule shipsc ON shipsc.id = mas.shipscheduleid 
    INNER JOIN ship ON ship.id = shipsc.shipid
    INNER JOIN recipient rec ON rec.id = mas.recipientid
    INNER JOIN containertype contype ON contype.id = mas.containertypeid
    WHERE 
		('' = departDate OR shipsc.departdate = departDate) 
        AND (0 = intShipId OR shipsc.shipid = intShipId) 
        AND (0 = intRecipientId OR mas.recipientid = intRecipientId)
		AND (0 = intPaymentTypeId OR mas.paymenttypeid = intPaymentTypeId)		
	ORDER BY rec.name, mas.containername, shipsc.departdate, mas.receiptno, mas.containername 
    ;
END$$
DELIMITER ;


DROP procedure IF EXISTS `sprRptInvoice`;
DELIMITER $$
CREATE PROCEDURE `sprRptInvoice`(IN intInvoiceMasId INT)
BEGIN
	SELECT
		mas.id, mas.invoiceno, mas.senderid, mas.recipientid, mas.invoicedate, mas.duedate, 
        mas.ppnpercent, mas.bankid, mas.invoicetypeid, mas.note, mas.status, mas.amount, mas.paidamount, mas.ppnamount, mas.insurance,
        mas.quarantine,
        dtl.id AS dtlid, dtl.domasid, dtl.amount AS dtlamount, dtl.note AS dtlnote,        
        domas.receiptno, domas.dodate, domas.containername, domas.seal, domas.note AS domasnote, con.name as conname,
        dodtl.itemname, dodtl.itemorder, dodtl.itemunit, dodtl.volume, dodtl.note AS dodtlnote,
        ship.name AS shipname, shipsc.departdate, shipsc.id AS shipscid, shipsc.destination, shipsc.depart,
        rec.name AS recname, rec.address AS recaddress,
        sender.name AS sendername, sender.address AS senderaddress, 
        bank.name AS bankname, bank.accountname, bank.accountno
	FROM invoicemas mas 
    INNER JOIN invoicedtl dtl ON dtl.invoicemasid = mas.id
    INNER JOIN domas ON domas.id = dtl.domasid
	INNER JOIN containertype con ON con.id = domas.containertypeid
    INNER JOIN dodtl ON dodtl.domasid = domas.id
    INNER JOIN shipschedule shipsc ON shipsc.id = domas.shipscheduleid 
    INNER JOIN ship ON ship.id = shipsc.shipid
    INNER JOIN recipient rec ON rec.id = domas.recipientid
    INNER JOIN sender ON sender.id = domas.senderid
    INNER JOIN bank ON bank.id = mas.bankid
    WHERE 
		(0 = intInvoiceMasId OR mas.id = intInvoiceMasId)         
	ORDER BY mas.id 
    ;
END$$
DELIMITER ;


DROP PROCEDURE IF EXISTS `sprRptDeliveryOrderReport`;
DELIMITER $$
CREATE PROCEDURE `sprRptDeliveryOrderReport`(IN departDate VARCHAR(10), IN intShipId INT, IN intRecipientId INT, IN strStatus VARCHAR(1), IN strVoyage VARCHAR(100), IN intSenderId INT, IN strReceiptNo VARCHAR(100))
BEGIN
	SELECT
		mas.receiptno, mas.dodate, mas.containername, mas.seal, mas.note, mas.recipientid,
        ship.name AS shipname, shipsc.departdate, shipsc.id AS shipscid, shipsc.voyage, shipsc.destination,
        rec.name AS recname, rec.address AS recaddress, inv.invoiceno, mas.senderid, send.name AS sendname                 
	FROM domas mas 
    INNER JOIN shipschedule shipsc ON shipsc.id = mas.shipscheduleid 
    INNER JOIN ship ON ship.id = shipsc.id
    INNER JOIN recipient rec ON rec.id = mas.recipientid 
    INNER JOIN sender send ON send.id = mas.senderid 
    LEFT JOIN
    (
		SELECT mas.id, mas.invoiceno, dtl.domasid
        FROM invoicemas mas
        INNER JOIN invoicedtl dtl ON mas.id = dtl.invoicemasid
    ) inv ON mas.id = inv.domasid
    WHERE 
		('' = departDate OR shipsc.departdate = departDate) 
        AND (0 = intShipId OR shipsc.shipid = intShipId) 
        AND (0 = intRecipientId OR mas.recipientid = intRecipientId) 
        AND ('0' = strStatus OR ('1' = strStatus AND IFNULL(inv.invoiceno,'') = '') OR ('2' = strStatus AND IFNULL(inv.invoiceno,'') <> ''))
        AND ('' = strVoyage OR shipsc.voyage = strVoyage) 
        AND (0 = intSenderId OR mas.senderid = intSenderId) 
        AND ('' = strReceiptNo OR mas.receiptno = strReceiptNo) 
	ORDER BY ship.name, shipsc.departdate, rec.name, mas.receiptno, send.name
    ;
END$$
DELIMITER ;


DROP PROCEDURE IF EXISTS `sprRptInvoiceReport`;
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sprRptInvoiceReport`(IN DateFr VARCHAR(10), IN DateTo VARCHAR(10), IN intSenderId INT, IN intRecipientId INT, IN strStatus VARCHAR(1))
BEGIN
	SELECT
		mas.id, mas.invoiceno, mas.invoicedate, mas.note, mas.recipientid, mas.senderid,
        sender.name AS sendername, rec.name AS recname, mas.amount, mas.paidamount
	FROM invoicemas mas     
    LEFT JOIN sender ON sender.id = mas.senderid 
    LEFT JOIN recipient rec ON rec.id = mas.recipientid 
    WHERE 
		('' = DateFr OR mas.invoicedate >= DateFr) 
        AND ('' = DateTo OR mas.invoicedate <= DateTo) 
        AND (0 = intSenderId OR mas.senderid = intSenderId) 
        AND (0 = intRecipientId OR mas.recipientid = intRecipientId) 
        AND ('0' = strStatus OR ('1' = strStatus AND mas.amount <> mas.paidamount) OR ('2' = strStatus AND mas.amount = mas.paidamount))
	ORDER BY mas.id, mas.invoicedate 
    ;
END$$
DELIMITER ;

DROP PROCEDURE IF EXISTS `sprRptItem`;
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sprRptItem`(IN intShipId INT, IN intIsShip INT, IN containerName VARCHAR(50), IN intRecId INT, IN intSendId INT, IN dateFrom VARCHAR(10), IN dateTo VARCHAR(10))
BEGIN
	SELECT 
		ship.name AS shipname, shipsc.departdate, shipsc.destination, shipsc.note AS shipscnote, shipsc.voyage, 
		shipsc.depart,
        mas.containername, mas.seal, mas.receiptno, mas.dodate, 
        rec.name recname,sen.name senname,
        dtl.itemname, dtl.itemorder, dtl.itemunit, dtl.volume, dtl.note AS dtlnote
    FROM domas mas
    INNER JOIN dodtl dtl ON dtl.domasid = mas.id 
    INNER JOIN recipient rec ON rec.id = mas.recipientid 
    INNER JOIN sender sen ON sen.id = mas.senderid
    LEFT OUTER JOIN shipschedule shipsc ON mas.shipscheduleid = shipsc.id 
    LEFT OUTER JOIN ship ON shipsc.shipid = ship.id     
    WHERE 
		(shipsc.departdate BETWEEN dateFrom AND dateTo) 
        AND (0 = intShipId OR shipsc.shipid = intShipId) 
        AND ((0 = intIsShip AND shipsc.id IS NULL) OR (1 = intIsShip AND shipsc.id IS NOT NULL )) 
        AND (0 = intRecId OR mas.recipientid = intRecId) 
        AND (0 = intSendId OR mas.senderid = intSendId) 
        
	ORDER BY ship.name, departdate, mas.containername
    ;
END$$
DELIMITER ;

DROP PROCEDURE IF EXISTS `sprRptContainerList`;
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sprRptContainerList`(IN dateFrom VARCHAR(10), IN dateTo VARCHAR(10), IN intShipId INT, IN strVoyage VARCHAR(100), IN intRecId INT, IN intSendId INT)
BEGIN
	SELECT 
		ship.name AS shipname, shipsc.departdate, shipsc.destination, shipsc.note AS shipscnote, shipsc.voyage, 
		shipsc.depart,
        mas.containername, mas.seal, rec.name AS recname, term.name AS termname,
        dtl.itemname, dtl.itemorder, dtl.itemunit, dtl.volume, dtl.note AS dtlnote, contype.name AS contypename,        
        sen.name AS sendname,invmas.invoiceno
    FROM shipschedule shipsc 
	INNER JOIN domas mas ON mas.shipscheduleid = shipsc.id 
	INNER JOIN dodtl dtl ON dtl.domasid = mas.id 
    INNER JOIN ship ON shipsc.shipid = ship.id 
    INNER JOIN recipient rec ON rec.id = mas.recipientid 
    INNER JOIN term ON term.id = mas.termid
    INNER JOIN containertype contype ON contype.id = mas.containertypeid    
    INNER JOIN sender sen on mas.senderid = sen.id
    LEFT JOIN invoicedtl invdtl on mas.id = invdtl.domasid
    LEFT JOIN invoicemas invmas on invdtl.invoicemasid = invmas.id
    WHERE 
		#('' = departDate OR shipsc.departdate = departDate) 
        (shipsc.departdate BETWEEN dateFrom AND dateTo) 
        AND (0 = intShipId OR shipsc.shipid = intShipId) 
        AND ('' = strVoyage OR shipsc.voyage = strVoyage)
        AND (0 = intRecId OR mas.recipientid = intRecId) 
        AND (0 = intSendId OR mas.senderid = intSendId) 
	ORDER BY ship.name, departdate, mas.containername
    ;
END$$
DELIMITER ;

DROP PROCEDURE IF EXISTS `sprRptInvoiceDtl`;
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sprRptInvoiceDtl`(IN intInvoiceMasId INT,IN strContainerName VARCHAR(50))
BEGIN
	SELECT
		mas.id, mas.invoiceno, mas.senderid, mas.recipientid, mas.invoicedate, mas.duedate, 
        mas.ppnpercent, mas.bankid, mas.invoicetypeid, mas.note, mas.status, mas.amount, mas.paidamount, mas.ppnamount, mas.insurance,
        mas.quarantine,
        dtl.id AS dtlid, dtl.domasid, dtl.amount AS dtlamount, dtl.note AS dtlnote,        
        domas.receiptno, domas.dodate, domas.containername, domas.seal, domas.note AS domasnote, con.name as conname,
        dodtl.itemname, dodtl.itemorder, dodtl.itemunit, dodtl.volume, dodtl.note AS dodtlnote,
        ship.name AS shipname, shipsc.departdate, shipsc.id AS shipscid, shipsc.destination, shipsc.depart,
        rec.name AS recname, rec.address AS recaddress,
        sender.name AS sendername, sender.address AS senderaddress, 
        bank.name AS bankname, bank.accountname, bank.accountno,
        case when mas.invoicetypeid = 0 and dodtl.itemordersender > 0 then dodtl.itemordersender when mas.invoicetypeid = 1 and dodtl.itemorderrecipient > 0 then dodtl.itemorderrecipient else dodtl.itemorder end itemorder2
	FROM invoicemas mas 
    INNER JOIN invoicedtl dtl ON dtl.invoicemasid = mas.id
    INNER JOIN domas ON domas.id = dtl.domasid
	INNER JOIN containertype con ON con.id = domas.containertypeid
    INNER JOIN dodtl ON dodtl.domasid = domas.id
    INNER JOIN shipschedule shipsc ON shipsc.id = domas.shipscheduleid 
    INNER JOIN ship ON ship.id = shipsc.shipid
    INNER JOIN recipient rec ON rec.id = domas.recipientid
    INNER JOIN sender ON sender.id = domas.senderid
    INNER JOIN bank ON bank.id = mas.bankid
    WHERE 
		(0 = intInvoiceMasId OR mas.id = intInvoiceMasId)      
        AND domas.containername = strContainerName
        AND 
        (
			(dodtl.itemordersender IS NOT NULL AND mas.invoicetypeid = 0) OR
            (dodtl.itemorderrecipient IS NOT NULL and mas.invoicetypeid = 1)
		)
	ORDER BY mas.id 
    ;
END$$
DELIMITER ;

