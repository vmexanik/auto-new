<?
/**
 * 
 * @author vladimir_zheliba
 *
 */

class MultiLanguage extends Base
{
    public $sPrefix="multi_language";
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{	            

	}
	//-----------------------------------------------------------------------------------------------
	
	public static function CheckingLocale(){
	    if(!Base::$aRequest['locale']){
	        if (Base::$aRequest['action']=='home' && !array_key_exists('locale', Base::$aRequest)){
	            Base::$aRequest['locale'] = '';
	        }
	        if (!array_key_exists('locale', Base::$aRequest) && $_COOKIE['current_locale']){
	            Base::$aRequest['locale'] = $_COOKIE['current_locale'];
	        }else{
	            Base::$aRequest['locale'] = Language::$sBaseLocale;
	        }
	    }
	}
	//-----------------------------------------------------------------------------------------------
	
	public static function ChangeCurrentLocale(){
	    if($_COOKIE['current_locale'] != Language::$sLocale){
    	    setcookie('current_locale',Language::$sLocale, strtotime( '+30 days' ),"/" );
            $_COOKIE['current_locale']=Language::$sLocale;
	    }
	}
	//-----------------------------------------------------------------------------------------------
	
	public static function IsLocale(){
	    return isset(Language::$sLocale) && Language::$sLocale!=Language::$sBaseLocale;
	}
	//-----------------------------------------------------------------------------------------------
	
	public static function GetMultiLanguageConstant(){
	    if(self::IsLocale() && Base::$aConstant){
    	    foreach (Base::$aConstant as $sConst=>$aValueConst){
    	        $sConstlocal=$sConst."_".Language::$sLocale;
    	        if(array_key_exists($sConstlocal, Base::$aConstant)){
    	            Base::$aConstant[$sConst]['value']=Base::$aConstant[$sConstlocal]['value'];
    	        }
    	    }
	    }
	}
	//-----------------------------------------------------------------------------------------------
	
	public static function SetAlternateUrl()
	{
	    $sAlternateUrl="/".ltrim($_SERVER['REQUEST_URI'],"/ua");
	    $sAlternateUrlUa="/ua".$sAlternateUrl;
	    Content::CheckSeoUrl($sAlternateUrl);
	    Content::CheckSeoUrl($sAlternateUrlUa);
	    if($sAlternateUrl) Base::$tpl->assign('sMultiUrl',$sAlternateUrl);
	    else Base::$tpl->assign('sMultiUrl',"/");
	    Base::$tpl->assign('sMultiUrlUa',$sAlternateUrlUa);
	    $sAlternateUrl=Base::GetConstant('global:project_url').$sAlternateUrl;
	    $sAlternateUrlUa=Base::GetConstant('global:project_url').$sAlternateUrlUa;
	    Base::$tpl->assign('sAlternateUrl',$sAlternateUrl);
	    Base::$tpl->assign('sAlternateUrlUa',$sAlternateUrlUa);
	}
	//-----------------------------------------------------------------------------------------------
	
	public static function Redirect($sUrl)
	{
	    if(MultiLanguage::IsLocale()){
	        Base::Redirect('/'.Language::$sLocale.$sUrl);
	    }
	    Base::Redirect($sUrl);
	}
	//-----------------------------------------------------------------------------------------------
	
	/**
	 * Rubricator All, Assoc
	 * @param string $sWhere
	 * @param string $sOrder
	 * @param string $bAssoc
	 * @return multitype:
	 */
	public static function GetLocalizedRubricator($aData, $sOrder='', $bAssoc=false) {
	    $aRubric=array();
	    if (self::IsLocale()) {
	        self::SetDataRubricator($aData, $sOrder);
	        if($bAssoc){
	            $aRubric=Base::$language->GetLocalizedAll($aData, false, " t.id as key_, ");
	        }else{
	            $aRubric=Base::$language->GetLocalizedAll($aData, false);
	        }
	    }else{
	        if($bAssoc){
	            $aRubric=Db::GetAssoc(str_replace("select","select r.id as key_, ",Base::GetSql("Rubricator",$aData)." ".$sOrder));
	        }else{
	            $aRubric=Db::GetAll(Base::GetSql("Rubricator",$aData)." ".$sOrder);
	        }
	    }
	    return $aRubric;
	}
	//-----------------------------------------------------------------------------------------------
	
	/**
	 * Rubricator All, Assoc Row
	 * @param string $sWhere
	 * @return multitype:
	 */
	public static function GetLocalizedRubricatorRow($aData) {
	    $aCategory=array();
	    if (self::IsLocale()) {
	        self::SetDataRubricator($aData);
	        if($aData['where']) $aData['where']=str_replace("t.", " ", $aData['where']);
	        $aCategory=Base::$language->GetLocalizedRow($aData);
	    }else{
	        $aCategory=Db::GetRow(Base::GetSql("Rubricator",$aData));
	    }
	    return $aCategory;
	}
	//-----------------------------------------------------------------------------------------------

