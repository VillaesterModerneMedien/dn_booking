-- Tabelle #__dnbooking_reservations erstellen oder aktualisieren
CREATE TABLE IF NOT EXISTS `#__dnbooking_reservations` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Spalten für #__dnbooking_reservations hinzufügen
ALTER TABLE `#__dnbooking_reservations`
    ADD COLUMN IF NOT EXISTS `admin_notes` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS `customer_notes` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS `general_notes` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS `reservation_price` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    ADD COLUMN IF NOT EXISTS `holiday` int(1) NOT NULL DEFAULT '0',
    ADD COLUMN IF NOT EXISTS `customer_id` int(11) NOT NULL DEFAULT '0',
    ADD COLUMN IF NOT EXISTS `room_id` int(11) NOT NULL DEFAULT '0',
    ADD COLUMN IF NOT EXISTS `extras_ids` json NOT NULL,
    ADD COLUMN IF NOT EXISTS `additional_info` json NOT NULL,
    ADD COLUMN IF NOT EXISTS `additional_infos2` json NOT NULL,
    ADD COLUMN IF NOT EXISTS `reservation_date` datetime NOT NULL,
    ADD COLUMN IF NOT EXISTS `reservation_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '',
    ADD COLUMN IF NOT EXISTS `published` tinyint(4) NOT NULL DEFAULT '0',
    ADD COLUMN IF NOT EXISTS `created` datetime NOT NULL,
    ADD COLUMN IF NOT EXISTS `modified` datetime NOT NULL,
    ADD COLUMN IF NOT EXISTS `discount` float(6) NOT NULL DEFAULT '0',
    ADD COLUMN IF NOT EXISTS `meal_time` varchar(100) NOT NULL;

-- Indizes für #__dnbooking_reservations hinzufügen
ALTER TABLE `#__dnbooking_reservations`
    ADD INDEX IF NOT EXISTS `idx_customer_id` (`customer_id`),
    ADD INDEX IF NOT EXISTS `published` (`published`);


-- Tabelle #__dnbooking_openinghours erstellen oder aktualisieren
CREATE TABLE IF NOT EXISTS `#__dnbooking_openinghours` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Spalten für #__dnbooking_openinghours hinzufügen
ALTER TABLE `#__dnbooking_openinghours`
    ADD COLUMN IF NOT EXISTS `day` date NOT NULL,
    ADD COLUMN IF NOT EXISTS `opening_time` int(11) NULL,
    ADD COLUMN IF NOT EXISTS `notes` mediumtext COLLATE utf8mb4_unicode_ci;

-- Tabelle #__dnbooking_rooms erstellen oder aktualisieren
CREATE TABLE IF NOT EXISTS `#__dnbooking_rooms` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Spalten für #__dnbooking_rooms hinzufügen
ALTER TABLE `#__dnbooking_rooms`
    ADD COLUMN IF NOT EXISTS `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
    ADD COLUMN IF NOT EXISTS `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    ADD COLUMN IF NOT EXISTS `description` mediumtext COLLATE utf8mb4_unicode_ci,
    ADD COLUMN IF NOT EXISTS `images` json NOT NULL,
    ADD COLUMN IF NOT EXISTS `personsmin` int(11) NOT NULL DEFAULT '0',
    ADD COLUMN IF NOT EXISTS `personsmax` int(11) NOT NULL DEFAULT '0',
    ADD COLUMN IF NOT EXISTS `ordering` int(11) NOT NULL DEFAULT '0',
    ADD COLUMN IF NOT EXISTS `priceregular` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    ADD COLUMN IF NOT EXISTS `pricecustom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    ADD COLUMN IF NOT EXISTS `published` tinyint(4) NOT NULL DEFAULT '0',
    ADD COLUMN IF NOT EXISTS `created` datetime NOT NULL,
    ADD COLUMN IF NOT EXISTS `modified` datetime NOT NULL;

-- Indizes für #__dnbooking_rooms hinzufügen
ALTER TABLE `#__dnbooking_rooms`
    ADD INDEX IF NOT EXISTS `published` (`published`),
    ADD INDEX IF NOT EXISTS `idx_alias` (`alias`);

