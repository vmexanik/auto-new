<?
//###########################################################
// Action Array
//###########################################################
$action_array = array(

);
//###########################################################
$sDirectory= SERVER_PATH.'/manager_panel/spec/';
if ($dh = opendir($sDirectory)) {
	while (($file = readdir($dh)) !== false) {
		if ($file != "." && $file != ".." && strpos($file,'.php')!==false) {
			if (filetype($sDirectory.$file)=="file") {
				$file_name_array=preg_split("/\.php/",$file);
				$file_name=$file_name_array[0];
				if (!in_array($file,$action_array)
				&& (strpos($file_name,'_standart_')===false)
				) {
					$action_array[$file_name]=$file;
				}
			}
		}
	}
	closedir($dh);
}

//###########################################################
//###########################################################
