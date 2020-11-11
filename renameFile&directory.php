<?php

$Directory = '/var/www/html/jspractice/Hello+ Suraj/';



function reNameFile($Directory){
    $count = 0;
    if ($handle = opendir($Directory)) {
        $dir = $Directory;
        
        while (false !== ($fileName = readdir($handle))) {

                if ($fileName == '.' || $fileName == '..') {
                    continue;
                }  
                
                $name = $dir.$fileName;
        
                if(is_dir($name)) reNameFile($name."/");
                

            
                $newName = str_replace(" ","",$fileName);
                $newName2 = str_replace("+","_plus_",$newName);
                if (file_exists($dir.$newName2)) {
                     $count++;
                     $name = pathinfo($newName2, PATHINFO_FILENAME);
                     $ext  = pathinfo($newName2, PATHINFO_EXTENSION);
                     $newName2 = $name.$count.$ext;
                }
                rename($dir.$fileName, $dir.$newName2);
        }  
        closedir($handle);
    }
}

reNameFile($Directory);

?>