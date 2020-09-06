<?

/**
 * 
 *
 */
require_once (SERVER_PATH . '/class/core/Admin.php');
class APrice extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sTableName='price';
		$this->sTablePrefix='price';
		$this->sAction='price';
		$this->sWinHead=Language::getDMessage('Price');
		$this->sPath = Language::GetDMessage('>>Price >');
		$this->sSqlPath="Price";
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex();
		
		//--------------------
		Base::$sText .= $this->SearchForm ();
		if ($this->aSearch) {
			if (Language::getConstant('mpanel_search_strong',0)) {
				if ($this->aSearch['id'])$this->sSearchSQL .= " and price.id = '".$this->aSearch['id']."'";
				if ($this->aSearch['id_price_group'])$this->sSearchSQL .= " and pgs.id_price_group = '".$this->aSearch['id_price_group']."'";
				if ($this->aSearch['id_provider'])	$this->sSearchSQL .= " and price.id_provider = '".$this->aSearch['id_provider']."'";
				if ($this->aSearch['code'])$this->sSearchSQL .= " and price.code = '".$this->aSearch['code']."'";
				if ($this->aSearch['price'])$this->sSearchSQL .= " and price.price = '".str_replace (',','.',$this->aSearch['price'])."'";
				if ($this->aSearch['part_rus'])	$this->sSearchSQL .= " and price.part_rus = '".$this->aSearch['part_rus']."'";
				if ($this->aSearch['part_ua'])	$this->sSearchSQL .= " and price.part_ua = '".$this->aSearch['part_ua']."'";
				if ($this->aSearch['pref'])	$this->sSearchSQL .= " and price.pref = '".$this->aSearch['pref']."'";
				if ($this->aSearch['cat'])	$this->sSearchSQL .= " and price.cat = '".$this->aSearch['cat']."'";
				if ($this->aSearch['post_date'])$this->sSearchSQL .= " and price.post_date>='".DateFormat::FormatSearch($this->aSearch['post_date'])."'";
				if ($this->aSearch['term'])	$this->sSearchSQL .= " and price.term = '".$this->aSearch['term']."'";
				if ($this->aSearch['stock'])	$this->sSearchSQL .= " and price.stock = '".$this->aSearch['stock']."'";
				if ($this->aSearch['number_min'])	$this->sSearchSQL .= " and price.number_min = '".$this->aSearch['number_min']."'";
			}
			else {
			    if ($this->aSearch['id'])$this->sSearchSQL .= " and price.id like '%".$this->aSearch['id']."%'";
			    if ($this->aSearch['id_price_group'])$this->sSearchSQL .= " and pgs.id_price_group like '%".$this->aSearch['id_price_group']."%'";
			    if ($this->aSearch['id_provider'])	$this->sSearchSQL .= " and price.id_provider like '%".$this->aSearch['id_provider']."%'";
			    if ($this->aSearch['code'])$this->sSearchSQL .= " and price.code like '%".$this->aSearch['code']."%'";
			    if ($this->aSearch['price'])$this->sSearchSQL .= " and price.price like '%".str_replace (',','.',$this->aSearch['price'])."%'";
			    if ($this->aSearch['part_rus'])	$this->sSearchSQL .= " and price.part_rus like '%".$this->aSearch['part_rus']."%'";
			    if ($this->aSearch['part_ua'])	$this->sSearchSQL .= " and price.part_ua like '%".$this->aSearch['part_ua']."%'";
			    if ($this->aSearch['pref'])	$this->sSearchSQL .= " and price.pref like '%".$this->aSearch['pref']."%'";
			    if ($this->aSearch['cat'])	$this->sSearchSQL .= " and price.cat like '%".$this->aSearch['cat']."%'";
			    if ($this->aSearch['post_date'])$this->sSearchSQL .= " and price.post_date>='".DateFormat::FormatSearch($this->aSearch['post_date'])."'";
			    if ($this->aSearch['term'])	$this->sSearchSQL .= " and price.term like '%".$this->aSearch['term']."%'";
			    if ($this->aSearch['stock'])	$this->sSearchSQL .= " and price.stock like '%".$this->aSearch['stock']."%'";
			    if ($this->aSearch['number_min'])	$this->sSearchSQL .= " and price.number_min like '%".$this->aSearch['number_min']."%'";
			}		    
		}
		//--------------------
		

		//require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id','sOrder'=>'price.id'),
		'id_price_group'=>array('sTitle'=>'id_price_group','sOrder'=>'pgs.id_price_group'),
		'id_provider'=>array('sTitle'=>'id_provider','sOrder'=>'price.id_provider'),
		'code'=>array('sTitle'=>'code','sOrder'=>'price.code'),
		'price'=>array('sTitle'=>'price','sOrder'=>'price.price'),
		'part_rus'=>array('sTitle'=>'part_rus','sOrder'=>'price.part_rus'),
		'part_ua'=>array('sTitle'=>'part_ua','sOrder'=>'price.part_ua'),
		'pref'=>array('sTitle'=>'pref','sOrder'=>'price.pref'),
		'cat'=>array('sTitle'=>'cat','sOrder'=>'price.cat'),
		'post_date'=>array('sTitle'=>'post_date','sOrder'=>'price.post_date'),
		'term'=>array('sTitle'=>'term','sOrder'=>'price.term'),
		'stock'=>array('sTitle'=>'stock','sOrder'=>'price.stock'),
		'number_min'=>array('sTitle'=>'number_min','sOrder'=>'price.number_min'),
		'action'=>array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function Preview() {
	    Base::$oResponse->addAssign ( 'sub_menu', 'innerHTML', '' );
	    $iId = ( int ) Base::$aRequest ['id'];
	    Base::$tpl->assign ( 'aData', Base::$db->GetRow ( "SELECT * FROM `price_import` WHERE `id`='$iId'" ) );
	    Base::$tpl->assign ( 'sReturn', stripslashes ( Base::$aRequest ['return'] ) );
	    Base::$sText .= Base::$tpl->fetch ( 'mpanel/price/preview.tpl' );
	    $this->AfterIndex ();
	}

}
?>