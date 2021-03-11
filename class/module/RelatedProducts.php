<?

class RelatedProducts extends Base
{
    //-----------------------------------------------------------------------------------------------
    public function Index()
    {
        Base::$tpl->assign("aPref",$aPref=array(""=>"")+Db::GetAssoc("Assoc/Pref", array('all'=>1)));

        //form
        $aField['pref']=array('title'=>'pref','type'=>'select','options'=>$aPref,'selected'=>Base::$aRequest['search']['pref'],'name'=>'search[pref]','class'=>'select_search');
        $aField['code']=array('title'=>'Code','type'=>'input','value'=>Base::$aRequest['search']['code'],'name'=>'search[code]','id'=>'code');
        $aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("d.m.Y",time()-30*86400),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
        $aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()+86400),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");

        $aData=array(
            'sHeader'=>"method=post enctype='multipart/form-data'",
            'aField'=>$aField,
            'bType'=>'generate',
            'sSubmitButton'=>'Search',
            'sSubmitAction'=>'related_products',
            'sReturnButton'=>'Clear',
            'sWidth'=>"800px",
            'bIsPost'=>1,
            'sGenerateTpl'=>'form/index_search.tpl',

        );
        $oForm= new Form($aData);
        Base::$sText.=$oForm->getForm();

        $aData=Base::$aRequest['search'];
        $aData['join'] = 1;
        if (Base::$aRequest['search']['pref']) {
            $sWhere.=" ";
        }
        if (Catalog::StripCode(Base::$aRequest['search']['code'])) {
            $aData['aCode']=array(Catalog::StripCode(Base::$aRequest['search']['code']));
            $sWhere.=" ";
        }
        if(Base::$aRequest['search']['date'])
            $sWhere.=" and ((cc.post_date>='".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
			 and cc.post_date<='".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'])."') or cc.post_date+0=0)";
        $aData['where']=$sWhere;

        $ooo=DB::GetAll(Base::GetSql("Catalog/PartRelated",$aData));
        //Table
        $oTable=new Table();
//        $oTable->sSql=Base::GetSql("Catalog/PartRelated",$aData);
        $oTable->sType='array';
        $oTable->aDataFoTable=$ooo;
        $oTable->aColumn=array(
            'id'=>array('sTitle'=>'id','sWidth'=>'10%'),
            'brand'=>array('sTitle'=>'name','sWidth'=>'20%'),
            'code'=>array('sTitle'=>'code','sWidth'=>'10%'),
            'relative_brand'=>array('sTitle'=>'name','sWidth'=>'20%'),
            'code_related'=>array('sTitle'=>'code_related','sWidth'=>'10%'),
            'post_date'=>array('sTitle'=>'Date','sWidth'=>'20%'),
            'action'=>array('sWidth'=>'10%'),

        );
        $oTable->iRowPerPage=20;
        $oTable->sDataTemplate='related_products/table.tpl';
        $oTable->sButtonTemplate='related_products/button.tpl';
        Base::$sText.=$oTable->getTable("Сопутствующие товары");
//        Base::$tpl->assign('aData',$aData);

