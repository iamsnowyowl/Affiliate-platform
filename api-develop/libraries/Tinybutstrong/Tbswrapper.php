<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH."libraries/Tinybutstrong/Tbs_class.php");
require_once(APPPATH."libraries/Tinybutstrong/tbs_plugin_opentbs.php");

$GLOBALS["x_num"] = 3152.456;
$GLOBALS["x_pc"] = 0.2567;
$GLOBALS["x_dt"] = mktime(13,0,0,2,15,2010);
$GLOBALS["x_bt"] = true;
$GLOBALS["x_bf"] = false;
$GLOBALS["x_delete"] = 1;
$GLOBALS["yourname"] = 'ari djemana';
class Tbswrapper {
	/**
     * TinyButStrong instance
     *
     * @var object
     */
    public static $TBS = null;

    /**
     * default constructor
     *
     */
    public function __construct(){
        if (self::$TBS == null) {
            self::$TBS = new clsTinyButStrong();
            self::$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
        }
    }

    public function tbsLoadTemplate($File, $HtmlCharSet=''){
        return self::$TBS->LoadTemplate($File, $HtmlCharSet);
    }

    public function tbsSetGlobal($global){
        foreach ($global as $key => $value) {
            self::$TBS->VarRef[$key] = $value;
        }
    }

    public function tbsMergeBlock($BlockName, $Source){
        return self::$TBS->MergeBlock($BlockName, $Source);
    }

    public function tbsMergeField($BaseName, $X){
        return self::$TBS->MergeField($BaseName, $X);
    }

    public function tbsRender(){
        self::$TBS->Show(TBS_NOTHING);
        return self::$TBS->Source;
    }

    public function tbsShow($constant, $output_file_name) {
        self::$TBS->Show($constant, $output_file_name);
        return self::$TBS->Source;
    }
}