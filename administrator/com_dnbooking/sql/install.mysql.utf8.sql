--
-- Table structure for table `#__dnbooking_reservations`
--
CREATE TABLE if not exists `#__dnbooking_reservations` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
    `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `content` mediumtext COLLATE utf8mb4_unicode_ci,
    `reservation_price` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `reservation_status` int(11) NOT NULL DEFAULT '0',
    `customer_id` int(11) NOT NULL DEFAULT '0',
    `room_id` int(11) NOT NULL DEFAULT '0',
    `persons_count` int(11) NOT NULL DEFAULT '0',
    `extras_ids` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `additional_info` json NOT NULL,
    `reservation_date` datetime NOT NULL,
    `published` tinyint(4) NOT NULL DEFAULT '0',
    `created` datetime NOT NULL,
    `created_by` int(10) unsigned NOT NULL DEFAULT '0',
    `modified` datetime NOT NULL,
    `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `idx_createdby` (`created_by`),
    KEY `published` (`published`),
    KEY `idx_alias` (`alias`)

) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE if not exists `#__dnbooking_rooms` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
    `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `description` mediumtext COLLATE utf8mb4_unicode_ci,
    `images` json NOT NULL,
    `personsmin` int(11) NOT NULL DEFAULT '0',
    `personsmax` int(11) NOT NULL DEFAULT '0',
    `priceregular` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `pricecustom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `published` tinyint(4) NOT NULL DEFAULT '0',
    `created` datetime NOT NULL,
    `created_by` int(10) unsigned NOT NULL DEFAULT '0',
    `modified` datetime NOT NULL,
    `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `idx_createdby` (`created_by`),
    KEY `published` (`published`),
    KEY `idx_alias` (`alias`)

) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__dnbooking_rooms`
(`alias`, `title`, `description`, `images`, `personsmin`, `personsmax`, `priceregular`, `pricecustom`, `published`, `created`, `created_by`, `modified`, `modified_by`)
VALUES
    ('room101', 'Standardzimmer', 'Dies ist ein Standardzimmer', '{}', 1, 2, 100.0, 200.0, 1, '2023-04-01 10:00:00', 1, '2023-04-01 10:00:00', 1);

CREATE TABLE if not exists `#__dnbooking_extras` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
    `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `description` mediumtext COLLATE utf8mb4_unicode_ci,
    `image`  varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `price` float NOT NULL DEFAULT '0',
    `published` tinyint(4) NOT NULL DEFAULT '0',
    `created` datetime NOT NULL,
    `created_by` int(10) unsigned NOT NULL DEFAULT '0',
    `modified` datetime NOT NULL,
    `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `idx_createdby` (`created_by`),
    KEY `published` (`published`),
    KEY `idx_alias` (`alias`)

    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__dnbooking_extras`
(`alias`, `title`, `description`, `image`, `price`, `published`, `created`, `created_by`, `modified`, `modified_by`)
VALUES
    ('beispiel_alias', 'Beispielextra', 'Dies ist eine Beschreibung des Extras', 'bild.jpg', 25.50, 1, NOW(), 1, NOW(), 1);

CREATE TABLE if not exists `#__dnbooking_customers` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
    `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `zip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `published` tinyint(4) NOT NULL DEFAULT '0',
    `created` datetime NOT NULL,
    `created_by` int(10) unsigned NOT NULL DEFAULT '0',
    `modified` datetime NOT NULL,
    `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `idx_createdby` (`created_by`),
    KEY `published` (`published`),
    KEY `idx_alias` (`alias`)

) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__dnbooking_customers` (
    alias,
    title,
    firstname,
    lastname,
    email,
    phone,
    address,
    city,
    zip,
    country,
    published,
    created,
    created_by,
    modified,
    modified_by
) VALUES (
             'john_doe',
             'Mr',
             'John',
             'Doe',
             'john.doe@example.com',
             '1234567890',
             '123 street',
             'city',
             '12345',
             'country',
             1,
             NOW(),
             1,
             NOW(),
             1
         );