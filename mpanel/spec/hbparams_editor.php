<?php
/*
 * 
 */
require_once (SERVER_PATH . '/class/core/Admin.php');
class AHbparamsEditor extends Admin {
	//-----------------------------------------------------------------------------------------------
	function AHbparamsEditor() {
		if (Base::$aRequest['data']['table_'] || Base::$aRequest['table']) {
			Base::$aRequest['data']['table_']?$this->sTableName = Base::$aRequest['data']['table_']:$this->sTableName = Base::$aRequest['table'];
			$this->sTablePrefix = 't';
			$this->sSqlPath = 'HbParamsEditor';
			$_SESSION['hb_editor_table']=$this->sTableName;
		} else {
			$this->sTablePrefix = 't';
			$this->sSqlPath = 'HbParamsEditor';
			$this->sTableName = $_SESSION['hb_editor_table'];
			Base::$aRequest['table']=$this->sTableName;
		}
		$this->sBeforeAddMethod='BeforeAdd';
		
		Base::$tpl->assign("sSelectedTable",$_SESSION['hb_editor_table']);
		
		$this->sAction = 'hbparams_editor';
		$this->sWinHead = Language::getDMessage('Handbooks');
		$this->sPath=Language::GetDMessage('>>Auto catalog >');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$sTablePref = 't.';
		$this->PreIndex();
		
		$aHAndbookTables=array(0=>"select item")+Db::GetAssoc("select table_,concat(table_,' ',name) as name from handbook");
		Base::$tpl->assign("aTables",$aHAndbookTables);
		Base::$sText.=Base::$tpl->fetch("mpanel/hbparams_editor/selector.tpl");
		
		if (Base::$aRequest['is_post']) {		
			require_once(SERVER_PATH.'/class/core/Table.php');
			$oTable=new Table();
			$this->initLocaleGlobal ();
			$oTable->aColumn=array(
				'id'=>array ('sTitle' => 'Id', 'sOrder' => $sTablePref.'id' ),
				'name'=>array ('sTitle' => 'Name', 'sOrder' => $sTablePref.'name' ),
				'visible'=>array ('sTitle' => 'visible', 'sOrder' => $sTablePref.'visible' ),
			    'language'=>array('sTitle' => 'Lang'),
				'action'=>array(),
			);
			if(!Base::$aRequest['table']) Base::$aRequest['table']=Base::$aRequest['data']['table_'];
			$this->CreateMap(Base::$aRequest['table']);
			
			$this->SetDefaultTable($oTable);
			Base::$sText.=Base::$tpl->fetch('mpanel/hbparams_editor/add_button.tpl');
			Base::$sText.=$oTable->getTable();
		}

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public static function CreateMap($sTableName,$sTemplateName="template_handbook"){
	    $sPath=SERVER_PATH."/include/locale_map/";
	    if(file_exists($sPath.$sTableName.".php")) return ;
	    $bSuc=copy($sPath.$sTemplateName.".php", $sPath.$sTableName.".php");
	    if($bSuc){
	        $sFile=file_get_contents($sPath.$sTableName.".php");
	        $sFile=str_replace($sTemplateName, $sTableName, $sFile);
	        file_put_contents($sPath.$sTableName.".php", $sFile);
	    }
	    if($sTemplateName!="template_handbook"){
	        unlink($sPath.$sTemplateName.".php");
	    }
	}
	//-----------------------------------------------------------------------------------------------
	/*public function ExcelHandbookImport() {
		$this->sAction = "hbparams_editor/import";
		Base::$tpl->assign('sReturn', stripslashes(Base::$aRequest['return']));
	
		Base::$oResponse->addAssign ( 'sub_menu', 'innerHTML', '' );
		Base::$oResponse->addAssign ( 'win_head', 'innerHTML', Language::getDMessage ( $sPath ) );
		//if (!$this->bIsAddon) {
		Base::$sText .= Base::$tpl->fetch($this->sAddonPath.'mpanel/'.$this->sAction.'/form_add.tpl');
		//		} else {
		//			Base::$sText .= Base::$tpl->fetch ( 'addon/mpanel/' . $this->sAction . '/form_add.tpl' );
		//		}
		Base::$oResponse->addScript ( '__FCKeditorNS = null;' );
		Base::$oResponse->addScript ( 'FCKeditorAPI = null;' );
		Base::$oResponse->addAssign ( 'sub_menu', 'innerHTML', '' );
		Base::$oResponse->addAssign ( 'win_text', 'innerHTML', Base::$sText );
		$this->Message ();
	
		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function ImportApply(){
		$sUploadDir = '/imgbank/temp_upload/mpanel/';
		$sFile = $_SERVER['DOCUMENT_ROOT'].$sUploadDir.Base::$aRequest['data']['upload_excel'];
		if (Base::$aRequest['data']['upload_excel'] && file_exists($sFile)) {
	
			require_once(SERVER_PATH.'/lib/excel/reader.php');
			$oReader = new Spreadsheet_Excel_Reader();
			$oReader->setOutputEncoding('UTF-8');
			$oReader->read($sFile);
	
			$aResult=$oReader->sheets[0]['cells'];
			if ($aResult){
				$iExcelLength=count($aResult);
				for ($i=1;$i<=$iExcelLength;$i++) {
					$sSql="insert into ".$aResult[$i][1]." (name, visible) values";
					
					for ($j=3;$j<=count($aResult[$i]);$j++){
						$sSql.="('".$aResult[$i][$j]."',1)";
						if ($j==count($aResult[$i])) $sSql.=";";
						else $sSql.=",";
					}
					Db::Execute($sSql);
					$sSql="";
				}
			}
			$this->AdminRedirect ( $this->sAction );
			//$this->Index();
		}
	}*/
	//-----------------------------------------------------------------------------------------------
}
?>