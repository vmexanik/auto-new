<?
/**
 * @author Yuriy Korzun
 * @version 1.0.1
 */
class ARedirect extends Admin {
	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName = 'redirect';
		$this->sTablePrefix = 'r';
		$this->sAction = 'redirect';
		$this->sWinHead = Language::getDMessage('Redirect');
		$this->sPath = Language::GetDMessage('>>Catalog >');
		$this->aCheckField = array ('link_from','link_to');
		$this->Admin ();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong', 0)) {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and r.id = '" . $this->aSearch['id'] . "'";
                if ($this->aSearch['link_from'])
                    $this->sSearchSQL .= " and r.link_from = '" . $this->aSearch['link_from'] . "'";
                if ($this->aSearch['link_to'])
                    $this->sSearchSQL .= " and r.link_to = '" . $this->aSearch['link_to'] . "'";
            } else {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and r.id like '%" . $this->aSearch['id'] . "%'";
                if ($this->aSearch['link_from'])
                    $this->sSearchSQL .= " and r.link_from like '%" . $this->aSearch['link_from'] . "%'";
                if ($this->aSearch['link_to'])
                    $this->sSearchSQL .= " and r.link_to like '%" . $this->aSearch['link_to'] . "%'";
            }
        }

		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=> array('sTitle'=>'Id', 'sOrder'=>'r.id'),
		'link_from' => array('sTitle'=>'LinkFrom', 'sOrder'=>'r.link_from'),
		'link_to' => array('sTitle'=>'LinkTo', 'sOrder'=>'r.link_to'),
		'action' => array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	
}
?>