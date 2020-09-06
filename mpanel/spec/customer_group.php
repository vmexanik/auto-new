<?

/**
 * @author Mikhail Starovoyt
 *
 */
class ACustomerGroup extends Admin {

	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName='customer_group';
		$this->sTablePrefix='cg';
		$this->sSqlPath = 'CustomerGroup/CustomerGroup';
		$this->sAction='customer_group';
		$this->sWinHead=Language::getDMessage('Customer group');
		$this->sPath = Language::GetDMessage('>>Users >');
		$this->aCheckField=array('group_discount');
		//$this->aFCKEditors = array ('description' );
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		$this->initLocaleGlobal();
		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id','sOrder'=>'cg.id'),
		'name'=>array('sTitle'=>'Name','sOrder'=>'cg.name'),
		'visible'=>array('sTitle'=>'Visible','sOrder'=>'cg.visible'),
		'group_discount'=>array('sTitle'=>'Group discount','sOrder'=>'cg.group_discount'),
		'hours_expired_cart'=>array('sTitle'=>'Hours expired cart','sOrder'=>'cg.hours_expired_cart'),
		//'price_type'=>array('sTitle'=>'Price Type','sOrder'=>'cg.price_type'),
		//'customer_group_margin'=>array('sTitle'=>'Group Margin','sOrder'=>'cg.customer_group_margin'),
		//'language'=>array('sTitle' => 'Lang'),
		'action'=>array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}

	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign(&$aData) {

		Base::$tpl->assign('aPriceType', BaseTemp::EnumToArray('customer_group','price_type'));

		//		if (Base::$aData['id'] || Base::$aRequest['id']){
		//			//$aData=Base::GetSql('CustomerGroup/WithUserProvider',array('id'=>Base::$aRequest['id']));
		//			$aData = Base::$db->GetRow("select cg. * , dp. * , up. *,
		//		    cg.name as cg_name
		//			from customer_group AS cg
		//			  inner join discount_provider AS dp ON cg.code = dp.code_customer_group
		//			  inner join user_provider AS up ON dp.id_user_provider = up.id_user
		//			where cg.id=".Base::$aRequest['id']." order by up.name");
		//		}else{
		//			$aData = Base::$db->GetAll("select up. * from user_provider as up group by up.name");
		//		}

	}
	//-----------------------------------------------------------------------------------------------
	public function AfterApply($aBeforeRow,$aAfterRow) {
		//		if (Base::$aRequest['data']['id']){
		//			if (Base::$aRequest['provider_discount']){
		//				$provider_discount = Base::$aRequest['provider_discount'];
		//				foreach ($provider_discount as $key => $value){
		//					$sQuery = "insert into discount_provider (code_customer_group,id_user_provider,value) values
		//							('".Base::$aRequest['data']['code']."','".$key."','".$value."')
		//						on duplicate key update value='".$value."';";
		//					$res = Base::$db->Execute ( $sQuery );
		//				}
		//			}
		//			if ($res){
		//				$this->Message('MT_NOTICE', $this->sWinHead.' '.Language::getDMessage('was added'));
		//			}else{
		//				$this->Message('MT_ERROR', $this->sWinHead.' '.Language::getDMessage('was not added'));
		//			}
		//		}
	}
	//-----------------------------------------------------------------------------------------------
}
?>