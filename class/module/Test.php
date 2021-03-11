<?

class Test extends Base
{
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
// 	    //select supplier
// 	    Test::PrintTable(TecdocDb::GetAll("select id, description
// 	        from tecdoc_1704.suppliers
// 	        where 1=1
// 	        limit 10
//         "),"производители запчастей");
	    
// 	    //select brand
// 	    Test::PrintTable(TecdocDb::GetAll("select id, description
// 	        from tecdoc_1704.manufacturers
// 	        where 1=1 and ispassengercar LIKE 'true' and canbedisplayed like 'true' and description in ('audi','bmw','opel','MERCEDES-BENZ','TOYOTA')
// 	        limit 10
//         "),"производители авто");
	    
// 	    // select years
	     
// 	    //select body
	     
// 	    //select volume
	     
// 	    //select engine
	    	  
	    	  
	    
// 	    //select model
// 	    Test::PrintTable(TecdocDb::GetAll("select m.id, m.description, m.fulldescription, m.constructioninterval
// 	        from tecdoc_1704.models as m
// 	        where 1=1 and m.canbedisplayed like 'true' and manufacturerid like '16'
// 	        limit 10
//         "),"модели авто 16");
	    
// 	    //select modification
// 	    Test::PrintTable(TecdocDb::GetAll("select id, constructioninterval, description, fulldescription
// 	        from tecdoc_1704.passanger_cars 
// 	        where 1=1 and canbedisplayed like 'true' and modelid like '9620'
// 	        limit 10
//         "),"модификации авто 9620");
	    
// 	    //modification info
// 	    Test::PrintTable(TecdocDb::GetAll("select displaytitle, displayvalue
// 	        from tecdoc_1704.passanger_car_attributes
// 	        where 1=1 and passangercarid like '110062'
//         "),"подробности по выбранному авто 110062");
	    
// 	    //select modification tree
// 	    Test::PrintTable(TecdocDb::GetAll("select t.id, t.parentid, t.description
// 	        from tecdoc_1704.passanger_car_trees as t
// 	        where 1=1 and t.passangercarid like '110062'
// 	        limit 100
//         "),"дерево по выбранному авто 110062");
	    
// 	    //select product groups
// 	    Test::PrintTable(TecdocDb::GetAll("select id, assemblygroupdescription, description, normalizeddescription
// 	        from tecdoc_1704.passanger_car_prd
// 	        where 1=1 and  `description` LIKE '%фильтр%' 
// 	        limit 100
//         "),"группы товаров");
	    
// 	    //select parts for tree
// 	    Test::PrintTable(TecdocDb::GetAll("select pcpds.passangercarid, pcpds.nodeid, pcpds.productid, pcpds.supplierid
// 	                                                                   ,t.description as tree , pcprd.description as product_group, s.description as supplier , a.datasupplierarticlenumber
// 	        from tecdoc_1704.passanger_car_pds as pcpds
// 	        join tecdoc_1704.passanger_car_prd as pcprd on pcprd.id=pcpds.productid
// 	        join tecdoc_1704.passanger_car_trees as t on t.passangercarid=pcpds.passangercarid and pcpds.nodeid=t.id
// 	        join tecdoc_1704.suppliers as s on pcpds.supplierid=s.id
// 	        join tecdoc_1704.article_links as alis on alis.productid = pcpds.productid and alis.linkageId = pcpds.passangercarid and alis.supplierid = pcpds.supplierid and alis.linkagetypeid='2'
// 	        join tecdoc_1704.articles as a on a.supplierId=alis.supplierId and a.DataSupplierArticleNumber=alis.datasupplierarticlenumber and a.ArticleStateDisplayValue like 'Нормальный'
// 	        where 1=1 and pcpds.passangercarid like '110062' and pcpds.productid like '9'
// 	        limit 200
//         "),"топливные фильтры по выбранному авто 110062");
	    
	    
// 	    //select product info
// 	    Test::PrintTable(TecdocDb::GetAll("select *
// 	        from tecdoc_1704.article_attributes
// 	        where 1=1 and  supplierid = '4' and datasupplierarticlenumber like 'WK 515'
// 	        limit 100
//         "),"характеристики товара MANN-FILTER	WK 515");

//        if(Auth::NeedAuth('manager')){
//            Base::$sText.="<button class='btn' onclick=\"xajax_process_browse_url('/?action=test_fix_cat');\">fix cat table</button>";
//        }
	    
