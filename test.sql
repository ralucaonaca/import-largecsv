-- Create table for data
CREATE TABLE product (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  description varchar(255) NOT NULL,
  code varchar(10) NOT NULL,
  created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  discontinued_at datetime DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores product data';

ALTER TABLE product
   CHANGE code code VARCHAR(10) NOT NULL AFTER id;

ALTER TABLE product
ADD stock INT(11)  AFTER description;

ALTER TABLE product
ADD cost FLOAT AFTER stock;


CREATE TABLE `csvimport` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `original_filename` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `row_count` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `csvrow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(255) NOT NULL DEFAULT '',
  `csv_file_import_id` int(11) DEFAULT NULL,
  `header` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `content` (`content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;