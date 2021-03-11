<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class AContextHint extends Admin {

	//-----------------------------------------------------------------------------------------------
	function AContextHint() {
		$this->sTableName='context_hint';
		$this->sTablePrefix='ch';
		$this->sAction='context_hint';
		$this->sWinHead=Language::getDMessage('Context Hint');
		$this->sPath=Language::GetDMessage('>>Customer support >');
		$this->aCheckField=array('key_');
		//$this->aFCKEditors = array ('content' );
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		Base::$sText .= $this->SearchForm();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong', 0)) {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and ch.id = '" . $this->aSearch['id'] . "'";
                if ($this->aSearch['key_'])
                    $this->sSearchSQL .= " and ch.key_ = '" . $this->aSearch['key_'] . "'";
                if ($this->aSearch['content'])
                    $this->sSearchSQL .= " and ch.content = '" . $this->aSearch['content'] . "'";
            } else {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and ch.id like '%" . $this->aSearch['id'] . "%'";
                if ($this->aSearch['key_'])
                    $this->sSearchSQL .= " and ch.key_ like '%" . $this->aSearch['key_'] . "%'";
                if ($this->aSearch['content'])
                    $this->sSearchSQL .= " and ch.content like '%" . $this->aSearch['content'] . "%'";
            }
            if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and ch.visible = '1'";
            if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and ch.visible = '0'";
            //with else "ignore" will not be found
            switch($this->aSearch['visible']){
                case '1':
                    $this->sSearchSQL.=" and ch.visible>='1'";
                    break;
                case '0':
                    $this->sSearchSQL.=" and ch.visible>='0'";
                    break;
                case  '':
                    break;
            }
        }

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn = array ();
		$oTable->aColumn['id']=array('sTitle'=>'Id','sOrder'=>'ch.id');
		$oTable->aColumn['key_']=array('sTitle'=>'Key','sOrder'=>'ch.key_');
		$oTable->aColumn['content']=array('sTitle'=>'Content','sOrder'=>'ch.content');
		$oTable->aColumn['visible']=array('sTitle'=>'Visible','sOrder'=>'ch.visible');
		$oTable->aColumn['num']=array('sTitle'=>'Num','sOrder'=>'ch.num');
		$this->initLocaleGlobal ();
		$oTable->aColumn ['language'] = array ('sTitle' => 'Lang' );
		$oTable->aColumn['action']=array();
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
}
?>