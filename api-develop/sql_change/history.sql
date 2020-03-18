

feature note : Penambahan feature 419 on expiration
date         : Sun Feb 16 22:43:16 2020 +0700
git log      : c9df1354b2e4403a30d98a8462723137b3abb6ca
alter query  : 
    1. eksekusi query alter ini: ALTER TABLE `tbl_user` ADD COLUMN `expired_date` DATETIME NOT NULL DEFAULT '2000-01-01 00:00:00' AFTER `modified_date`;
    2. Penambahan kolom "expired_date" pada v_user setelah kolom "activated_date"
    3. pastikan pada v_user sudah ditambah "expired_date"