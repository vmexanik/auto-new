<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class AProviderStatistic extends Admin {

	//-----------------------------------------------------------------------------------------------
	function AProviderStatistic() {
		$this->sTableName='provider_statistic';
		$this->sTablePrefix='ps';
		$this->sAction='provider_statistic';
		$this->sWinHead=Language::getDMessage('ProviderStatistics');
		$this->sPath = Language::GetDMessage('>>Logs >');
		//$this->aCheckField=array('name','code');
		$this->Admin();
		$this->sSqlPath='Provider/Statistic';
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn=array(
		'id_user'=>array('sTitle'=>'Id','sOrder'=>'ps.id_user'),
		'up.name'=>array('sTitle'=>'Name','sOrder'=>'up.name'),
		'ps.make'=>array('sTitle'=>'Make','sOrder'=>'ps.make'),
		'code_name'=>array('sTitle'=>'provider_code_name','sOrder'=>'up.code_name'),
		'delivery_term'=>array('sTitle'=>'delivery_term','sOrder'=>'ps.delivery_term'),
		'refuse_percent'=>array('sTitle'=>'refuse_percent','sOrder'=>'ps.refuse_percent'),
		'confirm_term'=>array('sTitle'=>'confirm_term','sOrder'=>'ps.confirm_term'),
		'volume_percent'=>array('sTitle'=>'volume_percent','sOrder'=>'ps.volume_percent'),
		'statistic_visible'=>array('sTitle'=>'statistic/manual','sOrder'=>'up.statistic_visible'),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
}
?>