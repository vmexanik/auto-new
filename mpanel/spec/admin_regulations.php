<?

/**
 * @author Vladimir Fedorov
 *
 */
require_once(SERVER_PATH . '/class/core/Admin.php');

class AAdminRegulations extends Admin
{
    //-----------------------------------------------------------------------------------------------
    function AAdminRegulations()
    {
        $this->sAdminRegulationsUrl = 'http://irbis.mstarproject.com';
        if (Base::$aGeneralConf['AdminRegulationsUrl'])
            $this->sAdminRegulationsUrl = Base::$aGeneralConf['AdminRegulationsUrl'];

        Base::$tpl->assign("sAdminRegulationsUrl", $this->sAdminRegulationsUrl);

        $this->sTableName = 'admin_regulations';
        $this->sTablePrefix = 'ar';
        $this->sAction = 'admin_regulations';
        $this->sWinHead = Language::getDMessage('Admin regulations');
        //$this->sPath = Language::GetDMessage('>> Admin regulations >');

        $this->Admin();
    }

    //-----------------------------------------------------------------------------------------------
    public function Index()
    {
        $this->PreIndex();

        $oTable = new Table();
        $oTable->aColumn = array(
            'id' => array('sTitle' => 'Code', 'sOrder' => 'ar.id'),
            'code' => array('sTitle' => 'Code', 'sOrder' => 'ar.code'),
            'date_modified' => array('sTitle' => 'Date modified', 'sOrder' => 'ar.date_modified'),
            'info' => array('sTitle' => 'Information'),
            'description' => array('sTitle' => 'Description', 'sOrder' => 'ar.description'),
            'action' => array(),
        );
        $this->SetDefaultTable($oTable);
        $oTable->aCallback = array($this, 'CallParse');
        Base::$sText .= $oTable->getTable();

        $this->AfterIndex();
    }

    //-----------------------------------------------------------------------------------------------
    public function BeforeApply()
    {

    }

    //-----------------------------------------------------------------------------------------------
    public function BeforeAddAssign(&$aData)
    {

    }

    //-----------------------------------------------------------------------------------------------
    public function Sinxronize()
    {
        switch (Base::$aRequest['code']) {
            case 'translate':
                $this->SinxronizeTranslate(Base::$aRequest['code']);
                break;
            case 'cat_model':
                $this->UpdateCatModel(Base::$aRequest['code']);
                break;
            case 'cat':
                $this->UpdateCat(Base::$aRequest['code']);
                break;
            case 'cat-cat_model-type_auto':
                $this->UpdateTypeAuto(Base::$aRequest['code']);
                break;
        }
    }

