<?
/**
 * @author Mikhail Starovoyt
 * @author Oleksandr Starovoit
 * @author Alexander Belogura
 */

class Delivery extends Base
{
	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
	}
	//-----------------------------------------------------------------------------------------------
	public function Set()
	{
		if (Base::$aRequest['xajax_request']) {
			if (!Base::$aRequest['id_delivery_type']) return;

			$aDeliveryType=Db::GetRow(Base::GetSql('DeliveryType',array('id'=>Base::$aRequest['id_delivery_type'])));
			if (!$aDeliveryType) return;
			$_SESSION['current_cart']['id_delivery_type']=Base::$aRequest['id_delivery_type'];
			$_SESSION['current_cart']['price_delivery']=$aDeliveryType['price'];

			$sUserCartSql=Base::GetSql("Part/Search",array(
			"type_"=>'cart',
			"where"=> " and c.id_user='".Auth::$aUser['id']."'",
			));
			$aUserCart=Db::GetAll($sUserCartSql);
			Base::$tpl->assign('aUserCart',$aUserCart);
			if ($aUserCart) foreach ($aUserCart as $aValue) {
				$dSubtotal+=$aValue['number']*Currency::PrintPrice($aValue['price'],null,2,"<none>");
			}
			
			$dUserCurrencyDeliveryPrice = Currency::PrintPrice($aDeliveryType['price'],null,2,"<none>"); 
			$dPriceTotal=$dSubtotal + $dUserCurrencyDeliveryPrice;

			Base::$oResponse->AddAssign('price_delivery','innerHTML',
			Base::$oCurrency->PrintSymbol($dUserCurrencyDeliveryPrice));
			Base::$oResponse->AddAssign('price_total','innerHTML',
			Base::$oCurrency->PrintSymbol($dPriceTotal));
		}
	}
	//-----------------------------------------------------------------------------------------------
}
?>