<?
/**
 * @author Mikhail Starovoyt
 * @version 4.5.3
 */

class APriceGroupPref extends Admin {
	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
		$this->sTableName='price_group_pref';
		$this->sTablePrefix='pgp';
		$this->sAction='price_group_pref';
		$this->sWinHead=Language::getDMessage('Price Group Pref');
		$this->sPath=Language::GetDMessage('>>Auto catalog >');
		$this->aCheckField=array('id_price_group','pref');
		$this->sSqlPath="Price/GroupPref";
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		$oTable=new Table();
		$sTablePref = 'pgp.';
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id','sOrder'=>$sTablePref.'id'),
		'id_price_group'=>array('sTitle'=>'Id price group','sOrder'=>$sTablePref.'id_price_group'),
		'pref'=>array('sTitle'=>'Pref','sOrder'=>$sTablePref.'pref'),
		'pg_name'=>array('sTitle'=>'price_group_name','sOrder'=>'pg.name'),
		'visible'=>array('sTitle'=>'Visible','sOrder'=>$sTablePref.'visible'),
		'action'=>array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function RemakePref()
	{
		//Clear Price_Group_Pref table
		Db::Execute("TRUNCATE TABLE price_group_pref");
		
		//Make new prefics
		$aPref=Db::GetAll("select distinct pref, id_price_group from price");
		foreach ($aPref as $aValue){
			Db::Execute("insert into price_group_pref (id_price_group, pref) values ('".$aValue['id_price_group']."','".$aValue['pref']."')");
		}

		//return
		$this->AdminRedirect ( $this->sAction );
	}
	//-----------------------------------------------------------------------------------------------
}
?>