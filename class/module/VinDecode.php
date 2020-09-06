<?

class VinDecode extends Base
{
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
	    $sUrl=$this->DecodeVin(Base::$aRequest['vin'],false);
	    
		Base::$sText.= $sUrl;
	}
	//-----------------------------------------------------------------------------------------------
	public function DecodeVin($sVin, $bRedirect=true) {
	    $params = array(
	        'vin' => $sVin,
	        'lang' => 'ru',
	    );
	    $aVinData=Db::GetAll("select * from vin_decode where vin='".$sVin."' ");
	    if(!$aVinData) {
    	    $aRequest=$this->msoap('http://tecdoc.ru/vin/index.php',$params,1,null,'',array(),5);
    	    if ($aRequest['error']!='')
    	        return;
    	    
    	    include_once SERVER_PATH.'/lib/simple_html_dom.php';
    	     
    	    $html = str_get_html($aRequest['request']);
    	    $aH3=$html->find('h3');
    	    $sH4=$aH3[1]->innertext;
    	    $sH4=substr($sH4, (strripos($sH4, '>')+1));
    	     
    	    $aTables = $html->find('.table-bordered');
    	    
    	    if($aTables) {
    	        foreach ($aTables as $oTmpTable) {
    	            $oTable=$oTmpTable->find('tr');
    	            //     	    $oTable = $html->find('table tr');
    	            $aVinData=array();
    	            $aVinData['vin']=$sVin;
    	            $aVinData['h4']=$sH4;
    	            foreach ($oTable as $oValue) {
    	                $aTd=$oValue->find('td');
    	            
    	                $sKey=$aTd[0]->plaintext;
    	                switch ($sKey) {
    	                    case 'Производитель': $sKey='brand';break;
    	                    case 'Модель': $sKey='model';break;
    	                    case 'Название модификации': $sKey='modification';break;
    	                    case 'Года выпуска': $sKey='year';break;
    	                    case 'Код двигателя': $sKey='engine';break;
    	                    case 'Тип тормозной системы': $sKey='brake';break;
    	                    case 'Объем двиг., см3': $sKey='ccm';break;
    	                    case 'Кузов': $sKey='body';break;
    	                    case 'Количество цилиндров': $sKey='cylinder';break;
    	                    case 'Объем цилиндров, см3': $sKey='cylinder_volume';break;
    	                    case 'Тип топлива': $sKey='fuel';break;
    	                    case 'Подготовка топлива': $sKey='injection';break;
    	                    case 'Тип привода': $sKey='drive';break;
    	                    case 'Вид двигателя': $sKey='engine_type';break;
    	                    case 'Мощность, л/c': $sKey='hp';break;
    	                    case 'Мощность, кВт': $sKey='kw';break;
    	                    case 'Тоннаж': $sKey='weight';break;
    	                    case 'Количество клапанов на цилиндр': $sKey='piston';break;
    	                }
    	                $sValue=$aTd[1]->plaintext;
    	                 
    	                if($sKey=='kw' || $sKey=='hp') {
    	                    $aData = explode(" ",$sValue);
					    	$sValue=$aData[0];
					        //$sValue=substr($sValue, 0, strpos($sValue, ' ')); - error 
    	                }
    	                 
    	                $aVinData[$sKey]=$sValue;
    	            }
    	            Db::AutoExecute('vin_decode', $aVinData);
    	        }
    	    }
	    }
	    
	    $aVins=Db::GetAll("select * from vin_decode where vin='".$sVin."' ");
	    if($aVins) {
	        foreach ($aVins as $sKey => $aVinData) {
	            if (!$aVinData['brand'] && !$aVinData['model'] && !$aVinData['modification']) {
	                unset($aVins[$sKey]);
	                Db::Execute("Delete from vin_decode where id=".$aVinData['id']);
	                continue;
	            }
	            $aTmp=VinDecode::GetAutoId($aVinData);
	            $aVins[$sKey]=$aTmp;
	        }
	        if (!$aVins)
	            return false;
	        
	        foreach ($aVins as $sKey => $aVinData) {
	            $sUrl=Content::CreateSeoUrl('catalog_assemblage_view',array(
	                'data[id_model_detail]'=>$aVins[$sKey]['id_model_detail'],
	                'data[id_model]'=>$aVins[$sKey]['id_model'],
	            ));
	            $iIdMake=TecdocDb::GetIdMakeByIdModel($aVins[$sKey]['id_model']);
	            $sUrl=str_replace('/cars/', 'c/', $sUrl);
	            $sMake=Db::GetOne("select name from cat where id='".$iIdMake."' ");
	            $sMake=strtolower($sMake);
	            $sUrl=str_replace("/".$sMake."/", '/', $sUrl);
	            $sUrl="/rubricator/".$sUrl;
	            $aVins[$sKey]['url']="<a href='".$sUrl."'>Перейти в каталог</a>";
	        }

	        $aColumn['brand']=array('sTitle'=>'brand');
	        $aColumn['model']=array('sTitle'=>'model');
	        $aColumn['modification']=array('sTitle'=>'modification');
	        $aColumn['year']=array('sTitle'=>'');
	        $aColumn['engine']=array('sTitle'=>'engine');
	        $aColumn['ccm']=array('sTitle'=>'ccm');
	        $aColumn['body']=array('sTitle'=>'body');
	        $aColumn['fuel']=array('sTitle'=>'fuel');
// 	        $aColumn['injection']=array('sTitle'=>'injection');
	        $aColumn['drive']=array('sTitle'=>'drive');
	        //$aColumn['hp']=array('sTitle'=>'hp');
	        //$aColumn['kw']=array('sTitle'=>'kw');
	        $aColumn['url']=array('sTitle'=>'url');
	        
	        $oTable = new Table();
	        $oTable->aColumn=$aColumn;
	        $oTable->sType='array';
	        $oTable->aDataFoTable=$aVins;
	        $oTable->iAllRow=0;
	        $oTable->sClass.=" mobile-table price-table";
	        $oTable->bCheckVisible=false;
	        $oTable->bCheckAllVisible=false;
	        $oTable->bStepperVisible=false;
	        $oTable->bDefaultChecked=false;
	        $oTable->sDataTemplate='catalog/row_vin_decode.tpl';
	        $oTable->iRowPerPage=count($aVins);
	        
	        Base::$sText.=$oTable->GetTable();
	    }

	    if(!$aVins) {
	        Base::$sText.=Language::GetText("vin_decode:empty_data");
	        return false;
	    }
	    return true;
	}
	//-----------------------------------------------------------------------------------------------
	public function GetAutoId($aVinData) {
		$isCheckDiapasonDataAll = 1;
		find_without_end_date:
	    if(!$aVinData['id_model_detail'] || !$aVinData['id_model']) {
	        //search in tecdoc
	        $sYear=$aVinData['year'];
	        
	        if(strpos($sYear, 'по')!==false) {
	            $sYearTmp=explode("по", $sYear);
	            
	            $aYear1=explode(".", trim($sYearTmp[0]));
	            $sYear1=$aYear1[1].$aYear1[0];
	            
	            $aYear2=explode(".", trim($sYearTmp[1]));
	            $sYear2=$aYear2[1].$aYear2[0];
	            
	            if(!isset($aYear2[1])) {
	                $sYear2=0;
	            }
	        } else {
	            $sYear=substr($sYear, 0, strpos($sYear, ' '));
	            $aYear=explode(".", $sYear);
	            $sYear=$aYear[1].$aYear[0];
	        }
	        	
	        if(!$aVinData['kw']) $aVinData['kw']='%';
	        
	        $sSql="select *
    	        from ".DB_OCAT."cat_alt_types
    	        where 
            ";
	        $aSql=array();
			// not use search modification - becouse not found all variant
	        //if($aVinData['modification']) $aSql[]=" Name like '".$aVinData['modification']."' ";
	        if($aVinData['ccm']) $aSql[]=" CCM like '".$aVinData['ccm']."' ";
	        if($aVinData['drive']) $aSql[]=" Drive like '".$aVinData['drive']."' ";
	        if($aVinData['body']) $aSql[]=" Body like '".$aVinData['body']."' ";
	        
	        if($aVinData['kw'] && $aVinData['hp']) {
	            $aSql[]=" KwHp like '".$aVinData['kw']."/".$aVinData['hp']."' ";
	        } else if(!$aVinData['kw'] && $aVinData['hp']) {
	            $aSql[]=" KwHp like '%/".$aVinData['hp']."' "; 
	        } else if($aVinData['kw'] && !$aVinData['hp']) {
	            $aSql[]=" KwHp like '".$aVinData['kw']."/%' ";
	        }
	        
	        if ($isCheckDiapasonDataAll) {
		        if($sYear1 && $sYear2) {
		            $aSql[]=" (DateStart like '".$sYear1."' and DateEnd like '".$sYear2."') ";
		        } elseif($sYear1 && !$sYear2) {
		            $aSql[]=" (DateStart like '".$sYear1."' and DateEnd is null) ";
		        } elseif($sYear) {
		            $aSql[]=" (DateStart like '".$sYear."' or DateEnd like '".$sYear."') ";
		        }
	        }
	        else {
	        	if($sYear1) {
	        		$aSql[]=" (DateStart like '".$sYear1."')";
	        	} elseif($sYear) {
	        		$aSql[]=" (DateStart like '".$sYear."' or DateEnd like '".$sYear."') ";
	        	}
	        }
	        
	        if($aVinData['brand']) {
	            $iIdMfa=TecdocDb::GetOne("select ID_mfa from ".DB_OCAT."cat_alt_manufacturer where Name like '".trim($aVinData['brand'])."' ");
	            if($iIdMfa) {
	                $aSql[]=" ID_mfa='".$iIdMfa."' ";
	            }
	        }
	         
	        $aModelDetail=TecdocDb::GetAll($sSql.implode(" and ", $aSql));
	        
	        if(count($aModelDetail)==1) {
	            $aVinData['id_model_detail']=$aModelDetail[0]['ID_src'];
	            
	            $aModel=TecdocDb::GetRow("select * from ".DB_OCAT."cat_alt_models where ID_mod = '".$aModelDetail[0]['ID_mod']."' ");
	            if($aModel) {
	                Db::Execute("update vin_decode set id_model='".$aModel['ID_src']."' where id='".$aVinData['id']."' ");
	                $aVinData['id_model']=$aModel['ID_src'];
	            }
	        } else {
	        	if($aModelDetail && $aVinData['model'])
	            foreach ($aModelDetail as $aValue) {
	                if(mb_stripos($aValue['Description'], $aVinData['model'],null,'UTF-8')) {
	                    $aVinData['id_model_detail']=$aValue['ID_src'];
	                    
	                    $aModel=TecdocDb::GetRow("select * from ".DB_OCAT."cat_alt_models where ID_mod = '".$aValue['ID_mod']."' ");
	                    if($aModel) {
	                        Db::Execute("update vin_decode set id_model='".$aModel['ID_src']."' where id='".$aVinData['id']."' ");
	                        $aVinData['id_model']=$aModel['ID_src'];
	                    }
	                    break;
	                }
	            }
	            // search without DateEnd
	            else {
	            	if ($isCheckDiapasonDataAll) {
		            	$isCheckDiapasonDataAll = 0;
		            	goto find_without_end_date;
	            	}
	            }
	        }
	        
	        Db::Execute("update vin_decode set id_model_detail='".$aVinData['id_model_detail']."' where id='".$aVinData['id']."' ");
	    }
	    
	    return $aVinData;
	}
	//-----------------------------------------------------------------------------------------------
	private function msoap($sDest,$aPostField=array(),$bMethodPost=0,$oFile=null,$sReferer='',$aHeader=array(),$iTimeout=1500) {
	    if($aPostField) foreach ($aPostField as $sKey=>$sValue) {
	        $sPostField.=$sKey.'='.urlencode($sValue).'&';
	    }
	    $sPostField=rtrim($sPostField,'&');
	
	    $aResult = array();
	    $aHeader[] = "Connection: Keep-Alive";
	
	    $oCh = curl_init();
	    curl_setopt($oCh, CURLOPT_POST, $bMethodPost);
	    if($bMethodPost){
	        curl_setopt($oCh, CURLOPT_CUSTOMREQUEST, "POST");
	        curl_setopt($oCh, CURLOPT_URL, $sDest);
	        if($sPostField){
	            $aHeader[] = "Content-type: application/x-www-form-urlencoded";
	            $aHeader[] = "Content-Length: " . strlen($sPostField);
	            curl_setopt($oCh, CURLOPT_POSTFIELDS, $sPostField);
	        }
	    }else{
	        curl_setopt($oCh, CURLOPT_CUSTOMREQUEST, "GET");
	        if($sPostField) curl_setopt($oCh, CURLOPT_URL, $sDest."?".$sPostField);
	        else curl_setopt($oCh, CURLOPT_URL, $sDest);
	    }
	    curl_setopt($oCh, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt($oCh, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($oCh, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($oCh, CURLOPT_BINARYTRANSFER, 1);
	    curl_setopt($oCh, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($oCh, CURLOPT_HEADER, 0);
	    curl_setopt($oCh, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:41.0) Gecko/20100101 Firefox/41.0'); //Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.71 Safari/537.36');
	    curl_setopt($oCh, CURLOPT_TIMEOUT, $iTimeout);
	    curl_setopt($oCh, CURLOPT_COOKIEFILE, "cookie.txt");
	    curl_setopt($oCh, CURLOPT_COOKIEJAR, "cookie.txt");
	    curl_setopt($oCh, CURLOPT_HTTPHEADER, $aHeader);
	
	    if($sReferer) curl_setopt($oCh, CURLOPT_REFERER, $sReferer);
	
	    curl_setopt($oCh, CURLINFO_HEADER_OUT, true);
	
	    if($oFile!=null) curl_setopt($oCh, CURLOPT_FILE, $oFile);
	    $sRequest = curl_exec($oCh);
	
	    $info = curl_getinfo($oCh);
	
	    $aResult['error']=$info['http_code'];
	    $aResult['redirected']=$info['url'];
	    $aResult['post'] = $sPostField;
	    if(!$sRequest) $aResult['error'] = curl_error($oCh).' ('.curl_errno($oCh).')';
	    else {
	        $aResult['request'] = $sRequest;
	    }
	    curl_close($oCh);
	
	    return $aResult;
	}
	//-----------------------------------------------------------------------------------------------
}
?>