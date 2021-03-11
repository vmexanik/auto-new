<?
/**
 * @author Aleksandr Starovoit
 * @author Mikhail Starovoit
 */
class TecdocDb extends Base
{
	/**
	 * Execute SQL
	 *
	 * @param sql		SQL statement to execute, or possibly an array holding prepared statement ($sql[0] will hold sql text)
	 * @param [inputarr]	holds the input data to bind to. Null elements will be set to null.
	 * @return 		RecordSet or false
	 */
	public function Execute($sSql,$aInput=false)
	{
	    if(Base::$oTecdocDb->debug) {
	        $start_time = microtime();
	    
	        $aResult=Base::$oTecdocDb->Execute("/* ".Base::GetConstant('global:project_name')." */".$sSql,$aInput);
	    
	        $end_time = microtime();
	        $elapsed_time = $end_time - $start_time;
	        $trace = debug_backtrace();
	        Debug::PrintPre("Execution time: ".$elapsed_time." File: ". $trace[0]['file']." on line: ".$trace[0]['line'],false);
	    
	        return $aResult;
	    } else {
	        return Base::$oTecdocDb->Execute("/* ".Base::GetConstant('global:project_name')." */".$sSql,$aInput);
	    }
		
	}
	//--------------------------------------------------------------------------------------------------

