<?

/**
 * @author Mikhail Starovoyt
 */

class ANews extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sTableName = 'news';
		$this->sTablePrefix = 'n';
		$this->sAction = 'news';
		$this->sWinHead = Language::getDMessage('News');
		$this->sPath = Language::GetDMessage('>>Content >');
		$this->aCheckField = array('short');
		//$this->aFCKEditors = array('full');
		//$this->sAddonPath='addon/';
		$this->sSqlPath='CoreNews';
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex ();

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong', 0)) {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and n.id = '" . $this->aSearch['id'] . "'";
                if ($this->aSearch['name'])
                    $this->sSearchSQL .= " and n.name = '" . $this->aSearch['name'] . "'";
                if ($this->aSearch['short'])
                    $this->sSearchSQL .= " and n.short = '" . $this->aSearch['short'] . "'";
                if ($this->aSearch['num'])
                    $this->sSearchSQL .= " and n.num = '" . $this->aSearch['num'] . "'";
            } else {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and n.id like '%" . $this->aSearch['id'] . "%'";
                if ($this->aSearch['name'])
                    $this->sSearchSQL .= " and n.name like '%" . $this->aSearch['name'] . "%'";
                if ($this->aSearch['short'])
                    $this->sSearchSQL .= " and n.short like '%" . $this->aSearch['short'] . "%'";
                if ($this->aSearch['num'])
                    $this->sSearchSQL .= " and n.num like '%" . $this->aSearch['num'] . "%'";
            }
            if ($this->aSearch['date_from'])
                $this->sSearchSQL .= " and UNIX_TIMESTAMP(n.post_date)>='".strtotime($this->aSearch['date_from'].' 00:00:00')."' ";
            if ($this->aSearch['date_to'])
                $this->sSearchSQL .= " and UNIX_TIMESTAMP(n.post_date)<='".strtotime($this->aSearch['date_to'].' 23:59:59')."'";
            if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and n.visible = '1'";
            if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and n.visible = '0'";
            //with else "ignore" will not be found
            switch($this->aSearch['visible']){
                case '1':
                    $this->sSearchSQL.=" and n.visible>='1'";
                    break;
                case '0':
                    $this->sSearchSQL.=" and n.visible>='0'";
                    break;
                case  '':
                    break;
            }
        }

		$oTable = new Table ( );
		$oTable->aColumn = array ();
		$oTable->aColumn ['id'] = array ('sTitle' => 'Id', 'sOrder' => 'n.id' );
		$oTable->aColumn ['name'] = array ('sTitle' => 'name', 'sOrder' => 'n.name' );
		$oTable->aColumn ['short'] = array ('sTitle' => 'Short', 'sOrder' => 'n.short' );
		$oTable->aColumn ['full'] = array ('sTitle' => 'Full', 'sOrder' => 'n.full' );
		$oTable->aColumn ['date'] = array ('sTitle' => 'Date', 'sOrder' => 'n.post_date' );
		$oTable->aColumn ['image'] = array('sTitle'=>'Image', 'sOrder'=>'n.image');
		$oTable->aColumn ['visible'] = array ('sTitle' => 'Visible', 'sOrder' => 'n.visible' );
		$oTable->aColumn ['num'] = array ('sTitle' => 'Num', 'sOrder' => 'n.num' );
		$this->initLocaleGlobal ();
		$oTable->aColumn ['language'] = array ('sTitle' => 'Lang' );
		$oTable->aColumn ['action'] = array ();
		$this->SetDefaultTable($oTable );
		Base::$sText .= $oTable->getTable ();

		$this->AfterIndex ();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeApply()
	{
		if (date('Y-m-d', strtotime(Base::$aRequest['data']['post_date'])) != '1970-01-01')
		    Base::$aRequest['data']['post_date'] = date('Y-m-d', strtotime(Base::$aRequest['data']['post_date']));
		else
		    Base::$aRequest['data']['post_date'] = '';
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign(&$aData)
	{
		if (!$aData['post_date']) $iTime=time();
		else $iTime=strtotime($aData['post_date']);

		$aData['post_date'] = date(Base::GetConstant('date_format:post_date'),$iTime);
	}
	//-----------------------------------------------------------------------------------------------
}
?>