-- Tabelle #__dnbooking_extras erstellen oder aktualisieren
CREATE TABLE IF NOT EXISTS `#__dnbooking_extras` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Spalten für #__dnbooking_extras hinzufügen
ALTER TABLE `#__dnbooking_extras`
    ADD COLUMN IF NOT EXISTS `ordering` int(11) NOT NULL DEFAULT '0',
    ADD COLUMN IF NOT EXISTS `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
    ADD COLUMN IF NOT EXISTS `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    ADD COLUMN IF NOT EXISTS `description` mediumtext COLLATE utf8mb4_unicode_ci,
    ADD COLUMN IF NOT EXISTS `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    ADD COLUMN IF NOT EXISTS `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'regular',
    ADD COLUMN IF NOT EXISTS `price` float NOT NULL DEFAULT '0',
    ADD COLUMN IF NOT EXISTS `published` tinyint(4) NOT NULL DEFAULT '0',
    ADD COLUMN IF NOT EXISTS `created` datetime NOT NULL,
    ADD COLUMN IF NOT EXISTS `modified` datetime NOT NULL;

-- Indizes für #__dnbooking_extras hinzufügen
ALTER TABLE `#__dnbooking_extras`
    ADD INDEX IF NOT EXISTS `published` (`published`),
    ADD INDEX IF NOT EXISTS `idx_alias` (`alias`);

-- Tabelle #__dnbooking_customers erstellen oder aktualisieren
CREATE TABLE IF NOT EXISTS `#__dnbooking_customers` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Spalten für #__dnbooking_customers hinzufügen
ALTER TABLE `#__dnbooking_customers`
    ADD COLUMN IF NOT EXISTS `salutation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    ADD COLUMN IF NOT EXISTS `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    ADD COLUMN IF NOT EXISTS `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    ADD COLUMN IF NOT EXISTS `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    ADD COLUMN IF NOT EXISTS `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    ADD COLUMN IF NOT EXISTS `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    ADD COLUMN IF NOT EXISTS `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    ADD COLUMN IF NOT EXISTS `zip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    ADD COLUMN IF NOT EXISTS `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    ADD COLUMN IF NOT EXISTS `published` tinyint(4) NOT NULL DEFAULT '0',
    ADD COLUMN IF NOT EXISTS `created` datetime NOT NULL,
    ADD COLUMN IF NOT EXISTS `modified` datetime NOT NULL;

-- Index für #__dnbooking_customers hinzufügen
ALTER TABLE `#__dnbooking_customers`
    ADD INDEX IF NOT EXISTS `published` (`published`);

-- E-Mail-Templates einfügen (nur wenn sie noch nicht existieren)
INSERT IGNORE INTO `#__mail_templates` (`template_id`, `extension`, `language`, `subject`, `body`, `htmlbody`, `attachments`, `params`)
VALUES
    ('com_dnbooking.reservation_downpayment', 'com_dnbooking', '',
     'Bestätigung Zahlungseingang für Geburtstagsraum:{ROOM_TITLE}',
     'Name: {CUSTOMER_FIRSTNAME} {CUSTOMER_LASTNAME}\r\nEmail: {CUSTOMER_EMAIL}', '', '',
     '{\"tags\":[\"customer_firstname\",\"customer_lastname\",\"admin_notes\",\"customer_email\", \"html_ordertable_simple\"]}'),
    ('com_dnbooking.reservation_closed', 'com_dnbooking', '',
     'Ihre Buchung vom: {RESERVATION_DATE} wurde abgeschlossen',
     'Name: {CUSTOMER_FIRSTNAME} {CUSTOMER_LASTNAME}\r\nEmail: {CUSTOMER_EMAIL}', '', '',
     '{\"tags\":[\"customer_firstname\",\"customer_lastname\",\"admin_notes\",\"customer_email\", \"html_ordertable_simple\"]}'),
    ('com_dnbooking.reservation_pending', 'com_dnbooking', '',
     'Reservierung eingegangen von {CUSTOMER_FIRSTNAME} {CUSTOMER_LASTNAME}',
     'Name: {CUSTOMER_FIRSTNAME} {CUSTOMER_LASTNAME}\r\nEmail: {CUSTOMER_EMAIL}', '', '',
     '{\"tags\":[\"customer_firstname\",\"customer_lastname\",\"admin_notes\",\"customer_email\"]}'),
    ('com_dnbooking.reservation_cancelled', 'com_dnbooking', '',
     'Reservierung ID {ID}  für den {RESERVATION_DATE} storniert.',
     'Name: {CUSTOMER_FIRSTNAME} {CUSTOMER_LASTNAME}\r\nEmail: {CUSTOMER_EMAIL}', '', '',
     '{\"tags\":[\"customer_firstname\",\"customer_lastname\",\"admin_notes\",\"customer_email\"]}');
