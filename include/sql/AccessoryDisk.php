<?
/**
 * @author Irina Miroshnichenko
 */
function SqlAccessoryDiskCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ad.id='{$aData['id']}'";
	}

	if (isset($aData['visible'])) {
		$sWhere.=" and ad.visible='{$aData['visible']}'";
	}

	if ($aData['join']) {
		$sJoin .= $sJoin;
	}

	if ($aData['show_price']) {
		$sField.=" , p.*";
		$sJoin.=" inner join price as p on p.item_code=ad.item_code ";
	}

	$sSql="select  c.*, ai.*, c.name as c_producer,
			insert(ad.item_code, 1, locate('_',ad.item_code), '') as ad_code
			".$sField."
			, ad.*
			from accessory_disk as ad
			left join accessory_image ai ON ai.code = ad.code_img
			inner join cat as c on c.id=ad.id_cat and c.visible=1
			".$sJoin."
			where 1=1 ".$sWhere;

	return $sSql;
}
?>