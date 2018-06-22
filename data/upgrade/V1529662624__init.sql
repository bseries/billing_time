-- Create syntax for TABLE 'billing_recurring_invoice_positions'
CREATE TABLE `billing_recurring_invoice_positions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `description` varchar(250) NOT NULL,
  `tags` varchar(250) DEFAULT NULL,
  `quantity` decimal(10,2) unsigned NOT NULL DEFAULT '1.00',
  `amount_currency` char(3) NOT NULL DEFAULT 'EUR',
  `amount_type` char(5) NOT NULL DEFAULT 'net',
  `amount_rate` int(5) unsigned NOT NULL DEFAULT '0',
  `amount` int(10) NOT NULL,
  `frequency` varchar(100) NOT NULL DEFAULT 'monthly',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `runs` int(10) unsigned DEFAULT '0',
  `ran` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='mirrors billing_invoice_positions';

-- Create syntax for TABLE 'billing_scheduled_invoice_positions'
CREATE TABLE `billing_scheduled_invoice_positions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `description` varchar(250) NOT NULL,
  `tags` varchar(250) DEFAULT NULL,
  `quantity` decimal(10,2) unsigned NOT NULL DEFAULT '1.00',
  `amount_currency` char(3) NOT NULL DEFAULT 'EUR',
  `amount_type` char(5) NOT NULL DEFAULT 'net',
  `amount_rate` int(5) unsigned NOT NULL DEFAULT '0',
  `amount` int(10) NOT NULL,
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `run_on` datetime DEFAULT NULL COMMENT 'NULL to run immediately',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='mirrors billing_invoice_posititions';