    //-----------------------------------------------------------------------------------------------
    public function SinxronizeTranslate($sCode)
    {
        $aInfo = array(
            'constant' => array('update' => 0, 'total' => Db::GetOne("select count(*) from constant")),
            'context_hint' => array('update' => 0, 'total' => Db::GetOne("select count(*) from context_hint")),
            'template' => array('update' => 0, 'total' => Db::GetOne("select count(*) from template")),
            'translate_message' => array('update' => 0, 'total' => Db::GetOne("select count(*) from translate_message")),
            'translate_text' => array('update' => 0, 'total' => Db::GetOne("select count(*) from translate_text")),
        );
        // get data from irbis
        $sUrl = $this->sAdminRegulationsUrl . '/pages/admin_regulations_sinxro_translate/';
        $sData = file_get_contents($sUrl);
        $aData = json_decode($sData, true);
        $iTime = date("Y-m-d H:i:s");
        if ($aData['constant']) {
            $sValues = '';
            foreach ($aData['constant'] as $aValue) {
                if ($sValues != '')
                    $sValues .= ',';
                $sValues .= "('" . mysql_real_escape_string($aValue['key_']) . "','" . mysql_real_escape_string($aValue['value']) . "','" . mysql_real_escape_string($aValue['description']) . "','" . $aValue['is_general'] . "','" . $aValue['type_data'] . "','" . $iTime . "')";
            }
            if ($sValues != '') {
                Db::Execute("Delete from constant where key_ = value");
                $iCnt = Db::GetOne("select count(*) from constant");
                Db::Execute("insert ignore into constant (key_, value, description, is_general, type_data, post_date) values " . $sValues);
                $iCntInserted = Db::GetOne("select count(*) from constant");
                $aInfo['constant']['total'] = $iCntInserted;
                $iCntInserted -= $iCnt;
                if ($iCntInserted > 0)
                    $aInfo['constant']['update'] = $iCntInserted;
            }
        }
        if ($aData['context_hint']) {
            $sValues = '';
            foreach ($aData['context_hint'] as $aValue) {
                if ($sValues != '')
                    $sValues .= ',';
                $sValues .= "('" . mysql_real_escape_string($aValue['key_']) . "','" . mysql_real_escape_string($aValue['content']) . "','" . $aValue['visible'] . "')";
            }
            if ($sValues != '') {
                Db::Execute("Delete from context_hint where key_ = content");
                $iCnt = Db::GetOne("select count(*) from context_hint");
                Db::Execute("insert ignore into context_hint (key_, content, visible) values " . $sValues);
                $iCntInserted = Db::GetOne("select count(*) from context_hint");
                $aInfo['context_hint']['total'] = $iCntInserted;
                $iCntInserted -= $iCnt;
                if ($iCntInserted > 0)
                    $aInfo['context_hint']['update'] = $iCntInserted;
            }
        }
        if ($aData['translate_message']) {
            $sValues = '';
            foreach ($aData['translate_message'] as $aValue) {
                if ($sValues != '')
                    $sValues .= ',';
                $sValues .= "('" . mysql_real_escape_string($aValue['code']) . "','" . mysql_real_escape_string($aValue['content']) . "','" . $aValue['page'] . "','" . $iTime . "')";
            }
            if ($sValues != '') {
                Db::Execute("Delete from translate_message where code = content");
                $iCnt = Db::GetOne("select count(*) from translate_message");
                Db::Execute("insert ignore into translate_message (code, content, page, post_date) values " . $sValues);
                $iCntInserted = Db::GetOne("select count(*) from translate_message");
                $aInfo['translate_message']['total'] = $iCntInserted;
                $iCntInserted -= $iCnt;
                if ($iCntInserted > 0)
                    $aInfo['translate_message']['update'] = $iCntInserted;
            }
        }
        if ($aData['template']) {
            $sValues = '';
            foreach ($aData['template'] as $aValue) {
                if ($sValues != '')
                    $sValues .= ',';
                $sValues .= "('" . mysql_real_escape_string($aValue['code']) . "','" . mysql_real_escape_string($aValue['name']) . "','" . mysql_real_escape_string($aValue['content']) . "','" . $aValue['type_'] . "','" .
                    $aValue['is_smarty'] . "','" . $aValue['priority'] . "','" . $iTime . "')";
            }
            if ($sValues != '') {
                Db::Execute("Delete from template where code = content");
                $iCnt = Db::GetOne("select count(*) from template");
                Db::Execute("insert ignore into template (code, name, content, type_, is_smarty, priority, post_date) values " . $sValues);
                $iCntInserted = Db::GetOne("select count(*) from template");
                $aInfo['template']['total'] = $iCntInserted;
                $iCntInserted -= $iCnt;
                if ($iCntInserted > 0)
                    $aInfo['template']['update'] = $iCntInserted;
            }
        }
        if ($aData['translate_text']) {
            $sValues = '';
            foreach ($aData['translate_text'] as $aValue) {
                if ($sValues != '')
                    $sValues .= ',';
                $sValues .= "('" . mysql_real_escape_string($aValue['code']) . "','" . mysql_real_escape_string($aValue['content']) . "','" . $iTime . "')";
            }
            if ($sValues != '') {
                Db::Execute("Delete from translate_text where code = content");
                $iCnt = Db::GetOne("select count(*) from translate_text");
                Db::Execute("insert ignore into translate_text (code, content, post_date) values " . $sValues);
                $iCntInserted = Db::GetOne("select count(*) from translate_text");
                $aInfo['translate_text']['total'] = $iCntInserted;
                $iCntInserted -= $iCnt;
                if ($iCntInserted > 0)
                    $aInfo['translate_text']['update'] = $iCntInserted;
            }
        }
        $sInfo = json_encode($aInfo);
        Db::Execute("update admin_regulations set date_modified=now(), info='" . $sInfo . "' where code='" . $sCode . "'");
        //Admin::Message('MT_NOTICE','SinxronizeTranslateDone');
        $this->AdminRedirect($this->sAction); // but not found message
    }

