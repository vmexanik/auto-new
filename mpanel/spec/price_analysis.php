<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class APriceAnalysis extends Admin {

	//-----------------------------------------------------------------------------------------------
	function APriceAnalysis() {
		$this->sTableName='price_analysis';
		$this->sTablePrefix='pa';
		$this->sAction='price_analysis';
		$this->sWinHead=Language::getDMessage('Price analysis');
		$this->sPath = Language::GetDMessage('>>Logs >');
		//$this->aCheckField=array('name');

		$this->sSql='';
		$this->sBeforeAddMethod='BeforeAdd';
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		//$_SESSION['admin']['id'];

		$this->PreIndex();

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn=array(
		'item_code'=>array('sTitle'=>'Item code','sOrder'=>'item_code','sWidth'=>'20%'),
		'provider1'=>array('sTitle'=>'Provider 1', 'sWidth'=>'10%'),
		'provider2'=>array('sTitle'=>'Provider 2', 'sWidth'=>'10%'),
		'percent2'=>array('sTitle'=>'Percent 2', 'sWidth'=>'12%'),
		'provider3'=>array('sTitle'=>'Provider 3', 'sWidth'=>'10%'),
		'percent3'=>array('sTitle'=>'Percent 3', 'sWidth'=>'12%'),
		'provider4'=>array('sTitle'=>'Provider 4', 'sWidth'=>'10%'),
		'percent4'=>array('sTitle'=>'Percent 4', 'sWidth'=>'12%'),
		);

		$this->SetDefaultTable($oTable);
		$oTable->iRowPerPage=30;
		$oTable->bCacheStepper=true;
		//$this->insert();

		if ($this->sSql=="") {
			$oTable->sSql=Base::GetSql('PriceAnalysis',array('where'=>$sWhere ));
		} else {
			$oTable->sSql=$this->sSql;
		}

		$a[]="";
		//Base::$tpl->assign('aReq',Base::$aRequest);
		Base::$tpl->assign('pref',Base::$db->getAssoc("select pref, name from cat where  visible=1 order by name"));
		Base::$tpl->assign('provider',$a+Base::$db->getAssoc(
		"    select up.id_user, concat(pr.code_delivery,' ',up.name) name "
		."\n from user_provider as up "
		."\n inner join provider_region as pr on up.id_provider_region=pr.id "
		."\n order by name")
		);

		Base::$sText.=Base::$tpl->fetch('mpanel/'.$this->sAction.'/form_top.tpl');
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAdd() {

	}

	public function Analysis() {

		$iCount=0;
		$aIN=array();
		
		$this->sSql=" select count(*) as cnt, item_code "	;

		for ($i=1;$i<=4;$i++) {
			if (Base::$aRequest['provider'.$i]) {
				$this->sSql.=", sum(if(id_provider=".Base::$aRequest['provider'.$i].",price,0)) as provider".$i.", ".Base::$aRequest['provider'.$i]." as id_provider".$i;
				$iCount++;
				$aIN[]=Base::$aRequest['provider'.$i];
			}
		}
		if (is_numeric(Base::$aRequest['price_from'])) $sWhere.=" and price>=".Base::$aRequest['price_from'];
		if (is_numeric(Base::$aRequest['price_to'])) $sWhere.=" and price<=".Base::$aRequest['price_to'];
			
		$this->sSql.=
		"\n from price "
		."\n where item_code like '".Base::$aRequest['pref']."%' and id_provider in (".implode(",",$aIN).") "
		.$sWhere
		."\n group by `item_code` "
		."\n having cnt=".$iCount
		;
		$this->Index();
	}


}
?>