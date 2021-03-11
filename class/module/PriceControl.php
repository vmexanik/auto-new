<?
/**
 * Loader price
 * @author Vladimir Fedorov
 *
 */

class PriceControl extends Base {
	var $sPrefix="price_control";

	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
		if (!(Base::$aGeneralConf['is_price_control_available'] && Base::$aGeneralConf['is_price_control_available'] == 1))
			return; // blocked 

	    if (!Base::$aData['price_control_allow'])
		  Auth::NeedAuth('manager');

		if (!Language::getConstant('use_price_control',0))
		    Base::Redirect('/pages/price');

		Base::$aData['global_class'] = 'PriceControl';
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		Base::Message();

		$this->sPrefixAction=$this->sPrefix;
		Base::$aTopPageTemplate=array('panel/tab_price.tpl'=>'price_control');

		Base::$sText.=Base::$tpl->fetch('price_profile/popup.tpl');
		Base::Message();
		
		if (Base::$aRequest['subaction']=='delete' && Base::$aRequest['id']) {
		    // check del all store
		    $aData = Db::GetRow("Select pi.*,u.login as login_provider
			from price_import pi
			inner join user_provider up on up.id_user = pi.id_provider
			inner join user u on u.id = up.id_user
			where pi.id =".Base::$aRequest['id']);
		    if (!$aData['login_provider'])
		        Db::Execute("Delete from price_import where id=".Base::$aRequest['id']);
		    else {
		        Db::Execute("Delete from price_import where code_in='".$aData['code_in'].
		        "' and id_price_queue=".$aData['id_price_queue']);
		    }
		    	
		    Base::Message(array('MT_NOTICE_NT'=>Language::GetMessage("item deleted")),false);
		}
		
		// форма отбора
		require_once(SERVER_PATH.'/class/core/Form.php');
		
		// error
		$sErrorStr = "(pi.price=0 or pi.id_provider=0 or pi.id_provider is null
		    or pi.code='' or pi.pref='' or pi.pref is null or pi.is_code_ok=0) ";

		// not error		
		$sWhere = " and (".$sErrorStr.") ";
		// end work
		$sWhere .= " and (pq.is_processed=2 or pq.is_processed=3) ";
		
		$aCodeInType = array('1' => 'Равно', '2' => 'Содержит', '3' => 'Начинается', '4' => 'Заканчивается');
			
