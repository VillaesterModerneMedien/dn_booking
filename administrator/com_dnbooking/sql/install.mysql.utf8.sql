--
-- Table structure for table `#__dnbooking_reservations`
--
CREATE TABLE if not exists `#__dnbooking_reservations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `content` mediumtext COLLATE utf8mb4_unicode_ci,
  `reservationfield1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `reservationfield2` int(11) NOT NULL DEFAULT '0',
  `reservationfield3` tinyint(4) NOT NULL DEFAULT '0',
  `published` tinyint(4) NOT NULL DEFAULT '0',
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL,
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `catid` int(11) NOT NULL DEFAULT '0',
  `note` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_catid` (`catid`),
  KEY `featured_catid` (`featured`, `catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `published` (`published`),
  KEY `idx_alias` (`alias`)

) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