    //-----------------------------------------------------------------------------------------------
    public function CallParse(&$aItem)
    {
        foreach ($aItem as $iKey => $aValue) {
            switch ($aValue['code']) {
                case 'translate':
                    $aItem[$iKey]['info'] = $this->ViewTranslate($aValue);
                    break;
                case 'cat_model':
                    $aItem[$iKey]['info'] = $this->CatModelStatus($aValue);
                    break;
                case 'cat':
                    $aItem[$iKey]['info'] = $this->CatStatus($aValue);
                    break;
                case 'cat-cat_model-type_auto':
                    $aItem[$iKey]['info'] = $this->TypeAutoStatus($aValue);
                    break;
                case 'cat clear':
                    $aItem[$iKey]['info'] = $this->CatClearStatus($aValue);
                    break;
            }
        }
    }

    //-----------------------------------------------------------------------------------------------
    public function TypeAutoStatus($aData)
    {
        if (!$aData['info'])
            return Language::getDMessage('not update');

        $aInfo = json_decode($aData['info'], true);
        if (!$aInfo)
            return '';

        $aMass[] = array(
            'version' => $aInfo['version'],
            'version_db_tof' => $aInfo['version_db_tof'],
            'update_cat' => $aInfo['update_cat'],
            'update_model' => $aInfo['update_model'],
        );

        Base::$tpl->assign("aDataColumn", array(
            'version' => array('sTitle' => Language::getDMessage('Version Tecdoc')),
            'version_db_tof' => array('sTitle' => Language::getDMessage('Version original Tecdoc')),
            'update_cat' => array('sTitle' => Language::getDMessage('Cat car/truck/mixed')),
            'update_model' => array('sTitle' => Language::getDMessage('Model car/truck')),
        ));

        Base::$tpl->assign("aData", $aMass);
        Base::$tpl->assign("sDataTemplateName", 'mpanel/admin_regulations/row_admin_regulations_translate.tpl');
        return Base::$tpl->fetch('mpanel/admin_regulations/only_table.tpl');
    }

    //-----------------------------------------------------------------------------------------------
    public function CatStatus($aData)
    {
        if (!$aData['info'])
            return Language::getDMessage('not update');

        $aInfo = json_decode($aData['info'], true);
        if (!$aInfo)
            return '';

        $aMass[] = array(
            'version' => $aInfo['version'],
            'update' => $aInfo['update'],
            'total' => $aInfo['total'],
            'update_link' => $aInfo['update_link'],
            'update_adres' => $aInfo['update_adres'],
        );

        Base::$tpl->assign("aDataColumn", array(
            'version' => array('sTitle' => Language::getDMessage('Version Tecdoc')),
            'update' => array('sTitle' => Language::getDMessage('Status update unknown')),
            'update_link' => array('sTitle' => Language::getDMessage('Status update link')),
            'update_adres' => array('sTitle' => Language::getDMessage('Status update adres')),
            'total' => array('sTitle' => Language::getDMessage('Total count')),
        ));

        Base::$tpl->assign("aData", $aMass);
        Base::$tpl->assign("sDataTemplateName", 'mpanel/admin_regulations/row_admin_regulations_translate.tpl');
        return Base::$tpl->fetch('mpanel/admin_regulations/only_table.tpl');
    }

    //-----------------------------------------------------------------------------------------------
    public function CatModelStatus($aData)
    {
        if (!$aData['info'])
            return Language::getDMessage('not update');

        $aInfo = json_decode($aData['info'], true);
        if (!$aInfo)
            return '';

        $aMass[] = array('version' => $aInfo['version'], 'inserted' => $aInfo['inserted'], 'total' => $aInfo['total']);

        Base::$tpl->assign("aDataColumn", array(
            'version' => array('sTitle' => Language::getDMessage('Version Tecdoc')),
            'inserted' => array('sTitle' => Language::getDMessage('Status update')),
            'total' => array('sTitle' => Language::getDMessage('Total count')),
        ));

        Base::$tpl->assign("aData", $aMass);
        Base::$tpl->assign("sDataTemplateName", 'mpanel/admin_regulations/row_admin_regulations_translate.tpl');
        return Base::$tpl->fetch('mpanel/admin_regulations/only_table.tpl');
    }

