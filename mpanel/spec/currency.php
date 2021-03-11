<?

/**
 * @author Mikhail Starovoyt
 *
 */
class ACurrency extends Admin {

    //-----------------------------------------------------------------------------------------------
    function __construct()
    {
        $this->sTableName='currency';
        $this->sTablePrefix='c';
        $this->sAction='currency';
        $this->sWinHead=Language::getDMessage('Currencies');
        $this->sPath=Language::GetDMessage('>>Configuration >');
        $this->aCheckField=array('code','name','value');
        if(file_exists(SERVER_PATH."/template/mpanel/dtree_new.tpl")) {
            //use /template/mpanel/***
        } else {
            $this->sAddonPath='addon/';
        }
        $this->sSqlPath='CoreCurrency';
        $this->Admin();
    }
    //-----------------------------------------------------------------------------------------------
    public function Index()
    {
        $this->PreIndex();

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong',0)) {
                if ($this->aSearch['id'])$this->sSearchSQL .= " and c.id = '".$this->aSearch['id']."'";
                if ($this->aSearch['code'])$this->sSearchSQL .= " and c.code = '".$this->aSearch['code']."'";
                if ($this->aSearch['name'])	$this->sSearchSQL .= " and c.name = '".$this->aSearch['name']."'";
                if ($this->aSearch['symbol'])	$this->sSearchSQL .= " and c.symbol = '".$this->aSearch['symbol']."'";
                if ($this->aSearch['value'])	$this->sSearchSQL .= " and c.value = '".$this->aSearch['value']."'";
                if ($this->aSearch['num'])	$this->sSearchSQL .= " and c.num = '".$this->aSearch['num']."'";
            }
            else {
                if ($this->aSearch['id'])$this->sSearchSQL .= " and c.id = '".$this->aSearch['id']."'";
                if ($this->aSearch['code'])$this->sSearchSQL .= " and c.code = '".$this->aSearch['code']."'";
                if ($this->aSearch['name'])	$this->sSearchSQL .= " and c.name = '".$this->aSearch['name']."'";
                if ($this->aSearch['symbol'])	$this->sSearchSQL .= " and c.symbol = '".$this->aSearch['symbol']."'";
                if ($this->aSearch['value'])	$this->sSearchSQL .= " and c.value = '".$this->aSearch['value']."'";
                if ($this->aSearch['num'])	$this->sSearchSQL .= " and c.num = '".$this->aSearch['num']."'";
            }
            if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and c.visible = '1'";
            if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and c.visible = '0'";
            //with else "ignore" will not be found
            switch($this->aSearch['visible']){
                case '1':
                    $this->sSearchSQL.=" and c.visible>='1'";
                    break;
                case '0':
                    $this->sSearchSQL.=" and c.visible>='0'";
                    break;
                case  '':
                    break;
            }
        }

        $this->initLocaleGlobal();

        $oTable=new Table();
        $oTable->aColumn=array(
            'id'=>array('sTitle'=>'Id','sOrder'=>'c.id'),
            'code'=>array('sTitle'=>'Code','sOrder'=>'c.code'),
            'name'=>array('sTitle'=>'CurrencyName','sOrder'=>'c.name'),
            'symbol'=>array('sTitle'=>'Symbol','sOrder'=>'c.symbol'),
            'image'=>array('sTitle'=>'Image','sOrder'=>'c.image'),
            'value'=>array('sTitle'=>'Value','sOrder'=>'c.value'),
            'visible'=>array('sTitle'=>'Visible','sOrder'=>'c.visible'),
            'num'=>array('sTitle'=>'Num','sOrder'=>'c.num'),
            'language'=>array('sTitle' => 'Lang'),
            'action'=>array(),
        );
        $this->SetDefaultTable($oTable);
        Base::$sText.=$oTable->getTable();

        $this->AfterIndex();
    }
    //-----------------------------------------------------------------------------------------------
    public function AfterApply($aBeforeRow,$aAfterRow)
    {
        //$aData=Base::$aRequest ['data'];
        if ($aAfterRow['value'] && $aBeforeRow['value']!=$aAfterRow['value']) {
            $aData['section']='change_currency';
            $aData['created_by']=$_SESSION['admin']['login'];
            $aData['description']=Language::GetDMessage('Currency change')."
			{$aAfterRow['code']}, <b>{$aBeforeRow['value']}</b>=><b>{$aAfterRow['value']}</b>";
            Base::$db->AutoExecute('log_finance',$aData,'INSERT');
        }
    }
    //-----------------------------------------------------------------------------------------------
}
?>