<?
/**
 * @author Irina Miroshnichenko
 */
function SqlAccessoryAccesCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and aa.id='{$aData['id']}'";
	}

	if (isset($aData['visible'])) {
		$sWhere.=" and aa.visible='{$aData['visible']}'";
	}

	if ($aData['join']) {
		$sJoin .= $sJoin;
	}

	if ($aData['show_price']) {
		$sField.=" , p.*";
		$sJoin.=" inner join price as p on p.item_code=aa.item_code ";
	}

	$sSql="select  aat.*,c.*, ai.*,
			aat.name as aat_name, c.name as c_producer,
			insert(aa.item_code, 1, locate('_',aa.item_code), '') as aa_code
			".$sField."
			, aa.*
			from accessory_acces as aa
			inner join accessory_acces_type as aat on aa.id_acces_type=aat.id
			left join accessory_image ai ON ai.code = aa.code_img
			inner join cat as c on c.id=aa.id_cat and c.visible=1
			".$sJoin."
			where 1=1 ".$sWhere;

	return $sSql;
}
?>