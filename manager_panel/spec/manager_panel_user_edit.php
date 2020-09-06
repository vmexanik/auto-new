<?php 
/**
 * @author Vladimir Fedorov
 */

class AManagerPanelUserEdit extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		Base::$aMessageJavascript = array(
		"MakeAuto_select"=> Language::GetMessage("Choose model"),
		"DetailAuto_select"=> Language::GetMessage("Choose year"),
		"add_auto_error"=>Language::GetMessage("error_add_auto"),
		"add_auto_17symbol"=> Language::GetMessage("vin_have_no_17_symbols"),
		"add_auto_model_empty"=> Language::GetMessage("model_and_series_empty"),
		"add_auto_modyfication_empty"=> Language::GetMessage("modyfication_empty"),
		"add_auto_volume_empty"=> Language::GetMessage("volume_empty"),
		);
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		parent::ManagerPanelRedirect();
		return;
	}
	//-----------------------------------------------------------------------------------------------
	public function Car()
	{
		if (!Base::$aRequest['id_cp'] || !Base::$aRequest['id_user']) {
			parent::ManagerPanelRedirect(Base::$aRequest['return']);
			return;
		}
			
		$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>Base::$aRequest['id_cp'])));
		if (!$aCartPackage) {
			parent::ManagerPanelRedirect(Base::$aRequest['return']);
			return;
		}
		
		$aUser = Db::GetRow(Base::GetSql('User',array('id'=>Base::$aRequest['id_user'])));
		if (!$aUser) {
			parent::ManagerPanelRedirect(Base::$aRequest['return']);
			return;
		}
		Base::$oResponse->addAssign ( 'title_popup', 'innerHTML', Language::getMessage('car_client').' #'.$aUser['id'] );
		Base::$tpl->assign('sListAuto',$this->GetListAuto($aUser['id']));
		//$oGarageManager = new GarageManager();
		//$sBody = $oGarageManager->GetFormAddAuto($oGarageManager);
		
		$sBody = Base::$tpl->fetch('manager_panel/user_edit/form_car_popup.tpl');
		
		Base::$oResponse->addAssign ( 'body_popup', 'innerHTML', $sBody );
		Base::$oResponse->addScript ("$('.block-popup').css({'width':'700','owerflow':'hidden'});");
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').show();");
		//Base::$aRequest['not_change_top_menu']=1;	
	}
	//-----------------------------------------------------------------------------------------------
	public function GetListAuto($id_user=0) {
		$oTable=new Table();
		$aData = Db::getAll(Base::GetSql('UserAuto',array('id_user'=>$id_user))."order by ua.id desc");
		$oTable->iRowPerPage=500;
		$oTable->aDataFoTable=$aData;
		$oTable->sType="array";
		$oTable->aColumn=array(
				'vin'				=> array('sTitle'=>Language::GetMessage('VIN'),'nosort'=>1),
				'id_make'			=> array('sTitle'=>Language::GetMessage('Make auto'),'nosort'=>1),
				'id_model'			=> array('sTitle'=>Language::GetMessage('Model auto'),'nosort'=>1),
				'year'				=> array('sTitle'=>Language::GetMessage('Year'),'nosort'=>1),
				'action'			=> array('nosort'=>1)
		);
		$oObject = new OwnAuto();
		$oTable->sDataTemplate='manager_panel/user_edit/row_user_auto.tpl';
		$oTable->aCallback=array($oObject,'CallParseUserAuto');
		//$oTable->sTemplateName = 'cart/table_popup.tpl';
		$oTable->sButtonTemplate = 'manager_panel/user_edit/button_car_add.tpl';
		$oTable->sClass="table table-striped";
		//if ($aData)
			$sText = '<div id="list_auto">'.$oTable->getTable().'</div>';
	
		// add auto hidden form
			$sText .= '<div id="add_form_auto" style="display:none;">' . $this->GetFormAddAuto($oObject, "Add auto", 0, array()) ."</div>";
		//$sText .= '<div id="add_form_auto" style="display:none;">' . $oObject->GetFormAddAuto($oObject, "Add auto", 0, array()) ."</div>";
	
		return $sText;
	}
	//-----------------------------------------------------------------------------------------------
	public function DelCar() {
		if (!Base::$aRequest['id'] || !Base::$aRequest['id_user'])
			return;
		
		$aUser = Db::GetRow(Base::GetSql('User',array('id'=>Base::$aRequest['id_user'])));
		if (!$aUser)
			return;
		
		$aData = Db::getRow(Base::GetSql('UserAuto',array(
			'id_user'=>Base::$aRequest['id_user'],'where' => ' and ua.id = '.Base::$aRequest['id'])));
		if ($aData) {
			Db::Execute("Delete from user_auto where id=".$aData['id']);
			$sText = $this->GetListAuto($aUser['id']);
			Base::$oResponse->addAssign ( 'body_popup', 'innerHTML', $sText );
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function AutoAdd() {
		if (!Base::$aRequest['id_cp'] || !Base::$aRequest['id_user'])
			return;
		
		$aUser = Db::GetRow(Base::GetSql('User',array('id'=>Base::$aRequest['id_user'])));
		if (!$aUser)
			return;

		$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>Base::$aRequest['id_cp'])));
		if (!$aCartPackage) {
			return;
		}
		
		/*
	
		if (Auth::$aUser['id'] != 0 && Base::$aRequest['id_make'] && Base::$aRequest['id_model'] && Base::$aRequest['id_model_detail'] && Base::$aRequest['vin']
		*&& Base::$aRequest['volume'] && Base::$aRequest['month'] && Base::$aRequest['year']*) {
			
		$aData = array(
				'vin' => Base::$aRequest['vin'],
				'id_user' => Auth::$aUser['id'],
				'id_make' => Base::$aRequest['id_make'],
				'id_model' => Base::$aRequest['id_model'],
				'id_model_detail' => Base::$aRequest['id_model_detail'],
				'body' => ((Base::$aRequest['body'] && $this->aTypeBody[Base::$aRequest['body']]) ? $this->aTypeBody[Base::$aRequest['body']] : ''),
				'engine' => ((Base::$aRequest['engine']) ? Base::$aRequest['engine'] : ''),
				'country_producer' => ((Base::$aRequest['country_producer']) ? Base::$aRequest['country_producer'] : ''),
				'kpp' => ((Base::$aRequest['kpp'] && $this->aTypeTransmission[Base::$aRequest['kpp']]) ? $this->aTypeTransmission[Base::$aRequest['kpp']] : ''),
				'wheel' => 	( (Base::$aRequest['wheel'] && $this->aTypeWheel[Base::$aRequest['wheel']]) ? $this->aTypeWheel[Base::$aRequest['wheel']] : ''),
				'is_abs' => (Base::$aRequest['is_abs'] ? Base::$aRequest['is_abs'] : 0),
				'is_hyd_weel' => (Base::$aRequest['is_hyd_weel'] ? Base::$aRequest['is_hyd_weel'] : 0),
				'is_conditioner' => (Base::$aRequest['is_conditioner'] ? Base::$aRequest['is_conditioner'] : 0),
				'customer_comment' => (Base::$aRequest['customer_comment'] ? Base::$aRequest['customer_comment'] : ''),
		);
			
		$aData['post'] = $aData['modified'] = time();
			
		$aData=String::FilterRequestData($aData);
		Db::AutoExecute("user_auto",$aData);
		$iId = Db::InsertId();*/

		Base::$aRequest['return']='action=manager_panel_user_edit_car&id_cp='.$aCartPackage['id']."&id_user=".$aUser['id'];
		parent::ManagerPanelRedirect();
	}	
	//-----------------------------------------------------------------------------------------------
	public function GetFormAddAuto($oObject, $sTitle = "Edit", $iSubmitNotPopUp = 1, $aData) {
		Base::$tpl->assign('aTypeBody',$oObject->aTypeBody);
		Base::$tpl->assign('aTypeTransmission',$oObject->aTypeTransmission);
		Base::$tpl->assign('aTypeWheel',$oObject->aTypeWheel);
		Base::$tpl->assign('aVinMonth',$oObject->aVinMonth);
	
		$aMake=Base::$tpl->get_template_vars('aMake');
		$aModel=Base::$tpl->get_template_vars('aModel');
		$aModelDetail=Base::$tpl->get_template_vars('aModelDetail');
	
		$aField['vin']=array('title'=>'vin','type'=>'input','value'=>!Base::$aRequest['data']['vin']?$aData['vin']:Base::$aRequest['data']['vin'],'name'=>'data[vin]','szir'=>1, 'maxlength' => 17);
		$aField['id_make']=array('title'=>'Make','type'=>'select','options'=>$aMake,'selected'=>!Base::$aRequest['data']['id_make']?$aData['id_make']:Base::$aRequest['data']['id_make'],'name'=>'data[id_make]','szir'=>1,'onchange'=>"change_MakeAuto(this);",'id'=>'ctlMakeOwnAuto');
		$aField['id_model']=array('title'=>'Model','type'=>'select','options'=>$aModel,'selected'=>!Base::$aRequest['data']['id_model']?$aData['id_model']:Base::$aRequest['data']['id_model'],'name'=>'data[id_model]','id'=>'ctlModelOwnAuto','onchange'=>"change_DetailAuto(this);",'szir'=>1);
		$aField['id_model_detail']=array('title'=>'Modification','type'=>'select','options'=>$aModelDetail,'selected'=>!Base::$aRequest['data']['id_model_detail']?$aData['id_model_detail']:Base::$aRequest['data']['id_model_detail'],'name'=>'data[id_model_detail]','id'=>'ctlModelDetailOwnAuto','szir'=>1);
		$aField['wheel']=array('title'=>'type_wheel','type'=>'select','options'=>$oObject->aTypeWheel,'selected'=>!Base::$aRequest['data']['id_wheel']?$aData['wheel']:Base::$aRequest['data']['wheel'],'name'=>'data[wheel]','id'=>'type_wheel');
		$aField['engine']=array('title'=>'engine','type'=>'input','value'=>!Base::$aRequest['data']['engine']?$aData['engine']:Base::$aRequest['data']['engine'],'name'=>'data[engine]');
		$aField['country_producer']=array('title'=>'country_producer','type'=>'input','value'=>!Base::$aRequest['data']['country_producer']?$aData['country_producer']:Base::$aRequest['data']['country_producer'],'name'=>'data[country_producer]');
		$aField['kpp']=array('title'=>'kpp','type'=>'select','options'=>$oObject->aTypeTransmission,'selected'=>!Base::$aRequest['data']['kpp']?$aData['kpp']:Base::$aRequest['data']['kpp'],'name'=>'data[kpp]','id'=>'type_transmission');
		$aField['is_abs_hidden']=array('type'=>'hidden','name'=>'data[is_abs]','value'=>'0');
		$aField['is_abs']=array('title'=>'is_abs','type'=>'checkbox','name'=>'data[is_abs]','value'=>'1','checked'=>(Base::$aRequest['data']['is_abs']==1 || $aData['is_abs']));
		$aField['is_hyd_weel_hidden']=array('type'=>'hidden','name'=>'data[is_hyd_weel]','value'=>'0');
		$aField['is_hyd_weel']=array('title'=>'is_hyd_weel','type'=>'checkbox','name'=>'data[is_hyd_weel]','value'=>'1','checked'=>(Base::$aRequest['data']['is_hyd_weel']==1 || $aData['is_hyd_weel']));
		$aField['is_conditioner_hidden']=array('type'=>'hidden','name'=>'data[is_conditioner]','value'=>'0');
		$aField['is_conditioner']=array('title'=>'is_conditioner','type'=>'checkbox','name'=>'data[is_conditioner]','value'=>'1','checked'=>(Base::$aRequest['data']['is_conditioner']==1 || $aData['is_conditioner']));
		$aField['customer_comment']=array('title'=>'Customer Comment','type'=>'textarea','name'=>'data[customer_comment]','value'=>!Base::$aRequest['data']['customer_comment']?$aData['customer_comment']:Base::$aRequest['data']['customer_comment']);
	
		if (!$iSubmitNotPopUp) {
			$aField['apply']=array('type'=>'button','class'=>'btn btn-default','value'=>'Apply','onclick'=>'popup_submit_mp(this);');
			$aField['return']=array('type'=>'button','class'=>'btn btn-default','value'=>'Back','onclick'=>'add_auto_form_return();','style'=>'float:left;');
		}
		
		$aField['ip_cp']=array('title'=>'id_cp','type'=>'hidden','value'=>Base::$aRequest['id_cp'],'name'=>'id_cp');
		$aField['id_user']=array('title'=>'id_user','type'=>'hidden','value'=>Base::$aRequest['id_user'],'name'=>'id_user');		
		
		$oForm=new Form();
		$oForm->sHeader="method=post";
		$oForm->sTitle= $sTitle;
		//$oForm->sContent=Base::$tpl->fetch($oObject->sPrefix.'/form_'.$oObject->sPrefix.'_add.tpl');
		$oForm->aField=$aField;
		$oForm->bType='generate';
		if ($iSubmitNotPopUp) {
			$oForm->sSubmitButton='Apply';
			$oForm->sSubmitAction=$oObject->sPrefixAction;
			$oForm->sReturnButton = '<< Return';
		}
		// 		else{
		// 			$oForm->sAdditionalButtonTemplate = $oObject->sPrefix.'/popup_'.$oObject->sPrefix.'_submit.tpl';
		// 		}
		$oForm->bIsPost=true;
		$oForm->sWidth="600px";
		$oForm->sReturnAction=$oObject->sPrefix;
			
		return $oForm->getForm();
	}
	//-----------------------------------------------------------------------------------------------
	public function CorrectBalance()
	{
		if (!Base::$aRequest['id_user']) {
			parent::ManagerPanelRedirect();
			return;
		}

		$aUser = Db::GetRow(Base::GetSql('Customer',array('id'=>Base::$aRequest['id_user'])));
		if (!$aUser) {
			parent::ManagerPanelRedirect();
			return;
		}
		Base::$tpl->assign('aUser',$aUser);
		if (Base::$aRequest['is_post']) {
			if (!Base::$aRequest['data_amount'] || !Base::$aRequest['data_pay_type']) {
				$sError="Please, fill the required fields";
				Base::$tpl->assign('aData',$aData=Base::$aRequest);
			}
			else {
				if (Base::$aRequest['data_pay_type']=='debt_customer')
					Base::$aRequest['data_amount'] = '-'.abs(Base::$aRequest['data_amount']);
			
				$sComment = Language::getMessage("correct balance").' ('.Language::getMessage(Base::$aRequest['data_pay_type']).') '.Base::$aRequest['data_comment'];
				Finance::Deposit(Base::$aRequest['id_user'],
					Base::$aRequest['data_amount'],
					mysql_real_escape_string($sComment),'',
					'interval',Base::$aRequest['data_pay_type'],0,0,0,
					'',0,0,true,0,'',
					Base::$aRequest['post_date']
				);
				$aUser = Db::GetRow(Base::GetSql('Customer',array('id'=>Base::$aRequest['id_user'])));
				Base::$tpl->assign('aUser',$aUser);
				$sBalance = Base::$tpl->fetch('manager_panel/template/balance.tpl');
				Base::$tpl->assign('sBalance',$sBalance);
				Base::$oResponse->addScript("$('#balance').html('".$sBalance."')");
				$this->ManagerPanelMessage ('MT_SUCCESS',Language::getMessage('balance corrected'),'reg_error_popup',1);
				return;
			}
			if ($sError) {
				$this->ManagerPanelMessage ('MT_ERROR',$sError,'reg_error_popup');
				return;
			}
		}
		
		Base::$oResponse->addAssign ( 'title_popup', 'innerHTML', Language::getMessage('correct balance').' #'.$aUser['id'] );
		$sDate = date("Y-m-d H:i:s",strtotime(Language::getConstant('finance_customer:board_date','01.01.2017').' 00:00:00') - 1);
		Base::$tpl->assign('sDate',$sDate);
		$aPayType=array('' => Language::getMessage("not selected"),
			'debt_customer' => Language::getMessage("Debt customer"),
			"prepay_customer" => Language::getMessage("Prepay customer"));
		Base::$tpl->assign('aPayType', $aPayType);
		$sBody = Base::$tpl->fetch('manager_panel/user_edit/form_correct_balance.tpl');
	
		Base::$oResponse->addAssign ( 'body_popup', 'innerHTML', $sBody );
		Base::$oResponse->addScript ("$('.block-popup').css({'width':'700','owerflow':'hidden'});");
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').show();");
		//Base::$aRequest['not_change_top_menu']=1;
	}
	//-----------------------------------------------------------------------------------------------
	public function Bill()
	{
		if (!Base::$aRequest['id_user']) {
			parent::ManagerPanelRedirect();
			return;
		}
	
		$aUser = Db::GetRow(Base::GetSql('Customer',array('id'=>Base::$aRequest['id_user'])));
		if (!$aUser) {
			parent::ManagerPanelRedirect();
			return;
		}
		Base::$tpl->assign('aUser',$aUser);
		
		$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>Base::$aRequest['id_cp'])));
		if (!$aCartPackage) {
			return;
		}
		Base::$tpl->assign('aCartPackage',$aCartPackage);
		
		if (!Base::$aRequest['code_template'] || Base::$aRequest['code_template']=='simple_bill') $sCodeTemplate='simple_bill';
		else $sCodeTemplate=Base::$aRequest['code_template'];
		
		Base::$tpl->assign('sCodeTemplate',$sCodeTemplate);
		$aAccount=Finance::AssignAccount(Auth::$aUser);
		Base::$tpl->assign('aAccount',$aAccount);
		
		if (Base::$aRequest['is_post']) {
			if (!Base::$aRequest['data_amount'] || !Base::$aRequest['data_id_account']) {
				$sError=Language::getMessage("Please, fill the required fields");
				Base::$tpl->assign('aData',$aData=Base::$aRequest);
			}
			else {
				//[----- INSERT -----------------------------------------------------]
				$aBill=array(
					'id_user' => Base::$aRequest['id_user'],
					'id_account' => Base::$aRequest['data_id_account'],
					'amount' => Base::$aRequest['data_amount'],
					'code_template' => Base::$aRequest['code_template'],
					'id_cart_package' => Base::$aRequest['id_cp'],
					'comment' => Base::$aRequest['comment'], 
				);
				$aBill = String::FilterRequestData($aBill,
					array('id_user','code_template','amount','id_cart_package','id_account','comment'));
				
				$aBill['post_date'] = date("Y-m-d 00:00:00",strtotime(Base::$aRequest['data_date']));
				
				Db::AutoExecute('bill',$aBill);
				$iIdBill = Db::InsertId();
				$aBill['account_name'] = Db::getOne("Select name from account where id=".$aBill['id_account']);
				$aBill['post_date_day'] = date("d-m-Y",strtotime($aBill['post_date']));
				
				if($aBill['id_user']) {
					$sOperation = 'pay_customer';
					if ($aBill['code_template'] == 'order_bill_bv' || $aBill['code_template'] == 'order_bill_rko')
						$sOperation = Db::getOne("select link_user_account_type_code from account where id=".$aBill['id_account']);
						
					$aOperation = Db::GetRow("Select * from user_account_type_operation where code='".$sOperation."'");
					Finance::Deposit($aBill['id_user'],$aBill['amount'],$aOperation['name'],$aBill['id_cart_package'],'interval','',
					0,0,0,$aOperation['code'],0,0,true,0,$aBill['comment'],$aBill['post_date'],$iIdBill);
					// check set pay order
					$aCartPackage=Db::GetRow("select * from cart_package where id='".$aBill['id_cart_package']."' and is_payed=0");
					if ($aCartPackage) {
						$dAmount = Db::getOne("Select sum(amount) from bill
					    	where id_cart_package=".$aBill['id_cart_package']." and (code_template='order_bill' or code_template='order_bill_bv')");
						if ($dAmount >= $aCartPackage['price_total'])
							Db::Execute("Update cart_package set is_payed=1 where id=".$aBill['id_cart_package']);
					}
				
					switch ($aBill['code_template']) {
						case 'order_bill':$sKeyTemplate='bill::create_pko';break;
						case 'order_bill_bv':$sKeyTemplate='bill::create_bv';break;
						case 'order_bill_rko':$sKeyTemplate='bill::create_rko';break;
					}
					if ($sKeyTemplate) {
						$sCustomerName = Db::getOne("Select name from user_customer where id_user=".$aBill['id_user']);
						$aManager=Db::GetRow(Base::GetSql('Manager',array(
							'id'=>Auth::$aUser['id_user'],
						)));
						Message::CreateDelayedNotification($aBill['id_user'],$sKeyTemplate
						,array('aBill'=>$aBill,'aManager'=>$aManager,'customer_name'=>$sCustomerName),true);
					}
				}
				//[----- END INSERT -------------------------------------------------]
				Base::$oResponse->addScript ("$('.js_manager_panel_popup').hide();");
				if (!Base::$aRequest['return'])
					Base::$aRequest['return']='action=manager_panel_manager_package_list_view&id='.$aCartPackage['id'];
				parent::ManagerPanelRedirect();
				return;
			}
			if ($sError) {
				$this->ManagerPanelMessage ('MT_ERROR',$sError,'reg_error_popup');
				return;
			}
		}
	
		if ($sCodeTemplate=='order_bill')
			$sTitle=Language::getMessage('s_pko');
		elseif ($sCodeTemplate=='order_bill_bv')
			$sTitle=Language::getMessage('order_bill_bv');
		elseif ($sCodeTemplate=='order_bill_rko')
			$sTitle=Language::getMessage('order_bill_rko');
		
		Base::$oResponse->addAssign ( 'title_popup', 'innerHTML', $sTitle);
		
		if (Base::$aRequest['return'])
			Base::$tpl->assign('sReturn',urlencode(Base::$aRequest['return']));
		$sBody = Base::$tpl->fetch('manager_panel/user_edit/form_bill.tpl');
	
		Base::$oResponse->addAssign ( 'body_popup', 'innerHTML', $sBody );
		Base::$oResponse->addScript ("$('.block-popup').css({'width':'700','owerflow':'hidden'});");
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').show();");
		//Base::$aRequest['not_change_top_menu']=1;
	}
	
}