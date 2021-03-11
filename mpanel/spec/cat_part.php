<?
require_once(SERVER_PATH . '/class/core/Admin.php');

class ACatPart extends Admin
{

    //-----------------------------------------------------------------------------------------------
    function ACatPart()
    {
        $this->sTableName = 'cat_part';
        $this->sTablePrefix = 'cp';
        $this->sTableId = 'id';
        $this->sAction = 'cat_part';
        $this->sWinHead = Language::getDMessage('Parts parameters');
        $this->sPath = Language::GetDMessage('>>Auto catalog >');
        $this->aCheckField = array('code');
        
        $this->aUniqueField = array('item_code');

        $this->sBeforeAddMethod = 'BeforeAdd';
        $this->Admin();
    }

    //-----------------------------------------------------------------------------------------------
    public function Index()
    {
        $this->PreIndex();

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong',0)) {
                if ($this->aSearch['id'])$this->sSearchSQL .= " and cp.id = '".$this->aSearch['id']."'";
                if ($this->aSearch['pref'])	$this->sSearchSQL .= " and cp.pref = '".$this->aSearch['pref']."'";
                if ($this->aSearch['brand'])	$this->sSearchSQL .= " and cp.brand = '".$this->aSearch['brand']."'";
                if ($this->aSearch['code'])	$this->sSearchSQL .= " and cp.code = '".$this->aSearch['code']."'";
                if ($this->aSearch['name_rus'])	$this->sSearchSQL .= " and cp.name_rus = '".$this->aSearch['name_rus']."'";
            }
            else {
                if ($this->aSearch['id'])$this->sSearchSQL .= " and cp.id like '%".$this->aSearch['id']."%'";
                if ($this->aSearch['pref'])	$this->sSearchSQL .= " and cp.pref like '%".$this->aSearch['pref']."%'";
                if ($this->aSearch['brand'])	$this->sSearchSQL .= " and cp.brand like '%".$this->aSearch['brand']."%'";
                if ($this->aSearch['code'])	$this->sSearchSQL .= " and cp.code like '%".$this->aSearch['code']."%'";
                if ($this->aSearch['name_rus'])	$this->sSearchSQL .= " and cp.name_rus like '%".$this->aSearch['name_rus']."%'";
            }
        }

        require_once(SERVER_PATH . '/class/core/Table.php');
        $oTable = new Table();
        if (Language::getConstant('use_price_control', 0)) {
            $oTable->aColumn = array(
                'id' => array('sTitle' => Language::getDMessage('Id'), 'sOrder' => 'cp.id', 'sWidth' => 5, 'sMethod' => 'exact'),
                'pref' => array('sTitle' => Language::getDMessage('Pref'), 'sOrder' => 'cp.pref', 'sWidth' => 10),
                'brand' => array('sTitle' => 'Brand', 'sWidth' => '10%', 'sOrder' => 'cp.title'),
                'code' => array('sTitle' => Language::getDMessage('Code'), 'sOrder' => 'cp.code', 'sWidth' => 10),
                'name' => array('sTitle' => 'Name price', 'sWidth' => '40%'),
                'name_rus' => array('sTitle' => 'Name Rus', 'sOrder' => 'cp.name_rus', 'sWidth' => '40%'),
                'weight' => array('sTitle' => Language::getDMessage('Weight'), 'sOrder' => 'cp.weight', 'sWidth' => 10),
                //'size_name'=>array('sTitle'=>Language::getDMessage('Size Name'), 'sOrder'=>'cp.size_name', 'sWidth'=>10),
                'is_checked_code_ok' => array('sTitle' => 'is_checked_code_ok', 'sOrder' => 'cp.is_checked_code_ok', 'sWidth' => 10),
                'is_checked_code_ok_date' => array('sTitle' => 'is_checked_code_ok_date', 'sOrder' => 'cp.is_checked_code_ok_date', 'sWidth' => 10),
                'is_checked_code_ok_manager' => array('sTitle' => 'is_checked_code_ok_manager', 'sOrder' => 'cp.is_checked_code_ok_manager', 'sWidth' => 10),
                'is_checked_code_ok_admin' => array('sTitle' => 'is_checked_code_ok_admin', 'sOrder' => 'cp.is_checked_code_ok_admin', 'sWidth' => 10),
                'action' => array()
            );
            $oTable->aCallbackAfter = array($this, 'CallParseAfter');
        } else {
            $oTable->aColumn = array(
                'id' => array('sTitle' => Language::getDMessage('Id'), 'sOrder' => 'cp.id', 'sWidth' => 5, 'sMethod' => 'exact'),
                'brand' => array('sTitle' => 'Brand', 'sWidth' => '10%', 'sOrder' => 'cp.title'),
                'pref' => array('sTitle' => Language::getDMessage('Pref'), 'sOrder' => 'cp.pref', 'sWidth' => 10),
                'code' => array('sTitle' => Language::getDMessage('Code'), 'sOrder' => 'cp.code', 'sWidth' => 10),
                'item_code' => array('sTitle' => Language::getDMessage('item_code'), 'sOrder' => 'cp.item_code', 'sWidth' => 10),
                'name_rus' => array('sTitle' => Language::getDMessage('Name Rus'), 'sOrder' => 'cp.name_rus', 'sWidth' => '40%'),
                'weight' => array('sTitle' => Language::getDMessage('Weight'), 'sOrder' => 'cp.weight', 'sWidth' => 10),
                'action' => array()
            );
        }

        $this->SetDefaultTable($oTable);

        $oTable->bCheckVisible = false;
        $oTable->bCacheStepper = true;

        //$oTable->sSql=Base::GetSql('CatPart',array('where'=>$sWhere ));

        Base::$sText .= $oTable->getTable();

        $this->AfterIndex();
    }

    //-----------------------------------------------------------------------------------------------
    public function BeforeAdd()
    {
        Base::$tpl->assign('aPref', Base::$db->getAssoc("select pref, concat(pref,' ',title) as name from cat order by name"));
    }

    //-----------------------------------------------------------------------------------------------
    public function BeforeAddAssign(&$aData)
    {
        $sName = Db::getOne("Select part_rus from price where item_code = '" . $aData['item_code'] . "' and part_rus!='' order by id desc");
        if ($sName)
            $aData['name'] = $sName;
        
        Base::$tpl->assign("aPriceGroup2",$aPriceGroup2=array(""=>"")+Db::GetAssoc("
			select  id , name FROM price_group"));
        
        Base::$tpl->assign("aselectPrice",$aselectPrice=Db::GetOne("
			select  id_price_group
			FROM price_group_assign where item_code like '".$aData['item_code']."'"));
        
        $aData['id_price_group']=$aselectPrice;
        
        $this->GetKriterias($aData);
        $this->GetImages($aData);
        $this->GetRubric($aData);
        $this->GetFilters($aData);
    }
    //-----------------------------------------------------------------------------------------------
    public function BeforeApply()
    {
        Base::$aRequest['data']['code'] = Catalog::StripCode(Base::$aRequest['data']['code']);
        Base::$aRequest['data']['item_code'] = Base::$aRequest['data']['pref'] . "_" . Base::$aRequest['data']['code'];
        Base::$aRequest['data']['weight'] = str_replace(',', '.', Base::$aRequest['data']['weight']);
        if (!Base::$aRequest['data']['name_rus']) {
            Base::$aRequest['data']['name_rus'] = DB::GetOne("select part_rus from price where item_code='" . Base::$aRequest['data']['item_code'] . "' and part_rus!='' order by id desc");
        }
        
        if (isset(Base::$aRequest['data']['id_price_group']) && Base::$aRequest['data']['item_code'])
            Db::Execute("insert into price_group_assign
				(id_price_group, item_code, pref) values
				('".Base::$aRequest['data']['id_price_group']."','".Base::$aRequest['data']['item_code']."','".Base::$aRequest['data']['pref']."')
			    on duplicate key update id_price_group=values(id_price_group)");
    }

    //-----------------------------------------------------------------------------------------------
    public function CallParseAfter(&$aItem)
    {
        if (!$aItem)
            return;

        $aItemCode = array();
        foreach ($aItem as $aValue) {
            if (!$aItemCode[$aValue['item_code']])
                $aItemCode[$aValue['item_code']] = 1;
        }
        if ($aItemCode) {
            $aData = Db::getAssoc("Select item_code, part_rus from price where item_code in ('" . implode("','", array_keys($aItemCode)) . "') and part_rus!='' group by item_code order by id desc");
            if ($aData) {
                foreach ($aItem as $iKey => $aValue) {
                    if ($aData[$aValue['item_code']])
                        $aItem[$iKey]['name'] = $aData[$aValue['item_code']];
                }
            }
        }
    }

    //-----------------------------------------------------------------------------------------------
    public function AfterApply($aBeforeRow, $aAfterRow)
    {
        if (Language::getConstant('use_price_control', 0)) {
            if ($aAfterRow['is_checked_code_ok'] != $aBeforeRow['is_checked_code_ok']) {
                Base::$db->Execute("update cat_part set is_checked_code_ok_date='" . date("Y-m-d H:i:s") . "', is_checked_code_ok_manager=0, is_checked_code_ok_admin=" . $this->aAdmin['id'] . " where id=" . $aAfterRow['id']);
            }
        }
    }
    //-----------------------------------------------------------------------------------------------
    public function Sync()
    {
        Db::Execute("insert ignore into cat_part (item_code, code, pref) 
            select item_code, code, pref
            from price 
        ");
		$this->AdminRedirect ( $this->sAction );
    }
    //-----------------------------------------------------------------------------------------------
    public function GetKriterias($aData)
    {
        if(!$aData['item_code']) return;
        
        $aPartInfo=TecdocDb::GetPartInfo(array(
            'item_code'=>$aData['item_code']
        ));
        if ($aPartInfo) {
            if (!$aPartInfo['art_id']) {
                $sArtId = Catalog::GetArtId($aPartInfo['item_code']);
                if ($sArtId)
                    $aPartInfo['art_id'] = $sArtId;
            }
             
            if ($aPartInfo['art_id'] && !$aData['art_id'])
                $aData['art_id']=$aPartInfo['art_id'];
        }
        
        $aCriterias = TecdocDb::GetCriterias(array(
            'aId'=>array($aData['art_id']),
            'aIdCatPart'=>array($aPartInfo['id_cat_part']),
            "type_"=>"all_edit"
        ));
        
        Base::$tpl->assign("aCriterias", $aCriterias);
    }
    //-----------------------------------------------------------------------------------------------
    public function AddKrit()
    {
        $aRequestData=String::FilterRequestData(Base::$aRequest);
        
        if($aRequestData['input_krit_name'] && $aRequestData['input_krit_value']) {
            $aData=array(
                'id_cat_part'=>$aRequestData['id_cat_part'],
                'name'=>$aRequestData['input_krit_name'],
                'code'=>$aRequestData['input_krit_value']
            );
            Db::AutoExecute("cat_info",$aData);
        }
        
        $this->GetKriterias(array(
            'item_code'=>$aRequestData['input_item_code']
        ));
        
        Base::$oResponse->AddAssign('table_krit','outerHTML',Base::$tpl->fetch('mpanel/cat_part/table_krit.tpl'));
    }
    //-----------------------------------------------------------------------------------------------
    public function DelKrit()
    {
        Db::Execute("delete from cat_info where id='".Base::$aRequest['id_cat_info']."' ");
        
        $this->GetKriterias(array(
            'item_code'=>Base::$aRequest['input_item_code']
        ));
        
        Base::$oResponse->AddAssign('table_krit','outerHTML',Base::$tpl->fetch('mpanel/cat_part/table_krit.tpl'));
    }
    //-----------------------------------------------------------------------------------------------
    public function GetImages($aData)
    {
        if(!$aData['item_code']) return;
        
        $aPartInfo=TecdocDb::GetPartInfo(array(
            'item_code'=>$aData['item_code']
        ));
        if ($aPartInfo) {
            if (!$aPartInfo['art_id']) {
                $sArtId = Catalog::GetArtId($aPartInfo['item_code']);
                if ($sArtId)
                    $aPartInfo['art_id'] = $sArtId;
            }
             
            if ($aPartInfo['art_id'] && !$aData['art_id'])
                $aData['art_id']=$aPartInfo['art_id'];
        }
        
        $aGraphic=TecdocDb::GetImages(array(
            'aIdGraphic'=>array($aData['art_id']),
            'aIdCatPart'=>array($aPartInfo['id_cat_part']),
        ),false,false);
        
        Base::$tpl->assign('aGraphic',$aGraphic);
    }
    //-----------------------------------------------------------------------------------------------
    public function AddImage()
    {
        $aRequestData=String::FilterRequestData(Base::$aRequest);
        
        if($aRequestData['image_input']) {
            $aFilePart = pathinfo(SERVER_PATH.$aRequestData['image_input']);
            
            $aData=array(
                'id_cat_part'=>$aRequestData['id_cat_part'],
                'image'=>$aRequestData['image_input'],
                'num'=>'0',
                'extension'=>$aFilePart['extension'],
                'pic'=>$aFilePart['filename']
            );
            Db::AutoExecute("cat_pic",$aData);
        }
        
        $this->GetImages(array(
            'item_code'=>$aRequestData['input_item_code']
        ));
        
        Base::$oResponse->AddAssign('table_image','outerHTML',Base::$tpl->fetch('mpanel/cat_part/table_image.tpl'));
    }
    //-----------------------------------------------------------------------------------------------
    public function DelImage()
    {
        Db::Execute("delete from cat_pic where id='".Base::$aRequest['id_cat_pic']."' ");
    
        $this->GetImages(array(
            'item_code'=>Base::$aRequest['input_item_code']
        ));
    
        Base::$oResponse->AddAssign('table_image','outerHTML',Base::$tpl->fetch('mpanel/cat_part/table_image.tpl'));
    }
    //-----------------------------------------------------------------------------------------------
    public function GetFilters($aData)
    {
        //LNB-57 show price group characteristic begin
        $aFilter = array();
        
        $sSql="select h.id,h.table_,h.name as krit_name from handbook as h
			inner join price_group_filter as pgf on pgf.id_handbook=h.id and pgf.id_price_group='".$aData['id_price_group']."'

			union all

			select h.id,h.table_,h.name as krit_name from handbook as h
			inner join rubricator_filter as rbf on rbf.id_handbook=h.id and rbf.id_rubricator='".$aData['id_rubric']."'
			";
        $aFilter=Db::GetAll($sSql);
        
        if ($aFilter) foreach ($aFilter as $sKey => $aValue) {
            $iPriceFilterId = Db::GetOne("select id_" . $aValue['table_'] . " from price_group_param where item_code='" . $aData['item_code'] . "'");
        
            $sValue = Db::GetOne("select name from " . $aValue['table_'] . " where id='" . $iPriceFilterId . "' and visible=1");
        
            $aFilter[$sKey]['krit_value'] = $sValue;
            $aParams = Db::GetAssoc("select id,name from " . $aValue['table_'] . " where visible=1 order by name");
            if ($aParams) {
                $aFilter[$sKey]['params'] = array("0" => "не выбрано") + Db::GetAssoc("select id,name from " . $aValue['table_'] . " where visible=1 order by name");
            } else {
                $aFilter[$sKey]['params'] = array("0" => "не выбрано");
            }
    
            $aFilter[$sKey]['id'] = $aValue['id'];
            $aFilter[$sKey]['krit_selected'] = $iPriceFilterId;
            $aFilter[$sKey]['table_'] = $aValue['table_'];
        }
        if ($aFilter) sort($aFilter);
        //LNB-57 end
        
        Base::$tpl->assign('aFilter',$aFilter);
    }
    //-----------------------------------------------------------------------------------------------
    public function GetRubric(&$aData)
    {
        if(!$aData['item_code']) return;
        
        $aPartInfo=TecdocDb::GetPartInfo(array(
            'item_code'=>$aData['item_code']
        ));
        if ($aPartInfo) {
            if (!$aPartInfo['art_id']) {
                $sArtId = Catalog::GetArtId($aPartInfo['item_code']);
                if ($sArtId)
                    $aPartInfo['art_id'] = $sArtId;
            }
             
            if ($aPartInfo['art_id'] && !$aData['art_id'])
                $aData['art_id']=$aPartInfo['art_id'];
        }
        
        $aTree = TecdocDb::GetAssoc("
            select lsg.ID_tree,g.id_src
                FROM " . DB_OCAT . "cat_alt_link_art lta
                join " . DB_OCAT . "cat_alt_link_str_grp lsg on lsg.ID_grp=lta.ID_grp
                join " . DB_OCAT . "cat_alt_groups as g on lsg.ID_grp=g.id_grp
                join " . DB_OCAT . "cat_alt_articles a on a.ID_art=lta.ID_art
                join " . DB_OCAT . "cat_alt_suppliers s on lta.ID_sup=s.ID_sup
                where a.id_src = '" . $aPartInfo['art_id'] . "' and s.ID_src = " . $aPartInfo['id_sup'].
            "
            union all
    
            select lsg.ID_tree,g.id_src
                FROM " . DB_OCAT_TRUCK . "cat_alt_link_art lta
                join " . DB_OCAT_TRUCK . "cat_alt_link_str_grp lsg on lsg.ID_grp=lta.ID_grp
                join " . DB_OCAT_TRUCK . "cat_alt_groups as g on lsg.ID_grp=g.id_grp
                join " . DB_OCAT_TRUCK . "cat_alt_articles a on a.ID_art=lta.ID_art
                join " . DB_OCAT_TRUCK . "cat_alt_suppliers s on lta.ID_sup=s.ID_sup
                where a.id_src = '" . $aPartInfo['art_id'] . "' and s.ID_src = " . $aPartInfo['id_sup']);
        
        if ($aTree) {
            $aWhere = '';
            foreach ($aTree as $iTree => $iGrp) {
                $aWhere[] = "(FIND_IN_SET('" . $iTree . "',id_tree) and FIND_IN_SET('" . $iGrp . "',id_group))";
            }
        
            $aRubricLast = Db::GetRow("
                select distinct * from rubricator
                where " . implode(" or \n", $aWhere)
            );
            
            $aData['id_rubric']=$aRubricLast['id'];
        }
    }
    //-----------------------------------------------------------------------------------------------
    public function ChangeFilter() {
        $aRequestData=String::FilterRequestData(Base::$aRequest);
        
        if ($aRequestData['param_id'] && isset($aRequestData['param_value']) && $aRequestData['item_code']) {
            Db::Execute("insert into price_group_param (item_code," . $aRequestData['param_id'] . ") 
                values ('" . $aRequestData['item_code'] . "','" . $aRequestData['param_value'] . "')
			    on duplicate key
			    update " . $aRequestData['param_id'] . "=values(" . $aRequestData['param_id'] . ")  ");
        }
    }
    //-----------------------------------------------------------------------------------------------
}

?>
