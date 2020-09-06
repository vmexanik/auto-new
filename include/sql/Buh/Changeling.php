<?
function SqlBuhChangelingCall($aData)
{

	$sWhere.=$aData['where'];

	if ($aData['date_to'] && $aData['date_from']) {
		$sPeriodTo=substr($aData['date_to'],0,8)."01";
		$sPeriodFrom=substr($aData['date_from'],0,8)."01";
		$sDateTo=substr($aData['date_to'],0,10)." 59:59";
		$sDateFrom=substr($aData['date_from'],0,10)." 00:00";

		$sWhere.=" and bem_end.date_month='".$sPeriodTo."'";

		//if ($sPeriodTo<>$sPeriodFrom) {
		$sJoin.=" left join buh_entry_month as bem_start
			on bem_start.date_month='".$sPeriodFrom."' 
			and bem_end.id_buh=bem_start.id_buh
			and bem_end.id_buh_subconto1=bem_start.id_buh_subconto1 ";
		//}

		$sWhere1.=" and be.post_date>='".$sPeriodFrom."' and be.post_date<='".$sDateTo."'";
		$sWhere2.=" and be.post_date>='".$sPeriodFrom."' and be.post_date<='".$sDateTo."'";

	} else {
		return "select null";
	}

	if ($aData['id_buh'])
	{
		$aBuh=Db::GetRow("select * from buh where id=".$aData['id_buh']);
		if ($aBuh['subconto1']) {
			if ($aBuh['subconto1']=='user') $sField.=" , t_sub1.login as name_subconto1";
			else $sField.=" , t_sub1.name as name_subconto1";
			
			$sJoin.=" join ".$aBuh['subconto1']." as t_sub1 on bem_end.id_buh_subconto1=t_sub1.id ";
		} else {
			$sField.=" , '' as name_subconto1";
		}
		$sWhere.=" and bem_end.id_buh=".$aData['id_buh'];
		$sWhere1.=" and be.id_buh_debit=".$aData['id_buh'];
		$sWhere2.=" and be.id_buh_credit=".$aData['id_buh'];
	}

	if ($aData['id_subconto1']) {

		$sWhere.=" and bem_end.id_buh_subconto1=".$aData['id_subconto1'];

		$sWhere1.=" and be.id_buh_debit_subconto1=".$aData['id_subconto1'];
		$sWhere2.=" and be.id_buh_credit_subconto1=".$aData['id_subconto1'];
	}

	$sSql="select bem_end.date_month, bem_end.id_buh, bem_end.id_buh_subconto1
		, if(ifnull(bem_start.amount_debit_start,0)-ifnull(bem_start.amount_credit_start,0)+ifnull(bed.amount_start,0)-ifnull(bec.amount_start,0)>0
		     , ifnull(bem_start.amount_debit_start,0)-ifnull(bem_start.amount_credit_start,0)+ifnull(bed.amount_start,0)-ifnull(bec.amount_start,0)
		     , 0) as amount_debit_start	
		, if(ifnull(bem_start.amount_credit_start,0)-ifnull(bem_start.amount_debit_start,0)+ifnull(bec.amount_start,0)-ifnull(bed.amount_start,0)>0
		     , ifnull(bem_start.amount_credit_start,0)-ifnull(bem_start.amount_debit_start,0)+ifnull(bec.amount_start,0)-ifnull(bed.amount_start,0)
		     , 0) as  amount_credit_start	
		, ifnull(bed.amount,0) as amount_debit
	 	, ifnull(bec.amount,0) as amount_credit
	 	, if(ifnull(bem_start.amount_debit_start,0)+ifnull(bed.amount_start,0)-ifnull(bec.amount_start,0)-ifnull(bem_start.amount_credit_start,0)+ifnull(bed.amount,0)-ifnull(bec.amount,0)>0
	 		, ifnull(bem_start.amount_debit_start,0)+ifnull(bed.amount_start,0)-ifnull(bec.amount_start,0)-ifnull(bem_start.amount_credit_start,0)+ifnull(bed.amount,0)-ifnull(bec.amount,0)
	 		, 0) as amount_debit_end
	 	, if(ifnull(bem_start.amount_credit_start,0)+ifnull(bec.amount_start,0)-ifnull(bed.amount_start,0)-ifnull(bem_start.amount_debit_start,0)+ifnull(bec.amount,0)-ifnull(bed.amount,0)>0
	 		, ifnull(bem_start.amount_credit_start,0)+ifnull(bec.amount_start,0)-ifnull(bed.amount_start,0)-ifnull(bem_start.amount_debit_start,0)+ifnull(bec.amount,0)-ifnull(bed.amount,0)
	 		, 0) as amount_credit_end
	 	".$sField."
	 	from buh_entry_month as bem_end 
	 	".$sJoin."
	 	left join 	
			(Select be.id_buh_debit as id_buh, be.id_buh_debit_subconto1 as id_subconto1
			, sum(if(be.post_date>='".$sDateFrom."', be.amount,0)) as amount
			, sum(if(be.post_date<'".$sDateFrom."', be.amount,0)) as amount_start
			from buh_entry as be 
			where 1=1
			".$sWhere1."
			group by be.id_buh_debit, be.id_buh_debit_subconto1
			) as bed on bem_end.id_buh=bed.id_buh and bem_end.id_buh_subconto1=bed.id_subconto1
		left join			
			(Select be.id_buh_credit as id_buh, be.id_buh_credit_subconto1 as id_subconto1
			, sum(if(be.post_date>='".$sDateFrom."', be.amount,0)) as amount
			, sum(if(be.post_date<'".$sDateFrom."', be.amount,0)) as amount_start
			from buh_entry as be 
			where 1=1
			".$sWhere2."
			group by be.id_buh_credit, be.id_buh_credit_subconto1
			) as bec on bem_end.id_buh=bec.id_buh and bem_end.id_buh_subconto1=bec.id_subconto1
		where 1=1
		".$sWhere
	;

	//Debug::PrintPre($sSql,false);

	return $sSql;
}
?>