ALTER TABLE `billing_recurring_invoice_positions` ADD `tags` VARCHAR(250)  NULL  DEFAULT NULL  AFTER `description`;
ALTER TABLE `billing_scheduled_invoice_positions` ADD `tags` VARCHAR(250)  NULL  DEFAULT NULL  AFTER `description`;
