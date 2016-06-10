CREATE TABLE `shipping_type` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `handle` VARCHAR(45) NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `cost` DECIMAL(6,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `handle_UNIQUE` (`handle` ASC)
);

INSERT INTO `shipping_type` (`handle`, `name`, `cost`) VALUES ('UK', 'Standard Royal Mail (UK Only)', '1.50');
INSERT INTO `shipping_type` (`handle`, `name`, `cost`) VALUES ('EU', 'European Shipping', '3.00');
INSERT INTO `shipping_type` (`handle`, `name`, `cost`) VALUES ('ROW', 'Rest of World Shipping', '4.50');
