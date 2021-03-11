<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class AConstant extends Admin {

	//-----------------------------------------------------------------------------------------------
	function AConstant() {
		$this->sTableName='constant';
		$this->sTablePrefix='c';
		$this->sAction='constant';
		$this->sWinHead=Language::getDMessage('Constants');
		$this->sPath=Language::GetDMessage('>>Configuration >');
		$this->aCheckField=array('key_','value');
	    if(file_exists(SERVER_PATH."/template/mpanel/dtree_new.tpl")) {
		    //use /template/mpanel/***
		} else {
		    $this->sAddonPath='addon/';
		}
		$this->sSqlPath='Constant';
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong',0)) {
                if ($this->aSearch['id'])$this->sSearchSQL .= " and c.id = '".$this->aSearch['id']."'";
                if ($this->aSearch['key_'])$this->sSearchSQL .= " and c.key_ = '".$this->aSearch['key_']."'";
                if ($this->aSearch['value'])	$this->sSearchSQL .= " and c.value = '".$this->aSearch['value']."'";
                if ($this->aSearch['description'])	$this->sSearchSQL .= " and c.description = '".$this->aSearch['description']."'";
            }
            else {
                if ($this->aSearch['id'])$this->sSearchSQL .= " and c.id = '".$this->aSearch['id']."'";
                if ($this->aSearch['key_'])$this->sSearchSQL .= " and c.key_ like '%".$this->aSearch['key_']."%'";
                if ($this->aSearch['value'])	$this->sSearchSQL .= " and c.value like '%".$this->aSearch['value']."%'";
                if ($this->aSearch['description'])	$this->sSearchSQL .= " and c.description like '%".$this->aSearch['description']."%'";
            }
        }

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id','sOrder'=>'c.id'),
		'key_'=>array('sTitle'=>'Key','sOrder'=>'c.key_'),
		'value'=>array('sTitle'=>'Value','sOrder'=>'c.value'),
		'description'=>array('sTitle'=>'Description','sOrder'=>'c.description'),
		'action'=>array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
}
?>