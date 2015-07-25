ALTER TABLE `billing_scheduled_invoice_positions` ADD `is_active` TINYINT(1)  UNSIGNED  NOT NULL  DEFAULT '0'  AFTER `amount`;
