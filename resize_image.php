<?php

$Directory = 'brandcharger_pictures/';

function resizeFile($Directory){
    
    $count = 0;
    if ($handle = opendir($Directory)) {
        $dir = $Directory;
        while (false !== ($fileName = readdir($handle))) {

                if ($fileName == '.' || $fileName == '..') {
                    continue;
                }  
                
                //exit();
                $name = $dir.$fileName;
        		//echo $name;
                if(is_dir($name)) {
                   	resizeFile($name."/");
                	continue;
                };
               
				image_resize($name,700,700);
        }  
        closedir($handle);
    }
}

function image_resize($file_name, $width, $height, $crop=FALSE) {

	$thumb = new Imagick($file_name);

	$thumb->resizeImage(750,750,Imagick::FILTER_LANCZOS,1);
	$thumb->writeImage($file_name);

	$thumb->destroy(); 

  
}

resizeFile($Directory);

?>