	private static function SetDataRubricator(&$aData, $sOrder='') {
        $sWhere='';
        Db::SetWhere($sWhere, $aData, 'id', 't');
        Db::SetWhere($sWhere, $aData, 'visible', 't');
        Db::SetWhere($sWhere, $aData, 'is_mainpage', 't');
        Db::SetWhere($sWhere, $aData, 'is_menu_visible', 't');
        if($aData['where']) $sWhere.=str_replace("r.", "t.", $aData['where']);
        $aData=array(
            'table'=>'rubricator',
            'where'=>$sWhere,
            'order'=>$sOrder
        );
	}
	//-----------------------------------------------------------------------------------------------
	public static function AddUrlLocaleForOutput(&$sOutput)
	{
	    if(self::IsLocale()){
	        $sOutput=str_ireplace('href="/select', 'href="/'.Language::$sLocale.'/select', $sOutput);
	        $sOutput=str_ireplace("href='/select", "href='/".Language::$sLocale."/select", $sOutput);
	
	        $sOutput=str_ireplace('href="/pages', 'href="/'.Language::$sLocale.'/pages', $sOutput);
	        $sOutput=str_ireplace("href='/pages", "href='/".Language::$sLocale."/pages", $sOutput);
	        $sOutput=str_ireplace('href="/model_for', 'href="/'.Language::$sLocale.'/model_for', $sOutput);
	        $sOutput=str_ireplace("href='/model_for", "href='/".Language::$sLocale."/model_for", $sOutput);
	        $sOutput=str_ireplace('href="/price', 'href="/'.Language::$sLocale.'/price', $sOutput);
	        $sOutput=str_ireplace("href='/price", "href='/".Language::$sLocale."/price", $sOutput);
	        $sOutput=str_ireplace('href="/buy', 'href="/'.Language::$sLocale.'/buy', $sOutput);
	        $sOutput=str_ireplace("href='/buy", "href='/".Language::$sLocale."/buy", $sOutput);
	        $sOutput=str_ireplace('href="/search_text', 'href="/'.Language::$sLocale.'/search_text', $sOutput);
	        $sOutput=str_ireplace("href='/search_text", "href='/".Language::$sLocale."/search_text", $sOutput);
	        $sOutput=str_ireplace('href="/cars', 'href="/'.Language::$sLocale.'/cars', $sOutput);
	        $sOutput=str_ireplace("href='/cars", "href='/".Language::$sLocale."/cars", $sOutput);
	        $sOutput=str_ireplace('href="/original_cross', 'href="/'.Language::$sLocale.'/original_cross', $sOutput);
	        $sOutput=str_ireplace("href='/original_cross", "href='/".Language::$sLocale."/original_cross", $sOutput);
	        $sOutput=str_ireplace('href="/rubricator', 'href="/'.Language::$sLocale.'/rubricator', $sOutput);
	        $sOutput=str_ireplace("href='/rubricator", "href='/".Language::$sLocale."/rubricator", $sOutput);
	        $sOutput=str_ireplace('href="/"', 'href="/'.Language::$sLocale.'"', $sOutput);
	        $sOutput=str_ireplace("href='/'", "href='/".Language::$sLocale."'", $sOutput);
	        $sOutput=str_ireplace('href="/?', 'href="/'.Language::$sLocale.'/?', $sOutput);
	        $sOutput=str_ireplace("href='/?", "href='/".Language::$sLocale."/?", $sOutput);
	        
	        $sOutput=str_ireplace("document.location='/price/'", "document.location='/".Language::$sLocale."/price/'", $sOutput);
	         
	        if(Base::$tpl->_tpl_vars['sMultiUrl']=="/"){
	            $sOutput=str_ireplace('id="multilanguage_option" value="/'.Language::$sLocale.'', 'id="multilanguage_option" value="/', $sOutput);
	        }else{
	            $sOutput=str_ireplace('id="multilanguage_option" value="/'.Language::$sLocale.'', 'id="multilanguage_option" value="', $sOutput);
	        }
	    }
	}
	//-----------------------------------------------------------------------------------------------
	
	/**
	 * Настройка выдачи урлов
	 */
	public static function GetLocalizedDropDownAdditional(&$aDropDownAdditional)
	{
	    if ($aDropDownAdditional && Language::$sLocale != Language::$sBaseLocale){
	        $sQuery = "select t.*,l.*
				from drop_down_additional t
				inner join locale_global l on (t.id=l.id_reference and l.table_name='drop_down_additional'
					and l.locale='" . Language::$sLocale . "')
				where	1=1 and ('".$_SERVER['REQUEST_URI']."' like t.url)";
	        $aDropDownAdditionalLocale=Db::GetRow($sQuery);
	        if($aDropDownAdditionalLocale)
	            foreach ($aDropDownAdditionalLocale as $sKey=>$sValue){
	            if($sKey=='id'){
	                if($aDropDownAdditional[$sKey]!=$sValue) break;
	                continue ;
	            }
	            if(isset($sValue) && $sValue) $aDropDownAdditional[$sKey]=$sValue;
	        }
	    }
	}
	//-----------------------------------------------------------------------------------------------
}
?>