	    //Test::GenerateRubricator();
	    Base::$sText.= "<br>Test module finished Ok.<br>";
	}
	//-----------------------------------------------------------------------------------------------
	public function CheckApp() {
	    $aData=TecdocDb::GetAll("
	        select
	           s.name as brand, a.search as code, a.id_art as img, a.id_art as typ
            from ".DB_OCAT."cat_alt_articles as a 
            join ".DB_OCAT."cat_alt_suppliers as s on s.id_src='9999' and a.id_sup=s.id_sup
	        
	        order by a.search
        ");
	     
	    $oTable=new Table();
	    $oTable->sType='array';
	    $oTable->aDataFoTable=$aData;
	    $oTable->aColumn=array(
	        'code'=>array('sTitle'=>'code'),
	        'model'=>array('sTitle'=>'model'),
	        'img'=>array('sTitle'=>'img'),
	        'typ'=>array('sTitle'=>'typ'),
	    );
	    $oTable->aOrdered="order by p.code";
	    $oTable->iRowPerPage=100;
	    $oTable->sDataTemplate='mpanel/cat/row_cat.tpl';
	    $oTable->aCallbackAfter=array($this,'CallParseApp');
	    Base::$sText.=$oTable->getTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseApp(&$aItem) {
	    if($aItem) {
	        foreach ($aItem as $sKey => $aValue) {
	            $aImages=TecdocDb::GetAll("
	                select i.path
	                from ".DB_OCAT."cat_alt_images as i
	                where i.id_art='".$aValue['img']."'
                ");
	            $aTmp=array();
	            foreach ($aImages as $aVal) {
	                $aTmp[]=$aVal['path'];
	            }
	             
	            if($aTmp) {
	                $aItem[$sKey]['img']="<img height='50px' src='http://tcd20.mstarproject.com/imgbank/tcd/".implode("'><img height='50px' src='http://tcd20.mstarproject.com/imgbank/tcd/", $aTmp)."'>";
	            }
	            
	            $aItem[$sKey]['typ']=TecdocDb::GetOne("SELECT GROUP_CONCAT(t.id_src SEPARATOR ', ')
	            FROM ".DB_OCAT."cat_alt_link_typ_art as l
	            JOIN ".DB_OCAT."cat_alt_types as t on l.id_typ=t.ID_typ
	            WHERE l.ID_art = '".$aValue['typ']."'");
	            
	            $aItem[$sKey]['code']="<a target='_blank' href='/buy/frictionmaster_".$aValue['code']."'>".$aValue['code']."</a>";
	        }
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function CheckImages() {
	    
	    $aData=TecdocDb::GetAll("
	        select 
	           r.id, 
	           r.code, 
	           concat(r.model, ' ',r.Trim) as model, 
	           ifnull(t.id_typ,0) as is_brand, 
	           a.id_art as img
            from fm.pads as r
            left join ".DB_OCAT."cat_alt_articles as a on r.code=a.search
            join ".DB_OCAT."cat_alt_suppliers as s on s.id_src='9999' and a.id_sup=s.id_sup
	        left join ".DB_OCAT."cat_alt_types as t on r.KType=t.id_src
	        
	        union
	        
	        select 
	           r.id, 
	           r.code, 
	           concat(r.model, ' ',r.Trim) as model, 
	           ifnull(t.id_typ,0) as is_brand, 
	           a.id_art as img
            from fm.rotors as r
            left join ".DB_OCAT."cat_alt_articles as a on r.code=a.search
            join ".DB_OCAT."cat_alt_suppliers as s on s.id_src='9999' and a.id_sup=s.id_sup
	        left join ".DB_OCAT."cat_alt_types as t on r.KType=t.id_src
        ");
	    
	    $oTable=new Table();
	    $oTable->sType='array';
	    $oTable->aDataFoTable=$aData;
	    $oTable->aColumn=array(
	        'id'=>array('sTitle'=>'id'),
	        'code'=>array('sTitle'=>'code'),
	        'model'=>array('sTitle'=>'model'),
	        'is_brand'=>array('sTitle'=>'is_brand'),
	        'img'=>array('sTitle'=>'img'),
	    );
	    $oTable->aOrdered="order by p.code";
	    $oTable->iRowPerPage=100;
	    $oTable->sDataTemplate='mpanel/cat/row_cat.tpl';
	    $oTable->aCallbackAfter=array($this,'CallParse');
	    Base::$sText.=$oTable->getTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParse(&$aItem) {
	    if($aItem) {
	        foreach ($aItem as $sKey => $aValue) {
	            $aImages=TecdocDb::GetAll("
	                select i.path
	                from ".DB_OCAT."cat_alt_images as i 
	                where i.id_art='".$aValue['img']."'
                ");
	            $aTmp=array();
	            foreach ($aImages as $aVal) {
	                $aTmp[]=$aVal['path'];
	            }
	            
	            if($aTmp) {
	                $aItem[$sKey]['img']="<img height='50px' src='http://tcd20.mstarproject.com/imgbank/tcd/".implode("'><img height='50px' src='http://tcd20.mstarproject.com/imgbank/tcd/", $aTmp)."'>";
	            }
	            
	            $aItem[$sKey]['code']="<a target='_blank' href='/buy/frictionmaster_".$aValue['code']."'>".$aValue['code']."</a>";
	        }
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function VinSearch() {
	    $aField['reg_nr']=array('title'=>'reg_nr','type'=>'input','name'=>'reg_nr','value'=>Base::$aRequest['reg_nr']);
	    $aData=array(
	        'sHeader'=>"method=post",
	        'sTitle'=>"reg_nr",
	        'aField'=>$aField,
	        'bType'=>'generate',
	        'sSubmitButton'=>'Apply',
	        'sSubmitAction'=>'test_vin_search',
	    );
	    $oForm=new Form($aData);
	    Base::$sText.=$oForm->getForm();
	    
	    if(Base::$aRequest['is_post']) {
	        include_once 'single/restclient.php';
	         
	        $api = new RestClient([
	            'base_url' => "https://opendatabot.com/api/v2",
	        ]);
	        $result = $api->get("transport-passports", array(
	            'apiKey' => "TACRaXT5t46W",
	            'number' => Base::$aRequest['reg_nr']
	        ));
	         
	        if($result->info->http_code == 200) {
	            $oResponse=$result->decode_response();
	            $sVin=$oResponse->data->items[0]->vin;

	            Base::$sText.="<h3>".$sVin."</h3><br>";
	            
	            $oVinDecode = new VinDecode();
	            $oVinDecode->DecodeVin($sVin);
	        }
	        
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function PrintTable($aData,$sTitle='') {
	    if($aData) {
	        if($sTitle) {
	            $sTable="<h1>".$sTitle."</h1>";
	        }
	        
	        $aHead=array_keys($aData[0]);
	        
	        $sTable.="
	           <table width='100%' class='at-table'>
                   <tr>
            ";
	        foreach ($aHead as $sValueHead) {
	            $sTable.="<th>".$sValueHead."</th>";
	        }
	        $sTable.="</tr>";
	        
	        foreach ($aData as $aValue) {
	            $sTable.="<tr>";
	            foreach ($aValue as $sTableTd) {
	                $sTable.="<td>".$sTableTd."</td>";
	            }
	            $sTable.="</tr>";
	        }
	        
	        $sTable.="</table><br><br>";
                   
           Base::$sText.=$sTable;
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function GenerateRubricator() {
        $sSql="(select t.ID_src,
               t.ID_src as id,
		       t.Level+1 level,
		       t.Name as name,
		       t.ID_parent id_parent
			from ".DB_OCAT."cat_alt_tree t
			where 1=1
			order by t.ID_src)";
        
        $aTreeAll=TecdocDb::GetAssoc($sSql);
        
       
        $a10001=array();
        foreach ($aTreeAll as $aValue) {
            if($aValue['id_parent']=='10001') {
                $a10001[$aValue['id']]=$aValue;
            }
        }

        foreach ($aTreeAll as $aValue) {
            if(in_array($aValue['id_parent'], array_keys($a10001))) {
                $a10001[$aValue['id_parent']]['childs'][$aValue['id']]=$aValue;
            }
        }
        
        Db::Execute("truncate table rubricator");
        
        foreach ($a10001 as $aValue) {
            Db::Execute("insert into rubricator (id,name,level,id_parent,visible,url,is_mainpage) values 
                ('".$aValue['id']."','".$aValue['name']."','1','0','1','r".$aValue['id']."','1') ");
            
            if($aValue['childs']) {
                foreach ($aValue['childs'] as $aChild) {
                    Db::Execute("insert into rubricator (id,name,level,id_parent,visible,url,is_mainpage,id_tree) values
                ('".$aChild['id']."','".$aChild['name']."','2','".$aChild['id_parent']."','1','r".$aChild['id']."','1','".$aChild['id']."') ");
                }
            }
            
        }

        $aRubrics=Db::GetAll("select * from rubricator where level='2'  ");

        foreach ($aRubrics as $aValue) {
            foreach ($aTreeAll as $atr) {
                if($aValue['id']==$atr['id_parent']) {
                    Db::Execute("insert into rubricator (id,name,level,id_parent,visible,url,id_tree) values 
                        ('".$atr['id']."','".$atr['name']."','3','".$aValue['id']."','1','r".$atr['id']."','".$atr['id']."') ");
                }
            }
        }
	}
	//-----------------------------------------------------------------------------------------------
	public function SetElitParams() {
		set_time_limit(0);
		$aItems=Db::GetAll("select * from elit_params where done='1'");
		$aPref=Base::$db->getAssoc("select UPPER(cp.name),c.pref from cat_pref cp inner join cat c on c.id=cp.cat_id");
		
		foreach ($aItems as $aValue) {
			//insert cat_part
				
			$sPref=$aPref[$aValue['brand']];
			$iCatPartId=Db::GetOne("select id from cat_part where item_code='".$sPref.'_'.$aValue['code']."' ");

			if($iCatPartId) {
				Db::AutoExecute("cat_info",array(
					'id_cat_part'=>$iCatPartId, 
					'name'=>$aValue['name_ru'], 
					'code'=>$aValue['value']
				));
				
				Db::Execute("update elit_params set done='2' where id='".$aValue['id']."' ");
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function SetElitImage() {
		set_time_limit(0);
		$aItems=Db::GetAll("select * from elit_image where done='1'");
		$aPref=Base::$db->getAssoc("select UPPER(cp.name),c.pref from cat_pref cp inner join cat c on c.id=cp.cat_id");
		
		foreach ($aItems as $aValue) {
			//insert cat_part
			
			$sPref=$aPref[$aValue['brand']];
			
			if($sPref) {
				Db::Execute("insert ignore into cat_part (item_code,code,pref) values ('".$sPref.'_'.$aValue['code']."','".$aValue['code']."','".$sPref."') ");
				
				$iCatPartId=Db::GetOne("select id from cat_part where item_code='".$sPref.'_'.$aValue['code']."' ");
				if($iCatPartId) {
					//add image to cat_pic
					$aFilePart = pathinfo(SERVER_PATH.$aValue['path']);
					
					Db::Autoexecute('cat_pic',array(
						'id_cat_part'=>$iCatPartId,
						'image'=>$aValue['path'],
						'pic'=>$aFilePart['basename'],
						'extension'=>$aFilePart['extension']
					));
					
					Db::Execute("update elit_image set done='2' where id='".$aValue['id']."' ");
				}
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function GetElitImages(){
		set_time_limit(0);
		$aItems=Db::GetAll("select * from elit_image where done='0'");
		$sImagePath="/imgbank/Image/elit_pic/";
		if (!file_exists(SERVER_PATH.$sImagePath)) {
			mkdir(SERVER_PATH.$sImagePath, true);
		}
		
		foreach ($aItems as $aValue) {
			
			$handle = @fopen($aValue['image'], "rb");
			$sContents = stream_get_contents($handle);
			fclose($handle);
			
			if($sContents){
				$aPathInfo=end(explode(".", $aValue['image']));
				$sFilename = $aValue['brand']."_".$aValue['code'].'.'.$aPathInfo;
				$handle = fopen(SERVER_PATH.$sImagePath.$sFilename, 'wb');
				fwrite($handle, $sContents);
				fclose($handle);
				
				Db::Execute("update elit_image set done='1', path='".$sImagePath.$sFilename."' where id='".$aValue['id']."' ");
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function ProcessExcel($iTimer) {
		set_time_limit(0);
		
		$sFileName=SERVER_PATH."/imgbank/temp_upload/price.xlsx";
		$sFileNameOut=SERVER_PATH."/imgbank/temp_upload/price_out.csv";
		
		$oExcel = new Excel();
		$oExcel->ReadExcel7($sFileName,true,false);
		$oExcel->SetActiveSheetIndex();

		ini_set('soap.wsdl_cache_enabled',0);
		ini_set('soap.wsdl_cache_ttl',0);
		$sSID=Test::GetSessionID();
		
		$iMaxRows=$oExcel->GetActiveSheet()->getHighestRow();
		Exchange::PrintFlush2("<br>Excel readed = ".$iMaxRows);
		
		$fp = fopen($sFileNameOut, 'w');
		$aHeader=array(
			"#",
			"brand",
			"code",
			"price",
			"stock",
		);
		fputcsv($fp, $aHeader,";");
		
		for ($i = 2; $i < $iMaxRows; $i++) {
			$sBrand = $oExcel->GetActiveSheet()->getCellByColumnAndRow(0, $i)->getValue();
			$sCode = $oExcel->GetActiveSheet()->getCellByColumnAndRow(1, $i)->getValue();
			
			$aPrice=Test::GetCodePrice($sSID,$sCode,$sBrand);
			//$oExcel->setCellValue('C'.$i, $aPrice['price']);
			//$oExcel->setCellValue('D'.$i, $aPrice['avail']);
			
			fputcsv($fp, array(
				$i-1,$sBrand,$sCode,$aPrice['price'],$aPrice['avail']
			),";");
			
			//Exchange::PrintFlush2("<br>Row: ".$i." ".$sBrand." ".$sCode." ".print_r($aPrice,true));
			Exchange::PrintFlush2("<br>#".($i-1)."  Time ".round(microtime(true)-$iTimer,3)." percent ".round(($i/$iMaxRows)*100)."%");
			
			sleep(2);
		}
		
		//$oExcel->WriterExcel7($sFileNameOut);
		
		fclose($sFileNameOut);
	}
	//-----------------------------------------------------------------------------------------------
	public function GetPriceTableByCode() {
		ini_set('soap.wsdl_cache_enabled',0);
		ini_set('soap.wsdl_cache_ttl',0);
		$sSid=Test::GetSessionID();
		$aCodes=Test::GetCodesByCode($sSid,Base::$aRequest['code']?Base::$aRequest['code']:'600001600');
		
		$aItem=array();
		foreach ($aCodes as $aValue) {
		$aResult=Test::GetPriceByCode($aValue['info']);
			
		unset($aValue['info']);
		$aItem[]=array_merge($aResult,$aValue);
		}
		
		
		$sText.="<table class='datatable'>";
		
		if($aItem[0]) $aHeader=array_keys($aItem[0]);
		if($aHeader) foreach ($aHeader as $sCol){
		$sText.="<th>".$sCol."</th>";
		}
		
		if($aItem) foreach ($aItem as $aValue) {
		$sText.="<tr>";
		
		if($aValue) foreach ($aValue as $sCol) {
		if(is_array($sCol)){
		$sText.="<td><table>";
		foreach ($sCol as $aSubcol) {
		$sText.="<tr>";
		
		foreach ($aSubcol as $sSubcol) {
		if(!is_object($sSubcol)) $sText.="<td>".$sSubcol."</td>";
		}
		
		$sText.="</tr>";
		}
		$sText.="</table></td>";
		} else {
		$sText.="<td>".$sCol."</td>";
		}
		}
		
		$sText.="</tr>";
		}
		
		$sText.="</table><br>";
		Base::$sText.=$sText;
	}
	//-----------------------------------------------------------------------------------------------
	public function GetSessionID() {
		$client = new SoapClient("http://wsvc11.carparts-cat.com/v31/login.asmx?WSDL", 	array('encoding'=>'utf-8','connection_timeout' => 1) );
	
		$aParams=array(
			'Username'=>'cormar',
			'Password'=>'12565521',
			'KatalogId'=>'137',
			'LanguageId'=>'16',
		);
		
		try{
			$oResponse=$client->GetSession($aParams);
		}catch(SoapFault $e){
			Debug::PrintPre($e,false);
		}
		
		return $oResponse->GetSessionResult->Item;
	}
	//-----------------------------------------------------------------------------------------------
	public function GetCodePrice($sSID,$sCode,$sBrand='') {
		//filter by brand
		switch ($sBrand) {
			case 'NGK':
				$sCode=str_replace("NGK", "", $sCode);
				break;
				
			case "Robert Bosch":
				$sBrand="Bosch";
				break;
				
			case "KYB":
				$sCode=str_replace("K", "", $sCode);
				break;
				
			case "Febi":
				$sBrand="FEBI BILSTEIN";
				break;
				
		}
		
		$client = new SoapClient("http://wsvc11.carparts-cat.com/v31/Parts.asmx?WSDL", 	array('encoding'=>'utf-8','connection_timeout' => 1) );
		$ns="http://tempuri.org/";
		$headerbody = array(
			'_SID' => $sSID,
		);
	
		$header = new SOAPHeader($ns, 'ManagedSoapHeader', $headerbody);
		$client->__setSoapHeaders($header);
	
		$aParams=array(
			'SprNr'=>'16',
			'SuchStr'=>$sCode,
			'Mode'=>'2',
			'KatTyp'=>'1',
			'HKatNr'=>'112',
			'FltNr'=>'64',
			'Lkz'=>'RO',
			'Wkz'=>'RON',
		);
		
		try{
			$oResponse=$client->GetArtVglNr($aParams);
		}catch(SoapFault $e){
			Debug::PrintPre($e,false);
		}
	
		$aResult=array();
		if($oResponse->GetArtVglNrResult->Items->OutPartsArticle)
		foreach ($oResponse->GetArtVglNrResult->Items->OutPartsArticle as $oValue) {
			if($oValue->KARTNR){
				//Debug::PrintPre($oValue,false);
				
				if(Catalog::StripCode($oValue->EARTNR)==Catalog::StripCode($sCode) && strtoupper($sBrand)==strtoupper($oValue->EINSPBEZ)) {
					$aResult=array(
						'info'=>array(
							'WholesalerArtNr'=>$oValue->KARTNR,
							'EinspNr'=>$oValue->EINSPNR,
							'EinspArtNr'=>$oValue->EARTNR,
							'RequestedQuantity'=>array('Value'=>"1"),
							'AvailState'=>'unbekannt',
						),
						'brand'=>$oValue->EINSPBEZ,
						'image'=>"<img src=\"".$oValue->THUMB."\">",
						'name'=>$oValue->GENBEZ,
						'status'=>$oValue->ARTSTATBEZ
					);
					break;
				}
			}
		}
		if($aResult['info']) $aPrice=Test::GetPriceByCode($aResult['info']);
			
		unset($aResult['info']);
		$aResult['avail']=$aPrice['stock'];
		$aResult['price']=$aPrice['price'][0]['Value'];
		$aResult['code']=$aPrice['Code'];
		$aResult['art']=$aPrice['Art'];
	
		//Debug::PrintPre($aResult,false);
		return $aResult;
	}
	//-----------------------------------------------------------------------------------------------
	public function GetCodesByCode($sSID,$sCode) {
		$client = new SoapClient("http://wsvc11.carparts-cat.com/v31/Parts.asmx?WSDL", 	array('encoding'=>'utf-8','connection_timeout' => 2) );
		
		$ns="http://tempuri.org/";
		
		$headerbody = array(
			'_SID' => $sSID,
		);
		
		//Create Soap Header.
		$header = new SOAPHeader($ns, 'ManagedSoapHeader', $headerbody);
		
		//set the Headers of Soap Client.
		$client->__setSoapHeaders($header);
		
		$aParams=array(
			'SprNr'=>'16',
			'SuchStr'=>$sCode,
			'Mode'=>'2',
			'KatTyp'=>'1',
			'HKatNr'=>'112',
			'FltNr'=>'64',
			'Lkz'=>'RO',
			'Wkz'=>'RON',
		);
		$oResponse=$client->GetArtVglNr($aParams);
		
		$aResult=array();
		foreach ($oResponse->GetArtVglNrResult->Items->OutPartsArticle as $oValue) {
			if($oValue->KARTNR){
				//Debug::PrintPre($oValue);
				$aResult[]=array(
					'info'=>array(
							'WholesalerArtNr'=>$oValue->KARTNR,
							'EinspNr'=>$oValue->EINSPNR,
							'EinspArtNr'=>$oValue->EARTNR,
							'RequestedQuantity'=>array('Value'=>"50"),
							'AvailState'=>'unbekannt',
					),
					'Brand'=>$oValue->EINSPBEZ,
					'Image'=>"<img src=\"".$oValue->THUMB."\">",
					'Name'=>$oValue->GENBEZ,
					'Text'=>$oValue->TXT_ARTINF,
					'Status'=>$oValue->ARTSTATBEZ
				);
			}
		}
		
		return $aResult;
	}
	//-----------------------------------------------------------------------------------------------
	public function GetPriceByCode($aCodes) {
		$client = new SoapClient("http://ws.autototal.ro/DVSE.WebApp.ErpService/ATTErp.asmx?WSDL", 
				array(
						'encoding'=>'utf-8',
						'connection_timeout' => 1,
						'proxy_host'     => "ws.autototal.ro",
                        'proxy_port'     => 30080,
						"trace" => 1,
						"exceptions" => 1,
                       'location'=>'http://ws.autototal.ro/DVSE.WebApp.ErpService/ATTErp.asmx'
				) 
		);
		
		$aParams=array(
			'user'=>array(
				'CustomerId'=>'1381',
				'PassWord'=>'12565521',
				'UserName'=>'cormar',
				),
			'items'=>array(
				'Item'=>array($aCodes)
			),
		);
		
		
		try{
			$oResponse=$client->GetArticleInformation($aParams);
		}catch(SoapFault $e){
			Debug::PrintPre($e,false);
		}		
		
		$aResult=array();
		//foreach ($oResponse->GetArticleInformationResult->Items->Item->Item as $oValue) {
		$oValue=$oResponse->GetArticleInformationResult->Items->Item->Item;
		{
			//Debug::PrintPre($oValue,false);
			
			$aPrice=array();
			if($oValue->Prices->Price) {
				$aPriceArray=(array)$oValue->Prices->Price;
				
				$aKeys=array_keys($aPriceArray);
				$aKeys=array_flip($aKeys);
				if(isset($aKeys['Text'])) {
					//single array, need convert
					$aPrice[]=$aPriceArray;
				} else {
					//multi object, need convert
					foreach ($aPriceArray as $aPriceObj) $aPrice[]=(array)$aPriceObj;
				}
			}
			
			$aResult=array(
				'Code'=>$oValue->EinspArtNr,
				'Art'=>$oValue->WholesalerArtNr,
				'Avail'=>$oValue->AvailState,
				'price'=>$aPrice
			);
			
			if($oValue->AvailState=='alternativlagerverfuegbar' || $oValue->AvailState=='verfuegbar') {
				$aQuantity=array();
				$aQuantityArray=(array)$oValue->Quantity->Quantity;
				
				$aKeys=array_keys($aQuantityArray);
				$aKeys=array_flip($aKeys);
				if(isset($aKeys['Text'])) {
					//single array, need convert
					$aQuantity[]=$aQuantityArray;
				} else {
					//multi object, need convert
					foreach ($aQuantityArray as $aQntObj) $aQuantity[]=(array)$aQntObj;
				}
			}
			
			if($aQuantity[0]['Value']) $aResult['stock']=$aQuantity[0]['Value'];
			else $aResult['stock']=0;
		}
	
		return $aResult;
	}
	//-----------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------
	public function UpdateCat()
	{
	   $sSql = "SELECT * FROM opti_sup s GROUP BY s.Search ORDER BY s.ID_src";
	   $sSqlMan = "SELECT * FROM opti_mfa ORDER BY ID_src";
	   $aUnknown = Db::GetAll($sSql);
	   $aUnknownMan = Db::GetAll($sSqlMan);
	   
	   $is_lower = 0;
	   if (Language::getConstant('admin_regulations:cat_name_is_lower','1'))
	       $is_lower = 1;
	   
	   if ($aUnknown) {
	       foreach ($aUnknown as $aValue) {
	           if ($is_lower)
	               $sNameFiltered = mb_strtolower(str_replace(array(' ','-','#','.','/',',','_',':','[',']','(',')','*','&','+','`','\'','"','\\','<','>','?','!','$','%','^','@','~','|','=',';','{','}','№'), '',trim(Content::Translit($aValue['Search']))),'UTF-8');
	           else
	               $sNameFiltered = mb_strtoupper(str_replace(array(' ','-','#','.','/',',','_',':','[',']','(',')','*','&','+','`','\'','"','\\','<','>','?','!','$','%','^','@','~','|','=',';','{','}','№'), '',trim(Content::Translit($aValue['Search']))),'UTF-8');
	   
	           $aRow = Db::GetRow("Select * from cat where name='".$sNameFiltered."'");
	   
	           // change id_sup
	           if ($aRow)
	               Db::Execute("Update cat set id_sup = ".$aValue['ID_src']." where id=".$aRow['id']);
	           else {
	               // generate pref
	               $sPref=String::GeneratePref();
	               $aInsertData=array(
	                   'pref'=>$sPref,
	                   'name'=>$sNameFiltered,
	                   'title'=>str_replace("'","`",$aValue['Name']),
	                   'id_sup'=>$aValue['ID_src'],
	               );
	               Db::AutoExecute("cat", $aInsertData);
	               $iCatId=Db::InsertId();
	               $aCatPref = Db::GetRow("Select * from cat_pref where name='".$sNameFiltered."'");
	               if ($aCatPref)
	                   DB::Execute("update cat_pref set cat_id=".$iCatId." where id=".$aCatPref['id']);
	               else
	                   DB::Execute("insert into cat_pref (name, cat_id) values ('".$sNameFiltered."','".$iCatId."')");
	           }
	       }
	   }
	   $iTotalUpdate = count($aUnknown);
	   if ($aUnknownMan) {
	       foreach ($aUnknownMan as $aValue) {
	           if ($is_lower)
	               $sNameFiltered = mb_strtolower(str_replace(array(' ','-','#','.','/',',','_',':','[',']','(',')','*','&','+','`','\'','"','\\','<','>','?','!','$','%','^','@','~','|','=',';','{','}','№'), '',trim(Content::Translit($aValue['Name']))),'UTF-8');
	           else
	               $sNameFiltered = mb_strtoupper(str_replace(array(' ','-','#','.','/',',','_',':','[',']','(',')','*','&','+','`','\'','"','\\','<','>','?','!','$','%','^','@','~','|','=',';','{','}','№'), '',trim(Content::Translit($aValue['Name']))),'UTF-8');
	   
	           $aRow = Db::GetRow("Select * from cat where name='".$sNameFiltered."'");
	   
	           // change id_mfa
	           if ($aRow)
	               Db::Execute("Update cat set is_brand=1, is_vin_brand=1, id_mfa = ".$aValue['ID_src']." where id=".$aRow['id']);
	           else {
	               // generate pref
	               $sPref=String::GeneratePref();
	               $aInsertData=array(
	                   'pref'=>$sPref,
	                   'name'=>$sNameFiltered,
	                   'title'=>str_replace("'","`",$aValue['Name']),
	                   'id_mfa'=>$aValue['ID_src'],
	                   'is_brand'=>1,
	                   'is_vin_brand'=>1
	               );
	               Db::AutoExecute("cat", $aInsertData);
	               $iCatId=Db::InsertId();
	               $aCatPref = Db::GetRow("Select * from cat_pref where name='".$sNameFiltered."'");
	               if ($aCatPref)
	                   DB::Execute("update cat_pref set cat_id=".$iCatId." where id=".$aCatPref['id']);
	               else
	                   DB::Execute("insert into cat_pref (name, cat_id) values ('".$sNameFiltered."','".$iCatId."')");
	           }
	       }
	   }
	   $iTotalUpdate += count($aUnknownMan);
	   
	       // update data cat
	       Db::Execute("UPDATE `cat` c SET link = (
				SELECT DISTINCT (t.WEB)
				FROM opti_sup AS t
				WHERE t.ID_src = c.id_sup ) where c.link is null");
	       $iTotalUpdateLink = Db::GetOne("SELECT ROW_COUNT()");
	   
	       Db::Execute("UPDATE `cat` c SET addres = ( SELECT DISTINCT (
				CONCAT( t.PostalCountry, ' ', t.City, ' ', t.Street ) )
				FROM opti_sup AS t
				WHERE t.ID_src = c.id_sup
				) where addres is null");
	       $iTotalUpdateAdres = Db::GetOne("SELECT ROW_COUNT()");
	   
	       Db::Execute("UPDATE `cat` c SET country = ( SELECT DISTINCT (t.PostalCountry)
				FROM opti_sup AS t
				WHERE t.ID_src = c.id_sup ) where country is null") ;
	   
	   Language::UpdateConstant('global:auto_pref_last','AAA');
	}
	//-----------------------------------------------------------------------------------------------
	public function UpdateCatModel()
	{
	    $is_lower = 0;
	    if (Language::getConstant('admin_regulations:cat_name_is_lower','1'))
	        $is_lower = 1;
	    
	    $aMfa = array();
	    $aOptiModels = Db::getAssoc("Select om.*,m.Name as brand,m.ID_src as id_mfa
	        ,SUBSTRING(om.DateStart, 1, 2) month_start,SUBSTRING(om.DateStart, 4, 4) year_start
    	    ,SUBSTRING(om.DateEnd, 1, 2) month_end,SUBSTRING(om.DateEnd, 4, 4) year_end
	        from opti_models om
	        inner join opti_mfa m on m.ID_mfa = om.ID_mfa
	        ");
	    if ($aOptiModels) {
	        $aDataInsert = array();
	        foreach ($aOptiModels as $aValue) {
	            if ($is_lower)
	                $sNameFiltered = mb_strtolower(str_replace(array(' ','-','#','.','/',',','_',':','[',']','(',')','*','&','+','`','\'','"','\\','<','>','?','!','$','%','^','@','~','|','=',';','{','}','№'), '',trim(Content::Translit($aValue['brand']))),'UTF-8');
	            else
	                $sNameFiltered = mb_strtoupper(str_replace(array(' ','-','#','.','/',',','_',':','[',']','(',')','*','&','+','`','\'','"','\\','<','>','?','!','$','%','^','@','~','|','=',';','{','}','№'), '',trim(Content::Translit($aValue['brand']))),'UTF-8');
	             
	            if (!$aMfa[$sNameFiltered])
	                $aMfa[$sNameFiltered] = $aValue['id_mfa'];
	            
	            $asExist = Db::getRow("Select * from cat_model where tof_mod_id=".$aValue['ID_src']);
	            if ($asExist) {
	                Db::Execute("Update cat_model set brand='".$aValue['brand']."',name='".mysql_real_escape_string($aValue['Name'])."',
	                    month_start='".$aValue['month_start']."',year_start='".$aValue['year_start']."',month_end='".
	                    $aValue['month_end']."',year_end='".$aValue['year_end']."',is_type_auto=1,id_mfa=".
	                    $aValue['id_mfa']." where id=".$asExist['id']);
	            }
	            else {
	                $aDataInsert[] = "('".$aValue['ID_src']."','".mysql_escape_string($aValue['brand'])."','".
	                    mysql_escape_string($aValue['Name'])."','".
	                    $aValue['month_start']."','".$aValue['year_start']."','".$aValue['month_end']."','".
	                    $aValue['year_end']."','1','".$aValue['id_mfa']."')";
	            }
	        }
	        if ($aDataInsert) {
	            Db::Execute($s="insert into cat_model (tof_mod_id,brand,name,month_start,year_start,month_end,year_end,visible, id_mfa)
					values ".implode(", ", $aDataInsert).
   					" on duplicate key update name=values(name), month_start=values(month_start), year_start=values(year_start),
					month_end=values(month_end), brand=values(brand),id_mfa=values(id_mfa)");
	            //file_put_contents("/tmp/aaa", $s);
	        }
	    }
	    $aNotExistBrand = array();
	    $aUnknownMan = Db::getAll("Select * from cat_model where id_mfa=0 group by brand");
	    if ($aUnknownMan) {
	        foreach ($aUnknownMan as $aValue) {
	            if ($is_lower)
	                $sNameFiltered = mb_strtolower(str_replace(array(' ','-','#','.','/',',','_',':','[',']','(',')','*','&','+','`','\'','"','\\','<','>','?','!','$','%','^','@','~','|','=',';','{','}','№'), '',trim(Content::Translit($aValue['brand']))),'UTF-8');
	            else
	                $sNameFiltered = mb_strtoupper(str_replace(array(' ','-','#','.','/',',','_',':','[',']','(',')','*','&','+','`','\'','"','\\','<','>','?','!','$','%','^','@','~','|','=',';','{','}','№'), '',trim(Content::Translit($aValue['brand']))),'UTF-8');
	            
	            if ($aMfa[$sNameFiltered]) {
	                Db::Execute("Update cat_model set id_mfa=".$aMfa[$sNameFiltered]." where brand='".mysql_real_escape_string($aValue['brand'])."'");
	            }
	            else {
    	            $aExist = Db::getRow("Select * from opti_mfa where Name='".mysql_real_escape_string($aValue['brand'])."'");
    	            if ($aExist) {
    	                Db::Execute("Update cat_model set id_mfa=".$aExist['ID_src']." where brand='".mysql_real_escape_string($aValue['brand'])."' and id_mfa=0");
    	            }
    	            else 
    	                $aNotExistBrand[$sNameFiltered] = $aValue['brand'];
	            }
	        }	
	    }
	    /*if ($aNotExistBrand)
	        file_put_contents("/tmp/_unk_brand_cat_model", print_r($aNotExistBrand,1));*/    	  
	}
	//-----------------------------------------------------------------------------------------------
	public function UpdateIdRubric(){
	    $aAllRubric=Db::GetAll("SELECT id, id_tree FROM rubricator ");
	    foreach ($aAllRubric as $aRubric) {
	        if($aRubric['id_tree']) {
	            $aTrees=explode(",", $aRubric['id_tree']);
	            $aTreeOut=array();
	            foreach ($aTrees as $iVal) {
	                if ($iVal)
	                   $aTreeOut[]=($iVal+89900);
	            }
	            Db::Execute("update rubricator set id_tree='".implode(",", $aTreeOut)."' where id='".$aRubric['id']."' ");
	        }
	    }
	
	}
    //-----------------------------------------------------------------------------------------------------------

    public function FixCat()
    {
        $aCats=DB::GetAssoc("SELECT id, name FROM cat group by name HAVING count(name)>1");

        foreach ($aCats as $key=>$name){
            DB::Execute("UPDATE cat SET name='".$name."1' where id='".$key."'");
        }
    }

}
?>