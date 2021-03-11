<?
/**
 * @author Mikhail Strovoyt
 */

class Content extends Base
{
	private $aDropdownMenu=array();
	private $aAccountMenu=array();
	public $aCrumbs=array();
	
	
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		Base::$tpl->assign_by_ref('oContent',$this);
		$oCurrency=new Currency();
		Base::$tpl->assign_by_ref('oCurrency', $oCurrency);
		Base::$oCurrency=$oCurrency;
		
	}
	//-----------------------------------------------------------------------------------------------
	public function CreateMainMenu()
	{
		$this->AssignCrumb();
		$this->DropdownGetCustom();
		Base::$tpl->assign('aDropdownMenu',$this->aDropdownMenu);

		$iMenuLeft=Db::GetOne("select id from drop_down where code='menu_left'");
		if ($iMenuLeft) {
			$aMenuLeft=Db::GetAll("select * from drop_down where id_parent=".$iMenuLeft." order by num");
			Base::$tpl->assign('aMenuLeft',$aMenuLeft);
		}

		if (Auth::$aUser['id']) {
			$sCache_name .= 'account_menu_'.Auth::$aUser['type_'];
			if (Base::$aRequest['locale'] && Base::$aRequest['locale']!='ru') {
				$sCache_name .= '_'.Base::$aRequest['locale'];
				$sAdd = '_'.Base::$aRequest['locale'];
			}
				
		    //need refresh after interval
		    $iNowDate=time();
		    $iLastRefresh=Base::GetConstant("cache:account_menu_last_update".$sAdd,time());
		    echo '<!--'.($iLastRefresh+(Base::GetConstant("cache:account_menu_last_update_interval".$sAdd,"60")*60)).' <= '. $iNowDate .'-->';
		    if(($iLastRefresh+(Base::GetConstant("cache:account_menu_last_update_interval".$sAdd,"60")*60)) <= $iNowDate) {
			$aAccount=Db::GetRow("select * from drop_down where code='".Auth::$aUser['type_']."_account'");
    			Base::$tpl->assign('iTopAccountMenu',$aAccount['id']);
    			$this->AccountGetChilds($aAccount['id']);
    			FileCache::SetValue('Home', $sCache_name, $this->aAccountMenu);
		    }
		    else {
			if(!($this->aAccountMenu=FileCache::GetValue('Home', $sCache_name))) {
    			    $aAccount=Db::GetRow("select * from drop_down where code='".Auth::$aUser['type_']."_account'");
    			    Base::$tpl->assign('iTopAccountMenu',$aAccount['id']);
    
    			    $this->AccountGetChilds($aAccount['id']);
    			    FileCache::SetValue('Home', $sCache_name, $this->aAccountMenu);
			}
		    }
		    
		    if ($this->aAccountMenu) {
		    	$aAMenu = $this->aAccountMenu; 
		    	foreach ($aAMenu as $iKey => $aValue) {
		    		if (!$this->CheckActionMenu($aValue['code'],$aValue['name']))
		    			unset($aAMenu[$iKey]);
		    		
		    		$this->aAccountMenu = array_values($aAMenu);
				}
		    }
		    
			Base::$tpl->assign('aAccountMenu',$this->aAccountMenu);
			
			$isAllowManagerProfile=1;
			if (Auth::$aUser['type_']=='manager' && !$this->CheckActionMenu('manager_profile'))
				$isAllowManagerProfile=0;
			
			$isAllowManagerMessages=1;
			if (Auth::$aUser['type_']=='manager' && !$this->CheckActionMenu('messages'))
				$isAllowManagerMessages=0;
			
			$isAllowManagerChangePrice=1;
			if (Auth::$aUser['type_']=='manager' && !$this->CheckActionMenu('user_change_level_price'))
				$isAllowManagerChangePrice=0;
			
			$isAllowManagerNews=1;
			if (Auth::$aUser['type_']=='manager' && !$this->CheckActionMenu('news'))
				$isAllowManagerNews=0;
			
			Base::$tpl->assign('isAllowManagerProfile',$isAllowManagerProfile);
			Base::$tpl->assign('isAllowManagerMessages',$isAllowManagerMessages);
			Base::$tpl->assign('isAllowManagerChangePrice',$isAllowManagerChangePrice);
			Base::$tpl->assign('isAllowManagerNews',$isAllowManagerNews);
		}

		Base::$tpl->assign('bRightSectionVisible',Base::$bRightSectionVisible);

		$aData=array(
		'table'=>'drop_down',
		'where'=>" and t.id_parent='204' and t.visible=1 order by t.num",
		);
		$aHelpMenu=Language::GetLocalizedAll($aData);
		Base::$tpl->assign('aHelpMenu',$aHelpMenu);

		$this->ParseTemplate();
		Base::$oContent->ShowTimer('FullPage');
		Base::$tpl->assign('sTimer',  $this->sTimer);
		
		//add canonical
		$sCanonicalUrl=Base::$tpl->_tpl_vars['sUrlCanonical'];
		if(!$sCanonicalUrl) {
		    switch(Base::$aRequest['action']) {
		        case 'catalog_price_view':
    		        $sUrl="/price/";
    		        if(Base::$aRequest['name']) $sUrl.=Base::$aRequest['name']."_";
    		        $sUrl.=Base::$aRequest['code'];
    		        break;
    		        
		        case 'catalog_part_info_view':
    		        $sUrl="/buy/";
    		        if(Base::$aRequest['cat_name']) $sUrl.=Base::$aRequest['cat_name']."_";
    		        $sUrl.=Base::$aRequest['code'];
    		        break;
    		        
		        case 'catalog_model_for':
    		        $sUrl="/model_for/";
    		        if(Base::$aRequest['data']['cat']) $sUrl.=Base::$aRequest['data']['cat']."_";
    		        $sUrl.=Base::$aRequest['data']['code'];
    		        break;
    		        
		        case 'catalog_model_view':
		            $sUrl="/cars/";
		            $sUrl.=Base::$aRequest['cat'];
		            break;
		            
		        case 'catalog_model_group_view':
		            $sUrl="/cars/";
		            $sUrl.=Base::$aRequest['cat']."/";
		            $sUrl.=Base::$aRequest['model_group'];
		            break;
    		        
		        case 'news_preview':
		            $sUrl="/pages/news/".Base::$aRequest['id'];
		            break;
		            
		        default:
		            $sUrl="/pages/".Base::$aRequest['action'];
		            break;
		    }
		
		    Base::$tpl->assign('sUrlCanonical',"http://".$_SERVER['HTTP_HOST'].str_replace("//", "/", $sUrl."/"));
		}
	}
	//-----------------------------------------------------------------------------------------------
	public  function DropdownGetCustom(){
		//need update
		$aData=array(
				'table'=>'drop_down',
				'where'=>" and t.id_parent=0 and t.visible=1 and t.is_menu_visible=1 order by t.num",
		);
		$aDropdown=Base::$language->GetLocalizedAll($aData);
		foreach ($aDropdown as $sKey => $aValue){
			$aData=array(
					'table'=>'drop_down',
					'where'=>" and t.id_parent='".$aValue['id']."' and t.visible=1 order by t.num",
			);
			$aDropdown[$sKey]['childs']=Base::$language->GetLocalizedAll($aData);
			if ($aDropdown[$sKey]['childs']) $aDropdown[$sKey]['childs_count']=count($aDropdown[$sKey]['childs']);
			else $aDropdown[$sKey]['childs_count']=0;
		}
		
		// check permissions
		foreach ($aDropdown as $iKey => $aValue) {
			if(!$this->CheckActionMenu($aValue['code'],$aValue['name']))
				unset($aDropdown[$iKey]);
		}
		$aDropdown = array_values($aDropdown);
		
		$this->aDropdownMenu=$aDropdown;
	}
	//-----------------------------------------------------------------------------------------------
	function DropdownGetChilds($iIdParent)
	{
		$aData=array(
		'table'=>'drop_down',
		'where'=>" and t.id_parent='$iIdParent' and t.visible=1 order by t.num",
		);
		$aDropdown=Base::$language->GetLocalizedAll($aData);

		if ($aDropdown) foreach ($aDropdown as $aValue) {
			$aValue['level_']=$aValue['level']+1;
			$this->aDropdownMenu[]=$aValue;
			if ($aValue['level']<=1) $this->DropdownGetChilds($aValue['id']);
		}
	}
	//-----------------------------------------------------------------------------------------------
	function AccountGetChilds($iIdParent)
	{
		$aData=array(
		'table'=>'drop_down',
		'where'=>" and id_parent='$iIdParent' and is_menu_visible=1 and visible=1 order by num",
		);
		$aDropdown=Base::$language->getLocalizedAll($aData);

		if ($aDropdown) foreach ($aDropdown as $aValue) {
			$aValue['level_']=$aValue['level']-1;
			$this->aAccountMenu[]=$aValue;
			if ($aValue['level']<=3) $this->AccountGetChilds($aValue['id']);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function AddCrumb($sName,$sLink='')
	{
		$isNotChangeUrl = 0;
		if (strpos(Base::$aRequest['action'],'levam_')!==false)
			$isNotChangeUrl = 1;
		
		if (!$isNotChangeUrl && Language::getConstant('global:url_is_lower',0) == 1)
			$sLink = mb_strtolower($sLink,'utf-8');
		
		if (Language::getConstant('global:url_is_not_last_slash',0) == 1) {
			if ($sLink != "/" && mb_substr($sLink, -1, 1, 'utf-8') == "/")
				$sLink = substr($sLink, 0, -1);
		}
		else {
			if($sLink!='' && strpos($sLink,'/?')===FALSE && mb_substr($sLink, -1, 1, 'utf-8') != "/")
				$sLink .= "/";
		}
		
		$this->aCrumbs[]=array('name'=>$sName,'link'=>$sLink);
	}
	//-----------------------------------------------------------------------------------------------
	private function AssignCrumb()
	{
		if(count($this->aCrumbs)<=1) {
		    Base::$oContent->AddCrumb('');
		}
		Base::$tpl->assign('aCrumbs',$this->aCrumbs);
	}
	//-----------------------------------------------------------------------------------------------
	public function DelAllCrumbs() {
		$this->aCrumbs = array();
		Base::$oContent->AddCrumb(Language::GetMessage('main page'),'/');
	}
	//-----------------------------------------------------------------------------------------------
	public function ParseTemplate($bRefreshCartAjax=false)
	{
		if (Auth::$aUser['type_']=='manager' || Auth::$aUser['type_']=='customer') {
				$aTemplateNumber['cart_number']=Db::GetOne("select count(*) from cart c
				    inner join user u on u.id = c.id_provider
				    inner join user_provider up on up.id_user = u.id
				    where c.type_='cart' and c.id_user=".Auth::$aUser['id_user']);
				// correct round
				$aMass = Db::GetAll("select c.number,c.price 
				        from cart c 
				        inner join user u on u.id = c.id_provider
				        inner join user_provider up on up.id_user = u.id
				        where c.type_='cart' and c.is_archive=0 and c.id_user=".Auth::$aUser['id_user']);
				foreach ($aMass as $aValue) 
					$aTemplateNumber['cart_total'] += $aValue['number'] * Base::$oCurrency->PrintPrice($aValue['price'],null,2,"<none>");

			if (!$bRefreshCartAjax){
				$aTemplateNumber['cart_package_number']=Db::GetOne("select count(*) from cart_package where is_archive=0
					".Auth::$sWhere);
				$aTemplateNumber['order_number']=Db::GetOne("select count(*) from cart where type_='order'
				    ".Auth::$sWhere);
				$aTemplateNumber['payment_report_id']=Db::GetOne("select count(*) from payment_report where is_read='0' or is_read is null");
				$aTemplateNumber['message_number']=Db::GetOne("select count(*) from message where (is_read='0' or is_read is null)
					and id_message_folder=1 and is_old='0' ".Auth::$sWhere);
				$aTemplateNumber['resolved']=Db::GetOne("select count(*) from call_me where resolved='0'");
			}
		}
		else $aTemplateNumber['cart_number']=0;
	
		if (Auth::$aUser['type_']=='customer') {
		    $aTemplateNumber['payment_declaration_id']=Db::GetOne("select count(*) from payment_declaration 
		        where (is_read='0' or is_read is null) and id_user='".Auth::$aUser['id']."'");
		}
		else $aTemplateNumber['payment_declaration_id']=0;

		if ($bRefreshCartAjax) {
			Base::$oResponse->AddAssign('icart_id','innerHTML',$aTemplateNumber['cart_number']);
			if ($aTemplateNumber['cart_number']>0){
			   Base::$oResponse->addScript("$('#icart_id').removeClass('empty').html;");
			}else{
			    Base::$oResponse->addScript("$('#icart_id').removeClass('empty').html;");
			}
			Base::$oResponse->AddAssign('icart_total_id','innerHTML'
			,Base::$oCurrency->PrintSymbol($aTemplateNumber['cart_total']));
			Base::$oResponse->AddAssign('icart_info','innerHTML',
			'('.$aTemplateNumber['cart_number'].') ' . Base::$oCurrency->PrintPrice($aTemplateNumber['cart_total'],1,0,'line'));
			if ($aTemplateNumber['cart_number'] > 0)
				Base::$oResponse->AddAssign('icart_make_id','innerHTML',
				'<a href="/?action=cart_cart" class="ordering-link">'.Language::GetMessage('make an order')."</a>");
		}
		else {
			$aTemplateNumber['message_number']=Db::GetOne("select count(*) from message where is_read=0
					and id_message_folder=1 and is_old='0' ".Auth::$sWhere);
			Base::$tpl->assign('aTemplateNumber',$aTemplateNumber);
		}
		
		if (Auth::$aUser['type_']=='manager' ) {
		    if(Auth::$aUser['is_super_manager'])
			     $iNotViewedOrders=Db::GetOne("select count(*) from cart_package where is_viewed=0");
		    else 
		        $iNotViewedOrders=Db::GetOne("select  count(*) from cart_package  join user_customer on user_customer.id_user=cart_package.id_user and user_customer.id_manager=".Auth::$aUser['id']." where cart_package.is_viewed=0 ");
			Base::$tpl->assign('iNotViewedOrders',$iNotViewedOrders);
			$iNotViewedVins=Db::GetOne("select count(*) from vin_request vr
			inner join user u on vr.id_user=u.id
			inner join user_customer uc on uc.id_user=u.id
			inner join customer_group cg on uc.id_customer_group=cg.id
			inner join user m on uc.id_manager=m.id
			where is_viewed=0");
			Base::$tpl->assign('iNotViewedVins',$iNotViewedVins);
		}
///---		
		if (Auth::$aUser['type_']=='customer' ) {
		  $aTemplateNumber['message_number']=Db::GetOne("select count(*) from message where is_read=0
					and id_message_folder=1 and is_old='0' ".Auth::$sWhere);
			Base::$tpl->assign('aTemplateNumber',$aTemplateNumber);
		}
///---		
	}
	//-----------------------------------------------------------------------------------------------
	public static function Init()
	{
	    // redirect
        $sUri=$_SERVER['REQUEST_URI'];

        $sRedirectLinkTo=Db::GetOne("select link_to from redirect where link_from ='".$sUri."'");

        if ($sRedirectLinkTo && ($sRedirectLinkTo!=$sUri)){
            Base::Redirect(Language::GetConstant('global:project_url').$sRedirectLinkTo);
        }
	    //end redirect


		Base::$oContent->CheckAccessManager();
		
		Base::$oContent->ClearTimer();

		// if web server convert url to small
		Base::$oContent->CheckMessageUrl();

		Base::Message();
		
		Base::$oContent->AddCrumb(Language::GetMessage('main page'),'/');
		
		if(Base::$aRequest['clear_auto']) {
		    setcookie("saved_auto",null,null,'/');
		    setcookie("cookie_id_model_detail",null,null,'/');
		    unset($_COOKIE['cookie_id_model_detail']);
		    unset($_SESSION['auto_selected']);
		    unset($_COOKIE['saved_auto']);
		
		
		    setcookie("id_make",null,null,'/');
		    setcookie("id_model",null,null,'/');
		    setcookie("id_model_detail",null,null,'/');
		
		    unset($_COOKIE['id_make']);
		    unset($_COOKIE['id_model']);
		    unset($_COOKIE['id_model_detail']);
		}
		
		mb_internal_encoding("UTF-8");
		$aRewriteAssoc=Db::GetAssoc("select static_rewrite,url from drop_down_additional where visible=1");
		if ($aRewriteAssoc) {
			$aRewriteKeys=array_keys($aRewriteAssoc);
			Content::RedirectOnSlash();
			if(strpos($_SERVER['REQUEST_URI'],'/?')!==FALSE && count(Base::$aRequest)==1 && Base::$aRequest['action'] 
					&& !in_array(Base::$aRequest['action'], array('home')) 
			){
				Base::Redirect('/pages/'.Base::$aRequest['action']);
			}
			if(strpos($_SERVER['REQUEST_URI'],'/pages/')!==FALSE && count(Base::$aRequest)==1 && Base::$aRequest['action']=='home'
			){
				Base::Redirect('/');
			}
			if(strpos($_SERVER['REQUEST_URI'],'/pages/')!==FALSE && in_array(Base::$aRequest['action'], $aRewriteKeys) ){
				$sRewriteURL=$aRewriteAssoc[Base::$aRequest['action']];
				$re1='.*?';	# Non-greedy match on filler
				$re2='(action)';	# Word 1
				$re3='(=)';	# Any Single Character 1
				$re4='((?:[a-z][a-z0-9_]*))';	# Variable Name 1
				if ($c=preg_match_all ("/".$re1.$re2.$re3.$re4."/is", $sRewriteURL, $matches)){
				    $sRewriteAction=$matches[3][0];
				}
				Base::$aRequest['action']=$sRewriteAction;
			}
		}
		$sSEOText=Db::GetOne("select description from drop_down_additional where static_rewrite='".Base::$aRequest['action']."' OR url LIKE  '%".Base::$aRequest['action']."%'");
		if ($sSEOText && $sSEOText!="<p>&nbsp;</p>") Base::$tpl->Assign('sSEOText',$sSEOText);
		
		Base::$tpl->assign('bNoneDotUrl',1);
		
		Repository::InitDatabase('news');
		
		// add crumb & caption if page
		Content::AddCrumbAndCaption();
		
		$sFavicon = Language::getConstant('favicon','/favicon.ico'); 
		if ($sFavicon != '/favicon.ico') {
			$aFileInfo = @getimagesize('.'.$sFavicon); 
			if ($aFileInfo['mime'] != '')
				Base::$tpl->Assign('sFaviconType',$aFileInfo['mime']);
		}

		Base::$sZirHtml="<i>*</i>";
		Base::$tpl->assign('sZir', Base::$sZirHtml);
		Form::$sTitleDivHeader=" class='form_title_div'";

		$oCatalog=new Catalog();
		Base::$tpl->assign('aCurrencyAssoc',Db::GetAssoc('Assoc/Currency'));
		Base::$aData['template']['bWidthLimit']=false;

		if(!Base::$aRequest['xajax'] && strpos($_SERVER['REQUEST_URI'],'mpanel')===FALSE ) {
		    PriceGroup::GetTabs();
		}
		Table::$sStepperAlign='left';
		
		if (Auth::$aUser['type_'] == manager) {
			// get price group user
			$aCustomerGroup = Db::GetAssoc("Select id, name from customer_group where visible=1 order by name");
			Base::$tpl->assign('aCustomerGroup',$aCustomerGroup);
			
		 	$sWhereManager .= " and uc.id_user = '".Auth::$aUser['id_type_price_user']."'";
			Base::$tpl->assign('aNameManager',$aNames = Db::GetAssoc("select id as id, 
			concat(uc.name,' ( ',u.login,' )', 
			IF(uc.phone is null or uc.phone='','',concat(' ".
		    Language::getMessage('tel.')." ',uc.phone))) name
			from user as u
			inner join user_customer as uc on u.id=uc.id_user
			where u.visible=1 and uc.name is not null and trim(uc.name)!=''
			".$sWhereManager."
			order by uc.name"));
			// all users
			if (!$aNames)
				Base::$tpl->assign('aNameManager',$aNames = Db::GetAssoc("select id as id,
				concat(uc.name,' ( ',u.login,' )',
				IF(uc.phone is null or uc.phone='','',concat(' ".
								Language::getMessage('tel.')." ',uc.phone))) name
				from user as u
				inner join user_customer as uc on u.id=uc.id_user
				where u.visible=1 and uc.name is not null order by uc.name"));
				
			Base::$tpl->assign('sURI',$_SERVER['REDIRECT_URL']);
		}		
		
		Content::ParseTemplate();
		
		
		
	    Resource::Get()->Add('/css/main.css',25);
	    Resource::Get()->Add('/css/context_hint.css',1);
	    
	    if(Base::GetConstant('style_colored','0')=='1') {
	        Resource::Get()->Add('/css/main_colored.css',time());
	    }
	    
	    Resource::Get()->Add('/js/jquery-1.8.2.min.js');
	    Resource::Get()->Add('/js/jquery-ui.min.js');
	    Resource::Get()->Add('/js/jquery.ui.touch-punch.min.js');
	    Resource::Get()->Add('/js/os-detection.js');
	    Resource::Get()->Add('/js/jquery.colorbox.js');
	    Resource::Get()->Add('/js/jquery.uniform.min.js');
	    Resource::Get()->Add('/js/slick.min.js');
	    Resource::Get()->Add('/js/html5shiv.js');
	    Resource::Get()->Add('/js/jquery.matchheight-min.js');
	    Resource::Get()->Add('/js/jquery.maskedinput.js');
	    Resource::Get()->Add('/js/jquery.select.js');
	    Resource::Get()->Add('/js/main.js',4);
	    Resource::Get()->Add('/js/functions.js',7);
	    Resource::Get()->Add('/js/check_phone.js');
        Resource::Get()->Add('/js/masonry.min.js');
		
	    
	        Resource::Get()->Add('/js/jquery.searchabledropdown-1.0.8.min.js',1);
	        Resource::Get()->Add('/js/select2.min.js',2);
	        Resource::Get()->Add('/css/select2.css',2);
	   if (Auth::$aUser['type_'] == 'manager'){
	        if (Language::getConstant('use_price_control',0))
	            Resource::Get()->Add('/js/price_control.js',1);
	    }
	    
	    Resource::Get()->Add('/css/datepick.css');
	    if (Language::$sLocale=='ru')
		    Resource::Get()->Add('/js/popcalendar/popcalendar_ru.js');
		else
			Resource::Get()->Add('/js/popcalendar/popcalendar.js');
		Resource::Get()->Add('/libp/jquery/jquery.datepick.js');
		

// 		Resource::Get()->Add('/css/main.css',30);
// 		Resource::Get()->Add('/css/ie7.css',0,'header',array('ie7'=>1));
// 		Resource::Get()->Add('/css/context_hint.css');
// 		Resource::Get()->Add('/css/jquery-ui.css',1);
// 		Resource::Get()->Add('/css/new.css',1);
		
// 		Resource::Get()->Add('/js/jquery-1.9.1.js',1);
// 		Resource::Get()->Add('/js/jquery.jcarousellite.js',1);
// 		Resource::Get()->Add('/js/jquery-ui.js',1);
// 		Resource::Get()->Add('/js/main.js',4);
// 		Resource::Get()->Add('/js/jquery.maskedinput.min.js',1);
// 		Resource::Get()->Add('/js/call_me.js',3);
// 		Resource::Get()->Add('/css/call_me.css',1);
// 		Resource::Get()->Add('/js/functions.js',11);
// 		Resource::Get()->Add('/js/jquery.validate.min.js',1);
// 		Resource::Get()->Add('/js/myscripts.js');
// 		Resource::Get()->Add('/js/cart.js',6);
// 		Resource::Get()->Add('/js/jquery.datepicker.extension.range.min.js',1);
		
// 		Resource::Get()->Add('/css/responsive.css',2);
// 		Resource::Get()->Add('/js/responsive.js',2);

// 		Resource::Get()->Add('/js/jquery.colorbox-min.js',1);
// 		Resource::Get()->Add('/css/colorbox.css',1);
// 		Resource::Get()->Add('/js/slick.min.js',1);
// 		Resource::Get()->Add('/css/slick.css',1);
// 		Resource::Get()->Add('/js/jquery.searchabledropdown-1.0.8.min.js',1);

// 		if (Auth::$aUser) {
// 			Resource::Get()->Add('/css/datepick.css');
// 			if (Language::$sLocale=='ru') 
// 			    Resource::Get()->Add('/libp/popcalendar/popcalendar_ru.js');
// 			else
// 			    Resource::Get()->Add('/libp/popcalendar/popcalendar.js');
// 			Resource::Get()->Add('/libp/jquery/jquery.datepick.js');
// 			if (Auth::$aUser['type_'] == 'manager'){ 
// 				//Resource::Get()->Add('/js/jquery.searchabledropdown-1.0.8.min.js',1);
// 				Resource::Get()->Add('/js/select2.min.js',1);
// 				Resource::Get()->Add('/js/i18n/ru.js',1);
// 				Resource::Get()->Add('/css/select2.min.css');
// 			}
// 		}
		$sUloginURI=urlencode(Base::GetConstant('global:project_url','http://irbis.mstarproject.com')."/?action=user_ulogin_login");
		Base::$tpl->assign('sUloginURI',$sUloginURI);
		
		
		//for sort table
		$aSortArray=array(
		    'price' => array('sort' =>'price','way' =>'up','name' =>'sort_by_price'),
		    'make' => array('sort' =>'make','way' =>'up','name' =>'sort_by_make'),
		    'code' => array('sort' =>'code','way' =>'up','name' =>'sort_by_code'),
		    'name' => array('sort' =>'name','way' =>'up','name' =>'sort_by_name'),
		    'term' => array('sort' =>'term','way' =>'up','name' =>'sort_by_term'),
		    'stock' => array('sort' =>'stock','way' =>'down','name' =>'sort_by_stock'),
		);
		if(Base::$aRequest['all_params']!=''){
		    $aSortArrayNew=array();
		    foreach ($aSortArray as $iky=>$aItemSortArray){
		        $iQstPos=strpos(Base::$aRequest['all_params'], $aItemSortArray['sort']);
		        if($iQstPos!==false) {
		            $aSortArrayNew[]=$aItemSortArray;
		            unset($aSortArray[$iky]);
		        }
		    }
		    $aSortArray=array_merge($aSortArrayNew,$aSortArray);
		}
		//Debug::PrintPre($aSortArray,false);
		Base::$tpl->assign('aSortArray',$aSortArray);
		MultiLanguage::SetAlternateUrl();
	}
	//-----------------------------------------------------------------------------------------------
	public function IsChangeableLogin($sLogin)
	{
		//return Customer::IsChangeableLogin($sLogin);
		return Customer::IsTempUser($sLogin);
	}
	//-----------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------
	public static function CallOldReplacer($sObject,$sOutput)
	{
		$aId=Language::$aOldParser[$sObject];

		switch ($sObject) {

			//================================================
			case 'customer':
				$aCustomer=Db::GetAll(Base::GetSql('Customer',array(
				'join_delivery_type'=> 1,
				'join_rating'=> 1,
				'join_rating_quality'=>1,
				'where'=>" and u.id in (".implode(',',$aId).")",
				)));
				$aExist='';
				if ($aCustomer) {
					foreach ($aCustomer as $aValue) $aCustomerId[]=$aValue['id_user'];
					$aCommentHash=Comment::GetCommentHash('customer',$aCustomerId);

					foreach ($aCustomer as $aValue) {
						$aValue['comment_list']=$aCommentHash[$aValue['id']];
						Base::$tpl->assign('aData',$aValue);
						$sOutput=str_replace('old_parser_'.$sObject.'_'.$aValue['id'].'_'
						,Base::$tpl->fetch('hint/customer.tpl'),$sOutput);
						$aExist[$aValue['id']] = 1;
					}
					foreach($aId as $sId) {
					    if (!$aExist[$sId])
							$sOutput=str_replace('old_parser_'.$sObject.'_'.$sId.'_','',$sOutput);
					}
				}
				break;
				//================================================
				case 'customer_uniq':
					foreach ($aId as $sValueId) {
						list($iRealId,$iUniqId)=explode("_",$sValueId);
						$aCustomer=Db::GetAll(Base::GetSql('Customer',array(
						'join_delivery_type'=> 1,
						'join_rating'=> 1,
						'join_rating_quality'=>1,
						'where'=>" and u.id = ".$iRealId
						)));
						if (!$aCustomer) 
							$aCustomer=Db::GetAll(Base::GetSql('CustomerNotManager',array(
									'join_delivery_type'=> 1,
									'join_rating'=> 1,
									'join_rating_quality'=>1,
									'where'=>" and u.id = ".$iRealId
							)));
						if ($aCustomer) {
							foreach ($aCustomer as $aValue) $aCustomerId[]=$aValue['id_user'];
							$aCommentHash=Comment::GetCommentHash('customer',$aCustomerId);
					
							foreach ($aCustomer as $aValue) {
								$aValue['comment_list']=$aCommentHash[$aValue['id']];
								$aValue['id'] = $iUniqId;
								$aValue['login_strip'] = str_replace(array('(',')'),"",$aValue['login']);
								Base::$tpl->assign('aData',$aValue);
								$sOutput=str_replace('old_parser_'.$sObject.'_'.$sValueId.'_'
										,Base::$tpl->fetch('hint/customer.tpl'),$sOutput);
							}
						}
						else {
							$sOutput=str_replace('old_parser_'.$sObject.'_'.$sValueId.'_'
									,$iRealId,$sOutput);
						}
					}
					break;
					//================================================
					case 'provider_uniq':
					    foreach ($aId as $sValueId) {
					        list($iRealId,$iUniqId)=explode("_",$sValueId);
					        $aProvider=Db::GetAll(Base::GetSql('Provider',array(
					            'where'=>" and u.id = ".$iRealId
					        )));
					        if ($aProvider) {
					            //Debug::PrintPre($aProvider);
					            foreach ($aProvider as $aValue) $aCustomerId[]=$aValue['id_user'];
					            $aCommentHash=Comment::GetCommentHash('provider',$aCustomerId);
					            	
					            foreach ($aProvider as $aValue) {
					                $aValue['comment_list']=$aCommentHash[$aValue['id']];
					                $aValue['id'] = $iUniqId;
					                $aValue['login_translit'] = Content::Translit($aValue['login']);
					                Base::$tpl->assign('aData',$aValue);
					                $sOutput=str_replace('old_parser_'.$sObject.'_'.$sValueId.'_'
					                    ,Base::$tpl->fetch('hint/provider.tpl'),$sOutput);
					            }
					        }
					        else {
					            $sOutput=str_replace('old_parser_'.$sObject.'_'.$sValueId.'_'
					                ,$iRealId,$sOutput);
					        }
					    }
					    break;
				//================================================
			case 'comment_customer_popup':
			case 'comment_cart_package_popup':
			case 'comment_cart_popup':
			case 'comment_provider_popup':
				if ($sObject=='comment_customer_popup') {
					$sSection='customer';
					$aRatingList=Db::GetAll(Base::GetSql('Rating',array(
					'section'=> 'user_quality',
					'order'=>'order by r.num'
					)));
				} elseif ($sObject=='comment_cart_package_popup') $sSection='cart_package';
				elseif ($sObject=='comment_cart_popup') $sSection='cart';
				elseif ($sObject=='comment_provider_popup') $sSection='provider';

				$aCommentHash=Comment::GetCommentHash($sSection,$aId);

				if($sObject=='comment_cart_popup'){
					$aCartSign=Db::GetAll(Base::GetSql('Cart',array(
					'id_array'=>$aId,
					'join_cart_delay'=>true)));
					$aCartSign=Language::Array2Hash($aCartSign,'id');
				}
				foreach ($aId as $iRefId) {
					Base::$tpl->assign('aComment',$aCommentHash[$iRefId]);
					Base::$tpl->assign('aRatingList',$aRatingList);
					if($sObject=='comment_cart_popup'){
						Base::$tpl->assign('aCartSign',$aCartSign[$iRefId]);
					}
					if ($sObject=='comment_customer_popup'){
						//set current rating value from last comment OR 3 BY DEFAULT
						$iNumRating=$aCommentHash[$iRefId][0]['num_rating']!=null?
						$aCommentHash[$iRefId][0]['num_rating']:
						Base::GetConstant('rating:user_default_quality',3);
						Base::$tpl->assign('iRatingNumCurrent',$iNumRating);
						Base::$tpl->assign('bCustomerPopup',true);
					}else
					Base::$tpl->assign('bCustomerPopup',false);
					Base::$tpl->assign('aData',$aCommentHash[$iRefId][0]);
					Base::$tpl->assign('sSection',$sSection);
					Base::$tpl->assign('iRefId',$iRefId);
					$sOutput=str_replace('old_parser_'.$sObject.'_'.$iRefId.'_',Base::$tpl->fetch('hint/comment.tpl'),$sOutput);
				}

				foreach ($aId as $aValue) {
					$sOutput=str_replace('old_parser_'.$sObject.'_'.$aValue,'',$sOutput);
				}
				break;
				//================================================
			case 'cart_store_end':
			case 'cart_work':
				if ($sObject=='cart_store_end') {
					$sWhere.=" and c.order_status in ('store','end','office_sent','office_received')
						and id_invoice_customer='0' and id_travel_sheet='0'
					";
				}
				elseif ($sObject=='cart_work') {
					$sWhere.=" and c.order_status in ('new','work','confirmed','road')";
				}
				else $sWhere.=" 1!=1";

				$aCart=Db::GetAll(Base::GetSql('Part/Search',array(
				'where'=>" and c.id_user in (".implode(',',$aId).") ".$sWhere,
				'order'=>" order by c.id",
				)));
				if ($aCart) foreach ($aCart as $aValue) $aCartHash[$aValue['id_user']][]=$aValue;

				foreach ($aId as $iRefId) {
					Base::$tpl->assign('aCart',$aCartHash[$iRefId]);
					Base::$tpl->assign('aData',$aCartHash[$iRefId][0]);
					Base::$tpl->assign('sSection',$sObject);
					Base::$tpl->assign('iRefId',$iRefId);

					$sOutput=str_replace('old_parser_'.$sObject.'_'.$iRefId.'_',Base::$tpl->fetch('hint/cart.tpl'),$sOutput);
				}

				foreach ($aId as $aValue) {
					$sOutput=str_replace('old_parser_'.$sObject.'_'.$aValue,'',$sOutput);
				}
				break;
				//================================================
			case 'provider':
				$aProvider=Db::GetAll(Base::GetSql('Provider',array(
				'join_provider_group'=>'1',
				'join_provider_region'=>'1',
				'where'=>" and u.id in (".implode(',',$aId).")",
				)));
				if ($aProvider) {
					foreach ($aProvider as $aValue) $aProviderId[]=$aValue['id'];
					$aCommentHash=Comment::GetCommentHash('provider',$aProviderId);

					foreach ($aProvider as $aValue) {
						$aValue['comment_list']=$aCommentHash[$aValue['id']];
						Base::$tpl->assign('aData',$aValue);
						$sOutput=str_replace('old_parser_'.$sObject.'_'.$aValue['id'].'_'
						,Base::$tpl->fetch('hint/provider_details.tpl'),$sOutput);
					}
				}
				break;
				//================================================

		}

		return $sOutput;
	}
	//-----------------------------------------------------------------------------------------------
	public function FirstNwords($sString, $iWord)
	{
		return String::FirstNwords($sString, $iWord);
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetOrderStatus($sKey)
	{
		require(SERVER_PATH.'/include/order_status_config.php');

		switch ($sKey) {
			case 'pending' :
			case 'refused' :
			case 'store_refused' :
			case 'end' :
			case 'store' :
			case 'road' :
			case 'confirmed' :
			case 'chk_customer' :
			case 'work' :
			case 'new' :
				return '<b><font color='.$aOrderStatusColor[$sKey].'>'.Language::getMessage($sKey).'</font></b>';
				break;

			case 'parsed' :
				return '<b><font color=blue>' . Language::getMessage ( $sKey ) . '</font></b>';
				break;

			default :
				return '<b>' . Language::getMessage ( $sKey ) . '</b>';
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function PrintPartName($aRow)
	{
		return Catalog::PrintPartName($aRow);
	}
	//-----------------------------------------------------------------------------------------------
	public function CorrectSeoUrl($sText,$sType)
	{
		switch ($sType) {
			case 'price_group':
				$sText=preg_replace(
				"/\?action=price_group&category=([a-zA-Z0-9_-\s.]+)(&brand=0)?&step=0/",
				'select/${1}/',
				$sText);
				$sText=preg_replace(
				"/\?action=price_group&category=([a-zA-Z0-9_-\s.]+)(&brand=0)?&step=(\d+)/",
				'select/${1}/0/${3}/',
				$sText);
				break;

			case 'price_group_brand':
				$sText=preg_replace(
				"/\?action=price_group&category=([a-zA-Z0-9_-\s.]+)&brand=([a-zA-Z0-9_-\s.]+)&step=/",
				'select/${1}/${2}/',
				$sText);
				$sText=preg_replace(
				"/\?action=price_group&category=([a-zA-Z0-9_-\s.]+)&brand=([a-zA-Z0-9_-\s.]+)&step=(\d+)/",
				'select/${1}/${2}/${3}/',
				$sText);
				break;
				
				

		}
		return $sText;
	}
	//-----------------------------------------------------------------------------------------------
	public function LoadBanners() {
	    $aBanner=Db::GetAll("select * from banner where visible=1");
	    
	    /* Необходимо на сервере установить  yum install libwebp-tools */
	    /*$converter = array(
	        '('=>'\(',
	        ')'=>'\)',
	        ' '=>'\ '
	    );
	    
	    if(strpos($_SERVER['HTTP_ACCEPT'], 'image/webp')!==false && $aBanner) {
	        foreach ($aBanner as $sKey => $aValue) {
	            $sImage=$aValue['image'];
	            $sWebpImage=str_replace(array('jpg','jpeg','png'), 'webp', $aValue['image']);

	            if(!file_exists(SERVER_PATH.$sWebpImage)) {
	                exec("cwebp ".SERVER_PATH.strtr($sImage, $converter)." -q 80 -o ".SERVER_PATH.strtr($sWebpImage, $converter));
	            }
	            $aBanner[$sKey]['image']=$sWebpImage;
	        }
	    }*/
	
	    if ($aBanner){
	        Base::$tpl->assign('aBanner',$aBanner);
	        Base::$tpl->assign('iBannerCount', count($aBanner));
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function Translit($str) 
	{
	    $tr = array(
	        "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
	        "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
	        "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
	        "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
	        "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
	        "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
	        "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
	        "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
	        "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
	        "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
	        "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
	        "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
	        "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
			chr(194)=>"",chr(160)=>"","/"=>"_"," "=>"_","-"=>"_","["=>"","]"=>"","&"=>"and","+"=>"plus",
	    	"("=>"",")"=>"","'"=>"","?"=>"","´"=>"","`"=>"",
			"À"=>"A","Á"=>"A","Â"=>"A","Ã"=>"A","Ä"=>"A","à"=>"a","á"=>"a","â"=>"a","ã"=>"a","ä"=>"a","Ą"=>"A","Ä"=>"A",
	    	"Ё"=>"E","Ë"=>"E","ё"=>"e","É"=>"E","È"=>"E","Ê"=>"E","è"=>"e","é"=>"e","ê"=>"e","ę"=>"e","Ě"=>"E","Ę" => "E","ě"=>"e",
	    	"Њ"=>"H",
    		"ł"=>'l',"Ł"=>"L","Ĺ"=>"L","ľ"=>"l",
    		"ŗ"=>"r","Ŗ"=>"R",
    		"ś" => "s","Ś" => "S",	    		 
	    	"Ӧ"=>"O","Ò"=>"O","Ó"=>"O","Ô"=>"O","Õ"=>"O","Ö"=>"O","ò"=>"o","ó"=>"o","ô"=>"o","õ"=>"o","ö"=>"o",
			"Ù"=>"U","Ú"=>"U","Û"=>"U","Ü"=>"U","ù"=>"u","ú"=>"u","û"=>"u","ü"=>"u",
	    	"Ż"=>"Z","Џ" => "DZ","џ"=>"dz",
	    	"Ї"=>"YI","ї"=>"yi",
	    	"Ѓ"=>"G","Ґ"=>"G","ґ"=>"g","Є"=>"YE","є"=>"ye","Ќ"=>"K","ќ" => "k","Ў" => "U","ў"=>"u"
	    );
	    return strtr($str,$tr);
	}
	//-----------------------------------------------------------------------------------------------
	public function TranslitRelaceBrand($str)
	{
		$tr = array(
			"À"=>"A","Á"=>"A","Â"=>"A","Ã"=>"A","Ä"=>"A","à"=>"a","á"=>"a","â"=>"a","ã"=>"a","ä"=>"a","Ą"=>"A",
	    	"Ё"=>"E","Ë"=>"E","ё"=>"e","É"=>"E","È"=>"E","Ê"=>"E","è"=>"e","é"=>"e","ê"=>"e","ę"=>"e","Ě"=>"E","Ę" => "E","ě"=>"e",
			"Њ"=>"H",
			"ł"=>'l',"Ł"=>"L","Ĺ"=>"L","ľ"=>"l","Ќ"=>"K","ќ" => "k","Ў" => "U","ў"=>"u","Џ" => "DZ","џ"=>"dz",
    		"ŗ"=>"r","Ŗ"=>"R",
    		"ś" => "s","Ś" => "S",	    		 
	    	"Ӧ"=>"O","Ò"=>"O","Ó"=>"O","Ô"=>"O","Õ"=>"O","Ö"=>"O","ò"=>"o","ó"=>"o","ô"=>"o","õ"=>"o","ö"=>"o",
			"Ù"=>"U","Ú"=>"U","Û"=>"U","Ü"=>"U","ù"=>"u","ú"=>"u","û"=>"u","ü"=>"u",
	    	"Ż"=>"Z",
	    	"Ї"=>"YI","ї"=>"yi",
	    	"Ѓ"=>"G","Ґ"=>"G","ґ"=>"g","Є"=>"YE","є"=>"ye",
		);
		return strtr($str,$tr);
	}	
	//-----------------------------------------------------------------------------------------------
	public function CreateSeoUrl($sAction,$aData,$bAbsolute=0)
	{
	    $aModel=$aData['model'];
	    $aModelDetail=$aData['model_detail'];
	    
		if($bAbsolute) $sUrl=Language::GetConstant('global:project_url');
		$sUrl.='/';
		if(!$aData['data[id_make]'] && $aData['data[id_model]']){
			$aData['data[id_make]']=TecdocDb::GetIdMakeByIdModel($aData['data[id_model]']);
		}
		if(!$aData['cat'] && $aData['data[id_make]']){
			if(!$aCat[$aData['data[id_make]']]){
				$aCat[$aData['data[id_make]']]=Db::GetOne("select name from cat where id='".$aData['data[id_make]']."'");
			}
			$aData['cat']=$aCat[$aData['data[id_make]']];
		}
		if(!$aData['model_translit'] && $aData['data[id_model]']){
			if(!$aModel[$aData['data[id_model]']]){
				/*$aModel[$aData['data[id_model]']]=Db::GetRow(Base::GetSql("OptiCatalog/Model",array(
				'id_model'=>$aData['data[id_model]'],
				'id_make'=>$aData['data[id_make]']
				)));*/
				
				$aModel[$aData['data[id_model]']]=TecdocDb::GetModel(array(
				'id_model'=>$aData['data[id_model]'],
				'id_make'=>$aData['data[id_make]']
				));
			}
			$aData['model_translit']=Content::Translit($aModel[$aData['data[id_model]']]['name']);
		}
		if(!$aData['model_detail_translit'] && $aData['data[id_model_detail]']){
			if(!$aModelDetail[$aData['data[id_model_detail]']]){
				/*$aModelDetail[$aData['data[id_model_detail]']]=Db::GetRow(Base::GetSql("OptiCatalog/ModelDetail",array(
				'id_model'=>$aData['data[id_model]'],
				'id_model_detail'=>$aData['data[id_model_detail]'],
				'id_make'=>$aData['data[id_make]']
				)));*/
			    $aModelDetail[$aData['data[id_model_detail]']]=TecdocDb::GetModelDetail(array(
				'id_model'=>$aData['data[id_model]'],
				'id_model_detail'=>$aData['data[id_model_detail]'],
				'id_make'=>$aData['data[id_make]']
				),$aData['aCat']);
			}
			$aData['model_detail_translit']=Content::Translit($aModelDetail[$aData['data[id_model_detail]']]['name']
					.' '.$aModelDetail[$aData['data[id_model_detail]']]['Name']);
		}
		if(is_numeric($aData['model_translit'])) $aData['model_translit']=$aData['cat']."_".$aData['model_translit'];
		if($aData['model_detail_translit'] && $aData['data[name_part]']) $sModel=$aData['model_detail_translit'].'_'
			.Content::Translit($aData['data[name_part]']).'-';
		elseif($aData['model_detail_translit']) $sModel=$aData['model_detail_translit'].'-';
		elseif($aData['model_translit']) $sModel=$aData['model_translit'].'-';
		else $sModel='';
		$aData['cat'] = Content::Translit($aData['cat']); 
		switch ($sAction) {
			case 'catalog_model_view':
				$sUrl.='cars/'.$aData['cat'].'/';
			break;
			case 'catalog_detail_model_view':
				$sUrl.='cars/'.$aData['cat'].'/'.$sModel.$aData['data[id_model]'].'/';
			break;
			case 'catalog_assemblage_view':
				$sUrl.='cars/'.$aData['cat'].'/'.$sModel.$aData['data[id_model]'].'-'.$aData['data[id_model_detail]'].'/';
			break;
			case 'catalog_part_view':
				$sUrl.='cars/'.$aData['cat'].'/'.$sModel
				.$aData['data[id_model]'].'-'.$aData['data[id_model_detail]'].'-'.$aData['data[id_part]'].'/';
			break;
			case 'rubricator_model_view':
				$sUrl=$sModel.$aData['data[id_model]'];
			break;
			case 'rubricator_detail_model_view':
				$sUrl=$sModel.$aData['data[id_model_detail]'];
			break;
			default:
				;
			break;
		}

		if (Language::getConstant('global:url_is_lower',0) == 1)
			$sUrl = mb_strtolower($sUrl,'utf-8');
		
		if (Language::getConstant('global:url_is_not_last_slash',0) == 1) {
			if (mb_substr($sUrl, -1, 1, 'utf-8') == "/")
				$sUrl = substr($sUrl, 0, -1);
		}	
			
		return $sUrl;
	}

	//-----------------------------------------------------------------------------------------------
	public function CustomizeTable ($oTable) {
		Base::$tpl->assign('bNoneDotUrl',1);
	}
	//-----------------------------------------------------------------------------------------------
	public function RedirectOnSlash()
	{
		return false;
		
		if(strpos($_SERVER['REQUEST_URI'],'?')===FALSE 
				&& strpos($_SERVER['REQUEST_URI'],'mpanel')===FALSE 
				&& substr($_SERVER['REQUEST_URI'],-1)!='/')
		Base::Redirect($_SERVER['REQUEST_URI'].'/');
	}
	//-----------------------------------------------------------------------------------------------
	public function ClearTimer()
	{
		$this->iTimer=microtime(true);
	}
	//-----------------------------------------------------------------------------------------------
	public function ShowTimer($sMessage='')
	{
		if(Auth::$aUser['type_']=='manager' && Language::getConstant('view_admin_time',0))
			$this->sTimer.=$sMessage.": <b>".round(microtime(true)-$this->iTimer,3)."</b>&nbsp;&nbsp;";
	}
	//-----------------------------------------------------------------------------------------------
	public function AddCrumbAndCaption() {
		if (/*Base::$aRequest['action'] != 'catalog' && */ Base::$aRequest['action'] != 'home' && Base::$aRequest['action']) {
			if (Base::$aRequest['action'] == 'news_preview')
				Base::$tpl->assign('sCaptionBlock',Language::GetMessage('news'));
			/*elseif (Base::$aRequest['action'] == 'catalog_part_view' || Base::$aRequest['action'] == 'catalog_model_view' ||
			 Base::$aRequest['action'] == 'catalog_detail_model_view' || Base::$aRequest['action'] == 'catalog_assemblage_view')
			Base::$tpl->assign('sCaptionBlock',Language::GetMessage('autoparts in store'));
			*/
			//elseif (Base::$aRequest['action'] == 'catalog_to') {}
			elseif (Base::$aRequest['action'] == 'catalog') {
			//Base::$oContent->AddCrumb(Language::GetMessage('catalog_auto'),'');
			}
			elseif (Base::$aRequest['action'] == 'catalog_price_view')
			Base::$tpl->assign('sCaptionBlock','<none>'); // for this page need empty caption for design
			elseif (Base::$aRequest['action'] == 'catalog_part_info_view') {
				Base::$tpl->assign('sCaptionBlock',Language::GetMessage('select universal autoparts'));
			}
			else {
				$aData = Db::GetRow("select * from drop_down where code='".Base::$aRequest['action']."'");
				if ($aData){
					$sName=$aData['name'];
					if (Base::$aRequest['locale'] && Base::$aRequest['locale']!='ru') {
						$aDataLocale = Db::getRow("Select * from locale_global where table_name='drop_down' and locale='".
							Base::$aRequest['locale']."' and id_reference=".$aData['id']);
						if ($aDataLocale['name']!='')
							$sName=$aDataLocale['name'];
					}
				}
				
				if ($sName) {
					Base::$tpl->assign('sCaptionBlock',$sName);
	
					if (Base::$aRequest['action'] == 'brands')
						Base::$oContent->AddCrumb(Language::GetMessage('catalog_brands'),'');
					else {
						if (!Base::GetConstant('global:drop_down_crumb',0))
							Base::$oContent->AddCrumb($sName,'');
					}
				}
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function GetMonthDay($iTimestamp='') {
		if (!$iTimestamp) $iTimestamp=time();
		$iTimestamp += $iTimeZone * 3600;
		return date('d.m',$iTimestamp);
	}
	//-----------------------------------------------------------------------------------------------
	public function GetYear($iTimestamp='') {
		if (!$iTimestamp) $iTimestamp=time();
		$iTimestamp += $iTimeZone * 3600;
		return date('Y',$iTimestamp);
	}
	//-----------------------------------------------------------------------------------------------
	public function FixChars($str)
	{
		$tr = array(
			chr(194)=>"",chr(160)=>"",
			"Р"=>"P","І"=>"I","і"=>"i","ї"=>"i","Ї"=>"I","Є"=>"E","є"=>"e","Ґ"=>"Г","ґ"=>"г",
			"É"=>"E","È"=>"E","Ê"=>"E","è"=>"e","é"=>"e","ê"=>"e","Ќ"=>"K","ќ" => "k","Ў" => "U","ў"=>"u",
			"À"=>"A","Á"=>"A","Â"=>"A","Ã"=>"A","Ä"=>"A","à"=>"a","á"=>"a","â"=>"a","ã"=>"a","ä"=>"a",
			"Ӧ"=>"O","Ò"=>"O","Ó"=>"O","Ô"=>"O","Õ"=>"O","Ö"=>"O","ò"=>"o","ó"=>"o","ô"=>"o","õ"=>"o","ö"=>"o",
			"Ù"=>"U","Ú"=>"U","Û"=>"U","Ü"=>"U","ù"=>"u","ú"=>"u","û"=>"u","ü"=>"u","Џ" => "DZ","џ"=>"dz",
		);
		return strtr($str,$tr);
	}
	//-----------------------------------------------------------------------------------------------
	public function CheckMessageUrl() {
		if (Base::$aRequest['amessage']['mt_error_nt'])
			Base::$aRequest['aMessage']['MT_ERROR_NT'] = Base::$aRequest['amessage']['mt_error_nt'];
		if (Base::$aRequest['amessage']['mt_error'])
			Base::$aRequest['aMessage']['MT_ERROR'] = Base::$aRequest['amessage']['mt_error'];
	
		if (Base::$aRequest['amessage']['mt_warning_nt'])
			Base::$aRequest['aMessage']['MT_WARNING_NT'] = Base::$aRequest['amessage']['mt_warning_nt'];
		if (Base::$aRequest['amessage']['mt_warning'])
			Base::$aRequest['aMessage']['MT_WARNING'] = Base::$aRequest['amessage']['mt_warning'];
	
		if (Base::$aRequest['amessage']['mt_notice_nt'])
			Base::$aRequest['aMessage']['MT_NOTICE_NT'] = Base::$aRequest['amessage']['mt_notice_nt'];
		if (Base::$aRequest['amessage']['mt_notice'])
			Base::$aRequest['aMessage']['MT_NOTICE'] = Base::$aRequest['amessage']['mt_notice'];
		////////////////////////////////////////////////
		if (Base::$aRequest['amessage']['mf_error_nt'])
			Base::$aRequest['aMessage']['MF_ERROR_NT'] = Base::$aRequest['amessage']['mf_error_nt'];
		if (Base::$aRequest['amessage']['mf_error'])
			Base::$aRequest['aMessage']['MF_ERROR'] = Base::$aRequest['amessage']['mf_error'];
	
		if (Base::$aRequest['amessage']['mf_warning_nt'])
			Base::$aRequest['aMessage']['MF_WARNING_NT'] = Base::$aRequest['amessage']['mf_warning_nt'];
		if (Base::$aRequest['amessage']['mf_warning'])
			Base::$aRequest['aMessage']['MF_WARNING'] = Base::$aRequest['amessage']['mf_warning'];
	
		if (Base::$aRequest['amessage']['mf_notice_nt'])
			Base::$aRequest['aMessage']['MF_NOTICE_NT'] = Base::$aRequest['amessage']['mf_notice_nt'];
		if (Base::$aRequest['amessage']['mf_notice'])
			Base::$aRequest['aMessage']['MF_NOTICE'] = Base::$aRequest['amessage']['mf_notice'];
	
		////////////////////////////////////////////////
		if (Base::$aRequest['amessage']['mi_error_nt'])
			Base::$aRequest['aMessage']['MI_ERROR_NT'] = Base::$aRequest['amessage']['mi_error_nt'];
		if (Base::$aRequest['amessage']['mi_error'])
			Base::$aRequest['aMessage']['MI_ERROR'] = Base::$aRequest['amessage']['mi_error'];
	
		if (Base::$aRequest['amessage']['mi_warning_nt'])
			Base::$aRequest['aMessage']['MI_WARNING_NT'] = Base::$aRequest['amessage']['mi_warning_nt'];
		if (Base::$aRequest['amessage']['mi_warning'])
			Base::$aRequest['aMessage']['MI_WARNING'] = Base::$aRequest['amessage']['mi_warning'];
	
		if (Base::$aRequest['amessage']['mi_notice_nt'])
			Base::$aRequest['aMessage']['MI_NOTICE_NT'] = Base::$aRequest['amessage']['mi_notice_nt'];
		if (Base::$aRequest['amessage']['mi_notice'])
			Base::$aRequest['aMessage']['MI_NOTICE'] = Base::$aRequest['amessage']['mi_notice'];
	}
	//-----------------------------------------------------------------------------------------------
	public function ProcessDropDownAdditional()
	{
	    if ($_SERVER['REQUEST_URI']) {
	        $aDropDownAdditional=Db::GetRow(Base::GetSql('CoreDropDownAdditional',array(
	            'visible'=>1,
        		'where'=>" and ('".$_SERVER['REQUEST_URI']."' like dda.url)",
	        )));
	    }
	
	    if ($aDropDownAdditional) {
	        if ($aDropDownAdditional['title'])
	            Base::$aData['template']['sPageTitle'] = $aDropDownAdditional['title'];
	        if ($aDropDownAdditional['page_description'])
	            Base::$aData ['template']['sPageDescription']=$aDropDownAdditional['page_description'];
	        if ($aDropDownAdditional['page_keyword'])
	            Base::$aData ['template']['sPageKeyword']=$aDropDownAdditional['page_keyword'];
	        if ($aDropDownAdditional['short_description'])
	            Base::$aData ['template']['sShortDescription']=$aDropDownAdditional['short_description'];
	        if ($aDropDownAdditional['description'])
	            Base::$aData ['template']['sDescription']=$aDropDownAdditional['description'];
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public static function SetMetaTagsPage($sKey,$aData=array())
	{
	    if(!$sKey) return;
	    $aDropDownAdditional=Db::GetRow(Base::GetSql('CoreDropDownAdditional',array(
	        'visible'=>1,
	        'where'=>" and (dda.url='".$_SERVER['REQUEST_URI']."')",
	    )));
	    if(!Base::$aData['template']['sPageTitle']
	        || Base::$aData['template']['sPageTitle']!=$aDropDownAdditional['title']){
	        $aPageTitle=String::GetSmartyTemplate($sKey.'page_title', $aData,false);
	        Base::$aData['template']['sPageTitle']=strip_tags($aPageTitle['parsed_text']);
	    }
	    if(!Base::$aData['template']['sPageDescription']){
	        $aPageDescription=String::GetSmartyTemplate($sKey.'page_description', $aData,false);
	        Base::$aData['template']['sPageDescription']=strip_tags($aPageDescription['parsed_text']);
	    }
	    if(!Base::$aData['template']['sPageKeyword']){
	        $aPageKeyword=String::GetSmartyTemplate($sKey.'page_keyword', $aData,false);
	        Base::$aData['template']['sPageKeyword']=strip_tags($aPageKeyword['parsed_text']);
	    }
	    if(!Base::$aData['template']['sPageH1']){
	        $aPageH1=String::GetSmartyTemplate($sKey.'page_h1', $aData,false);
	        Base::$aData['template']['sPageH1']=strip_tags($aPageH1['parsed_text']);
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public static function SetH1($sKey, $aData=array(),$sName='')
	{
	    if ($sName != '') {
	        Base::$aData['template']['sBodyH1']=$sName;
	        return;
	    }
	     
	    if(!$sKey) return;
	    $aBodyH1=String::GetSmartyTemplate($sKey.'body_h1', $aData,false);
	    Base::$aData['template']['sBodyH1']=strip_tags($aBodyH1['parsed_text']);
	}
    //-----------------------------------------------------------------------------------------------
	public function getStepper($oTable,$aData) {
	
	    $iPage = intval ( Base::$aRequest [$this->sPrefix . 'step'] );
	    $this->iPage=$iPage;
	
	    $aPageArray=Content::printPage($aData['iAllPageNumber'],$aData['iPage']);
	    $next = $iPage + 1;
	    $previous = $iPage - 1;
	    $iRowPerPage = $this->iRowPerPage;
	    $iRowNumber = $this->iRowNumber;
	    if($iRowPerPage) {
	        if (($iRowNumber % $iRowPerPage) > 0) $adding = 0;
	        else $adding = - 1;
	        $iPageNumber = intval ( $iRowNumber / $iRowPerPage ) + $adding;
	    }
	    if($this->sPrefix=='')
	        $this->sPrefix=$oTable->sPrefix;
		$this->iStartStep=1;

		if(strpos(Base::$aRequest['action'], 'manager_panel')!==false) 
			$oTable->sStepperType = 'manager_panel_stepper';

        switch ($oTable->sStepperType) {
	        case 'step_chpu':{
	            if ($iPageNumber > $this->iStepNumber)
	                $iPageNumber = $this->iStepNumber;
	            if($aPageArray) foreach ($aPageArray as $i) {
	                if (strcmp($aData['iPage'],$i)==0) {
	                    $sPageText .= "<a class='step selected'>".($i+$this->iStartStep)."</a>";
	                }
	                elseif(strcmp($i,'...')==0) {
	                    if(Content::isMobile()) {
	                        continue;
	                    }
	                    $sPageText .= "<a class='step blank'>...</a>";
	                }
	                else {
	                    $sUrlTmp=$aData['sPrefUrl']."/?" . $aData['sQueryString'] . "&" . $this->sPrefix. "step=".$i;
	                    self::CreateNedUrl($sUrlTmp);
	                    $sPageText .= "<a class='step' href='".$sUrlTmp."'>".($i+$this->iStartStep)."</a>";
	                }
	            }
	            
	            if ($aData['iPage'] > 0) {
	                $sPreviousText=$aData['sPrefUrl']."/?" . $aData['sQueryString'] . "&" . $oTable->sPrefix. "step=".$aData['previous'];
	                self::CreateNedUrl($sPreviousText);
	                $previous_text = "<a class='prev' href='".$sPreviousText."'>‹ " . Language::GetMessage ( 'Prev' ) . "</a>";
	                 
	                $sBeginText=$aData['sPrefUrl']."/?" . $aData['sQueryString'] . "&" . $oTable->sPrefix. "step=0";
	                self::CreateNedUrl($sBeginText);
	                $begin_text = "<a href='".$sBeginText."'>‹ " . Language::GetMessage ( 'begin page' ) . "</a>";
	            } else {
	                $previous_text = "";
	                $begin_text = "";
	            }
	             
	            $iAllPageNumber = $iPageNumber;
	            Base::$tpl->assign('iAllPageNumber', $iAllPageNumber+1);
	            $iPage = intval ( Base::$aRequest [$this->sPrefix . 'step'] );
	            $this->iPage=$iPage;
	            $iRowPerPage = $this->iRowPerPage;
	             
	            if($iRowPerPage) {
	                if (($iRowNumber % $iRowPerPage) > 0) $adding = 0;
	                else $adding = - 1;
	                $iPageNumber = intval ( $iRowNumber / $iRowPerPage ) + $adding;
	            }
	            
	            $sCanonicalUrl=PriceGroup::PreGenerateFilterLink($aData['sPrefUrl']."/?".$aData['sQueryString'] . "&" . $this->sPrefix. "step=".$iPage."&remove_all=1");
	            //---------------------------
	            if ($iPage < $i) {
	                $sNextUrl=$aData['sPrefUrl']."/?".$aData['sQueryString'] . "&" . $this->sPrefix. "step=".$next;
	                self::CreateNedUrl($sNextUrl);
	                $next_text = "<a class='next' href='".$sNextUrl."'>".Language::GetMessage('Next')." ›</a> ";
	                
	                $sEndUrl=$aData['sPrefUrl']."/?".$aData['sQueryString']."&".$oTable->sPrefix."step=".$aData['iAllPageNumber'];
	                self::CreateNedUrl($sEndUrl);
	                $end_text="<a href='".$sEndUrl."'>".Language::GetMessage('end page')." ›</a>";
	                
	                $iPrev=$next-2;
	                if($iPrev>0) {
	                    $sPrevUrl=$aData['sPrefUrl']."/?".$aData['sQueryString'] . "&" . $this->sPrefix. "step=".($next-2);
	                    self::CreateNedUrl($sPrevUrl);
	                } else {
	                    $sPrevUrl=$aData['sPrefUrl']."/?".$aData['sQueryString'] . "&" . $this->sPrefix. "step=0";
	                    self::CreateNedUrl($sPrevUrl);
	                    if($iPage<2) {
	                        $sPrevUrl=0;
	                    }
	                }
	            } else {
	                $sNextUrl=0;
	                $iPrev=$next-2;
	                if($iPrev>0) {
	                    $sPrevUrl=$aData['sPrefUrl']."/?".$aData['sQueryString'] . "&" . $this->sPrefix. "step=".($next-2);
	                    self::CreateNedUrl($sPrevUrl);
	                } else {
	                    $sPrevUrl=$aData['sPrefUrl']."/?".$aData['sQueryString'] . "&" . $this->sPrefix. "step=0";
	                    self::CreateNedUrl($sPrevUrl);
	                    if($iPage==0) {
	                        $sPrevUrl=0;
	                    }
	                }
	            }
	            //---------------------------------------------------
	            // rel next prev
	            if($sNextUrl) Base::$tpl->assign('sNextUrl',"http://".$_SERVER['HTTP_HOST'].str_replace("//", "/", $sNextUrl."/"));
	            if($sPrevUrl) Base::$tpl->assign('sPrevUrl',"http://".$_SERVER['HTTP_HOST'].str_replace("//", "/", $sPrevUrl."/"));
	            if($sCanonicalUrl) Base::$tpl->assign('sUrlCanonical',"http://".$_SERVER['HTTP_HOST'].str_replace("//", "/", $sCanonicalUrl."/"));
	             
	            if ($aData['iPage'] > 0) {
	                Base::$tpl->assign('bNoFollow',1);
	                Base::$tpl->assign('bNoIndex',1);
	            }
	             
	            if(!$aPageArray && $this->bStepperHideNoPages) {
	                return 0;
	            } else {
	                if($sPageText) {
	                    return '<div class="at-stepper">'.$previous_text.$sPageText.$next_text.'</div>';
	                } else {
	                    return 0;
	                }
	            }
	        }
	        break;
	        //-----------------------------------------------------------------------------------------------
	        case 'step_chpu_rubricator':{
	            if ($iPageNumber > $this->iStepNumber)
	                $iPageNumber = $this->iStepNumber;
	            if($aPageArray) foreach ($aPageArray as $i) {
	                if (strcmp($aData['iPage'],$i)==0) {
	                    $sPageText .= "<a class='step selected'>".($i+$this->iStartStep)."</a>";
	                }
	                elseif(strcmp($i,'...')==0) {
	                    if(Content::isMobile()) {
	                        continue;
	                    }
	                    $sPageText .= "<a class='step blank'>...</a>";
	                }
	                else {
	                    $sUrlTmp=$aData['sPrefUrl']."/?" . $aData['sQueryString'] . "&" . $this->sPrefix. "step=".$i;
	                    self::CreateNedUrlRubricator($sUrlTmp);
	                    $sPageText .= "<a class='step' href='".$sUrlTmp."'>".($i+$this->iStartStep)."</a>";
	                }
	            }
	            
	            if ($aData['iPage'] > 0) {
	                $sPreviousText=$aData['sPrefUrl']."/?" . $aData['sQueryString'] . "&" . $oTable->sPrefix. "step=".$aData['previous'];
	                self::CreateNedUrlRubricator($sPreviousText);
	                $previous_text = "<a class='prev' href='".$sPreviousText."'>‹ " . Language::GetMessage ( 'Prev' ) . "</a>";
	                
	                $sBeginText=$aData['sPrefUrl']."/?" . $aData['sQueryString'] . "&" . $oTable->sPrefix. "step=0";
	                self::CreateNedUrlRubricator($sBeginText);
	                $begin_text = "<a href='".$sBeginText."'>‹ " . Language::GetMessage ( 'begin page' ) . "</a>";
	            } else {
	                $previous_text = "";
	                $begin_text = "";
	            }
	             
	            $iAllPageNumber = $iPageNumber;
	            Base::$tpl->assign('iAllPageNumber', $iAllPageNumber+1);
	            $iPage = intval ( Base::$aRequest [$this->sPrefix . 'step'] );
	            $this->iPage=$iPage;
	            $iRowPerPage = $this->iRowPerPage;
	             
	            if($iRowPerPage) {
	                if (($iRowNumber % $iRowPerPage) > 0) $adding = 0;
	                else $adding = - 1;
	                $iPageNumber = intval ( $iRowNumber / $iRowPerPage ) + $adding;
	            }
	            
	            $sCanonicalUrl=Rubricator::PreGenerateFilterLink($aData['sPrefUrl']."/?".$aData['sQueryString'] . "&" . $this->sPrefix. "step=".$iPage."&remove_all=1");
	            //---------------------------
	            if ($iPage < $i) {
	                $sNextUrl=$aData['sPrefUrl']."/?".$aData['sQueryString'] . "&" . $this->sPrefix. "step=".$next;
	                self::CreateNedUrlRubricator($sNextUrl);
	                $next_text = "<a class='next' href='".$sNextUrl."'>".Language::GetMessage('Next')." ›</a> ";
	                
	                $sEndUrl=$aData['sPrefUrl']."/?".$aData['sQueryString']."&".$oTable->sPrefix."step=".$aData['iAllPageNumber'];
	                self::CreateNedUrlRubricator($sEndUrl);
	                $end_text="<a href='".$sEndUrl."'>".Language::GetMessage('end page')." ›</a>";
	                
	                $iPrev=$next-2;
	                if($iPrev>0) {
	                    $sPrevUrl=$aData['sPrefUrl']."/?".$aData['sQueryString'] . "&" . $this->sPrefix. "step=".($next-2);
	                    self::CreateNedUrlRubricator($sPrevUrl);
	                } else {
	                    $sPrevUrl=$aData['sPrefUrl']."/?".$aData['sQueryString'] . "&" . $this->sPrefix. "step=0";
	                    self::CreateNedUrlRubricator($sPrevUrl);
	                    if($iPage==0) {
	                        $sPrevUrl=0;
	                    }
	                }
	            } else {
	                $sNextUrl=0;
	                $iPrev=$next-2;
	                if($iPrev>0) {
	                    $sPrevUrl=$aData['sPrefUrl']."/?".$aData['sQueryString'] . "&" . $this->sPrefix. "step=".($next-2);
	                    self::CreateNedUrlRubricator($sPrevUrl);
	                } else {
	                    $sPrevUrl=$aData['sPrefUrl']."/?".$aData['sQueryString'] . "&" . $this->sPrefix. "step=0";
	                    self::CreateNedUrlRubricator($sPrevUrl);
	                    if($iPage==0) {
	                        $sPrevUrl=0;
	                    }
	                }
	            }
	            //---------------------------------------------------
	            // rel next prev
	            if($sNextUrl) Base::$tpl->assign('sNextUrl',"http://".$_SERVER['HTTP_HOST'].str_replace("//", "/", $sNextUrl."/"));
	            if($sPrevUrl) Base::$tpl->assign('sPrevUrl',"http://".$_SERVER['HTTP_HOST'].str_replace("//", "/", $sPrevUrl."/"));
	            if($sCanonicalUrl) Base::$tpl->assign('sUrlCanonical',"http://".$_SERVER['HTTP_HOST'].str_replace("//", "/", $sCanonicalUrl."/"));
	             
	            if ($aData['iPage'] > 0) {
	                Base::$tpl->assign('bNoFollow',1);
	                Base::$tpl->assign('bNoIndex',1);
	            }
	             
	            if(!$aPageArray && $this->bStepperHideNoPages) {
	                return 0;
	            } else {
	                if($sPageText) {
	                    return '<div class="at-stepper">'.$previous_text.$sPageText.$next_text.'</div>';
	                } else {
	                    return 0;
	                }
	            }
	        }
	        break;
	        //-----------------------------------------------------------------------------------------------
	        case 'manager_panel_stepper':{
	        	if ($iPageNumber > $this->iStepNumber)
	        		$iPageNumber = $this->iStepNumber;
	        	if($aPageArray) foreach ($aPageArray as $i) {
	        		if (strcmp($aData['iPage'],$i)==0) {
	        			$sPageText .= "<li class='active'><span>".($i+$this->iStartStep)."</span></li>";
	        		}
	        		elseif(strcmp($i,'...')==0) {
	        			if(Content::isMobile()) {
	        				continue;
	        			}
	        			$sPageText .= "<li class='disabled'><span>...</span></li>";
	        		}
	        		else {
	        			$sPageText .= "<li><a href='".$aData['sPrefUrl']."/?" . $aData['sQueryString'] . "&" . $this->sPrefix. "step=$i' ".$aData['sAjaxScript'].">".($i+$this->iStartStep)."</a></li>";
	        		}
	        	}
	        	 
	        	if ($aData['iPage'] > 0) {
	        		$previous_text = "<li><a class='prev' href='".$aData['sPrefUrl']."/?" . $aData['sQueryString'] . "&" . $oTable->sPrefix. "step=".$aData['previous']."'". $aData['sAjaxScript'].">« " . Language::GetMessage ( 'Prev' ) . "</a></li>";
	        		$begin_text = "<li><a href='".$aData['sPrefUrl']."/?" . $aData['sQueryString'] . "'". $aData['sAjaxScript'].">« " . Language::GetMessage ( 'begin page' ) . "</a></li>";
	        	} else {
	        		$previous_text = ""; //"‹ ".Language::GetMessage ( 'Prev' );
	        		$begin_text = "";
	        	}
	        	 
	        	$iAllPageNumber = $iPageNumber;
	        	Base::$tpl->assign('iAllPageNumber', $iAllPageNumber+1);
	        	$iPage = intval ( Base::$aRequest [$this->sPrefix . 'step'] );
	        	$this->iPage=$iPage;
	        	$iRowPerPage = $this->iRowPerPage;
	        	 
	        	if($iRowPerPage) {
	        		if (($iRowNumber % $iRowPerPage) > 0) $adding = 0;
	        		else $adding = - 1;
	        		$iPageNumber = intval ( $iRowNumber / $iRowPerPage ) + $adding;
	        	}
	        	$bNoneDotUrl = Base::$tpl->get_template_vars('bNoneDotUrl');
	        	if($bNoneDotUrl)  $sPrefUrl=''; else $sPrefUrl=Table::$sLinkPrefix;
	        	if ($this->bAjaxStepper)
	        		$sAjaxScript = " onclick=\" xajax_process_browse_url(this.href); return false;\" ";
	        	 
	        	$sQueryString = preg_replace ( '/&' . $this->sPrefix . 'step=(\d+)/', '', $this->sQueryString );
	        	$sCanonicalUrl = $sQueryString;
	        	//---------------------------
	        	if ($iPage < $i) {
	        		$next_text = "<li><a class=next href='".$aData['sPrefUrl']."/?"
	        				.$aData['sQueryString'] . "&" . $this->sPrefix. "step=$next' ".$aData['sAjaxScript']." >" . Language::GetMessage ( 'Next' ) . " »</a></li>";
	        		$end_text="<li><a href='".$aData['sPrefUrl']."/?".$aData['sQueryString']."&".$oTable->sPrefix."step=".$aData['iAllPageNumber']."'". $aData['sAjaxScript'].">".Language::GetMessage('end page')." »</a></li>";
	        	}
	        	//---------------------------------------------------
	        	// rel next prev
	        	if($sNextUrl) Base::$tpl->assign('sNextUrl',"https://".$_SERVER['HTTP_HOST'].str_replace("//", "/", $sNextUrl."/"));
	        	if($sPrevUrl) Base::$tpl->assign('sPrevUrl',"https://".$_SERVER['HTTP_HOST'].str_replace("//", "/", $sPrevUrl."/"));
	        	if($sCanonicalUrl) Base::$tpl->assign('sUrlCanonical',"http://".$_SERVER['HTTP_HOST'].str_replace("//", "/", $sCanonicalUrl."/"));
	        	 
	        	if ($aData['iPage'] > 0) {
	        		Base::$tpl->assign('bNoFollow',1);
	        		Base::$tpl->assign('bNoIndex',1);
	        	}
	        	 
	        	if(!$aPageArray && $this->bStepperHideNoPages) {
	        		return 0;
	        	} else {
	        		if($sPageText) {
	        			return '<ul class="pagination">'.$previous_text.$sPageText.$next_text.'</ul>';
	        		} else {
	        			return 0;
	        		}
	        	}
	        }
	        break;
	        //-----------------------------------------------------------------------------------------------
	        default:{
	            if ($iPageNumber > $this->iStepNumber)
	                $iPageNumber = $this->iStepNumber;
	            if($aPageArray) foreach ($aPageArray as $i) {
	                if (strcmp($aData['iPage'],$i)==0) {
	                    $sPageText .= "<a class='step selected'>".($i+$this->iStartStep)."</a>";
	                }
	                elseif(strcmp($i,'...')==0) {
	                    if(Content::isMobile()) {
	                        continue;
	                    }
	                    $sPageText .= "<a class='step blank'>...</a>";
	                }
	                else {
	                    $sPageText .= "<a class='step' href='".$aData['sPrefUrl']."/?" . $aData['sQueryString'] . "&" . $this->sPrefix. "step=$i' ".$aData['sAjaxScript'].">".($i+$this->iStartStep)."</a>";
	                }
	            }
	            
	            if ($aData['iPage'] > 0) {
	                $previous_text = "<a class='prev' href='".$aData['sPrefUrl']."/?" . $aData['sQueryString'] . "&" . $oTable->sPrefix. "step=".$aData['previous']."'". $aData['sAjaxScript'].">‹ " . Language::GetMessage ( 'Prev' ) . "</a>";
	                $begin_text = "<a href='".$aData['sPrefUrl']."/?" . $aData['sQueryString'] . "'". $aData['sAjaxScript'].">‹ " . Language::GetMessage ( 'begin page' ) . "</a>";
	            } else {
	                $previous_text = ""; //"‹ ".Language::GetMessage ( 'Prev' );
	                $begin_text = "";
	            }
	            
	            $iAllPageNumber = $iPageNumber;
	            Base::$tpl->assign('iAllPageNumber', $iAllPageNumber+1);
	            $iPage = intval ( Base::$aRequest [$this->sPrefix . 'step'] );
	            $this->iPage=$iPage;
	            $iRowPerPage = $this->iRowPerPage;
	            
	            if($iRowPerPage) {
	                if (($iRowNumber % $iRowPerPage) > 0) $adding = 0;
	                else $adding = - 1;
	                $iPageNumber = intval ( $iRowNumber / $iRowPerPage ) + $adding;
	            }
	            $bNoneDotUrl = Base::$tpl->get_template_vars('bNoneDotUrl');
	            if($bNoneDotUrl)  $sPrefUrl=''; else $sPrefUrl=Table::$sLinkPrefix;
	            if ($this->bAjaxStepper)
	                $sAjaxScript = " onclick=\" xajax_process_browse_url(this.href); return false;\" ";
	            
	            $sQueryString = preg_replace ( '/&' . $this->sPrefix . 'step=(\d+)/', '', $this->sQueryString );
	            $sCanonicalUrl = $sQueryString;
	            //---------------------------
	            if ($iPage < $i) {
	                $next_text = "<a class=next href='".$aData['sPrefUrl']."/?"
	                    .$aData['sQueryString'] . "&" . $this->sPrefix. "step=$next' ".$aData['sAjaxScript']." ></a> ";
	                $end_text="<a href='".$aData['sPrefUrl']."/?".$aData['sQueryString']."&".$oTable->sPrefix."step=".$aData['iAllPageNumber']."'". $aData['sAjaxScript'].">".Language::GetMessage('end page')." ›</a>";
	            }
	            //---------------------------------------------------
	            // rel next prev
	            if($sNextUrl) Base::$tpl->assign('sNextUrl',"https://".$_SERVER['HTTP_HOST'].str_replace("//", "/", $sNextUrl."/"));
	            if($sPrevUrl) Base::$tpl->assign('sPrevUrl',"https://".$_SERVER['HTTP_HOST'].str_replace("//", "/", $sPrevUrl."/"));
	            if($sCanonicalUrl) Base::$tpl->assign('sUrlCanonical',"http://".$_SERVER['HTTP_HOST'].str_replace("//", "/", $sCanonicalUrl."/"));
	            
	            if ($aData['iPage'] > 0) {
	                Base::$tpl->assign('bNoFollow',1);
	                Base::$tpl->assign('bNoIndex',1);
	            }
	            
	            if(!$aPageArray && $this->bStepperHideNoPages) {
	                return 0;
	            } else {
	                if($sPageText) {
	                    return '<div class="at-stepper">'.$previous_text.$sPageText.$next_text.'</div>';
	                } else {
	                    return 0;
	                }
	            }
	        }
	        break;
	    }
	    
// 	    <div class="at-stepper">
//     	    <a href="#" class="prev"></a>
//     	    <a href="#" class="next"></a>
    	    
//     	    <a class="step selected" href="#">1</a>
//     	    <a class="step" href="#">2</a>
//     	    <a class="step" href="#">3</a>
//     	    <a class="step" href="#">4</a>
//     	    <a class="step" href="#">5</a>
//     	    <a class="step" href="javascript:void(0);">...</a>
//     	    <a class="step" href="#">59</a>
// 	    </div>
	     
	}
	//-----------------------------------------------------------------------------------------------
	private static function CreateNedUrl(&$sUrl)
	{
	    $aLink=parse_url($sUrl);
	    parse_str($aLink['query'],$aRequestData);
	    PriceGroup::ParsingParameter($aRequestData);
	    $sUrl=PriceGroup::GenerateFilterLink($aRequestData);
	}
	//-----------------------------------------------------------------------------------------------
	private static function CreateNedUrlRubricator(&$sUrl)
	{
	    $aLink=parse_url($sUrl);
	    parse_str($aLink['query'],$aRequestData);
	    Rubricator::ParsingParameter($aRequestData);
	    if(Base::$tpl->_tpl_vars['sSelectedCarUrlRubricator']) {
	        $aRequestData['selected_auto']=Base::$tpl->_tpl_vars['sSelectedCarUrlRubricator'];
	    } elseif(Base::$aRequest['cat']) {
	        $aRequestData['selected_auto']="/c/".Base::$aRequest['cat'];
	        if(Base::$aRequest['model_group']) {
	            $aRequestData['selected_auto'].="_".Base::$aRequest['model_group'];
	        }
	    }
	    $sUrl=Rubricator::GenerateFilterLink($aRequestData);
	}
	//-----------------------------------------------------------------------------------------------
	public function Error404($bRedirectMissing=false) {
	    if (Base::GetConstant('global:404_exclude_query')) {
	        $aExcludeQuery=preg_split("/[\s,]+/", Base::GetConstant('global:404_exclude_query'));
	        foreach ($aExcludeQuery as $aValue) {
	            if (strpos($_SERVER['QUERY_STRING'],$aValue)!==false) return;
	        }
	    }
	    if ($bRedirectMissing) {
	        Base::Redirect('/missing/');
	    } else {
	        $aMissing=Db::GetRow("select * from drop_down where code='missing'");
	        Base::$sText=Base::$tpl->fetch('404.tpl');
	        Base::$aData['template']['sPageKeyword'] = $aMissing['page_keyword'];
	        Base::$aData['template']['sPageDescription'] = $aMissing['page_description'];
	        Base::$aData['template']['sPageTitle'] = $aMissing['title'];
	        
	        Home::GetPopularProducts();
	    }
	     
	    $bRedirectSent=false;
	    $aHeader=headers_list();
	    foreach($aHeader as $aValue)
	        if (stripos($aValue,'Location:')!==false) {
	            $bRedirectSent=true;
	            break;
	        }
	    if (!$bRedirectSent) header("HTTP/1.0 404 Not Found");
	}
	//-----------------------------------------------------------------------------------------------
	public function showCarSelect($bShowTpl=true) {
	    if($_COOKIE['id_model_detail'] && !Base::$aRequest['data']['id_model_detail'] && !Base::$aRequest['clear_auto']) {
	        $aAuto= Content::getSavedAuto();
	        Base::$aRequest['car_select']['id_model_detail'] = $aAuto['id_model_detail'];
	        Base::$aRequest['car_select']['id_model'] = $aAuto['id_model'];
	        Base::$aRequest['car_select']['id_make'] = $aAuto['id_make'];
        }elseif(Base::$aRequest['data']['id_model_detail']){
            Base::$aRequest['car_select']['id_model_detail'] = Base::$aRequest['data']['id_model_detail'];
            Base::$aRequest['car_select']['id_model'] = Base::$aRequest['data']['id_model'];
            Base::$aRequest['car_select']['id_make'] = Base::$aRequest['data']['id_make'];
        }
        if(Base::$aRequest['car_select']['id_model_detail'] && !Base::$aRequest['clear_auto']){
            $aModelDetailChosen=TecdocDb::GetModelDetail(Base::$aRequest['car_select']); //Db::GetRow(Base::GetSql("OptiCatalog/ModelDetail",Base::$aRequest['car_select']));
            if($aModelDetailChosen)
                $aModel = Db::GetRow("SELECT cmg.name as name_group, cm.name as name_model FROM cat_model as cm
			 inner join cat_model_group as cmg on cmg.visible=1 and FIND_IN_SET(cm.tof_mod_id, cmg.id_models)
			  WHERE cm.tof_mod_id = ".$aModelDetailChosen['id_model']
                );
            $aModelDetailChosen['new_name'] = $aModel['name_group'];

            $aBody = array(
                'наклонная задняя часть' => 'хэтчбек',
                'вездеход закрытый' => 'внедорожник',
                'вездеход открытый' => 'внедорожник',
                'вэн' => 'минивэн',
                'кабрио' => 'кабриолет',
                'c бортовой платформой/ходовая часть' => 'с бортом',
            );

            $aModelDetailChosen['new_body'] = $aBody[mb_convert_case($aModelDetailChosen['Body'], MB_CASE_LOWER, "UTF-8")];
            $sUrl=Content::CreateSeoUrl('catalog_assemblage_view',array(
                'data[id_make]'=>Base::$aRequest['car_select']['id_make'],
                'data[id_model]'=>Base::$aRequest['car_select']['id_model'],
                'data[id_model_detail]'=>Base::$aRequest['car_select']['id_model_detail'],
                'model_translit'=>Content::Translit($aModelDetailChosen['name'])
            ));
            if(Base::$aRequest['action'] == 'price_group' || Base::$aRequest['action'] == 'catalog'
                || Base::$aRequest['action'] == 'catalog_part_view' || (Base::$aRequest['action'] == 'catalog_assemblage_view' && Base::$aRequest['car_select']['rubricator_url']))
                    Base::$tpl->assign('sTecdocUrl',$sUrl);
                $aModelDetailChosen['Fuel'] = mb_strtolower($aModelDetailChosen['Fuel'],'utf8');
                $aSelectedCar = TecdocDb::GetModelInfo(Base::$aRequest['car_select']); //Db::GetRow(Base::GetSql("OptiCatalog/ModelInfo",Base::$aRequest['car_select']));
                $aModelDetailChosen['cylinder'] = $aSelectedCar['cylinder'];
                $aModelDetailChosen['image']=Catalog::GetModelPic(Base::$aRequest['car_select']);
                Base::$tpl->assign('aModelDetailChosen',$aModelDetailChosen);

                //output in smarty
                $sClearAutoUrl='';
                if(Base::$aRequest['subcategory']) {
                    //check wrong subcategory
                    $bExists=Db::GetOne("select id from rubricator where url='".Base::$aRequest['subcategory']."' ");
                    if($bExists) {
                        $sClearAutoUrl="/rubricator/".Base::$aRequest['category']."/".Base::$aRequest['subcategory']."?clear_auto=1";
                    } else {
                        $sClearAutoUrl="/rubricator/".Base::$aRequest['category']."?clear_auto=1";
                    }
                } elseif(Base::$aRequest['category']) {
                    $sClearAutoUrl="/rubricator/".Base::$aRequest['category']."?clear_auto=1";
                } elseif(!Base::$aRequest['subcategory'] && !Base::$aRequest['category'] && Base::$aRequest['action']=='rubricator') {
                    $sClearAutoUrl="/rubricator?clear_auto=1";
                } else {
                    $sClearAutoUrl="/pages/catalog/?clear_auto=1";
                }

                Base::$tpl->assign('sClearAutoUrl',$sClearAutoUrl);
                if($bShowTpl) $sShowCarSelected= Base::$tpl->fetch('car_select/chosen_modification.tpl');
                Base::$tpl->assign('sShowCarSelected',$sShowCarSelected);
        }
        if($bShowTpl){
            CarSelect::Index();
        }
    }
    //-----------------------------------------------------------------------------------------------
    public function getSavedAuto(){
        if($_COOKIE['id_model_detail']) {
            $aAuto=TecdocDb::GetSelectCar(array('id_model_detail'=>$_COOKIE['id_model_detail']));
            $aAuto['image']=Catalog::GetModelPic(array('id_model_detail'=>$_COOKIE['id_model_detail']));
             
            $sUrl=Content::CreateSeoUrl('catalog_assemblage_view',array(
                'data[id_make]'=>$aAuto['id_make'],
                'data[id_model]'=>$aAuto['id_model'],
                'data[id_model_detail]'=>$aAuto['id_model_detail'],
                'model_translit'=>Content::Translit($aAuto['name'])
            ));
            $sUrl=str_replace('/cars/', 'c/', $sUrl);
            $sMake=Db::GetOne("select name from cat where id='".$aAuto['id_make']."' ");
            $sMake=strtolower($sMake);
	        $sUrl=str_replace("/".$sMake."/", '/', $sUrl);
            
            Base::$tpl->assign('sTecdocUrl',"/rubricator/".$sUrl);
            Base::$tpl->assign('aModelDetailChosen',$aAuto);
            return $aAuto;
        } else {
            return false;
        }
    }
	//-----------------------------------------------------------------------------------------------
	// from copy actipon_includer.php
    public function CheckExistAction($sAction=''){
	    $action_array=array();
	    $directory=SERVER_PATH."/spec/";
	    if ($dh = opendir($directory)) {
	    	while (($file = readdir($dh)) !== false) {
	    		if ($file != "." && $file != ".." && strpos($file,'.php')!==false) {
	    			if (filetype($directory . $file)=="file") {
	    				$file_name_array=preg_split("/\.php/",$file);
	    				$file_name=$file_name_array[0];
	    				if (!in_array($file,$action_array)) $action_array[$file_name]=$file;
	    			}
	    		}
	    	}
	    	closedir($dh);
	    }
	    //----------------------------------------------------------
	    krsort($action_array, SORT_STRING);
	    
	    if ($sAction)
	    	$curract = $sAction;
	    else
	    	$curract = Base::$aRequest['action'];
	    
	    foreach ($action_array as $action_key => $action_value)
	    {
	    	$action_parts = explode('*', $action_key);
	    	$hasAll = true;
	    	$f = true;
	    	foreach ($action_parts as $action_part)
	    	{
	    		if (strlen(trim($action_part)) > 0)
	    		{
	    			$spos = strpos($curract,$action_part);
	    			if ($spos === false || (($spos > 0) && ($f == true)))
	    			{
	    				$hasAll = false;
	    			}
	    			$f = false;
	    		}
	    	}
	    	if ($hasAll == true)
	    	{
	    		return $action_value;
	    		break;
	    	}
	    }
	    return '';
    }
   	//-----------------------------------------------------------------------------------------------
    public function CheckStaticPage($sAction='') {
    	if (!$sAction)
    		$sAction=Base::$aRequest['action'];
    	
    	$iPageExist = Db::getOne("Select id from drop_down where code='".$sAction."' and visible=1");
    	if ($iPageExist)
    		return true;
    	
    	return false;
    }
    //-----------------------------------------------------------------------------------------------
    public function CheckActionMenu($sAction='',$sDescription='') {
    	return Base::$oContent->CheckAccessManager(false,$sAction,$sDescription);
    }
    //-----------------------------------------------------------------------------------------------
    public function CheckAccessManager($bChangeAction=true,$sAction='',$sDescription='') {
    	if ($sAction=='') {
	    $sAction = Base::$aRequest['action'];
	}
	if (strpos($sAction,"?")!==false) {
    	    $sAction=explode("?", $sAction);
    	    $sAction = $sAction[0];
    	}
    	
    	if (!$sAction)
    		 return true;

    	if (Base::$aRequest['xajax'] || Auth::$aUser['type_']!='manager' || Auth::$aUser['is_super_manager']
    		|| $sAction=='access_denied' || $sAction=='home' || $sAction=='missing')
    		 return true;
		
		// check exeption
		$iIsExeption = Db::getOne("Select id from role_action_exeption where action_name='".$sAction.
			"' and is_exeption=1");
		if ($iIsExeption)
			return true;
		    	
    	$iIsNotLinkAction=0;
    	$iIsStaticPage=0;
    	$iIdAction = Db::getOne("Select id from role_action where action_name='".$sAction."'");
    	if (!$iIdAction) {
    		// check action
    		if (Content::CheckExistAction($sAction)!='') {
    			Db::Execute("Insert into role_action (action_name, action_description)
						VALUES ('".$sAction."','".$sDescription."')");
    			$iIsNotLinkAction = 1;
    		}elseif (Content::CheckStaticPage($sAction))
    			$iIsStaticPage=1;
    	}
    
    	if ($iIsStaticPage && $iIsNotLinkAction==0) {}
    	elseif (Auth::$aUser['type_']=='manager' && (!Auth::$aUser['is_super_manager']
    			&& (!Auth::CheckPermissions($iIdAction) || $iIsNotLinkAction))) {
    		if ($bChangeAction) 
    			Base::$aRequest['action'] = 'access_denied';
    		return false;
    	}
    	return true;
    }
    //-----------------------------------------------------------------------------------------------
    public function CheckDashboard($sAction) {
        $aDashboardAction=array(
            'dashboard', 'customer_profile', 'cart_order', 'cart_package_list', 'finance', 'own_auto','message_preview','price_queue_edit',
            'message_change_current_folder', 'payment_report', 'payment_declaration','manager_provider','manager_cat_pref_add','manager_order_edit',
            'manager_office', 'catalog_manager_history_tree', 'manager_provider_requests_edit_cart','price_profile_provider_edit','garage_manager_add',
            'catalog_manager_history_image', 'catalog_manager_history_characteristic','catalog_manager_history_cross', 'catalog_manager_history_applicability',
            'manager_invoice_customer_invoice_edit','cart_order_edit','cart','cart_cart','cart_cart_edit','manager_profile','extension_td','extension_td_cat_info_import_add',
             
            'price', 'price_profile_add', 'garage_manager_edit', 'price_conformity', 'price_profile', 'price_profile_edit', 'manager_cat_pref', 'manager_package_list', 'manager_order', 'manager_customer',
            'manager_cart', 'message', 'vin_request_manager', 'finance_bill', 'manager_invoice_customer', 'manager_invoice_customer_invoice',
            'manager_cat', 'catalog_cross', 'payment_report_manager', 'payment_declaration_manager', 'manager_user_debt', 'manager_synonym',
            'price_ftp', 'manager_package_edit', 'manager_order_edit', 'manager_edit_weight', 'manager_change_provider','message_move_to_folder',
            'vin_request_manager_edit', 'vin_request_manager_send_preview', 'message_compose', 'message_send', 'message_forward', 'finance_bill_edit', 'finance_bill_provider_add', 'manager_cat_add', 'catalog_cross_stop',
            'catalog_cross_stop_add', 'catalog_cross_add', 'catalog_cross_import', 'payment_declaration_manager_add', 'payment_declaration_manager_edit',
             
            'manager_load_names','manager_load_images','extension_td_cat_info_import','extension_td_history_image','extension_td_history_characteristic',
            'extension_td_history_cross','extension_td_history_applicability','extension_td_history_tree','message_preview','message_reply','message_draft',
            'manager_provider_requests', 'manager_provider_requests_create','manager_provider_requests_created','price_add_new','finance_user',
            'manager_provider_requests_edit_input_info','manager_calculation','manager_product_params','manager_product_info','manager_product_image',
            'finance_reestr_provider_rko','finance_reestr_provider_bv','finance_bill_provider_edit','finance_bill_provider_edit',
            'manager_report_provider','manager_report_manager','manager_report_office','export_xml','manager_finance_add','novaposhta_list',
            'finance_reestr_provider_pko','finance_reestr_provider_pko_edit','finance_reestr_pko','finance_reestr_pko_edit','finance_reestr_bv','finance_reestr_bv_edit',
            'payment_report_add', 'payment_report_edit', 'finance_bill_add', 'cart_package_edit', 'user_change_password', 'price_profile_edit',
            'own_auto_edit','novaposhta','manager_package_list_work','manager_send_sms','catalog_manager_cat_info_import','finance_correct_balance',
            'finance_reestr_rko','finance_reestr_rko_edit','finance_customer','finance_provider','finance_profit','manager_group_provider',
            'manager_get_blacklist','manager_popular_products','call_me_show_manager','manager_finance','manager_order_report','cart_payment_end',
            'manager_customer_edit','garage_manager','own_auto_add','price_queue_edit','manager_package_status_edit','finance_bill_delete','manager_invoice_customer_create','message_delete','manager_cart_delete',
            'manager_finance_schet','manager_export_products','manager_provider_requests_edit','manager_control','manager_report_model','export_avtopro', 'related_products','related_products_add','related_products_import','related_products_edit'
        );
        if(Auth::$aUser['id'] && !(Content::IsChangeableLogin(Auth::$aUser['login']))) {
            $aDashboardAction[]='vin_request';
            $aDashboardAction[]='vin_request_add';
            $aDashboardAction[]='vin_request_copy';
            $aDashboardAction[]='vin_request_delete';
           
        }

        // добавление акшенов из сторонних модулей
        if (Base::$aData['global_class']) {
            $oClass = new Base::$aData['global_class'];
            if (method_exists($oClass,'Dashboard')) {
                $aDashboardAction = array_merge($aDashboardAction, $oClass->Dashboard());
            }
        }
         
        return in_array($sAction, $aDashboardAction);
    }
    //-----------------------------------------------------------------------------------------------
    public function setTableDefault(&$oTable) {
    	// old style table
    	if(strpos(Base::$aRequest['action'], 'manager_panel')!==false) {
    		$oTable->sTemplateName='manager_panel/index.tpl';
    		return;
    	}

        if(strpos($_SERVER['REQUEST_URI'], 'mpanel')===false) {
            $oTable->sCheckAllClass='js-checkbox';
            $oTable->bDefaultChecked = false;
            
            if($oTable->sClass!='at-table-striped') {
                $oTable->sClass='at-tab-table';
            }
            
            if($oTable->sTemplateName=='table/index2.tpl') {
                $oTable->sTemplateName='index.tpl';
            } elseif($oTable->sTemplateName=='table/table_analogs.tpl' || $oTable->sTemplateName=='table/table_list.tpl' || $oTable->sTemplateName=='table/table_thumb.tpl' || $oTable->sTemplateName=='cart/table_popup.tpl' 
            		|| $oTable->sTemplateName=='catalog/search_table.tpl' || $oTable->sTemplateName=='news/table_template.tpl') {
                //do nothing 
            } else {
                $oTable->sTemplateName='table/index.tpl';
                $oTable->bHeaderVisible=false;
            }
            
            
            if(Content::isMobile() && Base::$aRequest['action']!='catalog_model_for') {
                $oTable->iRowPerPage=10;
                $oTable->bStepperVisible=true;
            }
        }
    }
    //-----------------------------------------------------------------------------------------------
    public function setFormDefault(&$oForm) {
        if(strpos($_SERVER['REQUEST_URI'], 'mpanel')===false) {
            $oForm->sReturnButtonClass='at-btn';
            $oForm->sSubmitButtonClass='at-btn';
            
            if($oForm->sGenerateTpl=='form/index_search.tpl') {
                $oForm->sTemplatePath='form/main_search.tpl';
            } elseif (in_array(Base::$aRequest['action'], array('cart_onepage_order_manager','cart_onepage_order'))) {
                $oForm->sTemplatePath='form/main_cart.tpl';
            } elseif ($oForm->sTemplatePath=='form/main_reg.tpl') {
                $oForm->sTemplatePath='form/main_reg.tpl';
            } else {
                $oForm->sTemplatePath='form/main.tpl';
            }
        }
    }
    //-----------------------------------------------------------------------------------------------
    public function isMobile() {
        $useragent=$_SERVER['HTTP_USER_AGENT'];
        return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));
    }
    //-----------------------------------------------------------------------------------------------
    public function printPage($countPage, $actPage)
    {
        if(!Content::isMobile()) {
            $iAddPages=3;
        } else {
            $iAddPages=1;
        }
        //если страниц 0 или 1, вернём пустой массив (переключатели не выводятся)
        if ($countPage == 0) return array();
        if ($countPage > 10) //если страниц больше 10, заполним массив pageArray переключателями в зависимости от активной страницы
        {
            //если активная страница - одна из первых  или одна из последних страниц
            //то запишем в массив первые 5 и последние 5 переключателей, разделив их многоточием
            if($actPage <= $iAddPages || $actPage + $iAddPages >= $countPage)
            {
                if(!Content::isMobile()) {
                    for($i = 0; $i <= 4; $i++) {
                        $pageArray[$i] = $i;
                    }
                    $pageArray[$i] = "...";
                    for($j = $i+1, $k = 4; $j <= 10; $j++, $k--) {
                        $pageArray[$j] = $countPage - $k;
                    }
                } else {
                    for($i = 0; $i <= 1; $i++) {
                        $pageArray[$i] = $i;
                    }
                    $pageArray[$i] = "...";
                    for($j = $i+1, $k = 9; $j <= 10; $j++, $k--) {
                        $pageArray[$j] = $countPage - $k;
                    }
                }
            }  else {
                //в противном случае в массив запишем первые и последние две страницы
                //а посередине - пять страниц, с обоих сторон обрамлённых многоточием.
                //активная страница, таким образом, окажется в центре переключателей.
                if(!Content::isMobile()) {
                    $pageArray[0] = 0;
                    $pageArray[1] = 1;
                    $pageArray[2] = "...";
                    $pageArray[3] = $actPage - 2;
                    $pageArray[4] = $actPage - 1;
                    $pageArray[5] = $actPage;
                    $pageArray[6] = $actPage + 1;
                    $pageArray[7] = $actPage + 2;
                    $pageArray[8] = "...";
                    $pageArray[9] = $countPage - 1;
                    $pageArray[10] = $countPage;
                } else {
                    $pageArray[0] = 0;
                    $pageArray[1] = 1;
                    $pageArray[2] = "...";
                    $pageArray[3] = $actPage - 1;
                    $pageArray[4] = $actPage;
                    $pageArray[5] = $actPage + 1;
                    $pageArray[6] = "...";
                    $pageArray[7] = $countPage - 1;
                    $pageArray[8] = $countPage;
                }
            }
    } else {
        //если страниц меньше 10, просто заполним массив переключателей всеми номерами страниц подряд
        for($n = 0; $n < $countPage+1; $n++)
        {
            $pageArray[$n] = $n;
        }
    }
        return $pageArray;
    }
    //-----------------------------------------------------------------------------------------------
    public function ContentRedirect($sUrl,$is_check_lower=1)
    {
        if(strpos($_SERVER['REQUEST_URI'],'?gclid')!==false || strpos($_SERVER['REQUEST_URI'],'utm_')!==false){
            //do nothing
        } else {
            if (Language::getConstant('global:url_is_lower',0) == 1 && $is_check_lower)
                $sUrl = mb_strtolower($sUrl,'utf-8');
            
            if (Language::getConstant('global:url_is_not_last_slash',0) == 1) {
                if ($sUrl != "/" && mb_substr($sUrl, -1, 1, 'utf-8') == "/")
                    $sUrl = substr($sUrl, 0, -1);
            }
            
            Header ( "HTTP/1.1 301 Moved Permanently" );
            header ( 'Location: ' . $sUrl );
            die ();
        }
    }
    //-----------------------------------------------------------------------------------------------
    public static function CheckSeoUrl(&$sUrl) {
        if($sUrl{strlen($sUrl)-1}=='/'){
            $sUrl=substr($sUrl, 0, -1);
        }
        while (strpos($sUrl, "__")!==false)
            $sUrl=str_replace("__", "_", $sUrl);
        if(!Base::GetConstant('global:url_is_not_last_slash')) $sUrl.='/';
        if(Base::GetConstant('global:url_is_lower',0)) $sUrl=mb_strtolower($sUrl);
    }
    //-----------------------------------------------------------------------------------------------
	public function isGooglePageSpeed() {
        $sUserAgent=$_SERVER['HTTP_USER_AGENT'];
        
        if(stristr($sUserAgent, 'Chrome-Lighthouse') === FALSE) 
        	return false;

        return true;
    }
    //-----------------------------------------------------------------------------------------------
}
?>