<?
function SqlCatalogModelCall($aData) {
	
	if ($aData['id_make']) 
	{
		$sWhere.=" and mod_mfa_id = ".$aData['id_make'];
	}
	else 
	{
		$sWhere.=" and 1=0";
	}
	
	if ($aData['id_model']) 
	{
		$sWhere.=" and ".DB_TOF."tof__models.mod_id = ".$aData['id_model'];
	}
		
	$sSql="select *
	from ".DB_TOF."tof__type_numbers   
    join ".DB_TOF."tof__types 
      on ".DB_TOF."tof__types.typ_id = ".DB_TOF."tof__type_numbers.tyn_typ_id 
    join ".DB_TOF."tof__models
      on typ_mod_id = mod_id
    left outer join ".DB_TOF."tof__countries
                 on cou_cc = 'f'
    where ".DB_TOF."tof__type_numbers.tyn_kind = 2  and ".DB_TOF."tof__type_numbers.tyn_search_text like 'mct5206l%' ;
    ".$sWhere;

	return $sSql;
}
?>