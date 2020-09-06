<?

/**
 * @author Mikhail Starovoyt
 */

class AReport extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sAction = 'report';
		$this->sWinHead = Language::getDMessage('Reports');
		$this->sPath = Language::GetDMessage('>>Logs >');
		$this->aFilterName = array('customer_account_debt','opt_amount','manager_group','manager_order_status'
		,'invoice_earned_money');
		$this->Admin();

		User::AssignPartnerRegion();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex();
		Base::$tpl->assign('aFilterName',$this->aFilterName);

		Base::$sText .= $this->SearchForm();

		if ($this->aSearch['filter_name']) {
			$sMethod=Admin::ActionToClass($this->aSearch['filter_name']);
			$this->$sMethod();
		}
		//$this->AfterIndex();
		Base::$oResponse->addAssign ( 'win_text', 'innerHTML', Base::$sText );
	}
	//-----------------------------------------------------------------------------------------------
	public function PrepareExcel($aData)
	{
		set_include_path(SERVER_PATH.'/lib/PHPExcel/');
		require_once(SERVER_PATH.'/lib/PHPExcel/PHPExcel.php');
		require_once(SERVER_PATH.'/lib/PHPExcel/PHPExcel/Writer/Excel2007.php');
		require_once(SERVER_PATH.'/lib/PHPExcel/PHPExcel/Writer/Excel5.php');

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->setActiveSheetIndex(0);
		if ($aData['column_list']) foreach($aData['column_list'] as $key => $value) {
			$objPHPExcel->getActiveSheet()->setCellValue($key.'2', $value);
			$objPHPExcel->getActiveSheet()->getColumnDimension($key)->setAutoSize(true);
		}

		$objPHPExcel->getActiveSheet()->duplicateStyleArray(
		array('font'    => array('bold'      => true),
		'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		),
		'borders' => array('top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN)
		),
		'fill' => array('type'       => PHPExcel_Style_Fill::FILL_SOLID  ,
		'rotation'   => 90,'startcolor' => array(
		'argb' => 'FFA0A0A0'
		),
		'endcolor'   => array('argb' => 'FFFFFFFF'))),
		'A2:S2');
		$objPHPExcel->getActiveSheet()->setTitle('Report');
		$objPHPExcel->setActiveSheetIndex(0);

		if ($aData['excel_data']) {
			$i=3;
			foreach ($aData['excel_data'] as $aValue) {
				//$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $aValue['login']);
				foreach($aData['column_list'] as $key => $value) {
					$objPHPExcel->getActiveSheet()->setCellValue($key.$i, $aValue[$key]);
				}
				$i++;
			}

			$sFileName=date('Y-m-d H-i-s').' - '.$aData['report_name'].'.xls';
			$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
			$objWriter->save(SERVER_PATH.'/imgbank/temp_upload/'.$sFileName);

			return $sFileName;
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function FetchReport($aData)
	{
		$sFileName=$this->PrepareExcel($aData);

		Base::$tpl->assign('sFilePath','/imgbank/temp_upload/'.$sFileName);
		Base::$tpl->assign('sFileName',$sFileName);
		Base::$sText.=Base::$tpl->fetch('mpanel/'.$this->sAction.'/report_link.tpl');
	}
	//------------------------------------------------------------------------------------------------
	public function CustomerAccountDebt()
	{
		$aExcelData=Base::$db->GetAll(Base::GetSql('Report/CustomerAccountDebt',array('where'=>$sWhere)));
		$aCustomerDebtHash=Base::$db->GetAssoc(Base::GetSql('CustomerDebt'));
		//$aCustomerDebtHash=Language::Array2Hash($aCustomerDebt,'id_user');

		if ($aExcelData) foreach($aExcelData as $key => $value) {
			$aExcelData[$key]['A']=$value['login'];
			$aExcelData[$key]['B']=$value['current_account_amount'];
			$sCreditAmount='-'.$aCustomerDebtHash[$value['id_user']];
			if ($sCreditAmount=='-') $sCreditAmount=0;
			$aExcelData[$key]['C']=$sCreditAmount;
			$sContactInfo=$value['name'].' '.$value['phone'].' '.$value['state'].' '.$value['name']
			.' '.$value['address'].' '.$value['email'];
			$aExcelData[$key]['D']= iconv('windows-1251','utf-8',$sContactInfo);
		}

		$aData=array(
		'column_list'=>array(
		'A'=>'Login',
		'B'=>'Amount',
		'C'=>'CreditAmount',
		'D'=>'ContactInfo',
		),
		'excel_data'=>$aExcelData,
		'report_name'=>'CustomerAccountDebt',
		);

		$this->FetchReport($aData);
	}
	//-----------------------------------------------------------------------------------------------
	public function OptAmount()
	{
		$iCheckTime=time()-86400*30;
		$sQuery="select ual.id_user, sum(amount) as amount_sum
					from user u
					inner join user_account_log ual on u.id=ual.id_user
					where ual.type_='credit'
						and u.type_='customer'
						and ual.post > $iCheckTime
					group by ual.id_user";
		$aCustomerWithdrawAssoc=Base::$db->GetAssoc($sQuery);
		$aCustomerDebtAssoc=Base::$db->GetAssoc(Base::GetSql('CustomerDebt'));

		$aCustomerGroupAssoc=Base::$db->GetAssoc("select concat('\'',code,'\''),code from customer_group where code!='default'");

		$aExcelData=Base::$db->GetAll(Base::GetSql('Customer',array(
		'code_array'=>array_keys($aCustomerGroupAssoc),
		'test'=>0,
		'has_account_log'=>1,
		)));

		if ($aExcelData) foreach($aExcelData as $key => $value) {
			$aExcelData[$key]['A']=$value['login'];
			$aExcelData[$key]['B']= $value['customer_group_name'];
			$aExcelData[$key]['C']=$value['current_account_amount'];
			$sCreditAmount='-'.$aCustomerDebtAssoc[$value['id_user']];
			if ($sCreditAmount=='-') $sCreditAmount=0;
			$aExcelData[$key]['D']=$sCreditAmount;
			$sContactInfo=$value['name'].' '.$value['phone'].' '.$value['state'].' '.$value['name']
			.' '.$value['address'].' '.$value['email'];
			$aExcelData[$key]['E']= $aCustomerWithdrawAssoc[$value['id_user']];
			$aExcelData[$key]['F']= $value['user_debt'];
			$aExcelData[$key]['G']= $value['group_debt'];
			$aExcelData[$key]['H']= iconv('windows-1251','utf-8',$sContactInfo);
		}

		$aData=array(
		'column_list'=>array(
		'A'=>'Login',
		'B'=>'Group',
		'C'=>'Amount',
		'D'=>'CreditAmount',
		'E'=>'WithdrawAmount',
		'F'=>'UserDebt',
		'G'=>'GroupDebt',
		'H'=>'ContactInfo',
		),
		'excel_data'=>$aExcelData,
		'report_name'=>'OptAmount',
		);

		$this->FetchReport($aData);
	}
	//-----------------------------------------------------------------------------------------------
	public function ManagerGroup()
	{
		$aManagerList=Base::$db->getAll(Base::GetSql('Manager/HasCustomer'));
		if ($aManagerList) foreach ($aManagerList as $aValue) {
			//, '".$aValue['login']."' as manager_login
			if ($this->aSearch['date_from'])
			$sWhere.=" and ual.post_date > '".DateFormat::FormatSearch($this->aSearch['date_from'])."'";
			if ($this->aSearch['date_to'])
			$sWhere.=" and ual.post_date < '".DateFormat::FormatSearch($this->aSearch['date_to'])."'";

			if (!$sWhere) {
				$iCheckTime=time()-86400*30;
				$sWhere.=" and ual.post > $iCheckTime";
			}

			$sQuery="select uc.code_customer_group, sum(amount) as amount_sum
					from user u
					inner join user_customer uc on uc.id_user=u.id
					inner join user_account_log ual on u.id=ual.id_user
					where ual.type_='credit'
						and uc.id_manager='".$aValue['id']."'
						and u.type_='customer'
						".$sWhere."
					group by uc.code_customer_group";
			$aCustomerWithdrawAssoc[$aValue['id']]=Base::$db->GetAssoc($sQuery);
		}

		if ($aManagerList) foreach($aManagerList as $sKey => $aValue) {
			$aExcelData[$sKey]['A']=$aValue['login'];
			$aExcelData[$sKey]['B']=abs($aCustomerWithdrawAssoc[$aValue['id']]['default']);
			$aExcelData[$sKey]['C']=abs($aCustomerWithdrawAssoc[$aValue['id']]['margin3']);
			$aExcelData[$sKey]['D']=abs($aCustomerWithdrawAssoc[$aValue['id']]['margin4']);
			$aExcelData[$sKey]['E']=abs($aCustomerWithdrawAssoc[$aValue['id']]['margin5']);
			$aExcelData[$sKey]['F']=abs($aCustomerWithdrawAssoc[$aValue['id']]['margin6']);
			$aExcelData[$sKey]['G']=abs($aCustomerWithdrawAssoc[$aValue['id']]['margin7']);
			$aExcelData[$sKey]['H']=abs($aCustomerWithdrawAssoc[$aValue['id']]['Opt1']);
			$aExcelData[$sKey]['I']=abs($aCustomerWithdrawAssoc[$aValue['id']]['Opt2']);
			$aExcelData[$sKey]['J']=abs($aCustomerWithdrawAssoc[$aValue['id']]['Opt3']);
			$aExcelData[$sKey]['K']=abs($aCustomerWithdrawAssoc[$aValue['id']]['Opt4']);
			$aExcelData[$sKey]['L']=abs($aCustomerWithdrawAssoc[$aValue['id']]['opt5']);
			//$aExcelData[$key]['N']=abs($aCustomerWithdrawAssoc[$aValue['id']]['']);
		}

		$aData=array(
		'column_list'=>array(
		'A'=>'ManagerLogin',
		'B'=>'default',
		'C'=>'margin3',
		'D'=>'margin4',
		'E'=>'margin5',
		'F'=>'margin6',
		'G'=>'margin7',
		'H'=>'opt1',
		'I'=>'opt2',
		'J'=>'opt3',
		'K'=>'opt4',
		'L'=>'opt5',
		//'N'=>'total',
		),
		'excel_data'=>$aExcelData,
		'report_name'=>'ManagerGroup',
		);

		$this->FetchReport($aData);
	}
	//-----------------------------------------------------------------------------------------------
	public function ManagerOrderStatus()
	{
		require_once(SERVER_PATH.'/mpanel/spec/stat_manager.php');
		AStatManager::UpdateProviderPrice();

		//, '".$aValue['login']."' as manager_login
		if ($this->aSearch['date_from']) {
			$sWhere.=" and cl.post_date > '".DateFormat::FormatSearch($this->aSearch['date_from'])."'";
		}
		else $sWhere.=" and cl.post_date> DATE_SUB(CURDATE() , INTERVAL 30 DAY )";

		if ($this->aSearch['date_to'])
		$sWhere.=" and cl.post_date < '".DateFormat::FormatSearch($this->aSearch['date_to'])."'";

		//if (!$sWhere)

		$sQuery="select uu.login as manager_login, u.login, uc.code_customer_group, cl.*, c.*
						,cl.post_date
					from cart as c
					inner join cart_log as cl on (cl.id_cart=c.id and cl.order_status=c.order_status)
					inner join user u on c.id_user=u.id
					inner join user_customer uc on uc.id_user=u.id
					inner join user as uu on uc.id_manager=uu.id

					where cl.order_status in ('work','confirmed','road','store','end')
						".$sWhere."
					order by uu.login, uc.code_customer_group, u.login, cl.post_date";
		$aCartLog=Base::$db->GetAll($sQuery);

		if ($aCartLog) {
			$aCustomerLogin=array();
			foreach ($aCartLog as $aValue) {
				if (!in_array($aValue['login'],$aCustomerLogin)) $aCustomerLogin[]=$aValue['login'];

				$aCustomerCartLog[$aValue['login']][$aValue['order_status']][]=$aValue;
			}

			$aCartOrderStatus=array(
			//'new'=>array('D','E'),
			'work'=>array('F','G'),
			'confirmed'=>array('H','I'),
			'road'=>array('J','K'),
			'store'=>array('L','M'),
			'end'=>array('N','O'),);
			$i=1;
			foreach($aCustomerLogin as $sValue) {
				$iMaxHeight=max(
				//count($aCustomerCartLog[$sValue]['new']),
				count($aCustomerCartLog[$sValue]['work']),
				count($aCustomerCartLog[$sValue]['confirmed']),
				count($aCustomerCartLog[$sValue]['road']),
				count($aCustomerCartLog[$sValue]['store']),
				count($aCustomerCartLog[$sValue]['end'])
				);

				foreach (array_keys($aCartOrderStatus) as $sValue2) {
					for ($j=$i;$j<$i+$iMaxHeight;$j++) {
						$aCurrent=$aCustomerCartLog[$sValue][$sValue2][$j-$i];
						if (!$aCurrent) break;

						if ($i==$j && !$aExcelData[$j]['A'] && $aCurrent['manager_login']) {
							$aExcelData[$j]['A']=$aCurrent['manager_login'];
							$aExcelData[$j]['B']=$aCurrent['login'];
							$aExcelData[$j]['C']=$aCurrent['code_customer_group'];
							$aExcelData[$j]['D']=$aCurrent['id'];
						}

						$sInfo=$aCurrent['code'].' '.$aCurrent['post_date'].' '.$aCurrent['number'];
						$aExcelData[$j][$aCartOrderStatus[$sValue2][0]]=$sInfo;
						$sTotal=$aCurrent['number']*$aCurrent['price'];
						$aExcelData[$j][$aCartOrderStatus[$sValue2][1]]=$sTotal;

						if ($sValue2=='end') {
							$dEarned=$aCurrent['number']*($aCurrent['price']-$aCurrent['provider_price'])
							- $aCurrent['weight_delivery_cost'] -$aCurrent['dimension_cost'];
							if ($aCurrent['login_vin_request']) $aExcelData[$j]['P']=$dEarned;
							else $aExcelData[$j]['Q']=$dEarned;

							$aExcelData[$j]['R']=$aCurrent['weight_delivery_cost'];
							$aExcelData[$j]['S']=$aCurrent['dimension_cost'];
						}
					}
				}
				$i+=$iMaxHeight;
			}

			$aData=array(
			'column_list'=>array(
			'A'=>'MLogin',
			'B'=>'CLogin',
			'C'=>'Group',
			'D'=>'ID',
			'E'=>'',
			'F'=>'WorkInfo',
			'G'=>'WorkTo',
			'H'=>'ConfInfo',
			'I'=>'ConfTot',
			'J'=>'RoadInfo',
			'K'=>'RoadTot',
			'L'=>'StorInfo',
			'M'=>'StoreTot',
			'N'=>'EndInfo',
			'O'=>'EndTot',
			'P'=>'EndVinEarned',
			'Q'=>'EndNotvinEarned',
			'R'=>'WeightCost',
			'S'=>'DimensionCost',
			),
			'excel_data'=>$aExcelData,
			'report_name'=>'ManagerOrderStatus',
			);
		}
		$this->FetchReport($aData);
	}
	//-----------------------------------------------------------------------------------------------
	public function InvoiceEarnedMoney()
	{
		if ($this->aSearch['date_from']) {
			$sWhere.=" and cl.post_date > '".DateFormat::FormatSearch($this->aSearch['date_from'])."'";
			if ($this->aSearch['date_to'])
			$sWhere.=" and cl.post_date < '".DateFormat::FormatSearch($this->aSearch['date_to'])."'";

			$aDisctinctInvoiceAssoc=Db::GetAssoc('Assoc/DistinctInvoice',array('where'=>$sWhere));
		} else {
			$this->Message('MT_ERROR',Language::GetDMessage('Please, fill Date from field'));
			return;
		}

		if ($aDisctinctInvoiceAssoc) {
			$aCart=Db::GetAll(Base::GetSql('Cart',array(
			'where'=>" and c.id_provider_invoice in (".implode(',',array_keys($aDisctinctInvoiceAssoc)).")
				and c.order_status!='refused' and cl.order_status='store' ".$sWhere,
			'join_cart_log'=>'1',
			'order'=>" order by c.id_provider_invoice,u.login",
			)));

			if ($aCart) {
				foreach($aCart as $key => $aValue) {
					$dPriceOriginal=($aValue['provider_price']>0 ? $aValue['provider_price'] : $aValue['price_original']);

					$sCurrentInvoice=String::UtfEncode(trim($aValue['id_provider_invoice']));
					$aExcelData[$key]['A']=$sCurrentInvoice;
					$aExcelData[$key]['B']=$aValue['login'];
					$aExcelData[$key]['C']=$aValue['cat_name'];
					$aExcelData[$key]['D']=$aValue['id'];
					$aExcelData[$key]['E']=$aValue['code'];
					$aExcelData[$key]['F']=$aValue['number'];
					$aExcelData[$key]['G']=$dPriceOriginal;
					$aExcelData[$key]['H']=$aValue['price'];
					$dCurrentEarnedMoney=$aValue['number']*($aValue['price']-$dPriceOriginal);
					$aExcelData[$key]['I']=$dCurrentEarnedMoney;
					$aSubTotal[$sCurrentInvoice]+=$dCurrentEarnedMoney;
					$dTotalEarnedMoney+=$dCurrentEarnedMoney;
				}

				$aParsedSubtotal=array();
				foreach($aCart as $key => $aValue) {
					$sCurrentInvoice=String::UtfEncode(trim($aValue['id_provider_invoice']));
					if (!in_array($sCurrentInvoice,$aParsedSubtotal)) {
						$aExcelData[$key]['J']=$aSubTotal[$sCurrentInvoice];
						$aParsedSubtotal[]=$sCurrentInvoice;
					}
				}

				$aExcelData[$key]['K']=$dTotalEarnedMoney;
			}
		}

		$aData=array(
		'column_list'=>array(
		'A'=>'Invoice',
		'B'=>'Login',
		'C'=>'Make',
		'D'=>'ID',
		'E'=>'Code',
		'F'=>'Number',
		'G'=>'OriginalPrice',
		'H'=>'CustomerPrice',
		'I'=>'EarnedMoney',
		'J'=>'Subtotal',
		'K'=>'Total',
		),
		'excel_data'=>$aExcelData,
		'report_name'=>'InvoiceEarnedMoney',
		);

		$this->FetchReport($aData);
	}
	//-----------------------------------------------------------------------------------------------


}

?>