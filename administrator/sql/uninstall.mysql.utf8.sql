-- Uninstall
DROP TABLE IF EXISTS `#__dnbooking_reservations`;
DELETE FROM `#__content_types` WHERE `type_alias` IN ('com_dnbooking.reservation');
DROP TABLE IF EXISTS `#__dnbooking_rooms`;
DELETE FROM `#__content_types` WHERE `type_alias` IN ('com_dnbooking.room');
DROP TABLE IF EXISTS `#__dnbooking_extras`;
DELETE FROM `#__content_types` WHERE `type_alias` IN ('com_dnbooking.extra');
DROP TABLE IF EXISTS `#__dnbooking_customers`;
DELETE FROM `#__content_types` WHERE `type_alias` IN ('com_dnbooking.customer');

DELETE FROM `#__mail_templates` WHERE `extension` = 'com_dnbooking';
