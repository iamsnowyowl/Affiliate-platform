<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_applicant" => array(
		array(
			'field' => 'user_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'tuk_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'institution',
			'rules' => 'trim'
		),
		array(
			'field' => 'schedule_assessment_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'nik',
			'rules' => 'trim'
		),
		array(
			'field' => 'npwp',
			'rules' => 'trim'
		),
		array(
			'field' => 'nomor_skema',
			'rules' => 'trim'
		),
		array(
			'field' => 'tujuan_assessment',
			'rules' => 'trim'
		),
		array(
			'field' => 'kebangsaan',
			'rules' => 'trim'
		),
		array(
			'field' => 'kode_pos',
			'rules' => 'trim'
		),
		array(
			'field' => 'telepon_rumah',
			'rules' => 'trim'
		),
		array(
			'field' => 'telepon_kantor',
			'rules' => 'trim'
		),
		array(
			'field' => 'pendidikan_terakhir',
			'rules' => 'trim'
		),
		array(
			'field' => 'nama_lembaga',
			'rules' => 'trim'
		),
		array(
			'field' => 'jabatan',
			'rules' => 'trim'
		),
		array(
			'field' => 'alamat_pekerjaan',
			'rules' => 'trim'
		),
		array(
			'field' => 'kode_pos_pekerjaan',
			'rules' => 'trim'
		),
		array(
			'field' => 'telepon_pekerjaan',
			'rules' => 'trim'
		),
		array(
			'field' => 'fax_pekerjaan',
			'rules' => 'trim'
		),
		array(
			'field' => 'email_pekerjaan',
			'rules' => 'trim'
		),
		array(
			'field' => 'jobs_code',
			'rules' => 'trim'
		),
		array(
			'field' => 'religion',
			'rules' => 'trim'
		),
		array(
			'field' => 'nik_photo',
			'rules' => 'trim'
		),
		array(
			'field' => 'last_education_certificate_photo',
			'rules' => 'trim'
		),
		array(
			'field' => 'training_certificate_photo',
			'rules' => 'trim'
		),
		array(
			'field' => 'colored_photo',
			'rules' => 'trim'
		),
		array(
			'field' => 'family_card_photo',
			'rules' => 'trim'
		),
		array(
			'field' => 'npwp_photo',
			'rules' => 'trim'
		)
	),
	"update_applicant" => array(
		array(
			'field' => 'institution',
			'rules' => 'trim'
		),
		array(
			'field' => 'schedule_assessment_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'nik',
			'rules' => 'trim'
		),
		array(
			'field' => 'npwp',
			'rules' => 'trim'
		),
		array(
			'field' => 'jobs_code',
			'rules' => 'trim'
		),
		array(
			'field' => 'religion',
			'rules' => 'trim'
		),
		array(
			'field' => 'nik_photo',
			'rules' => 'trim'
		),
		array(
			'field' => 'pendidikan_terakhir',
			'rules' => 'trim'
		),
		array(
			'field' => 'last_education_certificate_photo',
			'rules' => 'trim'
		),
		array(
			'field' => 'training_certificate_photo',
			'rules' => 'trim'
		),
		array(
			'field' => 'colored_photo',
			'rules' => 'trim'
		),
		array(
			'field' => 'family_card_photo',
			'rules' => 'trim'
		),
		array(
			'field' => 'npwp_photo',
			'rules' => 'trim'
		)
	)
);