<?

$filename = implode("_",array($_GET["pid"]? 1 : $_GET["clid"],$_GET["host"],$_GET["pid"],$_GET["shopid"]));

$TestMode = empty($_GET["TestCSS"]) ? "" : "Test/";

header ('Content-type: text/css; charset=UTF-8');
$fn = "/data/servers/_clientCss/{$_GET["clid"]}/" . $TestMode . $filename . ".css";

if (file_exists($fn)) 
	echo file_get_contents($fn);
else echo $fn;


?>