<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_assessment" => array(
		array(
			'field' => 'assessment_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'gdrive_file_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'gdrive_letter_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'tuk_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'title',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'notes',
			'rules' => 'trim'
		),
		array(
			'field' => 'address',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'schema_text',
			'rules' => 'trim'
		),
		array(
			'field' => 'longitude',
			'rules' => 'trim'
		),
		array(
			'field' => 'latitude',
			'rules' => 'trim'
		),
		array(
			'field' => 'start_date',
			'rules' => 'trim|required|valid_date[Y-m-d\TH:i:sP]'
		),
		array(
			'field' => 'end_date',
			'rules' => 'trim|required|valid_date[Y-m-d\TH:i:sP]'
		),
		array(
			'field' => 'pleno_date',
			'rules' => 'trim|valid_date[Y-m-d\TH:i:sP]'
		),
		array(
			'field' => 'request_letter_url',
			'rules' => 'trim'
		),
		array(
			'field' => 'request_date',
			'rules' => 'trim'
		),
		array(
			'field' => 'sub_schema_number',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'last_activity_state',
			'rules' => 'trim|in_list[ON_REVISION,TUK_COMPLETE_FORM,ADMIN_CONFIRM_FORM,PORTFOLIO_APPLICANT_COMPLETED,ASSESSOR_READY,ADMIN_READY,DATE_PLACE_FIXED,ALL_LETTER_COMPLETED,ON_REVIEW_APPLICANT_DOCUMENT,ON_COMPLETED_REPORT,REAL_ASSESSMENT,PLENO_DOCUMENT_COMPLETED,PLENO_REPORT_READY,REQUEST_BLANKO_SENDING,COMPLETED,PRINT_CERTIFICATE,ASSESSMENT_REJECTED]'
		)
	),
	"update_assessment" => array(
		array(
			'field' => 'title',
			'rules' => 'trim'
		),
		array(
			'field' => 'gdrive_file_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'gdrive_letter_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'notes',
			'rules' => 'trim'
		),
		array(
			'field' => 'last_activity_state',
			'rules' => 'trim'
		),
		array(
			'field' => 'last_activity_description',
			'rules' => 'trim'
		),
		array(
			'field' => 'address',
			'rules' => 'trim'
		),
		array(
			'field' => 'schema_text',
			'rules' => 'trim'
		),
		array(
			'field' => 'longitude',
			'rules' => 'trim'
		),
		array(
			'field' => 'latitude',
			'rules' => 'trim'
		),
		array(
			'field' => 'start_date',
			'rules' => 'trim|valid_date[Y-m-d\TH:i:sP]'
		),
		array(
			'field' => 'end_date',
			'rules' => 'trim|valid_date[Y-m-d\TH:i:sP]'
		),
		array(
			'field' => 'pleno_date',
			'rules' => 'trim|valid_date[Y-m-d\TH:i:sP]'
		),
		array(
			'field' => 'request_date',
			'rules' => 'trim|valid_date[Y-m-d\TH:i:sP]'
		),
		array(
			'field' => 'request_letter_url',
			'rules' => 'trim'
		),
		array(
			'field' => 'sub_schema_number',
			'rules' => 'trim'
		),
		array(
			'field' => 'archive_flag',
			'rules' => 'trim'
		),
		array(
			'field' => 'last_activity_state',
			'rules' => 'trim|in_list[ON_REVISION,TUK_COMPLETE_FORM,ADMIN_CONFIRM_FORM,TUK_SEND_REQUEST_ASSESSMENT,PORTFOLIO_APPLICANT_COMPLETED,ASSESSOR_READY,ADMIN_READY,DATE_PLACE_FIXED,ALL_LETTER_COMPLETED,ON_REVIEW_APPLICANT_DOCUMENT,ON_COMPLETED_REPORT,REAL_ASSESSMENT,PLENO_DOCUMENT_COMPLETED,PLENO_REPORT_READY,REQUEST_BLANKO_SENDING,COMPLETED,PRINT_CERTIFICATE,ASSESSMENT_REJECTED]'
		),
		array(
			'field' => 'modified_by',
			'rules' => 'trim'
		),
		array(
			'field' => 'deleted_at',
			'rules' => 'trim'
		)
	)
);
