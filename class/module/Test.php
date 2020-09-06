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
	    
	    
	    
	    
	    //Test::GenerateRubricator();
	    Base::$sText.= "<br>Test module finished Ok.<br>";
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


}
?>