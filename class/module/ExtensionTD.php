<?
/**
 * Extension TecDoc
 * @author vladimir_zheliba
 *
 */
class ExtensionTD extends Base
{
    var $sPrefix="extension_td";
    private static $sDeleteID;
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
	    $this->CatInfoImport();
	}
	//-----------------------------------------------------------------------------------------------
	public function Tree()
	{
	    Auth::NeedAuth("manager");
	    
		Base::$bXajaxPresent=true;
	    Base::$tpl->assign('bLeftMenu',false);
	    $this->TreeAuto();
	
	    if(!Base::$aRequest['data']['id_model_detail']) {
	        Base::$sText.="error: model detail is null";
	        return;
	    }
	
	    if(Base::$aRequest['is_post']) {
	        if(Base::$aRequest['subaction']=='add') {
	            if(Base::$aRequest['data']['id_model_detail'] && Base::$aRequest['data']['id_cat_part']) {
	
	                Db::AutoExecute("cat_model_type_link",array(
	                'id_cat_model_type'=>Base::$aRequest['data']['id_model_detail'],
	                'id_cat_part'=>Base::$aRequest['data']['id_cat_part']
	                ));
	                Base::Redirect("/pages/extension_td_tree?data[id_model_detail]=".Base::$aRequest['data']['id_model_detail']);
	            } else {
	                //error
	            }
	        }
	        	
	        if(Base::$aRequest['subaction']=='delete_part') {
	            $iIdCatPart=Db::GetOne("select id_cat_part from cat_model_type_link where id='".Base::$aRequest['part']."' ");
	            Db::Execute("delete from cat_tree_link where id_cat_part='".$iIdCatPart."' ");
	            Db::Execute("delete from cat_model_type_link where id='".Base::$aRequest['part']."' ");
	            Base::Redirect("/pages/extension_td_tree?data[id_model_detail]=".Base::$aRequest['data']['id_model_detail']);
	        }
	        	
	        if(Base::$aRequest['subaction']=='delete_tree') {
	            $aData=Db::GetRow("select id_typ, id_tree from cat_tree_type_link where id='".Base::$aRequest['id']."' ");
	            self::$sDeleteID='';
	            self::TreeDeleteChilds($aData['id_tree']);
	            self::$sDeleteID.=$aData['id_tree'];
	            Db::Execute("delete from cat_tree_type_link where id_typ='".$aData['id_typ']."' and id_tree in (".self::$sDeleteID.")");
	            //Db::Execute("delete from cat_tree_type_link where id='".Base::$aRequest['id']."' ");
	            Base::Redirect("/pages/extension_td_tree?data[id_model_detail]=".Base::$aRequest['data']['id_model_detail']);
	        }
	    }
	
 	    Resource::Get()->Add('/css/tree.css',1);
	    Resource::Get()->Add('/js/tree_cm.js',1);
	
	    //new tree begin
	    $sSql="(select t.ID_src as id,
			       t.Level+1 str_level,
			       t.Sort str_sort,
			       0 expand,
			       t.Name as data,
			       t.ID_parent str_id_parent,
			       'red' color
				from ".DB_OCAT."cat_alt_tree t
   				where t.Level > 0
				order by t.Name)";
	
	    $aTreeItem=array();
	    $aTreeAll=TecdocDb::GetAll($sSql);
	    $aTree=array();
	    if ($aTreeAll) foreach ($aTreeAll as $aValue) {
	        $aTree[$aValue['id']]=$aValue;
	    }
	    unset($aTreeAll);
	
	    if (Base::$aRequest['data']['id_part']) $this->SetSelectedPart($aTree, Base::$aRequest['data']['id_part']);
	
	
	    // 		$aTreeExist=Db::GetAll($s=Base::GetSql("OptiCatalog/Assemblage",array(
	    // 				'id_model_detail'=>Base::$aRequest['data']['id_model_detail']
	    // 		)));
	    $aTreeExist=TecdocDb::GetTree(Base::$aRequest['data'],true);
	    $aTreeExistAssoc=array();
	    if ($aTreeExist) foreach ($aTreeExist as $aValue) {
	        $aTreeExistAssoc[$aValue['id']]=$aValue;
	    }
	
	    if ($aTree) foreach ($aTree as $aValue) {
	        $sAddUrl='';
	        if(array_key_exists($aValue['id'], $aTreeExistAssoc)) {
	            if($aTreeExistAssoc[$aValue['id']]['color']==0) $sColor="green";
	            else {
	                $sColor="blue";
	                $sAddUrl='  <a href="/pages/extension_td_tree?subaction=delete_tree&id='.$aTreeExistAssoc[$aValue['id']]['color'].'&data[id_model_detail]='.Base::$aRequest['data']['id_model_detail'].'&is_post=1">Delete</a>';
	            }
	        } else {
	            $sColor=$aValue['color'];
	            $sAddUrl='  <a href="/?action=extension_td_tree_add&data[id_typ]='.Base::$aRequest['data']['id_model_detail'].'&data[id_tree]='.$aValue['id'].'" >Add</a>';
	        }
	        	
	        $aTreeItem[$aValue['str_id_parent']][]=array(
	            "selected"=>$aValue['selected'],
	            "name"=>$aValue['data'],
	            "id"=>$aValue['id'],
	            "level"=>$aValue['str_level'],
	            "id_parend"=>$aValue['str_id_parent'],
	            "color"=>$sColor,
	            "url"=>'/?action=extension_td_tree_part&data[id_model_detail]='.Base::$aRequest['data']['id_model_detail'].'&data[id_tree]='.$aValue['id'],
	            "add_url"=>$sAddUrl
	        );
	    }
	    //-----------------------------
	
	    $aBrands=Db::GetAssoc("select distinct cp.pref, c.title from cat_part as cp inner join cat as c on c.pref=cp.pref order by c.title");
	    Base::$tpl->assign("aBrands",array(''=>'')+$aBrands);
	
// 	    $aData=array(
// 	        'sHeader'=>"method=get",
// 	        'sContent'=>Base::$tpl->fetch('extension_td/form_tree_add_part.tpl'),
// 	        'sSubmitButton'=>'Add',
// 	        'sSubmitAction'=>'extension_td_tree',
// 	        'bIsPost'=>1,
// 	    );
// 	    $oForm=new Form($aData);
	
	    $oTable=new Table();
	    $oTable->sSql="select c.title as brand, cp.code/*, t.Name as tree*/, cmtl.id as id_cmtl
	
				from cat_model_type_link cmtl
				inner join cat_part as cp on cp.id=cmtl.id_cat_part
				inner join cat as c on c.pref=cp.pref
	
				/*left join cat_tree_type_link as cttl on cttl.id_typ='".Base::$aRequest['data']['id_model_detail']."'
				left join ".DB_OCAT."cat_alt_tree as t on t.ID_tree=cttl.id_tree
				*/
				where cmtl.id_cat_model_type='".Base::$aRequest['data']['id_model_detail']."' ";
	    $oTable->aColumn['brand']=array('sTitle'=>'Brand','sWidth'=>'40%');
	    $oTable->aColumn['code']=array('sTitle'=>'Code','sWidth'=>'40%');
	    $oTable->aColumn['action']=array();
	
	    $oTable->bFormAvailable=false;
	    $oTable->bStepperVisible=false;
	    $oTable->iRowPerPage=Db::GetOne("select count(*) from cat_model_type_link where id_cat_model_type='".Base::$aRequest['data']['id_model_detail']."' ");
	    $oTable->sDataTemplate='extension_td/row_tree_part.tpl';
	
	    Base::$tpl->assign("sTree",$this->outTree($aTreeItem, 10001, 0));
	    Base::$tpl->assign("sTable",$oTable->getTable());
