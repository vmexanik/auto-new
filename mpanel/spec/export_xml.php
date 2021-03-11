<?
/**
 * @author Mikhail Starovoyt
 * @version 4.5.3
 */
class AExportXml extends Admin {
	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
		$this->sTableName='export_xml';
		$this->sTablePrefix='ex';
		$this->sAction='export_xml';
		$this->sWinHead=Language::getDMessage('Export xml');
		$this->sPath=Language::GetDMessage('>>Content >');
		$this->aCheckField=array('code');
		//$this->aFCKEditors = array ('description');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong', 0)) {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and ex.id = '" . $this->aSearch['id'] . "'";
                if ($this->aSearch['name'])
                    $this->sSearchSQL .= " and ex.name = '" . $this->aSearch['name'] . "'";
                if ($this->aSearch['code'])
                    $this->sSearchSQL .= " and ex.code = '" . $this->aSearch['code'] . "'";
                if ($this->aSearch['limit_count'])
                    $this->sSearchSQL .= " and ex.limit_count = '" . $this->aSearch['limit_count'] . "'";
                if ($this->aSearch['filename'])
                    $this->sSearchSQL .= " and ex.filename = '" . $this->aSearch['filename'] . "'";
            } else {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and ex.id like '%" . $this->aSearch['id'] . "%'";
                if ($this->aSearch['code'])
                    $this->sSearchSQL .= " and ex.code like '%" . $this->aSearch['code'] . "%'";
                if ($this->aSearch['name'])
                    $this->sSearchSQL .= " and ex.name like '%" . $this->aSearch['name'] . "%'";
                if ($this->aSearch['limit_count'])
                    $this->sSearchSQL .= " and ex.limit_count like '%" . $this->aSearch['limit_count'] . "%'";
                if ($this->aSearch['filename'])
                    $this->sSearchSQL .= " and ex.filename like '%" . $this->aSearch['filename'] . "%'";
            }
            if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and ex.visible = '1'";
            if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and ex.visible = '0'";
            //with else "ignore" will not be found
            switch($this->aSearch['visible']){
                case '1':
                    $this->sSearchSQL.=" and ex.visible>='1'";
                    break;
                case '0':
                    $this->sSearchSQL.=" and ex.visible>='0'";
                    break;
                case  '':
                    break;
            }
        }

		$oTable=new Table();
		$sTablePref = 'ex.';
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id','sOrder'=>$sTablePref.'id'),
		'code'=>array('sTitle'=>'Code','sOrder'=>$sTablePref.'code'),
		'name'=>array('sTitle'=>'Name','sOrder'=>$sTablePref.'name'),
		// 'price_link_suffix'=>array('sTitle'=>'price_link_suffix','sOrder'=>$sTablePref.'price_link_suffix'),
		'limit_count'=>array('sTitle'=>'count limit','sOrder'=>$sTablePref.'limit_count'),
		'filename'=>array('sTitle'=>'filename','sOrder'=>$sTablePref.'filename'),
		'visible'=>array('sTitle'=>'Visible','sOrder'=>$sTablePref.'visible'),
		'action'=>array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign(&$aData)
	{
		//----------------------------------------------------- price groups ------------------------
		$aPriceGroup=Db::GetAll(Base::GetSql("Price/Group",array(
		'visible'=>1,
		'order'=>' order by pg.name',
		"where"=>" and pg.code_name is not null",
		)));
		Base::$tpl->assign('aPriceGroup',$aPriceGroup);

		$aPriceGroupAssoc=Db::GetAssoc("select expg.id_price_group as id, expg.id_export_xml as value
			from export_xml_price_group as expg
			where id_export_xml='".$aData['id']."'
		");
		Base::$tpl->assign('aPriceGroupId',array_keys($aPriceGroupAssoc));
		
		Base::$tpl->assign('iMinPGId',Db::GetOne("select min(id) from price_group"));
		Base::$tpl->assign('iMaxPGId',Db::GetOne("select max(id) from price_group"));
		//----------------------------------------------------- brands ------------------------------
		$aBrand=Db::GetAll(Base::GetSql("Cat",array(
			'visible'=>1,
			'order'=>'title'
		)));
		
		Base::$tpl->assign('aBrand',$aBrand);
		
		$aBrandAssoc=Db::GetAssoc("select expg.id_brand as id, expg.id_export_xml as value
			from export_xml_brand as expg
			where id_export_xml='".$aData['id']."'
		");
		Base::$tpl->assign('aBrandId',array_keys($aBrandAssoc));
		
		Base::$tpl->assign('iMinBrandId',Db::GetOne("select min(id) from cat"));
		Base::$tpl->assign('iMaxBrandId',Db::GetOne("select max(id) from cat"));
		//----------------------------------------------------- providers -----------------------------
		$aProvider=Db::GetAll(Base::GetSql("Provider",array(
			'visible'=>1,
		)).' order by name');
		Base::$tpl->assign('aProvider',$aProvider);
		
		$aProviderAssoc=Db::GetAssoc("select expg.id_provider as id, expg.id_export_xml as value
			from export_xml_provider as expg
			where id_export_xml='".$aData['id']."'
		");
		Base::$tpl->assign('aProviderId',array_keys($aProviderAssoc));
		
		Base::$tpl->assign('iMinProviderId',Db::GetOne("select min(id_user) from user_provider"));
		Base::$tpl->assign('iMaxProviderId',Db::GetOne("select max(id_user) from user_provider"));
	}
	//-----------------------------------------------------------------------------------------------
	public function AfterApply($aBeforeRow,$aAfterRow)
	{
		//----------------------------------------------------- price groups ------------------------
		Db::Execute("delete from export_xml_price_group where id_export_xml='".$aAfterRow['id']."'");

		if (Base::$aRequest['data']['id_price_group']) {
			$aExportXmlPriceGroupInsert['id_export_xml']=$aAfterRow['id'];
			foreach (Base::$aRequest['data']['id_price_group'] as $sKey=>$aValue) {
				if($aValue=='on'){
				$aExportXmlPriceGroupInsert['id_price_group']=$sKey;
				Db::AutoExecute('export_xml_price_group',$aExportXmlPriceGroupInsert);
				}
			}
		}
		
		//----------------------------------------------------- providers -----------------------------
		Db::Execute("delete from export_xml_provider where id_export_xml='".$aAfterRow['id']."'");
		
		if (Base::$aRequest['data']['id_provider']) {
			$aExportXmlProviderInsert['id_export_xml']=$aAfterRow['id'];
			foreach (Base::$aRequest['data']['id_provider'] as $sKey=>$aValue) {
				if($aValue=='on'){
					$aExportXmlProviderInsert['id_provider']=$sKey;
					Db::AutoExecute('export_xml_provider',$aExportXmlProviderInsert);
				}
			}
		}
		
		//----------------------------------------------------- brands ------------------------------
		Db::Execute("delete from export_xml_brand where id_export_xml='".$aAfterRow['id']."'");
		
		if (Base::$aRequest['data']['id_brand']) {
			$aExportXmlbrandInsert['id_export_xml']=$aAfterRow['id'];
			foreach (Base::$aRequest['data']['id_brand'] as $sKey=>$aValue) {
				if($aValue=='on'){
					$aExportXmlbrandInsert['id_brand']=$sKey;
					Db::AutoExecute('export_xml_brand',$aExportXmlbrandInsert);
				}
			}
		}
		
	}
	//-----------------------------------------------------------------------------------------------

}
?>