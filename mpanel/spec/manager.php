<?
/**
 * @author Mikhail Starovoyt
 *
 */

require_once(SERVER_PATH.'/mpanel/spec/user.php');
class AManager extends AUser
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sSqlPath = 'Manager';
		$this->sTableName = 'user';
		$this->sAdditionalLink='_manager';
		$this->sTablePrefix = 'ug';
		$this->sAction = 'manager';
		$this->sWinHead = Language::getDMessage ( 'Manager' );
		$this->sPath = Language::GetDMessage('>>Users >');
		$this->aCheckField = array ('login');
		$this->aChildTable = array(
		array('sTableName'=>'user_manager', 'sTablePrefix'=>'um', 'sTableId'=>'id_user'),
		);
		$this->Admin ();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex();

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong', 0)) {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and u.id = '" . $this->aSearch['id'] . "'";
                if ($this->aSearch['name'])
                    $this->sSearchSQL .= " and um.name = '" . $this->aSearch['name'] . "'";
                if ($this->aSearch['login'])
                    $this->sSearchSQL .= " and u.login = '" . $this->aSearch['login'] . "'";
                if ($this->aSearch['email'])
                    $this->sSearchSQL .= " and u.email = '" . $this->aSearch['email'] . "'";
            } else {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and u.id like '%" . $this->aSearch['id'] . "%'";
                if ($this->aSearch['login'])
                    $this->sSearchSQL .= " and u.login like '%" . $this->aSearch['login'] . "%'";
                if ($this->aSearch['name'])
                    $this->sSearchSQL .= " and um.name like '%" . $this->aSearch['name'] . "%'";
                if ($this->aSearch['email'])
                    $this->sSearchSQL .= " and u.email like '%" . $this->aSearch['email'] . "%'";
            }
            if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and u.visible = '1'";
            if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and u.visible = '0'";
            //with else "ignore" will not be found
            switch($this->aSearch['visible']){
                case '1':
                    $this->sSearchSQL.=" and u.visible>='1'";
                    break;
                case '0':
                    $this->sSearchSQL.=" and u.visible>='0'";
                    break;
                case  '':
                    break;
            }
            if ($this->aSearch['has_customer']=='1')	$this->sSearchSQL .= " and um.has_customer = '1'";
            if ($this->aSearch['has_customer']=='0')	$this->sSearchSQL .= " and um.has_customer = '0'";
            //with else "ignore" will not be found
            switch($this->aSearch['has_customer']){
                case '1':
                    $this->sSearchSQL.=" and um.has_customer>='1'";
                    break;
                case '0':
                    $this->sSearchSQL.=" and um.has_customer>='0'";
                    break;
                case  '':
                    break;
            }
        }

		$oTable=new Table();
		$oTable->aColumn=array(
		'id' => array('sTitle'=>'Id', 'sOrder'=>'u.id'),
		'login' => array('sTitle'=>'Login', 'sOrder'=>'u.login'),
		'name' => array('sTitle'=>'Name', 'sOrder'=>'um.name'),
		'email' => array('sTitle'=>'Email', 'sOrder'=>'u.email'),
		'visible' => array('sTitle'=>'Visible', 'sOrder'=>'u.visible'),
		'has_customer' => array('sTitle'=>'Has customers', 'sOrder'=>'um.has_customer'),
		'action' => array(),
		);
		$oTable->aCallback=array($this,'CallParseRoles');
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseRoles(&$aItem)
	{
		foreach ($aItem as $sKey => $aValue) {
			$aAssignedRoles = Db::GetAll('select rn.name from role_name rn
			left join role_manager rm on rm.id_role = rn.id
			where rm.id_manager ='.$aValue['id']);
			$aItem[$sKey]['assigned_roles'] = $aAssignedRoles;
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign(&$aData)
	{
		$aRoles =  Db::GetAll("SELECT rm.id_manager, rn . *
			FROM `role_name` AS rn
			LEFT JOIN `role_manager` AS rm ON rm.id_role = rn.id
			AND id_manager = '".$aData['id']."'");
		Base::$tpl->assign ( 'aRoles', $aRoles );
	}
	//-----------------------------------------------------------------------------------------------
	public function AfterApply($aBeforeRow,$aAfterRow) {
		//Db::Execute("delete from user_manager_region where id_user='".$aAfterRow['id']."'");
		Db::Execute("DELETE FROM `role_manager` WHERE id_manager = ".Base::$aRequest['data']['id']);
		if(Base::$aRequest['data']['id_role']){
			foreach(Base::$aRequest['data']['id_role'] as $iIdRole){				
				Db::Execute("INSERT INTO `role_manager`( `id_manager`, `id_role`) 
				VALUES (".Base::$aRequest['data']['id'].",".$iIdRole.") on duplicate key update id=id");	
			}			
		}
		if (Base::$aRequest['data']['user_manager_region']) {
			$aUserManagerRegion=array('id_user'=>$aAfterRow['id']);
			foreach (Base::$aRequest['data']['user_manager_region'] as $sKey => $sValue) {
				if ($sValue) {
					$aUserManagerRegion['id_provider_region']=$sKey;
					Db::AutoExecute('user_manager_region',$aUserManagerRegion);
				}
			}
		}
	}
	//-----------------------------------------------------------------------------------------------


}

?>