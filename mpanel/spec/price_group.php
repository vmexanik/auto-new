<?
/**
 * @author Oleksandr Starovoit
 * @author Mikhail Starovoyt
 * @version 4.5.2
 */
class APriceGroup extends Admin {
	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
		$this->sTableName='price_group';
		$this->sTablePrefix='pg';
		$this->sAction='price_group';
		$this->sSqlPath="Price/Group";
		$this->sWinHead=Language::getDMessage('Price group');
		$this->sPath=Language::GetDMessage('>>Auto catalog >');
		$this->aCheckField=array('code','name');
		//$this->aFCKEditors = array ('description', 'bottom_text');
		$this->Admin();
		$this->sBeforeAddMethod='BeforeAdd';
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
// 	    Base::$tpl->Assign('iAllPrices',Db::GetOne("SELECT COUNT( * ) FROM  price "));
// 	    Base::$tpl->Assign('iNotAssociate',Db::GetOne("SELECT count(*) FROM `price` WHERE is_delayed_associate=1"));
// 	    Base::$tpl->Assign('iNotAssociatePrices',Db::GetOne("select count(*)
//             from price as p
//             left join price_group_assign as pgs on p.item_code=pgs.item_code
//             where pgs.id_price_group=0 or pgs.id_price_group is null
// 	    "));
	    
		$this->PreIndex();
				
		//--------------------
		Base::$sText .= $this->SearchForm ();
		if ($this->aSearch) {
			if (Language::getConstant('mpanel_search_strong',0)) {
				if ($this->aSearch['id'])$this->sSearchSQL .= " and pg.id = '".$this->aSearch['id']."'";
				if ($this->aSearch['code'])	$this->sSearchSQL .= " and pg.code = '".$this->aSearch['code']."'";
				if ($this->aSearch['code_name'])	$this->sSearchSQL .= " and pg.code_name = '".$this->aSearch['code_name']."'";
				if ($this->aSearch['name'])	$this->sSearchSQL .= " and pg.name = '".$this->aSearch['name']."'";
				if ($this->aSearch['level'])	$this->sSearchSQL .= " and pg.level = '".$this->aSearch['level']."'";
				if ($this->aSearch['id_parent'])	$this->sSearchSQL .= " and pg.id_parent = '".$this->aSearch['id_parent']."'";
				if ($this->aSearch['language'])	$this->sSearchSQL .= " and pg.language = '".$this->aSearch['language']."'";
			}
			else {
			    if ($this->aSearch['id'])$this->sSearchSQL .= " and pg.id like '%".$this->aSearch['id']."%'";
			    if ($this->aSearch['code'])	$this->sSearchSQL .= " and pg.code like '%".$this->aSearch['code']."%'";
			    if ($this->aSearch['code_name'])	$this->sSearchSQL .= " and pg.code_name like '%".$this->aSearch['code_name']."%'";
			    if ($this->aSearch['name'])	$this->sSearchSQL .= " and pg.name like '%".$this->aSearch['name']."%'";
			    if ($this->aSearch['level'])	$this->sSearchSQL .= " and pg.level like '%".$this->aSearch['level']."%'";
			    if ($this->aSearch['id_parent'])	$this->sSearchSQL .= " and pg.id_parent like '%".$this->aSearch['id_parent']."%'";
			    if ($this->aSearch['language'])	$this->sSearchSQL .= " and pg.language like '%".$this->aSearch['language']."%'";
			}
			if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and pg.visible='1'";
			if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and pg.visible='0'";
			switch($this->aSearch['visible']){
			    case '1':
			        $this->sSearchSQL.=" and pg.visible>='1'";
			        break;
			    case '0':
			        $this->sSearchSQL.=" and pg.visible>='0'";
			        break;
			    case  '':
			        break;
			}
			if ($this->aSearch['is_main']=='1')	$this->sSearchSQL .= " and pg.is_main='1'";
			if ($this->aSearch['is_main']=='0')	$this->sSearchSQL .= " and pg.is_main='0'";
			switch($this->aSearch['is_main']){
			    case '1':
			        $this->sSearchSQL.=" and pg.is_main>='1'";
			        break;
			    case '0':
			        $this->sSearchSQL.=" and pg.is_main>='0'";
			        break;
			    case  '':
			        break;
			}
			if ($this->aSearch['is_menu']=='1')	$this->sSearchSQL .= " and pg.is_menu='1'";
			if ($this->aSearch['is_menu']=='0')	$this->sSearchSQL .= " and pg.is_menu='0'";
			switch($this->aSearch['is_menu']){
			    case '1':
			        $this->sSearchSQL.=" and pg.is_menu>='1'";
			        break;
			    case '0':
			        $this->sSearchSQL.=" and pg.is_menu>='0'";
			        break;
			    case  '':
			        break;
			}
			if ($this->aSearch['is_product_list_visible']=='1')	$this->sSearchSQL .= " and pg.is_product_list_visible='1'";
			if ($this->aSearch['is_product_list_visible']=='0')	$this->sSearchSQL .= " and pg.is_product_list_visible='0'";
			switch($this->aSearch['is_product_list_visible']){
			    case '1':
			        $this->sSearchSQL.=" and pg.is_product_list_visible>='1'";
			        break;
			    case '0':
			        $this->sSearchSQL.=" and pg.is_product_list_visible>='0'";
			        break;
			    case  '':
			        break;
			}
		}
		//--------------------
		