//        Debug::PrintPre($aPref,0);
        Base::$sText.=Base::$tpl->fetch('related_products/list.tpl');
    }

    public function AddProduct()
    {
        /* [ apply  */
        if (Base::$aRequest['is_post'])
        {
            if (!Base::$aRequest['data']['pref'] || !Base::$aRequest['data']['code']
                || !Base::$aRequest['data']['pref_related'] || !Base::$aRequest['data']['code_related'])
            {
                Base::Message(array('MF_ERROR'=>'required fields brand and code'));
                Base::$aRequest['action']='related_add';
                Base::$tpl->assign('aData', Base::$aRequest['data']);
            }
            else
            {
                $aData=String::FilterRequestData(Base::$aRequest['data']);
                $aData["code"]=Catalog::StripCode(strtoupper($aData["code"]));
                $aData["code_related"]=Catalog::StripCode(strtoupper($aData["code_related"]));

                if (Base::$aRequest['id']) {
                    $aId=Db::GetRow("select * from related_products where id=".Base::$aRequest['id']);

                    Db::Execute("delete from related_products where pref='".$aId['pref']."' and code='".$aId['code']."'
					and pref_related='".$aId['pref_related']."' and code_related='".$aId['code_related']."'");

                    Db::Execute("delete from related_products where pref='".$aId['pref_related']."' and code='".$aId['code_related']."'
					and pref_related='".$aId['pref']."' and code_related='".$aId['code']."'");

                    $bUpdate=$this->InsertRelated($aData);
                    if ($bUpdate) $sMessage="&aMessage[MF_NOTICE]=Updated";
                    else $sMessage = "&aMessage[MF_ERROR]=Related not update";
                }
                else {
                    $bInsert=$this->InsertRelated($aData);
                    if($bInsert) $sMessage="&aMessage[MF_NOTICE]=related_product_added";
                    else $sMessage="&aMessage[MF_ERROR]=Related not added";
                }
                Form::RedirectAuto($sMessage);
            }
        }
        /* apply */

        if (Base::$aRequest['action']=='related_products_add' || Base::$aRequest['action']=='related_products_edit')
        {
            Base::$tpl->assign("aPref",$aPref=array(""=>"")+Db::GetAssoc("Assoc/Pref", array('all'=>1)));

            if (Base::$aRequest['action']=='related_products_edit')
            {
                Base::$tpl->assign('aData',Base::$db->getRow(Base::GetSql("Catalog/PartRelated",
                    array("id"=>Base::$aRequest['id']?Base::$aRequest['id']:"-1"))));
            } elseif (Base::$aRequest['action']=='related_products_add' && Base::$aRequest['item_code']) {
                list($aData['pref_related'],$aData['code_related'])=explode('_',Base::$aRequest['item_code']);
                Base::$tpl->assign('aData',$aData);
            }

            Resource::Get()->Add('/js/form.js',3285);

            $aData=array(
                'sHeader'=>"method=post",
                'sTitle'=>"Добавить товар",
                'sContent'=>Base::$tpl->fetch('related_products/form_add.tpl'),
                'sSubmitButton'=>'Apply',
                'sSubmitAction'=>"related_products_add",
                'sReturnButton'=>'<< Return',
                'bAutoReturn'=>true,
            );
            $oForm=new Form($aData);
            Base::$sText.=$oForm->getForm();
        }
        Base::Message();
    }

    public function InsertRelated($aData) {

        if ($aData['pref'] && $aData['code'] && $aData['pref_related'] && $aData['code_related']
            && !($aData['code']===$aData['code_related'] && $aData['pref']===$aData['pref_related'])
        ) {
            if( (preg_match('/^A[0-9]{10}$/', $aData['code']) || preg_match('/^A[0-9]{11}$/', $aData['code'])
                    || preg_match('/^A[0-9]{12}$/', $aData['code'])) && $sPrefMers == $aData['pref'])
                $aData['code'] = ltrim($aData['code'],'A');

            if( (preg_match('/^A[0-9]{10}$/', $aData['code_related']) || preg_match('/^A[0-9]{11}$/', $aData['code_related'])
                    || preg_match('/^A[0-9]{12}$/', $aData['code_related'])) && $sPrefMers == $aData['pref_related'])
                $aData['code_related'] = ltrim($aData['code_related'],'A');

            Db::Execute(" insert ignore into related_products (pref, code, pref_related, code_related, id_manager)
					values ('".$aData['pref']."','".$aData['code']."','".$aData['pref_related']."','".$aData['code_related']."','".Auth::$aUser['id_user']."')
					, ('".$aData['pref_related']."','".$aData['code_related']."','".$aData['pref']."','".$aData['code']."','".Auth::$aUser['id_user']."')
			    on duplicate key update  id_manager=values(id_manager)
					");
            return true;
        } else {
            return false;
        }
    }

    public function DeleteProduct($bRedirect=true) {
        Auth::NeedAuth('manager');
        if (Base::$aRequest['id'])
        {
            $aId=Db::GetRow("select * from related_products where id=".Base::$aRequest['id']);

            Db::Execute("delete from related_products where pref='".$aId['pref']."' and code='".$aId['code']."'
					and pref_related='".$aId['pref_related']."' and code_related='".$aId['code_related']."'");

            Db::Execute("delete from related_products where pref='".$aId['pref_related']."' and code='".$aId['code_related']."'
					and pref_related='".$aId['pref']."' and code_related='".$aId['code']."'");
            $sMessage="&aMessage[MF_NOTICE]=Delated";
        }  else {
            $sMessage="&aMessage[MF_ERROR]=You must enter id";
        }

        Base::$aRequest['return']="action=related_products";
        if($bRedirect) Form::RedirectAuto($sMessage);
    }

    public function ImportProduct() {
        set_time_limit(0);
        Auth::NeedAuth('manager');

        if (Base::$aRequest['is_post'])
        {
            if (is_uploaded_file($_FILES['import_file']['tmp_name']))
            {
                $aPref=Base::$db->getAssoc("
				select upper(title) as name, pref from cat
				union
				select upper(name) as name, pref from cat
				/*union
				select upper(pref) as name, pref from cat*/
				union
				select upper(cp.name) as name,c.pref FROM cat_pref as cp
				inner join cat as c on c.id=cp.cat_id
				");

                ini_set("memory_limit",-1);
                $aPathInfo = pathinfo($_FILES['import_file']['name']);

                if($aPathInfo['extension']=='xlsx') {
                    $oExcel = new Excel();
                    $oExcel->ReadExcel7($_FILES['import_file']['tmp_name'],true,false);
                    $oExcel->SetActiveSheetIndex();
                    $aResult=$oExcel->GetSpreadsheetData();
                } else {
                    $oExcel= new Excel();
                    $oExcel->ReadExcel5($_FILES['import_file']['tmp_name'],true);
                    $oExcel->SetActiveSheetIndex();
                    $oExcel->GetActiveSheet();

                    $aResult=$oExcel->GetSpreadsheetData();
                }

                if ($aResult)
                    foreach ($aResult as $sKey=>$aValue) {
                        if ($sKey>1)
                        {
                            $aData['pref']=$aPref[strtoupper(trim($aValue[1]))];
                            $aData['code']=Catalog::StripCode(strtoupper($aValue[2]));
                            if (trim($aValue[3]) == '' && trim($aValue[1]) != '')
                                $aData['pref_related']=$aData['pref'];
                            else
                                $aData['pref_related']=$aPref[strtoupper(trim($aValue[3]))];

                            $aData['code_related']=Catalog::StripCode(strtoupper($aValue[4]));

                            if ($aData['pref'] && $aData['code'] && $aData['pref_related'] && $aData['code_related'])
                            {
                                if (strpos($aData['code'],";")===false) {
                                    $this->InsertRelated($aData);
                                } else {
                                    $aCode=explode(";",$aData['code']);
                                    foreach ($aCode as $sCode) {
                                        $aData['code']=$sCode;
                                        $this->InsertRelated($aData);
                                    }
                                }
                            } else {
                                if (!$aData['pref'])
                                    Db::Execute("insert ignore into cat_pref (name) values (upper('".trim($aValue[1])."'))");
                                if (!$aData['pref_related'])
                                    Db::Execute("insert ignore into cat_pref (name) values (upper('".trim($aValue[3])."'))");
                            }
                        }
                    }
                $sMessage="&aMessage[MF_NOTICE_NT]=".Language::GetMessage("Upload related")." ".$_FILES['import_file']['name']." ".Language::GetMessage("succsessfully");
                Form::RedirectAuto($sMessage);
            }
            else Base::Message(array('MI_ERROR'=>Language::GetMessage("Error import file")));
        }

        Base::Message();

        $aField['default_file_to_import']=array('type'=>'text','value'=>Language::GetText("Default File to import prod"),'colspan'=>2);
        $aField['import_file']=array('title'=>'File to import','type'=>'file','name'=>'import_file');

        $aData=array(
            'sHeader'=>"method=post enctype='multipart/form-data'",
            'sTitle'=>"Загрузите файл",
            'aField'=>$aField,
            'bType'=>'generate',
            'sSubmitButton'=>'Load',
            'sSubmitAction'=>'related_products_import',
            'sReturnButton'=>'<< Return',
            'bAutoReturn'=>true,
            'sWidth'=>"400px",
        );
        $oForm=new Form($aData);
        Base::$sText.=$oForm->getForm();
    }
}
?>