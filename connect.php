<?
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(0);

//-----------------------------------------------------------------------------------------------------
if ($_SERVER["DOCUMENT_ROOT"]) $_SERVER_NAME=explode($_SERVER["DOCUMENT_ROOT"], str_replace("\\","/",dirname(__FILE__)));
$_SERVER_NAME=$_SERVER['SERVER_NAME'].$_SERVER_NAME[1];
$tmp_server_path=explode(":",dirname(__FILE__));
count($tmp_server_path)==1 ? $_SERVER_PATH=$tmp_server_path[0] : $_SERVER_PATH=str_replace("\\","/",$tmp_server_path[1]);
define(SERVER_NAME,$_SERVER_NAME);
define(SERVER_PATH,$_SERVER_PATH);

if(strpos(get_include_path(),";")=== false){
    set_include_path(SERVER_PATH."/lib/".":".SERVER_PATH."/lib/PHPExcel/".":".get_include_path());
} else {
    set_include_path(SERVER_PATH."/lib/".";".SERVER_PATH."/lib/PHPExcel/".";".get_include_path());
}
//-----------------------------------------------------------------------------------------------------
define(IN_PHPBB,true);
define('PHPBB_ROOT_PATH', '../libp/phpbb/');
$sForumUrl = '/libp/phpBB/';
include_once(SERVER_PATH.$sForumUrl.'config.php');
include_once(SERVER_PATH.$sForumUrl.'includes/constants.php');
//-----------------------------------------------------------------------------------------------------
$DB_CONF = array(
    'User' => 'mysql',
    'Password' => 'mysql',
    'Database' => 'auto',
    'Host' => '127.0.0.1',
    'Port' => '3306',
    'Type' => 'mysqlt',
    'Charset'=>'utf8',
    'Modules'=> 'transaction : pear'
);
/*database tecdoc*/
define(DB_TOF, 'tecdoc_1603.');
define(DB_OCAT, 'opti_1603.');
define(REMOTE_TECDOC, 'opti_1603.');
//define(OPTIMIZE_CSS, true);
//define(OPTIMIZE_JS, true);
//-----------------------------------------------------------------------------------------------------
$GENERAL_CONF = array(
    'MpanelVersion' => '5.0',
    'ProjectName' => 'Auto.Mstarproject',
    'Charset' => 'utf-8',
    'IsLive' => true,
    'BaseLocale' => 'ru',
    'LogAdmin' => true,
    'CookieDomain' => 'auto.lc',
    'TecDocUrl' => 'http://tecdoc2016-3.mstarproject.com',
    'AdminRegulationsUrl' => 'http://irbis.mstarproject.com', // not delete becouse not work sinhronize translate from other projects! - http://irbis.mstarproject.com/pages/admin_regulations_sinxro_translate/
    'SQLLog_' => $_SERVER['DOCUMENT_ROOT'].'/log_sql.csv', //rename the parameter SQLLog_ to SQLLog and configure the path
    'SQLLogFilter' => '|.*|siu'
);
//-----------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------
//--for backup.sh-return user or passwd
if ($argv[1]=='_for_cron_username') {
    echo $DB_CONF['User'];
} elseif ($argv[1]=='_for_cron_password') {
    echo $DB_CONF['Password'];
} elseif ($argv[1]=='_for_cron_database') {
    echo $DB_CONF['Database'];
}

//-----------------------------------------------------------------------------------------------------

?>