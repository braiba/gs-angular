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

ALTER TABLE `carts`
ADD COLUMN `shipping_type_ID` INT UNSIGNED NOT NULL DEFAULT 1 AFTER `paypal_token`,
ADD INDEX `c_shipping_type_ID_idx` (`shipping_type_ID` ASC);

ALTER TABLE `carts`
ADD CONSTRAINT `c_shipping_type_ID`
FOREIGN KEY (`shipping_type_ID`)
REFERENCES `shipping_type` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

UPDATE `category_types` SET `url_chunk`='fandoms' WHERE `category_type_ID`='1';
UPDATE `category_types` SET `url_chunk`='genres' WHERE `category_type_ID`='2';
UPDATE `category_types` SET `url_chunk`='products' WHERE `category_type_ID`='3';
UPDATE `category_types` SET `url_chunk`='limited-editions' WHERE `category_type_ID`='4';

UPDATE patterns SET url_chunk = REPLACE(url_chunk, "(", "") WHERE url_chunk LIKE "%(%";
UPDATE patterns SET url_chunk = REPLACE(url_chunk, ")", "") WHERE url_chunk LIKE "%)%";
UPDATE patterns SET url_chunk = REPLACE(url_chunk, "'", "") WHERE url_chunk LIKE "%'%";

UPDATE categories SET name = "Pokémon" WHERE url_chunk = "pokemon";