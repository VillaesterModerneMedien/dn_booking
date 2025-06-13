# Conversion to utf8mb4

-- Drop specific columns if they exist
ALTER TABLE `#__dnbooking_openinghours`
    DROP PRIMARY KEY,
    DROP COLUMN IF `id`,
    DROP COLUMN IF `notes`;

-- Add required columns if they don't exist
ALTER TABLE `#__dnbooking_openinghours`
    ADD COLUMN IF NOT EXISTS `day` date NOT NULL,
    ADD COLUMN IF NOT EXISTS `opening_time` int(11) NULL;

-- Drop existing primary key if it exists and set day as primary key
ALTER TABLE `#__dnbooking_openinghours`
    ADD PRIMARY KEY (`day`);