-- Uninstall
DROP TABLE IF EXISTS `#__dnbooking_reservations`;
DELETE FROM `#__content_types` WHERE `type_alias` IN ('com_dnbooking.reservation', 'com_dnbooking.category');