	/**
	* Execute SQL and get result array ([0]=>array(field=>value, ...),[1]=>array(....))
	*
	* @param sql
	* @return array ([0]=>array(field=>value, ...),[1]=>array(....))
	*/
	public function GetAll($sSql)
	{
        if(Base::$oTecdocDb->debug) {
            $start_time = microtime();
        
            $aResult=Base::$oTecdocDb->GetAll("/* ".Base::GetConstant('global:project_name')." */".$sSql);
        
            $end_time = microtime();
            $elapsed_time = $end_time - $start_time;
        
            $trace = debug_backtrace();
            Debug::PrintPre("Execution time TecDocDb::GetAll: ".$elapsed_time." File: ". $trace[0]['file']." on line: ".$trace[0]['line'],false);
        } else {
            $aResult=Base::$oTecdocDb->GetAll("/* ".Base::GetConstant('global:project_name')." */".$sSql);
        }
	    return $aResult;
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * Execute SQL and get result array(id1=>array(f1,f2 ...),id2=>array(f1,f2 ...))
	 *
	 * @param string $sSql or Assoc/Name
	 * @param array $aData for Base::GetSql
	 * @return array (id1=>array(f1,f2 ...),id2=>array(f1,f2 ...))
	 */
	public function GetAssoc($sSql, $aData=array(), $bReturnSql=false)
	{
		if ("Assoc/"==substr($sSql,0,6)) $sSql=Base::GetSql($sSql,$aData);
		return $bReturnSql?$sSql:Base::$oTecdocDb->GetAssoc($sSql);
	}
	//--------------------------------------------------------------------------------------------------

	/**
	* Execute SQL and get Row
	*
	* @param sql
	* @return array (fild=>value, fild2=>value2 ...)
	*/
	public function GetRow($sSql)
	{
        if(Base::$oTecdocDb->debug) {
            $start_time = microtime();
        
            $aResult=Base::$oTecdocDb->GetRow("/* ".Base::GetConstant('global:project_name')." */".$sSql);
        
            $end_time = microtime();
            $elapsed_time = $end_time - $start_time;
            $trace = debug_backtrace();
            Debug::PrintPre("Execution time TecDocDb::GetRow: ".$elapsed_time." File: ". $trace[0]['file']." on line: ".$trace[0]['line'],false);
        } else {
            $aResult=Base::$oTecdocDb->GetRow("/* ".Base::GetConstant('global:project_name')." */".$sSql);
        }
	    return $aResult;
	}
	//--------------------------------------------------------------------------------------------------

	/**
	* Execute SQL and one item
	*
	* @param sql
	* @return string item
	*/
	public function GetOne($sSql)
	{
		if(Base::$oTecdocDb->debug) {
	        $start_time = microtime();
	    
	        $aResult=Base::$oTecdocDb->GetOne("/* ".Base::GetConstant('global:project_name')." */".$sSql);
	    
	        $end_time = microtime();
	        $elapsed_time = $end_time - $start_time;
	        $trace = debug_backtrace();
    	    Debug::PrintPre("Execution time TecDocDb::GetOne: ".$elapsed_time." File: ". $trace[0]['file']." on line: ".$trace[0]['line'],false);
	    } else {
	        $aResult=Base::$oTecdocDb->GetOne("/* ".Base::GetConstant('global:project_name')." */".$sSql);
	    }
	    return $aResult;
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 *
	 * Similar to PEAR DB's autoExecute(), except that
	 * $mode can be 'INSERT' or 'UPDATE' or DB_AUTOQUERY_INSERT or DB_AUTOQUERY_UPDATE
	 * If $mode == 'UPDATE', then $where is compulsory as a safety measure.
	 *
	 * $forceUpdate means that even if the data has not changed, perform update.
	 */
	public function AutoExecute($sTable, $aFieldValue, $sMode = 'INSERT', $sWhere = FALSE, $bForceUpdate=true, $bMagicQuote=false)
	{
		if (Base::GetConstant('db:is_table_logged','0')) {
			$aTableArray=preg_split("/[\s,;]+/", Base::GetConstant('db:table_logged_array'));
			if (in_array($sTable, $aTableArray)) {
				$aLogTable=array(
				'table_name'=>$sTable,
				'mode_name'=>$sMode,
				'description'=>print_r($aFieldValue,true),
				'where_name'=>$sWhere,
				);
				Base::$oTecdocDb->AutoExecute('log_table', $aLogTable);
			}
		}
		return Base::$oTecdocDb->AutoExecute($sTable, $aFieldValue, $sMode, $sWhere, $bForceUpdate, $bMagicQuote);
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * Show debug sql
	 */
	public function Debug()
	{
		Base::$oTecdocDb->debug=true;
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * Write Log Sql to table adodb_logsql
	 */
	public function LogSql($bEnable=true)
	{
		Base::$oTecdocDb->LogSQL($bEnable);
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * Last insert ID
	 *
	 * @return integer ID
	 */
	public function InsertId()
	{
		return Base::$oTecdocDb->Insert_ID();
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * Number of row
	 *
	 * @return integer Col
	 */
	public function AffectedRow()
	{
		return Base::$oTecdocDb->Affected_Rows();
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * Start transaction
	 *
	 */
	public function StartTrans()
	{
		Base::$oTecdocDb->StartTrans();
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * Fail transaction
	 *
	 */
	public function FailTrans()
	{
		Base::$oTecdocDb->FailTrans();
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * Complete transaction
	 *
	 */
	public function CompleteTrans()
	{
		return Base::$oTecdocDb->CompleteTrans();
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * escape array function mysql_escape_string
	 *
	 * @param array $aData
	 * @return array
	 */
	public function Escape($aData)
	{
		if ($aData) {
			foreach ($aData as $sKey => $aValue) {
				$aDataNew[$sKey]=mysql_escape_string($aValue);
			}
			return $aDataNew;
		} else return false;
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * Get Insert Sql
	 *
	 * @param object $oSql
	 * @param array $aField
	 * @param boolen $bMagicq
	 * @param string $force
	 * @return string
	 */
	public function GetInsertSql($oSql, $aField, $bMagicq=true, $sForce=null)
	{
		return Base::$oTecdocDb->GetInsertSQL($oSql, $aField, $bMagicq, $sForce);
	}
	//--------------------------------------------------------------------------------------------------

	/**
	* Will select, getting rows from $offset (1-based), for $nrows.
	* This simulates the MySQL "select * from table limit $offset,$nrows" , and
	* the PostgreSQL "select * from table limit $nrows offset $offset". Note that
	* MySQL and PostgreSQL parameter ordering is the opposite of the other.
	* eg.
	*  SelectLimit('select * from table',3); will return rows 1 to 3 (1-based)
	*  SelectLimit('select * from table',3,2); will return rows 3 to 5 (1-based)
	*
	* Uses SELECT TOP for Microsoft databases (when $this->hasTop is set)
	* BUG: Currently SelectLimit fails with $sql with LIMIT or TOP clause already set
	*
	* @param sSql
	* @param iRow [nrows]		is the number of rows to get
	* @param iStart [offset]	is the row to start calculations from (1-based)
	* @param [inputarr]	array of bind variables
	* @param [secs2cache]		is a private parameter only used by jlim
	* @return object the recordset ($rs->databaseType == 'array')
 	*/
	public function SelectLimit($sSql, $iRow=-1, $iStart=-1, $inputarr=false,$secs2cache=0)
	{
		return Base::$oTecdocDb->SelectLimit($sSql, $iRow, $iStart, $inputarr, $secs2cache);
	}
	//--------------------------------------------------------------------------------------------------
	/**
	 * Return aditional info about table of current database
	 *
	 * @param string $sType
	 * @return array
	 */
	public function GetTableInfo($sType='')
	{
		$aRow= Db::GetAll("SHOW TABLE STATUS");
		foreach ($aRow as $sKey => $aValue) {
			switch ($sType) {
				case 'name':
					$aRowReturn[0].=' '.$aValue['Name'];
					$aRowReturn[]=$aValue['Name'];
					break;

				case 'dump':
					if ($sOldName && substr($aValue['Name'],0,3)!=substr($sOldName,0,3)) {
						$aRowReturn[0].="\\\n";
					}
					$aRowReturn[0].=$aValue['Name']." ";
					$sOldName=$aValue['Name'];
					break;

				default:
					$aRowReturn=$aRow;
					break;
			}
		}
		return $aRowReturn;
	}
	//--------------------------------------------------------------------------------------------------
	/**
	 * Set sWhere for include/sql function
	 *
	 * @param string $sWhere
	 * @param array $aData
	 * @param string $sDataField
	 * @param string $sPrefix
	 * @param string $sTableField
	 */
	public static function SetWhere(&$sWhere,$aData,$sDataField,$sPrefix,$sTableField="")
	{
		if ($aData[$sDataField]) {
			if ($sTableField=="") $sTableField=$sDataField;
			$s="='"; $ss="'";
			if (strpos($aData[$sDataField],'>')===0 || strpos($aData[$sDataField],'<')===0) {
				$s=""; $ss="";
			}
			$sWhere.=" and ".$sPrefix.".".$sTableField.$s.$aData[$sDataField].$ss;
		}
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * Get sql for convert date from sql to normal format
	 *
	 * @param string $sNameField
	 * @return srting
	 */
	public static function GetDateFormat($sNameField="post_date", $sFormat="")
	{
		if (!$sFormat) $sFormat=Base::GetConstant("date_format");
		return " date_format(".$sNameField.",'".$sFormat."')";
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * Get date or sql to convert data from normal to sql format
	 *
	 * @param string $sPostDate
	 * @param boolen $bReturnDate
	 * @return string
	 */
	public static function GetStrToDate($sPostDate, $bReturnDate=false, $sFormat="")
	{
		if (!$sFormat) $sFormat=Base::GetConstant("date_format");
		$sSql=" str_to_date('".$sPostDate."', '".$sFormat."') ";
		if ($bReturnDate) return Db::GetOne("select".$sSql); else return $sSql;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetModels($aData)
	{
	    $sParamsHash=md5(serialize($aData));
	    if(isset(Base::$oTecdocDb->realtimeCache['GetModels'][$sParamsHash])) {
	        return Base::$oTecdocDb->realtimeCache['GetModels'][$sParamsHash];
	    }
	    
	    if ($aData['id_make'] && !$aData['id_mfa'])
	    {
	        $sWhere.=" and man.ID_src = '".Db::GetOne("select id_mfa from cat where id='".$aData['id_make']."' ")."' ";
	    }
	    elseif ($aData['id_mfa']) {
	        $sWhere.=" and man.ID_src = '".$aData['id_mfa']."' ";
	    }
	    else
	    {
	        $sWhere.=" and 1=0";
	    }
	    
	    if ($aData['id_model'])
	    {
	        $sWhere.=" and m.ID_src in (".$aData['id_model'].")";
	        $sWhere2.=" and cm.tof_mod_id in (".$aData['id_model'].") ";
	    }
	    
	    if ($aData['id_models'])
	    {
	        $sWhere.=" and m.ID_src in(".$aData['id_models'].") ";
	        $sWhere2=" and cm.tof_mod_id in (".$aData['id_models'].") ";
	    }

	    if($aData['join']) {
	        $sJoin.=$aData['join'];
	    }
	    
	    if($aData['where']) {
	        $sWhere.=$aData['where'];
	    }

		if($aData['engines']){
	        $sSelect = ", Code as engines";
	        $sJoin .= " left JOIN ".DB_OCAT."cat_alt_engines as en on en.ID_src = m.ID_src ";
	    } 
	    
	    $sSql="select 
	        m.Name name,
	        m.ID_src mod_id,
	        m.ID_src id
    		,concat(substr(m.DateStart,4,4),substr(m.DateStart,1,2)) mod_pcon_start
    		,concat(substr(m.DateEnd,4,4),substr(m.DateEnd,1,2)) mod_pcon_end
    		,man.ID_src mod_mfa_id
    		,substr(m.DateStart,1,2) as month_start, 
	        substr(m.DateStart,4,4) as year_start
    		,substr(m.DateEnd,1,2) as month_end, 
	        substr(m.DateEnd,4,4) as year_end ". $sSelect. "
    		from ".DB_OCAT."cat_alt_models as m
    		inner join ".DB_OCAT."cat_alt_manufacturer man on m.ID_mfa=man.ID_mfa
    		    ".$sJoin."
         	where 1=1
            ".$sWhere." order by m.name";
	    
	    $aTecdocModel=TecdocDb::GetAll($sSql);
	    $aModelsList=array();
	    if($aTecdocModel) foreach ($aTecdocModel as $sKey => $aValue) {
	        $aModelsList[$aValue['id']]=$aValue['id'];
	    }
	    
	    if($aModelsList) $sWhere2.=" and cm.tof_mod_id in ('".implode("','", $aModelsList)."') ";
	    $aModels=Db::GetAssoc("select cm.tof_mod_id,cm.name from cat_model as cm where cm.visible=1 ".$sWhere2);
	    
	    if($aTecdocModel) foreach ($aTecdocModel as $sKey => $aValue) {
	       if(array_key_exists($aValue['id'], $aModels)) $aTecdocModel[$sKey]['name']=str_replace("'","",$aModels[$aValue['id']]);
	       else unset($aTecdocModel[$sKey]);
	    }
	    
	    if($aTecdocModel) sort($aTecdocModel);
	    Base::$oTecdocDb->realtimeCache['GetModels'][$sParamsHash]=$aTecdocModel;
	    
	    return $aTecdocModel;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetModel($aData)
	{
	    $aModel=TecdocDb::GetModels($aData);
	     
	    return $aModel[0];
	}
	//-----------------------------------------------------------------------------------------------
	public function GetModelAssoc($aData) {
	    $sParamsHash=md5(serialize($aData));
	    if(isset(Base::$oTecdocDb->realtimeCache['GetModelAssoc'][$sParamsHash])) {
	        return Base::$oTecdocDb->realtimeCache['GetModelAssoc'][$sParamsHash];
	    }
	    
	    if ($aData['multiple']) {
	        $sField.=", tm.*";
	    }
	    
	    if ($aData['id_make'] && !$aData['id_mfa'])
	    {
	    	$iIdTof=Db::GetOne("select id_mfa from cat where id='".$aData['id_make']."'");
	    	if ($iIdTof)
	        	$sWhere.=" and man.id_src = ".$iIdTof;
	    	else 
	    		$sWhere.= ' and 0=1';
	    }
	    elseif ($aData['id_mfa'])
	    {
	        $sWhere.=" and man.id_src = ".$aData['id_mfa'];
	    }
	    else
	    {
	        $sWhere.=" and 1=0";
	    }
	    
	    if ($aData['id_model'])
	    {
	        $sWhere.=" and m.ID_src in (".$aData['id_model'].")";
	    }
	    
	    if ($aData['sOrder']) {
	        $sOrder=$aData['sOrder'];
	    }
	    
	    $sSql="select m.ID_src id,
	        m.Name name
    		from ".DB_OCAT."cat_alt_models as m
    		inner join ".DB_OCAT."cat_alt_manufacturer man on m.ID_mfa=man.ID_mfa
         	where 1=1
            ".$sWhere." order by m.Name";
	     
	    $aTecdocModel=TecdocDb::GetAssoc($sSql);
	    if($aTecdocModel) $aModelsList=array_keys($aTecdocModel);
	     
	    if($aModelsList) $sWhere2.=" and cm.tof_mod_id in ('".implode("','", $aModelsList)."') ";
	    if($aTecdocModel) $aModels=Db::GetAssoc("select cm.tof_mod_id,cm.name from cat_model as cm where cm.visible=1 ".$sWhere2);
	     
	    if($aTecdocModel) foreach ($aTecdocModel as $sKey => $sValue) {
	        if(in_array($sKey, array_keys($aModels))) $aTecdocModel[$sKey]=$aModels[$sKey];
	        else unset($aTecdocModel[$sKey]);
	    }
	    
	    Base::$oTecdocDb->realtimeCache['GetModelAssoc'][$sParamsHash]=$aTecdocModel;
	    
	    return $aTecdocModel;
	}
	//-----------------------------------------------------------------------------------------------
	public function GetModelPicAssoc($aData) {
	    $sParamsHash=md5(serialize($aData));
	    if(isset(Base::$oTecdocDb->realtimeCache['GetModelPicAssoc'][$sParamsHash])) {
	        return Base::$oTecdocDb->realtimeCache['GetModelPicAssoc'][$sParamsHash];
	    }
	    
	    if ($aData['multiple']) {
	        $sField.=", tm.*";
	    }
	    
	    if ($aData['id_make'] && !$aData['id_mfa'])
	    {
	        $sWhere.=" and man.id_src = ".Db::GetOne("select id_mfa from cat where id='".$aData['id_make']."' ");
	    }
	    elseif ($aData['id_mfa'])
	    {
	        $sWhere.=" and man.id_src = ".$aData['id_mfa'];
	    }
	    else
	    {
	        $sWhere.=" and 1=0";
	    }
	    
	    if ($aData['id_model'])
	    {
	        $sWhere.=" and ".DB_OCAT."cat_alt_models.ID_src = ".$aData['id_model'];
	    }
	    
	    if ($aData['sOrder']) {
	        $sOrder=$aData['sOrder'];
	    }
	    
	    $sSql="select m.ID_src,	m.Name, man.ID_src as id_mfa
    		from ".DB_OCAT."cat_alt_models as m
    		inner join ".DB_OCAT."cat_alt_manufacturer man on m.ID_mfa=man.ID_mfa
         	where 1=1
            ".$sWhere." order by m.Name";
	     
	    $aTecdocModel=TecdocDb::GetAll($sSql);
		$aManufacturerList = array();
	    foreach($aTecdocModel as $aValueModel)
			$aManufacturerList[$aValueModel['id_mfa']] = $aValueModel['id_mfa'];
		
		$aManufacturerKeys = array_keys($aManufacturerList);
	    if($aManufacturerKeys) $sWhere2.=" and cm.id_mfa in ('".implode("','", $aManufacturerKeys)."') ";
	    if($aTecdocModel) $aModels=Db::GetAssoc("select cm.tof_mod_id as id_model, cm.name from cat_model as cm where 1=1 ".$sWhere2);
	    	//$aModels=Db::GetAssoc("select cm.id_model, cm.name from model_pic as cm where 1=1 ".$sWhere2);
	    
	    $aResArray = array();
	    if($aTecdocModel) foreach ($aTecdocModel as $sKey => $sValue) {
	        if(in_array($sValue['ID_src'], array_keys($aModels))) $aResArray[$sValue['ID_src']]=$sValue['Name'];
	        else unset($aTecdocModel[$sKey]);
	    }
	    Base::$oTecdocDb->realtimeCache['GetModelPicAssoc'][$sParamsHash]=$aResArray;
	    
	    return $aResArray;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetModelInfo($aData)
	{
	    $sParamsHash=md5(serialize($aData));
	    if(isset(Base::$oTecdocDb->realtimeCache['GetModelInfo'][$sParamsHash])) {
	        return Base::$oTecdocDb->realtimeCache['GetModelInfo'][$sParamsHash];
	    }
	    
	    if ($aData['id_model_detail'])
	    {
	        $sWhere.=" and cat_alt_types.ID_src in (".$aData['id_model_detail'].")";
	    }
	    else
	    {
	        $sWhere=" and 1=0";
	    }
	    
	    $sSql="select
         cat_alt_types.Description as type_auto,
         substr(cat_alt_types.DateStart,1,4) model_year_from,
         substr(cat_alt_types.DateEnd,1,4) model_year_to,
         LEFT(KwHp, LOCATE('/', KwHp)-1) power_kw,
		 SUBSTR(KwHp, LOCATE('/', KwHp)+1) power_hp,
         CCM as tech_engine_capacity,
         Body body_type,
         Drive drive_type,
         Fuel fuel_type,
         Engines engine_type,
		 Doors door,
         '' cylinder
        from ".DB_OCAT."cat_alt_types
        where 1=1
        ".$sWhere;
	    
	    $aResult=TecdocDb::GetRow($sSql);
	    Base::$oTecdocDb->realtimeCache['GetModelInfo'][$sParamsHash]=$aResult;
	    
	    return $aResult;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetModelDetails($aData,$aCat=false,$aCatTitles=false)
	{
	    $sParamsHash=md5(serialize($aData));
	    if(isset(Base::$oTecdocDb->realtimeCache['GetModelDetails'][$sParamsHash])) {
	        return Base::$oTecdocDb->realtimeCache['GetModelDetails'][$sParamsHash];
	    }
	    
		/*if (Base::$aRequest['locale'] && Base::$aRequest['locale']!='ru')
			return TecdocDb::GetModelDetailsDBTOF($aData,$aCat,Base::$aRequest['locale']);*/
		
	    if ($aData['id_model'] || $aData['id_model_detail'] || $aData['id_model_details']) 
	    {
	        if ($aData['id_model']) $sWhere.="and cat_alt_models.ID_src in (".$aData['id_model'].")";
	    }
	    else 
    	{
    		$sWhere="and 1=0";
    	}
    	
    	if ($aData['id_model_detail'])
    	{
    	    $sWhere.=" and cat_alt_types.ID_src in (".$aData['id_model_detail'].")";
    	}
    	
    	if ($aData['id_model_details'])
    	{
    	    $sWhere.=" and cat_alt_types.ID_src in ('".implode("','", $aData['id_model_details'])."')";
    	}
    	
    	if($aData['is_truck']) {
    	    $sField=",tof__types.TYP_MAX_WEIGHT as max_weight, tx1.TEX_TEXT as axle, tx2.TEX_TEXT as model_des ";
    	    $sJoin="
    	        left join ".DB_TOF."tof__types on  tof__types.TYP_ID=cat_alt_types.ID_src
    	        left join ".DB_TOF."tof__designations as d1 on d1.DES_LNG_ID='16' and tof__types.TYP_KV_AXLE_DES_ID=d1.DES_ID
    	        left join ".DB_TOF."tof__des_texts as tx1 on d1.DES_TEX_ID=tx1.TEX_ID
    	        left join ".DB_TOF."tof__designations as d2 on d2.DES_LNG_ID='16' and tof__types.TYP_KV_MODEL_DES_ID=d2.DES_ID
    	        left join ".DB_TOF."tof__des_texts as tx2 on d2.DES_TEX_ID=tx2.TEX_ID
    	        ";
    	}
    	
    	$sSql="select cat_alt_types.*
             , substr(cat_alt_types.DateStart,5,2) as month_start
             , substr(cat_alt_types.DateStart,1,4) as year_start
    		, substr(cat_alt_types.DateEnd,5,2) as month_end
    		, substr(cat_alt_types.DateEnd,1,4) as year_end
    
    	    , cat_alt_models.name as cat_alt_models_name
    		, cat_alt_models.ID_src as id_model
    	    , cat_alt_models.id_mod
    		, cat_alt_types.ID_src as id_model_detail
    		, cat_alt_types.Description as name
    		, LEFT(KwHp, LOCATE('/', KwHp)-1) kw_from
    		, SUBSTR(KwHp, LOCATE('/', KwHp)+1) hp_from
    		, CCM as ccm, Body as body
    		, cat_alt_models.ID_src as mod_id
    		, cat_alt_types.ID_src as typ_id
    		, cat_alt_manufacturer.ID_src as mod_mfa_id
    	    , cat_alt_types.Fuel as fuel
            , cat_alt_types.Description as Description    	    
    	    ".$sField."
            FROM ".DB_OCAT."cat_alt_types
            inner join ".DB_OCAT."cat_alt_models on cat_alt_models.ID_mod = cat_alt_types.ID_mod
        	inner join ".DB_OCAT."cat_alt_manufacturer on cat_alt_models.ID_mfa=cat_alt_manufacturer.ID_mfa
            	".$sJoin."
           where 1=1
            ".$sWhere." order by cat_alt_types.Description";;
    	
    	if(!$aCat) $aCat=Db::GetAssoc("select c.id_mfa,c.id from cat as c where c.visible=1 ");
    	if(!$aCatTitles) $aCatTitles=Db::GetAssoc("select c.id_mfa,c.title from cat as c where c.visible=1");
    	
    	$aTecdocModelDetail=TecdocDb::GetAll($sSql);
    	if($aTecdocModelDetail) foreach ($aTecdocModelDetail as $sKey => $aValue) {
    	    $aTecdocModelDetail[$sKey]['id_make']=$aCat[$aValue['mod_mfa_id']];
    	    $aTecdocModelDetail[$sKey]['name_model']=$aCatTitles[$aValue['mod_mfa_id']].' '.$aValue['cat_alt_models_name'].' '.$aValue['Name'];
    	}
    	
    	Base::$oTecdocDb->realtimeCache['GetModelDetails'][$sParamsHash]=$aTecdocModelDetail;
    	
    	return $aTecdocModelDetail;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetModelDetailAssoc($aData,$aCat=false)
	{
	    $aModelDetails=TecdocDb::GetModelDetails($aData,$aCat);
	    $aModelDetailsAssoc=array();
	    if($aModelDetails) {
	        foreach ($aModelDetails as $aValue) {
	            $aModelDetailsAssoc[$aValue['ID_src']]=$aValue['Name']." ".$aValue['year_start']."-".$aValue['year_end'].
			" ".$aValue['kw_from']."kW/".$aValue['hp_from']."ps ".$aValue['ccm']."ccm ".$aValue['Engines'];
	        }
	    }
	    
	    return $aModelDetailsAssoc;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetApplicability($aData)
	{
	    $sParamsHash=md5(serialize($aData));
	    if(isset(Base::$oTecdocDb->realtimeCache['GetApplicability'][$sParamsHash])) {
	        return Base::$oTecdocDb->realtimeCache['GetApplicability'][$sParamsHash];
	    }
	    
		/*if (Base::$aRequest['locale'] && Base::$aRequest['locale']!='ru')
			return TecdocDb::GetApplicabilityDBTOF($aData,Base::$aRequest['locale']);*/

		if(!$aData["art_id"]) $aData["art_id"]=-1;
		
	    if ($aData["code"] && $aData["art_id"]!=-1)
	    {
	        $aTypId=array();
	    
	        if($aData["art_id"] != -1)
	            $aTmp=TecdocDb::GetAll("select cat_alt_types.ID_src
			        from ".DB_OCAT."cat_alt_types
    				inner join ".DB_OCAT."cat_alt_link_typ_art lta on lta.ID_typ = cat_alt_types.ID_typ
    				inner join ".DB_OCAT."cat_alt_articles a on a.id_art=lta.ID_art
					and a.ID_src='".$aData["art_id"]."' and a.Search='".$aData["code"]."'"
	            );
	    
	        if ($aTmp) foreach ($aTmp as $aValue) {
	            $aTypId[]=$aValue['ID_src'];
	        }
	    
	        if ($aData['id_cat_part'] && !$aData['catalog_manager']) {
	            $aTmp=Db::GetAll($s="select id_cat_model_type
        			from cat_model_type_link
        			where id_cat_part = '".$aData["id_cat_part"]."'"
	            );
	            if ($aTmp) foreach ($aTmp as $aValue) {
	                $aTypId[]=$aValue['id_cat_model_type'];
	            }
	        }
	    
	        if ($aTypId != array())
	            $sWhere.=" and cat_alt_types.ID_src in (".implode(",",$aTypId).")";
	        else
	            $sWhere.=" and 0=1";
	    }
	    else
	    {
	        if(Base::GetConstant('catalog:show_oe','1')==1) {
	            if($aData['pref_arr']) {
	                $aBrandOe=Db::GetAssoc("select id_mfa,id_mfa as id from cat where pref in ('".implode("','", $aData['pref_arr'])."') and is_brand='1' and id_mfa>0 ");
	                $iIdTofOe=implode(",", $aBrandOe);
	            } else {
	                $aBrandOe=Db::GetRow("select id_mfa,is_brand from cat where pref='".$aData['pref']."' and is_brand='1' and id_mfa>0 ");
	                $iIdTofOe=$aBrandOe['id_mfa'];
	            }
	            
	            if(!$iIdTofOe && !$aData['id_cat_part']) {
	                $sWhere=" and 1=0";
	            }
	            
	            if($aBrandOe) {
	                $sJoin="inner join ".DB_OCAT."cat_alt_manufacturer as m on cat_alt_types.id_mfa=m.id_mfa and m.id_src in (".$iIdTofOe.") ";
	            }
	             
	            if ($iIdTofOe)
	            $aTmp=TecdocDb::GetAll("select cat_alt_types.ID_src
    		        from ".DB_OCAT."cat_alt_types
    				inner join ".DB_OCAT."cat_alt_link_typ_art lta on lta.ID_typ = cat_alt_types.ID_typ
	                ".$sJoin."
    				inner join ".DB_OCAT."cat_alt_articles a on a.id_art=lta.ID_art
    	            inner join ".DB_OCAT."cat_alt_original as o on o.id_art=a.id_art
    				where 1=1 and o.oe_code='".$aData["code"]."' and o.oe_brand in (".$iIdTofOe.") "
	            );
	            
	            if ($aTmp) foreach ($aTmp as $aValue) {
	                $aTypId[]=$aValue['ID_src'];
	            }
	            
	            if ($aData['id_cat_part'] && !$aData['catalog_manager']) {
	                $aTmp=Db::GetAll($s="select id_cat_model_type
        			from cat_model_type_link
        			where id_cat_part = '".$aData["id_cat_part"]."'"
	                );
	                if ($aTmp) foreach ($aTmp as $aValue) {
	                    $aTypId[]=$aValue['id_cat_model_type'];
	                }
	            }
	            
	            if ($aTypId != array()) {
	                $sWhere.=" and cat_alt_types.ID_src in (".implode(",",$aTypId).")";
	            } else {
	                $sWhere.=" and 0=1";
	            }
	        } elseif ($aData['id_cat_part'] && !$aData['catalog_manager']) {
	                $aTmp=Db::GetAll($s="select id_cat_model_type
        			from cat_model_type_link
        			where id_cat_part = '".$aData["id_cat_part"]."'"
	                );
	                if ($aTmp) foreach ($aTmp as $aValue) {
	                    $aTypId[]=$aValue['id_cat_model_type'];
	                }
	                if ($aTypId != array()) {
	                    $sWhere.=" and cat_alt_types.ID_src in (".implode(",",$aTypId).")";
	                } else {
	                    $sWhere.=" and 0=1";
	                }
	            } else {
	            $sWhere="and 1=0";
	        }
	    }
	    
	    $sSql="select cat_alt_types.*
             , substr(cat_alt_types.DateStart,5,2) as month_start
             , substr(cat_alt_types.DateStart,1,4) as year_start
    		, substr(cat_alt_types.DateEnd,5,2) as month_end
    		, substr(cat_alt_types.DateEnd,1,4) as year_end
	
    		, cat_alt_models.ID_src as id_model
    		, cat_alt_types.ID_src as id_model_detail
    		, cat_alt_types.Description as name
    		, LEFT(KwHp, LOCATE('/', KwHp)-1) kw_from
    		, SUBSTR(KwHp, LOCATE('/', KwHp)+1) hp_from
    		, CCM as ccm, Body as body
    		, cat_alt_models.ID_src as mod_id
    		, cat_alt_types.ID_src as typ_id
    		, cat_alt_manufacturer.ID_src as mod_mfa_id
    	
            FROM ".DB_OCAT."cat_alt_types
            inner join ".DB_OCAT."cat_alt_models on cat_alt_models.ID_mod = cat_alt_types.ID_mod
        	inner join ".DB_OCAT."cat_alt_manufacturer on cat_alt_models.ID_mfa=cat_alt_manufacturer.ID_mfa
       
           where 1=1
            ".$sWhere;
	     
	    $aTecdocModelDetail=TecdocDb::GetAll($sSql);
	    $aNeedCat=array();
	    if($aTecdocModelDetail) foreach ($aTecdocModelDetail as $sKey => $aValue) {
	        $aNeedCat[]=$aValue['mod_mfa_id'];
	    }
	    $aNeedCat=array_unique($aNeedCat);
	    $aCat=Db::GetAssoc("select c.id_mfa,c.id,c.name from cat as c where id_mfa in ('".implode("','", $aNeedCat)."') and c.visible='1' and c.is_brand='1' ");
	    
	    if($aTecdocModelDetail) foreach ($aTecdocModelDetail as $sKey => $aValue) {
	        if(!$aCat[$aValue['mod_mfa_id']]['id']) unset($aTecdocModelDetail[$sKey]);
	        else {
    	        $aTecdocModelDetail[$sKey]['id_make']=$aCat[$aValue['mod_mfa_id']]['id'];
    	        $aTecdocModelDetail[$sKey]['cat']=$aCat[$aValue['mod_mfa_id']]['name'];
	        }
	    }
	    sort($aTecdocModelDetail);
	    
	    Base::$oTecdocDb->realtimeCache['GetApplicability'][$sParamsHash]=$aTecdocModelDetail;
	     
	    return $aTecdocModelDetail;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetModelDetail($aData,$aCat=false)
	{
	    $aModelDetail=TecdocDb::GetModelDetails($aData,$aCat);
	    
	    return $aModelDetail[0];
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetTree($aData,$bColor=false)
	{
	    if (!$aData['id_model_detail']) return false;
	    
	    $sParamsHash=md5(serialize($aData));
	    if(isset(Base::$oTecdocDb->realtimeCache['GetTree'][$sParamsHash])) {
	        return Base::$oTecdocDb->realtimeCache['GetTree'][$sParamsHash];
	    }
	    
	    TecdocDb::SetWhere($sWhere, $aData, 'level', 't');
	    TecdocDb::SetWhere($sWhere, $aData, 'id_parent', 't');
	    
	    $sSql="(select t.ID_src as id,
            t.Level+1 str_level,
            t.Sort str_sort,
            0 expand,
            CONCAT(UCASE(MID(t.Name,1,1)),MID(t.Name,2)) as data,
            t.ID_parent str_id_parent,
            0 color
            from ".DB_OCAT."cat_alt_tree t
              
            INNER JOIN (
            SELECT link.ID_tree
            FROM ".DB_OCAT."`cat_alt_link_typ_art` ltyp
            INNER JOIN ".DB_OCAT."cat_alt_link_str_grp link ON link.`ID_grp` = ltyp.`ID_grp`
            INNER JOIN ".DB_OCAT."cat_alt_types t on ltyp.`ID_typ`=t.ID_typ and  t.ID_src in ('".$aData['id_model_detail']."')
            GROUP BY link.ID_tree
            )groups ON t.ID_tree = groups.ID_tree
                
            where t.Level > 0
            ".$sWhere."
            order by t.Name)";
	    
	    $aTreeTcD=TecdocDb::GetAll($sSql);
	    
    	$aIdSrc=Db::GetAssoc("select id, id_tree from cat_tree_type_link where id_typ='".$aData['id_model_detail']."'");
	    
// 	    if($aIdSrc) $sSql="select t.ID_src as id,
//             t.Level+1 str_level,
//             t.Sort str_sort,
//             0 expand,
//             CONCAT(UCASE(MID(t.Name,1,1)),MID(t.Name,2)) as data,
//             t.ID_parent str_id_parent,
//             0 color
//             from ".DB_OCAT."cat_alt_tree t
//             where t.Level > 0 and t.ID_src in ('".implode("','", $aIdSrc)."') order by t.Name";
    	
//     	$aTreeLocal=TecdocDb::GetAll($sSql);
    	
    	if(!$aTreeTcD) $aTreeTcD=array();
    	if(!$aTreeLocal) $aTreeLocal=array();
    	
    	if($bColor){
        	$aIdSrc=array_flip($aIdSrc);
        	if($aTreeLocal) foreach ($aTreeLocal as $iK=>$aV){
        	    $aTreeLocal[$iK]['color']=$aIdSrc[$aV['id']];
        	}
    	}
    	
    	$aTree=array_merge($aTreeTcD,$aTreeLocal);
    	
    	usort($aTree, function ($aA, $aB)
    	{
    	    if ($aA['data'] == $aB['data']) {
    	        return 0;
    	    }
    	    return ($aA['data'] < $aB['data']) ? -1 : 1;
    	});
	    
    	Base::$oTecdocDb->realtimeCache['GetTree'][$sParamsHash]=$aTree;
    	
	    return $aTree;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetTreeAssoc()
	{
	    $sParamsHash=md5(serialize('tree'));
	    if(isset(Base::$oTecdocDb->realtimeCache['GetTreeAssoc'][$sParamsHash])) {
	        return Base::$oTecdocDb->realtimeCache['GetTreeAssoc'][$sParamsHash];
	    }
	    
	    $sSql="select id_tree, CONCAT(id_tree,' - ',name) AS name_group
            from ".DB_OCAT."cat_alt_tree t
            where t.Level > 0
            order by t.Name";
	     
	    $aResult=TecdocDb::GetAssoc($sSql);
	    Base::$oTecdocDb->realtimeCache['GetTreeAssoc'][$sParamsHash]=$aResult;
	    
	    return $aResult;
	}
    //-----------------------------------------------------------------------------------------------
	public static function GetTreeTruck($aData)
	{
	    if (!$aData['id_model_detail']) return false;
	     
	    TecdocDb::SetWhere($sWhere, $aData, 'level', 't');
	    TecdocDb::SetWhere($sWhere, $aData, 'id_parent', 't');
	     
	    $sSql="select t.ID_src as id,
            t.Level+1 str_level,
            t.Sort str_sort,
            0 expand,
            CONCAT(UCASE(MID(t.Name,1,1)),MID(t.Name,2)) as data,
            t.ID_parent str_id_parent,
            0 color
            from ".DB_OCAT."cat_alt_tree_mod t
	
            INNER JOIN (
            SELECT link.ID_tree
            FROM ".DB_OCAT."`cat_alt_link_typ_art` ltyp
            INNER JOIN ".DB_OCAT."cat_alt_link_str_grp_mod link ON link.`ID_grp` = ltyp.`ID_grp`
            INNER JOIN ".DB_OCAT."cat_alt_types t on ltyp.`ID_typ`=t.ID_typ and  t.ID_src in ('".$aData['id_model_detail']."') 
            GROUP BY link.ID_tree
            )groups ON t.ID_tree = groups.ID_tree
	
            where t.Level > 0 and t.STR_TYPE =2
            ".$sWhere."
            order by t.Name";
	     
	    return TecdocDb::GetAll($sSql);
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetTreeParts($aData, $aCats=false, $bTruck=false)
	{
	    if ($aData['where'])
	    {
	        $sWhere=$aData['where'];
	    }
	    
	    if ($aData['id_part'] && $aData["id_model_detail"])
	    {
	        $sWhere.="";
	    }
	    else
	    {
	        $sWhere.=" and 0=1 ";
	    }

	    if($aData['id_group']) {
	        $sWhereGroup=" and grp.id_src in ('".$aData['id_group']."') ";
	    }
	    
	    if($aData["id_model_detail"]) {
	        if(strpos($aData["id_model_detail"], ",")!==false) {
	            //add '
	            $aTmp=explode(",", $aData["id_model_detail"]);
	            $aData["id_model_detail"]=implode("','", $aTmp);
	        }
	    }

	    if($bTruck) {
	        $sLinkTable="cat_alt_link_str_grp_mod";
	    } else {
	        $sLinkTable="cat_alt_link_str_grp";
	    }
	    
	    $sSql="select a.ID_src art_id, UPPER(a.Search) art_article_nr
			, a.Name as name
	        , s.ID_src as id_sup
	        , grp.id_src as id_group, grp.Name as group_name
	        , a.id_art
			FROM ".DB_OCAT."cat_alt_link_typ_art lta
			join ".DB_OCAT."cat_alt_types t on lta.ID_typ=t.ID_typ and t.ID_src in ( '".$aData['id_model_detail']."' )
			join ".DB_OCAT.$sLinkTable." lsg on lsg.ID_tree in ('".$aData['id_part']."') and lsg.ID_grp=lta.ID_grp
			join ".DB_OCAT."cat_alt_groups as grp on grp.id_grp=lsg.ID_grp ".$sWhereGroup."
			join ".DB_OCAT."cat_alt_articles a on a.ID_art=lta.ID_art
			join ".DB_OCAT."cat_alt_suppliers s on lta.ID_sup=s.ID_sup
            	where 1=1
            ".$sWhere;
	    if(!$aCats) $aCats=Db::GetAssoc("select c.id_sup,c.id,c.pref,c.title,c.image,c.name from cat as c where c.visible=1");
	    $aCatsMfa=Db::GetAssoc("select c.id_mfa,c.id,c.pref,c.title,c.image,c.name from cat as c where c.visible=1");
	    
	    $aTreeDetails=TecdocDb::GetAll($sSql);
	    if ($aTreeDetails) {
	        $aArtForOriginalLink=array();
	        foreach ($aTreeDetails as $sKey => $aValue) {
    	        $aCat=$aCats[$aValue['id_sup']];
    	        
    	        $aTreeDetails[$sKey]['item_code']=$aCat['pref']."_".$aValue['art_article_nr'];
    	        $aTreeDetails[$sKey]['brand']=$aCat['title'];
    	        $aTreeDetails[$sKey]['image_logo']=$aCat['image'];
    	        $aTreeDetails[$sKey]['pref']=$aCat['pref'];
    	        
    	        $aArtForOriginalLink[]=$aValue['id_art'];
    	    }
	    }
	    
	    if(Base::GetConstant('catalog:show_oe','1')==1) {
	        $iIdMake=Base::$aRequest['car_select']['id_make']?Base::$aRequest['car_select']['id_make']:Base::$aRequest['data']['id_make'];
	        $aMake=Db::GetRow("select id_mfa,is_brand from cat where id='".$iIdMake."' ");
	        
    	    if ($aArtForOriginalLink){
    	        /*$sSql="select 0 art_id, UPPER(o.oe_code) art_article_nr
    			, '' as name
    	        , o.oe_brand as id_mfa
    			FROM ".DB_OCAT."cat_alt_link_typ_art lta
    			join ".DB_OCAT."cat_alt_types t on lta.ID_typ=t.ID_typ and t.ID_src in ('".$aData['id_model_detail']."') 
    			join ".DB_OCAT."cat_alt_link_str_grp lsg on lsg.ID_tree in ('".$aData['id_part']."') and lsg.ID_grp=lta.ID_grp
    			join ".DB_OCAT."cat_alt_articles a on a.ID_art=lta.ID_art
    			join ".DB_OCAT."cat_alt_suppliers s on lta.ID_sup=s.ID_sup
    			join ".DB_OCAT."cat_alt_original o on o.id_art=a.id_art
    			".$sJoin."
                	where 1=1 and o.id_art in ('".implode("','", $aArtForOriginalLink)."') and o.oe_brand='".$aMake['id_mfa']."' 
                ";*/
		 $sSql="select 0 art_id, UPPER(o.oe_code) art_article_nr
		  , '' as name
		  , o.oe_brand as id_mfa
		  FROM ".DB_OCAT."cat_alt_original o
	          where 1=1 and o.id_art in ('".implode("','", $aArtForOriginalLink)."') and o.oe_brand='".$aMake['id_mfa']."'
		  ";
	      
    	        $aTreeDetailsOriginal=TecdocDb::GetAll($sSql);
    	        if ($aTreeDetailsOriginal) {
    	            foreach ($aTreeDetailsOriginal as $sKey => $aValue) {
    	                $aCat=$aCatsMfa[$aValue['id_mfa']];
    	                
    	                $aDataNew=array();
    	                $aDataNew=$aValue;
    	                $aDataNew['item_code']=$aCat['pref']."_".$aValue['art_article_nr'];
    	                $aDataNew['brand']=$aCat['title'];
    	                $aDataNew['image_logo']=$aCat['image'];
    	                $aDataNew['pref']=$aCat['pref'];
    	                
    	                $aTreeDetails[]=$aDataNew;
    	            }
    	        }
    	    }
	    }
	    $sWhereGroup='';
	    if($aData['id_group']) $sWhereGroup=" and ct.id_group in ('".$aData['id_group']."')";
	    $sSql="
		select 0 art_id
			, cp.code art_article_nr
			, cp.item_code as item_code
			, cp.name as name
			, cat.title as brand
			, cat.image as image_logo
			, cat.pref as pref
		from cat_part cp
		inner join cat on cat.pref=cp.pref
		inner join cat_model_type_link cm on cm.id_cat_part=cp.id and cm.id_cat_model_type in ('".$aData['id_model_detail']."')
		inner join cat_tree_link ct on ct.id_cat_part=cp.id and ct.id_tree IN ('".$aData['id_part']."')".$sWhereGroup."
		";
	    
	    $aPartsSite=Db::GetAll($sSql);
	    
	    if(!$aTreeDetails) $aTreeDetails=array();
	    if(!$aPartsSite) $aPartsSite=array();
	    $aTreeDetails=array_merge($aTreeDetails,$aPartsSite);
	    
	    return $aTreeDetails;
	}//-----------------------------------------------------------------------------------------------
	public static function GetTreePartsWihtoutSite($aData, $aCats=false)
	{
	    if ($aData['where'])
	    {
	        $sWhere=$aData['where'];
	    }
	    
	    if ($aData['id_part'] && $aData["id_model_detail"])
	    {
	        $sWhere.="";
	    }
	    else
	    {
	        $sWhere.=" and 0=1 ";
	    }

	    if($aData['id_group']) {
	        $sWhereGroup=" and grp.id_src in ('".$aData['id_group']."') ";
	    }
	    
	    if($aData["id_model_detail"]) {
	        if(strpos($aData["id_model_detail"], ",")!==false) {
	            //add '
	            $aTmp=explode(",", $aData["id_model_detail"]);
	            $aData["id_model_detail"]=implode("','", $aTmp);
	        }
	    }
	    
	    if($aData['limit']) {
	        $iLimit=$aData['limit'];
	    }
	    
	    if($aData['id_model'] && strpos($aData['id_model'], ",")!==false) {
	        $sSql="select a.ID_src art_id, UPPER(a.Search) art_article_nr
    			, a.Name as name
    	        , s.ID_src as id_sup
    	        , grp.id_src as id_group, grp.Name as group_name
    	        , a.id_art
    			FROM ".DB_OCAT."cat_alt_link_mod_art lma
    			join ".DB_OCAT."cat_alt_models as m on m.id_mod=lma.id_mod and m.id_src in (".$aData['id_model'].")
    			join ".DB_OCAT."cat_alt_link_str_grp lsg on lsg.ID_tree in ('".$aData['id_part']."') and lsg.ID_grp=lma.ID_grp
    			join ".DB_OCAT."cat_alt_groups as grp on grp.id_grp=lsg.ID_grp ".$sWhereGroup."
    			join ".DB_OCAT."cat_alt_articles a on a.ID_art=lma.ID_art
    			join ".DB_OCAT."cat_alt_suppliers s on lma.ID_sup=s.ID_sup
                	where 1=1
                ".$sWhere;
	    } else {
	        $sSql="select a.ID_src art_id, UPPER(a.Search) art_article_nr
    			, a.Name as name
    	        , s.ID_src as id_sup
    	        , grp.id_src as id_group, grp.Name as group_name
    	        , a.id_art
    			FROM ".DB_OCAT."cat_alt_link_typ_art lta
    			join ".DB_OCAT."cat_alt_types t on lta.ID_typ=t.ID_typ and t.ID_src in ( '".$aData['id_model_detail']."' )
    			join ".DB_OCAT."cat_alt_link_str_grp lsg on lsg.ID_tree in ('".$aData['id_part']."') and lsg.ID_grp=lta.ID_grp
    			join ".DB_OCAT."cat_alt_groups as grp on grp.id_grp=lsg.ID_grp ".$sWhereGroup."
    			join ".DB_OCAT."cat_alt_articles a on a.ID_art=lta.ID_art
    			join ".DB_OCAT."cat_alt_suppliers s on lta.ID_sup=s.ID_sup
                	where 1=1
                ".$sWhere;
	    }
	    //Debug::PrintPre($sSql);
// 	    if(!$aCats) 
        $aCats=Db::GetAssoc("select c.id_sup,c.id,c.pref,c.title,c.image,c.name,c.id_cat_virtual from cat as c where c.visible=1");
		$aCatsMfa=Db::GetAssoc("select c.id_mfa,c.id,c.pref,c.title,c.image,c.name from cat as c where c.visible=1");
        $aCatsVirtual=Db::GetAssoc("select c.id as id2,c.id,c.pref,c.title,c.image,c.name from cat as c where c.visible=1 and c.is_cat_virtual='1' ");
	    
	    $aTreeDetails=array();
	    
	    $iTrye=0;
	    while ($iTrye < 4 && count($aTreeDetails)<=$iLimit) {
	        
	        $sLimit=" limit ".($iTrye*5000).",5000";
	        $aTmp=TecdocDb::GetAll($sSql." group by a.ID_src ".$sLimit);
	        if($aTmp) {
	            foreach ($aTmp as $aValueTmp) {
	                $aTreeDetails[$aValueTmp['id_art']]=$aValueTmp;
	            }
	        }
	        else 
	        	break;
	        
	        $iTrye++;
	    }
	    
	    if($aTreeDetails) {
	        $aArtForOriginalLink=array_keys($aTreeDetails);
	        sort($aTreeDetails);
	    }
	    if ($aTreeDetails) {
	        foreach ($aTreeDetails as $sKey => $aValue) {
    	        $aCat=$aCats[$aValue['id_sup']];
    	        if($aCat['id_cat_virtual']) {
    	            $aCat=$aCatsVirtual[$aCat['id_cat_virtual']];
    	        }
    	        if ($aCat){
			        $sImage='';
			        if ($aCat['is_use_own_logo'] && $aCat['image'])
			        	$sImage = $aCat['image'];
			        elseif ($aCat['image_tecdoc'])
			        	$sImage = Base::$aGeneralConf['TecDocUrl']."/imgbank/tcd".$aCat['image_tecdoc'];
			         
			        $aTreeDetails[$sKey]['item_code']=$aCat['pref']."_".$aValue['art_article_nr'];
			        $aTreeDetails[$sKey]['brand']=$aCat['title'];
			        $aTreeDetails[$sKey]['image_logo']=$sImage;
			        $aTreeDetails[$sKey]['pref']=$aCat['pref'];
				}
    	    }
	    }
	    
	    if(Base::GetConstant('catalog:show_oe','1')==1) {
	        $iIdMake=Base::$aRequest['car_select']['id_make']?Base::$aRequest['car_select']['id_make']:Base::$aRequest['data']['id_make'];
	        $aMake=Db::GetRow("select id_mfa,is_brand from cat where id='".$iIdMake."' ");
	        
    	    if ($aArtForOriginalLink){
    	        /*$sSql="select 0 art_id, UPPER(o.oe_code) art_article_nr
    			, '' as name
    	        , o.oe_brand as id_mfa
    			FROM ".DB_OCAT."cat_alt_link_typ_art lta
    			join ".DB_OCAT."cat_alt_types t on lta.ID_typ=t.ID_typ and t.ID_src in ( '".$aData['id_model_detail']."')
    			join ".DB_OCAT."cat_alt_link_str_grp lsg on lsg.ID_tree in ('".$aData['id_part']."') and lsg.ID_grp=lta.ID_grp
    			join ".DB_OCAT."cat_alt_articles a on a.ID_art=lta.ID_art
    			join ".DB_OCAT."cat_alt_suppliers s on lta.ID_sup=s.ID_sup
    			join ".DB_OCAT."cat_alt_original o on o.id_art=a.id_art
    			".$sJoin."
                	where 1=1 and o.id_art in ('".implode("','", $aArtForOriginalLink)."') and o.oe_brand='".$aMake['id_mfa']."' 
                ";*/
                $sSql="select 0 art_id, UPPER(o.oe_code) art_article_nr
		  , '' as name
		  , o.oe_brand as id_mfa
		  FROM ".DB_OCAT."cat_alt_original o
	          where 1=1 and o.id_art in ('".implode("','", $aArtForOriginalLink)."') and o.oe_brand='".$aMake['id_mfa']."'
		";
    	        
    	        $aTmpOrig=TecdocDb::GetAll($sSql);
    	        if($aTmpOrig) {
    	            foreach ($aTmpOrig as $aValue) {
    	                $aTreeDetailsOriginal[$aValue['art_article_nr']."_".$aValue['id_mfa']]=$aValue;
    	            }
    	            if($aTreeDetailsOriginal) {
    	                sort($aTreeDetailsOriginal);
    	            }
    	        }
    	        
    	        
    	        if ($aTreeDetailsOriginal) {
    	            foreach ($aTreeDetailsOriginal as $sKey => $aValue) {
    	                $aCat=$aCatsMfa[$aValue['id_mfa']];
    	                if($aCat['id_cat_virtual']) {
    	                    $aCat=$aCatsVirtual[$aCat['id_cat_virtual']];
    	                }
						if ($aCat) {
			                $sImage='';
			                if ($aCat['is_use_own_logo'] && $aCat['image'])
			                	$sImage = $aCat['image'];
			                elseif ($aCat['image_tecdoc'])
			                	$sImage = Base::$aGeneralConf['TecDocUrl']."/imgbank/tcd".$aCat['image_tecdoc'];
			                
			                $aDataNew=array();
			                $aDataNew=$aValue;
			                $aDataNew['item_code']=$aCat['pref']."_".$aValue['art_article_nr'];
			                $aDataNew['brand']=$aCat['title'];
		   	                $aDataNew['image_logo']=$sImage;
			                $aDataNew['pref']=$aCat['pref'];
			                
			                $aTreeDetails[]=$aDataNew;
						}
    	            }
    	        }
    	    }
	    }
	    
	    return $aTreeDetails;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetTreePartsRubricator($aData, $aCats=false)
	{
	    static $aCatsVirtual;
	    
	    if ($aData['where'])
	    {
	        $sWhere=$aData['where'];
	    }
	     
	    if ($aData['id_part'])
	    {
	        $sWhere.="";
	    }
	    else
	    {
	        $sWhere.=" and 0=1 ";
	    }
	    
	    if($aData['id_group']) {
	        $sWhereGroup=" and grp.id_src in ('".$aData['id_group']."') ";
	    }
	    
	    if($aData['id_mfa']) {
	        $sSql="select DISTINCT a.ID_src art_id, UPPER(a.Search) art_article_nr
			, a.Name as name
	        , s.ID_src as id_sup
	        , grp.id_src as id_group, grp.Name as group_name
			FROM ".DB_OCAT."cat_alt_link_mfa_art lta
			join ".DB_OCAT."cat_alt_link_str_grp lsg on lsg.ID_tree in ('".$aData['id_part']."') and lsg.ID_grp=lta.ID_grp
			join ".DB_OCAT."cat_alt_groups as grp on grp.id_grp=lsg.ID_grp ".$sWhereGroup."
			join ".DB_OCAT."cat_alt_articles a on a.ID_art=lta.ID_art
			join ".DB_OCAT."cat_alt_suppliers s on lta.ID_sup=s.ID_sup
			join ".DB_OCAT."cat_alt_manufacturer as mfa on mfa.id_mfa=lta.id_mfa and mfa.id_src='".$aData['id_mfa']."' 
            	where 1=1
            ".$sWhere." ".$aData['limit'];
	    } else {
	        $sSql="select DISTINCT a.ID_src art_id, UPPER(a.Search) art_article_nr
			, a.Name as name
	        , s.ID_src as id_sup
	        , grp.id_src as id_group, grp.Name as group_name
			FROM ".DB_OCAT."cat_alt_link_art lta
			join ".DB_OCAT."cat_alt_link_str_grp lsg on lsg.ID_tree in ('".$aData['id_part']."') and lsg.ID_grp=lta.ID_grp
			join ".DB_OCAT."cat_alt_groups as grp on grp.id_grp=lsg.ID_grp ".$sWhereGroup."
			join ".DB_OCAT."cat_alt_articles a on a.ID_art=lta.ID_art
			join ".DB_OCAT."cat_alt_suppliers s on lta.ID_sup=s.ID_sup
			 	where 1=1
            ".$sWhere." ".$aData['limit'];
	    }
	    
	    if(!$aCats) $aCats=Db::GetAssoc("select c.id_sup,c.id,c.pref,c.title,c.image,c.name,c.id_cat_virtual from cat as c where c.visible=1 and c.id_sup>0 ");
	    if(!$aCatsVirtual) $aCatsVirtual=Db::GetAssoc("select c.id as id2,c.id,c.pref,c.title,c.image,c.name from cat as c where c.visible=1 and c.is_cat_virtual='1' ");
	    
	    $aTreeDetails=TecdocDb::GetAll($sSql);
	    $aTreeDetailsTmp=array();
	    if ($aTreeDetails) foreach ($aTreeDetails as $sKey => $aValue) {
	        $aCat=$aCats[$aValue['id_sup']];
	        // добавить в результат виртуальные бренды
	        if($aCat['id_cat_virtual']) {
	            $aCat=$aCatsVirtual[$aCat['id_cat_virtual']];
    	        if ($aCat) {
    	            $aDataNew=array();
    	            $aDataNew=$aValue;
    	            $aDataNew['item_code']=$aCat['pref']."_".$aValue['art_article_nr'];
    	            $aDataNew['brand']=$aCat['title'];
    	            $aDataNew['image_logo']=$aCat['image'];
    	            $aDataNew['pref']=$aCat['pref'];
    	             
    	            $aTreeDetails[]=$aDataNew;
	           }
	        }
	        $aTreeDetails[$sKey]['item_code']=$aCat['pref']."_".$aValue['art_article_nr'];
	        $aTreeDetails[$sKey]['brand']=$aCat['title'];
	        $aTreeDetails[$sKey]['image_logo']=$aCat['image'];
	        $aTreeDetails[$sKey]['pref']=$aCat['pref'];
	        
	        $aTreeDetailsTmp[$aTreeDetails[$sKey]['item_code']]=$aTreeDetails[$sKey];
	    }
	    $aTreeDetails=array_values($aTreeDetailsTmp);
	    unset($aTreeDetailsTmp);
	     
	    return $aTreeDetails;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetPartCriterias($aData) 
	{
	    $sParamsHash=md5(serialize($aData));
	    if(isset(Base::$oTecdocDb->realtimeCache['GetPartCriterias'][$sParamsHash])) {
	        return Base::$oTecdocDb->realtimeCache['GetPartCriterias'][$sParamsHash];
	    }
	    
	    if ($aData['where'])
	    {
	        $sWhere=$aData['where'];
	    }
	    
	    if(!$aData['aId']) $aData['aId']=array();
	    $inId = "'".implode("','",$aData['aId'])."'";
	    $inIdOpti = "'".TecdocDb::GetOne("select group_concat(ID_art SEPARATOR '\',\'') from ".DB_OCAT."cat_alt_articles
		where ID_src>0 and ID_src in(".$inId.")")."'";
	    
	    if(!$aData['aIdCatPart']) $aData['aIdCatPart']=array();
	    $inIdCatPart = "'".implode("','",$aData['aIdCatPart'])."'";
	    
	    if ($inIdOpti!="''") {   	
	        $sWhere.=" and art.ID_art in(".$inIdOpti.")";
	        if ($aData['id_model_detail']) {
	            $sWhere1.=" and art.ID_art in(".$inIdOpti.") and t.ID_src in (".$aData['id_model_detail'].") ";
	        } else {
	            $sWhere1.=" and 0=1 ";
	        }
	    } else {
	        $sWhere.=" and 0=1 ";
	        $sWhere1.=" and 0=1 ";
	    }
	    
	    if ($inIdCatPart)
	    {
	        $sWhere2.=" and id_cat_part in(".$inIdCatPart.")";
	    }
	    else
	    {
	        $sWhere2.=" and 0=1 ";
	    }
	    
	    if ($aData['type_']=="all") {
	        $sField.=" distinct  krit_name, krit_value";
	    } elseif ($aData['type_']=="all_edit") {
	        $sField.=" krit_name, krit_value, id_cat_info";
	    } else {
	        $sField.=" group_concat(' ', krit_name, ' ', krit_value) as criteria ";
	        $sGroup.=" group by crt.acr_art_id";
	    
	        if ($aData['type_']=="only_la") $sWhere.=" and 0=1 ";
	    }
	    
	    $sSqlTecdoc="
        	select
        	".$sField."
        	from (
        	select art.ID_src as acr_art_id
        		, a.Name as krit_name
        		, a.Value as krit_value
        		, 2 flag
        		, lac.Sort sort
        		, 0 as id_cat_info
           from ".DB_OCAT."cat_alt_additions a
        		join ".DB_OCAT."cat_alt_link_art_inf lac on lac.ID_add=a.ID_add
        		join ".DB_OCAT."cat_alt_articles art on art.ID_art=lac.ID_art
        where 1=1
          ".$sWhere."
          union all
        select art.ID_src as acr_art_id
        		, a.Name as krit_name
        		, a.Value as krit_value
        	   , 1 flag
        	   , ltc.Sort sort
        	   , 0 as id_cat_info
           from ".DB_OCAT."cat_alt_additions a
        		join ".DB_OCAT."cat_alt_link_typ_inf ltc on ltc.ID_add=a.ID_add
        		join ".DB_OCAT."cat_alt_articles art on art.ID_art=ltc.ID_art
        		join ".DB_OCAT."cat_alt_types t on t.ID_typ=ltc.ID_typ
        	where  1=1
          ".$sWhere1."
        ) as crt
        ".$sGroup;
	    
	    $sSqlSite="select
        	".$sField."
        	from (
        	select id_cat_part as acr_art_id
        		, name as krit_name
        		, code as krit_value
        		, 2 as flag
        		, 0 as sort
        		, id as id_cat_info
           from cat_info
        	where 1=1
         ".$sWhere2."
        ) as crt
        ".$sGroup;
	    
	    $aDataTecdoc=TecdocDb::GetAll($sSqlTecdoc);
	    $aDataSite=Db::GetAll($sSqlSite);
	    
	    if(!$aDataTecdoc) $aDataTecdoc=array();
	    if(!$aDataSite) $aDataSite=array();
	    
	    $aResult=array_merge($aDataTecdoc,$aDataSite);
	    Base::$oTecdocDb->realtimeCache['GetPartCriterias'][$sParamsHash]=$aResult;
	    
	    return $aResult;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetImages($aData, $aCats=false, $bAssoc=TRUE)
	{
	    $sParamsHash=md5(serialize($aData).'&bAssoc='.($bAssoc ? 1 : 0));
	    if(isset(Base::$oTecdocDb->realtimeCache['GetImages'][$sParamsHash])) {
	        return Base::$oTecdocDb->realtimeCache['GetImages'][$sParamsHash];
	    }
	    
	    if(!$aData['aIdGraphic']) $aData['aIdGraphic']=array();
	    $inIdGraphic = "'".implode("','",$aData['aIdGraphic'])."'";
	    
	    if(!$aData['aIdCatPart']) $aData['aIdCatPart']=array();
	    $inIdCatPart = "'".implode("','",$aData['aIdCatPart'])."'";
	    
	    //MPI-1653
		$sWhere.=" and g.path not like '0/0.jpg' ";
	    
	    if ($inIdGraphic and $inIdGraphic != "''" and $inIdGraphic != "'0'") {
	        $sWhere.=" and a.ID_src in(".$inIdGraphic.")";
	    } else {
	        $sWhere.=" and 0=1 ";
	    }
	    
	    if ($inIdCatPart and $inIdCatPart != "''") {
	        $sWhere2.=" and cpp.id_cat_part in(".$inIdCatPart.")";
	    } else {
	        $sWhere2.=" and 0=1";
	    }
	    
	    $sAdd='';
	    if ($aData['type_image']=='pdf')
	    {
	        $sWhere.=" and g.path like '%.pdf' ";
	        $sWhere2.=" and extension='pdf' ";
		$sAdd = '/pdf';
	    }
	    else
	    {
	        $sWhere.=" and g.path not like '%.pdf' ";
	        $sWhere2.=" and extension<>'pdf' ";
	    }
	    
	    if($aData['codes']) {
	        $sWhere2=" and cp.code in (".$aData['codes'].") group by cp.item_code";
	    }
	    
	    if($aData['codesTD']) {
	    	$sWhere=" and a.Search in (".$aData['codesTD'].") and a.ID_src>0 and g.path not like '%.pdf' group by a.Search, s.ID_src";
	    }
	    
	    if ($aData['hide_tof_image']) {
	        $sWhere.=" and 0=1 ";
	    }

	    $sSql="select upper(trim(cp.item_code)) as item_code, cpp.image as img_path, cpp.id as id_cat_pic, cpp.*
        FROM cat_pic cpp
	    inner join cat_part as cp on cp.id=cpp.id_cat_part 
        where 1=1
        ".$sWhere2;
	    
	    $sSql2="select a.Search, s.ID_src as id_sup, 
        concat( '".Base::$aGeneralConf['TecDocUrl']."/imgbank/tcd".$sAdd."' , g.path) as img_path
        FROM ".DB_OCAT."cat_alt_images g
        JOIN ".DB_OCAT."cat_alt_articles a ON a.ID_art = g.ID_art
        join ".DB_OCAT."cat_alt_suppliers as s on s.id_sup=a.id_sup
        where 1=1
        ".$sWhere;

	    $aImagesSite=Db::GetAll($sSql);
        $aImagesTcd=TecdocDb::GetAll($sSql2);
	    $aResult=array();
        
        if($aImagesTcd) {
        	$aAllPathTcd = array();
        	foreach($aImagesTcd as $aValue)
        		if (!$aAllPathTcd[$aValue['img_path']])
        			$aAllPathTcd[$aValue['img_path']] = $aValue['img_path'];
        	if ($aAllPathTcd) {
        		$aImagesTcdInfo = Db::GetAssoc("Select c.path as key_,c.* from cat_pic_tecdoc c where path in ('".implode("','", $aAllPathTcd)."')");
        	} 
        	
            if(!$aCats) {
				$aCatsAll=Db::GetAll("select c.pref,c.id_sup,c.id,c.id_cat_virtual from cat as c where c.visible=1");
        		foreach($aCatsAll as $aVal) {
        	    	if ($aVal['id_sup'])
        				$aCats[$aVal['id_sup']][$aVal['pref']] = $aVal;
        		}
			}
            foreach ($aImagesTcd as $sKey => $aValue) {
            	$aValue['Search'] = mb_strtoupper($aValue['Search'],'utf-8');
            	if ($aImagesTcdInfo[$aValue['img_path']])
            		$aValue += $aImagesTcdInfo[$aValue['img_path']];
            	
            	if ($aValue['num'])
            		$aTmpSort[] = $aValue['num'];
            	else 
            		$aTmpSort[] = 0;
            	
            	$aTmp[] = $aValue;
            	/*
                if($bAssoc) $aResult[$aCats[$aValue['id_to f']]['pref']."_".$aValue['Search']]=$aValue;
                else $aResult[]=array('img_path'=>$aValue);
                */
            }
        }
        if($aImagesSite) foreach ($aImagesSite as $aValue) {
        	$aTmp[] = $aValue;
        	$aTmpSort[] = $aValue['num'];  
        	/*
            if($bAssoc) $aResult[$aValue['item_code']]=$aValue;
            else $aResult[]=$aValue;
            */
        }
        if ($aTmp) {
            $aCatsVirtual=Db::GetAssoc("select c.id,c.pref from cat as c where c.visible=1 and c.is_cat_virtual");
	        array_multisort ($aTmpSort, SORT_ASC, SORT_NUMERIC, $aTmp);
	        foreach($aTmp as $aValue) {
	        	if($bAssoc) {
	        		// tecdoc
	        		if ($aValue['Search']) {
						if (!is_array($aCats[$aValue['id_sup']])){
	        				$aResult[$aCats[$aValue['id_sup']]['pref']."_".$aValue['Search']]=$aValue;
						}else {
							foreach ($aCats[$aValue['id_sup']] as $sPrefCat => $aPrefCat){
            		    		$aResult[$sPrefCat."_".$aValue['Search']]=$aValue;
            		    		if($aCats[$aValue['id_sup']][$aPrefCat['pref']]['id_cat_virtual'] && $aCatsVirtual[$aCats[$aValue['id_sup']][$aPrefCat['pref']]['id_cat_virtual']]){
            		    		    $aResult[$aCatsVirtual[$aCats[$aValue['id_sup']][$aPrefCat['pref']]['id_cat_virtual']]."_".$aValue['Search']]=$aValue;
            		    		}
							}
            	    	}
	        		}else $aResult[$aValue['item_code']]=$aValue;
	        	}
	        	else 
	        		$aResult[] = $aValue;
	        }
        }
        Base::$oTecdocDb->realtimeCache['GetImages'][$sParamsHash]=$aResult;
        
	    return $aResult;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetArt($aData)
	{
	    $sParamsHash=md5(serialize($aData));
	    if(isset(Base::$oTecdocDb->realtimeCache['GetArt'][$sParamsHash])) {
	        return Base::$oTecdocDb->realtimeCache['GetArt'][$sParamsHash];
	    }
	    
	    if(!$aData['id_sup'] && $aData['pref']) $aData['id_sup']=Db::GetOne("select id_sup from cat where pref='".$aData['pref']."' ");
	    
	    $aResult=TecdocDb::GetOne("
			select a.ID_src
			from ".DB_OCAT."cat_alt_articles a
			INNER JOIN ".DB_OCAT."cat_alt_suppliers as s on a.ID_sup=s.ID_sup and s.ID_src='".$aData['id_sup']."'
			where a.Search = '".$aData['code']."'");
	    
	    Base::$oTecdocDb->realtimeCache['GetArt'][$sParamsHash]=$aResult;
	    return $aResult;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetArtName($aData)
	{
	    $sParamsHash=md5(serialize($aData));
	    if(isset(Base::$oTecdocDb->realtimeCache['GetArtName'][$sParamsHash])) {
	        return Base::$oTecdocDb->realtimeCache['GetArtName'][$sParamsHash];
	    }
	    
	    if(!$aData['id_sup'] && $aData['pref']) $aData['id_sup']=Db::GetOne("select id_sup from cat where pref='".$aData['pref']."' ");
	    
	    $aResult= TecdocDb::GetRow("
			select a.ID_src, a.Name
			from ".DB_OCAT."cat_alt_articles a
			INNER JOIN ".DB_OCAT."cat_alt_suppliers as s on a.ID_sup=s.ID_sup and s.ID_src='".$aData['id_sup']."'
			where a.Search = '".$aData['code']."'");
	    
	    Base::$oTecdocDb->realtimeCache['GetArtName'][$sParamsHash]=$aResult;
	    return $aResult;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetArts($aCodes)
	{
	    $sParamsHash=md5(serialize($aCodes));
	    if(isset(Base::$oTecdocDb->realtimeCache['GetArts'][$sParamsHash])) {
	        return Base::$oTecdocDb->realtimeCache['GetArts'][$sParamsHash];
	    }
	    
	    $aArts=TecdocDb::GetAll("select a.ID_src as art_id, a.Search as code, s.ID_src as id_sup
			from ".DB_OCAT."cat_alt_articles a
			INNER JOIN ".DB_OCAT."cat_alt_suppliers as s on a.ID_sup=s.ID_sup
			where a.Search in ('".implode("','", $aCodes)."') ");
	    
	    if($aArts) {
            $aIdTof=array_column($aArts,'id_sup');
            $aIdTof=array_unique($aIdTof);
	        
	        $aCat=Db::GetAssoc("select id_sup,pref from cat where id_sup in ('".implode("','",$aIdTof)."') ");
	        $aResult=array();
	        
	        if($aCat) foreach ($aArts as $aValue) {
	            $sPref=$aCat[$aValue['id_sup']];
	            
	            if(!$sPref || !$aValue['art_id']) continue;
	            $sIteCode=$sPref."_".$aValue['code'];
	            $aResult[$sIteCode]=$aValue['art_id'];
	        }	        
	    }
	    
	    Base::$oTecdocDb->realtimeCache['GetArts'][$sParamsHash]=$aResult;
	    
	    return $aResult;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetPartInfo($aData,$aCats=false)
	{
	    $sParamsHash=md5(serialize($aData));
	    if(isset(Base::$oTecdocDb->realtimeCache['GetPartInfo'][$sParamsHash])) {
	        return Base::$oTecdocDb->realtimeCache['GetPartInfo'][$sParamsHash];
	    }
	    
	    if ($aData['item_code']) {
	        list($aData['pref'],$aData['sCode'])=explode("_",$aData['item_code']);
	    }
	    
	    if ($aData['sCode']) {
	        $sWhere.=" and a.Search in ('".$aData['sCode']."') and a.Search<>'' ";
	        $sWhere1.=" and cp.code in ('".$aData['sCode']."')";
	    } elseif ($aData['art_id'] && !$aData['id_cat_part']) {
	        $sWhere.=" and a.ID_src in (".$aData['art_id'].")";
	        $sWhere1.=" and 0=1";
	    } elseif ($aData['id_cat_part'] && !$aData['art_id']) {
	        $sWhere.=" and 0=1";
	        $sWhere1.=" and cp.id in (".$aData['id_cat_part'].")";
	    } elseif($aData['id_cat_part'] && $aData['art_id']) {
	        $sWhere.=" and a.ID_src in (".$aData['art_id'].")";
	        $sWhere1.=" and cp.id in (".$aData['id_cat_part'].")";
	    } else {
	        return "select null ";
	    }
	    
	    if ($aData['pref']) {
	        //$sWhere.=" and cat2.pref='".$aData['pref']."'";
	        $sWhere1.=" and cp.pref='".$aData['pref']."'";
	    }
	    
	    $sSql="
            select  a.ID_src art_id, a.Search as code
            		, a.Name as name
	               ,s2.ID_src as id_sup
             from ".DB_OCAT."cat_alt_articles as a
            INNER JOIN ".DB_OCAT."cat_alt_suppliers as s2 on a.ID_sup=s2.ID_sup
             where 1=1 and a.ID_src>0
             ".$sWhere;
	    
	    if(MultiLanguage::IsLocale()){
	        $sName=", cp.name_".Language::$sLocale." as name";
	    }else{
	        $sName=", cp.name_rus as name";
	    }
	    $sSql2="Select '0', cp.code as code
            , cp.item_code as  item_code
            , cp.pref as pref, c.title as brand".$sName."
            , cp.id as id_cat_part
            ,c.name as cat_name
            from cat_part as cp
            inner join cat as c on cp.pref = c.pref
            where 1=1
            ".$sWhere1;
	    
	    $aInfoCat=Db::GetRow($sSql2);
	    if(!$aInfoCat) {
	        Db::Execute("insert ignore into cat_part (item_code,code,pref) values 
	            ('".$aData['pref']."_".$aData['sCode']."','".$aData['sCode']."','".$aData['pref']."') ");
	        $aInfoCat=Db::GetRow($sSql2);
	    }
	    
	    $aInfoTcd=TecdocDb::GetAll($sSql);
	    
	    if(!$aCats) {
	        $aCats=Db::GetAssoc("select c.id_sup,c.id,c.pref,c.title,c.image,c.name from cat as c where c.visible=1");
	    }
	    
	   	$aReturnValue = array();
	    if($aInfoTcd) {
	    	foreach($aInfoTcd as $aValueTcd){
		        $aCat=$aCats[$aValueTcd['id_sup']];
		        if($aCat['pref'] == $aData['pref']){
		        	$aReturnValue = $aValueTcd;
			        $aReturnValue['item_code']=$aCat['pref']."_".$aValueTcd['code'];
			        $aReturnValue['pref']=$aCat['pref'];
			        $aReturnValue['brand']=$aCat['title'];
			        $aReturnValue['id_cat_part']=$aInfoCat['id_cat_part'];
			        $aReturnValue['cat_name']=$aCat['name'];
			        $aReturnValue['code']=$aValueTcd['code'];
		        }
	    	}
	    } else {
	        if($aInfoCat) {
	            $aReturnValue['item_code']=$aInfoCat['item_code'];
	            $aReturnValue['pref']=$aInfoCat['pref'];
	            $aReturnValue['brand']=$aInfoCat['brand'];
	            $aReturnValue['id_cat_part']=$aInfoCat['id_cat_part'];
	            $aReturnValue['cat_name']=$aInfoCat['cat_name'];
				$aReturnValue['code']=$aInfoCat['code'];
	        }
	    }
	    
	    if($aInfoCat) {
	        if(!$aReturnValue['item_code']) $aReturnValue['item_code']=$aInfoCat['item_code'];
	        if(!$aReturnValue['pref']) $aReturnValue['pref']=$aInfoCat['pref'];
	        if(!$aReturnValue['brand']) $aReturnValue['brand']=$aInfoCat['brand'];
	        if(!$aReturnValue['id_cat_part']) $aReturnValue['id_cat_part']=$aInfoCat['id_cat_part'];
	        if(!$aReturnValue['cat_name']) $aReturnValue['cat_name']=$aInfoCat['cat_name'];
	        if(!$aReturnValue['code']) $aReturnValue['code']=$aInfoCat['code'];
	        if(!$aReturnValue['name']) $aReturnValue['name']=$aInfoCat['name'];
	    }
	    
	    Base::$oTecdocDb->realtimeCache['GetPartInfo'][$sParamsHash]=$aReturnValue;
	  
	    return $aReturnValue;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetCross($aData,$aCats=false)
	{
	    if($aData['aCode'] && is_array($aData['aCode'])) {
	        $aData['sCode'] = "'".implode("','",$aData['aCode'])."'";
	    }
	    if ($aData['sCode']) {
	    	$aCodes = array_unique(explode(',',$aData['sCode']));
	    	// для корректной работы виртуальности
	    	if (count($aCodes)==1)
	    	    $aData['sCode'] = $aCodes[0];
	    }
	    
	    if ($aData['code']) $aData['sCode']="'".trim($aData['code'],"'")."'";
	    
	    if ($aData['sCode']) {
	        $sWhere.=" and a1.Search in (".$aData['sCode'].") and a1.Search<>''";
	        $sWhere2.=" and a2.Search in (".$aData['sCode'].") and a2.Search<>''";
	        $sWhere1.=" and cc.code in (".$aData['sCode'].") ";
	        $sWhereStop=" and cc.code in (".$aData['sCode'].") ";
			$sWhere1oe.=" and o.oe_code in (".$aData['sCode'].")";
	    } elseif($aData['aItemCode']) {
            $aCodes=array();
            foreach ($aData['aItemCode'] as $sValue) {
               $sPref='';
               $sCode='';
               list($sPref,$sCode)=explode("_", $sValue);
               $aCodes[]=$sCode;
            }
            $aCodes=array_unique($aCodes);
            $sCode=implode("','", $aCodes);

            $sWhere.=" and a1.Search in ('".$sCode."') and a1.Search<>''";
            $sWhere2.=" and a2.Search in ('".$sCode."') and a2.Search<>''";
            $sWhere1.=" and cc.code in ('".$sCode."') ";
            $sWhereStop =" and cc.code in ('".$sCode."') ";
			$sWhere1oe.=" and o.oe_code in ('".$sCode."')";
	    } else {
	        return null;
	    }
	    
	    if ($aData['pref']) {
	    	$sWherePref .= " and cc.pref='".$aData['pref']."'";
	    	$sWhereStop .= " and cc.pref='".$aData['pref']."'";
	    	
	    	$aCat = Db::GetRow("SELECT * FROM cat WHERE pref like '".$aData['pref']."'");
	    	$aVagPref = array();
	    	$aVagIDTof = array();
	    	$aVagIDMfa = array();
	    	if ($aCat['id_cat_virtual']!=0) {
	    		$aVag=Db::getAll("Select c.* from cat c
	    				inner join cat c2 on c2.id = c.id_cat_virtual and c2.visible=1
	    				where c.visible=1 and c.id_cat_virtual=".$aCat['id_cat_virtual']);
	    		if ($aVag) {
	    			foreach ($aVag as $sKey => $aVagItem) {
	    				foreach ($aCodes as $sCode) {
		    				$sUnion.="
							union SELECT concat('".$aVagItem['pref']."','_',".$sCode.") as  item_code_crs ,
							concat('".$aData['pref']."','_',".$sCode.") as item_code, 0 is_replacement, 0 as art_id, 0 as art_id2,
							'".$aData['pref']."' as pref, ".$sCode." as code,
							'".$aVagItem['pref']."' as pref_crs, ".$sCode." as code_crs";
	    				}
	    				$aVagPref[$aVagItem['pref']]=$aVagItem['pref'];
	    				if ($aVagItem['id_sup'])
	    					$aVagIDTof[$aVagItem['id_sup']] = $aVagItem['id_sup'];
	    				if ($aVagItem['id_mfa'])
	    				    $aVagIDMfa[$aVagItem['id_mfa']] = $aVagItem['id_mfa'];
	    			}
	    			$sWherePref=" and cc.pref in ('".implode("','",$aVagPref)."')";
	    		}
	    	}
	    	elseif ($aCat['is_cat_virtual']!=0) {
	    		if ($aCat['id_sup'])
	    			$aVagIDTof[$aCat['id_sup']] = $aCat['id_sup'];
	    		if ($aCat['id_mfa'])
	    		    $aVagIDMfa[$aCat['id_mfa']] = $aCat['id_mfa'];
	    		$aVag=Db::getAll("Select c.* from cat c
	    				where c.visible=1 and c.id_cat_virtual=".$aCat['id']);
	    		if ($aVag) {
	    			foreach ($aVag as $sKey => $aVagItem) {
	    				foreach ($aCodes as $sCode) {
		    				$sUnion.="
							union SELECT concat('".$aVagItem['pref']."','_',".$sCode.") as  item_code_crs ,
							concat('".$aData['pref']."','_',".$sCode.") as item_code, 0 is_replacement, 0 as art_id, 0 as art_id2,
							'".$aData['pref']."' as pref, ".$sCode." as code,
							'".$aVagItem['pref']."' as pref_crs, ".$sCode." as code_crs";
	    				}
	    				$aVagPref[$aVagItem['pref']]=$aVagItem['pref'];
	    				if ($aVagItem['id_sup'])
	    					$aVagIDTof[$aVagItem['id_sup']] = $aVagItem['id_sup'];
	    				if ($aVagItem['id_mfa'])
	    				    $aVagIDMfa[$aVagItem['id_mfa']] = $aVagItem['id_mfa'];
	    			}
	    			
	    		}
	    	}
	    	if ($aVagPref) {
	    		if ($aData['pref'])
	    			$aVagPref[$aData['pref']] = $aData['pref'];
	    		
	    	    if($aCat['id_sup']){
    	    		$sWherePref=" and cc.pref in ('".implode("','",$aVagPref)."','".$aData['pref']."')";
    	    		//$sWhere1 .= $sWherePref;	    	        
	    	    }else {
    	    		$sWherePref=" and cc.pref in ('".implode("','",$aVagPref)."')";
    	    		//$sWhere1 .= $sWherePref;
	    	    }
	    	}
	    	
	    	$sWhere1 .= $sWherePref;
	    	
	    	if ($aVagIDTof) {
	    	    if($aCat['id_sup']){
	    	        $sWhere .= " and s1.ID_src in ('".implode("','",$aVagIDTof)."','".$aCat['id_sup']."')";
	    	        $sWhere2 .= " and s2.ID_src in ('".implode("','",$aVagIDTof)."','".$aCat['id_sup']."')";
	    	    }else {
    	    		$sWhere .= " and s1.ID_src in ('".implode("','",$aVagIDTof)."')";
    	    		$sWhere2 .= " and s2.ID_src in ('".implode("','",$aVagIDTof)."')";
	    	    }
				if ($aVagIDMfa) {
					if ($aCat['id_mfa']){
				 		$sWhere1oe .= " and m1.ID_src in ('".implode("','",$aVagIDMfa)."','".$aCat['id_mfa']."')";
					}
					else {
						$sWhere1oe .= " and m1.ID_src in ('".implode("','",$aVagIDMfa)."')";
					}
				}
	    	}
	    	elseif ($aVagIDMfa) {
				if ($aCat['id_mfa']){
			 		$sWhere1oe .= " and m1.ID_src in ('".implode("','",$aVagIDMfa)."','".$aCat['id_mfa']."')";
				}
				else {
    	    		$sWhere1oe .= " and m1.ID_src in ('".implode("','",$aVagIDMfa)."')";
				}
	    	}
	    	elseif($aCat['id_sup']){
	    		$sWhere .= " and s1.ID_src in ('".$aCat['id_sup']."')";
	    		$sWhere2 .= " and s2.ID_src in ('".$aCat['id_sup']."')";
	    		if ($aCat['id_mfa'])
	    		    $sWhere1oe .= " and m1.ID_src in ('".$aCat['id_mfa']."')";
	    	}
	    	elseif($aCat['id_mfa']){
	    	    $sWhere1oe .= " and m1.ID_src in ('".$aCat['id_mfa']."')";
	    	}
	    	else {
	    		$sWhere .= " and 1=0 ";
	    		$sWhere2 .= " and 1=0 ";
	    		$sWhere1oe .= " and 1=0 ";
	    	}
	    }
	    $sWhere_oe = $sWhere;
	    $sWhere.= ' and a2.ID_src > 0';
	    $sWhere2.=' and a1.ID_src > 0';
	    
	    if ($aData['limit']) {
	        $sLimit=" limit ".$aData['limit'];
	    }
	    
	    $sSql="select t.* from (
        	SELECT concat(cc.pref_crs,'_',cc.code_crs) as  item_code_crs
        	   ,  concat(cc.pref,'_',cc.code) as item_code
        	   , cc.is_replacement, 0 as art_id, 0 as art_id2
	    	   , cc.pref as pref, cc.code as code
	    	   , cc.pref_crs as pref_crs, cc.code_crs as code_crs
        	FROM cat_cross as cc
        	where 1=1
        	".$sWhere1
        	    	.$sUnion.
        	    	") t
        	LEFT OUTER JOIN cat_cross_stop ccs ON t.item_code=concat(ccs.pref,'_',ccs.code) and t.item_code_crs=concat(ccs.pref_crs,'_',ccs.code_crs)
        	WHERE ccs.id IS null".$sLimit;

	    if (!$sWhereStop)
	    	$sWhereStop = " and 1=0 ";
	    $sSqlStop = "Select concat(pref_crs,'_',code_crs) as item_code_crs, concat(pref,'_',code) as item_code 
	    	from cat_cross_stop cc where 1=1".$sWhereStop;
	    		
	    $sSqlTcd="select 
    	        UPPER(a2.Search) as  code_crs,
    	        UPPER(a1.Search) as  code,
    	        0 as is_replacement, 
    	        a1.ID_src as art_id, 
    	        a2.ID_src as art_id2,
    	        s1.ID_src as id_sup1,
    	        s2.ID_src as id_sup2,
	            1 as ind
        	FROM ".DB_OCAT."cat_alt_crossing c
        	INNER JOIN ".DB_OCAT."cat_alt_articles a1 ON c.ID_art = a1.ID_art
        	INNER JOIN ".DB_OCAT."cat_alt_articles a2 ON c.ID_cross = a2.ID_art
        	INNER JOIN ".DB_OCAT."cat_alt_suppliers as s1 on a1.ID_sup=s1.ID_sup
        	INNER JOIN ".DB_OCAT."cat_alt_suppliers as s2 on a2.ID_sup=s2.ID_sup
        	where 1=1
        	".$sWhere."
        	 union
        	select 
        	    UPPER(a1.Search) as  code_crs,
        	    UPPER(a2.Search) as  code,
        		0 as is_replacement, 
        	    a2.ID_src as art_id, 
        	    a1.ID_src as art_id2,
        	    s2.ID_src as id_sup1,
    	        s1.ID_src as id_sup2,
        	    2 as ind
        	FROM ".DB_OCAT."cat_alt_crossing c
        	INNER JOIN ".DB_OCAT."cat_alt_articles a1 ON c.ID_art = a1.ID_art
        	INNER JOIN ".DB_OCAT."cat_alt_articles a2 ON c.ID_cross = a2.ID_art
        	INNER JOIN ".DB_OCAT."cat_alt_suppliers as s1 on a1.ID_sup=s1.ID_sup
        	INNER JOIN ".DB_OCAT."cat_alt_suppliers as s2 on a2.ID_sup=s2.ID_sup
        	where 1=1
        	".$sWhere2."
        	    union
        	select
        	UPPER(o.oe_code) as  code_crs,
        	UPPER(a1.Search) as  code,
        	0 as is_replacement,
        	a1.ID_src as art_id,
        	0 as art_id2,
        	s1.ID_src as id_sup1,
        	m1.ID_src as id_sup2,
        	3 as ind
        	FROM ".DB_OCAT."cat_alt_original o
        	INNER JOIN ".DB_OCAT."cat_alt_articles a1 ON a1.ID_art = o.ID_art
        	INNER JOIN ".DB_OCAT."cat_alt_suppliers as s1 on a1.ID_sup=s1.ID_sup
        	INNER JOIN ".DB_OCAT."cat_alt_manufacturer as m1 on o.oe_brand=m1.ID_src
        	where 1=1
        	".$sWhere_oe."
        	    union
        	select
        	UPPER(a1.Search) as  code_crs,
        	UPPER(o.oe_code) as  code,
        	0 as is_replacement,
           	0 as art_id,
        	a1.ID_src as art_id2,
        	m1.ID_src as id_sup1,
       	    s1.ID_src as id_sup2,
        	4 as ind
        	FROM ".DB_OCAT."cat_alt_original o
        	INNER JOIN ".DB_OCAT."cat_alt_articles a1 ON a1.ID_art = o.ID_art
        	INNER JOIN ".DB_OCAT."cat_alt_suppliers as s1 on a1.ID_sup=s1.ID_sup
        	INNER JOIN ".DB_OCAT."cat_alt_manufacturer as m1 on o.oe_brand=m1.ID_src
        	where 1=1
        	".$sWhere1oe.$sLimit;
	    
	    $aCrossTcd=TecdocDb::GetAll($sSqlTcd);
	    $aCrossStop=Db::GetAssoc($sSqlStop);

	    if($aCrossTcd) {
	       $aVirtPrefAssoc = array();
	       $aCats=Db::GetAssoc("select c.id_sup,c.id,c.pref,c.title,c.image,c.name,c.is_cat_virtual,c.id_cat_virtual from cat as c where c.visible=1 and c.id_sup>0");
	       $aCatsMfa=Db::GetAssoc("select c.id_mfa,c.id_sup,c.id,c.pref,c.title,c.image,c.name,c.is_cat_virtual,c.id_cat_virtual from cat as c where c.visible=1 and c.id_mfa > 0");
	       $aCrossForStop=array();
	       $aVirtualItemCodes=array();
	       $aVagCatAssoc = array();
	       $aCatCheckVirtual = array();
	       foreach ($aCrossTcd as $sKey => $aValue) {
	           $aCat1=$aCats[$aValue['id_sup1']];
	           $aCat2=$aCats[$aValue['id_sup2']];
	           $aCat3=$aCatsMfa[$aValue['id_sup2']];
	           $aCat4=$aCatsMfa[$aValue['id_sup1']];
	           
	           if ($aValue['ind']==1) {
	               $sPref=$aCat1['pref'];
	               $sPrefCrs=$aCat2['pref'];
	               $aCatCheckVirtual = $aCat1;
	           }
               elseif ($aValue['ind']==2) {
                   $sPref=$aCat1['pref'];
                   $sPrefCrs=$aCat2['pref'];
	               $aCatCheckVirtual = $aCat2;
               }
               elseif ($aValue['ind']==3) {
                   $sPref=$aCat1['pref'];
                   $sPrefCrs=$aCat3['pref'];
                   $aCatCheckVirtual = $aCat1;
               }
               elseif ($aValue['ind']==4) {
                   $sPrefCrs=$aCat2['pref'];
                   $sPref=$aCat4['pref'];
                   $aCatCheckVirtual = $aCat4;
               }
                
	           if(!$sPref || !$sPrefCrs) {
	               unset($aCrossTcd[$sKey]);
	               continue;
	           }

			   // если в запросе ItemCode, лишние кроссы текдока убрать
	           if ($aData['aItemCode'] && !$aData['aItemCode'][$sPref.'_'.$aValue['code']]) {
	               unset($aCrossTcd[$sKey]);
	               continue;
	           }

	           if ($aData['pref'] && 
	               (($sPref!=$aData['pref'] && !$aVagPref[$sPref])
	                   ||
	                ($sPref!=$aData['pref'] && !$aVagPref)
                   )) {
	               unset($aCrossTcd[$sKey]);
	               continue;
	           }
	           // exclude cross stop
	           if ($aCrossStop[$sPrefCrs."_".$aValue['code_crs']] && 
	       		$aCrossStop[$sPrefCrs."_".$aValue['code_crs']] == $sPref."_".$aValue['code']) {
	           		unset($aCrossTcd[$sKey]);
	           		continue;
	           }
	           $aCrossTcd[$sKey]['item_code']=$sPref."_".$aValue['code'];
	           $aCrossTcd[$sKey]['item_code_crs']=$sPrefCrs."_".$aValue['code_crs'];
	           
	           $aCrossForStop[]=$sPref."_".$aValue['code'];
	           
	           $aCrossTcd[$sKey]['pref']=$sPref;
	           $aCrossTcd[$sKey]['pref_crs']=$sPrefCrs;
	           
	          if ($aCatCheckVirtual['is_cat_virtual']) {
	          	if (isset($aVagCatAssoc[$aCatCheckVirtual['id']]))
	          		$aVag = $aVagCatAssoc[$aCatCheckVirtual['id']];
	          	else { 
	          		$aVag=Db::getAll("Select c.* from cat c
		    				where c.visible=1 and c.id_cat_virtual=".$aCatCheckVirtual['id']);
	          		$aVagCatAssoc[$aCatCheckVirtual['id']] = $aVag;
	          	}
	          	if ($aVag) {
	          		foreach ($aVag as $sKeyP => $aVagItem) {
	          			// add virtual info stop
	          			$aCrossForStop[]=$aVagItem['pref']."_".$aValue['code'];
	          			if (!$aVirtPrefAssoc[$aCatCheckVirtual['pref']][$aVagItem['pref']])
	          				$aVirtPrefAssoc[$aCatCheckVirtual['pref']][$aVagItem['pref']] = $aVagItem['pref']; 
	          			if (!$aVirtPrefAssoc[$aVagItem['pref']][$aCatCheckVirtual['pref']])
	          				$aVirtPrefAssoc[$aVagItem['pref']][$aCatCheckVirtual['pref']] = $aCatCheckVirtual['pref'];
	          			
	          		   if ($aCrossTcd[$sKey]['pref']==$aData['pref'] &&
	          			"'".$aCrossTcd[$sKey]['code']."'"==$aData['sCode'] &&
	          			$aVagItem['pref']!=$aCrossTcd[$sKey]['pref']) {
	          				$aVirtualItem = $aCrossTcd[$sKey];
	          				$aVirtualItem['pref'] = $aVagItem['pref'];
	          				$aVirtualItem['item_code'] = $aVagItem['pref'].'_'.$aVirtualItem['code'];
	          				$aVirtualItems[] = $aVirtualItem;
	          			}elseif ($aCrossTcd[$sKey]['pref_crs']==$aData['pref'] &&
	          			"'".$aCrossTcd[$sKey]['code_crs']."'"==$aData['sCode'] &&
	          			$aVagItem['pref']!=$aCrossTcd[$sKey]['pref_crs']) {
	          				$aVirtualItem = $aCrossTcd[$sKey];
	          				$aVirtualItem['pref_crs'] = $aVagItem['pref'];
	          				$aVirtualItem['item_code_crs'] = $aVagItem['pref'].'_'.$aVirtualItem['code_crs'];
	          				$aVirtualItems[] = $aVirtualItem;
	          			}
	          		}
	          	}
	          }
	          elseif ($aCatCheckVirtual['id_cat_virtual']!=0) {
	          	$aVag=Db::getAll("Select c.* from cat c
		    				inner join cat c2 on c2.id = c.id_cat_virtual and c2.visible=1
		    				where c.visible=1 and c.id_cat_virtual=".$aCatCheckVirtual['id_cat_virtual'].
	          				" union select c.* from cat c 
	          				where c.visible=1 and c.is_cat_virtual=1 and c.id=".$aCatCheckVirtual['id_cat_virtual']);
	          	if ($aVag) {
	          		foreach ($aVag as $sKeyP => $aVagItem) {
	          			// add virtual info stop
	          			$aCrossForStop[]=$aVagItem['pref']."_".$aValue['code'];
	          			if (!$aVirtPrefAssoc[$aCatCheckVirtual['pref']][$aVagItem['pref']])
	          				$aVirtPrefAssoc[$aCatCheckVirtual['pref']][$aVagItem['pref']] = $aVagItem['pref'];
						if (!$aVirtPrefAssoc[$aVagItem['pref']][$aCatCheckVirtual['pref']])
	          				$aVirtPrefAssoc[$aVagItem['pref']][$aCatCheckVirtual['pref']] = $aCatCheckVirtual['pref'];
	          			
	          			if ($aCrossTcd[$sKey]['pref']==$aData['pref'] &&
	          			"'".$aCrossTcd[$sKey]['code']."'"==$aData['sCode'] &&
	          			$aVagItem['pref']!=$aCrossTcd[$sKey]['pref']) {
	          				$aVirtualItem = $aCrossTcd[$sKey];
	          				$aVirtualItem['pref'] = $aVagItem['pref'];
	          				$aVirtualItem['item_code'] = $aVagItem['pref'].'_'.$aVirtualItem['code'];
	          				$aVirtualItems[] = $aVirtualItem;
	          			}elseif ($aCrossTcd[$sKey]['pref_crs']==$aData['pref'] &&
	          			"'".$aCrossTcd[$sKey]['code_crs']."'"==$aData['sCode'] &&
	          			$aVagItem['pref']!=$aCrossTcd[$sKey]['pref_crs']) {
	          				$aVirtualItem = $aCrossTcd[$sKey];
	          				$aVirtualItem['pref_crs'] = $aVagItem['pref'];
	          				$aVirtualItem['item_code_crs'] = $aVagItem['pref'].'_'.$aVirtualItem['code_crs'];
	          				$aVirtualItems[] = $aVirtualItem;
	          			}
	          		}
	          	}
	          }
	       }
	       
	       if ($aVirtualItems)
	       	$aCrossTcd = array_merge($aCrossTcd,$aVirtualItems);
	       /*if($aCrossForStop) {
	           $aCrossForStop=array_unique($aCrossForStop);
	           $aStop=Db::GetAll("select concat(pref,'_',code) as item_code, concat(pref_crs,'_',code_crs) as item_code_crs from cat_cross_stop where concat(pref,'_',code) in ('".implode("','", $aCrossForStop)."')");
	           if($aStop) foreach ($aCrossTcd as $sKey => $aValueCross) {
	               foreach ($aStop as $aValueStop) {
	                   if($aValueCross['item_code']==$aValueStop['item_code'] && $aValueCross['item_code_crs']==$aValueStop['item_code_crs'])
        	           unset($aCrossTcd[$sKey]);
	               }
	           }
	       }*/
	    }
	    else { // добавить стоп кроссы по входящему коду
	        if ($aCodes && $aCat)
	        foreach ($aCodes as $sVal) {
	            $aCrossForStop[]=$aCat['pref']."_".str_replace("'","",$sVal);
	        }
	    }
	    sort($aCrossTcd);
	    $aCrossCat=Db::GetAll($sSql);
	    if(!$aCrossTcd) $aCrossTcd=array();
	    if(!$aCrossCat) $aCrossCat=array();
	    
	    $aCatVirt=Db::GetAssoc("SELECT `pref`,`id`,`id_cat_virtual` FROM `cat` WHERE `id_cat_virtual`!=0 OR `is_cat_virtual`!=0");
	    $aCatVirtIds=Db::GetAssoc("SELECT `id`,`pref`,`id_cat_virtual` FROM `cat` WHERE `id_cat_virtual`!=0 OR `is_cat_virtual`!=0");
	    foreach ($aCrossCat as $iKeie=>$aCrossCatItem){
	        // если в запросе ItemCode, лишние кроссы текдока убрать
	        if ($aData['aItemCode'] && !$aData['aItemCode'][$aCrossCatItem['item_code']]) {
	            unset($aCrossCat[$iKeie]);
	            continue;
	        }
	        $spref1 = explode("_", $aCrossCatItem['item_code_crs']);
	        if ($aCatVirt[$spref1[0]]!=array()){
	            
	            if($aCatVirt[$spref1[0]]['id_cat_virtual']>0)
	                $aCrossCat[]=array(
	                    'item_code_crs' => $aCatVirtIds[$aCatVirt[$spref1[0]]['id_cat_virtual']]['pref']."_".$spref1[1],
	                    'item_code' =>$aCrossCatItem['item_code']
	                    
	                );
	        }
	        $spref2 = explode("_", $aCrossCatItem['item_code']);
	        if ($aCatVirt[$spref2[0]]!=array()){
	            
	            if($aCatVirt[$spref2[0]]['id_cat_virtual']>0)
	                $aCrossCat[]=array(
	                    'item_code_crs' => $aCatVirtIds[$aCatVirt[$spref2[0]]['id_cat_virtual']]['pref']."_".$spref2[1],
	                    'item_code' =>$aCrossCatItem['item_code']
	                     
	                );
	        }
	    }
	    
	    if($aCrossTcd) foreach ($aCrossTcd as $iKeie=>$aCrossCatItem){
	        $spref1 = explode("_", $aCrossCatItem['item_code_crs']);
	        if ($aCatVirt[$spref1[0]]!=array()){
	             
	            if($aCatVirt[$spref1[0]]['id_cat_virtual']>0)
	                $aCrossTcd[]=array(
	                    'item_code_crs' => $aCatVirtIds[$aCatVirt[$spref1[0]]['id_cat_virtual']]['pref']."_".$spref1[1],
	                    'item_code' =>$aCrossCatItem['item_code']
	                     
	                );
	        }
	        $spref2 = explode("_", $aCrossCatItem['item_code']);
	        if ($aCatVirt[$spref2[0]]!=array()){
	             
	            if($aCatVirt[$spref2[0]]['id_cat_virtual']>0)
	                $aCrossTcd[]=array(
	                    'item_code_crs' => $aCatVirtIds[$aCatVirt[$spref2[0]]['id_cat_virtual']]['pref']."_".$spref2[1],
	                    'item_code' =>$aCrossCatItem['item_code']
	    
	                );
	        }
	    }
	    
		/* get original in sql ind=3,4
	    if(Base::GetConstant('price:show_oe','1')==1 && $aData['sCode']) {
	    	$sRequestCode = mb_strtoupper(Base::$aRequest['code'],'UTF-8');
	    	$sSql="select
    		    oe_code,
    		    code,
    		    oe_brand,
    		    brand
            from ".DB_OCAT."cat_alt_original as c
            where 1=1 and (oe_code like ".$aData['sCode']." or code like ".$aData['sCode']." ) ";
	    	$aOriginals=TecdocDb::GetAll($sSql);
	    	if($aOriginals) $aCatAssoc=Db::GetAssoc("select id_to f,pref from cat where id_to f >0");
	    	// cut other brand original
	    	$aPrefOriginals = array();
	    	if ($aData['pref']) {
	    		$sItemCode = $aData['pref'].'_'.$sRequestCode;
	    		$iIdTof = Db::GetOne("select id_to f from cat where pref='".$aData['pref']."'");
	    		if ($iIdTof) {
	    			if($aOriginals) foreach ($aOriginals as $aValue) {
	    				if ($aValue['code']==$sRequestCode && $aValue['brand']==$iIdTof)
	    					$aPrefOriginals[] = $aValue;
	    				elseif ($aValue['oe_code']==$sRequestCode && $aValue['oe_brand']==$iIdTof)
	    				$aPrefOriginals[] = $aValue;
	    			}
	    			$aOriginals = $aPrefOriginals;
	    		}
	    	}
	    
	    	$aItemCodeOriginal=array();
	    	if($aOriginals) foreach ($aOriginals as $skeyOriginal => $aValueOriginal) {
	    		// cut other brand original without id_to f
	    		if ($sItemCode &&
	    		$aCatAssoc[$aValueOriginal['brand']]."_".$aValueOriginal['code'] != $sItemCode &&
	    		$aCatAssoc[$aValueOriginal['oe_brand']]."_".$aValueOriginal['oe_code'] != $sItemCode
	    		)
	    		continue;
	    
	    		 
	    		$aTmp=array();
	    		$aTmp['pref']=$aCatAssoc[$aValueOriginal['brand']];
	    		$aTmp['pref_crs']=$aCatAssoc[$aValueOriginal['oe_brand']];
	    
	    		if(!$aTmp['pref'] || !$aTmp['pref_crs']) continue;
	    
	    		$aTmp['item_code']=$aCatAssoc[$aValueOriginal['brand']]."_".$aValueOriginal['code'];
	    		$aTmp['item_code_crs']=$aCatAssoc[$aValueOriginal['oe_brand']]."_".$aValueOriginal['oe_code'];
	    		
	    		// exclude cross stop
	    		if ($aCrossStop[$aTmp['item_code']] &&
	    			$aCrossStop[$aTmp['item_code']] == $aTmp['item_code_crs']) {
	    			continue;
	    		}
	    		
	    		if ($aTmp['pref'] && $aTmp['pref_crs'])
	    			$aItemCodeOriginal[]=$aTmp;
	    
	    
	    		$aTmp=array();
	    		$aTmp['pref_crs']=$aCatAssoc[$aValueOriginal['brand']];
	    		$aTmp['pref']=$aCatAssoc[$aValueOriginal['oe_brand']];
	    
	    		$aTmp['item_code_crs']=$aCatAssoc[$aValueOriginal['brand']]."_".$aValueOriginal['code'];
	    		$aTmp['item_code']=$aCatAssoc[$aValueOriginal['oe_brand']]."_".$aValueOriginal['oe_code'];
	    		if ($aTmp['pref'] && $aTmp['pref_crs'])
	    			$aItemCodeOriginal[]=$aTmp;
	    	}
	    
	    	if(!$aItemCodeOriginal) $aItemCodeOriginal=array();
	    	if(!$aCrossTcd) $aCrossTcd=array();
	    	$aCrossTcd=array_merge($aCrossTcd,$aItemCodeOriginal);
	    }*/
	    // Get OE numbers end
	    
	    if($aCrossForStop && $aCrossTcd) {
			$aCrossForStop=array_unique($aCrossForStop);
	        $aStop=Db::GetAll("select concat(pref,'_',code) as item_code, concat(pref_crs,'_',code_crs) as item_code_crs from cat_cross_stop where concat(pref,'_',code) in ('".implode("','", $aCrossForStop)."')");
	        if($aStop) foreach ($aCrossTcd as $sKey => $aValueCross) {
	               foreach ($aStop as $aValueStop) {
	               		if(($aValueCross['item_code']==$aValueStop['item_code'] && $aValueCross['item_code_crs']==$aValueStop['item_code_crs'])
	               		    || ($aValueCross['item_code']==$aValueStop['item_code_crs'] && $aValueCross['item_code_crs']==$aValueStop['item_code'])) {
	               			unset($aCrossTcd[$sKey]);
	               			continue;
	               		}
	               	   	list($sPrefStop,$sCodeStop) = explode('_',$aValueStop['item_code']);
	               	   	if ($aVirtPrefAssoc[$sPrefStop]) {
	               	   		foreach ($aVirtPrefAssoc[$sPrefStop] as $sPrefItemVirt) {
	               	   			$sVirtCode = $sPrefItemVirt.'_'.$sCodeStop;
	               	   			if($aValueCross['item_code']==$sVirtCode && $aValueCross['item_code_crs']==$aValueStop['item_code_crs']) {
	               	   				unset($aCrossTcd[$sKey]);
	               	   				break;
	               	   			}
	               	   		}
	               	   	} 
	               }
	        }
	        sort($aCrossTcd);
	    }
	    
	    if(!$aCrossTcd) $aCrossTcd=array();
	    if(!$aCrossCat) $aCrossCat=array();

	    return array_merge($aCrossTcd,$aCrossCat);
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetCriterias($aData)
	{
	    $sParamsHash=md5(serialize($aData));
	    if(isset(Base::$oTecdocDb->realtimeCache['GetCriterias'][$sParamsHash])) {
	        return Base::$oTecdocDb->realtimeCache['GetCriterias'][$sParamsHash];
	    }
	    
		/*if (Base::$aRequest['locale'] && Base::$aRequest['locale']!='ru' )
			return TecdocDb::GetCriteriasDBTOF($aData,Base::$aRequest['locale']);*/
		
	    if ($aData['where'])
	    {
	        $sWhere=$aData['where'];
	    }
	    
	    if(!$aData['aId'] || (count($aData['aId']) == 1 && $aData['aId'][0] == 0)) $aData['aId']=array();
	    $inId = "'".implode("','",$aData['aId'])."'";
	    $inIdOpti = "'".TecdocDb::GetOne("select group_concat(ID_art SEPARATOR '\',\'') from ".DB_OCAT."cat_alt_articles
		where ID_src>0 and ID_src in(".$inId.")")."'";
	    
	    if(!$aData['aIdCatPart']) $aData['aIdCatPart']=array();
	    $inIdCatPart = "'".implode("','",$aData['aIdCatPart'])."'";
	    
	    
	    if ($inIdOpti != "''") {
	        $sWhere.=" and art.ID_art in(".$inIdOpti.")";
	        if ($aData['id_model_detail']) {
	            $sWhere1.=" and art.ID_art in(".$inIdOpti.") and t.ID_src in (".$aData['id_model_detail'].") ";
	        } else {
	            $sWhere1.=" and 0=1 ";
	        }
	    } else {
	        $sWhere.=" and 0=1 ";
	        $sWhere1.=" and 0=1 ";
	    }
	    
	    if ($inIdCatPart != "''")
	    {
	        $sWhere2.=" and id_cat_part in(".$inIdCatPart.")";
	    }
	    else
	    {
	        $sWhere2.=" and 0=1 ";
	    }
	    
	    $sSelectKrit = ", name as krit_name
                        , code as krit_value";
	    if ($aData['type_']=="all") {
	        $sField.=" distinct  krit_name, krit_value";
	        if(MultiLanguage::IsLocale()){
	            $sFieldLocal=" distinct krit_name_".Language::$sLocale." as krit_name, krit_value_".Language::$sLocale." as krit_value";
	            $sSelectKrit = ", name_".Language::$sLocale." as krit_name_".Language::$sLocale."
	                            , code_".Language::$sLocale." as krit_value_".Language::$sLocale."";
	        }else{
	            $sFieldLocal=$sField;
	        }
	    } elseif ($aData['type_']=="all_edit") {
	        $sField.=" krit_name, krit_value, id_cat_info";
            $sFieldLocal.=" krit_name, krit_name_ua, krit_value, krit_value_ua, id_cat_info";
            $sSelectKrit.= ", name_ua as krit_name_ua
                            , code_ua as krit_value_ua";
	    } else {
	        $sField.=" group_concat(' ', krit_name, ' ', krit_value) as criteria ";
	        $sGroup.=" group by crt.acr_art_id";
	    
	        if ($aData['type_']=="only_la") $sWhere.=" and 0=1 ";
	    }
	    
	    $sSql="
        	select
        	".$sField."
        	from (
        	select art.ID_src as acr_art_id
    		, a.Name as krit_name
    		, a.Value as krit_value
    		, 2 flag
    		, lac.Sort sort
    		, 0 as id_cat_info
               from ".DB_OCAT."cat_alt_additions a
        		join ".DB_OCAT."cat_alt_link_art_inf lac on lac.ID_add=a.ID_add
        		join ".DB_OCAT."cat_alt_articles art on art.ID_art=lac.ID_art
        where 1=1
          ".$sWhere."
          union all
        select art.ID_src as acr_art_id
        		, a.Name as krit_name
        		, a.Value as krit_value
        	   , 1 flag
        	   , ltc.Sort sort
        	   , 0 as id_cat_info
           from ".DB_OCAT."cat_alt_additions a
        		join ".DB_OCAT."cat_alt_link_typ_inf ltc on ltc.ID_add=a.ID_add
        		join ".DB_OCAT."cat_alt_articles art on art.ID_art=ltc.ID_art
        		join ".DB_OCAT."cat_alt_types t on t.ID_typ=ltc.ID_typ
        	where  1=1
          ".$sWhere1.") as crt ".$sGroup;
	    
	    $sSqlCat="select
        	".$sFieldLocal." from (
	        select id_cat_part as acr_art_id
		".$sSelectKrit."
		, 2 as flag
		, 0 as sort
		, id as id_cat_info
           from cat_info
        	where 1=1
         ".$sWhere2."
        ) as crt
        ".$sGroup;
	    
	    $aCritTcd=TecdocDb::GetAll($sSql);
	    $aCritCat=Db::GetAll($sSqlCat);
	    
	    if(!$aCritCat) $aCritCat=array();
	    if(!$aCritTcd) $aCritTcd=array();
	    
	    $aResult = array_merge($aCritTcd,$aCritCat);
	    
	    Base::$oTecdocDb->realtimeCache['GetCriterias'][$sParamsHash]=$aResult;
	    return $aResult;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetOriginals($aData,$aCats=false)
	{
	    if ($aData['sCode']) {
	        $sWhere.=" and ctal.arl_search_number in (".$aData['sCode'].") and ctal.arl_search_number<>'' and ctal.arl_kind=1 ";
	        $sJoin.=" inner join ".DB_TOF."tof__art_lookup as ctal on ctal.arl_art_id = cta.art_id ";
	        $sWhere1.=" and cc.code in (".$aData['sCode'].")";
	    } elseif ($aData['art_id'] || ($aData['pref'] && $aData['code']) ) {
	        if ($aData['art_id']) {
	            $sWhere.=" and a.ID_src in (".$aData['art_id'].")";
	        } else {
	            $sWhere.=" and 0=1";
	        }
	        if ($aData['pref'] && $aData['code']) {
	            $sWhere1.=" and cc.code='".$aData['code']."' and cc.pref='".$aData['pref']."'";
	        } else {
	            $sWhere1.=" and 0=1";
	        }
	    } else {
	        $sWhere.=" and 0=1";
	    }
	    if ($aData['limit']) {
	        $sLimit=" limit ".$aData['limit'];
	    }
	    
	    $sSql="
        	select  a.ID_src as art_id, c.oe_code as number, s.ID_src as id_mfa, 1 as is_original
        	from ".DB_OCAT."cat_alt_original as c
        	INNER JOIN ".DB_OCAT."cat_alt_articles a on a.ID_art=c.ID_art
        	INNER JOIN ".DB_OCAT."cat_alt_manufacturer as s on c.oe_Brand=s.id_src
        	 ".$sJoin."
        	 where 1=1 ".$sWhere.$sLimit;
	    
	    $aOriginals=TecdocDb::GetAll($sSql);
	    if($aOriginals) foreach ($aOriginals as $sKey => $aValue) {
	       $aCats=Db::GetAssoc("select c.id_mfa,c.id,c.pref,c.title,c.image,c.name from cat as c where c.visible=1 and c.is_brand=1 ");
	       if ($aCats[$aValue['id_mfa']]) {
    	       $aOriginals[$sKey]['name']=$aCats[$aValue['id_mfa']]['title'];
    	       $aOriginals[$sKey]['item_code']=$aCats[$aValue['id_mfa']]['pref']."_".$aValue['number'];
	       }
	       else 
	           unset($aOriginals[$sKey]);
	    }
	    
	    if(!$sWhere1) $sWhere1=" and 0=1";
	    
	    $sSqlSite=" 
        	Select -1 as art_id, cc.code_crs as number, c.title as name
         	from cat_cross as cc
        	inner join cat as c on cc.pref_crs = c.pref and c.is_brand=1
        	where 1=1 "
        	.$sWhere1;
	    $aOriginalsSite=Db::GetAll($sSqlSite);
	    
	    if(!$aOriginals) $aOriginals=array();
	    if(!$aOriginalsSite) $aOriginalsSite=array();
	    $aOriginals=array_merge($aOriginals,$aOriginalsSite);
	    
	    return $aOriginals;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetIdMakeByIdModel($iIdModel)
	{
	    $iIdTof=TecdocDb::GetOne("select man.ID_src
           from ".DB_OCAT."cat_alt_manufacturer man 
           inner join ".DB_OCAT."cat_alt_models m on m.ID_mfa=man.ID_mfa and m.ID_src in (".$iIdModel.")
	       ");
	    if($iIdTof) $iIdMake=Db::GetOne("select id from cat where id_mfa='".$iIdTof."' ");
	    
	    return $iIdMake;
	}
	//-----------------------------------------------------------------------------------------------
	public static function AssocArtIdItemCode($aData)
	{
	    $aData=TecdocDb::GetAssoc("select a.ID_src art_id_key, UPPER(a.Search) as item_code, s.ID_src as id_sup
			FROM ".DB_OCAT."cat_alt_articles a
			join ".DB_OCAT."cat_alt_link_str_art lsa on lsa.ID_tree in ('".implode("','",$aData)."') and lsa.ID_art=a.ID_art
			/*join ".DB_OCAT."cat_alt_link_art_inf as lai on lai.ID_art=a.ID_art
			join ".DB_OCAT."cat_alt_link_str_grp as lsg on lsg.ID_tree in ('".implode("','",$aData)."') and lai.id_grp=lsg.id_grp*/
			join ".DB_OCAT."cat_alt_suppliers s on a.ID_sup=s.ID_sup
		");
	    
	    if($aData) {
	        $aCat=array();
	        foreach ($aData as $aValue) $aCat[$aValue['id_sup']]=$aValue['id_sup'];
	        
	        $aBrands=Db::GetAssoc("select id_sup,pref from cat where id_sup in ('".implode("','", $aCat)."') ");
	        if($aBrands) foreach ($aData as $sKey => $aValue) {
	            if(!$aBrands[$aValue['id_sup']]) {
	                unset($aData[$sKey]);
	                continue;
	            }
	            $aData[$sKey]=$aBrands[$aValue['id_sup']]."_".$aValue['item_code'];
	        }
	    }
	    
	    return $aData;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetCriteriasDBTOF($aData,$sLang='en')
	{
		switch ($sLang) {
			case 'en':$iIdLang=4;break;
		}			
		
		if ($aData['where'])
		{
			$sWhere=$aData['where'];
		}
		
		if(!$aData['aId']) $aData['aId']=array();
		$inId = "'".implode("','",$aData['aId'])."'";
		
		if(!$aData['aIdCatPart']) $aData['aIdCatPart']=array();
		$inIdCatPart = "'".implode("','",$aData['aIdCatPart'])."'";
		
		
		if ($inId) {
			$sWhere.=" and acr_art_id in(".$inId.")";
			if ($aData['id_model_detail']) {
				$sWhere1.=" and la_art_id in(".$inId.") and lat_typ_id=".$aData['id_model_detail'];
			} else {
				$sWhere1.=" and 0=1 ";
			}
		} else {
			$sWhere.=" and 0=1 ";
			$sWhere1.=" and 0=1 ";
		}
		
		if ($inIdCatPart)
		{
			$sWhere2.=" and id_cat_part in(".$inIdCatPart.")";
		}
		else
		{
			$sWhere2.=" and 0=1 ";
		}
		
		if ($aData['type_']=="all") {
			$sField.=" distinct  krit_name, krit_value";
		} elseif ($aData['type_']=="all_edit") {
			$sField.=" krit_name, krit_value, id_cat_info";
		} else {
			$sField.=" group_concat(' ', krit_name, ' ', krit_value) as criteria ";
			$sGroup.=" group by acr_art_id";
		
			if ($aData['type_']=="only_la") $sWhere.=" and 0=1 ";
		}
		
		$sSql="
			select
			".$sField."
			from (
			select acr_art_id
				, des_texts.tex_text as krit_name
				, ifnull(des_texts2.tex_text, acr_value) as krit_value
				, 2 flag
				, acr_sort sort
				, acr_kv_des_id kv_des_id
				, acr_cri_id cri_id
				, acr_ga_id ga_id
				, 0 as id_cat_info
		   from
				".DB_TOF."tof__article_criteria
			left join ".DB_TOF."tof__designations as designations2 on designations2.des_id = acr_kv_des_id
			left join ".DB_TOF."tof__des_texts as des_texts2 on des_texts2.tex_id = designations2.des_tex_id
			inner join ".DB_TOF."tof__criteria on cri_id = acr_cri_id
			inner join ".DB_TOF."tof__designations as designations on designations.des_id = cri_des_id
			inner join ".DB_TOF."tof__des_texts as des_texts on des_texts.tex_id = designations.des_tex_id
		where 1=1 and (designations.des_lng_id is null or designations.des_lng_id = ".$iIdLang.") and 
				(designations2.des_lng_id is null or designations2.des_lng_id = ".$iIdLang.")
		  ".$sWhere."
		  union all
		select la_art_id
			   , trim(cri_tex.tex_text) krit_name
			   , coalesce(lac_value, lac_tex.tex_text) krit_value
			   , 1 flag
			   , lac_sort sort
			   , lac_kv_des_id kv_des_id
			   , lac_cri_id cri_id
			   , la_ga_id ga_id
			   , 0 as id_cat_info
			from  ".DB_TOF."tof__la_criteria
			join ".DB_TOF."tof__link_art on la_id = lac_la_id
			join ".DB_TOF."tof__link_la_typ on lat_la_id =la_id and lat_ga_id=la_ga_id
			join ".DB_TOF."tof__criteria on cri_id = lac_cri_id
			join ".DB_TOF."tof__designations cri_des on cri_des.des_id = cri_des_id and cri_des.des_lng_id = ".$iIdLang."
			join ".DB_TOF."tof__des_texts cri_tex on cri_tex.tex_id = cri_des.des_tex_id
			left join ".DB_TOF."tof__designations lac_des on lac_des.des_id = ifnull(lac_kv_des_id,-1) and lac_des.des_lng_id = ".$iIdLang."
			left join ".DB_TOF."tof__des_texts lac_tex on lac_tex.tex_id = lac_des.des_tex_id
			where  1=1
		  ".$sWhere1.") as crt ".$sGroup;
		
		$sSqlCat="select
        	".$sField." from (
	        select id_cat_part as acr_art_id
		, name as krit_name
		, code as krit_value
		, 2 as flag
		, 0 as sort
		, id as id_cat_info
           from cat_info
        	where 1=1
         ".$sWhere2."
        ) as crt
        ".$sGroup;
		 
		$aCritTcd=TecdocDb::GetAll($sSql);
		$aCritCat=Db::GetAll($sSqlCat);
		 
		if(!$aCritCat) $aCritCat=array();
		if(!$aCritTcd) $aCritTcd=array();
		 
		return array_merge($aCritTcd,$aCritCat);
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetModelDetailsDBTOF($aData,$aCat=false,$sLang='en')
	{
		$iCountryId = 187;
		switch ($sLang) {
			case 'en':$iIdLang=4;break;
		}
		
		if ($aData['id_model'])
		{
			$sWhere.="and typ_mod_id = ".$aData['id_model'];
		}
		elseif ($aData["type_number"])
		{
			$sJoin=" inner join ".DB_TOF."tof__type_numbers as ttn on ".DB_TOF."tof__types.typ_id = ttn.tyn_typ_id ";
			$sWhere.=" and ttn.tyn_kind = 2  AND ttn.tyn_search_text like '".$aData["type_number"]."%' ";
		}
		elseif ($aData["code"] && $aData["art_id"])
		{
			$sJoin=" inner join ".DB_TOF."tof__link_la_typ_view on typ_id = lat_typ_id";
			$sWhere.=" and art_article_nr = '".$aData["code"]."' and art_id='".$aData["art_id"]."'";
		}
		else
		{
			$sWhere="and 1=0";
		}
		
		if ($aData['id_model_detail'])
		{
			$sWhere.=" and typ_id = ".$aData['id_model_detail'];
		}
		
		if ($aData['year'])
		{
			$sWhere.=" and ifnull(tyc_pcon_start, typ_pcon_start)<=".$aData['year']."01"
					." and ifnull(ifnull(tyc_pcon_end, typ_pcon_end),999999)>=".$aData['year']."01";
		}
		
		
		if ($aData['volume'])
		{
			$dVolume=str_replace(",",".",$aData['volume']);
			if ($dVolume<=100) {
				$sWhere.=" and round(ifnull(tyc_ccm, typ_ccm),-2)="
						.($dVolume*1000);
			} else {
				$sWhere.=" and round(ifnull(tyc_ccm, typ_ccm),-2)="
						.round($dVolume,-2);
			}
		
			//$sWhere.=" and substring(ifnull(tyc_ccm, typ_ccm),1,2)="
			//.substr(str_replace(array(",","."),"",$aData['volume']),0,2);
		}
		
		if ($aData['fuel'])
		{
			$sWhere.=" and ifnull(tyc_kv_engine_des_id, typ_kv_engine_des_id)=".$aData['fuel'];
		}
		
		
		$sSql="select ifnull(lng_tex.tex_text, uni_tex.tex_text) name,
         ifnull(tyc_pcon_start, typ_pcon_start) pcon_start,
         ifnull(tyc_pcon_end, typ_pcon_end) pcon_end,
         ifnull(tyc_kw_from, typ_kw_from) kw_from,
         ifnull(tyc_hp_from, typ_hp_from) hp_from,
         ifnull(tyc_ccm, typ_ccm) ccm,
         ifnull(tyc_bod_tex.tex_text, ifnull(bod_tex.tex_text, ifnull(tyc_mod_tex.tex_text, mod_tex.tex_text))) body,
         ifnull(tyc_axl_tex.tex_text, axl_tex.tex_text) axis,
         ifnull(tyc_max_weight, typ_max_weight) max_weight,
         ifnull(tyc_kv_body_des_id, ifnull(typ_kv_body_des_id, ifnull(tyc_kv_model_des_id , typ_kv_model_des_id ))) body_des_id,
         ifnull(tyc_kv_engine_des_id, typ_kv_engine_des_id) engine_des_id,
         ifnull(tyc_kv_axle_des_id, typ_kv_axle_des_id) axis_des_id,
         typ_mod_id mod_id,
         typ_id typ_id,
         '              ' button,
         substring(typ_la_ctm,".$iCountryId.",1) flag_id,
         mod_pc,
         mod_cv,
         mod_mfa_id,
		 typ_kv_fuel_des_id,
         typ_sort
         , substr(ifnull(tyc_pcon_start, typ_pcon_start),5,2) as month_start
         , substr(ifnull(tyc_pcon_start, typ_pcon_start),1,4) as year_start
		, substr(ifnull(tyc_pcon_end, typ_pcon_end),5,2) as month_end
		, substr(ifnull(tyc_pcon_end, typ_pcon_end),1,4) as year_end
		/*, c.id as id_make*/
		, typ_mod_id as id_model
		, typ_id as id_model_detail
	    from ".DB_TOF."tof__types
	    inner join ".DB_TOF."tof__models on typ_mod_id = mod_id
	    /*inner join cat as c on mod_mfa_id = c.id_to f*/
	      ".$sJoin."
	    left outer join ".DB_TOF."tof__designations model_des
	                 on ".DB_TOF."tof__types.typ_kv_model_des_id = model_des.des_id
	                and model_des.des_lng_id = ".$iIdLang."
	    left outer join ".DB_TOF."tof__des_texts mod_tex
	                 on model_des.des_tex_id = mod_tex.tex_id
	    left outer join ".DB_TOF."tof__designations axle_des
	                 on ".DB_TOF."tof__types.typ_kv_axle_des_id = axle_des.des_id
	                and axle_des.des_lng_id = ".$iIdLang."
	    left outer join ".DB_TOF."tof__des_texts axl_tex
	                 on axle_des.des_tex_id = axl_tex.tex_id
	    left outer join ".DB_TOF."tof__designations body_des
	                 on ".DB_TOF."tof__types.typ_kv_body_des_id = body_des.des_id
	                and body_des.des_lng_id = ".$iIdLang."
	    left outer join ".DB_TOF."tof__des_texts bod_tex
	                 on body_des.des_tex_id = bod_tex.tex_id
	    left outer join ".DB_TOF."tof__country_designations lng_des
	                 on typ_mmt_cds_id = lng_des.cds_id
	                and lng_des.cds_lng_id = ".$iIdLang."
	                and substring(lng_des.cds_ctm,".$iCountryId.",1) = 1
	    left outer join ".DB_TOF."tof__des_texts lng_tex
	                 on lng_des.cds_tex_id = lng_tex.tex_id
	    left outer join ".DB_TOF."tof__country_designations uni_des
	                 on typ_mmt_cds_id = uni_des.cds_id
	                and uni_des.cds_lng_id = 255
	                and substring(uni_des.cds_ctm,".$iCountryId.",1) = 1
	    left outer join ".DB_TOF."tof__des_texts uni_tex
	                 on uni_des.cds_tex_id = uni_tex.tex_id
	    left outer join ".DB_TOF."tof__typ_country_specifics
	                 on typ_id = tyc_typ_id
	                and tyc_cou_id = ".$iCountryId."
	    left outer join ".DB_TOF."tof__designations tyc_model_des
	                 on tyc_kv_model_des_id = tyc_model_des.des_id
	                and tyc_model_des.des_lng_id = ".$iIdLang."
	    left outer join ".DB_TOF."tof__des_texts tyc_mod_tex
	                 on tyc_model_des.des_tex_id = tyc_mod_tex.tex_id
	    left outer join ".DB_TOF."tof__designations tyc_axle_des
	                 on tyc_kv_axle_des_id = tyc_axle_des.des_id
	                and tyc_axle_des.des_lng_id = ".$iIdLang."
	    left outer join ".DB_TOF."tof__des_texts tyc_axl_tex
	                 on tyc_axle_des.des_tex_id = tyc_axl_tex.tex_id
	    left outer join ".DB_TOF."tof__designations tyc_body_des
	                 on tyc_kv_body_des_id = tyc_body_des.des_id
	                and tyc_body_des.des_lng_id = ".$iIdLang."
	    left outer join ".DB_TOF."tof__des_texts tyc_bod_tex
	                 on tyc_body_des.des_tex_id = tyc_bod_tex.tex_id
			
	    left outer join ".DB_TOF."tof__country_designations short_des
		              on typ_cds_id = short_des.cds_id
			          and substring(short_des.cds_ctm,".$iCountryId.",1) = 1
			          and short_des.cds_lng_id = ".$iIdLang."
		 left outer join ".DB_TOF."tof__des_texts short_tex
		              on short_tex.tex_id = short_des.cds_tex_id
			
	   where 1=1 and substring(typ_ctm,".$iCountryId.",1) = 1
	    ".$sWhere." order by ifnull(lng_tex.tex_text, uni_tex.tex_text)";
		
		if(!$aCat) $aCat=Db::GetAssoc("select c.id_mfa,c.id from cat as c where c.visible=1");
		$aCatTitles=Db::GetAssoc("select c.id_mfa,c.title from cat as c where c.visible=1");
		$aCatName=Db::GetAssoc("select c.id_mfa,c.id,c.name from cat as c where c.visible=1");		
		 
		$aTecdocModelDetail=TecdocDb::GetAll($sSql);
		if($aTecdocModelDetail) foreach ($aTecdocModelDetail as $sKey => $aValue) {
			$aTecdocModelDetail[$sKey]['id_make']=$aCat[$aValue['mod_mfa_id']];
			$aTecdocModelDetail[$sKey]['name_model']=$aCatTitles[$aValue['mod_mfa_id']].' '.$aValue['cat_alt_models_name'].' '.$aValue['Name'];
			$aTecdocModelDetail[$sKey]['cat']=$aCatName[$aValue['mod_mfa_id']]['name'];
		}
		return $aTecdocModelDetail;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetApplicabilityDBTOF($aData,$sLang='en')
	{
		return TecdocDb::GetModelDetailsDBTOF($aData,false,$sLang);
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetModificationAssoc($aData) {
	    $sParamsHash=md5(serialize($aData));
	    if(isset(Base::$oTecdocDb->realtimeCache['GetModificationAssoc'][$sParamsHash])) {
	        return Base::$oTecdocDb->realtimeCache['GetModificationAssoc'][$sParamsHash];
	    }
	    
		$aResult = array();
		
		if (!$aData['id_model'])
			return array();
		
		$aModel=Db::GetAssoc("select * from cat_model as cm where cm.visible=1 and tof_mod_id in (".$aData['id_model'].") ");
		if (!$aModel)
			return array();
				
		$sWhere.="and cat_alt_models.ID_src = ".$aData['id_model'];
				
		$sSql="select cat_alt_types.*
         , substr(cat_alt_types.DateStart,5,2) as month_start
         , substr(cat_alt_types.DateStart,1,4) as year_start
		, substr(cat_alt_types.DateEnd,5,2) as month_end
		, substr(cat_alt_types.DateEnd,1,4) as year_end
		, cat_alt_models.ID_src as id_model
		, cat_alt_types.ID_src as id_model_detail
		, cat_alt_types.Description as name
		, LEFT(KwHp, LOCATE('/', KwHp)-1) kw_from
		, SUBSTR(KwHp, LOCATE('/', KwHp)+1) hp_from
		, CCM as ccm, Body as body
		, cat_alt_models.ID_src as mod_id
		, cat_alt_types.ID_src as typ_id
		, cat_alt_manufacturer.ID_src as mod_mfa_id
	    FROM ".DB_OCAT."cat_alt_types
	    inner join ".DB_OCAT."cat_alt_models on cat_alt_models.ID_mod = cat_alt_types.ID_mod
		inner join ".DB_OCAT."cat_alt_manufacturer on cat_alt_models.ID_mfa=cat_alt_manufacturer.ID_mfa
	      ".$sJoin."			
	   where 1=1
	    ".$sWhere." order by concat(cat_alt_types.Description,substr(cat_alt_types.DateStart,1,4),substr(cat_alt_types.DateEnd,1,4))";		
		
		$aTecdocModi=TecdocDb::GetAll($sSql);
		if ($aTecdocModi) {
			foreach ($aTecdocModi as $aValue)
				$aResult[$aValue['id_model_detail']] = $aValue['name']." ".$aValue['year_start']."-".$aValue['year_end'];
		}
		
		Base::$oTecdocDb->realtimeCache['GetModificationAssoc'][$sParamsHash]=$aResult;
		
		return $aResult;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetSelectCar($aData) {
	    $sParamsHash=md5(serialize($aData));
	    if(isset(Base::$oTecdocDb->realtimeCache['GetSelectCar'][$sParamsHash])) {
	        return Base::$oTecdocDb->realtimeCache['GetSelectCar'][$sParamsHash];
	    }
	    
	    if ($aData['id_model_detail']) {
	        $sWhere.=" and t.id_src in ('".$aData['id_model_detail']."')";
	    }
	
	    $sSql="select 
                    t.Description as name, 
                    t.id_src as id, 
                    t.fuel, 
                    ifnull( substr(t.DateStart,1,4) ,0)
                    ,m.id_src as id_model
                    ,t.id_src as id_model_detail
	                ,man.ID_src as id_mfa
	             from ".DB_OCAT."cat_alt_types as t 
	             inner join ".DB_OCAT."cat_alt_models m on m.id_mod=t.id_mod
	             inner join ".DB_OCAT."cat_alt_manufacturer man on m.ID_mfa=man.ID_mfa 
	             where 1=1 ".$sWhere."
	    	     order by t.Name
	        ";
	    $aTecdocAuto=TecdocDb::GetRow($sSql);
	    if($aTecdocAuto) {
	        $aCat=Db::GetRow("select * from cat where id_mfa='".$aTecdocAuto['id_mfa']."' ");
	        
	        if($aCat) {
	            $aTecdocAuto['brand']=$aCat['title'];
	            $aTecdocAuto['id_make']=$aCat['id'];
	        }
	        
	        $aTecdocAuto['name']=str_replace($aCat['title'], '', $aTecdocAuto['name']);
	        $aTecdocAuto['full_name']=$aTecdocAuto['name'];
	        
	        if($aModId) {
	            $sSql="select cm.tof_mod_id, 
	                cm.name as model
	                from cat_model as cm
            	    inner join cat_model_group as cmg on cmg.visible=1 and FIND_IN_SET(cm.tof_mod_id, cmg.id_models)
            	    where cm.visible=1
            	    and cm.tof_mod_id ='".$aTecdocAuto['id_mfa']."'
            	    ";
	            $aModelVisible=Db::GetRow($sSql);
	             
	            if($aModelVisible) {
	                $aTecdocAuto=array_merge($aTecdocAuto,$aModelVisible);
	            }
	        }
	    }
	    Base::$oTecdocDb->realtimeCache['GetSelectCar'][$sParamsHash]=$aTecdocAuto;
	    
	    return $aTecdocAuto;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetYears($sModel) {
	    $sParamsHash=md5(serialize($sModel));
	    if(isset(Base::$oTecdocDb->realtimeCache['GetYears'][$sParamsHash])) {
	        return Base::$oTecdocDb->realtimeCache['GetYears'][$sParamsHash];
	    }
	    
	    $sSql="select cm.tof_mod_id,cm.tof_mod_id as id
            from cat_model as cm
    	    inner join cat_model_group as cmg on cmg.visible=1 and FIND_IN_SET(cm.tof_mod_id, cmg.id_models)
    	    where cm.visible=1 and cmg.code like '".$sModel."'
    	    ";
	    $aModelVisible=Db::GetAssoc($sSql);
	    if($aModelVisible) {
	        $sSql = "SELECT distinct
	           ifnull( substr(m.DateStart,4,4) ,0) as year,
	           ifnull( substr(m.DateEnd, 4, 4) , ".date("Y")." ) AS year_end
			FROM ".DB_OCAT."cat_alt_models as m
	        WHERE ifnull( substr(m.DateStart,4,4) ,0)>=1980 and m.ID_src in ('".implode("','", $aModelVisible)."')
	        order by year";
	         
	        $aTecDocYears=TecdocDb::GetAll($sSql);
	    }
	    Base::$oTecdocDb->realtimeCache['GetYears'][$sParamsHash]=$aTecDocYears;
	    return $aTecDocYears;
	}
	//-----------------------------------------------------------------------------------------------
}
?>