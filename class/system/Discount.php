<?

/**
 * @author Mikhail Starovoyt
 * @version 4.5.3
 */

class Discount extends Base
{
	public static $aDiscount=array();

	//-----------------------------------------------------------------------------------------------
	public static function Refresh()
	{
		if (Base::GetConstant('payment:finance_module','finance')=='finance') {
			$sCheckTime=date("Y-m-d" ,time()-86400*Base::GetConstant("discount:dynamic_discount_period_day",30));

			$sQuery="select cp.id_user, sum(cp.price_total) as amount_sum
			from user u
			inner join cart_package as cp on u.id=cp.id_user
			where cp.price_total>0 and cp.order_status in ('work', 'end')
				and u.type_='customer'
				and cp.post_date > '".$sCheckTime."'
			group by cp.id_user";
			$aUser=Db::GetAll($sQuery);

			if ($aUser) foreach ($aUser as $aValue) {
				$sQuery="update user_customer set discount_dynamic='".Discount::GetDiscount($aValue['amount_sum'])."'
				where id_user='".$aValue['id_user']."'";
				Db::Execute($sQuery);
			}
		}
		else {
			$sDateFrom=date('Y-m-d',time()-86400*Base::GetConstant("discount:dynamic_discount_period_day",30));
			$sDateTo=date('Y-m-d');

			$sSql=Base::GetSql("Buh/Changeling",array(
			'date_from'=> $sDateFrom,
			'date_to'=> $sDateTo,
			'id_buh'=>'361',
			));
			$aUserAmountPeriod=Db::GetAll($sSql);

			if ($aUserAmountPeriod) foreach ($aUserAmountPeriod as $aValue) {
				$sQuery="update user_customer set discount_dynamic='".Discount::GetDiscount($aValue['amount_credit'])."'
				where id_user='".$aValue['id_buh_subconto1']."'";

				Db::Execute($sQuery);
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetDiscount($dAmount)
	{
		$dAmount=abs($dAmount);
		if (!Discount::$aDiscount) Discount::$aDiscount=Db::GetAll("select * from discount where visible=1 order by amount ");

		//$iDiscount=Discount::$aDiscount[0]['discount'];
		$iDiscount=0;
		foreach (Discount::$aDiscount as $value) {
			if ($value['amount']>$dAmount) break;
			$iDiscount=$value['discount'];
		}
		return $iDiscount;
	}
	//-----------------------------------------------------------------------------------------------
	public static function CustomerDiscount($aUser,$iProvider='')
	{
		if (!is_array($aUser) || $aUser['type_']=='manager') return 0;
		if ($aUser['price_type']=='margin') return 0;
        if($aUser['cg_visible']!=1)$aUser['group_discount']=0;
		if (Base::GetConstant('discount:type','sum')=='sum') {
			$iDiscount=$aUser['discount_static']+$aUser['discount_dynamic']+$aUser['group_discount'];
		} else {
			$iDiscount=max(array($aUser['discount_static'], $aUser['discount_dynamic'], $aUser['group_discount']));
		}


		if ($iDiscount>Base::GetConstant('price:discount_max','10')) $iDiscount=Base::GetConstant('price:discount_max','10');
		return $iDiscount;
	}
	//-----------------------------------------------------------------------------------------------
	public static function Index()
	{
		Base::$aData['template']['bWidthLimit']=true;
		$oTable=new Table();
		$oTable->sWidth='600';
		$oTable->sSql="select * from discount where visible=1  ";
		$oTable->aOrdered="order by amount";
		$oTable->aColumn=array(
		'name'=>array('sTitle'=>'Discount Amount','sWidth'=>'250px'),
		'description'=>array('sTitle'=>'Discount Percentage','sWidth'=>'350px'),
		);
		$oTable->sDataTemplate='discount/row_discount.tpl';
		$oTable->iRowPerPage=20;
		$oTable->bStepperVisible=false;

		Base::$sText.=$oTable->getTable("Dynamic Discounts");
	}
	//-----------------------------------------------------------------------------------------------
}
?>