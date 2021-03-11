<?
/**
 * @author 
 *
 */
class ACatCross extends Admin {
	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName='cat_cross';
		$this->sTablePrefix='cc';
		$this->sTableId='id';
		$this->sAction='cat_cross';
		$this->sSqlPath = "Catalog/PartCross";
		$this->sWinHead=Language::getDMessage('cross');
		$this->sPath=Language::GetDMessage('>>Auto catalog >');
		$this->aCheckField=array('pref','pref_crs','code','code_crs');

		$this->sBeforeAddMethod='BeforeAdd';
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();
		
		Base::$sText .= $this->SearchForm ();
		if ($this->aSearch) {
		    if (Language::getConstant('mpanel_search_strong',0)) {
		        if ($this->aSearch['id'])$this->sSearchSQL .= " and cc.id = '".$this->aSearch['id']."'";
		        if ($this->aSearch['pref'])	$this->sSearchSQL .= " and cc.pref = '".$this->aSearch['pref']."'";
		        if ($this->aSearch['code'])	$this->sSearchSQL .= " and cc.code = '".Catalog::StripCode($this->aSearch['code'])."'";
		        if ($this->aSearch['pref_crs'])	$this->sSearchSQL .= " and cc.pref_crs = '".$this->aSearch['pref_crs']."'";
		        if ($this->aSearch['code_crs'])	$this->sSearchSQL .= " and cc.code_crs = '".Catalog::StripCode($this->aSearch['code_crs'])."'";
		        if ($this->aSearch['source'])	$this->sSearchSQL .= " and cc.source = '".$this->aSearch['source']."'";
		    } else {
		        if ($this->aSearch['id'])$this->sSearchSQL .= " and cc.id like '%".$this->aSearch['id']."%'";
		        if ($this->aSearch['pref'])	$this->sSearchSQL .= " and cc.pref like '%".$this->aSearch['pref']."%'";
		        if ($this->aSearch['code'])	$this->sSearchSQL .= " and cc.code like '%".Catalog::StripCode($this->aSearch['code'])."%'";
		        if ($this->aSearch['pref_crs'])	$this->sSearchSQL .= " and cc.pref_crs like '%".$this->aSearch['pref_crs']."%'";
		        if ($this->aSearch['code_crs'])	$this->sSearchSQL .= " and cc.code_crs like '%".Catalog::StripCode($this->aSearch['code_crs'])."%'";
		        if ($this->aSearch['source'])	$this->sSearchSQL .= " and cc.source like '%".$this->aSearch['source']."%'";
		    }
		}
		
		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>Language::getDMessage('Id'), 'sOrder'=>'cc.id', 'sMethod'=>'exact'),
		'pref'=>array('sTitle'=>Language::getDMessage('Pref'), 'sOrder'=>'cc.pref'),
		'code'=>array('sTitle'=>Language::getDMessage('code'), 'sOrder'=>'cc.code'),
		'pref_crs'=>array('sTitle'=>Language::getDMessage('pref_crs'), 'sOrder'=>'cc.pref_crs'),
		'code_crs'=>array('sTitle'=>Language::getDMessage('code_crs'), 'sOrder'=>'cc.code_crs'),
		'source'=>array('sTitle'=>Language::getDMessage('source'), 'sOrder'=>'cc.source'),
		'action' => array ()
		);
		$oTable->bCheckVisible=false;
		$this->SetDefaultTable($oTable, array(
		    'join'=>1
		));
		Base::$tpl->assign("aPref", $aPref = array("" => "") + Db::GetAssoc("Assoc/Pref", array('all' => 1)));
		
		Base::$sText.=$oTable->getTable();
		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAdd() {
		Base::$tpl->assign('aPref',Base::$db->getAssoc("select pref, concat(title,' ',pref) as name from cat order by name"));
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeApply()
	{
	    Base::$aRequest['data']['code']=Catalog::StripCode(Base::$aRequest['data']['code']);
	    Base::$aRequest['data']['code_crs']=Catalog::StripCode(Base::$aRequest['data']['code_crs']);
	}
	//-----------------------------------------------------------------------------------------------
	public function AfterApply($aBeforeRow,$aAfterRow) {
	    if($aBeforeRow) {
	        Db::Execute("delete from cat_cross where pref='".$aBeforeRow['pref_crs']."' and code='".$aBeforeRow['code_crs']."' and pref_crs='".$aBeforeRow['pref']."' and code_crs='".$aBeforeRow['code']."' ");
	    }
	    
	    Db::Execute(" insert ignore into cat_cross (pref, code, pref_crs, code_crs, source, id_manager) values 
	        ('".$aAfterRow['pref_crs']."','".$aAfterRow['code_crs']."','".$aAfterRow['pref']."','".$aAfterRow['code']."','".$aAfterRow['source']."','".$aAfterRow['id_manager']."') ");
	}
	//-----------------------------------------------------------------------------------------------
	public function Delete()
	{
	    if (is_array ( Base::$aRequest ['row_check'] )) {
	        $aDelTmp = Db::GetAll("select * from `" . $this->sTableName . "` where " . $this->sTableId . " in(" . implode (',', Base::$aRequest ['row_check'] ) . ")");
	        if($aDelTmp) {
	            foreach ($aDelTmp as $aDelRow) {
	                Db::Execute("delete from cat_cross where pref='".$aDelRow['pref_crs']."' and code='".$aDelRow['code_crs']."' and pref_crs='".$aDelRow['pref']."' and code_crs='".$aDelRow['code']."' ");
	            }
	        }
	        
	        Db::Execute ("delete from " . $this->sTableName . " where " . $this->sTableId . " in(" . implode (',', Base::$aRequest ['row_check'] ) . ")" );
	    } else {
	        $aDelRow = Db::GetRow("select * from `" . $this->sTableName . "` where " . $this->sTableId . "='" . Base::$aRequest ['id'] ."'");
	        Db::Execute("delete from cat_cross where pref='".$aDelRow['pref_crs']."' and code='".$aDelRow['code_crs']."' and pref_crs='".$aDelRow['pref']."' and code_crs='".$aDelRow['code']."' ");
	        
	        Db::Execute ("delete from `" . $this->sTableName . "` where " . $this->sTableId . "='" . Base::$aRequest ['id'] ."'" );
	    }
	            
        $this->AdminRedirect ( $this->sAction ); //not tested yet
	}
	//-----------------------------------------------------------------------------------------------
	public function  Import(){
	    $this->sAction = "cat_cross/import";
	    Base::$tpl->assign('sReturn', stripslashes(Base::$aRequest['return']));
	    $this->ProcessTemplateForm("Import");
	}
	//-----------------------------------------------------------------------------------------------
	public function ImportApply(){
	    $sUploadDir = '/imgbank/temp_upload/mpanel/';
	    $sFile = $_SERVER['DOCUMENT_ROOT'].$sUploadDir.Base::$aRequest['data']['upload_xls'];
	    if (Base::$aRequest['data']['upload_xls'] && file_exists($sFile)) {
	        set_time_limit(0);
	        
	        $aPref = Base::$db->getAssoc("
				select upper(title) as name, pref from cat
				union
				select upper(name) as name, pref from cat
				union
				select upper(cp.name) as name,c.pref FROM cat_pref as cp
				inner join cat as c on c.id=cp.cat_id
				");
	        
	        ini_set("memory_limit", -1);
	        $aPathInfo = pathinfo($sFile);
	        
	        if ($aPathInfo['extension'] == 'xlsx') {
	            $oExcel = new Excel();
	            $oExcel->ReadExcel7($sFile, true, false);
	            $oExcel->SetActiveSheetIndex();
	            $aResult = $oExcel->GetSpreadsheetData();
	        } else {
	            $oExcel = new Excel();
	            $oExcel->ReadExcel5($sFile, true);
	            $oExcel->SetActiveSheetIndex();
	            $oExcel->GetActiveSheet();
	        
	            $aResult = $oExcel->GetSpreadsheetData();
	        }
	        
	        if ($aResult) {
	            foreach ($aResult as $sKey => $aValue) {
	                if ($sKey > 1) {
	                    $aData['pref'] = $aPref[strtoupper(trim($aValue[1]))];
	                    $aData['code'] = Catalog::StripCode(strtoupper($aValue[2]));
	                    if (trim($aValue[3]) == '' && trim($aValue[1]) != '')
	                        $aData['pref_crs'] = $aData['pref'];
	                    else
	                        $aData['pref_crs'] = $aPref[strtoupper(trim($aValue[3]))];
	        
	                    $aData['code_crs'] = Catalog::StripCode(strtoupper($aValue[4]));
	                    $aData['source'] = Catalog::StripCode(strtoupper($aValue[5]));
	        
	                    if ($aData['pref'] && $aData['code'] && $aData['pref_crs'] && $aData['code_crs']) {
	                        if (strpos($aData['code'], ";") === false) {
	                            $this->InsertCross($aData);
	                        } else {
	                            $aCode = explode(";", $aData['code']);
	                            foreach ($aCode as $sCode) {
	                                $aData['code'] = $sCode;
	                                $this->InsertCross($aData);
	                            }
	                        }
	                    } else {
	                        if (!$aData['pref'])
	                            Db::Execute("insert ignore into cat_pref (name) values (upper('" . trim($aValue[1]) . "'))");
	                        if (!$aData['pref_crs'])
	                            Db::Execute("insert ignore into cat_pref (name) values (upper('" . trim($aValue[3]) . "'))");
	                    }
	                }
	            }
	        }
	        	
	        $this->AdminRedirect ( $this->sAction );
	    }
	}
	//-----------------------------------------------------------------------------------------------
	function InsertCross($aData)
	{
	    static $sPrefMers;
	
	    if (!$sPrefMers)
	        $sPrefMers = Db::GetOne("SELECT pref FROM `cat` WHERE id_mfa = " . Language::getConstant("mercedes:id_src_tecdoc", 74)); // MERCEDES || MERCEDESBENZ
	
	    if ($aData['pref'] && $aData['code'] && $aData['pref_crs'] && $aData['code_crs']
	        && !(strcasecmp($aData['code'], $aData['code_crs']) == 0 && $aData['pref'] == $aData['pref_crs'])
	    ) {
	        if ((preg_match('/^A[0-9]{10}$/', $aData['code']) || preg_match('/^A[0-9]{11}$/', $aData['code'])
	            || preg_match('/^A[0-9]{12}$/', $aData['code'])) && $sPrefMers == $aData['pref'])
	                $aData['code'] = ltrim($aData['code'], 'A');
	
	            if ((preg_match('/^A[0-9]{10}$/', $aData['code_crs']) || preg_match('/^A[0-9]{11}$/', $aData['code_crs'])
	                || preg_match('/^A[0-9]{12}$/', $aData['code_crs'])) && $sPrefMers == $aData['pref_crs'])
	                    $aData['code_crs'] = ltrim($aData['code_crs'], 'A');
	
	                // мерседесные коды сам на себя записи создавались
	                if (strcasecmp($aData['code'], $aData['code_crs']) == 0 && $aData['pref'] == $aData['pref_crs'])
	                    return false;
	
	                Db::Execute(" insert ignore into cat_cross (pref, code, pref_crs, code_crs, source, id_manager)
					values ('" . $aData['pref'] . "','" . $aData['code'] . "','" . $aData['pref_crs'] . "','" . $aData['code_crs'] . "','" . $aData['source'] . "','" . Auth::$aUser['id_user'] . "')
					, ('" . $aData['pref_crs'] . "','" . $aData['code_crs'] . "','" . $aData['pref'] . "','" . $aData['code'] . "','" . $aData['source'] . "','" . Auth::$aUser['id_user'] . "')
			    on duplicate key update source=values(source), id_manager=values(id_manager)
					");
	                return true;
	    } else {
	        return false;
	    }
	}
	//-----------------------------------------------------------------------------------------------
}
?>