// 	    Base::$tpl->assign("sForm",$oForm->getForm());

	    $aRubrics=Db::GetAll("select * from rubricator where visible='1' ");
	    usort($aRubrics, function ($a, $b)
	    {
	        if ($a['sort'] == $b['sort']) {
	            return 0;
	        }
	        return ($a['sort'] < $b['sort']) ? -1 : 1;
	    });
	    $aTreeRubricItem=array();
	    if ($aRubrics) foreach ($aRubrics as $aValue) {
	    
	        if($aValue['id_tree']==0) {
	            $bAlow=true;
	        } else {
	            $aTreeItems=explode(",", $aValue['id_tree']);
	            $bAlow=false;
	            foreach ($aTreeItems as $iTree) {
// 	                if(in_array($iTree, array_keys($aTreeExistAssoc))) {
	                    $bAlow=true;
// 	                }
	            }
	        }
	    
	        if($bAlow) {
	            $aTreeRubricItem[$aValue['id_parent']][]=array(
	                "selected"=>$aValue['selected'],
	                "name"=>$aValue['name'],
	                "id"=>$aValue['id'],
	                "level"=>$aValue['level'],
	                "id_parend"=>$aValue['id_parent'],
	                "color"=>$sColor,
	                "url"=>'/pages/extension_td_tree_rubric?data[id_model_detail]='.Base::$aRequest['data']['id_model_detail'].'&data[id_rubric]='.$aValue['id'],
	            );
	        }
	    }
	    Base::$tpl->assign('sTreeRubric',$this->outTree($aTreeRubricItem, 0, 1));
	    
	    Base::$sText.=Base::$tpl->fetch('extension_td/tree.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function TreeAuto()
	{
		$aRow=TecdocDb::GetRow("select cat_alt_types.Description
         , substr(cat_alt_types.DateStart,5,2) as month_start
         , substr(cat_alt_types.DateStart,1,4) as year_start
		 , substr(cat_alt_types.DateEnd,5,2) as month_end
		 , substr(cat_alt_types.DateEnd,1,4) as year_end
		 , LEFT(KwHp, LOCATE('/', KwHp)-1) kw_from
		 , SUBSTRING(KwHp FROM LOCATE('/', KwHp)+1) hp_from
		 , CCM as ccm, Body as body
	    from ".DB_OCAT."cat_alt_types
	    inner join ".DB_OCAT."cat_alt_models on cat_alt_models.ID_mod = cat_alt_types.ID_mod
	    inner join ".DB_OCAT."cat_alt_manufacturer on cat_alt_models.ID_mfa=cat_alt_manufacturer.ID_mfa
	    where 1=1 and cat_alt_types.ID_src = '".Base::$aRequest['data']['id_model_detail']."'");
		
		$sText="<table width=\"100%\" class=\"bs-table\"><tr>";
		foreach ($aRow as $sValue) $sText.="<td>".$sValue."</td>";
		$sText.="</tr></table><br>";
		
		Base::$sText.=$sText;
	}
	//-----------------------------------------------------------------------------------------------
	private function mb_ucfirst($str, $enc = 'utf-8') {
		return mb_strtoupper(mb_substr($str, 0, 1, $enc), $enc).mb_substr($str, 1, mb_strlen($str, $enc), $enc);
	}
	//-----------------------------------------------------------------------------------------------
	public function outTree(&$category_arr=array(), $parent_id, $level) {
	    $sText="";
	    if (isset($category_arr[$parent_id])) { //Если категория с таким parent_id существует
	        $iChildCount=count($category_arr[$parent_id]);
	        $iEndCounter=0;
	        foreach ($category_arr[$parent_id] as $value) { //Обходим
	            $iEndCounter++;
	             
	            $level = $level + 1; //Увеличиваем уровень вложености
	            //Рекурсивно вызываем эту же функцию, но с новым $parent_id и $level
	            $sResult=$this->outTree($category_arr, $value["id"], $level);
	             
	            if ($value["selected"]) {
	                $sExpandStatus=' ExpandOpen';
	                if(!$sResult) $sBold='style="font-weight: bold;"';
	                else $sBold='';
	            } else {
	                $sExpandStatus=' ExpandClosed';
	                $sBold='';
	            }
	             
	            if ($sResult) {
	                // have child and need expand
	                $sText.='<li class="Node';
	                if($level==1) $sText.=' IsRoot';
	                $sText.=$sExpandStatus;
	                if($iEndCounter==$iChildCount) $sText.=' IsLast';
	                $sText.='">';
	                $sText.='  <div class="Expand"></div>';
	                $sText.='  <div class="dContent"><a href="#" class="expand" onclick="return false" '.$sBold.' style="color:'.$value['color'].';">'.$this->mb_ucfirst($value["name"]).'</a>'.$value["add_url"].'</div>';
	            } else {
	                // not need expand
	                $sText.='<li class="Node';
	                if($level==1) $sText.=' IsRoot';
	                $sText.=' ExpandLeaf';
	                if($iEndCounter==$iChildCount) $sText.=' IsLast';
	                $sText.='">';
	                $sText.='	<div class="Expand"></div>';
	                $sText.='	<div class="dContent"><a href="'.$value["url"].'" class="expand" '.$sBold.' style="color:'.$value['color'].';">'.$this->mb_ucfirst($value["name"]).'</a>'.$value["add_url"].'</div>';
	            }
	             
	            if ($sResult) {
	                //expand
	                $sText.='	<ul class="Container">';
	                $sText.=$sResult;
	                $sText.='	</ul>';
	            }
	             
	            $level = $level - 1; //Уменьшаем уровень вложености
	            $sText.="</li>";
	             
	        }
	    }
	    return $sText;
	}
	//-----------------------------------------------------------------------------------------------
	public function TreeChangeSelect()
	{
	    if(Base::$aRequest['data']['pref']) {
	        $aCodes=Db::GetAssoc("select cp.id, cp.code
				from cat_part as cp
				inner join cat as c on c.pref=cp.pref
				where cp.pref='".Base::$aRequest['data']['pref']."'
				order by c.title, cp.code");
	        Base::$tpl->assign("aCodes",array(''=>'')+$aCodes);
	        	
	        Base::$oResponse->addAssign('id_select_code','outerHTML',Base::$tpl->fetch('extension_td/tree_select_code.tpl'));
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function TreeAdd()
	{
	    if(!Base::$aRequest['data']['id_typ'] || !Base::$aRequest['data']['id_tree']) {
	        Base::$sText.="error: model detail is null";
	        return;
	    }
	    //add to cat_tree_type_link
	    $aData=String::FilterRequestData(Base::$aRequest['data']);
	    Db::AutoExecute("cat_tree_type_link",$aData);
	    
	    Base::$aRequest['data']['id_model_detail']=Base::$aRequest['data']['id_typ'];
	    $aTreeExist=TecdocDb::GetTree(Base::$aRequest['data'],true);
	    $aIDTreeExist=array();
	    if($aTreeExist) foreach ($aTreeExist as $aValue){
	        $aIDTreeExist[]=$aValue['id'];
	    }
	
	    self::TreeAddParrent($aData,$aIDTreeExist);
	
	    Base::Redirect("/pages/extension_td_tree?data[id_model_detail]=".$aData['id_typ']);
	}
	//-----------------------------------------------------------------------------------------------
	public static function TreeAddParrent($aData,$aIDTreeExist)
	{
	    $iIdParent=TecdocDb::GetOne("select ID_parent from ".DB_OCAT."cat_alt_tree where ID_tree='".$aData['id_tree']."'");
	    if($iIdParent && $iIdParent!=10001 && !in_array($iIdParent, $aIDTreeExist)){
	        $aData['id_tree']=$iIdParent;
	        self::TreeAddParrent($aData);
	        if(!Db::GetOne("select count(*) from cat_tree_type_link where id_typ='".$aData['id_typ']."' and id_tree='".$aData['id_tree']."'"))
	            Db::AutoExecute("cat_tree_type_link",$aData);
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public static function TreeDeleteChilds($iID)
	{
	    $aChild=TecdocDb::GetAssoc("select ID_src, ID_tree from ".DB_OCAT."cat_alt_tree where ID_parent='".$iID."'");
	    if($aChild) foreach ($aChild as $iValue) self::TreeDeleteChilds($iValue);
	    self::$sDeleteID.=$iID.",";
	}
	//-----------------------------------------------------------------------------------------------
	public function TreePart()
	{
	    Base::$tpl->assign('bLeftMenu',false);
	    $this->TreeAuto();
	    Base::$sText.="<table width=\"100%\" class=\"bs-table\"><tr><td><a href='/pages/extension_td_tree?data[id_model_detail]=".Base::$aRequest['data']['id_model_detail']."'>Назад</a></td><td>".TecdocDb::GetOne("select Name from ".DB_OCAT."cat_alt_tree where ID_tree='".Base::$aRequest['data']['id_tree']."' ")."</td></tr></table><br>";
	
	    if(!Base::$aRequest['data']['id_model_detail'] || !Base::$aRequest['data']['id_tree']) {
	        Base::$sText.="error: model detail is null";
	        return;
	    }
	
	    //add to cat_tree_link
	    if(Base::$aRequest['is_post']) {
	        if(Base::$aRequest['subaction']=='add' && Base::$aRequest['data']['id_tree'] && Base::$aRequest['data']['id_cat_part']) {
	            Db::AutoExecute("cat_tree_link",array(
	            "id_cat_part"=>Base::$aRequest['data']['id_cat_part'],
	            "id_tree"=>Base::$aRequest['data']['id_tree']
	            ));
	            Base::Redirect("/?action=extension_td_tree_part&data[id_model_detail]=".Base::$aRequest['data']['id_model_detail']."&data[id_tree]=".Base::$aRequest['data']['id_tree']);
	        }
	        	
	        if(Base::$aRequest['subaction']=='delete') {
	            Db::Execute("delete from cat_tree_link where id='".Base::$aRequest['id']."' ");
	            Base::Redirect("/pages/extension_td_tree_part?data[id_model_detail]=".Base::$aRequest['data']['id_model_detail']."&data[id_tree]=".Base::$aRequest['data']['id_tree']);
	        }
	    }
	    	
	    $aCodes=Db::GetAssoc("select cp.id, concat(c.title,' ',cp.code)
				from cat_model_type_link cmtl
				inner join cat_part as cp on cp.id=cmtl.id_cat_part
				inner join cat as c on c.pref=cp.pref
				where cmtl.id_cat_model_type='".Base::$aRequest['data']['id_model_detail']."'
				order by c.title, cp.code");
	    Base::$tpl->assign("aCodes",array(''=>'')+$aCodes);
	    
	    $aGroups=TecdocDb::GetAssoc(" select
	        grp.id_src as id_group, grp.Name as group_name
	        from ".DB_OCAT."cat_alt_groups as grp
	        join ".DB_OCAT."cat_alt_link_str_grp as lsg on grp.id_grp=lsg.ID_grp
	        where lsg.id_tree in (".Base::$aRequest['data']['id_tree'].")
        ");
	    
	    Base::$tpl->assign("aGroupsTcd",array(''=>'')+$aGroups);
	
	    $aData=array(
	        'sHeader'=>"method=get",
	        'sContent'=>Base::$tpl->fetch('extension_td/form_tree_add_detail.tpl'),
	        'sSubmitButton'=>'Add',
	        'sSubmitAction'=>'extension_td_tree_part',
	        'sAdditionalButtonTemplate'=>'extension_td/button_tree_add_detail.tpl',
	        'bIsPost'=>1,
	    );
	    $oForm=new Form($aData);
	    Base::$sText.=$oForm->GetForm();
	
	
	    $oTable=new Table();
	    $oTable->sSql="select c.title as brand, cp.code, ctl.id, ctl.id_group
	
				from cat_model_type_link cmtl
				inner join cat_part as cp on cp.id=cmtl.id_cat_part
				inner join cat as c on c.pref=cp.pref
				inner join cat_tree_link as ctl on ctl.id_cat_part=cmtl.id_cat_part and ctl.id_tree='".Base::$aRequest['data']['id_tree']."'
	
				where cmtl.id_cat_model_type='".Base::$aRequest['data']['id_model_detail']."'
				";
	    $oTable->aColumn['brand']=array('sTitle'=>'Brand','sWidth'=>'40%');
	    $oTable->aColumn['code']=array('sTitle'=>'Code','sWidth'=>'40%');
	    $oTable->aColumn['group']=array('sTitle'=>'group',);
	    $oTable->aColumn['action']=array();
	
	    $oTable->bFormAvailable=false;
	    $oTable->bStepperVisible=1;
	    $oTable->iRowPerPage=100;
	    $oTable->sDataTemplate='extension_td/row_tree_detail.tpl';
	
	    Base::$sText.=$oTable->getTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function TreePartCopy()
	{
	    Base::$tpl->assign('bLeftMenu',false);
	    $aData=Db::GetAll("
			select cmtl.id,cp.code,c.name as brand
		
			from cat_model_type_link as cmtl
			inner join cat_tree_link as ctl on cmtl.id_cat_part=ctl.id_cat_part and ctl.id_tree='".Base::$aRequest['data']['id_tree']."'
			inner join cat_part as cp on cp.id=cmtl.id_cat_part
			inner join cat as c on c.pref=cp.pref
		
			where id_cat_model_type='".Base::$aRequest['data']['id_model_detail']."' ");
	
	    $aModelDetail=TecdocDb::GetAll("
			select md.id_src as id, CONCAT('[',md.id_src,'] ') as sId,
			CONCAT(md.Description,' ',ifnull(substr(md.DateStart,5,2),''),'.',ifnull(substr(md.DateStart,1,4),''),' - ',ifnull(substr(md.DateEnd,5,2),''),'.',ifnull(substr(md.DateEnd,1,4),''),' ',ifnull(LEFT(md.KwHp, LOCATE('/', KwHp)-1),''),' ',ifnull(SUBSTRING(md.KwHp FROM LOCATE('/', KwHp)+1),''),' ',ifnull(md.CCM,''),' ',ifnull(md.Body,'')) as name
	
			from ".DB_OCAT."cat_alt_types as md
			where md.ID_mod in
			(select cat_alt_models.ID_mod
			from ".DB_OCAT."cat_alt_types
			inner join ".DB_OCAT."cat_alt_models on cat_alt_models.ID_mod = cat_alt_types.ID_mod
			where cat_alt_types.id_src='".Base::$aRequest['data']['id_model_detail']."')
			order by md.Description
		");
	    if($aModelDetail) unset($aModelDetail[Base::$aRequest['data']['id_model_detail']]);
	
	    $oTable=new Table();
	    $oTable->sType='array';
	    $oTable->aDataFoTable=$aData;
	    $oTable->aColumn=array(
	        'id'=>array('sTitle'=>'#'),
	        'code'=>array('sTitle'=>'code'),
	        'brand'=>array('sTitle'=>'brand'),
	    );
	    $oTable->bCheckVisible=true;
	    $oTable->bDefaultChecked=false;
	    $oTable->iRowPerPage=100;
	    $oTable->sFormAction="extension_td_tree_part_copy_process";
	    $oTable->sDataTemplate='extension_td/row_tree_part_copy.tpl';
	    $oTable->sButtonTemplate='extension_td/button_tree_part_copy.tpl';
	
	    $oTable->iRowPerPage=100;
	
	    Base::$tpl->assign('aModelDetail',$aModelDetail);
	    Base::$sText.=$oTable->getTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function TreePartCopyProcess() {
	    if(Base::$aRequest['is_post'] && Base::$aRequest['id_model_detail_new']){
	        $aRows=Base::$aRequest['row_check'];
	        	
	        $aNewModelDetail=Base::$aRequest['id_model_detail_new'];
	        if($aNewModelDetail) foreach($aNewModelDetail as $iNewModelDetail) {
	            //check node exists
	            self::CheckNode(Base::$aRequest['id_tree'],$iNewModelDetail);
	
	            if ($aRows) foreach($aRows as $iId){
	                $aProduct=Db::GetRow("select * from cat_model_type_link where id='".$iId."'");
	                if($aProduct) {
	                    Db::AutoExecute("cat_model_type_link",array(
	                    "id_cat_part"=>$aProduct['id_cat_part'],
	                    "id_cat_model_type"=>$iNewModelDetail,
	                    ));
	                }
	            }
	        }
	        Base::Redirect("/?".Base::$aRequest['return']);
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function CatInfoImport(){
	    Auth::NeedAuth('manager');
	    Base::$tpl->assign("sBaseAction", $this->sPrefix."_cat_info_import");
	
	    Base::$aTopPageTemplate=array('panel/tab_extension_td_cat_info_import.tpl'=>$this->sPrefix."_cat_info_import");
	
	    Base::Message();
	
	    $aPref=array(""=>"")+Db::GetAssoc("select c.pref, c.title from cat_pref cp inner join cat c on c.id=cp.cat_id order by c.name");
	    Base::$tpl->assign("aPref",$aPref);
	    $aPrefFrom=array(""=>"")+Db::GetAssoc("Assoc/Pref",array(
	        'id_sup'=>'>0',
	        'all'=>1
	    ));
	    Base::$tpl->assign("aPrefFrom",$aPrefFrom);
	
	    /* [ apply  */
	    if (Base::$aRequest['is_post'])
	    {
	        if (is_uploaded_file($_FILES['import_file']['tmp_name'])) {
	            //if (Base::$aRequest['data']['id_user_provider'] && Base::$aRequest['data']['pref']) {
	
	            $oExcel= new Excel();
	            $oExcel->ReadExcel5($_FILES['import_file']['tmp_name'],true);
	            $oExcel->SetActiveSheetIndex();
	            $oExcel->GetActiveSheet();
	
	            $aPref=Db::GetAssoc("SELECT upper( title ) AS name, pref
				FROM cat
				UNION ALL
				SELECT upper( name ) AS name, pref
				FROM cat
				UNION ALL
				SELECT upper( cp.name ) AS name, c.pref
				FROM cat_pref AS cp
				INNER JOIN cat AS c ON c.id = cp.cat_id");
	
	            $aResult=$oExcel->GetSpreadsheetData();
	
	            if ($aResult) foreach ($aResult as $sKey=>$aValue) {
	
	                if ($sKey==1) $aKey=$aValue;
	                elseif ($sKey>1)
	                {
	                    unset($aData);
	                    foreach ($aKey as $sKey1 => $aValue1){
	                        if(isset($aData[$aValue1])) $aValue1.=' ';
	                        if(isset($aData[$aValue1])) $aValue1.=' ';
	                        if(isset($aData[$aValue1])) $aValue1.=' ';
	                        $aData[$aValue1]=mysql_escape_string($aValue[$sKey1]);
	                    }
	                    {
	                        //default file
	                        $aCat=Db::GetRow("select * from cat where pref='".$aPref[trim(mb_strtoupper($aData['brand']))]."'");
	                        $aData['brand']=$aCat['name'];
	                        $aData['pref']=$aCat['pref'];
	                        if ($aData['brand_from']) $aData['pref_from']=trim($aPref[strtoupper($aData['brand_from'])]);
	
	                        $aData['code']=Catalog::StripCode($aData['code']);
	                        $aData['code_from']=Catalog::StripCode($aData['code_from']);
	                        Db::AutoExecute("cat_info_import",$aData);
	                    }
	                }
	            }
	            //die();
	            $sMessage="&aMessage[MT_NOTICE]=Upload and processing sucsessfuly";
	            Form::Redirect("?action=".$this->sPrefix."_cat_info_import".$sMessage);
	        } else {
	
	            if (!Base::$aRequest['data']['pref'] || !Base::$aRequest['data']['code']
	                || !Base::$aRequest['data']['pref_from'] || !Base::$aRequest['data']['code_from'])
	            {
	                Base::Message(array('MF_ERROR'=>'Required fields brand and code'));
	                Base::$aRequest['action']=$this->sPrefix.'_cat_info_import_add';
	                Base::$tpl->assign('aData', Base::$aRequest['data']);
	            }
	            else
	            {
	                $aData=String::FilterRequestData(Base::$aRequest['data']);
	
	                $aData["code"]=Catalog::StripCode(strtoupper($aData["code"]));
	                $aData["code_from"]=Catalog::StripCode(strtoupper($aData["code_from"]));
	                $aData["brand"]=$aPref[$aData['pref']];
	                $aData["brand_from"]=$aPrefFrom[$aData['pref_from']];
	
	                $sDbTable="cat_info_import";
	                if (Base::$aRequest['id']) {
	                    Db::AutoExecute($sDbTable,$aData,"UPDATE","id=".Base::$aRequest['id']
	                    .(Auth::$aUser['type_']=="manager"?"":Auth::$sWhere));
	                    $sMessage="/?aMessage[MT_NOTICE]=updated";
	                }
	                else
	                {
	                    Db::AutoExecute($sDbTable,$aData);
	                    $sMessage="/?aMessage[MT_NOTICE]=added";
	                }
	
	                Base::Redirect("/pages/extension_td".$sMessage);
	                //Form::RedirectAuto($sMessage);
	            }
	        }
	    }
	    /* ] apply */
	
	
	    if (Base::$aRequest['action']==$this->sPrefix.'_cat_info_import_add' || Base::$aRequest['action']==$this->sPrefix.'_cat_info_import_edit')
	    {
	        if (Base::$aRequest['action']==$this->sPrefix.'_cat_info_import_edit') {
	            Base::$tpl->assign('aData', Db::GetRow(Base::GetSql("Cat/InfoImport",
	            array("id"=>Base::$aRequest['id']?Base::$aRequest['id']:"-1"))));
	        } elseif (Base::$aRequest['action']==$this->sPrefix.'_cat_info_import_add' && Base::$aRequest['item_code']) {
	            list($aData['pref_crs'],$aData['code_crs'])=explode('_',Base::$aRequest['item_code']);
	            Base::$tpl->assign('aData',$aData);
	        } else{
	            Base::$tpl->assign('aData',$aData);
	        }
	
	        $aData=array(
	            'sHeader'=>"method=post",
	            'sTitle'=>"Add info from part",
	            'sContent'=>Base::$tpl->fetch($this->sPrefix.'/form_cat_info_import_add.tpl'),
	            'sSubmitButton'=>'Apply',
	            'sSubmitAction'=>$this->sPrefix."_cat_info_import",
	            'sReturnButton'=>'<< Return',
	            'bAutoReturn'=>true,
	            'sWidth'=>"500px",
	        );
	        $oForm=new Form($aData);
	        Base::$sText.=$oForm->getForm();
	
	        return;
	    }
	    
	    $aData=array(
	        'sHeader'=>"method=post enctype='multipart/form-data'",
	        //'sTitle'=>"Import cross",
	        'sContent'=>Base::$tpl->fetch($this->sPrefix."/form_cat_info_import.tpl"),
	        'sSubmitButton'=>'Load',
	        'sSubmitAction'=>$this->sPrefix."_cat_info_import",
	        //'sReturnButton'=>'<< Return',
	        //'bAutoReturn'=>true,
	        'sWidth'=>"400px",
	    );
	    $oForm=new Form($aData);
	    Base::$sText.=$oForm->getForm();
	
	
	    $oTable=new Table();
	    $oTable->sSql=Base::GetSql('Cat/InfoImport');
	
	    $oTable->aColumn['brand']=array('sTitle'=>'Brand');
	    //$oTable->aColumn['pref']=array('sTitle'=>'Pref');
	    $oTable->aColumn['code']=array('sTitle'=>'Code');
	    $oTable->aColumn['brand_from']=array('sTitle'=>'Brand from');
	    //$oTable->aColumn['pref_from']=array('sTitle'=>'Pref from');
	    $oTable->aColumn['code_from']=array('sTitle'=>'Code from');
	    $oTable->aColumn['load_image']=array('sTitle'=>'load_image');
	    $oTable->aColumn['load_characteristics']=array('sTitle'=>'load_characteristics');
	    $oTable->aColumn['load_cross']=array('sTitle'=>'load_cross');
	    $oTable->aColumn['load_applicability']=array('sTitle'=>'load_applicability');
	    $oTable->aColumn['action']=array('sTitle'=>'');
	
	    //Auth::$aUser['type_']=='customer'?
	    //$oTable->aColumn['price_retail']=array('sTitle'=>'Price_retail','sWidth'=>'10%', "sOrder"=>"price_retail"):"";
	    $oTable->aOrdered="order by cii.id ";
	
	    $oTable->sDataTemplate=$this->sPrefix."/row_cat_info_import.tpl";
	    $oTable->iRowPerPage=Base::$aRequest['content']?Base::$aRequest['content']:20;
	    $oTable->sActionRowPerPage=preg_replace ( '/&content=([^&]*)/', '', $_SERVER ['QUERY_STRING']);
	
	    //$oTable->aCallback=array($this,'CallParseEbay');
	    $oTable->sButtonTemplate=$this->sPrefix."/button_cat_info_import.tpl";
	
	    Base::$sText.=$oTable->getTable();
	    Base::$aData['template']['sPageTitle']=Language::GetMessage('Cat info import module title');
	}
	//-----------------------------------------------------------------------------------------------
	public function CatInfoImportDelete($bRedirect=true)
	{
	    Auth::NeedAuth("manager");
	    if (Base::$aRequest['imported'] )
	    {
	        /*
	         */
	
	    } elseif (Base::$aRequest['id'] ){
	        Db::Execute("delete from cat_info_import where id=".Base::$aRequest['id']);
	    } else {
	        Db::Execute("TRUNCATE cat_info_import");
	    }
	
	    if ($bRedirect) Form::RedirectAuto();
	}
	//-----------------------------------------------------------------------------------------------
	public function CatInfoImportSet()
	{
	    Auth::NeedAuth("manager");
	    $aData=Db::GetAll(Base::GetSql("Cat/InfoImport"));
	    foreach ($aData as $sKey => $aValue) {
	        $this->AddCatInfo($aValue);
	    }
	    $this->CatInfoImportDelete(false);
	    if($this->aDataCatInfo)
	        foreach ($this->aDataCatInfo as $aValue) {
	            $this->AddCatInfo($aValue);
	        }
	
	    Base::Redirect("/?action=extension_td_cat_info_import&aMessage[MT_NOTICE]=Added sucsessfuly");
	}
	//-----------------------------------------------------------------------------------------------
	public function CatInfoImportSetCharacteristic()
	{
	    Auth::NeedAuth("manager");
	    $aData=Db::GetAll(Base::GetSql("Cat/InfoImport"));
	    foreach ($aData as $sKey => $aValue) {
	        $this->AddCatInfoCharacteristic($aValue);
	    }
	    $this->CatInfoImportDelete(false);
	    if($this->aDataCatInfo)
	        foreach ($this->aDataCatInfo as $aValue) {
	            $this->AddCatInfoCharacteristic($aValue);
	        }
	
	    Base::Redirect("/?action=extension_td_cat_info_import&aMessage[MT_NOTICE]=Added sucsessfuly");
	}
	//-----------------------------------------------------------------------------------------------
	public function CatInfoImportSetImage()
	{
	    Auth::NeedAuth("manager");
	    $aData=Db::GetAll(Base::GetSql("Cat/InfoImport"));
	    foreach ($aData as $sKey => $aValue) {
	        $this->AddCatInfoImage($aValue);
	    }
	    $this->CatInfoImportDelete(false);
	    if($this->aDataCatInfo) {
	        foreach ($this->aDataCatInfo as $aValue) {
	            $this->AddCatInfoImage($aValue);
	        }
	    }
	     
	    Base::Redirect("/?action=extension_td_cat_info_import&aMessage[MT_NOTICE]=Added sucsessfuly");
	}
	//-----------------------------------------------------------------------------------------------
	public function CatInfoImportSetCross()
	{
	    Auth::NeedAuth("manager");
	    $aData=Db::GetAll(Base::GetSql("Cat/InfoImport"));
	    foreach ($aData as $sKey => $aValue) {
	        $this->AddCatInfoCross($aValue);
	    }
	    $this->CatInfoImportDelete(false);
	    if($this->aDataCatInfo) {
	        foreach ($this->aDataCatInfo as $aValue) {
	            $this->AddCatInfoCross($aValue);
	        }
	    }
	
	    Base::Redirect("/?action=extension_td_cat_info_import&aMessage[MT_NOTICE]=Added sucsessfuly");
	}
	//-----------------------------------------------------------------------------------------------
	public function CatInfoImportSetApplicability()
	{
	    Auth::NeedAuth("manager");
	    $aData=Db::GetAll(Base::GetSql("Cat/InfoImport"));
	    foreach ($aData as $sKey => $aValue) {
	        $this->AddCatInfoApplicability($aValue);
	    }
	    $this->CatInfoImportDelete(false);
	    if($this->aDataCatInfo) {
	        foreach ($this->aDataCatInfo as $aValue) {
	            $this->AddCatInfoApplicability($aValue);
	        }
	    }
	     
	    Base::Redirect("/?action=extension_td_cat_info_import&aMessage[MT_NOTICE]=Added sucsessfuly");
	}
	//-----------------------------------------------------------------------------------------------
	public function HistoryImage() {
	     
	    Auth::NeedAuth('manager');
	    Base::$aTopPageTemplate=array('panel/tab_extension_td_cat_info_import.tpl'=>$this->sPrefix."_history_image");
	     
	    $oForm= new Form();
	    $oForm->sContent=Base::$tpl->fetch("extension_td/form_history_search.tpl");
	    $oForm->sSubmitButton="Search";
	    $oForm->sSubmitAction=$this->sPrefix."_history_image";
	    $oForm->sReturnButton="Clear";
	    $oForm->bIsPost=0;
	    $oForm->sWidth="800px";
	    Base::$sText.=$oForm->getForm();
	    Base::$sText.="<br>";
	
	    $sWhere='';
	    //---------------------------------------------------------------------------------------------
	    if(Base::$aRequest['search']['code']) {
	        $sWhere.=" and cp.code like '%".trim(Base::$aRequest['search']['code'])."%' ";
	    }
	    if(Base::$aRequest['search']['brand']) {
	        $sWhere.=" and cp.pref in (select pref from cat where name like '%".Base::$aRequest['search']['brand']."%' or title like '%".Base::$aRequest['search']['brand']."%' ) ";
	    }
	    //---------------------------------------------------------------------------------------------
	     
	    $oTable=new Table();
	    $oTable->sSql="select cat_pic.id, c.title as brand, cp.code, cat_pic.image, c.name as c_name
	        from cat_pic
	        inner join cat_part as cp on cat_pic.id_cat_part=cp.id
	        inner join cat as c on cp.pref=c.pref
	        where 1=1".$sWhere;
	    $oTable->aColumn=array(
	        'id'=>array('sTitle'=>'#id'),
	        'brand'=>array('sTitle'=>'brand'),
	        'code'=>array('sTitle'=>'code'),
	        'image'=>array('sTitle'=>'image'),
	        'action'=>array(),
	    );
	    $oTable->bCheckVisible=false;
	    $oTable->bDefaultChecked=false;
	    $oTable->bFormAvailable=true;
	    $oTable->iRowPerPage=100;
	    $oTable->sDataTemplate='extension_td/row_history_image.tpl';
	    $oTable->bCheckVisible=true;
	    $oTable->bCheckAllVisible=true;
	    $oTable->sButtonTemplate='extension_td/button_image.tpl';
	     
	    $oTable->iRowPerPage=100;
	    Base::$sText.=$oTable->GetTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function HistoryCharacteristic() {	     
	    Auth::NeedAuth('manager');
	    Base::$aTopPageTemplate=array('panel/tab_extension_td_cat_info_import.tpl'=>$this->sPrefix."_history_characteristic");
	
	    $oForm= new Form();
	    $oForm->sContent=Base::$tpl->fetch("extension_td/form_history_search.tpl");
	    $oForm->sSubmitButton="Search";
	    $oForm->sSubmitAction=$this->sPrefix."_history_characteristic";
	    $oForm->sReturnButton="Clear";
	    $oForm->bIsPost=0;
	    $oForm->sWidth="800px";
	    Base::$sText.=$oForm->getForm();
	    Base::$sText.="<br>";
	     
	    $sWhere='';
	    //---------------------------------------------------------------------------------------------
	    if(Base::$aRequest['search']['code']) {
	        $sWhere.=" and cp.code like '%".trim(Base::$aRequest['search']['code'])."%' ";
	    }
	    if(Base::$aRequest['search']['brand']) {
	        $sWhere.=" and cp.pref in (select pref from cat where name like '%".Base::$aRequest['search']['brand']."%' or title like '%".Base::$aRequest['search']['brand']."%' ) ";
	    }
	    //---------------------------------------------------------------------------------------------
	     
	    $oTable=new Table();
	    $oTable->sSql="select cat_info.id, c.title as brand, cp.code, cat_info.name as crit_name, cat_info.code as crit_value, c.name as c_name
	        from cat_info
	        inner join cat_part as cp on cat_info.id_cat_part=cp.id
	        inner join cat as c on cp.pref=c.pref
	        where 1=1 ".$sWhere;
	    $oTable->aColumn=array(
	        'id'=>array('sTitle'=>'#id'),
	        'brand'=>array('sTitle'=>'brand'),
	        'code'=>array('sTitle'=>'code'),
	        'crit_name'=>array('sTitle'=>'crit_name'),
	        'crit_value'=>array('sTitle'=>'crit_value'),
	        'action'=>array(),
	    );
	    $oTable->bCheckVisible=false;
	    $oTable->bDefaultChecked=false;
	    $oTable->bFormAvailable=true;
	    $oTable->iRowPerPage=100;
	    $oTable->sDataTemplate='extension_td/row_history_characteristic.tpl';
	    $oTable->bCheckVisible=true;
	    $oTable->bCheckAllVisible=true;
	    $oTable->sButtonTemplate='extension_td/button_characteristics.tpl';
	     
	    $oTable->iRowPerPage=100;
	    Base::$sText.=$oTable->GetTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function HistoryCross() {	     
	    Auth::NeedAuth('manager');
	    Base::$aTopPageTemplate=array('panel/tab_extension_td_cat_info_import.tpl'=>$this->sPrefix."_history_cross");
	
	    $oForm= new Form();
	    $oForm->sContent=Base::$tpl->fetch("extension_td/form_history_search.tpl");
	    $oForm->sSubmitButton="Search";
	    $oForm->sSubmitAction=$this->sPrefix."_history_cross";
	    $oForm->sReturnButton="Clear";
	    $oForm->bIsPost=0;
	    $oForm->sWidth="800px";
	    Base::$sText.=$oForm->getForm();
	    Base::$sText.="<br>";
	     
	    $sWhere='';
	    //---------------------------------------------------------------------------------------------
	    if(Base::$aRequest['search']['code']) {
	        $sWhere.=" and cc.code_crs like '%".trim(Base::$aRequest['search']['code'])."%' ";
	    }
	    if(Base::$aRequest['search']['brand']) {
	        $sWhere.=" and cc.pref_crs in (select pref from cat where name like '%".Base::$aRequest['search']['brand']."%' or title like '%".Base::$aRequest['search']['brand']."%' ) ";
	    }
	    //---------------------------------------------------------------------------------------------
	     
	    $oTable=new Table();
	    $oTable->sSql="select cc.id, cc.code_crs as code, c.title as brand, c1.title as cross_brand, c.name as c_name, cc.code as cross_code
    	    from cat_cross as cc
    	    inner join cat as c on cc.pref_crs = c.pref
    	    inner join cat as c1 on cc.pref = c1.pref /*and c1.is_brand=1*/
    	    where 1=1".$sWhere;
	    $oTable->aColumn=array(
	        'id'=>array('sTitle'=>'#id'),
	        'brand'=>array('sTitle'=>'brand'),
	        'code'=>array('sTitle'=>'code'),
	        'cross_brand'=>array('sTitle'=>'cross_brand'),
	        'cross_code'=>array('sTitle'=>'cross_code'),
	        'action'=>array(),
	    );
	    $oTable->bCheckVisible=false;
	    $oTable->bDefaultChecked=false;
	    $oTable->bFormAvailable=true;
	    $oTable->iRowPerPage=100;
	    $oTable->sDataTemplate='extension_td/row_history_cross.tpl';
	    $oTable->bCheckVisible=true;
	    $oTable->bCheckAllVisible=true;
	    $oTable->sButtonTemplate='extension_td/button_cross.tpl';
	
	    $oTable->iRowPerPage=100;
	    Base::$sText.=$oTable->GetTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function HistoryApplicability() {
	    Auth::NeedAuth('manager');
	    Base::$aTopPageTemplate=array('panel/tab_extension_td_cat_info_import.tpl'=>$this->sPrefix."_history_applicability");
	     
	    $oForm= new Form();
	    $oForm->sContent=Base::$tpl->fetch("extension_td/form_history_applicability_search.tpl");
	    $oForm->sSubmitButton="Search";
	    $oForm->sSubmitAction=$this->sPrefix."_history_applicability";
	    $oForm->sReturnButton="Clear";
	    $oForm->bIsPost=0;
	    $oForm->sWidth="800px";
	    Base::$sText.=$oForm->getForm();
	    Base::$sText.="<br>";
	     
	    if(Base::$aRequest['search']['model']) {
	        $this->aModelTmp=TecdocDb::GetAssoc("select cat_alt_types.id_src
    		, cat_alt_types.Description as name
            FROM ".DB_OCAT."cat_alt_types
            inner join ".DB_OCAT."cat_alt_models on cat_alt_models.ID_mod = cat_alt_types.ID_mod
        	inner join ".DB_OCAT."cat_alt_manufacturer on cat_alt_models.ID_mfa=cat_alt_manufacturer.ID_mfa
            where 1=1 and cat_alt_types.Description like '%".trim(Base::$aRequest['search']['model'])."%' ");
	    }
	
	    $sWhere='';
	    //---------------------------------------------------------------------------------------------
	    if(Base::$aRequest['search']['code']) {
	        $sWhere.=" and cp.code like '%".trim(Base::$aRequest['search']['code'])."%' ";
	    }
	    if(Base::$aRequest['search']['brand']) {
	        $sWhere.=" and cp.pref in (select pref from cat where name like '%".Base::$aRequest['search']['brand']."%' or title like '%".Base::$aRequest['search']['brand']."%' ) ";
	    }
	    if(Base::$aRequest['search']['model']) {
	        $sWhere.=" and cmt.id_cat_model_type in ('".implode("','", array_keys($this->aModelTmp))."') ";
	    }
	    //---------------------------------------------------------------------------------------------
	     
	    $oTable=new Table();
	    $sSql="select cmt.id, c.title as brand, cp.code, c.name as c_name, cmt.id_cat_model_type
	        from cat_model_type_link as cmt
	        inner join cat_part as cp on cmt.id_cat_part=cp.id
	        inner join cat as c on cp.pref=c.pref
	        where 1=1".$sWhere;
	    $oTable->sSql=$sSql;
	    $oTable->aColumn=array(
	        'id'=>array('sTitle'=>'#id','sOrder'=>'cmt.id'),
	        'brand'=>array('sTitle'=>'brand'),
	        'code'=>array('sTitle'=>'code'),
	        'model_name'=>array('sTitle'=>'model_name','sOrder'=>'cmt.id_cat_model_type'),
	        'action'=>array(),
	    );
	    $oTable->bCheckVisible=false;
	    $oTable->bDefaultChecked=false;
	    $oTable->bFormAvailable=true;
	    $oTable->iRowPerPage=100;
	    $oTable->sDataTemplate='extension_td/row_history_applicability.tpl';
	    $oTable->aCallback=array($this,'CallParseApplicability');
	    $oTable->bCheckVisible=true;
	    $oTable->bCheckAllVisible=true;
	    $oTable->sButtonTemplate='extension_td/button_applicability.tpl';
	     
	    $oTable->iRowPerPage=100;
	    Base::$sText.=$oTable->GetTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseApplicability(&$aItem) {
	    if($aItem) {
	        if(!$this->aModelTmp) {
	            $aTypes=array();
	            foreach ($aItem as $sKey => $aValue) {
	                $aTypes[]=$aValue['id_cat_model_type'];
	            }
	            $this->aModelTmp=TecdocDb::GetAssoc("select cat_alt_types.id_src
            		, cat_alt_types.Description as name
                    FROM ".DB_OCAT."cat_alt_types
                    inner join ".DB_OCAT."cat_alt_models on cat_alt_models.ID_mod = cat_alt_types.ID_mod
                	inner join ".DB_OCAT."cat_alt_manufacturer on cat_alt_models.ID_mfa=cat_alt_manufacturer.ID_mfa
                    where 1=1 and cat_alt_types.id_src in ('".implode("','", $aTypes)."') ");
	        }
	         
	        foreach ($aItem as $sKey => $aValue) {
	            $aItem[$sKey]['model_name']=$this->aModelTmp[$aValue['id_cat_model_type']];
	        }
	         
	        if(Base::$aRequest['order']=='model_name') {
	            usort($aItem, function ($a, $b)
	            {
	                if ($a['model_name'] == $b['model_name']) {
	                    return 0;
	                }
	                if(Base::$aRequest['way']=='asc') return ($a['model_name'] < $b['model_name']) ? -1 : 1;
	                else return ($a['model_name'] > $b['model_name']) ? -1 : 1;
	            });
	        }
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function HistoryImageDelete() {
	    if(Base::$aRequest['id']) {
	        Db::Execute("delete from cat_pic where id='".Base::$aRequest['id']."' ");
	    }
	    if(Base::$aRequest['row_check']) {
	        foreach (Base::$aRequest['row_check'] as $iId) {
	            Db::Execute("delete from cat_pic where id='".$iId."' ");
	        }
	    }
	    Base::Redirect("?".Base::$aRequest['return']);
	}
	//-----------------------------------------------------------------------------------------------
	public function HistoryCharacteristicDelete() {
	    if(Base::$aRequest['id']) {
	        Db::Execute("delete from cat_info where id='".Base::$aRequest['id']."' ");
	    }
	    if(Base::$aRequest['row_check']) {
	        foreach (Base::$aRequest['row_check'] as $iId) {
	            Db::Execute("delete from cat_info where id='".$iId."' ");
	        }
	    }
	    Base::Redirect("?".Base::$aRequest['return']);
	}
	//-----------------------------------------------------------------------------------------------
	public function HistoryCrossDelete() {
	    if(Base::$aRequest['id']) {
	        Db::Execute("delete from cat_cross where id='".Base::$aRequest['id']."' ");
	    }
	    if(Base::$aRequest['row_check']) {
	        foreach (Base::$aRequest['row_check'] as $iId) {
	            Db::Execute("delete from cat_cross where id='".$iId."' ");
	        }
	    }
	    Base::Redirect("?".Base::$aRequest['return']);
	}
	//-----------------------------------------------------------------------------------------------
	public function HistoryApplicabilityDelete() {
	    if(Base::$aRequest['id']) {
	        Db::Execute("delete from cat_model_type_link where id='".Base::$aRequest['id']."' ");
	    }
	    if(Base::$aRequest['row_check']) {
	        foreach (Base::$aRequest['row_check'] as $iId) {
	            Db::Execute("delete from cat_model_type_link where id='".$iId."' ");
	        }
	    }
	    Base::Redirect("?".Base::$aRequest['return']);
	}
	//-----------------------------------------------------------------------------------------------
	public function HistoryTree() {
	    Auth::NeedAuth('manager');
	    Base::$aTopPageTemplate=array('panel/tab_extension_td_cat_info_import.tpl'=>$this->sPrefix."_history_tree");
	
	    $oForm= new Form();
	    $oForm->sContent=Base::$tpl->fetch("extension_td/form_history_tree_search.tpl");
	    $oForm->sSubmitButton="Search";
	    $oForm->sSubmitAction=$this->sPrefix."_history_tree";
	    $oForm->sReturnButton="Clear";
	    $oForm->bIsPost=0;
	    $oForm->sWidth="800px";
	    Base::$sText.=$oForm->getForm();
	    Base::$sText.="<br>";
	     
	    if(Base::$aRequest['search']['tree']) {
	        $this->aTree=TecdocDb::GetAssoc("select
	            t.ID_src ,
                CONCAT('[',t.ID_src,'] ',UCASE(MID(t.Name,1,1)),MID(t.Name,2)) as name
                from ".DB_OCAT."cat_alt_tree t
                where 1=1 and CONCAT('[',t.ID_src,'] ',UCASE(MID(t.Name,1,1)),MID(t.Name,2)) like '%".trim(Base::$aRequest['search']['tree'])."%'
            ");
	    }
	     
	    if(Base::$aRequest['search']['group']) {
	        $this->aGroup=TecdocDb::GetAssoc("select
    	        grp.id_src, grp.Name as name
    	        from ".DB_OCAT."cat_alt_groups as grp
    	        where grp.Name like '%".trim(Base::$aRequest['search']['group'])."%'
            ");
	    }
	
	    $sWhere='';
	    //---------------------------------------------------------------------------------------------
	    if(Base::$aRequest['search']['code']) {
	        $sWhere.=" and cp.code like '%".trim(Base::$aRequest['search']['code'])."%' ";
	    }
	    if(Base::$aRequest['search']['brand']) {
	        $sWhere.=" and cp.pref in (select pref from cat where name like '%".Base::$aRequest['search']['brand']."%' or title like '%".Base::$aRequest['search']['brand']."%' ) ";
	    }
	    if(Base::$aRequest['search']['tree']) {
	        $sWhere.=" and ctl.id_tree in ('".implode("','", array_keys($this->aTree))."') ";
	    }
	    if(Base::$aRequest['search']['group']) {
	        $sWhere.=" and ctl.id_group in ('".implode("','", array_keys($this->aGroup))."') ";
	    }
	    //---------------------------------------------------------------------------------------------
	
	    $oTable=new Table();
	    $oTable->sSql="select ctl.id, c.title as brand, cp.code, c.name as c_name, ctl.id_tree, ctl.id_group
	        from cat_tree_link as ctl
	        inner join cat_part as cp on ctl.id_cat_part=cp.id
	        inner join cat as c on cp.pref=c.pref
	        where 1=1".$sWhere;
	    $oTable->aColumn=array(
	        'id'=>array('sTitle'=>'#id','sOrder'=>'ctl.id'),
	        'brand'=>array('sTitle'=>'brand'),
	        'code'=>array('sTitle'=>'code'),
	        'tree'=>array('sTitle'=>'tree','sOrder'=>'ctl.id_tree'),
	        'group'=>array('sTitle'=>'group','sOrder'=>'ctl.id_group'),
	        'action'=>array(),
	    );
	    $oTable->bCheckVisible=false;
	    $oTable->bDefaultChecked=false;
	    $oTable->bFormAvailable=true;
	    $oTable->iRowPerPage=100;
	    $oTable->sDataTemplate='extension_td/row_history_tree.tpl';
	    $oTable->aCallback=array($this,'CallParseTree');
	    $oTable->bCheckVisible=true;
	    $oTable->bCheckAllVisible=true;
	    $oTable->sButtonTemplate='extension_td/button_tree.tpl';
	
	    $oTable->iRowPerPage=100;
	    Base::$sText.=$oTable->GetTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseTree(&$aItem) {
	    if($aItem) {
	        foreach ($aItem as $sKey => $aValue) {
	            if(!$this->aTree) {
	                $aTree=array();
	                foreach ($aItem as $sKey => $aValue) {
	                    $aTree[]=$aValue['id_tree'];
	                }
	                 
	                $this->aTree=TecdocDb::GetAssoc("
    	                select t.ID_src ,
                            CONCAT('[',t.ID_src,'] ',UCASE(MID(t.Name,1,1)),MID(t.Name,2)) as name
                        from ".DB_OCAT."cat_alt_tree t
                        where 1=1 and t.ID_src in ('".implode("','", $aTree)."')
	                ");
	            }
	            if(!$this->aGroup) {
	                $aGroup=array();
	                foreach ($aItem as $sKey => $aValue) {
	                    $aGroup[]=$aValue['id_group'];
	                }
	                 
	                $this->aGroup=TecdocDb::GetAssoc("select
            	        grp.id_src, grp.Name as name
            	        from ".DB_OCAT."cat_alt_groups as grp
            	        where grp.id_src in ('".implode("','", $aGroup)."')
    	            ");
	            }
	
	        }
	         
	        foreach ($aItem as $sKey => $aValue) {
	            $aItem[$sKey]['tree']=$this->aTree[$aValue['id_tree']];
	            $aItem[$sKey]['group']=$this->aGroup[$aValue['id_group']];
	        }
	         
	        if(Base::$aRequest['order']=='tree') {
	            usort($aItem, function ($a, $b)
	            {
	                if ($a['tree'] == $b['tree']) {
	                    return 0;
	                }
	                if(Base::$aRequest['way']=='asc') return ($a['tree'] < $b['tree']) ? -1 : 1;
	                else return ($a['tree'] > $b['tree']) ? -1 : 1;
	            });
	        }
	        if(Base::$aRequest['order']=='group') {
	            usort($aItem, function ($a, $b)
	            {
	                if ($a['group'] == $b['group']) {
	                    return 0;
	                }
	                if(Base::$aRequest['way']=='asc') return ($a['group'] < $b['group']) ? -1 : 1;
	                else return ($a['group'] < $b['group']) ? 1 : -1;
	            });
	        }
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function HistoryTreeDelete() {
	    if(Base::$aRequest['id']) {
	        Db::Execute("delete from cat_tree_link where id='".Base::$aRequest['id']."' ");
	    }
	    if(Base::$aRequest['row_check']) {
	        foreach (Base::$aRequest['row_check'] as $iId) {
	            Db::Execute("delete from cat_tree_link where id='".$iId."' ");
	        }
	    }
	    Base::Redirect("?".Base::$aRequest['return']);
	}
	//-----------------------------------------------------------------------------------------------
	public function AddCatInfo($aData=array())
	{
	    set_time_limit(0);
	
	    //check load data
	    if($aData['load_characteristics']) $this->AddCatInfoCharacteristic($aData);
	    if($aData['load_image']) $this->AddCatInfoImage($aData);
	    if($aData['load_cross']) $this->AddCatInfoCross($aData);
	    if($aData['load_applicability']) $this->AddCatInfoApplicability($aData);
	}
	//-----------------------------------------------------------------------------------------------
	public function AddCatInfoCharacteristic($aData)
	{
	    set_time_limit(0);
	    if(!$aData['pref'] || !$aData['code'] || !$aData['code_from'] || !$aData['pref_from']) return false;
	
	    $aPartInfo=TecdocDb::GetPartInfo(array(
	        'sCode'=>$aData['code'],
	        'pref'=>$aData['pref']
	    ));
	
	    if(!$aPartInfo['id_cat_part']) {
	        $iIdCatPart=Db::GetOne("select id from cat_part where item_code='".$aData['pref']."_".$aData['code']."'");
	        if(!$iIdCatPart) {
	            Db::Execute("
					insert ignore into cat_part (item_code, pref, code )
        			values ('".$aData['pref']."_".$aData['code']."','".$aData['pref']."','".$aData['code']."')
				");
	            $iIdCatPart=Db::GetOne("select id from cat_part where item_code='".$aData['pref']."_".$aData['code']."'");
	        }
	        $aPartInfo['id_cat_part']=$iIdCatPart;
	    }
	
	    $aPartInfoFrom=TecdocDb::GetPartInfo(array(
	        'sCode'=>$aData['code_from'],
	        'pref'=>$aData['pref_from']
	    ));
	
	    if(!$aPartInfoFrom['id_cat_part']) {
	        $aPartInfoFrom['id_cat_part']=Db::GetOne("select id from cat_part where item_code='".$aData['pref_from']."_".$aData['code_from']."'");
	    }
	
	    $aCriteriaFrom=TecdocDb::GetCriterias(array(
	        'aId'=>array($aPartInfoFrom['art_id']),
	        'aIdCatPart'=>array($aPartInfoFrom['id_cat_part']),
	        "type_"=>"all"
	    ));
	
	    if($aCriteriaFrom) {
	        foreach ($aCriteriaFrom as $aValue) {
	            $aValue=Db::Escape($aValue);
	            Db::Execute("
					insert ignore into cat_info (id_cat_part, name, code)
					values ('".$aPartInfo['id_cat_part']."','".$aValue['krit_name']."','".$aValue['krit_value']."')
				");
	        }
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function AddCatInfoImage($aData) {
	    set_time_limit(0);
	    if(!$aData['pref'] || !$aData['code'] || !$aData['code_from'] || !$aData['pref_from']) return false;
	     
	    $aPartInfo=TecdocDb::GetPartInfo(array(
	        'sCode'=>$aData['code'],
	        'pref'=>$aData['pref']
	    ));
	     
	    if(!$aPartInfo['id_cat_part']) {
	        $iIdCatPart=Db::GetOne("select id from cat_part where item_code='".$aData['pref']."_".$aData['code']."'");
	        if(!$iIdCatPart) {
	            Db::Execute("
					insert ignore into cat_part (item_code, pref, code )
        			values ('".$aData['pref']."_".$aData['code']."','".$aData['pref']."','".$aData['code']."')
				");
	            $iIdCatPart=Db::GetOne("select id from cat_part where item_code='".$aData['pref']."_".$aData['code']."'");
	        }
	        $aPartInfo['id_cat_part']=$iIdCatPart;
	    }
	     
	    $aPartInfoFrom=TecdocDb::GetPartInfo(array(
	        'sCode'=>$aData['code_from'],
	        'pref'=>$aData['pref_from']
	    ));
	     
	    if(!$aPartInfoFrom['id_cat_part']) {
	        $aPartInfoFrom['id_cat_part']=Db::GetOne("select id from cat_part where item_code='".$aData['pref_from']."_".$aData['code_from']."'");
	    }
	     
	    $aImageFrom=TecdocDb::GetImages(array(
	        'aIdGraphic'=>array($aPartInfoFrom['art_id']),
	        'aIdCatPart'=>array($aPartInfoFrom['id_cat_part']),
	    ));
	     
	    if($aImageFrom) {
	        foreach ($aImageFrom as $aValue) {
	            $aFilePart = pathinfo($aValue['img_path']);
	            $aValue=Db::Escape($aValue);
	            Db::Execute("
					insert ignore into cat_pic (id_cat_part, image, pic, extension, width)
					values ('".$aPartInfo['id_cat_part']."','".$aValue['img_path']."','".$aFilePart['filename']."'
					,'".$aFilePart['extension']."','".$aValue['img_width']."')
				");
	        }
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function AddCatInfoCross($aData) {
	    set_time_limit(0);
	    if(!$aData['pref'] || !$aData['code'] || !$aData['code_from'] || !$aData['pref_from']) return false;
	
	    $aPartInfo=TecdocDb::GetPartInfo(array(
	        'sCode'=>$aData['code'],
	        'pref'=>$aData['pref']
	    ));
	
	    if(!$aPartInfo['id_cat_part']) {
	        $iIdCatPart=Db::GetOne("select id from cat_part where item_code='".$aData['pref']."_".$aData['code']."'");
	        if(!$iIdCatPart) {
	            Db::Execute("
					insert ignore into cat_part (item_code, pref, code )
        			values ('".$aData['pref']."_".$aData['code']."','".$aData['pref']."','".$aData['code']."')
				");
	            $iIdCatPart=Db::GetOne("select id from cat_part where item_code='".$aData['pref']."_".$aData['code']."'");
	        }
	        $aPartInfo['id_cat_part']=$iIdCatPart;
	    }
	
	    $aPartInfoFrom=TecdocDb::GetPartInfo(array(
	        'sCode'=>$aData['code_from'],
	        'pref'=>$aData['pref_from']
	    ));
	
	    if(!$aPartInfoFrom['id_cat_part']) {
	        $aPartInfoFrom['id_cat_part']=Db::GetOne("select id from cat_part where item_code='".$aData['pref_from']."_".$aData['code_from']."'");
	    }
	     
	    if($aPartInfo && $aPartInfoFrom) {
	      $aCross['pref']=$aPartInfo['pref']; 
	      $aCross['code']=$aPartInfo['code']; 
	      $aCross['pref_crs']=$aPartInfoFrom['pref'];
	      $aCross['code_crs']=$aPartInfoFrom['code'];
	      $this->InsertCross($aCross);
	    }   
	     
	    $aOriginalCodeFrom=TecdocDb::GetOriginals(array(
	        'art_id'=>$aPartInfoFrom['art_id'],
	        'aIdCatPart'=>array($aPartInfoFrom['id_cat_part']),
	        'pref'=>$aPartInfoFrom['pref'],
	        'code'=>$aPartInfoFrom['code'],
	    ));
	
	    if($aOriginalCodeFrom) {
	        foreach ($aOriginalCodeFrom as $aValue) {
	            $aValue["code"]=$aData['code'];
	            $aValue['pref']=$aData['pref'];
	
	            $aValue["code_crs"]=Catalog::StripCode(strtoupper($aValue["number"]));
	            $aValue['pref_crs']=Db::GetOne("select pref from cat where name='".$aValue['name']."'");
	
	            $this->InsertCross($aValue);
	        }
	    }
	
	    $aCrossFrom=TecdocDb::GetCross(array(
	        'sCode'=>"'".$aPartInfoFrom['code']."'",
	        'pref'=>$aPartInfoFrom['pref']
	    ));
	
	    if($aCrossFrom) {
	        foreach ($aCrossFrom as $aValue) {
	            $aValue["code"]=$aData['code'];
	            $aValue['pref']=$aData['pref'];
	
	            list($aValue['pref_crs'],$aValue["code_crs"])=explode("_", $aValue['item_code_crs']);
	
	            $this->InsertCross($aValue);
	        }
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function AddCatInfoApplicability($aData) {
	    set_time_limit(0);
	    if(!$aData['pref'] || !$aData['code'] || !$aData['code_from'] || !$aData['pref_from']) return false;
	
	    $aPartInfo=TecdocDb::GetPartInfo(array(
	        'sCode'=>$aData['code'],
	        'pref'=>$aData['pref']
	    ));
	
	    if(!$aPartInfo['id_cat_part']) {
	        $iIdCatPart=Db::GetOne("select id from cat_part where item_code='".$aData['pref']."_".$aData['code']."'");
	        if(!$iIdCatPart) {
	            Db::Execute("
					insert ignore into cat_part (item_code, pref, code )
        			values ('".$aData['pref']."_".$aData['code']."','".$aData['pref']."','".$aData['code']."')
				");
	            $iIdCatPart=Db::GetOne("select id from cat_part where item_code='".$aData['pref']."_".$aData['code']."'");
	        }
	        $aPartInfo['id_cat_part']=$iIdCatPart;
	    }
	
	    $aPartInfoFrom=TecdocDb::GetPartInfo(array(
	        'sCode'=>$aData['code_from'],
	        'pref'=>$aData['pref_from']
	    ));
	
	    if(!$aPartInfoFrom['id_cat_part']) {
	        $aPartInfoFrom['id_cat_part']=Db::GetOne("select id from cat_part where item_code='".$aData['pref_from']."_".$aData['code_from']."'");
	    }
	     
	    if(!$aPartInfoFrom['code'] && $aPartInfoFrom['item_code']) {
	        $sPrefTmp=0;
	        $sCodeTmp=0;
	        list($sPrefTmp,$sCodeTmp)=explode("_", $aPartInfoFrom['item_code']);
	        $aPartInfoFrom['code']=$sCodeTmp;
	    }
	    $aApplicabilityFrom=TecdocDb::GetApplicability(array(
	        'code'=>Catalog::StripCode($aPartInfoFrom['code']),
	        'art_id'=>$aPartInfoFrom['art_id'],
	        'pref'=>$aPartInfoFrom['pref'],
	        'catalog_manager'=>1
	    ));
	     
	    if($aApplicabilityFrom) {
	        foreach ($aApplicabilityFrom as $aValue) {
	            Db::Execute("insert ignore into cat_model_type_link (id_cat_model_type, id_cat_part)
				values ('".$aValue['id_model_detail']."','".$aPartInfo['id_cat_part']."')");
	
	            if($aPartInfoFrom['art_id']) {
	                $aIdTree=TecdocDb::GetAssoc($sSqlTree="select lsg.ID_tree,grp.id_src as ID_grp
                        FROM ".DB_OCAT."cat_alt_link_typ_art lta
                        join ".DB_OCAT."cat_alt_types t on lta.ID_typ=t.ID_typ and t.id_src='".$aValue['id_model_detail']."'
                        join ".DB_OCAT."cat_alt_link_str_grp lsg on lsg.ID_grp=lta.ID_grp
    	                join ".DB_OCAT."cat_alt_groups as grp on grp.id_grp=lsg.ID_grp
                        join ".DB_OCAT."cat_alt_articles a on a.ID_art=lta.ID_art
                        where a.id_src='".$aPartInfoFrom['art_id']."'
    	            ");
	            } else {
	                $iIdTofOe=Db::GetOne("select id_mfa from cat where pref='".$aPartInfoFrom['pref']."' ");
	                $aIdTree=TecdocDb::GetAssoc($sSqlTree="select lsg.ID_tree,grp.id_src as ID_grp
                        FROM ".DB_OCAT."cat_alt_link_typ_art lta
                        join ".DB_OCAT."cat_alt_types t on lta.ID_typ=t.ID_typ and t.id_src='".$aValue['id_model_detail']."'
                        join ".DB_OCAT."cat_alt_link_str_grp lsg on lsg.ID_grp=lta.ID_grp
    	                join ".DB_OCAT."cat_alt_groups as grp on grp.id_grp=lsg.ID_grp
                        join ".DB_OCAT."cat_alt_articles a on a.ID_art=lta.ID_art
	                join ".DB_OCAT."cat_alt_original as o on o.id_art=a.id_art
                        where o.oe_code='".$aPartInfoFrom["code"]."' and o.oe_brand='".$iIdTofOe."'
    	            ");
	            }
	             
	            if($aIdTree) {
	                foreach ($aIdTree as $iIdTree => $iIdGrp) {
	                    Db::Execute("insert ignore into cat_tree_link (id_tree, id_cat_part, id_group)
				            values ('".$iIdTree."','".$aPartInfo['id_cat_part']."', '".$iIdGrp."')");
	                }
	            }
	        }
	    }
	}
	//-----------------------------------------------------------------------------------------------
	private function InsertCross($aData) {
	    static $sPrefMers;
	
	    if (!$sPrefMers)
		$sPrefMers = Db::GetOne("SELECT pref FROM `cat` WHERE id_mfa = ".Language::getConstant("mercedes:id_src_tecdoc",74)); // MERCEDES || MERCEDESBENZ
	
	    if ($aData['pref'] && $aData['code'] && $aData['pref_crs'] && $aData['code_crs']
	        && !($aData['code']==$aData['code_crs'] && $aData['pref']==$aData['pref_crs'])
	    ) {
	        if( (preg_match('/^A[0-9]{10}$/', $aData['code']) || preg_match('/^A[0-9]{11}$/', $aData['code'])
	            || preg_match('/^A[0-9]{12}$/', $aData['code'])) && $sPrefMers == $aData['pref'])
	                $aData['code'] = ltrim($aData['code'],'A');
	
	            if( (preg_match('/^A[0-9]{10}$/', $aData['code_crs']) || preg_match('/^A[0-9]{11}$/', $aData['code_crs'])
	                || preg_match('/^A[0-9]{12}$/', $aData['code_crs'])) && $sPrefMers == $aData['pref_crs'])
	                    $aData['code_crs'] = ltrim($aData['code_crs'],'A');
	
	                Db::Execute(" insert ignore into cat_cross (pref, code, pref_crs, code_crs, source, id_manager)
					values ('".$aData['pref']."','".$aData['code']."','".$aData['pref_crs']."','".$aData['code_crs']."','".$aData['source']."','".Auth::$aUser['id_user']."')
					, ('".$aData['pref_crs']."','".$aData['code_crs']."','".$aData['pref']."','".$aData['code']."','".$aData['source']."','".Auth::$aUser['id_user']."')
					");
	                return true;
	    } else {
	        return false;
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function CheckNode($iIdTree,$iIdTyp) {
	    //check tree node enable
	    if(!TecdocDb::GetOne("SELECT link.ID_tree
			FROM ".DB_OCAT."`cat_alt_link_typ_art` ltyp
			INNER JOIN ".DB_OCAT."cat_alt_link_str_grp link ON link.`ID_grp` = ltyp.`ID_grp`
			INNER JOIN ".DB_OCAT."cat_alt_types t on ltyp.`ID_typ`=t.ID_typ and  t.ID_src='".$iIdTyp."' and link.ID_tree='".$iIdTree."'
			GROUP BY link.ID_tree"))
	    {
	        //not in tecdoc,check additional table
	        if(!Db::GetOne("select id from cat_tree_type_link where id_typ='".$iIdTyp."' and id_tree='".$iIdTree."' ")) {
	            Db::AutoExecute("cat_tree_type_link",array(
	            "id_tree"=>$iIdTree,
	            "id_typ"=>$iIdTyp
	            ));
	        }
	    }
	
	    //check parent node
	    $iParentNode=TecdocDb::GetOne("SELECT ID_parent FROM ".DB_OCAT."cat_alt_tree WHERE `ID_tree` = '".$iIdTree."' ");
	    if($iParentNode) {
	        self::CheckNode($iParentNode, $iIdTyp);
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function SetSelectedPart(&$aTree=array(), $iPart) {
	    if(!$aTree[$iPart] || !$iPart) return;
	    $aTree[$iPart]['selected']=1;
	    if ($iPart=='10001') return;
	    $this->SetSelectedPart($aTree, $aTree[$iPart]['str_id_parent']);
	}
	//-----------------------------------------------------------------------------------------------
	public function Parse($aCat,&$sCode){
	    if($aCat['parser_patern'])
	        $sCode=trim(preg_replace('/.*?('.$aCat['parser_patern'].').*?/','\1',$sCode));
	    if($aCat['parser_before']){
	        $sCode=trim(preg_replace('/^('.$aCat['parser_before'].')(.*)/','\2',$sCode));
	    }
	    if($aCat['trim_left_by']){
	        $iPos=strpos($sCode,$aCat['trim_left_by']);
	        if($iPos!==FALSE) $sCode=substr($sCode,$iPos+1);
	    }
	    if($aCat['trim_right_by']){
	        $iPos=strpos($sCode,$aCat['trim_right_by']);
	        if($iPos!==FALSE) $sCode=substr($sCode,0,$iPos);
	    }	
	}
	//-----------------------------------------------------------------------------------------------
	public function TreeRubricDelete() {
	    if(Base::$aRequest['row_check']) {
	        foreach (Base::$aRequest['row_check'] as $iIdRow) {
	            Db::Execute("delete from cat_tree_link where id='".$iIdRow."' ");
	        }
	         
	        Base::Redirect("/?action=extension_td_tree_rubric&data[id_model_detail]=".Base::$aRequest['data']['id_model_detail']."&data[id_rubric]=".Base::$aRequest['data']['id_rubric']
	            ."&amessage[mf_notice]=Выбранные элементы удалены");
	    } else {
	        Base::Redirect("/?action=extension_td_tree_rubric&data[id_model_detail]=".Base::$aRequest['data']['id_model_detail']."&data[id_rubric]=".Base::$aRequest['data']['id_rubric']
	            ."&amessage[mf_error]=Нет выбранных элементов для удаления");
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function TreeRubricExport () {
	    $aRubric=Db::GetRow("select * from rubricator where id='".Base::$aRequest['data']['id_rubric']."' ");
	     
	    $sSql="select c.title as brand, cp.code, if(cp.name<>'',cp.name,p.part_rus) as name
				from cat_model_type_link cmtl
				inner join cat_part as cp on cp.id=cmtl.id_cat_part
				inner join cat as c on c.pref=cp.pref
				inner join cat_tree_link as ctl on ctl.id_cat_part=cmtl.id_cat_part and ctl.id_tree in (".$aRubric['id_tree'].") and ctl.id_group in (".$aRubric['id_group'].")
				left join price as p on p.item_code=cp.item_code
				where cmtl.id_cat_model_type='".Base::$aRequest['data']['id_model_detail']."'
				group by cmtl.id
		";
	    $aParts=Db::GetAll($sSql);
	     
	    $oExcel = new Excel();
	    $aHeader=array(
	        'A'=>array("value"=>'id_model_detail', "autosize"=>true),
	        'B'=>array("value"=>'id_rubric', "autosize"=>true),
	        'C'=>array("value"=>'brand', "autosize"=>true),
	        'D'=>array("value"=>'code', "autosize"=>true),
	        'E'=>array("value"=>'name', "autosize"=>true),
	    );
	    $oExcel->SetHeaderValue($aHeader,1);
	    $oExcel->SetAutoSize($aHeader);
	    $oExcel->DuplicateStyleArray("A1:E1");
	     
	    $i=$j=2;
	    foreach ($aParts as $aValue)
	    {
	        $oExcel->setCellValue('A'.$i, Base::$aRequest['data']['id_model_detail']);
	        $oExcel->setCellValue('B'.$i, $aRubric['id']);
	        $oExcel->setCellValue('C'.$i, $aValue['brand']);
	        $oExcel->setCellValue('D'.$i, $aValue['code']);
	        $oExcel->setCellValue('E'.$i, $aValue['name']);
	         
	        $i++;
	    }
	    //end
	    $sFileName=uniqid().'.xls';
	    $oExcel->WriterExcel5(SERVER_PATH.'/imgbank/temp_upload/'.$sFileName, true);
	}
	//-----------------------------------------------------------------------------------------------
	public function TreeRubricImport () {
	    if(Base::$aRequest['is_post']) {
	        set_time_limit(0);
	
	        if (is_uploaded_file($_FILES['import_file']['tmp_name']))
	        {
	            $aPref=Db::GetAssoc("select UPPER(cp.name),c.pref from cat_pref cp inner join cat c on c.id=cp.cat_id");
	            $aPrefName=Base::$db->getAssoc("select id,name from cat_pref");
	
	            $oExcel= new Excel();
	            $ext = pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION);
	            if ($ext == 'xls')
	                $oExcel->ReadExcel5($_FILES['import_file']['tmp_name'],true);
	            else
	                $oExcel->ReadExcel7($_FILES['import_file']['tmp_name'],true);
	
	            $oExcel->SetActiveSheetIndex();
	            $oExcel->GetActiveSheet();
	             
	            $aResult=$oExcel->GetSpreadsheetData();
	             
	            if ($aResult) {
	                foreach ($aResult as $key=>$value) {
	                    unset($u);
	                     
	                    $u['pref']=strtoupper($value[3]);
	                    if (in_array($u['pref'],$aPrefName)) {
	                         
	                        $aRubric=Db::GetRow("select * from rubricator where id='".$value[2]."' ");
	                        $aTree=explode(",", $aRubric['id_tree']);
	                        $aGroup=explode(",", $aRubric['id_group']);
	                         
	                        $u['pref']=$aPref[$u['pref']];
	                        $u['code']=Catalog::StripCode($value[4]);
	                        $u['item_code']=$u['pref']."_".$u['code'];
	                         
	                        //----------------------------------------------
	                        $iCatPartId=Db::GetOne("select id from cat_part where item_code = '".$u['item_code']."' ");
	                        if(!$iCatPartId) {
// 	                            Db::AutoExecute("cat_part", array(
// 	                                'item_code'=>$u['item_code'],
// 	                                'code'=>$u['code'],
// 	                                'pref'=>$u['pref']
// 	                            ));
	                            Db::Execute("insert ignore into cat_part (item_code,code,pref) values
	                                ('".$u['item_code']."','".$u['code']."','".$u['pref']."')");
	                            $iCatPartId=Db::GetOne("select id from cat_part where item_code = '".$u['item_code']."' ");
	                        }
	                         
// 	                        Db::AutoExecute("cat_model_type_link", array(
// 	                            'id_cat_model_type'=>$value[1],
// 	                            'id_cat_part'=>$iCatPartId
// 	                        ));
	                        Db::Execute("insert ignore into cat_model_type_link (id_cat_model_type,id_cat_part) values
	                            ('".$value[1]."','".$iCatPartId."')");
	                         
	                        if($aRubric['id_price_group']) {
// 	                            Db::AutoExecute("price_group_assign", array(
// 	                                'item_code'=>$u['item_code'],
// 	                                'pref'=>Base::$aRequest['data']['pref'],
// 	                                'id_price_group'=>$aRubric['id_price_group']
// 	                            ));
	                            Db::Execute("insert ignore into price_group_assign (item_code,pref,id_price_group) values 
	                                ('".$u['item_code']."','".Base::$aRequest['data']['pref']."','".$aRubric['id_price_group']."') ");
	                        }
	                         
// 	                        Db::AutoExecute("cat_tree_link",array(
// 	                            "id_cat_part"=>$iCatPartId,
// 	                            "id_tree"=>$aTree[0],
// 	                            "id_group"=>$aGroup[0]
// 	                        ));
                            Db::Execute("insert ignore into cat_tree_link (id_cat_part,id_tree,id_group) values 
                                ('".$iCatPartId."','".$aTree[0]."','".$aGroup[0]."') ");
	                        //----------------------------------------------
	                    }
	                }
	            }
	        }
	         
	        Base::Redirect("/?action=extension_td_tree_rubric&data[id_model_detail]=".Base::$aRequest['data']['id_model_detail']."&data[id_rubric]=".Base::$aRequest['data']['id_rubric']);
	    }
	     
	    $aData=array(
	        'sHeader'=>"method=post enctype='multipart/form-data'",
	        'sContent'=>Base::$tpl->fetch('extension_td/import.tpl'),
	        'sSubmitButton'=>'Import',
	        'sSubmitAction'=>'extension_td_tree_rubric_import',
	        'sError'=>$sError,
	    );
	    $oForm=new Form($aData);
	    Base::$sText.=$oForm->getForm();
	}
	//-----------------------------------------------------------------------------------------------
	public function TreeRubric()
	{
	    Base::$tpl->assign('bLeftMenu',false);
	    $this->TreeAuto();
	    $aRubric=Db::GetRow("select * from rubricator where id='".Base::$aRequest['data']['id_rubric']."' ");
	    Base::$sText.="<table width=\"100%\" class=\"datatable\">
	        <tr>
	           <th><a href='/pages/extension_td_tree?data[id_model_detail]=".Base::$aRequest['data']['id_model_detail']."'><< Назад</a></th>
	           <th>Выбранная рубрика: ".$aRubric['name']."</th>
           </tr></table><br>";
	     
	    if(!Base::$aRequest['data']['id_model_detail'] || !Base::$aRequest['data']['id_rubric']) {
	        Base::$sText.="error: model detail is null";
	        return;
	    }
	     
	    //-------------------------------------------------------------------
	     
	    if(Base::$aRequest['is_post']) {
	        if(Base::$aRequest['subaction']=='add') {
	            if(Base::$aRequest['data']['id_rubric'] && Base::$aRequest['data']['id_model_detail'] && Base::$aRequest['data']['pref'] && Base::$aRequest['data']['code']) {
	                $aTree=explode(",", $aRubric['id_tree']);
	                $aGroup=explode(",", $aRubric['id_group']);
	
	                $iCatPartId=Db::GetOne("select id from cat_part where item_code = '".(Base::$aRequest['data']['pref']."_".Base::$aRequest['data']['code'])."' ");
	                if(!$iCatPartId) {
	                    Db::AutoExecute("cat_part", array(
	                        'item_code'=>(Base::$aRequest['data']['pref']."_".Base::$aRequest['data']['code']),
	                        'code'=>Base::$aRequest['data']['code'],
	                        'pref'=>Base::$aRequest['data']['pref']
	                    ));
	                    $iCatPartId=Db::GetOne("select id from cat_part where item_code = '".(Base::$aRequest['data']['pref']."_".Base::$aRequest['data']['code'])."' ");
	                }
	
	                Db::AutoExecute("cat_model_type_link", array(
	                    'id_cat_model_type'=>Base::$aRequest['data']['id_model_detail'],
	                    'id_cat_part'=>$iCatPartId
	                ));
	
	                if($aRubric['id_price_group']) {
	                    Db::AutoExecute("price_group_assign", array(
	                        'item_code'=>(Base::$aRequest['data']['pref']."_".Base::$aRequest['data']['code']),
	                        'pref'=>Base::$aRequest['data']['pref'],
	                        'id_price_group'=>$aRubric['id_price_group']
	                    ));
	                }
	
	                Db::AutoExecute("cat_tree_link",array(
	                    "id_cat_part"=>$iCatPartId,
	                    "id_tree"=>$aTree[0],
	                    "id_group"=>$aGroup[0]
	                ));
	
	                Base::Redirect("/?action=extension_td_tree_rubric&data[id_model_detail]=".Base::$aRequest['data']['id_model_detail']."&data[id_rubric]=".Base::$aRequest['data']['id_rubric']);
	            } else {
	                Base::Redirect("/?action=extension_td_tree_rubric&data[id_model_detail]=".Base::$aRequest['data']['id_model_detail']."&data[id_rubric]=".Base::$aRequest['data']['id_rubric']
	                    ."&amessage[mf_error]=Не правильно заполненные данные");
	            }
	        }
	
	        if(Base::$aRequest['subaction']=='delete') {
	            Db::Execute("delete from cat_tree_link where id='".Base::$aRequest['id']."' ");
	            Base::Redirect("/pages/extension_td_tree_rubric?data[id_model_detail]=".Base::$aRequest['data']['id_model_detail']."&data[id_rubric]=".Base::$aRequest['data']['id_rubric']);
	        }
	    }
	
	    //-------------------------------------------------------------------
	     
	    $aBrands=Db::GetAssoc("select distinct c.pref, c.title 
                    	        from cat_pref as cp
                    	        inner join cat as c on c.id=cp.cat_id
                    	        order by c.title");
	    Base::$tpl->assign("aBrands",array(''=>'')+$aBrands);
	     
	    $aData=array(
	        'sHeader'=>"method=get",
	        'sContent'=>Base::$tpl->fetch('extension_td/form_rubric_add_detail.tpl'),
	        'sSubmitButton'=>'Add',
	        'sSubmitAction'=>'extension_td_tree_rubric',
	        // 				'sAdditionalButtonTemplate'=>'extension_td/button_tree_add_detail.tpl',
	        'bIsPost'=>1,
	    );
	    $oForm=new Form($aData);
	    Base::$sText.=$oForm->GetForm();
	    Base::$sText.="<hr>";
	     
	    //-------------------------------------------------------------------
	     
	    $oTable=new Table();
	    $oTable->sSql="select c.title as brand, cp.code, ctl.id, ctl.id_group, if(cp.name<>'',cp.name,p.part_rus) as name
				from cat_model_type_link cmtl
				inner join cat_part as cp on cp.id=cmtl.id_cat_part
				inner join cat as c on c.pref=cp.pref
				inner join cat_tree_link as ctl on ctl.id_cat_part=cmtl.id_cat_part and ctl.id_tree in (".$aRubric['id_tree'].") and ctl.id_group in (".$aRubric['id_group'].")
				left join price as p on p.item_code=cp.item_code
				where cmtl.id_cat_model_type='".Base::$aRequest['data']['id_model_detail']."'
				group by cmtl.id
				    order by cp.code
				";
	    $oTable->aColumn['brand']=array('sTitle'=>'Brand','sWidth'=>'10%');
	    $oTable->aColumn['code']=array('sTitle'=>'Code','sWidth'=>'20%');
	    $oTable->aColumn['name']=array('sTitle'=>'name');
	    $oTable->aColumn['action']=array();
	     
	    $oTable->bFormAvailable=false;
	    $oTable->bStepperVisible=1;
	    $oTable->iRowPerPage=100;
	    $oTable->bCheckVisible=true;
	    $oTable->bCheckAllVisible=true;
	    $oTable->bFormAvailable=true;
	    $oTable->bDefaultChecked=false;
	    $oTable->sDataTemplate='extension_td/row_tree_rubricator.tpl';
	    $oTable->sButtonTemplate='extension_td/button_tree_rubricator.tpl';
	     
	    Base::$sText.=$oTable->getTable();
	}
	//-----------------------------------------------------------------------------------------------
}