    //-----------------------------------------------------------------------------------------------
    public function ViewTranslate($aData)
    {
        if (!$aData['info'])
            return '';

        $aInfo = json_decode($aData['info'], true);
        if (!$aInfo)
            return '';

        $aData = $this->CallParseTranslate($aInfo);
        Base::$tpl->assign("aDataColumn", array(
            'name' => array('sTitle' => Language::getDMessage('Name table')),
            'info' => array('sTitle' => Language::getDMessage('Status update')),
            'total' => array('sTitle' => Language::getDMessage('Total count')),
        ));

        Base::$tpl->assign("aData", $aData);
        Base::$tpl->assign("sDataTemplateName", 'mpanel/admin_regulations/row_admin_regulations_translate.tpl');
        return Base::$tpl->fetch('mpanel/admin_regulations/only_table.tpl');
    }

    //-----------------------------------------------------------------------------------------------
    public function CallParseTranslate(&$aItem)
    {
        $aData = array();
        foreach ($aItem as $iKey => $aValue) {
            $aData[] = array('name' => $iKey, 'info' => $aValue['update'], 'total' => $aValue['total']);
        }
        return $aData;
    }

    //-----------------------------------------------------------------------------------------------
    public function UpdateCatModel($sCode)
    {
        $is_lower = 0;
        if (Language::getConstant('admin_regulations:cat_name_is_lower', '1'))
            $is_lower = 1;

        $aInfo = array(
            'version' => DB_OCAT,
            'inserted' => 0,
            'total' => Db::GetOne("select count(*) from cat_model")
        );
        $iTotal = $aInfo['total'];

        $aMfa = array();
        $aOptiModels = TecdocDb::getAssoc("Select om.*,m.Name as brand,m.ID_src as id_mfa
	        ,SUBSTRING(om.DateStart, 1, 2) month_start,SUBSTRING(om.DateStart, 4, 4) year_start
    	    ,SUBSTRING(om.DateEnd, 1, 2) month_end,SUBSTRING(om.DateEnd, 4, 4) year_end
	        from " . DB_OCAT . "cat_alt_models om
	        inner join " . DB_OCAT . "cat_alt_manufacturer m on m.ID_mfa = om.ID_mfa
	        ");

        if ($aOptiModels) {
            $aDataInsert = array();
            foreach ($aOptiModels as $aValue) {
                if ($is_lower)
                    $sNameFiltered = mb_strtolower(str_replace(array(' ', '-', '#', '.', '/', ',', '_', ':', '[', ']', '(', ')', '*', '&', '+', '`', '\'', '"', '\\', '<', '>', '?', '!', '$', '%', '^', '@', '~', '|', '=', ';', '{', '}', '№'), '', trim(Content::Translit($aValue['brand']))), 'UTF-8');
                else
                    $sNameFiltered = mb_strtoupper(str_replace(array(' ', '-', '#', '.', '/', ',', '_', ':', '[', ']', '(', ')', '*', '&', '+', '`', '\'', '"', '\\', '<', '>', '?', '!', '$', '%', '^', '@', '~', '|', '=', ';', '{', '}', '№'), '', trim(Content::Translit($aValue['brand']))), 'UTF-8');

                if (!$aMfa[$sNameFiltered])
                    $aMfa[$sNameFiltered] = $aValue['id_mfa'];

                $asExist = Db::getRow("Select * from cat_model where tof_mod_id=" . $aValue['ID_src']);
                if ($asExist) {
                    Db::Execute("Update cat_model set brand='" . $aValue['brand'] . "',name='" . mysql_real_escape_string($aValue['Name']) . "',
	                    month_start='" . $aValue['month_start'] . "',year_start='" . $aValue['year_start'] . "',month_end='" .
                        $aValue['month_end'] . "',year_end='" . $aValue['year_end'] . "',is_type_auto=1,id_mfa=" .
                        $aValue['id_mfa'] . " where id=" . $asExist['id']);
                } else {
                    $aDataInsert[] = "('" . $aValue['ID_src'] . "','" . mysql_real_escape_string($aValue['brand']) . "','" .
                        mysql_real_escape_string($aValue['Name']) . "','" .
                        $aValue['month_start'] . "','" . $aValue['year_start'] . "','" . $aValue['month_end'] . "','" .
                        $aValue['year_end'] . "','1','" . $aValue['id_mfa'] . "')";
                }
            }
            if ($aDataInsert) {
                Db::Execute($s = "insert into cat_model (tof_mod_id,brand,name,month_start,year_start,month_end,year_end,visible, id_mfa)
					values " . implode(", ", $aDataInsert) .
                    " on duplicate key update name=values(name), month_start=values(month_start), year_start=values(year_start),
					month_end=values(month_end), brand=values(brand),id_mfa=values(id_mfa)");
            }
        }

        $iTotalInserted = Db::GetOne("select count(*) from cat_model");
        $aInfo['total'] = $iTotalInserted;
        $iTotalInserted -= $iTotal;
        if ($iTotalInserted > 0)
            $aInfo['inserted'] = $iTotalInserted;

        $sInfo = json_encode($aInfo);
        Db::Execute("update admin_regulations set date_modified=now(), info='" . $sInfo . "' where code='" . $sCode . "'");
        Language::UpdateConstant('global:auto_pref_last', 'AAA');
        $this->AdminRedirect($this->sAction);
    }

    //-----------------------------------------------------------------------------------------------
    public function UpdateCat($sCode)
    {
        // first check cat_name
        require_once(SERVER_PATH . '/mpanel/spec/cat.php');
        $oCat = new ACat();
        $oCat->CheckName();
        $iTotalUpdate = 0;

        $aInfo = array(
            'version' => DB_OCAT,
            'update' => 0,
            'total' => Db::GetOne("select count(*) from cat"),
            'update_link' => 0,
            'update_adres' => 0,
        );
        $iTotal = $aInfo['total'];

        $sSql = "SELECT * FROM " . DB_OCAT . "cat_alt_suppliers s ";
        $sSqlMan = "SELECT *  FROM " . DB_OCAT . "cat_alt_manufacturer s";

        $aUnknown = TecdocDb::GetAll($sSql);
        $aUnknownMan = TecdocDb::GetAll($sSqlMan);

        $is_lower = 0;
        if (Language::getConstant('admin_regulations:cat_name_is_lower', '1'))
            $is_lower = 1;

        if ($aUnknown) {
            foreach ($aUnknown as $aValue) {
                if ($is_lower)
                    $sNameFiltered = mb_strtolower(str_replace(array(' ', '-', '#', '.', '/', ',', '_', ':', '[', ']', '(', ')', '*', '&', '+', '`', '\'', '"', '\\', '<', '>', '?', '!', '$', '%', '^', '@', '~', '|', '=', ';', '{', '}', '№'), '', trim(Content::Translit($aValue['Search']))), 'UTF-8');
                else
                    $sNameFiltered = mb_strtoupper(str_replace(array(' ', '-', '#', '.', '/', ',', '_', ':', '[', ']', '(', ')', '*', '&', '+', '`', '\'', '"', '\\', '<', '>', '?', '!', '$', '%', '^', '@', '~', '|', '=', ';', '{', '}', '№'), '', trim(Content::Translit($aValue['Search']))), 'UTF-8');

                $aRow = Db::GetRow("Select * from cat where name='".$sNameFiltered."' and id_sup = ".$aValue['ID_src']);
                $aRowSup = Db::GetRow("Select * from cat where id_sup = ".$aValue['ID_src']);

                if (!$aRow && mb_strtolower(str_replace('1','',$aRowSup['name']))==$sNameFiltered){
                    $aRow=$aRowSup;
                }

                // change id_tof
                if ($aRow)
                    Db::Execute("Update cat set id_sup = " . $aValue['ID_src'] . " where id=" . $aRow['id']);
                else {
                    // generate pref
                    $sPref = String::GeneratePref();
                    $aInsertData = array(
                        'pref' => $sPref,
                        'name' => $sNameFiltered,
                        'title' => str_replace("'", "`", $aValue['Name']),
                        'id_sup' => $aValue['ID_src'],
                    );

                    $aCheckName=DB::GetOne("Select id from cat where name='".$aInsertData['name']."'");

                    while($aCheckName){
                        $aInsertData['name'].=1;
                        $aCheckName=DB::GetOne("Select * from cat where name='".$aInsertData['name']."'");
                    }

                    Db::AutoExecute("cat", $aInsertData);
                    $iCatId = Db::InsertId();
                    $aCatPref = Db::GetRow("Select * from cat_pref where name='" . $sNameFiltered . "'");
                    if ($aCatPref)
                        DB::Execute("update cat_pref set cat_id=" . $iCatId . " where id=" . $aCatPref['id']);
                    else
                        DB::Execute("insert into cat_pref (name, cat_id) values ('" . $sNameFiltered . "','" . $iCatId . "')");
                }
            }
        }
        $iTotalUpdate = count($aUnknown);

        if ($aUnknownMan) {
            foreach ($aUnknownMan as $aValue) {
                if ($is_lower)
                    $sNameFiltered = mb_strtolower(str_replace(array(' ', '-', '#', '.', '/', ',', '_', ':', '[', ']', '(', ')', '*', '&', '+', '`', '\'', '"', '\\', '<', '>', '?', '!', '$', '%', '^', '@', '~', '|', '=', ';', '{', '}', '№'), '', trim(Content::Translit($aValue['Name']))), 'UTF-8');
                else
                    $sNameFiltered = mb_strtoupper(str_replace(array(' ', '-', '#', '.', '/', ',', '_', ':', '[', ']', '(', ')', '*', '&', '+', '`', '\'', '"', '\\', '<', '>', '?', '!', '$', '%', '^', '@', '~', '|', '=', ';', '{', '}', '№'), '', trim(Content::Translit($aValue['Name']))), 'UTF-8');

                $aRow = Db::GetRow("Select * from cat where name='" . $sNameFiltered . "'");

                // change id_tof
                if ($aRow)
                    Db::Execute("Update cat set is_brand=1, is_vin_brand=1, id_mfa = " . $aValue['ID_src'] . " where id=" . $aRow['id']);
                else {
                    // generate pref
                    $sPref = String::GeneratePref();
                    $aInsertData = array(
                        'pref' => $sPref,
                        'name' => $sNameFiltered,
                        'title' => str_replace("'", "`", $aValue['Name']),
                        'id_mfa' => $aValue['ID_src'],
                        'is_brand' => 1,
                        'is_vin_brand' => 1
                    );
                    Db::AutoExecute("cat", $aInsertData);
                    $iCatId = Db::InsertId();
                    $aCatPref = Db::GetRow("Select * from cat_pref where name='" . $sNameFiltered . "'");
                    if ($aCatPref)
                        DB::Execute("update cat_pref set cat_id=" . $iCatId . " where id=" . $aCatPref['id']);
                    else
                        DB::Execute("insert into cat_pref (name, cat_id) values ('" . $sNameFiltered . "','" . $iCatId . "')");
                }
            }
        }
        $iTotalUpdate += count($aUnknownMan);

        $iTotal = Db::GetOne("select count(*) from cat");
        $aInfo['total'] = $iTotal;
        $aInfo['update'] = $iTotalUpdate;

        $sInfo = json_encode($aInfo);
        Db::Execute("update admin_regulations set date_modified=now(), info='" . $sInfo . "' where code='" . $sCode . "'");
        Language::UpdateConstant('global:auto_pref_last', 'AAA');
        $this->AdminRedirect($this->sAction);
    }

    //-----------------------------------------------------------------------------------------------
    public function UpdateTypeAuto($sCode)
    {
        $aInfo = array(
            'version' => DB_OCAT,
            'version_db_tof' => DB_TOF,
            'update_cat' => 0,
            'update_model' => 0,
        );

        $aMassTruck = Db::GetAssoc(
            "SELECT m.ID_src, concat(mf.name, ' ', m.Name) as name
			FROM " . DB_OCAT . "cat_alt_models m
			INNER JOIN " . DB_OCAT . "cat_alt_manufacturer mf ON mf.ID_mfa = m.ID_mfa
			WHERE m.ID_src
			IN (
			SELECT MOD_ID
			FROM " . DB_TOF . "`tof__models`
			WHERE MOD_CV =1
			)");

        if ($aMassTruck) {
            Db::Execute("Update cat_model set is_type_auto=2 where tof_mod_id in (" . implode(',', array_keys($aMassTruck)) . ")");
            $iTotalCar = Db::GetOne("Select count(*) from cat_model where is_type_auto=1");
            $iTotalTruck = Db::GetOne("Select count(*) from cat_model where is_type_auto=2");
            $aInfo['update_model'] = $iTotalCar . "/" . $iTotalTruck;

            $aMass = Db::GetAll("SELECT c1.ID_src, c1.Name, ca.Name, cm . *
				FROM `cat_model` cm
				INNER JOIN " . DB_OCAT . "cat_alt_models ca ON ca.ID_src = cm.tof_mod_id
				INNER JOIN " . DB_OCAT . "cat_alt_manufacturer c1 ON c1.ID_mfa = ca.ID_mfa
				GROUP BY c1.ID_src, is_type_auto
				ORDER BY c1.ID_src");

            $aData = array();
            foreach ($aMass as $aValue) {
                if (!$aData[$aValue['ID_src']])
                    $aData[$aValue['ID_src']] = $aValue['is_type_auto'];
                elseif ($aData[$aValue['ID_src']] && $aData[$aValue['ID_src']] != $aValue['is_type_auto'])
                    $aData[$aValue['ID_src']] = 3;
            }

            foreach ($aData as $iKey => $sValue)
                Db::Execute("Update cat set is_type_auto=" . $sValue . " where id_tof=" . $iKey);

            $iTotalCar = Db::GetOne("Select count(*) from cat where is_type_auto=1");
            $iTotalTruck = Db::GetOne("Select count(*) from cat where is_type_auto=2");
            $iTotalCarTruck = Db::GetOne("Select count(*) from cat where is_type_auto=3");
            $aInfo['update_cat'] = $iTotalCar . "/" . $iTotalTruck . "/" . $iTotalCarTruck;
        }

        $sInfo = json_encode($aInfo);
        Db::Execute("update admin_regulations set date_modified=now(), info='" . $sInfo . "' where code='" . $sCode . "'");
        Language::UpdateConstant('global:auto_pref_last', 'AAA');
        $this->AdminRedirect($this->sAction);
    }

    //-----------------------------------------------------------------------------------------------
    public function CatClear()
    {
        $aAllCat = Db::GetAll("Select * from cat");
        $aAllUnlinkCat = Db::GetAll("Select * from cat where id_tof=0");
        $i = 0;
        if ($aAllUnlinkCat) {
            foreach ($aAllUnlinkCat as $aValue) {
                $iCount = Db::GetOne("Select count(*) from price where pref='" . $aValue['pref'] . "' /*and price > 0*/");
                //if ($iCount == 0) {
                $i += 1;
                Db::Execute("Delete from cat_pref where cat_id='" . $aValue['id'] . "'");
                Db::Execute("Delete from cat where id='" . $aValue['id'] . "'");
                Db::Execute("Delete from price where pref='" . $aValue['pref'] . "'");
                //}
            }
        }
        $aInfo = array(
            'total' => count($aAllCat),
            'total_unlink' => count($aAllUnlinkCat),
            'del_if_empty_price' => $i
        );

        $sInfo = json_encode($aInfo);
        Db::Execute("update admin_regulations set date_modified=now(), info='" . $sInfo . "' where code='cat clear'");
        Language::UpdateConstant('global:auto_pref_last', 'AAA');
        $this->AdminRedirect($this->sAction);
    }

    //-----------------------------------------------------------------------------------------------
    public function CatClearStatus($aData)
    {
        if (!$aData['info'])
            return Language::getDMessage('not info');

        $aInfo = json_decode($aData['info'], true);
        if (!$aInfo)
            return '';

        $aMass[] = $aInfo;

        Base::$tpl->assign("aDataColumn", array(
            'total' => array('sTitle' => Language::getDMessage('Total count')),
            'total_unlink' => array('sTitle' => Language::getDMessage('Total unlink')),
            'del_if_empty_price' => array('sTitle' => Language::getDMessage('Deleted records')),
        ));

        Base::$tpl->assign("aData", $aMass);
        Base::$tpl->assign("sDataTemplateName", 'mpanel/admin_regulations/row_admin_regulations_cat_clear.tpl');
        return Base::$tpl->fetch('mpanel/admin_regulations/only_table.tpl');
    }
}

?>