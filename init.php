<?
if(strpos($_SERVER['REQUEST_URI'],'//') !== false)
    header("Location: ".str_replace('//','/',$_SERVER['REQUEST_URI']));

// - Smarty -
require_once( SERVER_PATH.'/lib/smarty/Smarty.class.php');
$oSmarty = new Smarty();
$oSmarty->template_dir=SERVER_PATH.'/template/';
$oSmarty->compile_dir=SERVER_PATH.'/template/templates_c/';
$oSmarty->config_dir=SERVER_PATH.'/templates_c/configs/';
$oSmarty->cache_dir=SERVER_PATH.'/templates_c/cache/';
$oSmarty->compile_check = true;
//$smarty->debugging = false;

// - AdoDB -
include_once('lib/adodb/adodb.inc.php');
$oDb=&ADONewConnection($DB_CONF['Type'], $DB_CONF['Modules']);
$oDb->Connect($DB_CONF['Host'].':'.$DB_CONF['Port'],$DB_CONF['User'],$DB_CONF['Password'],$DB_CONF['Database']);
$oDb->_Execute("/*!40101 SET NAMES '".$DB_CONF['Charset']."' */");
$oDb->SetFetchMode(ADODB_FETCH_ASSOC);
//$oDb->nameQuote="`";
//$ADODB_QUOTE_FIELDNAMES=true;
//correct time_zone
date_default_timezone_set('Europe/Kiev');
$oDb->_Execute("SET `time_zone`='".date('P')."'");

if (!$GENERAL_CONF['IsLive']) {
	/* for log sql*/
	//$oDb->Execute("TRUNCATE TABLE `adodb_logsql`");
	//$oDb->LogSQL();
	//$ADODB_PERF_MIN=0.01;

	//$oDb->debug=true;
	include_once('lib/adodb/adodb-errorhandler.inc.php');
	$oDb->raiseErrorFn = "ADODB_Error_Handler";

	//for production site
	//define('ADODB_ERROR_LOG_TYPE',3);
	//define('ADODB_ERROR_LOG_DEST',SERVER_PATH.'/log/db_error.log');
}

// for autoload with operator new
function SystemAutoload($sClass) {
	if (is_file(SERVER_PATH.'/class/system/'.$sClass.'.php')) require_once(SERVER_PATH.'/class/system/'.$sClass.'.php');
	elseif (is_file(SERVER_PATH.'/class/module/'.$sClass.'.php')) require_once(SERVER_PATH.'/class/module/'.$sClass.'.php');
	elseif (is_file(SERVER_PATH.'/class/core/'.$sClass.'.php')) require_once(SERVER_PATH.'/class/core/'.$sClass.'.php');
}
spl_autoload_register('SystemAutoload');

require_once( SERVER_PATH.'/class/core/Base.php');
require_once( SERVER_PATH.'/class/system/Content.php');
require_once( SERVER_PATH.'/class/system/Language.php');
require_once( SERVER_PATH.'/class/system/ManagerPanel.php');

$oTecdocDb=&ADONewConnection($DB_CONF['Type'], $DB_CONF['Modules']);
$oTecdocDb->Connect('95.217.86.252:3306','root_remote_2001','xk43ELsj6IgqQW9r');
$oTecdocDb->_Execute("/*!40101 SET NAMES '".$DB_CONF['Charset']."' */");
$oTecdocDb->SetFetchMode(ADODB_FETCH_ASSOC);

Base::$db = $oDb;
Base::$oTecdocDb = $oTecdocDb;
Base::$tpl = $oSmarty;
Base::$aGeneralConf = $GENERAL_CONF;
Base::$aDbConf = $DB_CONF;

/**
 * This file is part of the array_column library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey (http://benramsey.com)
 * @license http://opensource.org/licenses/MIT MIT
 */

if (!function_exists('array_column')) {
	/**
	 * Returns the values from a single column of the input array, identified by
	 * the $columnKey.
	 *
	 * Optionally, you may provide an $indexKey to index the values in the returned
	 * array by the values from the $indexKey column in the input array.
	 *
	 * @param array $input A multi-dimensional array (record set) from which to pull
	 *                     a column of values.
	 * @param mixed $columnKey The column of values to return. This value may be the
	 *                         integer key of the column you wish to retrieve, or it
	 *                         may be the string key name for an associative array.
	 * @param mixed $indexKey (Optional.) The column to use as the index/keys for
	 *                        the returned array. This value may be the integer key
	 *                        of the column, or it may be the string key name.
	 * @return array
	 */
	function array_column($input = null, $columnKey = null, $indexKey = null)
	{
		// Using func_get_args() in order to check for proper number of
		// parameters and trigger errors exactly as the built-in array_column()
		// does in PHP 5.5.
		$argc = func_num_args();
		$params = func_get_args();

		if ($argc < 2) {
			trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
			return null;
		}

		if (!is_array($params[0])) {
			trigger_error(
				'array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given',
				E_USER_WARNING
			);
			return null;
		}

		if (!is_int($params[1])
			&& !is_float($params[1])
			&& !is_string($params[1])
			&& $params[1] !== null
			&& !(is_object($params[1]) && method_exists($params[1], '__toString'))
		) {
			trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
			return false;
		}

		if (isset($params[2])
			&& !is_int($params[2])
			&& !is_float($params[2])
			&& !is_string($params[2])
			&& !(is_object($params[2]) && method_exists($params[2], '__toString'))
		) {
			trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
			return false;
		}

		$paramsInput = $params[0];
		$paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;

		$paramsIndexKey = null;
		if (isset($params[2])) {
			if (is_float($params[2]) || is_int($params[2])) {
				$paramsIndexKey = (int) $params[2];
			} else {
				$paramsIndexKey = (string) $params[2];
			}
		}

		$resultArray = array();

		foreach ($paramsInput as $row) {
			$key = $value = null;
			$keySet = $valueSet = false;

			if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
				$keySet = true;
				$key = (string) $row[$paramsIndexKey];
			}

			if ($paramsColumnKey === null) {
				$valueSet = true;
				$value = $row;
			} elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
				$valueSet = true;
				$value = $row[$paramsColumnKey];
			}

			if ($valueSet) {
				if ($keySet) {
					$resultArray[$key] = $value;
				} else {
					$resultArray[] = $value;
				}
			}

		}

		return $resultArray;
	}

}

Base::PreInit();
setlocale(LC_TIME, Base::$aGeneralConf['BaseLocale']);

if ($bIsMpanalRequest) {
	$sCodeMpanelLanguage = Db::GetOne("select content from admin_option where
			id_admin='".$_SESSION['admin']['id']."' and module='language' and code='id_mpanel_language'");
	if (!$sCodeMpanelLanguage) $sCodeMpanelLanguage='ru';
	else Base::$aRequest ['locale']=$sCodeMpanelLanguage;
	Base::$tpl->assign('sCodeMpanelLanguage',$sCodeMpanelLanguage);
}

$oLanguage=new Language(Base::$aGeneralConf['BaseLocale']);
$oSmarty->assign_by_ref("oLanguage", $oLanguage);
Base::$language = $oLanguage;

Base::Init();
?>
