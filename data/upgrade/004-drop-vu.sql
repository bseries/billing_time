ALTER TABLE `billing_recurring_invoice_positions` DROP `virtual_user_id`;
ALTER TABLE `billing_recurring_invoice_positions` CHANGE `user_id` `user_id` INT(11)  UNSIGNED  NOT NULL;
ALTER TABLE `billing_scheduled_invoice_positions` DROP `virtual_user_id`;
ALTER TABLE `billing_scheduled_invoice_positions` CHANGE `user_id` `user_id` INT(11)  UNSIGNED  NOT NULL;
