feature note
: Penambahan Kolom Dan Validasi
date         : Wed Mar 11
git log      : 26d3f1431d51f716d90121b45a25ac254504c4c6 | 26d3f143
alter query  : 
    1. eksekusi query
alter
ini:
ALTER TABLE `sertimedia_local_db`.`tbl_assessment_applicant`
ADD COLUMN `jobs_code` VARCHAR
(255) NOT NULL AFTER `date_of_birth_external`,
ADD COLUMN `pendidikan_terakhir` VARCHAR
(45) NOT NULL DEFAULT '' AFTER `jobs_code`;
