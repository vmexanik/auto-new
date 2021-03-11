<?php
/**
 * @author Yuriy Korzun
 * ALTER TABLE `user_customer` ADD `id_1c` VARCHAR(50) NOT NULL;
 * ALTER TABLE `cart_package` ADD `id_1c` VARCHAR(50) NOT NULL;
 * ALTER TABLE `user_provider` ADD `id_1c` VARCHAR(50) NOT NULL;
 *
 *
 */

class Exchange extends Base
{
	var $sTempDir = '/imgbank/temp_upload/exchange/';
	var $iTimer;
	var $oXml; 
	var $iTimerMinute;
	var $bAutoImport = 0;
	var $aType=array(
	'sale'=>'sale',
	'catalog'=>'catalog',
	);
	//-----------------------------------------------------------------------------------------------
	private function Auth()
	{
			header('WWW-Authenticate: Basic realm="Who are you?"');
			header('HTTP/1.0 401 Unauthorized');
			die('Access denied');
	}
	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
		if(isset($_SERVER['HTTP_AUTHORIZATION'])) {
			$aAuthParams = explode(":" , base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
			$_SERVER['PHP_AUTH_USER'] = $aAuthParams[0];
			unset($aAuthParams[0]);
			$_SERVER['PHP_AUTH_PW'] = implode('',$aAuthParams);
		}
		if(Base::$aRequest['session']){
			$aUser=Db::GetRow("select * from user where password='".Base::$aRequest['session']."' and type_='manager'");
			$_SESSION['user']['isUser'.Auth::$sProjectName]=1;
			$_SESSION['user']['id']=$aUser['id'];
			$_SESSION['user']['type_']=$aUser['type_'];
		}
		$bIsAuth=Auth::IsAuth();
		//Debug::PrintPre($_SERVER);
		if (!$bIsAuth && !isset($_SERVER['PHP_AUTH_USER'])) {
			$this->Auth();
		} elseif (!$bIsAuth) {
			$aUser=Auth::IsUser($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW'],false,true);
			if(!$aUser || $aUser['type_']!='manager') $this->Auth();
			Base::$aRequest['remember_me']=1;
			Auth::Login($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW'],false,true,true);
		}
		Db::Execute("SET @lng_id = 16");
		Db::Execute("SET @cou_id = 187");
		$this->iTimer=time();
		$this->iTimerMinute=time();
	}
	//-----------------------------------------------------------------------------------------------
	public function PrintFlush($s){
		print "progress\n";
		print $s;
		ob_end_flush();
		ob_flush();
		flush();
		ob_flush();
		flush();
		ob_start();
	}
	//-----------------------------------------------------------------------------------------------
	public function PrintFlush2($s){
		print $s;
		ob_end_flush();
		ob_flush();
		flush();
		ob_flush();
		flush();
		ob_start();
	}
	//-----------------------------------------------------------------------------------------------
	public function Progress($i,$iMax,$bTimer=true)
	{
		$iDef=time()-($this->iTimerMinute);
		if ($iDef<60 && $bTimer) return;
		$this->iTimerMinute=time();
		$this->PrintFlush("Continued:".floor($i/$iMax*100)."%\n");
		return true;
	}
	//-----------------------------------------------------------------------------------------------
	private function OnFileName()
	{
	    if (!Base::$aRequest['filename']) die("failure\nNo file.");
    	    
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		set_time_limit(0);
		header("Expires: Thu, 01 Jan 1970 00:00:01 GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		$sType=Base::$aRequest['type'];
		$sMode=Base::$aRequest['mode'];
//********* Mode Type *******//		
       if(in_array($sType, $this->aType)){

   //********* Mode Checkaut *******//
		      if($sMode=='checkauth'){//Начало сеанса
			    	die("success\n" . session_name() . "\n" . session_id());
			   }
    //********* Mode Init *******//			
			
			   if($sMode=='init'){//Запрос параметров от сайта
				  $tmp_files = glob(SERVER_PATH.$this->sTempDir.'*.*');
				    if(is_array($tmp_files))
				      foreach($tmp_files as $v)
				       {
					//unlink($v);
				       }
				die("zip=no\nfile_limit=100000000\n");
				
			    }
		}
	switch ($sType){ 
		 case 'sale':
		  ob_start('ob_gzhandler');
		  ob_implicit_flush(0); // отключаем неявную отправку буфера
	        switch ($sMode){
	            case 'brands':
	                $this->SaleBrands();//выгрузка брендов
	                break;
	            case 'price':
	                $this->SalePrice();//выгрузка товаров
	                break;
	            case 'providers':
	                $this->SaleProviders();//выгрузка поставщиков
	                break;
	            case 'customers':
	               $this->SaleCustomers();//выгрузка контрагентов
				    break;
            	case 'orders':
				    $this->SaleOrders();//выгрузка заказов
			        break;
            	case 'success':
            	    $this->SaleSuccess();//для 1С
            	    break;
	        }
		case 'catalog':
            switch ($sMode){
		        case 'file':    
		            $this->CatalogFile();
		            break;
		        case 'import':
		            $this->CatalogImport();//импорт 
		            break;
            }
	    }
		die("failure\nNot implemented.");
		Debug::PrintPre(Base::$aRequest);
	}
	//-----------------------------------------------------------------------------------------------
	public function SendRequest($url, $params) {
		set_time_limit(0);
			foreach ($params as $key => &$val) {
				if (is_array($val)) $val = implode(',', $val);
				$post_params[] = $key.'='.urlencode($val);
			}
			if ($post_params)
				$post_string = implode('&', $post_params);
		
			$parts = parse_url($url);
		
			$fp = fsockopen($parts['host'],
					isset($parts['port'])?$parts['port']:80,
					$errno, $errstr, 30);
			stream_set_timeout($fp, 30000);
		
			$out = "GET /?".$post_string." HTTP/1.1\r\n";
			$out.= "Host: ".$parts['host']."\r\n";
			$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out.= "Content-Length: ".strlen($post_string)."\r\n";
			$out.= "Connection: Close\r\n\r\n";
			$out.= $post_string;
		
			fwrite($fp, $out);
			fclose($fp);
			return $errno;
	}
	//----------------------------------------------------------------------------------------------
	public function  SalePrice(){
	    $no_spaces = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n".
	        '<КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date ( 'Y-m-d' )  . '"></КоммерческаяИнформация>';
	    $oXml = new SimpleXMLElement ( $no_spaces );
	    $aPrice=Base::$db->getAll("select p.id , p.part_rus , p.id_provider ,p.code , p.description, p.item_code, p.cat, p.price, p.stock, c.is_brand 
	                               from price p
                                   left join cat as c on p.pref=c.pref
	                               LIMIT 5");
	    $doc0 = $oXml->addChild ("Товары");
	    if($aPrice)
	        foreach($aPrice as $aValue)
	        {
 	            $sLastExportId=$aValue['id'];
 	                if($sLastExportId<$aValue['id']);
 	                    $doc = $doc0->addChild ("Товар");
	                    $doc->addAttribute ( "Код", $aValue['id']);
        	            $doc->addAttribute ( "Название", $aValue['part_rus']);
        	            $doc->addAttribute ( "КодПоставщика", $aValue['id_provider']);
        	            //$doc->addAttribute ( "ЕденицаИзмерения", $aValue['']);
        	            $doc->addAttribute ( "Артикул", $aValue['code']);
        	            $doc->addAttribute ( "Описание", $aValue['description']);
        	            $doc->addAttribute ( "ПрефиксКод", $aValue['item_code']);
        	            $doc->addAttribute ( "Производитель", $aValue['cat']);
        	            $doc->addAttribute ( "Количество", $aValue['stock']);
        	            $doc->addAttribute ( "Цена", $aValue['price']);
        	             
	          //Db::Execute("update price set status_1c=1 where id='".$aValue['id']."'"); //признак выгрузки
	        }
	    header ( "Content-type: text/xml; charset=utf-8" );
	    $sOutput=$oXml->asXML();
	    print $sOutput;
	    header('Content-Length: '.ob_get_length());
	    ob_end_flush();
	    die();
	}
	//----------------------------------------------------------------------------------------------
	public function  SaleBrands(){
	    $no_spaces = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n".
	        '<КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date ( 'Y-m-d' )  . '"></КоммерческаяИнформация>';
	    $oXml = new SimpleXMLElement ( $no_spaces );
	    $aCat=Db::GetAll(Db::GetSql("Cat",array(
	        'where'=>"and c.visible=1 "
	    )));
	    
	    $doc0 = $oXml->addChild ("Бренды");
	    if($aCat)
	        foreach($aCat as $aValue)
	        {
 	            $sLastExportId=$aValue['id'];
 	            if($sLastExportId<$aValue['id']);
 	            $doc = $doc0->addChild ("Бренд");
 	            $doc->addAttribute ( "Номер", $aValue['id']);
 	            $doc->addAttribute ( "Название", $aValue['name']);
 	            $doc->addAttribute ( "Префикс", $aValue['pref']);
	        }
	    header ( "Content-type: text/xml; charset=utf-8" );
	    $sOutput=$oXml->asXML();
	    print $sOutput;
	    header('Content-Length: '.ob_get_length());
	    ob_end_flush();
	    die();
	}
	//----------------------------------------------------------------------------------------------
	public function  SaleProviders(){
	    $no_spaces = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n".
	        '<КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date ( 'Y-m-d' )  . '"></КоммерческаяИнформация>';
	    $oXml = new SimpleXMLElement ( $no_spaces );
	    $aProvider=Db::GetAll(Db::GetSql("Provider",array(
	        'where'=>"and u.visible=1 /*and up.status_1c<2  and u.post_date>SUBDATE(now(), INTERVAL 40 DAY)*/ "
	    )));
	    $doc0 = $oXml->addChild ("Поставщики");
	    if($aProvider)
	        foreach($aProvider as $aValue)
	        {
	            $sLastExportId=$aValue['id_user'];
	            if($sLastExportId<$aValue['id_user']);
	            $doc = $doc0->addChild ("Поставщик");
	            $doc->addAttribute ( "Номер", $aValue['id_user']);
	            $doc->addAttribute ( "Название", $aValue['name']);
	            $doc->addAttribute ( "Описание", $aValue['description']);
	            $doc->addAttribute ( "КодовоеНазвание", $aValue['code_name']);
	            $doc->addAttribute ( "Страна", $aValue['country']);
	            $doc->addAttribute ( "Город", $aValue['city']);
	            $doc->addAttribute ( "Адрес", $aValue['address']);
	            $doc->addAttribute ( "Телефон", $aValue['phone']);
	            $doc->addAttribute ( "Логин", $aValue['login']);
	            $doc->addAttribute ( "Почта", $aValue['email']);
	            $doc->addAttribute ( "СрокПоставки", $aValue['term']);
	            $doc->addAttribute ( "Наценка", $aValue['pg_name']);
	            $doc->addAttribute ( "Примечания", $aValue['remark']);
	            $doc->addAttribute ( "Идентификатор1С", $aValue['id_1c']);
	           // Db::Execute("update user_provider set status_1c=1 where id_user='".$aValue['id_user']."'");//признак выгрузки
	        }
	    header ( "Content-type: text/xml; charset=utf-8" );
	    $sOutput=$oXml->asXML();
	    print $sOutput;
	    header('Content-Length: '.ob_get_length());
	    ob_end_flush();
	    die();
	}
	//----------------------------------------------------------------------------------------------
	public function  SaleCustomers(){
	    $no_spaces = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n".
	        '<КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date ( 'Y-m-d' )  . '"></КоммерческаяИнформация>';
	    $oXml = new SimpleXMLElement ( $no_spaces );
	    $aCustomer=Db::GetAll(Db::GetSql("Customer",array(
	        'where'=>"and u.visible=1 /*and uc.name is not null and uc.status_1c<2  and u.post_date>SUBDATE(now(), INTERVAL 40 DAY)*/ "
	    )));
	    $doc0 = $oXml->addChild ("Контрагенты");
	    if($aCustomer)
	        foreach($aCustomer as $aValue)
	        {
	            $sLastExportId=$aValue['id_user'];
	            if($sLastExportId<$aValue['id_user']);
	            $doc = $doc0->addChild ("Контрагент");
	            $doc->addAttribute ( "Номер", $aValue['id_user']);
	            $doc->addAttribute ( "Идентификатор1С", $aValue['id_user_1c']);
	            $doc->addAttribute ( "ФИО", $aValue['name']);
	            $doc->addAttribute ( "Логин", $aValue['login']);
	            $doc->addAttribute ( "Почта", $aValue['email']);
	            $doc->addAttribute ( "Телефон", $aValue['phone']);
	            $doc->addAttribute ( "Город", $aValue['city']);
	            $doc->addAttribute ( "Адрес", $aValue['address']);
	            $doc->addAttribute ( "ДатаРегистрации", $aValue['post_date']);
	            $doc->addAttribute ( "ДатаПоследнегоВизита", $aValue['last_visit_date']);
	            $doc->addAttribute ( "Примечания", $aValue['remark']);
	            if($aValue['id_user_customer_type']=='2'){
	            $doc->addAttribute ( "ТипПользователя", Language::GetMessage('юридическое лицо'));
	            $doc->addAttribute ( "НазваниеОрганизации", $aValue['entity_name']);
	            $doc->addAttribute ( "КодЕДРПОУ", $aValue['additional_field1']);
	            $doc->addAttribute ( "ИПН", $aValue['additional_field2']);
	            $doc->addAttribute ( "БанковскиеPеквизиты", $aValue['additional_field3']);
	            $doc->addAttribute ( "ЮридическийAдрес", $aValue['additional_field4']);
	            $doc->addAttribute ( "ПочтовыйAдрес", $aValue['additional_field5']);
	            }else {
	            $doc->addAttribute ( "ТипПользователя", Language::GetMessage('частное лицо'));
	               }
	            //Db::Execute("update user_customer set status_1c=1 where id_user='".$aValue['id_user']."'");
	    }
	    header ( "Content-type: text/xml; charset=utf-8" );
	    $sOutput=$oXml->asXML();
	    print $sOutput;
	    header('Content-Length: '.ob_get_length());
	    ob_end_flush();
	    die();
	}
	//----------------------------------------------------------------------------------------------
	public function  SaleOrders (){
	    $no_spaces = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n".
	        '<КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date ( 'Y-m-d' )  . '"></КоммерческаяИнформация>';
	    $oXml = new SimpleXMLElement ( $no_spaces );
	    $docs = $oXml->addChild ("Заказы");
	    $aCartPackage=Db::GetAll(Db::GetSql("CartPackage",array(
	        'where'=>" and cp.order_status='new' /*and cp.post_date>SUBDATE(now(), INTERVAL 40 DAY)*/"
	        //'where'=>" and cp.id>'".$sLastId."'"
	    )));
	    if($aCartPackage)
	        foreach($aCartPackage as $aValue)
	        {
	            $aCart=Db::GetAll($s=Db::GetSql("Cart",array(
	                'where'=>" and c.id_cart_package='".$aValue['id']."'"
	            )));
	            //Debug::PrintPre($aCart,false);
	            if($aCart){
	                $sLastExportId=$aValue['id'];
	                if($sLastExportId<$aValue['id']);
	                $doc = $docs->addChild ("Заказ");
	                $doc->addAttribute ( "НомерЗаказа", $aValue['id']);
	                $doc->addAttribute ( "НомерЗаказа1C", $aValue['id_1c']);
	                $doc->addAttribute ( "ДатаЗаказа", date('Y-m-d',  strtotime($aValue['post_date'])));
	                $doc->addAttribute ( "ВремяЗаказа",  date('H:i:s',  strtotime($aValue['post_date'])));
	                $doc->addAttribute ( "СуммаЗаказа", $aValue['price_total']);
	                $doc->addAttribute ( "СпособОплаты", $aValue['payment_type_name']);
	                $doc->addAttribute ( "Доставка", $aValue['delivery_type_name']);
	                $doc->addAttribute ( "ИдМенеджера", $aValue['id_manager']);
	                $doc->addAttribute ( "СтатусЗаказа", $aValue['order_status']);
	                $user = $doc->addChild ( 'Контрагент' );
	                $user->addAttribute ( "Номер", $aValue['id_user']);
	                $user->addAttribute ( "ФИО", $aValue['name']);
	                $user->addAttribute ( "Идентификатор1С", $aValue['id_user_1c']);
	                $user->addAttribute ( "Логин", $aValue['login']);
	                $user->addAttribute ( "Почта", $aValue['email']);
	                $user->addAttribute ( "Телефон", $aValue['phone']);
	                $user->addAttribute ( "Город", $aValue['city']);
	                $user->addAttribute ( "Адрес", $aValue['address']);
//     	            if($aValue['id_user_customer_type']=='2'){
//     	            $user->addAttribute ( "ТипПользователя", Language::GetMessage('юридическое лицо'));
//     	            $user->addAttribute ( "НазваниеОрганизации", $aValue['entity_name']);
//     	            $user->addAttribute ( "КодЕДРПОУ", $aValue['additional_field1']);
//     	            $user->addAttribute ( "ИПН", $aValue['additional_field2']);
//     	            $user->addAttribute ( "БанковскиеPеквизиты", $aValue['additional_field3']);
//     	            $user->addAttribute ( "ЮридическийAдрес", $aValue['additional_field4']);
//     	            $user->addAttribute ( "ПочтовыйAдрес", $aValue['additional_field5']);
//     	            }else {
//     	            $user->addAttribute ( "ТипПользователя", Language::GetMessage('частное лицо'));
//    	               }
	              //  Db::Execute("update cart_package set status_1c=1 where id='".$aValue['id']."'");
    	              $doc = $doc->addChild ("Товары");
	                   foreach($aCart as $aValueCart){
	                    $t1_1 = $doc->addChild ( 'Товар' );
	                    $t1_2 = $t1_1->addAttribute ( "Ид", $aValueCart['id']);
	                    $t1_2 = $t1_1->addAttribute ( "Код", $aValueCart['code']);
	                    $t1_2 = $t1_1->addAttribute ( "Цена", $aValueCart['price']);
	                    $t1_2 = $t1_1->addAttribute ( "ЗакупочнаяЦена", $aValueCart['price_original']);
	                    $t1_2 = $t1_1->addAttribute ( "Количество", $aValueCart['number']);
	                    $t1_2 = $t1_1->addAttribute ( "Сумма", $aValueCart['price']*$aValueCart['number'] );
	                    $t1_2 = $t1_1->addAttribute ( "КомментарийМереджера", $aValueCart['manager_comment']);
	                    $t1_2 = $t1_1->addAttribute ( "СрокПоставки", $aValueCart['term']);
	                    $t1_2 = $t1_1->addAttribute ( "СтатусТовара", $aValueCart['order_status']);
	                    $t1_2 = $t1_1->addAttribute ( "КодПоставщика", $aValueCart['id_provider']);
	                    $t1_2 = $t1_1->addAttribute ( "Постaвщик", $aValueCart['provider_name']);
	                    $t1_2 = $t1_1->addAttribute ( "ПрефиксКод", $aValueCart['item_code']);
	                    $t1_2 = $t1_1->addAttribute ( "Префикс", $aValueCart['pref']);
	                    $t1_2 = $t1_1->addAttribute ( "Производитель", $aValueCart['cat_name']);
	                    $t1_2 = $t1_1->addAttribute ( "Название", $aValueCart['name_translate']);
	                    $t1_2 = $t1_1->addAttribute ( "Идентификатор1С", $aValueCart['id_cart_1c']);	                   }
	            }
	        }
	    //Base::UpdateConstant('exchange:last_order_export_id_tmp',$sLastExportId);
	    
	    header ( "Content-type: text/xml; charset=utf-8" );
	    $sOutput=$oXml->asXML();
	    print $sOutput;
	    header('Content-Length: '.ob_get_length());
	    ob_end_flush();
	    die();
	}
	//----------------------------------------------------------------------------------------------
	public function SaleSuccess(){
	        Db::Execute("update user_customer set status_1c=2 where status_1c=1");
	//				$a=Db::GetAssoc("select id,id as name from cart_package where status_1c=1");
	//				if($a)
	    //				foreach ($a as $value) {
	    //					Cart::SendPendingWork($value);
	    //				}
	        Db::Execute("update cart_package set status_1c=2 where status_1c=1");
	        die();
	        Base::UpdateConstant('exchange:last_export_date',date("Y-m-d H:i:s"));
	        Base::UpdateConstant('exchange:last_order_export_id',Base::GetConstant('exchange:last_order_export_id_tmp','0'));
	        die();
        }
	 //---------------------------------------------------------------------------------------------
     public function  CatalogFile(){
            $this->OnFileName();
            $sFileName = SERVER_PATH.$this->sTempDir.Base::$aRequest['filename'];
            $f = fopen($sFileName, 'w');
            fwrite($f, file_get_contents('php://input'));
            fclose($f);
            if(stripos(Base::$aRequest['filename'],'update')!==FALSE){
                Base::UpdateConstant('exchange:import_bra','0');
                Base::UpdateConstant('exchange:import_nom','0');
                Base::UpdateConstant('exchange:import_kon','0');
                Base::UpdateConstant('exchange:import_ski','0');
                Base::UpdateConstant('exchange:import_doc','0');
                Base::UpdateConstant('exchange:time_'.Base::$aRequest['filename'],date('Y-m-d H:i:s'));
                if($this->bAutoImport){
                    $this->PrintFlush2("success\n");
                    $url = 'http://'.$_SERVER['HTTP_HOST'];
                    $params = array(
                        'action' => 'exchange',
                        'mode' => 'import',
                        'type' => 'catalog',
                        'filename' => Base::$aRequest['filename'],
                        'session' => Auth::$aUser['password'],
                    );
                    $r=$this->SendRequest($url, $params);
                    $params['return']=$r;
                    Base::UpdateConstant('exchange:params_'.Base::$aRequest['filename'],print_r($params,true));
                    $sNeedImport = 'import';
                    die();
                }
            }
            elseif(stripos(Base::$aRequest['filename'],'sverka')!==FALSE){
                $sNeedImport = 'sverka';
            }
            if(!$this->bAutoImport) die("success");
        }
        //---------------------------------------------------------------------------------------------
        public function CatalogImport(){
            $this->OnFileName();
            $sFileName = SERVER_PATH.$this->sTempDir.Base::$aRequest['filename'];
            if(!file_exists($sFileName)) die("failure\nFile not found.");
            $this->aNamePref=Base::$db->getAssoc("select upper(cp.name),cat.pref from cat_pref cp inner join cat on cat.id=cp.cat_id
					union select upper(title),pref from cat
					union select upper(pref),pref from cat");
            $this->oXml = simplexml_load_file($sFileName);
            $this->iMax=10;
            $this->iProgress=1;
            $this->iMax++;
            if(Base::GetConstant('exchange:import_ski','0'))$this->iProgress++;
            $this->iTimerMinute=time();
        
        foreach ($this->oXml as $key => $val) {
            switch ($key){
                case 'Бренды':
                    $this->CatalogImportBrands(); 
                    break;
                case 'Товары':
                    $this->CatalogImportPrice();
                    break;
                case 'Поставщики':
                    $this->CatalogImportProviders();
                    break;
                case 'Контрагенты':
                    $this->CatalogImportCustomers();
                    Base::UpdateConstant('exchange:import_kon','1');
                    $this->iTimerMinute=time();
                    break;
                case 'Заказы':
                    $this->CatalogImportOrders();
                    break;
                case 'Кроссы':
                   	$this->CatalogImportCrosses();
                   	break;
               	case 'ВсеКроссы':
               		$this->CatalogImportAllCrosses();
               		break;
            }
        }
        @copy($sFileName, SERVER_PATH.$this->sTempDir."log/".date('Y-m-d_H-i-s_').Base::$aRequest['filename']);
       die("success");
        }
        //---------------------------------------------------------------------------------------------
        public function CatalogImportBrands(){
        	$sAttr1 = 'Бренды';$sAttr2 = 'Бренд';
            //$aCat=Base::$db->getAssoc("select pref,c.* from cat c");
            $oOffer=$this->oXml->$sAttr1;
            if($oOffer->$sAttr2)
                foreach($oOffer->$sAttr2 as $aValue)
                {
                    $aCart=json_decode(json_encode($aValue), TRUE); //(array)$aValue;
                    $aCart=$aCart['@attributes'];
                    $sName=trim(mb_strtoupper($aCart['Название']));
                    $sPref=$this->aNamePref[$sName];
                    if(!$sPref){
                        Db::Execute("insert ignore into cat_pref (name) values ('".mysql_escape_string ($sName)."')");
                        continue;
                    }
              }
        }
        //---------------------------------------------------------------------------------------------
        public function CatalogImportProviders(){
        	$sAttr1 = 'Поставщики';$sAttr2 = 'Поставщик';
            $oOffer=$this->oXml->$sAttr1;
            if($oOffer->$sAttr2)
                foreach($oOffer->$sAttr2 as $aValue)
                {
                    $this->iProgress++;
                    $aProvider1C=json_decode(json_encode($aValue), TRUE);
                    $aProvider1C=$aProvider1C['@attributes'];
                    foreach ($aProvider1C as $key => $value) {
                        $aProvider1C[$key]=trim($value);
                    }
                    $aProvider=Db::GetRow("select up.*,u.login from user_provider up inner join user u on u.id=up.id_user
							where (up.id_1c!='' && up.id_1c='".$aProvider1C['Идентификатор1С']."') or u.login='".$aProvider1C['Логин']."'");
                    $sProviderGroupId=Db::GetRow("select id from provider_group where name='".$aProvider1C['Наценка']."'");
                    if($aProvider['id_user']){  //меняем данные поставщика
                        Db::Execute("update user_provider set status_1c=2
                                ,id_1c='".mysql_escape_string($aProvider1C['Идентификатор1С'])."'
                                ,name='".mysql_escape_string($aProvider1C['Название'])."'
                                ,description='".mysql_escape_string($aProvider1C['Описание'])."'
                                ,code_name='".mysql_escape_string($aProvider1C['КодовоеНазвание'])."'
                                ,country='".mysql_escape_string($aProvider1C['Страна'])."'
                                ,city='".mysql_escape_string($aProvider1C['Город'])."'
                                ,address='".$aProvider1C['Адрес']."'
                                ,phone='".mysql_escape_string($aProvider1C['Телефон'])."'
                                ,term='".$aProvider1C['СрокПоставки']."'
                                ,id_provider_group='".$sProviderGroupId['id']."'
                                ,last_date_work='".$aProvider1C['ДатаПоследнегоЗаказа']."'
                                ,remark='".$aProvider1C['Примечания']."'
                                where id_user='".$aProvider['id_user']."'");
                        
/*                        if(trim($aProvider1C['Почта']) && ($aProvider1C['head']=='' || $aProvider1C['head']==$aProvider1C['Номер'] )){
                            $sLogin=$aProvider1C['Почта'];
                        }else{*/
                            $sLogin=$aProvider1C['Логин'];
                        //}
                        $sLogin=trim($sLogin);
                        if(!$sLogin){
                            Debug::PrintPre($aProvider,false);
                            $aProvider1C['error']='Empty login/email';
                            Debug::PrintPre($aProvider1C,false);
                            continue;
                        }
                        Db::Execute("update user set email='".mysql_escape_string($aProvider1C['Почта'])."',
								login='".mysql_escape_string($sLogin)."' where id='".$aProvider['id_user']."'");
                    }else{ // добавляем нового пользователя/поставщика с 1С
                        Base::$aRequest['data']['name']=$aProvider1C['Название'];
                        $aCustomer=Auth::AutoCreateUser();
                        $sPassword=Auth::GeneratePassword();
                        $sSalt=String::GenerateSalt();
                        $sLogin='am'.$aCustomer['login'];
                        if(Db::GetOne($s="select count(*) from user where
								login='".mysql_escape_string($sLogin)."' and id!='".$aCustomer['id_user']."'")){
        								$aProvider1C['error']='Dublicate login/email';
        								Debug::PrintPre($aProvider1C,false);
        								continue;
                        }
                        Db::Execute("update user set
								login='".mysql_escape_string($sLogin)."',
								email='".mysql_escape_string($aProvider1C['Почта'])."',
								password='".String::Md5Salt($sPassword,$sSalt)."',
								password_temp='".$sPassword."',
								type_='provider',
								salt='".$sSalt."',
								receive_notification='".Base::GetConstant("user:default_notification","0")."'
								where id='".$aCustomer['id_user']."'");
                        Db::Execute($ss=" insert into user_provider set status_1c=2
                                ,id_user='".mysql_escape_string($aCustomer['id_user'])."'
                                ,id_1c='".mysql_escape_string($aProvider1C['Идентификатор1С'])."'
                                ,name='".mysql_escape_string($aProvider1C['Название'])."'
                                ,description='".mysql_escape_string($aProvider1C['Описание'])."'
                                ,code_name='".mysql_escape_string($aProvider1C['КодовоеНазвание'])."'
                                ,country='".mysql_escape_string($aProvider1C['Страна'])."'
                                ,city='".mysql_escape_string($aProvider1C['Город'])."'
                                ,address='".$aProvider1C['Адрес']."'
                                ,phone='".mysql_escape_string($aProvider1C['Телефон'])."'
                                ,term='".$aProvider1C['СрокПоставки']."'
                                ,id_provider_group='".$sProviderGroupId['id']."'
                                ,last_date_work='".$aProvider1C['ДатаПоследнегоЗаказа']."'
                                ,remark='".$aProvider1C['Примечания']."'");
                    }
                }
        }
        //---------------------------------------------------------------------------------------------
        public function CatalogImportCustomers(){
        	$sAttr1 = 'Контрагенты';$sAttr2 = 'Контрагент';
            $oOffer=$this->oXml->$sAttr1;
            if($oOffer->$sAttr2)
                foreach($oOffer->$sAttr2 as $aValue)
                {
                    $aCustomer1C=json_decode(json_encode($aValue), TRUE);
                    $aCustomer1C=$aCustomer1C['@attributes'];
                    foreach ($aCustomer1C as $key => $value) {
                        $aCustomer1C[$key]=trim($value);
                    }
                        if ($aCustomer1C ['ТипПользователя']==Language::GetMessage('частное лицо')){
                            $aCustomer1C ['ТипПользователя']=1;
                            }else {
                            $aCustomer1C ['ТипПользователя']=2;
                         }
                    $aCustomer=Db::GetRow("select uc.*,u.login from user_customer uc inner join user u on u.id=uc.id_user
							where uc.id_1c='".$aCustomer1C['Идентификатор1С']."' or u.login='".$aCustomer1C['Логин']."'");
                    if($aCustomer['id_user']){  //меняем данные пользователя
                        Db::Execute("update user_customer set
                                 id_1c='".mysql_escape_string($aCustomer1C['Идентификатор1С'])."'
                                ,name='".mysql_escape_string($aCustomer1C['ФИО'])."'
                                ,city='".mysql_escape_string($aCustomer1C['Город'])."'
                                ,address='".$aCustomer1C['Адрес']."'
                                ,phone='".mysql_escape_string($aCustomer1C['Телефон'])."'
                                ,remark='".$aCustomer1C['Примечания']."'
                                ,id_user_customer_type='".$aCustomer1C['ТипПользователя']."'
                                ,entity_name='".$aCustomer1C['НазваниеОрганизации']."'
                                ,additional_field1='".mysql_escape_string($aCustomer1C['КодЕДРПОУ'])."'
                                ,additional_field2='".mysql_escape_string($aCustomer1C['ИПН'])."'
                                ,additional_field3='".$aCustomer1C['БанковскиеPеквизиты']."'
                                ,additional_field4='".$aCustomer1C['ЮридическийAдрес']."'
                                ,additional_field5='".$aCustomer1C['ПочтовыйAдрес']."'
                                where id_user='".$aCustomer['id_user']."'");
                        $sLogin=trim($aCustomer['login']);
                        if(!$sLogin){
                            Debug::PrintPre($aCustomer,false);
                            $aCustomer1C['error']='Empty login/email';
                            Debug::PrintPre($aCustomer1C,false);
                            continue;
                        }
                        Db::Execute("update user set email='".mysql_escape_string($aCustomer1C['Почта'])."',
								login='".mysql_escape_string($sLogin)."' where id='".$aCustomer['id_user']."'");
                    }else{ // добавляем нового пользователя с 1С
                        
                        Base::$aRequest['data']['name']=$aCustomer1C['ФИО'];
                        $aCustomer=Auth::AutoCreateUser();
                        $sSalt=String::GenerateSalt();
                        $sPassword='123456';
                        $sLogin=$aCustomer1C['Логин'];
                        if(Db::GetOne("select count(*) from user where
								login='".mysql_escape_string($sLogin)."' and id!='".$aCustomer['id_user']."'")){
                        								$aCustomer1C['error']='Dublicate login/email';
                        								Debug::PrintPre($aCustomer1C,false);
                        								continue;
                        }
                        Db::Execute("update user set
								login='".mysql_escape_string($sLogin)."',
								email='".mysql_escape_string($aCustomer1C['Почта'])."',
								password='".String::Md5Salt($sPassword,$sSalt)."',
								password_temp='".$sPassword."',
								salt='".$sSalt."',
                                is_temp='0',
								receive_notification='".Base::GetConstant("user:default_notification","0")."'
								where id='".$aCustomer['id_user']."'");
                        Db::Execute("update user_customer set
                                id_1c='".$aCustomer1C['Идентификатор1С']."'
                                ,name='".mysql_escape_string($aCustomer1C['ФИО'])."'
                                ,phone='".mysql_escape_string($aCustomer1C['Телефон'])."'
                                ,city='".mysql_escape_string($aCustomer1C['Город'])."'
                                ,address='".$aCustomer1C['Адрес']."'
                                ,remark='".$aCustomer1C['Примечания']."'
                                ,id_user_customer_type='".$aCustomer1C['ТипПользователя']."'
                                where id_user='".$aCustomer['id_user']."'");
                       }
                }
        }
       //---------------------------------------------------------------------------------------------
       public function CatalogImportPrice(){ //&& !Base::GetConstant('exchange:import_nom','0')
       	$sAttr1 = 'Товары';$sAttr2 = 'Товар';$sAttr3 = 'ОчиститьПоставщиков';
        $oOffer=$this->oXml->$sAttr1;
        if($oOffer->$sAttr3){
            $aClearProvider=json_decode(json_encode($oOffer->$sAttr3), TRUE);
            $sClearProvider=trim(str_replace(' ','',$aClearProvider['@attributes']['Ид']));
            Db::Execute("delete from price where id_provider in (".$sClearProvider.")");
        }
        if($oOffer->$sAttr2){
            foreach($oOffer->$sAttr2 as $aValue)
            {
                $aCart=json_decode(json_encode($aValue), TRUE); //(array)$aValue;
                if ($aCart['@attributes'])
                    $aCart=$aCart['@attributes'];
                $iProvider=$aValue['КодПоставщика'];
                $sBrand=strtoupper(trim($aValue['Производитель']));
                $sPref=$this->aNamePref[$sBrand];
                $sCode=Catalog::StripCode($aCart['Артикул']);
                $sCodeIn=str_replace( "'", "",$aCart['Артикул']);
                if(!$sPref) Db::Execute("insert ignore into cat_pref (name) values ('".mysql_escape_string ($sBrand)."')");
                if(!$sPref || !$sCode) continue;
                $sPrice=(string)$aCart['Цена'];
                $sPrice=mb_ereg_replace ( '[^0-9\.,]*', '', $sPrice );
                $sPrice=str_replace( ',', '.', $sPrice );
                $sStock=$aCart['Количество']?$aCart['Количество']:'0';
                //  $sStock=(int)str_replace( ',', '.', $sStock );
                
                Db::Execute(" insert into price SET
					item_code='".$sPref.'_'.$sCode."',
					id_provider='".$iProvider."',
					code='".$sCode."',
					code_in='".$sCodeIn."',
					part_rus='".mysql_real_escape_string($aCart['Название'])."',
					price='".$sPrice."',
					pref='".$sPref."',
					cat='".$sBrand."',
					stock='".$sStock."',
                    description='".mysql_real_escape_string($aCart['Описание'])."'
					on duplicate key update code_in=values(code_in),price=values(price), part_rus=values(part_rus), stock=values(stock), description=values(description)
					"
                );
               }
            }
        }
        //---------------------------------------------------------------------------------------------
		public function CatalogImportOrders(){
			$sAttr1 = 'Заказы';$sAttr2 = 'Заказ';
	        $oManager = new Manager();
	        $oOffer=$this->oXml->$sAttr1;
	        $iDoc=0;
	        $f = fopen(SERVER_PATH."/imgbank/temp_upload/exchange.log", "a");
	        if($oOffer->$sAttr2)
	            $aPaymentType=DB::GetAssoc("select name, id from payment_type");
	            $aDelivery=DB::GetAssoc("select name, id from delivery_type");
            foreach($oOffer->$sAttr2 as $aValue)
            {
             $aOrder1C=json_decode(json_encode($aValue), TRUE); //(array)$aValue;//                        //Debug::PrintPre($aOrder1C);
                // --------------------------------------------------- Обработка Заказ товара
                if($aOrder1C){
                    $oCart1C=$aOrder1C['Товары'];
                    $aUser1C=json_decode(json_encode($aOrder1C['Контрагент']['@attributes']), TRUE);
                    if ($aOrder1C['@attributes'])
                        $aOrder1C=$aOrder1C['@attributes'];
                    
                    fwrite($f, "Doc id=".$aOrder1C['НомерЗаказа']." 1c=".$aOrder1C['НомерЗаказа1C']."\n");
                    $aOrder= Db::GetRow("select * from cart_package where id='".$aOrder1C['НомерЗаказа']."' or id_1c='".$aOrder1C['НомерЗаказа1C']."'");
                    
                    if($aOrder['id']){
                        //заказ с сайта - надо проверить позиции
                        $sPrice=(string)$aOrder1C['СуммаЗаказа'];
                        $sPrice=mb_ereg_replace ( '[^0-9\.,]*', '', $sPrice );
                        $sPrice=str_replace( ',', '.', $sPrice );
                        $iIdPaymentType=$aPaymentType['СпособОплаты'];
                        $iIdDelivery=$aDelivery['Доставка'];
                        if (!$iIdPaymentType){$iIdPaymentType='2';}
                        if (!$iIdDelivery){$iIdDelivery='1';}
                        $aOrderUpdate=array(
                            'price_total'=>$sPrice,
                            'id_1c'=>$aOrder1C['НомерЗаказа1C'],
                            'id_payment_type'=>$iIdPaymentType,
                            'id_delivery_type'=>$iIdDelivery,
                            'order_status'=>$aOrder1C['СтатусЗаказа'],
                            'post_date_changed'=>date("Y-m-d H:i:s")
                        );
                        Db::AutoExecute("cart_package",$aOrderUpdate,'UPDATE'," id='".$aOrder['id']."'");
                        if ($aOrder['order_status']!=$aOrder1C['СтатусЗаказа']) {
	                        $iIdManager = (Auth::$aUser['type_']=='manager' ? Auth::$aUser['id_user'] : 0);
	                        // log
	                        Base::$db->Execute("insert into cart_package_log (id_cart_package,id_user_manager,post_date,order_status,comment,ip)
			    				values ('".$aOrder['id']."','".$iIdManager."','".date("Y-m-d H:i:s")."','".$aOrder1C['СтатусЗаказа']."','','".Auth::GetIp()."')");
                        }
        
                        $aCart=Db::GetAll("select * from cart where id_cart_package='".$aOrder['id']."'
									and type_='order'
									");
                        $aItemCode=array();
                        foreach($aCart as $iKey=>$aValueC){
                            $aItemCode[$aValueC['item_code']]['id']=$aValueC['id'];
                            $aItemCode[$aValueC['item_code']]['price']=$aValueC['price'];
                            $aItemCode[$aValueC['item_code']]['number']=$aValueC['number'];
                            $aItemCode[$aValueC['item_code']]['order_status']=$aValueC['order_status'];
                            $aItemCode[$aValueC['item_code']]['processed']=0;
                        }
                       
                        unset($aCartList);
                        
                
                        $aCartList =json_decode(json_encode($oCart1C['Товар']), TRUE);                           
                        if($aCartList)
                             
                            foreach($aCartList as $aValueC)
                            {
                                if($aValueC['@attributes']){
                                    $aValueC=$aValueC['@attributes'];
                                }
                                $aCart1C=$aValueC;
                                $aCart1CA=$aCart1C;
                                $sPrice=(string)$aCart1CA['Цена'];
                                $sPrice=mb_ereg_replace ( '[^0-9\.,]*', '', $sPrice );
                                $sPrice=str_replace( ',', '.', $sPrice );
                                $sPriceOriginal=(string)$aCart1CA['ЗакупочнаяЦена'];
                                $sPriceOriginal=mb_ereg_replace ( '[^0-9\.,]*', '', $sPriceOriginal );
                                $sPriceOriginal=str_replace( ',', '.', $sPriceOriginal );
                                $sPriceCurrency=(string)$aCart1CA['Сумма'];
                                $sPriceCurrency=mb_ereg_replace ( '[^0-9\.,]*', '', $sPriceCurrency );
                                $sPriceCurrency=str_replace( ',', '.', $sPriceCurrency );
                                $iNumber=(int)$aCart1CA['Количество'];
                                $sCode=Catalog::StripCode($aCart1CA['Код']);
                                $aCodeB=Db::GetRow("select * from price where code ='".$sCode."'");
                                if ($sCode!=$aCodeB['code']){
                                    die("Eror input data");
                                }
                                $sBrand=strtoupper(trim($aCart1CA['Производитель']));
                                $sStatusCart=$aCart1CA['СтатусТовара'];
                                $sPref=$this->aNamePref[$sBrand];

								if (!$sPref || !$sCode)
                                    die("Error input data");

                                $sItemCode=$sPref.'_'.$sCode;
                                if(isset($aItemCode[$sItemCode])){
                                    fwrite($f, "Cart id=".$aItemCode[$sItemCode]['id']."\n");
                                    if($aItemCode[$sItemCode]['price']!=$sPrice){
                                        fwrite($f, "Price site=".$aItemCode[$sItemCode]['price']." 1c=".$sPrice."\n");
                                        if($sPrice>0){
                                            Base::$aRequest['ignore_confirm_growth']=1;
                                            $oManager->ProcessOrderStatus($aItemCode[$sItemCode]['id'],'change_price','','','','',$sPrice);
                                            $aItemCode[$sItemCode]['log'].='price,';
                                        }else{
                                            fwrite($f, "-- NotChangePrice\n");
                                        }
                                    }
                                    if($aItemCode[$sItemCode]['number']!=$iNumber){
                                        fwrite($f, "Number site=".$aItemCode[$sItemCode]['number']." 1c=".$iNumber."\n");
                                        $oManager->ProcessOrderStatus($aItemCode[$sItemCode]['id'],'change_quantity','','','','',$iNumber);
                                        $aItemCode[$sItemCode]['log'].='number,';
                                    }
                                    if($aItemCode[$sItemCode]['order_status']!=$sStatusCart){
                                        fwrite($f, "Status site=".$aItemCode[$sItemCode]['order_status']." 1c=".$sStatusCart."\n");
                                        $oManager->ProcessOrderStatus($aItemCode[$sItemCode]['id'],$sStatusCart);
                                        $aItemCode[$sItemCode]['log'].=$sStatusCart.',';
                                    }
                                    $aItemCode[$sItemCode]['processed']=1;
                                }else{
                                    $aCart=array(
                                        'id_user'=>$aOrder['id_user'],
                                        'id_cart_package'=>$aOrder['id'],
                                        'code'=>$sCode,
                                        'pref'=>$sPref,
                                        'item_code'=>$sItemCode,
                                        'cat_name'=>(string)$aCart1CA['Производитель'],
                                        'number'=>$iNumber,
                                        'price'=>$sPrice,
                                        'price_original'=>$sPriceOriginal,
                                        'price_currency_user'=>$sPriceCurrency,
                                        'post_date'=>$aOrder1C['ДатаЗаказа'].' '.$aOrder1C['ВремяЗаказа'],
                                        'order_status'=>$sStatusCart,
                                        'id_provider'=>$aCart1CA['КодПоставщика'],
                                        'provider_name'=>$aCart1CA['Постaвщик'],
                                        'type_'=>'order',
                                        'name_translate'=>(string)($aCart1CA['Название']),
                                    );
                                    Db::AutoExecute("cart",$aCart);
                                    $iIdCart = Db::InsertId();
                                    // log
                                    Base::$db->Execute("insert into cart_log (id_cart,post,order_status,comment,id_user_manager)
                                    	values ('$iIdCart',UNIX_TIMESTAMP(),'".$sStatusCart."','$sComment',".$iIdManager.")");
                                }
                            }
                        foreach($aItemCode as $iKey=>$aValueIC){
                            if(!$aValueIC['processed']){
                                fwrite($f, "Refused id=".$aValueIC['id']."\n");
                                $oManager->ProcessOrderStatus($aValueIC['id'],'refused');
                                $aItemCode[$iKey]['log'].='refused,';
                            }
                        }
                        Cart::SendPendingWork($aOrder['id']);
                    }else{
                        //заказ новый из 1С - надо создать
                       if (!$aUser1C)
                            die("Error input data");
                        
                        if ($aUser1C ['ТипПользователя']==Language::GetMessage('частное лицо')){
                            $aUser1C ['ТипПользователя']=1;
                            }else {
                            $aUser1C ['ТипПользователя']=2;
                         }
                        $sWhere='';
                        if ($aUser1C['Номер'])
                            $sWhere = " and uc.id_user='".$aUser1C['Номер']."'";
                        if ($aUser1C['Логин'])
                            $sWhere .= " and (u.login='".$aUser1C['Логин']."' and u.type_='customer')";
                        
                        if (!$sWhere)
                            die("Error input data");
                        
                        $aCustomer=Db::GetRow("select uc.* from user_customer uc
                            inner join user u on u.id = uc.id_user 
                            where 1=1 ".$sWhere);
                        if(!$aCustomer['id_user']){
                            Base::$aRequest['data']['name']=$aUser1C['ФИО'];
                            $aCustomer=Auth::AutoCreateUser();
                            $sSalt=String::GenerateSalt();
                            $sPassword='123456';
                            $sLogin=$aCustomer1C['Логин'];
                            if(Db::GetOne($s="select count(*) from user where
								    login='".mysql_escape_string($sLogin)."' and id!='".$aCustomer['id_user']."'")){
        								    $aCustomer1C['error']='Dublicate login/email';
        								    Debug::PrintPre($aCustomer1C,false);
        								    continue;
                            }
                            Db::Execute("update user set
    								login='".mysql_escape_string($sLogin)."',
    								email='".mysql_escape_string($aUser1C['Почта'])."',
    								password='".String::Md5Salt($sPassword,$sSalt)."',
    								password_temp='".$sPassword."',
    								salt='".$sSalt."',
    								receive_notification='".Base::GetConstant("user:default_notification","0")."'
    								where id='".$aCustomer['id_user']."'");
                            Db::Execute("update user_customer set status_1c=2
                                    ,id_user_1c='".$aUser1C['Идентификатор1С']."'
                                    ,name='".mysql_escape_string($aUser1C['ФИО'])."'
                                    ,city='".mysql_escape_string($aUser1C['Город'])."'
                                    ,address='".$aUser1C['Адрес']."'
                                    ,phone='".mysql_escape_string($aUser1C['Телефон'])."'
                                    ,id_user_customer_type='".$aUser1C['ТипПользователя']."'
                                    ,entity_name='".$aUser1C['НазваниеОрганизации']."'
                                    ,additional_field1='".mysql_escape_string($aUser1C['КодЕДРПОУ'])."'
                                    ,additional_field2='".mysql_escape_string($aUser1C['ИПН'])."'
                                    ,additional_field3='".$aUser1C['БанковскиеPеквизиты']."'
                                    ,additional_field4='".$aUser1C['ЮридическийAдрес']."'
                                    ,additional_field5='".$aUser1C['ПочтовыйAдрес']."'
                                    where id_user='".$aCustomer['id_user']."'");
                             
                        }
                        $sPrice=(string)$aOrder1C['СуммаЗаказа'];
                        $sPrice=mb_ereg_replace ( '[^0-9\.,]*', '', $sPrice );
                        $sPrice=str_replace( ',', '.', $sPrice );
                        $iIdPaymentType=$aPaymentType['СпособОплаты'];
                        $iIdDelivery=$aDelivery['Доставка'];
                        if (!$iIdPaymentType){$iIdPaymentType='2';}
                        if (!$iIdDelivery){$iIdDelivery='1';}
                        $aOrder=array(
                            'id_user'=>$aCustomer['id_user'],
                            'price_total'=>$sPrice,
                            'price_delivery'=>0.00,
                            'id_payment_type'=>$iIdPaymentType,
                            'id_delivery_type'=>$iIdDelivery,
                            'post_date'=>$aOrder1C['ДатаЗаказа'].' '.$aOrder1C['ВремяЗаказа'],
                            'order_status'=>$aOrder1C['СтатусЗаказа'],
                            'id_1c'=>$aOrder1C['НомерЗаказа1C'],
                            'post_date'=>date("Y-m-d H:i:s"),
			    			'post_date_changed'=>date("Y-m-d H:i:s"),
                        );
                        
            			if (!$aOrder['id_payment_type'] || !$aOrder['id_delivery_type'])
            			    die("Error input data");

                        Db::AutoExecute("cart_package",$aOrder);
                        $iCartPackage=Db::InsertId();
                       	$iIdManager = (Auth::$aUser['type_']=='manager' ? Auth::$aUser['id_user'] : 0);
                       	// log
                       	Base::$db->Execute("insert into cart_package_log (id_cart_package,id_user_manager,post_date,order_status,comment,ip)
		    				values ('".$iCartPackage."','".$iIdManager."','".date("Y-m-d H:i:s")."','".$aOrder1C['@attributes']['СтатусЗаказа']."','','".Auth::GetIp()."')");
                        
                        unset($aCartList);
                        
                        $aCartList =json_decode(json_encode($oCart1C['Товар']), TRUE);                        
                        if($aCartList)
                            foreach($aCartList as $aValueC)
                            {
                                if($aValueC['@attributes']){
                                    $aValueC=$aValueC['@attributes'];
                                }
                                //$aCart1C=json_decode(json_encode($aValueC), TRUE); //(array)$aValueC;
                                $aCart1C=$aValueC;
                                $aCart1CA=$aCart1C;
                                $sPrice=(string)$aCart1CA['Цена'];
                                $sPrice=mb_ereg_replace ( '[^0-9\.,]*', '', $sPrice );
                                $sPrice=str_replace( ',', '.', $sPrice );
                                $sPriceOriginal=(string)$aCart1CA['ЗакупочнаяЦена'];
                                $sPriceOriginal=mb_ereg_replace ( '[^0-9\.,]*', '', $sPriceOriginal );
                                $sPriceOriginal=str_replace( ',', '.', $sPriceOriginal );
                                $sPriceCurrency=(string)$aCart1CA['Сумма'];
                                $sPriceCurrency=mb_ereg_replace ( '[^0-9\.,]*', '', $sPriceCurrency );
                                $sPriceCurrency=str_replace( ',', '.', $sPriceCurrency );
                                $sCode=Catalog::StripCode($aCart1CA['Код']);
                                $aCodeB=Db::GetRow("select * from price where code ='".$sCode."'");
                                if ($sCode!=$aCodeB['code']){
                                    die("Eror input data");
                                }
                                $sBrand=strtoupper(trim($aCart1CA['Производитель']));
                                $sStatusCart=$aCart1CA['СтатусТовара'];
                                $sPref= $this->aNamePref[$sBrand];
								if (!$sPref || !$sCode)
                                    die("Error input data");

                                $aCart=array(
                                    'id_user'=>$aCustomer['id_user'],
                                    'id_cart_package'=>$iCartPackage,
                                    'code'=>$sCode,
                                    'pref'=>$sPref,
                                    'item_code'=>$sPref.'_'.$sCode,
                                    'cat_name'=>(string)$aCart1CA['Производитель'],
                                    'number'=>(int)$aCart1CA['Количество'],
                                    'term'=>$aCart1CA['СрокПоставки'],
                                    'price'=>$sPrice,
                                    'price_original'=>$sPriceOriginal,
                                    'price_currency_user'=>$sPriceCurrency,
                                    'post_date'=>$aOrder1C['ДатаЗаказа'].' '.$aOrder1C['ВремяЗаказа'],
                                    'order_status'=>$sStatusCart,
                                    'type_'=>'order',
                                    'id_provider'=>$aCart1CA['КодПоставщика'],
                                    'provider_name'=>$aCart1CA['Постaвщик'],
                                    'name_translate'=>(string)($aCart1CA['Название']),
                                    'id_cart_1c'=>$aCart1CA['Идентификатор1С'],
                                );
                                Db::AutoExecute("cart",$aCart);
                                $iIdCart = Db::InsertId();
                                // log
                                Base::$db->Execute("insert into cart_log (id_cart,post,order_status,comment,id_user_manager)
                                values ('$iIdCart',UNIX_TIMESTAMP(),'".$sStatusCart."','$sComment',".$iIdManager.")");
                            }
                        Cart::SendPendingWork($iCartPackage);
                    }
                     
                }
                // --------------------------------------------------- Обработка Заказ товара
            }
        //Base::UpdateConstant('exchange:import_doc',$iDoc);
        fclose($f);
    }
    //---------------------------------------------------------------------------------------------
    public function CatalogImportCrosses() {
    	$sAttr1 = 'Кроссы';$sAttr2 = 'Кросс';
    	if (!isset($this->oXml->$sAttr1->$sAttr2))
    		return;
    	$oCrosses = $this->oXml->$sAttr1->$sAttr2;
		$aCross = array();
		$aBrand=Base::$db->getAssoc("SELECT upper( cp.name ) AS name, c.pref
                                			FROM cat_pref AS cp
                                			INNER JOIN cat AS c ON c.id = cp.cat_id"
	                );
		foreach($oCrosses as $o1cData) {
			$a1cData=(array)$o1cData;
			$a1cData=$a1cData['@attributes'];
			
			
			$sPref='';$sBrand=strtoupper(trim($a1cData['Производитель']));$sPref=$aBrand[$sBrand];
			if ($sPref)
    	    $aCross['pref'] = $sPref;
    	    $sPrefCrs='';$sBrand=strtoupper(trim($a1cData['КроссПроизводитель']));$sPrefCrs=$aBrand[$sBrand];
    	    if ($sPrefCrs)
    	        $aCross['pref_crs'] = $sPrefCrs;
    	
	        if (!$sPref || !$sPrefCrs){
	            $aError[]='Check brans on cross '.$a1cData['Производитель'].' - '.$a1cData['Артикул'].' on '.$a1cData['КроссПроизводитель'].' - '.$a1cData['КроссАртикул'];
	        }else{
	            $aCross['code']=Catalog::StripCode(strtoupper($a1cData['Артикул']));
	            $aCross['code_crs']=Catalog::StripCode(strtoupper($a1cData['КроссАртикул']));
	            $aCross['source']='Источник 1C';
	
            if ($aCross['pref'] && $aCross['code'] && $aCross['pref_crs'] && $aCross['code_crs'])
                Catalog::InsertCross($aCross);
			
	        }
			
    	}
    }
    //-----------------------------------------------------------------------------------------------
    public function CatalogImportAllCrosses($oXml) {
    	$sAttr1 = 'ВсеКроссы';$sAttr2 = 'Кросс';
    	if (!isset($this->oXml->$sAttr1->$sAttr2))
    		return;

		// del all crosses
    	Db::Execute("Delete from cat_cross");
    	$aCross = array();
    	$aBrand1C=Base::$db->getAssoc("select id,pref from 1c_brand");
    	$aPref=Base::$db->getAssoc("select pref, upper(title) as name from cat");
    	$oCrosses = $this->oXml->$sAttr1->$sAttr2;
    	foreach($oCrosses as $o1cData) {
    		$a1cData=(array)$o1cData;
    		$a1cData=$a1cData['@attributes'];
			$sPref='';$sBrand=trim($a1cData['Производитель']);$sPref=$aBrand1C[$sBrand];
			if ($sPref)
				$aCross['pref'] = $sPref;
			else
				continue;
    
			$sPref='';$sBrand=trim($a1cData['КроссПроизводитель']);$sPref=$aBrand1C[$sBrand];
			if ($sPref)
				$aCross['pref_crs'] = $sPref;
			else
				continue;
  					
			$aCross['code']=Catalog::StripCode(strtoupper($a1cData['Артикул']));
			//$aCross['pref_crs']=$aPref[strtoupper(trim($a1cData['КроссПроизводитель']))];
			$aCross['code_crs']=Catalog::StripCode(strtoupper($a1cData['КроссАртикул']));
			$aCross['source']=$a1cData['Источник'];
  					
			if ($aCross['pref'] && $aCross['code'] && $aCross['pref_crs'] && $aCross['code_crs'])
				Catalog::InsertCross($aCross);
    	}
    }
    //-----------------------------------------------------------------------------------------------
}
?>