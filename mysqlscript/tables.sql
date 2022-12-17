CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,  
  `remember_token` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `branchid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,  
  `status` tinyint(1) DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `branchid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accountname` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `accountno` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,  
  `status` tinyint(1) DEFAULT '0',
  `ppn` tinyint(1) DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `branchid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `shipschedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shipid` int(11) DEFAULT NULL,
  `departdate` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,  
  `destination` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,  
  `voyage` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,  
  `note` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,  
  `status` tinyint(1) DEFAULT '0',
  `depart` VARCHAR(50) NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `branchid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',  
  PRIMARY KEY (`id`),
  FOREIGN KEY (`shipid`) REFERENCES ship(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `sender` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,  
  `status` tinyint(1) DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `branchid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `recipient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,  
  `status` tinyint(1) DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `branchid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `term` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `branchid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `paymenttype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `branchid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `containertype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `branchid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `domas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receiptno` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dodate` date DEFAULT NULL,
  `senderid` int(11) DEFAULT NULL,
  `recipientid` int(11) DEFAULT NULL,
  `termid` int(11) DEFAULT NULL,
  `paymenttypeid` int(11) DEFAULT NULL,
  `fullcontainerflag` tinyint(1) DEFAULT '0',
  `containername` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,  
  `containertypeid` int(11) DEFAULT NULL,
  `carflag` tinyint(1) DEFAULT '0',
  `seal` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,  
  `note` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,  
  `shipscheduleid` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `branchid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',  
  PRIMARY KEY (`id`),
  FOREIGN KEY (`paymenttypeid`) REFERENCES paymenttype(`id`),
  FOREIGN KEY (`containertypeid`) REFERENCES containertype(`id`),
  FOREIGN KEY (`termid`) REFERENCES term(`id`),
  FOREIGN KEY (`shipscheduleid`) REFERENCES shipschedule(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `dodtl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domasid` int(11) DEFAULT NULL,
  `itemname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `itemorder` decimal(20,2) DEFAULT '0.00',
  `itemordersender` decimal(20,2) DEFAULT '0.00',
  `itemorderrecipient` decimal(20,2) DEFAULT '0.00',
  `itemunit` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `volume` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,  
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `branchid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',  
  PRIMARY KEY (`id`),
  FOREIGN KEY (`domasid`) REFERENCES domas(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `invoicemas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoiceno` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,  
  `senderid` int(11) DEFAULT NULL,
  `recipientid` int(11) DEFAULT NULL,
  `invoicedate` date DEFAULT NULL,
  `duedate` date DEFAULT NULL,
  `departdate` date DEFAULT NULL,
  `ppnpercent` int(11) DEFAULT '0',
  `bankid` int(11) DEFAULT NULL,
  `invoicetypeid` int(11) DEFAULT NULL,
  `note` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,  
  `status` tinyint(1) DEFAULT '0',
  `amount` decimal(20,2) DEFAULT '0.00',
  `paidamount` decimal(20,2) DEFAULT '0.00',
  `ppnamount` decimal(20,2) DEFAULT '0.00',
  `insurance` decimal(20,2) DEFAULT '0.00',
  `quarantine` decimal(20,2) DEFAULT '0.00',
  `shipid` int(11) DEFAULT NULL,
  `invoicename` varchar(100) DEFAULT NULL,
  `invoiceaddr` varchar(500) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `branchid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',  
  PRIMARY KEY (`id`),
  FOREIGN KEY (`senderid`) REFERENCES sender(`id`),
  FOREIGN KEY (`recipientid`) REFERENCES recipient(`id`),
  FOREIGN KEY (`bankid`) REFERENCES bank(`id`),
  FOREIGN KEY (`shipid`) REFERENCES ship(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `invoicedtl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoicemasid` int(11) DEFAULT NULL,
  `domasid` int(11) DEFAULT NULL,
  `amount` decimal(20,2) DEFAULT '0.00',  
  `note` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,  
  `status` tinyint(1) DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `branchid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',  
  PRIMARY KEY (`id`),
  FOREIGN KEY (`invoicemasid`) REFERENCES invoicemas(`id`),
  FOREIGN KEY (`domasid`) REFERENCES domas(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `cashierinmas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cashierinno` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cashierindate` date DEFAULT NULL,
  `bankid` int(11) DEFAULT NULL,  
  `invoicetypeid` int(11) DEFAULT NULL,
  `senderid` int(11) DEFAULT NULL,
  `recipientid` int(11) DEFAULT NULL,
  `note` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,  
  `amount` decimal(20,2) DEFAULT '0.00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `branchid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',  
  PRIMARY KEY (`id`),
  FOREIGN KEY (`bankid`) REFERENCES bank(`id`),
  FOREIGN KEY (`senderid`) REFERENCES sender(`id`),
  FOREIGN KEY (`recipientid`) REFERENCES recipient(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `cashierindtl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cashierinmasid` int(11) NOT NULL,
  `invoicemasid` int(11) DEFAULT NULL,
  `amount` decimal(20,2) DEFAULT '0.00',
  `note` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,  
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `branchid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',  
  PRIMARY KEY (`id`),
  FOREIGN KEY (`cashierinmasid`) REFERENCES cashierinmas(`id`),
  FOREIGN KEY (`invoicemasid`) REFERENCES invoicemas(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `title` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

