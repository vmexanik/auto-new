<?
/**
 * @author Mikhail Starovoyt
 */

class Finance extends Base
{
	private static $aHaveMoney=array();
	public static $aUserAccountLogTypeAssoc=array();

	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
		if (Base::$aRequest['action']!='finance_payforaccount') {
			Auth::NeedAuth();
			Base::$aTopPageTemplate=array('panel/tab_'.Auth::$aUser['type_'].'.tpl'=>'finance');
		}
		Base::$aData['template']['bWidthLimit']=false;
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$aUserAccount=Base::$db->getRow("select * from user_account where 1=1 ".Auth::$sWhere);
		$aUserAccount['amount']=Currency::PrintPrice($aUserAccount['amount'],Auth::$aUser['code_currency']);
		Base::$tpl->assign('aUserAccount',$aUserAccount);
		Base::$tpl->assign('sDiscount', $sDiscount=Discount::CustomerDiscount(Auth::$aUser));
		Base::$tpl->assign('sDebt',Currency::PrintPrice(
		max(array(Auth::$aUser['user_debt'], Auth::$aUser['group_debt']))),Auth::$aUser['code_currency']);

		Base::$tpl->assign('aAccesType',array(
		'own'=>Language::GetMessage('Own finance'),
		'subuser'=>Language::GetMessage('Subuser finance'),
		));
		Base::$tpl->assign('aUserAccountLogType',Base::$db->GetAssoc(Base::GetSql('Finance/UserAccountLogTypeAssoc')));

		$aField['amount']=array('title'=>'Account money','type'=>'text','value'=>$aUserAccount['amount']);
		if(Auth::$aUser['type_']=='customer' && Auth::$aUser['price_type']=='discount')
		    $aField['discount']=array('title'=>'Discount','type'=>'text','value'=>$sDiscount.' %','contexthint'=>'customer_finance_discount');
		$aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("1.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
		$aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		
		$aData=array(
		'sHeader'=>"",
		'sTitle'=>"Finance info",
		//'sContent'=>Base::$tpl->fetch('finance/form_user_account_log_search.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sGenerateTpl'=>'form/index_search.tpl',
		'sSubmitButton'=>'Search',
		'sSubmitAction'=>'finance',
		'sReturnButton'=>'Clear',
		'bIsPost'=>0,
		'sError'=>$sError,
		);
		$oForm=new Form($aData);

		Base::$sText.=$oForm->getForm();


		// --- search ---
		if (Base::$aRequest['search']['date']) {
			$sWhere.=" and ual.post>='".strtotime(Base::$aRequest['date_from'])."'
				and ual.post<='".strtotime(Base::$aRequest['date_to'])."'";
		}
		if (Base::$aRequest['search']['subuser_login']) $sWhere.=" and u.login='".Base::$aRequest['search']['subuser_login']."'";

		if (Base::$aRequest['search']['acces_type']=='own' || !Base::$aRequest['search']['acces_type']) {
			$sWhere.=" and ual.id_user='".Auth::$aUser['id']."'";
		}
		else {
			$aSubuserId=array_keys(Base::$db->GetAssoc(Base::GetSql('Customer/SubuserAssoc',array(
			'id_user'=>Auth::$aUser['id'],
			))));
			$sWhere.=" and ual.id_user in(".implode(',',$aSubuserId).")";
		}
		if (Base::$aRequest['search']['id_user_account_log_type']) {
			$sWhere.=" and ual.id_user_account_log_type='".Base::$aRequest['search']['id_user_account_log_type']."'";
		}
		if (Base::$aRequest['search']['id']) {
			$sWhere.=" and ual.id='".Base::$aRequest['search']['id']."'";
		}
		// --------------

		$oTable=new Table();
		$oTable->iRowPerPage=20;
		$oTable->sSql=Base::GetSql('UserAccountLog',array('where'=>$sWhere));
		$_SESSION['finance']['current_sql']=$oTable->sSql;
		$oTable->aOrdered="order by id desc";
		$oTable->aColumn=array(
		'current_account_amount'=>array('sTitle'=>'PostAccountAmount'),
		'account_amount'=>array('sTitle'=>'AccountAmount/DebtAmount'),
		'debet'=>array('sTitle'=>'finance debet'),
		'credit'=>array('sTitle'=>'finance credit'),
		'post'=>array('sTitle'=>'Date'),
		'description'=>array('sTitle'=>'UalDescription'),
		);
		$oTable->sDataTemplate='finance/row_user_account_log.tpl';
		$oTable->sButtonTemplate='finance/button_finance.tpl';
		$oTable->aCallback=array($this,'CallParseLog');

		Base::$sText.=$oTable->getTable("Account Log",'customer_account_log');
	}
	//-----------------------------------------------------------------------------------------------
// 	public function CallParseLog(&$aItem)
// 	{
// 		$aCustomerDebt=Base::$db->GetAll(Base::GetSql('CustomerDebt'));
// 		$aCustomerDebtHash=Language::Array2Hash($aCustomerDebt,'id_user');
// 		//Base::$tpl->assign('aCustomerDebtHash',$aCustomerDebtHash);

// 		if ($aItem) foreach($aItem as $key => $value) {
// 			$aItem[$key]['current_debt_amount']=$aCustomerDebtHash[$value['id_user']]['amount'];
// 		}
// 	}
	//-----------------------------------------------------------------------------------------------
	public function ExportAll()
	{

		$sFileName=Finance::CreateFinanceExcel($_SESSION['finance']['current_sql'].' order by ual.id desc');
		Base::$tpl->assign('sFileName',$sFileName);

		Base::$sText.=Base::$tpl->fetch('finance/export.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public static function CreateFinanceExcel($sSql,$bShowCustomer=false)
	{
		set_include_path(SERVER_PATH.'/lib/PHPExcel/');
		require_once(SERVER_PATH.'/lib/PHPExcel/PHPExcel.php');
		require_once(SERVER_PATH.'/lib/PHPExcel/PHPExcel/Writer/Excel2007.php');
		require_once(SERVER_PATH.'/lib/PHPExcel/PHPExcel/Writer/Excel5.php');

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A2', iconv('windows-1251','utf-8',Language::getMessage('LOG_CurrentAmount')));
		$objPHPExcel->getActiveSheet()->setCellValue('B2', iconv('windows-1251','utf-8',Language::getMessage('LOG_AccountAmount')));
		$objPHPExcel->getActiveSheet()->setCellValue('C2', iconv('windows-1251','utf-8',Language::getMessage('LOG_Amount')));
		$objPHPExcel->getActiveSheet()->setCellValue('D2', iconv('windows-1251','utf-8',Language::getMessage('LOG_CustomId')));
		$objPHPExcel->getActiveSheet()->setCellValue('E2', iconv('windows-1251','utf-8',Language::getMessage('LOG_POST')));
		$objPHPExcel->getActiveSheet()->setCellValue('F2', iconv('windows-1251','utf-8',Language::getMessage('LOG_Type')));
		$objPHPExcel->getActiveSheet()->setCellValue('G2', iconv('windows-1251','utf-8',Language::getMessage('LOG_PayType')));
		$objPHPExcel->getActiveSheet()->setCellValue('H2', iconv('windows-1251','utf-8',Language::getMessage('LOG_Description')));

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
		array('font'    => array('bold'      => true),
		'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
		'borders' => array('top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN)
		),
		'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID  ,'rotation'   => 90,
		'startcolor' => array('argb' => 'FFA0A0A0'),'endcolor'   => array('argb' => 'FFFFFFFF'
		))),'A2:M2');

		$objPHPExcel->getActiveSheet()->setTitle('Account Logs');
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		$aLog=Base::$db->getAll($sSql);
		if ($aLog) {
			$i=3;
			foreach ($aLog as $aValue) {
				if ($bShowCustomer) $sCsutomerLogin=$aValue['login'];

				$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,
				iconv('windows-1251','utf-8',strip_tags(Language::PrintPrice($aValue['current_account_amount'])).' '.$sCsutomerLogin ));

				if (Auth::$aUser['type_']=='manager') $aValue['account_amount']=str_replace('.',',',$aValue['account_amount']);
				else $aValue['account_amount']=iconv('windows-1251','utf-8',strip_tags(Language::PrintPrice($aValue['account_amount'])));
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$i,$aValue['account_amount']);

				if (Auth::$aUser['type_']=='manager') $aValue['amount']=str_replace('.',',',$aValue['amount']);
				else $aValue['amount']=iconv('windows-1251','utf-8',strip_tags(Language::PrintPrice($aValue['amount'])));
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $aValue['amount']);