		$aAllProvider = Db::getAssoc("Select u.login as key_, u.login from price_import pi
				inner join user u on u.id = pi.id_provider and u.type_='provider'
				inner join user_provider up on up.id_user = u.id
				inner join price_queue pq on pq.id = pi.id_price_queue
			".$sWhere." group by u.login order by u.login");
			
		$aProviderOptions = array('' => 'Все') + $aAllProvider;
		$aField['id_provider']=array('title'=>'Provider','type'=>'select','options'=>$aProviderOptions,'selected'=>Base::$aRequest['id_provider'],'name'=>'id_provider', 'id' => 'id_provider', 'onchange' => 'viewstorage();');
		$id_provider = 0;
		if (Base::$aRequest['id_provider'])
		    $id_provider = Db::getOne("Select id from user where type_='provider' && login='".Base::$aRequest['id_provider']."'");
		
		$aBrandAssoc = Db::getAssoc("Select pi.cat as key_, pi.cat as name from price_import pi
			inner join price_queue pq on pq.id = pi.id_price_queue
			".$sWhere." order by pi.cat");
		$aField['brand']=array('title'=>'Brand price','type'=>'select','options'=>array('' => 'Все','is_empty' => Language::getMessage('not_fill_cat'))+$aBrandAssoc,'name'=>'id_cat', 'id' => 'id_cat', 'selected'=>Base::$aRequest['id_cat']);
		$aField['code_type']=array('title'=>'CodeType','type'=>'select','options'=> $aCodeInType,'name'=>'code_type','selected'=>Base::$aRequest['code_type']);
		$aField['code']=array('title'=>'Code','type'=>'input','value'=>Base::$aRequest['code'],'name'=>'code');
		$aField['is_empty_code']=array('title'=>'is_empty_code','type'=>'checkbox','name'=>'data[is_empty_code]','value'=>'1','checked'=>Base::$aRequest['data']['is_empty_code']);
		
		$aData=array(
		    'sHeader'=>"method=get",
		    'aField'=>$aField,
		    'bType'=>'generate',
		    'sSubmitButton'=>'Select',
		    'sSubmitAction'=>'price_control',
		    'sReturnButton'=>'Clear',
		    'sError'=>$sError,
		    'sGenerateTpl'=>'form/index_search.tpl',
		);
		$oForm=new Form($aData);
		
		Base::$sText.=$oForm->getForm();
		
		$sSql=Base::GetSql('PriceControl');
		$iCountTotalError = Db::getOne($a='select count(*) from ('.$sSql.') as sss');
		//Debug::PrintPre($a);
		$sAfterTableHeader = Language::getMessage('total errors').': '.$iCountTotalError;
		
		$sWhere='';
		if (Base::$aRequest['id_provider']) {
		    if (Base::$aRequest['id_provider']=='is_empty')
		        $sWhere .= ' and pi.id_provider=0';
		    else {
		        $iId = Db::getOne("Select id from user where type_='provider' and login='".Base::$aRequest['id_provider']."'");
	            $sWhere .= ' and pi.id_provider='.$iId;
		    }
		}
		if (Base::$aRequest['id_cat']) {
		    if (Base::$aRequest['id_cat']=='is_empty')
		        $sWhere .= " and (pi.pref='' or pi.pref is null) and pi.cat=''";
		    else {
		        $sWhere .= " and pi.cat='".mysql_real_escape_string(Base::$aRequest['id_cat'])."'";
	        }
        }
		
        if (Base::$aRequest['code']) {
		  $sCode = trim(Base::$aRequest['code']);
		  if ($sCode) {
		      if (Base::$aRequest['code_type']=='1')
		          $sWhere .= " and pi.code='".mysql_real_escape_string($sCode)."'";
		      elseif (Base::$aRequest['code_type']=='2')
	              $sWhere .= " and pi.code like '%".mysql_real_escape_string($sCode)."%'";
		      elseif (Base::$aRequest['code_type']=='3')
		          $sWhere .= " and pi.code like '".mysql_real_escape_string($sCode)."%'";
		      elseif (Base::$aRequest['code_type']=='4')
				  $sWhere .= " and pi.code like '%".mysql_real_escape_string($sCode)."'";
		  }
		}
		
		if (Base::$aRequest['data']['is_empty_code'])
		    $sWhere .= " and pi.code_in=''";
		
        if ($sWhere=='') {
            $sWhere .= ' and 0=1';
            Base::Message(array('MT_WARNING'=>'for see data need filtered'));
		}
		
		$oTable=new Table();
		$oTable->sSql=Base::GetSql('PriceControl', array(
	        'where'=>$sWhere,
		));
		//Debug::PrintPre($oTable->sSql);
		$_SESSION['analize_buffer_price']['current_sql']=$oTable->sSql;
		
		$iCountError = Db::getOne('select count(*) from ('.$oTable->sSql.') as sss');
		$sAfterTableHeader .= '<br>'.Language::getMessage('total filtered errors').': '.$iCountError;
		
		Base::$tpl->assign('sAfterTableHeader',$sAfterTableHeader);
		
		$aBrands = Db::GetAssoc("Assoc/Pref");
        Base::$tpl->assign('aBrands',$aBrands);
		
        $aProvider = Db::GetAssoc("Assoc/UserProvider");
		Base::$tpl->assign('aProvider',$aProvider);
		
	    $oTable->iRowPerPage=50;
	    $oTable->aOrdered="order by id asc";
		$oTable->aColumn=array(
			'id'=>array('sTitle'=>'Id','sWidth'=>'20%'),
		    'login_provider_buffer'=>array('sTitle'=>'postavsh','sWidth'=>'20%'),
			'code'=>array('sTitle'=>'Code','sWidth'=>'20%'),
	        'cat'=>array('sTitle'=>'cat','sWidth'=>'20%'),
	        'part_rus'=>array('sTitle'=>'Name','sWidth'=>'20%'),
		    'stock'=>array('sTitle'=>'kvo','sWidth'=>'20%'),
	        'price'=>array('sTitle'=>'Price','sWidth'=>'20%'),
	        'action'=>array(),
		);
		$oTable->sDataTemplate='price_control/row_price_control.tpl';
		$oTable->bCheckVisible=true;
		$oTable->bFormAvailable=true;
		$oTable->sButtonTemplate='price_control/button_price_control.tpl';
		$oTable->sFormHeader='method=post';
		
		Base::$sText.=$oTable->getTable("AnalizeBufferPrice");
	}
	// -------------------------------------------------------------------------------------------
	public function Dashboard () {
	    return array('price_control_system','price_control','price_control_edit_code',
	        'price_control_change_code','price_control_change_code_import',
	        'price_control_change_code_add','price_control_change_code_del',
	        'price_control_change_code_edit','price_control_locked_code',
	        'price_control_change_code_pi','price_control_edit_code_pi'
	    );
	}
	//--------------------------------------------------------------------------------------------
	public function ChangeCode () {
	    Base::$oContent->AddCrumb(Language::GetMessage('change code'),'');
	    
	    Resource::Get()->Add('/js/select_search.js');
	     
	    $aProviderAssoc = array("" => Language::getMessage('not selected')) + Db::getAssoc("Select u.id as key_, u.login
		        from user_provider up
				inner join user u on u.id = up.id_user and u.type_='provider'
			    order by u.login");
	    Base::$tpl->assign("aProviderAssoc",$aProviderAssoc);
	     
	    if(Auth::$aUser['type_']=='customer' && (!Auth::$aUser['can_change_code'] || !Auth::$aUser['card_editor']))
	        Auth::NeedAuth('manager');
	    Base::$aTopPageTemplate=array('panel/tab_price.tpl'=>'price_change_code');
	    Base::$tpl->assign("aManager",array(""=>"")+Db::GetAssoc("Assoc/UserManager", array('all'=>1)));
	
	    if (Base::$aRequest['is_post'])
	    {
	        if ((!Base::$aRequest['data']['pref'] && !Base::$aRequest['data']['cat_in']) || !Base::$aRequest['data']['code'] || !Base::$aRequest['data']['code_replace'] || !Base::$aRequest['data']['pref_replace']){
	            $sError=("Please, fill the required fields");
	            Base::$aRequest['action']='price_change_code';
	            Base::$tpl->assign('aData',Base::$aRequest['data']);
	        }
	        elseif (Base::$aRequest['data']['pref'] && Db::GetOne("select * from change_code
				     where pref=(select pref from cat where id='".Base::$aRequest['data']['pref']."')
	                 and code='".Base::$aRequest['data']['code']."'
	                 and pref_replace=(select pref from cat where id='".Base::$aRequest['data']['pref_replace']."')
	                 and code_replace='".Base::$aRequest['data']['code_replace']."'")){
	
		                 $sError=("Code change is present!");
		                 Base::$aRequest['action']='price_change_code';
		                 Base::$tpl->assign('aData',Base::$aRequest['data']);
	        }
	        elseif (Base::$aRequest['data']['cat_in'] && Db::GetOne("select * from change_code
				     where cat_in='".mysql_real_escape_string(trim(Base::$aRequest['data']['cat_in']))."'
	                 and code='".Base::$aRequest['data']['code']."'
	                 and pref_replace=(select pref from cat where id='".Base::$aRequest['data']['pref_replace']."')
	                 and code_replace='".Base::$aRequest['data']['code_replace']."'")){
		                  
		                 $sError=("Code change is present!");
		                 Base::$aRequest['action']='price_change_code';
		                 Base::$tpl->assign('aData',Base::$aRequest['data']);
	        }
	        else {
	            $aData=String::FilterRequestData(Base::$aRequest['data']);
	            $aData['code']= str_replace(" ", "", $aData['code']);
	            $aData['code_replace']= str_replace(" ", "", $aData['code_replace']);
	            if ($aData['pref'])
	                $aData['pref']=Db::GetOne("select pref from cat
    				     where id='".$aData['pref']."'");
	            else
	                $aData['pref']='';
	            $aData['pref_replace']=Db::GetOne("select pref from cat
				     where id='".$aData['pref_replace']."'");
	
	            $aData['manager']= Auth::$aUser['id'];
	            Db::AutoExecute('change_code',$aData);
	            // Base::$sText.="<div class='notice_p'>".Language::GetMessage('Code change added')."</div>";
	            Base::Message(array('MI_NOTICE'=>'added'));
	        }
	    }
	    //Base::$tpl->assign('aPrefAssoc',array(""=>"")+Db::GetAssoc("select id, concat(title,' [',pref,']') name from cat order by title"));
	    $aPrefAssoc = array(""=>"")+Db::GetAssoc("select id, concat(title,' [',pref,']') name from cat order by title");
	    	  
	    if (Base::$aRequest['action']=='price_control_change_code_del') {
	        Db::Execute("delete from change_code where id='".Base::$aRequest['id']."'");
	        Base::Message(array('MI_NOTICE'=>'deleted'));
	    }

	    $aField['brand_cat']=array('title'=>'brand_cat','type'=>'select','options'=>$aPrefAssoc,'name'=>'data[pref]','selected'=>$aData['pref'],'class'=>'js-select', 'value' => $aData['pref'],'szir'=>1);
	    $aField['brandin']=array('title'=>'brandin','type'=>'input','value'=>$aData['cat_in'],'name'=>'data[cat_in]');
	    $aField['code']=array('title'=>'Code','type'=>'input','value'=>$aData['code'],'name'=>'data[code]','szir'=>1);
	    $aField['brand_replace']=array('title'=>'Brand replace','type'=>'select','options'=>$aPrefAssoc,'name'=>'data[pref_replace]','selected'=>$aData['pref_replace'],'class'=>'js-select', 'value' => $aData['pref_replace'],'szir'=>1);
	    $aField['code_replace']=array('title'=>'Code change','type'=>'input','value'=>$aData['code_replace'],'name'=>'data[code_replace]','szir'=>1);
	    $aField['provider']=array('title'=>'Provider','type'=>'select','options'=>$aProviderAssoc,'name'=>'data[id_provider]','selected'=>$aData['id_provider'],'class'=>'js-select', 'value' => $aData['id_provider']);
	    $aField['id']=array('type'=>'hidden','name'=>'data[id]','value'=>$aData['id']);
	    	     
	    $aData=array(
	        'sHeader'=>"method=post",
	        'sTitle'=>"Code change add",
	        //'sContent'=>Base::$tpl->fetch('price_control/form_change_code.tpl'),
	        'aField'=>$aField,
	        'bType'=>'generate',
	        'sSubmitButton'=>'Add',
	        'sSubmitAction'=>'price_control_change_code_add',
	        'sReturnButton'=>'Import change code',
	        'sReturnAction'=>'price_control_change_code_import',
	        'sWidth'=>'50%',
	        'sError'=>$sError,
	
	    );
	    $oForm=new Form($aData);
	    Base::$sText.=$oForm->getForm();

	    unset($aField); 
	    unset($aData); 
	    $aField['brand_cat']=array('title'=>'brand_cat','type'=>'select','options'=>array(""=>"")+Db::GetAssoc("select id, concat(title,' [',pref,']') name from cat order by title"),'name'=>'search[pref]','selected'=>Base::$aRequest['search']['pref'],'class'=>'js-select');
	    $aField['cat_in']=array('title'=>'BrandIn','type'=>'input','value'=>Base::$aRequest['search']['cat_in'],'name'=>'search[cat_in]');
	    $aField['code']=array('title'=>'Code','type'=>'input','value'=>Base::$aRequest['search']['code'],'name'=>'search[code]');
	    $aField['brand_replace']=array('title'=>'brand_replace','type'=>'select','options'=>array(""=>"")+Db::GetAssoc("select id, concat(title,' [',pref,']') name from cat order by title"),'name'=>'search[brand_replace]','selected'=>Base::$aRequest['search']['pref'],'class'=>'js-select');
	    $aField['code_replace']=array('title'=>'Code replace','type'=>'input','value'=>Base::$aRequest['search']['code_replace'],'name'=>'search[code_replace]');
	    $aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("d.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
	    $aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
	    $aField['manager']=array('title'=>'Manager','type'=>'select','options'=>array(""=>"")+Db::GetAssoc("Assoc/UserManager", array('all'=>1)),'name'=>'search[manager]','selected'=>Base::$aRequest['search']['manager'],'class'=>'js-select');
	    $aField['provider']=array('title'=>'Provider','type'=>'select','options'=>$aProviderAssoc,'name'=>'search[id_provider]','selected'=>Base::$aRequest['search']['id_provider'],'class'=>'js-select', 'value' => Base::$aRequest['search']['id_provider']);
		// Debug::PrintPre($aField['code']);
	    $aData=array(
	        'sHeader'=>"method=get",
	        //'sContent'=>Base::$tpl->fetch('price_profile/form_price_profile_search.tpl'),
	        'aField'=>$aField,
	        'bType'=>'generate',
	        'sGenerateTpl'=>'form/index_search.tpl',
	        'sSubmitButton'=>'Search',
	        'sSubmitAction'=>'price_control_change_code',
	        'sReturnButton'=>'Clear',
	        'bIsPost'=>0,
	        'sWidth'=>'30%',
	        'sError'=>$sError,
	    );
	    $oForm=new Form($aData);
	    Base::$sText.=$oForm->getForm();
	
	    $oTable=new Table();
	    $oTable->iRowPerPage=20;
	
	    $aData=Base::$aRequest['search'];
	
	    if (Base::$aRequest['search']['code']) {
	        $aData['code']=Base::$aRequest['search']['code'];
	    }

	    if (Base::$aRequest['search']['cat_in']) {
	        $aData['cat_in']=Base::$aRequest['search']['cat_in'];
	    }

	    if (Base::$aRequest['search']['pref']) {
	        $aData['pref']=Base::$aRequest['search']['pref'];
	    }

	    if (Base::$aRequest['search']['brand_replace']) {
	        $aData['brand_replace']=Base::$aRequest['search']['brand_replace'];
	    }

	    if (Base::$aRequest['search']['code_replace']) {
	        $aData['code_replace']=Base::$aRequest['search']['code_replace'];
	    }
	     
	    if(Base::$aRequest['search']['manager'])
	        $sWhere.=" and (cc.manager = '".Base::$aRequest['search']['manager']."')";
	
	    if(Base::$aRequest['search']['date'] && Base::$aRequest['search']['date_to'] && Base::$aRequest['search']['date_from'])
	        $sWhere.=" and (cc.post_date>='".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
			     and cc.post_date<='".DateFormat::FormatSearch(Base::$aRequest['search']['date_to']." 23:59:59")."')";
	
	    if (Base::$aRequest['search']['id_provider'])
	        $sWhere .=" and up.id_user = ".Base::$aRequest['search']['id_provider'];
	
	    $aData['where']=$sWhere;
	    if($aData || $sWhere) {
	        $oTable->sSql=Base::GetSql("CodeChange",$aData);
	    } else {
	        $oTable->sSql=("select cc.*, c.name, c2.name as name2, up.name as provider,
                    concat(um.login,'::',u.name) as manager_id
               from change_code as cc
	           left join  cat as c on cc.pref=c.pref
               left join  cat as c2 on cc.pref_replace=c2.pref
               left join user_manager as u on cc.manager=u.id_user
               LEFT JOIN user AS um ON cc.manager = um.id
               left join user_provider up on up.id_user = cc.id_provider
	            ");
	    }
	    $oTable->aColumn=array(
	        'name'=>array('sTitle'=>'brand_cat', 'sOrder'=>'c.name'),
	        'cat_in'=>array('sTitle'=>'BrandIn'),
	        'code'=>array('sTitle'=>'Code', 'sOrder'=>'cc.code'),
	        'name2'=>array('sTitle'=>'Brand replace', 'sOrder'=>'name2'),
	        'code_replace'=>array('sTitle'=>'Code_replace', 'sOrder'=>'cc.code_replace'),
	        'provider'=>array('sTitle'=>'Provider', 'sOrder'=>'up.name'),
	        'post_date'=>array('sTitle'=>'Date', 'sOrder'=>'cc.post_date'),
	        'manager_id'=>array('sTitle'=>'Manager', 'sOrder'=>'manager_id'),
	        'action'=>array(),
	    );
	    $oTable->sDataTemplate='price_control/row_change_code.tpl';
	    //$oTable->aOrdered=$sOrder;
	    $sTable=$oTable->getTable("ChangeCode");
	     
	    Base::$sText.=$sTable;
	}
	// -------------------------------------------------------------------------------------------
	public function EditCode () {
	    Resource::Get()->Add('/js/select_search.js');
	
	    Auth::NeedAuth('manager');
	
	    Base::$aTopPageTemplate=array('panel/tab_price.tpl'=>'price_control_edit_code');
	
	    if (Base::$aRequest['is_post'])
	    {
	
	    }
	
	    $aPrefAssoc = array(""=>"")+Db::GetAssoc("select id, concat(title,' [',pref,']') name from cat order by title");
	    $aField['id_cat']=array('title'=>'Brand','type'=>'select','options'=>$aPrefAssoc,'name'=>'search[id_cat]','selected'=>Base::$aRequest['search']['id_cat'],'class'=>'js-select', 'value' => Base::$aRequest['search']['id_cat']);
	
	    $aData=array(
	        'sHeader'=>"method=get",
	        //'sContent'=>Base::$tpl->fetch('price_profile/form_price_profile_search.tpl'),
	        'aField'=>$aField,
	        'bType'=>'generate',
	        'sGenerateTpl'=>'form/index_search.tpl',
	        'sSubmitButton'=>'Search',
	        'sSubmitAction'=>'price_control_edit_code',
	        'sReturnButton'=>'Clear',
	        'bIsPost'=>0,
	        'sWidth'=>'30%',
	        'sError'=>$sError,
	    );
	    $oForm=new Form($aData);
	    Base::$sText.=$oForm->getForm();
	
	    $oTable=new Table();
	    $oTable->iRowPerPage=20;
	    $oTable->aOrdered=" order by name ";
	
	    $sWhere='';
	    if (Base::$aRequest['search']['id_cat'])
	        $sWhere .=  " and id = ".Base::$aRequest['search']['id_cat'];
	     
	    $oTable->sSql="select * from cat where 1=1 ".$sWhere;
	
	    $oTable->aColumn=array(
	        'name'=>array('sTitle'=>'name', 'sOrder'=>'name'),
	        'title'=>array('sTitle'=>'Title'),
	        'parser_info'=>array('sTitle'=>'parser_info'),
	        'action'=>array(),
	    );
	    $oTable->sDataTemplate='price_control/row_edit_code.tpl';
	    $sTable=$oTable->getTable("EditCode");
	    Base::$oContent->AddCrumb(Language::GetMessage('edit code'),'');
	    Base::$sText.=$sTable;
	}
	//-----------------------------------------------------------------------------------------------
	public function ChangeCodeEdit() {
	    Base::$oContent->AddCrumb(Language::GetMessage('change code'),'/pages/price_control_change_code');
	    Base::$oContent->AddCrumb(Language::GetMessage('change code edit'),'');
	    
	    if(Auth::$aUser['type_']=='customer' && (!Auth::$aUser['can_change_code'] || !Auth::$aUser['card_editor']))
	        Auth::NeedAuth('manager');
	    
	    Base::$aTopPageTemplate=array('panel/tab_price.tpl'=>'price_change_code');
	    $aPrefAssoc = array(""=>"")+Db::GetAssoc("select id, concat(title,' [',pref,']') name from cat order by title");
	    $aProviderAssoc = array("" => Language::getMessage('not selected')) + Db::getAssoc("Select u.id as key_, u.login
		        from user_provider up
				inner join user u on u.id = up.id_user and u.type_='provider'
			    order by u.login");
	     
	    if (Base::$aRequest['is_post'])
	    {
	        if (!Base::$aRequest['data']['pref'] || !Base::$aRequest['data']['code'] || !Base::$aRequest['data']['code_replace']){
	            $sError=("Please, fill the required fields");
	            Base::$aRequest['action']='price_change_code';
	            Base::$tpl->assign('aData',Base::$aRequest['data']);
	        }
	        elseif  (Base::$aRequest['data']['code'] == Base::$aRequest['data']['code_replace']){
	            $sError=("Code replace and code must be different ");
	            Base::$aRequest['action']='price_change_code';
	            Base::$tpl->assign('aData',Base::$aRequest['data']);
	
	        }
	        else {
	            $aData=String::FilterRequestData(Base::$aRequest['data']);
	            $aData['code']= str_replace(" ", "", $aData['code']);
	            $aData['code_replace']= str_replace(" ", "", $aData['code_replace']);
	            $aData['pref']=Db::GetOne("select pref from cat
				     where id='".$aData['pref']."'");
	            $aData['pref_replace']=Db::GetOne("select pref from cat
				     where id='".$aData['pref_replace']."'");
	            $aData['post_date']= date("Y-m-d H:i:s");
	            $aData['manager']= Auth::$aUser['id'];
	            Db::AutoExecute('change_code',$aData,'UPDATE','id = '.Base::$aRequest['data']['id']);
	            //Base::$sText.="<div class='notice_p'>".Language::GetMessage('Code change edit')."</div>";
	            Base::Message(array('MI_NOTICE'=>'edit'));
	        }
	
	    }
	    $aRow=Db::GetRow("select cc.*, c.name, c2.name as name2, c.id as id_pref , c2.id as id_pref2
                	            from change_code as cc
                	            left join  cat as c on cc.pref=c.pref
	                            left join  cat as c2 on cc.pref_replace=c2.pref
                	            where cc.id = '".Base::$aRequest['id']."'");
	    Base::$tpl->assign('aRow',$aRow);
	    
	    $aField['brand_cat']=array('title'=>'brand_cat','type'=>'select','options'=>$aPrefAssoc,'name'=>'data[pref]','selected'=>$aRow['id_pref'],'class'=>'js-select', 'value' => $aRow['id_pref'],'szir'=>1);
	    $aField['brandin']=array('title'=>'brandin','type'=>'input','value'=>$aRow['cat_in'],'name'=>'data[cat_in]');
	    $aField['code']=array('title'=>'Code','type'=>'input','value'=>$aRow['code'],'name'=>'data[code]','szir'=>1);
	    $aField['brand_replace']=array('title'=>'Brand replace','type'=>'select','options'=>$aPrefAssoc,'name'=>'data[pref_replace]','selected'=>$aRow['id_pref2'],'class'=>'js-select', 'value' => $aRow['id_pref2'],'szir'=>1);
	    $aField['code_replace']=array('title'=>'Code change','type'=>'input','value'=>$aRow['code_replace'],'name'=>'data[code_replace]','szir'=>1);
	    $aField['provider']=array('title'=>'Provider','type'=>'select','options'=>$aProviderAssoc,'name'=>'data[id_provider]','selected'=>$aRow['id_provider'],'class'=>'js-select', 'value' => $aRow['id_provider']);
	    $aField['id']=array('type'=>'hidden','name'=>'data[id]','value'=>$aRow['id']);
	    
	    $aData=array(
	        'sHeader'=>"method=post",
	        'sTitle'=>"Code change edit",
	        //'sContent'=>Base::$tpl->fetch('price/form_change_code_edit.tpl'),
	        'aField'=>$aField,
	        'bType'=>'generate',
	        'sSubmitButton'=>'Save',
	        'sSubmitAction'=>'price_control_change_code_edit',
	        'sReturnButton'=>'<< Return',
	        'bAutoReturn'=>true,
	        'sWidth'=>'750px',
	        'sError'=>$sError,
	
	    );
	    $oForm=new Form($aData);
	    Base::$sText.=$oForm->getForm();
	}
	//----------------------------------------------------------------------------------------------
	public function ChangeCodeImport () {
	
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
	                        $aData['pref_replace']=$aPref[strtoupper(trim($aValue[3]))];
	                        $aData['code_replace']=Catalog::StripCode(strtoupper($aValue[4]));
	                        $aData['manager']=Auth::$aUser['id_user'];
	                         
	                        if ($aData['pref'] && $aData['code'] && $aData['pref_replace'] && $aData['code_replace'])
	                        {
	                            Db::Execute($s= " insert ignore into  change_code (pref, code, pref_replace, code_replace, manager)
					                          values ('".$aData['pref']."','".$aData['code']."','".$aData['pref_replace']."','".$aData['code_replace']."','".$aData['manager']."')
					                          on duplicate key update pref=values(pref), code=values(code), pref_replace=values(pref_replace), code_replace=values(code_replace), manager=values(manager)
					                       ");
	                        }
	                    }
	                }
	            $sMessage="&aMessage[MF_NOTICE_NT]=".Language::GetMessage("Upload from file")." ".$_FILES['import_file']['name']." ".Language::GetMessage("succsessfully");
	            Form::RedirectAuto($sMessage);
	        }
	        else Base::Message(array('MI_ERROR'=>'Possible file upload attack'));
	    }
	     
	    Base::Message();
	     
	    $aField['default_file_to_import']=array('type'=>'text','value'=>Language::GetText("Default file to import code change"),'colspan'=>2);
	    $aField['import_file']=array('title'=>'File to import','type'=>'file','name'=>'import_file');
	     
	    $aData=array(
	        'sHeader'=>"method=post enctype='multipart/form-data'",
	        'aField'=>$aField,
	        'bType'=>'generate',
	        'sSubmitButton'=>'Load',
	        'sSubmitAction'=>$this->sPrefix.'_change_code_import',
	        'sReturnButton'=>'<< Return',
	        'sReturnAction'=>'pages/price_change_code',
	        'bAutoReturn'=>true,
	        'sWidth'=>"400px",
	    );
	    $oForm=new Form($aData);
	    Base::$sText.=$oForm->getForm();
	     
	}
	//---------------------------------------------------------------------------------------------
	public function UpdateCatParse () {
	    if (!Base::$aRequest['data']) {
	        Base::$oResponse->addScript("window.location='/pages/price_edit_code'");
	        return;
	    }
	    $sData = base64_decode(Base::$aRequest['data']);
	    $aData=explode("&", $sData);
	    if (!$aData) {
	        Base::$oResponse->addScript("window.location='/pages/price_edit_code'");
	        return;
	    }
	    $aParam = array();
	    foreach ($aData as $sValue) {
	        list($name,$val) = explode('=',$sValue);
	        $aParam[$name] = $val;
	    }
	    if (!$aParam['id']) {
	        Base::$oResponse->addScript("window.location='/pages/price_edit_code'");
	        return;
	    }
	    $aCat = Db::getRow("Select * from cat where id=".$aParam['id']);
	    if (!$aCat) {
	        Base::$oResponse->addScript("window.location='/pages/price_edit_code'");
	        return;
	    }
	
	    $parser_before = ($aParam['parser_before'] ? $aParam['parser_before'] : '');
	    $parser_after = ($aParam['parser_after'] ? $aParam['parser_after'] : '');
	    $trim_left_by = ($aParam['trim_left_by'] ? $aParam['trim_left_by'] : '');
	    $trim_right_by = ($aParam['trim_right_by'] ? $aParam['trim_right_by'] : '');
	
	    Db::Execute("Update cat set parser_before='".$parser_before."', parser_after='".$parser_after.
	    "', trim_left_by='".$trim_left_by."', trim_right_by='".$trim_right_by."'
    where id='".$aCat['id']."'");
	    Base::$oResponse->addScript("alert('Сохранено')");
	}
	//AZP-44 end------------------------------------------------------------------------------------
	// по текдоку и cat_part
	// $isAllBuffer=1 и $sPref - при обновлении бренда в работе над ошибками прайса
	public function CheckTecDocCode($iIdPriceQueue=0, $isAllBuffer=0, $sPref='') {
	    if (!$iIdPriceQueue && !$isAllBuffer)
	        return;
	
	    if ($isAllBuffer && !$sPref)
	        return;
	     
	    $sWhereNTDConfirm = ' and (pf.id is null or pf.is_not_tecdoc_brand_need_confirm_code=1 or
	       c.id_sup!=0 or c.id_mfa!=0)';
	    $sWhereNTDConfirmNot = ' and (pf.id is not null and pf.is_not_tecdoc_brand_need_confirm_code=0 and
	       c.id_sup=0 and c.id_mfa=0)'; 
	    
	    if ($isAllBuffer) {
	        $sWhere = " and pi.pref='".$sPref."'";
	        $iAll = Db::getOne("SELECT count(*) FROM price_import pi
    	    WHERE 1=1 ".$sWhere);
	        // обнулить признак перед проверкой в буфере
	        Db::Execute("Update price_import pi 
	            left join price_queue pq on pq.id = pi.id_price_queue 
	            left join price_profile pf on pf.id = pq.id_price_profile
	            left join cat c on c.pref = pi.pref
	            set pi.is_code_ok=0, pi.is_checked_code=0
	            where 1=1 ".$sWhereNTDConfirm.$sWhere);
	    }
	    else {
	            Db::Execute("Update price_import pi  
	                left join cat c on c.pref = pi.pref
       	            left join price_queue pq on pq.id = pi.id_price_queue 
	                left join price_profile pf on pf.id = pq.id_price_profile
	                set pi.is_code_ok=0, pi.is_checked_code=0
	                where (pi.id_price_queue=".$iIdPriceQueue.$sWhereNTDConfirm.
	                   ") or pi.pref IS NULL or pi.pref = ''");

	            $isNeedConfirmCodeNonTecDoc = Db::getOne("Select pp.is_not_tecdoc_brand_need_confirm_code
	                from price_queue pq
	                inner join price_profile pp on pp.id = pq.id_price_profile
	                where pq.id = ".$iIdPriceQueue);

	            $sWhere = " and c.id_sup!=0 AND pi.pref IS NOT NULL AND pi.pref != '' and pi.id_price_queue=".$iIdPriceQueue;
	            if ($isNeedConfirmCodeNonTecDoc) {
	                //  не tecdoc установить подтверждены, если флаг
	                Db::Execute("Update price_import pi
	                inner join cat c on c.pref = pi.pref
	                inner join price_queue pq on pq.id = pi.id_price_queue
	                inner join price_profile pf on pf.id = pq.id_price_profile
	                set pi.is_code_ok=1, pi.is_checked_code=1
	                where pi.id_price_queue = ".$iIdPriceQueue.$sWhereNTDConfirmNot);
	                 
	                $sWhere = " AND pi.pref IS NOT NULL AND pi.pref != '' and pi.id_price_queue=".$iIdPriceQueue;
	            }
	                 
	            $iAll = Db::getOne("SELECT count(*) FROM price_import pi inner join cat as c on c.pref=pi.pref WHERE 1=1 ".$sWhere);
	    }
	     
	    $iPortion = Language::getConstant("limit_price_import_check_code",1000);
	    while(1) {
            // если обработку прервали по времени / руками
	        if ($iIdPriceQueue && Price::getStoppedQueueFlag($iIdPriceQueue)) {
	            Base::$aData['stop_load_price']=4;
	            return;
	        }
	        
	        $aIds = array();
	        $aData = Db::getAssoc($s="Select pi.id as key_, pi.*, c.id_sup, c.id_mfa, c.id_sync, cp.is_checked_code_ok as cp_is_checked_code_ok
	            from price_import pi
	            inner join cat c on c.pref = pi.pref 
	            left join cat_part cp on cp.item_code = pi.item_code
	            where 1=1 ".$sWhere." and is_checked_code=0 limit ".$iPortion);

	        if (!$aData)
	            break;
	
	        $aCodes = $aItemCodes = $isCodeOk = $isCodeBad = $aTecDocData = $aTecDocDataOriginal = array();
	        foreach ($aData as $aValue) {
	            // cat_part ok
	            if ($aValue['cp_is_checked_code_ok']) {
	                $isCodeOk[$aValue['item_code']] = 1;
	                continue;
	            }
	            // не текдок, пропускаем
	            if (!$aValue['id_sup'] && $aValue['id_mfa']) {
	                $isCodeBad[$aValue['item_code']] = 1;
	                continue;
	            }
	            
	            if ($aValue['id_sup'])
	               $aItemCodes[$aValue['code']."_".$aValue['id_sup']] = $aValue['item_code'];
	            if ($aValue['id_mfa'])
	               $aItemCodes[$aValue['code']."_".$aValue['id_mfa']] = $aValue['item_code'];
	            if ($aValue['id_sync']!='') {
	                $aIds = explode(",",$aValue['id_sync']);
	                if ($aIds)
	                    foreach ($aIds as $sId)
	                        $aItemCodes[$aValue['code']."_".$sId] = $aValue['item_code'];
	            }
	            $aCodes[$aValue['code']] = 1;
	        }
	        if ($aCodes) {
	            $aTecDocData = TecdocDb::getAssoc("SELECT concat(a.Search,'_',s.ID_src) as key_, a.Article
    	        FROM ".DB_OCAT."`cat_alt_articles` a
    	        INNER JOIN ".DB_OCAT."cat_alt_suppliers s ON s.ID_sup = a.ID_sup
    	        WHERE a.Search
    	        IN ( '".implode("','",array_keys($aCodes))."')");
	             
	            $aTecDocDataOriginal = TecdocDb::getAssoc("Select concat(o.oe_code,'_',o.oe_brand) as key_, o.oe_code
    	        from ".DB_OCAT."cat_alt_original o
    	        inner join ".DB_OCAT."cat_alt_manufacturer m ON m.ID_src = o.oe_brand
    	        where o.oe_code in ( '".implode("','",array_keys($aCodes))."')");
	        }
	
	        if ($aTecDocData) {
	            foreach ($aItemCodes as $sKey => $sItemCode) {
	                if ($aTecDocData[$sKey])
	                    $isCodeOk[$sItemCode] = 1;
	            }
	        }
	        if ($aTecDocDataOriginal) {
	            foreach ($aItemCodes as $sKey => $sItemCode) {
	                if ($aTecDocDataOriginal[$sKey])
	                    $isCodeOk[$sItemCode] = 1;
	            }
	        }
	        // остальные в ошибки
	        foreach($aData as $aValue) {
	            if (!$isCodeOk[$aValue['item_code']])
	                $isCodeBad[$aValue['item_code']]=1;
	        }
	        
            // установить признак код ok
            if ($isCodeOk) 
                Db::Execute($s="Update price_import pi inner join cat as c on c.pref=pi.pref set pi.is_code_ok=1, pi.is_checked_code=1 where 1=1 ".$sWhere.
                    " and pi.is_checked_code=0 and pi.item_code in ('".implode("','", array_keys($isCodeOk))."')");

	        // установить признак - проверено остальным
	        if ($isCodeBad)
	        Db::Execute("Update price_import pi inner join cat as c on c.pref=pi.pref set pi.is_checked_code=1 where 1=1 ".$sWhere.
	            " and pi.is_checked_code=0 and pi.item_code in ('".implode("','", array_keys($isCodeBad))."')");
            	             
            if (!$isAllBuffer) {
                $iWorked = Db::getOne("SELECT count(*)
                FROM price_import pi
                inner join cat as c on c.pref=pi.pref
                WHERE 1=1 ".$sWhere." and pi.is_checked_code=1");

                $fProgress = floatval(($iWorked / $iAll) * 100);
                if ($fProgress > 100)
                    $fProgress = 100;
                Db::Execute("update price_queue set progress_check_code=".$fProgress." where id=".$iIdPriceQueue);
            }
	    }
	    //
	    if (!$isAllBuffer) {
	        Db::Execute("update price_queue set progress_check_code=100 where id=".$iIdPriceQueue);
	        return Db::getOne("Select count(*) from price_import where id_price_queue=".$iIdPriceQueue." and is_code_ok=0");
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function LockedCode() {
	    Base::$oContent->AddCrumb(Language::GetMessage('locked code'),'');
	    
	    Base::$aTopPageTemplate=array('panel/tab_price.tpl'=>'price_control_locked_code');
	    
        require_once(SERVER_PATH.'/class/core/Form.php');

        Resource::Get()->Add('/js/select_search.js');

        $aField['code']=array('title'=>'CodeInPrice','type'=>'input','value'=>Base::$aRequest['code_in'],'name'=>'code_in');
        // $aField['brand']=array('title'=>'BrandIn','type'=>'input','value'=>Base::$aRequest['brand_in'],'name'=>'brand_in');
        $aField['brand']=array('title'=>'BrandIn','type'=>'select','options'=>array(""=>"")+Db::GetAssoc("select cat_in, cat_in as name from price_import_locked group by cat_in order by cat_in"),'name'=>'cat_in','selected'=>Base::$aRequest['search']['pref'],'class'=>'js-select');

        $aProviderAssoc = Db::getAssoc("Select up.id_user, up.name
    	from user u
    	inner join user_provider up on u.id = up.id_user
    	where u.type_='provider' and u.visible order by up.name");
        $aField['provider']=array('title'=>'Provider','type'=>'select','options'=>array('' => Language::getMessage('not selected'))+$aProviderAssoc,'name'=>'id_provider','selected'=>Base::$aRequest['id_provider'],'class'=>'select_name_provider js-select');
        $aField['manager']=array('title'=>'Manager','type'=>'select','options'=>array(""=>"")+Db::GetAssoc("Assoc/UserManager", array('all'=>1)),'name'=>'search[manager]','selected'=>Base::$aRequest['search']['manager'],'class'=>'js-select');
        $aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("Y-m-1",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'yyyy-mm-dd')",'checkbox'=>1);
        $aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("Y-m-d",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'yyyy-mm-dd')");

        $aData=array(
            'sHeader'=>"method=post",
            'aField'=>$aField,
            'bType'=>'generate',
            'sSubmitButton'=>'Select',
            'sReturnButton'=>'Clear',
            'sSubmitAction'=>'price_control_locked_code',
            'sError'=>$sError,
            'sGenerateTpl'=>'form/index_search.tpl',
        );
        $oForm->bAutoReturn=true;
        $oForm=new Form($aData);

        Base::$sText.=$oForm->getForm();

        $sWhere = '';
        if (Base::$aRequest['code_in'])
            $sWhere = " and (pil.code_in like '%".Base::$aRequest['code_in']."%')";

        if (Base::$aRequest['id_provider'])
            $sWhere .= " and pil.id_provider=".Base::$aRequest['id_provider'];

        if (Base::$aRequest['cat_in'])
            $sWhere .= " and pil.cat_in like '%".Base::$aRequest['cat_in']."%'";

        if (Base::$aRequest['search']['manager'])
            $sWhere .= " and pil.id_manager='".Base::$aRequest['search']['manager']."'";

        if (Base::$aRequest['search']['date']) {
            $sDateFrom=DateFormat::FormatSearch(Base::$aRequest['search']['date_from'],"Y-m-d 00:00:00");
            $sDateTo=DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59");
            $sWhere .= " and pil.date_set>='".$sDateFrom."' and pil.date_set<='".$sDateTo."' ";
        }

        require_once(SERVER_PATH.'/class/core/Table.php');
        $oTable=new Table();
        $oTable->sSql="select pil.*, m.login as manager_login,
			up.name as name_provider, u.login as provider_login, um.name as manager_name
			from price_import_locked pil
			inner join user u on pil.id_provider=u.id
			inner join user_provider up on up.id_user = pil.id_provider
			left join user m on pil.id_manager=m.id
			left join user_manager as um on um.id_user = m.id
		 	where 1=1 ".$sWhere;

        $oTable->aColumn=array(
            'code_in'=>array('sTitle'=>'CodeInPrice','sWidth'=>'15px'),
            'cat_in'=>array('sTitle'=>'brandIn'),
            'provider'=>array('sTitle'=>'provider'),
            'date_set'=>array('sTitle'=>'date_set','sWidth'=>'20%'),
            'id_manager'=>array('sTitle'=>'manager','sWidth'=>'20%'),
            'action'=>array(),
        );
        $oTable->aOrdered="order by pil.id desc";
        $oTable->iRowPerPage=100;
        $oTable->sDataTemplate='price_control/row_locked_code.tpl';

        Base::$sText.=$oTable->getTable("Locked list import price");
	}
	//-----------------------------------------------------------------------------------------------
	public function UnLockedCode() {
        Db::Execute("Delete from price_import_locked where id=".Base::$aRequest['id']);
	    Base::Redirect('/pages/price_control_locked_code');
	}
	//-----------------------------------------------------------------------------------------------
	// переделана из Price::Install
	public function Install($bRedirect=true) {	    
	    // clear all null price - на странице работы над ошибками
	    if (Base::$aRequest['install_ok']) {
	        $sWhere = " and (((pq.is_processed!=3 and pq.progress=100 ) and t.id_price_queue is not null) or t.id_price_queue is null) ";
	    }
	    else {
	        $sWhere = " and (((pq.is_processed=2 and pq.progress=100) and t.id_price_queue is not null) or t.id_price_queue is null) ";
	    }
	    
	    $sWherePref=" and (t.pref is not null and t.pref != '') ";
	    $sWhereNotError = " and (t.price!=0 and t.id_provider is not null
		    and t.id_provider!=0 and t.code!='' and t.is_code_ok=1) ";
	    
	    // $aPriceProfile['delete_before']==1
	    $aClearData=Base::$db->GetAssoc("select distinct(t.id_provider) as id, t.id_provider
			from price_import t
			inner join price_queue pq on pq.id=t.id_price_queue
			inner join price_profile pp on pp.id=pq.id_price_profile
			where 1=1 ".str_replace("and t.id_price_queue is not null) or t.id_price_queue is null", ")", $sWhere)."
				and pp.delete_before=1
				".$sWherePref.$sWhereNotError." 
				and (t.id_user='".Auth::$aUser['id']."' or t.id_user=1)
			group by t.id_provider");
	    
	    if ($aClearData) Base::$db->Execute("update price set price=0 where id_provider in (".implode(',',array_keys($aClearData)).")");
	    
	    // через функцию, так как надо в разных местах исп-ть
	    PriceControl::OnlyInstallPrice($sWhere);
	    
	    $sMessage="&aMessage[MI_NOTICE]=Price installed successful";
	    Price::ClearImport($sMessage,$bRedirect,$sType = 'ignore_empty');
	}
	// -------------------------------------------------------------------------------------------
	public function OnlyInstallPrice($sWhere,$sPref = '') {
	    if (!$sPref)
	        $sWherePref=" and (pref is not null and pref != '')";
	    else
	        $sWherePref=" and pref='".$sPref."'";
	
	    $sWhereNotError = " and (t.price!=0 and t.id_provider is not null
		    and t.id_provider!=0 and t.code!='' and t.is_code_ok=1) ";
/*	замена сразу, иначе контроль кода не пройдет
	    // замены с сущ-щими префиксами
	    Base::$db->Execute("update price_import as pi,
	    (select pi.id,cc.code_replace,c.title,cc.pref,cc.pref_replace
		        from price_import as pi
        		inner join change_code cc on cc.code = pi.code and cc.pref=pi.pref
        		inner join cat c on c.pref = cc.pref_replace
        		left join user_provider up on up.id_user = cc.id_provider and pi.id_provider=cc.id_provider
        		where pi.code=cc.code and pi.pref=cc.pref and
		          (cc.id_provider=0 or up.id_user is not null) group by pi.id
    		) as b2
    		set pi.code=b2.code_replace,
    		pi.pref=if(b2.pref_replace='',b2.pref,b2.pref_replace),
    		pi.cat=b2.title,
    		pi.item_code=concat(if(b2.pref_replace='',b2.pref,b2.pref_replace),'_',b2.code_replace)
    		where pi.id = b2.id");
	
		// замены по входящему бренду
		Base::$db->Execute("update price_import as pi,
			(select pi.id,cc.code_replace,c.title,cc.cat_in,cc.pref_replace
		        from price_import as pi
        		inner join change_code cc on cc.code = pi.code and cc.cat_in=pi.cat
        		inner join cat c on c.pref = cc.pref_replace
        		left join user_provider up on up.id_user = cc.id_provider and pi.id_provider=cc.id_provider
        		where pi.code=cc.code and pi.cat=cc.cat_in and
		          (cc.id_provider=0 or up.id_user is not null) group by pi.id
    		) as b2
    		set pi.code=b2.code_replace,
    		pi.pref=if(b2.pref_replace='','',b2.pref_replace),
    		pi.cat=b2.title,
    		pi.item_code=concat(if(b2.pref_replace='','',b2.pref_replace),'_',b2.code_replace)
    		where pi.id = b2.id");
*/		
		Base::$db->Execute(" insert into price
			    (item_code, id_provider, code, cat, cat_in, part_rus, part_eng, price, code_in, pref, description, stock, term, grp, number_min, is_restored, is_delayed_associate, id_margin_price)
			select item_code, id_provider, code, cat, cat_in, part_rus, part_eng, price, code_in, pref, description, stock, term, grp, number_min, is_restored, is_delayed_associate, id_margin_price
			from price_import as t
			LEFT JOIN price_queue AS pq ON pq.id = t.id_price_queue
			where 1=1 ".$sWhere."
			and (t.id_user='".Auth::$aUser['id']."' or t.id_user=1)
			".$sWherePref.$sWhereNotError."
			on duplicate key update price=values(price)
				, part_rus=values(part_rus), part_eng=values(part_eng), code_in=values(code_in), cat_in=values(cat_in), description=values(description)
			, term=values(term), stock=values(stock), grp=values(grp), number_min=values(number_min), is_restored=values(is_restored)
			, is_delayed_associate=values(is_delayed_associate), id_margin_price=values(id_margin_price)"
		);
		unset(Base::$aData['is_change_items_price']);
		$iCountCodeOk = Db::AffectedRow();
		if($iCountCodeOk)
		    Base::$aData['is_change_items_price'] = $iCountCodeOk;
	
		Db::Execute("insert into price_group_assign (item_code,id_price_group,pref)
		    select item_code,id_price_group,pref
		    from price_import t
		    where update_group>0 and is_delayed_associate=0 ".$sWherePref.$sWhereNotError."
			on duplicate key update
				id_price_group = values(id_price_group),
		         pref = values(pref)
    	");
	}
	//-----------------------------------------------------------------------------------------------
	public function DeleteItems()
	{
	    if (Base::$aRequest['row_check']) {
	        Db::Execute("Delete from price_import where id in (".implode(',',Base::$aRequest['row_check']).")");
	    }
	    if (Base::$aRequest['return_action_buffer'])
	        Base::Redirect(urldecode(Base::$aRequest['return_action_buffer']).'&aMessage[mt_notice_nt]='.Language::getMessage('items_deleted'));
	    else
   	        Base::Redirect('/pages/price_control?aMessage[mt_notice_nt]='.Language::getMessage('items_deleted'));
	}
	//-----------------------------------------------------------------------------------------------
	public function DeleteFilteredItems()
	{
	    if ($_SESSION['analize_buffer_price']['current_sql']) {
	        Db::Execute("delete price_import from price_import inner join (".$_SESSION['analize_buffer_price']['current_sql'].") as a on a.id = price_import.id");
	    }
	    if (Base::$aRequest['return_action_buffer'])
	        Base::Redirect(urldecode(Base::$aRequest['return_action_buffer']).'&aMessage[mt_notice_nt]='.Language::getMessage('items_deleted'));
	    else
   	        Base::Redirect('/pages/price_control?aMessage[mt_notice_nt]='.Language::getMessage('items_deleted'));
	}
	//-----------------------------------------------------------------------------------------------
	public function UpdateSelectProvider() {
	    // error
	    $sErrorStr = "(pi.price=0 or pi.id_provider=0 or pi.id_provider is null
		    or pi.code='' or pi.pref='' or pi.pref is null or pi.is_code_ok=0) ";

	    // not error
	    $sWhere = " and (".$sErrorStr.") ";
	     
	    // end work
	    $sWhere .= " and (pq.is_processed=2 or pq.is_processed=3) ";
	    
	    $aData = array('' => 'Все');
	    if (Base::$aRequest['id_provider']) {
	        $aProviderAssoc = Db::getAssoc("Select id, login from user where type_='provider' and login='".Base::$aRequest['id_provider']."'");
            $aData = $aData + $aProviderAssoc;
	
	        $aBrandAssoc = Db::getAssoc("Select if(pi.cat='','is_empty',pi.cat) as key_, if(pi.cat='','без бренда',pi.cat) as name from price_import pi
			inner join price_queue pq on pq.id = pi.id_price_queue
			where pi.id_provider in (".implode(",",array_keys($aProviderAssoc)).") ".$sWhere." group by pi.cat order by pi.cat");
	    }
	    else 
            $aBrandAssoc = Db::getAssoc("Select if(pi.cat='','is_empty',pi.cat) as key_, if(pi.cat='','без бренда',pi.cat) as name from price_import pi
			inner join price_queue pq on pq.id = pi.id_price_queue
			where 1=1 ".$sWhere." group by pi.cat order by pi.cat");
	        
	    $aInput = array(
	        'title'=>'Brand price',
	        'type'=>'select',
	        'options'=>array('' => 'Все') + $aBrandAssoc,
	        'selected'=>'',
	        'name'=>'id_cat',
	        'id' => 'id_cat'
	    );
	    Base::$tpl->assign('aInput',$aInput);
	    $sElement = Base::$tpl->fetch('form/select.tpl');
	    Base::$oResponse->AddAssign('id_cat','outerHTML',$sElement);
	    Base::$oResponse->AddScript("$.uniform.update();");
	}
	// -------------------------------------------------------------------------------------------
	public function UpdatePriceImport($iCatId=0,$sCatImportPrice='',$sPref='',$aRowPriceImport=0) {
	    if (!$iCatId || !$sPref)
	        return;
	
	    $aCat=Db::GetRow("select * from cat where id=".$iCatId);
	    if (!$aCat)
	        return;
	
	    // update pref, item_code
	    if (!$aCat['parser_before'] && !$aCat['parser_after'] && !$aCat['trim_left_by'] && !$aCat['trim_right_by'])
	    {
	        // update after else
	    }
	    else {
	        // только 1 запись буфера очистка кода
	        if ($aRowPriceImport) {
	            $sCode = '';
	            if($aCat['parser_patern'])
	                $sCode=trim(preg_replace('/.*('.$aCat['parser_patern'].').*/i','\1',$aRowPriceImport['code_in']));
	            if($aCat['parser_before']){
	                if(!$sCode) $sCode=$aRowPriceImport['code_in'];
	                $sCode=trim(preg_replace('/^('.$aCat['parser_before'].')(.*)/i','\2',$sCode));
	            }
	            if($aCat['parser_after']){
	                if(!$sCode) $sCode=$aRowPriceImport['code_in'];
	                $sCode=trim(preg_replace('/('.$aCat['parser_after'].')(.*)/i','\2',$sCode));
	            }
	            if($aCat['trim_left_by']){
	                if(!$sCode) $sCode=$aRowPriceImport['code_in'];
	                $iPos=strpos($sCode,$aCat['trim_left_by']);
	                if($iPos!==FALSE) $sCode=substr($sCode,$iPos+1);
	            }
	            if($aCat['trim_right_by']){
	                if(!$sCode) $sCode=$aRowPriceImport['code_in'];
	                $iPos=strpos($sCode,$aCat['trim_right_by']);
	                if($iPos!==FALSE) $sCode=substr($sCode,0,$iPos);
	            }
	            	
	            if ($sCode) {
	                $sCode = trim($sCode);
	                $sCode = Catalog::StripCode($sCode);
	            }

	            if ($sCode && strcasecmp($sCode,$aRowPriceImport['code'])!=0)
	                Db::Execute("Update price_import set code='".$sCode."' where id=".$aRowPriceImport['id']);
	        }
	        else {
	            // rebuild code, item_code
	            $i=0;
	            $iPortion = Language::getConstant("limit_price_import_rebuild_code",10000);
	            $iChangeRecord=0;
	            while(1) {
	                if ($sCatImportPrice)
	                   $aData = Db::getAll("Select * from price_import
								where cat='".mysql_real_escape_string($sCatImportPrice)."' limit ".$i*$iPortion.",".$iPortion);
	                else
	                    $aData = Db::getAll("Select * from price_import
								where pref='".$sPref."' limit ".$i*$iPortion.",".$iPortion);
	                
	                if (!$aData)
	                    break;
	                foreach ($aData as $aValue) {
	                    $sCode = '';
	                    if($aCat['parser_patern'])
	                        $sCode=trim(preg_replace('/.*('.$aCat['parser_patern'].').*/i','\1',$aValue['code_in']));
	                    if($aCat['parser_before']){
	                        if(!$sCode) $sCode=$aValue['code_in'];
	                        $sCode=trim(preg_replace('/^('.$aCat['parser_before'].')(.*)/i','\2',$sCode));
	                    }
	                    if($aCat['parser_after']){
	                        if(!$sCode) $sCode=$aValue['code_in'];
	                        $sCode=trim(preg_replace('/('.$aCat['parser_after'].')(.*)/i','\2',$sCode));
	                    }
	                    if($aCat['trim_left_by']){
	                        if(!$sCode) $sCode=$aValue['code_in'];
	                        $iPos=strpos($sCode,$aCat['trim_left_by']);
	                        if($iPos!==FALSE) $sCode=substr($sCode,$iPos+1);
	                    }
	                    if($aCat['trim_right_by']){
	                        if(!$sCode) $sCode=$aValue['code_in'];
	                        $iPos=strpos($sCode,$aCat['trim_right_by']);
	                        if($iPos!==FALSE) $sCode=substr($sCode,0,$iPos);
	                    }
	                    	
	                    if ($sCode) {
	                        $sCode = trim($sCode);
	                        $sCode = Catalog::StripCode($sCode);
	                    }
	                    	
	                    if ($sCode && strcasecmp($sCode,$aValue['code'])!=0) {
	                        Db::Execute("Update price_import set code='".$sCode."' where id=".$aValue['id']);
	                        $iChangeRecord +=1;
	                    }
	                }
	                $i+=1;
	            }
	        }
	    }
	
	    if (!$aRowPriceImport) {
	        if ($sCatImportPrice)
	            Base::$db->Execute("
					update price_import
					set pref='".$sPref."', item_code=concat('".$sPref."','_',code)
					where cat='".$sCatImportPrice."'");
	        else
	            Base::$db->Execute("
    					update price_import
    					set item_code=concat('".$sPref."','_',code)
    					where pref='".$sPref."'");
	    }
	    else
	        Base::$db->Execute("
					update price_import
					set pref='".$sPref."', item_code=concat('".$sPref."','_',code)
					where id='".$aRowPriceImport['id']."'");
	
	    // проверка кодов после обновления брендa по всему буферу
	    if (!$aRowPriceImport) {
	        PriceControl::CheckTecDocCode(0, 1, $sPref);
	        unset(Base::$aData['is_change_buffer_price']);
	        if($iChangeRecord)
	            Base::$aData['is_change_buffer_price'] = $iChangeRecord;
	    }
	
	    // install price
	    // загруженная запись в буфер
	    $sWhere = " and (((pq.is_processed=2 and pq.progress=100) and t.id_price_queue is not null) or t.id_price_queue is null) ";
	    PriceControl::OnlyInstallPrice($sWhere,$sPref);
	    PriceControl::ClearImportPref($sWhere,$sPref);
	}
	// -------------------------------------------------------------------------------------------
	public function ClearImportPref($sWhere='',$sPref='') {
	    if (!$sPref)
	        return;
	
	    $sWhereNotError = " and (t.price!=0 and t.id_provider is not null and t.id_provider!=0 and t.code!='' and t.is_code_ok=1) ";
	
	    Base::$db->Execute("delete t from `price_import` t
			left join price_queue pq on pq.id = t.id_price_queue
			where 1=1 ".$sWhere.$sWhereNotError."
				and (t.id_user='".Auth::$aUser['id']."' or t.id_user=1) and t.pref='".$sPref."'");
	}
	//-----------------------------------------------------------------------------------------------
	public function ImportSetPrice(){
	    if(Base::$aRequest['cat'] && Base::$aRequest['id'] && Base::$aRequest['pref']){
	        set_time_limit(0);
	        	
	        $iCatId=Db::GetOne("select id from cat where pref='".Base::$aRequest['pref']."'");
	        if (!$iCatId)
	            return;
	        	
	        $sPref = Base::$aRequest['pref'];
	        Db::StartTrans();
	        Base::$db->AutoExecute("cat_pref", array('pref'=>Base::$aRequest['pref'],'cat_id'=>$iCatId) , "UPDATE", "id=".Base::$aRequest['id'], true, true);
	
	        $aInsertData=array(
	            'parser_before'=> (Base::$aRequest['parser_before'] ? mysql_real_escape_string(Base::$aRequest['parser_before']) : ''),
	            'parser_after'=> (Base::$aRequest['parser_after'] ? mysql_real_escape_string(Base::$aRequest['parser_after']) : ''),
	            'trim_left_by'=> (Base::$aRequest['trim_left_by'] ? mysql_real_escape_string(Base::$aRequest['trim_left_by']) : ''),
	            'trim_right_by'=> (Base::$aRequest['trim_right_by'] ? mysql_real_escape_string(Base::$aRequest['trim_right_by']) : ''),
	        );
	        Base::$db->AutoExecute("cat", $aInsertData , "UPDATE", "id=".$iCatId, true, true);
	
	        // 1) обновление кода, итем кода, префикса в буфере
	        // 2) заливка из буфера в прайс
	        // 3) очистка полей буфера по бренду
	        PriceControl::UpdatePriceImport($iCatId,Base::$aRequest['cat'],$sPref);
	
	        Db::CompleteTrans();
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function AddToPticeItems() {
	    if (Base::$aRequest['row_check']) {
	        $isOk = 0;
	        $sWhere=" and (pref is not null and pref != '')";
	        $sWhereNotError = " and (t.price!=0 and t.id_provider is not null
		      and t.id_provider!=0 and t.code!='') ";
	         
	        $aData = Db::getAssoc("Select item_code as key_, item_code, code, pref
                from price_import
                where id in (".implode(',',Base::$aRequest['row_check']).")".$sWhere);
	        if ($aData) {
	            $isOk = 1;
	            foreach ($aData as $aValue) {
	                Db::Execute("
                		insert into cat_part (item_code, pref, code, is_checked_code_ok, is_checked_code_ok_date, is_checked_code_ok_manager)
                		values ('".$aValue['item_code']."','".$aValue['pref']."','".$aValue['code']."',1,'".
	                		date("Y-m-d H:i:s")."',".Auth::$aUser['id_user'].")
                		on duplicate key update is_checked_code_ok=values(is_checked_code_ok),
                        is_checked_code_ok_date=values(is_checked_code_ok_date), is_checked_code_ok_manager=values(is_checked_code_ok_manager)
           		    ");
	                Db::Execute("
    					update price_import
    					set is_checked_code=1, is_code_ok=1
               		    where item_code='".$aValue['item_code']."'");
	            }
	        }
	
	    }
	
	    if ($isOk) {
	        // install price
	        // загруженная запись в буфер
	        $sWhere = " and (((pq.is_processed=2 and pq.progress=100) and t.id_price_queue is not null) or t.id_price_queue is null) ";
	        PriceControl::OnlyInstallPrice($sWhere);
	        // clear from buffer
	        $sWhereNotError = " and (t.price!=0 and t.id_provider is not null and t.id_provider!=0 and t.code!='' and t.is_code_ok=1) ";
	        Base::$db->Execute("delete t from `price_import` t
			left join price_queue pq on pq.id = t.id_price_queue
			where 1=1 ".$sWhere.$sWhereNotError."
				and (t.id_user='".Auth::$aUser['id']."' or t.id_user=1) and t.item_code in ('".
					implode("','",array_keys($aData))."')");
	    }
	     
	    if (Base::$aRequest['return_action_buffer'])
	        Base::Redirect(urldecode(Base::$aRequest['return_action_buffer']));
	    else
	        Base::Redirect("/pages/price_control");
	}
	//AZP-44 end------------------------------------------------------------------------------------
	public function ChangeCodePi () {
	    $sReturnUrl = "/pages/price_control";
	    if (Base::$aRequest['return_action'])
	        $sReturnUrl = (urldecode(Base::$aRequest['return_action']));
	
	    if (!Base::$aRequest['id'])
	        Base::Redirect($sReturnUrl);
	     
	    $aPi = Db::getRow("Select pi.*,up.name as name_provider, c.id_sup, c.id_mfa
            from price_import pi
            inner join user_provider up on up.id_user = pi.id_provider
            inner join cat c on c.pref=pi.pref
            where pi.id=".Base::$aRequest['id']);
	    if (!$aPi || $aPi['is_code_ok']==1) {
	        Base::Redirect($sReturnUrl);
	    }
	     
	    $iIdProvider = $aPi['id_provider'];
        $aProvider[$iIdProvider] = $iIdProvider;
	
	    // вдруг такую запись уже лочили, удалить из буфера
	    $iLockedExist = Db::getOne("Select id from price_import_locked where id_provider=".$iIdProvider.
	        " and cat_in='".mysql_real_escape_string($aPi['cat'])."' and code_in='".mysql_real_escape_string($aPi['code_in'])."'");
	    if ($iLockedExist) {
	        Db::Execute("Delete from price_import where code_in='".mysql_real_escape_string($aPi['code_in']).
	        "' and cat='".mysql_real_escape_string($aPi['cat_in'])."' and id_provider in (".implode(",",$aProvider).")");
	        Base::Redirect($sReturnUrl);
	    }
	    Resource::Get()->Add('/js/select_search.js');
	
	    if (Base::$aRequest['is_post'])
	    {
	    }
	     
	    Base::$tpl->assign('aPrefAssoc',array(""=>"")+Db::GetAssoc("select pref, title from cat order by title"));
	
	    Base::$oContent->AddCrumb(Language::GetMessage('analize_buffer_price'),$sReturnUrl);
	    Base::$oContent->AddCrumb(Language::GetMessage('change code'),'');
	     
	    Base::$tpl->assign("sReturnAction",$sReturnUrl);
	    Base::$tpl->assign("iIdProvider",$iIdProvider);
	    Base::$tpl->assign("aPi",$aPi);
	    	    
	    Base::$sText.=Base::$tpl->fetch('price_control/change_code.tpl');
	}
	//------------------------------------------------------------------------------------
	public function EditCodePi () {
	    $sReturnUrl = "/pages/price_control";
	    if (Base::$aRequest['return_action'])
	        $sReturnUrl = (urldecode(Base::$aRequest['return_action']));
	
	    if (!Base::$aRequest['id'])
	        Base::Redirect($sReturnUrl);
	
	    $aPi = Db::getRow("Select pi.*,up.name as name_provider, c.id_sup, c.id_mfa
	        from price_import pi
            inner join user_provider up on up.id_user = pi.id_provider
	        inner join cat c on c.pref = pi.pref
            where pi.id=".Base::$aRequest['id']);
	    if (!$aPi || $aPi['is_code_ok']==1 || !$aPi['pref']) {
	        Base::Redirect($sReturnUrl);
	    }
	    $aCat = Db::getRow("Select * from cat where pref='".$aPi['pref']."'");
	     
	    $iIdProvider = $aPi['id_provider'];
        $aProvider[$iIdProvider] = $iIdProvider;
	
	    // вдруг такую запись уже лочили, удалить из буфера
	    $iLockedExist = Db::getOne("Select id from price_import_locked where id_provider=".$iIdProvider.
	        " and cat_in='".mysql_real_escape_string($aPi['cat'])."' and code_in='".mysql_real_escape_string($aPi['code_in'])."'");
	    if ($iLockedExist) {
	        Db::Execute("Delete from price_import where code_in='".mysql_real_escape_string($aPi['code_in']).
	        "' and cat='".mysql_real_escape_string($aPi['cat_in'])."' and id_provider in (".implode(",",$aProvider).")");
	        Base::Redirect($sReturnUrl);
	    }
	    Resource::Get()->Add('/js/select_search.js');
	    if (Base::$aRequest['is_post'])
	    {
	    }
	     
	    Base::$oContent->AddCrumb(Language::GetMessage('analize_buffer_price'),$sReturnUrl);
	    Base::$oContent->AddCrumb(Language::GetMessage('edit code'),'');
	
	    Base::$tpl->assign("sReturnAction",$sReturnUrl);
	    Base::$tpl->assign("iIdProvider",$iIdProvider);
	    Base::$tpl->assign("aPi",$aPi);
	    Base::$tpl->assign("aCat",$aCat);
	    Base::$sText.=Base::$tpl->fetch('price_control/edit_code.tpl');
	}
	//------------------------------------------------------------------------------------
	public function ReplaceCode () {
	    if (!Base::$aRequest['data']) {
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	        return;
	    }
	    $sData = base64_decode(Base::$aRequest['data']);
	    $aData=explode("&", $sData);
	    if (!$aData) {
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	        return;
	    }
	    $aParam = array();
	    foreach ($aData as $sValue) {
	        list($name,$val) = explode('=',$sValue);
	        $aParam[$name] = $val;
	    }
	    if (!$aParam['id']) {
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	        return;
	    }
	    $aPi = Db::getRow("Select pi.*,up.name as name_provider 
	        from price_import pi
            inner join user_provider up on up.id_user = pi.id_provider
            where pi.id=".$aParam['id']);
	    
	    if (!$aPi || $aPi['is_code_ok']==1 || !$aPi['cat'] || !$aPi['id_provider'] || !$aPi['code_in']) {
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	        return;
	    }

	    $isTecDoc = Db::getOne("Select 1 from cat where pref='".$aParam['pref']."' and (id_sup>0 or id_mfa>0) ");
	    if ($isTecDoc) {
	       $isCheckCode = PriceControl::CheckCodeOneTecdoc($aParam['pref'],$aParam['new_code']);
	       if (!$isCheckCode) {
	           Base::$oResponse->AddAssign('info_change_code','innerHTML',
	           '<span style="color:red">Такой код и бренд не найдены в Текдок!</span>');
	           Base::$oResponse->addScript("$('#checked_code_ok_".$aParam['id']."').val('0');");
	           return;
	       }
	    }
	    else { // добавляем подтверждение на код и бренд не текдока
	        $cpid = Db::getOne("Select id from cat_part where item_code = '".$aParam['pref']."_".$aParam['new_code']."'");
	        if ($cpid)
	           Db::Execute("Update cat_part set is_checked_code_ok=1, is_checked_code_ok_date='".
	               date("Y-m-d H:i:s")."', is_checked_code_ok_manager=".Auth::$aUser['id_user'].
	               " where id=".$cpid);
	        else 
	            Db::Execute("Insert into cat_part (item_code,code,pref,is_checked_code_ok,is_checked_code_ok_date,is_checked_code_ok_manager)
	                VALUES ('".$aParam['pref']."_".$aParam['new_code']."','".$aParam['new_code']."','".$aParam['pref']."',1,'".date("Y-m-d H:i:s")."',".Auth::$aUser['id_user'].")");
	    }

	    $iIdProvider = $aPi['id_provider'];
	    $aProvider[$iIdProvider] = $iIdProvider;
	    
	    Db::Execute("Insert into change_code (pref_replace, code_replace, id_provider, post_date, manager, cat_in, code, pref) values
		('".$aParam['pref']."','".$aParam['new_code']."','".$iIdProvider."','".date("Y-m-d H:i:s")."','".Auth::$aUser['id_user'].
			"','".mysql_real_escape_string($aPi['cat'])."','".mysql_real_escape_string($aPi['code_in'])."','".mysql_real_escape_string($aPi['pref'])."')
		on duplicate key update id_provider=values(id_provider), cat_in=values(cat_in),code=values(code),
			    pref_replace = values(pref_replace), code_replace = values(code_replace), pref = values(pref)");

	    // обновить буфер
	    Db::Execute("Update price_import set pref='".$aParam['pref']."', code='".$aParam['new_code'].
	       "', item_code='".$aParam['pref'].'_'.$aParam['new_code']."', is_code_ok=1, is_checked_code=1
	        where code_in='".mysql_real_escape_string($aPi['code_in']).
	       "' and cat='".mysql_real_escape_string($aPi['cat_in'])."' and id_provider in (".implode(",",$aProvider).")");
	    
	    // перенести в прайс, удалить из буфера
	    $sWhere = " and (((pq.is_processed=2 and pq.progress=100) and t.id_price_queue is not null) or t.id_price_queue is null) ";
	    $sWhere .= " and code_in='".mysql_real_escape_string($aPi['code_in']).
	       "' and cat='".mysql_real_escape_string($aPi['cat_in'])."' and id_provider in (".implode(",",$aProvider).")";
	        
	    PriceControl::OnlyInstallPrice($sWhere,$aParam['pref']);
	    PriceControl::ClearImportPref($sWhere,$aParam['pref']);
	    
	    // удалить из буфера
	    /*Db::Execute("Delete from price_import where code_in='".mysql_real_escape_string($aPi['code_in']).
	    "' and cat='".mysql_real_escape_string($aPi['cat_in'])."' and id_provider in (".implode(",",$aProvider).")");
	   */
	    
	    if ($aParam['return_action'])
	        Base::$oResponse->addScript("window.location='".urldecode($aParam['return_action'])."'");
	    else
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	}
	//------------------------------------------------------------------------------------
	public function CheckCode () {
	    if (!Base::$aRequest['data']) {
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	        return;
	    }
	    $sData = base64_decode(Base::$aRequest['data']);
	    $aData=explode("&", $sData);
	    if (!$aData) {
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	        return;
	    }
	    $aParam = array();
	    foreach ($aData as $sValue) {
	        list($name,$val) = explode('=',$sValue);
	        $aParam[$name] = $val;
	    }
	    if (!$aParam['id']) {
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	        return;
	    }
	    $aPi = Db::getRow("Select pi.*,up.name as name_provider from price_import pi
            inner join user_provider up on up.id_user = pi.id_provider
            where pi.id=".$aParam['id']);
	    if (!$aPi || $aPi['is_code_ok']==1 || !$aParam['pref'] || !$aParam['new_code']) {
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	        return;
	    }
	
	    $isTecDoc = Db::getOne("Select if(id_sup,id_sup,id_mfa) from cat where pref='".$aParam['pref']."'");
	     
	    $isCheckCode = PriceControl::CheckCodeOneTecdoc($aParam['pref'],$aParam['new_code']);
	    if (!$isCheckCode && $isTecDoc) {
	        Base::$oResponse->AddAssign('info_change_code','innerHTML',
	        '<span style="color:red">Такой код и бренд не найдены в Текдок!</span>');
	        Base::$oResponse->addScript("$('#checked_code_ok_".$aParam['id']."').val('0');");
	    }
	    elseif (!$isCheckCode && !$isTecDoc) {
	        Base::$oResponse->AddAssign('info_change_code','innerHTML',
	        '<span style="color:red">Такой код и бренд не подтверждены как верные!</span>');
	        Base::$oResponse->addScript("$('#checked_code_ok_".$aParam['id']."').val('0');");
	    }
	    else {
	        Base::$oResponse->AddAssign('info_change_code','innerHTML',
	        '<span style="color:green">Такой код и бренд корректны!</span>');
	        Base::$oResponse->addScript("$('#checked_code_ok_".$aParam['id']."').val('1');");
	    }
	}
	//------------------------------------------------------------------------------------
	public function	CheckCodeOneTecdoc($sPref='',$sCode='') {
	    if (!$sPref || !$sCode)
	        return 0;
	     
	    $sCode = Catalog::StripCode($sCode);
	     
	    // cat_part
	    $isOk = Db::getOne("Select is_checked_code_ok from cat_part where item_code='".$sPref."_".$sCode."'");
	    if ($isOk)
	        return 1;
	
	    $sIdTof = Db::getOne("Select if(id_sup,id_sup,id_mfa) from cat where pref='".$sPref."'");
	    if (!$sIdTof)
	        return 0;
	     
	    // коды
	    $isExist = TecdocDb::getOne("Select a.ID_art from ".DB_OCAT."cat_alt_articles a
	        inner join ".DB_OCAT."cat_alt_suppliers s ON s.ID_sup = a.ID_sup
	        where a.Search='".$sCode."' and s.ID_src=".$sIdTof);
	    if ($isExist)
	        return 1;
	    // оригиналы
	    $isExist = TecdocDb::getOne("Select o.id_oe from ".DB_OCAT."cat_alt_original o
	        inner join ".DB_OCAT."cat_alt_manufacturer m ON m.ID_src = o.oe_brand
	        where o.oe_code='".$sCode."' and o.oe_brand=".$sIdTof);
	    if ($isExist)
	        return 1;
	
	    return 0;
	}
	//------------------------------------------------------------------------------------
	public function LockedCodeOne () {
	    if (!Base::$aRequest['data']) {
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	        return;
	    }
	    $sData = base64_decode(Base::$aRequest['data']);
	    $aData=explode("&", $sData);
	    if (!$aData) {
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	        return;
	    }
	    $aParam = array();
	    foreach ($aData as $sValue) {
	        list($name,$val) = explode('=',$sValue);
	        $aParam[$name] = $val;
	    }
	    if (!$aParam['id']) {
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	        return;
	    }
	    $aPi = Db::getRow("Select pi.*,up.name as name_provider from price_import pi
            inner join user_provider up on up.id_user = pi.id_provider
            where pi.id=".$aParam['id']);
	    if (!$aPi || $aPi['is_code_ok']==1 || !$aPi['cat'] || !$aPi['id_provider'] || !$aPi['code_in']) {
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	        return;
	    }
	
	    $iIdProvider = $aPi['id_provider'];
        $aProvider[$iIdProvider] = $iIdProvider;
	
	    Db::Execute("Insert into price_import_locked (id_provider, date_set, id_manager, cat_in, code_in) values
		('".$iIdProvider."','".date("Y-m-d H:i:s")."','".Auth::$aUser['id_user'].
			"','".mysql_real_escape_string($aPi['cat'])."','".mysql_real_escape_string($aPi['code_in'])."')
		on duplicate key update id_provider=values(id_provider), cat_in=values(cat_in),code_in=values(code_in)");
	
	    // удалить из буфера
	    Db::Execute("Delete from price_import where code_in='".mysql_real_escape_string($aPi['code_in']).
	    "' and cat='".mysql_real_escape_string($aPi['cat_in'])."' and id_provider in (".implode(",",$aProvider).")");
	
	    if ($aParam['return_action'])
	        Base::$oResponse->addScript("window.location='".urldecode($aParam['return_action'])."'");
	    else
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	}
	//------------------------------------------------------------------------------------
	public function CheckCodeE () {
	    if (!Base::$aRequest['data']) {
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	        return;
	    }
	    $sData = base64_decode(Base::$aRequest['data']);
	    $aData=explode("&", $sData);
	    if (!$aData) {
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	        return;
	    }
	    $aParam = array();
	    foreach ($aData as $sValue) {
	        list($name,$val) = explode('=',$sValue);
	        $aParam[$name] = $val;
	    }
	    if (!$aParam['id']) {
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	        return;
	    }
	    $aPi = Db::getRow("Select pi.*,up.name as name_provider, c.title as brand, c.id_sup,c.id_mfa
	        from price_import pi
            inner join user_provider up on up.id_user = pi.id_provider
	        inner join cat c on c.pref = pi.pref
            where pi.id=".$aParam['id']);
	    if (!$aPi || $aPi['is_code_ok']==1 || !$aPi['pref']) {
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	        return;
	    }
	    // очистка кода
	    $sCode = $aPi['code_in'];
	    $parser_before = ($aParam['parser_before'] ? $aParam['parser_before'] : '');
	    $parser_after = ($aParam['parser_after'] ? $aParam['parser_after'] : '');
	    $trim_left_by = ($aParam['trim_left_by'] ? $aParam['trim_left_by'] : '');
	    $trim_right_by = ($aParam['trim_right_by'] ? $aParam['trim_right_by'] : '');
	
	    if($parser_before)
	        $sCode=trim(preg_replace('/^('.$parser_before.')(.*)/i','\2',$sCode));
	
	    if($parser_after)
	        $sCode=trim(preg_replace('/('.$parser_after.')(.*)/i','\2',$sCode));
	
	    if($trim_left_by){
	        $iPos=strpos($sCode,$trim_left_by);
	        if($iPos!==FALSE) $sCode=substr($sCode,$iPos+1);
	    }
	
	    if($trim_right_by){
	        $iPos=strpos($sCode,$trim_right_by);
	        if($iPos!==FALSE) $sCode=substr($sCode,0,$iPos);
	    }
	    //
	     
	    $isCheckCode = PriceControl::CheckCodeOneTecdoc($aPi['pref'],$sCode);
	    if (!$isCheckCode && ($aPi['id_sup'] || $aPi['id_mfa'])) {
	        Base::$oResponse->AddAssign('info_change_code','innerHTML',
	        '<span style="color:red">Такой код [ <b>'.$sCode.'</b> ] для бренда [ '.$aPi['brand'].' ] не найден в Текдок!</span>');
	        Base::$oResponse->addScript("$('#checked_code_ok_".$aParam['id']."').val('0');");
	    }
	    elseif(!$isCheckCode && !$aPi['id_sup'] && !$aPi['id_mfa']) {
	        Base::$oResponse->AddAssign('info_change_code','innerHTML',
	        '<span style="color:red">Такой код [ <b>'.$sCode.'</b> ] для бренда [ '.$aPi['brand'].' ] не подтвержден!</span>');
	        Base::$oResponse->addScript("$('#checked_code_ok_".$aParam['id']."').val('0');");
	    }
	    elseif ($isCheckCode && ($aPi['id_sup'] || $aPi['id_mfa'])) {
	        Base::$oResponse->AddAssign('info_change_code','innerHTML',
	        '<span style="color:green">Такой код [<b> '.$sCode.' </b>] для бренда [ '.$aPi['brand'].' ] есть в базе Текдок!</span>');
	        Base::$oResponse->addScript("$('#checked_code_ok_".$aParam['id']."').val('1');");
	    }
	    elseif ($isCheckCode && !$aPi['id_sup'] && !$aPi['id_mfa']) {
	        Base::$oResponse->AddAssign('info_change_code','innerHTML',
	        '<span style="color:green">Такой код [<b> '.$sCode.' </b>] для бренда [ '.$aPi['brand'].' ] подтвержден!</span>');
	        Base::$oResponse->addScript("$('#checked_code_ok_".$aParam['id']."').val('1');");
	    }
	}
	//------------------------------------------------------------------------------------
	public function EditCodeAddProduct () {
	    if (!Base::$aRequest['data']) {
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	        return;
	    }
	    $sData = base64_decode(Base::$aRequest['data']);
	    $aData=explode("&", $sData);
	    if (!$aData) {
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	        return;
	    }
	    $aParam = array();
	    foreach ($aData as $sValue) {
	        list($name,$val) = explode('=',$sValue);
	        $aParam[$name] = $val;
	    }
	    if (!$aParam['id']) {
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	        return;
	    }
	    $aPi = Db::getRow("Select pi.*,up.name as name_provider, c.title as brand,
	        c.id_sup, c.id_mfa
	        from price_import pi
            inner join user_provider up on up.id_user = pi.id_provider
	        inner join cat c on c.pref = pi.pref
	        left join cat_part cp on cp.item_code = pi.item_code
            where pi.id=".$aParam['id']);
	    if (!$aPi || $aPi['is_code_ok']==1 || !$aPi['pref']) {
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
	        return;
	    }
	     
	    $iIdProvider = $aPi['id_provider'];
        $aProvider[$iIdProvider] = $iIdProvider;
	     
	    // очистка кода
	    $sCode = $aPi['code_in'];
	    $parser_before = ($aParam['parser_before'] ? $aParam['parser_before'] : '');
	    $parser_after = ($aParam['parser_after'] ? $aParam['parser_after'] : '');
	    $trim_left_by = ($aParam['trim_left_by'] ? $aParam['trim_left_by'] : '');
	    $trim_right_by = ($aParam['trim_right_by'] ? $aParam['trim_right_by'] : '');
	
	    if($parser_before)
	        $sCode=trim(preg_replace('/^('.$parser_before.')(.*)/i','\2',$sCode));
	
	    if($parser_after)
	        $sCode=trim(preg_replace('/('.$parser_after.')(.*)/i','\2',$sCode));
	
	    if($trim_left_by){
	        $iPos=strpos($sCode,$trim_left_by);
	        if($iPos!==FALSE) $sCode=substr($sCode,$iPos+1);
	    }
	
	    if($trim_right_by){
	        $iPos=strpos($sCode,$trim_right_by);
	        if($iPos!==FALSE) $sCode=substr($sCode,0,$iPos);
	    }
	    //
	    $sCode = Catalog::StripCode($sCode);
	     
        $cpid = Db::getOne("Select id from cat_part where item_code = '".$aPi['pref']."_".$sCode);
        if ($cpid)
            Db::Execute("Update cat_part set is_checked_code_ok=1, is_checked_code_ok_date='".
                date("Y-m-d H:i:s")."', is_checked_code_ok_manager=".Auth::$aUser['id_user'].
                " where id=".$cpid);
        else
            Db::Execute("Insert into cat_part (item_code,code,pref,is_checked_code_ok,is_checked_code_ok_date,is_checked_code_ok_manager)
	                VALUES ('".$aPi['pref'].'_'.$sCode."','".$sCode."','".$aPi['pref']."',1,'".date("Y-m-d H:i:s")."',".Auth::$aUser['id_user'].")");
    
        // признак обновления парсера бренда
        $isChangeParser = 0;
        $aCatOldParse = Db::getRow("Select * from cat where pref='".$aPi['pref']."'");
        if ($aCatOldParse && ($aCatOldParse['parser_before'] != $parser_before ||
            $aCatOldParse['parser_after'] != $parser_after || $aCatOldParse['trim_left_by'] != $trim_left_by ||
            $aCatOldParse['trim_right_by'] != $trim_right_by)) {
                $isChangeParser = 1;
                Db::Execute("Update cat set parser_before='".$parser_before."', parser_after='".$parser_after.
                "', trim_left_by='".$trim_left_by."', trim_right_by='".$trim_right_by."'
    	        where pref='".$aPi['pref']."'");
        }
        // --- одиночная запись перенос в прайс
        
	    
	    // обновить буфер
	    Db::Execute("Update price_import set code='".$sCode.
	    "', item_code='".$aPi['pref'].'_'.$sCode."', is_code_ok=1, is_checked_code=1
	    where code_in='".mysql_real_escape_string($aPi['code_in']).
	    "' and pref='".$aPi['pref']."' and id_provider in (".implode(",",$aProvider).")");
	    
	    // перенести в прайс, удалить из буфера
	    $sWhere = " and (((pq.is_processed=2 and pq.progress=100) and t.id_price_queue is not null) or t.id_price_queue is null) ";
	    $sWhere .= " and code_in='".mysql_real_escape_string($aPi['code_in']).
	    "' and pref='".$aPi['pref']."' and id_provider in (".implode(",",$aProvider).")";
	    
	    PriceControl::OnlyInstallPrice($sWhere,$aPi['pref']);
	    PriceControl::ClearImportPref($sWhere,$aPi['pref']);
	    // --- одиночная запись перенос в прайс -- конец
	     
	    // проверка остальных записей по бренду
	    if ($isChangeParser) {
	        // install
	        Db::StartTrans();
	        // 1) обновление кода, итем кода, префикса в буфере
	        // 2) заливка из буфера в прайс
	        // 3) очистка полей буфера по бренду
	        PriceControl::UpdatePriceImport($aPi['cid'],'',$aPi['pref']);
	        Db::CompleteTrans();
	        // проверка остальных записей по бренду - конец
	    }
	    if ($aParam['return_action'])
	        Base::$oResponse->addScript("window.location='".urldecode($aParam['return_action'])."'");
	    else
	        Base::$oResponse->addScript("window.location='/pages/price_control'");
    }
    //------------------------------------------------------------------------------------
    public function AddProduct () {
        if (!Base::$aRequest['data']) {
            Base::$oResponse->addScript("window.location='/pages/price_control'");
            return;
        }
        $sData = base64_decode(Base::$aRequest['data']);
        $aData=explode("&", $sData);
        if (!$aData) {
            Base::$oResponse->addScript("window.location='/pages/price_control'");
            return;
        }
        $aParam = array();
        foreach ($aData as $sValue) {
            list($name,$val) = explode('=',$sValue);
            $aParam[$name] = $val;
        }
        if (!$aParam['id']) {
            Base::$oResponse->addScript("window.location='/pages/price_control'");
            return;
        }
        $aPi = Db::getRow("Select pi.*,up.name as name_provider, c.title as brand,
	        c.id_sup, c.id_mfa, cp.id as cpid
	        from price_import pi
            inner join user_provider up on up.id_user = pi.id_provider
	        inner join cat c on c.pref = pi.pref
	        left join cat_part cp on cp.item_code = pi.item_code
            where pi.id=".$aParam['id']);
        if (!$aPi || $aPi['is_code_ok']==1 || !$aPi['pref']) {
            Base::$oResponse->addScript("window.location='/pages/price_control'");
            return;
        }
    
        $iIdProvider = $aPi['id_provider'];
        $aProvider[$iIdProvider] = $iIdProvider;
    
        if ($aPi['cpid'])
            Db::Execute("Update cat_part set is_checked_code_ok=1, is_checked_code_ok_date='".
                date("Y-m-d H:i:s")."', is_checked_code_ok_manager=".Auth::$aUser['id_user'].
                " where id=".$aPi['cpid']);
        else
            Db::Execute("Insert into cat_part (item_code,code,pref,is_checked_code_ok,is_checked_code_ok_date,is_checked_code_ok_manager)
                VALUES ('".$aPi['item_code']."','".$aPi['code']."','".$aPi['pref']."',1,'".date("Y-m-d H:i:s")."',".Auth::$aUser['id_user'].")");
    
        // обновить буфер
        Db::Execute("Update price_import set is_code_ok=1, is_checked_code=1
        where code_in='".mysql_real_escape_string($aPi['code_in']).
            "' and pref='".$aPi['pref']."' and id_provider in (".implode(",",$aProvider).")");
    
        // перенести в прайс, удалить из буфера
        $sWhere = " and (((pq.is_processed=2 and pq.progress=100) and t.id_price_queue is not null) or t.id_price_queue is null) ";
        $sWhere .= " and code_in='".mysql_real_escape_string($aPi['code_in']).
        "' and pref='".$aPi['pref']."' and id_provider in (".implode(",",$aProvider).")";
    
        PriceControl::OnlyInstallPrice($sWhere,$aPi['pref']);
        PriceControl::ClearImportPref($sWhere,$aPi['pref']);
    
        if ($aParam['return_action'])
            Base::$oResponse->addScript("window.location='".urldecode($aParam['return_action'])."'");
        else
            Base::$oResponse->addScript("window.location='/pages/price_control'");
    }
    //-----------------------------------------------------------------------------------------------
    public function SetBrand() {
        // not use now - if use need TODO Price::UpdatePriceImport - update all stock record
        //return;
        	
        if (!Base::$aRequest['id'] || !Base::$aRequest['id_brand']) {
            Base::$oResponse->addScript("location.reload();");
            return;
        }
        $aCat = Db::getRow("Select * from cat where pref='".Base::$aRequest['id_brand']."'");
        if (!$aCat) {
            Base::$oResponse->addScript("location.reload();");
            return;
        }
        	
        $aRow = Db::getRow("Select * from price_import where id=".Base::$aRequest['id']);
        if (!$aRow) {
            Base::$oResponse->addScript("location.reload();");
            return;
        }
    
        // update all not link records
        if ($aRow['cat']!='') {
            Db::Execute("insert into cat_pref (name,cat_id) values (upper('".
                mysql_real_escape_string($aRow['cat'])."'),".$aCat['id'].")
                on duplicate key update cat_id = values(cat_id)");
            	
            // 1) обновление кода, итем кода, префикса в буфере
            // 2) заливка из буфера в прайс
            // 3) очистка полей буфера по бренду
            PriceControl::UpdatePriceImport($aCat['id'],mysql_real_escape_string($aRow['cat']),$aCat['pref']);
        }
        else {
            Db::Execute("Update price_import set cat='".mysql_real_escape_string($aRow['cat'])."', pref='".Base::$aRequest['id_brand']."' where id=".$aRow['id']);
            if ($aRow['price'] > 0 && $aRow['id_provider'] && $aRow['code']!='' /*&& $aRow['pref'] - updated*/) {
                // 1) обновление кода, итем кода, префикса в буфере
                // 2) заливка из буфера в прайс
                // 3) очистка полей буфера по бренду
                // 4) только одну запись из буфера
                PriceControl::UpdatePriceImport($aCat['id'],mysql_real_escape_string($aRow['cat']),$aCat['pref'],$aRow);
            }
        }
        Base::$oResponse->addScript("location.reload();");
    }
    //-----------------------------------------------------------------------------------------------
    public function SetNewBrand() {
        if (!Base::$aRequest['data']) {
            Base::$oResponse->addScript("location.reload();");
            return;
        }
        $sData = base64_decode(Base::$aRequest['data']);
        $aData=explode("&", $sData);
        if (!$aData) {
            Base::$oResponse->addScript("location.reload();");
            return;
        }
        $aParam = array();
        foreach ($aData as $sValue) {
            list($name,$val) = explode('=',$sValue);
            $aParam[$name] = $val;
        }
        if (!$aParam['id']) {
            Base::$oResponse->addScript("location.reload();");
            return;
        }
    
        $aRow = Db::getRow("Select * from price_import where id=".$aParam['id']);
        if (!$aRow) {
            Base::$oResponse->addScript("location.reload();");
            return;
        }
        if ($aRow['cat']=='') {
            Base::$oResponse->addScript("location.reload();");
            return;
        }
    
        $sCatName=mb_strtoupper(str_replace(array(' ','-','#','.','/',',','_',':','[',']','(',')','*','&','+','`','\'','"','\\','<','>','?','!','$','%','^','@','~','|','=',';','{','}','№'), '', trim(Content::Translit($aRow['cat']))),'UTF-8');
        if ($sCatName=='') {
            Base::$oResponse->addScript("location.reload();");
            return;
        }
    
        $iCatId=Db::GetOne("select id from cat where title='".mysql_real_escape_string($aRow['cat'])."' or name like '".$sCatName."' ");
        if(!$iCatId){
            $sPref=String::GeneratePref();
            IF (!$sPref) {
                Base::$oResponse->addScript("location.reload();");
                return;
            }
            $iCatId=0;
            Db::AutoExecute("cat", array(
            'pref'=>$sPref,
            'name'=>mb_strtolower($sCatName),
            'title'=>mysql_real_escape_string($aRow['cat']),
            )
            );
            $iCatId=Db::InsertId();
        }
        
        Db::Execute("insert into cat_pref (name,cat_id) values (upper('".
            mysql_real_escape_string($aRow['cat'])."'),".$iCatId.")
                on duplicate key update cat_id = values(cat_id)");

        $aCat = Db::getRow("Select * from cat where id='".$iCatId."'");
        if (!$aCat) {
            Base::$oResponse->addScript("location.reload();");
            return;
        }
    
        // обновить буфер
        Base::$db->Execute("
			update price_import
			set price_import.pref='".$aCat['pref']."',
			    is_checked_code=1, is_code_ok=0,
				price_import.item_code=concat('".$aCat['pref']."','_',price_import.code)
			where (price_import.pref='' or price_import.pref is null)
				and price_import.cat='".mysql_real_escape_string($aRow['cat'])."'");
    
        // в прайс не переносим, так как не текдоковский бренд надо первый раз будет коды подтверждать
    
        Base::$oResponse->addScript("location.reload();");
    }
    //-----------------------------------------------------------------------------------------------
    public function SetCode() {
        if (!Base::$aRequest['id'] || !Base::$aRequest['code']) {
            Base::$oResponse->addScript("location.reload();");
            return;
        }
        	
        $aRow = Db::getRow("Select * from price_import where id=".Base::$aRequest['id']);
        if (!$aRow) {
            Base::$oResponse->addScript("location.reload();");
            return;
        }
    
        $aRow['code'] = Catalog::StripCode(Base::$aRequest['code']);
        $aRow['item_code'] = $aRow['pref']."_".Base::$aRequest['code'];

        Db::Execute("Update price_import set code='".mysql_real_escape_string(Base::$aRequest['code'])."'
			,item_code = '".mysql_real_escape_string($aRow['item_code'])."' where id=".$aRow['id']);
    
        // в прайс не заливаем, надо проверить код текдок/не текдок
    
        Base::$oResponse->addScript("location.reload();");
	}
	//-----------------------------------------------------------------------------------------------
	public function SetPrice() {
	    if (!Base::$aRequest['id'] || !Base::$aRequest['price'] || floatval(str_replace(',', '.',Base::$aRequest['price']))<=0) {
	        Base::$oResponse->addScript("location.reload();");
	        return;
	    }
	    	
	    $aRow = Db::getRow("Select * from price_import where id=".Base::$aRequest['id']);
	    if (!$aRow) {
	        Base::$oResponse->addScript("location.reload();");
	        return;
	    }
	
	    $aRow['price'] = floatval(str_replace(',', '.',Base::$aRequest['price']));
        Db::Execute("Update price_import set price='".$aRow['price']."'
			where id=".$aRow['id']);
	
	    // в прайс не заливаем, может нужно будет подтверждение кода
	
	    Base::$oResponse->addScript("location.reload();");
	}
	//-----------------------------------------------------------------------------------------------
	public function SetName() {
	    if (!Base::$aRequest['id'] || !Base::$aRequest['name']) {
	        Base::$oResponse->addScript("location.reload();");
	        return;
	    }
	    	
	    $aRow = Db::getRow("Select * from price_import where id=".Base::$aRequest['id']);
	    if (!$aRow) {
	        Base::$oResponse->addScript("location.reload();");
	        return;
	    }
	
	    $aRow['part_rus'] = Base::$aRequest['name'];
        Db::Execute("Update price_import set part_rus='".mysql_real_escape_string($aRow['part_rus'])."'
			where id=".$aRow['id']);
        
        // в прайс не заливаем, может нужно будет подтверждение кода
        
	    Base::$oResponse->addScript("location.reload();");
	}
	//-----------------------------------------------------------------------------------------------
	public function SetProvider() {
	    if (!Base::$aRequest['id'] || !Base::$aRequest['id_provider']) {
	        Base::$oResponse->addScript("location.reload();");
	        return;
	    }
	    	
	    $aRow = Db::getRow("Select * from price_import where id=".Base::$aRequest['id']);
	    if (!$aRow) {
	        Base::$oResponse->addScript("location.reload();");
	        return;
	    }
	
	    $aRow['id_provider'] = Base::$aRequest['id_provider'];
        Db::Execute("Update price_import set id_provider='".Base::$aRequest['id_provider']."'
			where id=".$aRow['id']);
	
	    // в прайс не заливаем, может нужно будет подтверждение кода

	    Base::$oResponse->addScript("location.reload();");
	}
	//---------------------------------------------------------------------------------------------
	public function CheckInstallCatParse () {
	    if (!Base::$aRequest['data']) {
	        Base::$oResponse->addScript("window.location='/pages/price_control_edit_code'");
	        return;
	    }
	    $sData = base64_decode(Base::$aRequest['data']);
	    $aData=explode("&", $sData);
	    if (!$aData) {
	        Base::$oResponse->addScript("window.location='/pages/price_control_edit_code'");
	        return;
	    }
	    set_time_limit(0);
	
	    $aParam = array();
	    foreach ($aData as $sValue) {
	        list($name,$val) = explode('=',$sValue);
	        $aParam[$name] = $val;
	    }
	    if (!$aParam['id']) {
	        Base::$oResponse->addScript("window.location='/pages/price_control_edit_code'");
	        return;
	    }
	    $aCat = Db::getRow("Select * from cat where id=".$aParam['id']);
	    if (!$aCat) {
	        Base::$oResponse->addScript("window.location='/pages/price_control_edit_code'");
	        return;
	    }
	
	    $parser_before = ($aParam['parser_before'] ? $aParam['parser_before'] : '');
	    $parser_after = ($aParam['parser_after'] ? $aParam['parser_after'] : '');
	    $trim_left_by = ($aParam['trim_left_by'] ? $aParam['trim_left_by'] : '');
	    $trim_right_by = ($aParam['trim_right_by'] ? $aParam['trim_right_by'] : '');
	
	    Db::Execute("Update cat set parser_before='".$parser_before."', parser_after='".$parser_after.
	    "', trim_left_by='".$trim_left_by."', trim_right_by='".$trim_right_by."'
        where id='".$aCat['id']."'");
	
	    // install
	    Db::StartTrans();
	    // 1) обновление кода, итем кода, префикса в буфере
	    // 2) заливка из буфера в прайс
	    // 3) очистка полей буфера по бренду
	    PriceControl::UpdatePriceImport($aCat['id'],'',$aCat['pref']);
	
	    Db::CompleteTrans();
	
	    Base::$oResponse->addScript("alert('Изменено записей в ошибках: ".(Base::$aData['is_change_buffer_price'] > 0 ? Base::$aData['is_change_buffer_price'] : 0).
	    " Добавлено в прайс: ".(Base::$aData['is_change_items_price']>0 ? Base::$aData['is_change_items_price'] : 0)."');");
	}
}
?>
