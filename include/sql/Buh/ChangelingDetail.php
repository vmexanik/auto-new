<?
function SqlBuhChangelingDetailCall($aData){
	$sWhere.=$aData['where'];

	if ($aData['date_to'] && $aData['date_from']) {
		$sPeriodTo=substr($aData['date_to'],0,8)."01";
		$sPeriodFrom=substr($aData['date_from'],0,8)."01";
		$sDateTo=substr($aData['date_to'],0,10)." 59:59";
		$sDateFrom=substr($aData['date_from'],0,10)." 00:00";

		$sWhere1.=" and be.post_date>='".$sDateFrom."' and be.post_date<='".$sDateTo."'";
		$sWhere2.=" and be.post_date>='".$sDateFrom."' and be.post_date<='".$sDateTo."'";

	} else {
		return "select null";
	}
	
	if ($aData['id_buh'])
	{
		$sWhere1.=" and be.id_buh_debit=".$aData['id_buh'];
		$sWhere2.=" and be.id_buh_credit=".$aData['id_buh'];
	}

	if ($aData['id_subconto1']) {
		$sWhere1.=" and be.id_buh_debit_subconto1=".$aData['id_subconto1'];
		$sWhere2.=" and be.id_buh_credit_subconto1=".$aData['id_subconto1'];
	}
	
	$sSql="Select be.id_buh_debit as id_buh_debit, be.id_buh_credit as id_buh_credit
		, be.amount as amount_debit, '' as amount_credit
		, ".DateFormat::GetSqlDate("be.post_date")." as post_date
		, bs.code as document
		, be.buh_section_id as id_document
		, be.description
		, be.post_date as post_date_order
		, be.id
		from buh_entry as be
		left join buh_section as bs on bs.id=be.id_buh_section
		where 1=1
		".$sWhere1."
		union all
		Select be.id_buh_debit as id_buh_debit, be.id_buh_credit as id_buh_credit
		, '' as amount_debit, be.amount as amount_credit
		, ".DateFormat::GetSqlDate("be.post_date")." as post_date
		, bs.code as document
		, be.buh_section_id as id_document
		, be.description
		, be.post_date as post_date_order
		, be.id
		from buh_entry as be
		left join buh_section as bs on bs.id=be.id_buh_section
		where 1=1
		".$sWhere2
	;
	
	//Debug::PrintPre($sSql,false);
	
	return $sSql;	
}
?>