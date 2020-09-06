<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class AStyleColored extends Admin {

	//-----------------------------------------------------------------------------------------------
	function AStyleColored() {
		$this->sTableName='style_colored';
		$this->sTablePrefix='c';
		$this->sAction='style_colored';
		$this->sWinHead=Language::getDMessage('style_colored');
		$this->sPath=Language::GetDMessage('>>Configuration >');
		$this->aCheckField=array('name','value');
		$this->sSqlPath='StyleColored';
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id','sOrder'=>'c.id'),
		'name'=>array('sTitle'=>'Key','sOrder'=>'c.name'),
		'value'=>array('sTitle'=>'Value','sOrder'=>'c.value'),
		'default'=>array('sTitle'=>'default','sOrder'=>'c.default'),
		'description'=>array('sTitle'=>'Description','sOrder'=>'c.description'),
		'action'=>array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function Generate() {
	    $fTemplateCss = fopen(SERVER_PATH.'/css/main_colored_template.css', 'r');
	    $sTemplateCss = '';
	    while (!feof($fTemplateCss)) {
	        $sTemplateCss.=fread($fTemplateCss, 1);
	    }
	    fclose($fTemplateCss);
	     
	    $aReplacingData=Db::GetAssoc("select name, value from style_colored");
	    $fOutputCss = fopen(SERVER_PATH.'/css/main_colored.css', 'w+');
	    foreach ($aReplacingData as $sKey => $sValue) {
	        $sTemplateCss=str_replace($sKey, $sValue, $sTemplateCss);
	    }
	    fwrite($fOutputCss, $sTemplateCss);
	    fclose($fOutputCss);
	    
		$this->AdminRedirect ( $this->sAction, $aMessage );		
	}
	//-----------------------------------------------------------------------------------------------
	public function SetDefault() {
	    Db::Execute("update style_colored set value=`default`");
	    
		$this->AdminRedirect ( $this->sAction, $aMessage );		
	}
	//-----------------------------------------------------------------------------------------------
}
?>