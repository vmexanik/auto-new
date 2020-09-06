<?

/**
 * @author Roman Degtyarev
 */

class GarageManager extends Base
{
	var $aTypeDrive = array();
	var $aTypeFuel = array();
	var $aTypeTransmission = array();
	var $aTypeBody = array();
	var $aTypeWheel = array();
	var $aVinMonth = array();
	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
		Auth::NeedAuth('manager');
		Base::$aData['template']['bWidthLimit']=true;
		
		$this->aTypeBody 			= VinRequest::Get_aTypeBody();
		$this->aTypeDrive 			= $this->Get_aTypeDrive();
		$this->aTypeFuel 			= $this->Get_aTypeFuel();
		$this->aTypeTransmission 	= VinRequest::Get_aTypeKpp();
		$this->aTypeWheel 			= VinRequest::Get_aTypeWheel();
		$this->aVinMonth 			= VinRequest::Get_Months();
	}
	//-----------------------------------------------------------------------------------------------	
	public function Index()
	{
		Base::$oContent->AddCrumb(Language::GetMessage('Users'),'/pages/manager_customer');
		Base::$oContent->AddCrumb(Language::GetMessage('garage user'),'');
		
		Base::$sText.="<h2>".Db::GetOne('Select if(uc.name is null,u.login,concat(uc.name," (",u.login,")")) from user_customer uc
				inner join user u ON u.id = uc.id_user
				where id_user='.Base::$aRequest['id_user'])."</h2>";
		Base::$tpl->assign('iUserId',Base::$aRequest['id_user']);
		$sSql = "select u.*,ua.*,ua.id as ua_id 
			from user_auto ua
			inner join user as u on ua.id_user=u.id
			where 1=1 and id_user = ".Base::$aRequest['id_user'] ;
		$oTable=new Table();
		$oTable->iRowPerPage=500;
		$oTable->sSql=$sSql;
		$oTable->aColumn=array(
			'id_make'			=> array('sTitle'=>Language::GetMessage('Make auto')),
			'id_model'			=> array('sTitle'=>Language::GetMessage('Model auto')),
			'id_model_detail'	=> array('sTitle'=>Language::GetMessage('Modification')),
			'customer_comment'	=> array('sTitle'=>Language::GetMessage('Customer comment')),
			'action'			=> array('sTitle'=>'&nbsp;','sWidth'=>'5%'),
		);
		$oTable->sDataTemplate=$this->sPrefix . 'garage_manager/row_user_auto.tpl';
		$oTable->sButtonTemplate=$this->sPrefix . 'garage_manager/button_user_auto.tpl';
		$oTable->aCallback=array($this,'CallParseUserAuto');

		Base::$sText.=$oTable->getTable("User auto");
	}
	//-----------------------------------------------------------------------------------------------	
	public function Profile()
	{
		
		
		$sWhere.=" and c.id_user='".Base::$aRequest['id_user']."'";

		$oTable=new Table();
		$oTable->sWidth='100%';
		$oTable->sSql=Base::GetSql("Part/Search",array(
		"where"=>$sWhere,
		"cart_log_join"=>$bCartJoin,
		"sSearchType"=>"sticker",
		));

		$oTable->aOrdered="order by c.post desc";
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'#/Order #'),
		'code'=>array('sTitle'=>'CartCodeStatus'),
		'name'=>array('sTitle'=>'Name/Customer_Id'),
		'term'=>array('sTitle'=>'Term'),
		'number'=>array('sTitle'=>'Number'),
		'price'=>array('sTitle'=>'Price'),
		'total'=>array('sTitle'=>'Total'),
		);
		$oTable->sDataTemplate='dashboard/row_order.tpl';
		//$oTable->sSubtotalTemplate='dashboard/subtotal_order.tpl';
		$oTable->iRowPerPage=4;
		$oTable->bStepperVisible=false;
		Base::$sText.=$oTable->GetTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseUserAuto(&$aItem)
	{
		if (is_array($aItem) && count($aItem) > 0) {
			foreach ($aItem as $ikey => $aValue) {
				/*if ($aItem[$ikey]['id_model_detail'] != 0) {
					$aRec = array('id_model' => $aItem[$ikey]['id_model'], 'id_make' => $aItem[$ikey]['id_make'], 'id_model_detail' => $aItem[$ikey]['id_model_detail']);
					$aDetails=Db::GetRow(Base::GetSql("OptiCatalog/ModelDetail",$aRec));
					$aItem[$ikey]['id_model_detail'] = $aDetails['Description'] . '&nbsp;' . $aDetails['year_start']."-".$aDetails['year_end'];
				}
				else 
					$aItem[$ikey]['id_model_detail'] = '';
				*/
				$aItem[$ikey]['month'] = $this->aVinMonth[$aValue['month']];
				$aItem[$ikey]['id_make'] = Db::GetOne("select title from cat where id='".$aValue['id_make']."'");
				$aItem[$ikey]['id_model'] = Db::GetOne("select name from cat_model where tof_mod_id='".$aValue['id_model']."'");
				$aModelDetail=TecdocDb::GetModelDetail(array(
						'id_model_detail'=>$aValue['id_model_detail']
				));
				$aItem[$ikey]['id_model_detail'] = $aModelDetail['name']." ".$aModelDetail['year_start']
				."-".$aModelDetail['year_end'];
			}
		}
	}
	//-----------------------------------------------------------------------------------------------	
	public function AddComment()
	{
		if (Base::$aRequest['is_post']) 
		{
			Db::Execute("UPDATE `user_auto` SET `manager_comment`='".Base::$aRequest['data']['manager_comment']."' WHERE id = ".Base::$aRequest['data']['id']);
			Base::Redirect("/?action=garage_manager&id_user=".Base::$aRequest['data']['id_user']);
		}
		$aData['id_user'] = Base::$aRequest['id_user'];
		$aData['id'] = Base::$aRequest['id'];
		$aData['manager_comment'] = Db::GetOne('Select manager_comment from user_auto where id='.$aData['id']); 
		Base::$tpl->assign('aData',$aData);
		
		$aField['manager_comment']=array('title'=>'manager_comment','type'=>'textarea','name'=>'data[manager_comment]','value'=>$aData['manager_comment']);
		$aField['id']=array('type'=>'hidden','name'=>'data[id]','value'=>$aData['id']);
		$aField['id_user']=array('type'=>'hidden','name'=>'data[id_user]','value'=>$aData['id_user']);
		
		$aData=array(
		'sHeader'=>"method=post",
		'sTitle'=>"Add comment",
		//'sContent'=>Base::$tpl->fetch('garage_manager/form_add_comment.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sSubmitButton'=>'Apply',
		'sSubmitAction'=>'garage_manager_add_comment',
		'sError'=>$sError,
		);
		$oForm=new Form($aData);

		Base::$sText.=$oForm->getForm();
		
	}
	//-----------------------------------------------------------------------------------------------	
	public function Edit()
	{
			if (Base::$aRequest['is_post']) {
			$aData=String::FilterRequestData(Base::$aRequest['data']);
			if (Base::$aRequest['Year'])
				$aData['year'] = Base::$aRequest['Year'];
			
			// errors
			$iErrors = 0;
			if (Auth::$aUser['id_user'] == 0) {
				Base::Message(array('MF_ERROR'=> Language::GetMessage('This method allow only registered users')));
				$iErrors = 1;
			} elseif ($aData['id_make'] == 0 || $aData['id_model'] == 0 /*|| $aData['volume'] == ""*/) {
				
				Base::Message(array('MF_ERROR'=> Language::GetMessage('Please fill all required fields')));
				$iErrors = 1;
			}
			elseif (trim($aData['vin'])!='' && mb_strlen(trim($aData['vin'])) != Base::GetConstant('vin_request:length',17)) {
				Base::Message(array('MF_ERROR'=> Language::GetMessage("vin_have_no_17_symbols")));
				$iErrors = 1;
			}
			
			if ($iErrors == 0) {	
				// add all id => data
				$aData['body'] = ($this->aTypeBody[$aData['body']] ? $this->aTypeBody[$aData['body']] : '');
				//$aData['type_drive'] = $this->aTypeDrive[$aData['id_type_drive']];
				//$aData['type_fuel'] = $this->aTypeFuel[$aData['id_type_fuel']];
				$aData['kpp'] = ($this->aTypeTransmission[$aData['kpp']] ? $this->aTypeTransmission[$aData['kpp']] : '');
				$aData['wheel'] = ($this->aTypeWheel[$aData['wheel']] ? $this->aTypeWheel[$aData['wheel']] : '');
				/*
				$oImageProcess=new ImageProcess();
				$aImage=$oImageProcess->GetUploadedImage('passport_image',1,'/imgbank/Image/passport_image/',Auth::$aUser['id']
				,Base::GetConstant('passport_image:big_width',800),Base::GetConstant('passport_image:small_width',150),true);
				if ($aImage[1]) {
					$aPassportImage=array(
					'id_user'=>Auth::$aUser['id'],
					'name'=>$aImage[1]['name'],
					'name_small'=>$aImage[1]['name_small'],
					);
					Db::AutoExecute('passport_image',$aPassportImage);
				}
				$aData['passport_image_name'] = $aPassportImage['name'];
				$aData['passport_image_name_small'] = $aPassportImage['name_small'];
				*/
				//$aData['id_user'] = Auth::$aUser['id_user'];
				if (Base::$aRequest['id']) {
					$aData['modified'] = time(); 
					Db::AutoExecute("user_auto",$aData,"UPDATE","id=".Base::$aRequest['id']);
					Base::Message(array('MF_NOTICE' => Language::getMessage('Your auto updated')));
					Base::Redirect("/?action=garage_manager&id_user=".Base::$aRequest['data']['id_user']);
				} else {
					$aData['post'] = $aData['modified'] = time();
					Db::AutoExecute("user_auto",$aData);
					Base::Message(array('MF_NOTICE' => Language::getMessage('Your auto added')));
					Base::Redirect("/?action=garage_manager&id_user=".Base::$aRequest['data']['id_user']);
				}
			}
			else {
				Base::$aRequest['action']=$this->sPrefix.'_add';
				Base::$aRequest['data']['date'] = $aData['year'] .'-01-01';
				Base::$tpl->assign('aData', Base::$aRequest['data']);
				$this->GetInfoAuto($aData);
			}
		}
		
		Base::$tpl->assign('aMake', array(""=>Language::getMessage('choose make'))+Db::GetAssoc("Assoc/Cat",array(
			'is_brand'=>1,
			'is_main'=>1,
			)));
			
			if (Base::$aRequest['action']=='garage_manager_add'){
					$aData['id_user'] = Base::$aRequest['id_user'];
					$aData['is_manager_created'] = 1;
					Base::$tpl->assign('aData', $aData);
			}
			// edit
			if (Base::$aRequest['id']) {
				$aData = Db::GetRow("select * from user_auto where id=".Base::$aRequest['id']);
				if (!$aData['id'] || $aData['id_user'] != Base::$aRequest['id_user'])
					$aData = array();
				
				$aData['body'] = array_search($aData['body'],$this->aTypeBody);
				$aData['id_type_drive'] = array_search($aData['type_drive'],$this->aTypeDrive);
				$aData['id_type_fuel'] = array_search($aData['type_fuel'],$this->aTypeFuel);
				$aData['kpp'] = array_search($aData['kpp'],$this->aTypeTransmission);
				$aData['wheel'] = array_search($aData['wheel'],$this->aTypeWheel);
				$aData['date'] = $aData['year'] .'-01-01';
				Base::$tpl->assign('aData', $aData);
				$this->GetInfoAuto($aData);
			}
			// not move to GetFormAddAuto - becouse ajax used not work in popup - create order
			Base::$aMessageJavascript = array(
			"MakeAuto_select"=> Language::GetMessage("Choose model"),
			"DetailAuto_select"=> Language::GetMessage(""),
			);
			
			Base::$sText.=$this->GetFormAddAuto($this,"Edit",1,$aData);
			
			return;
	}
	//-----------------------------------------------------------------------------------------------
	public function Delete()
	{
		if (!Base::$aRequest['id']) {
			$sMessage = '&aMessage[MT_ERROR]=' . Language::GetMessage('Not found record with own auto to del.');
		}
		else {
			$aRow = Db::GetRow("select * from user_auto where id=".Base::$aRequest['id']);
			if (!$aRow['id'] || $aRow['id_user'] != Base::$aRequest['id_user']) {
				$sMessage = '&aMessage[MT_ERROR]='.Language::GetMessage('Not found or access denied record in user auto.');
			}
			else {
				Db::Execute("delete from user_auto where id=".Base::$aRequest['id']);
				$sMessage = '&aMessage[MT_NOTICE]='.Language::GetMessage('Own auto record delete successfully.');
			}
		}
		Base::Redirect("/?action=garage_manager&id_user=".Base::$aRequest['id_user']);
	}
	//-----------------------------------------------------------------------------------------------	
	public function GetInfoAuto($aData) {
			if ($aData['id_make'] != 0) {
// 			$aModelAll=Db::GetAll(Base::GetSql("OptiCatalog/Model",array("id_make"=>$aData['id_make']))
// 					." order by name ");
			$aModelAll=TecdocDb::GetModels(array("id_make"=>$aData['id_make']));
			$aModel=array();
			if ($aModelAll) foreach ($aModelAll as $sKey => $aValue) {
				$aModel[$aValue['id']] = $aValue['name'];
			}
			Base::$tpl->assign('aModel',$aModel);
		}
		
		if ($aData['id_make'] != 0 && $aData['id_model'] != 0) {
			$aRec = array('id_model' => $aData['id_model'], 'id_make' => $aData['id_make']);
			$aModelDetail=TecdocDb::GetModificationAssoc($aRec);
			/*$aModelDetailAll=Db::GetAll(Base::GetSql("OptiCatalog/ModelDetail",$aRec));
			foreach ($aModelDetailAll as $sKey => $aValue) {
				$aModelDetail[$aValue['id_model_detail']]=$aValue['name']." ".$aValue['year_start']
				."-".$aValue['year_end'];
			}*/
			Base::$tpl->assign('aModelDetail',$aModelDetail);
		}
	}
	//-----------------------------------------------------------------------------------------------	
	public function GetFormAddAuto($oObject, $sTitle = "Edit", $iSubmitNotPopUp = 1) {
	    Base::$oContent->AddCrumb(Language::GetMessage('Users'),'/pages/manager_customer');
		Base::$oContent->AddCrumb(Language::GetMessage('garage user'),'');

		if(Base::$aRequest['action']=='garage_manager_add') $sTitle = "Add auto";
	    
	    Base::$tpl->assign('aTypeBody',$oObject->aTypeBody);
		Base::$tpl->assign('aTypeTransmission',$aTypeTransmission=$oObject->aTypeTransmission);
		Base::$tpl->assign('aTypeWheel',$aTypeWheel=$oObject->aTypeWheel);
		Base::$tpl->assign('aVinMonth',$oObject->aVinMonth);

		$aData=Base::$tpl->get_template_vars('aData');
		$aMake=Base::$tpl->get_template_vars('aMake');
		$aModel=Base::$tpl->get_template_vars('aModel');
		$aModelDetail=Base::$tpl->get_template_vars('aModelDetail');
		
		
		if(Base::$aRequest['action']=='garage_manager_edit'){
		    Base::$tpl->assign('aModel',$aModel=array(''=>Language::GetMessage('Choose Model '))+$aModel);
		    Base::$tpl->assign('aModelDetail',$aModelDetail=array(''=>Language::GetMessage('not selected'))+$aModelDetail);
		}

		$aField['vin']=array('title'=>'vin','type'=>'input','value'=>(!Base::$aRequest['data']['vin'])?$aData['vin']:Base::$aRequest['data']['vin'],'name'=>'data[vin]');
		$aField['id_make']=array('title'=>'Make','type'=>'select','options'=>$aMake,'selected'=>!Base::$aRequest['data']['id_make']?$aData['id_make']:Base::$aRequest['data']['id_make'],'name'=>'data[id_make]','szir'=>1,'onchange'=>"change_MakeAuto(this);",'id'=>'ctlMakeOwnAuto');
		$aField['id_model']=array('title'=>'Model','type'=>'select','options'=>$aModel,'selected'=>!Base::$aRequest['data']['id_model']?$aData['id_model']:Base::$aRequest['data']['id_model'],'name'=>'data[id_model]','id'=>'ctlModelOwnAuto','onchange'=>"change_DetailAuto(this);",'szir'=>1);
		$aField['id_model_detail']=array('title'=>'Modification','type'=>'select','options'=>$aModelDetail,'selected'=>!Base::$aRequest['data']['id_model_detail']?$aData['id_model_detail']:Base::$aRequest['data']['id_model_detail'],'name'=>'data[id_model_detail]','id'=>'ctlModelDetailOwnAuto','szir'=>1);
		$aField['wheel']=array('title'=>'type_wheel','type'=>'select','options'=>$aTypeWheel,'selected'=>!Base::$aRequest['data']['id_wheel']?$aData['wheel']:Base::$aRequest['data']['wheel'],'name'=>'data[wheel]','id'=>'type_wheel');
		$aField['engine']=array('title'=>'engine','type'=>'input','value'=>!Base::$aRequest['data']['engine']?$aData['engine']:Base::$aRequest['data']['engine'],'name'=>'data[engine]');
		$aField['country_producer']=array('title'=>'country_producer','type'=>'input','value'=>!Base::$aRequest['data']['country_producer']?$aData['country_producer']:Base::$aRequest['data']['country_producer'],'name'=>'data[country_producer]');
		$aField['kpp']=array('title'=>'kpp','type'=>'select','options'=>$aTypeTransmission,'selected'=>!Base::$aRequest['data']['kpp']?$aData['kpp']:Base::$aRequest['data']['kpp'],'name'=>'data[kpp]','id'=>'type_transmission');
		$aField['is_abs_hidden']=array('type'=>'hidden','name'=>'data[is_abs]','value'=>'0');
		$aField['is_abs']=array('title'=>'is_abs','type'=>'checkbox','name'=>'data[is_abs]','value'=>'1','checked'=>(Base::$aRequest['data']['is_abs']==1 || $aData['is_abs']));
		$aField['is_hyd_weel_hidden']=array('type'=>'hidden','name'=>'data[is_hyd_weel]','value'=>'0');
		$aField['is_hyd_weel']=array('title'=>'is_hyd_weel','type'=>'checkbox','name'=>'data[is_hyd_weel]','value'=>'1','checked'=>(Base::$aRequest['data']['is_hyd_weel']==1 || $aData['is_hyd_weel']));
		$aField['is_conditioner_hidden']=array('type'=>'hidden','name'=>'data[is_conditioner]','value'=>'0');
		$aField['is_conditioner']=array('title'=>'is_conditioner','type'=>'checkbox','name'=>'data[is_conditioner]','value'=>'1','checked'=>(Base::$aRequest['data']['is_conditioner']==1 || $aData['is_conditioner']));
		$aField['customer_comment']=array('title'=>'Customer Comment','type'=>'textarea','name'=>'data[customer_comment]','value'=>!Base::$aRequest['data']['customer_comment']?$aData['customer_comment']:Base::$aRequest['data']['customer_comment']);
		$aField['id_user']=array('type'=>'hidden','name'=>'data[id_user]','value'=>$aData['id_user']);
		$aField['is_manager_created']=array('type'=>'hidden','name'=>'data[is_manager_created]','value'=>$aData['is_manager_created']);
		
		$oForm=new Form();
		$oForm->sHeader="method=post enctype='multipart/form-data'";
		$oForm->sTitle= $sTitle;
// 		$oForm->sContent=Base::$tpl->fetch('garage_manager/form_car_add.tpl');
		$oForm->aField=$aField;
		$oForm->bType='generate';
		if ($iSubmitNotPopUp) {
			$oForm->sSubmitButton='Apply';
			$oForm->sSubmitAction=$oObject->sPrefixAction;
			$oForm->sReturnButton = '<< Return';
		}
		$oForm->bIsPost=true;
		$oForm->sWidth="600px";
		$oForm->sReturnAction="garage_manager&id_user=".Base::$aRequest['id_user'];	
		return $oForm->getForm();
	}
	//-----------------------------------------------------------------------------------------------	
	public function Get_aTypeFuel() {
		// use count from 1! in smarty tlp check 0 and not set equal
		return array(
			1 => Language::GetMessage('Petrol/Ethanol'),
			2 => Language::GetMessage('Petrol/Natural Gas (CNG)'),
			3 => Language::GetMessage('Petrol/Petroleum Gas (LPG)'),
			4 => Language::GetMessage('бензин'),
			5 => Language::GetMessage('био-горючее'),
			6 => Language::GetMessage('водород'),
			7 => Language::GetMessage('газ'),
			8 => Language::GetMessage('дизель'),
			9 => Language::GetMessage('природный газ'),
			10 => Language::GetMessage('сжиженный газ'),
			11 => Language::GetMessage('смесь'),
			12 => Language::GetMessage('эластичное топливо'),
			13 => Language::GetMessage('электрическ. - бензин'),
			14 => Language::GetMessage('электрическ. - дизельное топливо'),
			15 => Language::GetMessage('электричество'),
		);
	}
	//-----------------------------------------------------------------------------------------------
	// for use in other module
	public function Get_aTypeDrive() {
		// use count from 1! in smarty tlp check 0 and not set equal
		return array(
			1 => Language::GetMessage('Привод на все колеса'),
			2 => Language::GetMessage('Привод на все колеса постоянный'),
			3 => Language::GetMessage('Привод на задние колеса'),
			4 => Language::GetMessage('Привод на передние колеса'),
		);
	}


}
?>