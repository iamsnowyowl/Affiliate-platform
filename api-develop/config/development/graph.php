<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
// only string allowed
$config = array();
$config['select'] = "fields";
$config['search'] = "search";
$config['sort'] = "sort";
$config['group'] = "group";
$config['limit'] = "limit";
$config['offset'] = "offset";
$config['published_time'] = "published_date";
$config['created_time'] = "created_date";
$config['modified_time'] = "modified_date";
$config['separator_filter'] = ",";
$config['separator_search'] = ",";
$config['filter_not_identifier'] = "-";
$config['forbidden_key_search'] = 'id, expired_date, activated_date, published_date, created_date, modified_date, last_login, date_of_birth, user_id, created_by, role_code'; // put here forbidden key for search
$config['range_time_wrapper_start'] = "(";
$config['range_time_wrapper_end'] = ")";
$config['separator_range_time'] = ",";
$config['separator_select'] = ",";
$config['separator_sort'] = ",";
$config['separator_group'] = ",";
$config['separator_published_time'] = ",";
$config['separator_created_time'] = ",";
$config['separator_modified_time'] = ",";
$config['min_filter_in_to_transform'] = 3; // if filter has value more than minimum. then where condition filter will use where_in instead!.
$config['default_offset'] = 0;
$config['default_limit'] = 10;
$config['max_allowed_packet'] = 100;
$config['max_link_page'] = 4; // lenght link left and right. should be even
