<?
//include_once "single/NovaPoshta/bootstrap.php";
include_once SERVER_PATH."/single/NovaPoshtaApi2.php";
include_once SERVER_PATH."/single/NovaPoshtaApi2Areas.php";
class Novaposhta extends Base
{
    public function Novaposhta(){
        
    }
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
	    Auth::NeedAuth('manager');
	    $oNP = new NovaPoshtaApi2(Base::GetConstant('novaposhta:key','40526d7535b7e92437586008d93c7da7'));
	    if(Base::$aRequest['is_post']){
	       $aResponce=$this->SendEN(Base::$aRequest);
	       if(!$aResponce['success']){
	           Base::$sText.=$aResponce['errors'][0];
	       }
	    }
	    if(Base::$aRequest['area']&& !Base::$aRequest['city']){
	        $aCity=$this->GetCities(Base::$aRequest['area']);
	        Base::$tpl->assign('aCity',array("0"=>Language::getMessage('Выбирите город'))+ $aCity);
	        Base::$oResponse->addAssign('city','outerHTML',
	            Base::$tpl->fetch("nova/select_city.tpl"));
	    }
	    if(Base::$aRequest['area']&& Base::$aRequest['city']){
	        if(Base::$aRequest['ServiceType']=='WarehouseWarehouse'){
	        $aWarehouses=$this->GetWarehouses(Base::$aRequest);
	        Base::$tpl->assign('aAddress',array(""=>Language::getMessage('Выбирите отделение'))+$aWarehouses);
	        Base::$oResponse->addAssign('address','outerHTML',
	            Base::$tpl->fetch("nova/select_address.tpl"));
	        }
	        else{
	            $aWarehousesStreet=$this->GetWarehousesStreet(Base::$aRequest);
	            Base::$tpl->assign('aStreet',array(""=>Language::getMessage('Выбирите улицу'))+$aWarehousesStreet);
	            Base::$oResponse->addAssign('addressS','outerHTML',
	                Base::$tpl->fetch("nova/select_address2.tpl"));
	        }
	    }
	    
	    //get all EN for some order
	    $oAllEn = $this->GetAllEN(Base::$aRequest['id']);
	    if($oAllEn){
	      Base::$tpl->assign('aAllEn',$oAllEn->getTable());
	    }
	    //FORM for EN
	    Base::$sText.=Base::$tpl->fetch('nova/main.tpl');
   