				$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $aValue['custom_id']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $aValue['post_date']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, iconv('windows-1251','utf-8',$aValue['type_']));
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, iconv('windows-1251','utf-8',$aValue['pay_type']));
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, iconv('windows-1251','utf-8',$aValue['description']));
				$i++;
				if ($i>=500) break;
			}

			$sFileName=uniqid().'.xls';
			$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
			$objWriter->save(SERVER_PATH.'/imgbank/temp_upload/'.$sFileName);
		}
		else $sFileName='EmptyData.xls';

		return $sFileName;
	}
	//-----------------------------------------------------------------------------------------------
	public function BillforUser ()
	{
	    $aTemplate=array(
	        ''=>Language::GetMessage('All'),
	        'simple_bill'=>Language::GetMessage('simple_bill'),
	        'order_bill'=>Language::GetMessage('order_bill'),
	    );
	    
	    $aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("1.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
	    $aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
	    $aField['amount_from']=array('title'=>'amFrom','type'=>'input','value'=>Base::$aRequest['search']['amount_from'],'name'=>'search[amount_from]','checkbox'=>1);
	    $aField['amount_to']=array('title'=>'amTo','type'=>'input','value'=>Base::$aRequest['search']['amount_to'],'name'=>'search[amount_to]');
	    $aField['template']=array('title'=>'Template','type'=>'select','options'=>$aTemplate,'selected'=>Base::$aRequest['search']['template'],'name'=>'search[template]');
	    $aField['id_cart_package']=array('title'=>'cartpackage #','type'=>'input','value'=>Base::$aRequest['search']['id_cart_package'],'name'=>'search[id_cart_package]');
	    $aField['id']=array('title'=>'id','type'=>'input','value'=>Base::$aRequest['search']['id'],'name'=>'search[id]');
	    $aData=array(
	        'sHeader'=>"method=get",
	        //'sContent'=>Base::$tpl->fetch('finance/form_bill_user_search.tpl'),
	        'aField'=>$aField,
	        'bType'=>'generate',
	        'sGenerateTpl'=>'form/index_search.tpl',
	        'sSubmitButton'=>'Search',
	        'sSubmitAction'=>'finance_user',
	        'sReturnButton'=>'Clear',
	        'bIsPost'=>0,
	        'sWidth'=>'55%',
	        'sError'=>$sError,
	    );
	    $oForm=new Form($aData);
	    
	    Base::$sText .= $oForm->getForm();
	    
	    $oTable=new Table();
	    if (Auth::$aUser['type_']=='customer') $sWhere=Auth::$sWhere;
	    else $sWhere='';
	    
	    $sWhere = str_replace('and id_user','and b.id_user', $sWhere);
	    
	    // --- search ---
	    if (Base::$aRequest['search']['template'])
	        $sWhere.=" and b.code_template ='".Base::$aRequest['search']['template']."'";
	    if (Base::$aRequest['search']['date']) {
	        $sWhere.=" and (b.post_date >= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
	            and b.post_date <= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."') ";
	    }
	    if (Base::$aRequest['search']['amount']) {
	        $sWhere.=" and (b.amount >= '".Base::$aRequest['search']['amount_from']."'
	            and b.amount <= '".Base::$aRequest['search']['amount_to']."') ";
	    }
	    if (Base::$aRequest['search']['id_cart_package'])
	        $sWhere.=" and b.id_cart_package like '%".Base::$aRequest['search']['id_cart_package']."%'";
	    if (Base::$aRequest['search']['id'])
	        $sWhere.=" and b.id like '%".Base::$aRequest['search']['id']."%'";
	    // --- search ---
	    $oTable->sSql=Base::GetSql('Bill',array(
	        "where"=>$sWhere,
	    ));
	    $oTable->aColumn=array(
	        'id_cart_package'=>array('sTitle'=>'cartpackage #'),
	        'id'=>array('sTitle'=>'id'),
	        'amount'=>array('sTitle'=>'Amount'),
	        'amount'=>array('sTitle'=>'Amount'),
	        'template'=>array('sTitle'=>'Template'),
	        'post'=>array('sTitle'=>'Date'),
	        'action'=>array(),
	    );
	    $oTable->aOrdered="order by b.post_date desc";
	    $oTable->sDataTemplate='finance/row_bill.tpl';
	    $oTable->sButtonTemplate='finance/button_bill.tpl';
	    $oTable->bCheckVisible=true;
	    $oTable->sWidth='100%';
	    
	    if(Auth::$aUser['type_']=='manager') {
	        Base::$sText.=$oTable->getTable("Customer Bills",'manager_bill');
	    } else {
	        Base::$sText.=$oTable->getTable("Customer Bills",'customer_bill');
	    }
	}
	//-----------------------------------------------------------------------------------------------	
	public function Bill()
	{
	    if(Auth::$aUser['is_super_manager'])
	        $sWhereManager = ' ';
	    else
	        $sWhereManager = " and uc.id_manager='".Auth::$aUser['id_user']."' ";
	    
		if (Base::$aRequest['is_post']) {
			$bCheckManagerLogin=true;
			if (Auth::$aUser['type_']=='manager') {
				if (!Base::$aRequest['data']['login']) $bCheckManagerLogin=false;
				else {
					$aUser=Db::GetRow(Base::GetSql('Customer',array('login'=>Base::$aRequest['data']['login'])));
					if ($aUser) $iIdUser=$aUser['id'];
					else $bCheckManagerLogin=false;
				}
			}

			if (!Base::$aRequest['data']['amount'] || !Base::$aRequest['data']['id_account'] || !Base::$aRequest['data']['id_cart_package'] || !$bCheckManagerLogin) {
				$sError=Language::GetMessage("Please, fill amount, id_account, login fields");
				if(!Base::$aRequest['data']['id_cart_package']) {
				    $sError.=" ".Language::GetMessage("fill order id field");
				}
				Base::$aRequest['action']='finance_bill_add';
				Base::$tpl->assign('aData',$aData=Base::$aRequest['data']);
			}
			else {
				// check cart package
				if (Base::$aRequest['data']['id_cart_package']) {
					$aCartPackage = Db::getRow("Select * from cart_package where id=".Base::$aRequest['data']['id_cart_package']);
					if (!$aCartPackage || $aCartPackage['id_user']!=$iIdUser)
						$sError.=" ".Language::GetMessage("incorrect order id field");
				}
				
				if (!$sError) {
					if (!Base::$aRequest['id']) {
						//[----- INSERT -----------------------------------------------------]
						$aBill=String::FilterRequestData(Base::$aRequest['data']
						,array('code_template','amount','id_cart_package','id_account','comment'));
						if (!$aBill['id_account']) {
							if ($aBill['code_template'] == 'order_bill_bv')
								$aActiveAccount=Db::GetRow(Base::GetSql('Account',array('visible'=>1,'in_use_bv' => 1)));
							elseif ($aBill['code_template'] == 'order_bill_rko')
								$aActiveAccount=Db::GetRow(Base::GetSql('Account',array('visible'=>1,'in_use_rko' => 1)));
							else 
								$aActiveAccount=Db::GetRow(Base::GetSql('Account',array('visible'=>1,'in_use_pko' => 1)));
							
							$aBill['id_account']=$aActiveAccount['id'];
						}
						
						if (Auth::$aUser['type_']=='customer') $aBill['id_user']=Auth::$aUser['id'];
						else $aBill['id_user']=$iIdUser;
	
						$iExist = Db::getOne("Select id from bill where
							id_user = ".$iIdUser." and id_cart_package=".Base::$aRequest['data']['id_cart_package'].
							" and amount='".Base::$aRequest['data']['amount'].
							"' and code_template='".Base::$aRequest['data']['code_template'].
							"' and id_account='".$aBill['id_account']."'");
						if ($iExist)
							$sError.=" ".Language::GetMessage("this document already created");
						else {
							$aBill['post_date'] = date("Y-m-d H:i:s",strtotime(Base::$aRequest['post_date']));

							Db::AutoExecute('bill',$aBill);
							$iIdBill = Db::InsertId();
							$aBill['account_name'] = Db::getOne("Select name from account where id=".$aBill['id_account']);
							$aBill['post_date_day'] = date("d-m-Y",strtotime($aBill['post_date']));
						
							if($aBill['id_user']) {
								$sOperation = 'pay_customer';
								if ($aBill['code_template'] == 'order_bill_bv' || $aBill['code_template'] == 'order_bill_rko')
									$sOperation = Db::getOne("select link_user_account_type_code from account where id=".Base::$aRequest['data']['id_account']);
							
								$aOperation = Db::GetRow("Select * from user_account_type_operation where code='".$sOperation."'");
								Finance::Deposit($aBill['id_user'],$aBill['amount'],$aOperation['name'],Base::$aRequest['data']['id_cart_package'],'interval','',
									0,0,0,$aOperation['code'],0,0,true,0,$aBill['comment'],$aBill['post_date'],$iIdBill);
								// check set pay order
								$aCartPackage=Db::GetRow("select * from cart_package where id='".Base::$aRequest['data']['id_cart_package']."' and is_payed=0");
								if ($aCartPackage) {
									$dAmount = Db::getOne("Select sum(amount) from bill 
										where id_cart_package=".Base::$aRequest['data']['id_cart_package']." and (code_template='order_bill' or code_template='order_bill_bv')");
									if ($dAmount >= $aCartPackage['price_total'])
										Db::Execute("Update cart_package set is_payed=1 where id=".Base::$aRequest['data']['id_cart_package']);
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
										,array('aBill'=>$aBill,'aManager'=>$aManager,'customer_name'=>$sCustomerName),true,$aCart['id']);
								}
							}
						}
						//[----- END INSERT -------------------------------------------------]
					} else {
						//[----- UPDATE -----------------------------------------------------]
						$aCurrentData = Db::getRow("Select * from bill where id='".Base::$aRequest['id']."'");
						// check link ual
						$aUal = Db::getRow("Select l.* from user_account_log l
							inner join user u on u.id = l.id_user and u.type_='customer'
							where l.id_bill=".Base::$aRequest['id']);
						if ($aCurrentData && $aUal) {
							$sPostDate = Base::$aRequest['post_date'].' 00:00:00';
							$sLastTime = date("Y-m-d 23:59:59",strtotime(Base::$aRequest['post_date']));
							$sMax = Db::getOne("Select max(post_date) from user_account_log where 
								post_date>'".$sPostDate."' and post_date<'".$sLastTime."' and id_user=".$aCurrentData['id_user']);
							if ($sMax)
								$sPostDate = date("Y-m-d H:i:s",strtotime($sMax)+1);
							
							$sQuery="update bill set post_date='".date("Y.m.d H:i:s",strtotime($sPostDate))."',
								comment='".mysql_real_escape_string(Base::$aRequest['data']['comment'])."',
								amount='".Base::$aRequest['data']['amount']."'
								".(Base::$aRequest['data']['id_account'] ? ",id_account='".Base::$aRequest['data']['id_account']."'":"")."
								".(Base::$aRequest['data']['id_cart_package'] ? ",id_cart_package='".Base::$aRequest['data']['id_cart_package']."'":"").
		                        "	where id='".Base::$aRequest['id']."'";
							Base::$db->Execute($sQuery);
							// ual
							$dAmount = Base::$aRequest['data']['amount'];
							if ($aUal['operation']=='back_pay_customer')
								$dAmount = '-'.abs(Base::$aRequest['data']['amount']);
							
							$sQuery="update user_account_log set post_date='".date("Y.m.d H:i:s",strtotime($sPostDate))."',
								comment='".mysql_real_escape_string(Base::$aRequest['data']['comment'])."',
								amount='".$dAmount."'
								".(Base::$aRequest['data']['id_account'] ? ",id_account='".Base::$aRequest['data']['id_account']."'":"")."
								".(Base::$aRequest['data']['id_cart_package'] ? ",custom_id='".Base::$aRequest['data']['id_cart_package']."'":"")."
                        		where id='".$aUal['id']."'";
							Base::$db->Execute($sQuery);
							// recalc balance
							$dCurrentBalance = $this->getDebtBegin($aUal['id_user']);
							Db::Execute("Update user_account set amount='".$dCurrentBalance."' where id_user='".$aUal['id_user']."'");
						}
						//[----- END UPDATE -------------------------------------------------]
					}
				}
			}
			if (!$sError) {
				if (Base::$aRequest['return_action'])
					Base::Redirect("/?action=".Base::$aRequest['return_action']);
				Base::Redirect("/?action=finance_bill");
			}
		}
		
		if ($sError && Base::$aRequest['return_action']) {
			Base::$aRequest['action'] = 'finance_bill_add';
			if (Base::$aRequest['id'])
				Base::$aRequest['action'] = 'finance_bill_edit';
			//Base::Redirect("/?action=".Base::$aRequest['return_action']."&aMessage[MI_ERROR_NT]=".Language::getMessage($sError));
		}
		
		Base::$tpl->assign('aNameUser',$aNameUser=array(0 =>'')+Db::GetAssoc("select u.login, concat(uc.name,' ( ',u.login,' )',
				IF(uc.phone is null or uc.phone='','',concat(' ".
		    Language::getMessage('tel.')." ',uc.phone))) name
		from user as u
		inner join user_customer as uc on u.id=uc.id_user
		where u.visible=1 and uc.name is not null and trim(uc.name)!='' ".$sWhereManager."
		/*and uc.id_manager='".Auth::$aUser['id_user']."'*/
		order by uc.name"));
		
		if (Base::$aRequest['action']=='finance_bill_add' || Base::$aRequest['action']=='finance_bill_edit') {
			if (Base::$aRequest['action']=='finance_bill_edit') {
				$aBill=Db::GetRow(Base::GetSql('Bill',array('id'=>Base::$aRequest['id'])));
				Base::$tpl->assign('aData',$aData=$aBill);
				Base::$aRequest['code_template'] = $aBill['code_template'];
			}
			if (Base::$aRequest['data']['amount']) Base::$tpl->assign('aData',$aData=Base::$aRequest['data']);

			if (!Base::$aRequest['code_template'] || Base::$aRequest['code_template']=='simple_bill') $sCodeTemplate='simple_bill';
			else $sCodeTemplate=Base::$aRequest['code_template'];

			Base::$tpl->assign('sCodeTemplate',$sCodeTemplate);
			$aAccount=Finance::AssignAccount(Auth::$aUser);
			
			$sReturnAction = 'finance_bill';
			if (Base::$aRequest['return_action'])
				$sReturnAction = Base::$aRequest['return_action'];

			$aField['post_date']=array('title'=>'Date','type'=>'date','value'=>Base::$aRequest['post_date']?Base::$aRequest['post_date']:date("d.m.Y"),
				'name'=>'post_date','id'=>'date','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
				
			//if(Base::$aRequest['code_template']=='order_bill') 
			    $aField['id_cart_package']=array('title'=>'Id cart package','type'=>'input','value'=>$aData['id_cart_package'],'name'=>'data[id_cart_package]','szir'=>1);

			if(Auth::$aUser['type_']=='manager') {
				$iReadOnly=0;
				if ($aBill)
					$iReadOnly=1;
			    $aField['login']=array('title'=>'Login','readonly'=>$iReadOnly, 'type'=>'input','value'=>$aData['login'],'name'=>'data[login]','szir'=>1);//'class'=>"phone_mask", 'placeholder'=>"(___)___ __ __"
			}
			$aField['id_account']=array('title'=>'Account','type'=>'select','options'=>$aAccount,'selected'=>$aData['id_account'],'name'=>'data[id_account]','szir'=>1);
			//$aField['type_operation']=array('title'=>'type_operation','type'=>'select','options'=>$aTypeOperation,'selected'=>'','name'=>'data[type_operation]','szir'=>1);
			$aField['amount']=array('title'=>'Amount','type'=>'input','value'=>$aData['amount']?$aData['amount']:Base::$aRequest['amount'],'name'=>'data[amount]','szir'=>1);
			$aField['code_template']=array('type'=>'hidden','name'=>'data[code_template]','value'=>$sCodeTemplate);
			$aField['comment']=array('title'=>'Comment','type'=>'textarea','name'=>'data[comment]','value'=>$aData['comment']?$aData['comment']:Base::$aRequest['data']['comment']);
			$aField['return_action']=array('type'=>'hidden','name'=>'return_action','value'=>$sReturnAction);
			
			$aData=array(
			'sHeader'=>"method=post",
			'sTitle'=> $sCodeTemplate,
			//'sContent'=>Base::$tpl->fetch('finance/form_bill.tpl'),
			'aField'=>$aField,
			'bType'=>'generate',
			'sSubmitButton'=>'Apply',
			'sSubmitAction'=>'finance_bill',
			'sReturnButton'=>'<< Return',
			'sReturnAction'=>$sReturnAction,
			'sError'=>$sError,
			);
			$oForm=new Form($aData);

			Base::$sText.=Language::GetText('finance bill add desctiption');
			Base::$sText.=$oForm->getForm();
			return;
		}

		if (Base::$aRequest['action']=='finance_bill_delete') {
			if (Auth::$aUser['type_']=='customer') $sWhere=Auth::$sWhere; else $sWhere='';
			/*if (Base::$aRequest['row_check']) {
				Base::$db->Execute("delete from bill where id in (".implode(',',Base::$aRequest['row_check']).")
					".$sWhere);
			}
			else {*/
			// check link ual
			$aUal = Db::getRow("Select * from user_account_log where id_bill=".Base::$aRequest['id']);
			if ($aUal) {
				Base::$db->Execute("delete from bill where id='".Base::$aRequest['id']."'
				".$sWhere);
				Base::$db->Execute("delete from user_account_log where id='".$aUal['id']."'");
				// recalc balance
				$dCurrentBalance = $this->getDebtBegin($aUal['id_user']);
				Db::Execute("Update user_account set amount='".$dCurrentBalance."' where id_user='".$aUal['id_user']."'");
			}
			if (Base::$aRequest['return_action'])
				Base::Redirect("/?action=".Base::$aRequest['return_action']);
			Base::Redirect("/?action=finance_bill");				
			//}
		}
// 		    Resource::Get()->Add('/js/jquery.searchabledropdown-1.0.8.min.js',1);
// 		    Resource::Get()->Add('/js/select2.min.js',1);
// 		    Resource::Get()->Add('/css/select2.min.css');
		
		$aTemplate=array(
		  ''=>Language::GetMessage('All'),
		  'simple_bill'=>Language::GetMessage('simple_bill'),
		  'order_bill'=>Language::GetMessage('order_bill'),
		);
		
		
		Base::$tpl->assign('aNameUser',$aNameUser=array(0 =>'')+Db::GetAssoc("select u.login, concat(ifnull(uc.name,''),' ( ',u.login,' )',
				IF(uc.phone is null or uc.phone='','',concat(' ".
						Language::getMessage('tel.')." ',uc.phone))) name
		from user as u
		inner join user_customer as uc on u.id=uc.id_user
		where u.visible=1 /*and uc.name is not null and trim(uc.name)!=''*/
		".$sWhereManager."
		order by uc.name"));
		Resource::Get()->Add('/js/select_search.js');
		
		if(Auth::$aUser['typle_']=='manager') 
		    $aField['search_login']=array('title'=>'Login','type'=>'select','options'=>$aNameUser,'selected'=>Base::$aRequest['search_login'],'name'=>'search_login','class'=>'select_search');
		$aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("1.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
		$aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		if(Auth::$aUser['typle_']=='manager') 
		    $aField['fio']=array('title'=>'Fio','type'=>'input','value'=>Base::$aRequest['search']['fio'],'name'=>'search[fio]');
		$aField['amount_from']=array('title'=>'amFrom','type'=>'input','value'=>Base::$aRequest['search']['amount_from'],'name'=>'search[amount_from]','checkbox'=>1);
		$aField['amount_to']=array('title'=>'amTo','type'=>'input','value'=>Base::$aRequest['search']['amount_to'],'name'=>'search[amount_to]');
		$aField['template']=array('title'=>'Template','type'=>'select','options'=>$aTemplate,'selected'=>Base::$aRequest['search']['template'],'name'=>'search[template]');
		$aField['id_cart_package']=array('title'=>'cartpackage #','type'=>'input','value'=>Base::$aRequest['search']['id_cart_package'],'name'=>'search[id_cart_package]');
		$aField['id']=array('title'=>'id','type'=>'input','value'=>Base::$aRequest['search']['id'],'name'=>'search[id]');
		$aField['search_login']=array('title'=>'Login_','type'=>'select','options'=>$aNameUser,'name'=>'search_login','selected'=>Base::$aRequest['search_login'],'class'=>'select_name_user');
		$aData=array(
				'sHeader'=>"method=get",
				//'sContent'=>Base::$tpl->fetch('finance/form_bill_search.tpl'),
		        'aField'=>$aField,
		        'bType'=>'generate',
		        'sGenerateTpl'=>'form/index_search.tpl',
				'sSubmitButton'=>'Search',
				'sSubmitAction'=>'finance_bill',
				'sReturnButton'=>'Clear',
				'bIsPost'=>0,
		        'sWidth'=>'80%',
				'sError'=>$sError,
		);
		$oForm=new Form($aData);
		
		Base::$sText .= $oForm->getForm();
		
		$oTable=new Table();
		if (Auth::$aUser['type_']=='customer') $sWhere=Auth::$sWhere;
		else $sWhere='';
		
		$sWhere = str_replace('and id_user','and b.id_user', $sWhere);
		
		// --- search ---
		if (Base::$aRequest['search_login']) {
		    $sWhere.=" and (u.login like '%".Base::$aRequest['search_login']."%'";
		    $sWhere.=" || uc.name like '%".Base::$aRequest['search_login']."%'";
		    $sWhere.=" || uc.phone like '%".Base::$aRequest['search_login']."%')";
		}
		if (Base::$aRequest['search']['fio']) $sWhere.=" and uc.name like '%".Base::$aRequest['search']['fio']."%'";
		
		if (Base::$aRequest['search']['template'])
		    $sWhere.=" and b.code_template ='".Base::$aRequest['search']['template']."'";
		if (Base::$aRequest['search']['date']) {
		    $sWhere.=" and (b.post_date >= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
	            and b.post_date <= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."') ";
		}
		if (Base::$aRequest['search']['amount']) {
		    $sWhere.=" and (b.amount >= '".Base::$aRequest['search']['amount_from']."'
	            and b.amount <= '".Base::$aRequest['search']['amount_to']."') ";
		}
		if (Base::$aRequest['search']['id_cart_package']) 
		    $sWhere.=" and b.id_cart_package like '%".Base::$aRequest['search']['id_cart_package']."%'";
		if (Base::$aRequest['search']['id'])
		    $sWhere.=" and b.id like '%".Base::$aRequest['search']['id']."%'";
		
		// --- search ---
		$oTable->sSql=Base::GetSql('Bill',array(
		"where"=>$sWhere,
		));
		$oTable->aColumn=array(
		'id_cart_package'=>array('sTitle'=>'cartpackage #'),
		'id'=>array('sTitle'=>'id'),
		'amount'=>array('sTitle'=>'Amount'),
		'template'=>array('sTitle'=>'Template'),
		'post'=>array('sTitle'=>'Date'),
		'action'=>array(),
		);
		$oTable->aOrdered="order by b.post_date desc";
		$oTable->sDataTemplate='finance/row_bill.tpl';
		$oTable->sButtonTemplate='finance/button_bill.tpl';
		$oTable->bCheckVisible=true;
		$oTable->sWidth='100%';
		Base::$sText.=$oTable->getTable("Customer Bills",'customer_bill');
	}
	//-----------------------------------------------------------------------------------------------
	public function BillProviderPrint($iIdBill) {
		if ($iIdBill) Base::$aRequest['id']=$iIdBill;
		
		$aBill=Base::$db->getRow("select * from bill_provider where id='".Base::$aRequest['id']."' ".$sWhere);
		$aBill['amount']=str_replace(".",",",sprintf("%.2f",Currency::BillRound($aBill['amount'])));
		$aBill['amount_string']=String::GetUcfirst(Currency::CurrecyConvert($aBill['amount'],
				Base::GetConstant('global:base_currency')));
		$aUser=Db::GetRow(Base::GetSql('Provider',array('id'=>$aBill['id_user'])));
		
		//Base::$tpl->assign('aActiveAccount',Db::GetRow(Base::GetSql('Account',array('id'=>$aBill['id_account']))));
		
		Base::$tpl->assign('sDate', date ("d.m.Y", strtotime( $aBill['post_date'])));
		Base::$tpl->assign('aBill',$aBill);
		Base::$tpl->assign('aUser',$aUser);
		
		$sContent=Base::$tpl->fetch('finance/print_'.$aBill['code_template'].'_provider.tpl');
		
		if (Base::$aRequest['send_file']) {
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=\"finance_bill.html\"");
			Base::$tpl->assign('sContent',$sContent);
			Base::$tpl->assign('bHideButtonTable', true);
			die(Base::$tpl->fetch('addon/print_content/index.tpl'));
		}
		PrintContent::Append($sContent);
		Base::Redirect('?action=print_content');
	}
	//-----------------------------------------------------------------------------------------------
	public function BillPrint($iIdBill='')
	{
		if ($iIdBill) Base::$aRequest['id']=$iIdBill;

		$aBill=Base::$db->getRow("select * from bill where id='".Base::$aRequest['id']."' ".$sWhere);
		$aBill['amount']=str_replace(".",",",sprintf("%.2f",Currency::BillRound($aBill['amount'])));
		$aBill['amount_string']=String::GetUcfirst(Currency::CurrecyConvert($aBill['amount'],
			Base::GetConstant('global:base_currency')));
		$aUser=Db::GetRow(Base::GetSql('Customer',array('id'=>$aBill['id_user'])));

		Base::$tpl->assign('aActiveAccount',Db::GetRow(Base::GetSql('Account',array('id'=>$aBill['id_account']))));

		Base::$tpl->assign('sDate', date ("d.m.Y", strtotime( $aBill['post_date'])));
		Base::$tpl->assign('aBill',$aBill);
		Base::$tpl->assign('aUser',$aUser);

		$sContent=Base::$tpl->fetch('finance/print_'.$aBill['code_template'].'.tpl');

		if (Base::$aRequest['send_file']) {
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=\"finance_bill.html\"");
			Base::$tpl->assign('sContent',$sContent);
			Base::$tpl->assign('bHideButtonTable', true);
			die(Base::$tpl->fetch('addon/print_content/index.tpl'));
		}
		PrintContent::Append($sContent);
		Base::Redirect('?action=print_content');
	}
	//-----------------------------------------------------------------------------------------------
	public function BillPay()
	{
		$aBill=Db::GetRow(Base::GetSql('Bill',array(
		'id'=>(Base::$aRequest['id']? Base::$aRequest['id'] : -1),
		'join_currency'=>true,
		)));
		if (!$aBill) Form::RedirectAuto("&aMessage[MI_NOTICE]=bill_incorrect");

		$aUser=Db::GetRow(Base::GetSql('Customer',array('id'=>$aBill['id_user'])));

		if($aBill['code_template']=='order_bill' && !$aBill['is_payed'] && Auth::$aUser['has_customer'] && Auth::$aUser['is_support']){
			Base::$aRequest['data']['id']=$aBill['id_user'];
			Base::$aRequest['data']['id_subconto1']=$aBill['id_account'];
			Base::$aRequest['data']['amount']=Currency::BasePrice($aBill['amount'],$aBill['currency_code']);
			Base::$aRequest['data']['code_currency']=$aBill['currency_code'];
			//Base::$aRequest['data']['custom_id']=;
			//Base::$aRequest['data']['section']=;
			Base::$aRequest['data']['id_user_account_log_type_debit']=361;
			Base::$aRequest['data']['currency_'.$aBill['currency_code']]=$aBill['amount'];

			require_once(SERVER_PATH.'/mpanel/spec/customer.php');
			$oACustomer=new ACustomer();
			$oACustomer->DepositApply(false);

			$aBillUpdate=array(
			'is_payed'=>1,
			'payed_date'=>date("Y-m-d H:i:s"),
			'payed_manager'=>Auth::$aUser['login'],
			);
			Db::AutoExecute('bill',$aBillUpdate,'UPDATE',"id='".$aBill['id']."'");
		}

		Form::RedirectAuto();
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * The sigle method for money transactions
	 *
	 * @param  $iIdUser - user which acount is aupdated
	 * @param  $dAmount - amount of money
	 * @param  $sDescription - Transaction description
	 * @param  $iCustomId - ref_id for Section
	 * @param  $sPayType - internal, webmoney, nal, beznal
	 * @param  $sSection - ref table for links
	 * @param  $sData - additional text info
	 * @param  $iIdUserAccountLogType - strict transaction type, reference key to user_aaccount_log_type table
	 * @return Insert_ID into user_account_log table
	 */
	public function Deposit($iIdUser,$dAmount,$sDescription='',$iCustomId='',$sSection='internal',$sData=''
	,$iIdUserAccountLogTypeDebit=0,$iIdUserAccountLogTypeCredit=0
	,$iIdSubconto1=0,$sOperation='',$iIdSubconto2=0,$iIdSubconto3=0,$bCheckZero=true,$iIdOffice=0
	,$sComment='',$sPostDate='',$iIdBill=0,$iIdCart=0)
	{
		Db::StartTrans();
		
		$dAmount = str_replace(',', '.', $dAmount);
		
		$aSerialData=array($iIdUser,$dAmount,$sDescription,$iCustomId,$sPayType,$sSection,$sData,$iIdUserAccountLogType,$sComment);
		if ($dAmount == 0 && $bCheckZero) {
			Finance::TransactionError($aSerialData,$iIdUser,Language::GetMessage('Zero amount'));
		}
		
		if ($sOperation) {
			$aOperation = Db::GetRow("Select * from user_account_type_operation where code='".$sOperation."'");
			if ($aOperation['formula_balance'] == '+')
				$dAmount = abs($dAmount);
			else {
				$dAmount = abs($dAmount);
				$dAmount = -$dAmount;
			}
		}
		if ($sPostDate=='')
			$sPostDate = date("Y-m-d H:i:s");
		else {
			$sTime = substr($sPostDate,11);
			if ($sTime=='00:00:00') { // need next free time in log for this date
				$sLastTime = date("Y-m-d 23:59:59",strtotime($sPostDate));
				$sMax = Db::getOne("Select max(post_date) from user_account_log where post_date>'".$sPostDate."' and post_date<'".$sLastTime.
					"' and id_user='".$iIdUser."'");
				if ($sMax)
					$sPostDate = date("Y-m-d H:i:s",strtotime($sMax)+1);
			}
		} 
		$bResult = Db::Execute("insert user_account_log(id_user,amount,description
			,custom_id,section,data
			,id_user_account_log_type_debit,id_user_account_log_type_credit
			,id_subconto1,id_subconto2,id_subconto3,operation,id_office,comment, post_date, id_bill, id_cart)
			values ('$iIdUser','$dAmount','".mysql_real_escape_string($sDescription)."'
			,'".mysql_real_escape_string($iCustomId)."','$sSection','".mysql_real_escape_string($sData)."'
			,'".$iIdUserAccountLogTypeDebit."','".$iIdUserAccountLogTypeCredit."'
			,'".$iIdSubconto1."','".$iIdSubconto2."','".$iIdSubconto3."','".$sOperation."',".$iIdOffice.
			",'".$sComment."','".$sPostDate."',".$iIdBill.",".$iIdCart.")");
		if (!$bResult) Finance::TransactionError($aSerialData,$iIdUser,Language::GetMessage('failed insert'));

		$iInsertId=Db::InsertId();
		if($dAmount!=0){
			$bResult = Db::Execute("update user_account set amount=(amount + ($dAmount)) where id_user='$iIdUser'");
			if (!$bResult) Finance::TransactionError($aSerialData,$iIdUser,Language::GetMessage('failed amount update'));
			$bResult = Db::Execute("update user_account_log
				set account_amount='".($dAccountAmount = Finance::AccountAmount($iIdUser))."'
				,debt_amount='".($dDebtAmount = Finance::DebtAmount($iIdUser))."'
					where id='$iInsertId' ");
			if (!$bResult) Finance::TransactionError($aSerialData,$iIdUser,Language::GetMessage('failed account_amount update'));			

			// if user provider and exist group AOT-41
			$sTypeUser=Db::getOne("Select type_ from user where id=".$iIdUser);
			if ($sTypeUser=='provider') {
				$aGroup = Db::getRow("Select * from user_provider_group where id_user=".$iIdUser);
				if ($aGroup) {
					$dBalanceGroup = Finance::DebtAmountGroup($aGroup['id_group']);
					$bResult = Db::Execute("update user_provider_group_main set amount=".$dBalanceGroup." where id='".$aGroup['id_group']."'");
					if (!$bResult) Finance::TransactionError($aSerialData,$iIdProvider,Language::GetMessage('failed amount update'));
				}
			}
		}
		
		Db::CompleteTrans();

		if ($dAmount>0 && !Base::$db->HasFailedTrans()){
			Cron::SendAutopayPackage($iIdUser);
		}
		return $iInsertId;
	}
	//-----------------------------------------------------------------------------------------------
	private function TransactionError($aData, $iIdUser='', $sDescription='')
	{
		Base::$db->FailTrans();
		Log::FinanceAdd($aData, 'finance_transaction', $iIdUser, $sDescription);
	}
	//-----------------------------------------------------------------------------------------------
	public function AccountAmount($iIdUser){
		return Base::$db->getOne("select amount from user_account where id_user='$iIdUser'");
	}
	//-----------------------------------------------------------------------------------------------
	public function DebtAmount($iIdUser){
		if ($iIdUser) $sWhere.=" and ld.id_user='$iIdUser'";
		return Base::$db->getOne("select sum(amount) from log_debt as ld where ld.is_payed='0' ".$sWhere);
	}
	//-----------------------------------------------------------------------------------------------
	public function HaveMoney($dAmount,$iIdUser='',$bFullPayment=false)
	{
		if (!$iIdUser) $iIdUser=Auth::$aUser['id'];
		$aUser=Base::$db->GetRow( Base::GetSql('Customer',array('id'=>$iIdUser)));
		if (!$aUser) return false;

		if (!$bFullPayment) {
			//percent debt
			if (($aUser['amount']* (1 + $aUser['group_debt_percent']/100)) >= $dAmount)
			return true;
		}
		//usual debt
		if (($aUser['amount'] + max($aUser['user_debt'],$aUser['group_debt'])) >= $dAmount) return true;

		return false;
	}
	//-----------------------------------------------------------------------------------------------
	public function PayForAccount()
	{
		Base::$aData['template']['bWidthLimit']=true;
		/**
		 * Creating currency exchange table
		 */
		Base::$tpl->assign('aCurrency',Base::$db->getAll("select * from currency where visible=1 order by num"));
		Base::$sText=str_replace('[$currency]',Base::$tpl->fetch('finance/currency_exchange.tpl'),Base::$sText);
	}
	//-----------------------------------------------------------------------------------------------
	public function GetGeneralAccountAmount(){
		return Base::$db->GetOne("select account_amount from general_account_log order by id desc");
	}
	//-----------------------------------------------------------------------------------------------
	public function GetUserAccountLogTypeAssoc(){
		if (Finance::$aUserAccountLogTypeAssoc) return Finance::$aUserAccountLogTypeAssoc;
		Finance::$aUserAccountLogTypeAssoc=Base::$db->GetAssoc(Base::GetSql('Finance/UserAccountLogTypeAssoc',array(
		'assoc_value'=>'all',
		)));
		return Finance::$aUserAccountLogTypeAssoc;
	}
	//-----------------------------------------------------------------------------------------------
	public function GetSumAmount($sUserType='customer'){
		return Base::$db->GetOne("
			select sum(ua.amount)
			from user_account as ua
			inner join user as u on (u.id=ua.id_user and u.type_='$sUserType')");
	}
	//-----------------------------------------------------------------------------------------------
	public function AssignAccount($aUser)
	{
		if (Auth::$aUser['type_']=='customer') $bIsActive=1;
		
		if (Base::$aRequest['code_template']=='order_bill')
			$aAccount=Db::GetAssoc('Assoc/Account',array(
				'visible'=>1,
				'in_use_pko'=>1
			));
		elseif (Base::$aRequest['code_template']=='order_bill_bv')
			$aAccount=Db::GetAssoc('Assoc/Account',array(
				'visible'=>1,
				'in_use_bv'=>1
			));
		elseif (Base::$aRequest['code_template']=='order_bill_rko')
			$aAccount=Db::GetAssoc('Assoc/Account',array(
				'visible'=>1,
				'in_use_rko'=>1
			));
		else 
			$aAccount=Db::GetAssoc('Assoc/Account',array(
				'visible'=>1,
				'is_active'=>$bIsActive,
			));

		/*if (Auth::$aUser['type_']=='manager') {
			//All other accounts, if no regional visible account
			$aAccount=array(0=>Language::GetMessage('Choose any account'))+
				Db::GetAssoc('Assoc/Account');
		}*/
		$aAccount=array(0=>Language::GetMessage('Choose any account'))+$aAccount;
		Base::$tpl->assign('aAccount',$aAccount);
		return $aAccount;
	}
	//-----------------------------------------------------------------------------------------------
	public function AssignSubtotal($sWhere,$ignore_visible=0)
	{
		$aDataDebet['where']=$sWhere.' and ual.amount>0';
		$aDataCredit['where']=$sWhere.' and ual.amount<0';
		$aDataDebet['sum']='ual.amount';
		$aDataCredit['sum']='ual.amount';
		$aDataCredit['ignore_visible'] = $ignore_visible;
		$aDataDebet['ignore_visible'] = $ignore_visible;
		Base::$tpl->assign('dTotalAmountDebet',Base::$db->GetOne(Base::GetSql('UserAccountLog',$aDataDebet)));
		Base::$tpl->assign('dTotalAmountCredit',Base::$db->GetOne(Base::GetSql('UserAccountLog',$aDataCredit)));
	}
	//-----------------------------------------------------------------------------------------------
	public static function ClearTestDataFinance($aUserId=NULL)
	{
		if(!$aUserId)
		$aUserId = array_keys(DB::GetAssoc("select u.id as id, u.id as value from user as u where u.is_test='1'"));
		if ($aUserId){
			$sWhere=" and id_user in (".implode(',',$aUserId).")";
			DB::Execute("delete from user_account_log where 1=1".$sWhere);
		}
	}
	//-----------------------------------------------------------------------------------------------
	//sDay = 10.10.2011 (from input or other string )
	public static function GetAccountDebitCreditDay($sDay)
	{
		$sDayFrom=DateFormat::FormatSearch(date("Y-m-d",strtotime($sDay)));
		$sDayUntil=DateFormat::FormatSearch(date("Y-m-d",strtotime($sDay)+86400));
		$sSql="select a.id,
	    		sum(if(ual.amount>0,ual.amount,0)) as sum_debit,
       	sum(if(LOCATE('-',ual.`data`)>0,0,SUBSTRING_INDEX(SUBSTRING_INDEX(ual.`data`,' ',-2),' ',1))) as concat_debit,
	    		sum(if(ual.amount<0,ual.amount,0)) as sum_credit,
        sum(if(LOCATE('-',ual.`data`)>0,SUBSTRING_INDEX(SUBSTRING_INDEX(ual.`data`,' ',-2),' ',1),0)) as concat_credit
  			from account as a
  			left join user_account_log as ual on (ual.id_subconto1 = a.id)
  			where ual.post_date>='".$sDayFrom."'  and ual.post_date<='".$sDayUntil."'
  			GROUP BY ual.id_subconto1";
		$aAssocAccountDC = Db::GetAssoc($sSql);
		$aAssocAccount = Db::GetAssoc("Assoc/Account",array(
		'order'=>'order by a.id',
		'multiple'=>true
		));
		foreach($aAssocAccount as $sKey=>$sValue){
			$aAssocAccount[$sKey]['debit'] = $aAssocAccountDC[$sKey]?$aAssocAccountDC[$sKey]['sum_debit']:0;
			$aAssocAccount[$sKey]['credit'] = $aAssocAccountDC[$sKey]?$aAssocAccountDC[$sKey]['sum_credit']:0;
			$aAssocAccount[$sKey]['debit_currency'] = $aAssocAccountDC[$sKey]?
			$aAssocAccountDC[$sKey]['concat_debit']
			:0;
			$aAssocAccount[$sKey]['credit_currency'] = $aAssocAccountDC[$sKey]?
			$aAssocAccountDC[$sKey]['concat_credit']
			:0;
		}
		return $aAssocAccount;
	}
	//-----------------------------------------------------------------------------------------------
	public static function RecalculateBalanceAccount($mixed = NULL)
	{
		if(!$mixed) $mixed = array_keys(Db::GetAssoc("Assoc/Account"));

		Finance::ClearTestDataFinance();

		$aAssocAccountLogMonthPrew = AccountLogMonth::GetDayLog(date("Y-m-d",time()-86400));
		$aAssocCurrentAccount=Finance::GetAccountDebitCreditDay(date("Y-m-d",time()));

		if(is_array($mixed)){
			foreach($mixed as $sKey){
				$aData['balance'] = $aAssocAccountLogMonthPrew[$sKey]['amount_debit_end']
				+$aAssocCurrentAccount[$sKey]['debit']
				+$aAssocCurrentAccount[$sKey]['credit'];
				$aData['balance_currency'] = $aAssocAccountLogMonthPrew[$sKey]['amount_currency_debit_end']
				+$aAssocCurrentAccount[$sKey]['debit_currency']
				+$aAssocCurrentAccount[$sKey]['credit_currency'];
				Db::AutoExecute('account',$aData,"UPDATE"," id = ".$sKey);
			}
		}else if(intval($mixed)){
			$aData['balance'] = $aAssocAccountLogMonthPrew[$mixed]['amount_debit_end']
			+$aAssocCurrentAccount[$mixed]['debit']
			+$aAssocCurrentAccount[$mixed]['credit'];
			$aData['balance_currency'] = $aAssocAccountLogMonthPrew[$mixed]['amount_currency_debit_end']
			+$aAssocCurrentAccount[$mixed]['debit_currency']
			+$aAssocCurrentAccount[$mixed]['credit_currency'];
			Db::AutoExecute('account',$aData,"UPDATE"," id = ".$mixed);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetDescriptionDebt($dAmount=0,$sName='')
	{
		$sText = "<br /><span style='color:blue;'>".Base::$oCurrency->PrintPrice($dAmount,1)."</span>";
		if ($sName)
			$sText .= "<br />".$sName; 
		return $sText;
	}
	//-----------------------------------------------------------------------------------------------
	public function DepositSchet($dAmount,$sDescription='',$iCustomId='',$iIdSubconto1=0,$sOperation,$iIdOffice=0)
	{
		Db::StartTrans();
	
		$aSerialData=array($dAmount,$sDescription,$iCustomId,$iIdSubconto1,$sOperation,$iIdOffice);
		if ($sOperation) {
			$aOperation = Db::GetRow("Select * from user_account_type_operation where code='".$sOperation."'");
			if ($aOperation['formula_balance'] == '+')
				$dAmount = abs($dAmount);
			else {
				$dAmount = abs($dAmount);
				$dAmount = -$dAmount;
			}
		}
	
		$bResult = Db::Execute("insert office_account_log(amount,description
				,custom_id,id_account,operation,id_office)
				values ('$dAmount','".mysql_real_escape_string($sDescription)."'
			,'".mysql_real_escape_string($iCustomId)."','".$iIdSubconto1."','".$sOperation."',".$iIdOffice.")");
		if (!$bResult) Finance::TransactionError($aSerialData,'',Language::GetMessage('failed insert'));
	
		$iInsertId=Db::InsertId();
		if($dAmount!=0){
			$bResult = Db::Execute("update office set balance=(balance + ($dAmount)) where id='$iIdOffice'");
			if (!$bResult) Finance::TransactionError($aSerialData,'',Language::GetMessage('failed amount update'));
		}
	
		$bResult = Db::Execute("update office_account_log
			set account_amount='".Finance::AccountAmountSchet($iIdOffice)."'
			where id='$iInsertId' ");
		if (!$bResult) Finance::TransactionError($aSerialData,'',Language::GetMessage('failed account_amount update'));
	
		Db::CompleteTrans();
	
		return $iInsertId;
	}
	//-----------------------------------------------------------------------------------------------
	public function AccountAmountSchet($iIdOffice){
		return Base::$db->getOne("select balance from office where id='$iIdOffice'");
	}
	//-----------------------------------------------------------------------------------------------
	public function AssignSubtotalSchet($sWhere)
	{
		$aDataDebet['where']=$sWhere.' and oal.amount>0';
		$aDataCredit['where']=$sWhere.' and oal.amount<0';
		$aDataDebet['sum']='oal.amount';
		$aDataCredit['sum']='oal.amount';
		Base::$tpl->assign('dTotalAmountDebet',Base::$db->GetOne(Base::GetSql('OfficeAccountLog',$aDataDebet)));
		Base::$tpl->assign('dTotalAmountCredit',Base::$db->GetOne(Base::GetSql('OfficeAccountLog',$aDataCredit)));
	}
	//-----------------------------------------------------------------------------------------------
	public function FinanceCustomer()
	{
	    Base::$sText.=Base::$tpl->fetch('manager/link_calculation.tpl');
	
	    if (Base::$aRequest['select_search_manager']) {
	    	$aManager=Db::GetRow(Base::GetSql('Manager',array(
	    			'login'=>Base::$aRequest['select_search_manager'],
	    	)));
	    	if (!$aManager)
	    		$sWhereManager = " and 1=0";
	    	else
	    		$sWhereManager = " and uc.id_manager='".$aManager['id_user']."' ";
	    }
	    if(Auth::$aUser['is_super_manager']||Auth::$aUser['all_customer_visible']) {
	        $sWhereManager .= ' ';
	    } else {
	        $sWhereManager .= " and uc.id_manager='".Auth::$aUser['id_user']."' ";
	    }
	    
	    Base::$tpl->assign('aNameUser',$aNameUser=array('' =>' Все')+Db::GetAssoc("select u.login, concat(ifnull(uc.name,''),' ( ',u.login,' )',
				IF(uc.phone is null or uc.phone='','',concat(' ".
	        Language::getMessage('tel.')." ',uc.phone))) name
		from user as u
		inner join user_customer as uc on u.id=uc.id_user
		where u.visible=1 /*and uc.name is not null and trim(uc.name)!=''*/
		".$sWhereManager."
		order by uc.name"));

	    if(Auth::$aUser['is_super_manager']) {
		    Base::$tpl->assign('aNameManager',$aNameManager=array(0 =>'')+Db::GetAssoc("select u.login, concat(ifnull(um.name,''),' ( ',u.login,' )') name
			from user as u
			inner join user_manager as um on u.id=um.id_user
			where u.visible=1 /*and uc.name is not null and trim(uc.name)!=''*/
			order by um.name"));
	    }
	    else 
	    	Base::$tpl->assign('aNameManager',$aNameManager=array(Auth::$aUser['login'] => Auth::$aUser['name'] . ' ( '.Auth::$aUser['login'].' )'));
	    			
	    Base::$tpl->assign('aTypeReport',$aTypeReport=array('short' => Language::getMessage('short_report'),'detail' => Language::getMessage('detail_report'),
	    	'log' => Language::getMessage('log data')));
	    Resource::Get()->Add('/js/select_search.js');
	    
	    /*$aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("1.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
	    $aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
	    $aField['search_login']=array('title'=>'Customer','type'=>'select','options'=>$aNameUser,'name'=>'search_login','selected'=>Base::$aRequest['search_login'],'class'=>'select_search');
	    $aField['description']=array('title'=>'Description','type'=>'input','value'=>Base::$aRequest['search']['description'],'name'=>'search[description]');
	    */
	    $sDateStart = Language::getConstant('finance_customer:board_date','20.09.2017');
	    if (strtotime(Base::$aRequest['search']['date_from'])<strtotime($sDateStart.' 00:00:00'))
	    	$_REQUEST['search']['date_from'] = Base::$aRequest['search']['date_from'] = $sDateStart;
	     
	    $aData=array(
	        'sHeader'=>"method=get",
	        'aField'=>$aField,
	        //'bType'=>'generate',
	        //'sGenerateTpl'=>'form/index_search.tpl',
	    	'sContent'=>Base::$tpl->fetch('finance/form_finance_customer.tpl'),
	        /*'sSubmitButton'=>'Generate',*/
	        'sSubmitAction'=>'finance_customer',
	        'sReturnButton'=>'Clear',
	        'bIsPost'=>0,
	        'sWidth'=>'500px',
	        'sError'=>$sError,
	    );
	    $oForm=new Form($aData);
	    $oForm->sAdditionalButtonTemplate='finance/button_export.tpl';
	    Base::$sText.=$oForm->getForm();
	
	    $aLogData = $this->getDataFinanceCustomer();
	    
	    // type report
		$sTypeReport = $this->getTypeReport();
		switch ($sTypeReport) {
			case '1':$this->ShortAllClient($aLogData,1);return;
			case '2':$this->DetailAllClient($aLogData,1);return;
			case '3':$this->ShortOneClientDates($aLogData,1);return;
			case '4':$this->ShortOneClientNoDates($aLogData,1);return;
			case '5':$this->DetailOneClient($aLogData,1);return;
		}
		
		if (!Base::$aRequest['is_post'])
			return;
		
		// --- search ---
		if (Base::$aRequest['select_search_customer']) {
			$sWhere.=" and (u.login like '%".Base::$aRequest['select_search_customer']."%'";
			$sWhere.=" || uc.name like '%".Base::$aRequest['select_search_customer']."%'";
			$sWhere.=" || uc.phone like '%".Base::$aRequest['select_search_customer']."%')";
		}
		
		if (Base::$aRequest['search']['date']) {
			$sWhere.=" and ual.post_date>='".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
				and ual.post_date<'".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."'";
		}
		
		$sWhere.=" and u.type_='customer' ";
		// --------------
		//Finance::AssignSubtotal($sWhere);
		// --------------
		$oTable=new Table();
		$oTable->iRowPerPage=20;
		$oTable->sSql=Base::GetSql('UserAccountLog',array(
				'where'=>$sWhere,
		));	
		
		$oTable->aOrdered="order by ual.post_date desc,ual.id desc";
	    $oTable->aColumn=array(
	        'row_id'=>array('sTitle'=>'#'),
	        'post_date'=>array('sTitle'=>'Date'),
	        'login'=>array('sTitle'=>'Customer Login'),
	        'debt_amount'=>array('sTitle'=>'DebtAmount'),
	        'credit'=>array('sTitle'=>'finance credit'),
	        'debet'=>array('sTitle'=>'finance debet'),
	        'account_amount'=>array('sTitle'=>'AccountAmount'),
	        'description'=>array('sTitle'=>'Description'),
	    );
	    $oTable->sDataTemplate='finance/row_finance_customer.tpl';
	    //$oTable->sButtonTemplate='finance/button_finance_customer.tpl';
	    //$oTable->sSubtotalTemplate='finance/subtotal_finance.tpl';
	    $oTable->aCallback=array($this,'CallParseLog');
	
	    Base::$sText.=$oTable->getTable("Account Log",'customer_account_log');
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseLog(&$aItem)
	{
		if (!$aItem)
			return;
		
	    $aIdCustomer=array();
	    if ($aItem) foreach($aItem as $key => $value) {
	    	if (!$value['id'])
	    		continue;
	        $aItem[$key]['row_id']=$value['id'];
	        if (!in_array($value['id_user'],$aIdCustomer)) {
	            $aIdCustomer[]=$value['id_user'];
	        }
	        if ($value['custom_id']>0) {
	            $aCustomId[]=$value['custom_id'];
	        }
	    }
	
	    $aCustomerManagerHash=Base::$db->GetAssoc(Base::GetSql('Customer/ManagerAssoc',array('id_user_array'=>$aIdCustomer)));
	    if ($aCustomId) {
	        $aDebtCartAssoc=Db::GetAssoc('Assoc/Debt',array('where'=>" and ld.is_payed='0'
				and custom_id in (".(implode(',',$aCustomId)).")"));
	    }
	    if ($aItem) foreach($aItem as $sKey => $aValue) {
	    	$sDokument = $this->getNameDocument($aValue);
	    	$aItem[$sKey]['document'] = $sDokument;
	        $aItem[$sKey]['manager_login']=$aCustomerManagerHash[$aValue['id_user']];
	        $aItem[$sKey]['debt_cart_unpaid']=$aDebtCartAssoc[$aValue['custom_id']];
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function FinanceUserExport()
	{
		Base::$aRequest['select_search_customer'] = Auth::$aUser['login'];
		$aLogData = $this->getDataFinanceCustomer();
		$this->DetailOneClient($aLogData);
	}
	//-----------------------------------------------------------------------------------------------
	public function FinanceCustomerExport()
	{
		$aLogData = $this->getDataFinanceCustomer();
		// type report
		$sTypeReport = $this->getTypeReport();

	    switch ($sTypeReport) {
	    	case '1':$this->ShortAllClient($aLogData);break;
	    	case '2':$this->DetailAllClient($aLogData);break;
	    	case '3':$this->ShortOneClientDates($aLogData);break;
	    	case '4':$this->ShortOneClientNoDates($aLogData);break;
	    	case '5':$this->DetailOneClient($aLogData);break;
	    }
	    
	    // default report type
	    $oExcel = new Excel();
	    $aHeader=array(
	        'A'=>array("value"=>'row_id'),
	        'B'=>array("value"=>'post_date', "autosize"=>true),
	        'C'=>array("value"=>'login', "autosize"=>true),
	        'D'=>array("value"=>'debt_amount', "autosize"=>true),
	        'E'=>array("value"=>'credit', "autosize"=>true),
	        'F'=>array("value"=>'debet', "autosize"=>true),
	        'G'=>array("value"=>'account_amount', "autosize"=>true),
	        'H'=>array("value"=>'description', "autosize"=>true),
	    );
	    $oExcel->SetHeaderValue($aHeader,1,false);
	    $oExcel->SetAutoSize($aHeader);
	    $oExcel->DuplicateStyleArray("A1:H1");
	    
	    $i=$j=2;
	    foreach ($aLogData as $aValue)
	    {
	        $oExcel->SetCellValueExplicit('A'.$i, $aValue['id']);
	        $oExcel->SetCellValueExplicit('B'.$i, $aValue['post_date']);
	        $oExcel->SetCellValueExplicit('C'.$i, $aValue['login']);
	        $oExcel->SetCellValueExplicit('D'.$i, $aValue['debt_amount']);
	        $oExcel->SetCellValueExplicit('E'.$i, $aValue['amount']<0?$aValue['amount']:'');
	        $oExcel->SetCellValueExplicit('F'.$i, $aValue['amount']>=0?$aValue['amount']:'');
	        $oExcel->SetCellValueExplicit('G'.$i, $aValue['account_amount']);
	        $oExcel->SetCellValueExplicit('H'.$i, $aValue['description']);
	    
	        $i++;
	    }
	    //end
	    $sFileName=uniqid().'.xls';
	    $oExcel->WriterExcel5(SERVER_PATH.'/imgbank/temp_upload/'.$sFileName, true);
	    
	    
// 	    Base::$tpl->assign('sFileName',$sFileName);
// 	    Base::$sText.=Base::$tpl->fetch('finance/export_finance.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function ShortAllClient($aData,$is_view_page=0) {
		$sDateFrom = Base::$aRequest['search']['date_from'];
		$sDateTo = Base::$aRequest['search']['date_to'];
		$sDateFromStart = date("Y-m-d H:i:s",strtotime($sDateFrom.'00:00:00'));
		$sDateToEnd = date("Y-m-d H:i:s",strtotime($sDateTo.'23:59:59'));
		
		if (Base::$aRequest['select_search_manager']) {
			$iIdManager = Db::getOne("Select id from user u
		    		where u.login='".Base::$aRequest['select_search_manager']."' and u.type_='manager'");
			if ($iIdManager)
				$sWhere .= " and uc.id_manager='".$iIdManager."' ";
			else
				$sWhere .= " and 0=1 ";
		}
		
		if(Auth::$aUser['is_super_manager']||Auth::$aUser['all_customer_visible']) {
			$sWhereManager = ' ';
		} else {
			$sWhereManager = " and uc.id_manager='".Auth::$aUser['id_user']."' ";
		}
		
		$sWhere.=" and u.type_='customer' ".$sWhereManager;
		 
		$aNameUser=Db::GetAssoc("select u.id, concat(ifnull(uc.name,''),' ( ',u.login,' )',
				IF(uc.phone is null or uc.phone='','',concat(' ".
						Language::getMessage('tel.')." ',uc.phone))) name
			from user as u
			inner join user_customer as uc on u.id=uc.id_user
			where u.visible=1".$sWhere."
			order by uc.name");
		//Debug::PrintPre($aNameUser);
		$aUserAssoc = array();
		$aUserRefusedOrder = array();
		foreach ($aNameUser as $iIdUser => $sNameCustomer) {
			$iSum1 = $this->getDebtBegin($iIdUser,$sDateFromStart);
			$aUserAssoc[$iIdUser] = array(
					'debt_amount' => $iSum1,
					'credit' => '0.00',
					'debet' => '0.00',
					'account_amount' => '0.00',
					'name_customer' => $sNameCustomer,
			);
		}
		//Debug::PrintPre($aData);
		foreach ($aData as $aValue) {
			$sDokument = $this->getNameDocument($aValue);
			if (!$sDokument || !$aValue['id_cart_package'])
				continue;
			
			$this->RewriteCreditAmount($aValue,$credit,$debet);
			if ($credit==0 && $debet==0)
				continue;
			
			$aUserAssoc[$aValue['id_user']]['credit'] += $credit;
			$aUserAssoc[$aValue['id_user']]['debet'] += $debet;
		}
		foreach ($aUserAssoc as $iIdUser => $aValue) {
			$aUserAssoc[$iIdUser]['account_amount'] = number_format(round($aValue['debt_amount'] - abs($aValue['credit']) + $aValue['debet'],2),2,".","");
		}
		// page
		if ($is_view_page) {
			$aDataSet = array();
			$i=1;
			$iSum1=$iSum2=$iSum3=$iSum4=0;
			foreach ($aUserAssoc as $iIdUser => $aValue) {
				if (Base::$aRequest['not_null'] && $aValue['account_amount']==0)
					continue;
				$aDataSet[] = array(
						'num_str' => $i,
						'name_customer' => $aValue['name_customer'],
						'debt_amount' => number_format($aValue['debt_amount'],2,".",""),
						'credit' => number_format($aValue['credit'],2,".",""),
						'debet' => number_format(abs($aValue['debet']),2,".",""),
						'account_amount' => number_format($aValue['account_amount'],2,".",""),
				);
				$i+=1;
				$iSum1 += $aValue['debt_amount'];
				$iSum2 += $aValue['credit'];
				$iSum3 += abs($aValue['debet']);
				$iSum4 += $aValue['account_amount'];
			}
			
			$iSum1 = number_format($iSum1,2,".","");
			$iSum2 = number_format($iSum2,2,".","");
			$iSum3 = number_format($iSum3,2,".","");
			$iSum4 = number_format($iSum4,2,".","");
			
			Base::$tpl->assign('iTotal',($i-1));
			Base::$tpl->assign('total_debt_amount',$iSum1);
			Base::$tpl->assign('total_credit',$iSum2);
			Base::$tpl->assign('total_debet',$iSum3);
			Base::$tpl->assign('total_account_amount',$iSum4);

			$oTable=new Table();
			$oTable->iRowPerPage=100;
			$oTable->aDataFoTable = $aDataSet;
			$oTable->sType='array';
			$oTable->aColumn=array(
				'num_str'=>array('sTitle'=>'num_str'),
				'login'=>array('sTitle'=>'customer login'),
				'debt_amount'=>array('sTitle'=>'DebtAmount'),
				'credit'=>array('sTitle'=>'finance credit'),
				'debet'=>array('sTitle'=>'finance debet'),
				'account_amount'=>array('sTitle'=>'AccountAmount'),
			);
			$oTable->sDataTemplate='finance/row_finance_customer_1.tpl';
			$oTable->sSubtotalTemplate='finance/subtotal_finance_1.tpl';
			Base::$sText.=$oTable->getTable("Account Log",'customer_account_log');
			return;
		}
		else {
			$aStyleText= array(
				'font' => array('bold' => true),
				'alignment' => array('horizontal' => 'center',),
				'borders' => array( 
						'top' => array( 'style' => 'thin' ),
						'left' => array( 'style' => 'thin' ),
						'right' => array( 'style' => 'thin' ),
						'bottom' => array( 'style' => 'thin' ),
				),
			);
			
			$oExcel= new Excel();
			$oExcel->ReadExcel7(SERVER_PATH."/imgbank/finance_customer_report_1.xlsx");
			$oExcel->SetActiveSheetIndex();
			$oExcel->GetActiveSheet();
			
			$aStyleNumber= $aStyleText;
			$aStyleNumber['numberformat'] = $oExcel->aStyleFormatNumber00['numberformat'];
			$aCenter= array(
					'alignment' => array('horizontal' => 'center',),
			);
			$aCenterNumber= array(
					'alignment' => array('horizontal' => 'center',),
					'numberformat' => $oExcel->aStyleFormatNumber00['numberformat']
			);
				
			$this->MakroHeaderSet($oExcel);
				
			$oExcel->SetCellValue('B4',"Взаиморасчеты с клиентами за период с ".$sDateFrom." по ".$sDateTo);
	
			$i=8;
			$iSum1=$iSum2=$iSum3=$iSum4=0;
			foreach ($aUserAssoc as $iIdUser => $aValue) {
				if (Base::$aRequest['not_null'] && $aValue['account_amount']==0)
					continue;
				$oExcel->SetCellValueExplicit('B'.$i,$i-7,'',$aCenter);
				$oExcel->SetCellValueExplicit('C'.$i,$aValue['name_customer']);
				$oExcel->SetCellValueExplicit('D'.$i, $aValue['debt_amount'],'',$aCenterNumber,2,'n');
				$oExcel->SetCellValueExplicit('E'.$i, $aValue['credit'],'',$aCenterNumber,2,'n');
				$oExcel->SetCellValueExplicit('F'.$i, abs($aValue['debet']),'',$aCenterNumber,2,'n');
				$oExcel->SetCellValueExplicit('G'.$i, $aValue['account_amount'],'',$aCenterNumber,2,'n');
				$i+=1;
				$iSum1 += $aValue['debt_amount'];
				$iSum2 += $aValue['credit'];
				$iSum3 += abs($aValue['debet']);
				$iSum4 += $aValue['account_amount'];
			}
			$oExcel->SetCellValueExplicit('B'.$i,'Строк '.($i-8),'',$aStyleText);
			$oExcel->SetCellValueExplicit('C'.$i,'ВСЕГО','',$aStyleText);
			$oExcel->SetCellValueExplicit('D'.$i,$iSum1,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('E'.$i,$iSum2,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('F'.$i,$iSum3,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('G'.$i,$iSum4,'',$aStyleNumber,2,'n');
			
			//end
			$sFileName=uniqid().'.xlsx';
			$oExcel->WriterExcel7(SERVER_PATH.'/imgbank/temp_upload/'.$sFileName, true);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function FinanceProviderExport() {
		$aLogData = $this->getDataFinanceProvider();
		// type report
		$sTypeReport = $this->getTypeReportProvider();
		
		switch ($sTypeReport) {
			case '1':$this->ShortAllClientProvider($aLogData);break;
			case '2':$this->DetailAllClientProvider($aLogData);break;
			case '3':$this->ShortAllClientDatesProvider($aLogData);break;
		}
		
	    $sSql=$_SESSION['finance']['current_sql'];
	    // 	    $sSql=Base::GetSql('UserAccountLog',array(
	    // 	        'where'=>$sWhere,
	    // 	    ));
	    $sSql.=" order by ual.id desc";
	    $aLogData=Db::GetAll($sSql);
	    $this->CallParseLog($aLogData);
	     
	    $oExcel = new Excel();
	    $aHeader=array(
	        'A'=>array("value"=>'row_id'),
	        'B'=>array("value"=>'post_date', "autosize"=>true),
	        'C'=>array("value"=>'login', "autosize"=>true),
	        'D'=>array("value"=>'debt_amount', "autosize"=>true),
	        'E'=>array("value"=>'credit', "autosize"=>true),
	        'F'=>array("value"=>'debet', "autosize"=>true),
	        'G'=>array("value"=>'account_amount', "autosize"=>true),
	        'H'=>array("value"=>'description', "autosize"=>true),
	    );
	    $oExcel->SetHeaderValue($aHeader,1,false);
	    $oExcel->SetAutoSize($aHeader);
	    $oExcel->DuplicateStyleArray("A1:H1");
	     
	    $i=$j=2;
	    foreach ($aLogData as $aValue)
	    {
	        $oExcel->SetCellValueExplicit('A'.$i, $aValue['id']);
	        $oExcel->SetCellValueExplicit('B'.$i, $aValue['post_date']);
	        $oExcel->SetCellValueExplicit('C'.$i, $aValue['login']);
	        $oExcel->SetCellValueExplicit('D'.$i, $aValue['debt_amount']);
	        $oExcel->SetCellValueExplicit('E'.$i, $aValue['amount']<0?$aValue['amount']:'');
	        $oExcel->SetCellValueExplicit('F'.$i, $aValue['amount']>=0?$aValue['amount']:'');
	        $oExcel->SetCellValueExplicit('G'.$i, $aValue['account_amount']);
	        $oExcel->SetCellValueExplicit('H'.$i, $aValue['description']);
	         
	        $i++;
	    }
	    //end
	    $sFileName=uniqid().'.xls';
	    $oExcel->WriterExcel5(SERVER_PATH.'/imgbank/temp_upload/'.$sFileName, true);
	     
// 	    Base::$tpl->assign('sFileName',$sFileName);
// 	    Base::$sText.=Base::$tpl->fetch('finance/export_finance.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function FinanceProfitExport() {
		$aLogData = $this->getDataFinanceProfit();
    	// type report
    	$sTypeReport = 1; // short all client
    	if (Base::$aRequest['search_type_report'] == 'detail')
    		$sTypeReport = 2; // detail all client
   		elseif (Base::$aRequest['search_date'] && Base::$aRequest['search_type_report'] == 'short')
   			$sTypeReport = 3; // short client for dates
    	
    	switch ($sTypeReport) {
    		case '1':$this->ProfitShortAllClient($aLogData);return;
    		case '2':$this->ProfitDetailAllClient($aLogData);return;
    		case '3':$this->ProfitShortOneClientDates($aLogData);return;
    	}
	}
	//-----------------------------------------------------------------------------------------------
	public function FinanceProvider()
	{
	    Base::$sText.=Base::$tpl->fetch('manager/link_calculation.tpl');
	    Resource::Get()->Add('/js/select_search.js');
	    
	     Base::$tpl->assign('aNameUser',$aNameUser=array(0 =>'')+Db::GetAssoc("select u.login, concat(ifnull(up.name,''),' ( ',u.login,' )') name
		from user as u
		inner join user_provider as up on u.id=up.id_user
		where u.visible=1 
		order by up.name"));
	    
	   Base::$tpl->assign('aTypeReport',$aTypeReport=array(
	    'short' => Language::getMessage('short_report'),'detail' => Language::getMessage('detail_report'),
	    'log' => Language::getMessage('log data')));
	     	     
	   if (!Base::$aRequest['search']['date_from'] || strtotime(Base::$aRequest['search']['date_from'])<strtotime(Language::getConstant('finance_provider:board_date','01.01.2017').' 23:59:59'))
	    	$_REQUEST['search']['date_from'] = Base::$aRequest['search']['date_from'] = Language::getConstant('finance_provider:board_date','01.01.2017');
	   
	    $aData=array(
	    		'sHeader'=>"method=get",
	    		'aField'=>$aField,
	    		//'bType'=>'generate',
	    		//'sGenerateTpl'=>'form/index_search.tpl',
	    		'sContent'=>Base::$tpl->fetch('finance/form_finance_provider.tpl'),
	    		/*'sSubmitButton'=>'Generate',*/
	    		'sSubmitAction'=>'finance_provider',
	    		'sReturnButton'=>'Clear',
	    		'bIsPost'=>0,
	    		'sWidth'=>'500px',
	    		'sError'=>$sError,
	    );
	    $oForm=new Form($aData);
	    $oForm->sAdditionalButtonTemplate='finance/button_export_provider.tpl';
	    Base::$sText.=$oForm->getForm();
	    
	    $aLogData = $this->getDataFinanceProvider();
	     
	    // type report
	    $sTypeReport = $this->getTypeReportProvider();
	    switch ($sTypeReport) {
	    	case '1':$this->ShortAllClientProvider($aLogData,1);return;
	    	case '2':$this->DetailAllClientProvider($aLogData,1);return;
	    	case '3':$this->ShortAllClientDatesProvider($aLogData,1);return;
	    }
	    
	    if (!Base::$aRequest['is_post'])
	    	return;
	    
	    // --- search ---
	    if (Base::$aRequest['select_search_provider']) {
	    	$sWhere.=" and u.login = '".Base::$aRequest['select_search_provider']."'";
	        /*$sWhere.=" and (u.login like '%".Base::$aRequest['select_search_provider']."%'";
	        $sWhere.=" || up.name like '%".Base::$aRequest['select_search_provider']."%'";
	        $sWhere.=" || up.phone like '%".Base::$aRequest['select_search_provider']."%')";*/
	    }
	
	    if (Base::$aRequest['search']['date']) {
	        $sWhere.=" and ual.post_date>='".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
				and ual.post_date<'".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."'";
	    }
	    if (Base::$aRequest['search']['description']) {
	        $sWhere.=" and ual.description like '%".Base::$aRequest['search']['description']."%'";
	    }
	    $sWhere.=" and u.type_='provider' ";
	    // --------------
	    //Finance::AssignSubtotal($sWhere);
	    // --------------
	     
	    $oTable=new Table();
	    $oTable->iRowPerPage=20;
	    $oTable->sSql=Base::GetSql('UserAccountLog',array(
	        'where'=>$sWhere,
	    ));
	    $_SESSION['finance']['current_sql']=$oTable->sSql;
	     
	    $oTable->aOrdered="order by ual.post_date desc,ual.id desc";
	    $oTable->aColumn=array(
	        'row_id'=>array('sTitle'=>'#'),
	        'post_date'=>array('sTitle'=>'Date'),
	        'login'=>array('sTitle'=>'Customer Login'),
	        'debt_amount'=>array('sTitle'=>'DebtAmount'),
	        'credit'=>array('sTitle'=>'finance credit'),
	        'debet'=>array('sTitle'=>'finance debet'),
	        'account_amount'=>array('sTitle'=>'AccountAmount'),
	        'description'=>array('sTitle'=>'Description'),
	    );
	    $oTable->sDataTemplate='finance/row_finance_provider.tpl';
	    //$oTable->sSubtotalTemplate='finance/subtotal_finance.tpl';
	    $oTable->sButtonTemplate='finance/button_finance_provider.tpl';
	    $oTable->aCallback=array($this,'CallParseLogProvider');
	
	    /*$iSumBalance=Db::GetOne("
    		    select sum(oal./ *account_* /amount)
        		from office_account_log oal
        		left join office as o on o.id=oal.id_office
    		");
	    Base::$tpl->assign('iSumBalance',$iSumBalance);*/
	    Base::$sText.=$oTable->getTable("Account Log",'customer_account_log');
	    
	}
	//-----------------------------------------------------------------------------------------------
	public function Profit() {
	    Base::$sText.=Base::$tpl->fetch('manager/link_calculation.tpl');
	    Resource::Get()->Add('/js/select_search.js');

    	if (Base::$aRequest['select_search_manager']) {
    		$aManager=Db::GetRow(Base::GetSql('Manager',array(
    				'login'=>Base::$aRequest['select_search_manager'],
    		)));
    		if (!$aManager)
    			$sWhereManager = " and 1=0";
    		else
    			$sWhereManager = " and uc.id_manager='".$aManager['id_user']."' ";
    	}
    	if(Auth::$aUser['is_super_manager']||Auth::$aUser['all_customer_visible']) {
    		$sWhereManager .= ' ';
    	} else {
    		$sWhereManager .= " and uc.id_manager='".Auth::$aUser['id_user']."' ";
    	}
	    	 
    	Base::$tpl->assign('aNameUser',$aNameUser=array('' =>' Все')+Db::GetAssoc("select u.login, concat(ifnull(uc.name,''),' ( ',u.login,' )',
			IF(uc.phone is null or uc.phone='','',concat(' ".
  				Language::getMessage('tel.')." ',uc.phone))) name
			from user as u
			inner join user_customer as uc on u.id=uc.id_user
			where u.visible=1 /*and uc.name is not null and trim(uc.name)!=''*/
			".$sWhereManager."
			order by uc.name"));
	    
    	if(Auth::$aUser['is_super_manager']) {
    		Base::$tpl->assign('aNameManager',$aNameManager=array(0 =>'')+Db::GetAssoc("select u.login, concat(ifnull(um.name,''),' ( ',u.login,' )') name
		from user as u
		inner join user_manager as um on u.id=um.id_user
		where u.visible=1 /*and uc.name is not null and trim(uc.name)!=''*/
		order by um.name"));
    	}
    	else
    		Base::$tpl->assign('aNameManager',$aNameManager=array(Auth::$aUser['login'] => Auth::$aUser['name'] . ' ( '.Auth::$aUser['login'].' )'));
	    
    	Base::$tpl->assign('aTypeReport',$aTypeReport=array('short' => Language::getMessage('short_report'),'detail' => Language::getMessage('detail_report')));
	    
    	/*if (strtotime(Base::$aRequest['search']['date_from'])<strtotime('2017-09-19 23:59:59'))
    		$_REQUEST['search']['date_from'] = Base::$aRequest['search']['date_from'] = '20.09.2017';*/
	    
    	$aData=array(
    			'sHeader'=>"method=get",
    			'aField'=>$aField,
    			'sContent'=>Base::$tpl->fetch('finance/form_finance_profit.tpl'),
    			'sSubmitAction'=>'finance_profit',
    			'sReturnButton'=>'Clear',
    			'bIsPost'=>0,
    			'sWidth'=>'500px',
    			'sError'=>$sError,
    	);
    	$oForm=new Form($aData);
    	$oForm->sAdditionalButtonTemplate='finance/button_export_profit.tpl';
    	Base::$sText.=$oForm->getForm();

    	if (!Base::$aRequest['is_post'])
    		return;
    	 
    	$aLogData = $this->getDataFinanceProfit();
    	//Debug::PrintPre($aLogData);

    	// type report
    	$sTypeReport = 1; // short all client
    	if (Base::$aRequest['search_type_report'] == 'detail')
    		$sTypeReport = 2; // detail all client
   		elseif (Base::$aRequest['search_date'] && Base::$aRequest['search_type_report'] == 'short')
   			$sTypeReport = 3; // short client for dates
    	
    	switch ($sTypeReport) {
    		case '1':$this->ProfitShortAllClient($aLogData,1);return;
    		case '2':$this->ProfitDetailAllClient($aLogData,1);return;
    		case '3':$this->ProfitShortOneClientDates($aLogData,1);return;
    	}
    
/*	    
	    	// --- search ---
	    	if (Base::$aRequest['select_search_customer']) {
	    		$sWhere.=" and (u.login like '%".Base::$aRequest['select_search_customer']."%'";
	    		$sWhere.=" || uc.name like '%".Base::$aRequest['select_search_customer']."%'";
	    		$sWhere.=" || uc.phone like '%".Base::$aRequest['select_search_customer']."%')";
	    	}
	    
	    	if (Base::$aRequest['search']['date']) {
	    		$sWhere.=" and ual.post_date>='".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
				and ual.post_date<'".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."'";
	    	}
	    
	    	$sWhere.=" and u.type_='customer' ";
	    	// --------------
	    	//Finance::AssignSubtotal($sWhere);
	    	// --------------
	    	$oTable=new Table();
	    	$oTable->iRowPerPage=20;
	    	$oTable->sSql=Base::GetSql('UserAccountLog',array(
	    			'where'=>$sWhere,
	    	));
	    
	    	$oTable->aOrdered="order by ual.post_date desc,ual.id desc";
	    	$oTable->aColumn=array(
	    			'row_id'=>array('sTitle'=>'#'),
	    			'post_date'=>array('sTitle'=>'Date'),
	    			'login'=>array('sTitle'=>'Customer Login'),
	    			'debt_amount'=>array('sTitle'=>'DebtAmount'),
	    			'credit'=>array('sTitle'=>'finance credit'),
	    			'debet'=>array('sTitle'=>'finance debet'),
	    			'account_amount'=>array('sTitle'=>'AccountAmount'),
	    			'description'=>array('sTitle'=>'Description'),
	    	);
	    	$oTable->sDataTemplate='finance/row_finance_customer.tpl';
	    	//$oTable->sButtonTemplate='finance/button_finance_customer.tpl';
	    	$oTable->sSubtotalTemplate='finance/subtotal_finance.tpl';
	    	$oTable->aCallback=array($this,'CallParseLog');
	    
	    	Base::$sText.=$oTable->getTable("Account Log",'customer_account_log');
	    }
	    */
    	
	    /*if(Auth::$aUser['is_super_manager']||Auth::$aUser['all_customer_visible']) {
	        $sWhereManager = ' ';
	    } else {
	        $sWhereManager = " and uc.id_manager='".Auth::$aUser['id_user']."' ";
	    }
	    
	    Base::$tpl->assign('aNameUser',$aNameUser=array(0 =>'')+Db::GetAssoc("select u.login, concat(ifnull(uc.name,''),' ( ',u.login,' )',
				IF(uc.phone is null or uc.phone='','',concat(' ".
	        Language::getMessage('tel.')." ',uc.phone))) name
		from user as u
		inner join user_customer as uc on u.id=uc.id_user
		where u.visible=1 / *and uc.name is not null and trim(uc.name)!=''* /
		".$sWhereManager."
		order by uc.name"));
	    
	    $aField['search_login']=array('title'=>'Customer','type'=>'select','options'=>$aNameUser,'name'=>'search_login','selected'=>Base::$aRequest['search_login'],'class'=>'select_search');
	    $aField['id_cart_package']=array('title'=>'id_cart_package','type'=>'input','value'=>Base::$aRequest['search']['id_cart_package'],'name'=>'search[id_cart_package]');
	    $aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("1.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
	    $aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
	    
	    $aData=array(
	        'sHeader'=>"method=get",
	        'aField'=>$aField,
	        'bType'=>'generate',
	        'sGenerateTpl'=>'form/index_search.tpl',
	        'sSubmitButton'=>'Search',
	        'sSubmitAction'=>'finance_profit',
	        'sReturnButton'=>'Clear',
	        'bIsPost'=>0,
	        'sWidth'=>'700px',
	        'sError'=>$sError,
	    );
	    $oForm=new Form($aData);
	    Base::$sText.=$oForm->getForm();
	
	    // --- search ---
	    if (Base::$aRequest['search_login']) {
	        $sWhere.=" and (u.login like '%".Base::$aRequest['search_login']."%'";
	        $sWhere.=" || uc.name like '%".Base::$aRequest['search_login']."%'";
	        $sWhere.=" || uc.phone like '%".Base::$aRequest['search_login']."%')";
	    }
	
	    if (Base::$aRequest['search']['date']) {
	        $sWhere.=" and cp.post_date>='".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
				and cp.post_date<'".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."'";
	    }
	    if (Base::$aRequest['search']['id_cart_package']) {
	        $sWhere.=" and cp.id like '%".Base::$aRequest['search']['id_cart_package']."%'";
	    }
	    // --------------

	    $sSql="select 
	           c.id, 
	           cp.post_date, 
	           c.id_user, 
	           c.price, 
	           c.price_original, 
	           c.id_provider, 
	           cur.value, 
	           cur.code,
	           c.number,
	           round(c.price*c.number,2) as total_price,
	           round((c.price_original*c.number)/cur.value,2) as total_price_original,
	           round((c.price*c.number)-((c.price_original*c.number)/cur.value),2) as profit,
	           u.login
	        from cart_package as cp
	        inner join cart as c on c.id_cart_package=cp.id
	        inner join user as u on cp.id_user=u.id
	        inner join user_customer as uc on u.id=uc.id_user
	        inner join user_provider as up on c.id_provider=up.id_user
	        inner join currency as cur on cur.id=up.id_currency
	        where (cp.order_status='work' or cp.order_status='end') and cp.is_payed='1'
	        ".$sWhere;
	    
	    $oTable=new Table();
	    $oTable->iRowPerPage=20;
	    $oTable->sSql=$sSql;
	    $_SESSION['finance']['current_sql']=$oTable->sSql;

	    $oTable->aOrdered="order by c.id desc";
	    $oTable->aColumn=array(
	        'row_id'=>array('sTitle'=>'#'),
	        'post_date'=>array('sTitle'=>'Date'),
	        'login'=>array('sTitle'=>'Customer Login'),
	        'total_price'=>array('sTitle'=>'total_price'),
	        'total_price_original'=>array('sTitle'=>'total_price_original'),
	        'profit'=>array('sTitle'=>'profit'),
	    );
	    $oTable->sDataTemplate='finance/row_finance_profit.tpl';
	    // 	    $oTable->aCallback=array($this,'CallParseLog');
	    $oTable->sButtonTemplate='finance/button_finance_profit.tpl';
	    
	    Base::$sText.=$oTable->getTable("Account Log",'customer_account_log');*/
	}
	//-----------------------------------------------------------------------------------------------
	public function AddDeposit() {
	    
	    if (Base::$aRequest['is_post'])
	    {
	        if (!Base::$aRequest['data']['amount'] || !Base::$aRequest['data']['id_user'] || !Base::$aRequest['data']['custom_id']) {
	            Form::ShowError("Please, fill the required fields");
	            Base::$aRequest['action']='manager_finance_add';
	            Base::$tpl->assign('aData',$aData=Base::$aRequest['data']);
	        }
	        else {
	            $aOperation = Db::GetRow("Select * from user_account_type_operation where code='".Base::$aRequest['data']['pay_type']."'");
	            Finance::Deposit(Base::$aRequest['data']['id_user'],Base::$aRequest['data']['amount'],$aOperation['name'],Base::$aRequest['data']['custom_id'],'interval','',0,0,0,$aOperation['code'],0,0,true,0);
	            
	            Form::RedirectAuto("&aMessage[MI_NOTICE]=payment added");
	        }
	    }
	    
        if(Base::$aRequest['xajax']) {
            $_REQUEST=array_merge($_REQUEST,Base::$aRequest);
        }
    
        Base::$tpl->assign('aAccount',array('0'=>Language::getMessage("Other"))+Db::GetAssoc(Base::GetSql('Assoc/Account')));
        Base::$tpl->assign('aCurrency', Db::GetAll(Base::GetSql('Currency',array("order"=>"num"))));
        $aPayType=Db::GetAssoc("select code, name from user_account_type_operation");
        Base::$tpl->assign('aPayType', $aPayType);
    
        $aField['login']=array('title'=>'Deposit to customer','type'=>'text','value'=>Base::$aRequest['login']);
        //if(Base::$aRequest['custom_id']) 
	$aField['custom_id']=array('title'=>'Deposit to cart package','type'=>'input','value'=>Base::$aRequest['custom_id'],'name'=>'data[custom_id]','szir'=>1);
        $aField['hr']=array('type'=>'hr','colspan'=>2);
        $aField['amount']=array('title'=>'Amount','type'=>'input','value'=>$aData['amount'],'name'=>'data[amount]','szir'=>1);
        $aField['pay_type']=array('title'=>'Pay Type','type'=>'select','options'=>$aPayType,'name'=>'data[pay_type]','szir'=>1);
        $aField['description']=array('title'=>'Description','type'=>'textarea','name'=>'data[description]');
        $aField['id_user']=array('type'=>'hidden','name'=>'data[id_user]','value'=>Base::$aRequest['id_user']);
        $aField['return']=array('type'=>'hidden','name'=>'data[return]','value'=>urlencode(Base::$aRequest['return']));
    
        $aData=array(
            'sHeader'=>"method=post",
            'sTitle'=>"Finance add",
            'aField'=>$aField,
            'bType'=>'generate',
            'sSubmitButton'=>'Apply',
            'sSubmitAction'=>'finance_add_deposit',
            'sError'=>$sError,
        );
        if(Base::$aRequest['xajax']) {
            $aData['sHeader']="method=post onsubmit='submit_form(this); return false;' id='main_form'";
        }
        $oForm=new Form($aData);
    
        if(Base::$aRequest['xajax']) {
            Base::$oResponse->AddAssign('order_status_popup_title','innerHTML','Добавить средства на счет');
            Base::$oResponse->AddAssign('order_status_popup_content','innerHTML',$oForm->getForm());
            Base::$oResponse->AddScript("popupOpen('.js-popup-order-status');");
            return;
        } else {
            Base::$sText.=$oForm->getForm();
            return;
        }
	}
	//-----------------------------------------------------------------------------------------------
	public function DeleteDeposit() {
	    
        if(Base::$aRequest['id_user_account_log'] && Base::$aRequest['id_user'])
        {
            $aDeletedAmount = Db::GetRow("SELECT * FROM user_account_log WHERE id='".Base::$aRequest['id_user_account_log']."'");
            if($aDeletedAmount){
                $aNeedUpdateAmount = Db::GetAll("
					SELECT *
					FROM user_account_log
					WHERE post_date >= '".$aDeletedAmount['post_date']."' and id_user='".Base::$aRequest['id_user']."' and id!='".Base::$aRequest['id_user_account_log']."'
					ORDER BY post_date"
                );
                $sLastAmount = $aDeletedAmount['account_amount'] - $aDeletedAmount['amount'];
                if($aNeedUpdateAmount){
                    foreach ($aNeedUpdateAmount as $sKey => $aValue) {
                        $sUpdateAmount = $aValue['amount'] + $sLastAmount;
                        Db::Execute("UPDATE user_account_log SET  account_amount = '".$sUpdateAmount."' WHERE id = '".$aValue['id']."'");
                        $sLastAmount = $sUpdateAmount;
                    }
                }
                Db::Execute("DELETE FROM user_account_log WHERE id ='".Base::$aRequest['id_user_account_log']."'");
                Db::Execute("UPDATE user_account SET  amount = '".$sLastAmount."' WHERE id_user = '".Base::$aRequest['id_user']."'");
            }
            if(Base::$aRequest['return']) {
                Base::Redirect("/?".Base::$aRequest['return']);
            } else {
                Base::Redirect("/pages/manager_finance/");
            }
        }
        else
        {
            if(Base::$aRequest['return']) {
                Base::Redirect("/?".Base::$aRequest['return']);
            } else {
                Base::Redirect("/pages/manager_finance/");
            }
        }
	}
	//-----------------------------------------------------------------------------------------------
	public function SetManager() {
		if (Base::$aRequest['login']) {		
			$aManager=Db::GetRow(Base::GetSql('Manager',array(
				'login'=>Base::$aRequest['login'],
			)));
			if (!$aManager)
				$sWhereManager = " and 1=0";
			else
				$sWhereManager = " and uc.id_manager='".$aManager['id_user']."' ";
		}
		
		if(Auth::$aUser['is_super_manager']||Auth::$aUser['all_customer_visible']) {
			$sWhereManager .= ' ';
		} else {
			$sWhereManager .= " and uc.id_manager='".Auth::$aUser['id_user']."' ";
		}
		 
		Base::$tpl->assign('aNameUser',$aNameUser=array(0 =>'')+Db::GetAssoc("select u.login, concat(ifnull(uc.name,''),' ( ',u.login,' )',
				IF(uc.phone is null or uc.phone='','',concat(' ".
						Language::getMessage('tel.')." ',uc.phone))) name
		from user as u
		inner join user_customer as uc on u.id=uc.id_user
		where u.visible=1 /*and uc.name is not null and trim(uc.name)!=''*/
		".$sWhereManager."
		order by uc.name"));
		Base::$oResponse->addScript("$(\"#select_name_user\").remove();");
		Base::$oResponse->addAssign('sel_customer','innerHTML',
			Base::$tpl->fetch("finance/select_customer.tpl"));
		Base::$oResponse->addScript("$(\"#select_name_user\").searchable({
   			maxListSize: 50,
   			maxMultiMatch: 25,
    		wildcards: true,
    		ignoreCase: true,
    		latency: 1000,
    		warnNoMatch: '".Language::getMessage('no matches')." ...',
    		zIndex: 'auto'
    	});");
	}
	//-----------------------------------------------------------------------------------------------
	public function ShortOneClientDates($aData,$is_view_page=0) {
		$sDateFrom = Base::$aRequest['search']['date_from'];
		$sDateTo = Base::$aRequest['search']['date_to'];
		$sDateFromStart = date("Y-m-d H:i:s",strtotime($sDateFrom.'00:00:00'));
		$sDateToEnd = date("Y-m-d H:i:s",strtotime($sDateTo.'23:59:59'));
		
		$aCustomer=Db::GetRow(Base::GetSql('Customer',array(
				'login'=>Base::$aRequest['select_search_customer'],
		)));
		$sNameCustomer = $aCustomer['name_customer'];
		
		$iSum1 = $this->getDebtBegin($aCustomer['id_user'],$sDateFromStart);

		$aDataSet = array();
		$i=1;
		$iSum2=$iSum3=0;
		$aDatesCart = array();
		foreach ($aData as $aValue) {
			if (!$aValue['id_cart_package'])
				continue;
			
			$sDocument = $this->getNameDocument($aValue); 
			if (!$sDocument && Base::$aRequest['empty_orders'][$aValue['id_user']] && Base::$aRequest['empty_orders'][$aValue['id_user']][$aValue['id_cart_package']]) {
				continue;
			}
			if (!$sDocument && Base::$aRequest['empty_cart'][$aValue['id_user']] && Base::$aRequest['empty_cart'][$aValue['id_user']][$aValue['id_cart']]) {
				continue;
			}
			$sDate = date("d-m-Y",strtotime($aValue['post_date']));
			$sDateStart = date("Y-m-d 00:00:00",strtotime($aValue['post_date']));
			$sDateEnd = date("Y-m-d 23:59:59",strtotime($aValue['post_date']));
			
			if ($aValue['operation']=='pending_work' && !$aDatesCart[$aValue['custom_id']])
				$aDatesCart[$aValue['custom_id']] = $sDate;

			$this->RewriteCreditAmount($aValue,$credit,$debet,$aDatesCart,$sDate);
			if ($credit==0 && $debet==0)
				continue;
			
			$iSum2 += $credit;
			$iSum3 += $debet;
			if ($aDataSet[$sDate]) {
				$aDataSet[$sDate]['credit'] += $credit;
				$aDataSet[$sDate]['debet'] += $debet;
			}
			else {
				$debt_amount = $this->getDebtBegin($aCustomer['id_user'],$sDateStart);
				$account_amount = $this->getDebtBegin($aCustomer['id_user'],$sDateEnd);
				
				$aDataSet[$sDate] = array(
						'num_str' => $i,
						'post_date' => $sDate,
						'debt_amount' => $debt_amount,
						'credit' => $credit,
						'debet' => $debet,
						'account_amount' => $account_amount,
				);
				$i+=1;
			}
		}
		foreach ($aDataSet as $sDate => $aValue) {
			$aDataSet[$sDate]['account_amount'] = number_format(round($aValue['debt_amount'] - abs($aValue['credit']) + $aValue['debet'],2),2,".","");
			$aDataSet[$sDate]['debt_amount'] = number_format($aValue['debt_amount'],2,".","");
			$aDataSet[$sDate]['credit'] = number_format($aValue['credit'],2,".","");
			$aDataSet[$sDate]['debet'] = number_format($aValue['debet'],2,".","");
		}
		$iSum4 = number_format($iSum1 - abs($iSum2) + $iSum3,2,".","");
		$iSum1 = number_format($iSum1,2,".","");
		$iSum2 = number_format($iSum2,2,".","");
		$iSum3 = number_format($iSum3,2,".","");
		
		Base::$tpl->assign('iTotal',($i-1));
		Base::$tpl->assign('total_debt_amount',$iSum1);
		Base::$tpl->assign('total_credit',$iSum2);
		Base::$tpl->assign('total_debet',$iSum3);
		Base::$tpl->assign('total_account_amount',$iSum4);
		$aDataSet = array_values($aDataSet);
		
		if ($is_view_page) {
			$oTable=new Table();
			$oTable->iRowPerPage=1000;
			$oTable->aDataFoTable = $aDataSet;
			$oTable->sType='array';
			$oTable->aColumn=array(
					'num_str'=>array('sTitle'=>'num_str'),
					'post_date'=>array('sTitle'=>'post_date'),
					'debt_amount'=>array('sTitle'=>'DebtAmount'),
					'credit'=>array('sTitle'=>'finance credit'),
					'debet'=>array('sTitle'=>'finance debet'),
					'account_amount'=>array('sTitle'=>'AccountAmount'),
			);
			$oTable->sDataTemplate='finance/row_finance_customer_3.tpl';
			$oTable->sSubtotalTemplate='finance/subtotal_finance_3.tpl';
			Base::$sText.=$oTable->getTable("Account Log",'customer_account_log');
			return;
		}
		else {
			$aStyleText= array(
					'font' => array('bold' => true),
					'alignment' => array('horizontal' => 'center',),
					'borders' => array(
							'top' => array( 'style' => 'thin' ),
							'left' => array( 'style' => 'thin' ),
							'right' => array( 'style' => 'thin' ),
							'bottom' => array( 'style' => 'thin' ),
					),
			);
		
			$oExcel= new Excel();
			$oExcel->ReadExcel7(SERVER_PATH."/imgbank/finance_customer_report_3.xlsx");
			$oExcel->SetActiveSheetIndex();
			$oExcel->GetActiveSheet();
		
			$aStyleNumber= $aStyleText;
			$aStyleNumber['numberformat'] = $oExcel->aStyleFormatNumber00['numberformat'];
			$aCenter= array(
					'alignment' => array('horizontal' => 'center',),
			);
			$aCenterNumber= array(
					'alignment' => array('horizontal' => 'center',),
					'numberformat' => $oExcel->aStyleFormatNumber00['numberformat']
			);
		
			$this->MakroHeaderSet($oExcel);
					
			$oExcel->SetCellValue('B4',"Взаиморасчеты с клиентом за период с ".$sDateFrom." по ".$sDateTo);
			$oExcel->SetCellValue('B5',"Отчет по взаиморасчетам краткий по датам: ".$sNameCustomer);
				
			$i=8;
			foreach ($aDataSet as $aValue) {				
				$oExcel->SetCellValueExplicit('B'.$i, $aValue['num_str'],'',$aCenter);
				$oExcel->SetCellValueExplicit('C'.$i, $aValue['post_date']);
				$oExcel->SetCellValueExplicit('D'.$i, $aValue['debt_amount'],'',$aCenterNumber,2,'n');
				$oExcel->SetCellValueExplicit('E'.$i, $aValue['credit'],'',$aCenterNumber,2,'n');
				$oExcel->SetCellValueExplicit('F'.$i, $aValue['debet'],'',$aCenterNumber,2,'n');
				$oExcel->SetCellValueExplicit('G'.$i, $aValue['account_amount'],'',$aCenterNumber,2,'n');
				$i+=1;
			}
			$iSum4 = $aValue['account_amount'];
				
			$oExcel->SetCellValueExplicit('B'.$i,'Строк '.($i-8),'',$aStyleText);
			$oExcel->SetCellValueExplicit('C'.$i,'ВСЕГО','',$aStyleText);
			$oExcel->SetCellValueExplicit('D'.$i,$iSum1,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('E'.$i,$iSum2,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('F'.$i,$iSum3,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('G'.$i,$iSum4,'',$aStyleNumber,2,'n');
		
			//end
			$sFileName=uniqid().'.xlsx';
			$oExcel->WriterExcel7(SERVER_PATH.'/imgbank/temp_upload/'.$sFileName, true);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function ShortOneClientNoDates($aData,$is_view_page=0) {
		$sDateFrom = Base::$aRequest['search']['date_from'];
		$sDateTo = Base::$aRequest['search']['date_to'];
		$sDateFromStart = date("Y-m-d H:i:s",strtotime($sDateFrom.'00:00:00'));
		$sDateToEnd = date("Y-m-d H:i:s",strtotime($sDateTo.'23:59:59'));
				
		$aCustomer=Db::GetRow(Base::GetSql('Customer',array(
			'login'=>Base::$aRequest['select_search_customer'],
		)));
		$sNameCustomer = $aCustomer['name_customer'];
		
		$iSum1 = $this->getDebtBegin($aCustomer['id_user'],$sDateFromStart);
		$iSum2=$iSum3=0;
		foreach ($aData as $aValue) {
			if (!$aValue['id_cart_package'])
				continue;
					
			$sDocument = $this->getNameDocument($aValue);
			if (!$sDocument && Base::$aRequest['empty_orders'][$aValue['id_user']] && Base::$aRequest['empty_orders'][$aValue['id_user']][$aValue['id_cart_package']]) {
				continue;
			}
			if (!$sDocument && Base::$aRequest['empty_cart'][$aValue['id_user']] && Base::$aRequest['empty_cart'][$aValue['id_user']][$aValue['id_cart']]) {
				continue;
			}
				
			$this->RewriteCreditAmount($aValue,$credit,$debet);
			if ($credit==0 && $debet==0)
				continue;
			
			$iSum2 += $credit;
			$iSum3 += $debet;
		}
		$iSum1 = number_format($iSum1,2,".","");
		$iSum2 = number_format($iSum2,2,".","");
		$iSum3 = number_format($iSum3,2,".","");
		$iSum4 = number_format($iSum1 - abs($iSum2) + $iSum3,2,".","");
				
		$aDataSet[] = array(
			'num_str'=>1,
			'debt_amount' => $iSum1,
			'credit' => $iSum2,
			'debet' => $iSum3,
			'account_amount' => $iSum4,
		);
		if ($is_view_page) {
			
			Base::$tpl->assign('iTotal','1');
			Base::$tpl->assign('total_debt_amount',$iSum1);
			Base::$tpl->assign('total_credit',$iSum2);
			Base::$tpl->assign('total_debet',$iSum3);
			Base::$tpl->assign('total_account_amount',$iSum4);

			$oTable=new Table();
			$oTable->iRowPerPage=1000;
			$oTable->aDataFoTable = $aDataSet;
			$oTable->sType='array';
			$oTable->aColumn=array(
				'num_str'=>array('sTitle'=>'num_str'),
				'debt_amount'=>array('sTitle'=>'DebtAmount'),
				'credit'=>array('sTitle'=>'finance credit'),
				'debet'=>array('sTitle'=>'finance debet'),
				'account_amount'=>array('sTitle'=>'AccountAmount'),
			);
			$oTable->sDataTemplate='finance/row_finance_customer_4.tpl';
			$oTable->sSubtotalTemplate='finance/subtotal_finance_4.tpl';
			Base::$sText.=$oTable->getTable("Account Log",'customer_account_log');
			return;
		}
		else {
			$aStyleText= array(
					'font' => array('bold' => true),
					'alignment' => array('horizontal' => 'center',),
					'borders' => array(
							'top' => array( 'style' => 'thin' ),
							'left' => array( 'style' => 'thin' ),
							'right' => array( 'style' => 'thin' ),
							'bottom' => array( 'style' => 'thin' ),
					),
			);
		
			$oExcel= new Excel();
			$oExcel->ReadExcel7(SERVER_PATH."/imgbank/finance_customer_report_4.xlsx");
			$oExcel->SetActiveSheetIndex();
			$oExcel->GetActiveSheet();
		
			$aStyleNumber= $aStyleText;
			$aStyleNumber['numberformat'] = $oExcel->aStyleFormatNumber00['numberformat'];
			$aCenter= array(
					'alignment' => array('horizontal' => 'center',),
			);
			$aCenterNumber= array(
					'alignment' => array('horizontal' => 'center',),
					'numberformat' => $oExcel->aStyleFormatNumber00['numberformat']
			);
			
			$this->MakroHeaderSet($oExcel);
				
			$oExcel->SetCellValue('B4',"Взаиморасчеты с клиентом за период с ".$sDateFrom." по ".$sDateTo);
			$oExcel->SetCellValue('B5',"Отчет по взаиморасчетам краткий: ".$sNameCustomer);
			$i=8;
			$oExcel->SetCellValueExplicit('B'.$i,1,'',$aCenter);
			$oExcel->SetCellValueExplicit('C'.$i, $aDataSet[0]['debt_amount'],'',$aCenterNumber,2,'n');
			$oExcel->SetCellValueExplicit('D'.$i, $aDataSet[0]['credit'],'',$aCenterNumber,2,'n');
			$oExcel->SetCellValueExplicit('E'.$i, $aDataSet[0]['debet'],'',$aCenterNumber,2,'n');
			$oExcel->SetCellValueExplicit('F'.$i, $aDataSet[0]['account_amount'],'',$aCenterNumber,2,'n');
			$i+=1;
			$oExcel->SetCellValueExplicit('B'.$i,'Строк 1','',$aStyleText);
			$oExcel->SetCellValueExplicit('C'.$i,$aDataSet[0]['debt_amount'],'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('D'.$i,$aDataSet[0]['credit'],'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('E'.$i,$aDataSet[0]['debet'],'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('F'.$i,$aDataSet[0]['account_amount'],'',$aStyleNumber,2,'n');
		
			//end
			$sFileName=uniqid().'.xlsx';
			$oExcel->WriterExcel7(SERVER_PATH.'/imgbank/temp_upload/'.$sFileName, true);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function DetailOneClient($aData,$is_view_page=0) {
		//Debug::PrintPre($aData);
		$sDateFrom = Base::$aRequest['search']['date_from'];
		$sDateTo = Base::$aRequest['search']['date_to'];
		$sDateFromStart = date("Y-m-d H:i:s",strtotime($sDateFrom.'00:00:00'));
		$sDateToEnd = date("Y-m-d H:i:s",strtotime($sDateTo.'23:59:59'));
		
		$aCustomer=Db::GetRow(Base::GetSql('Customer',array(
			'login'=>Base::$aRequest['select_search_customer'],
		)));
		$sNameCustomer = $aCustomer['name_customer'];
		
		$iSum1 = $this->getDebtBegin($aCustomer['id_user'],$sDateFromStart);
		$aDataAssoc = array();
		$aDataStartSumForDate = array();
		$aDataCurrentSumForDate = array();
		$i=1;
		foreach ($aData as $aValue) {
			$sDokument = $this->getNameDocument($aValue);
			if (!$sDokument || !$aValue['id_cart_package'])
				continue;
			
			$aValue['document'] = $sDokument;
			$sKeyDokument = $sDokument;
			if (mb_strpos($sDokument,'Заказ №')===false) {
				$sKeyDokument = $sDokument.'_'.$i;
				$i+=1; 
			}
			
			$this->RewriteCreditAmount($aValue,$credit,$debet);
			if ($credit==0 && $debet==0)
				continue;
			
			if (!$aDataAssoc[$sKeyDokument]) {
				$aDataAssoc[$sKeyDokument] = $aValue;
				$aDataAssoc[$sKeyDokument]['post_date'] = date("d-m-Y",strtotime($aValue['post_date']));
				$aDataAssoc[$sKeyDokument]['credit'] = $credit;
				$aDataAssoc[$sKeyDokument]['debet'] = $debet;
			}
			else {
				// update diff fields
				$aDataAssoc[$sKeyDokument]['credit'] += $credit;
				$aDataAssoc[$sKeyDokument]['debet'] += $debet;
			}
		}
		//Debug::PrintPre($aDataAssoc);
		$aDataResult=array();$dStartAmount=0;$i=1;$iSum2=$iSum3=0;
		foreach ($aDataAssoc as $sKey => $aValue) {
			if (!$aDataResult)
				$dStartAmount = $iSum1;
			else 
				$dStartAmount = $dEndAmount;

			$dEndAmount = number_format($dStartAmount,2,".","") - abs(number_format($aValue['credit'],2,".","")) + number_format($aValue['debet'],2,".","");
			
			$aValue['account_amount'] = number_format($dEndAmount,2,".","");
			$aValue['debt_amount'] = number_format($dStartAmount,2,".","");
			$aValue['num_str'] = $i;
			//$aValue['document'] = $sKey;
			$aValue['debet'] = number_format($aValue['debet'],2,".","");
			$aValue['credit'] = number_format($aValue['credit'],2,".","");
			$aDataResult[] = $aValue;
			$i+=1;
			$iSum2+=$aValue['credit'];
			$iSum3+=$aValue['debet'];
		}

		$iSum1 = number_format($iSum1,2,".","");
		$iSum2 = number_format($iSum2,2,".","");
		$iSum3 = number_format($iSum3,2,".","");
		$iSum4 = number_format($iSum1 - abs($iSum2) + $iSum3,2,".","");
		
		Base::$tpl->assign('iTotal',($i-1));
		Base::$tpl->assign('total_debt_amount',$iSum1);
		Base::$tpl->assign('total_credit',$iSum2);
		Base::$tpl->assign('total_debet',$iSum3);
		Base::$tpl->assign('total_account_amount',$iSum4);
		//Debug::PrintPre($aDataResult);
		if ($is_view_page) {
			$oTable=new Table();
			$oTable->iRowPerPage=1000;
			$oTable->aDataFoTable = $aDataResult;
			$oTable->sType='array';
			$oTable->aColumn=array(
					'num_str'=>array('sTitle'=>'num_str'),
					'document'=>array('sTitle'=>'document'),
					'post_date'=>array('sTitle'=>'post_date'),
					'debt_amount'=>array('sTitle'=>'DebtAmount'),
					'credit'=>array('sTitle'=>'finance credit'),
					'debet'=>array('sTitle'=>'finance debet'),
					'account_amount'=>array('sTitle'=>'AccountAmount'),
			);
			$oTable->sDataTemplate='finance/row_finance_customer_5.tpl';
			$oTable->sSubtotalTemplate='finance/subtotal_finance_5.tpl';
			Base::$sText.=$oTable->getTable("Account Log",'customer_account_log');
			return;
		}
		else {
			$aStyleText= array(
					'font' => array('bold' => true),
					'alignment' => array('horizontal' => 'center',),
					'borders' => array(
							'top' => array( 'style' => 'thin' ),
							'left' => array( 'style' => 'thin' ),
							'right' => array( 'style' => 'thin' ),
							'bottom' => array( 'style' => 'thin' ),
					),
			);
		
			$oExcel= new Excel();
			$oExcel->ReadExcel7(SERVER_PATH."/imgbank/finance_customer_report_5.xlsx");
			$oExcel->SetActiveSheetIndex();
			$oExcel->GetActiveSheet();
		
			$aStyleNumber= $aStyleText;
			$aStyleNumber['numberformat'] = $oExcel->aStyleFormatNumber00['numberformat'];
			$aCenter= array(
					'alignment' => array('horizontal' => 'center',),
			);
			$aCenterNumber= array(
					'alignment' => array('horizontal' => 'center',),
					'numberformat' => $oExcel->aStyleFormatNumber00['numberformat']
			);
	
			$this->MakroHeaderSet($oExcel);
						
			$oExcel->SetCellValue('B4',"Взаиморасчеты с клиентом за период с ".$sDateFrom." по ".$sDateTo);
			$oExcel->SetCellValue('B5',"Отчет по взаиморасчетам подробный: ".$sNameCustomer);
				
			$i=8;
			$iSum2=$iSum3=$iSum4=0;
			foreach ($aDataResult as $sKey => $aValue) {
				//$iSum1 = $aValue['start'];
				$oExcel->SetCellValueExplicit('B'.$i, $i-7,'',$aCenter);
				$oExcel->SetCellValueExplicit('C'.$i, $aValue['document']);
				$oExcel->SetCellValueExplicit('D'.$i, $aValue['post_date']);
				$oExcel->SetCellValueExplicit('E'.$i, $aValue['debt_amount'],'',$aCenterNumber,2,'n');
				$oExcel->SetCellValueExplicit('F'.$i, $aValue['credit'],'',$aCenterNumber,2,'n');
				$oExcel->SetCellValueExplicit('G'.$i, $aValue['debet'],'',$aCenterNumber,2,'n');
				$oExcel->SetCellValueExplicit('H'.$i, $aValue['account_amount'],'',$aCenterNumber,2,'n');
				$i+=1;
				$iSum2 += $aValue['credit'];
				$iSum3 += $aValue['debet'];
			}
			$iSum4 = $iSum1 - abs($iSum2) + $iSum3;
			
			$oExcel->SetCellValueExplicit('B'.$i,'Строк '.($i-8),'',$aStyleText);
			$oExcel->SetCellValueExplicit('C'.$i,'','',$aStyleText);
			$oExcel->SetCellValueExplicit('D'.$i,'ВСЕГО','',$aStyleText);
			$oExcel->SetCellValueExplicit('E'.$i,$iSum1,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('F'.$i,$iSum2,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('G'.$i,$iSum3,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('H'.$i,$iSum4,'',$aStyleNumber,2,'n');
		
			if (Auth::$aUser['type_']=='customer') {
				$oExcel->SetCellValue('B1','');
				$oExcel->SetCellValue('B2','');
				$oExcel->SetCellValue('C1','');
				$oExcel->SetCellValue('C2','');
			}
			//end
			$sFileName=uniqid().'.xlsx';
			$oExcel->WriterExcel7(SERVER_PATH.'/imgbank/temp_upload/'.$sFileName, true);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function DetailAllClient($aData,$is_view_page=0) {
		$sDateFrom = Base::$aRequest['search']['date_from'];
		$sDateTo = Base::$aRequest['search']['date_to'];
		$sDateFromStart = date("Y-m-d H:i:s",strtotime($sDateFrom.'00:00:00'));
		$sDateToEnd = date("Y-m-d H:i:s",strtotime($sDateTo.'23:59:59'));
		
		if (Base::$aRequest['select_search_manager']) {
			$iIdManager = Db::getOne("Select id from user u
		    		where u.login='".Base::$aRequest['select_search_manager']."' and u.type_='manager'");
			if ($iIdManager)
				$sWhere .= " and uc.id_manager='".$iIdManager."' ";
			else
				$sWhere .= " and 0=1 ";
		}
		
		if(Auth::$aUser['is_super_manager']||Auth::$aUser['all_customer_visible']) {
			$sWhereManager = ' ';
		} else {
			$sWhereManager = " and uc.id_manager='".Auth::$aUser['id_user']."' ";
		}
		
		$sWhere.=" and u.type_='customer' ".$sWhereManager;
			
		$aNameUser=Db::GetAssoc("select u.id, u.login, concat(ifnull(uc.name,''),' ( ',u.login,' )',
				IF(uc.phone is null or uc.phone='','',concat(' ".
				Language::getMessage('tel.')." ',uc.phone))) name
			from user as u
			inner join user_customer as uc on u.id=uc.id_user
			where u.visible=1".$sWhere."
			order by uc.name");
		
		$aDataAssoc = array();
		foreach ($aNameUser as $iIdUser => $aValue) {
			$iSum1 = $this->getDebtBegin($iIdUser,$sDateFromStart);
			$aDataAssoc[$aValue['name']] = array(
					'start' => number_format($iSum1,2,".",""),
					'name' => $aValue['name'],
			);
		}
		$i=1;
		foreach ($aData as $aValue) {
			$sDokument = $this->getNameDocument($aValue);
			if (!$sDokument || !$aValue['id_cart_package'])
				continue;
			
			$aValue['document'] = $sDokument;
			$sKeyDokument = $sDokument;
			if (mb_strpos($sDokument,'Заказ №')===false) {
				$sKeyDokument = $sDokument.'_'.$i;
				$i+=1;
			}
			
			$this->RewriteCreditAmount($aValue,$credit,$debet);
			if ($credit==0 && $debet==0)
				continue;
			
			if (!$aDataAssoc[$aValue['name_customer']]['items'][$sKeyDokument]) {
				$aDataAssoc[$aValue['name_customer']]['items'][$sKeyDokument] = $aValue;
				$aDataAssoc[$aValue['name_customer']]['items'][$sKeyDokument]['post_date'] = date("d-m-Y",strtotime($aValue['post_date']));
				$aDataAssoc[$aValue['name_customer']]['items'][$sKeyDokument]['credit'] = $credit;
				$aDataAssoc[$aValue['name_customer']]['items'][$sKeyDokument]['debet'] = $debet;
			}
			else {
				// update diff fields
				$aDataAssoc[$aValue['name_customer']]['items'][$sKeyDokument]['credit'] += $credit;
				$aDataAssoc[$aValue['name_customer']]['items'][$sKeyDokument]['debet'] += $debet;
			}
		}
		//Debug::PrintPre($aDataAssoc);
		$aDataResult=array();$dStartAmount=0;$iSum2=$iSum3=0;
		foreach ($aDataAssoc as $sKey => $aValue) {
			$aDataResult[$sKey] = $aValue;
			if ($aValue['items'])
			foreach($aValue['items'] as $sItemKey => $aItem) {			
				if (!isset($aDataResult[$sKey]['current_start']))
					$aDataResult[$sKey]['current_start'] = number_format($aDataResult[$sKey]['start'],2,".","");
				else
					$aDataResult[$sKey]['current_start'] = number_format($aDataResult[$sKey]['current_end'],2,".","");
			
				$aDataResult[$sKey]['current_end'] = $aDataResult[$sKey]['current_start'] 
					- abs(number_format($aItem['credit'],2,".","")) + number_format($aItem['debet'],2,".","");
					
				$aDataResult[$sKey]['items'][$sItemKey]['account_amount'] = number_format($aDataResult[$sKey]['current_end'],2,".","");
				$aDataResult[$sKey]['items'][$sItemKey]['debt_amount'] = number_format($aDataResult[$sKey]['current_start'],2,".","");
			}
		}
		$aDataAssoc = $aDataResult;
		if ($is_view_page) {
			$j=1;
			foreach ($aDataResult as $sNameCustomer => $aValue) {
				$dSumCredit=0;$dSumDebet=0;
				if ($aValue['items'])
				foreach ($aValue['items'] as $sDokument => $aItem) {
					$dSumCredit+=number_format($aItem['credit'],2,".","");
					$dSumDebet+=number_format($aItem['debet'],2,".","");
					//$dLastAccountAmout = $aItem['account_amount'];
					$iSum2 += number_format($aItem['credit'],2,".","");
					$iSum3 += number_format($aItem['debet'],2,".","");
					$aDataAssoc[$sNameCustomer]['items'][$sDokument]['num_str'] = $j;
					$aDataAssoc[$sNameCustomer]['items'][$sDokument]['credit'] = number_format($aItem['credit'],2,".","");
					$aDataAssoc[$sNameCustomer]['items'][$sDokument]['debet'] = number_format($aItem['debet'],2,".","");
					$aDataAssoc[$sNameCustomer]['items'][$sDokument]['account_amount'] = number_format($aItem['account_amount'],2,".","");
					$j+=1;
				}
				$iSum1 += number_format($aValue['start'],2,".","");
				$aDataAssoc[$sNameCustomer]['credit'] = number_format($dSumCredit,2,".","");
				$aDataAssoc[$sNameCustomer]['debet'] = number_format($dSumDebet,2,".","");
				$aDataAssoc[$sNameCustomer]['end'] = number_format(number_format($aValue['start'],2,".","") - abs(number_format($dSumCredit,2,".","")) + number_format($dSumDebet,2,".",""),2,".","");
				// wtf ???
				if ($aDataAssoc[$sNameCustomer]['end']=='-0.00')
				    $aDataAssoc[$sNameCustomer]['end'] = '0.00';
				$iSum4 += number_format($aDataAssoc[$sNameCustomer]['end'],2,".","");
			}
			$iSum1 = number_format($iSum1,2,".","");
			$iSum2 = number_format($iSum2,2,".","");
			$iSum3 = number_format($iSum3,2,".","");
			$iSum4 = number_format($iSum4,2,".","");
				
			Base::$tpl->assign('iTotal',($j-1));
			Base::$tpl->assign('total_debt_amount',$iSum1);
			Base::$tpl->assign('total_credit',$iSum2);
			Base::$tpl->assign('total_debet',$iSum3);
			Base::$tpl->assign('total_account_amount',$iSum4);
			
			$oTable=new Table();
			$oTable->iRowPerPage=1000;
			$oTable->aDataFoTable = array_values($aDataAssoc);
			$oTable->sType='array';
			$oTable->aColumn=array(
				'num_str'=>array('sTitle'=>'num_str'),
				'login'=>array('sTitle'=>'customer login'),
				'post_date'=>array('sTitle'=>'post_date'),
				'debt_amount'=>array('sTitle'=>'DebtAmount'),
				'credit'=>array('sTitle'=>'finance credit'),
				'debet'=>array('sTitle'=>'finance debet'),
				'account_amount'=>array('sTitle'=>'AccountAmount'),
			);
			//$oTable->sTemplateName='finance/table_finance_customer_2.tpl';
			$oTable->sDataTemplate='finance/row_finance_customer_2.tpl';
			$oTable->sSubtotalTemplate='finance/subtotal_finance_2.tpl';
			Base::$sText.=$oTable->getTable("Account Log",'customer_account_log');
			return;
		}
		else {
			$aStyleText= array(
					'font' => array('bold' => true),
					'alignment' => array('horizontal' => 'center',),
					'borders' => array(
							'top' => array( 'style' => 'thin' ),
							'left' => array( 'style' => 'thin' ),
							'right' => array( 'style' => 'thin' ),
							'bottom' => array( 'style' => 'thin' ),
					),
			);
		
			$oExcel= new Excel();
			$oExcel->ReadExcel7(SERVER_PATH."/imgbank/finance_customer_report_2.xlsx");
			$oExcel->SetActiveSheetIndex();
			$oExcel->GetActiveSheet();
		
			$aStyleNumber= $aStyleText;
			$aStyleUserFill = $aStyleText;
			unset($aStyleUserFill['borders']);
			$aStyleUserFill['fill'] = array( 
				'type' => 'solid',
				'startcolor' => array('argb' => 'e7e4e4'),
				'endcolor'   => array('argb' => '00000000')
			);
			$aStyleUserFillLeft = $aStyleUserFill;
			$aStyleUserFillLeft['alignment'] = array('horizontal' => 'left',);
			$aStyleNumber['numberformat'] = $oExcel->aStyleFormatNumber00['numberformat'];
			$aStyleUserFill['numberformat'] = $oExcel->aStyleFormatNumber00['numberformat'];
			$aCenter= array(
					'alignment' => array('horizontal' => 'center',),
			);
			$aCenterNumber= array(
					'alignment' => array('horizontal' => 'center',),
					'numberformat' => $oExcel->aStyleFormatNumber00['numberformat']
			);
			
			$this->MakroHeaderSet($oExcel);
			
			$oExcel->SetCellValue('B4',"Взаиморасчеты с клиентами за период с ".$sDateFrom." по ".$sDateTo);

			$i=8;$j=1;
			$iSum1=$iSum2=$iSum3=$iSum4=0;
			foreach ($aDataAssoc as $sNameCustomer => $aValue) {
				$oExcel->SetCellValueExplicit('C'.$i,$sNameCustomer,'',$aStyleUserFillLeft);
				$oExcel->SetCellValueExplicit('D'.$i,'','',$aStyleUserFillLeft);
				$oExcel->SetCellValueExplicit('E'.$i,$aValue['start'],'',$aStyleUserFill,2,'n');
				$iClientStart = $i;
				$i+=1;$dSumCredit=0;$dSumDebet=0;
				if ($aValue['items'])
				foreach ($aValue['items'] as $sDokument => $aItem) {
					$oExcel->SetCellValueExplicit('B'.$i,$j,'',$aCenter);
					$oExcel->SetCellValueExplicit('C'.$i, $aItem['document']);			
					$oExcel->SetCellValueExplicit('D'.$i, $aItem['post_date']);
					$oExcel->SetCellValueExplicit('E'.$i, $aItem['debt_amount'],'',$aCenterNumber,2,'n');
					$oExcel->SetCellValueExplicit('F'.$i, $aItem['credit'],'',$aCenterNumber,2,'n');
					$oExcel->SetCellValueExplicit('G'.$i, $aItem['debet'],'',$aCenterNumber,2,'n');
					$oExcel->SetCellValueExplicit('H'.$i, $aItem['account_amount'],'',$aCenterNumber,2,'n');
					$j+=1;$i+=1;$dSumCredit+=$aItem['credit'];$dSumDebet+=$aItem['debet'];
					$iSum2 += $aItem['credit'];
					$iSum3 += $aItem['debet'];
				}
				$oExcel->SetCellValueExplicit('F'.$iClientStart,$dSumCredit,'',$aStyleUserFill,2,'n');
				$oExcel->SetCellValueExplicit('G'.$iClientStart,$dSumDebet,'',$aStyleUserFill,2,'n');
				$dLastAccountAmount = $aValue['start'] - abs($dSumCredit) + $dSumDebet;
				$oExcel->SetCellValueExplicit('H'.$iClientStart,$dLastAccountAmount,'',$aStyleUserFill,2,'n');
				$iSum1 += $aValue['start'];
				$iSum4 += $dLastAccountAmount;				
			}
			$oExcel->SetCellValueExplicit('B'.$i,'Строк '.($j-1),'',$aStyleText);
			$oExcel->SetCellValueExplicit('C'.$i,'','',$aStyleText);
			$oExcel->SetCellValueExplicit('D'.$i,'ВСЕГО','',$aStyleText);
			$oExcel->SetCellValueExplicit('E'.$i,$iSum1,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('F'.$i,$iSum2,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('G'.$i,$iSum3,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('H'.$i,$iSum4,'',$aStyleNumber,2,'n');
		
			//end
			$sFileName=uniqid().'.xlsx';
			$oExcel->WriterExcel7(SERVER_PATH.'/imgbank/temp_upload/'.$sFileName, true);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function getNameDocument($aValue) {
		$sDokument = '';$isIgnoreOrder=0;
		if ($aValue['operation']=='pending_work' || $aValue['data']=='package_return' 
			|| $aValue['data']=='cart' || $aValue['operation']=='pay_delivery'
		 	|| $aValue['operation']=='refused') {
			if ($aValue['id_cart_package'] || $aValue['data']=='package_return') {
				$sDokument = 'Заказ № '.($aValue['custom_id']?$aValue['custom_id']:'');
				$isIgnoreOrder = 1;
			}
		}
		elseif ($aValue['operation']=='pay_customer')
			$sDokument = 'Пко № '.($aValue['custom_id']?$aValue['custom_id']:'');
		elseif ($aValue['operation']=='pay_customer_ks' || $aValue['operation']=='pay_customer_rs')
			$sDokument = 'Банковская выписка № '.($aValue['custom_id']?$aValue['custom_id']:'');
		elseif ($aValue['operation']=='back_pay_customer')
			$sDokument = 'Рко № '.($aValue['custom_id']?$aValue['custom_id']:'');
		elseif ($aValue['data']=='debt_customer' || $aValue['data']=='prepay_customer')
			$sDokument = '';
		elseif ($aValue['data']=='return_store' || $aValue['data']=='return_provider')
			$sDokument = 'Накладная возврата';
		else 
			$sDokument = '№ '.($aValue['custom_id']?$aValue['custom_id']:'');

		if ($isIgnoreOrder && Base::$aRequest['empty_orders'][$aValue['id_user']] && Base::$aRequest['empty_orders'][$aValue['id_user']][$aValue['id_cart_package']]) {
			return '';
		}
		
		if ($isIgnoreOrder && Base::$aRequest['empty_cart'][$aValue['id_user']] && Base::$aRequest['empty_cart'][$aValue['id_user']][$aValue['id_cart']]) {
			return '';
		}
		
		return $sDokument;
	}
	//-----------------------------------------------------------------------------------------------
	public function getTypeReport() {
		if (!Base::$aRequest['is_post'] || Base::$aRequest['search_type_report'] == 'log')
			return 0;
		$sTypeReport = 1; // short all client
		if (Base::$aRequest['search_type_report'] == 'detail')
			$sTypeReport = 2; // detail all client
		if (Base::$aRequest['select_search_customer'] != '') {
			if (Base::$aRequest['search_date'] && Base::$aRequest['search_type_report'] == 'short')
				$sTypeReport = 3; // short client for dates
			elseif (!Base::$aRequest['search_date'] && Base::$aRequest['search_type_report'] == 'short')
				$sTypeReport = 4; // short client without dates
			elseif (Base::$aRequest['search_type_report'] == 'detail')
				$sTypeReport = 5; // detail client without dates
		}
		return $sTypeReport;	
	}
	//-----------------------------------------------------------------------------------------------
	public function getTypeReportProvider() {
		if (!Base::$aRequest['is_post'] || Base::$aRequest['search_type_report'] == 'log')
			return 0;
		$sTypeReport = 1; // short all client
		if (Base::$aRequest['search_type_report'] == 'detail')
			$sTypeReport = 2; // detail all client
		elseif (Base::$aRequest['search_date'] && Base::$aRequest['search_type_report'] == 'short')
			$sTypeReport = 3; // short client for dates

		return $sTypeReport;
	}
	//-----------------------------------------------------------------------------------------------
	public function getDataFinanceCustomer() {
		// --- search ---
		if (Base::$aRequest['select_search_customer']) {
			$sWhere.=" and u.login = '".Base::$aRequest['select_search_customer']."'";
		}
		
		//if (Base::$aRequest['search_date']) {
			$sWhere.=" and ual.post_date>='".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
					and ual.post_date<'".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."'";
			$sWhereDate = " and ual.post_date>='".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
					and ual.post_date<'".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."'";
		//}
		if (Base::$aRequest['search']['description']) {
			$sWhere.=" and ual.description like '%".Base::$aRequest['search']['description']."%'";
		}
		 
		if (Base::$aRequest['select_search_manager']) {
			$iIdManager = Db::getOne("Select id from user u
		    		where u.login='".Base::$aRequest['select_search_manager']."' and u.type_='manager'");
			if ($iIdManager)
				$sWhere .= " and uc.id_manager='".$iIdManager."' ";
			else
				$sWhere .= " and 0=1 ";
		}
		
		if(Auth::$aUser['is_super_manager']/*||Auth::$aUser['all_customer_visible']*/) {
			$sWhereManager = ' ';
		} else {
			$sWhereManager = " and uc.id_manager='".Auth::$aUser['id_user']."' ";
		}
		
		if (Auth::$aUser['type_']=='manager')
			$sWhereType =" and u.type_='customer' ".$sWhereManager;
		
		if (!Base::$aRequest['is_post'])
			$aLogData = array();
		else {
			// --------------
			Finance::AssignSubtotal($sWhere);
			// --------------
			$sSql = Base::GetSql('UserAccountLog',array(
			'where'=>$sWhere.$sWhereType,
		    ));
		    $sSql.=" order by uc.name,ual.post_date,ual.id";
		    $aLogData=Db::GetAll($sSql);

		    // add refused items ual
		    if ($aLogData) {
		    	$aOrders = array();
		    	foreach ($aLogData as $aValue)
		    		if (!$aOrders[$aValue['custom_id']])
		    			$aOrders[$aValue['custom_id']] = $aValue['custom_id'];
				if ($aOrders)
		    		$aCartRefused = Db::getAssoc("SELECT id as key_, id
					FROM `cart`	WHERE id_cart_package IN ( ".implode(',',array_keys($aOrders))." )
					AND order_status = 'refused'");
				if ($aCartRefused) {
					$sWhere = " and ((1=1 ".$sWhere.$sWhereType.") || (ual.id_cart in (".implode(",",$aCartRefused).")".$sWhereType.$sWhereDate."))";
					$sSql = Base::GetSql('UserAccountLog',array(
							'where'=>$sWhere,
					));
					$sSql.=" order by uc.name,ual.post_date,ual.id";
					$aLogData=Db::GetAll($sSql);
				}
		    }
	    }

	    $this->CallParseLog($aLogData);
		return $aLogData;	  
	}
	//-----------------------------------------------------------------------------------------------
	public function MakroHeaderSet(&$oExcel) {
		if (Base::$aRequest['select_search_manager']) {
			$aManager=Db::GetRow(Base::GetSql('Manager',array(
					'login'=>Base::$aRequest['select_search_manager'],
			)));
			if ($aManager)
				$oExcel->SetCellValue('C1',$aManager['name']." (".$aManager['login'].")");
			else
				$oExcel->SetCellValue('C1',"не найден");
		}
		else
			$oExcel->SetCellValue('C1',"все");
		
		if (Base::$aRequest['select_search_customer']) {
			$aCustomer=Db::GetRow(Base::GetSql('Customer',array(
					'login'=>Base::$aRequest['select_search_customer'],
			)));
			if ($aCustomer)
				$oExcel->SetCellValue('C2',$aCustomer['name']." (".$aCustomer['login'].") тел. ".$aCustomer['phone']);
			else
				$oExcel->SetCellValue('C2',"не найден");
		}
		else
			$oExcel->SetCellValue('C2',"Все");
	}
	//-----------------------------------------------------------------------------------------------
	public function getDebtBegin($iIdUser,$sStartDateTime='') {
		if (!$iIdUser)
			return 0;

		if (!$sStartDateTime)
			$sWhere = " and ual.id_user=".$iIdUser;
		else 
			$sWhere = " and ual.id_user=".$iIdUser." and ual.post_date<'".$sStartDateTime."'";

		$sWhereUser = " and ual.id_user=".$iIdUser;
		
		$aCartPackagesEmpty = array();
		$aCartPackages = Db::getAssoc("select cp.id as key_, cp.id
			from user_account_log ual
			inner join cart_package cp on cp.id = ual.custom_id
			where 1=1 and ual.id_user=".$iIdUser." group by cp.id");
		if ($aCartPackages) {
			$aCartPackagesOk = Db::getAssoc("SELECT id_cart_package as key_, id_cart_package
				FROM `cart`	WHERE id_cart_package IN ( ".implode(',',array_keys($aCartPackages))." )
				AND order_status != 'refused'
				GROUP BY id_cart_package");
			$aCartPackagesEmpty = array_diff($aCartPackages, $aCartPackagesOk);
		}
		
		if ($aCartPackagesEmpty)
			Base::$aRequest['empty_orders'][$iIdUser] = $aCartPackagesEmpty;
		
		if ($aCartPackagesOk)
			$sWhereAdd =" || (cp.id in (".implode(',',array_keys($aCartPackagesOk)).")) ";
		elseif ($aCartPackagesEmpty) 
			$sWhereAdd = " || (cp.id not in (".implode(',',array_keys($aCartPackagesEmpty)).")) ";
		
		if ($aCartPackagesEmpty)
			$sWhereEmpty = " and (id_cart_package not in (".implode(',',array_keys($aCartPackagesEmpty)).")) ";
		
		$sWhereNotIgnoreEmpty = " ual.operation='pay_customer'
			 || ual.operation = 'pay_customer_ks' || ual.operation = 'pay_customer_rs'
			 || ual.operation = 'back_pay_customer' || ual.data = 'debt_customer'
			 || ual.data='prepay_customer' || ual.data='return_store' 
			 || ual.data='return_provider' ";

		// заказы в интервале дат
		$aOrders = Db::getAssoc("select custom_id as id, custom_id
			from user_account_log ual
			left join cart_package cp on cp.id = ual.custom_id
			where 1=1 and ((".$sWhereNotIgnoreEmpty.$sWhereAdd.")".$sWhere.")");
		
		if ($aOrders)
			$aCartRefused = Db::getAssoc("SELECT id as key_, id
				FROM `cart`	WHERE id_cart_package IN ( ".implode(',',array_keys($aOrders)).
				" )".$sWhereEmpty." AND order_status = 'refused'");
		
		// отказные детали по заказам в интервале дат кроме полностью отказных заказов
		if ($aCartRefused) {
			$sWhereRefused = " || (ual.id_cart in (".implode(',',array_keys($aCartRefused)).") ".$sWhereUser.")";
		}
		
		// все отказные детали
		if ($aCartPackagesOk)
			$aCartRefusedAll = Db::getAssoc("SELECT id as key_, id
				FROM `cart`	WHERE id_cart_package IN ( ".implode(',',array_keys($aCartPackagesOk)).
				" )".$sWhereEmpty." AND order_status = 'refused'");
		if ($aCartRefusedAll)
			Base::$aRequest['empty_cart'][$iIdUser] = $aCartRefusedAll;
		
		$sSql="select *
			from user_account_log ual
			left join cart_package cp on cp.id = ual.custom_id
			where 1=1 and ((".$sWhereNotIgnoreEmpty.$sWhereAdd.")".$sWhere.")".$sWhereRefused;
		
		// потому что нужно обновить суммы заказа, не учитывать изменения в деталях
		$aData = Db::getAll($sSql);
		if ($aData) {
			$iSum = 0;
			foreach ($aData as $aValue) {
				if ($aValue['data']=='debt_customer' || $aValue['data']=='prepay_customer')
					$iSum += $aValue['amount'];
				else {
					$this->RewriteCreditAmount($aValue,$credit,$debet,$aDatesCart,$sDate);
					if ($credit==0 && $debet==0)
						continue;
					elseif ($credit!=0)
						$iSum += $credit;
					else 
						$iSum += $debet;
				}
			}
		}

		//$iSum = Db::getOne($sSql);
		if (!$iSum)
			$iSum = 0;
		return $iSum;
	}
	//-----------------------------------------------------------------------------------------------
	public function RewriteCreditAmount($aValue,&$credit=0,&$debet=0,&$aDatesCart=array(),&$sDate='') {
		// cart refused
		if ($aValue['operation']=='refused' && $aValue['id_cart']>0 && isset($aValue['custom_id'])) {
			$debet=$credit=0;
			return;
		}
		
		// сверить сумму за заказ с текущей суммой заказа
		if ($aValue['operation']=='pending_work' && isset($aValue['custom_id'])) {
			$dTotalOrder = Db::getOne("select price_total from cart_package where id=".$aValue['custom_id']);
			// round if need
			$dTotalOrder = Currency::PrintPrice($dTotalOrder,null,0,"<none>");
			if ($dTotalOrder && $dTotalOrder!=abs($aValue['amount']))
				$aValue['amount'] = -$dTotalOrder;
		}
		// операция изменения цены/кол-ва - игнор
		if ($aValue['data']=='cart' && !$aValue['operation'] && $aValue['id_cart']>0) {
			$debet=$credit=0;
			return;
		}
		
		// операция оплата доставки - входит в сумму заказа
		if (!$aValue['data'] && $aValue['operation']=='pay_delivery' && isset($aValue['custom_id'])) {
			$debet=$credit=0;
			return;
		}
		// операция возврат оплаты доставки - входит в сумму заказа
		if ($aValue['data']=='cart' && !$aValue['operation'] && isset($aValue['custom_id'])) {
			$debet=$credit=0;
			return;
		}
		
		if (Base::$aRequest['empty_orders'][$aValue['id_user']] && Base::$aRequest['empty_orders'][$aValue['id_user']][$aValue['id_cart_package']])
			$sDocument = $this->getNameDocument($aValue);
		
		if ($aValue['data']=='debt_customer' || $aValue['data']=='prepay_customer' ||
			$aValue['data']=='debt_provider' || $aValue['data']=='prepay_provider') {
			$debet=$credit=0;
		}
		elseif (!$sDocument && Base::$aRequest['empty_orders'][$aValue['id_user']] && Base::$aRequest['empty_orders'][$aValue['id_user']][$aValue['id_cart_package']]) {
			$debet=$credit=0;
		}
		elseif ($aValue['data']!='cart' && $aValue['operation']!='refused') {
			$credit = $aValue['amount']<0?$aValue['amount']:'0';
			$debet = abs($aValue['amount']>=0?$aValue['amount']:'0');
		}
		else {
			$credit=$aValue['amount'];
			$debet=0;
			// rewrite date
			if ($aDatesCart[$aValue['custom_id']])
				$sDate = $aDatesCart[$aValue['custom_id']];
		}
		$credit = number_format($credit,2,".","");
		$debet = number_format($debet,2,".","");
	}
	//-----------------------------------------------------------------------------------------------
	public function CorrectBalance() {
		if (Base::$aRequest['id_provider']) {
			$this->CorrectBalanceProvider();
			return;
		}
			
		if (Base::$aRequest['is_post'])
		{
			if (!Base::$aRequest['data']['amount'] || !Base::$aRequest['data']['id_user'] || !Base::$aRequest['data']['pay_type']) {
				$sError="Please, fill the required fields";
				Base::$tpl->assign('aData',$aData=Base::$aRequest['data']);
			}
			else {
				if (Base::$aRequest['data']['pay_type']=='debt_customer')
					Base::$aRequest['data']['amount'] = '-'.abs(Base::$aRequest['data']['amount']);
				
				$sComment = Language::getMessage("correct balance").' ('.Language::getMessage(Base::$aRequest['data']['pay_type']).') '.Base::$aRequest['data']['comment'];
				Finance::Deposit(Base::$aRequest['data']['id_user'],
					Base::$aRequest['data']['amount'],
					mysql_real_escape_string($sComment),'',
					'interval',Base::$aRequest['data']['pay_type'],0,0,0,
					'',0,0,true,0,'',
					Base::$aRequest['data']['post_date']
				);
				Form::RedirectAuto("&aMessage[MI_NOTICE]=balance corrected");
			}
		}
		 
		$aPayType=array('' => Language::getMessage("not selected"), 
				'debt_customer' => Language::getMessage("Debt customer"),
				"prepay_customer" => Language::getMessage("Prepay customer"));
		Base::$tpl->assign('aPayType', $aPayType);
	
		//$sDate = '2017-09-19 23:59:59';
		$sDate = date("Y-m-d H:i:s",strtotime(Language::getConstant('finance_customer:board_date','01.01.2017').' 00:00:00') - 1);
		$aField['login']=array('title'=>'correct balance to customer','type'=>'text','value'=>Base::$aRequest['login']);
		$aField['date']=array('title'=>'date','type'=>'text','value'=>$sDate);
		$aField['hr']=array('type'=>'hr','colspan'=>2);
		$aField['amount']=array('title'=>'Amount','type'=>'input','value'=>$aData['amount'],'name'=>'data[amount]','szir'=>1);
		$aField['pay_type']=array('title'=>'Pay Type','type'=>'select','options'=>$aPayType,'name'=>'data[pay_type]','szir'=>1);
		$aField['comment']=array('title'=>'Comment','type'=>'textarea','name'=>'data[comment]','value'=>$aData['comment']?$aData['comment']:Base::$aRequest['data']['comment']);
		$aField['id_user']=array('type'=>'hidden','name'=>'data[id_user]','value'=>Base::$aRequest['id_user']);
		$aField['post_date']=array('type'=>'hidden','name'=>'data[post_date]','value'=>$sDate);
		$aField['return']=array('type'=>'hidden','name'=>'data[return]','value'=>urlencode(Base::$aRequest['return']));
	
		$aData=array(
				'sHeader'=>"method=post",
				'sTitle'=>"Correct balance",
				'aField'=>$aField,
				'bType'=>'generate',
				'sSubmitButton'=>'Apply',
				'sSubmitAction'=>'finance_correct_balance',
				'sError'=>$sError,
		);
		$oForm=new Form($aData);
	
		Base::$sText.=$oForm->getForm();
	}	
	//-----------------------------------------------------------------------------------------------
	public function FinanceCustomerSetCustomId() {
		if (Base::$aRequest['id']) {
			$aData = Db::getRow("Select ual.*, cp.id as id_cart_package 
				from user_account_log ual
				left join cart_package cp on cp.id = ual.custom_id
				where ual.id=".Base::$aRequest['id']);
			if ($aData) {
				$sCid=str_replace('+',' ',str_replace("\n",' ',urldecode(Base::$aRequest['cid'])));
		
				Db::Execute("Update user_account_log set custom_id='".mysql_escape_string($sCid)."'
						where id=".Base::$aRequest['id']);
				
				$aData = Db::getRow("Select ual.*, cp.id as id_cart_package
					from user_account_log ual
					left join cart_package cp on cp.id = ual.custom_id
					where ual.id=".Base::$aRequest['id']);
			}
			
			if ($aData)
				$sDokument = Finance::getNameDocument($aData);

			$sText = '';
			if ($aData['data']=='package_return' || !$aData['id_cart_package'])
				$sText = '<br><span style="color:grey">Заказ № '.$sCid.'</span>';
			elseif ($sDokument)
				$sText = '<br><span style="color:green">'.$sDokument.'</span>';
			
			Base::$oResponse->addScript("$('#div_edit_comment_".Base::$aRequest['id']."').hide();");
			Base::$oResponse->addScript("$('#img_save_comment_".Base::$aRequest['id']."').hide();");
			Base::$oResponse->addScript("$('#img_edit_comment_".Base::$aRequest['id']."').show();");
			Base::$oResponse->addScript("$('#div_view_comment_".Base::$aRequest['id']."').html('".$sText."');");
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function Reestr()
	{
		if(Auth::$aUser['is_super_manager']||Auth::$aUser['all_customer_visible'])
			$sWhereManager = ' ';
		else
			$sWhereManager = " and uc.id_manager='".Auth::$aUser['id_user']."' ";

		Base::$sText.=Base::$tpl->fetch('panel/tab_manager_reestr.tpl');
		
		Base::$tpl->assign('aNameUser',$aNameUser=array(0 =>'')+Db::GetAssoc("select u.login, concat(ifnull(uc.name,''),' ( ',u.login,' )',
				IF(uc.phone is null or uc.phone='','',concat(' ".
					Language::getMessage('tel.')." ',uc.phone))) name
		from user as u
		inner join user_customer as uc on u.id=uc.id_user
		where u.visible=1 /*and uc.name is not null and trim(uc.name)!=''*/
		".$sWhereManager."
		order by uc.name"));
		Resource::Get()->Add('/js/select_search.js');


		$aTemplate = array(''=>Language::GetMessage('All'));
		if (Base::$aRequest['action']=='finance_reestr_pko') {
			$aTemplate += Db::getAssoc("Select id, name from account where visible=1 and in_use_pko=1 order by name");
		}
		elseif (Base::$aRequest['action']=='finance_reestr_bv') {
			$aTemplate += Db::getAssoc("Select id, name from account where visible=1 and in_use_bv=1 order by name");
		}
		elseif (Base::$aRequest['action']=='finance_reestr_rko') {
			$aTemplate += Db::getAssoc("Select id, name from account where visible=1 and in_use_rko=1 order by name");
		}
		
/*		$aTemplate=array(
				
				'simple_bill'=>Language::GetMessage('simple_bill'),
				'order_bill'=>Language::GetMessage('order_bill'),
		);*/
		
		if(Auth::$aUser['typle_']=='manager') $aField['search_login']=array('title'=>'Login','type'=>'select','options'=>$aNameUser,'selected'=>Base::$aRequest['search_login'],'name'=>'search_login','class'=>'select_search');
		$aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("1.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
		$aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		if(Auth::$aUser['typle_']=='manager') $aField['fio']=array('title'=>'Fio','type'=>'input','value'=>Base::$aRequest['search']['fio'],'name'=>'search[fio]');
		$aField['amount_from']=array('title'=>'amFrom','type'=>'input','value'=>Base::$aRequest['search']['amount_from'],'name'=>'search[amount_from]','checkbox'=>1);
		$aField['amount_to']=array('title'=>'amTo','type'=>'input','value'=>Base::$aRequest['search']['amount_to'],'name'=>'search[amount_to]');
		$aField['template']=array('title'=>'Template','type'=>'select','options'=>$aTemplate,'selected'=>Base::$aRequest['search']['template'],'name'=>'search[template]');
		$aField['id_cart_package']=array('title'=>'cartpackage #','type'=>'input','value'=>Base::$aRequest['search']['id_cart_package'],'name'=>'search[id_cart_package]');
		$aField['id']=array('title'=>'id','type'=>'input','value'=>Base::$aRequest['search']['id'],'name'=>'search[id]');
		$aField['search_login']=array('title'=>'Login_','type'=>'select','options'=>$aNameUser,'name'=>'search_login','selected'=>Base::$aRequest['search_login'],'class'=>'select_name_user');
		$aData=array(
				'sHeader'=>"method=get",
				//'sContent'=>Base::$tpl->fetch('finance/form_bill_search.tpl'),
				'aField'=>$aField,
				'bType'=>'generate',
				'sGenerateTpl'=>'form/index_search.tpl',
				'sSubmitButton'=>'Search',
				'sSubmitAction'=>Base::$aRequest['action'],
				'sReturnButton'=>'Clear',
				'bIsPost'=>0,
				'sWidth'=>'80%',
				'sError'=>$sError,
		);
		$oForm=new Form($aData);
	
		Base::$sText .= $oForm->getForm();
	
		$oTable=new Table();
		if (Auth::$aUser['type_']=='customer') $sWhere=Auth::$sWhere;
		else $sWhere='';
	
		$sWhere = str_replace('and id_user','and b.id_user', $sWhere);
	
		// --- search ---
		if (Base::$aRequest['search_login']) {
			$sWhere.=" and (u.login like '%".Base::$aRequest['search_login']."%'";
			$sWhere.=" || uc.name like '%".Base::$aRequest['search_login']."%'";
			$sWhere.=" || uc.phone like '%".Base::$aRequest['search_login']."%')";
		}
		if (Base::$aRequest['search']['fio']) $sWhere.=" and uc.name like '%".Base::$aRequest['search']['fio']."%'";
	
		if (Base::$aRequest['search']['date']) {
			$sWhere.=" and (b.post_date >= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
	            and b.post_date <= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."') ";
		}
		if (Base::$aRequest['search']['amount']) {
			$sWhere.=" and (b.amount >= '".Base::$aRequest['search']['amount_from']."'
	            and b.amount <= '".Base::$aRequest['search']['amount_to']."') ";
		}
		if (Base::$aRequest['search']['id_cart_package'])
			$sWhere.=" and b.id_cart_package like '%".Base::$aRequest['search']['id_cart_package']."%'";
		if (Base::$aRequest['search']['id'])
			$sWhere.=" and b.id like '%".Base::$aRequest['search']['id']."%'";
	
		switch (Base::$aRequest['action']) {
			case 'finance_reestr_pko':
				$sWhere .= " and code_template='order_bill'";
				break;
			case 'finance_reestr_bv':
				$sWhere .= " and code_template='order_bill_bv'";
				break;
			case 'finance_reestr_rko':
				$sWhere .= " and code_template='order_bill_rko'";
				break;
		}
		
		if (Base::$aRequest['search']['template'])
			$sWhere .= " and b.id_account = ".Base::$aRequest['search']['template'];
		
		// --- search ---
		$oTable->sSql=Base::GetSql('Bill',array(
				"where"=>$sWhere,
		));

		$oTable->aColumn=array(
				'id_cart_package'=>array('sTitle'=>'cartpackage #'),
				'id'=>array('sTitle'=>'id'),
				'amount'=>array('sTitle'=>'Amount'),
				'template'=>array('sTitle'=>'Template'),
				'post'=>array('sTitle'=>'Date'),
				'action'=>array(),
		);
		$oTable->aOrdered="order by b.post_date desc";
		$oTable->sDataTemplate='finance/row_bill.tpl';
		$oTable->sButtonTemplate='finance/button_bill.tpl';
		$oTable->bCheckVisible=false;
		$oTable->sWidth='100%';
		Base::$sText.=$oTable->getTable("Customer Bills",'customer_bill');
	}
	//-----------------------------------------------------------------------------------------------
	public function FinanceUser()
	{
		Base::$tpl->assign('aTypeReport',$aTypeReport=array('detail' => Language::getMessage('detail_report')));
		 
		$sDateStart = Language::getConstant('finance_customer:board_date','20.09.2017');
		if (strtotime(Base::$aRequest['search']['date_from'])<strtotime($sDateStart.' 00:00:00'))
	    	$_REQUEST['search']['date_from'] = Base::$aRequest['search']['date_from'] = $sDateStart;
		
		Base::$aRequest['select_search_customer'] = Auth::$aUser['login'];
		
		$aData=array(
				'sHeader'=>"method=post",
				'aField'=>$aField,
				'sContent'=>Base::$tpl->fetch('finance/form_finance_user.tpl'),
				'sSubmitAction'=>'finance_user',
				'sReturnButton'=>'Clear',
				'sReturnAction'=>'finance_user',
				'bIsPost'=>0,
				'sWidth'=>'500px',
				'sError'=>$sError,
		);
		$oForm=new Form($aData);
		$oForm->sAdditionalButtonTemplate='finance/button_export_user.tpl';
		Base::$sText.=$oForm->getForm();
	
		$aLogData = $this->getDataFinanceCustomer();
		if (Base::$aRequest['is_post'])
			$this->DetailOneClient($aLogData,1);
	}
	//-----------------------------------------------------------------------------------------------
	public function getDataFinanceProfit() {
		// --- search ---
		if (Base::$aRequest['select_search_customer']) {
			$sWhere.=" and u.login = '".Base::$aRequest['select_search_customer']."'";
		}
	
		$sHaving=" having cl_post_date>='".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
					and cl_post_date<'".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."'";
			
		if (Base::$aRequest['select_search_manager']) {
			$iIdManager = Db::getOne("Select id from user u
		    		where u.login='".Base::$aRequest['select_search_manager']."' and u.type_='manager'");
			if ($iIdManager)
				$sWhere .= " and uc.id_manager='".$iIdManager."' ";
			else
				$sWhere .= " and 0=1 ";
		}
	
		if(Auth::$aUser['is_super_manager']||Auth::$aUser['all_customer_visible']) {
			$sWhereManager = ' ';
		} else {
			$sWhereManager = " and uc.id_manager='".Auth::$aUser['id_user']."' ";
		}
	
		if (Auth::$aUser['type_']=='manager')
			$sWhere.=" and u.type_='customer' ".$sWhereManager;
	
		if (!Base::$aRequest['is_post'])
			$aLogData = array();
		else {
			// --------------
			//Finance::AssignSubtotal($sWhere);
			// --------------
			$sSql = "Select c.*, uc.name as name_customer, u.login,
				(select post_date from cart_log where cart_log.id_cart = c.id and cart_log.order_status='end' order by cart_log.id desc limit 1) as cl_post_date
				from cart c
				inner join user u on u.id = c.id_user
				inner join user_customer uc on uc.id_user = c.id_user
				where 1=1 and u.visible=1".$sWhere." and c.order_status='end' /*order by c.post_date, uc.name*/ ".$sHaving;
			

			
			$aLogData=Db::GetAll($sSql);
		}
		//Debug::PrintPre($sSql);
		return $aLogData;
	}
	//-----------------------------------------------------------------------------------------------
	public function ProfitShortOneClientDates($aData,$is_view_page=0) {
		$sDateFrom = Base::$aRequest['search']['date_from'];
		$sDateTo = Base::$aRequest['search']['date_to'];
		$sDateFromStart = date("Y-m-d H:i:s",strtotime($sDateFrom.'00:00:00'));
		$sDateToEnd = date("Y-m-d H:i:s",strtotime($sDateTo.'23:59:59'));

		$aDataSet = array();
		$i=1;
		$aDatesCart = array();
		foreach ($aData as $aValue) {
			$sDate = date("d-m-Y",strtotime($aValue['cl_post_date']));
			$sDateStart = date("Y-m-d 00:00:00",strtotime($aValue['cl_post_date']));
			$sDateEnd = date("Y-m-d 23:59:59",strtotime($aValue['cl_post_date']));
			$iSum1 += ($aValue['price']*$aValue['number']);
			$iPriceOriginal = $aValue['price_original'];
			if ($aValue['price_original_one_currency']!=0 && 
				$aValue['price_original_one_currency']!=$aValue['price_original'])
				$iPriceOriginal = $aValue['price_original_one_currency'];

			$iSum2 += ($iPriceOriginal*$aValue['number']);
			
			if ($aDataSet[$sDate]) {
				$aDataSet[$sDate]['total_price'] += ($aValue['price']*$aValue['number']);
				$aDataSet[$sDate]['total_real_price'] += ($iPriceOriginal*$aValue['number']);
			}
			else {	
				$aDataSet[$sDate] = array(
						'num_str' => $i,
						'post_date' => $sDate,
						'total_price' => ($aValue['price']*$aValue['number']),
						'total_real_price' => ($iPriceOriginal*$aValue['number']),
				);
				$i+=1;
			}
		}
		foreach ($aDataSet as $sDate => $aValue) {
			$aDataSet[$sDate]['profit'] = number_format($aValue['total_price']-$aValue['total_real_price'],2,".","");
			$aDataSet[$sDate]['total_price'] = number_format($aValue['total_price'],2,".","");
			$aDataSet[$sDate]['total_real_price'] = number_format($aValue['total_real_price'],2,".","");
			$aTmp[] = $aDataSet[$sDate];
			$aTmpSort[] = strtotime($aDataSet[$sDate]['post_date']);
		}
		array_multisort ($aTmpSort, SORT_ASC, SORT_NUMERIC, $aTmp);
		$aDataSet = $aTmp;
		$i=1;
		foreach ($aDataSet as $sDate => $aValue) {
			$aDataSet[$sDate]['num_str'] = $i;
			$i+=1;
		}
		
		$iSum3 = number_format($iSum1 - $iSum2,2,".","");
		$iSum1 = number_format($iSum1,2,".","");
		$iSum2 = number_format($iSum2,2,".","");
	
		Base::$tpl->assign('iTotal',($i-1));
		Base::$tpl->assign('total_price',$iSum1);
		Base::$tpl->assign('total_real_price',$iSum2);
		Base::$tpl->assign('total_profit',$iSum3);
		$aDataSet = array_values($aDataSet);
	
		if ($is_view_page) {
			$oTable=new Table();
			$oTable->iRowPerPage=1000;
			$oTable->aDataFoTable = $aDataSet;
			$oTable->sType='array';
			$oTable->aColumn=array(
					'num_str'=>array('sTitle'=>'num_str'),
					'post_date'=>array('sTitle'=>'post_date'),
					'total_price'=>array('sTitle'=>'price_profit'),
					'total_real_price'=>array('sTitle'=>'real_price_profit'),
					'profit'=>array('sTitle'=>'profit'),
			);
			$oTable->sDataTemplate='finance/row_finance_profit_3.tpl';
			$oTable->sSubtotalTemplate='finance/subtotal_finance_profit_3.tpl';
			Base::$sText.=$oTable->getTable("Profit");
			return;
		}
		else {
			$aStyleText= array(
					'font' => array('bold' => true),
					'alignment' => array('horizontal' => 'center',),
					'borders' => array(
							'top' => array( 'style' => 'thin' ),
							'left' => array( 'style' => 'thin' ),
							'right' => array( 'style' => 'thin' ),
							'bottom' => array( 'style' => 'thin' ),
					),
			);
	
			$oExcel= new Excel();
			$oExcel->ReadExcel7(SERVER_PATH."/imgbank/finance_profit_report_3.xlsx");
			$oExcel->SetActiveSheetIndex();
			$oExcel->GetActiveSheet();
	
			$aStyleNumber= $aStyleText;
			$aStyleNumber['numberformat'] = $oExcel->aStyleFormatNumber00['numberformat'];
			$aCenter= array(
					'alignment' => array('horizontal' => 'center',),
			);
			$aCenterNumber= array(
					'alignment' => array('horizontal' => 'center',),
					'numberformat' => $oExcel->aStyleFormatNumber00['numberformat']
			);
				
			$oExcel->SetCellValue('B2',"Отчет по валовой прибыли с ".$sDateFrom." по ".$sDateTo);
			
			$this->MakroHeaderSetProfit($oExcel);
			
			$i=8;
			foreach ($aDataSet as $aValue) {
				$oExcel->SetCellValueExplicit('B'.$i, $aValue['num_str'],'',$aCenter);
				$oExcel->SetCellValueExplicit('C'.$i, $aValue['post_date']);
				$oExcel->SetCellValueExplicit('D'.$i, $aValue['total_price'],'',$aCenterNumber,2,'n');
				$oExcel->SetCellValueExplicit('E'.$i, $aValue['total_real_price'],'',$aCenterNumber,2,'n');
				$oExcel->SetCellValueExplicit('F'.$i, $aValue['profit'],'',$aCenterNumber,2,'n');
				$i+=1;
			}
			$iSum4 = $aValue['account_amount'];
	
			$oExcel->SetCellValueExplicit('B'.$i,'Строк '.($i-8),'',$aStyleText);
			$oExcel->SetCellValueExplicit('C'.$i,'ВСЕГО','',$aStyleText);
			$oExcel->SetCellValueExplicit('D'.$i,$iSum1,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('E'.$i,$iSum2,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('F'.$i,$iSum3,'',$aStyleNumber,2,'n');
	
			//end
			$sFileName=uniqid().'.xlsx';
			$oExcel->WriterExcel7(SERVER_PATH.'/imgbank/temp_upload/'.$sFileName, true);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function ProfitShortAllClient($aData,$is_view_page=0) {
		$sDateFrom = Base::$aRequest['search']['date_from'];
		$sDateTo = Base::$aRequest['search']['date_to'];
		$sDateFromStart = date("Y-m-d H:i:s",strtotime($sDateFrom.'00:00:00'));
		$sDateToEnd = date("Y-m-d H:i:s",strtotime($sDateTo.'23:59:59'));
		
		$aDataSet = array();
		$i=1;
		$aDatesCart = array();
		foreach ($aData as $aValue) {
			$iSum1 += ($aValue['price']*$aValue['number']);
			
			$iPriceOriginal = $aValue['price_original'];
			if ($aValue['price_original_one_currency']!=0 &&
			$aValue['price_original_one_currency']!=$aValue['price_original'])
				$iPriceOriginal = $aValue['price_original_one_currency'];
				
			$iSum2 += ($iPriceOriginal*$aValue['number']);
		}
	
		$iSum3 = number_format($iSum1 - $iSum2,2,".","");
		$iSum1 = number_format($iSum1,2,".","");
		$iSum2 = number_format($iSum2,2,".","");

		$aDataSet[] = array(
			'num_str' => '1',
			'total_price' => $iSum1,
			'total_real_price' => $iSum2,
			'total_profit' => $iSum3
		);
		
		Base::$tpl->assign('iTotal','1');
		Base::$tpl->assign('total_price',$iSum1);
		Base::$tpl->assign('total_real_price',$iSum2);
		Base::$tpl->assign('total_profit',$iSum3);
		$aDataSet = array_values($aDataSet);
	
		if ($is_view_page) {
			$oTable=new Table();
			$oTable->iRowPerPage=1000;
			$oTable->aDataFoTable = $aDataSet;
			$oTable->sType='array';
			$oTable->aColumn=array(
					'num_str'=>array('sTitle'=>'num_str'),
					'total_price'=>array('sTitle'=>'price_profit'),
					'total_real_price'=>array('sTitle'=>'real_price_profit'),
					'total_profit'=>array('sTitle'=>'profit'),
			);
			$oTable->sDataTemplate='finance/row_finance_profit_1.tpl';
			$oTable->sSubtotalTemplate='finance/subtotal_finance_profit_1.tpl';
			Base::$sText.=$oTable->getTable("Profit");
			return;
		}
		else {
			$aStyleText= array(
					'font' => array('bold' => true),
					'alignment' => array('horizontal' => 'center',),
					'borders' => array(
							'top' => array( 'style' => 'thin' ),
							'left' => array( 'style' => 'thin' ),
							'right' => array( 'style' => 'thin' ),
							'bottom' => array( 'style' => 'thin' ),
					),
			);
	
			$oExcel= new Excel();
			$oExcel->ReadExcel7(SERVER_PATH."/imgbank/finance_profit_report_1.xlsx");
			$oExcel->SetActiveSheetIndex();
			$oExcel->GetActiveSheet();
	
			$aStyleNumber= $aStyleText;
			$aStyleNumber['numberformat'] = $oExcel->aStyleFormatNumber00['numberformat'];
			$aCenter= array(
					'alignment' => array('horizontal' => 'center',),
			);
			$aCenterNumber= array(
					'alignment' => array('horizontal' => 'center',),
					'numberformat' => $oExcel->aStyleFormatNumber00['numberformat']
			);
	
			$oExcel->SetCellValue('B2',"Отчет по валовой прибыли с ".$sDateFrom." по ".$sDateTo);
			
			$this->MakroHeaderSetProfit($oExcel);
				
			$i=8;
			foreach ($aDataSet as $aValue) {
				$oExcel->SetCellValueExplicit('B'.$i, $aValue['num_str'],'',$aCenter);
				$oExcel->SetCellValueExplicit('C'.$i, $aValue['total_price'],'',$aCenterNumber,2,'n');
				$oExcel->SetCellValueExplicit('D'.$i, $aValue['total_real_price'],'',$aCenterNumber,2,'n');
				$oExcel->SetCellValueExplicit('E'.$i, $aValue['total_profit'],'',$aCenterNumber,2,'n');
				$i+=1;
			}
	
			$oExcel->SetCellValueExplicit('B'.$i,'Строк '.($i-8),'',$aStyleText);
			$oExcel->SetCellValueExplicit('C'.$i,$iSum1,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('D'.$i,$iSum2,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('E'.$i,$iSum3,'',$aStyleNumber,2,'n');
	
			//end
			$sFileName=uniqid().'.xlsx';
			$oExcel->WriterExcel7(SERVER_PATH.'/imgbank/temp_upload/'.$sFileName, true);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function ProfitDetailAllClient($aData,$is_view_page=0) {
		$sDateFrom = Base::$aRequest['search']['date_from'];
		$sDateTo = Base::$aRequest['search']['date_to'];
		$sDateFromStart = date("Y-m-d H:i:s",strtotime($sDateFrom.'00:00:00'));
		$sDateToEnd = date("Y-m-d H:i:s",strtotime($sDateTo.'23:59:59'));

		$aDataSet = array();
		$i=1;
		$aDatesCart = array();
		foreach ($aData as $aValue) {
			$sDate = date("d-m-Y",strtotime($aValue['cl_post_date']));
			$sDateStart = date("Y-m-d 00:00:00",strtotime($aValue['cl_post_date']));
			$sDateEnd = date("Y-m-d 23:59:59",strtotime($aValue['cl_post_date']));
			
			$iPriceOriginal = $aValue['price_original'];
			if ($aValue['price_original_one_currency']!=0 &&
			$aValue['price_original_one_currency']!=$aValue['price_original'])
				$iPriceOriginal = $aValue['price_original_one_currency'];
			
			$iSum1 += ($aValue['price']*$aValue['number']);
			$iSum2 += ($iPriceOriginal*$aValue['number']);
			$sKeyData = $sDate.'_'.$aValue['login'].$aValue['id_cart_package'].$aValue['code'];
			if ($aDataSet[$sKeyData]) {
				$aDataSet[$sKeyData]['total_price'] += ($aValue['price']*$aValue['number']);
				$aDataSet[$sKeyData]['total_real_price'] += ($iPriceOriginal*$aValue['number']);
			}
			else {	
				$aDataSet[$sKeyData] = array(
					'post_date' => $sDate,
					'login' => $aValue['login'].' - '.$aValue['name_customer'],
					'code' => $aValue['code'],
					'id_cart_package' => $aValue['id_cart_package'],
					'total_price' => ($aValue['price']*$aValue['number']),
					'total_real_price' => ($iPriceOriginal*$aValue['number']),
				);
				$i+=1;
			}
		}
		foreach ($aDataSet as $sDate => $aValue) {
			$aDataSet[$sDate]['profit'] = number_format($aValue['total_price']-$aValue['total_real_price'],2,".","");
			$aDataSet[$sDate]['total_price'] = number_format($aValue['total_price'],2,".","");
			$aDataSet[$sDate]['total_real_price'] = number_format($aValue['total_real_price'],2,".","");
			$aTmp[] = $aDataSet[$sDate];
			$aTmpSort[] = $aDataSet[$sDate]['id_cart_package'] + strtotime(date("d.m.Y 00:00:00",strtotime($aDataSet[$sDate]['post_date'])));
		}
		array_multisort ($aTmpSort, SORT_ASC, SORT_NUMERIC, $aTmp);
		$aDataSet = $aTmp;
		$i=1;
		foreach ($aDataSet as $sDate => $aValue) {
			$aDataSet[$sDate]['num_str'] = $i;
			$i+=1;
		}
		//Debug::PrintPre($aDataSet);
		$iSum3 = number_format($iSum1 - $iSum2,2,".","");
		$iSum1 = number_format($iSum1,2,".","");
		$iSum2 = number_format($iSum2,2,".","");
	
		Base::$tpl->assign('iTotal',($i-1));
		Base::$tpl->assign('total_price',$iSum1);
		Base::$tpl->assign('total_real_price',$iSum2);
		Base::$tpl->assign('total_profit',$iSum3);
		$aDataSet = array_values($aDataSet);
	
		if ($is_view_page) {
			$oTable=new Table();
			$oTable->iRowPerPage=100;
			$oTable->aDataFoTable = $aDataSet;
			$oTable->sType='array';
			$oTable->aColumn=array(
				'num_str'=>array('sTitle'=>'num_str'),
				'login'=>array('sTitle'=>'login_customer'),
				'code'=>array('sTitle'=>'cartcode'),
				'id_cart_package'=>array('sTitle'=>'id cart package'),
				'post_date'=>array('sTitle'=>'post_date'),
				'total_price'=>array('sTitle'=>'price_profit'),
				'total_real_price'=>array('sTitle'=>'real_price_profit'),
				'profit'=>array('sTitle'=>'profit'),
			);
			$oTable->sDataTemplate='finance/row_finance_profit_2.tpl';
			$oTable->sSubtotalTemplate='finance/subtotal_finance_profit_2.tpl';
			Base::$sText.=$oTable->getTable("Profit");
			return;
		}
		else {
			$aStyleText= array(
					'font' => array('bold' => true),
					'alignment' => array('horizontal' => 'center',),
					'borders' => array(
							'top' => array( 'style' => 'thin' ),
							'left' => array( 'style' => 'thin' ),
							'right' => array( 'style' => 'thin' ),
							'bottom' => array( 'style' => 'thin' ),
					),
			);
	
			$oExcel= new Excel();
			$oExcel->ReadExcel7(SERVER_PATH."/imgbank/finance_profit_report_2.xlsx");
			$oExcel->SetActiveSheetIndex();
			$oExcel->GetActiveSheet();
	
			$aStyleNumber= $aStyleText;
			$aStyleNumber['numberformat'] = $oExcel->aStyleFormatNumber00['numberformat'];
			$aCenter= array(
					'alignment' => array('horizontal' => 'center',),
			);
			$aCenterNumber= array(
					'alignment' => array('horizontal' => 'center',),
					'numberformat' => $oExcel->aStyleFormatNumber00['numberformat']
			);
				
			$oExcel->SetCellValue('B2',"Отчет по валовой прибыли с ".$sDateFrom." по ".$sDateTo);
			
			$this->MakroHeaderSetProfit($oExcel);
			
			$i=8;
			foreach ($aDataSet as $aValue) {
				$oExcel->SetCellValueExplicit('B'.$i, $aValue['num_str'],'',$aCenter);
				$oExcel->SetCellValueExplicit('C'.$i, $aValue['login']);
				$oExcel->SetCellValueExplicit('D'.$i, $aValue['code']);
				$oExcel->SetCellValueExplicit('E'.$i, $aValue['id_cart_package']);
				$oExcel->SetCellValueExplicit('F'.$i, $aValue['post_date']);
				$oExcel->SetCellValueExplicit('G'.$i, $aValue['total_price'],'',$aCenterNumber,2,'n');
				$oExcel->SetCellValueExplicit('H'.$i, $aValue['total_real_price'],'',$aCenterNumber,2,'n');
				$oExcel->SetCellValueExplicit('I'.$i, $aValue['profit'],'',$aCenterNumber,2,'n');
				$i+=1;
			}
	
			$oExcel->SetCellValueExplicit('B'.$i,'Строк '.($i-8),'',$aStyleText);
			$oExcel->SetCellValueExplicit('C'.$i,'','',$aStyleText);
			$oExcel->SetCellValueExplicit('D'.$i,'','',$aStyleText);
			$oExcel->SetCellValueExplicit('E'.$i,'','',$aStyleText);
			$oExcel->SetCellValueExplicit('F'.$i,'ВСЕГО','',$aStyleText);
			$oExcel->SetCellValueExplicit('G'.$i,$iSum1,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('H'.$i,$iSum2,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('I'.$i,$iSum3,'',$aStyleNumber,2,'n');
	
			//end
			$sFileName=uniqid().'.xlsx';
			$oExcel->WriterExcel7(SERVER_PATH.'/imgbank/temp_upload/'.$sFileName, true);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function MakroHeaderSetProfit(&$oExcel) {
		if (Base::$aRequest['select_search_customer']) {
			$aCustomer=Db::GetRow(Base::GetSql('Customer',array(
					'login'=>Base::$aRequest['select_search_customer'],
			)));
			$sNameCustomer = $aCustomer['name_customer'];
		}
		if (Base::$aRequest['select_search_manager']) {
			$aManager = Db::GetRow(Base::GetSql('Manager',array(
					'login'=>Base::$aRequest['select_search_manager'],
			)));
			$sNameManager = $aManager['name'] . '('.$aManager['login'].')';
		}	
		
		if ($sNameCustomer)
			$oExcel->SetCellValue('C5',$sNameCustomer);
		if ($sNameManager)
			$oExcel->SetCellValue('C4',$sNameManager);
		
	}
	//-----------------------------------------------------------------------------------------------
	public function CorrectBalanceProvider() {
		if (Base::$aRequest['is_post'])
		{
			$a=0;
			if (!Base::$aRequest['data']['amount'] || !Base::$aRequest['data']['id_provider'] || !Base::$aRequest['data']['pay_type']) {
				$sError="Please, fill the required fields";
				Base::$tpl->assign('aData',$aData=Base::$aRequest['data']);
			}
			else {
				if (Base::$aRequest['data']['pay_type']=='debt_provider')
					Base::$aRequest['data']['amount'] = '-'.abs(Base::$aRequest['data']['amount']);
	
				$sComment = Language::getMessage("correct balance").' ('.Language::getMessage(Base::$aRequest['data']['pay_type']).') '.Base::$aRequest['data']['comment'];
				Finance::Deposit(Base::$aRequest['data']['id_provider'],
				Base::$aRequest['data']['amount'],
				mysql_real_escape_string($sComment),'',
				'interval',Base::$aRequest['data']['pay_type'],0,0,0,
				'',0,0,true,0,'',
				Base::$aRequest['data']['post_date']
				);
				Form::RedirectAuto("&aMessage[MI_NOTICE]=balance corrected");
			}
		}
			
		$aPayType=array('' => Language::getMessage("not selected"),
				'debt_provider' => Language::getMessage("Debt provider"),
				"prepay_provider" => Language::getMessage("Prepay provider"));
		Base::$tpl->assign('aPayType', $aPayType);

		//$sDate = '2017-11-08 23:59:59';
		$sDate = date("Y-m-d H:i:s",strtotime(Language::getConstant('finance_provider:board_date','01.01.2017').' 00:00:00') - 1);
		$aField['login']=array('title'=>'correct balance to provider','type'=>'text','value'=>Base::$aRequest['login']);
		$aField['date']=array('title'=>'date','type'=>'text','value'=>$sDate);
		$aField['hr']=array('type'=>'hr','colspan'=>2);
		$aField['amount']=array('title'=>'Amount','type'=>'input','value'=>$aData['amount'],'name'=>'data[amount]','szir'=>1);
		$aField['pay_type']=array('title'=>'Pay Type','type'=>'select','options'=>$aPayType,'name'=>'data[pay_type]','szir'=>1);
		$aField['comment']=array('title'=>'Comment','type'=>'textarea','name'=>'data[comment]','value'=>$aData['comment']?$aData['comment']:Base::$aRequest['data']['comment']);
		$aField['id_user']=array('type'=>'hidden','name'=>'data[id_provider]','value'=>Base::$aRequest['id_provider']);
		$aField['post_date']=array('type'=>'hidden','name'=>'data[post_date]','value'=>$sDate);
		$aField['return']=array('type'=>'hidden','name'=>'data[return]','value'=>urlencode(Base::$aRequest['return']));
	
		$aData=array(
				'sHeader'=>"method=post",
				'sTitle'=>"Correct balance",
				'aField'=>$aField,
				'bType'=>'generate',
				'sSubmitButton'=>'Apply',
				'sSubmitAction'=>'finance_correct_balance',
				'sError'=>$sError,
		);
		$oForm=new Form($aData);
	
		Base::$sText.=$oForm->getForm();
	}
	//-----------------------------------------------------------------------------------------------
	public function DebtAmountGroup($iIdGroup){
		/*if ($iIdGroup) 
			$aUsers = Db::getAssoc("Select id_user as key_,id_user from user_provider_group where id_group=".$iIdGroup);
		if ($aUsers)
			return Base::$db->getOne("select sum(amount) from user_account_log 
				where id_user in (".implode(",",array_keys($aUsers)).")");
		
		return 0;*/
		return Finance::getDebtBeginProvider(0,'',$iIdGroup);
	}
	//-----------------------------------------------------------------------------------------------
	public function Provider()
	{
		/*if(Auth::$aUser['is_super_manager']||Auth::$aUser['all_customer_visible'])
			$sWhereManager = ' ';
		else
			$sWhereManager = " and uc.id_manager='".Auth::$aUser['id_user']."' ";
		 */
		if (Base::$aRequest['is_post']) {
			$bCheckManagerLogin=true;
			if (Auth::$aUser['type_']=='manager') {
				if (!Base::$aRequest['data']['login']) $bCheckManagerLogin=false;
				else {
					$aUser=Db::GetRow(Base::GetSql('Provider',array('id'=>Base::$aRequest['data']['id_provider'])));
					if ($aUser) $iIdUser=$aUser['id'];
					else $bCheckManagerLogin=false;
				}
			}
			if (!Base::$aRequest['data']['amount'] || !Base::$aRequest['data']['code_account'] || !Base::$aRequest['data']['id_cart_package'] || !$bCheckManagerLogin) {
				$sError=Language::GetMessage("Please, fill amount, id_account, login fields");
				if(!Base::$aRequest['data']['id_cart_package']) {
					$sError.=" ".Language::GetMessage("fill order id field");
				}
				Base::$aRequest['action']='finance_bill_provider_add';
				Base::$tpl->assign('aData',$aData=Base::$aRequest['data']);
			}
			else {
				// check cart package
				if (Base::$aRequest['data']['id_cart_package']) {
					$aCart = Db::getRow("Select * from cart where id=".Base::$aRequest['data']['id_cart']);
					if (!$aCart || $aCart['id_provider']!=$iIdUser)
						$sError.=" ".Language::GetMessage("incorrect order id field");
				}
				$iExist = Db::getOne("Select id from bill_provider where 
					id_user = ".$iIdUser." and id_cart=".Base::$aRequest['data']['id_cart'].
					" and amount='".Base::$aRequest['data']['amount'].
					"' and code_template='".Base::$aRequest['data']['code_template'].
					"' and code_account='".Base::$aRequest['data']['code_account']."'"); 
				if ($iExist)
					$sError.=" ".Language::GetMessage("this document already created");
				
				if (!$sError) {
					if (!Base::$aRequest['id']) {
						//[----- INSERT -----------------------------------------------------]
						$aBillProvider=String::FilterRequestData(Base::$aRequest['data']
								,array('code_template','amount','id_cart_package','id_account','comment','id_cart','code_account','id_provider'));
						if (!$aBillProvider['code_account']) {
							$sCodeAccount = Finance::AssignAccountProvider(1);
								
							$aBillProvider['code_account']=$sCodeAccount;
						}
	
						/*if (Auth::$aUser['type_']=='provider') 
							$aBillProvider['id_user']=Auth::$aUser['id'];
						else*/ 
							$aBillProvider['id_user']=$iIdUser;
						
						if (Auth::$aUser['type_']=='manager')
							$aBillProvider['id_user_manager']=Auth::$aUser['id'];
						
						$aBillProvider['post_date'] = date("Y-m-d H:i:s",
							strtotime(Base::$aRequest['post_date']." ".
								Base::$aRequest['data']['time'].':59'));
						
						Db::AutoExecute('bill_provider',$aBillProvider);
						$iIdBill = Db::InsertId();
						//$aBillProvider['account_name'] = Db::getOne("Select name from account where id=".$aBill['id_account']);
						//$aBillProvider['post_date_day'] = date("d-m-Y",strtotime($aBillProvider['post_date']));
	
						if($aBillProvider['id_user']) {
							// возврат поставщику - ПКО
							/*if ($aBillProvider['code_account']=='return_provider') {
								$sDecription = Language::getMessage('return_provider').' '.$aBillProvider['id_cart'];
								Finance::Deposit($aBillProvider['id_user'],'-'.abs($aBillProvider['amount']),$sDecription,
								$aBillProvider['id_cart_package'],'interval'
								,'return_provider',0,0,0,'',0,0,false,0,'','',$iIdBill,$aBillProvider['id_cart']);
							}
							else {*/
								$sOperation = $aBillProvider['code_account'];								
								$aOperation = Db::GetRow("Select * from user_account_type_operation where code='".$sOperation."'");
	
								Finance::Deposit($aBillProvider['id_user'],$aBillProvider['amount'],$aOperation['name'],
								Base::$aRequest['data']['id_cart_package'],'interval','',
								0,0,0,$aOperation['code'],0,0,false,0,$aBillProvider['comment'],$aBillProvider['post_date'],$iIdBill,$aBillProvider['id_cart']);
							//}						
						}
						//[----- END INSERT -------------------------------------------------]
					} else {
						//[----- UPDATE -----------------------------------------------------]			
						$aCurrentData = Db::getRow("Select * from bill_provider where id='".Base::$aRequest['id']."'");
						// check link ual
						$aUal = Db::getRow("Select l.* from user_account_log l
							inner join user u on u.id = l.id_user and u.type_='provider' 
							where l.id_bill=".Base::$aRequest['id']);
						if ($aCurrentData && $aUal) {
							$sPostDate = Base::$aRequest['post_date'].' 00:00:00';
							$sLastTime = date("Y-m-d 23:59:59",strtotime(Base::$aRequest['post_date']));
							$sMax = Db::getOne("Select max(post_date) from user_account_log where
								post_date>'".$sPostDate."' and post_date<'".$sLastTime."' and id_user=".$aCurrentData['id_user']);
							if ($sMax)
								$sPostDate = date("Y-m-d H:i:s",strtotime($sMax)+1);
								
							$sQuery="update bill_provider set post_date='".date("Y.m.d H:i:s",strtotime($sPostDate))."',
								comment='".mysql_real_escape_string(Base::$aRequest['data']['comment'])."',
								amount='".Base::$aRequest['data']['amount']."'
								".(Base::$aRequest['data']['id_account'] ? ",id_account='".Base::$aRequest['data']['id_account']."'":"")."
								".(Base::$aRequest['data']['id_cart_package'] ? ",id_cart_package='".Base::$aRequest['data']['id_cart_package']."'":"").
									"	where id='".Base::$aRequest['id']."'";
							Base::$db->Execute($sQuery);
							// ual
							$dAmount = Base::$aRequest['data']['amount'];
							if ($aUal['operation']=='back_pay_provider')
								$dAmount = '-'.abs(Base::$aRequest['data']['amount']);
								
							$sQuery="update user_account_log set post_date='".date("Y.m.d H:i:s",strtotime($sPostDate))."',
								comment='".mysql_real_escape_string(Base::$aRequest['data']['comment'])."',
								amount='".$dAmount."'
								".(Base::$aRequest['data']['id_account'] ? ",id_account='".Base::$aRequest['data']['id_account']."'":"")."
								".(Base::$aRequest['data']['id_cart_package'] ? ",custom_id='".Base::$aRequest['data']['id_cart_package']."'":"")."
                        		where id='".$aUal['id']."'";
							Base::$db->Execute($sQuery);
							// recalc balance
							$dCurrentBalance = $this->getDebtBeginProvider($aUal['id_user']);
							Db::Execute("Update user_account set amount='".$dCurrentBalance."' where id_user='".$aUal['id_user']."'");
							// provider
							$aGroup = Db::getRow("Select * from user_provider_group where id_user=".$aUal['id_user']);
							if ($aGroup) {
								//$dBalanceGroup = Finance::DebtAmountGroup($aGroup['id_group']);
								Db::Execute("update user_provider_group_main set amount=".$dCurrentBalance." where id='".$aGroup['id_group']."'");
							}
						}
						//[----- END UPDATE -------------------------------------------------]
					}
				}
			}
			if (!$sError) {
				if (Base::$aRequest['return_action'])
					Base::Redirect("/?action=".Base::$aRequest['return_action']);
				Base::Redirect("/?action=finance_bill_provider");
			}
		}
	
		if ($sError && Base::$aRequest['return_action']) {
			Base::$aRequest['action'] = 'finance_bill_provider_add';
			if (Base::$aRequest['id'])
				Base::$aRequest['action'] = 'finance_bill_provider_edit';
		}
	
		Base::$tpl->assign('aNameUser',$aNameUser=array(0 =>'')+Db::GetAssoc("select u.login, concat(up.name,' ( ',u.login,' )',
				IF(up.phone is null or up.phone='','',concat(' ".
					Language::getMessage('tel.')." ',up.phone))) name
		from user as u
		inner join user_provider as up on u.id=up.id_user
		where u.visible=1 and up.name is not null and trim(up.name)!=''
		order by up.name"));
	
		if (Base::$aRequest['data']['id_provider']) {
			$aProvider=Db::GetRow(Base::GetSql('Provider',array(
					'id'=>Base::$aRequest['data']['id_provider'],
			)));
			if (!$aProvider)
				Base::Redirect("/pages/manager_order");
		}
		
		if (Base::$aRequest['action']=='finance_bill_provider_add' || Base::$aRequest['action']=='finance_bill_provider_edit') {
			if (Base::$aRequest['action']=='finance_bill_provider_edit') {
				$aBillProvider=Db::GetRow(Base::GetSql('BillProvider',array('id'=>Base::$aRequest['id'])));
				Base::$tpl->assign('aData',$aData=$aBillProvider);
				Base::$aRequest['code_template'] = $aBillProvider['code_template'];
			}
						
			if (Base::$aRequest['data']['amount']) Base::$tpl->assign('aData',$aData=Base::$aRequest['data']);
	
			if (!Base::$aRequest['code_template'] || Base::$aRequest['code_template']=='simple_bill') 
				$sCodeTemplate='simple_bill';
			else $sCodeTemplate=Base::$aRequest['code_template'];
	
			Base::$tpl->assign('sCodeTemplate',$sCodeTemplate);
			$aAccount=Finance::AssignAccountProvider();
				
			$sReturnAction = 'finance_bill_provider';
			if (Base::$aRequest['return_action'])
				$sReturnAction = Base::$aRequest['return_action'];
	
			$aField['id_cart']=array('type'=>'hidden','name'=>'data[id_cart]','value'=>$aData['id_cart']);
			$aField['id_cart_package']=array('type'=>'hidden', 'value'=>$aData['id_cart_package'],'name'=>'data[id_cart_package]');
			$aField['post_date']=array('title'=>'Date','type'=>'date','value'=>Base::$aRequest['post_date']?Base::$aRequest['post_date']:date("d.m.Y"),
					'name'=>'post_date','id'=>'date','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
			$aField['id_cart_package_view']=array('title'=>'Id cart package','type'=>'text', 'readonly'=>'1', 'value'=>$aData['id_cart_package'],'szir'=>1);

			if(Auth::$aUser['type_']=='manager') {
				if ($aBillProvider) { 
					$aField['name_provider']=array('title'=>'Provider', 'type' => 'text', 'value'=>$aBillProvider['name']. ' ('.$aBillProvider['login'].')','name'=>'data[login]','szir'=>1);
					$aField['login']=array('type'=>'hidden','name'=>'data[login]','value'=>$aBillProvider['login']);
					$aField['id_provider']=array('type'=>'hidden','name'=>'data[id_provider]','value'=>$aBillProvider['id_user']);
				}
				elseif ($aProvider) {
					$aField['name_provider']=array('title'=>'Provider', 'type' => 'text', 'value'=>$aProvider['name']. ' ('.$aProvider['login'].')','name'=>'data[login]','szir'=>1);
					$aField['login']=array('type'=>'hidden','name'=>'data[login]','value'=>$aProvider['login']);
					$aField['id_provider']=array('type'=>'hidden','name'=>'data[id_provider]','value'=>$aProvider['id_user']);
				}				
				else {
					//$aField['login']=array('title'=>'Provider','readonly'=>0, 'type'=>'input','value'=>'','name'=>'data[login]','szir'=>1);
					$aField['login']=array('title'=>'Provider','type'=>'select','options'=>$aNameUser,'selected'=>'','name'=>'data[login]','szir'=>1,'id'=>'select_name_user');
				}
			}
			$aField['id_account']=array('title'=>'Account','type'=>'select','options'=>$aAccount,'selected'=>$aData['code_account'],'name'=>'data[code_account]','szir'=>1);
			$aField['amount']=array('title'=>'Amount','type'=>'input','value'=>$aData['amount']?$aData['amount']:Base::$aRequest['amount'],'name'=>'data[amount]','szir'=>1);
			$aField['code_template']=array('type'=>'hidden','name'=>'data[code_template]','value'=>$sCodeTemplate);
			$aField['comment']=array('title'=>'Comment','type'=>'textarea','name'=>'data[comment]','value'=>$aData['comment']?$aData['comment']:Base::$aRequest['data']['comment']);
			$aField['return_action']=array('type'=>'hidden','name'=>'return_action','value'=>$sReturnAction);
				
			$aData=array(
					'sHeader'=>"method=post",
					'sTitle'=> $sCodeTemplate,
					//'sContent'=>Base::$tpl->fetch('finance/form_bill.tpl'),
					'aField'=>$aField,
					'bType'=>'generate',
					'sSubmitButton'=>'Apply',
					'sSubmitAction'=>'finance_bill_provider',
					'sReturnButton'=>'<< Return',
					'sReturnAction'=>$sReturnAction,
					'sError'=>$sError,
			);
			$oForm=new Form($aData);
	
			Base::$sText.=Language::GetText('finance bill add desctiption');
			Base::$sText.=$oForm->getForm();
			return;
		}
	
		if (Base::$aRequest['action']=='finance_bill_provider_delete') {
			// check link ual
			$aUal = Db::getRow("Select * from user_account_log ual
				inner join user u on u.id = ual.id_user
				where id_bill=".Base::$aRequest['id']." and u.type_='provider'");
			if ($aUal) {
				Base::$db->Execute("delete from bill_provider where id='".Base::$aRequest['id']."'");
				Base::$db->Execute("delete from user_account_log where id='".$aUal['id']."'");
				// recalc balance
				$dCurrentBalance = $this->getDebtBeginProvider($aUal['id_user']);
				Db::Execute("Update user_account set amount='".$dCurrentBalance."' where id_user='".$aUal['id_user']."'");
				$aGroup = Db::getRow("Select * from user_provider_group where id_user=".$aUal['id_user']);
				if ($aGroup) {
					$dBalanceGroup = Finance::DebtAmountGroup($aGroup['id_group']);
					Db::Execute("update user_provider_group_main set amount=".$dBalanceGroup." where id='".$aGroup['id_group']."'");
				}
			}
			if (Base::$aRequest['return_action'])
				Base::Redirect("/?action=".Base::$aRequest['return_action']);
			Base::Redirect("/?action=finance_bill");
		}
		Resource::Get()->Add('/js/jquery.searchabledropdown-1.0.8.min.js',1);
		Resource::Get()->Add('/js/select2.min.js',1);
		Resource::Get()->Add('/css/select2.min.css');
	
		$aTemplate=array(
				''=>Language::GetMessage('All'),
				'simple_bill'=>Language::GetMessage('simple_bill'),
				'order_bill'=>Language::GetMessage('order_bill'),
		);
	
	
		Base::$tpl->assign('aNameUser',$aNameUser=array(0 =>'')+Db::GetAssoc("select u.login, concat(ifnull(uc.name,''),' ( ',u.login,' )',
				IF(uc.phone is null or uc.phone='','',concat(' ".
					Language::getMessage('tel.')." ',uc.phone))) name
		from user as u
		inner join user_customer as uc on u.id=uc.id_user
		where u.visible=1 /*and uc.name is not null and trim(uc.name)!=''*/
		".$sWhereManager."
		order by uc.name"));
		Resource::Get()->Add('/js/select_search.js');
	
		if(Auth::$aUser['typle_']=='manager') $aField['search_login']=array('title'=>'Login','type'=>'select','options'=>$aNameUser,'selected'=>Base::$aRequest['search_login'],'name'=>'search_login','class'=>'select_search');
		$aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("1.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
		$aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		if(Auth::$aUser['typle_']=='manager') $aField['fio']=array('title'=>'Fio','type'=>'input','value'=>Base::$aRequest['search']['fio'],'name'=>'search[fio]');
		$aField['amount_from']=array('title'=>'amFrom','type'=>'input','value'=>Base::$aRequest['search']['amount_from'],'name'=>'search[amount_from]','checkbox'=>1);
		$aField['amount_to']=array('title'=>'amTo','type'=>'input','value'=>Base::$aRequest['search']['amount_to'],'name'=>'search[amount_to]');
		$aField['template']=array('title'=>'Template','type'=>'select','options'=>$aTemplate,'selected'=>Base::$aRequest['search']['template'],'name'=>'search[template]');
		$aField['id_cart_package']=array('title'=>'cartpackage #','type'=>'input','value'=>Base::$aRequest['search']['id_cart_package'],'name'=>'search[id_cart_package]');
		$aField['id']=array('title'=>'id','type'=>'input','value'=>Base::$aRequest['search']['id'],'name'=>'search[id]');
		$aField['search_login']=array('title'=>'Login_','type'=>'select','options'=>$aNameUser,'name'=>'search_login','selected'=>Base::$aRequest['search_login'],'class'=>'select_search');
		$aData=array(
				'sHeader'=>"method=get",
				//'sContent'=>Base::$tpl->fetch('finance/form_bill_search.tpl'),
				'aField'=>$aField,
				'bType'=>'generate',
				'sGenerateTpl'=>'form/index_search.tpl',
				'sSubmitButton'=>'Search',
				'sSubmitAction'=>'finance_bill',
				'sReturnButton'=>'Clear',
				'bIsPost'=>0,
				'sWidth'=>'80%',
				'sError'=>$sError,
		);
		$oForm=new Form($aData);
	
		Base::$sText .= $oForm->getForm();
	
		$oTable=new Table();
		$sWhere="u.type_='provider'";
	
		$sWhere = str_replace('and id_user','and b.id_user', $sWhere);
	
		// --- search ---
		if (Base::$aRequest['search_login']) {
			$sWhere.=" and (u.login like '%".Base::$aRequest['search_login']."%'";
			$sWhere.=" || up.name like '%".Base::$aRequest['search_login']."%'";
			$sWhere.=" || up.phone like '%".Base::$aRequest['search_login']."%')";
		}
		if (Base::$aRequest['search']['fio']) $sWhere.=" and uc.name like '%".Base::$aRequest['search']['fio']."%'";
	
		if (Base::$aRequest['search']['template'])
			$sWhere.=" and b.code_template ='".Base::$aRequest['search']['template']."'";
		if (Base::$aRequest['search']['date']) {
			$sWhere.=" and (b.post_date >= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
	            and b.post_date <= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."') ";
		}
		if (Base::$aRequest['search']['amount']) {
			$sWhere.=" and (b.amount >= '".Base::$aRequest['search']['amount_from']."'
	            and b.amount <= '".Base::$aRequest['search']['amount_to']."') ";
		}
		if (Base::$aRequest['search']['id_cart_package'])
			$sWhere.=" and b.id_cart_package like '%".Base::$aRequest['search']['id_cart_package']."%'";
		if (Base::$aRequest['search']['id'])
			$sWhere.=" and b.id like '%".Base::$aRequest['search']['id']."%'";
	
		// --- search ---
		$oTable->sSql=Base::GetSql('Bill',array(
				"where"=>$sWhere,
		));
		$oTable->aColumn=array(
				'id_cart_package'=>array('sTitle'=>'cartpackage #'),
				'id'=>array('sTitle'=>'id'),
				'amount'=>array('sTitle'=>'Amount'),
				'template'=>array('sTitle'=>'Template'),
				'post'=>array('sTitle'=>'Date'),
				'action'=>array(),
		);
		$oTable->aOrdered="order by b.post_date desc";
		$oTable->sDataTemplate='finance/row_bill.tpl';
		$oTable->sButtonTemplate='finance/button_bill.tpl';
		$oTable->bCheckVisible=true;
		$oTable->sWidth='100%';
		Base::$sText.=$oTable->getTable("Customer Bills",'customer_bill');
	}
	//-----------------------------------------------------------------------------------------------
	public function AssignAccountProvider($iGetOne=0)
	{
		if (Base::$aRequest['code_template']=='order_bill')
			$aAccount=array('back_pay_provider' => Language::GetMessage('return_provider_code'));
		elseif (Base::$aRequest['code_template']=='order_bill_bv')
			$aAccount=array('pay_provider_bv' => Language::GetMessage('pay_provider_bv'), 
							'pay_provider_bv_prepay' => Language::GetMessage('pay_provider_bv_prepay'));
		elseif (Base::$aRequest['code_template']=='order_bill_rko')
			$aAccount=array('pay_provider_rko' => Language::GetMessage('pay_provider_rko'), 
							'pay_provider_rko_prepay' => Language::GetMessage('pay_provider_rko_prepay'));

		if ($iGetOne)
			return key($aAccount);
		
		$aAccount=array(0=>Language::GetMessage('Choose any account'))+$aAccount;
		Base::$tpl->assign('aAccount',$aAccount);
		return $aAccount;
	}
	//-----------------------------------------------------------------------------------------------
	public function getDataFinanceProvider() {
		// --- search ---
		if (Base::$aRequest['select_search_provider']) {
			$iIdGroup = Db::getOne("Select id_group 
				from user_provider_group upg
				inner join user u on u.id = upg.id_user
				where u.visible=1 and u.login='".Base::$aRequest['select_search_provider']."' and u.type_='provider'");
			if ($iIdGroup) {
				$aUsers = Db::getAssoc("Select id_user as key_,id_user
				from user_provider_group upg
				inner join user u on u.id = upg.id_user
				where /*u.visible=1 and*/ id_group='".$iIdGroup."' and u.type_='provider'");
				if ($aUsers)
					$sWhere.=" and u.id in (".implode(",",$aUsers).")";
				else 
					$sWhere.=" and u.login = '".Base::$aRequest['select_search_provider']."'";
			}
			else
				$sWhere.=" and u.login = '".Base::$aRequest['select_search_provider']."'";
		}
	
		//if (Base::$aRequest['search_date']) {
		$sWhere.=" and ual.post_date>='".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
					and ual.post_date<'".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."'";
		//}
		if (Base::$aRequest['search']['description']) {
			$sWhere.=" and ual.description like '%".Base::$aRequest['search']['description']."%'";
		}

		$sWhere.=" and u.type_='provider' ";
	
		if (!Base::$aRequest['is_post'])
			$aLogData = array();
		else {
			// --------------
			/*$ignore_visible = 0;
			if ($iIdGroup)*/
				$ignore_visible=1; // если выбраны все - не определить по группам
			
			Finance::AssignSubtotal($sWhere,$ignore_visible);
			// --------------
			$sSql = Base::GetSql('UserAccountLog',array(
				'where'=>$sWhere,
				'ignore_visible' => $ignore_visible,
			));
			$sSql.=" order by up.name,ual.id,ual.post_date";
			$aLogData=Db::GetAll($sSql);
		}
		 
		//$this->CallParseLog($aLogData);
		return $aLogData;
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseLogProvider(&$aItem)
	{
		if (!$aItem)
			return;
	
		$aCartPackageAssoc = array();
		$aCartIdAssoc = array();
		foreach($aItem as $sKey => $aValue) {
			$aCartPackageAssoc[$aValue['custom_id']] = $aValue['custom_id'];
			$aCartIdAssoc[$aValue['id_cart']] = $aValue['id_cart']; 
		}
		$aCartPackageAssocStatus = Db::getAssoc("Select id, order_status from cart_package where id in (".implode(",",$aCartPackageAssoc).")");
		$aCartIdAssocStatus = Db::getAssoc("Select id, order_status from cart where id in (".implode(",",$aCartIdAssoc).")");
		
		if ($aItem) foreach($aItem as $sKey => $aValue) {
			$sDokument = $this->getNameDocumentProvider($aValue);
			$aItem[$sKey]['document'] = $sDokument;
			if ($aCartPackageAssocStatus[$aValue['custom_id']])
				$aItem[$sKey]['package_status'] = $aCartPackageAssocStatus[$aValue['custom_id']];
			if ($aCartIdAssocStatus[$aValue['id_cart']])
				$aItem[$sKey]['cart_status'] = $aCartIdAssocStatus[$aValue['id_cart']];
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function getNameDocumentProvider($aValue) {
		if (Base::$aRequest['empty_orders'][$aValue['id_user']] && Base::$aRequest['empty_orders'][$aValue['id_user']][$aValue['id_cart_package']]) {
			return '';
		}
	
		$sDokument = '';
		if ($aValue['data']=='return_provider')
			$sDokument = 'Накладная возврата';
		elseif ($aValue['operation']=='pay_provider')
			$sDokument = 'Поступление детали на склад';
		elseif ($aValue['operation']=='pay_provider_rko' || $aValue['operation']=='pay_provider_rko_prepay')
			$sDokument = 'РКО № '.($aValue['id_bill']?$aValue['id_bill']:'');
		elseif ($aValue['operation']=='pay_provider_bv' || $aValue['operation']=='pay_provider_bv_prepay')
			$sDokument = 'БВ № '.($aValue['id_bill']?$aValue['id_bill']:'');
		elseif ($aValue['operation']=='back_pay_provider')
			$sDokument = 'ПКО № '.($aValue['id_bill']?$aValue['id_bill']:'');
		else
			$sDokument = 'не определен';
		
		return $sDokument;
	}
	//-----------------------------------------------------------------------------------------------
	public function ShortAllClientProvider($aData,$is_view_page=0) {
		$sDateFrom = Base::$aRequest['search']['date_from'];
		$sDateTo = Base::$aRequest['search']['date_to'];
		$sDateFromStart = date("Y-m-d H:i:s",strtotime($sDateFrom.'00:00:00'));
		$sDateToEnd = date("Y-m-d H:i:s",strtotime($sDateTo.'23:59:59'));
		
		$sWhere.=" and u.type_='provider' ";

		if (Base::$aRequest['select_search_provider']) {
			// group?
			$iIdGroup = Db::getOne("Select id_group
				from user_provider_group upg
				inner join user u on u.id = upg.id_user
				where u.visible=1 and u.login='".Base::$aRequest['select_search_provider']."' and u.type_='provider'");
			if ($iIdGroup) {
				$aUsersAllow = Db::getAssoc("Select id_user as key_,id_user
				from user_provider_group upg
				inner join user u on u.id = upg.id_user
				where /*u.visible=1 and*/ id_group='".$iIdGroup."' and u.type_='provider'");
			}
			else {
				$aProvider=Db::GetRow(Base::GetSql('Provider',array(
					'login'=>Base::$aRequest['select_search_provider'],
				)));
				if ($aProvider)
					$aUsersAllow = array($aProvider['id'] => $aProvider['id']);
			}
		}
		
		$aNameUser=Db::GetAll("select u.id, concat(ifnull(up.name,''),' ( ',u.login,' )',
				IF(up.phone is null or up.phone='','',concat(' ".
				Language::getMessage('tel.')." ',up.phone))) name, 
				m.amount as group_amount, upg.is_main, upg.id_group, u.visible
			from user as u
			inner join user_provider as up on u.id=up.id_user
			inner join user_account ua on ua.id_user=u.id
			left join user_provider_group upg on upg.id_user = u.id
			left join user_provider_group_main m on m.id = upg.id_group
			where 1=1 /*and u.visible=1*/ ".$sWhere." order by up.name");

		$aGroup=array();
		$aNameUserNew = array();
		$aUserGroup = array();
		foreach ($aNameUser as $iKey => $aValue) {
			// filter provider?
			if ($aUsersAllow && !$aUsersAllow[$aValue['id']]) 
				continue;
			
			if (!$aValue['id_group'])
				$aNameUserNew[] = $aValue;
			else {
				if (!$aGroup[$aValue['id_group']])
					$aGroup[$aValue['id_group']] = $aValue;
				elseif ($aValue['is_main'])
					$aGroup[$aValue['id_group']] = $aValue;
				$aUserGroup[$aValue['id']] = $aValue['id_group']; 
			}
		}
		if ($aGroup)
			$aNameUserNew = array_merge($aNameUserNew,$aGroup); 

		// sort
		foreach ($aNameUserNew as $aValue) {
			$aTmp[] = $aValue; 
			$aTmpSort[] = $aValue['name'];
		}
		array_multisort ($aTmpSort, SORT_ASC, SORT_STRING, $aTmp);
		$aNameUser = $aTmp;
		
		//Debug::PrintPre($aNameUser);
		$aUserAssoc = array();
		foreach ($aNameUser as $iKey => $aValue) {
			$iSum1 = $this->getDebtBeginProvider($aValue['id'],$sDateFromStart);
			$aUserAssoc[$aValue['id']] = array(
					'debt_amount' => $iSum1,
					'credit' => '0.00',
					'debet' => '0.00',
					'account_amount' => '0.00',
					'name_provider' => $aValue['name'],
					'visible' => $aValue['visible'],
			);
		}
		//Debug::PrintPre($aGroup);
		foreach ($aData as $aValue) {
			$sDokument = $this->getNameDocumentProvider($aValue);
			if (!$sDokument || !$aValue['id_cart_package'])
				continue;
				
			$this->RewriteCreditAmount($aValue,$credit,$debet);
			
			if (!$aUserAssoc[$aValue['id_user']]) {
				if ($aUserGroup[$aValue['id_user']] && $aGroup[$aUserGroup[$aValue['id_user']]]['id'])
					$aValue['id_user'] = $aGroup[$aUserGroup[$aValue['id_user']]]['id'];
				else
					continue; 
			}
			$aUserAssoc[$aValue['id_user']]['credit'] += $credit;
			$aUserAssoc[$aValue['id_user']]['debet'] += $debet;
		}
		foreach ($aUserAssoc as $iIdUser => $aValue) {
			$aUserAssoc[$iIdUser]['account_amount'] = number_format(round($aValue['debt_amount'] - abs($aValue['credit']) + $aValue['debet'],2),2,".","");
		}
		// page
		if ($is_view_page) {
			$aDataSet = array();
			$i=1;
			$iSum1=$iSum2=$iSum3=$iSum4=0;
			foreach ($aUserAssoc as $iIdUser => $aValue) {
				if ($aValue['visible']) {
					$iSum1 += $aValue['debt_amount'];
					$iSum2 += $aValue['credit'];
					$iSum3 += abs($aValue['debet']);
					$iSum4 += $aValue['account_amount'];
					$aDataSet[] = array(
						'num_str' => $i,
						'name_provider' => $aValue['name_provider'],
						'debt_amount' => number_format($aValue['debt_amount'],2,".",""),
						'credit' => number_format($aValue['credit'],2,".",""),
						'debet' => number_format(abs($aValue['debet']),2,".",""),
						'account_amount' => number_format($aValue['account_amount'],2,".",""),
					);
					$i+=1;
				}
			}
				
			$iSum1 = number_format($iSum1,2,".","");
			$iSum2 = number_format($iSum2,2,".","");
			$iSum3 = number_format($iSum3,2,".","");
			$iSum4 = number_format($iSum4,2,".","");
				
			Base::$tpl->assign('iTotal',($i-1));
			Base::$tpl->assign('total_debt_amount',$iSum1);
			Base::$tpl->assign('total_credit',$iSum2);
			Base::$tpl->assign('total_debet',$iSum3);
			Base::$tpl->assign('total_account_amount',$iSum4);
	
			$oTable=new Table();
			$oTable->iRowPerPage=100;
			$oTable->aDataFoTable = $aDataSet;
			$oTable->sType='array';
			$oTable->aColumn=array(
					'num_str'=>array('sTitle'=>'num_str'),
					'login'=>array('sTitle'=>'provider'),
					'debt_amount'=>array('sTitle'=>'DebtAmount'),
					'credit'=>array('sTitle'=>'finance credit'),
					'debet'=>array('sTitle'=>'finance debet'),
					'account_amount'=>array('sTitle'=>'AccountAmount'),
			);
			$oTable->sDataTemplate='finance/row_finance_provider_1.tpl';
			$oTable->sSubtotalTemplate='finance/subtotal_finance_1.tpl';
			Base::$sText.=$oTable->getTable("Account Log",'provider_account_log');
			return;
		}
		else {
			$aStyleText= array(
					'font' => array('bold' => true),
					'alignment' => array('horizontal' => 'center',),
					'borders' => array(
							'top' => array( 'style' => 'thin' ),
							'left' => array( 'style' => 'thin' ),
							'right' => array( 'style' => 'thin' ),
							'bottom' => array( 'style' => 'thin' ),
					),
			);
				
			$oExcel= new Excel();
			$oExcel->ReadExcel7(SERVER_PATH."/imgbank/finance_provider_report_1.xlsx");
			$oExcel->SetActiveSheetIndex();
			$oExcel->GetActiveSheet();
				
			$aStyleNumber= $aStyleText;
			$aStyleNumber['numberformat'] = $oExcel->aStyleFormatNumber00['numberformat'];
			$aCenter= array(
					'alignment' => array('horizontal' => 'center',),
			);
			$aCenterNumber= array(
					'alignment' => array('horizontal' => 'center',),
					'numberformat' => $oExcel->aStyleFormatNumber00['numberformat']
			);
	
			$this->MakroHeaderSetProvider($oExcel);
	
			$oExcel->SetCellValue('B4',"Взаиморасчеты с поставщиками за период с ".$sDateFrom." по ".$sDateTo);
	
			$i=9;
			$iSum1=$iSum2=$iSum3=$iSum4=0;
			foreach ($aUserAssoc as $iIdUser => $aValue) {	
				if ($aValue['visible']) {
					$oExcel->SetCellValueExplicit('B'.$i,$i-8,'',$aCenter);
					$oExcel->SetCellValueExplicit('C'.$i,$aValue['name_provider']);
					$oExcel->SetCellValueExplicit('D'.$i, $aValue['debt_amount'],'',$aCenterNumber,2,'n');
					$oExcel->SetCellValueExplicit('E'.$i, $aValue['credit'],'',$aCenterNumber,2,'n');
					$oExcel->SetCellValueExplicit('F'.$i, abs($aValue['debet']),'',$aCenterNumber,2,'n');
					$oExcel->SetCellValueExplicit('G'.$i, $aValue['account_amount'],'',$aCenterNumber,2,'n');
					$i+=1;
					$iSum1 += $aValue['debt_amount'];
					$iSum2 += $aValue['credit'];
					$iSum3 += abs($aValue['debet']);
					$iSum4 += $aValue['account_amount'];
				}
				$iSum1 += $aValue['debt_amount'];
				$iSum2 += $aValue['credit'];
				$iSum3 += abs($aValue['debet']);
				$iSum4 += $aValue['account_amount'];
			}
			$oExcel->SetCellValueExplicit('B'.$i,'Строк '.($i-9),'',$aStyleText);
			$oExcel->SetCellValueExplicit('C'.$i,'ВСЕГО','',$aStyleText);
			$oExcel->SetCellValueExplicit('D'.$i,$iSum1,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('E'.$i,$iSum2,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('F'.$i,$iSum3,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('G'.$i,$iSum4,'',$aStyleNumber,2,'n');
				
			//end
			$sFileName=uniqid().'.xlsx';
			$oExcel->WriterExcel7(SERVER_PATH.'/imgbank/temp_upload/'.$sFileName, true);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function getDebtBeginProvider($iIdUser,$sStartDateTime='',$iIdGroup=0) {
		if (!$iIdUser && !$iIdGroup)
			return 0;
		
		if ($iIdUser)
			$iIdGroup = Db::getOne("Select id_group from user_provider_group where id_user=".$iIdUser);

		if ($iIdGroup) {
			$aUsers = Db::getAssoc("Select id_user as key_,id_user from user_provider_group where id_group=".$iIdGroup);
			if ($aUsers) {
				$sWhereUser = " and ual.id_user in (".implode(",",array_keys($aUsers)).")";
				$sWhere = $sWhereUser;
				if ($sStartDateTime)
					 $sWhere .= " and ual.post_date<'".$sStartDateTime."'";
			}
		}
		else {
			$sWhereUser = " and ual.id_user=".$iIdUser;
			$sWhere = $sWhereUser;
			if ($sStartDateTime)
				$sWhere .= " and ual.post_date<'".$sStartDateTime."'";
		}
		
		$aCartPackagesEmpty = array();
		$aCartPackages = Db::getAssoc("select cp.id as key_, cp.id
			from user_account_log ual
			inner join cart_package cp on cp.id = ual.custom_id
			where 1=1 ".$sWhereUser." group by cp.id");
		if ($aCartPackages) {
			$aCartPackagesOk = Db::getAssoc("SELECT id_cart_package as key_, id_cart_package
				FROM `cart`	WHERE id_cart_package IN ( ".implode(',',array_keys($aCartPackages))." )
				AND order_status != 'refused'
				GROUP BY id_cart_package");
	
			$aCartPackagesEmpty = array_diff($aCartPackages, $aCartPackagesOk);
		}
	
		if ($aCartPackagesEmpty) {
			if ($aUsers)
				foreach ($aUsers as $iUserId) {
					Base::$aRequest['empty_orders'][$iUserId] = $aCartPackagesEmpty;
			}
			else
				Base::$aRequest['empty_orders'][$iIdUser] = $aCartPackagesEmpty;
		}
	
		if ($aCartPackagesOk)
			$sWhereAdd =" || ((ual.data!='prepay_provider' ||
				ual.data!='debt_provider') && cp.id in (".implode(',',array_keys($aCartPackagesOk)).")) ";
		elseif ($aCartPackagesEmpty) {
			$sWhereAdd = " || ((ual.data!='prepay_provider' || ual.data!='debt_provider') && cp.id not in (".implode(',',array_keys($aCartPackagesEmpty)).")) ";
		}
	
		$iSum = Db::getOne("select sum(amount)
			from user_account_log ual
			left join cart_package cp on cp.id = ual.custom_id
			where 1=1 and (ual.data='debt_provider'	|| ual.data='prepay_provider'".
				$sWhereAdd.")".$sWhere);
		if (!$iSum)
			$iSum = 0;
		return $iSum;
	}
	//-----------------------------------------------------------------------------------------------
	public function MakroHeaderSetProvider(&$oExcel) {	
		if (Base::$aRequest['select_search_provider']) {
			$aCustomer=Db::GetRow(Base::GetSql('Provider',array(
					'login'=>Base::$aRequest['select_search_provider'],
			)));
			if ($aCustomer)
				$oExcel->SetCellValue('C6',$aCustomer['name']." (".$aCustomer['login'].") тел. ".$aCustomer['phone']);
			else
				$oExcel->SetCellValue('C6',"не найден");
		}
		else
			$oExcel->SetCellValue('C6',"Все");
	}
	//-----------------------------------------------------------------------------------------------
	public function ShortAllClientDatesProvider($aData,$is_view_page=0) {
		$sDateFrom = Base::$aRequest['search']['date_from'];
		$sDateTo = Base::$aRequest['search']['date_to'];
		$sDateFromStart = date("Y-m-d H:i:s",strtotime($sDateFrom.'00:00:00'));
		$sDateToEnd = date("Y-m-d H:i:s",strtotime($sDateTo.'23:59:59'));
		
		$sWhere.=" and u.type_='provider' ";

		if (Base::$aRequest['select_search_provider']) {
			// group?
			$iIdGroup = Db::getOne("Select id_group
				from user_provider_group upg
				inner join user u on u.id = upg.id_user
				where u.visible=1 and u.login='".Base::$aRequest['select_search_provider']."' and u.type_='provider'");
			if ($iIdGroup) {
				$aUsersAllow = Db::getAssoc("Select id_user as key_,id_user
				from user_provider_group upg
				inner join user u on u.id = upg.id_user
				where /*u.visible=1 and*/ id_group='".$iIdGroup."' and u.type_='provider'");
			}
			else {
				$aProvider=Db::GetRow(Base::GetSql('Provider',array(
					'login'=>Base::$aRequest['select_search_provider'],
				)));
				if ($aProvider)
					$aUsersAllow = array($aProvider['id'] => $aProvider['id']);
			}
		}
		
		$aNameUser=Db::GetAll("select u.id, concat(ifnull(up.name,''),' ( ',u.login,' )',
				IF(up.phone is null or up.phone='','',concat(' ".
				Language::getMessage('tel.')." ',up.phone))) name, 
				m.amount as group_amount, upg.is_main, upg.id_group, u.visible
			from user as u
			inner join user_provider as up on u.id=up.id_user
			inner join user_account ua on ua.id_user=u.id
			left join user_provider_group upg on upg.id_user = u.id
			left join user_provider_group_main m on m.id = upg.id_group
			where 1=1 /*and u.visible=1*/ ".$sWhere." order by up.name");
		
		$aGroup=array();
		$aNameUserNew = array();
		$aUserGroup = array();
		foreach ($aNameUser as $iKey => $aValue) {
			// filter provider?
			if ($aUsersAllow && !$aUsersAllow[$aValue['id']]) 
				continue;
			
			if (!$aValue['id_group'])
				$aNameUserNew[] = $aValue;
			else {
				if (!$aGroup[$aValue['id_group']])
					$aGroup[$aValue['id_group']] = $aValue;
				elseif ($aValue['is_main'])
					$aGroup[$aValue['id_group']] = $aValue;
				
				$aUserGroup[$aValue['id']] = $aValue['id_group'];
			}
		}
		if ($aGroup)
			$aNameUserNew = array_merge($aNameUserNew,$aGroup); 

		// sort
		foreach ($aNameUserNew as $aValue) {
			$aTmp[] = $aValue; 
			$aTmpSort[] = $aValue['name'];
		}
		array_multisort ($aTmpSort, SORT_ASC, SORT_STRING, $aTmp);
		$aNameUser = $aTmp;
		
		//Debug::PrintPre($aRewriteUser);
		$aDataAssoc = array();
		foreach ($aNameUser as $iKey => $aValue) {
			$iSum1 = $this->getDebtBeginProvider($aValue['id'],$sDateFromStart);
			$aDataAssoc[$aValue['id']] = array(
				'start' => number_format($iSum1,2,".",""),
				'name' => $aValue['name'],
				'visible' => $aValue['visible'],
			);
		}

		// sort and check data
		$aTmp=$aTmpSort=array();
		if ($aData) {
			foreach ($aData as $aValue) {
				$sDokument = $this->getNameDocumentProvider($aValue);
				if (!$sDokument || !$aValue['id_cart_package'])
					continue;
				
				if (!$aDataAssoc[$aValue['id_user']]) {
					if ($aUserGroup[$aValue['id_user']] && $aGroup[$aUserGroup[$aValue['id_user']]]['id'])
						$aValue['id_user'] = $aGroup[$aUserGroup[$aValue['id_user']]]['id'];
					else
						continue;
				}
				
				$aTmp[]=$aValue;
				$aTmpSort[]=$aValue['id_user'] + strtotime($aValue['post_date']); 
			}
			array_multisort ($aTmpSort, SORT_ASC, SORT_NUMERIC, $aTmp);
			$aData = $aTmp;
		}
		
		//Debug::PrintPre($aData);
		if ($aData)
		foreach ($aData as $aValue) {
				
			$this->RewriteCreditAmount($aValue,$credit,$debet);
			
			if (!$aDataAssoc[$aValue['id_user']]) {
				if ($aUserGroup[$aValue['id_user']] && $aGroup[$aUserGroup[$aValue['id_user']]]['id'])
					$aValue['id_user'] = $aGroup[$aUserGroup[$aValue['id_user']]]['id'];
				else
					continue; 
			}
			$sKeyDokument = date("Y-m-d",strtotime($aValue['post_date'])); 
			
			if (!$aDataAssoc[$aValue['id_user']]['items'][$sKeyDokument]) {
				$aDataAssoc[$aValue['id_user']]['items'][$sKeyDokument] = $aValue;
				$aDataAssoc[$aValue['id_user']]['items'][$sKeyDokument]['post_date'] = date("d-m-Y",strtotime($aValue['post_date']));
				$aDataAssoc[$aValue['id_user']]['items'][$sKeyDokument]['credit'] = $credit;
				$aDataAssoc[$aValue['id_user']]['items'][$sKeyDokument]['debet'] = $debet;
			}
			else {
				// update diff fields
				$aDataAssoc[$aValue['id_user']]['items'][$sKeyDokument]['credit'] += $credit;
				$aDataAssoc[$aValue['id_user']]['items'][$sKeyDokument]['debet'] += $debet;
			}
		}
		//Debug::PrintPre($aUserAssoc);
		
		$aDataResult=array();$dStartAmount=0;$iSum2=$iSum3=0;
		foreach ($aDataAssoc as $sKey => $aValue) {
			if (!$aValue['visible']) continue;

			$aDataResult[$sKey] = $aValue;
			if ($aValue['items'])
			foreach($aValue['items'] as $sItemKey => $aItem) {
				if (!isset($aDataResult[$sKey]['current_start']))
					$aDataResult[$sKey]['current_start'] = $aDataResult[$sKey]['start'];
				else
					$aDataResult[$sKey]['current_start'] = $aDataResult[$sKey]['current_end'];
					
				$aDataResult[$sKey]['current_end'] = $aDataResult[$sKey]['current_start']
				- abs($aItem['credit']) + $aItem['debet'];
					
				$aDataResult[$sKey]['items'][$sItemKey]['account_amount'] = number_format($aDataResult[$sKey]['current_end'],2,".","");
				$aDataResult[$sKey]['items'][$sItemKey]['debt_amount'] = number_format($aDataResult[$sKey]['current_start'],2,".","");
			}
		}
		$aDataAssoc = $aDataResult;
		$iSum1=$iSum2=$iSum3=$iSum4=0;
		// page
		if ($is_view_page) {
			$j=1;
			foreach ($aDataResult as $sNameProvider => $aValue) {
				$dSumCredit=0;$dSumDebet=0;
				if ($aValue['items'])
					foreach ($aValue['items'] as $sDokument => $aItem) {
						$dSumCredit+=$aItem['credit'];
						$dSumDebet+=$aItem['debet'];
						//$dLastAccountAmout = $aItem['account_amount'];
						$iSum2 += $aItem['credit'];
						$iSum3 += $aItem['debet'];
						$aDataAssoc[$sNameProvider]['items'][$sDokument]['num_str'] = $j;
						$aDataAssoc[$sNameProvider]['items'][$sDokument]['credit'] = number_format($aItem['credit'],2,".","");
						$aDataAssoc[$sNameProvider]['items'][$sDokument]['debet'] = number_format($aItem['debet'],2,".","");
						$aDataAssoc[$sNameProvider]['items'][$sDokument]['account_amount'] = number_format($aItem['account_amount'],2,".","");
						$j+=1;
					}
					$iSum1 += $aValue['start'];
					$aDataAssoc[$sNameProvider]['credit'] = number_format($dSumCredit,2,".","");
					$aDataAssoc[$sNameProvider]['debet'] = number_format($dSumDebet,2,".","");
					$aDataAssoc[$sNameProvider]['end'] = number_format($aValue['start'] - abs($dSumCredit) + $dSumDebet,2,".","");
					// wtf ???
					if ($aDataAssoc[$sNameProvider]['end']=='-0.00')
					    $aDataAssoc[$sNameProvider]['end'] = '0.00';

					$iSum4 += $aDataAssoc[$sNameProvider]['end'];
			}
			$iSum1 = number_format($iSum1,2,".","");
			$iSum2 = number_format($iSum2,2,".","");
			$iSum3 = number_format($iSum3,2,".","");
			$iSum4 = number_format($iSum4,2,".","");
			
			Base::$tpl->assign('iTotal',($j-1));
			Base::$tpl->assign('total_debt_amount',$iSum1);
			Base::$tpl->assign('total_credit',$iSum2);
			Base::$tpl->assign('total_debet',$iSum3);
			Base::$tpl->assign('total_account_amount',$iSum4);
				
			$oTable=new Table();
			$oTable->iRowPerPage=1000;
			$oTable->aDataFoTable = array_values($aDataAssoc);
			$oTable->sType='array';
			$oTable->aColumn=array(
				'num_str'=>array('sTitle'=>'num_str'),
				'login'=>array('sTitle'=>'provider'),
				'post_date'=>array('sTitle'=>'post_date'),
				'debt_amount'=>array('sTitle'=>'DebtAmount'),
				'credit'=>array('sTitle'=>'finance credit'),
				'debet'=>array('sTitle'=>'finance debet'),
				'account_amount'=>array('sTitle'=>'AccountAmount'),
			);
			$oTable->sDataTemplate='finance/row_finance_provider_3.tpl';
			$oTable->sSubtotalTemplate='finance/subtotal_finance_provider_3.tpl';
			Base::$sText.=$oTable->getTable("Account Log",'provider_account_log');
			return;
		}
		else {
			$aStyleText= array(
					'font' => array('bold' => true),
					'alignment' => array('horizontal' => 'center',),
					'borders' => array(
							'top' => array( 'style' => 'thin' ),
							'left' => array( 'style' => 'thin' ),
							'right' => array( 'style' => 'thin' ),
							'bottom' => array( 'style' => 'thin' ),
					),
			);
				
			$oExcel= new Excel();
			$oExcel->ReadExcel7(SERVER_PATH."/imgbank/finance_provider_report_3.xlsx");
			$oExcel->SetActiveSheetIndex();
			$oExcel->GetActiveSheet();
				
						$aStyleNumber= $aStyleText;
			$aStyleUserFill = $aStyleText;
			unset($aStyleUserFill['borders']);
			$aStyleUserFill['fill'] = array( 
				'type' => 'solid',
				'startcolor' => array('argb' => 'e7e4e4'),
				'endcolor'   => array('argb' => '00000000')
			);
			$aStyleUserFillLeft = $aStyleUserFill;
			$aStyleUserFillLeft['alignment'] = array('horizontal' => 'left',);
			$aStyleNumber['numberformat'] = $oExcel->aStyleFormatNumber00['numberformat'];
			$aStyleUserFill['numberformat'] = $oExcel->aStyleFormatNumber00['numberformat'];
			$aCenter= array(
					'alignment' => array('horizontal' => 'center',),
			);
			$aCenterNumber= array(
					'alignment' => array('horizontal' => 'center',),
					'numberformat' => $oExcel->aStyleFormatNumber00['numberformat']
			);

			$this->MakroHeaderSetProvider($oExcel);
	
			$oExcel->SetCellValue('B4',"Взаиморасчеты с поставщиками за период с ".$sDateFrom." по ".$sDateTo);
	
			$i=9;$j=1;
			$iSum1=$iSum2=$iSum3=$iSum4=0;
			foreach ($aDataAssoc as $iIdProvider => $aValue) {
				$oExcel->SetCellValueExplicit('C'.$i,$aValue['name'],'',$aStyleUserFillLeft);
				$oExcel->SetCellValueExplicit('D'.$i,'','',$aStyleUserFillLeft);
				$oExcel->SetCellValueExplicit('E'.$i,$aValue['start'],'',$aStyleUserFill,2,'n');
				$iClientStart = $i;
				$i+=1;$dSumCredit=0;$dSumDebet=0;
				if ($aValue['items'])
				foreach ($aValue['items'] as $sDokument => $aItem) {
					$oExcel->SetCellValueExplicit('B'.$i,$j,'',$aCenter);
					$oExcel->SetCellValueExplicit('C'.$i, $aItem['document']);			
					$oExcel->SetCellValueExplicit('D'.$i, $aItem['post_date']);
					$oExcel->SetCellValueExplicit('E'.$i, $aItem['debt_amount'],'',$aCenterNumber,2,'n');
					$oExcel->SetCellValueExplicit('F'.$i, $aItem['credit'],'',$aCenterNumber,2,'n');
					$oExcel->SetCellValueExplicit('G'.$i, $aItem['debet'],'',$aCenterNumber,2,'n');
					$oExcel->SetCellValueExplicit('H'.$i, $aItem['account_amount'],'',$aCenterNumber,2,'n');
					$j+=1;$i+=1;$dSumCredit+=$aItem['credit'];$dSumDebet+=$aItem['debet'];
					$iSum2 += $aItem['credit'];
					$iSum3 += $aItem['debet'];
				}
				$oExcel->SetCellValueExplicit('F'.$iClientStart,$dSumCredit,'',$aStyleUserFill,2,'n');
				$oExcel->SetCellValueExplicit('G'.$iClientStart,$dSumDebet,'',$aStyleUserFill,2,'n');
				$dLastAccountAmount = $aValue['start'] - abs($dSumCredit) + $dSumDebet;
				$oExcel->SetCellValueExplicit('H'.$iClientStart,$dLastAccountAmount,'',$aStyleUserFill,2,'n');
				$iSum1 += $aValue['start'];
				$iSum4 += $dLastAccountAmount;				
			}
			$oExcel->SetCellValueExplicit('B'.$i,'Строк '.($j-1),'',$aStyleText);
			$oExcel->SetCellValueExplicit('C'.$i,'','',$aStyleText);
			$oExcel->SetCellValueExplicit('D'.$i,'ВСЕГО','',$aStyleText);
			$oExcel->SetCellValueExplicit('E'.$i,$iSum1,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('F'.$i,$iSum2,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('G'.$i,$iSum3,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('H'.$i,$iSum4,'',$aStyleNumber,2,'n');
				
			//end
			$sFileName=uniqid().'.xlsx';
			$oExcel->WriterExcel7(SERVER_PATH.'/imgbank/temp_upload/'.$sFileName, true);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function DetailAllClientProvider($aData,$is_view_page=0) {
		$sDateFrom = Base::$aRequest['search']['date_from'];
		$sDateTo = Base::$aRequest['search']['date_to'];
		$sDateFromStart = date("Y-m-d H:i:s",strtotime($sDateFrom.'00:00:00'));
		$sDateToEnd = date("Y-m-d H:i:s",strtotime($sDateTo.'23:59:59'));
	
		$sWhere.=" and u.type_='provider' ";
	
		if (Base::$aRequest['select_search_provider']) {
			// group?
			$iIdGroup = Db::getOne("Select id_group
				from user_provider_group upg
				inner join user u on u.id = upg.id_user
				where /*u.visible=1 and*/ u.login='".Base::$aRequest['select_search_provider']."' and u.type_='provider'");
			if ($iIdGroup) {
				$aUsersAllow = Db::getAssoc("Select id_user as key_,id_user
				from user_provider_group upg
				inner join user u on u.id = upg.id_user
				where /*u.visible=1 and*/ id_group='".$iIdGroup."' and u.type_='provider'");
			}
			else {
				$aProvider=Db::GetRow(Base::GetSql('Provider',array(
						'login'=>Base::$aRequest['select_search_provider'],
				)));
				if ($aProvider)
					$aUsersAllow = array($aProvider['id'] => $aProvider['id']);
			}
		}
	
		$aNameUser=Db::GetAll("select u.id, ifnull(up.name,concat('( ',u.login,' )')) name,
				m.amount as group_amount, upg.is_main, upg.id_group, u.visible
			from user as u
			inner join user_provider as up on u.id=up.id_user
			inner join user_account ua on ua.id_user=u.id
			left join user_provider_group upg on upg.id_user = u.id
			left join user_provider_group_main m on m.id = upg.id_group
			where 1=1 /*and u.visible=1*/ ".$sWhere." order by up.name");
	
		$aGroup=array();
		$aNameUserNew = array();
		$aUserGroup = array();
		foreach ($aNameUser as $iKey => $aValue) {
			// filter provider?
			if ($aUsersAllow && !$aUsersAllow[$aValue['id']])
				continue;
				
			if (!$aValue['id_group'])
				$aNameUserNew[] = $aValue;
			else {
				if (!$aGroup[$aValue['id_group']])
					$aGroup[$aValue['id_group']] = $aValue;
				elseif ($aValue['is_main'])
				$aGroup[$aValue['id_group']] = $aValue;
	
				$aUserGroup[$aValue['id']] = $aValue['id_group'];
			}
		}
		if ($aGroup)
			$aNameUserNew = array_merge($aNameUserNew,$aGroup);
	
		// sort
		foreach ($aNameUserNew as $aValue) {
			$aTmp[] = $aValue;
			$aTmpSort[] = $aValue['name'];
		}
		array_multisort ($aTmpSort, SORT_ASC, SORT_STRING, $aTmp);
		$aNameUser = $aTmp;
	
		//Debug::PrintPre($aRewriteUser);
		$aDataAssoc = array();
		foreach ($aNameUser as $iKey => $aValue) {
			$iSum1 = $this->getDebtBeginProvider($aValue['id'],$sDateFromStart);
			$aDataAssoc[$aValue['id']] = array(
					'start' => number_format($iSum1,2,".",""),
					'name' => $aValue['name'],
					'visible' => $aValue['visible']
			);
		}
	
		// sort and check data
		$aTmp=$aTmpSort=array();
		if ($aData) {
			foreach ($aData as $aValue) {
				$sDokument = $this->getNameDocumentProvider($aValue);
				if (!$sDokument || !$aValue['id_cart_package'])
					continue;
	
				if (!$aDataAssoc[$aValue['id_user']]) {
					if ($aUserGroup[$aValue['id_user']] && $aGroup[$aUserGroup[$aValue['id_user']]]['id'])
						$aValue['id_user'] = $aGroup[$aUserGroup[$aValue['id_user']]]['id'];
					else
						continue;
				}
				$aValue['document'] = $sDokument;
				$aTmp[]=$aValue;
				$aTmpSort[]=$aValue['id_user'] + strtotime($aValue['post_date']);
			}
			array_multisort ($aTmpSort, SORT_ASC, SORT_NUMERIC, $aTmp);
			$aData = $aTmp;
		}
	
		//Debug::PrintPre($aData);
		$sKeyDokument = 1;
		if ($aData)
		foreach ($aData as $aValue) {
	
			$this->RewriteCreditAmount($aValue,$credit,$debet);
				
			if (!$aDataAssoc[$aValue['id_user']]) {
				if ($aUserGroup[$aValue['id_user']] && $aGroup[$aUserGroup[$aValue['id_user']]]['id'])
					$aValue['id_user'] = $aGroup[$aUserGroup[$aValue['id_user']]]['id'];
				else
					continue;
			}
			//$sKeyDokument = date("Y-m-d",strtotime($aValue['post_date']));
				
			if (!$aDataAssoc[$aValue['id_user']]['items'][$sKeyDokument]) {
				$aDataAssoc[$aValue['id_user']]['items'][$sKeyDokument] = $aValue;
				$aDataAssoc[$aValue['id_user']]['items'][$sKeyDokument]['post_date'] = date("d-m-Y",strtotime($aValue['post_date']));
				$aDataAssoc[$aValue['id_user']]['items'][$sKeyDokument]['credit'] = $credit;
				$aDataAssoc[$aValue['id_user']]['items'][$sKeyDokument]['debet'] = $debet;
			}

			$sKeyDokument += 1;
		}
		//Debug::PrintPre($aUserAssoc);
	
		$aDataResult=array();$dStartAmount=0;$iSum2=$iSum3=0;
		foreach ($aDataAssoc as $sKey => $aValue) {
			if (!$aValue['visible']) continue;

			$aDataResult[$sKey] = $aValue;
			if ($aValue['items'])
			foreach($aValue['items'] as $sItemKey => $aItem) {
				if (!isset($aDataResult[$sKey]['current_start']))
					$aDataResult[$sKey]['current_start'] = $aDataResult[$sKey]['start'];
				else
					$aDataResult[$sKey]['current_start'] = $aDataResult[$sKey]['current_end'];
					
				$aDataResult[$sKey]['current_end'] = $aDataResult[$sKey]['current_start']
				- abs($aItem['credit']) + $aItem['debet'];
					
				$aDataResult[$sKey]['items'][$sItemKey]['account_amount'] = number_format($aDataResult[$sKey]['current_end'],2,".","");
				$aDataResult[$sKey]['items'][$sItemKey]['debt_amount'] = number_format($aDataResult[$sKey]['current_start'],2,".","");
			}
		}
		$aDataAssoc = $aDataResult;
		$iSum1=$iSum2=$iSum3=$iSum4=0;
		// page
		if ($is_view_page) {
			$j=1;
			foreach ($aDataResult as $sNameProvider => $aValue) {
				$dSumCredit=0;$dSumDebet=0;
				if ($aValue['items'])
				foreach ($aValue['items'] as $sDokument => $aItem) {
					$dSumCredit+=$aItem['credit'];
					$dSumDebet+=$aItem['debet'];
					//$dLastAccountAmout = $aItem['account_amount'];
					$iSum2 += $aItem['credit'];
					$iSum3 += $aItem['debet'];
					$aDataAssoc[$sNameProvider]['items'][$sDokument]['num_str'] = $j;
					$aDataAssoc[$sNameProvider]['items'][$sDokument]['credit'] = number_format($aItem['credit'],2,".","");
					$aDataAssoc[$sNameProvider]['items'][$sDokument]['debet'] = number_format($aItem['debet'],2,".","");
					$aDataAssoc[$sNameProvider]['items'][$sDokument]['account_amount'] = number_format($aItem['account_amount'],2,".","");
					$j+=1;
				}
				$iSum1 += $aValue['start'];
				$aDataAssoc[$sNameProvider]['credit'] = number_format($dSumCredit,2,".","");
				$aDataAssoc[$sNameProvider]['debet'] = number_format($dSumDebet,2,".","");
				$aDataAssoc[$sNameProvider]['end'] = number_format($aValue['start'] - abs($dSumCredit) + $dSumDebet,2,".","");
				// wtf ???
				if ($aDataAssoc[$sNameProvider]['end']=='-0.00')
				    $aDataAssoc[$sNameProvider]['end'] = '0.00';

				$iSum4 += $aDataAssoc[$sNameProvider]['end'];
			}
			$iSum1 = number_format($iSum1,2,".","");
			$iSum2 = number_format($iSum2,2,".","");
			$iSum3 = number_format($iSum3,2,".","");
			$iSum4 = number_format($iSum4,2,".","");
				
			Base::$tpl->assign('iTotal',($j-1));
			Base::$tpl->assign('total_debt_amount',$iSum1);
			Base::$tpl->assign('total_credit',$iSum2);
			Base::$tpl->assign('total_debet',$iSum3);
			Base::$tpl->assign('total_account_amount',$iSum4);
	
			$oTable=new Table();
			$oTable->iRowPerPage=1000;
			$oTable->aDataFoTable = array_values($aDataAssoc);
			$oTable->sType='array';
			$oTable->aColumn=array(
					'num_str'=>array('sTitle'=>'num_str'),
					'document'=>array('sTitle'=>'document_provider'),
					'code'=>array('sTitle'=>'code'),
					'id_cart_package'=>array('sTitle'=>'id_cart_package'),
					'name_provider'=>array('sTitle'=>'provider'),
					'post_date'=>array('sTitle'=>'post_date'),
					'debt_amount'=>array('sTitle'=>'DebtAmount'),
					'credit'=>array('sTitle'=>'finance credit'),
					'debet'=>array('sTitle'=>'finance debet'),
					'account_amount'=>array('sTitle'=>'AccountAmount'),
			);
			$oTable->sDataTemplate='finance/row_finance_provider_2.tpl';
			$oTable->sSubtotalTemplate='finance/subtotal_finance_provider_2.tpl';
			Base::$sText.=$oTable->getTable("Account Log",'provider_account_log');
			return;
		}
		else {
			$aStyleText= array(
					'font' => array('bold' => true),
					'alignment' => array('horizontal' => 'center',),
					'borders' => array(
							'top' => array( 'style' => 'thin' ),
							'left' => array( 'style' => 'thin' ),
							'right' => array( 'style' => 'thin' ),
							'bottom' => array( 'style' => 'thin' ),
					),
			);
	
			$oExcel= new Excel();
			$oExcel->ReadExcel7(SERVER_PATH."/imgbank/finance_provider_report_2.xlsx");
			$oExcel->SetActiveSheetIndex();
			$oExcel->GetActiveSheet();
	
			$aStyleNumber= $aStyleText;
			$aStyleUserFill = $aStyleText;
			unset($aStyleUserFill['borders']);
			$aStyleUserFill['fill'] = array(
					'type' => 'solid',
					'startcolor' => array('argb' => 'e7e4e4'),
					'endcolor'   => array('argb' => '00000000')
			);
			$aStyleUserFillLeft = $aStyleUserFill;
			$aStyleUserFillLeft['alignment'] = array('horizontal' => 'left',);
			$aStyleNumber['numberformat'] = $oExcel->aStyleFormatNumber00['numberformat'];
			$aStyleUserFill['numberformat'] = $oExcel->aStyleFormatNumber00['numberformat'];
			$aCenter= array(
					'alignment' => array('horizontal' => 'center',),
			);
			$aCenterNumber= array(
					'alignment' => array('horizontal' => 'center',),
					'numberformat' => $oExcel->aStyleFormatNumber00['numberformat']
			);
			$aCenterText= array(
				'alignment' => array('horizontal' => 'center',),
				'numberformat' => $oExcel->aStyleFormatText['numberformat']
			);
				
			$this->MakroHeaderSetProvider($oExcel);
	
			$oExcel->SetCellValue('B4',"Взаиморасчеты с поставщиками за период с ".$sDateFrom." по ".$sDateTo);
	
			$i=9;$j=1;
			$iSum1=$iSum2=$iSum3=$iSum4=0;
			foreach ($aDataAssoc as $iIdProvider => $aValue) {
				$oExcel->SetCellValueExplicit('C'.$i,$aValue['name'],'',$aStyleUserFillLeft);
				$oExcel->SetCellValueExplicit('D'.$i,'','',$aStyleUserFillLeft);
				$oExcel->SetCellValueExplicit('E'.$i,'','',$aStyleUserFillLeft);
				$oExcel->SetCellValueExplicit('F'.$i,'','',$aStyleUserFillLeft);
				$oExcel->SetCellValueExplicit('G'.$i,'','',$aStyleUserFillLeft);
				$oExcel->SetCellValueExplicit('H'.$i,$aValue['start'],'',$aStyleUserFill,2,'n');
				$iClientStart = $i;
				$i+=1;$dSumCredit=0;$dSumDebet=0;
				if ($aValue['items'])
				foreach ($aValue['items'] as $sDokument => $aItem) {
					$oExcel->SetCellValueExplicit('B'.$i,$j,'',$aCenter);
					$oExcel->SetCellValueExplicit('C'.$i, $aItem['document']);
					$oExcel->SetCellValueExplicit('D'.$i, $aItem['code'],'',$aCenterText,'','s');
					$oExcel->SetCellValueExplicit('E'.$i, $aItem['id_cart_package'],'',$aCenterText,'','s');
					if ($aItem['id_group'])
						$oExcel->SetCellValueExplicit('F'.$i, $aItem['name_provider']);
					
					$oExcel->SetCellValueExplicit('G'.$i, Language::GetPostDate($aItem['post_date']),'',$aCenterText,'','s');
					$oExcel->SetCellValueExplicit('H'.$i, $aItem['debt_amount'],'',$aCenterNumber,2,'n');
					$oExcel->SetCellValueExplicit('I'.$i, $aItem['credit'],'',$aCenterNumber,2,'n');
					$oExcel->SetCellValueExplicit('J'.$i, $aItem['debet'],'',$aCenterNumber,2,'n');
					$oExcel->SetCellValueExplicit('K'.$i, $aItem['account_amount'],'',$aCenterNumber,2,'n');
					$j+=1;$i+=1;$dSumCredit+=$aItem['credit'];$dSumDebet+=$aItem['debet'];
					$iSum2 += $aItem['credit'];
					$iSum3 += $aItem['debet'];
				}
				$oExcel->SetCellValueExplicit('I'.$iClientStart,$dSumCredit,'',$aStyleUserFill,2,'n');
				$oExcel->SetCellValueExplicit('J'.$iClientStart,$dSumDebet,'',$aStyleUserFill,2,'n');
				$dLastAccountAmount = $aValue['start'] - abs($dSumCredit) + $dSumDebet;
				$oExcel->SetCellValueExplicit('K'.$iClientStart,$dLastAccountAmount,'',$aStyleUserFill,2,'n');
				$iSum1 += $aValue['start'];
				$iSum4 += $dLastAccountAmount;
			}
			$oExcel->SetCellValueExplicit('B'.$i,'Строк '.($j-1),'',$aStyleText);
			$oExcel->SetCellValueExplicit('C'.$i,'','',$aStyleText);
			$oExcel->SetCellValueExplicit('D'.$i,'','',$aStyleText);
			$oExcel->SetCellValueExplicit('E'.$i,'','',$aStyleText);
			$oExcel->SetCellValueExplicit('F'.$i,'','',$aStyleText);
			$oExcel->SetCellValueExplicit('G'.$i,'','',$aStyleText);
			$oExcel->SetCellValueExplicit('H'.$i,$iSum1,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('I'.$i,$iSum2,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('J'.$i,$iSum3,'',$aStyleNumber,2,'n');
			$oExcel->SetCellValueExplicit('K'.$i,$iSum4,'',$aStyleNumber,2,'n');
	
			//end
			$sFileName=uniqid().'.xlsx';
			$oExcel->WriterExcel7(SERVER_PATH.'/imgbank/temp_upload/'.$sFileName, true);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function ReestrProvider()
	{	

		Base::$sText.=Base::$tpl->fetch('panel/tab_manager_provider_reestr.tpl');
	
		$sWhere.=" and u.type_='provider' ";
		$aNameUser=array('0' =>'Все')+Db::GetAssoc("select u.id, concat(ifnull(up.name,''),' ( ',u.login,' )',
				IF(up.phone is null or up.phone='','',concat(' ".
				Language::getMessage('tel.')." ',up.phone))) name
			from user as u
			inner join user_provider as up on u.id=up.id_user
			inner join user_account ua on ua.id_user=u.id
			left join user_provider_group upg on upg.id_user = u.id
			left join user_provider_group_main m on m.id = upg.id_group
			where u.visible=1".$sWhere." order by up.name");

		Base::$tpl->assign('aNameUser',$aNameUser);
		
		Resource::Get()->Add('/js/select_search.js');

		if (Auth::$aUser['type_']=='customer') $sWhere=Auth::$sWhere;
		else $sWhere='';
		
		$sWhere = str_replace('and id_user','and b.id_user', $sWhere);
		
		switch (Base::$aRequest['action']) {
			case 'finance_reestr_provider_pko':
				$sWhere .= " and code_template='order_bill' and code_account='back_pay_provider'";
				Base::$aRequest['code_template'] = 'order_bill';
				break;
			case 'finance_reestr_provider_bv':
				$sWhere .= " and code_template='order_bill_bv'";
				Base::$aRequest['code_template'] = 'order_bill_bv';
				break;
			default:
			case 'finance_reestr_provider_rko':
				$sWhere .= " and code_template='order_bill_rko'";
				Base::$aRequest['code_template'] = 'order_bill_rko';
				break;
		}
		
		//$aTemplate = array(''=>Language::GetMessage('All'));
		$aTemplate = Finance::AssignAccountProvider();
	
		if(Auth::$aUser['typle_']=='manager') $aField['search_login']=array('title'=>'Login','type'=>'select','options'=>$aNameUser,'selected'=>Base::$aRequest['search_login'],'name'=>'search_login','class'=>'select_search');
		$aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("1.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
		$aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		if(Auth::$aUser['typle_']=='manager') $aField['fio']=array('title'=>'Fio','type'=>'input','value'=>Base::$aRequest['search']['fio'],'name'=>'search[fio]');
		$aField['amount_from']=array('title'=>'amFrom','type'=>'input','value'=>Base::$aRequest['search']['amount_from'],'name'=>'search[amount_from]','checkbox'=>1);
		$aField['amount_to']=array('title'=>'amTo','type'=>'input','value'=>Base::$aRequest['search']['amount_to'],'name'=>'search[amount_to]');
		$aField['template']=array('title'=>'Template','type'=>'select','options'=>$aTemplate,'selected'=>Base::$aRequest['search']['template'],'name'=>'search[template]');
		$aField['id_cart_package']=array('title'=>'cartpackage #','type'=>'input','value'=>Base::$aRequest['search']['id_cart_package'],'name'=>'search[id_cart_package]');
		$aField['id']=array('title'=>'id','type'=>'input','value'=>Base::$aRequest['search']['id'],'name'=>'search[id]');
		$aField['search_login']=array('title'=>'Login_','type'=>'select','options'=>$aNameUser,'name'=>'search_login','selected'=>Base::$aRequest['search_login'],'class'=>'select_name_user');
		$aData=array(
				'sHeader'=>"method=get",
				//'sContent'=>Base::$tpl->fetch('finance/form_bill_search.tpl'),
				'aField'=>$aField,
				'bType'=>'generate',
				'sGenerateTpl'=>'form/index_search.tpl',
				'sSubmitButton'=>'Search',
				'sSubmitAction'=>Base::$aRequest['action'],
				'sReturnButton'=>'Clear',
				'bIsPost'=>0,
				'sWidth'=>'80%',
				'sError'=>$sError,
		);
		$oForm=new Form($aData);
	
		Base::$sText .= $oForm->getForm();
	
		$oTable=new Table();
	
		// --- search ---
		if (Base::$aRequest['search_login']) {
			$sWhere.=" and u.id='".Base::$aRequest['search_login']."'";
		}
		if (Base::$aRequest['search']['fio']) $sWhere.=" and uc.name like '%".Base::$aRequest['search']['fio']."%'";
	
		if (Base::$aRequest['search']['date']) {
			$sWhere.=" and (b.post_date >= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
	            and b.post_date <= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."') ";
		}
		if (Base::$aRequest['search']['amount']) {
			$sWhere.=" and (b.amount >= '".Base::$aRequest['search']['amount_from']."'
	            and b.amount <= '".Base::$aRequest['search']['amount_to']."') ";
		}
		if (Base::$aRequest['search']['id_cart_package'])
			$sWhere.=" and b.id_cart_package like '%".Base::$aRequest['search']['id_cart_package']."%'";
		if (Base::$aRequest['search']['id'])
			$sWhere.=" and b.id like '%".Base::$aRequest['search']['id']."%'";
	
		if (Base::$aRequest['search']['template'])
			$sWhere .= " and b.code_account = '".Base::$aRequest['search']['template']."'";
	
		// --- search ---
		$oTable->sSql=Base::GetSql('BillProvider',array(
			"where"=>$sWhere,
		));
	
		$oTable->aColumn=array(
				'id_cart_package'=>array('sTitle'=>'cartpackage #'),
				'id'=>array('sTitle'=>'id'),
				'amount'=>array('sTitle'=>'Amount'),
				'template'=>array('sTitle'=>'Template'),
				'post'=>array('sTitle'=>'Date'),
				'action'=>array(),
		);
		$oTable->aOrdered="order by b.post_date desc,b.id desc";
		$oTable->sDataTemplate='finance/row_bill_provider.tpl';
		$oTable->sButtonTemplate='finance/button_bill.tpl';
		$oTable->bCheckVisible=false;
		$oTable->sWidth='100%';
		Base::$sText.=$oTable->getTable("Provider Bills",'provider_bill');
	}
}
?>