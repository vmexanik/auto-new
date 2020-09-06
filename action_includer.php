<?

//----------------------------------------------------------
$action_array=array();
$directory=SERVER_PATH."/spec/";
if ($dh = opendir($directory)) {
	while (($file = readdir($dh)) !== false) {
		if ($file != "." && $file != ".." && strpos($file,'.php')!==false) {
			if (filetype($directory . $file)=="file") {
				$file_name_array=preg_split("/\.php/",$file);
				$file_name=$file_name_array[0];
				if (!in_array($file,$action_array)) $action_array[$file_name]=$file;
			}
		}
	}
	closedir($dh);
}
//----------------------------------------------------------
krsort($action_array, SORT_STRING);

$curract = Base::$aRequest['action'];
foreach ($action_array as $action_key => $action_value)
{
	$action_parts = explode('*', $action_key);
	$hasAll = true;
	$f = true;
	foreach ($action_parts as $action_part)
	{
		if (strlen(trim($action_part)) > 0)
		{
			$spos = strpos($curract,$action_part);
			if ($spos === false || (($spos > 0) && ($f == true)))
			{
				$hasAll = false;
			}
			$f = false;
		}
	}
	if ($hasAll == true)
	{
		include_once('spec/'.$action_value);
		break;
	}
}
//----------------------------------------------------------
?>