<?
class ARubricator extends Admin {
	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName = 'rubricator';
		$this->sTablePrefix = 'r';
		$this->sAction = 'rubricator';
		$this->sWinHead = Language::getDMessage ('Rubricator');
		$this->sPath = Language::GetDMessage('>> Auto catalog >');
		$this->aCheckField = array("name","url");
		$this->Admin ();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex ();
		Base::$sText .= $this->SearchForm ();
		if ($this->aSearch) {
			if (Language::getConstant('mpanel_search_strong',0)) {
				if ($this->aSearch['id'])$this->sSearchSQL .= " and r.id = '".$this->aSearch['id']."'";
				if ($this->aSearch['url'])	$this->sSearchSQL .= " and r.url = '".$this->aSearch['url']."'";
				if ($this->aSearch['name'])	$this->sSearchSQL .= " and r.name = '".$this->aSearch['name']."'";
				if ($this->aSearch['level'])	$this->sSearchSQL .= " and r.level = '".$this->aSearch['level']."'";
				if ($this->aSearch['id_parent'])	$this->sSearchSQL .= " and r.id_parent = '".$this->aSearch['id_parent']."'";
				if ($this->aSearch['language'])	$this->sSearchSQL .= " and r.language = '".$this->aSearch['language']."'";
			}
			else {
			    if ($this->aSearch['id'])$this->sSearchSQL .= " and r.id like '%".$this->aSearch['id']."%'";
			    if ($this->aSearch['url'])	$this->sSearchSQL .= " and r.url like '%".$this->aSearch['url']."%'";
			    if ($this->aSearch['name'])	$this->sSearchSQL .= " and r.name like '%".$this->aSearch['name']."%'";
			    if ($this->aSearch['level'])	$this->sSearchSQL .= " and r.level like '%".$this->aSearch['level']."%'";
			    if ($this->aSearch['id_parent'])	$this->sSearchSQL .= " and r.id_parent like '%".$this->aSearch['id_parent']."%'";
			    if ($this->aSearch['language'])	$this->sSearchSQL .= " and r.language like '%".$this->aSearch['language']."%'";
			}
			if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and r.visible='1'";
			if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and r.visible='0'";
			switch($this->aSearch['visible']){
			    case '1':
			        $this->sSearchSQL.=" and r.visible>='1'";
			        break;
			    case '0':
			        $this->sSearchSQL.=" and r.visible>='0'";
			        break;
			    case  '':
			        break;
			}
			if ($this->aSearch['is_main']=='1')	$this->sSearchSQL .= " and r.is_main='1'";
			if ($this->aSearch['is_main']=='0')	$this->sSearchSQL .= " and r.is_main='0'";
			switch($this->aSearch['is_main']){
			    case '1':
			        $this->sSearchSQL.=" and r.is_main>='1'";
			        break;
			    case '0':
			        $this->sSearchSQL.=" and r.is_main>='0'";
			        break;
			    case  '':
			        break;
			}
			if ($this->aSearch['is_menu']=='1')	$this->sSearchSQL .= " and r.is_menu='1'";
			if ($this->aSearch['is_menu']=='0')	$this->sSearchSQL .= " and r.is_menu='0'";
			switch($this->aSearch['is_menu']){
			    case '1':
			        $this->sSearchSQL.=" and r.is_menu>='1'";
			        break;
			    case '0':
			        $this->sSearchSQL.=" and r.is_menu>='0'";
			        break;
			    case  '':
			        break;
			}
			
		}
		//--------------------
		
