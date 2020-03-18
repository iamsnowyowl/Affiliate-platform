<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "create_jobs" => array(
        array(
            'field' => 'jobs_id',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'jobs_code',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'jobs_name',
            'rules' => 'trim|required'
        )
    )
);