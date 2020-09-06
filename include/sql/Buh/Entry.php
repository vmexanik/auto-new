<?
function SqlBuhEntryCall($aData)
{
	if ($aData['id']) 
	{
		$sWhere.=" and be.id='".$aData['id']."'";
	}

	$sSql="select be.*
		, b1.subconto1 as debit_subconto, b2.subconto1 as credit_subconto
		from buh_entry as be
		inner join buh as b1 on b1.id=be.id_buh_debit
		inner join buh as b2 on b2.id=be.id_buh_credit
			".$sJoin."
			where 1=1
			".$sWhere;

	return $sSql;	
}
?>