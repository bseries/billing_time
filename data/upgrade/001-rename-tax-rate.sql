ALTER TABLE `billing_recurring_invoice_positions` CHANGE `tax_rate` `amount_rate` INT(5)  UNSIGNED  NOT NULL  DEFAULT '0';
ALTER TABLE `billing_recurring_invoice_positions` MODIFY COLUMN `amount_rate` INT(5) UNSIGNED NOT NULL DEFAULT '0' AFTER `amount_type`;
ALTER TABLE `billing_recurring_invoice_positions` DROP `tax_type`;

ALTER TABLE `billing_scheduled_invoice_positions` CHANGE `tax_rate` `amount_rate` INT(5)  UNSIGNED  NOT NULL  DEFAULT '0';
ALTER TABLE `billing_scheduled_invoice_positions` MODIFY COLUMN `amount_rate` INT(5) UNSIGNED NOT NULL DEFAULT '0' AFTER `amount_type`;
ALTER TABLE `billing_scheduled_invoice_positions` DROP `tax_type`;


