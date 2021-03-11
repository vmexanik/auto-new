<?
/**
 * @author 
 *
 */
class AComplexMargin extends Admin {

	public $sPathToFile='/imgbank/temp_upload/';
	
	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName = 'margin_price';
		$this->sTablePrefix = 't';
		$this->sAction = 'complex_margin';
		$this->sWinHead = Language::getDMessage ( 'Complex Margin' );
		$this->sPath = Language::GetDMessage('>>Users >');
		$this->aCheckField = array ('margin');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong', 0)) {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and t.id = '" . $this->aSearch['id'] . "'";
                if ($this->aSearch['name'])
                    $this->sSearchSQL .= " and t.name = '" . $this->aSearch['name'] . "'";
                    if ($this->aSearch['currency'])
                    $this->sSearchSQL .= " and t.currency = '" . $this->aSearch['currency'] . "'";
                if ($this->aSearch['price_before'])
                    $this->sSearchSQL .= " and t.price_before = '" . $this->aSearch['price_before'] . "'";
                if ($this->aSearch['price_after'])
                    $this->sSearchSQL .= " and t.price_after = '" . $this->aSearch['price_after'] . "'";
                if ($this->aSearch['brand'])
                    $this->sSearchSQL .= " and t.brand = '" . $this->aSearch['brand'] . "'";
                if ($this->aSearch['provider'])
                    $this->sSearchSQL .= " and t.provider = '" . $this->aSearch['provider'] . "'";
                if ($this->aSearch['price_group'])
                    $this->sSearchSQL .= " and t.price_group = '" . $this->aSearch['price_group'] . "'";
                if ($this->aSearch['code'])
                    $this->sSearchSQL .= " and t.code = '" . $this->aSearch['code'] . "'";
                if ($this->aSearch['margin'])
                    $this->sSearchSQL .= " and t.margin = '" . $this->aSearch['margin'] . "'";

            } else {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and t.id like '%" . $this->aSearch['id'] . "%'";
                if ($this->aSearch['code'])
                    $this->sSearchSQL .= " and t.code like '%" . $this->aSearch['code'] . "%'";
                if ($this->aSearch['name'])
                    $this->sSearchSQL .= " and t.name like '%" . $this->aSearch['name'] . "%'";
                if ($this->aSearch['currency'])
                    $this->sSearchSQL .= " and t.currency like '%" . $this->aSearch['currency'] . "%'";
                if ($this->aSearch['price_before'])
                    $this->sSearchSQL .= " and t.price_before like '%" . $this->aSearch['price_before'] . "%'";
                if ($this->aSearch['price_after'])
                    $this->sSearchSQL .= " and t.price_after like '%" . $this->aSearch['price_after'] . "%'";
                if ($this->aSearch['brand'])
                    $this->sSearchSQL .= " and t.brand like '%" . $this->aSearch['brand'] . "%'";
                if ($this->aSearch['provider'])
                    $this->sSearchSQL .= " and t.provider like '%" . $this->aSearch['provider'] . "%'";
                if ($this->aSearch['price_group'])
                    $this->sSearchSQL .= " and t.price_group like '%" . $this->aSearch['price_group'] . "%'";
                if ($this->aSearch['margin'])
                    $this->sSearchSQL .= " and t.currency like '%" . $this->aSearch['margin'] . "%'";
            }
            if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and t.visible = '1'";
            if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and t.visible = '0'";
            //with else "ignore" will not be found
            switch($this->aSearch['visible']){
                case '1':
                    $this->sSearchSQL.=" and t.visible>='1'";
                    break;
                case '0':
                    $this->sSearchSQL.=" and t.visible>='0'";
                    break;
                case  '':
                    break;
            }
        }

		$this->initLocaleGlobal();
		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=> array('sTitle'=>'Id', 'sOrder'=>'t.id'),
		'name'=> array('sTitle'=>'name', 'sOrder'=>'t.name'),
		'currency'=> array('sTitle'=>'currency', 'sOrder'=>'t.id_currency'),
		'price_before'=> array('sTitle'=>'price_before', 'sOrder'=>'t.price_before'),
		'price_after'=> array('sTitle'=>'price_after', 'sOrder'=>'t.price_after'),
		'brand'=> array('sTitle'=>'brand', 'sOrder'=>'brand'),
		'provider'=> array('sTitle'=>'provider', 'sOrder'=>'provider'),
		'price_group'=> array('sTitle'=>'price_group', 'sOrder'=>'price_group'),
		'code'=> array('sTitle'=>'price group code', 'sOrder'=>'code'),
		'margin' => array('sTitle'=>'Margin' , 'sOrder'=>'t.margin'),
		'visible' => array('sTitle'=>'Visible', 'sOrder'=>'t.visible'),
		'action'=> array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign(&$aData)
	{
		Base::$tpl->assign('aData',$aData);
		Base::$tpl->assign('aCurrency', array(''=>'')+Db::GetAssoc("Assoc/Currency",array("type_"=>"id")));
		
		$this->ChangeProvider();
		$this->ChangeCat();
		$this->ChangeGroup();
	}
	//-----------------------------------------------------------------------------------------------
	public function ChangeCat() {
		if(!isset(Base::$aRequest['all_records'])) $bSelectAll=1;
		else $bSelectAll=Base::$aRequest['all_records'];
		
		if($bSelectAll) $sWhere='1';
		else $sWhere=" visible=1";
		
		$aCat=Db::GetAssoc("select id,title from cat where".$sWhere."
			 order by title");
		
		if(isset(Base::$aRequest['data'])) Base::$tpl->assign('aData',Base::$aRequest['data']);
		Base::$tpl->assign('bAllRecords',$bSelectAll);
		Base::$tpl->assign('aCat',array("0"=>Language::GetMessage("not selected"))+$aCat);
		$sText=Base::$tpl->fetch('mpanel/complex_margin/cat_select.tpl');
		
		if(Base::$aRequest['is_change']) {
			Base::$oResponse->addAssign('cat_col','innerHTML',$sText);
		} else {
			Base::$tpl->assign('sCat',$sText);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function ChangeProvider() {
		if(!isset(Base::$aRequest['all_records'])) $bSelectAll=1;
		else $bSelectAll=Base::$aRequest['all_records'];
		
		if($bSelectAll==1) $sWhere='';
		else $sWhere=" and u.visible=1";
		
		$aProviders=Db::GetAssoc("select up.id_user,up.name from user_provider as up
			inner join user as u on u.id=up.id_user".$sWhere."
			order by up.name");
		
		if(isset(Base::$aRequest['data'])) Base::$tpl->assign('aData',Base::$aRequest['data']);
		Base::$tpl->assign('bAllRecords',$bSelectAll);
		Base::$tpl->assign('aProviders',array("0"=>Language::GetMessage("not selected"))+$aProviders);
		$sText=Base::$tpl->fetch('mpanel/complex_margin/provider_select.tpl');
		
		if(Base::$aRequest['is_change']) {
			Base::$oResponse->addAssign('provider_col','innerHTML',$sText);
		} else {
			Base::$tpl->assign('sProviders',$sText);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function ChangeGroup() {
		if(!isset(Base::$aRequest['all_records'])) $bSelectAll=1;
		else $bSelectAll=Base::$aRequest['all_records'];
		
		if($bSelectAll==1) $sWhere='1';
		else $sWhere=" visible=1";
		
		$aPriceGroup=Db::GetAssoc("select id,concat(code,' - ',name) as name from price_group where ".$sWhere."
			order by name");
		
		if(isset(Base::$aRequest['data'])) Base::$tpl->assign('aData',Base::$aRequest['data']);
		Base::$tpl->assign('bAllRecords',$bSelectAll);
		Base::$tpl->assign('aPriceGroup',array("0"=>Language::GetMessage("not selected"))+$aPriceGroup);
		$sText=Base::$tpl->fetch('mpanel/complex_margin/group_select.tpl');
		
		if(Base::$aRequest['is_change']) {
			Base::$oResponse->addAssign('group_col','innerHTML',$sText);
		} else {
			Base::$tpl->assign('sGroup',$sText);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function Copy()
	{
		if(Base::$aRequest['id']){
			$aData=Db::GetRow("select * from margin_price where id='".Base::$aRequest['id']."'");
			if($aData){
				unset($aData['id']);
				Db::AutoExecute("margin_price",$aData);
			}
		}
		$this->AdminRedirect ( $this->sAction );
	}
	//-----------------------------------------------------------------------------------------------
	public function Export()
	{
		$sFileName=DateFormat::GetFileDateTime(time(),'',false)."_margins.xls";
	
		$aData=Db::GetAll(Base::GetSql('MarginPrice'));
		
		if ($aData){
			$oExcel = new Excel();
			$aHeader=array(
				'A'=>array("value"=>'ID'),
				'B'=>array("value"=>'Нижняя цена'),
				'C'=>array("value"=>'Верхняя цена'),
				'D'=>array("value"=>'Производитель'),
				'E'=>array("value"=>'Поставщик'),
				'F'=>array("value"=>'Код группы деталей'),
				'G'=>array("value"=>'Группа деталей'),
				'H'=>array("value"=>'Наценка'),
				'I'=>array("value"=>'Видимый'),
				'J'=>array("value"=>'Валюта'),
				'K'=>array("value"=>'Название'),
			);
			
			$oExcel->SetHeaderValue($aHeader,1,false);
			$oExcel->SetAutoSize($aHeader);
			$oExcel->DuplicateStyleArray("A1:K1");
	
			$i=2;
	
			foreach ($aData as $aValue)
			{
				$oExcel->setCellValue('A'.$i, $aValue['id']);
				$oExcel->setCellValue('B'.$i, $aValue['price_before']);
				$oExcel->setCellValue('C'.$i, $aValue['price_after']);
				$oExcel->setCellValue('D'.$i, $aValue['brand']);
				$oExcel->setCellValue('E'.$i, $aValue['provider']);
				$oExcel->setCellValue('F'.$i, $aValue['code']);
				$oExcel->setCellValue('G'.$i, $aValue['price_group']);
				$oExcel->setCellValue('H'.$i, $aValue['margin']);
				$oExcel->setCellValue('I'.$i, $aValue['visible']);
				$oExcel->setCellValue('J'.$i, $aValue['currency']);
				$oExcel->setCellValue('K'.$i, $aValue['name']);
				
				$i++;
			}
			//end new sheet
			$oExcel->WriterExcel5(SERVER_PATH.$this->sPathToFile.$sFileName, false);
		}
	
		Base::$tpl->assign('sFileName',$sFileName);
		Base::$tpl->assign('sFilePath',$this->sPathToFile.$sFileName);
	
		Base::$sText .=Base::$tpl->fetch('mpanel/user/export_file.tpl');
		$this->Index();
	}
	//-----------------------------------------------------------------------------------------------
	public function  Import(){
		$this->sAction = "complex_margin/import";
		Base::$tpl->assign('sReturn', stripslashes(Base::$aRequest['return']));
		$this->ProcessTemplateForm("Import");
	}
	//-----------------------------------------------------------------------------------------------
	public function ImportApply(){
		$sUploadDir = '/imgbank/temp_upload/mpanel/';
		$sFile = $_SERVER['DOCUMENT_ROOT'].$sUploadDir.Base::$aRequest['data']['upload_txt'];
		if (Base::$aRequest['data']['upload_txt'] && file_exists($sFile)) {
			set_time_limit(0);
				
			require_once(SERVER_PATH.'/lib/excel/reader.php');
			$oReader = new Spreadsheet_Excel_Reader();
			$oReader->setOutputEncoding('CP1251');
			$oReader->setUTFEncoder('iconv');
			$oReader->setOutputEncoding('UTF-8');
			$oReader->read($sFile);
	
			$aData=$oReader->sheets[0]['cells'];
			
			$aKeys=array(
				'1'=>'id',
				'2'=>'price_before',
				'3'=>'price_after',
				'4'=>'brand',
				'5'=>'provider',
				'6'=>'code',
				'7'=>'price_group',
				'8'=>'margin',
				'9'=>'visible',
				'10'=>'currency',
				'11'=>'name',
			);
			function MapKeys($aKey, $aValue) {
				$aReturn=array();
				foreach ($aValue as $sKey =>$sValue) $aReturn[$aKey[$sKey]]=$sValue;
				return $aReturn;
			}
			
			$i=0;
			if ($aData) foreach ($aData as $aValue){
				$i++;
				if($i==1) continue;
				
				$aValue=MapKeys($aKeys,$aValue);
				
				if($aValue['id']) {
					//update info
					$aValue['id_cat']=Db::GetOne("select id from cat where title='".$aValue['brand']."' ");
					$aValue['id_provider']=Db::GetOne("select id_user from user_provider where name='".$aValue['provider']."' ");
					$aValue['id_price_group']=Db::GetOne("select id from price_group where code='".$aValue['code']."' ");
					
					$aValue['id_currency']=Db::GetOne("select id from currency where name='".$aValue['currency']."' ");
					if(!$aValue['id_currency']) $aValue['id_currency']=0;

					if(!$aValue['id_cat']) $aValue['id_cat']=0;
					if(!$aValue['id_provider']) $aValue['id_provider']=0;
					if(!$aValue['id_price_group']) $aValue['id_price_group']=0;
					
					Db::AutoExecute('margin_price',$aValue,'UPDATE',"id='".$aValue['id']."'");
				} else {
					//create new
					$aValue['id_cat']=Db::GetOne("select id from cat where title='".$aValue['brand']."' ");
					$aValue['id_provider']=Db::GetOne("select id_user from user_provider where name='".$aValue['provider']."' ");
					$aValue['id_price_group']=Db::GetOne("select id from price_group where code='".$aValue['code']."' ");

					$aValue['id_currency']=Db::GetOne("select id from currency where name='".$aValue['currency']."' ");
					if(!$aValue['id_currency']) $aValue['id_currency']=0;
					
					if(!$aValue['id_cat']) $aValue['id_cat']=0;
					if(!$aValue['id_provider']) $aValue['id_provider']=0;
					if(!$aValue['id_price_group']) $aValue['id_price_group']=0;
					
					if(!isset($aValue['visible'])) $aValue['visible']='1';
					Db::AutoExecute('margin_price',$aValue);
				}
			}
			
			$this->AdminRedirect ( $this->sAction );
		}
	}
	//-----------------------------------------------------------------------------------------------
}

?>