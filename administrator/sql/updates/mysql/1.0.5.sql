# Conversion to utf8mb4

--
-- ALTER TABLE `#__dnbooking_ ...`
--
-- Drop specific columns if they exist
ALTER TABLE `#__dnbooking_reservations`
    MODIFY COLUMN `extra_ids` LONGTEXT;
