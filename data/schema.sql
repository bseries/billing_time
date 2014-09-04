-- Create syntax for TABLE 'billing_plans'
CREATE TABLE `billing_plans` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `amount_currency` char(3) NOT NULL DEFAULT 'EUR',
  `amount_type` char(5) NOT NULL DEFAULT 'net',
  `amount` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'billing_scheduled_invoice_positions'
CREATE TABLE `billing_scheduled_invoice_positions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `virtual_user_id` int(11) unsigned DEFAULT NULL,
  `description` varchar(250) NOT NULL,
  `quantity` decimal(10,2) unsigned NOT NULL DEFAULT '1.00',
  `amount_currency` char(3) NOT NULL DEFAULT 'EUR',
  `amount_type` char(5) NOT NULL DEFAULT 'net',
  `amount` int(10) NOT NULL,
  `start` date NOT NULL COMMENT 'start of billing period; for info',
  `stop` date NOT NULL COMMENT 'stop of billing period; for info',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='mirrors billing_invoice_posititions';

ALTER TABLE `users` ADD `billing_invoice_frequency` VARCHAR(20) NOT NULL  DEFAULT 'monthly'  AFTER `shipping_address_id`;

