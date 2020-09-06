<?

class Binotel extends Base
{
    var $oBinotel=null;
    
    //-----------------------------------------------------------------------------------------------
    public function __construct()
    {
        $key = Base::GetConstant('binotel:key','ce28b1-db4e0b0');
        $secret = Base::GetConstant('binotel:secret','87ef39-7441e0-88e84a-77edd3-7c4afa9f');
         
        $this->oBinotel = new BinotelApi($key, $secret);
        $this->oBinotel->debug = true;
        $this->oBinotel->disableSSLChecks();
    }
    //-----------------------------------------------------------------------------------------------
    public function Index()
    {
        Base::$sText.=Base::$tpl->fetch('binotel/tab_calls.tpl');
    
        if(!Base::$aRequest['search']['date_from']) Base::$aRequest['search']['date_from'] = date("d.m.Y",time());
        if(!Base::$aRequest['search']['date_to']) Base::$aRequest['search']['date_to'] = date("d.m.Y",time());

        $aData=$this->GetAllCallsByPeriod(Base::$aRequest['search']['date_from'],Base::$aRequest['search']['date_to'],Base::$aRequest['search']['employee']);
        Base::$tpl->assign('sStat',$this->GetAllCallsStat($aData));
        $aEmployeesNumbers=array(
            ''=>Language::GetMessage('All')
        );
        $aEmployees=$this->GetAllManagers();
        foreach ($aEmployees as $sKey=>$sValue)
            $aEmployeesNumbers[$sValue['extNumber']]=($sValue['name']?$sValue['name'].' - ':'').$sValue['extNumber'];
        
        Resource::Get()->Add('/js/binotel.js');
         
        $aField['employee']=array('title'=>'Employee','type'=>'select','options'=>$aEmployeesNumbers,'selected'=>Base::$aRequest['search']['employee'],'name'=>'search[employee]');
        $aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("d.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
        $aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
//         $aField['show_stat']=array('type'=>'button','value'=>Language::GetMessage('Summary statistics'),'onclick'=>'showStat()');
//         $aField['stat']=array('type'=>'span','id'=>'summ_stat','value'=>$this->GetAllCallsStat($aData),'style'=>'display:none','colspan'=>2);
         
        $oForm=new Form();
        $oForm->aField=$aField;
        $oForm->bType='generate';
        $oForm->sGenerateTpl='form/index_search.tpl';
        $oForm->sSubmitButton='search';
        $oForm->sSubmitAction='binotel';
        $oForm->sReturnButton='Clear';
        $oForm->sAdditionalButtonTemplate='binotel/summary_statistics.tpl';
        Base::$sText.=$oForm->getForm();
        
        $oTable=new Table();
        $oTable->sType='array';
        $oTable->aDataFoTable=$aData;
    
        $oTable->aColumn['disposition']=array('sTitle'=>'status',);
        $oTable->aColumn['externalNumber']=array('sTitle'=>'customer',);
	    $oTable->aColumn['internalNumber']=array('sTitle'=>'employee',);
        $oTable->aColumn['sWaitsec']=array('sTitle'=>'waitsec',);
        $oTable->aColumn['sBillsec']=array('sTitle'=>'billsec',);
        $oTable->aColumn['date']=array('sTitle'=>'time',);
        $oTable->aColumn['action']=array();
    
        // 		$oTable->aCallback=array($this,'CallParseModel');
        $oTable->iRowPerPage=count($aData);
        $oTable->sDataTemplate='binotel/row_table.tpl';
        // 		$oTable->aOrdered=" order by name ";
        Base::$sText.=$oTable->GetTable();
         
        return true;
    }
    //-----------------------------------------------------------------------------------------------
	public function InputCalls() {
        Base::$sText.=Base::$tpl->fetch('binotel/tab_calls.tpl');
        
        if(!Base::$aRequest['search']['date_from']) Base::$aRequest['search']['date_from'] = date("d.m.Y",time());
        if(!Base::$aRequest['search']['date_to']) Base::$aRequest['search']['date_to'] = date("d.m.Y",time());
        
        $aPhoneNumbers=array(
          ''=>Language::GetMessage('All')  
        );
       
        $aData=$this->GetInputCallsByPeriod(Base::$aRequest['search']['date_from'],Base::$aRequest['search']['date_to']);
        Base::$tpl->assign('sStat',$this->GetInputCallsStat($aData));
	    
        Resource::Get()->Add('/js/binotel.js');
	    
	    $aField['to_phone']=array('title'=>'To number','type'=>'select','options'=>$aPhoneNumbers);
	    $aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("d.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
	    $aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
// 	    $aField['show_stat']=array('type'=>'button','value'=>Language::GetMessage('Summary statistics'),'onclick'=>'showStat()');
// 	    $aField['stat']=array('type'=>'span','id'=>'summ_stat','value'=>$this->GetInputCallsStat($aData),'style'=>'display:none','colspan'=>2);

	    $oForm=new Form();
	    $oForm->aField=$aField;
	    $oForm->bType='generate';
	    $oForm->sGenerateTpl='form/index_search.tpl';
	    $oForm->sSubmitButton='search';
	    $oForm->sSubmitAction='binotel_input';
	    $oForm->sReturnButton='Clear';
	    $oForm->sAdditionalButtonTemplate='binotel/summary_statistics.tpl';
	    Base::$sText.=$oForm->getForm();
	    
	    $oTable=new Table();
	    $oTable->sType='array';
	    $oTable->aDataFoTable=$aData;
	    
	    $oTable->aColumn['disposition']=array('sTitle'=>'status',);
	    $oTable->aColumn['externalNumber']=array('sTitle'=>'from',);
	    $oTable->aColumn['internalNumber']=array('sTitle'=>'to',);
	    $oTable->aColumn['sWaitsec']=array('sTitle'=>'waitsec',);
	    $oTable->aColumn['sBillsec']=array('sTitle'=>'billsec',);
	    $oTable->aColumn['date']=array('sTitle'=>'time',);
	    $oTable->aColumn['action']=array();
	    
	    // 		$oTable->aCallback=array($this,'CallParseModel');
	    $oTable->iRowPerPage=count($aData);
	    $oTable->sDataTemplate='binotel/row_table.tpl';
	    // 		$oTable->aOrdered=" order by name ";
	    Base::$sText.=$oTable->GetTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function OutputCalls() {
        Base::$sText.=Base::$tpl->fetch('binotel/tab_calls.tpl');
        
        if(!Base::$aRequest['search']['date_from']) Base::$aRequest['search']['date_from'] = date("d.m.Y",time());
        if(!Base::$aRequest['search']['date_to']) Base::$aRequest['search']['date_to'] = date("d.m.Y",time());
        
        $aEmployeesNumbers=array(
          ''=>Language::GetMessage('All')
        );
        $aEmployees=$this->GetAllManagers();
        foreach ($aEmployees as $sKey=>$sValue)
            $aEmployeesNumbers[$sValue['extNumber']]=($sValue['name']?$sValue['name'].' - ':'').$sValue['extNumber'];
    
        $aData=$this->GetOutputCallsByPeriod(Base::$aRequest['search']['date_from'],Base::$aRequest['search']['date_to'],Base::$aRequest['search']['employee']);
        Base::$tpl->assign('sStat',$this->GetOutputCallsStat($aData));
        Resource::Get()->Add('/js/binotel.js');
        
        $aField['from_employee']=array('title'=>'Employee','type'=>'select','options'=>$aEmployeesNumbers,'selected'=>Base::$aRequest['search']['employee'],'name'=>'search[employee]');
        $aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("d.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
        $aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
//         $aField['show_stat']=array('type'=>'button','value'=>Language::GetMessage('Summary statistics'),'onclick'=>'showStat()');
//         $aField['stat']=array('type'=>'span','id'=>'summ_stat','value'=>$this->GetOutputCallsStat($aData),'style'=>'display:none','colspan'=>2);
         
        $oForm=new Form();
        $oForm->aField=$aField;
        $oForm->bType='generate';
        $oForm->sGenerateTpl='form/index_search.tpl';
        $oForm->sSubmitButton='search';
        $oForm->sSubmitAction='binotel_output';
        $oForm->sReturnButton='Clear';
        $oForm->sAdditionalButtonTemplate='binotel/summary_statistics.tpl';
        Base::$sText.=$oForm->getForm();
        
	    $oTable=new Table();
	    $oTable->sType='array';
	    $oTable->aDataFoTable=$aData;
	     
	    $oTable->aColumn['disposition']=array('sTitle'=>'status',);
	    $oTable->aColumn['internalNumber']=array('sTitle'=>'from',);
	    $oTable->aColumn['externalNumber']=array('sTitle'=>'to',);
	    $oTable->aColumn['sWaitsec']=array('sTitle'=>'waitsec',);
	    $oTable->aColumn['sBillsec']=array('sTitle'=>'billsec',);
	    $oTable->aColumn['date']=array('sTitle'=>'time',);
	    $oTable->aColumn['action']=array();
	     
	    // 		$oTable->aCallback=array($this,'CallParseModel');
	    $oTable->iRowPerPage=count($aData);
	    $oTable->sDataTemplate='binotel/row_table.tpl';
	    // 		$oTable->aOrdered=" order by name ";
	    Base::$sText.=$oTable->GetTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function LostCalls() {
        Base::$sText.=Base::$tpl->fetch('binotel/tab_calls.tpl');
        
        if(!Base::$aRequest['search']['date_from']) Base::$aRequest['search']['date_from'] = date("d.m.Y",time());
        if(!Base::$aRequest['search']['date_to']) Base::$aRequest['search']['date_to'] = date("d.m.Y",time());
        
	    $aData=$this->GetLostCalls(Base::$aRequest['search']['date_from'],Base::$aRequest['search']['date_to']);
	    Base::$tpl->assign('sStat',$this->GetLostCallsStat($aData));
	    Resource::Get()->Add('/js/binotel.js');
	    
	    $aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("d.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
	    $aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
// 	    $aField['show_stat']=array('type'=>'button','value'=>Language::GetMessage('Summary statistics'),'onclick'=>'showStat()');
// 	    $aField['stat']=array('type'=>'span','id'=>'summ_stat','value'=>$this->GetLostCallsStat($aData),'style'=>'display:none','colspan'=>2);
	     
	    $oForm=new Form();
	    $oForm->aField=$aField;
	    $oForm->bType='generate';
	    $oForm->sGenerateTpl='form/index_search.tpl';
	    $oForm->sSubmitButton='search';
	    $oForm->sSubmitAction='binotel_lost';
	    $oForm->sReturnButton='Clear';
	    $oForm->sAdditionalButtonTemplate='binotel/summary_statistics.tpl';
	    Base::$sText.=$oForm->getForm();
	    
	    $oTable=new Table();
	    $oTable->sType='array';
	    $oTable->aDataFoTable=$aData;
	     
	    $oTable->aColumn['disposition']=array('sTitle'=>'status',);
	    $oTable->aColumn['externalNumber']=array('sTitle'=>'from',);
	    $oTable->aColumn['internalNumber']=array('sTitle'=>'to',);
	    $oTable->aColumn['sWaitsec']=array('sTitle'=>'waitsec',);
	    $oTable->aColumn['sBillsec']=array('sTitle'=>'billsec',);
	    $oTable->aColumn['attemptsCallBack']=array('sTitle'=>'attempts to call back');
	    $oTable->aColumn['date']=array('sTitle'=>'time',);
	    $oTable->aColumn['action']=array();
	     
	    // 		$oTable->aCallback=array($this,'CallParseModel');
	    $oTable->iRowPerPage=count($aData);
	    $oTable->sDataTemplate='binotel/row_table.tpl';
	    // 		$oTable->aOrdered=" order by name ";
	    Base::$sText.=$oTable->GetTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function CallsByManager() {
        Base::$sText.=Base::$tpl->fetch('binotel/tab_calls.tpl');
        
        $aEmployees=$this->GetAllManagers();
        foreach ($aEmployees as $sKey=>$sValue)
            $aEmployeesNumbers[$sValue['extNumber']]=($sValue['name']?$sValue['name'].' - ':'').$sValue['extNumber'];
        
        if(!Base::$aRequest['search']['date_from']) Base::$aRequest['search']['date_from'] = date("d.m.Y",time());
        if(!Base::$aRequest['search']['date_to']) Base::$aRequest['search']['date_to'] = date("d.m.Y",time());
        if(!Base::$aRequest['search']['employee']) Base::$aRequest['search']['employee'] = reset(array_keys($aEmployees));
           
        Resource::Get()->Add('/js/binotel.js');
        
        $aField['from_employee']=array('title'=>'Employee','type'=>'select','options'=>$aEmployeesNumbers,'selected'=>Base::$aRequest['search']['employee'],'name'=>'search[employee]');
        $aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("d.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
        $aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
        if(Base::$aRequest['is_post']) {
            $aData=$this->GetCallsByManager(Base::$aRequest['search']['date_from'],Base::$aRequest['search']['date_to'],Base::$aRequest['search']['employee']);
//             $aField['show_stat']=array('type'=>'button','value'=>Language::GetMessage('Summary statistics'),'onclick'=>'showStat()');
//             $aField['stat']=array('type'=>'span','id'=>'summ_stat','value'=>$this->GetCallsByManagerStat($aData),'style'=>'display:none','colspan'=>2);
        }
        Base::$tpl->assign('sStat',$this->GetCallsByManagerStat($aData));
        $oForm=new Form();
        $oForm->aField=$aField;
        $oForm->bType='generate';
        $oForm->sSubmitButton='search';
        $oForm->sGenerateTpl='form/index_search.tpl';
        $oForm->sSubmitAction='binotel_by_manager';
        $oForm->sReturnButton='Clear';
        $oForm->sAdditionalButtonTemplate='binotel/summary_statistics.tpl';
        Base::$sText.=$oForm->getForm();
        
	    if(Base::$aRequest['is_post']) {
    	    $oTable=new Table();
    	    $oTable->sType='array';
    	    $oTable->aDataFoTable=$aData;
    	     
    	    $oTable->aColumn['disposition']=array('sTitle'=>'status',);
    	    $oTable->aColumn['externalNumber']=array('sTitle'=>'from',);
    	    $oTable->aColumn['internalNumber']=array('sTitle'=>'to',);
    	    $oTable->aColumn['sWaitsec']=array('sTitle'=>'waitsec',);
    	    $oTable->aColumn['sBillsec']=array('sTitle'=>'billsec',);
    	    $oTable->aColumn['date']=array('sTitle'=>'time',);
    	    $oTable->aColumn['action']=array();
    	     
    	    // 		$oTable->aCallback=array($this,'CallParseModel');
    	    $oTable->iRowPerPage=count($aData);
    	    $oTable->sDataTemplate='binotel/row_table.tpl';
    	    // 		$oTable->aOrdered=" order by name ";
    	    Base::$sText.=$oTable->GetTable();
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function CallsByNumber() {
        Base::$sText.=Base::$tpl->fetch('binotel/tab_calls.tpl');
        
	    if(Base::$aRequest['is_post']) {
    	    $aData=$this->GetCallsByNumber(Base::$aRequest['number']);
    	    
    	    $oTable=new Table();
    	    $oTable->sType='array';
    	    $oTable->aDataFoTable=$aData;
    	    
    	    $oTable->aColumn['disposition']=array('sTitle'=>'status',);
    	    $oTable->aColumn['externalNumber']=array('sTitle'=>'from',);
    	    $oTable->aColumn['internalNumber']=array('sTitle'=>'to',);
    	    $oTable->aColumn['sWaitsec']=array('sTitle'=>'waitsec',);
    	    $oTable->aColumn['sBillsec']=array('sTitle'=>'billsec',);
    	    $oTable->aColumn['date']=array('sTitle'=>'time',);
    	    $oTable->aColumn['action']=array();
    	    
    	    // 		$oTable->aCallback=array($this,'CallParseModel');
    	    $oTable->iRowPerPage=count($aData);
    	    $oTable->sDataTemplate='binotel/row_table.tpl';
    	    // 		$oTable->aOrdered=" order by name ";
    	    Base::$sText.=$oTable->GetTable();
	    }
	    $aField['number']=array('title'=>'number','type'=>'input','value'=>Base::$aRequest['number'],'name'=>'number');
	    $aData=array(
// 		'sHeader'=>"method=get",
// 		'sTitle'=>"Manager Profile",
		//'sContent'=>Base::$tpl->fetch('binotel/form_search_by_number.tpl'),
	    'aField'=>$aField,
	    'bType'=>'generate',
	    'sGenerateTpl'=>'form/index_search.tpl',
		'sSubmitButton'=>'search',
		'sSubmitAction'=>'binotel_by_number',
	    'sReturnButton'=>'Clear',
// 		'sError'=>$sError,
		);
		$oForm=new Form($aData);
		Base::$sText.=$oForm->getForm();
	}
	//-----------------------------------------------------------------------------------------------
	public function CallsNow() {
        Base::$sText.=Base::$tpl->fetch('binotel/tab_calls.tpl');
        
	    $aData=$this->GetCallsNow();
	    
	    $aEmployees=$this->GetAllManagers();
	    $iOnline = 0;
	    foreach ($aEmployees as $sKey => $aValue)
	        if($aValue['extStatus']['status']==='online') $iOnline++;
	    
	    $iIncoming = 0;
	    $iOutgoing = 0;
	    foreach ($aData as $sKey => $aValue){
	        if($aValue['callType'] == 0) $iIncoming++;
	        else $iOutgoing++;
	    }
	        
	    $aField['managers_online']=array('title'=>'сотрудники онлайн','type'=>'text','value'=>$iOnline);
	    $aField['incoming']=array('title'=>'входящие звонки','type'=>'text','value'=>$iIncoming);
	    $aField['outgoing']=array('title'=>'исходящие звонки','type'=>'text','value'=>$iOutgoing);
	    
	    $oForm=new Form();
	    $oForm->aField=$aField;
	    $oForm->bType='generate';
	    $oForm->sGenerateTpl='form/index_search.tpl';
	    $oForm->sSubmitButton='search';
	    $oForm->sSubmitAction='binotel_now';
	    $oForm->sReturnButton='Clear';
	    Base::$sText.=$oForm->getForm();
	    
	    $oTable=new Table();
	    $oTable->sType='array';
	    $oTable->aDataFoTable=$aData;
	    
	    $oTable->aColumn['disposition']=array('sTitle'=>'status',);
	    $oTable->aColumn['externalNumber']=array('sTitle'=>'customer',);
	    $oTable->aColumn['internalNumber']=array('sTitle'=>'manager',);
	    $oTable->aColumn['sBillsec']=array('sTitle'=>'billsec',);
	    $oTable->aColumn['date']=array('sTitle'=>'time',);
	    $oTable->aColumn['action']=array();
	    
	    // 		$oTable->aCallback=array($this,'CallParseModel');
	    $oTable->iRowPerPage=count($aData);
	    $oTable->sDataTemplate='binotel/row_table.tpl';
	    // 		$oTable->aOrdered=" order by name ";
	    Base::$sText.=$oTable->GetTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function CommentEdit(){
	    
        $aField['comment']=array('title'=>'Comment','type'=>'textarea','name'=>'comment');
        $aData=array(
	        'sHeader'=>"method=get",
	        'sTitle'=>"edit",
	        'aField'=>$aField,
	        'bType'=>'generate',
	        'sSubmitButton'=>'Edit',
	        'sSubmitAction'=>'binotel_comment_edit',
	        'sReturnButton'=>'return',
	        'bAutoReturn'=>true,
	        'sError'=>$sError
	    );
	    $oForm=new Form($aData);
	    Base::$sText.=$oForm->getForm();
	}
	//-----------------------------------------------------------------------------------------------
	public function Managers() {
	    Base::$sText.=Base::$tpl->fetch('binotel/tab_users.tpl');
	
	    $aData=$this->GetAllManagers();
	    sort($aData);
	
	    $oTable=new Table();
	    $oTable->sType='array';
	    $oTable->aDataFoTable=$aData;
	
	    $oTable->aColumn['employeeID']=array('sTitle'=>'employeeID',);
	    $oTable->aColumn['email']=array('sTitle'=>'email',);
	    $oTable->aColumn['name']=array('sTitle'=>'name',);
	    $oTable->aColumn['mobilePhoneNumber']=array('sTitle'=>'mobilePhoneNumber',);
	    $oTable->aColumn['presenceState']=array('sTitle'=>'presenceState',);
	    $oTable->aColumn['department']=array('sTitle'=>'department',);
	    $oTable->aColumn['extNumber']=array('sTitle'=>'extNumber',);
	    $oTable->aColumn['extHash']=array('sTitle'=>'extHash',);
	    $oTable->aColumn['extStatus']=array('sTitle'=>'extStatus',);
	    //$oTable->aColumn['action']=array();
	
	    // 		$oTable->aCallback=array($this,'CallParseModel');
	    $oTable->iRowPerPage=count($aData);
	    $oTable->sDataTemplate='binotel/row_table.tpl';
	    // 		$oTable->aOrdered=" order by name ";
	    Base::$sText.=$oTable->GetTable();
	
	    return true;
	}
	//-----------------------------------------------------------------------------------------------
	public function UserAdd() {
	    if(Base::$aRequest['is_post']) {
	        $aResult=$this->CreateBinotelUser(
	            Base::$aRequest['name'],
	            Base::$aRequest['phone'],
	            Base::$aRequest['description'],
	            Base::$aRequest['email'],
	            Base::$aRequest['id_manager'],
	            $aLabels=array()
	        );
	         
	        if($aResult['status']!='success') $sError="error";
	        else {
	            if(Base::$aRequest['import']) {
	                $aBinotelUser=$this->GetUserByNameOrPhone(array_shift(Base::$aRequest['phone']));
	                if($aBinotelUser) {
	                    $aBinotelUser=current($aBinotelUser);
	                    Db::Execute("update user_customer set
	                        is_binotel_sync=1,
	                        id_binotel_user='".$aBinotelUser['id']."'
	                        where id_user='".Base::$aRequest['id']."' ");
	                }
	            }
	            
	            if(Base::$aRequest['return']) $sReturn="/?".Base::$aRequest['return'];
	            else $sReturn="/pages/binotel_users/";
	             
	            Base::Redirect(str_replace("??", "?", $sReturn));
	        }
	    }
	     
	    $aAllManagers=$this->GetAllManagers();
	    $aManagers=array();
	    if($aAllManagers) {
	        foreach ($aAllManagers as $aValue) {
	            if($aValue['employeeID']==0) continue;
	            $aManagers[$aValue['employeeID']]=$aValue['name'];
	        }
	    }
	    Base::$tpl->assign('aManagers',$aManagers);
	    
	    if(Base::$aRequest['id'] && Base::$aRequest['import']) {
	        $aUser=Db::GetRow("select u.email, uc.name, uc.phone 
	            from user as u 
	            inner join user_customer as uc on u.id=uc.id_user and u.id='".Base::$aRequest['id']."' ");
	        $aUser['numbers']=array(
	            Catalog::StripCode($aUser['phone'])
	        );
	        Base::$tpl->assign('aData',$aUser);
	    }
	    $aField['name']=array('title'=>'name','type'=>'input','value'=>$aUser['name'],'name'=>'name','szir'=>1);
	    $aField['description']=array('title'=>'description','type'=>'input','value'=>$aUser['description'],'name'=>'description');
	    $aField['email']=array('title'=>'email','type'=>'input','value'=>$aUser['email'],'name'=>'email');
	    $aField['id_manager']=array('title'=>'manager','type'=>'select','options'=>$aManagers,'selected'=>$aUser['assignedToEmployeeID'],'name'=>'id_manager','szir'=>1);
        $aField['add_phone']=array('title'=>'phone','add_to_td'=>array());
        foreach ($aUser['numbers'] as $sPhone){
            $aField['add_phone']['add_to_td']=array_merge($aField['add_phone']['add_to_td'],array(
                $sPhone=>array('type'=>'input','value'=>$sPhone,'name'=>'phone[]'),
                $sPhone.'btn'=>array('type'=>'button','class'=>'rmPhone','value'=>'-','br'=>1)
            ));
        }
        $aField['add_phone']['add_to_td']=array_merge($aField['add_phone']['add_to_td'],array('add_phone_btn'=>array('title'=>'phone','type'=>'button','value'=>'add phone','class'=>'addPhone')));
	    $aData=array(
	        'sHeader'=>"method=get",
	        'sTitle'=>"add",
// 	        'sContent'=>Base::$tpl->fetch('binotel/form_user_edit.tpl'),
	        'aField'=>$aField,
	        'bType'=>'generate',
	        'sSubmitButton'=>'add',
	        'sSubmitAction'=>'binotel_user_add',
	        'sReturnButton'=>'return',
	        'bAutoReturn'=>true,
	        'sError'=>$sError
	    );
	    $oForm=new Form($aData);
	    Base::$sText.=$oForm->getForm();
	}
	//-----------------------------------------------------------------------------------------------
	public function UserImport() {
	    Base::$sText.=Base::$tpl->fetch('binotel/tab_users.tpl');
	    
	    Base::$tpl->assign('aNameUser',$aNameUser=array(0 =>'')+Db::GetAssoc("select u.login, concat(uc.name,' ( ',u.login,' )',
				IF(uc.phone is null or uc.phone='','',concat(' ".
	        Language::getMessage('tel.')." ',uc.phone))) name
		from user as u
		inner join user_customer as uc on u.id=uc.id_user
		where u.visible=1 and uc.name is not null and trim(uc.name)!=''
		order by uc.name"));
	    
	    Resource::Get()->Add('/js/select_search.js');
	    
	    $aField['search_login']=array('title'=>'Login','type'=>'select','options'=>$aNameUser,'name'=>'search_login','selected'=>Base::$aRequest['search_login'],'class'=>'select_search');
	    $aField['name']=array('title'=>'CustName','type'=>'input','value'=>Base::$aRequest['search']['name'],'name'=>'search[name]');
	    $aField['phone']=array('title'=>'Phone','type'=>'input','value'=>Base::$aRequest['search']['phone'],'name'=>'search[phone]','id'=>'user_phone','placeholder'=>'(___)___ __ __');
	    
	    $aData=array(
	        'sHeader'=>"method=get",
	        //'sContent'=>Base::$tpl->fetch('manager/form_customer_search.tpl'),
	        'aField'=>$aField,
	        'bType'=>'generate',
	        'sGenerateTpl'=>'form/index_search.tpl',
	        'sSubmitButton'=>'Search',
	        'sSubmitAction'=>'binotel_user_import',
	        'sReturnButton'=>'Clear',
	        'bIsPost'=>0,
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
	    
	    if (Base::$aRequest['search']['name']) $sWhere.=" and uc.name like '%".Base::$aRequest['search']['name']."%'";
		if (Base::$aRequest['search']['phone']) $sWhere.=" and uc.phone like '%".Base::$aRequest['search']['phone']."%'";
		// --------------
	    
	    $oTable=new Table();
	    $oTable->sSql="select cg.*,uc.*, ua.* ,u.*, cg.name as group_name
					, m.login as manager_login
					 from user u
				inner join user_customer uc on uc.id_user=u.id
				inner join user_account ua on ua.id_user=u.id
				inner join customer_group cg on uc.id_customer_group=cg.id
				inner join user m on uc.id_manager=m.id
			 where 1=1
			 ".$sWhere;
	    $oTable->aColumn=array(
	        'id'=>array('sTitle'=>'CustID','sOrder'=>'u.id'),
	        'login'=>array('sTitle'=>'Login','sOrder'=>'u.login'),
	        'name'=>array('sTitle'=>'name','sOrder'=>'uc.name'),
	        'email'=>array('sTitle'=>'Email','sOrder'=>'u.email'),
	        'phone'=>array('sTitle'=>'phone','sOrder'=>'uc.phone'),
	        'is_binotel_sync'=>array('sTitle'=>'is_binotel_sync','sOrder'=>'uc.is_binotel_sync'),
	        'id_binotel_user'=>array('sTitle'=>'id_binotel_user','sOrder'=>'uc.id_binotel_user'),
	        'action'=>array(),
	    );
	    $oTable->iRowPerPage=20;
	    $oTable->sDataTemplate='binotel/row_user_import.tpl';
	    
	    Base::$sText.=$oTable->getTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function UserEdit() {
	    if(Base::$aRequest['is_post']) {
	        $aResult=$this->EditBinotelUser(
	            Base::$aRequest['id'],
	            Base::$aRequest['name'],
	            Base::$aRequest['phone'],
	            Base::$aRequest['description'],
	            Base::$aRequest['email'],
	            Base::$aRequest['id_manager'],
	            $aLabels=array()
	        );
	        
	        if($aResult['status']!='success') $sError="error";
	        else {
	            if(Base::$aRequest['return']) $sReturn="/?".Base::$aRequest['return'];
	            else $sReturn="/pages/binotel_users/";
	            
	            Base::Redirect(str_replace("??", "?", $sReturn));
	        }
	    }
	    
	    $aUser=array_shift($this->GetUserById(Base::$aRequest['id']));
	    Base::$tpl->assign('aData',$aUser);
	    $aAllManagers=$this->GetAllManagers();
	    $aManagers=array();
	    if($aAllManagers) {
	        foreach ($aAllManagers as $aValue) {
	            if($aValue['employeeID']==0) continue;
	            $aManagers[$aValue['employeeID']]=$aValue['name'];
	        }
	    }
	    Base::$tpl->assign('aManagers',$aManagers);
	    $aField['name']=array('title'=>'name','type'=>'input','value'=>$aUser['name'],'name'=>'name','szir'=>1);
	    $aField['description']=array('title'=>'description','type'=>'input','value'=>$aUser['description'],'name'=>'description');
	    $aField['email']=array('title'=>'email','type'=>'input','value'=>$aUser['email'],'name'=>'email');
	    $aField['id_manager']=array('title'=>'manager','type'=>'select','options'=>$aManagers,'selected'=>$aUser['assignedToEmployeeID'],'name'=>'id_manager','szir'=>1);
        $aField['add_phone']=array('title'=>'phone','add_to_td'=>array());
        foreach ($aUser['numbers'] as $sPhone){
            $aField['add_phone']['add_to_td']=array_merge($aField['add_phone']['add_to_td'],array(
                $sPhone=>array('type'=>'input','value'=>$sPhone,'name'=>'phone[]'),
                $sPhone.'btn'=>array('type'=>'button','class'=>'rmPhone','value'=>'-','br'=>1)
            ));
        }
        $aField['add_phone']['add_to_td']=array_merge($aField['add_phone']['add_to_td'],array('add_phone_btn'=>array('title'=>'phone','type'=>'button','value'=>'add phone','class'=>'addPhone')));
	    
	    $aData=array(
    		'sHeader'=>"method=get",
    		'sTitle'=>"edit",
// 	        'sContent'=>Base::$tpl->fetch('binotel/form_user_edit.tpl'),
	        'aField'=>$aField,
	        'bType'=>'generate',
	        'sSubmitButton'=>'Edit',
	        'sSubmitAction'=>'binotel_users_edit',
	        'sReturnButton'=>'return',
	        'bAutoReturn'=>true,
	        'sError'=>$sError
	    );
	    $oForm=new Form($aData);
	    Base::$sText.=$oForm->getForm();
	}
	//-----------------------------------------------------------------------------------------------
	public function Users() {
        Base::$sText.=Base::$tpl->fetch('binotel/tab_users.tpl');
        
        if(Base::$aRequest['action']=='binotel_users_delete') {
            $this->RemoveBinotelUser(trim(Base::$aRequest['id']));
            
            if(Base::$aRequest['return']) $sReturn="/?".Base::$aRequest['return'];
            else $sReturn="/pages/binotel_users/";
            
            Base::Redirect(str_replace("??", "?", $sReturn));
        }
        $aField['id_user']=array('title'=>'id_user','type'=>'input','value'=>Base::$aRequest['search']['id'],'name'=>'search[id]');
        $aField['number']=array('title'=>'phone_number_or_name','type'=>'input','value'=>Base::$aRequest['search']['number'],'name'=>'search[number]');
        $aData=array(
    // 		'sHeader'=>"method=get",
    // 		'sTitle'=>"Manager Profile",
//          'sContent'=>Base::$tpl->fetch('binotel/form_search_users.tpl'),
            'aField'=>$aField,
            'bType'=>'generate',
            'sGenerateTpl'=>'form/index_search.tpl',
            'sSubmitButton'=>'search',
            'sSubmitAction'=>'binotel_users',
            'sReturnButton'=>'Clear',
            'sAdditionalButtonTemplate'=>'binotel/button_users.tpl'
        );
        $oForm=new Form($aData);
        Base::$sText.=$oForm->getForm();
        
        if(Base::$aRequest['search']['id']) {
            $aData=$this->GetUserById(trim(Base::$aRequest['search']['id']));
        } elseif(Base::$aRequest['search']['number']) {
            $aData=$this->GetUserByNameOrPhone(trim(Base::$aRequest['search']['number']));
        } else {
            $aData=$this->GetAllUsers();
        }
        
        //получить массив бинотел_ид из базы
        $aIdBinotelUser=Db::GetAssoc("select uc.id_binotel_user, uc.id_user from user_customer uc
                                      where uc.is_binotel_sync=1");     
        
        foreach ($aData as $sKey => $aValue){
            //проверять, если есть такой айди в базе как у клиента, то  ['is_sync']=1;
            if($aIdBinotelUser[$aValue['id']]) $aData[$sKey]['is_sync']=1;
        }
	    $oTable=new Table();
	    $oTable->sType='array';
	    $oTable->aDataFoTable=$aData;
	
	    $oTable->aColumn['id']=array('sTitle'=>'id user',);
        $oTable->aColumn['name']=array('sTitle'=>'name',);
        $oTable->aColumn['description']=array('sTitle'=>'description',);
        $oTable->aColumn['email']=array('sTitle'=>'email',);
        $oTable->aColumn['assignedToEmployeeID']=array('sTitle'=>'assignedToEmployeeID',);
        $oTable->aColumn['assignedToEmployeeNumber']=array('sTitle'=>'assignedToEmployeeNumber',);
        $oTable->aColumn['assignedToEmployee']=array('sTitle'=>'assignedToEmployee',);
        $oTable->aColumn['numbers']=array('sTitle'=>'numbers',);
        $oTable->aColumn['labels']=array('sTitle'=>'labels',);
        $oTable->aColumn['is_sync']=array('sTitle'=>'is_sync',);
	    $oTable->aColumn['action']=array();
	
	    // 		$oTable->aCallback=array($this,'CallParseModel');
	    $oTable->iRowPerPage=count($aData);
	    $oTable->sDataTemplate='binotel/row_users.tpl';
	    // 		$oTable->aOrdered=" order by name ";
	    Base::$sText.=$oTable->GetTable();
	     
	    return true;
	}
	//-----------------------------------------------------------------------------------------------
	public function Call()
	{
        $this->MakeCallToExternal(trim(Base::$aRequest['internal']), trim(Base::$aRequest['external']));
	    
	    return true;
	}
	//-----------------------------------------------------------------------------------------------
	public function AnalyticsTrends(){
	    Base::$sText.=Base::$tpl->fetch('binotel/tab_analytics.tpl');
	    
	    for($i=0; $i <= 6; $i++)
	        /** example - $aWeeks[0]=array('from'=>'26.12.2016', 'to'=>'01.01.2017', 'caption'=>'26-Dec - 01-Jan') **/
	        $aWeeks[$i] = $this->GetDateWeek($i);
        krsort($aWeeks);
	    Base::$tpl->assign('aWeeks',$aWeeks);
	    
	    //don't showing, but need for statistics
	    $aWeeks[$i+1] = $this->GetDateWeek($i+1);

	    if(!Base::$aRequest['week']) Base::$aRequest['week'] = 0;
	    
	    //get calls on selected week and previous week
	    $aCalls = $this->GetAllCallsByPeriod($aWeeks[Base::$aRequest['week']+1]['from'], $aWeeks[Base::$aRequest['week']]['to']);
	    
        //empty data for table average load
	    for($i=8; $i<20; $i++) $aData['average_load'][$i.'-'.($i+1)] = array('incoming'=>0,'lost'=>'0');
	    
	    if($aCalls) foreach ($aCalls as $sKey => $aValue){
	        //if this_week
	        if($aValue['startTime'] >= strtotime($aWeeks[Base::$aRequest['week']]['from'])){   
	           if($aValue['callType'] == 0) {
	               $aData['amount_of_calls']['this_week']['incoming'] += 1;  
	               $aData['duration_calls']['this_week']['in_billsec'] += $aValue['billsec'];
	               
	               //average_load
	               $hour = date('G',$aValue['startTime']);
	               if($aData['average_load'][$hour.'-'.($hour+1)]){
	                   $aData['average_load'][$hour.'-'.($hour+1)]['incoming'] += 1;
	                   if($aValue['disposition']!=='ANSWER') 
	                       $aData['average_load'][$hour.'-'.($hour+1)]['lost'] += 1;
	               }             
	           } else{
	               $aData['amount_of_calls']['this_week']['outgoing'] += 1;
	               $aData['duration_calls']['this_week']['out_billsec'] += $aValue['billsec'];
	           }    
	           if($aValue['isNewCall'] == 1) {
	               $aData['new_calls']['this_week']['new'] += 1;
	               if($aValue['disposition'] !== 'ANSWER') $aData['new_calls']['this_week']['lost'] += 1;
	           }
	           $aData['quality']['this_week']['waitsec'] += $aValue['waitsec'];
	           if($aValue['disposition'] !== 'ANSWER') $aData['quality']['this_week']['lost'] += 1;       
   
	        //if last_week  
	        } else { 
	            if($aValue['callType'] == 0){
	                $aData['amount_of_calls']['last_week']['incoming'] += 1;
	                $aData['duration_calls']['last_week']['in_billsec'] += $aValue['billsec'];
	            } else {
	                $aData['amount_of_calls']['last_week']['outgoing'] += 1;
	                $aData['duration_calls']['last_week']['out_billsec'] += $aValue['billsec'];
	            } 
	            if($aValue['isNewCall'] == 1){
	                $aData['new_calls']['last_week']['new'] += 1;
	                if($aValue['disposition'] !== 'ANSWER') $aData['new_calls']['last_week']['lost'] += 1;
	            } 
	            $aData['quality']['last_week']['waitsec'] += $aValue['waitsec'];
	            if($aValue['disposition'] !== 'ANSWER') $aData['quality']['last_week']['lost'] += 1;  
	        }
	    }
// 	    $aData['amount_of_calls']['this_week']['incoming']=28;
// 	    $aData['amount_of_calls']['this_week']['outgoing']=11;
// 	    $aData['amount_of_calls']['last_week']['incoming']=24;
	    
// 	    $aData['amount_of_calls']['last_week']['outgoing']=8;
	    
	    //Объем звонков
	    $aData['amount_of_calls']['this_week']['all'] = $aData['amount_of_calls']['this_week']['incoming'] + $aData['amount_of_calls']['this_week']['outgoing'];
	    $aData['amount_of_calls']['last_week']['all'] = $aData['amount_of_calls']['last_week']['incoming'] + $aData['amount_of_calls']['last_week']['outgoing'];
	    $aData['amount_of_calls']['percent']['incoming'] = $this->GetPercentGrowth($aData['amount_of_calls']['last_week']['incoming'], $aData['amount_of_calls']['this_week']['incoming']);
	    $aData['amount_of_calls']['percent']['outgoing'] = $this->GetPercentGrowth($aData['amount_of_calls']['last_week']['outgoing'], $aData['amount_of_calls']['this_week']['outgoing']);
	    $aData['amount_of_calls']['percent']['all'] = $this->GetPercentGrowth($aData['amount_of_calls']['last_week']['all'], $aData['amount_of_calls']['this_week']['all']);
	    
// 	    $aData['new_calls']['this_week']['new']=5;
// 	    $aData['new_calls']['last_week']['new']=1;
// 	    $aData['new_calls']['this_week']['lost']=1;

	    //Звонили впервые
	    $aData['new_calls']['this_week']['percent_lost'] = round(($aData['new_calls']['this_week']['lost'] * 100) / $aData['new_calls']['this_week']['new']);
	    $aData['new_calls']['last_week']['percent_lost'] = round(($aData['new_calls']['last_week']['lost'] * 100) / $aData['new_calls']['last_week']['new']);
	    $aData['new_calls']['percent_new'] = $this->GetPercentGrowth($aData['new_calls']['last_week']['new'], $aData['new_calls']['this_week']['new']);
	    
// 	    $aData['quality']['last_week']['lost']=1;
// 	    $aData['quality']['this_week']['lost']=100;

	    //Показатели качества
	    $aData['quality']['this_week']['sWaitsec'] = $this->SecondsToTimeInterval($aData['quality']['this_week']['waitsec']/$aData['amount_of_calls']['this_week']['all']);
	    $aData['quality']['last_week']['sWaitsec'] = $this->SecondsToTimeInterval($aData['quality']['last_week']['waitsec']/$aData['amount_of_calls']['last_week']['all']);
	    $aData['quality']['percent_lost'] = $this->GetPercentGrowth($aData['quality']['last_week']['lost'],$aData['quality']['this_week']['lost']);
	    

// 	    $aData['average_load']['9-10']['incoming']=15;
// 	    $aData['average_load']['9-10']['lost']=10;

// 	    $aData['duration_calls']['this_week']['in_billsec']=4080;
// 	    $aData['amount_of_calls']['this_week']['incoming']=1;
// 	    $aData['amount_of_calls']['this_week']['all']=1;
	    
	    //Длительность разговоров
	    $aData['duration_calls']['this_week']['all_billsec'] = $aData['duration_calls']['this_week']['in_billsec'] + $aData['duration_calls']['this_week']['out_billsec'];
	    $aData['duration_calls']['last_week']['all_billsec'] = $aData['duration_calls']['last_week']['in_billsec'] + $aData['duration_calls']['last_week']['out_billsec'];
	    $aData['duration_calls']['this_week']['avg_in'] = $aData['duration_calls']['this_week']['in_billsec']/$aData['amount_of_calls']['this_week']['incoming'];
	    $aData['duration_calls']['this_week']['avg_out'] = $aData['duration_calls']['this_week']['out_billsec']/$aData['amount_of_calls']['this_week']['outgoing'];
	    $aData['duration_calls']['this_week']['avg_all'] = $aData['duration_calls']['this_week']['all_billsec']/$aData['amount_of_calls']['this_week']['all'];
	    $aData['duration_calls']['this_week']['sAvg_in'] = $this->SecondsToTimeInterval($aData['duration_calls']['this_week']['avg_in']);
	    $aData['duration_calls']['this_week']['sAvg_out'] = $this->SecondsToTimeInterval($aData['duration_calls']['this_week']['avg_out']);
	    $aData['duration_calls']['this_week']['sAvg_all'] = $this->SecondsToTimeInterval($aData['duration_calls']['this_week']['avg_all']);
	    $aData['duration_calls']['last_week']['avg_in'] = $aData['duration_calls']['last_week']['in_billsec']/$aData['amount_of_calls']['last_week']['incoming'];
	    $aData['duration_calls']['last_week']['avg_out'] = $aData['duration_calls']['last_week']['out_billsec']/$aData['amount_of_calls']['last_week']['outgoing'];
	    $aData['duration_calls']['last_week']['avg_all'] = $aData['duration_calls']['last_week']['all_billsec']/$aData['amount_of_calls']['last_week']['all'];
	    $aData['duration_calls']['last_week']['sAvg_in'] = $this->SecondsToTimeInterval($aData['duration_calls']['last_week']['avg_in']);
	    $aData['duration_calls']['last_week']['sAvg_out'] = $this->SecondsToTimeInterval($aData['duration_calls']['last_week']['avg_out']);
	    $aData['duration_calls']['last_week']['sAvg_all'] = $this->SecondsToTimeInterval($aData['duration_calls']['last_week']['avg_all']);       
	    $aData['duration_calls']['percent']['avg_in'] = $this->GetPercentGrowth($aData['duration_calls']['last_week']['avg_in'],$aData['duration_calls']['this_week']['avg_in']);
        $aData['duration_calls']['percent']['avg_out'] = $this->GetPercentGrowth($aData['duration_calls']['last_week']['avg_out'],$aData['duration_calls']['this_week']['avg_out']);
        $aData['duration_calls']['percent']['avg_all'] = $this->GetPercentGrowth($aData['duration_calls']['last_week']['avg_all'],$aData['duration_calls']['this_week']['avg_all']);
        
	    Base::$tpl->assign('aData',$aData);
	    
	    Base::$sText.=Base::$tpl->fetch('binotel/analytics_trends.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function AnalyticsLoad(){
	    Base::$sText.=Base::$tpl->fetch('binotel/tab_analytics.tpl');
	    
	    if(!Base::$aRequest['search']['date_from']) Base::$aRequest['search']['date_from'] = date("d.m.Y",time());
	    if(!Base::$aRequest['search']['date_to']) Base::$aRequest['search']['date_to'] = date("d.m.Y",time());
	    
	    $aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("d.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
	    $aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
	    
	    $oForm=new Form();
	    $oForm->aField=$aField;
	    $oForm->bType='generate';
	    $oForm->sGenerateTpl='form/index_search.tpl';
	    $oForm->sSubmitButton='search';
	    $oForm->sSubmitAction='binotel_analytics_load';
	    $oForm->sReturnButton='Clear';
	    Base::$sText.=$oForm->getForm();
	    
	    //empty data for table average load
	    for($i=0; $i<24; $i++) $aData[$i.'-'.($i+1)] = array('incoming'=>0,'lost'=>'0','billsec'=>0);
	    
	    $aCalls = $this->GetAllCallsByPeriod(Base::$aRequest['search']['date_from'], Base::$aRequest['search']['date_to']);
	    if($aCalls) foreach ($aCalls as $sKey => $aValue)
	        if($aValue['callType'] == 0){     
	           $hour = date('G',$aValue['startTime']);
	           $aData[$hour.'-'.($hour+1)]['incoming'] += 1;
	           $aData[$hour.'-'.($hour+1)]['billsec'] += $aValue['billsec'];
	           if($aValue['disposition']!=='ANSWER')
	               $aData[$hour.'-'.($hour+1)]['lost'] += 1; 
	        }
        foreach ($aData as $sKey => $aValue)
            $aData[$sKey]['sBillsec']=$this->SecondsToTimeInterval($aValue['billsec']);
	    
	    Base::$tpl->assign('aData',$aData);
	    
	    Base::$sText.=Base::$tpl->fetch('binotel/analytics_load.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function AnalyticsProductivity(){
	    Base::$sText.=Base::$tpl->fetch('binotel/tab_analytics.tpl');
	
	    if(!Base::$aRequest['search']['date_from']) Base::$aRequest['search']['date_from'] = date("d.m.Y",time());
	    if(!Base::$aRequest['search']['date_to']) Base::$aRequest['search']['date_to'] = date("d.m.Y",time());
	     
	    $aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("d.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
	    $aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
	     
	    $oForm=new Form();
	    $oForm->aField=$aField;
	    $oForm->bType='generate';
	    $oForm->sGenerateTpl='form/index_search.tpl';
	    $oForm->sSubmitButton='search';
	    $oForm->sSubmitAction='binotel_analytics_productivity';
	    $oForm->sReturnButton='Clear';
	    Base::$sText.=$oForm->getForm();
	    
	    $aCalls = $this->GetAllCallsByPeriod(Base::$aRequest['search']['date_from'], Base::$aRequest['search']['date_to']);

	    $aEmployees=$this->GetAllManagers();
	    foreach ($aEmployees as $sKey=>$aValue)
	        $aData[$aValue['extNumber']]=array('managerName'=>$aValue['name'],'incoming_unique_numbers'=>array(),'outgoing_unique_numbers'=>array());
	    $aData['total']['incoming_unique_numbers']=array();
	    $aData['total']['outgoing_unique_numbers']=array();
	    
	    if($aCalls)foreach ($aCalls as $sKey => $aValue){
	        if($aValue['callType']==0){ //incoming
	            if($aValue['disposition']=='ANSWER'){
	               $aData[$aValue['internalNumber']]['incoming_success'] += 1;
	               $aData['total']['incoming_success'] += 1;
	               if($aValue['isNewCall']){
	                   $aData[$aValue['internalNumber']]['incoming_new'] += 1;
	                   $aData['total']['incoming_new'] += 1;
	               }
	               if(!in_array($aValue['externalNumber'],$aData[$aValue['internalNumber']]['incoming_unique_numbers']))
	                   array_push($aData[$aValue['internalNumber']]['incoming_unique_numbers'],$aValue['externalNumber']);
	               if(!in_array($aValue['externalNumber'],$aData['total']['incoming_unique_numbers']))
	                   array_push($aData['total']['incoming_unique_numbers'],$aValue['externalNumber']);
	            } else{
	                $aData[$aValue['internalNumber']]['incoming_failed'] += 1;
	                $aData['total']['incoming_failed'] += 1;
	            }
	                
	            $aData[$aValue['internalNumber']]['billsec_incoming'] += $aValue['billsec'];
	            $aData['total']['billsec_incoming'] += $aValue['billsec'];
	            foreach($aValue['dstNumbers'] as $aValuedst){
	               if($aData[$aValuedst['dstNumber']]){
	                   $aData[$aValuedst['dstNumber']]['incoming_waitsec'] += $aValuedst['waitsec'];
	                   $aData['total']['incoming_waitsec'] += $aValuedst['waitsec'];
	                   break;
	               } else{
	                   $aData[$aValue['internalNumber']]['incoming_waitsec'] += $aValue['waitsec'];
	                   $aData['total']['incoming_waitsec'] += $aValue['waitsec'];
	               }	            
	            }
	        }else { //outgoing
	            $aData[$aValue['internalNumber']]['outgoing_amount'] += 1;
	            $aData['total']['outgoing_amount'] += 1;
	            if(!in_array($aValue['externalNumber'],$aData[$aValue['internalNumber']]['outgoing_unique_numbers']))
	                array_push($aData[$aValue['internalNumber']]['outgoing_unique_numbers'],$aValue['externalNumber']);
	            if(!in_array($aValue['externalNumber'],$aData['total']['outgoing_unique_numbers']))
	                array_push($aData['total']['outgoing_unique_numbers'],$aValue['externalNumber']);
	            if($aValue['disposition']=='ANSWER'){
	                $aData[$aValue['internalNumber']]['outgoing_success'] += 1;
	                $aData['total']['outgoing_success'] += 1;
	            }
	            $aData[$aValue['internalNumber']]['billsec_outgoing'] += $aValue['billsec'];
	            $aData['total']['billsec_outgoing'] += $aValue['billsec'];
	        }
	    }
	    $aData['total']['incoming_unique']=count($aData['total']['incoming_unique_numbers']);
	    $aData['total']['outgoing_unique']=count($aData['total']['outgoing_unique_numbers']);
	    $aData['total']['billsec_all']=$aData['total']['billsec_incoming']+$aData['total']['billsec_outgoing'];
	    
	    foreach ($aData as $sKey=>$aValue){
	       $aData[$sKey]['incoming_unique']=count($aValue['incoming_unique_numbers']);
	       $aData[$sKey]['outgoing_unique']=count($aValue['outgoing_unique_numbers']);
	       $aData[$sKey]['billsec_all']=$aData[$sKey]['billsec_incoming']+$aData[$sKey]['billsec_outgoing'];
	       $aData[$sKey]['sBillsec_all']=$this->SecondsToTimeInterval($aData[$sKey]['billsec_all']);
	       $aData[$sKey]['sBillsec_incoming']=$this->SecondsToTimeInterval($aData[$sKey]['billsec_incoming']);
	       $aData[$sKey]['sBillsec_outgoing']=$this->SecondsToTimeInterval($aData[$sKey]['billsec_outgoing']);
	       $aData[$sKey]['sAvarage_incoming_waitsec']=$this->SecondsToTimeInterval($aData[$sKey]['incoming_waitsec']/($aData[$sKey]['incoming_success']+$aData[$sKey]['incoming_failed']));
	       $aData[$sKey]['sAvarage_billsec']=$this->SecondsToTimeInterval($aData[$sKey]['billsec_all']/($aData[$sKey]['outgoing_success']+$aData[$sKey]['incoming_success']));

	       $aData[$sKey]['incoming_success_percent']=round(($aData[$sKey]['incoming_success']*100)/$aData['total']['incoming_success']);
	       $aData[$sKey]['incoming-new_percent']=round(($aData[$sKey]['incoming-new']*100)/$aData[$sKey]['incoming_success']);
	       $aData[$sKey]['incoming_unique_percent']=round(($aData[$sKey]['incoming_unique']*100)/$aData[$sKey]['incoming_success']);
	       $aData[$sKey]['outgoing_amount_percent']=round(($aData[$sKey]['outgoing_amount']*100)/$aData['total']['outgoing_amount']);
	       $aData[$sKey]['outgoing_success_percent']=round(($aData[$sKey]['outgoing_success']*100)/$aData[$sKey]['outgoing_amount']);
	       $aData[$sKey]['outgoing_unique_percent']=round(($aData[$sKey]['outgoing_unique']*100)/$aData[$sKey]['outgoing_amount']);
	       $aData[$sKey]['billsec_all_percent']=round(($aData[$sKey]['billsec_all']*100)/$aData['total']['billsec_all']);
	       $aData[$sKey]['billsec_incoming_percent']=round(($aData[$sKey]['billsec_incoming']*100)/$aData[$sKey]['billsec_all']);
	       $aData[$sKey]['billsec_outgoing_percent']=round(($aData[$sKey]['billsec_outgoing']*100)/$aData[$sKey]['billsec_all']);
	    }
	    $aData['total']['sBillsec_all']=$this->SecondsToTimeInterval($aData['total']['billsec_all']);
	    $aData['total']['sBillsec_incoming']=$this->SecondsToTimeInterval($aData['total']['billsec_incoming']);
	    $aData['total']['sBillsec_outgoing']=$this->SecondsToTimeInterval($aData['total']['billsec_outgoing']);
	    $aData['total']['sAvarage_billsec']=$this->SecondsToTimeInterval($aData['total']['billsec_all']/($aData['total']['outgoing_success']+$aData['total']['incoming_success']));
	    $aData['total']['sAvarage_incoming_waitsec']=$this->SecondsToTimeInterval($aData['total']['incoming_waitsec']/($aData['total']['incoming_success']+$aData['total']['incoming_failed']));
	    $aData['total']['incoming_new_percent']=round(($aData['total']['incoming_new']*100)/$aData['total']['incoming_success']);
	    $aData['total']['outgoing_success_percent']=round(($aData['total']['outgoing_success']*100)/$aData['total']['outgoing_amount']);
	    $aData['total']['billsec_incoming_percent']=round(($aData['total']['billsec_incoming']*100)/$aData['total']['billsec_all']);
	    $aData['total']['billsec_outgoing_percent']=round(($aData['total']['billsec_outgoing']*100)/$aData['total']['billsec_all']);
	    
	    Base::$tpl->assign('aData',$aData);
	    
	    Base::$sText.=Base::$tpl->fetch('binotel/analytics_productivity.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function AnalyticsTimeline(){
	    Base::$sText.=Base::$tpl->fetch('binotel/tab_analytics.tpl');
	    
	    if(!Base::$aRequest['search']['date_from']) Base::$aRequest['search']['date_from'] = date("d.m.Y",time());
	    if(!Base::$aRequest['search']['date_to']) Base::$aRequest['search']['date_to'] = date("d.m.Y",time());
	     
	    $aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("d.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
	    $aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
	     
	    $oForm=new Form();
	    $oForm->aField=$aField;
	    $oForm->bType='generate';
	    $oForm->sGenerateTpl='form/index_search.tpl';
	    $oForm->sSubmitButton='search';
	    $oForm->sSubmitAction='binotel_analytics_timeline';
	    $oForm->sReturnButton='Clear';
	    Base::$sText.=$oForm->getForm();
	    
	    
 	    $aResult=$this->GetAllCallsByPeriod(Base::$aRequest['search']['date_from'], Base::$aRequest['search']['date_to']);
	    if($aResult) foreach ($aResult as $sKey=>$aValue){
	        if($aValue['disposition']!='ANSWER' && $aValue['callType']==0){
	            $aCalls[$sKey]['id']=$aValue['callID'];
	            $aCalls[$sKey]['group']='failed-0';
	            $aCalls[$sKey]['end']=$aValue['startTime']+$aValue['waitsec'];
	            $aCalls[$sKey]['content']=$aValue['waitsec'];
	            $aCalls[$sKey]['title']=$aValue['waitsec'].' - '.$aValue['externalNumber'].' - входящий';
	            $aCalls[$sKey]['className']='failed';
	            if(!$aGroups['failed-0']){
	                $aGroups['failed-0']['content']='Непринятые';
	                $aGroups['failed-0']['order']='1';
	            }
	        }elseif ($aValue['disposition']=='ANSWER') {
	            $aCalls[$sKey]['id']=$aValue['internalNumber'].'-'.$aValue['callID'];
	            $aCalls[$sKey]['group']=$aValue['internalNumber'];
	            $aCalls[$sKey]['end']=$aValue['startTime']+$aValue['billsec'];
	            $aCalls[$sKey]['content']=$aValue['billsec'];
	            $aCalls[$sKey]['title']=$aValue['billsec'].' - '.$aValue['externalNumber'].($aValue['callType']==0)?'входящий':'исходящий'.
	            ' - время ожидания '.$aValue['waitsec'];
	        }
	        $aCalls[$sKey]['start']=$aValue['startTime'];
	        $aCalls[$sKey]['sContent']=$this->SecondsToTimeInterval($aCalls[$sKey]['content']);
	    }
	    
	    $aManagers=$this->GetAllManagers();
 	    foreach ($aManagers as $sKey=>$aValue){
 	        $aGroups[$aValue['extNumber']]['content']=$aValue['name']?$aValue['name'].' - '.$aValue['extNumber']:$aValue['extNumber'];
 	        $aGroups[$aValue['extNumber']]['order']=$aValue['extNumber'];
 	    }
	    $aDateRange['min']=mktime(0,0,0,date('n',strtotime(Base::$aRequest['search']['date_from'])),date('j',strtotime(Base::$aRequest['search']['date_from'])),date('Y',strtotime(Base::$aRequest['search']['date_from'])));
	    $aDateRange['max']=mktime(23,59,59,date('n',strtotime(Base::$aRequest['search']['date_to'])),date('j',strtotime(Base::$aRequest['search']['date_to'])),date('Y',strtotime(Base::$aRequest['search']['date_to'])));
        $aDateRange['window']=date("Y-m-d",$aDateRange['min']);
	    
	    Base::$tpl->assign('aDateRange',$aDateRange);
	    Base::$tpl->assign('aGroups',$aGroups);
	    Base::$tpl->assign('aCalls',$aCalls);
	    
	    Base::$sText.=Base::$tpl->fetch('binotel/analytics_timeline.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function AnalyticsOutgoing(){
	    Base::$sText.=Base::$tpl->fetch('binotel/tab_analytics.tpl');

	    if(!Base::$aRequest['search']['date_from']) Base::$aRequest['search']['date_from'] = date("d.m.Y",time());
	    if(!Base::$aRequest['search']['date_to']) Base::$aRequest['search']['date_to'] = date("d.m.Y",time());
	    
	    $aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("d.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
	    $aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
	    
	    $oForm=new Form();
	    $oForm->aField=$aField;
	    $oForm->bType='generate';
	    $oForm->sGenerateTpl='form/index_search.tpl';
	    $oForm->sSubmitButton='search';
	    $oForm->sSubmitAction='binotel_analytics_outgoing';
	    $oForm->sReturnButton='Clear';
	    Base::$sText.=$oForm->getForm();
	    
 	    $aCalls = $this->GetOutputCallsByPeriod(Base::$aRequest['search']['date_from'], Base::$aRequest['search']['date_to']);
// 	    $aCalls['121690788']['externalNumber']='+380888932784';
//  	    $aCalls['121690788']['billsec']=654;
	    foreach ($aCalls as $sKey => $aValue){
	        //международные если начинается с + ???
	        if($aValue['externalNumber']{0}=='+' && substr($aValue['externalNumber'],0,4)!='+380')
	            $aData['total']['international']+=$aValue['billsec'];
	        else{
	           $iCode = substr($aValue['externalNumber'],0,3);
	           switch ($iCode){
	               //городские направления Чернигов ???
	               case '046':
	                   $aData['total']['local']+=$aValue['billsec'];
	               //kievstar
	               case '039': case '067': case '068': case '096': case '097': case '098':
	                   $aData['total']['kievstar']+=$aValue['billsec'];
	                   continue;
	            
	               //mts
	               case '050': case '066': case '095': case '099':
	                   $aData['total']['mts']+=$aValue['billsec'];
	                   continue;
	            
	               //life
	               case '063': case '093':
	                   $aData['total']['life']+=$aValue['billsec'];
	                   continue;
	                
	                //межгород
	               //case '046':
	               case '031': case '032': case '033': case '034': case '035': case '036': case '037': case '038': case '041': case '042': 
	               case '043': case '044': case '045':  case '047': case '048': case '049': case '051': case '052': case '053': case '054': 
	               case '055': case '056': case '057': case '058': case '059': case '061': case '062': case '063': case '064': case '065': case '069':
	                   $aData['total']['regional']+=$aValue['billsec'];
	                   continue;
	           }
	        }

	    }
	    $aData['total']['sLocal']=$this->SecondsToTimeInterval($aData['total']['local']);
	    $aData['total']['sKievstar']=$this->SecondsToTimeInterval($aData['total']['kievstar']);
	    $aData['total']['sMts']=$this->SecondsToTimeInterval($aData['total']['mts']);
	    $aData['total']['sLife']=$this->SecondsToTimeInterval($aData['total']['life']);
	    $aData['total']['sRegional']=$this->SecondsToTimeInterval($aData['total']['regional']);
	    $aData['total']['sInternational']=$this->SecondsToTimeInterval($aData['total']['international']);
	    
	    Base::$tpl->assign('aData',$aData);
	    
	    Base::$sText.=Base::$tpl->fetch('binotel/analytics_outgoing.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function CallsReception(){
	    
// 	    Base::$aRequest['didNumber']= '0443334000';
	    Base::$aRequest['didNumber']='0993103778';
	    Base::$aRequest['srcNumber']= '0670219424';
	    Base::$aRequest['generalCallID']= '2500834';
	    Base::$aRequest['callType']= '0';
	    Base::$aRequest['companyID']= '3041';
	    Base::$aRequest['requestType']= 'receivedTheCall';

	
	    
	    if(Base::$aRequest['requestType']=='receivedTheCall' && Base::$aRequest['callType']=='0'){
	        $aCustomerData = current($this->GetUserByNameOrPhone(Base::$aRequest['didNumber']));
	        $aCustomerData['number']=Base::$aRequest['didNumber'];
	    
	        $aCustomerData['srcNumber']=Base::$aRequest['srcNumber'];
	        $aCustomerData['extNumber']=Base::$aRequest['extNumber'];
	        
	        Base::$tpl->assign('aCustomerData',$aCustomerData);
	        Base::$sText.=Base::$tpl->fetch('binotel/calls_reception.tpl');
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function GetCallFromStart() {
	    /*
	     Пример 2: входящие или исходящие звонки с N времени по настоящее время.
	    
	     Вариант адреса:
	     - all-incoming-calls-since - для входящих
	     - all-outgoing-calls-since - для исходящих
	    
	     Параметры:
	     - timestamp  - время начала выбора звонков (в формате unix timestamp)
	     */
	    
	    $lastRequestTimestamp = strtotime("2016-01-01 00:00:00"); // Sat, 01 Jun 2013 00:00:00 +0300
	    
	    $result = $this->oBinotel->sendRequest('stats/all-incoming-calls-since', array(
	        'timestamp' => $lastRequestTimestamp
	    ));
	    
// 	    if ($result['status'] === 'success') {
// 	        Debug::PrintPre($result['callDetails'],false);
// 	    } else {
// 	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
// 	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
// 	    }
	    
	    
	    $aData=$result['callDetails'];
	    if($aData) foreach ($aData as $sKey => $aValue) {
	        if($aValue['disposition']=='ANSWER') {
	            $aData[$sKey]['record_url']=$this->GetCallRecord($aValue['callID']);
	        }
	        $aData[$sKey]['date']=Language::GetDateTime($aValue['startTime']);
	    }
	    
	    return $aData;
	}
	//-----------------------------------------------------------------------------------------------
	public function GetCallRecord($sCallId)
	{
	    /*
	     Пример 11: получение ссылки на запись разговора.
	     Внимание: время жизни ссылки на запись разговора 15 минут.
	    
	     Параметры:
	     - callID  - идентификатор записи разговора
	     */
	    
	    $result = $this->oBinotel->sendRequest('stats/call-record', array(
	        'callID' => $sCallId
	    ));
	    
// 	    if ($result['status'] === 'success') {
// 	        Debug::PrintPre($result['url'],false);
// 	    } else {
// 	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
// 	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
// 	    }

	    return $result['url'];
	}
	//-----------------------------------------------------------------------------------------------
	public function GetCallsNow() {
	    /*
	     Пример 3: звонки которые в онлайне.
	     Параметры: пустой массив.
	     */
	    
	    $result = $this->oBinotel->sendRequest('stats/online-calls', array());
	    
	    if($result['callDetails']) foreach ($result['callDetails'] as $sKey => $aValue) {
	        if($aValue['billsec'] != 0) $result['callDetails'][$sKey]['sBillsec'] = $this->SecondsToTimeInterval($aValue['billsec']);
	        else $result['callDetails'][$sKey]['sBillsec'] = '';
	        $result['callDetails'][$sKey]['date']=Language::GetDateTime($aValue['startTime']);
	    }
	    
// 	    if ($result['status'] === 'success') {
// 	        Debug::PrintPre($result['callDetails'],false);
// 	    } else {
// 	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
// 	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
// 	    }
	    
	    return $result['callDetails'];
	}
	//-----------------------------------------------------------------------------------------------
	public function GetLostCalls($sDateStart,$sDateEnd) {
	    /*
	     Пример 6: потерянные звонки за сегодня.
	     Параметры: пустой массив.
	     */
	    
// 	    $result = $this->oBinotel->sendRequest('stats/list-of-lost-calls-today', array());
	    
// 	    if ($result['status'] === 'success') {
// 	        Debug::PrintPre($result['callDetails'],false);
// 	    } else {
// 	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
// 	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
// 	    } 

        if($sDateStart === $sDateEnd)
            $result = $this->oBinotel->sendRequest('stats/list-of-lost-calls-today', array());
        else{
            $incoming = $this->GetInputCallsByPeriod($sDateStart,$sDateEnd);
            $outgoing = $this->GetOutputCallsByPeriod($sDateStart, $sDateEnd);      

	        if($incoming) foreach ($incoming as $sKeyIn => $aValueIn) {
	            $iAttemptsCallBack = 0;
	            $aPhoneNumbers = array();
	            
	            if($aValueIn['disposition'] != 'ANSWER'){
	               $bIsLost = true;
	               foreach ($outgoing as $sKeyOut => $aValueOut){
	                   if($aValueIn['externalNumber'] === $aValueOut['externalNumber']){
	                       if($aValueOut['disposition'] == 'ANSWER'){
	                          $bIsLost = false;
	                          break; 
	                       } else 
	                           $iAttemptsCallBack++;
	                   }
	               }
	               foreach ($incoming as $sKeyIn2 => $aValueIn2){
	                   if($aValueIn['externalNumber'] === $aValueIn2['externalNumber'] && $aValueIn2['disposition'] == 'ANSWER'){
	                       $bIsLost = false;
	                       break;
	                   }      
	               }
	               if(in_array($aValueIn['externalNumber'], $aPhoneNumbers)) $bIsLost = false;
	                   else array_push($aPhoneNumbers, $aValueIn['externalNumber']);
	               if($bIsLost){
	                   $result['callDetails'][$sKeyIn] = $aValueIn;
	                   $result['callDetails'][$sKeyIn]['attemptsCallBack'] = $iAttemptsCallBack++;
	                   $result['callDetails'][$sKeyIn]['date'] = Language::GetDateTime($aValueIn['startTime']);
	                   if($aValueIn['billsec'] != 0) $result['callDetails'][$sKeyIn]['sBillsec'] = $this->SecondsToTimeInterval($aValueIn['billsec']);
	                       else $result['callDetails'][$sKeyIn]['sBillsec'] = '';
	                   if($aValueIn['waitsec'] != 0) $result['callDetails'][$sKeyIn]['sWaitsec']=$this->SecondsToTimeInterval($aValueIn['waitsec']);
	                       else $result['callDetails'][$sKeyIn]['sWaitsec']='';
	               }  
	           }
	        }
        }
	    
	    return $result['callDetails'];
	}
	//-----------------------------------------------------------------------------------------------
	public function GetCallsByNumber($sNumber) {
	    /*
	     Пример 7: звонки по номеру телефона (как входящие, так и исходящие).
	    
	     Параметры:
	     - number  - номер или номера в массиве
	     */
	    
	    $result = $this->oBinotel->sendRequest('stats/history-by-number', array(
	        'number' => array($sNumber)
	    ));
	    
// 	    if ($result['status'] === 'success') {
// // 	        Debug::PrintPre($result['callDetails'],false);
// 	    } else {
// 	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
// 	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
// 	    }
	    
	    if($result['callDetails']) foreach ($result['callDetails'] as $sKey => $aValue) {
	        if($aValue['billsec'] != 0) $result['callDetails'][$sKey]['sBillsec'] = $this->SecondsToTimeInterval($aValue['billsec']);
	           else $result['callDetails'][$sKey]['sBillsec'] = '';
	        if($aValue['waitsec'] != 0) $result['callDetails'][$sKey]['sWaitsec']=$this->SecondsToTimeInterval($aValue['waitsec']);
	           else $result['callDetails'][$sKey]['sWaitsec']='';
	        if($aValue['disposition']=='ANSWER') {
	            $result['callDetails'][$sKey]['record_url']=$this->GetCallRecord($aValue['callID']);
	        } 	        
	        $result['callDetails'][$sKey]['date']=Language::GetDateTime($aValue['startTime']);
	    }
	    
	    return $result['callDetails'];
	}
	//-----------------------------------------------------------------------------------------------
	public function GetCallsByUser($sUser) {
	    /*
	     Пример 8: звонки по идентификатору клиента (как входящие, так и исходящие).
	    
	     Параметры:
	     - customerID  - идентификатор клиента или идентификаторы клиентов в массиве
	     */
	    
	    $result = $this->oBinotel->sendRequest('stats/history-by-customer-id', array(
	        'customerID' => $sUser
	    ));
	    
	    if ($result['status'] === 'success') {
	        Debug::PrintPre($result['callDetails'],false);
	    } else {
	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
	    }
	    
	    return $result['callDetails'];
	}
	//-----------------------------------------------------------------------------------------------
    public function GetCallsByManager($sDateStart,$sDateEnd,$iManagerNumber){
        $result = $this->GetAllCallsByPeriod($sDateStart, $sDateEnd, $iManagerNumber);
        
        return $result;
    }
	//-----------------------------------------------------------------------------------------------
	public function GetCallInfo($sCallId) {
	    /*
	     Пример 10: данные о звонке по идентификатору звонка.
	    
	     Параметры:
	     - generalCallID  - идентификатор звонка или массив c идентификаторами звонков
	     */
	    
	    $result = $this->oBinotel->sendRequest('stats/call-details', array(
	        'generalCallID' => array($sCallId)
	    ));
	    
	    if ($result['status'] === 'success') {
	        Debug::PrintPre($result['callDetails'],false);
	    } else {
	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
	    }
	    
	    return $result['callDetails'];
	}
	//-----------------------------------------------------------------------------------------------
	public function GetMyLastCalls($sInternalNumber) {
	    /*
	     Пример 9: недавние звонки по внутреннему номеру сотрудника (как входящие, так и исходящие). Используется для реализации функции "Мои недавние звонки" для сотрудника.
	    
	     Параметры:
	     - internalNumber  - внутренний номер сотрудника
	    
	     Ограничения: звонки за последние 2 недели и не более 50 звонков.
	     */
	    
	    $result = $this->oBinotel->sendRequest('stats/recent-calls-by-internal-number', array(
	        'internalNumber' => $sInternalNumber
	    ));
	    
	    if ($result['status'] === 'success') {
	        Debug::PrintPre($result['callDetails'],false);
	    } else {
	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
	    }
	    
	    return $result['callDetails'];
	}
	//-----------------------------------------------------------------------------------------------
	public function GetMyCallsByDate($sInternalNumber,$iDateStart,$iDateEnd) {
	    /*
	     Пример 5: звонки по внутреннему номеру сотрудника за период (как входящие, так и исходящие).
	    
	     Параметры:
	     - internalNumber  - внутренний номер сотрудника
	     - startTime  - время начала выбора звонков (в формате unix timestamp)
	     - stopTime  - время окончания выбора звонков (в формате unix timestamp)
	    
	     Ограничения: период не может быть больше 7 дней.
	     */
	    
	    $result = $this->oBinotel->sendRequest('stats/list-of-calls-by-internal-number-for-period', array(
	        'internalNumber' => $sInternalNumber,
	        'startTime' => $iDateStart, //1370034000, // Sat, 01 Jun 2013 00:00:00 +0300
	        'stopTime' => $iDateEnd, //1370638799 // Fri, 07 Jun 2013 23:59:59 +0300
	    ));
	    
	    if ($result['status'] === 'success') {
	        Debug::PrintPre($result['callDetails'],false);
	    } else {
	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
	    }
	    
	    return $result['callDetails'];
	}
	//-----------------------------------------------------------------------------------------------
	public function GetCallsByDay() {
	    /*
	     Пример 4: звонки за день (как входящие, так и исходящие).
	    
	     Параметры:
	     - dayInTimestamp  - день (в формате unix timestamp, при отсутствии этого параметра звонки буду взяты за сегодня)
	     */
	    
	    $result = $this->oBinotel->sendRequest('stats/list-of-calls-per-day', array(
	        'dayInTimestamp' => mktime(0, 0, 0, 11, 25, 2015)
	    ));
	    
	    if ($result['status'] === 'success') {
	        Debug::PrintPre($result['callDetails'],false);
	    } else {
	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
	    }
	    
	    return $result['callDetails'];
	}
	//-----------------------------------------------------------------------------------------------
	public function GetCallsByDate($sDateStart,$sDateEnd) {
	    /*
	     Пример 1: входящие или исходящие звонки за период.
	    
	     Вариант адреса:
	     - incoming-calls-for-period - для входящих
	     - outgoing-calls-for-period - для исходящих
	    
	     Параметры:
	     - startTime  - время начала выбора звонков (в формате unix timestamp)
	     - stopTime  - время окончания выбора звонков (в формате unix timestamp)
	     */
	    
	    $result = $this->oBinotel->sendRequest('stats/outgoing-calls-for-period', array(
	        'startTime' => strtotime($sDateStart), // Sat, 01 Jun 2013 00:00:00 +0300
	        'stopTime' => strtotime($sDateEnd) // Sat, 01 Jun 2013 23:59:59 +0300
	    ));
	    
	    if ($result['status'] === 'success') {
	        Debug::PrintPre($result['callDetails'],false);
	    } else {
	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
	    }
	    
	    return $result['callDetails'];
	}
	//-----------------------------------------------------------------------------------------------
	public function GetInputCallsByPeriod($sDateStart,$sDateEnd) { 
	    $result = $this->oBinotel->sendRequest('stats/incoming-calls-for-period', array(
	        'startTime' => mktime(0,0,0,date('n',strtotime($sDateStart)),date('j',strtotime($sDateStart)),date('Y',strtotime($sDateStart))), 
	        'stopTime' => mktime(23,59,59,date('n',strtotime($sDateEnd)),date('j',strtotime($sDateEnd)),date('Y',strtotime($sDateEnd))) 
	    ));
     
	    if($result['callDetails']) foreach ($result['callDetails'] as $sKey => $aValue) {
	        if($aValue['billsec'] != 0) $result['callDetails'][$sKey]['sBillsec'] = $this->SecondsToTimeInterval($aValue['billsec']);
	           else $result['callDetails'][$sKey]['sBillsec'] = '';
	        if($aValue['waitsec'] != 0) $result['callDetails'][$sKey]['sWaitsec']=$this->SecondsToTimeInterval($aValue['waitsec']);
	           else $result['callDetails'][$sKey]['sWaitsec']='';
	        if($aValue['disposition']=='ANSWER') {
	            $result['callDetails'][$sKey]['record_url']=$this->GetCallRecord($aValue['callID']);
	        } 	        
	        $result['callDetails'][$sKey]['date']=Language::GetDateTime($aValue['startTime']);
	    }

        return $result['callDetails'];	
	}
	//-----------------------------------------------------------------------------------------------
	public function GetOutputCallsByPeriod($sDateStart,$sDateEnd,$iEmployeeNumber='') {
	    $result = $this->oBinotel->sendRequest('stats/outgoing-calls-for-period', array(
	        'startTime' => mktime(0,0,0,date('n',strtotime($sDateStart)),date('j',strtotime($sDateStart)),date('Y',strtotime($sDateStart))), 
	        'stopTime' => mktime(23,59,59,date('n',strtotime($sDateEnd)),date('j',strtotime($sDateEnd)),date('Y',strtotime($sDateEnd))) 
	    ));
	    
	    if($result['callDetails']) foreach ($result['callDetails'] as $sKey => $aValue) {
	        if($iEmployeeNumber=='' || $iEmployeeNumber===$aValue['internalNumber']){
	            if($aValue['billsec']!=0) $result['callDetails'][$sKey]['sBillsec']=$this->SecondsToTimeInterval($aValue['billsec']);
	               else $result['callDetails'][$sKey]['sBillsec']='';
	            if($aValue['waitsec']!=0)$result['callDetails'][$sKey]['sWaitsec']=$this->SecondsToTimeInterval($aValue['waitsec']);
	               else $result['callDetails'][$sKey]['sWaitsec']='';
	            if($aValue['disposition']=='ANSWER')
	                $result['callDetails'][$sKey]['record_url']=$this->GetCallRecord($aValue['callID']);
	            $result['callDetails'][$sKey]['date']=Language::GetDateTime($aValue['startTime']); 
	        } elseif($iEmployeeNumber!='') 
	            unset($result['callDetails'][$sKey]);
	    }
	     
	    return $result['callDetails'];
	}
	//-----------------------------------------------------------------------------------------------
    public function GetAllCallsByPeriod($sDateStart,$sDateEnd,$iEmployeeNumber=''){
        $incoming = $this->oBinotel->sendRequest('stats/incoming-calls-for-period', array(
            'startTime' => mktime(0,0,0,date('n',strtotime($sDateStart)),date('j',strtotime($sDateStart)),date('Y',strtotime($sDateStart))),
            'stopTime' => mktime(23,59,59,date('n',strtotime($sDateEnd)),date('j',strtotime($sDateEnd)),date('Y',strtotime($sDateEnd)))
        ));
        $outgoing = $this->oBinotel->sendRequest('stats/outgoing-calls-for-period', array(
            'startTime' => mktime(0,0,0,date('n',strtotime($sDateStart)),date('j',strtotime($sDateStart)),date('Y',strtotime($sDateStart))),
            'stopTime' => mktime(23,59,59,date('n',strtotime($sDateEnd)),date('j',strtotime($sDateEnd)),date('Y',strtotime($sDateEnd)))
        ));
        $result = $incoming['callDetails'] + $outgoing['callDetails'];
        krsort($result);

        if($result) foreach ($result as $sKey => $aValue) {
            if($iEmployeeNumber=='' || $iEmployeeNumber===$aValue['internalNumber']){
                if($aValue['billsec']!=0) $result[$sKey]['sBillsec']=$this->SecondsToTimeInterval($aValue['billsec']);
                else $result[$sKey]['sBillsec']='';
                if($aValue['waitsec']!=0)$result[$sKey]['sWaitsec']=$this->SecondsToTimeInterval($aValue['waitsec']);
                else $result[$sKey]['sWaitsec']='';
                if($aValue['disposition']=='ANSWER') 
                    $result[$sKey]['record_url']=$this->GetCallRecord($aValue['callID']);
                $result[$sKey]['date']=Language::GetDateTime($aValue['startTime']);
            } elseif($iEmployeeNumber!='')
            unset($result[$sKey]);
        }

        return $result;
    }
	//-----------------------------------------------------------------------------------------------
	public function GetInputCalls() {
	    /*
	     Пример 2: входящие или исходящие звонки с N времени по настоящее время.
	    
	     Вариант адреса:
	     - all-incoming-calls-since - для входящих
	     - all-outgoing-calls-since - для исходящих
	    
	     Параметры:
	     - timestamp  - время начала выбора звонков (в формате unix timestamp)
	     */
	    
	    $lastRequestTimestamp = strtotime("2016-01-01 00:00:00"); // Sat, 01 Jun 2013 00:00:00 +0300
	    
	    $result = $this->oBinotel->sendRequest('stats/all-incoming-calls-since', array(
	        'timestamp' => $lastRequestTimestamp
	    ));
	    
// 	    if ($result['status'] === 'success') {
// 	        Debug::PrintPre($result['callDetails'],false);
// 	    } else {
// 	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
// 	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
// 	    }
	    
	    
	    $aData=$result['callDetails'];
	    if($aData) foreach ($aData as $sKey => $aValue) {
	        if($aValue['disposition']=='ANSWER') {
	            $aData[$sKey]['record_url']=$this->GetCallRecord($aValue['callID']);
	        }
	        $aData[$sKey]['date']=Language::GetDateTime($aValue['startTime']);
	    }
	    
	    return $aData;
	}
	//-----------------------------------------------------------------------------------------------
	public function GetOutputputCalls() {
	    /*
	     Пример 2: входящие или исходящие звонки с N времени по настоящее время.
	    
	     Вариант адреса:
	     - all-incoming-calls-since - для входящих
	     - all-outgoing-calls-since - для исходящих
	    
	     Параметры:
	     - timestamp  - время начала выбора звонков (в формате unix timestamp)
	     */
	    
	    $lastRequestTimestamp = strtotime("2016-01-01 00:00:00"); // Sat, 01 Jun 2013 00:00:00 +0300
	    
	    $result = $this->oBinotel->sendRequest('stats/all-outgoing-calls-since', array(
	        'timestamp' => $lastRequestTimestamp
	    ));
	    
// 	    if ($result['status'] === 'success') {
// 	        Debug::PrintPre($result['callDetails'],false);
// 	    } else {
// 	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
// 	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
// 	    }
	    
	    
	    $aData=$result['callDetails'];
	    if($aData) foreach ($aData as $sKey => $aValue) {
	        if($aValue['disposition']=='ANSWER') {
	            $aData[$sKey]['record_url']=$this->GetCallRecord($aValue['callID']);
	        }
	        $aData[$sKey]['date']=Language::GetDateTime($aValue['startTime']);
	    }
	    
	    return $aData;
	}
	//-----------------------------------------------------------------------------------------------
	public function GetAllUsers()
	{
	    /*
	     Пример 1: выбор всех клиентов с мини-срм "Мои клиенты".
	    
	     Параметры: пустой массив.
	     */
	    
	    $result = $this->oBinotel->sendRequest('customers/list', array());
	    
	    if ($result['status'] === 'success') {
// 	        Debug::PrintPre($result['customerData'],false);
	    } else {
	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
	    }
	     
	    return $result['customerData'];
	}
	//-----------------------------------------------------------------------------------------------
	public function GetUserById($iIdUser)
	{
	    /*
	     Пример 2: выбор клиентов с мини-срм "Мои клиенты" по идентификатору клиента.
	    
	     Параметры:
	     - customerID  - идентификатор клиента или идентификаторы клиентов в массиве.
	     */
	    
	    $customerID = array($iIdUser);
	    
	    $result = $this->oBinotel->sendRequest('customers/take-by-id', array(
	        'customerID' => $customerID
	    ));
	    
	    if ($result['status'] === 'success') {
// 	        Debug::PrintPre($result['customerData'],false);
	    } else {
	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
	    }
	     
	    return $result['customerData'];
	}
	//-----------------------------------------------------------------------------------------------
	public function GetUserByNameOrPhone($sNameOrPhone)
	{
	    /*
	     Пример 4: поиск клиентов в мини-срм "Мои клиенты" по имени или по номеру телефона.
	    
	     Параметры:
	     - subject  - часть имени или номера телоефона.
	     */
	    
	    $result = $this->oBinotel->sendRequest('customers/search', array(
	        'subject' => $sNameOrPhone
	    ));
	    
	    if ($result['status'] === 'success') {
// 	        Debug::PrintPre($result['customerData'],false);
	    } else {
	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
	    }
	     
	    return $result['customerData'];
	}
	//-----------------------------------------------------------------------------------------------
	public function CreateBinotelUser($sName,$aPhones,$sDescription='',$sEmail='',$iManager='901',$aLabels=array())
	{
	    /*
	     Пример 5: создание клиента в мини-срм "Мои клиенты".
	    
	     Параметры:
	     - name  - имя клиента, имя должно быть уникальным (обязательное поле!)
	     - numbers  - массив номеров, все номера должны быть уникальными (обязательное поле!)
	     - email  - email клиента
	     - assignedToEmployeeNumber  - внутренний номер сотрудника в АТС Binotel (пример: 904, важно чтобы линия была закреплена за сотрудником в MyBinotel!)
	     */
	    
	    $result = $this->oBinotel->sendRequest('customers/create', array(
	        'name' => $sName,
	        'numbers' => $aPhones,
	        'description' => $sDescription,
	        'email' => $sEmail,
	        'assignedToEmployeeNumber' => $iManager,
	    ));
	    
	    if ($result['status'] === 'success') {
	        Debug::PrintPre($result,false);
	    } else {
	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
	    }
	    
	    return $result;
	}
	//-----------------------------------------------------------------------------------------------
	public function EditBinotelUser($sId,$sName,$aPhones,$sDescription='',$sEmail='',$iManager='901',$aLabels=array())
	{
	    /*
	     Пример 6: редактирование клиента в мини-срм "Мои клиенты".
	    
	     ВНИМАНИЕ: все данные в массиве которые будут передаваться, будут изменяться, по этому если Вам нужно изменить только имя клиента, необходимо передавать только поле с новым именем с идентификатором клиента. Если вам необходимо добавить новый номер, или удалить номер, для этого нужно передавать новый актуальный список номеров. Редактирование меток происходит так же как и редактирование номеров.
	    
	     Параметры:
	     - id  - идентификатор клиента (обязательное поле!)
	     - name  - имя клиента, имя должно быть уникальным
	     - numbers  - массив номеров, все номера должны быть уникальными
	     - description  - информация о клиенте
	     - email  - email клиента
	     - assignedToEmployeeNumber  - внутренний номер сотрудника в АТС Binotel (пример: 904, важно чтобы линия была закреплена за сотрудником в MyBinotel!)
	     - labels  - массив клиента с идентификаторами меток (список меток с идентификаторами можно получить: customers/listOfLabels)
	    
	    
	     В примере ниже мы делаем:
	     - изменяем имя
	     - обновляем телефонные номера
	     - очищаем описание
	     - убираем ответственного сотрудника
	     - убираем метки
	     */
	    
	    $result = $this->oBinotel->sendRequest('customers/update', array(
	        'id' => $sId,
	        'name' => $sName,
	        'numbers' => $aPhones,
	        'description' => $sDescription,
	        'email' => $sEmail,
	        //'assignedToEmployeeNumber' => $iManager,
	        'assignedToEmployeeID'=> $iManager,
	        'labels' => $aLabels
	    ));
	    
	    if ($result['status'] === 'success') {
// 	        Debug::PrintPre($result,false);
	    } else {
	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
	    }
	    
	    return $result;
	}
	//-----------------------------------------------------------------------------------------------
	public function RemoveBinotelUser($iIdUser)
	{
	    /*
	     Пример 7: удаление клиента в мини-срм "Мои клиенты" по идентификатору клиента.
	    
	     Параметры:
	     - customerID  - идентификатор клиента или идентификаторы клиентов в массиве.
	     */
	    
	    $customerID = array($iIdUser);
	    
	    $result = $this->oBinotel->sendRequest('customers/delete', array(
	        'customerID' => $customerID
	    ));
	    
	    if ($result['status'] === 'success') {
	        Debug::PrintPre($result['status'],false);
	    } else {
	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
	    }
	    
	    return $result;
	}
	//-----------------------------------------------------------------------------------------------
	public function GetClientLabels()
	{
	    /*
	     Пример 8: выбор всех меток с мини-срм "Мои клиенты".
	     Параметры: пустой массив.
	    
	     Разъяснения данных в информации о сценарии:
	     - id  - идентификатор метки
	     - name  - имя метки
	     */
	    
	    $result = $this->oBinotel->sendRequest('customers/listOfLabels', array());
	    
	    if ($result['status'] === 'success') {
	        Debug::PrintPre($result['listOfLabels'],false);
	    } else {
	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
	    }
	    
	    return $result['listOfLabels'];
	}
	//-----------------------------------------------------------------------------------------------
	public function GetAllManagers()
	{
	    /*
	     Пример 1: выбор всех сотрудников.
	     Параметры: пустой массив.
	    
	     Разъяснения данных в информации о сотруднике:
	     - employeeID  - идентификатор сотрудника
	     - email  - email сотрудника
	     - name  - имя сотрудника
	     - mobilePhoneNumber  - мобильный номер сотрудника (в разработке)
	     - presenceState  - статус сотрудника (активен / неактивен, используется для функции липкости)
	     - department  - название отдела
	     - extNumber  - внутренний номер сотрудника (пример: 902)
	     - extHash  - SIP номер сотрудника
	     - extStatus:
	     - status  - состояние внутренней линии сотрудника (online - онлайн, inuse - разговаривает, ringing - совершается вызов на эту линию, offline - офлайн)
	     */
	    
	    $result = $this->oBinotel->sendRequest('settings/list-of-employees', array());
	    
	    if ($result['status'] === 'success') {
// 	        Debug::PrintPre($result['listOfEmployees'],false);
	    } else {
	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
	    }
	    
	    return $result['listOfEmployees'];
	}
	//-----------------------------------------------------------------------------------------------
	public function MakeCallToExternal($iInternal,$iExternal)
	{
	    /*
	     Пример 1: инициирование двустороннего звонка с внутренней линией и внешним номером.
	    
	     Параметры:
	     — ext_number  - внутренний номер сотрудника (первый участник разговора)
	     — phone_number  - телефонный номер куда нужно позвонить (второй участник разговора)
	     — limitCallTime  - ограничение длительности звонка в секундах (необязательный параметр)
	     — playbackWaiting  - проигрывание, первому участнику разговора, фразы: "ожидайте пожалуйста на линии, происходит соединение со 2-м участником разговора". По умолчанию стоит TRUE, принимает значения: TRUE или FALSE (необязательный параметр).
	     */
	    
	    $result = $this->oBinotel->sendRequest('calls/ext-to-phone', array(
	        'ext_number' => $iInternal,
	        'phone_number' => $iExternal,
	        /*'playbackWaiting' => FALSE*/
	    ));
	    
	    if ($result['status'] === 'success') {
	        Debug::PrintPre($result['generalCallID'],false);
	    } else {
	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
	    }
	    
	    return $result['generalCallID'];
	}
	//-----------------------------------------------------------------------------------------------
	public function CallTransfer($iCallId,$iInternalNumber)
	{
	    /*
	     Пример 4: перевод звонка с участием.
	    
	     Параметры:
	     — generalCallID  - идентификатор звонка
	     — phone_number  - номер на который переводится звонок
	     */
	    
	    $result = $this->oBinotel->sendRequest('calls/attended-call-transfer', array(
	        'generalCallID' => $iCallId,
	        'phone_number' => $iInternalNumber
	    ));
	    
	    if ($result['status'] === 'success') {
	        Debug::PrintPre($result,false);
	    } else {
	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
	    }
	    
	    return $result;
	}
	//-----------------------------------------------------------------------------------------------
	public function KillCall($iCallId)
	{
	    /*
	     Пример 5: завершение звонка.
	    
	     Параметры:
	     — generalCallID  - идентификатор звонка
	     */
	    
	    $result = $this->oBinotel->sendRequest('calls/hangup-call', array(
	        'generalCallID' => $iCallId
	    ));
	    
	    if ($result['status'] === 'success') {
	        Debug::PrintPre($result,false);
	    } else {
	        Debug::PrintPre(sprintf('Что-то пошло не так! %s', PHP_EOL),false);
	        Debug::PrintPre(sprintf('Ошибка %s: %s %s', $result['code'], $result['message'], PHP_EOL),false);
	    }
	    
	    return $result;
	}
// 	//-----------------------------------------------------------------------------------------------
// 	public function MakeCall()
// 	{
	    
// 	}
// 	//-----------------------------------------------------------------------------------------------
// 	public function MakeCall()
// 	{
	    
// 	}
// 	//-----------------------------------------------------------------------------------------------
// 	public function MakeCall()
// 	{
	    
// 	}
// 	//-----------------------------------------------------------------------------------------------
// 	public function MakeCall()
// 	{
	    
// 	}
// 	//-----------------------------------------------------------------------------------------------
// 	public function MakeCall()
// 	{
	    
// 	}
	//-----------------------------------------------------------------------------------------------
	public function GetAllCallsStat($aData){
	    $iNewCalls = 0;
	    $iSuccessfulCalls = 0;
	    $iUnsuccessfulCalls = 0;
	    $iTotalCallsDuration = 0;
	    $iTotalCallsWaitingTime = 0;
	    
	    if($aData) foreach ($aData as $sKey => $aValue) {
	       if($aValue['disposition']=='ANSWER') $iSuccessfulCalls++;
	           else $iUnsuccessfulCalls++;
	       if($aValue['isNewCall'])$iNewCalls++;
	       array_push($aUniqueCalls, $aValue['externalNumber']);
	       $iTotalCallsDuration+=$aValue['billsec'];
	       $iTotalCallsWaitingTime+=$aValue['waitsec']; 
	    }
	    
	    $sStat="Всех звонков за выбранный период: <b>".count($aData)."</b><br>
                Звонили впервые: <b>".$iNewCalls."</b><br><br>
                Успешных звонков: <b>".$iSuccessfulCalls."</b><br>
                Неуспешных звонков: <b>".$iUnsuccessfulCalls."</b><br><br>
                Длительность всех разговоров: <b>".$this->SecondsToTimeInterval($iTotalCallsDuration)."</b><br>
                Среднее время разговора: <b>".$this->SecondsToTimeInterval($iTotalCallsDuration/$iSuccessfulCalls)."</b><br>
                Среднее время ожидания: <b>".$this->SecondsToTimeInterval($iTotalCallsWaitingTime/count($aData))."</b>";
	     
	    return $sStat;
	}
	//-----------------------------------------------------------------------------------------------
    public function GetInputCallsStat($aData){
        $iNewCalls = 0;
        $aUniqueCalls = array();
        $iReceivedCalls = 0;
        $iMissedCalls = 0;
        $iTotalCallsDuration = 0;
        $iWaitingTimeReceivedCalls = 0;
        $iWaitingTimeMissedCalls = 0;
        
        if($aData) foreach ($aData as $sKey => $aValue) {
            if($aValue['disposition']=='ANSWER') {
                $iReceivedCalls++;
                $iWaitingTimeReceivedCalls+=$aValue['waitsec'];
            } else {
                $iMissedCalls++;
                $iWaitingTimeMissedCalls+=$aValue['waitsec'];
            }
            if($aValue['isNewCall'])$iNewCalls++;
            array_push($aUniqueCalls, $aValue['externalNumber']);
            $iTotalCallsDuration+=$aValue['billsec'];
        }
                
        $sStat="Всех звонков за выбранный период: <b>".count($aData)."</b>  из них уникальных: <b>".count(array_unique($aUniqueCalls))."</b><br>
                Звонили впервые: <b>".$iNewCalls." (".(count(array_unique($aUniqueCalls))*100)/$iNewCalls."%)"."</b><br><br>
                Принятых звонков: <b>".$iReceivedCalls." (".(($iReceivedCalls*100)/count($aData))."%)"."</b><br>
                Непринятых звонков: <b>".$iMissedCalls." (".(($iMissedCalls*100)/count($aData))."%)"."</b><br><br>
                Длительность всех разговоров: <b>".$this->SecondsToTimeInterval($iTotalCallsDuration)."</b><br>
                Среднее время разговора: <b>".$this->SecondsToTimeInterval($iTotalCallsDuration/$iReceivedCalls)."</b><br>
                Среднее время ожидания всех звонков: <b>".$this->SecondsToTimeInterval(($iWaitingTimeReceivedCalls+$iWaitingTimeMissedCalls)/count($aData))."</b>, 
                    принятых звонков: <b>".$this->SecondsToTimeInterval($iWaitingTimeReceivedCalls/$iReceivedCalls)."</b>, 
                    непринятых звонков: <b>".$this->SecondsToTimeInterval($iWaitingTimeMissedCalls/$iMissedCalls)."</b>";
         
        return $sStat; 
    }
	//-----------------------------------------------------------------------------------------------
    public function GetOutputCallsStat($aData){
        $aUniqueCalls=array();
        $iSuccessfulCalls=0;
        $iUnsuccessfulCalls=0;
        $iTotalCallsDuration=0;
         
        if($aData) foreach ($aData as $sKey => $aValue) {
            if($aValue['disposition']=='ANSWER') {
                $iSuccessfulCalls++;
                $iWaitingTimeReceivedCalls+=$aValue['waitsec'];
            } else {
                $iUnsuccessfulCalls++;
                $iWaitingTimeMissedCalls+=$aValue['waitsec'];
            }    
            array_push($aUniqueCalls, $aValue['externalNumber']);
            $iTotalCallsDuration+=$aValue['billsec'];
        } 
        
        $sStat="Всех звонков за выбранный период: <b>".count($aData)."</b>  из них уникальных: <b>".count(array_unique($aUniqueCalls))."</b><br>
                  Успешно совершенных звонков: <b>".$iSuccessfulCalls."</b><br>
                  Не удалось дозвониться, вызовов: <b>".$iUnsuccessfulCalls."</b><br>
                  Длительность всех разговоров: <b>".$this->SecondsToTimeInterval($iTotalCallsDuration)."</b>";
        
        return $sStat;
    }
    //-----------------------------------------------------------------------------------------------
    public function GetLostCallsStat($aData){   
        $iNotProcessedFor30Min = 0;
        $iNewCalls = 0;
        
        if($aData) foreach ($aData as $sKey => $aValue){
            if(time() - $aValue['startTime'] >= 1800) $iNotProcessedFor30Min++;
            if($aValue['isNewCall']) $iNewCalls++;
        }

        $sStat="Всего потерянных звонков за выбранный период: <b>".count($aData)."</b>  из них звонили впервые: <b>".$iNewCalls."</b><br>
	            Необработанных в течение 30 минут: <b>".$iNotProcessedFor30Min."</b>";
         
       return $sStat;  
    }
    //-----------------------------------------------------------------------------------------------
    public function GetCallsByManagerStat($aData){
        $aUniqueCalls=array();
        $iSuccessfulCalls = 0;
        $iWaitingTimeReceivedCalls = 0;
        $iIncoming = 0;
        $iIncomingSuccess = 0;
        $iOutgoing = 0;
        $iOutgoingSuccess = 0;
        $iTotalBillsec = 0;
        $iIncomingBillsec = 0;
        $iOutgoingBillsec = 0;
        $iIncomingWaitsec = 0;
        $iOutgoingWaitsec = 0;
        
        if($aData) foreach ($aData as $sKey => $aValue){
            if($aValue['disposition']=='ANSWER') {
                $iSuccessfulCalls++;
                $iWaitingTimeReceivedCalls+=$aValue['waitsec'];
            }
            //if incoming
            if($aValue['callType'] == 0) {
                $iIncoming++;
                $iIncomingBillsec += $aValue['billsec'];
                if($aValue['disposition']=='ANSWER') $iIncomingSuccess++;
                $iIncomingWaitsec += $aValue['waitsec'];
            }
            else {
                $iOutgoing++;
                $iOutgoingBillsec += $aValue['billsec'];
                if($aValue['disposition']=='ANSWER') $iOutgoingSuccess++;
                $iOutgoingWaitsec += $aValue['waitsec'];
            }
            array_push($aUniqueCalls, $aValue['externalNumber']);
            $iTotalBillsec += $aValue['billsec'];
        }
        $sStat="Всех звонков за выбранный период: <b>".count($aData)."</b>  из них уникальных: <b>".count(array_unique($aUniqueCalls))."</b>,<br> 
                    успешно завершенных звонков: <b>".$iSuccessfulCalls."</b><br><br>
                Входящих звонков за выбранный период: <b>".$iIncoming." (".($iIncoming*100)/count($aData)."%)"."</b>, 
                    из них принятых: <b>".$iIncomingSuccess." (".($iIncomingSuccess*100)/$iIncoming."%)"."</b><br>
                Исходящих звонков за выбранный период: <b>".$iOutgoing." (".($iOutgoing*100)/count($aData)."%)"."</b>,<br> 
                    из них успешно завершенных : <b>".$iOutgoingSuccess." (".($iOutgoingSuccess*100)/$iOutgoing."%)"."</b><br><br>
                Длительность всех разговоров: <b>".$this->SecondsToTimeInterval($iTotalBillsec)."</b> 
                    из них входящих: <b>".$this->SecondsToTimeInterval($iIncomingBillsec)." (".($iIncomingBillsec*100)/$iTotalBillsec."%)"."</b>, 
                    исходящих: <b>".$this->SecondsToTimeInterval($iOutgoingBillsec)." (".($iOutgoingBillsec*100)/$iTotalBillsec."%)"."</b><br>
                Среднее время разговора: <b>".$this->SecondsToTimeInterval($iTotalBillsec/$iSuccessfulCalls)."</b><br><br>
                Среднее время ожидания при входящем звонке: <b>".$this->SecondsToTimeInterval($iIncomingWaitsec/$iIncoming)."</b><br>
                Длительность времени ожидания при исходящем звонке: <b>".$this->SecondsToTimeInterval($iOutgoingWaitsec/$iOutgoing)."</b>";
        
        return $sStat;
    }
    //-----------------------------------------------------------------------------------------------
    public function SecondsToTimeInterval($seconds){
        $seconds = ceil($seconds);
        $h = sprintf("%'02d",floor(($seconds/60)/60));
        $m = sprintf("%'02d",floor(($seconds%3600)/60));
        $s = sprintf("%'02d",ceil($seconds%60));
        if($h > 0) return $h.':'.$m.':'.$s;
        else return $m.':'.$s;
    }
    //-----------------------------------------------------------------------------------------------
    function GetDateWeek($number)
    {
        $k = strtotime("last week Monday");
        $Year = date("Y",$k);
        $Month = date("m",$k);
        $Day = date("d",$k);
        if ($Month < 2 && $Day < 8) {
            $Year = $Year--;
            $Month = $Month--;
        }
        if ($Month > 1 && $Day < 8)
            $Month = $Month--;
    
        $Day = $Day-7*$number;
        
        $aData['from'] = date('d-m-Y', mktime(0, 0, 0, $Month, $Day, $Year));
        $aData['to'] = date('d-m-Y', mktime(0, 0, 0, $Month, $Day+6, $Year));
        $aData['caption'] = date('j-M', mktime(0, 0, 0, $Month, $Day, $Year)).' - '.date('j-M', mktime(0, 0, 0, $Month, $Day+6, $Year));
        
        return $aData;
        
    }
    //-----------------------------------------------------------------------------------------------
    public function GetPercentGrowth($before, $after){
        return round((($after - $before)/($before?$before:$after))*100);
    }
    //-----------------------------------------------------------------------------------------------
}
?>