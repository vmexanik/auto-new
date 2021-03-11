<?

/**
 * @author Mikhail Kuleshov
 * @author Mikhail Starovoyt
 * @author Yuriy Korzun
 * @author evgeniy.lazarev
 * @version 4.5.4
 */

class ExportXml
{
	
	private $aExportXmlAssocRow;
	public $sPrefix ="export_xml";
	public $aSection = array('siteindex', 'hotline','price','prom');

	//-----------------------------------------------------------------------------------------------
	public function __construct($bNeedAuth=true)
	{
// 		if ($bNeedAuth&&Base::$aRequest['xx']!=1) Auth::NeedAuth('manager',2);
		
		Base::$bXajaxPresent=true;
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$sSql=Base::GetSql('Assoc/ExportXmlQuery',array('multiple'=>1));
		$aExportXmlAssoc=Db::GetAssoc($sSql);

		if ($this->aSection) foreach ($this->aSection as $aValue) {
			$sFilename = SERVER_PATH.'/imgbank/xml/'.$aValue.'.xml';
			if ($aValue=='hotline') {
				$aExportXmlAssocRow = $aExportXmlAssoc[$aValue];
				$sNameFile = $aExportXmlAssocRow['filename']?$aExportXmlAssocRow['filename']:'hotline';
				Base::$tpl->assign('sNameFileHotline', $sNameFile);
				Base::$tpl->assign('aHotlineRange', range(1, Base::GetConstant($this->sPrefix.':hotline_current_index')));
				$sFilename = SERVER_PATH.'/imgbank/xml/'.$sNameFile.Base::GetConstant($this->sPrefix.':hotline_current_index').'.xml';
			}
			/*if ($aValue=='yandex') {
				$sFilename = SERVER_PATH.'/imgbank/xml/market.yml';
			}
			if ($aValue=='price') {
				$aExportXmlAssocRow = $aExportXmlAssoc[$aValue];
				$sNameFile = $aExportXmlAssocRow['filename']?$aExportXmlAssocRow['filename']:'price';
				Base::$tpl->assign('sNameFilePrice', $sNameFile);
				Base::$tpl->assign('aPriceRange', range(1, Base::GetConstant($this->sPrefix.':price_current_index')));
				$sFilename = SERVER_PATH.'/imgbank/xml/'.$sNameFile.Base::GetConstant($this->sPrefix.':price_current_index').'.xml';
			}
			if ($aValue=='prom') {
				$aExportXmlAssocRow = $aExportXmlAssoc[$aValue];
				$sNameFile = $aExportXmlAssocRow['filename']?$aExportXmlAssocRow['filename']:'prom';
				Base::$tpl->assign('sNameFileProm', $sNameFile);
				Base::$tpl->assign('aPromRange', range(1, Base::GetConstant($this->sPrefix.':prom_current_index')));
				$sFilename = SERVER_PATH.'/imgbank/xml/'.$sNameFile.Base::GetConstant($this->sPrefix.':prom_current_index').'.xml';
			}*/
			if ($aValue=='siteindex') {
				Base::$tpl->assign('aSitemapBeforeRange', range(1, Base::GetConstant($this->sPrefix.':sitemap_before_current_index',21)));
				Base::$tpl->assign('aSitemapRange', range(1, Base::GetConstant($this->sPrefix.':sitemap_current_index',7)));
			}
			if (file_exists($sFilename)) {
				Base::$tpl->assign('b'.ucwords($aValue).'XmlLink', true);
				Base::$tpl->assign('s'.ucwords($aValue).'XmlDate', date("Y-m-d H:i:s", filemtime($sFilename)));
			}
		}
		Base::$sText.=Base::$tpl->fetch($this->sPrefix.'/index.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function Generate($bXajaxRequest=true)
	{
// 	    Debug::PrintPre(Base::$aRequest);
		set_time_limit(0);
		$sSql=Base::GetSql('Assoc/ExportXmlQuery',array('multiple'=>1));
		$aExportXmlAssoc=Db::GetAssoc($sSql);
		$this->aExportXmlAssocRow=$aExportXmlAssoc[Base::$aRequest['section']];
		// Base::$tpl->assign('aExportXml', $this->aExportXmlAssocRow);
	/*	$aPriceGroupAssoc=Db::GetAssoc("select expg.id_price_group as id, expg.id_export_xml as value
			from export_xml_price_group as expg
			where id_export_xml='".$this->aExportXmlAssocRow['id']."'
			");*/
		
		$aPriceGroup=Db::GetAll(Base::GetSql("Price/Group",array(
		'visible'=>1,
		'order'=>' order by pg.id_parent ',
		"where"=>" and pg.code_name is not null ",//and pg.id in (-1,".implode(',',array_keys($aPriceGroupAssoc)).")",
		)));
		//if (in_array(0,array_keys($aPriceGroupAssoc))) {
			$aPriceGroup=array(0=>array(
			'id'=>1,
			'name'=>Language::GetMessage('Without price group'),
			)
			)+$aPriceGroup;
		//}

		Base::$tpl->assign('aPriceGroup',$aPriceGroup);

		$sPriceCalc="round(if(p.price/cu.value*(1+pg.group_margin/100+0)*(100-0)/100>p.price*(1+pg.group_margin/100+0),
		p.price/cu.value*(1+pg.group_margin/100+0)*(100-0)/100, p.price/cu.value),2)";
		$sWhere='';
		if(Base::$aRequest['section']=='yandex'){
			$sWhere.=" and ".$sPriceCalc.">".Base::GetConstant("export_xml:min_price","100");
		}elseif(Base::$aRequest['section']=='price'){
			$sWhere.=" and prg.id_parent>0 ".
				" and ".$sPriceCalc.">".Base::GetConstant("export_xml:price_min_price","400");
		}else{
			$sWhere.=" and ".$sPriceCalc.">".Base::GetConstant("export_xml:min_price","100");
		}
		$sPriceSql=Base::GetSql("Catalog/Price",array(
		"where"=> $sWhere,
		"all_price_group"=> "1",
		));
		// //$sLimit=Base::GetConstant("export_xml:length","100");
		$sSimplePriceSql="select p.id, p.pref,  c.title as make, c.name as cat_name, p.code as code_, p.post_date
			, if(ifnull(cp.name_rus,'')<>'', cp.name_rus, ifnull(p.part_rus,'')) as name_translate
			, id_price_group, c.id_sup as tof_sup_id, c.id_mfa as tof_mfa_id
			, ".$sPriceCalc." as price
			".substr($sPriceSql,strpos($sPriceSql,'from price as p'));
		// Debug::PrintPre($sSimplePriceSql);
		// $sSimplePriceLimitSql=$sSimplePriceSql." limit 0, ".$sLimit;
		//$sHotlineSqlLimitSql=$sSimplePriceSql." group by p.item_code limit 0, ".Base::GetConstant("export_xml:hotline_length","1000000");

		Base::$tpl->assign('sSection', Base::$aRequest['section']);
		Base::$tpl->assign('sExt', 'xml');

		switch (Base::$aRequest['section'])
		{
			// Export for hotline.ua	
			case 'hotline':
				set_time_limit(0);
				Base::UpdateConstant($this->sPrefix.':hotline_export_running',1);
				if(Base::GetConstant("export_xml:update_table_price","1")) $this->UpdateTablePriceXml();
				// Debug::PrintPre($this->aExportXmlAssocRow);
				$iLimitHotline = $this->aExportXmlAssocRow['limit_count']?$this->aExportXmlAssocRow['limit_count']:Base::GetConstant("export_xml:hotline_length","1000000");
				$sTableNameMinPrice="export_xml_min_price";

				$sWhere='';
				// select brand
				$aBrand=Db::GetAssoc("select id_brand,id_brand as id 
					from export_xml_brand where id_export_xml='".$this->aExportXmlAssocRow['id']."' ");
				if($aBrand) $sWhere.=" and brand_id in('".implode("','", $aBrand)."') ";
				
				//select provider
				$aProvider=Db::GetAssoc("select id_provider,id_provider as id 
					from export_xml_provider where id_export_xml='".$this->aExportXmlAssocRow['id']."' ");
				if($aProvider) $sWhere.=" and id_provider in('".implode("','", $aProvider)."') ";		
				
				//select price_group
				$aPriceGroup=Db::GetAssoc("select id_price_group,id_price_group as id  
					from export_xml_price_group where id_export_xml='".$this->aExportXmlAssocRow['id']."' ");
				if($aPriceGroup) $sWhere.=" and id_price_group in('".implode("','", $aPriceGroup)."') ";
				
// 				Debug::PrintPre($sWhere);

				$sHotlineSqlLimitSql="SELECT * FROM ".$sTableNameMinPrice." WHERE 1=1 ".$sWhere." limit 0, ".$iLimitHotline;
				// Debug::PrintPre($sHotlineSqlLimitSql);
				$aImagesAssoc = Db::GetAssoc("SELECT item_code, img_path FROM export_xml_image WHERE img_path<>'".Language::GetConstant('export_xml:default_image','AвтоКарта')."'");
				
				Base::$tpl->assign('sCurrentDate', date('Y-m-d H:i'));

				$sContentBegin=Base::$tpl->fetch($this->sPrefix.'/hotline_xml_begin.tpl');
				$sContentEnd=Base::$tpl->fetch($this->sPrefix.'/hotline_xml_end.tpl');
				$iHotlinePortion=Base::GetConstant($this->sPrefix.':hotline_portion',10000);

				$sNameFile = $this->aExportXmlAssocRow['filename']?$this->aExportXmlAssocRow['filename']:'hotline';
				// Debug::PrintPre($sFileName);

				$i=1;
				do {
				    $sFilename = SERVER_PATH.'/imgbank/xml/'.$sNameFile.$i++.'.xml';
				} while (file_exists($sFilename)?unlink($sFilename):false);

				$iCurrentIndex=1;
				$i=1;
				$sFilename = SERVER_PATH.'/imgbank/xml/'.$sNameFile.$iCurrentIndex.'.xml';

				$aResult = mysql_query($sHotlineSqlLimitSql, Base::$db->_connectionID);

				$count=0;

				$oFile = fopen($sFilename, 'w');
				fwrite($oFile, $sContentBegin);
				if($aResult)
				while ($aValue = mysql_fetch_assoc($aResult)) {
					set_time_limit(0);
					$count++;

					$sImage = $this->GetImagesXml($aImagesAssoc, $aValue['item_code'], $aValue);
					//-------------------------------------------------------------------
					$trans = array("\x1C" => ".");
					$orig_text = htmlspecialchars(strip_tags($aValue['name_translate']));
					$sName = strtr($orig_text,$trans);
					//-------------------------------------------------------------------
					// Stock
					if((!$aValue['stock_filtered'] || $aValue['stock_filtered'] ===0) && $aValue['term']>1){
						$sStock = '<stock days="'.$aValue['term'].'">Под заказ</stock>';
					}
					else $sStock = '<stock>В наличии</stock>';
					
					//-------------------------------------------------------------------
					//-------------------------------------------------------------------
					// Description
					Base::$tpl->assign('name', $sName);
					Base::$tpl->assign('brand', htmlspecialchars($aValue['cat_name']));
					Base::$tpl->assign('code', $aValue['code_']);
					Base::$tpl->assign('sSmartyTemplate', $this->aExportXmlAssocRow['description']);
					$sDescription = Base::$tpl->fetch('addon/smarty_template.tpl');
					//--------------------------------------------------------------------
					if(!$aValue['id_price_group'] || $aValue['id_price_group']==0) $aValue['id_price_group'] = 1;
					$i++;
					$sContentRow="<item>
							<id>".$aValue['id']."</id>
							<categoryId>".$aValue['id_price_group']."</categoryId>
							<code>".$aValue['code_']."</code>
							<vendor><![CDATA[".htmlspecialchars($aValue['cat_name'])."]]></vendor>
							<name><![CDATA[".$sName."]]></name>
							<description><![CDATA[".$sDescription."]]></description>
							<url>".$this->GetLink($aValue)."</url>
							<image>".$sImage."</image>
							<priceRUAH>".ceil($aValue['min_price'])."</priceRUAH>
							<priceOUSD></priceOUSD>
							".$sStock."
							<guarantee>12</guarantee>
						</item>";
					fwrite($oFile, $sContentRow);

					if (!($i % $iHotlinePortion)) {
						fwrite($oFile, $sContentEnd);
						fclose($oFile);
						$iCurrentIndex++;
						$sFilename = SERVER_PATH.'/imgbank/xml/'.$sNameFile.$iCurrentIndex.'.xml';

						$oFile = fopen($sFilename, 'w');
						fwrite($oFile, $sContentBegin);
					}
				}
				fwrite($oFile, $sContentEnd);
				fclose($oFile);

				Base::UpdateConstant($this->sPrefix.':hotline_current_index',$iCurrentIndex);
				Base::UpdateConstant($this->sPrefix.':hotline_count',$count);
				Base::UpdateConstant($this->sPrefix.':hotline_export_running',0);
				if ($bXajaxRequest) {
					if (file_exists($sFilename)) {
						$iCurrentIndex = Base::GetConstant($this->sPrefix.':hotline_current_index');
						$sFilename = SERVER_PATH.'/imgbank/xml/'.$sNameFile.$iCurrentIndex.'.xml';
						Base::$tpl->assign('sSection', $sNameFile);
						Base::$tpl->assign('aRange', range(1, $iCurrentIndex));

						Base::$oResponse->AddAssign('xml_link_hotline_id','innerHTML',
						Base::$tpl->fetch($this->sPrefix.'/file_link.tpl'));
						$sDate = date ("Y-m-d H:i:s", filemtime($sFilename));
						Base::$oResponse->AddAssign('xml_date_hotline_id','innerHTML','count '.$count.' '.$sDate);
					}
					Base::$oResponse->AddScript("$('#button_hotline_id').toggle(500); $('#image_hotline_id').toggle(500);");
				}
				break;
				
			// Export for price.ua	
			/*case 'price':

				set_time_limit(0);
				Base::UpdateConstant($this->sPrefix.':price_export_running',1);
				if(Base::GetConstant("export_xml:update_table_price","1")) $this->UpdateTablePriceXml();

				$iLimitPrice = $this->aExportXmlAssocRow['limit_count']?$this->aExportXmlAssocRow['limit_count']:Base::GetConstant("export_xml:price_length","1000000");
				$sTableNameMinPrice="export_xml_min_price";
				
				$sWhere='';
				// select brand
				$aBrand=Db::GetAssoc("select id_brand,id_brand as id 
					from export_xml_brand where id_export_xml='".$this->aExportXmlAssocRow['id']."' ");
				if($aBrand) $sWhere.=" and brand_id in('".implode("','", $aBrand)."') ";
				
				//select provider
				$aProvider=Db::GetAssoc("select id_provider,id_provider as id 
					from export_xml_provider where id_export_xml='".$this->aExportXmlAssocRow['id']."' ");
				if($aProvider) $sWhere.=" and id_provider in('".implode("','", $aProvider)."') ";		
				
				//select price_group
				$aPriceGroup=Db::GetAssoc("select id_price_group,id_price_group as id  
					from export_xml_price_group where id_export_xml='".$this->aExportXmlAssocRow['id']."' ");
				if($aPriceGroup) $sWhere.=" and id_price_group in('".implode("','", $aPriceGroup)."') ";
				
				// Debug::PrintPre($sWhere);

				$sPriceSqlLimitSql="SELECT * FROM ".$sTableNameMinPrice." WHERE 1=1 ".$sWhere." limit 0, ".$iLimitPrice;
				// Debug::PrintPre($sPriceSqlLimitSql);
				$aImagesAssoc = Db::GetAssoc("SELECT item_code, img_path FROM export_xml_image WHERE img_path<>'".Language::GetConstant('export_xml:default_image','http://maxdrive.mstarproject.com/image/design/site_logo.png')."'");
				
				Base::$tpl->assign('sCurrentDate', date('Y-m-d H:i'));

				$sContentBegin=Base::$tpl->fetch($this->sPrefix.'/price_xml_begin.tpl');
				$sContentEnd=Base::$tpl->fetch($this->sPrefix.'/price_xml_end.tpl');
				$iPricePortion=Base::GetConstant($this->sPrefix.':price_portion',$iLimitPrice);

				$sNameFile = $this->aExportXmlAssocRow['filename']?$this->aExportXmlAssocRow['filename']:'price';
				// Debug::PrintPre($sFileName);

				$i=1;
				do {
				    $sFilename = SERVER_PATH.'/imgbank/xml/'.$sNameFile.$i++.'.xml';
				} while (file_exists($sFilename)?unlink($sFilename):false);

				$iCurrentIndex=1;
				$i=1;
				$sFilename = SERVER_PATH.'/imgbank/xml/'.$sNameFile.$iCurrentIndex.'.xml';

				$aResult = mysql_query($sPriceSqlLimitSql, Base::$db->_connectionID);

				$count=0;

				$oFile = fopen($sFilename, 'w');
				fwrite($oFile, $sContentBegin);
				if($aResult)
				while ($aValue = mysql_fetch_assoc($aResult)) {
					set_time_limit(0);
					$count++;

					$sImage = $this->GetImagesXml($aImagesAssoc, $aValue['item_code'], $aValue);
					//-------------------------------------------------------------------
					$trans = array("\x1C" => ".");
					$orig_text = htmlspecialchars(strip_tags($aValue['name_translate']));
					$sName = strtr($orig_text,$trans);
					//-------------------------------------------------------------------
					// Stock
					if((!$aValue['stock_filtered'] || $aValue['stock_filtered'] ===0) && $aValue['term']>1){
						$sStock = '<available>Заказ</available>';
					}
					else $sStock = '<available>Склад</available>';
					
					//-------------------------------------------------------------------
					//-------------------------------------------------------------------
					// Description
					Base::$tpl->assign('name', $sName);
					Base::$tpl->assign('brand', htmlspecialchars($aValue['cat_name']));
					Base::$tpl->assign('code', $aValue['code_']);
					Base::$tpl->assign('sSmartyTemplate', $this->aExportXmlAssocRow['description']);
					$sDescription = Base::$tpl->fetch('addon/smarty_template.tpl');
					//--------------------------------------------------------------------
					if(!$aValue['id_price_group'] || $aValue['id_price_group']==0) $aValue['id_price_group'] = 1;
					$i++;
					$sContentRow = '
					<item id="'.$aValue['id'].'">
						<name><![CDATA['.$sName.']]></name>
						<categoryId>'.$aValue['id_price_group'].'</categoryId>
						<priceuah>'.ceil($aValue['min_price']).'</priceuah>
						<url>'.$this->GetLink($aValue).'</url>
						<image>'.$sImage.'</image>
						<vendor><![CDATA['.htmlspecialchars($aValue['cat_name']).']]></vendor>
						<code>'.$aValue['code_'].'</code>
						<description><![CDATA['.$sDescription.']]></description>
						<warranty>12</warranty>
						'.$sStock.'
					</item>';
					fwrite($oFile, $sContentRow);

					if (!($i % $iPricePortion)) {
						fwrite($oFile, $sContentEnd);
						fclose($oFile);
						$iCurrentIndex++;
						$sFilename = SERVER_PATH.'/imgbank/xml/'.$sNameFile.$iCurrentIndex.'.xml';

						$oFile = fopen($sFilename, 'w');
						fwrite($oFile, $sContentBegin);
					}
				}
				fwrite($oFile, $sContentEnd);
				fclose($oFile);

				Base::UpdateConstant($this->sPrefix.':price_current_index',$iCurrentIndex);
				Base::UpdateConstant($this->sPrefix.':price_count',$count);
				Base::UpdateConstant($this->sPrefix.':price_export_running',0);
				if ($bXajaxRequest) {
					if (file_exists($sFilename)) {
						$iCurrentIndex = Base::GetConstant($this->sPrefix.':price_current_index');
						$sFilename = SERVER_PATH.'/imgbank/xml/'.$sNameFile.$iCurrentIndex.'.xml';
						Base::$tpl->assign('sSection', $sNameFile);
						Base::$tpl->assign('aRange', range(1, $iCurrentIndex));

						Base::$oResponse->AddAssign('xml_link_price_id','innerHTML',
						Base::$tpl->fetch($this->sPrefix.'/file_link.tpl'));
						$sDate = date ("Y-m-d H:i:s", filemtime($sFilename));
						Base::$oResponse->AddAssign('xml_date_price_id','innerHTML','count '.$count.' '.$sDate);
					}
					Base::$oResponse->AddScript("$('#button_price_id').toggle(500); $('#image_price_id').toggle(500);");
				}
				break;
			// Export for prom.ua	
			case 'prom':

				set_time_limit(0);
				Base::UpdateConstant($this->sPrefix.':prom_export_running',1);
				if(Base::GetConstant("export_xml:update_table_price","1")) $this->UpdateTablePriceXml();

				$iLimitProm = $this->aExportXmlAssocRow['limit_count']?$this->aExportXmlAssocRow['limit_count']:Base::GetConstant("export_xml:prom_length","1000000");
				$sTableNameMinPrice="export_xml_min_price";

				$sWhere='';
				// select brand
				$aBrand=Db::GetAssoc("select id_brand,id_brand as id 
					from export_xml_brand where id_export_xml='".$this->aExportXmlAssocRow['id']."' ");
				if($aBrand) $sWhere.=" and brand_id in('".implode("','", $aBrand)."') ";
				
				//select provider
				$aProvider=Db::GetAssoc("select id_provider,id_provider as id 
					from export_xml_provider where id_export_xml='".$this->aExportXmlAssocRow['id']."' ");
				if($aProvider) $sWhere.=" and id_provider in('".implode("','", $aProvider)."') ";		
				
				//select price_group
				$aPriceGroup=Db::GetAssoc("select id_price_group,id_price_group as id  
					from export_xml_price_group where id_export_xml='".$this->aExportXmlAssocRow['id']."' ");
				if($aPriceGroup) $sWhere.=" and id_price_group in('".implode("','", $aPriceGroup)."') ";
				
				// Debug::PrintPre($sWhere);

				$sPromSqlLimitSql="SELECT * FROM ".$sTableNameMinPrice." WHERE 1=1 ".$sWhere." limit 0, ".$iLimitProm;
				// Debug::PrintPre($sPriceSqlLimitSql);
				$aImagesAssoc = Db::GetAssoc("SELECT item_code, img_path FROM export_xml_image WHERE img_path<>'".Language::GetConstant('export_xml:default_image','http://maxdrive.mstarproject.com/image/design/site_logo.png')."'");
				
				Base::$tpl->assign('sCurrentDate', date('Y-m-d H:i'));

				$sContentBegin=Base::$tpl->fetch($this->sPrefix.'/prom_xml_begin.tpl');
				$sContentEnd=Base::$tpl->fetch($this->sPrefix.'/prom_xml_end.tpl');
				$iPromPortion=Base::GetConstant($this->sPrefix.':prom_portion',1000000);

				$sNameFile = $this->aExportXmlAssocRow['filename']?$this->aExportXmlAssocRow['filename']:'prom';
				// Debug::PrintPre($sFileName);

				$i=1;
				do {
				    $sFilename = SERVER_PATH.'/imgbank/xml/'.$sNameFile.$i++.'.xml';
				} while (file_exists($sFilename)?unlink($sFilename):false);

				$iCurrentIndex=1;
				$i=1;
				$sFilename = SERVER_PATH.'/imgbank/xml/'.$sNameFile.$iCurrentIndex.'.xml';

				$aResult = mysql_query($sPromSqlLimitSql, Base::$db->_connectionID);

				$count=0;

				$oFile = fopen($sFilename, 'w');
				fwrite($oFile, $sContentBegin);
				if($aResult)
				while ($aValue = mysql_fetch_assoc($aResult)) {
					set_time_limit(0);
					$count++;

					$sImage = $this->GetImagesXml($aImagesAssoc, $aValue['item_code'], $aValue);
					//-------------------------------------------------------------------
					//Name
					$trans = array("\x1C" => ".");
					$orig_text = htmlspecialchars(strip_tags($aValue['name_translate']));
					$sName = strtr($orig_text,$trans);
					if($sName==''||$sName=='.'){
					    $sName='Автозапчасть';
					}
					
					//-------------------------------------------------------------------
					// Stock
					if((!$aValue['stock_filtered'] || $aValue['stock_filtered'] ===0) && $aValue['term']>1){
						$sStock = '<available>false</available>';
					}
					else $sStock = '<available>true</available>';
					
					//-------------------------------------------------------------------
					// Description
					Base::$tpl->assign('name', $sName);
					Base::$tpl->assign('brand', htmlspecialchars($aValue['cat_name']));
					Base::$tpl->assign('code', $aValue['code_']);
					Base::$tpl->assign('sSmartyTemplate', $this->aExportXmlAssocRow['description']);
					$sDescription = Base::$tpl->fetch('addon/smarty_template.tpl');
					//--------------------------------------------------------------------
					if(!$aValue['id_price_group'] || $aValue['id_price_group']==0) $aValue['id_price_group'] = 1;
					$i++;
					$sContentRow = '
					<item id="'.$aValue['id'].'">
						<name><![CDATA['.$sName.']]></name>
						<categoryId>'.$aValue['id_price_group'].'</categoryId>
						<priceuah>'.ceil($aValue['min_price']).'</priceuah>
						<url>'.$this->GetLink($aValue).'</url>
						<image>'.$sImage.'</image>
						<vendor><![CDATA['.htmlspecialchars($aValue['cat_name']).']]></vendor>
						<vendorCode>'.$aValue['code_'].'</vendorCode>
						<description><![CDATA['.$sDescription.']]></description>
						<warranty>12</warranty>
						'.$sStock.'
					</item>';
					fwrite($oFile, $sContentRow);

					if (!($i % $iPromPortion)) {
						fwrite($oFile, $sContentEnd);
						fclose($oFile);
						$iCurrentIndex++;
						$sFilename = SERVER_PATH.'/imgbank/xml/'.$sNameFile.$iCurrentIndex.'.xml';

						$oFile = fopen($sFilename, 'w');
						fwrite($oFile, $sContentBegin);
					}
				}
				fwrite($oFile, $sContentEnd);
				fclose($oFile);

				Base::UpdateConstant($this->sPrefix.':prom_current_index',$iCurrentIndex);
				Base::UpdateConstant($this->sPrefix.':prom_count',$count);
				Base::UpdateConstant($this->sPrefix.':prom_export_running',0);
				if ($bXajaxRequest) {
					if (file_exists($sFilename)) {
						$iCurrentIndex = Base::GetConstant($this->sPrefix.':prom_current_index');
						$sFilename = SERVER_PATH.'/imgbank/xml/'.$sNameFile.$iCurrentIndex.'.xml';
						Base::$tpl->assign('sSection', $sNameFile);
						Base::$tpl->assign('aRange', range(1, $iCurrentIndex));

						Base::$oResponse->AddAssign('xml_link_prom_id','innerHTML',
						Base::$tpl->fetch($this->sPrefix.'/file_link.tpl'));
						$sDate = date ("Y-m-d H:i:s", filemtime($sFilename));
						Base::$oResponse->AddAssign('xml_date_prom_id','innerHTML','count '.$count.' '.$sDate);
					}
					Base::$oResponse->AddScript("$('#button_prom_id').toggle(500); $('#image_prom_id').toggle(500);");
				}
				break;*/

			case 'sitemap_html':
				$this->GenerateSiteMap();

				if ($bXajaxRequest) {						
					Base::$oResponse->AddScript("$('#button_sitemap_html_id').toggle(500); $('#image_sitemap_html_id').toggle(500);");
				}
				break;
			
			case 'siteindex':

				$i=1;
				do {
				    $sFilename = SERVER_PATH.'/imgbank/xml/sitemap'.$i++.'.xml';
				} while (file_exists($sFilename)?unlink($sFilename):false);

				// update model list
				// if(Language::GetConstant('export_xml:siteindex_update_model_list',0)) $this->GetModelListXml();
				// Debug::PrintPre(1);
				$sFilename = SERVER_PATH.'/imgbank/xml/siteindex.xml';

				$iSitemapBeforeCount=Language::GetConstant($this->sPrefix.':sitemap_before_current_index',0);
				if($iSitemapBeforeCount) $aSitemapBefore=range(1, $iSitemapBeforeCount);

				$sSimplePriceSql=Base::GetSql("Catalog/Price",array(
					"where"=> $sWhere,
					"all_price_group"=> "1",
					));
		        $sTableName="pe_".date("Ymd");
				Db::Execute("drop table if exists ".$sTableName);
				Db::Execute("create table ".$sTableName." ".$sSimplePriceSql);

				$iPriceCount=Db::GetOne("select count(*) from ".$sTableName);

				$iSiteindexPortion=Base::GetConstant($this->sPrefix.':siteindex_portion',2000);
				$iSitemapCount=intval($iPriceCount/$iSiteindexPortion);
				$aSitemap=range(2, $iSitemapCount+1);
				
				if($aSitemapBefore) Base::$tpl->assign('aSitemapBefore',$aSitemapBefore);
				Base::$tpl->assign('aSitemap',array(1)+$aSitemap);
				Base::$tpl->assign('sSiteindexDate',date('Y-m-d'));
				Base::UpdateConstant($this->sPrefix.':sitemap_current_index',$iSitemapCount);
				
				$sContent=Base::$tpl->fetch($this->sPrefix.'/siteindex_xml.tpl');
				file_put_contents($sFilename,$sContent);
				
				//static content begin
				$sBeforeContent=$this->GetStaticXml();
				Base::$tpl->assign('sBeforeContent',$sBeforeContent);
				$sFilename = SERVER_PATH.'/imgbank/xml/sitemap1.xml';
				$sContent=Base::$tpl->fetch($this->sPrefix.'/sitemap_xml.tpl');
				file_put_contents($sFilename,$sContent);
				//static content end
				Base::$tpl->assign('sBeforeContent','');
				$sContent='';

				if ($aSitemap) foreach ($aSitemap as $aValue) {

					$sPortionSql="select * from ".$sTableName.' limit '.($aValue-1)*$iSiteindexPortion.','.$iSiteindexPortion;
					$aPrice=Db::GetAll($sPortionSql);
					Base::$tpl->assign('aPrice', $aPrice);
					Base::$tpl->assign('aBuyTags', array('changefreq'=>'daily', 'priority'=>'0.8'));
					$sFilename = SERVER_PATH.'/imgbank/xml/sitemap'.$aValue.'.xml';

					$sContent=Base::$tpl->fetch($this->sPrefix.'/sitemap_xml.tpl');
					file_put_contents($sFilename,$sContent);
				}
				Db::Execute("drop table if exists ".$sTableName);

				if ($bXajaxRequest) {
					if (file_exists($sFilename)) {
						Base::$oResponse->AddAssign('xml_link_siteindex_id','innerHTML',
						Base::$tpl->fetch($this->sPrefix.'/file_link.tpl'));

						Base::$oResponse->AddAssign('xml_date_siteindex_id','innerHTML',date("Y-m-d H:i:s",filemtime($sFilename)));
					}
					Base::$oResponse->AddScript("$('#button_siteindex_id').toggle(500); $('#image_siteindex_id').toggle(500);");
				}
				break;

			/*case 'ava':
				Base::$tpl->assign('sCurrentDate', date('Y-m-d H:i'));
				$sFilename = SERVER_PATH.'/imgbank/xml/ava.xml';

				$sContentBegin=Base::$tpl->fetch($this->sPrefix.'/ava_xml_begin.tpl');
				$sContentEnd=Base::$tpl->fetch($this->sPrefix.'/ava_xml_end.tpl');

				$aResult = mysql_query($sSimplePriceLimitSql);

				$oFile = fopen($sFilename, 'w');
				fwrite($oFile, $sContentBegin);
				if($aResult)
				while ($aValue = mysql_fetch_assoc($aResult)) {
					$sContentRow="
					<item id='".$aValue['id']."'>
						<name>".htmlspecialchars(strip_tags($aValue['name_translate']))."</name>
						<url>".$this->GetLink($aValue)."</url>
						<price>".$aValue['price']."</price>
						<categoryId>".$aValue['id_price_group']."</categoryId>
						<vendor>".htmlspecialchars($aValue['cat_name'])."</vendor>
						<description>".htmlspecialchars(strip_tags($aValue['description']))."</description>
						<vendorCode>".$aValue['code_']."</vendorCode>
					</item>";
					fwrite($oFile, $sContentRow);
				}
				fwrite($oFile, $sContentEnd);
				fclose($oFile);

				if ($bXajaxRequest) {
					if (file_exists($sFilename)) {
						Base::$oResponse->AddAssign('xml_link_ava_id','innerHTML',
						Base::$tpl->fetch($this->sPrefix.'/file_link.tpl'));

						Base::$oResponse->AddAssign('xml_date_ava_id','innerHTML',date ("Y-m-d H:i:s", filemtime($sFilename)));
					}
					Base::$oResponse->AddScript("$('#button_ava_id').toggle(500); $('#image_ava_id').toggle(500);");
				}
				break;

			case 'hotprice':
				Base::$tpl->assign('sCurrentDate', date('Y-m-d H:i'));
				Base::$tpl->assign('bShowSingleXml', 1);
				$sFilename = SERVER_PATH.'/imgbank/xml/hotprice.xml';

				$sContentBegin=Base::$tpl->fetch($this->sPrefix.'/hotprice_xml_begin.tpl');
				$sContentEnd=Base::$tpl->fetch($this->sPrefix.'/hotprice_xml_end.tpl');

				$aResult = mysql_query($sSimplePriceLimitSql);

				$oFile = fopen($sFilename, 'w');
				fwrite($oFile, $sContentBegin);
				if($aResult)
				while ($aValue = mysql_fetch_assoc($aResult)) {
					$sContentRow="
					<item id='".$aValue['id']."'>
						<name>".htmlspecialchars(strip_tags($aValue['name_translate']))."</name>
						<url>".$this->GetLink($aValue)."</url>
						<price>".$aValue['price']."</price>
						<categoryId>".$aValue['id_price_group']."</categoryId>
						<vendor>".htmlspecialchars($aValue['cat_name'])."</vendor>
						<description></description>
						<vendorCode>".$aValue['code_']."</vendorCode>
					</item>";
					fwrite($oFile, $sContentRow);
				}
				fwrite($oFile, $sContentEnd);
				fclose($oFile);

				if ($bXajaxRequest) {
					if (file_exists($sFilename)) {
						Base::$oResponse->AddAssign('xml_link_hotprice_id','innerHTML',
						Base::$tpl->fetch($this->sPrefix.'/file_link.tpl'));

						Base::$oResponse->AddAssign('xml_date_hotprice_id','innerHTML',date ("Y-m-d H:i:s", filemtime($sFilename)));
					}
					Base::$oResponse->AddScript("$('#button_hotprice_id').toggle(500); $('#image_hotprice_id').toggle(500);");
				}
				break;

			case 'autobazar':
				Base::$tpl->assign('sCurrentDate', date('Y-m-d H:i'));
				$sFilename = SERVER_PATH.'/imgbank/xml/autobazar.xml';

				$sContentBegin=Base::$tpl->fetch($this->sPrefix.'/autobazar_xml_begin.tpl');
				$sContentEnd=Base::$tpl->fetch($this->sPrefix.'/autobazar_xml_end.tpl');

				$aResult = mysql_query($sSimplePriceLimitSql);

				$oFile = fopen($sFilename, 'w');
				fwrite($oFile, $sContentBegin);
				if($aResult)
				while ($aValue = mysql_fetch_assoc($aResult)) {
					$sImage = $aValue['image'] ? 
					(Language::GetConstant('export_xml:project_url') . $aValue['image']) : 
					Language::GetConstant('export_xml:autobazar_image','http://www.autoklad.ua/imgbank/Image/logo_1.png');  
					$sContentRow="
					<item>
						<code>".$aValue['code_']."</code>
						<vendor>".htmlspecialchars($aValue['cat_name'])."</vendor>
						<name>".htmlspecialchars(strip_tags($aValue['name_translate']))."</name>
						<image>".$sImage."</image>
						<url>".$this->GetLink($aValue)."</url>
						<price_UAH>".$aValue['price']."</price_UAH>
						<price_USD>".Base::$oCurrency->Price($aValue['price'],3)."</price_USD>
						<price_EUR>".Base::$oCurrency->Price($aValue['price'],2)."</price_EUR>
						<stock>".Language::GetConstant('export_xml:autobazar_stock','на складе')."</stock>
						<term>".Language::GetConstant('export_xml:autobazar_term','5')."</term>
					</item>";
					fwrite($oFile, $sContentRow);
				}
				fwrite($oFile, $sContentEnd);
				fclose($oFile);

				if ($bXajaxRequest) {
					if (file_exists($sFilename)) {
						Base::$oResponse->AddAssign('xml_link_autobazar_id','innerHTML',
						Base::$tpl->fetch($this->sPrefix.'/file_link.tpl'));

						Base::$oResponse->AddAssign('xml_date_autobazar_id','innerHTML',date ("Y-m-d H:i:s", filemtime($sFilename)));
					}
					Base::$oResponse->AddScript("$('#button_autobazar_id').toggle(500); $('#image_autobazar_id').toggle(500);");
				}
				break;

*/
/*
 
			case 'price':
				Base::$tpl->assign('bShowSingleXml', 1);
				Base::$tpl->assign('sCurrentDate', date('Y-m-d H:i'));
				$i=1;
				do {
				    $sFilename = SERVER_PATH.'/imgbank/xml/price'.$i++.'.xml';
				} while (file_exists($sFilename)?unlink($sFilename):false);

				//$iCurrentIndex=1;
				$iCurrentIndex='';
				$i=1;
				$sFilename = SERVER_PATH.'/imgbank/xml/price'.$iCurrentIndex.'.xml';

				$sContentBegin=Base::$tpl->fetch($this->sPrefix.'/price_xml_begin.tpl');
				$sContentEnd=Base::$tpl->fetch($this->sPrefix.'/price_xml_end.tpl');
				$iHotlinePortion=Base::GetConstant($this->sPrefix.':price_portion',10000);

				$aResult = mysql_query($sSimplePriceLimitSql);

				$oFile = fopen($sFilename, 'w');
				fwrite($oFile, $sContentBegin);
				if($aResult)
				while ($aValue = mysql_fetch_assoc($aResult)) {
					$sImage = $aValue['image'] ?
					(Language::GetConstant('export_xml:project_url') . $aValue['image']) :
					Language::GetConstant('export_xml:autobazar_image','http://klan.com.ua/imgbank/Image/logo_1.png');  
				$sContentRow="
			<item id=\"".$aValue['id']."\">
				<name>".htmlspecialchars(strip_tags($aValue['name_translate']))."</name>
				<categoryId>".$aValue['id_price_group']."</categoryId>
				<price>".$aValue['price']."</price>
				<bnprice>".$aValue['price']."</bnprice>
				<url>".$this->GetLink($aValue)."</url>
				<image>".$sImage."</image>
				<vendor>".htmlspecialchars($aValue['cat_name'])."</vendor>
				<description>".htmlspecialchars(strip_tags($aValue['name_translate']))."</description>
				<warranty>".Language::GetConstant('export_xml:price_warranty','12')."</warranty>
			</item>	";
					fwrite($oFile, $sContentRow);
					$i++;
					/*if (!($i % $iHotlinePortion)) {
						fwrite($oFile, $sContentEnd);
						fclose($oFile);
						$iCurrentIndex++;
						$sFilename = SERVER_PATH.'/imgbank/xml/price'.$iCurrentIndex.'.xml';

						$oFile = fopen($sFilename, 'w');
						fwrite($oFile, $sContentBegin);
					}*//*
				}
				fwrite($oFile, $sContentEnd);
				fclose($oFile);

				Base::UpdateConstant($this->sPrefix.':price_current_index',$iCurrentIndex);
				if ($bXajaxRequest) {
					if (file_exists($sFilename)) {
						//Base::$tpl->assign('aRange', range(1, $iCurrentIndex));
						Base::$oResponse->AddAssign('xml_link_price_id','innerHTML',
						Base::$tpl->fetch($this->sPrefix.'/file_link.tpl'));

						Base::$oResponse->AddAssign('xml_date_price_id','innerHTML',date ("Y-m-d H:i:s", filemtime($sFilename)));
					}
					Base::$oResponse->AddScript("$('#button_price_id').toggle(500); $('#image_price_id').toggle(500);");
				}
				break;
*/
			
			/*case 'yandex':
				Base::UpdateConstant('export_xml:yandex_i',0);
					
				Base::$tpl->assign('sCurrentDate', date('Y-m-d H:i'));
				Base::$tpl->assign('sSection', 'market');
				Base::$tpl->assign('sExt', 'yml');
				Base::$tpl->assign('bShowSingleXml', 1);
				$sFilename = SERVER_PATH.'/imgbank/xml/market.yml';

				$sContentBegin=Base::$tpl->fetch($this->sPrefix.'/yandex_xml_begin.tpl');
				$sContentEnd=Base::$tpl->fetch($this->sPrefix.'/yandex_xml_end.tpl');

				if (Base::GetConstant("export_xml:limit_enable","1")==1){
					$aResult = mysql_query($sSimplePriceLimitSql);
				} else {
					$aResult = mysql_query($sSimplePriceSql);
				}

				$oFile = fopen($sFilename, 'w');
				fwrite($oFile, $sContentBegin);
				if($aResult)
				while ($aValue = mysql_fetch_assoc($aResult)) {
					set_time_limit(0);
					Db::Execute("update constant set value=value+1 where key_='export_xml:yandex_i'");	
				
				
					/*$sArtId=Db::getOne($sql="select ART_ID FROM ".DB_TOF."tof__articles as cta
						INNER JOIN cat as cat ON cat.id_to f = cta.ART_SUP_ID
						inner join ".DB_TOF."tof__art_lookup l on ARL_ART_ID=ART_ID
						where ARL_SEARCH_NUMBER='".$aValue['code_']."' and cat.name='".$aValue['cat_name']."'
						order by ARL_KIND
						");
					
					$aGraphic=Db::GetRow(Base::GetSql("Catalog/Graphic",array(
					'aIdGraphic'=>array($sArtId),
					'order'=>'gra_sort desc'
					)));

					$sImage = $aGraphic['img_path'] ?
					(Language::GetConstant('export_xml:project_url') . $aGraphic['img_path']) :
					Language::GetConstant('export_xml:default_image','http://klan.com.ua/imgbank/Image/logo_1.png');*/
					
					/*$aModelDetail=Db::GetAll(Base::GetSql("Catalog/ModelDetail", array(
					"code"=>$aValue['code_'],
					"art_id"=>$sArtId
					)));
					$aModelList=array();
					foreach ($aModelDetail as $sKey => $aValueModel){
						$sModelName=$aValueModel['name'];
						$aName=preg_split("/ /",$sModelName);
						$aTmp=explode(",",$aName[0]." ".$aName[1]." ".$aName[2]);
						$aTmp=explode("(", $aTmp[0]);
						$aModelList[$sKey]=$aTmp[0];
					}
					unset($aModelDetail);
					$aModelList=array_unique($aModelList);
					$sParam="";
					foreach ($aModelList as $sValue){
						$sParam.="					    <param name='применяемость'><![CDATA[".htmlspecialchars($sValue)."]]></param>\n";
					}
					unset($aModelList);*//*
					
					$aGraphic=Db::GetRow("select * from ".DB_TOF."tof__link_gra_art_view where ARL_SEARCH_NUMBER='".$aValue['code_']."' ".
							"and ART_SUP_ID='".$aValue['tof_sup_id']."'");
					
					$sImage = $aGraphic['img_path'] ?
					(Language::GetConstant('export_xml:project_url') .'/imgbank/tcd/'. $aGraphic['img_path']) :
					Language::GetConstant('export_xml:default_image','http://klan.com.ua/imgbank/Image/logo_1.png');
					
					$sNotes=Language::GetConstant('export_xml:sales_notes','минимальная сумма заказа 100 грн.');
					$sContentRow="
					<offer id=\"".$aValue['id']."\" type=\"vendor.model\" available=\"true\" bid=\"13\">
					    <url>".$this->GetLink($aValue)."</url>
					    <price>".$aValue['price']."</price>
					    <currencyId>UAH</currencyId>
					    <categoryId>1</categoryId>
					    <picture>".$sImage."</picture>
					    <store>false</store>
					    <pickup>false</pickup>
					    <delivery>true</delivery>
					    <local_delivery_cost>30</local_delivery_cost>
					    <typePrefix>Автозапчасть</typePrefix>
					    <vendor><![CDATA[".htmlspecialchars($aValue['cat_name'])."]]></vendor>
					    <vendorCode>".$aValue['code_']."</vendorCode>
					    <model><![CDATA[".htmlspecialchars(strip_tags($aValue['name_translate']))." ".htmlspecialchars($aValue['cat_name'])." ".$aValue['code_']."]]></model>
					    <sales_notes>".$sNotes."</sales_notes>
					    <manufacturer_warranty>true</manufacturer_warranty>
					    ".$sParam."
					</offer>";
					fwrite($oFile, $sContentRow);
				}
				fwrite($oFile, $sContentEnd);
				fclose($oFile);
				/*$sFilenameZip = SERVER_PATH.'/imgbank/xml/yandex.zip';
				unlink($sFilenameZip);
				$oZip = new ZipArchive;
				if ($oZip->open($sFilenameZip, ZIPARCHIVE::CREATE) !== true) {
					$bNotZip=TRUE;
				}else{
					$oZip->addFile($sFilename, 'yandex.yml');
					$oZip->close();
				}*//*

				if ($bXajaxRequest) {
					if (file_exists($sFilename)) {
						Base::$oResponse->AddAssign('xml_link_yandex_id','innerHTML',
						Base::$tpl->fetch($this->sPrefix.'/file_link.tpl'));

						Base::$oResponse->AddAssign('xml_date_yandex_id','innerHTML',date ("Y-m-d H:i:s", filemtime($sFilename)));
					}
					Base::$oResponse->AddScript("$('#button_yandex_id').toggle(500); $('#image_yandex_id').toggle(500);");
				}
				break;*/


	/*		case 'vcene':
				Base::$tpl->assign('sCurrentDate', date('Y-m-d H:i'));
				Base::$tpl->assign('sSection', 'vcene');
				Base::$tpl->assign('sExt', 'yml');
				Base::$tpl->assign('bShowSingleXml', 1);
				$sFilename = SERVER_PATH.'/imgbank/xml/vcene.xml';

				$sContentBegin=Base::$tpl->fetch($this->sPrefix.'/vcene_xml_begin.tpl');
				$sContentEnd=Base::$tpl->fetch($this->sPrefix.'/vcene_xml_end.tpl');

				$aResult = mysql_query($sSimplePriceLimitSql);

				$oFile = fopen($sFilename, 'w');
				fwrite($oFile, $sContentBegin);
				if($aResult)
				while ($aValue = mysql_fetch_assoc($aResult)) {
					$sImage = $aValue['image'] ?
					(Language::GetConstant('export_xml:project_url') . $aValue['image']) :
					Language::GetConstant('export_xml:autobazar_image','http://www.autoklad.ua/imgbank/Image/logo_1.png');
					$sNotes=Language::GetConstant('export_xml:sales_notes','минимальная сумма заказа 100 грн.');
					$sContentRow="
					<offer id=\"".$aValue['id']."\" available=\"true\">
						<url>".$this->GetLink($aValue)."</url>
						<price>".$aValue['price']."</price>
						<currencyId>UAH</currencyId>
						<categoryId>".$aValue['id_price_group']."</categoryId>
						<picture>".$sImage."</picture>
						<delivery>true</delivery>
						<name>".htmlspecialchars(strip_tags($aValue['name_translate']))." ".$aValue['cat_name']." ".$aValue['code_']."</name>
						<vendor>".htmlspecialchars($aValue['cat_name'])."</vendor>
						<vendorCode>".$aValue['code_']."</vendorCode>
						<sales_notes>".$sNotes."</sales_notes>
					</offer>";
					fwrite($oFile, $sContentRow);
				}
				fwrite($oFile, $sContentEnd);
				fclose($oFile);

				if ($bXajaxRequest) {
					if (file_exists($sFilename)) {
						Base::$oResponse->AddAssign('xml_link_vcene_id','innerHTML',
						Base::$tpl->fetch($this->sPrefix.'/file_link.tpl'));

						Base::$oResponse->AddAssign('xml_date_vcene_id','innerHTML',date ("Y-m-d H:i:s", filemtime($sFilename)));
					}
					Base::$oResponse->AddScript("$('#button_vcene_id').toggle(500); $('#image_vcene_id').toggle(500);");
				}
				break;*/


			default:
				Base::Redirect("/pages/export_xml/");

		}
	}
	//-----------------------------------------------------------------------------------------------
	public function CopyImageToPrice()
	{
		$aPriceIdForUpdate=Db::GetAssoc("select code, id from price where (image_update is null)
			or TIMESTAMPDIFF(HOUR,image_update,current_timestamp)>".
			Base::GetConstant($this->sPrefix.':image_interval_hour',48).
			" order by id limit ".Base::GetConstant($this->sPrefix.':image_per_minute',1000));
		if ($aPriceIdForUpdate)
		{
			Db::Execute("update price set post_date=post_date, image_update=current_timestamp
				where id in ('".implode("','",$aPriceIdForUpdate)."')");
			$aGraphic=Db::GetAll(Base::GetSql("Catalog/Graphic",array(
				'aIdGraphic'=>$aPriceIdForUpdate,
				//'hide_tof_image'=>$aPartInfo['hide_tof_image'],
				)));
			if ($aGraphic)
			foreach ($aGraphic as $aValue){
				Db::Execute("update price set post_date=post_date, image='".$aValue['img_path'].
				"' where id='".$aValue['lgl_la_id']."'");
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function GenerateAll()
	{
		//if(Base::GetConstant($this->sPrefix.':generate_all_runing',0)) return;
		Base::UpdateConstant($this->sPrefix.':generate_all_runing',1);
		if ($this->aSection) foreach ($this->aSection as $aValue) {
			print($aValue.' ');@ob_flush();@flush();
			Base::$aRequest['section']=$aValue;
			$this->Generate(false);
		}
		Base::UpdateConstant($this->sPrefix.':generate_all_runing',0);
	}
	//-----------------------------------------------------------------------------------------------
	public function GetStaticXml()
	{
		$aDropDown=Db::GetAll(Base::GetSql('DropDown',array(
		'where'=>" and visible=1 and link=0",
		)));
		if ($aDropDown) foreach ($aDropDown as $aValue) {
			$sPage=Db::GetOne("select static_rewrite from drop_down_additional where url='action=".$aValue['code']."'");
			if($sPage)$sUrl='/pages/'.$sPage;
			else $sUrl='/pages/'.$aValue['code']."/";
			$aStaticItem[]=array(
			'loc'=>Language::GetConstant('export_xml:project_url').$sUrl,
			'post_date'=>$aValue['post_date'],
			'changefreq'=>'weekly',
			'priority'=>'0.6',
			);
		}

		$aNews=Db::GetAll(Base::GetSql('News',array(
		'where'=>" and visible=1",
		)));
		if ($aNews) foreach ($aNews as $aValue) {
			$aStaticItem[]=array(
			'loc'=>Language::GetConstant('export_xml:project_url').'/pages/news/'.$aValue['id']."/",
			'post_date'=>$aValue['post_date'],
			'changefreq'=>'weekly',
			'priority'=>'0.6',
			);
		}

		// rubricator /rubricator/
		$aRubricAssoc=Db::GetAssoc("select r.id as aa, r.* from rubricator as r where r.visible='1' ");
		if($aRubricAssoc) {
		    $aRubricTree=array();
		    foreach ($aRubricAssoc as $aValue) {
		        $aRubricTree[$aValue['id_parent']][$aValue['id']]=$aValue['id'];
		    }
		
		    if($aRubricTree) {
		        foreach ($aRubricTree as $iBaseRubric => $aChildsRubric) {
		            if($aRubricAssoc[$iBaseRubric]['is_mainpage']) {
		                $aStaticItem[]=array(
		                    'loc'=>Language::GetConstant('export_xml:project_url')."/rubricator/".$aRubricAssoc[$iBaseRubric]['url'],
		                    'changefreq'=>'daily',
							'priority'=>'1.0',
		                );
		                if($aChildsRubric) {
		                    foreach ($aChildsRubric as $iChildRubric) {
		                        $aStaticItem[]=array(
		                            'loc'=>Language::GetConstant('export_xml:project_url')."/rubricator/".$aRubricAssoc[$iBaseRubric]['url']."/".$aRubricAssoc[$iChildRubric]['url'],
		                            'changefreq'=>'daily',
									'priority'=>'1.0',
		                        );
		                    }
		                }
		            }
		        }
		    }
		}
		//-----------------------------------------------------------------------------------------------------------------------
		// price_group /select/
		$aGroupsAssoc=Db::GetAssoc("select pg.id as aa, pg.*
		    from price_group as pg
		    left join rubricator as r on pg.id=r.id_price_group
		    where pg.visible=1 and r.id_price_group is null
		    order by pg.name
		");
		
		
		if($aGroupsAssoc) {
		    $aGroupTree=array();
		    foreach ($aGroupsAssoc as $aValue) {
		        $aGroupTree[$aValue['id_parent']][$aValue['id']]=$aValue['id'];
		    }
		
		    if($aGroupTree) {
		        foreach ($aGroupTree as $iBaseRubric => $aChildsRubric) {
		            // if($aGroupsAssoc[$iBaseRubric]['is_main']) {
		                $aStaticItem[]=array(
		                    'loc'=>Language::GetConstant('export_xml:project_url')."/select/".$aGroupsAssoc[$iBaseRubric]['code_name'],
		                    'changefreq'=>'daily',
							'priority'=>'1.0',
		                );
		                if($aChildsRubric) {
		                    foreach ($aChildsRubric as $iChildRubric) {
		                        $aStaticItem[]=array(
		                            'loc'=>Language::GetConstant('export_xml:project_url')."/select/".$aGroupsAssoc[$iChildRubric]['code_name'],
		                            'changefreq'=>'daily',
									'priority'=>'1.0',
		                        );
		                    }
		                }
		            // }
		        }
		    }
		}		
		
		Base::$tpl->assign('aStaticItem',$aStaticItem);
		return Base::$tpl->fetch($this->sPrefix.'/sitemap_xml_row.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function GetLink($aValue)
	{
		$sContent="/buy/".$aValue['cat_name']."_".$aValue['code_']."/";
		return htmlspecialchars(Language::GetConstant('export_xml:project_url').$sContent);
	}
	//-----------------------------------------------------------------------------------------------
	public function GetModelListXml(){
		//------------------------------------------------------------------------------------------------------------------------
		// catalog /catalog/
		$iCount = 1;
		$iFile = 0;
		$iSiteindexPortion=Base::GetConstant($this->sPrefix.':siteindex_portion',2000);

		$aCatAssoc=Db::GetAssoc("Assoc/Cat",array(
		 "multiple"=>1,
		 "is_brand"=>1,
		 "is_main"=>1,
		 ));
		$aStaticItem = array();	
		if($aCatAssoc) {
			foreach ($aCatAssoc as $iIdCat => $aCat) {
				$aStaticItem[]=array(
		 			'loc'=>Language::GetConstant('export_xml:project_url')."/catalog/".strtolower(Content::Translit($aCatAssoc[$iIdCat]['c_name'])),
					'changefreq'=>'daily',
					'priority'=>'1.0',
				);
				$iCount++;
				if($iCount==$iSiteindexPortion){ 
					$iFile++;
					$this->PutContentXml($iFile,$aStaticItem);
					unset($aStaticItem);
					$iCount = 1;
				}

				$aModelGroup=Db::GetAll("select * from cat_model_group where visible=1 and id_make='".$iIdCat."' order by name");
				if ($aModelGroup) foreach ($aModelGroup as $sKey => $aValue){
					$aStaticItem[]=array(
			 			'loc'=>Language::GetConstant('export_xml:project_url')."/catalog/".strtolower(Content::Translit($aCatAssoc[$iIdCat]['c_name']))."/".$aModelGroup[$sKey]['code']."/",
						'changefreq'=>'daily',
						'priority'=>'1.0',
					);
					$iCount++;
					if($iCount==$iSiteindexPortion){ 
						$iFile++;
						$this->PutContentXml($iFile,$aStaticItem);
						unset($aStaticItem);
						$iCount = 1;
					}

				    $aModels=TecdocDb::GetModels(array(
				        "id_make"=>$aValue['id_make'],
				        "id_models"=>$aValue['id_models'],
				        "is_hide"=>1
				    ));
					
				    ExportXml::CallParseModel($aModels,$aCat['name'],$iIdCat);
				    if ($aModels) foreach ($aModels as $sKey1 => $aValue1){
				    	$aStaticItem[]=array(
				 			'loc'=>Language::GetConstant('export_xml:project_url').$aValue1['seourl'],
							'changefreq'=>'daily',
							'priority'=>'1.0',
						);
						$iCount++;
						if($iCount==$iSiteindexPortion){ 
							$iFile++;
							$this->PutContentXml($iFile,$aStaticItem);
							unset($aStaticItem);
							$iCount = 1;
						}

						$aModelsDetail=TecdocDb::GetModelDetails(array("id_model"=>$aValue1['id']));

						ExportXml::CallParseModelDetail($aModelsDetail);
						if ($aModelsDetail) foreach ($aModelsDetail as $sKey2 => $aValue2){
							$aStaticItem[]=array(
					 			'loc'=>Language::GetConstant('export_xml:project_url').$aValue2['seourl'],
								'changefreq'=>'daily',
								'priority'=>'1.0',
							);
							$iCount++;
							if($iCount==$iSiteindexPortion){ 
								$iFile++;
								$this->PutContentXml($iFile,$aStaticItem);
								unset($aStaticItem);
								$iCount = 1;
							}
						}
				    }
				}	
				
			}
			if($aStaticItem){
				$iFile++;
				$this->PutContentXml($iFile,$aStaticItem);
				unset($aStaticItem);
			}
			Base::UpdateConstant($this->sPrefix.':siteindex_update_model_list',0);
			Base::UpdateConstant($this->sPrefix.':sitemap_before_current_index',$iFile);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseModel(&$aItem, $sCatName, $iIdMake)
	{
		if ($aItem) {
			
			foreach($aItem as $key => $aValue) {
				$aItem[$key]['seourl']=Content::CreateSeoUrl('catalog_detail_model_view',array(
				'cat'=>$sCatName,
				'data[id_make]'=>$iIdMake,
				'data[id_model]'=>$aValue['mod_id'],
				'model_translit'=>Content::Translit($aValue['name'])
				));
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseModelDetail(&$aItem)
	{
		if ($aItem) {
			// Debug::PrintPre($aItem);
			foreach($aItem as $key => $aValue) {
			    if(!Base::$aRequest['cat'] && $aValue['id_make']) Base::$aRequest['cat']=Db::GetOne("select name from cat where id='".$aValue["id_make"]."' ");
			    
				$aItem[$key]['seourl']=Content::CreateSeoUrl('catalog_assemblage_view',array(
				'cat'=>$sCatName,
				'model_detail'=>array($aValue['id_model_detail']=>array('name'=>$aValue['name'],'Name'=>$aValue['Name'])),
				'data[id_make]'=> $iIdMake,
				'data[id_model]'=>$aValue['id_model'],
				//'data[id_brand]'=>$aValue['id_model'],
				'data[id_model_detail]'=>$aValue['id_model_detail'],
				'model_translit'=>Content::Translit($aValue['name'])
				));
				//Debug::PrintPre($aItem[$key]['seourl']);
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function PutContentXml($iFile,$aStaticItem)
	{
		Base::$tpl->assign('aStaticItem',$aStaticItem);
		$sBeforeContent = Base::$tpl->fetch($this->sPrefix.'/sitemap_xml_row.tpl');
		Base::$tpl->assign('sBeforeContent',$sBeforeContent);
		$sFilename = SERVER_PATH.'/imgbank/xml/sitemap-'.$iFile.'.xml';
		$sContent=Base::$tpl->fetch($this->sPrefix.'/sitemap_xml.tpl');
		file_put_contents($sFilename,$sContent);
		Base::$tpl->assign('sBeforeContent','');
		$sContent='';
		
	}
	// -----------------------------------------------------------------------------------------------
	public function UpdateTablePriceXml()
	{
		set_time_limit(0);
		$sPriceSql=Base::GetSql("Catalog/Price",array(
		"where"=> $sWhere." and p.price > 0 ",
		"all_price_group"=> "1",
		));
		// Debug::PrintPre($sPriceSql);
		$sTableName="export_xml_price";
		$sTableNameMinPrice="export_xml_min_price";
		Db::Execute("DROP TABLE IF EXISTS ".$sTableName);
		Db::Execute("DROP TABLE IF EXISTS ".$sTableNameMinPrice);

		// print $sTableName." truncated<br>";
		// print $sTableNameMinPrice." truncated<br>";

		Db::Execute("CREATE TABLE ".$sTableName." ".$sPriceSql);
		
		$sMinPriceSql = "SELECT *, min(price) as min_price FROM `".$sTableName."` group by item_code";

		Db::Execute("CREATE TABLE ".$sTableNameMinPrice." ".$sMinPriceSql);
	}
	//--------------------------------------------------------------------------------
	public function GetImagesXml(&$aImagesAssoc, $sItemCode, $aItem)
	{
		if($aImagesAssoc[$sItemCode]){
			return $aImagesAssoc[$sItemCode];
		}else{
			$aArtId[] = Catalog::GetArtId($aItem['item_code']);
			$aGraphic=TecdocDb::GetImages(array(
			    'aIdGraphic'=>$aArtId,
			    'aIdCatPart'=>array($aItem['id_cat_part']),
			),$this->aCats,false);
			if($aGraphic){
				$aData['item_code']=$aItem['item_code'];
				if(strripos($aGraphic[0]['img_path'], '/imgbank/tcd/')) $aData['img_path'] = $aGraphic[0]['img_path'];
				else $aData['img_path'] = Language::GetConstant('export_xml:project_url').$aGraphic[0]['img_path'];
				Db::Execute("insert ignore into export_xml_image (item_code, img_path) 
					values ('".$aData['item_code']."','".$aData['img_path']."') ");
				// Db::AutoExecute('export_xml_image',$aData);
			}

			if(!$aGraphic) {
				$sImage = Language::GetConstant('export_xml:default_image','АвтоКарта');
				Db::Execute("insert ignore into export_xml_image (item_code, img_path) 
					values ('".$aItem['item_code']."','".$sImage."') ");
			}
			elseif(strripos($aGraphic[0]['img_path'], '/imgbank/tcd/')) $sImage = $aGraphic[0]['img_path'];
			else $sImage = Language::GetConstant('export_xml:project_url').$aGraphic[0]['img_path'];

			$aImagesAssoc[$sItemCode]=$sImage;

			return $sImage;
		}
	}
	//--------------------------------------------------------------------------------
// JPN-333
	// SiteMap html ---------------------------------------------------------------------------------------------------------------
	public function GenerateSiteMap()
	{
		$sFilename = SERVER_PATH.'/imgbank/html/sitemap.html';
	    Base::$tpl->assign('sFilename', 'sitemap.html');
    	$oFile = fopen($sFilename, 'w');
    	fwrite($oFile, '');
		$aSitemap=ExportXml::GetDataForSiteMap();
		if($aSitemap) {
			$aRoot[] = array('name'=>'Главная', 'url'=>'/', 'childs'=>'0');
			Base::$tpl->assign('aRoot',$aRoot);
			Base::$tpl->assign('aSitemap',$aSitemap);
			$sContent=Base::$tpl->fetch('sitemap/index.tpl');
			fwrite($oFile, $sContent);
		}
		
		fclose($oFile);
	}

	public function GetDataForSiteMap()
	{
		$aRoot[] = array('name'=>'Главная', 'url'=>'/', 'childs'=>'0');
		$aSitemap=array();
 		$aCat=DB::GetAll("select id, upper(name) as name, lower(name) as lname, title from cat where is_brand=1 and visible=1 order by name");
		$aList=array();
		// /pages/catalog/ --------------------------------------------------------------------------------------------------------------
		if($aCat) {
			$aChildsCat=array();
			foreach ($aCat as $aValue) {
				$aChildsModel=array();
				$aModel=Db::GetAll("select * from cat_model_group where visible=1 and id_make='".$aValue['id']."' order by name");
				if ($aModel) 
					// unset если 0 моделей
					foreach ($aModel as $sKey_m => $aValue_m){
					$aModels=TecdocDb::GetModels(array(
						"id_make"=>$aValue_m['id_make'],
						"id_models"=>$aValue_m['id_models'],
					));
					
					$aModelsCount=count($aModels);
					if($aModelsCount == 0) unset($aModel[$sKey_m]);
					unset($sKey_m);
					unset($aValue_m);
					unset($aModelsCount);
					}
				
				if ($aModel)  foreach ($aModel as $aVal) {
					$sUrl='/catalog/'.$aValue['lname'].'/'.$aVal['code'].'/';
					// проверка наличия ссылки в стоп листе
					//---------------------------------------
					// проверка наличия имени в export_sitemap_list
					$sUrl2=substr($sUrl, 1);
					if(array_key_exists($sUrl2, $aList)){
						$aChildsModel[]=array('name'=>$aList[$sUrl2], 'url'=>$sUrl, 'childs'=>'0');
					}
					//----------------------------------------------
					else {
						$aChildsModel[]=array('name'=>$aVal['name'], 'url'=>$sUrl, 'childs'=>'0');
						unset($aName);
						unset($sName);
						unset($sUrl);
					}
					unset($sUrl2);
				}
				$sUrl='/catalog/'.$aValue['lname'].'/';
				// проверка наличия имени в export_sitemap_list
				$sUrl2=substr($sUrl, 1);
				if($aList && array_key_exists($sUrl2, $aList)){
					usort($aChildsModel, array(self, 'SortByName'));
					$aChildsCat[]=array('name'=>$aList[$sUrl2], 'url'=>$sUrl, 'childs'=>$aChildsModel);
				}
				//----------------------------------------------
				else {
				    $sName=$aValue['title'];
					usort($aChildsModel, array(self, 'SortByName'));
					$aChildsCat[]=array('name'=>strip_tags($sName), 'url'=>$sUrl, 'childs'=>$aChildsModel);
					unset($aName);
					unset($sUrl);
				}
				unset($sUrl2);
			}
			usort($aChildsCat, array(self, 'SortByName'));
			if($aList && array_key_exists('pages/catalog/', $aList)){
				$aSitemap[]=array('name'=>$aList['pages/catalog/'], 'url'=>'/pages/catalog/', 'childs'=>$aChildsCat);
			}
			//----------------------------------------------
			else {
				$aSitemap[]=array('name'=>'Каталог автозапчастей', 'url'=>'/pages/catalog/', 'childs'=>$aChildsCat);
			}
		}
        //----------------------------------------------------------------------------------------------------------------------- 
        // rubricator /pages/zapchasti/ 
        $aRubricAssoc=Db::GetAssoc("select r.id as aa, r.* from rubricator as r where r.visible='1' ");
		if($aRubricAssoc) {
		    $aRubricTree=array();
		    foreach ($aRubricAssoc as $aValue) {
		        $aRubricTree[$aValue['id_parent']][$aValue['id']]=$aValue['id'];
		    }
		    
		    if($aRubricTree) {
		        foreach ($aRubricTree as $iBaseRubric => $aChildsRubric) {
		            if($aRubricAssoc[$iBaseRubric]['is_mainpage']) {
		                $aRubricChilds=array();
		                if($aChildsRubric) {
		                    foreach ($aChildsRubric as $iChildRubric) {
		                        $aChilds=array();
		                        if($aRubricAssoc[$iChildRubric]['id_price_group']) $aBrands=Db::GetAll(Base::GetSql("Price/GroupPref",array(
        							"id_price_group"=>$aRubricAssoc[$iChildRubric]['id_price_group'],
        							"join_price"=>1,
        							"order"=> " order by c.title",
        							"where"=>" and c.visible=1",
    							)));
		                        if($aBrands) foreach ($aBrands as $aVal) {
		                            
		                            $sUrl="/pages/zapchasti/".$aRubricAssoc[$iBaseRubric]['url']."/".$aRubricAssoc[$iChildRubric]['url'].'/?rb_filter=1&brand='.mb_strtolower($aVal['c_name']);
		                            $sName=$aRubricAssoc[$iChildRubric]['name']." ".$aVal['c_title'];
		                            
		                            $aChilds[]=array('name'=>strip_tags($sName), 'url'=>$sUrl, 'childs'=>'0');
		                        }
		                        
		                        usort($aChilds, array(self, 'SortByName'));
		                        $aRubricChilds[]=array(
		                            'name'=>$aRubricAssoc[$iChildRubric]['name'], 
		                            'url'=>"/pages/zapchasti/".$aRubricAssoc[$iBaseRubric]['url']."/".$aRubricAssoc[$iChildRubric]['url'], 
		                            'childs'=>$aChilds
		                        );
		                    }
		                }
		                
		                usort($aRubricChilds, array(self, 'SortByName'));
		                $aSitemapChilds[]=array(
		                    'name'=>$aRubricAssoc[$iBaseRubric]['name'], 
		                    'url'=>"/pages/zapchasti/".$aRubricAssoc[$iBaseRubric]['url'], 
		                    'childs'=>$aRubricChilds
		                );
		            }
		        }
		        
                $aRubricatorSitemap=$aSitemapChilds;
                unset($aSitemapChilds);
		        //$aSitemap[]=array('name'=>'Автозапчасти по группам', 'url'=>'/pages/rubricator/', 'childs'=>$aSitemapChilds);
		    }
		}
		//-----------------------------------------------------------------------------------------------------------------------
		// price_group /avtotovaru/
		$aGroupsAssoc=Db::GetAssoc("select pg.id as aa, pg.* 
		    from price_group as pg
		    left join rubricator as r on pg.id=r.id_price_group
		    where pg.visible=1 and r.id_price_group is null
		    order by pg.name 
		");
		
		if($aGroupsAssoc) {
		    $aGroupTree=array();
		    foreach ($aGroupsAssoc as $aValue) {
		        $aGroupTree[$aValue['id_parent']][$aValue['id']]=$aValue['id'];
		    }
		    
		    if($aGroupTree) {
		        foreach ($aGroupTree as $iBaseRubric => $aChildsRubric) {
		            if($aGroupsAssoc[$iBaseRubric]['is_main']) {
		            	$aRubricChilds=array();
		                if($aChildsRubric) {
		                    foreach ($aChildsRubric as $iChildRubric) {
		                        $aChilds=array();
		                        $aBrands=Db::GetAll(Base::GetSql("Price/GroupPref",array(
        							"id_price_group"=>$iChildRubric,
        							"join_price"=>1,
        							"order"=> " order by c.title",
        							"where"=>" and c.visible=1",
    							)));
		                        
		                        $aLevel2Tmp = Db::GetAll("SELECT * FROM price_group WHERE id_parent='".$iChildRubric."' and visible=1");
		                        if($aLevel2Tmp){
		                        	$aChildsBrandTmp = array();
		                        	if($aLevel2Tmp)foreach ($aLevel2Tmp as $sKeyTmp => $aValueTmp) {
		                        		if($aGroupsAssoc[$aValueTmp['id']]){
			                        		$aBrands2=Db::GetAll(Base::GetSql("Price/GroupPref",array(
			        							"id_price_group"=>$aValueTmp['id'],
			        							"join_price"=>1,
			        							"order"=> " order by c.title",
			        							"where"=>" and c.visible=1",
			    							)));
			    							
			    							$aChildsBrandTmp = array();
			    							foreach ($aBrands2 as $aVal2) {
		                            
					                            $sUrl2="/avtotovaru/".$aGroupsAssoc[$aValueTmp['id']]['code_name'].'/'.mb_strtolower($aVal2['c_name']);
					                            $sName2=$aGroupsAssoc[$aValueTmp['id']]['name']." ".$aVal2['c_title'];
					                            
					                            $aChildsBrandTmp[]=array('name'=>strip_tags($sName2), 'url'=>$sUrl2, 'childs'=>'0');
					                            
					                        }
		                        			
			    							$sUrl2="/avtotovaru/".$aGroupsAssoc[$aValueTmp['id']]['code_name'].'/';
				                            $sName2=$aGroupsAssoc[$aValueTmp['id']]['name'];
				                        	$aChilds[]=array('name'=>strip_tags($sName2), 'url'=>$sUrl2, 'childs'=>$aChildsBrandTmp);
				                        }
		                        	}

		                        	
		                        }
		                        foreach ($aBrands as $aVal) {
		                            
		                            $sUrl="/avtotovaru/".$aGroupsAssoc[$iChildRubric]['code_name'].'/'.mb_strtolower($aVal['c_name']);
		                            $sName=$aGroupsAssoc[$iChildRubric]['name']." ".$aVal['c_title'];
		                            
		                            $aChilds[]=array('name'=>strip_tags($sName), 'url'=>$sUrl, 'childs'=>'0');
		                        }
		                        
		                        usort($aChilds, array(self, 'SortByName'));
		                        $aRubricChilds[]=array(
		                            'name'=>$aGroupsAssoc[$iChildRubric]['name'], 
		                            'url'=>"/avtotovaru/".$aGroupsAssoc[$iChildRubric]['code_name'], 
		                            'childs'=>$aChilds
		                        );
		                    }
		                }
		                usort($aRubricChilds, array(self, 'SortByName'));
		                $aSitemapChilds[]=array(
		                    'name'=>$aGroupsAssoc[$iBaseRubric]['name'], 
		                    'url'=>"/avtotovaru/".$aGroupsAssoc[$iBaseRubric]['code_name'], 
		                    'childs'=>$aRubricChilds
		                );
		            }
		        }

                $aPriceGroupSitemap=$aSitemapChilds;
                unset($aSitemapChilds);
		        //$aSitemap[]=array('name'=>'Автозапчасти по группам', 'url'=>'/pages/rubricator/', 'childs'=>$aSitemapChilds);
		    }
		}
		
		if(!$aPriceGroupSitemap) $aPriceGroupSitemap=array();
		if(!$aRubricatorSitemap) $aRubricatorSitemap=array();
		$aAll=array_merge($aRubricatorSitemap,$aPriceGroupSitemap);
		usort($aAll, array(self, 'SortByName'));
		$aSitemap[]=array('name'=>'Автозапчасти по группам', 'url'=>'/pages/zapchasti/', 'childs'=>$aAll);
		//-----------------------------------------------------------------------------------------------------------------------
		//drop down
		$aDropDown=Db::GetAll(Base::GetSql("DropDown",array(
		    'where'=>" and d.level='1' and d.is_menu_visible='1' and d.code<>'catalog' "
		))." order by d.name ");
		if($aDropDown) {
		    foreach ($aDropDown as $aValue) {
		        $aSitemap[]=array('name'=>$aValue['name'], 'url'=>'/pages/'.$aValue['code'], 'childs'=>0);
		    }
		   //$aSitemap[]=array('name'=>'VIN запрос', 'url'=>'/pages/vin_request_add/', 'childs'=>0);
		}
		//-----------------------------------------------------------------------------------------------------------------------
		return $aSitemap;
	}
	//-----------------------------------------------------------------------------------------------------------------------------
	function SortByName ($a, $b)
	{
		if ($a['name'] == $b['name']) {
			return 0;
		}
		return ($a['name'] < $b['name']) ? -1 : 1;
	}
	function GetNameDropDownAdditional($sUrl)
	{
		$aName = Db::GetRow($s=Base::GetSql('CoreDropDownAdditional',array(
		'visible'=>1,
		'where'=>" and  dda.url like '".$sUrl."'",
		)));
		
		preg_match_all('#<h1>(.+?)</h1>#is', $aName['description_top'], $arr);
		$sName1 = $arr['1']['0'];
		Base::$tpl->assign('sSmartyTemplate', $sName1);
		$sName = Base::$tpl->fetch('addon/smarty_template.tpl');

		return $sName;
	}
	//-----------------------------------------------------------------------------------------------
}
?>