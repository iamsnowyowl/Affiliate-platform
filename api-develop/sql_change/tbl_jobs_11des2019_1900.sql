CREATE TABLE `tbl_jobs` (
  `row_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `jobs_id` varchar(255) NOT NULL,
  `jobs_code` varchar(255) NOT NULL,
  `jobs_name` varchar(255) NOT NULL,
  `created_by` int(11) unsigned NOT NULL DEFAULT '0',
  `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
  `deleted_at` datetime NOT NULL DEFAULT '2000-01-01 00:00:00',
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`row_id`),
  UNIQUE KEY `jobs_code_UNIQUE` (`jobs_code`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

Buat `v_assessment_deleted`
 SELECT 
        `tbl_assessment`.`row_id` AS `row_id`,
        `tbl_assessment`.`assessment_id` AS `assessment_id`,
        `tbl_assessment`.`sub_schema_number` AS `sub_schema_number`,
        `tbl_assessment`.`gdrive_file_id` AS `gdrive_file_id`,
        `tbl_assessment`.`gdrive_letter_id` AS `gdrive_letter_id`,
        `tbl_assessment`.`tuk_id` AS `tuk_id`,
        `tbl_assessment`.`title` AS `title`,
        `tbl_assessment`.`notes` AS `notes`,
        `tbl_assessment`.`last_activity_state` AS `last_activity_state`,
        `tbl_assessment`.`last_activity_description` AS `last_activity_description`,
        `tbl_assessment`.`address` AS `address`,
        `tbl_assessment`.`request_letter_url` AS `request_letter_url`,
        `tbl_assessment`.`longitude` AS `longitude`,
        `tbl_assessment`.`latitude` AS `latitude`,
        `tbl_assessment`.`schema_text` AS `schema_text`,
        `tbl_assessment`.`created_by` AS `created_by`,
        `tbl_assessment`.`modified_by` AS `modified_by`,
        `tbl_assessment`.`start_date` AS `start_date`,
        `tbl_assessment`.`end_date` AS `end_date`,
        `tbl_assessment`.`pleno_date` AS `pleno_date`,
        `tbl_assessment`.`request_date` AS `request_date`,
        `tbl_assessment`.`deleted_at` AS `deleted_at`,
        `tbl_assessment`.`created_date` AS `created_date`,
        `tbl_assessment`.`modified_date` AS `modified_date`,
        `tbl_assessment`.`archive_flag` AS `archive_flag`
    FROM
        `tbl_assessment`
    WHERE
        (`tbl_assessment`.`deleted_at` BETWEEN '2000-01-01 00:00:01' AND NOW())
