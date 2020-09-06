<?
/**
 * @author Mikhail Strovoyt
 * @version 4.5.2
 */

class AccessDenied extends Base
{
	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
	    Base::$sText.=Base::$tpl->fetch('access_denied/error.tpl');
	}
}
?>