		$oTable = new Table ( );
		$oTable->aColumn = array ();
		$oTable->aColumn ['id'] = array ('sTitle' => 'Id', 'sOrder' => $this->sTablePrefix.'.id' );
		$oTable->aColumn ['name'] = array ('sTitle' => 'Name', 'sOrder' => $this->sTablePrefix.'.name');
		$oTable->aColumn ['id_parent'] = array ('sTitle' => 'Id Parent', 'sOrder' => $this->sTablePrefix.'.id_parent');
		$oTable->aColumn ['level'] = array ('sTitle' => 'Level', 'sOrder' => $this->sTablePrefix.'.level');
		$oTable->aColumn ['url'] = array ('sTitle' => 'Url', 'sOrder' => $this->sTablePrefix.'.url');
		$oTable->aColumn ['image'] = array ('sTitle' => 'Image', 'sOrder' => $this->sTablePrefix.'.image');
		$oTable->aColumn ['sort'] = array ('sTitle' => 'sort', 'sOrder' => $this->sTablePrefix.'.sort');
		$oTable->aColumn ['is_mainpage'] = array ('sTitle' => 'is_mainpage', 'sOrder' => $this->sTablePrefix.'.is_mainpage');
		$oTable->aColumn ['is_menu_visible'] = array ('sTitle' => 'is_menu_visible', 'sOrder' => $this->sTablePrefix.'.is_menu_visible');
		$oTable->aColumn ['id_price_group'] = array ('sTitle' => 'id_price_group', 'sOrder' => $this->sTablePrefix.'.id_price_group');
		$oTable->aColumn ['visible'] = array ('sTitle' => 'Visible', 'sOrder' => $this->sTablePrefix.'.visible');
		$this->initLocaleGlobal ();
		$oTable->aColumn ['language']=array('sTitle'=>'Lang');
		$oTable->aColumn ['action'] = array ();
		$this->SetDefaultTable ( $oTable );
		Base::$sText .= $oTable->getTable ();
		$this->AfterIndex ();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeApply()
	{
		if(Base::$aRequest['data']['id_tree']) Base::$aRequest['data']['id_tree']=implode(',',Base::$aRequest['data']['id_tree']);
// 		if(isset(Base::$aRequest['data']['id_group'])) 
		    Base::$aRequest['data']['id_group']=implode(',',Base::$aRequest['data']['id_group']);	
	

		DB::Execute("delete from rubricator_filter where id_rubricator='".Base::$aRequest['data']['id']."'");
		$aHandBook=Base::$aRequest['data']['handbook'];
		if ($aHandBook){
		    foreach ($aHandBook as $aItem){
		        $aData=array(
		            'id_handbook'=>$aItem,
		            'id_rubricator'=>Base::$aRequest['data']['id'],
		        );
		        Db::AutoExecute('rubricator_filter',$aData);
		    }
		}

	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign($aData) 
	{
		$aBaseTree=array('0'=>'not selected')+TecdocDb::GetTreeAssoc();
		
		$aRubrics=array();
		$aParentRubrics=Base::$db->getAssoc("SELECT id, CONCAT(if(level='1','','------'),' ',id,' - ',name) AS name_group
 			FROM rubricator
		    WHERE level in ('1')
		    ORDER BY name
	    ");
		if($aParentRubrics) {
		    foreach ($aParentRubrics as $sKey=>$sValue) {
		        $aRubrics[$sKey]=$sValue;
		
		        $aChildRubrics=Base::$db->getAssoc("SELECT id, CONCAT(if(level='1','','------'),' ',id,' - ',name) AS name_group
         			FROM rubricator
        		    WHERE level in ('2') and id_parent='".$sKey."'
        		    ORDER BY name
        	    ");
		        if($aChildRubrics) {
		            foreach ($aChildRubrics as $sKeyChild => $sValueChild) {
		                $aRubrics[$sKeyChild]=$sValueChild;
		            }
		        }
		    }
		}
		
		$aBaseLevelGroups=array('0'=>'not selected')+$aRubrics;
		$aBaseLevels=array(1=>1,2=>2,3=>3);
		
		Base::$tpl->assign ( 'aBaseTree', $aBaseTree );
		Base::$tpl->assign ( 'aBaseTreeSelect', (!$aData['id_tree'])?array(0=>0):explode(',',$aData['id_tree']));
 		Base::$tpl->assign ( 'aBaseLevelGroups', $aBaseLevelGroups );
 		Base::$tpl->assign ( 'sBaseLevelGroups', $aData['id_parent'] );
		Base::$tpl->assign ( 'aBaseLevels', $aBaseLevels );
		Base::$tpl->assign ( 'sBaseLevels', $aData['level'] );
		
		$this->sScriptForAdd="$('#select_tree').select2().on('change', function() { 
		    xajax_process_browse_url('/?action=rubricator_change_select_part&id=".$aData['id']."&id_tree='+$(this).val());
	    });";
		if ($aData['id_tree']!='' && trim($aData['id_tree'])!='0')
		$aGroup=TecdocDb::GetAssoc(" select
	        grp.id_src as id_group, concat('[',grp.id_src,'] ',grp.Name) as group_name
	        from ".DB_OCAT."cat_alt_groups as grp
	        join ".DB_OCAT."cat_alt_link_str_grp as lsg on grp.id_grp=lsg.ID_grp
	        where lsg.id_tree in (".$aData['id_tree'].")
        ");
	    $aGroupSelected=explode(",", Db::GetOne("select id_group from rubricator where id='".$aData['id']."' "));
	    
	    Base::$tpl->assign ('aGroup',$aGroup);
	    Base::$tpl->assign ('aGroupSelected',$aGroupSelected);
	    
// 	    Base::$tpl->assign ( 'aPriceGroups', array("0"=>"не выбрано")+Db::GetAssoc("select id, name from price_group order by name") );

		$aHandbook=Base::$db->GetAll('select * from handbook');
	    Base::$tpl->assign('aHandbook',$aHandbook);
	    
	    $aPriceGroupFilter=Base::$db->GetAll("select * from rubricator_filter
			where id_rubricator='".Base::$aRequest['id']."'");
	    
	    $aSelectedHandbook=array();
	    if ($aPriceGroupFilter)
	        foreach($aPriceGroupFilter as $key=>$value){
	        $aSelectedHandbook[$value['id_handbook']]=$value['id_handbook'];
	    }
	    Base::$tpl->assign('aSelectedHandbook',$aSelectedHandbook);
	}
	//-----------------------------------------------------------------------------------------------
	public function ChangeSelectPart() {

		Base::$aRequest['id_tree'] = preg_replace('/^0,/m', '', Base::$aRequest['id_tree']);

		if (Base::$aRequest['id_tree'])
	    $aGroup=TecdocDb::GetAssoc(" select
	        grp.id_src as id_group, concat('[',grp.id_src,'] ',grp.Name) as group_name
	        from ".DB_OCAT."cat_alt_groups as grp 
	        join ".DB_OCAT."cat_alt_link_str_grp as lsg on grp.id_grp=lsg.ID_grp
	        where lsg.id_tree in (".Base::$aRequest['id_tree'].")
        ");
	    $aGroupSelected=explode(",", Db::GetOne("select id_group from rubricator where id='".Base::$aRequest['id']."' "));
	    
	    Base::$tpl->assign ('aGroup',$aGroup);
	    Base::$tpl->assign ('aGroupSelected',$aGroupSelected);
	    
	    Base::$oResponse->AddAssign('id_group_list','innerHTML',Base::$tpl->fetch('mpanel/rubricator/change_group.tpl'));
	}
	//-----------------------------------------------------------------------------------------------
}
?>
