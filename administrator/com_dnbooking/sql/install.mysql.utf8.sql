--
-- Table structure for table `#__dnbooking_reservations`
--
CREATE TABLE if not exists `#__dnbooking_reservations`
(
    `id`                int(11) unsigned                        NOT NULL AUTO_INCREMENT,
    `admin_notes`       mediumtext COLLATE utf8mb4_unicode_ci            DEFAULT NULL,
    `customer_notes`    mediumtext COLLATE utf8mb4_unicode_ci            DEFAULT NULL,
    `general_notes`     mediumtext COLLATE utf8mb4_unicode_ci            DEFAULT NULL,
    `reservation_price` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `holiday`           int(1)                                  NOT NULL DEFAULT '0',
    `customer_id`       int(11)                                 NOT NULL DEFAULT '0',
    `room_id`           int(11)                                 NOT NULL DEFAULT '0',
    `extras_ids`        json                                    NOT NULL,
    `additional_info`   json                                    NOT NULL,
    `additional_infos2`  json                                    NOT NULL,
    `reservation_date`  datetime                                NOT NULL,
    `reservation_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '',
    `published`         tinyint(4)                              NOT NULL DEFAULT '0',
    `created`           datetime                                NOT NULL,
    `modified`          datetime                                NOT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_customer_id` (`customer_id`),
    KEY `published` (`published`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

--
-- Table structure for table `#__dnbooking_openinghours`
--
CREATE TABLE if not exists `#__dnbooking_openinghours`
(
    `id`           int(11) unsigned NOT NULL AUTO_INCREMENT,
    `day`          date             NOT NULL,
    `opening_time` int(11)          NULL,
    `notes`        mediumtext COLLATE utf8mb4_unicode_ci,

    PRIMARY KEY (`id`)

) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE if not exists `#__dnbooking_rooms`
(
    `id`           int(11) unsigned                                       NOT NULL AUTO_INCREMENT,
    `alias`        varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
    `title`        varchar(255) COLLATE utf8mb4_unicode_ci                NOT NULL DEFAULT '',
    `description`  mediumtext COLLATE utf8mb4_unicode_ci,
    `images`       json                                                   NOT NULL,
    `personsmin`   int(11)                                                NOT NULL DEFAULT '0',
    `personsmax`   int(11)                                                NOT NULL DEFAULT '0',
    `ordering`     int(11)                                                NOT NULL DEFAULT '0',
    `priceregular` varchar(255) COLLATE utf8mb4_unicode_ci                NOT NULL DEFAULT '',
    `pricecustom`  varchar(255) COLLATE utf8mb4_unicode_ci                NOT NULL DEFAULT '',
    `published`    tinyint(4)                                             NOT NULL DEFAULT '0',
    `created`      datetime                                               NOT NULL,
    `modified`     datetime                                               NOT NULL,
    PRIMARY KEY (`id`),
    KEY `published` (`published`),
    KEY `idx_alias` (`alias`)

) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE if not exists `#__dnbooking_extras`
(
    `id`          int(11) unsigned                                       NOT NULL AUTO_INCREMENT,
    `alias`       varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
    `title`       varchar(255) COLLATE utf8mb4_unicode_ci                NOT NULL DEFAULT '',
    `description` mediumtext COLLATE utf8mb4_unicode_ci,
    `image`       varchar(255) COLLATE utf8mb4_unicode_ci                NOT NULL DEFAULT '',
    `price`       float                                                  NOT NULL DEFAULT '0',
    `published`   tinyint(4)                                             NOT NULL DEFAULT '0',
    `created`     datetime                                               NOT NULL,
    `modified`    datetime                                               NOT NULL,
    PRIMARY KEY (`id`),
    KEY `published` (`published`),
    KEY `idx_alias` (`alias`)

) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE if not exists `#__dnbooking_customers`
(
    `id`         int(11) unsigned                        NOT NULL AUTO_INCREMENT,
    `salutation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `firstname`  varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `lastname`   varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `email`      varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `phone`      varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `address`    varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `city`       varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `zip`        varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `published`  tinyint(4)                              NOT NULL DEFAULT '0',
    `created`    datetime                                NOT NULL,
    `modified`   datetime                                NOT NULL,
    PRIMARY KEY (`id`),
    KEY `published` (`published`)

) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;


INSERT INTO `#__mail_templates` (`template_id`, `extension`, `language`, `subject`, `body`, `htmlbody`, `attachments`, `params`) VALUES ('com_dnbooking.reservation_statuschange', 'com_dnbooking', '', 'Kundenemail von {CUSTOMER_FIRSTNAME} {CUSTOMER_LASTNAME}', 'Name: {CUSTOMER_FIRSTNAME} {CUSTOMER_LASTNAME}\r\nEmail: {CUSTOMER_EMAIL}', '', '', '{\"tags\":[\"customer_firstname\",\"customer_lastname\",\"admin_notes\",\"customer_email\", \"html_ordertable_simple\"]}');
INSERT INTO `#__mail_templates` (`template_id`, `extension`, `language`, `subject`, `body`, `htmlbody`, `attachments`, `params`) VALUES ('com_dnbooking.reservation_downpayment', 'com_dnbooking', '', 'Bestätigung Zahlungseingang für Geburtstagsraum:{ROOM_TITLE}', 'Name: {CUSTOMER_FIRSTNAME} {CUSTOMER_LASTNAME}\r\nEmail: {CUSTOMER_EMAIL}', '', '', '{\"tags\":[\"customer_firstname\",\"customer_lastname\",\"admin_notes\",\"customer_email\", \"html_ordertable_simple\"]}');
INSERT INTO `#__mail_templates` (`template_id`, `extension`, `language`, `subject`, `body`, `htmlbody`, `attachments`, `params`) VALUES ('com_dnbooking.reservation_closed', 'com_dnbooking', '', 'Ihre Buchung vom: {RESERVATION_DATE} wurde abgeschlossen', 'Name: {CUSTOMER_FIRSTNAME} {CUSTOMER_LASTNAME}\r\nEmail: {CUSTOMER_EMAIL}', '', '', '{\"tags\":[\"customer_firstname\",\"customer_lastname\",\"admin_notes\",\"customer_email\", \"html_ordertable_simple\"]}');
INSERT INTO `#__mail_templates` (`template_id`, `extension`, `language`, `subject`, `body`, `htmlbody`, `attachments`, `params`) VALUES ('com_dnbooking.reservation_pending', 'com_dnbooking', '', 'Reservierung eingegangen von {CUSTOMER_FIRSTNAME} {CUSTOMER_LASTNAME}', 'Name: {CUSTOMER_FIRSTNAME} {CUSTOMER_LASTNAME}\r\nEmail: {CUSTOMER_EMAIL}', '', '', '{\"tags\":[\"customer_firstname\",\"customer_lastname\",\"admin_notes\",\"customer_email\"]}');
INSERT INTO `#__mail_templates` (`template_id`, `extension`, `language`, `subject`, `body`, `htmlbody`, `attachments`, `params`) VALUES ('com_dnbooking.reservation_confirmed', 'com_dnbooking', '', 'Reservierung ID {ID} bestätigt für den {RESERVATION_DATE}', 'Name: {CUSTOMER_FIRSTNAME} {CUSTOMER_LASTNAME}\r\nEmail: {CUSTOMER_EMAIL}', '', '', '{\"tags\":[\"customer_firstname\",\"customer_lastname\",\"admin_notes\",\"customer_email\"]}');
INSERT INTO `#__mail_templates` (`template_id`, `extension`, `language`, `subject`, `body`, `htmlbody`, `attachments`, `params`) VALUES ('com_dnbooking.reservation_cancelled', 'com_dnbooking', '', 'Reservierung ID {ID}  für den {RESERVATION_DATE} storniert.', 'Name: {CUSTOMER_FIRSTNAME} {CUSTOMER_LASTNAME}\r\nEmail: {CUSTOMER_EMAIL}', '', '', '{\"tags\":[\"customer_firstname\",\"customer_lastname\",\"admin_notes\",\"customer_email\"]}');
INSERT INTO `#__mail_templates` (`template_id`, `extension`, `language`, `subject`, `body`, `htmlbody`, `attachments`, `params`) VALUES ('com_dnbooking.customMail', 'com_dnbooking', '', 'Nachricht von Sensapolis an {CUSTOMER_FIRSTNAME} {CUSTOMER_LASTNAME}', 'Hallo {CUSTOMER_FIRSTNAME} {CUSTOMER_LASTNAME},\r\n\r\n{CUSTOMER_MAILMESSAGE}\r\n\r\nName: {CUSTOMER_FIRSTNAME} {CUSTOMER_LASTNAME}\r\nEmail: {CUSTOMER_EMAIL}', '', '', '{\"tags\":[\"customer_firstname\",\"customer_lastname\",\"admin_notes\",\"customer_email\"]}');