	    Base::$sText.=$this->GenerateForm(Base::$aRequest['id']);
	    //$this->SendEN();
	}
	//----------------------------------------------------------
	private function GetAllEN($iOrder){
	    
	    $oTable=new Table();
	    $oTable->sSql="select * FROM nova_en where order_id=".$iOrder." and status=1";
	     
	    $oTable->aColumn=array(
	        'Id'=>array('sTitle'=>'#id'),
	        'order'=>array('sTitle'=>'order','sWidth'=>'10%'),
	        'Date'=>array('sTitle'=>'Date',),
	        'Link'=>array('sTitle'=>'Link','sWidth'=>'50%'),
	        'reestr'=>array('sTitle'=>'reestr',),
	    );
	    $oTable->sDataTemplate='nova/row_nova.tpl';
	    $oTable->aOrdered="order by id_en desc";

// 	    $oTable->aCallback=array($this,'CallParseNp');
	    return $oTable;
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseNp(&$aItem) {
	    if($aItem) {
	        foreach ($aItem as $sKey => $aValue) {
	            if(!$aValue['en_key']) {
	                $aTmp=unserialize($aValue['responce']);
	                $aItem[$sKey]['en_key']=$aTmp['data'][0]['Ref'];
	                Db::Execute("update nova_en set en_key='".$aItem[$sKey]['en_key']."' where id_en='".$aValue['id_en']."' ");
	            }
	        }
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function GetAllList() {
	    Base::$sText.=Base::$tpl->fetch('manager/link_custom.tpl');
	    
	    if(Base::$aRequest['is_post']) {
	        $aEn=Db::GetAssoc("select id_en,en_key FROM nova_en where 1=1 and status=1 and en_key is not null and reestr_key is null ");
	        
	        if($aEn) {
	            $oNP = new NovaPoshtaApi2(Base::GetConstant('novaposhta:key','40526d7535b7e92437586008d93c7da7'));

	            $oNP->model('ScanSheet');
	            $oNP->method('insertDocuments');
	            $oNP->params(array(
	                    'DocumentRefs'=>array_values($aEn)
	                ));
	            $oResult=$oNP->execute();
	            
	            if($oResult['success']) {
	                $sRef=$oResult['data'][0]['Ref'];
	                $sNumber=$oResult['data'][0]['Number'];
	                
	                Db::Execute("update nova_en set 
	                    reestr_key='".$sRef."', 
	                    reestr='".$sNumber."' 
	                    where id_en in ('".implode("','", array_keys($aEn))."')");
	            }
	        }
	        
	        Base::Redirect("/pages/novaposhta_list");
	    }
	    
	    $oTable=new Table();
	    $oTable->sSql="select * FROM nova_en where 1=1 and status=1 and en_key is not null";
	    
	    $oTable->aColumn=array(
	        'Id'=>array('sTitle'=>'#id'),
	        'order'=>array('sTitle'=>'order','sWidth'=>'10%'),
	        'Date'=>array('sTitle'=>'Date',),
	        'Link'=>array('sTitle'=>'Link',),
	        'reestr'=>array('sTitle'=>'reestr',),
	        'action'=>array(),
	    );
	    $oTable->sDataTemplate='nova/row_nova.tpl';
	    $oTable->aOrdered="order by id_en desc";
	    
	    Base::$sText.=Base::$tpl->fetch('nova/button_reestr.tpl');
	    Base::$sText.=$oTable->GetTable();
	}
	//----------------------------------------------------------
	private function GetCities($sArea){
	    $oNP = new NovaPoshtaApi2(Base::GetConstant('novaposhta:key','40526d7535b7e92437586008d93c7da7'));
	    $aResponceCity= $oNP->getCities2($sArea);
	    if($aResponceCity['success']){
	        foreach ($aResponceCity['data'][0] as $value){
	            $aCity[$value['Description']]=$value['Description'];
	        }
	    }
	    return $aCity;
	}
	private function GetWarehousesStreet($aResponce){
	    $oNP = new NovaPoshtaApi2(Base::GetConstant('novaposhta:key','40526d7535b7e92437586008d93c7da7'));
	    $senderCity =$oNP->getCity($aResponce['city'],$aResponce['area']);
	    if($senderCity['data'][0]['Ref']){
	        $s = $senderCity['data'][0]['Ref'];
	    }
	    else{
	        $s = $senderCity['data'][0][0]['Ref'];
	    }
	    for($iI = 1;$iI<=100; $iI++){
	    $aResponceStreet= $oNP->getStreet($s,'',$iI);
	    if(count($aResponceStreet['data'])>0){
	    if($aResponceStreet['success']){
	        foreach ($aResponceStreet['data'] as $value){
	            $aStreets[$value['Ref']] =$value['StreetsType']. $value['Description'];
	        }
	    }
	    }
	    else{
	        break;
	    }
	   }
	    return $aStreets;
	}
	private function GetWarehouses($aResponce){
	    $oNP = new NovaPoshtaApi2(Base::GetConstant('novaposhta:key','40526d7535b7e92437586008d93c7da7'));
            $senderCity =$oNP->getCity($aResponce['city'],$aResponce['area']);
            if($senderCity['data'][0]['Ref']){
                $s = $senderCity['data'][0]['Ref'];
            }
            else{
                $s = $senderCity['data'][0][0]['Ref'];
            }
            
        $aResponceWarehouses= $oNP->getWarehouses($s);
        if($aResponceWarehouses['success']){
            foreach ($aResponceWarehouses['data'] as $value){
                $aWarehouses[$value['Description']] = $value['Description'];
            }
        }
	    return $aWarehouses;
	}
	//----------------------------------------------------------
	private function GenerateForm($iId){
	    $oNP = new NovaPoshtaApi2(Base::GetConstant('novaposhta:key','40526d7535b7e92437586008d93c7da7'));
	    Base::$tpl->fetch('nova/profile.tpl');
	    $sSql1="select * from user_customer as c,cart_package as cp where c.id_user=cp.id_user and cp.id=".$iId." group by cp.id";
	    $aUser=Db::GetAll($sSql1);
	    Base::$tpl->assign('aManager',Auth::$aUser);
	    Base::$tpl->assign('aUser',$aUser[0]);
	    Base::$tpl->assign('aList',$this->GetOrderList($iId));
	    
	    $aAreas=array();
	    $aAreas['']=Language::getMessage('Выбирите область');
	    $aResponceAreas= $oNP->getAreas();
	    if($aResponceAreas['success']){
	        foreach ($aResponceAreas['data'] as $value){
	            $aAreas[$value['Description']]=$value['Description'];
	        }
	    }
	    
	    Base::$tpl->assign('aAreas',$aAreas);
	/*    
	    //check user delivery data
	    if($aDeliveryData) {
	        //select region
	        Base::$tpl->assign('sAreaSelected',$aDeliveryData['region']);
	        
	        //get and select city
	        $aCity=$this->GetCities($aDeliveryData['region']);
	        if($aCity) Base::$tpl->assign('aCity',array("0"=>Language::getMessage('Выбирите город'))+ $aCity);
	        //$smarty.request.data.city
	        $_REQUEST['data']['city']=str_replace('\\', '', $aDeliveryData['city']);
	        
	        
	        //get and select warehouse
	        $aWarehouses=$this->GetWarehouses(array(
	            'city'=>$aDeliveryData['city'],
	            'area'=>$aDeliveryData['region']
	        ));
	        if($aWarehouses) Base::$tpl->assign('aAddress',array(""=>Language::getMessage('Выбирите отделение'))+$aWarehouses);
	        //$smarty.request.data.address
            $_REQUEST['data']['address']=str_replace('quot;', '"', str_replace('amp;', '', str_replace('&', '', str_replace('\\', '', $aDeliveryData['warehouse']))));	        
	    }
	    Base::$tpl->assign('iIdCartPackage', $iId);*/
	    $aData=array(
	        'sHeader'=>"method=post",
	        'sTitle'=>"Доп.данные для накладной",
	        'sContent'=>Base::$tpl->fetch('nova/profile.tpl'),
// 	        'sSubmitButton'=>'Generate EN',
	        'sSubmitAction'=>'novaposhta',
	        'sError'=>$sError,
	        'sWidth'=>'100%',
	        'sClass'=>'form NovaForm'
	    );
	    $oForm=new Form($aData);
	    $aForm=$oForm->getForm();
	    return  $aForm;
	}
	//----------------------------------------------------------
	private function GetOrderList($iId){
	    $sSql1="select ln.positions from log_nova as ln, nova_en as ne where ne.order_id=ln.order_id and status=1 and ln.order_id=".$iId ." group by positions";
	    $aNovaList=Db::GetAll($sSql1);
	    $iCounter=0;
	    $sNovaList="0";
	    if($aNovaList){
    	    foreach ($aNovaList as $aValue){
    	        $sNovaList .=$aValue['positions'];
    	        $iCounter++;
    	        if($iCounter!=count($aNovaList)){
    	            $sNovaList.=",";
    	        }
    	    }
	    }
	    else{
	        $sNovaList=0;
	    }
	    $sSql="SELECT * FROM `cart` where id_cart_package=".$iId." and id not in(".$sNovaList.")";
	    $aList = Db::GetAll($sSql);
	    return $aList;
	}
	//----------------------------------------------------------
	public function SendEN($aRequest){
	    $aData=$aRequest['data'];
	    $aPositions=$aRequest['position'];
	    //API key
	    $oNP = new NovaPoshtaApi2(Base::GetConstant('novaposhta:key','40526d7535b7e92437586008d93c7da7'));
	    // Перед генерированием ЭН необходимо получить данные отправителя
	    // Получение всех отправителей
	    $senderInfo = $oNP->getCounterparties('Sender', 1, '', '');
	    // Выбор отправителя в конкретном городе (в данном случае - в первом попавшемся)
	    $sender = $senderInfo['data'][0];
	    $sender['City']=Base::GetConstant('novaposhta:sender_city_ref',"db5c897c-391c-11dd-90d9-001a92567626"); //идентификатор харькова
	    // Информация о складе отправителя
	    $senderWarehouses = $oNP->getWarehouses($sender['City']);
	    
	    $aSenderWarehouse=array();
	    if($senderWarehouses['data']) {
	        foreach ($senderWarehouses['data'] as $aValueSenderWarehouse) {
	            if(Base::GetConstant('novaposhta:senderWarehouses', '1') == $aValueSenderWarehouse['Number']) {
	                $aSenderWarehouse=$aValueSenderWarehouse;
	                break;
	            }
	        }
	    }
	    if(!$aSenderWarehouse) {
	        $aSenderWarehouse=$senderWarehouses['data'][0];
	    }
	    
	    // Данные отправителя
	    $aSender=$sender;
	    $aSender['SenderAddress']=$aSenderWarehouse['Ref'];
	    $aSender['CitySender']=$sender['City'];
	    $aSender['Ref']='';
	    $aSender['SendersPhone']==Base::GetConstant('novaposhta:sender_phone','380993103778');
	    $aSender['City']=Base::GetConstant('novaposhta:sender_city','Чернігів');
	    $aSender['ContactSender']=Base::GetConstant('novaposhta:sender_ref',"81fb3ffd-92c5-11e6-a54a-005056801333");
	    $aSender['Sender']=Base::GetConstant('novaposhta:sender_ref',"81fb3ffd-92c5-11e6-a54a-005056801333");
	    $aSender['Counterparty']=Base::GetConstant('novaposhta:sender_ref',"81fb3ffd-92c5-11e6-a54a-005056801333");
	    
	    if($sender['FirstName']!=''){
	        $aSender['FirstName'] = $sender['FirstName'];
	        $aSender['MiddleName'] = $sender['MiddleName'];
	        $aSender['LastName'] = $sender['LastName'];
	    }else{
	        $aSender['Description']=$sender['Description'];
	    }
	        
	    // Данные получателя
	    $aReceiver=array(
	        'FirstName' => $aData['surname'],
	        'MiddleName' => $aData['lastname'],
	        'LastName' => $aData['name'],
	        'Phone' => $aData['phone'],
	        'City' => $aData['city'],
	        'Region' => $aData['state'],
	    );
	    if($aData['ServiceType'] == 'WarehouseDoors')
	    {
	        $aReceiver['street'] = stripslashes($aData['addressS']);
	        $aReceiver['house'] = stripslashes($aData['house']);
	        $aReceiver['apartment'] = stripslashes($aData['apartment']);
	    }
	    else{
	        $aReceiver['Warehouse'] = stripslashes($aData['address']);
	        
	        $iPos=strpos($aReceiver['Warehouse'], "Поштомат");
	        if($iPos===false) {
	            //отделения
	            $bPostomat=false;
	        } else {
	            //почтоматы
	            $bPostomat=true;
	        }
	    }
	    /*// Данные получателя
	    $aReceiver=array(
	        'FirstName' => 'Сидор',
	        'MiddleName' => 'Сидорович',
	        'LastName' => 'йцуйцу',
	        'Phone' => '0509998877',
	        'City' => 'Чернигов',
	        'Region' => 'Черниговская',
	        'Warehouse' => 'Отделение №1: ул. Старобелоуская, 77',
	    );*/
	    $dWeight = floatval(str_replace(",", ".", $aData['weight']));
	    $aOptions=array(
	        // Дата отправления
	        'DateTime' => date('d.m.Y'),
	        // Тип доставки, дополнительно - getServiceTypes()
	        'ServiceType' => $aData['ServiceType'],
	        // Тип оплаты, дополнительно - getPaymentForms()
	        'PaymentMethod' => $aData['paymentmethod'],
	        // Кто оплачивает за доставку
	        'PayerType' => $aData['payertype'],
	        // Стоимость груза в грн
	        'Cost' => floatval ($aData['price']),
	        // Кол-во мест
	        'SeatsAmount' => $aData['number'],
	        // Описание груза
	        'Description' => $aData['description'],
	        // Тип доставки, дополнительно - getCargoTypes
	        'CargoType' => $aData['servicetype2'],
	        // Вес груза
	        'Weight' => $dWeight,
	        'VolumeWeight' => $aData['volume_weight'],
	        // Объем груза в куб.м.
	        'VolumeGeneral' => intval($aData['bulk']),
	        // внутренний номер заказа
	        'InfoRegClientBarcodes' => $aData['order_number'],
	        /*// Обратная доставка
	        'BackwardDeliveryData' => array(
	            array(
	                // Кто оплачивает обратную доставку
	                'PayerType' => 'Recipient',
	                // Тип доставки
	                'CargoType' => '',
	                // Значение обратной доставки
	                'RedeliveryString' => '',
	            )
	        )*/
	        'PackingNumber' => $aData['PackingNumber'],
	    );
	    
	    if($bPostomat) {
	        $aOptions['OptionsSeat']=array(array(
	            'volumetricVolume'=>'1',
	            'volumetricWidth'=>'30',
	            'volumetricLength'=>'30',
	            'volumetricHeight'=>'30',
	            'weight'=>$dWeight
	        ));
	    }
	   
	    if($aData['nal']=='on'){
	    $aOptions['BackwardDeliveryData'] = array(
	        array(
	            // Кто оплачивает обратную доставку
	            'PayerType' => 'Recipient',
	            // Тип доставки
	            'CargoType' => 'Money',
	            // Значение обратной доставки
	            'RedeliveryString' => $aData['nal_number'],
	        )
	    );
	    }
	    
	    
	    $aArray='';
	    $aArray[]=$aSender;
	    $aArray[]=$aReceiver;
	    $aArray[]=$aOptions;
	    $iCounter=0;
	    $sPosition='';
	    if($aRequest['position']) foreach ($aRequest['position'] as $key => $value){
	        $sPosition.=$key ;
	        $iCounter++;
	        if($iCounter!=count($aRequest['position'])){
	            $sPosition.=",";
	        }
	    }
	    $sSql1="INSERT into log_nova(object,order_id,positions,id_user,date)
	        values('".serialize($aArray)."',".$aRequest['id'].",'".$sPosition."',".$aRequest['data']['id_user'].",NOW())
	        ";
	    Db::Execute($sSql1);
	    $iInsertID=Db::InsertId();
	    
	    // Генерирование новой накладной
	    $result = $oNP->newInternetDocument($aSender, $aReceiver ,$aOptions);

	    if($result['success']){
	        $bStatus=1;
	    }
	    else{
	        $bStatus=0;
	    }
	    $tmp=1;
	   $sSql1="INSERT into nova_en(	responce,	status,	id_log,	order_id, en_key)
	     values('".serialize($result)."','".$bStatus."',".$iInsertID.",".$aRequest['id'].",'".$result['data'][0]['Ref']."')
	     ";
	     Base::$db->Execute($sSql1);
	     
	     
	     
	     
	    //insert into payment_declaration
	    $aDataPaymentDeclaration=array(
	        'id_user'=>$aCartPackage['id_user'],
	        'recipient'=>$aCartPackage['name'],
	        'carrier'=>'Нова Пошта',
	        'number_declaration'=>$result['data'][0]['IntDocNumber'],
	        'number_places'=>$aData['number'],
	        'id_cart_package'=>$aCartPackage['id']
	    );
	    Db::AutoExecute('payment_declaration',$aDataPaymentDeclaration);
	    
	    //change order_status to shipped
	    // cover - наложка - AT-1277
	    Db::Execute("update cart_package set order_status='cover',post_date_changed='".date("Y-m-d H:i:s")."' where id='".$aCartPackage['id']."'");
	    $iIdManager = (Auth::$aUser['type_']=='manager' ? Auth::$aUser['id_user'] : 0);
	    // log
	    Base::$db->Execute("insert into cart_package_log (id_cart_package,id_user_manager,post_date,order_status,comment,ip)
		    values ('".$aCartPackage['id']."','".$iIdManager."','".date("Y-m-d H:i:s")."','cover','','".Auth::GetIp()."')");
	     
	    // Sms::AddDelayed($aCartPackage['login'],"По заказу №".$aCartPackage['id']." сформирована ТТН №".$result['data'][0]['IntDocNumber']);
	    Sms::AddDelayed($aCartPackage['login'],"Ваш заказ №".$aCartPackage['id']." отправлен службой НоваПошта. ТТН №".$result['data'][0]['IntDocNumber'].". Тел. 0957557007");
	    return $result;
	}
	
	//-----------------------------------------------------------------------------------------------
	public function DeleteReestr()
	{
		if(!Base::$aRequest['id_en']) Base::Redirect("/?".Base::$aRequest['return']);
		Db::Execute("delete from nova_en where id_en=".Base::$aRequest['id_en']);
		Base::Redirect("/?".Base::$aRequest['return']);
	}
}
?>