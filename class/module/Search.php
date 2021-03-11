<?

/**
 * @author Mikhail Starovoyt
 * @version 4.5.2
 */

class Search
{
	private $sPrefix="search";
	public $sQuery="";

	/**
	 * sample calls
	 search --config d:\Sphinx\sphinx.conf vin
	 indexer --all --config d:\Sphinx\sphinx.conf
	 indexer --rotate --config d:\Sphinx\sphinx.conf --all
	*/

	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
		$this->sQuery=$this->StripQuery(Base::$aRequest['search']['query']);
		Base::$tpl->assign('sQuery',$this->sQuery);
	}
	//-----------------------------------------------------------------------------------------------
	public function Index($bShowSearchForm=true)
	{
		Base::$bXajaxPresent=true;
		/*if ($bShowSearchForm) {
			$aData=array(
			'sHeader'=>"method=get",
			'sContent'=>Base::$tpl->fetch($this->sPrefix.'/form_sphinx_search.tpl'),
			'sSubmitButton'=>'Search',
			'sSubmitAction'=>'search',
			'sReturnButton'=>'Clear',
			'sReturnAction'=>'search',
			'sWidth'=>'450px',
			'bIsPost'=>'0',
			'sError'=>$sError,
			);
			$oForm=new Form($aData);
			Base::$sText.=$oForm->getForm();
		}
		if ($this->sQuery && Base::$aRequest['search']['id_price_group']) {
			$this->SinglePriceGroup();
		}
		else {
			$this->AllPriceGrouped();
		}*/
		$this->AllSearch();
	}
	//-----------------------------------------------------------------------------------------------
	public function AllPriceGrouped()
	{
		if ($this->sQuery) {

			require_once(SERVER_PATH.'/lib/sphinx/sphinxapi.php');
			$sSphinxKeyword=$this->GetSphinxKeyword($this->sQuery);

			if (!$sSphinxKeyword) {
				Base::$sText.=Language::GetText('empty search results');
				return;
			}

			$oSphinxClient = new SphinxClient();
			$this->SetDefaultSetting($oSphinxClient);

			$oSphinxClient->SetGroupBy("id_price_group", SPH_GROUPBY_ATTR, "@count desc" );
			$aResult = $oSphinxClient->Query($sSphinxKeyword, 'price_group_'.Base::$aDbConf['Database']);
			//Base::$sText.=$sSphinxKeyword."<br>";

			if ( $aResult === false ) {
				Base::$sText.="Query failed: ".$oSphinxClient->GetLastError()."<br>";
			}
			else {
				if ($oSphinxClient->GetLastWarning() ) {
					Base::$sText.="WARNING: ".$oSphinxClient->GetLastWarning()."<br>";
				}
				if ($aResult['matches']) {
					$aPriceGroupAssoc=Db::GetAssoc('Assoc/PriceGroup',array(
					'visible'=>1,
					'multiple'=>1,
					));
					$aPriceGroupAssoc[0]['name']=Language::GetMessage('price_group_none');
					$aPriceGroupAssoc[0]['code']=-1;
					foreach ($aResult['matches'] as $aValue){
						$PriceGroupRow=$aPriceGroupAssoc[$aValue['attrs']['id_price_group']];

						if ($PriceGroupRow) {
							$PriceGroupRow['total_found']=$aValue['attrs']['@count'];
							$PriceGroupRow['price_group']=$PriceGroupRow;
							$PriceGroupRow['price_group']['id']=$aValue['attrs']['id_price_group'];
							$aResultPriceGroup[]=$PriceGroupRow;
						}
					}

					//--- sorting results -------
					if($aPriceGroupAssoc)
					foreach ($aPriceGroupAssoc as $aValue) {
						$aPriceGroupAssocName[]=$aValue['name'];
						$aPriceGroupAssocNameAssoc[$aValue['name']]=$aValue['code'];
					}
					sort($aPriceGroupAssocName,SORT_STRING);

					if($aPriceGroupAssocName)
					foreach ($aPriceGroupAssocName as $aValue) {
						$iIdPriceGroup=$aPriceGroupAssocNameAssoc[$aValue];
						if($aResultPriceGroup)
						foreach($aResultPriceGroup as $aValue2) {
							if ($iIdPriceGroup==$aValue2['price_group']['code']) $aResultPriceGroupSorted[]=$aValue2;
						}
					}
					//--- sorting results -------
					Base::$tpl->assign('aResultPriceGroupSorted',$aResultPriceGroupSorted);

					Base::$sText.=Base::$tpl->fetch($this->sPrefix.'/all_price_group.tpl');
				}
				else {
					Base::$sText.=Language::GetText('empty search results');
				}
				//
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function AllPriceGroup()
	{
		if ($this->sQuery) {
			require_once(SERVER_PATH.'/lib/sphinx/sphinxapi.php');
			$sSphinxKeyword=$this->GetSphinxKeyword($this->sQuery);

			if (!$sSphinxKeyword) return;

			$oSphinxClient = new SphinxClient();
			$this->SetDefaultSetting($oSphinxClient);

			$aPriceGroup=Db::GetAll(Base::GetSql("Price/Group",array(
			'visible'=>1,
			"where"=>" and pg.code_name is not null",
			)));
			if ($aPriceGroup) {
				$aResultAll=array();
				$i=0;

				foreach ($aPriceGroup as $aValue) {

					$oSphinxClient->SetFilter('id_price_group', array($aValue['id']));
					$iQuery = $oSphinxClient->AddQuery($sSphinxKeyword, 'price_group_'.Base::$aDbConf['Database']);
					$oSphinxClient->ResetFilters();
					$bAddedUnrunQuery=true;

					$aPriceGroupAssoc[$iQuery+(32*$i)]=$aValue;

					if ($iQuery && !($iQuery % 31) ) {
						$aResultQuery=$oSphinxClient->RunQueries();
						$aResultAll=array_merge($aResultAll,$aResultQuery);

						$sLastError=$oSphinxClient->GetLastError();
						$i++;
						$bAddedUnrunQuery=false;
					}
				}
				if ($bAddedUnrunQuery) {
					$aResultQuery=$oSphinxClient->RunQueries();
					$aResultAll=array_merge($aResultAll,$aResultQuery);
				}
			}
			Base::$sText.=$sSphinxKeyword."<br>";

			if ( $aResultAll === false ) {
				Base::$sText.="Query failed: ".$oSphinxClient->GetLastError()."<br>";
			}
			else {
				foreach ($aResultAll as $sKey => $aValue) {
					if ($aValue['total_found']) {
						$aValue['price_group']=$aPriceGroupAssoc[$sKey];

						$aResultPriceGroup[]=$aValue;
					}
				}

				Base::$tpl->assign('aResultPriceGroup',$aResultPriceGroup);
				Base::$sText.=Base::$tpl->fetch($this->sPrefix.'/all_price_group.tpl');
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function SinglePriceGroup()
	{
		if(Base::$aRequest['search']['id_price_group']==-1)
			$aPriceGroupRow=array('id'=>0,'name'=>Language::GetMessage('price_group_none'));
		else
		$aPriceGroupRow=Db::GetRow(Base::GetSql("Price/Group",array(
		'visible'=>1,
		"where"=>" and pg.code_name is not null",
		'id'=>(Base::$aRequest['search']['id_price_group'] ? Base::$aRequest['search']['id_price_group'] : -1),
		)));
		//Base::$tpl->assign('aPriceGroupRow',$aPriceGroupRow);

		if ($this->sQuery && $aPriceGroupRow) {

			require_once(SERVER_PATH.'/lib/sphinx/sphinxapi.php');
			$sSphinxKeyword=$this->GetSphinxKeyword($this->sQuery);
			if (!$sSphinxKeyword) return;

			$oSphinxClient = new SphinxClient();
			$this->SetDefaultSetting($oSphinxClient);

			$oSphinxClient->SetFilter('id_price_group', array($aPriceGroupRow['id']));
			$aResult = $oSphinxClient->Query($sSphinxKeyword, 'price_group_'.Base::$aDbConf['Database']);
			//Base::$sText.=$sSphinxKeyword."<br>";

			if ( $aResult === false ) {
				Base::$sText.="Query failed: ".$oSphinxClient->GetLastError()."<br>";
			}
			else {
				if ($oSphinxClient->GetLastWarning() ) {
					Base::$sText.="WARNING: ".$oSphinxClient->GetLastWarning()."<br>";
				}

				if (!empty($aResult["matches"]) ) {

					$aId = array_keys($aResult['matches']);
					$sOrder="";
				if(Base::GetConstant('complex_margin_enble','0')) {
        			if (!Base::$aRequest['sort'] || Base::$aRequest['sort'] == 'price')
        				$sOrder = " t.price ";
        			elseif (Base::$aRequest['sort'] == 'term')
        				$sOrder = " t.term ";
        			elseif (Base::$aRequest['sort'] == 'stock')
        				$sOrder = " CONVERT(replace(replace(replace(replace(replace(replace(replace(replace(t.stock,'>',''),'+',''),'++',''),'+++',''),'есть','1'),'X',''),'XX',''),'XXX',''), SIGNED) ";
        			elseif (Base::$aRequest['sort'] == 'brand')
        				$sOrder = " t.brand ";
        			elseif (Base::$aRequest['sort'] == 'name')
        				$sOrder = " t.name_translate ";
        			elseif (Base::$aRequest['sort'] == 'art_article_nr')
        				$sOrder = " t.code ";
    		    } else {
    		        if (!Base::$aRequest['sort'] || Base::$aRequest['sort'] == 'price')
    		            $sOrder = " p.price ";
    		        elseif (Base::$aRequest['sort'] == 'brand')
    		        $sOrder = " c.title ";
    		        elseif (Base::$aRequest['sort'] == 'provider')
    		        $sOrder = " up.name ";
    		        elseif (Base::$aRequest['sort'] == 'term')
    		        $sOrder = " p.term ";
    		        elseif (Base::$aRequest['sort'] == 'stock')
    		        $sOrder = " CONVERT(replace(replace(replace(replace(replace(replace(replace(replace(p.stock,'>',''),'+',''),'++',''),'+++',''),'есть','1'),'X',''),'XX',''),'XXX',''), SIGNED) ";
    		        elseif (Base::$aRequest['sort'] == 'name_translate')
    		        $sOrder = " coalesce(cp.name_rus,p.part_rus,''),c.title,p.code ";
    		        elseif (Base::$aRequest['sort'] == 'code')
    		        $sOrder = " p.code ";
    		    }
					
					if (Base::$aRequest['way'] && Base::$aRequest['way'] == 'down')
						$sOrder .= ' desc ';
					
					$sSql=Base::GetSql("Catalog/Price",array(
					"customer_discount"=>Discount::CustomerDiscount(Auth::$aUser),
					"id_price_group"=>$aPriceGroupRow['id'],
					"where"=>" and p.id in (".implode(',',$aId).")",
					"order"=>$sOrder,
					));

					$oTable=new Table();
					$oTable->sSql=$sSql;
					$oTable->aColumn=array(
					'brand'=>array('sTitle'=>'brand', 'sClass'=>'cell-brand'),
					'code'=>array('sTitle'=>'code', 'sClass'=>'cell-code'),
					'name_translate'=>array('sTitle'=>'Name', 'sClass'=>'cell-name'),
					'stock'=>array('sTitle'=>'Stock','sWidth'=>'5%', 'sClass'=>'cell-stock'),
					'term'=>array('sTitle'=>'Term','sWidth'=>'5%', 'sClass'=>'cell-term'),
					'number'=>array('sTitle'=>'Number','sWidth'=>'5%', 'sClass'=>'cell-number','nosort'=>1),
					'price'=>array('sTitle'=>'Price','sWidth'=>'5%', 'sClass'=>'cell-price'),
					'action'=>array('sClass'=>'cell-action','nosort'=>1),
					);
					Auth::$aUser['type_']=='manager'?$oTable->aColumn['provider']=array('sTitle'=>'Provider','sWidth'=>'5%'):"";
						
					$oTable->sClass .= ' search-table mobile-table';
					$oTable->sDataTemplate="catalog/row_catalog_search_advance.tpl";
					$oTable->iRowPerPage=25;
					$oTable->sTemplateName = 'catalog/search_table.tpl';
					//$oTable->bStepperVisible=false;
					Resource::Get()->Add('/css/thickbox.css');
					Resource::Get()->Add('/libp/jquery/jquery.thickbox.js');
					
					// macro sort table
					Catalog::SortTable();
					
					Base::$sText.=$oTable->GetTable("Sphinx search",'',' - "'.$this->sQuery.'" - '.$aPriceGroupRow['name']);

					//Base::$sText.=Debug::PrintPre($aResult,false,true);
				}
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	
	private function SetDefaultSetting(&$oSphinxClient,$iLimit=1000)
	{
		$oSphinxClient->SetMatchMode(SPH_MATCH_EXTENDED2);
		//$oSphinxClient->SetMatchMode(SPH_MATCH_ANY);

		$oSphinxClient->SetSortMode(SPH_SORT_RELEVANCE);
		$oSphinxClient->SetLimits(0,$iLimit);

		$oSphinxClient->SetFieldWeights(array (
		'code' => 50,
		'brand' => 40,
		'part_name' => 10,
		'price_group_name' => 5,
		));
	}
	//-----------------------------------------------------------------------------------------------
	private function GetSphinxKeyword($sQuery)
	{
		//return $sQuery;
		$sQuery=  str_replace('/', '\/', $sQuery);
		$sQuery=  str_replace('-', '', $sQuery);
		$aRequestString=preg_split('/[\s,]+/', $sQuery, 5);

		if ($aRequestString) {
			foreach ($aRequestString as $sValue)
			{
				if (strlen($sValue)>=3)
				{
					$sStr = $sValue." | *".$sValue."*";
					$sData_i_eng = str_replace("і","i", $sValue);
					$sData_i_eng = str_replace("І","I", $sValue);
					$sData_i_ua = str_replace("i","і", $sValue);
					$sData_i_ua = str_replace("I","І", $sValue);
					if ($sValue != $sData_i_eng)
						$sStr .= " | ".$sData_i_eng." | *".$sData_i_eng."*";
					if ($sValue != $sData_i_ua)
						$sStr = " ".$sData_i_ua." | *".$sData_i_ua."* |" . $sStr;
						
					$aKeyword[] .= "(".$sStr.")";
				}
			}
			if ($aKeyword) $sSphinxKeyword = implode(" & ", $aKeyword);
		}
		return $sSphinxKeyword;
	}
	//-----------------------------------------------------------------------------------------------
	private function StripQuery($sQuery)
	{
		return strip_tags(str_replace(array('#','.','/',':','[',']','(',')','*','&','+','`','\'','"'),"",trim($sQuery)));
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * Deprecated methods for creating multiple config files
	 *
	 public function CreateConfigFile()
	{
		$sConfigFilePath=Db::GetConstant('sphinx:config_file_path',SERVER_PATH.'/imgbank/sphinx/');
		$sConfigFileName='sphinx.conf';
		$sConfigTemplate=Db::GetConstant('sphinx:config_template','production');

		if (!file_exists($sConfigFilePath)) mkdir($sConfigFilePath);

		$sTopSection.=$this->GetPriceGroupConfig();
		Base::$tpl->assign('sTopSection',$sTopSection);
		$sFileContent=Base::$tpl->fetch($this->sPrefix.'/config_sphinx_'.$sConfigTemplate.'.tpl');

		file_put_contents($sConfigFilePath.$sConfigFileName,$sFileContent);
	}
	//-----------------------------------------------------------------------------------------------
	private function GetPriceGroupConfig()
	{
		Base::$tpl->assign('sDataFilePath',Base::GetConstant('sphinx:data_file_path','/var/data/'));

		return Base::$tpl->fetch($this->sPrefix.'/config_price_group.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	*/
	//-----------------------------------------------------------------------------------------------
	public function AllSearch()
	{
		if ($this->sQuery) {
// 			if ($_COOKIE['incodeoriginal'])
// 				$this->sQuery = urldecode($_COOKIE['incodeoriginal']);
			 
			$_GET['code'] = $this->sQuery; // for set in form 
			require_once(SERVER_PATH.'/lib/sphinx/sphinxapi.php');
			$sSphinxKeyword=$this->GetSphinxKeyword($this->sQuery);
			if (!$sSphinxKeyword) return;
	
			$oSphinxClient = new SphinxClient();
			$this->SetDefaultSetting($oSphinxClient);
	
			$aResult = $oSphinxClient->Query($sSphinxKeyword, 'price_group_'.Base::$aDbConf['Database']);
			
			$sSql = 'SELECT * FROM (SELECT NULL) as c WHERE 0';
			
			if ( $aResult === false && Auth::$aUser['type_'] == 'manager') {
				Base::$sText.="Query failed: ".$oSphinxClient->GetLastError()."<br>";
			}
			if ( $aResult !== false ) {
				if ($oSphinxClient->GetLastWarning() && Auth::$aUser['type_'] == 'manager') {
					Base::$sText.="WARNING: ".$oSphinxClient->GetLastWarning()."<br>";
				}
	
				if (!empty($aResult["matches"]) ) {
	
					$aId = array_keys($aResult['matches']);
					$sOrder="";
				if(Base::GetConstant('complex_margin_enble','0')) {
        			if (!Base::$aRequest['sort'] || Base::$aRequest['sort'] == 'price')
        				$sOrder = " t.price ";
        			elseif (Base::$aRequest['sort'] == 'term')
        				$sOrder = " t.term ";
        			elseif (Base::$aRequest['sort'] == 'stock')
        				$sOrder = " CONVERT(replace(replace(replace(replace(replace(replace(replace(replace(t.stock,'>',''),'+',''),'++',''),'+++',''),'есть','1'),'X',''),'XX',''),'XXX',''), SIGNED) ";
        			elseif (Base::$aRequest['sort'] == 'brand')
        				$sOrder = " t.brand ";
        			elseif (Base::$aRequest['sort'] == 'name_translate')
        				$sOrder = " t.name_translate ";
        			elseif (Base::$aRequest['sort'] == 'code')
        				$sOrder = " t.code ";
    		    } else {
    		        if (!Base::$aRequest['sort'] || Base::$aRequest['sort'] == 'price')
    		            $sOrder = " p.price/cu.value ";
    		        elseif (Base::$aRequest['sort'] == 'brand')
    		        $sOrder = " c.title ";
    		        elseif (Base::$aRequest['sort'] == 'provider')
    		        $sOrder = " up.name ";
    		        elseif (Base::$aRequest['sort'] == 'term'){
    		            if (Base::GetConstant('price:term_from_provider',1)) {
    		                $sOrder = " up.term ";
    		            } else {
    		                $sOrder = " p.term ";
    		            }
    		        }
    		        elseif (Base::$aRequest['sort'] == 'stock')
    		        $sOrder = " CONVERT(replace(replace(replace(replace(replace(replace(replace(replace(p.stock,'>',''),'+',''),'++',''),'+++',''),'есть','1'),'X',''),'XX',''),'XXX',''), SIGNED) ";
    		        elseif (Base::$aRequest['sort'] == 'name_translate')
    		        $sOrder = " coalesce(cp.name_rus,p.part_rus,'') ";
    		        elseif (Base::$aRequest['sort'] == 'code')
    		        $sOrder = " p.code ";
    		    }
										
					if (Base::$aRequest['way'] && Base::$aRequest['way'] == 'down')
						$sOrder .= ' desc ';
						
					$sSql=Base::GetSql("Catalog/Price",array(
							"customer_discount"=>Discount::CustomerDiscount(Auth::$aUser),
							"where"=>" and p.id in (".implode(',',$aId).") and p.price > 0 ",
							"order"=>$sOrder,
							"is_not_check_item_code"=>1
					));
				}
			}
		}
		$oTable=new Table();
		$oTable->sSql=$sSql;
		$oTable->aColumn=array(
				'brand'=>array('sTitle'=>'brand', 'sClass'=>'cell-brand'),
				'code'=>array('sTitle'=>'code', 'sClass'=>'cell-code'),
				'name_translate'=>array('sTitle'=>'Name', 'sClass'=>'cell-name'),
				'stock'=>array('sTitle'=>'Stock','sWidth'=>'5%', 'sClass'=>'cell-stock'),
				'term'=>array('sTitle'=>'Term','sWidth'=>'5%', 'sClass'=>'cell-term'),
				'number'=>array('sTitle'=>'Number','sWidth'=>'5%', 'sClass'=>'cell-number','nosort'=>1),
				'price'=>array('sTitle'=>'Price','sWidth'=>'5%', 'sClass'=>'cell-price'),
				'action'=>array('sClass'=>'cell-action','nosort'=>1),
		);
		$oTable->sClass .= ' search-table mobile-table';
		$oTable->sDataTemplate="catalog/row_catalog_search_advance.tpl";
		$oTable->sTemplateName = 'catalog/search_table.tpl';
		$oTable->iRowPerPage=Language::getConstant('search:limit_page_group',25);
		$oTable->aCallback=array($this,'CallParse');

		// Если нужна подгрузка картинок, раскомментировать
		// $oTable->aCallbackAfter=array($this,'CallParseAfter');
		
		// macro sort table
		Catalog::SortTable();

		Base::$sText.=$oTable->GetTable("Sphinx search",'',' - "'.$this->sQuery.'"');
		Base::$oContent->AddCrumb(Language::GetMessage('result_search'));
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParse(&$aItem)
	{
		if($aItem) {
			Catalog::PosPriceParse($aItem,false,false);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseAfter(&$aItem)
	{
        if ($aItem) foreach ($aItem as $sKey=>$aValue) {
	        if($aValue['id_cat_part']){
	            $aCatPart[]=$aValue['id_cat_part'];
	            $aKeyPart[$aValue['id_cat_part']]=$sKey;
	        }
	         
	        if ($aItem[$sKey]['litrs_child']) foreach ($aItem[$sKey]['litrs_child'] as $sKeyChild=>$aValueChild) {
	            $aCatPart[]=$aValueChild['id_cat_part'];
	            $aKeyPart[$aValueChild['id_cat_part']]=$sKeyChild;
	        }
	    }
	    
        $aCodes=array();
		if($aItem) foreach ($aItem as $sKey=>$aValue) {
		    $aCodes[]=$aValue['code'];
		}
		if($aCodes) $aArtIds=TecdocDb::GetArts($aCodes);
		if($aArtIds) foreach ($aItem as $sKey=>$aValue) {
		    $sArtId='';
		    $sArtId=$aArtIds[$aValue['item_code']];
		    if($sArtId) {
		        $aArtId[]=$sArtId;
		        $aKeyArt[$sArtId]=$sKey;
		        $aItem[$sKey]['art_id']=$sArtId;
		    }
		}

		$aGraphic=TecdocDb::GetImages(array(
		    'aIdGraphic'=>$aArtId,
		    'order'=>'gra_sort desc'
		));
		if($aGraphic){
			foreach ($aItem as $sKey=>$aValue) {
				if (array_key_exists($aValue['item_code'],$aGraphic))
				$aItem[$sKey]['image']=$aGraphic[$aValue['item_code']]['img_path'];
			}
		}

		$aGraphic=TecdocDb::GetImages(array(
		    'aIdCatPart'=>$aCatPart,
		    'order'=>'gra_sort desc'
		));
		if($aGraphic){
			foreach ($aItem as $sKey=>$aValue) {
				if (array_key_exists($aValue['item_code'],$aGraphic))
				$aItem[$sKey]['image']=$aGraphic[$aValue['item_code']]['img_path'];
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
}
?>