<?

/**
 * @author Mikhail Starovoyt
 *
 */

class AAccount extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sTableName='account';
		$this->sTablePrefix='a';
		$this->sAction='account';
		$this->sWinHead=Language::getDMessage('Account');
		$this->sPath = Language::GetDMessage('>>Users >');
		$this->aCheckField=array('id_buh','name','account_id','holder_name','bank_name','holder_code','bank_mfo');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex();

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong', 0)) {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and a.id = '" . $this->aSearch['id'] . "'";
                if ($this->aSearch['id_buh'])
                    $this->sSearchSQL .= " and a.id_buh = '" . $this->aSearch['id_buh'] . "'";
                if ($this->aSearch['name'])
                    $this->sSearchSQL .= " and a.name = '" . $this->aSearch['name'] . "'";
                if ($this->aSearch['account_id'])
                    $this->sSearchSQL .= " and a.account_id = '" . $this->aSearch['account_id'] . "'";
                if ($this->aSearch['holder_name'])
                    $this->sSearchSQL .= " and a.holder_name = '" . $this->aSearch['holder_name'] . "'";
                if ($this->aSearch['bank_name'])
                    $this->sSearchSQL .= " and a.bank_name = '" . $this->aSearch['bank_name'] . "'";
                if ($this->aSearch['bank_code'])
                    $this->sSearchSQL .= " and a.bank_code = '" . $this->aSearch['bank_code'] . "'";
                if ($this->aSearch['correspondent_account'])
                    $this->sSearchSQL .= " and a.correspondent_account = '" . $this->aSearch['correspondent_account'] . "'";
                if ($this->aSearch['holder_code'])
                    $this->sSearchSQL .= " and a.holder_code = '" . $this->aSearch['holder_code'] . "'";
                if ($this->aSearch['bank_mfo'])
                    $this->sSearchSQL .= " and a.bank_mfo = '" . $this->aSearch['bank_mfo'] . "'";
                if ($this->aSearch['amount'])
                    $this->sSearchSQL .= " and a.amount = '" . $this->aSearch['amount'] . "'";
            } else {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and a.id like '%" . $this->aSearch['id'] . "%'";
                if ($this->aSearch['id_buh'])
                    $this->sSearchSQL .= " and a.id_buh like '%" . $this->aSearch['id_buh'] . "%'";
                if ($this->aSearch['name'])
                    $this->sSearchSQL .= " and a.name like '%" . $this->aSearch['name'] . "%'";
                if ($this->aSearch['account_id'])
                    $this->sSearchSQL .= " and a.account_id like '%" . $this->aSearch['account_id'] . "%'";
                if ($this->aSearch['holder_name'])
                    $this->sSearchSQL .= " and a.holder_name like '%" . $this->aSearch['holder_name'] . "%'";
                if ($this->aSearch['bank_name'])
                    $this->sSearchSQL .= " and a.bank_name like '%" . $this->aSearch['bank_name'] . "%'";
                if ($this->aSearch['bank_code'])
                    $this->sSearchSQL .= " and a.bank_code like '%" . $this->aSearch['bank_code'] . "%'";
                if ($this->aSearch['correspondent_account'])
                    $this->sSearchSQL .= " and a.correspondent_account like '%" . $this->aSearch['correspondent_account'] . "%'";
                if ($this->aSearch['holder_code'])
                    $this->sSearchSQL .= " and a.holder_code like '%" . $this->aSearch['holder_code'] . "%'";
                if ($this->aSearch['bank_mfo'])
                    $this->sSearchSQL .= " and a.bank_mfo like '%" . $this->aSearch['bank_mfo'] . "%'";
                if ($this->aSearch['amount'])
                    $this->sSearchSQL .= " and a.amount like '%" . $this->aSearch['amount'] . "%'";
            }
            if ($this->aSearch['date_from'])
                $this->sSearchSQL .= " and UNIX_TIMESTAMP(a.post_date)>='".strtotime($this->aSearch['date_from'])."' ";
            if ($this->aSearch['date_to'])
                $this->sSearchSQL .= " and UNIX_TIMESTAMP(a.post_date)<='".strtotime($this->aSearch['date_to'])."'";
            if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and a.visible = '1'";
            if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and a.visible = '0'";
            //with else "ignore" will not be found
            switch($this->aSearch['visible']){
                case '1':
                    $this->sSearchSQL.=" and a.visible>='1'";
                    break;
                case '0':
                    $this->sSearchSQL.=" and a.visible>='0'";
                    break;
                case  '':
                    break;
            }
            if ($this->aSearch['is_active']=='1')	$this->sSearchSQL .= " and a.is_active = '1'";
            if ($this->aSearch['is_active']=='0')	$this->sSearchSQL .= " and a.is_active = '0'";
            //with else "ignore" will not be found
            switch($this->aSearch['is_active']){
                case '1':
                    $this->sSearchSQL.=" and a.is_active>='1'";
                    break;
                case '0':
                    $this->sSearchSQL.=" and a.is_active>='0'";
                    break;
                case  '':
                    break;
            }
        }

		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id','sOrder'=>'a.id'),
		'id_buh'=>array('sTitle'=>'IdBuh','sOrder'=>'a.id_buh'),
		'name'=>array('sTitle'=>'Ofice / name','sOrder'=>'a.name'),
		'account_id'=>array('sTitle'=>'account_id','sOrder'=>'a.account_id'),
		'holder_name'=>array('sTitle'=>'holder_name','sOrder'=>'a.holder_name'),
		'bank_name'=>array('sTitle'=>'bank_name','sOrder'=>'a.bank_name'),
		'bank_code'=>array('sTitle'=>'bank_code','sOrder'=>'a.bank_code'),
		'correspondent_account'=>array('sTitle'=>'correspondent_account','sOrder'=>'a.correspondent_account'),
		'holder_code'=>array('sTitle'=>'holder_code','sOrder'=>'a.holder_code'),
		'bank_mfo'=>array('sTitle'=>'bank_mfo','sOrder'=>'a.bank_mfo'),
		'is_active'=>array('sTitle'=>'Is Active','sOrder'=>'a.is_active'),
		'visible'=>array('sTitle'=>'Visible','sOrder'=>'a.visible'),
		'post_date'=>array('sTitle'=>'Date','sOrder'=>'a.post_date'),
		'action'=>array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText .= $oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function Activate()
	{
		Db::Execute("update account set is_active='0'");
		Db::Execute("update account set is_active='1' where id='".Base::$aRequest['id']."'");
		$this->Index();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign(&$aData)
	{
		Base::$tpl->assign('aOffice',Db::GetAssoc('Assoc/Office'));
		Base::$tpl->assign('aCurrencyAssoc', Db::GetAssoc("Assoc/Currency",array(
		'key_field'=>'id',
		)));
	}
	//-----------------------------------------------------------------------------------------------


}
?>