		$this->initLocaleGlobal ();
		$oTable=new Table();
		$sTablePref = 'pg.';
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id','sOrder'=>$sTablePref.'id'),
		'code'=>array('sTitle'=>'Code','sOrder'=>$sTablePref.'code'),
		'code_name'=>array('sTitle'=>'Code name','sOrder'=>$sTablePref.'code_name'),
		'name'=>array('sTitle'=>'Name','sOrder'=>$sTablePref.'name'),
		'level'=>array('sTitle'=>'Level','sOrder'=>$sTablePref.'level'),
		'id_parent'=>array('sTitle'=>'ID Parent','sOrder'=>$sTablePref.'id_parent'),
		'is_product_list_visible'=>array('sTitle'=>'Product visible','sOrder'=>$sTablePref.'is_product_list_visible'),
		'image' => array('sTitle'=>'Image', 'sOrder'=>$sTablePref.'image'),
		'is_menu'=>array('sTitle'=>'is menu','sOrder'=>$sTablePref.'is_menu'),
		'is_main'=>array('sTitle'=>'is main','sOrder'=>$sTablePref.'is_main'),
		'sort' => array ('sTitle' => 'sort', 'sOrder' => $sTablePref.'sort'),
		'visible'=>array('sTitle'=>'Visible','sOrder'=>$sTablePref.'visible'),
		'language'=>array('sTitle' => 'Lang' ),
		'action'=>array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign($aData) {
		$aBaseLevelGroups =  array('0'=>'not selected')+Base::$db->getAssoc("select id, CONCAT(
		    '[',level,'] ',id,' - ',name) as name_group
			from price_group where level in ('0','1','2') order by name");
		
		Base::$tpl->assign ( 'aBaseLevelGroups', $aBaseLevelGroups );
		Base::$tpl->assign ( 'sBaseLevelGroups', $aData['id_parent'] );
		Base::$tpl->assign ( 'aBaseLevels', array(0=>0,1=>1,2=>2,3=>3));
		Base::$tpl->assign ( 'sBaseLevels', $aData['level'] );
	}
	//-----------------------------------------------------------------------------------------------
	public function AfterApply ($aBeforeRow,$aAfterRow) {
	    //remove cache
	    if(file_exists(SERVER_PATH."/cache/Home/main_groups.cache")) unlink(SERVER_PATH."/cache/Home/main_groups.cache");
	    if(file_exists(SERVER_PATH."/cache/Home/main_tabs.cache")) unlink(SERVER_PATH."/cache/Home/main_tabs.cache");
	    // if change data
		if (trim($aBeforeRow['link_name_group']) != trim($aAfterRow['link_name_group'])) {
	    	if(file_exists(SERVER_PATH."/cache/Associate/associate_data.cache")) unlink(SERVER_PATH."/cache/Associate/associate_data.cache");
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function RemoveAssociate()
	{
	    Db::Execute("update price set is_delayed_associate='1' where item_code in (select item_code from price_group_assign WHERE id_price_group='".Base::$aRequest['id']."' ) ");
	    Db::Execute("DELETE FROM price_group_assign WHERE id_price_group='".Base::$aRequest['id']."' ");
	    $this->AdminRedirect ( $this->sAction );
	}
	//-----------------------------------------------------------------------------------------------
	public function RemoveAllAssociate()
	{
	    Db::Execute("truncate price_group_assign ");
	    Db::Execute("update price set is_delayed_associate='1' ");
	    $this->AdminRedirect ( $this->sAction );
	}
	//-----------------------------------------------------------------------------------------------
	public function UpdateAssociate() 
	{
	    set_time_limit(0);
	    
	    // becouse not rewrite id_price_group - BUS-40 
	    Db::Execute("Delete from price_group_assign where id_price_group=0");
	    // delete unknown link group
	    $aIdUNK = Db::GetAssoc("SELECT pgs.id_price_group as key_,pgs.id_price_group 
	        FROM `price_group_assign` pgs
    	    left join price_group pg on pg.id = pgs.id_price_group
    	    WHERE pg.id is null
    	    group by pgs.id_price_group");
	    if ($aIdUNK)
	        Db::Execute("Delete from price_group_assign where id_price_group in (".implode(",",$aIdUNK).")");
	    
	    $iIdPriceGroup=Base::$aRequest['id'];
	    $aPriceGroup = Db::GetRow("select * from price_group where id='".$iIdPriceGroup."' ");
	    if($aPriceGroup) {
	        if(!$aPriceGroup['link_name_group']) return;
	         
	        $aWords=explode(";", $aPriceGroup['link_name_group']);
	        if($aWords) {
	            require_once(SERVER_PATH.'/lib/sphinx/sphinxapi.php');
	            $oSphinxClient = new SphinxClient();
	            $oSphinxClient->SetMatchMode(SPH_MATCH_EXTENDED);
	            $oSphinxClient->SetSortMode(SPH_SORT_RELEVANCE);
	    
	            $oSphinxClient->SetFieldWeights(array (
	                'part_name' => 95,
	            ));
	             
	            $sSphinxKeyword='';
	            $aW=array();
	            foreach ($aWords as $sKeyWord => $sWord) {
	                $aWords[$sKeyWord]=str_replace('"','',trim($sWord));
	                if(!$aWords[$sKeyWord]) continue;
	                // count word
	                $aCntWord = explode(" ",$aWords[$sKeyWord]);
	                if (count($aCntWord)>1)
	                	$aWords[$sKeyWord] = '"'.$aWords[$sKeyWord].'"';
	                	
	                $aW[]=$aWords[$sKeyWord];
	        	
	    		$sData_i_eng = str_replace("і","i", $aWords[$sKeyWord]);
	    		$sData_i_eng = str_replace("І","I", $sData_i_eng);
	    		$sData_i_ua = str_replace("i","і", $aWords[$sKeyWord]);
	    		$sData_i_ua = str_replace("I","І", $sData_i_ua);
	    		if ($aWords[$sKeyWord] != $sData_i_eng)
	    		    $aW[]=$sData_i_eng;
	                if ($aWords[$sKeyWord] != $sData_i_ua)
	    		    $aW[]=$sData_i_ua;
	            }
	            $aWords = $aW;
	             
	            $aStopWords=explode(";", $aPriceGroup['link_group_stop']);
	            $aStop = array();
	            if($aStopWords) foreach ($aStopWords as $sKeyStop => $aValueStop) {
	        	$aValueStop=trim($aValueStop);
	        	if (!$aValueStop) 
	        	    continue;
	        	$aStop[]=$aValueStop;
	        	
	    		$sData_i_eng = str_replace("і","i", $aValueStop);
	    		$sData_i_eng = str_replace("І","I", $sData_i_eng);
	    		$sData_i_ua = str_replace("i","і", $aValueStop);
	    		$sData_i_ua = str_replace("I","І", $sData_i_ua);
	    		if ($aValueStop != $sData_i_eng)
	    		    $aStop[]=$sData_i_eng;
	                if ($aValueStop != $sData_i_ua)
	    		    $aStop[]=$sData_i_ua;
	            }
	            $aStopWords = $aStop;

	            $sSphinxKeyword="(".implode(") | (", $aWords).") ";
	            if($aStopWords) {
	                $sSphinxKeyword.="-(".implode(") -(", $aStopWords).")";
	                $sLast=substr($sSphinxKeyword, strlen($sSphinxKeyword)-1);
	                if($sLast=="-") {
	                    $sSphinxKeyword=substr($sSphinxKeyword, 0, -1);
	                }
	            }

	            $aId=array();
	            foreach (range(0, 1000) as $i) {
	                $oSphinxClient->SetLimits(($i*1000), 1000, 1000000);
	                // search by one field part_name if need searh 2 field "@(field1,field2)"
	                $aResult = $oSphinxClient->Query('@part_name '.$sSphinxKeyword, 'price_group_'.Base::$aDbConf['Database']);
	                if($aResult["matches"]) {
	                    $aId = array_merge($aId,array_keys($aResult['matches']));
	                } else {
	                    break;
	                }
	            }
	            
                if($aId) {
                    $aData=Db::GetAll("select item_code,'".$iIdPriceGroup."' as id_price_group, pref from price where id in ('".implode("','", $aId)."') ");
    
                    if($aData) {
                        $sSql="insert into price_group_assign (item_code,id_price_group,pref) values \n";
                        foreach ($aData as $aValue) {
                            $sSql.="('".$aValue['item_code']."','".$aValue['id_price_group']."','".$aValue['pref']."'),\n";
                        }
                        $sSql=substr($sSql, 0, -2);
                        // not rewrite id_price_group BUS-40
                        $sSql.="\n on duplicate key update id_price_group=values(id_price_group),pref=values(pref)";
                        Db::Execute($sSql);
                    }
	            }
	        }
	    }
	    
// 	    Base::$sText.=$sSql;
// 	    Base::$sText.="<input type=button value='".Language::getDMessage('<< Return')."' onClick=\" xajax_process_browse_url('?".Base::$aRequest['return']
// 	    ."'); return false; \" class=submit_button>";
// 	    $this->AfterIndex();
	}
    //-----------------------------------------------------------------------------------------------
    public function UpdateAssociateOld()
    {
	    $aPriceGroupAssoc = Db::GetAssoc("Select code,id from price_group where id='".Base::$aRequest['id']."' ");
	    
	    $iStart = time();
	    //file_put_contents('/tmp/_inc',"\n Start: ".$iStart,FILE_APPEND);
	    set_time_limit(0);
	    $iPriceCount = Db::GetOne("SELECT count(*) FROM price WHERE price > 0 and is_delayed_associate = 1");
	    $aPriceId = array();
	    for($i=0; $i<$iPriceCount; $i= $i+1000){
	    	$aPrices = array();
		    $aPrices = Db::GetAll("SELECT id,part_rus,item_code,code,pref,is_delayed_associate,description FROM price WHERE is_delayed_associate = 1 limit ".$i.", 1000"); 
		    $aInsert = array();
		    $aDelete = array();
		    if($aPrices){
		        foreach($aPrices as $aPrice){
		            $aPrice['id_price_group'] = $this->FindAssociate($aPrice,Base::$aRequest['id']);
		            if($aPrice['id_price_group']!='' && $aPrice['id_price_group'] && $aPriceGroupAssoc[$aPrice['id_price_group']]) {
		                $aPrice['id_price_group'] = $aPriceGroupAssoc[$aPrice['id_price_group']];
		                $aInsert[]= " ('".$aPrice['item_code']."','".$aPrice['id_price_group']."','".$aPrice['pref']."')";
		            }
// 		            else {
// 		                $aDelete[] = $aPrice['item_code'];
// 		            }
		            $aPriceId[] = $aPrice['item_code'];
		        }
		        Debug::PrintPre($this->FindAssociate($aPrice,Base::$aRequest['id']));
		        if($aInsert) {
		            Db::Execute("insert ignore into price_group_assign (item_code,id_price_group,pref)
					    values ".implode(',', $aInsert)."
			    		    on duplicate key update id_price_group = values(id_price_group), pref = values(pref) ");
		        }
// 		        if($aDelete)
// 		            Db::Execute("DELETE FROM price_group_assign WHERE item_code in ('".implode("','", $aDelete)."')");
		    }
    	    if($aPriceId)
    		   Db::Execute("UPDATE price SET is_delayed_associate = 0, post_date = post_date WHERE item_code in('".implode("','", $aPriceId)."')");
	    }
	    $this->AdminRedirect ( $this->sAction );
	}
	//-----------------------------------------------------------------------------------------------
	public function FindAssociate($aData,$iIdPriceGroup) {
	    static $aDataAssociate, $sSql;
	
	    if (!$aDataAssociate)
	        $aDataAssociate = $this->BuildAssociateData($iIdPriceGroup);
	
	    if ($aData['part_rus']!='') {
	    	$sText  = $aData['part_rus'];
	    }
	    else 
	    	$sText = $aData['description'];
	    
	    $sData_i_eng = str_replace("і","i", $sText);
	    $sData_i_eng = str_replace("І","I", $sData_i_eng);
	     
	    foreach ($aDataAssociate as $sString => $sCodeGroup) {
	        if (mb_stripos($sText,$sString) !== false)
	            return $sCodeGroup;
	        // i symbol eng / ua
	        if (mb_stripos($sData_i_eng,$sString) !== false)
	            return $sCodeGroup;
	    }
	    return '';
	}
	// -------------------------------------------------------------------------------------------
	public function BuildAssociateData($iIdPriceGroup) {
	    $aResult = array();
	    if($iIdPriceGroup)
	       $aData = Db::GetAll("Select code, link_name_group from price_group where id='".$iIdPriceGroup."' ");
	    if (!$aData)
	        return $aResult;
	    	
	    foreach($aData as $aValue) {
	        $aMass = explode(';',$aValue['link_name_group']);
	        foreach ($aMass as $sLink) {
	            if($sLink && trim($sLink)!='') {
	                $sLink = str_replace("\\", "", trim($sLink));
	                $sLink = str_replace("\n", "", $sLink);
	                $sLink_i_eng = str_replace("І","I",str_replace("і","i", $sLink));
	
	                //$aResult[$sLink] = $aValue['code'];
	                $aMass=explode(" ",$sLink);
	                $i=count($aMass)*100; // count words
	                foreach($aMass as $sWord)
	                	$i+=mb_strlen($sWord,'UTF-8');
	                
	                $aTmpSort[] = $i;
	                $aTmp[] = array('name' => $sLink, 'code' => $aValue['code']);
	
	                if ($sLink_i_eng != $sLink) {
	                    $aTmpSort[] = $i;
	                    $aTmp[] = array('name' => $sLink_i_eng, 'code' => $aValue['code']);
	                }
	            }
	        }
	    }
	    	
	    // array count words string
	    if ($aTmpSort) {
	        array_multisort ($aTmpSort, SORT_DESC, SORT_STRING, $aTmp);
	        foreach($aTmp as $aValue)
	            $aResult[$aValue['name']] = $aValue['code'];
	    }
	    return $aResult;
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAdd() {
		$aHandbook=Base::$db->GetAll('select * from handbook');
		Base::$tpl->assign('aHandbook',$aHandbook);
	
		$aPriceGroupFilter=Base::$db->GetAll("select * from price_group_filter
			where id_price_group='".Base::$aRequest['id']."'");
		//Base::$tpl->assign('aPriceGroupFilter',$aPriceGroupFilter);
	
		$aSelectedHandbook=array();
		if ($aPriceGroupFilter)
		foreach($aPriceGroupFilter as $key=>$value){
			$aSelectedHandbook[$value['id_handbook']]=$value['id_handbook'];
		}
		Base::$tpl->assign('aSelectedHandbook',$aSelectedHandbook);
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeApply() {
		Base::$db->Execute("delete from price_group_filter where id_price_group='".Base::$aRequest['data']['id']."'");
		$aHandBook=Base::$aRequest['data']['handbook'];
		if ($aHandBook){
			foreach ($aHandBook as $aItem){
				$aData=array(
					'id_handbook'=>$aItem,
					'id_price_group'=>Base::$aRequest['data']['id'],
				);
				Db::AutoExecute('price_group_filter',$aData);
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function CheckAssociate() {
	    $iIdPriceGroup=Base::$aRequest['id'];
	    $aPriceGroup = Db::GetRow("select * from price_group where id='".$iIdPriceGroup."' ");
	    if($aPriceGroup) {
	        $aWords=explode(";", $aPriceGroup['link_name_group']);
	        if($aWords) {
	            require_once(SERVER_PATH.'/lib/sphinx/sphinxapi.php');
	            $oSphinxClient = new SphinxClient();
	            $oSphinxClient->SetMatchMode(SPH_MATCH_EXTENDED);
	            $oSphinxClient->SetSortMode(SPH_SORT_RELEVANCE);
	    
	            $oSphinxClient->SetFieldWeights(array (
	                'part_name' => 95,
	            ));
	             
	            $sSphinxKeyword='';
	            $aW=array();
	            foreach ($aWords as $sKeyWord => $sWord) {
	                $aWords[$sKeyWord]=str_replace('"','',trim($sWord));
	                if(!$aWords[$sKeyWord]) continue;
	                // count word
	                $aCntWord = explode(" ",$aWords[$sKeyWord]);
	                if (count($aCntWord)>1)
	                	$aWords[$sKeyWord] = '"'.$aWords[$sKeyWord].'"';
	                
	                $aW[]=$aWords[$sKeyWord];
	        	
	    		$sData_i_eng = str_replace("і","i", $aWords[$sKeyWord]);
	    		$sData_i_eng = str_replace("І","I", $sData_i_eng);
	    		$sData_i_ua = str_replace("i","і", $aWords[$sKeyWord]);
	    		$sData_i_ua = str_replace("I","І", $sData_i_ua);
	    		if ($aWords[$sKeyWord] != $sData_i_eng)
	    		    $aW[]=$sData_i_eng;
	                if ($aWords[$sKeyWord] != $sData_i_ua)
	    		    $aW[]=$sData_i_ua;
	            }
	            $aWords = $aW;
	            
	            $aStopWords=explode(";", $aPriceGroup['link_group_stop']);
	            $aStop = array();
	            if($aStopWords) foreach ($aStopWords as $sKeyStop => $aValueStop) {
	        	$aValueStop=trim($aValueStop);
	        	if (!$aValueStop) 
	        	    continue;
	        	$aStop[]=$aValueStop;
	        	
	    		$sData_i_eng = str_replace("і","i", $aValueStop);
	    		$sData_i_eng = str_replace("І","I", $sData_i_eng);
	    		$sData_i_ua = str_replace("i","і", $aValueStop);
	    		$sData_i_ua = str_replace("I","І", $sData_i_ua);
	    		if ($aValueStop != $sData_i_eng)
	    		    $aStop[]=$sData_i_eng;
	                if ($aValueStop != $sData_i_ua)
	    		    $aStop[]=$sData_i_ua;
	            }
	            $aStopWords = $aStop;
	            $sSphinxKeyword="(".implode(") | (", $aWords).") ";
	            if($aStopWords) {
	                $sSphinxKeyword.="-(".implode(") -(", $aStopWords).")";
	                $sLast=substr($sSphinxKeyword, strlen($sSphinxKeyword)-1);
	                if($sLast=="-") {
	                    $sSphinxKeyword=substr($sSphinxKeyword, 0, -1);
	                }
	            }
	            
	            $aId=array();
	            foreach (range(0, 1000) as $i) {
	                $oSphinxClient->SetLimits(($i*1000), 1000, 1000000);
	                // search by one field part_name if need searh 2 field "@(field1,field2)"
	                $aResult = $oSphinxClient->Query('@part_name '.$sSphinxKeyword, 'price_group_'.Base::$aDbConf['Database']);
	                if($aResult["matches"]) {
	                    $aId = array_merge($aId,array_keys($aResult['matches']));
	                } else {
	                    break;
	                }
	            }
	    
	            Base::$sText.=$sSphinxKeyword."<br><br>";
	            
	            Base::$sText.="<input type=button value='".Language::getDMessage('<< Return')."' onClick=\" xajax_process_browse_url('?".Base::$aRequest['return']
	            ."'); return false; \" class=submit_button>";
	            
	            $oTable=new Table();
	            $this->SetDefaultTable($oTable);
	            $oTable->aColumn['make']=array('sTitle'=>'Make');
	            $oTable->aColumn['code']=array('sTitle'=>'Code');
	            $oTable->aColumn['name_translate']=array('sTitle'=>'Name');
	            $oTable->sDataTemplate='mpanel/cat/row_cat.tpl';
	            $oTable->sDefaultOrder='order by name_translate';
	            $oTable->SetSql('Catalog/Price', array(
	                "where"=>($aId ? " and p.id in (".implode(',',$aId).") " : " and 1=0 "),
	                "is_not_check_item_code"=>1
	            ) );
	            $oTable->sSql=str_replace("order by  t.price asc", "", $oTable->sSql);
	            Base::$sText.=$oTable->getTable();
	            
	            Base::$sText.="<input type=button value='".Language::getDMessage('<< Return')."' onClick=\" xajax_process_browse_url('?".Base::$aRequest['return']
	            ."'); return false; \" class=submit_button>";
	        }
	    }
	    $this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------

}
?>