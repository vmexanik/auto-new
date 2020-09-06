<?
require_once(SERVER_PATH.'/class/core/Base.php');
class Cron extends Base {

	//-----------------------------------------------------------------------------------------------
	public function Cron()
	{
	}
	//-----------------------------------------------------------------------------------------------
	public function CloseCartPackage($iId,$sOrderStatus='refused')
	{
		if (!in_array($sOrderStatus,array('refused','end','removed'))) return false;
		$bCheck=true;
		$aCart=Base::$db->getAll("select cp.*,c.* from cart c
			inner join cart_package cp on c.id_cart_package=cp.id
			where c.id_cart_package='$iId' and type_='order'
			order by c.order_status
			");
		if (!$aCart) return false;

		foreach ($aCart as $value) {
			if ($value['order_status']=='end') $sOrderStatus='end';
			if (!in_array($value['order_status'],array('refused','end','removed'))) {
				$bCheck=false;
				break;
			}
		}
		if ($bCheck) {
			Base::$db->Execute("update cart_package set order_status='$sOrderStatus',post_date_changed='".date("Y-m-d H:i:s")."' where id='$iId'");
			$iIdManager = (Auth::$aUser['type_']=='manager' ? Auth::$aUser['id_user'] : 0);
			// log
			Base::$db->Execute("insert into cart_package_log (id_cart_package,id_user_manager,post_date,order_status,comment,ip)
		    values ('".$iId."','".$iIdManager."','".date("Y-m-d H:i:s")."','".$sOrderStatus."','','".Auth::GetIp()."')");
				
			//Close debts for closed cart apckages
			Base::$db->Execute("update log_debt set pay_date=UNIX_TIMESTAMP()
				where custom_id='$iId' and is_payed=0");
			//			require_once(SERVER_PATH.'/class/module/Debt.php');
			//			Debt::CheckPayDebt();

			if ($value['price_total']>0){
				$aData['id_buh_debit_subconto1']=$value['id_user'];
				$aData['id_buh_debit']=361;
				$aData['id_buh_credit']=703;
				$aData['amount']=$value['price_total'];
				$aData['id_buh_section']=1;
				$aData['buh_section_id']=$iId;
				$aEntry[]=$aData;
				$oBuh = new Buh();
				$oBuh->EntryMany($aEntry);
			}

			$aCartPackage=Db::getRow("Select * from cart_package where id=".$iId);
			// return delivery money
			if ($sOrderStatus=="refused" && $aCartPackage['price_delivery']!=0)
				Finance::Deposit($aCartPackage['id_user'],abs($aCartPackage['price_delivery']),
				Language::getMessage('return money for delivery'),
				$aCartPackage['id'],'internal','cart','',5);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function SendAutopayPackage($iIdUser)
	{
		return; // AT-1275
		/*$aCartPackage=Base::$db->getAll("select cp.* from cart_package cp
				inner join user_account ua on ua.id_user=cp.id_user
			where cp.id_user='$iIdUser' and cp.autopay='1'
				and cp.order_status='pending'
			order by cp.post");
		if (!$aCartPackage) return false;

		require_once(SERVER_PATH.'/class/module/Cart.php');
		foreach ($aCartPackage as $value) {
			if (Finance::HaveMoney($value['price_total'],$value['id_user'],$value['full_payment'])) Cart::SendPendingWork($value['id']);
		}*/
	}
	//-----------------------------------------------------------------------------------------------
	public function ClearOldData()
	{
		Base::$db->Execute("delete from log_visit where post<".(time() -7*86400));
		Base::$db->Execute("delete from log_admin where post_date<DATE_SUB(CURDATE() , INTERVAL 60 DAY )");
		Base::$db->Execute("delete from mail_delayed where post<".(time() -10*86400));
		Base::$db->Execute("TRUNCATE TABLE print_content");

		Base::$db->Execute("update `message` set is_old=1 WHERE post_date<DATE_SUB(CURDATE() , INTERVAL 60 DAY )");
		Base::$db->Execute("update `message` set is_old=1 WHERE is_read=0 and post_date<DATE_SUB(CURDATE() , INTERVAL 30 DAY )");

		Base::$db->Execute("delete from price_search_log where post_date<DATE_SUB(CURDATE() , INTERVAL 30 DAY)");
	}
	//-----------------------------------------------------------------------------------------------
	public function SendDbBackup()
	{
		// DB dump sending -------
		Mail::SendAttach('mikhail.starovojt@gmail.com','[DB Dump]: '.date('Y-m-d'),'DB dump: '.'allavto_backup_'.date('Y-m-d').'.zip'
		,array(
		'/home/mstar/backup/allavto.com.ua/allavto_backup_'.date('Y-m-d').'.sql.zi_'=>'')
		);
		Base::$sText.="<br>".'/home/mstar/backup/allavto.com.ua/allavto_backup_'.date('Y-m-d').'.sql.zi_';
		//------------------------
	}
	//-----------------------------------------------------------------------------------------------
	public function NotifyPendingOrder()
	{
		require_once(SERVER_PATH.'/class/core/Mail.php');
		$aCartPackage=Base::$db->GetAll("select u.*, uc.*, cp.* from cart_package cp
			inner join user u on cp.id_user=u.id
			inner join user_customer uc on uc.id_user=u.id
			where cp.order_status='pending'
				and cp.post<".(time() - 5*86400)." and cp.post>".(time() - 6*86400)  );
		if ($aCartPackage) {
			require_once(SERVER_PATH.'/class/system/Content.php');
			foreach ($aCartPackage as $value) {
				$aData=array(
				'cart_package'=>$value,
				);
				$sBody=String::GetSmartyTemplate('pending_cart_package',$aData);
				Mail::AddDelayed($value['email'],Language::getMessage('Pending Cart Package'),$sBody);
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function NotifyLastVisit()
	{
		require_once(SERVER_PATH.'/class/core/Mail.php');
		$aUser=Base::$db->GetAll("select * from user where receive_notification='1' and is_last_visit_notified='0'
					and last_visit_date<DATE_SUB(CURDATE() , INTERVAL 30 DAY )");
		if ($aUser) {
			require_once(SERVER_PATH.'/class/system/Content.php');
			foreach ($aUser as $aValue) {
				$aUserId[]=$aValue['id'];
				$aData=array(
				'aUser'=>$aValue,
				);
				$aTemplate=Content::GetSmartyTemplate('last_visit_notification',$aData);
				$sBody=$aTemplate['parsed_text'];
				$sSubject=$aTemplate['name'];
				if ($aValue['email']) {
					Mail::AddDelayed($aValue['email'],$sSubject,$sBody,'','',true,$aTemplate['priority']);
				}
			}

			Base::$db->Execute("update user set is_last_visit_notified='1' where id in (".implode(',',$aUserId).")");
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function DeleteTemporaryCustomer()
	{
		if (!Base::GetConstant('user:clear_autocreated_customer',1)) return;

		$sSql="select u.id, u.login
			from user as u
			left join cart_package as cp on cp.id_user=u.id
			left join vin_request as vr on vr.id_user=u.id
			where cp.id IS NULL and vr.id IS NULL  and u.login REGEXP  '^a[0-9]*$'
				and u.post_date<DATE_SUB(NOW(),INTERVAL ".Base::GetConstant('user:old_temporary_customer_hour',12)." HOUR)
		";
		$aUserIdAssoc=Db::GetAssoc($sSql);

		if ($aUserIdAssoc) {
			Db::Execute("delete from user where id in (".implode(',',array_keys($aUserIdAssoc)).")");
			Db::Execute("delete from user_customer where id_user in (".implode(',',array_keys($aUserIdAssoc)).")");
			Db::Execute("delete from cart where id_user in (".implode(',',array_keys($aUserIdAssoc)).")");
			Db::Execute("delete from user_account where id_user in (".implode(',',array_keys($aUserIdAssoc)).")");
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function ClearCustomerData()
	{
		set_time_limit(0);
		$aCustomer=Db::GetAssoc("select id_user as id,id_user from user_customer");
		if(!$aCustomer) die('Not found!');
		$inCustomer="'".implode("','", $aCustomer)."'";
		$aCart=Db::GetAssoc("select id as i,id  from cart where id_user in (".$inCustomer.")");
		$aCartPackage=Db::GetAssoc("select id as i,id  from cart_package where id_user in (".$inCustomer.")");
		$aCartInvoice=Db::GetAssoc("select id as i,id  from cart_invoice where id_user_customer in (".$inCustomer.")");
		$aMessage=Db::GetAssoc("select id as i,id  from message where id_user in (".$inCustomer.")");

		$sSqlDelete['Customer']="delete from user_customer where id_user in (".$inCustomer.")";
		$sSqlDelete['User']="delete from user where id in (".$inCustomer.")";
		$sSqlDelete['Cart']="delete from cart where id_user in (".$inCustomer.")";
		$sSqlDelete['CartLog']="delete from cart_log where id_cart in ('".implode("','", $aCart)."')";
		$sSqlDelete['CartPackage']="delete from cart_package where id_user in (".$inCustomer.")";
		$sSqlDelete['CartPackageLog']="delete from cart_package_log where id_cart_package in ('".implode("','", $aCartPackage)."')";
		$sSqlDelete['CartInvoice']="delete from cart_invoice where id_user_customer in (".$inCustomer.")";
		$sSqlDelete['CartInvoiceLog']="delete from cart_invoice_log where id_cart_invoice in ('".implode("','", $aCartInvoice)."')";
		$sSqlDelete['InvoiceCustomer']="delete from invoice_customer where id_user in (".$inCustomer.")";
		$sSqlDelete['Message']="delete from message where id_user in (".$inCustomer.")";
		$sSqlDelete['MessageAttachment']="delete from message_attachment where id_message in ('".implode("','", $aMessage)."')";
		$sSqlDelete['UserAccount']="delete from user_account where id_user in (".$inCustomer.")";
		$sSqlDelete['UserAccountLog']="delete from user_account_log where id_user in (".$inCustomer.")";
		$sSqlDelete['VinRequest']="delete from vin_request where id_user in (".$inCustomer.")";
		$sSqlDelete['LogFinance']="delete from log_finance where id_user in (".$inCustomer.")";
		$sSqlDelete['CartUnlinks']="delete FROM cart where id_cart_package != 0";
		$sSqlDelete['UserAuto']="delete from user_auto where id_user in (".$inCustomer.")";
		$sSqlDelete['CartPackageUnlinks']="delete FROM cart_package";
		$sSqlDelete['Declaration']="delete FROM payment_declaration";
		$sSqlDelete['PaymentReport']="delete FROM payment_report";
		$sSqlDelete['CartInvoice']="delete FROM cart_invoice";
		$sSqlDelete['MessageUnlinks']="delete from message";
		$sSqlDelete['MessageAttachmentUnlinks']="delete from message_attachment";
		$sSqlDelete['CallMe']="delete from call_me";
		$sSqlDelete['CallMe']="delete from ulogin_user";
		
		foreach ($sSqlDelete as $sKey => $sValue) {
			print "<b>".$sKey."</b><br>".$sValue;
			Debug::PrintPre(Db::Execute($sValue),false);
			print "<hr>";
		}
		die();
	}
	//-----------------------------------------------------------------------------------------------
	public function MoveExpiredCart() {
		// delete from expired cart
		$iDelExpiredSec = Base::GetConstant('hours_expired_cart_delete',24) * 60 * 60;
		Db::Execute("DELETE from cart_deleted where (UNIX_TIMESTAMP() - post_delete) > ".$iDelExpiredSec);
		
		// add to expired cart positions
		$aMass = DB::GetAll("Select c.*, UNIX_TIMESTAMP(c.post_date) as pd_post, cg.hours_expired_cart 
				from cart c
				inner join user_customer uc on uc.id_user = c.id_user    
				left join customer_group cg on cg.id = uc.id_customer_group
				where type_ = 'cart' order by post_date asc");
		$iExpiredSec_def = Base::GetConstant('hours_expired_cart',24) * 60 * 60;
		foreach ($aMass as $aValue) {
			if ($aValue['hours_expired_cart'] != 0)
				$iExpiredSec = $aValue['hours_expired_cart'] * 60 * 60;
			else
				$iExpiredSec = $iExpiredSec_def;
			
			if ((time() - $aValue['pd_post']) < $iExpiredSec )
				continue;
			
			$aValue['post_delete'] = time();
			
			$sInfo = json_encode($aValue);
			
			Base::$db->Execute("delete from cart_deleted where id='".$aValue['id']."' and type_='cart'");
			
			Db::Execute("insert into cart_deleted (id, id_user, code, post_delete, type_, item_code, pref, info) 
					VALUES (".$aValue['id'].",".$aValue['id_user'].",'".$aValue['code']."',".$aValue['post_delete'].",'".
							$aValue['type_']."','".$aValue['item_code']."','".$aValue['pref']."','".$sInfo."')");
			Base::$db->Execute("delete from cart where id='".$aValue['id']."' and type_='cart'");
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function ClearOldPriceQueue() {
		$iBoardDayForClear = Language::GetConstant('global:BoardDayForClearPriceQueue',30);
		$iBoardDayLeftForClear = Language::GetConstant('global:BoardDayLeftForClearPriceQueue',10);
		// all, only_worked - if position not worked - not delete
		$sTypeClear = Language::GetConstant('global:ClearPriceQueue','all');
		if ($sTypeClear == 'all')
			$aData = Db::GetAll("SELECT * FROM price_queue where 
				DATEDIFF(now(),post_date) > ".$iBoardDayForClear."
				and id not in (select id from (select id from price_queue where visible = 1
				ORDER BY `price_queue`.`id` DESC limit ".$iBoardDayLeftForClear." )as a )");
		else 
			$aData = Db::GetAll("SELECT * FROM price_queue where is_processed != 0 and  
				DATEDIFF(now(),post_date) > ".$iBoardDayForClear."
				and id not in (select id from (select id from price_queue where visible = 1
				ORDER BY `price_queue`.`id`  DESC limit ".$iBoardDayLeftForClear." )as a )");
		if ($aData) {
			foreach ($aData as $aValue) {
				@unlink($aValue['file_path']);
				$aMass[] = $aValue['id'];
			}
			Db::Execute("Delete from price_queue where id in (".implode(",",$aMass).")");
			Db::Execute("Delete from price_import where id_price_queue in (".implode(",",$aMass).")");
			Db::Execute("Delete from log_price_queue where id_price_queue in (".implode(",",$aMass).")");
		}
		// check and clear other unknown files
		$oPriceQueue = new PriceQueue();
		$aData = glob(SERVER_PATH.$oPriceQueue->sPathToFile."*.*");
		if ($aData) {
			foreach($aData as $sFilePath) {
				$sBasename = basename($sFilePath);
				$iId = Db::GetOne("Select id from price_queue where file_name = '".$sBasename."'");
				if (!$iId) {
					$iTime = filemtime($sFilePath);
					if ((time() - $iTime) > $iBoardDayForClear * 24 * 60 * 60)
						@unlink($sFilePath);
				}
			}
		}
		Db::Execute("delete FROM `log_price_queue` where id_price_queue not in (select id from price_queue)");
		// defragmentation - very good clear space
		//Db::Execute("ALTER TABLE `log_price_queue` ENGINE = InnoDB");
	}
	//-----------------------------------------------------------------------------------------------
	public function ClearOldServiceLog() {
		$iBoardDayForClear = Language::GetConstant('global:BoardDayForClearServiceLog',30);
		Db::Execute("Delete from service_log where DATEDIFF(now(),post_date) > ".$iBoardDayForClear);
	}
	//-----------------------------------------------------------------------------------------------
	public function ClearOldMailDelayed() {
		$iBoardDayForClear = Language::GetConstant('global:BoardDayForClearMailDelayed',30);
		Db::Execute("Delete from mail_delayed where DATEDIFF(now(),post_date) > ".$iBoardDayForClear);
	}
	//-----------------------------------------------------------------------------------------------
	public function ClearOldUserNotification() {
		$iBoardDayForClear = Language::GetConstant('global:BoardDayForClearUserNotification',30);
		Db::Execute("Delete from user_notification where DATEDIFF(now(),post_date) > ".$iBoardDayForClear);
	}
	//-----------------------------------------------------------------------------------------------
	public function ClearOldAutoSearchLog() {
		$iBoardDayForClear = Language::GetConstant('global:BoardDayForClearAutoSearchLog',30);
		Db::Execute("Delete from auto_search_log where DATEDIFF(now(),post_date) > ".$iBoardDayForClear);
	}
	//-----------------------------------------------------------------------------------------------
	public function ClearOldPriceSearchLog() {
		$iBoardDayForClear = Language::GetConstant('global:BoardDayForClearPriceSearchLog',30);
		Db::Execute("Delete from price_search_log where DATEDIFF(now(),post_date) > ".$iBoardDayForClear);
	}
	//-----------------------------------------------------------------------------------------------
	public function ClearAllOld() {
		Cron::ClearOldAutoSearchLog();
		Cron::ClearOldPriceSearchLog();
		Cron::ClearOldPriceQueue();
		Cron::ClearOldServiceLog();
		Cron::ClearOldMailDelayed();
		Cron::ClearOldUserNotification();
	}
	//-----------------------------------------------------------------------------------------------
	public function AssociateDelayedPrices(){
		set_time_limit(0);
		$aPriceGroupAssoc = Db::GetAssoc("Select code,id from price_group");
		$iCount = Db::GetOne("SELECT Count(*) FROM price WHERE is_delayed_associate = 1");
		$oPrice = new Price();
		//$oPrice->InitAssociate();
		for($i=0; $i<$iCount; $i= $i+5000){
			$aPrices = Db::GetAll("SELECT * FROM price WHERE is_delayed_associate = 1 LIMIT ".$i.", 5000");
			if($aPrices)
				foreach($aPrices as $aPrice){
					$aPrice['id_price_group'] = $oPrice->FindAssociate($aPrice);
					if($aPrice['id_price_group']!='' && $aPrice['id_price_group'] && $aPriceGroupAssoc[$aPrice['id_price_group']]) {
						$aPrice['id_price_group'] = $aPriceGroupAssoc[$aPrice['id_price_group']];
						Db::Execute("insert into price_group_assign (item_code,id_price_group,pref) 
							values('".$aPrice['item_code']."','".$aPrice['id_price_group']."','".$aPrice['pref']."')
							on duplicate key update id_price_group = values(id_price_group), pref = values(pref) ");
					}
				}
		}
		Db::Execute("UPDATE price SET is_delayed_associate = 0, post_date = post_date WHERE is_delayed_associate = 1");
	}
	//-----------------------------------------------------------------------------------------------
	public function AssociateDelayedPricesMinutely(){
		if (Language::GetConstant('cron_associate_stop','0'))
			return;
		
		set_time_limit(0);
		
		// becouse not rewrite id_price_group - BUS-40 
		Db::Execute("Delete from price_group_assign where id_price_group=0");
		
		$aAllPriceGroups=Db::GetAll("select * from price_group where visible='1' and link_name_group <> '' or link_name_group is not null ");
		if($aAllPriceGroups) foreach ($aAllPriceGroups as $aPriceGroup) {
		    Debug::WriteToLog("/tmp/price_group_associate_".date("Y_m_d__H").".log","Price group ".$aPriceGroup['id']." start time ".date("H:i:s"));
		    
		    $aWords=explode(";", $aPriceGroup['link_name_group']);
		    if($aWords) {
		        require_once(SERVER_PATH.'/lib/sphinx/sphinxapi.php');
		        $oSphinxClient = new SphinxClient();
		        $oSphinxClient->SetMatchMode(SPH_MATCH_EXTENDED);
		        $oSphinxClient->SetSortMode(SPH_SORT_RELEVANCE);
		         
		        $oSphinxClient->SetFieldWeights(array (
		            'part_name' => 95,
		        ));
		    
		        $sSphinxKeyword='';
	            $aW=array();
	            foreach ($aWords as $sKeyWord => $sWord) {
	            	$aWords[$sKeyWord]=str_replace('"','',trim($sWord));
	            	if(!$aWords[$sKeyWord]) continue;
	            	// count word
	            	$aCntWord = explode(" ",$aWords[$sKeyWord]);
	            	if (count($aCntWord)>1)
	            		$aWords[$sKeyWord] = '"'.$aWords[$sKeyWord].'"';

	        	$aW[]=$aWords[$sKeyWord];
	        	
	    		$sData_i_eng = str_replace("і","i", $aWords[$sKeyWord]);
	    		$sData_i_eng = str_replace("І","I", $sData_i_eng);
	    		$sData_i_ua = str_replace("i","і", $aWords[$sKeyWord]);
	    		$sData_i_ua = str_replace("I","І", $sData_i_ua);
	    		if ($aWords[$sKeyWord] != $sData_i_eng)
	    		    $aW[]=$sData_i_eng;
	                if ($aWords[$sKeyWord] != $sData_i_ua)
	    		    $aW[]=$sData_i_ua;
	            }
	            $aWords = $aW;
		    
		        $aStopWords=explode(";", $aPriceGroup['link_group_stop']);
	            $aStop = array();
	            if($aStopWords) foreach ($aStopWords as $sKeyStop => $aValueStop) {
	        	$aValueStop=trim($aValueStop);
	        	if (!$aValueStop) 
	        	    continue;
	        	$aStop[]=$aValueStop;
	        	
	    		$sData_i_eng = str_replace("і","i", $aValueStop);
	    		$sData_i_eng = str_replace("І","I", $sData_i_eng);
	    		$sData_i_ua = str_replace("i","і", $aValueStop);
	    		$sData_i_ua = str_replace("I","І", $sData_i_ua);
	    		if ($aValueStop != $sData_i_eng)
	    		    $aStop[]=$sData_i_eng;
	                if ($aValueStop != $sData_i_ua)
	    		    $aStop[]=$sData_i_ua;
	            }
	            $aStopWords = $aStop;
		    
		        $sSphinxKeyword="(".implode(") | (", $aWords).") ";
		        if($aStopWords) {
		            $sSphinxKeyword.="-".implode(" -", $aStopWords);
		            $sLast=substr($sSphinxKeyword, strlen($sSphinxKeyword)-1);
		            if($sLast=="-") {
		                $sSphinxKeyword=substr($sSphinxKeyword, 0, -1);
		            }
		        }
			Debug::WriteToLog("/tmp/price_group_associate_".date("Y_m_d__H").".log","Query: @part_name ".$sSphinxKeyword." ".date("H:i:s"));
		        $aId=array();
		        foreach (range(0, 1000) as $i) {
		            $oSphinxClient->SetLimits(($i*1000), 1000, 1000000);
		            // search by one field part_name if need searh 2 field "@(field1,field2)"
		            $aResult = $oSphinxClient->Query('@part_name '.$sSphinxKeyword, 'price_group_'.Base::$aDbConf['Database']);
		            if($aResult["matches"]) {
		                $aId = array_merge($aId,array_keys($aResult['matches']));
		            } else {
		                break;
		            }
		        }
		         
		        if($aId) {
		            $aData=Db::GetAll("select item_code,'".$aPriceGroup['id']."' as id_price_group,pref from price where id in ('".implode("','", $aId)."') ");
		    
		            if($aData) {
		                $sSql="insert into price_group_assign (item_code,id_price_group,pref) values \n";
		                foreach ($aData as $aValue) {
		                    $sSql.="('".$aValue['item_code']."','".$aValue['id_price_group']."','".$aValue['pref']."'),\n";
		                }
		                $sSql=substr($sSql, 0, -2);
		                // not rewrite id_price_group BUS-40
		                $sSql.="\n on duplicate key update id_price_group=values(id_price_group), pref=values(pref)";
		                $bInstalled=Db::Execute($sSql);
		            }
		            Debug::WriteToLog("/tmp/price_group_associate_".date("Y_m_d__H").".log","Price group ".$aPriceGroup['id']." count ".count($aId));
		            Debug::WriteToLog("/tmp/price_group_associate_".date("Y_m_d__H").".log",$bInstalled);
		        }
		    }
		    
		    Debug::WriteToLog("/tmp/price_group_associate_".date("Y_m_d__H").".log","Price group ".$aPriceGroup['id']." end time ".date("H:i:s"));
		}
	}
	//-----------------------------------------------------------------------------------------------
	public static function UpdatePriceMargin() {
	    set_time_limit(0);
	    //Db::Debug();
	     
	    Db::Execute("update price, (select t.id,t.margin_id from (select
            pgs.id_price_group as dbg_id_price_group,
            mp.id_price_group as dbg_mp_id_price_group,
            p.id_provider as dbg_id_provider,
            mp.id_provider as dbg_mp_id_provider,
            up.id_currency as dbg_id_currency,
            c.id as dbg_id_cat,
            mp.id_cat as dbg_mp_id_cat,
            mp.id as margin_id,
            if(mp.id_currency=up.id_currency and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=p.id_provider and mp.id_cat=c.id,80,
            if(mp.id_currency=up.id_currency and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=p.id_provider and mp.id_cat=0,50,
            if(mp.id_currency=up.id_currency and mp.id_price_group=0 and mp.id_provider=p.id_provider and mp.id_cat=c.id,60,
            if(mp.id_currency=up.id_currency and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=0 and mp.id_cat=c.id,70,
            if(mp.id_currency=up.id_currency and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=0 and mp.id_cat=0,50,
            if(mp.id_currency=up.id_currency and mp.id_price_group=0 and mp.id_provider=0 and mp.id_cat=0,10,
            if(mp.id_currency=up.id_currency and mp.id_price_group=0 and mp.id_provider=p.id_provider and mp.id_cat=0,20,
            if(mp.id_currency=up.id_currency and mp.id_price_group=0 and mp.id_provider=0 and mp.id_cat=c.id,30,
            
            if(mp.id_currency=0 and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=p.id_provider and mp.id_cat=c.id,8,
            if(mp.id_currency=0 and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=p.id_provider and mp.id_cat=0,5,
            if(mp.id_currency=0 and mp.id_price_group=0 and mp.id_provider=p.id_provider and mp.id_cat=c.id,6,
            if(mp.id_currency=0 and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=0 and mp.id_cat=c.id,7,
            if(mp.id_currency=0 and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=0 and mp.id_cat=0,4,
            if(mp.id_currency=0 and mp.id_price_group=0 and mp.id_provider=0 and mp.id_cat=0,3,
            if(mp.id_currency=0 and mp.id_price_group=0 and mp.id_provider=p.id_provider and mp.id_cat=0,2,
            if(mp.id_currency=0 and mp.id_price_group=0 and mp.id_provider=0 and mp.id_cat=c.id,1,			0)))))))) )))))))) as priority
            ,mp.margin
            ,p.id
            from price as p
            left join price_group_assign as pgs on pgs.item_code=p.item_code
            inner join cat as c on p.pref=c.pref and c.visible=1
            inner join provider_virtual as pv on p.id_provider=pv.id_provider
            inner join user_provider as up on pv.id_provider_virtual=up.id_user
            inner join provider_group as pg on up.id_provider_group=pg.id
            inner join user as u on up.id_user=u.id and u.visible=1
            inner join currency as cu FORCE INDEX (PRIMARY) on up.id_currency=cu.id
            left join price_group prg on prg.id=pgs.id_price_group
            left join margin_price as mp on
	        ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=up.id_currency and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=p.id_provider and mp.id_cat=c.id) or 
            ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=up.id_currency and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=p.id_provider and mp.id_cat=0) or 
            ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=up.id_currency and mp.id_price_group=0 and mp.id_provider=p.id_provider and mp.id_cat=c.id) or 
            ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=up.id_currency and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=0 and mp.id_cat=c.id) or 
            ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=up.id_currency and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=0 and mp.id_cat=0) or 
            ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=up.id_currency and mp.id_price_group=0 and mp.id_provider=0 and mp.id_cat=0) or 
            ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=up.id_currency and mp.id_price_group=0 and mp.id_provider=p.id_provider and mp.id_cat=0) or 
            ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=up.id_currency and mp.id_price_group=0 and mp.id_provider=0 and mp.id_cat=c.id)
            or
            ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=0 and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=p.id_provider and mp.id_cat=c.id) or 
            ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=0 and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=p.id_provider and mp.id_cat=0) or 
            ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=0 and mp.id_price_group=0 and mp.id_provider=p.id_provider and mp.id_cat=c.id) or 
            ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=0 and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=0 and mp.id_cat=c.id) or 
            ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=0 and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=0 and mp.id_cat=0) or 
            ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=0 and mp.id_price_group=0 and mp.id_provider=0 and mp.id_cat=0) or 
            ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=0 and mp.id_price_group=0 and mp.id_provider=p.id_provider and mp.id_cat=0) or 
            ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=0 and mp.id_price_group=0 and mp.id_provider=0 and mp.id_cat=c.id)
            where 1=1 and p.id_margin_price=0 and p.price>0 
	              
	        having 1 order by priority desc, mp.id desc) as t group by t.id
            ) as margin_range
            set price.id_margin_price=margin_range.margin_id
            where price.id=margin_range.id");
	     
	}
	//-----------------------------------------------------------------------------------------------
	// проверить, если детали заказа собраны, перевести заказ в собран (assembled)
	// лог обновить и флаг колокольчика выставить
	public static function AssumedCartPackage($iIdCartPackage,$sOrderStatus) {
		// log cart package
		if ($sOrderStatus=='removed')
			Base::$db->Execute("insert into cart_package_log (id_cart_package,id_user_manager,post_date,order_status,comment,ip)
		    	values ('".$iIdCartPackage."','".Auth::$aUser['id_user']."','".date("Y-m-d H:i:s")."','removed_cart','','".Auth::GetIp()."')");
		
		$iCnt = Db::getOne("Select count(*) from cart where id_cart_package=".$iIdCartPackage.
			" and order_status!='accrued' and order_status!='removed' and order_status!='refused'");
		if ($iCnt==0) {
			$sStatusOrder = 'assembled';
			Base::$db->Execute("update cart_package set	order_status='".$sStatusOrder."'
				,post_date_changed='".date("Y-m-d H:i:s")."'
				where id='".$iIdCartPackage."'");
			// log 
			Base::$db->Execute("insert into cart_package_log (id_cart_package,id_user_manager,post_date,order_status,comment,ip)
		    	values ('".$iIdCartPackage."',".Auth::$aUser['id_user'].",'".date("Y-m-d H:i:s")."','".$sStatusOrder."','','".Auth::GetIp()."')");
		}		
	}
	//-----------------------------------------------------------------------------------------------
	public function CheckProviderLogAccountAmount() {
		$aProvider = Db::getAll("Select * from user where type_='provider' and visible=1");
		$aAmountBalance = Db::GetAssoc("Select id_user,amount from user_account ua
			inner join user u on u.id = ua.id_user and u.type_='provider' and u.visible=1");
		$aProviderGroup = Db::getAssoc("Select id_user,id_group from user_provider_group");
		$aProviderGroupAmount = Db::getAssoc("Select id,amount from user_provider_group_main");
		$aProviderGroupAssoc = Db::getAssoc("Select id,pref from user_provider_group_main");
		if ($aProviderGroup) {
			$aGroupAssoc = array();
			foreach ($aProviderGroup as $iIdUser => $iIdGroup)
				$aGroupAssoc[$iIdGroup][] = $iIdUser;
		}
		//$aErrorProvider = array();
		$aError = array();
		if ($aProvider) {
			foreach ($aProvider as $aValue) {
				if ($aProviderGroup[$aValue['id']]) {
					//$aIDUser = Db::getAssoc("Select id_user as key_, id_user from user_provider_group where id_group=".$aProviderGroup[$aValue['id']]);
					$aIDUser = array($aValue['id'] => $aValue['id']);
					//continue;
				}
				else {
					$aIDUser = array($aValue['id'] => $aValue['id']);
					//continue;
				}
				$aLog = Db::getAll("Select l.* from user_account_log l
						left join cart_package cp on cp.id = l.custom_id 
						where (cp.id is not Null or l.data='debt_provider' or l.data='prepay_provider') and 
						l.id_user in (".implode(',',array_keys($aIDUser)).") order by l.post_date,l.id");
				$iFirst = 0;
				$dBalance = 0;
				if ($aLog) {
					foreach ($aLog as $aLogItem) {
						if ($aLogItem['amount']==0)
							continue;
						if (!$iFirst) {
							$dBalance = $aLogItem['amount'];
							$iFirst = 1;
							if ($aLogItem['account_amount']!=$dBalance)
								Db::Execute("Update user_account_log set account_amount=".$dBalance." where id=".$aLogItem['id']);
							continue;
						}
						$dBalance += $aLogItem['amount'];
						if (abs($dBalance - $aLogItem['account_amount']) > 0.00001) {
							if (!$aErrorProvider[$aValue['id'].'_'.$aValue['login']])
								$aErrorProvider[$aValue['id'].'_'.$aValue['login']] = 'Log ID: '.$aLogItem['id'].' Реальный баланс:'.$dBalance.' не равен балансу в логе '.$aLogItem['account_amount']." group: ".$aProviderGroup[$aValue['id']];
							//Db::Execute("Update user_account_log set account_amount=".$dBalance." where id=".$aLogItem['id']);
							break;							
						}
					}
				}
				if (!$aErrorProvider[$aValue['id'].'_'.$aValue['login']]) {
					if (abs($aAmountBalance[$aValue['id']] - $dBalance) > 0.00001) {
						$aErrorProvider[$aValue['id'].'_'.$aValue['login']] = 'Баланс поставщика: '.$aAmountBalance[$aValue['id']].' не равен расчетному балансу в логе '.$dBalance." group: ".$aProviderGroup[$aValue['id']];
						//Db::Execute("Update user_account set amount=".$dBalance." where id_user = ".$aValue['id']);
					}
				}
			}
		}
		if ($aProviderGroup) {
			foreach ($aGroupAssoc as $iIdGroup => $aUsers) {
				$dBalanceGroupNow = Finance::DebtAmountGroup($iIdGroup);
				if (abs($aProviderGroupAmount[$iIdGroup]-$dBalanceGroupNow) > 0.00001) {
					$aErrorProvider[$iIdGroup] = $aProviderGroupAssoc[$iIdGroup].') Баланс группы: '.$aProviderGroupAmount[$iIdGroup].' не равен расчетному балансу в логе '.round($dBalanceGroupNow,2);
					//Db::Execute("update user_provider_group_main set amount=".$dBalanceGroupNow." where id='".$iIdGroup."'");
				}
			}
				
		}

		if ($aErrorProvider) {
			$sBody = '';
			foreach ($aErrorProvider as $sKey => $sStr)
				$sBody.=$sKey .') '.$sStr."\n<br>";
				
			Mail::AddDelayed(Language::getConstant('mail_error_balance_provider','info@mstarproject.com'),"Ошибки в балансе заказчиков",$sBody);				
		}
		//Debug::PrintPre($sBody);
	}
	//-----------------------------------------------------------------------------------------------
	public function CheckCustomerLogAccountAmount() {
		$aUser = Db::getAll("Select u.*,uc.name,ua.amount from user u
			inner join user_customer uc on uc.id_user = u.id
			left join user_account ua on ua.id_user = u.id
			where type_='customer'
		");
		foreach ($aUser as $aValue) {
			$dSum = Db::getOne("Select sum(amount) from user_account_log where id_user=".$aValue['id']);
			if (!$dSum)
				$dSum = 0;
			if ($dSum!=$aValue['amount'])
				$aError[] = $aValue['id'].'_'.$aValue['login'].'_'.$aValue['name'].') Баланс: '.$aValue['amount'].' не равен расчетному балансу в логе '.round($dSum,2);
		}
		$sBody = '';
		if ($aError)
		foreach ($aError as $sKey => $sStr)
			$sBody.=$sKey .') '.$sStr."\n<br>";
		
		Debug::PrintPre($sBody);
	}
}
?>
