<?
/**
 * @author Irina Miroshnichenko
 */
function SqlAccessoryOilCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ao.id='{$aData['id']}'";
	}

	if (isset($aData['visible'])) {
		$sWhere.=" and ao.visible='{$aData['visible']}'";
	}

	if ($aData['join']) {
		$sJoin .= $sJoin;
	}

	if ($aData['show_price']) {
		$sField.=" , p.*";
		$sJoin.=" inner join price as p on p.item_code=ao.item_code ";
	}

	$sSql="select  c.*, ai.*, c.name as c_producer,
			insert(ao.item_code, 1, locate('_',ao.item_code), '') as ao_code
			".$sField."
			, ao.*
			from accessory_oil as ao
				left join accessory_image ai ON ai.code = ao.code_img
				inner join cat as c on c.id=ao.id_cat and c.visible=1
			".$sJoin."
			where 1=1 ".$sWhere;

	return $sSql;
}
?>