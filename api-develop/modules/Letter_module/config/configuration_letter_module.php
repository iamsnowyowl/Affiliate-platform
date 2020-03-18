<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config["pk_use_ai"] = FALSE;
$config["soft_delete"] = TRUE;
$config["check_unique"] = TRUE;
$config["hard_delete_word"] = "letter_name";

// media configuration
$config["enable_media"] = FALSE;
$config["media_column_name"] = "media";
/* 
*	available option is [FILE, BINARY]. 
*	default is FILE if option is not one of [FILE,BINARY]. 
* 	keep in mind: Use BINARY when your priority is confidentiality. 
*/
$config["media_store_operation"] = "FILE";

$config["request_letter"] = [];
$config["request_letter"]["template"] = getenv("FILE_PROTECTED_PATH")."/letter_template/TUK/SURAT_PERMOHONAN.docx"; 
$config["request_letter"]["filename"] = "Surat Permohonan TUK.docx"; 
$config["surat_tugas_assessor"] = []; 
$config["surat_tugas_assessor"]["template"] = getenv("FILE_PROTECTED_PATH")."/letter_template/SURAT_TUGAS_ASESOR.docx"; 
$config["surat_tugas_assessor"]["filename"] = "Surat Tugas Asesor.docx"; 
$config["surat_tugas_admin"] = []; 
$config["surat_tugas_admin"]["template"] = getenv("FILE_PROTECTED_PATH")."/letter_template/SURAT_TUGAS_ADMIN.docx"; 
$config["surat_tugas_admin"]["filename"] = "Surat Tugas Admin.docx"; 
$config["surat_tugas_pleno"] = []; 
$config["surat_tugas_pleno"]["template"] = getenv("FILE_PROTECTED_PATH")."/letter_template/SURAT_TUGAS_PLENO.docx"; 
$config["surat_tugas_pleno"]["filename"] = "SK TUK.docx";
$config["baps"] = []; 
$config["baps"]["template"] = getenv("FILE_PROTECTED_PATH")."/letter_template/BAPS.xlsx"; 
$config["baps"]["filename"] = "Berita Acara Penerbitan Sertifikat.xlsx";
$config["apl01"] = []; 
$config["apl01"]["template"] = getenv("FILE_PROTECTED_PATH")."/letter_template/APL01.docx"; 
$config["apl01"]["filename"] = "APL01.docx";
$config["fr_mak_05"] = [];
$config["fr_mak_05"]["template"] = getenv("FILE_PROTECTED_PATH")."/letter_template/FR_MAK_05.docx";
$config["fr_mak_05"]["filename"] = "FR-MAK 05.docx";