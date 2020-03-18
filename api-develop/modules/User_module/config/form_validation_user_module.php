<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_user" => array(
		array(
			'field' => 'username',
			'rules' => 'trim|required|min_length[3]|max_length[100]'
		),
		array(
			'field' => 'email',
			'rules' => 'trim|required|valid_email|min_length[3]|max_length[100]'
		),
		array(
			'field' => 'first_name',
			'rules' => 'trim|required|min_length[2]|max_length[100]|ucwords'
		),
		array(
			'field' => 'last_name',
			'rules' => 'trim|min_length[2]|max_length[100]|ucwords'
		),
		array(
			'field' => 'gender_code',
			'rules' => 'trim|required|min_length[1]|in_list[N,M,F]'
		),
		array(
			'field' => 'contact',
			'rules' => 'trim|required'
			// 'rules' => 'trim|required|is_natural|min_length[3]|max_length[18]'
		),
		array(
			'field' => 'jobs_code',
			'rules' => 'trim'
		),
		array(
			'field' => 'jobs_name',
			'rules' => 'trim'
		),
		array(
			'field' => 'address',
			'rules' => 'trim|max_length[255]'
		),
		array(
			'field' => 'place_of_birth',
			'rules' => 'trim|max_length[50]'
		),
		array(
			'field' => 'date_of_birth',
			'rules' => 'trim'
		),
		array(
			'field' => 'activated_date',
			'rules' => 'trim|valid_date[Y-m-d H:i:s]'
		),
		array(
			'field' => 'expired_date',
			'rules' => 'trim|valid_date[Y-m-d H:i:s]'
		),
		array(
			'field' => 'signature',
			'rules' => 'trim'
		)
	),
	"update_user" => array(
		array(
			'field' => 'role_code',
			'rules' => 'trim|exact_length[3]'
		),
		array(
			'field' => 'first_name',
			'rules' => 'trim|min_length[2]|max_length[100]|ucwords'
		),
		array(
			'field' => 'last_name',
			'rules' => 'trim|min_length[2]|max_length[100]|ucwords'
		),
		array(
			'field' => 'group_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'picture',
			'rules' => 'trim'
		),
		array(
			'field' => 'contact',
			'rules' => 'trim|is_natural|min_length[3]|max_length[18]'
		),
		array(
			'field' => 'gender_code',
			'rules' => 'trim|min_length[1]|in_list[N,M,F]'
		),
		array(
			'field' => 'is_active',
			'rules' => 'trim|exact_length[1]|in_list[0,1]'
		),
		array(
			'field' => 'address',
			'rules' => 'trim|max_length[255]'
		),
		array(
			'field' => 'jobs_code',
			'rules' => 'trim'
		),
		array(
			'field' => 'jobs_name',
			'rules' => 'trim'
		),
		array(
			'field' => 'place_of_birth',
			'rules' => 'trim|max_length[50]'
		),
		array(
			'field' => 'date_of_birth',
			'rules' => 'trim'
		),
		array(
			'field' => 'activated_date',
			'rules' => 'trim|valid_date[Y-m-d H:i:s]'
		),
		array(
			'field' => 'signature',
			'rules' => 'trim'
		),
		array(
			'field' => 'deleted_at',
			'rules' => 'trim'
		),
		array(
			'field' => 'modified_by',
			'rules' => 'trim'
		),
		array(
			'field' => 'expired_date',
			'rules' => 'trim|valid_date[Y-m-d H:i:s]'
		)
	),
	"update_user_password_by_old_password" => array(
		array(
			'field' => 'oldpassword',
			'rules' => 'trim|min_length[6]|required|max_length[100]'
		),
		array(
			'field' => 'password',
			'rules' => 'trim|min_length[6]|required|max_length[100]'
		),
		array(
			'field' => 'passconf',
			'rules' => 'trim|matches[password]|required'
		)
	),
	"update_user_password" => array(
		array(
			'field' => 'password',
			'rules' => 'trim|min_length[6]|required|max_length[100]'
		),
		array(
			'field' => 'passconf',
			'rules' => 'trim|matches[password]|required'
		)
	),
	"login" => array(
		array(
				'field' => 'username_email',
				'rules' => 'trim|required|min_length[3]|max_length[100]'
		),
		array(
				'field' => 'password',
				'rules' => 'trim|required|min_length[6]|max_length[100]'
		)
	),
	"reset_password" => array(
		array(
				'field' => 'email',
				'rules' => 'trim|required|valid_email|min_length[3]|max_length[100]'
		)
	)
);