<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Image_lib extends CI_Image_lib {

	protected $_strict;

    public function __construct($props = array())
    {
        parent::__construct($props);
    }

    public function store_image_from_base64($img, array $config)
	{
		// decode image from base64
		$img = base64_decode($img);
		$img = imagecreatefromstring($img);
		$base_path = "";
		$unique_path = "";
		$fullpath = "";

		if ($img === FALSE) {
			return FALSE;
		}

		$base_path .= (!empty($config['path_destination'])) ? $config['base_path'].$config['path_destination'] : $config['base_path'];
		$unique_path .= (!empty($config['unique_path'])) ? $config['unique_path'] : "";

		if (!file_exists($base_path.$unique_path)) {
			mkdir($base_path.$unique_path, 0755, TRUE);
		}
		
		$filename = (!empty($config['filename'])) ? $config['filename'] : "temp_".md5(time());

		switch ($config['ext']) {
			case 'jpg':
			case 'jpeg':
				$fullpath = "$base_path$unique_path/$filename.jpg";
				imagejpeg($img, $fullpath);
				if (!file_exists($fullpath)) {
					// image may fail to create. returning FALSE
					return FALSE;
				}
				break;
			case 'png':
				$fullpath = "$base_path$unique_path/$filename.png";
				imagepng($img, $fullpath);
				if (!file_exists($fullpath)) {
					// image may fail to create. returning FALSE
					return FALSE;
				}
				break;			
		}
		
		imagedestroy($img);

		return TRUE;
	}

	public function store_file_from_base64($file, array $config)
	{
		// decode image from base64
		$expl = explode(",", $file);

		$file = base64_decode(array_pop($expl));
		$base_path = "";
		$unique_path = "";
		$fullpath = "";

		if ($file === FALSE) {
			return FALSE;
		}

		$base_path .= $config['base_path'];
		$unique_path .= (!empty($config['unique_path'])) ? $config['unique_path'] : "";

		if (!file_exists($base_path.$unique_path)) {
			mkdir($base_path.$unique_path, 0755, TRUE);
		}
		
		$filename = (!empty($config['filename'])) ? $config['filename'] : "temp_".md5(time());
		$fullpath = "$base_path$unique_path/$filename";
		file_put_contents($fullpath, $file);
		return $fullpath;
	}

}