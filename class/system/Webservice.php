<?
/**
 * @author Yuriy Korzun
 */

class Webservice extends Base
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
	}
	//-----------------------------------------------------------------------------------------------
	public function GetPricePartmaster($sCode,$sPref='')
	{
		if (!Base::GetConstant("webservice:partmaster",0)) return;
		$bNewData=Db::GetOne("select count(*) from service_log where service_name='partmaster' and code='".$sCode."'
		and (current_timestamp-post_date)/100<".Base::GetConstant("webservice:partmaster_interval_minute",120)." ");
		if($bNewData) return;

		ini_set("soap.wsdl_cache_enabled", "0"); // отключаем кэширование WSDL
		$client = new SoapClient("http://partmaster.com.ua/service.wsdl");
		$client->Auth(Base::GetConstant("webservice:partmaster_login",'auto_soap'),
				Base::GetConstant("webservice:partmaster_password",'GfhnvfcnthAT'));
		if($sPref){
			$aCat=Base::$db->GetRow(Base::GetSql('Cat',array('pref'=>$sPref)));
			$sCatName=$aCat['title'];
		}
		else
		$sCatName='';
		$oPrice=$client->GetPrice($sCode,$sCatName);
		Db::Execute("insert into service_log (service_name,code) VALUES ('partmaster','".$sCode."')
		on duplicate key update post_date=current_timestamp");

		$sCurrency='USD';
		foreach (Base::$oCurrency->aCurrencyAssoc as $aValue) {
			if($aValue['code']==$sCurrency) $dKurs=$aValue['reciprocal'];
		}
		if(!$dKurs) $dKurs=1;

		if($oPrice && $oPrice->GetPriceResult)
		foreach ($oPrice->GetPriceResult as $oValue) {
			$aCatPref=Base::$db->GetRow(Base::GetSql('CatPref',array('name'=>$oValue->brand)));
			if($aCatPref)
			$aCat=Base::$db->GetRow(Base::GetSql('Cat',array('pref'=>$aCatPref['pref'])));
			if($aCat && $oValue->code && $oValue->item_code)
			Base::$db->Execute(" insert into price
			(item_code, id_provider, code, cat, 
			part_rus, part_eng, price, pref, term, stock)
			VALUES
			('".$aCat['pref']."_".$oValue->code."', 851, '".$oValue->code."', '".$aCat['title']."',
			'".$oValue->name_ru."', '".$oValue->name."', '".($oValue->price * $dKurs)."', '".$aCat['pref']."','0','1')
			on duplicate key update price=values(price), part_rus=values(part_rus), part_eng=values(part_eng) "
			);
		}

	}

}
?>