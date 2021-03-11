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

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong',0)) {
                if ($this->aSearch['id'])$this->sSearchSQL .= " and c.id = '".$this->aSearch['id']."'";
                if ($this->aSearch['name'])	$this->sSearchSQL .= " and c.name = '".$this->aSearch['name']."'";
                if ($this->aSearch['value'])	$this->sSearchSQL .= " and c.value = '".$this->aSearch['value']."'";
                if ($this->aSearch['default'])	$this->sSearchSQL .= " and c.default = '".$this->aSearch['default']."'";
                if ($this->aSearch['description'])	$this->sSearchSQL .= " and c.description = '".$this->aSearch['description']."'";
            }
            else {
                if ($this->aSearch['id'])$this->sSearchSQL .= " and c.id like '%".$this->aSearch['id']."%'";
                if ($this->aSearch['name'])	$this->sSearchSQL .= " and c.name like '%".$this->aSearch['name']."%'";
                if ($this->aSearch['value'])	$this->sSearchSQL .= " and c.value like '%".$this->aSearch['value']."%'";
                if ($this->aSearch['default'])	$this->sSearchSQL .= " and c.default like '%".$this->aSearch['default']."%'";
                if ($this->aSearch['description'])	$this->sSearchSQL .= " and c.description like '%".$this->aSearch['description']."%'";
            }
        }

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