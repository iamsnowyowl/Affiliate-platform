<?php
defined('BASEPATH') OR exit ('No direct script access allowed');

$config = array(
    "default" => array(
        'tbl_jobs.row_id' => 'row_id',
        'tbl_jobs.jobs_id' => 'jobs_id',
        'tbl_jobs.jobs_code' => 'jobs_code',
        'tbl_jobs.jobs_name' => 'jobs_name',
        'tbl_jobs.created_by' => 'created_by',
        'tbl_jobs.modified_by' => 'modified_by',
        'tbl_jobs.created_date' => 'created_date',
        'tbl_jobs.modified_date' => 'modified_date'
    ),
    "optional" => array(
        'tbl_jobs.created_by' => 'created_by',
        'tbl_jobs.modified_by' => 'modified_by',
        'tbl_jobs.created_date' => 'created_date',
        'tbl_jobs.modified_date' => 'modified_date'
    )
);