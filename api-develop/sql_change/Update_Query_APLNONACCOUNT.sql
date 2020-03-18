feature note
: Penambahan Kolom v_assessment_applicant
date         : Thus Mar 12
git log      : 26d3f1431d51f716d90121b45a25ac254504c4c6 | 26d3f143
alter query  : 
1 changing from `v_user_applicant`.`jobs_code` AS `jobs_code`, Line 22
  TO
      IF((`tbl_assessment_applicant`.`applicant_id` = '0'),
			    `tbl_assessment_applicant`.`jobs_code`,
          `v_user_applicant`.`jobs_code`) AS `jobs_code`,
2 adding column after `v_user_applicant`.`jobs_name` AS `jobs_name` AS `tbl_assessment_applicant`.`pendidikan_terakhir` AS `pendidikan_terakhir`

