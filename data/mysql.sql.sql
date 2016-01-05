--
-- Database: `platinum`
--

-- -----------------------------------------------------------------------------

--
-- Events table
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `allday` tinyint(1) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `class_name` varchar(255) DEFAULT NULL,
  `editable` tinyint(1) NOT NULL,
  `color` int(11) NOT NULL,
  `background_color` int(11) NOT NULL,
  `border_color` int(11) NOT NULL,
  `text_color` int(11) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -----------------------------------------------------------------------------

--
-- Stock control tables.
--

CREATE TABLE `sc_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sc_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_code` varchar(24) NOT NULL,
  `short_description` varchar(128) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `stock_qty` int(11) NOT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sc_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_code` varchar(8) NOT NULL,
  `location_name` varchar(128) NOT NULL,
  `contact_name` varchar(64) DEFAULT NULL,
  `phone` varchar(24) DEFAULT NULL,
  `fax` varchar(24) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sc_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `movement_type_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `input_date` datetime NOT NULL,
  `trans_date` datetime NOT NULL,
  `qty` int(11) NOT NULL,
  `narrative` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sc_trans_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(3) NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `sc_trans_types` (`id`, `code`, `name`) VALUES
(1, 'GR', 'Goods Receipt'),
(2, 'TRF', 'Stock Transfer'),
(3, 'ADJ', 'Stock Adjustment'),
(4, 'CNT', 'Stock Count'),
(5, 'OUT', 'Booked Out'),
(6, 'SPE', 'Special'),
(8, 'WO', 'Written Off');

CREATE TABLE `sc_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `sc_units` (`id`, `name`) VALUES
(1, 'mm'),
(2, 'Feet'),
(3, 'Each'),
(4, 'Box'),
(5, 'Kg'),
(6, 'Metres');

ALTER TABLE `sc_items`
  ADD CONSTRAINT `FK_2747BB1012469DE2` FOREIGN KEY (`category_id`) REFERENCES `sc_categories` (`id`);

ALTER TABLE `sc_transactions`
  ADD CONSTRAINT `FK_8FC927A7126F525E` FOREIGN KEY (`item_id`) REFERENCES `sc_items` (`id`),
  ADD CONSTRAINT `FK_8FC927A764D218E` FOREIGN KEY (`location_id`) REFERENCES `sc_locations` (`id`),
  ADD CONSTRAINT `FK_8FC927A7EA4ED04A` FOREIGN KEY (`movement_type_id`) REFERENCES `sc_trans_types` (`id`);

-- -----------------------------------------------------------------------------

--
-- Sales tables
--

CREATE TABLE `sa_account` (
  `id`             int(11) NOT NULL AUTO_INCREMENT,
  `account_number` varchar(24) NOT NULL,
  `company_name`   varchar(64) NOT NULL,
  `address`        text DEFAULT NULL,
  `post_code`      varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sa_branch` (
  `id`            int(11) NOT NULL AUTO_INCREMENT,
  `branch_number` varchar(24) NOT NULL,
  `company_name`  varchar(64) NOT NULL,
  `address`       text DEFAULT NULL,
  `post_code`     varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sa_order` (
  `id`            int(11) NOT NULL AUTO_INCREMENT,
  `order_date`    date NOT NULL,
  `order_status`  varchar(24) NOT NULL,
  `customer_ref`  varchar(24),
  `sa_account_id` int(11) NOT NULL,
  `sa_branch_id`  int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sa_order_line` (
  `id`            int(11) NOT NULL AUTO_INCREMENT,
  `sa_order_id`   int(11) NOT NULL,
  `sc_item_id`    int(11) NOT NULL,
  `qty`           int(11) NOT NULL,
  `order_status`  varchar(24) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `sa_order`
  ADD CONSTRAINT `FK_SA_ORDER_SA_ACCOUNT` FOREIGN KEY (`sa_account_id`) REFERENCES `sa_account` (`id`),
  ADD CONSTRAINT `FK_SA_ORDER_SA_BRANCH` FOREIGN KEY (`sa_branch_id`) REFERENCES `sa_branch` (`id`);

ALTER TABLE `sa_order_line`
  ADD CONSTRAINT `FK_SA_ORDER_LINE_SA_ORDER` FOREIGN KEY (`sa_order_id`) REFERENCES `sa_account` (`id`),
  ADD CONSTRAINT `FK_SA_ORDER_LINE_SC_ITEM` FOREIGN KEY (`sc_item_id`) REFERENCES `sa_branch` (`id`);

-- ----------------------------------------------------------------------------- 
--
-- User tables
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `display_name` varchar(50) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user_role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `default` smallint(6) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;