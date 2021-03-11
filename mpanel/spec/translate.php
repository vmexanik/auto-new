<?


class ATranslate extends Admin {

	public $sPathToFile='/imgbank/temp_upload/';
	
	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$aTranslateLanguageList=Db::GetAll(Base::GetSql("Language",array('where'=>" and l.id>1")));
		Base::$tpl->assign('aTranslateLanguageList',$aTranslateLanguageList);
		if (!$_SESSION['translate']['current_locale']) {
			$_SESSION['translate']['current_locale']=$aTranslateLanguageList[0]['code'];
		}

		$this->sTableName='translate';
		$this->sTablePrefix='t';
		$this->sAction='translate';
		$this->sWinHead=Language::getDMessage('Translate');
		$this->sPath=Language::GetDMessage('>>Customer support >');
		//$this->aCheckField=array('login','name','description');
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
                if ($this->aSearch['code'])
                    $this->sSearchSQL .= " and t.code = '" . $this->aSearch['code'] . "'";
                if ($this->aSearch['content'])
                    $this->sSearchSQL .= " and t.content = '" . $this->aSearch['content'] . "'";
            } else {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and t.id like '%" . $this->aSearch['id'] . "%'";
                if ($this->aSearch['code'])
                    $this->sSearchSQL .= " and t.code like '%" . $this->aSearch['code'] . "%'";
                if ($this->aSearch['content'])
                    $this->sSearchSQL .= " and t.content like '%" . $this->aSearch['content'] . "%'";
            }
        }

		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id','sOrder'=>'t.id'),
		'code'=>array('sTitle'=>'code','sOrder'=>'t.code'),
		'content'=>array('sTitle'=>'content','sOrder'=>'l.content'),
		'name'=>array(),
		'description'=>array(),
		'description2'=>array(),
		'action'=>array(),
		);
		$this->SetDefaultTable($oTable);
		$oTable->sDefaultOrder=" order by t.id";
		Base::$sText.=$oTable->getTable();
		//Base::$sText.=Base::$tpl->fetch('mpanel/translate/save_button.tpl');

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function Change()
	{
		$_SESSION['translate']['current_locale']=Base::$aRequest['content'];
		$this->index();
	}
	//-----------------------------------------------------------------------------------------------
	public function ExportTranslation()
	{
		$aTmp = array('B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','R','S','T','Q','S');
		
			$sFileName=DateFormat::GetFileDateTime(time(),'',false)."_translations.xls";

			$aLanguage=Db::GetAll('select code from language');
			
			if (Base::$aRequest['row_check']){
				foreach (Base::$aRequest['row_check'] as $sKey => $sValue){
					$aData[$sKey]=Db::GetRow("select id,code,content from translate_message where id='".$sValue."'");
				}
			}
			else {
				$aData=Db::GetAll("select id,code,content from translate_message");
			}
			foreach ($aData as $sKey => $aValue){
				foreach ($aLanguage as $sLangKey => $alangVal){
					if ($sLangKey==0){
						$sRow=Db::GetOne("select content from translate_message where code='".$aValue['code']."'");
					}
					else {
						$sRow=Db::GetOne("select content from locale_global where table_name='translate_message' and id_reference='".$aValue['id']."' and locale='".$alangVal['code']."'");
					}
					if(!$sRow) $sRow="-";
					$aData[$sKey]['translate'][$sLangKey]=$sRow;
				}
			}
			/*
			*  Txt export
			*
			// All of transtale texts
			$sData="";
			foreach ($aData as $aValue){
				$sOtherLanguages="";
				foreach ($aValue['translate'] as $aOtherValue){
					$sOtherLanguages.="|".$aOtherValue;
				}
				$sData.=$aValue['code'].$sOtherLanguages."\n";
			}
			// Head of file
			$sFileHead="code";
			foreach ($aLanguage as $aValue){
				$sFileHead.="|".$aValue['code'];
			}
			$sFileHead.="\n";
			$sData=$sFileHead.$sData;

			file_put_contents(SERVER_PATH.$this->sPathToFile.$sFileName,$sData);
			*/
			if ($aData){
				$oExcel = new Excel();
					$aHeader=array(
					'A'=>array("value"=>'code'),);
					foreach ($aLanguage as $sKey => $aValue){
						$aHeader=$aHeader+array($aTmp[$sKey]=>array("value"=>$aValue['code']));
					}
				$oExcel->SetHeaderValue($aHeader,1);
				$oExcel->SetAutoSize($aHeader);
				$oExcel->DuplicateStyleArray("A1:U1");

				$i=2;
				
				foreach ($aData as $aValue)
				{
					$oExcel->setCellValue('A'.$i, $aValue['code']);
					
					foreach ($aValue['translate'] as $sKey => $aTranslateValue){
						$oExcel->setCellValue($aTmp[$sKey].$i, $aTranslateValue);
					}
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
	public function Save()
	{
		//Base::$oResponse->addAlert('1');

		if (Base::$aRequest['content']) foreach (Base::$aRequest['content'] as $sKey => $sValue) {
			Db::Execute("update locale_global set content='".$sValue."' where i='".$sKey."'");
		}
		$this->index();
	}
	//-----------------------------------------------------------------------------------------------
	public function  ImportTranslation(){
		$this->sAction = "translate/import";
		Base::$tpl->assign('sReturn', stripslashes(Base::$aRequest['return']));
		$this->ProcessTemplateForm("Import translation");
	}
	//-----------------------------------------------------------------------------------------------
	public function ImportTranslationApply(){
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
			
		/*
		* txt import
		*
			$sData=file_get_contents($sFile);
			$aData=explode("\n",$sData);
			unset($sData);
			foreach ($aData as $sKey => $aValue){
				$aData[$sKey]=explode("|",$aValue);
			}
			*/
			$sBaseLanguage=Db::GetOne("select code from language where id=1");
		
			$aLanguages=$aData[1];
			unset($aLanguages[1]);
			unset($aData[1]);
			foreach ($aLanguages as $sKey => $aValue){
				$aLanguages[$sKey]=substr($aValue, 4);
			}
			foreach ($aData as $sKey => $aValue){
				$iId=addslashes(Db::GetOne("select id from translate_message where code='".addslashes($aValue[1])."'"));
				foreach ($aLanguages as $sLanguageKey => $sValue) {
					$aValue[$sLanguageKey]=addslashes(trim($aValue[$sLanguageKey]));
					$sValue=trim($sValue);
					if ($aLanguages[$sLanguageKey]==$sBaseLanguage){
						$sSql="UPDATE translate_message SET content='".$aValue[$sLanguageKey]."' WHERE code='".$aValue[1]."'";
					}
					else {
						$sSql="INSERT INTO locale_global (content, id_reference, locale, table_name) VALUES ('".$aValue[$sLanguageKey]."', '".$iId."', '".$sValue."', 'translate_message')
							ON DUPLICATE KEY UPDATE content='".$aValue[$sLanguageKey]."'";
					}
					Db::Execute($sSql);		
				}
			}
			
			
			$this->AdminRedirect ( $this->sAction );
			//$this->Index();
		}
	}
	//-----------------------------------------------------------------------------------------------
}
?>