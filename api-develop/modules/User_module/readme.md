Required module
*Authentication_module
*Error_module
*Session_module
*Permission_module
*Role_module
*Common_module

required config file
*config.php under module to be reconfigure as needed
*form_validation.php under module to be reconfigure as needed

required modified as needed
*configuration for database on application/config
*configuration for index file, session on application/config



<!-- default config for session use redis -->

$config['sess_driver'] = 'redis';
// $config['sess_driver'] = 'database';
// $config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = "tbl_sessions";
// $config['sess_save_path'] = 'tcp://localhost:6379?auth=1f5849c93d35867499a958c16e35ee2141ffc28ff7f65c99d43aee282c03fc88';
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;