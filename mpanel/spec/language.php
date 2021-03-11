<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class ALanguage extends Admin {

    //-----------------------------------------------------------------------------------------------
    function ALanguage() {
        $this->sTableName='language';
        $this->sTablePrefix='l';
        $this->sAction='language';
        $this->sWinHead=Language::getDMessage('Languages');
        $this->sPath=Language::GetDMessage('>>Configuration >');
        $this->aCheckField=array('name','code');
        if(file_exists(SERVER_PATH."/template/mpanel/dtree_new.tpl")) {
            //use /template/mpanel/***
        } else {
            $this->sAddonPath='addon/';
        }
        $this->sSqlPath='CoreLanguage';
        $this->Admin();
    }
    //-----------------------------------------------------------------------------------------------
    public function Index() {
        $this->PreIndex();

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong',0)) {
                if ($this->aSearch['id'])$this->sSearchSQL .= " and l.id = '".$this->aSearch['id']."'";
                if ($this->aSearch['code'])$this->sSearchSQL .= " and l.code = '".$this->aSearch['code']."'";
                if ($this->aSearch['name'])	$this->sSearchSQL .= " and l.name = '".$this->aSearch['name']."'";
                if ($this->aSearch['num'])	$this->sSearchSQL .= " and l.num = '".$this->aSearch['num']."'";
            }
            else {
                if ($this->aSearch['id'])$this->sSearchSQL .= " and l.id = '".$this->aSearch['id']."'";
                if ($this->aSearch['code'])$this->sSearchSQL .= " and l.code = '".$this->aSearch['code']."'";
                if ($this->aSearch['name'])	$this->sSearchSQL .= " and l.name = '".$this->aSearch['name']."'";
                if ($this->aSearch['num'])	$this->sSearchSQL .= " and l.num = '".$this->aSearch['num']."'";
            }
            if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and l.visible = '1'";
            if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and l.visible = '0'";
            //with else "ignore" will not be found
            switch($this->aSearch['visible']){
                case '1':
                    $this->sSearchSQL.=" and l.visible>='1'";
                    break;
                case '0':
                    $this->sSearchSQL.=" and l.visible>='0'";
                    break;
                case  '':
                    break;
            }
        }

        require_once(SERVER_PATH.'/class/core/Table.php');
        $oTable=new Table();
        $oTable->aColumn=array();
        $oTable->aColumn['id']=array('sTitle'=>'Id','sOrder'=>'l.id');
        $oTable->aColumn['name']=array('sTitle'=>'Name/Domain','sOrder'=>'l.name');
        $oTable->aColumn['code']=array('sTitle'=>'Code','sOrder'=>'l.code');
        $oTable->aColumn['visible']=array('sTitle'=>'Visible','sOrder'=>'l.visible');
        $oTable->aColumn['image']=array('sTitle'=>'Image');
        $oTable->aColumn['num']=array('sTitle'=>'Num','sOrder'=>'l.num');
        $this->initLocaleGlobal();
        $oTable->aColumn['language']=array('sTitle' => 'Lang');
        $oTable->aColumn['action']=array();
        $this->SetDefaultTable($oTable);
        Base::$sText.=$oTable->getTable();

        $this->AfterIndex();
    }
    //-----------------------------------------------------------------------------------------------
    public function MpanelChange()
    {
        Db::Execute ("insert into admin_option (id_admin,module,code,content) values
			('".$_SESSION['admin']['id']."','language','id_mpanel_language','".Base::$aRequest['content']."')
			on duplicate key update content='".Base::$aRequest['content']."'");

        Base::$oResponse->AddRedirect('/mpanel/login.php');
    }
    //-----------------------------------------------------------------------------------------------
}
?>