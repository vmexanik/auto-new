<?php

/**
 * @author Vmexanik
 *
 * tecdoc filter
 */

Class ATecdocFilter extends Admin
{

    /**
     * @var array
     *
     * input array of form data
     */
    public $aInputData = array();

    /**
     * @var string
     *
     * js to add to the page
     */
    public $sScriptEnd = "$(\"select[name='data[rubric]']\").select2();";

    /**
     * @constant string
     *
     * work mode replace by assign xajax
     */
    const REPLACE = 0;

    /**
     * @constant string
     *
     * work mode return text
     */
    const TEXT = 1;

    private $aCat;

    function __construct()
    {
        $this->sWinHead=Language::GetDMessage('TecDoc Filter');
        $this->sPath = Language::GetDMessage('>>Catalog >');
        $this->Admin();
    }

    //------------------------------------------------------------------------------------------------------------------
    function Index()
    {
        $this->PreIndex();

        $this->aInputData = Base::$aRequest['data'];

        //router
        $this->TecDocFilterRouter();

        $aRubric = array('0'=>Language::GetDMessage('select rubric'))+DB::GetAssoc(Base::GetSql('Assoc/Rubricator', array('visible' => 1, 'where' => ' and r.level=3')));

        Base::$tpl->Assign('aRubric', $aRubric);

        Base::$sText .= Base::$tpl->Fetch('mpanel/tecdoc_filter/inner.tpl');

        $this->AfterIndex();

        if ($this->sScriptEnd)
            Base::$oResponse->AddScript($this->sScriptEnd);
    }

    //------------------------------------------------------------------------------------------------------------------
    public function TecDocFilterRouter()
    {
        switch (Base::$aRequest['subaction']) {

            case 'select_rubric':
                $this->SelectRubric();
                break;

            case 'save':
                $this->Save();
                break;

            case 'unbind_filter':
                $this->UnbindFilter();
                break;

            case 'delete_filter':
                $this->DeleteFilter();
                break;

            case 'edit_filter':
                $this->EditFilter();
                break;
        }
    }

    //------------------------------------------------------------------------------------------------------------------
    public function SelectRubric()
    {
        Base::$tpl->Assign('iRubricSelected', $this->aInputData['selected_index']);
        $aSelectedRubric = DB::GetRow(Base::GetSql('Rubricator', array('where' => ' and r.id=' . $this->aInputData['selected_index'])));

        $aHandbook = Base::$db->GetAll('select * from handbook');
        Base::$tpl->assign('aHandbook', $aHandbook);

        $aPriceGroupFilter = Base::$db->GetAll("select * from rubricator_filter
			where id_rubricator='" . $aSelectedRubric['id'] . "'");

        $aSelectedHandbook = array();
        if ($aPriceGroupFilter)
            foreach ($aPriceGroupFilter as $key => $value) {
                $aSelectedHandbook[$value['id_handbook']] = $value['id_handbook'];
            }
        Base::$tpl->assign('aSelectedHandbook', $aSelectedHandbook);

        $aCritArray = $this->GetRubricCrit($this->aInputData['selected_index']);

        Base::$tpl->assign('aCritNames', $aCritArray);

        if (Base::$aRequest['subaction'] == 'select_rubric') {
            $sFilter = $this->GetFilter(array(), 0, self::TEXT);
            $sAddFilterDiv = Base::$tpl->fetch('mpanel/tecdoc_filter/add_filter_div.tpl');
            Base::$tpl->Assign('sFilter', $sFilter . $sAddFilterDiv);
            Base::$tpl->Assign('sSubaction', 'save');
        }

        $this->sScriptEnd = file_get_contents("../libp/mpanel/js/tecdoc_filter.js");
    }

    //------------------------------------------------------------------------------------------------------------------
    public function Save()
    {
        if ($this->aInputData['id_rubric'] && !Base::$aRequest['edit']){
            $this->ProcessHandbook($this->aInputData['handbook']);

            Base::$aRequest['subaction'] ='select_rubric';
            $this->aInputData['selected_index'] = $this->aInputData['id_rubric'];
            $this->SelectRubric();
            Base::$tpl->Assign('sMessage',Language::GetDMessage('successfully created'));
        }
        elseif (Base::$aRequest['edit'])
            $this->SaveEdited(Base::$aRequest);
    }

    //------------------------------------------------------------------------------------------------------------------
    public function ProcessHandbook($handbook)
    {
        foreach ($handbook as $key => $value) {
            $value['table_'] = strtolower(Content::Translit($value['name']));
            $sSql = "SHOW TABLES LIKE '" . $value['table_'] . "'";
            $oNewTable = Db::GetRow($sSql);
            if ($oNewTable) {
                $value['table_'] = $value['table_'] . "_hb";
            }
            $sSql = "CREATE TABLE IF NOT EXISTS `" . $value['table_'] . "` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL DEFAULT '',
            `visible` int(11) NOT NULL DEFAULT '1',
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
            Db::Execute($sSql);

            $sSql = "ALTER TABLE `price_group_param` ADD `id_" . $value['table_'] . "` INT( 11 ) NOT NULL DEFAULT '0'";
            Db::Execute($sSql);

            $sSql = "INSERT IGNORE INTO handbook (name,table_,is_collapsed) VALUES ('" . $value['name'] . "','" . $value['table_'] . "',0)";
            Db::Execute($sSql);

            $iIdHandbook = DB::InsertId();

            $sSql = "INSERT IGNORE INTO rubricator_filter (id_rubricator,id_handbook) VALUES ('" . $this->aInputData['id_rubric'] . "','" . $iIdHandbook . "')";
            DB::Execute($sSql);

            $this->ProcessHandbookParam($this->aInputData['param'][$key], $value['table_']);
        }
    }

    //------------------------------------------------------------------------------------------------------------------
    public function ProcessHandbookParam($aParam, $sTable)
    {
        require_once 'hbparams_editor.php';
        AHbparamsEditor::CreateMap($sTable);
        foreach ($aParam as $key => $value) {
            DB::Execute("INSERT IGNORE INTO " . $sTable . " (name, visible) VALUES ('" . $value['param_name'] . "','1')");
            if ($value['is_parsed'] && $value['parsing_template']) {
                $iIdParam = DB::InsertId();
                $this->BindProducts($value, $sTable, $iIdParam);
            }
        }
    }

    //------------------------------------------------------------------------------------------------------------------
    public function BindProducts($value, $sTable, $iIdParam)
    {
        $aCrit = $this->GetRubricCrit($this->aInputData['id_rubric']);

        if ($aCrit) {
            foreach ($aCrit as $aValueCrit) {
                foreach ($aValueCrit['childs'] as $aValueCritChilds) {
                    if ($this->ParsingCrit($aValueCritChilds, $value['parsing_template'], $value['is_regexp'])) {
                        $aItems = $this->FindItemCodesByCrit($this->aInputData['id_rubric'], $aValueCrit['crit'], $aValueCritChilds);
                        if ($aItems)
                            foreach ($aItems as $aItem) {
                                $this->BindOne($aItem, $sTable, $iIdParam);
                            }
                    }
                }
            }
        }
    }

    //------------------------------------------------------------------------------------------------------------------
    public function ParsingCrit($sCrit, $parsing_template, $is_regexp)
    {
        $aParsingTemplates = explode(',', $parsing_template);
        if ($is_regexp) {
            foreach ($aParsingTemplates as $sTemplate) {
                preg_match('/' . $sTemplate . '/', $sCrit, $matches);
                if (!empty($matches))
                    return true;
            }
        } else {
            $aParsingTemplatesTrimmed=array_map('trim',$aParsingTemplates);
            foreach ($aParsingTemplatesTrimmed as $sTemplate) {
                if (strpos($sCrit, $sTemplate) !== false)
                    return true;
            }
        }
        return false;
    }

    //------------------------------------------------------------------------------------------------------------------
    public function BindOne($aValue, $sTable, $iIdParam)
    {
        $aBindItem = DB::GetRow("SELECT * FROM price_group_param WHERE item_code='" . $aValue['item_code'] . "'");

        if (!$aBindItem) {
            $sSql = "INSERT INTO price_group_param (id_" . $sTable . ", item_code) VALUES ('" . $iIdParam . "','" . $aValue['item_code'] . "')";
        } else {
            $sSql = "UPDATE price_group_param SET id_" . $sTable . "='" . $iIdParam . "' WHERE item_code='" . $aBindItem['item_code'] . "'";
        }

        DB::Execute($sSql);

    }

    //------------------------------------------------------------------------------------------------------------------
    public function GetParameterRow($aDataRow = array(), $sNumberFilter = '', $sNumberRow = '', $sMode = self::REPLACE)
    {
        if ($sNumberFilter === '')
            $sNumberFilter = Base::$aRequest['number_filter'];

        if ($sNumberRow === '')
            $sNumberRow = Base::$aRequest['number_param'];

        if (!empty($aDataRow))
            Base::$tpl->Assign('aDataRow', $aDataRow);

        Base::$tpl->Assign('sNumberFilter', $sNumberFilter);
        Base::$tpl->Assign('sNumberRow', $sNumberRow);

        $sText = Base::$tpl->fetch('mpanel/tecdoc_filter/row_parameter.tpl');
        $sAddRowParamDiv = Base::$tpl->fetch('mpanel/tecdoc_filter/add_row_param_div.tpl');

        if (!$sMode)
            Base::$oResponse->AddAssign('new_parameter_' . $sNumberFilter, 'outerHTML', $sText . $sAddRowParamDiv);
        else
            return $sText;
    }

    //------------------------------------------------------------------------------------------------------------------
    public function GetFilter($aDataFilter = array(), $sNumberFilter = '', $sMode = self::REPLACE)
    {
        $sRowFilter = '';

        if ($sNumberFilter === '')
            $sNumberFilter = Base::$aRequest['number_filter'];

        if (!empty($aDataFilter) && !empty($aDataFilter['params'])) {
            Base::$tpl->Assign('sFilterData', $aDataFilter);

            foreach ($aDataFilter['params'] as $sKeyParam => $aParam) {
                $sRowFilter .= $this->GetParameterRow($aParam, $sNumberFilter, $sKeyParam, self::TEXT);
            }
        } else
            $sRowFilter = $this->GetParameterRow(array(), $sNumberFilter, '0', self::TEXT);

        $sAddRowParamDiv = Base::$tpl->fetch('mpanel/tecdoc_filter/add_row_param_div.tpl');
        Base::$tpl->Assign('sRowParam', $sRowFilter . $sAddRowParamDiv);
        $sText = Base::$tpl->fetch('mpanel/tecdoc_filter/row_filter.tpl');
        $sAddFilterDiv = Base::$tpl->fetch('mpanel/tecdoc_filter/add_filter_div.tpl');

        if (!$sMode)
            Base::$oResponse->AddAssign('new_filter', 'outerHTML', $sText . $sAddFilterDiv);
        else
            return $sText;
    }

    //------------------------------------------------------------------------------------------------------------------
    public function UnbindFilter()
    {
        DB::Execute("DELETE FROM rubricator_filter WHERE id_handbook in (" . Base::$aRequest['id_filter'] . ") and id_rubricator='" . Base::$aRequest['selected_rubric'] . "'");

        Base::$aRequest['subaction'] ='select_rubric';
        $this->aInputData['selected_index'] = Base::$aRequest['selected_rubric'];
        $this->SelectRubric();
        Base::$tpl->Assign('sMessage',Language::GetDMessage('successfully unbinded'));
    }

    //------------------------------------------------------------------------------------------------------------------
    public function DeleteFilter()
    {
        $aFilter = DB::GetAll("SELECT * FROM handbook WHERE id in (" . Base::$aRequest['id_filter'] . ")");

        if ($aFilter){
            DB::Execute("DELETE FROM rubricator_filter WHERE id_handbook in (" . Base::$aRequest['id_filter'] . ")");
            DB::Execute("DELETE FROM handbook WHERE id in (" . Base::$aRequest['id_filter'] . ")");

            foreach ($aFilter as $aValue) {
                DB::Execute("ALTER TABLE `price_group_param` DROP `id_" . $aValue['table_'] . "`");
                DB::Execute("DROP TABLE " . $aValue['table_']);
            }

            $aTableColumns=array_column(DB::GetAll("describe price_group_param"),'Field');
            $aTableColumnsWithoutItemCode=array_slice($aTableColumns,1,count($aTableColumns));

            DB::Execute("DELETE FROM price_group_param WHERE ".implode("='0' and ",$aTableColumnsWithoutItemCode)."='0' ");
        }

        $this->aInputData['selected_index'] = Base::$aRequest['selected_rubric'];
        Base::$aRequest['subaction'] ='select_rubric';
        $this->SelectRubric();
        Base::$tpl->Assign('sMessage',Language::GetDMessage('successfully deleted'));
    }

    //------------------------------------------------------------------------------------------------------------------
    public function EditFilter()
    {
        $aHandbook = DB::GetAll("SELECT * FROM handbook WHERE id in (" . Base::$aRequest['id_filter'] . ")");

        if ($aHandbook) {
            $sText = '';

            foreach ($aHandbook as $sKey => $aFilter) {
                $aFilter['params'] = DB::GetAll("SELECT * FROM " . $aFilter['table_']);
                $sText .= $this->GetFilter($aFilter, $sKey, self::TEXT);
            }

            $sAddFilterDiv = Base::$tpl->fetch('mpanel/tecdoc_filter/add_filter_div.tpl');
            Base::$tpl->Assign('sFilter', $sText . $sAddFilterDiv);
        }

        Base::$tpl->Assign('sEdit', "<input type='hidden' name='edit' value='1'>");
        $this->aInputData['selected_index'] = Base::$aRequest['selected_rubric'];
        $this->SelectRubric();
    }

    //------------------------------------------------------------------------------------------------------------------
    public function SaveEdited($Request)
    {
        $sMessage='';
        foreach ($Request['data']['id_filter'] as $key => $value) {
            $aHandbook = DB::GetRow("SELECT * FROM handbook WHERE id='" . $value . "'");

            $aRubricatorFilter= DB::GetRow("SELECT * FROM rubricator_filter WHERE id_rubricator='".Base::$aRequest['data']['id_rubric']."'  and id_handbook='" . $aHandbook['id'] . "'");
            if (!$aRubricatorFilter)
                DB::Execute("INSERT INTO rubricator_filter (id_rubricator,id_handbook) VALUES ('".Base::$aRequest['data']['id_rubric']."','" . $aHandbook['id'] . "')");

            $aHandbook['name'] = $Request['data']['handbook'][$key]['name'];

            $this->ProcessHandbookParamEdited($Request['data']['param'][$key], $aHandbook['table_']);

            DB::AutoExecute('handbook', $aHandbook, 'UPDATE', ' id=' . $value);

            unset($Request['data']['handbook'][$key]);
            unset($Request['data']['param'][$key]);

            if (!$sMessage)
            $sMessage.=Language::GetDMessage('edited successfully').'<br>';
        }

        if ($Request['data']['handbook']){
            $this->ProcessHandbook($Request['data']['handbook']);
            $sMessage.=Language::GetDMessage('added successfully');
        }

        if (!$sMessage)
        $sMessage=Language::GetDMessage('error, try again');

        Base::$aRequest['subaction'] ='select_rubric';
        $this->aInputData['selected_index'] = Base::$aRequest['data']['id_rubric'];
        $this->SelectRubric();
        Base::$tpl->Assign('sMessage',$sMessage);
    }

    //------------------------------------------------------------------------------------------------------------------
    public function ProcessHandbookParamEdited($aParam, $sTable)
    {
        $aTableParam = DB::GetAll("SELECT * FROM " . $sTable);

        $aIdsToSave = array_intersect(array_column($aParam, 'id_param'), array_column($aTableParam, 'id'));

        if ($aIdsToSave) {

            $aFieldsToUpdate = array();
            $aKeyTableParamToDeleting = array();
            $aParamForInsert = array();

            foreach ($aIdsToSave as $key => $value) {
                $sKeyNewParam = array_search($value, array_column($aParam, 'id_param'));
                $aFieldsToUpdate[$value]['name'] = $aParam[$sKeyNewParam]['param_name'];
                $aFieldsToUpdate[$value]['id'] = $aParam[$sKeyNewParam]['id_param'];

                $sKeyTableParamToDeleting = array_search($value, array_column($aTableParam, 'id'));
                $aTableParam[$sKeyTableParamToDeleting]['update'] = 1;
                $aParam[$sKeyNewParam]['update'] = 1;
            }

            foreach ($aTableParam as $aTableParamValue) {
                if (!$aTableParamValue['update'])
                    $aKeyTableParamToDeleting[] = $aTableParamValue['id'];
            }

            foreach ($aParam as $sKey => $aParamValue) {
                if (!$aParamValue['update']) {
                    $aParamForInsert[$sKey]['name'] = $aParamValue['param_name'];
                    $aParamForInsert[$sKey]['visible'] = 1;
                }
            }

            if ($aFieldsToUpdate) {
                foreach ($aFieldsToUpdate as $aValueUpdate)
                    DB::AutoExecute($sTable, $aValueUpdate, 'UPDATE', " id=" . $aValueUpdate['id']);
            }

            if ($aKeyTableParamToDeleting) {
                DB::Execute("DELETE FROM " . $sTable . " WHERE id in ('" . implode("','", $aKeyTableParamToDeleting) . "')");
                DB::Execute("UPDATE price_group_param SET id_" . strtolower($sTable) . "=0 WHERE id_" . $sTable . " in ('" . implode("','", $aKeyTableParamToDeleting) . "')");
            }

            if ($aParamForInsert) {
                foreach ($aParamForInsert as $aValueInsert)
                    DB::AutoExecute($sTable, $aValueInsert, 'INSERT');
            }

            require_once (SERVER_PATH . '/mpanel/spec/hbparams_editor.php');
            AHbparamsEditor::CreateMap($sTable);
            $aTableNewParam = DB::GetAll("SELECT * FROM " . $sTable);
            foreach ($aParam as $aValueParam) {
                if ($aValueParam['is_parsed'] && $aValueParam['parsing_template']) {
                    $sKey = array_search($aValueParam['param_name'], array_column($aTableNewParam, 'name'));
                    $this->BindProducts($aValueParam, $sTable, $aTableNewParam[$sKey]['id']);
                }
            }
        }
    }

    //------------------------------------------------------------------------------------------------------------------
    public function GetRubricCrit($iRubricId)
    {
        $aSelectedRubric = DB::GetRow(Base::GetSql('Rubricator', array('where' => ' and r.id=' . $iRubricId)));

        $sSql =  "select distinct c.criteria_name as crit, c.criteria_value
					from " . DB_OCAT . "cat_alt_cri_all as c
						join " . DB_OCAT . "cat_alt_articles a on a.ID_src=c.art_id
						join " . DB_OCAT . "cat_alt_link_art lta on a.id_art=lta.id_art
						join " . DB_OCAT . "cat_alt_link_str_grp lsg on lsg.ID_tree in (" . $aSelectedRubric['id_tree'] . ") and lsg.ID_grp=lta.ID_grp
						join " . DB_OCAT . "cat_alt_groups as grp on grp.id_grp=lsg.ID_grp and grp.id_src in (" . $aSelectedRubric['id_group'] . ")";

        $aCritNames = TecdocDb::GetAll($sSql);

        $aCritArray = array();
        foreach ($aCritNames as $aValue) {
            $aCritArray[$aValue['crit']]['crit'] = $aValue['crit'];
            $aCritArray[$aValue['crit']]['childs'][$aValue['criteria_value']] = $aValue['criteria_value'];
        }

        return $aCritArray;

    }

    //------------------------------------------------------------------------------------------------------------------
    public function FindItemCodesByCrit($id_rubric, $aValueCrit, $aValueCritChilds)
    {
        $aSelectedRubric = DB::GetRow(Base::GetSql('Rubricator', array('where' => ' and r.id=' . $id_rubric)));

        if (!$this->aCat)
            $this->aCat = DB::GetAssoc("SELECT id_sup, pref FROM cat WHERE id_sup>0");

        $sSqlItemByCrit= " select distinct a.Search code,s.ID_src id_sup
					from " . DB_OCAT . "cat_alt_cri_all as c
						join " . DB_OCAT . "cat_alt_articles a on a.ID_src=c.art_id
						join " . DB_OCAT . "cat_alt_suppliers s on a.ID_sup=s.ID_sup
						join " . DB_OCAT . "cat_alt_link_art lta on a.id_art=lta.id_art
						join " . DB_OCAT . "cat_alt_link_str_grp lsg on lsg.ID_tree in (" . $aSelectedRubric['id_tree'] . ") and lsg.ID_grp=lta.ID_grp
						join " . DB_OCAT . "cat_alt_groups as grp on grp.id_grp=lsg.ID_grp and grp.id_src in (" . $aSelectedRubric['id_group'] . ")

					where c.criteria_name='" . $aValueCrit . "' AND c.criteria_value='" . $aValueCritChilds . "'";

        $aArtIds = TecdocDb::GetAll($sSqlItemByCrit);

        foreach ($aArtIds as $sKey=>$aId){
            $aArtIds[$sKey]['item_code'] = $this->aCat[$aId['id_sup']] . "_" . $aId['code'];
        }

        return $aArtIds;
    }
}