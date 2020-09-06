 <div class="at-hWrapper">
       <div class="wrapper-cell">
           <div class="at-mainer">
               <div class="at-pages-menu js-menu-pages">
                   <div class="inner-pages">
                       <div class="mob-header-pages">
                           <span class="close" onclick="atTopMenuClose()"></span>
                       </div>
                       {foreach from=$aDropdownMenu item=aItem name=menu key=sKey}
					   	<a href="/pages/{$aItem.code}" >{$aItem.name}</a>
					   {/foreach}
                       <div class="at-docs-link">
	                       <select id="lang1" onchange="document.location=this.options[this.selectedIndex].value;"
	                       		style="-webkit-appearance: none;-moz-appearance: none;appearance: none;height: 30px;border: none;">
							   {if $smarty.request.locale=='ua'}
							   <option value="">{$oLanguage->GetMessage('UA')}</option>
							   <option id="multilanguage_option" value="{$sMultiUrl}">{$oLanguage->GetMessage('RU')}</option>
							   {else}
							   <option value="">{$oLanguage->GetMessage('RU')}</option>
							   <option id="multilanguage_option" value="{$sMultiUrlUa}">{$oLanguage->GetMessage('UA')}</option>
							   {/if}
						   </select>
                       </div>

                       {*<div class="mob-links">
                       {foreach from=$aDropdownMenu item=aItem name=menu key=sKey}
					   	<a href="/pages/{$aItem.code}" >{$aItem.name}</a>
					   {/foreach}
                       </div>*}
                   </div>
               </div>
           </div>

           <div class="at-mid-header">
               <div class="at-mainer">
                   <div class="mid-header-wrap">
                       <div class="mid-header-part logo-part">
                           {if $oLanguage->GetConstant('global:project_url')=='http://auto-carta.com.ua/'}
                            <div class="ar-logo">
					            <a href="/">АвтоКарта</a><br>
					            <span>Интернет-магазин автозапчастей</span>
					        </div>
					        {/if}
                            {if $oLanguage->GetConstant('global:project_url')!='http://auto-carta.com.ua/'}
                             <a class="at-logo-top" href="/">
                                <img src="{$oLanguage->GetConstant('logo','/image/logo-top.png')}" alt="{$oLanguage->getConstant('global:title')}" title="{$oLanguage->getConstant('global:title')}">
                             </a>
                            {/if} 
                       </div>

                       <div class="mid-header-part phones-part{if $aAuthUser.type_=='manager'}_not_style{/if}">
                       {if $aAuthUser.type_=='manager'}
                       {if $aAuthUser.type_ == 'manager' && $isAllowManagerChangePrice}
                       <div class="header-select">
                        	<form action="/">
	                        	<div class="user-select">
		                        	<div class="radio_user" style="padding-bottom: 4px;">
		                        		<input type="radio" name="type_price" class="js-radio" value="user" {if $aAuthUser.type_price == 'user'}checked{/if}>
		                        		{$oLanguage->getMessage("user")}:<br>
		                        	</div>
		                        	<label class="user_select">
		                        		{html_options name=data[id_type_price_user] options=$aNameManager selected=$aAuthUser.id_type_price_user id="select_name_user_id"}
		                        	</label>
	                        	</div>
	                        	
	                        	<div class="group-select">
		                        	<div class="radio_group" style="padding-bottom: 4px;">
		                        		<input type="radio" name="type_price" class="js-radio" value="group" {if $aAuthUser.type_price == 'group' || $aAuthUser.type_price == 'none'}checked{/if}>
		                        		{$oLanguage->getMessage("group user")}:<br>
		                        	</div>
		                        	
		                        	<label class="group_select">
										{if $aAuthUser.id_type_price_group!=0}
											{assign var='id_type_price_group' value=$aAuthUser.id_type_price_group}
		                        		{else}
		                        			{assign var='id_type_price_group' value=$oLanguage->getConstant('IdDefaultPriceGroupManager',1)}
		                        		{/if}                        		
		                        		{html_options name=data[id_type_price_group] id="select_group_user" options=$aCustomerGroup selected=$id_type_price_group} 
		                        	</label>
	                        	</div>
	                        	<input name="action" value="user_change_level_price" type="hidden">
	                        	<input name="uri" value="{$sURI}" type="hidden">
	                        	<input type="submit" value="{$oLanguage->getMessage('OK')}" class="at-btn" 
	                        	style="min-width: 45px !important; height: 40px!important;line-height: 40px;">
                        	</form>
                        	{literal}
								<script type="text/javascript">    
								    $(document).ready(function() {
								    	 $('#select_name_user_id').select2({
										    language: 'ru',
								    		    ajax: {
								    		      url: "/?action=manager_get_user_select",
								    		      dataType: 'json',
								    		      data: function (term, page) {
								    		        return {
								    		          data: term
								    		        };
								    		      },
								    		      processResults: function (data) {
								    		            return {
								    		                results: $.map(data, function (item) {
								    		                    return {
								    		                        text: item.name,
								    		                        id: item.id
								    		                    }
								    		                })
								    		            };
								    		        }
								    		    }
								    		  });
								    	 $('#select_group_user').select2();
								    });									
							    </script>
								{/literal}
								
								{if $smarty.request.action != ''}
									{literal}
									<style>
										.header-select .user-select{
											width:300px;
										}
										.header-select .group-select{
											width:300px;
										}
								    </style>
							     {/literal}
							     {/if}
                       </div>
                       {/if}
                       {else}
                           <div class="at-phones-top no-mob-phones">
                               <div class="inner-wrap">
                                   <div class="main-phone" onclick="
                                   $('.at-phones-top').toggleClass('active');
                                   $('.js-phones-drop, .js-phones-drop-mask').toggle();
                                   ">
                                       {$oLanguage->GetConstant('global:project_phone')}
                                       <i></i>
                                   </div>
									<a href="javascript:void(0);" onclick="popupOpen('.js-popup-call-block');">
										{$oLanguage->GetMessage('callme')}
									</a>
                                   <div class="phones-top-drop js-phones-drop">
                                       <div class="phone mts">{$oLanguage->GetMessage('phone1')}</div>
                                       <div class="phone kiyv">{$oLanguage->GetMessage('phone2')}</div>
                                       <div class="phone life">{$oLanguage->GetMessage('phone3')}</div>
                                   </div>
                               </div>

                           </div>
                       {/if}
                       </div>

                       <div class="mid-header-part user-part">
                           <div class="at-mob-toggle-menu">
                               <a href="javascript:void(0);" onclick="atTopMenuOpen();">
                                   <span></span>
                               </a>
                           </div>
                          {if $aAuthUser.type_!='manager'}
                           <div class="at-phones-top no-pk-phones">
	                              <div class="inner-wrap">
	                                  <div class="main-phone" onclick="
	                                  $('.at-phones-top').toggleClass('active');
	                                  $('.js-phones-drop, .js-phones-drop-mask').toggle();
	                                  ">
	                                      {*$oLanguage->GetConstant('global:project_phone')*}
	                                      <i></i>
	                                  </div>
	                                  <div class="phones-top-drop js-phones-drop">
		                                     <a href="javascript:void(0);" onclick="popupOpen('.js-popup-call-block');">
												{$oLanguage->GetMessage('callme')}
											</a>
	                                      <div class="phone mts">{$oLanguage->GetMessage('phone1')}</div>
	                                      <div class="phone kiyv">{$oLanguage->GetMessage('phone2')}</div>
	                                      <div class="phone life">{$oLanguage->GetMessage('phone3')}</div>
	                                  </div>
	                              </div>
	                          </div>
							{/if}
							{if $aAuthUser.id && !($oContent->IsChangeableLogin($aAuthUser.login)) }
                           <div class="at-auth-block loged">
								{if $aAuthUser.type_=='manager'}	
									<div class="callback">
										<span class="count-call">{$aTemplateNumber.resolved}</span>
									</div>
									<div class="neworder">
										<span class="count-order">{$iNotViewedOrders}</span>
									</div>
								{/if}	
                               <a href="javascript:void(0);" onclick="atCabinetMenuOpen();" >
                                   <span>{$oLanguage->GetMessage('Личный кабинет')}</span>
                               </a>

                               <div class="auth-menu js-auth-menu">
                                   <div class="menu-header">
                                       {$oLanguage->GetMessage('Личный кабинет')}

                                       <a class="close" href="javascript:void(0);" onclick="atCabinetMenuClose();"></a>
                                   </div>
                                   <table class="user-name">
                                       <tr>
                                           <td>
                                               <a href="/pages/{$aAuthUser.type_}_profile"><strong>{$aAuthUser.login}</strong></a>
                                           </td>
                                       </tr>
                                   </table>
                                   <ul class="list">
				                       {if $aAuthUser.type_=='manager'}
								  		 <li>
								  		 	<a href="/mpanel" target="_blank">Перeйти в MPanel</a>
								   	  	 </li>
								   	  {/if}
				                       {foreach from=$aAccountMenu item=aItem}
				                       <li>
                                           <a href="/pages/{if !$aItem.link}{$aItem.code}{else}{$aItem.code}{/if}">
                                           {$aItem.name}
											{if $aAuthUser.type_=='manager'}
												{if $aItem.code=="message"}{if $aTemplateNumber.message_number} <span class="count">{$aTemplateNumber.message_number}</span>{/if}{/if}
												{if $aItem.code=="payment_report_manager"}{if $aTemplateNumber.payment_report_id} <span class="count">{$aTemplateNumber.payment_report_id}</span>{/if}{/if}
												{if $aItem.code=="vin_request_manager"}{if $iNotViewedVins} <span class="count">{$iNotViewedVins}</span>{/if}{/if}
												{if $aItem.code=="manager_package_list"}{if $iNotViewedOrders} <span class="count">{$iNotViewedOrders}</span>{/if}{/if}
												{if $aItem.code=="call_me_show_manager"}{if $aTemplateNumber.resolved} <span class="count">{$aTemplateNumber.resolved}</span>{/if}{/if}
											{/if}
											{if $aAuthUser.type_=='customer'}
												{if $aItem.code=="payment_declaration"}{if $aTemplateNumber.payment_declaration_id} <span class="count">{$aTemplateNumber.payment_declaration_id}</span>{/if}{/if}
												{if $aItem.code=="message_change_current_folder"}{if $aTemplateNumber.message_number} <span class="count">{$aTemplateNumber.message_number}</span>{/if}{/if}
											{/if}
											</a>
										{/foreach}
                                       <li class="logout">
                                           <a href="/pages/user_logout">{$oLanguage->GetMessage('Выход')}</a>
                                       </li>
                                   </ul>

                                   <div class="manager">
                                       <a href="/?action=message_compose&compose_to={$aAuthUser.manager_login}">{$oLanguage->GetMessage('Your personal manager')}</a>
                                   </div>
                               </div>
                           </div>
							{else}
							<div class="at-auth-block" >
                               <a href="javascript:void(0);" onclick="popupOpen('.js-popup-auth-block');"><span>{$oLanguage->GetMessage('enter')}</span></a>
                           </div>
                           {/if}
                           <div class="at-basket-widget" id='cart_block'>
                               <a href="javascript:void(0);" onclick="xajax_process_browse_url('/?action=cart_show_popup_cart'); return false;">
                                   <span class="count {if $aTemplateNumber.cart_number<=0} empty{/if}" id="icart_id">{$aTemplateNumber.cart_number}</span>
                                   <span class="name">Корзина</span>
                               </a>
                           </div>
                           <div class="clear"></div>
                       </div>
                   </div>
				{if $oLanguage->GetConstant('global:additional_baner')}
					<div class="at-index-banner-wrap">
						{$oLanguage->GetText('additional_baner')}
					</div>
				{/if}
               </div>
           </div>

           <div class="clear"></div>
           
           
           <div class="at-post-header no-mob-phones">
               <div class="at-mainer">
                   
                   <div class="post-header">
                      
                       <div class="post-header-part part-button">
                           <a class="at-top-button" href="/pages/vin_request_add">{$oLanguage->GetMessage('VIN request')}</a>
                       </div>
                       <div class="post-header-part part-search" >
                           <div class="at-block-search">
                           <form class="at-search-from" action="/" onsubmit="document.location='/price/'+$('#js-search-code').val(); return false;">
                               <input name="code" type="text" id="js-search-code" placeholder="Введите номер запчасти" onclick="if (this.value=='{$oLanguage->GetMessage('default_code')}') this.value=''"
					value="{if $smarty.get.code}{$smarty.get.code}{else}{$oLanguage->GetMessage('default_code')}{/if}">
                               <input type="submit" value="Найти" style="background-image:none;width:60px;" onclick="document.location='/price/'+$('#js-search-code').val(); return false;">
							   <input name="action" value="catalog_price_view" type="hidden"/>
                               <i class="close" onclick="$('.js-block-search').hide();"></i>
                               </form>
                           </div>
                       </div>

                       <div class="post-header-part part-button">
                           <a class="at-top-button" href="/pages/price_search_log">{$oLanguage->GetMessage('You searched')}</a>
                       </div>
						
						<div class="post-header-part part-button button-document">
						{if $oLanguage->GetConstant('global:project_url')=='http://irbis.mstarproject.com'}
							{if $sLocale=='en'}
							{assign var='sHref' value='http://manual.mstarproject.com/index.php/Standard_manual_-_English_Version'}
							{else}
							{assign var='sHref' value='http://manual.mstarproject.com/index.php/Демо_сайт_автозапчастей_редизайн_-_Пакет_Стандарт'}
							{/if}
							<a href="{$sHref}" target="_blank" class="at-top-button">{$oLanguage->GetMessage('Read documentation')}</a>
						{/if}
						</div>
                   </div>
               </div>
           </div>
           
           <div class="at-post-header no-mob-phones"  style="background-color: #4394cc;" >
               <div class="at-mainer">
                   <div class="post-header">
                       
                       <div class="post-header-part part-nav">
                                                   <nav class="mi-nav">
                                <a href="javascript:void(0);" class="mi-mob-nav-toggle js-mob-nav-toggle">
                                    <span>Каталог товаров</span>
                                </a>

                                <div class="inner-nav">
                                    {foreach item=aBaseGroup from=$aGroups}
                                            {if $aBaseGroup.is_rubricator}
                                    <div class="nav-element">
                                        <div class="inner-element">
                                            <a href="/rubricator/{$aBaseGroup.url}">
                                                <span>{$aBaseGroup.name}</span>
                                            </a>
                                        </div>
                                          <div class="nav-drop">
                                            <ul>
                                           {foreach from=$aBaseGroup.childs item=aItem}
                                                <li>
                                                       <div class="name">
                                                           <a href="/rubricator/{$aItem.url|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$aItem.name}</a>
                                                       </div>
                                                      {foreach item=aItemChild from=$aItem.childs}
                                                        <div class="sub-item">
                                                           <a href="/rubricator/{$aItemChild.url|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$aItemChild.name}</a>
                                                       </div>
                                                      {/foreach}
                                                </li>
                                                {/foreach} 
                                            </ul>
                                            
                                        </div>
                                    </div>
                                    {else}
                                    <div class="nav-element">
                                        <div class="inner-element">
                                            <a href="/select/{$aBaseGroup.code_name}">
                                                <span>{$aBaseGroup.name}</span>
                                            </a>
                                        </div>
                                          <div class="nav-drop">
                                            <ul>
                                            {foreach from=$aBaseGroup.childs item=aItem}
                                                <li>
                                                    <div class="sub-goup">
	                                                        <div class="name">
	                                                            <a href="/select/{$aItem.code_name|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$aItem.name}</a>
	                                                        </div>
                                                        {foreach item=aItemChild from=$aItem.childs}
	                                                         <div class="sub-item">
	                                                            <a href="/select/{$aItemChild.code_name|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$aItemChild.name}</a>
	                                                        </div>
                                                        {/foreach}
                                                    </div>
                                                </li>
                                                {/foreach} 
                                            </ul>
                                        </div>
                                    </div>
                                  		{/if}  
                                    {/foreach}
                                </div>
                            </nav>
                        </div>
                   </div>
               </div>
           </div>
           
          {* <!-- old
           <div class="at-post-header">
               <div class="at-mainer">
                   <div class="post-header">
                       
                       <div class="post-header-part part-nav">
                            <div class="at-nav">
                                <a class="mob-toggle" href="javascript:void(0);" onclick="
                                    $('.at-nav .nav-drop').show();
                                    $('body').addClass('overscroll-stop');
                                ">
                                    <strong>{$oLanguage->GetMessage('Catalog')}<i></i></strong>
                                </a>
                                <a href="javascript:void(0);"><strong>{$oLanguage->GetMessage('Catalog')}<i></i></strong></a>

                                <div class="nav-drop">
                                    <div class="mob-head">
                                        <span class="close" onclick="
                                            $('.at-nav .nav-drop').hide();
                                    $('body').removeClass('overscroll-stop');
                                        "></span>
                                    </div>
                                    <div class="nav-drop-inner">
                                    
                                        <ul class="lvl1">
                                            
                                            {foreach item=aBaseGroup from=$aGroups}
                                            {if $aBaseGroup.is_rubricator}
                                            <li class="js-has-lvl2">
                                                <a href="/rubricator/{$aBaseGroup.url}">{$aBaseGroup.name}</a>
                                                <ul class="lvl2">
                                                    {foreach from=$aBaseGroup.childs item=aItem}
                                                    {if $aItem.childs}
                                                    <li class="js-has-lvl3">
                                                        <a href="javascript:void(0);">{$aItem.name}</a>

                                                        <ul class="lvl3">
                                                        {foreach item=aItemChild from=$aItem.childs}
                                                            <li><a href="/rubricator/{$aItemChild.url|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$aItemChild.name}</a></li>
                                                        {/foreach}
                                                        </ul>
                                                    </li>
                                                    {else}
                                                    <li>
                                                        <a href="/rubricator/{$aItem.url|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$aItem.name}</a>
                                                    </li>
                                                    {/if}
                                                    {/foreach}
                                                </ul>
                                            </li>
                                            {else}
                                            <li class="js-has-lvl2">
                                                <a href="/select/{$aBaseGroup.code_name}">{$aBaseGroup.name}</a>
                                                <ul class="lvl2">
                                                {foreach item=aItem from=$aBaseGroup.childs}
                                                {if $aItem.is_menu}
                                                    {if $aItem.childs}
                                                    <li class="js-has-lvl3">
                                                        <a href="/select/{$aItem.code_name|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$aItem.name}</a>

                                                        <ul class="lvl3">
                                                        {foreach item=aItemChild from=$aItem.childs}
	                                                    {if $aItemChild.is_menu}
                                                            <li><a href="/select/{$aItemChild.code_name|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$aItemChild.name}</a></li>
                                                        {/if}
	                                                    {/foreach}
                                                        </ul>
                                                    </li>
                                                    {else}
                                                    <li>
                                                        <a href="/select/{$aItem.code_name|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$aItem.name}</a>
                                                    </li>
                                                    {/if}
                                                {/if}
                                                {/foreach}
                                                </ul>
                                            </li>
                                            {/if}
                                            {/foreach}
                                            
                                        </ul>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                       <div class="post-header-part part-search">
                           <a href="javascript:void(0);" class="mob-block-search" onclick="
                               $('.js-block-search').show();
                           "></a>

                           <div class="at-block-search js-block-search">
                           <form class="at-search-from" action="/">
                               <input name="code" type="text" placeholder="Какую запчасть ищите?" onclick="if (this.value=='{$oLanguage->GetMessage('default_code')}') this.value=''"
					value="{if $smarty.get.code}{$smarty.get.code}{else}{$oLanguage->GetMessage('default_code')}{/if}">
                               <input type="submit" value="">
							   <input name="action" value="catalog_price_view" type="hidden"/>
                               <i class="close" onclick="$('.js-block-search').hide();"></i>
                               </form>
                           </div>
                       </div>

                       <div class="post-header-part part-button">
                           <a class="at-top-button" href="/pages/price_search_log">{$oLanguage->GetMessage('You searched')}</a>
                       </div>

                       <div class="post-header-part part-button">
                           <a class="at-top-button" href="/pages/vin_request_add">{$oLanguage->GetMessage('VIN request')}</a>
                       </div>
                   </div>
               </div>
           </div>
           -->*}
           <!-- PANEL FOR MOBILE -->
              <div class="at-post-header no-pk-phones" >
               <div class="at-mainer">
                   <div class="post-header">
                       

                           <div class="at-block-search {*js-block-search*}" style="display:block;position: inherit;">
                           <form class="at-search-from" action="/" onsubmit="document.location='/price/'+$('#js-search-code').val(); return false;">
                               <input name="code" type="text" id="js-search-code-mobile" placeholder="Введите номер запчасти" onclick="if (this.value=='{$oLanguage->GetMessage('default_code')}') this.value=''"
					value="{if $smarty.get.code}{$smarty.get.code}{*else}{$oLanguage->GetMessage('default_code')*}{/if}">
                               <input type="submit" value="" onclick="document.location='/price/'+$('#js-search-code-mobile').val(); return false;">
							   <input name="action" value="catalog_price_view" type="hidden"/>
                               {*<i class="close" onclick="$('.js-block-search').hide();"></i>*}
                               </form>
                           </div>
                       
                            <div class="at-nav">
                                <a class="mob-toggle" href="javascript:void(0);" onclick="
                                    $('.at-nav .nav-drop').show();
                                    $('body').addClass('overscroll-stop');
                                ">
                                    <strong>{$oLanguage->GetMessage('Catalog')}<i></i></strong>
                                </a>
                                <a href="javascript:void(0);"><strong>{$oLanguage->GetMessage('Catalog')}<i></i></strong></a>

                                <div class="nav-drop">
                                    <div class="mob-head">
                                        <span class="close" onclick="
                                            $('.at-nav .nav-drop').hide();
                                    $('body').removeClass('overscroll-stop');
                                        "></span>
                                    </div>
                                    <div class="nav-drop-inner">
                                    
                                        <ul class="lvl1">
                                            
                                            {foreach item=aBaseGroup from=$aGroups}
                                            {if $aBaseGroup.is_rubricator}
                                            <li class="js-has-lvl2">
                                                <a href="/rubricator/{$aBaseGroup.url}">{$aBaseGroup.name}</a>
                                                <ul class="lvl2">
                                                    {foreach from=$aBaseGroup.childs item=aItem}
                                                    {if $aItem.childs}
                                                    <li class="js-has-lvl3">
                                                        <a href="javascript:void(0);">{$aItem.name}</a>

                                                        <ul class="lvl3">
                                                        {foreach item=aItemChild from=$aItem.childs}
                                                            <li><a href="/rubricator/{$aItemChild.url|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$aItemChild.name}</a></li>
                                                        {/foreach}
                                                        </ul>
                                                    </li>
                                                    {else}
                                                    <li>
                                                        <a href="/rubricator/{$aItem.url|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$aItem.name}</a>
                                                    </li>
                                                    {/if}
                                                    {/foreach}
                                                </ul>
                                            </li>
                                            {else}
                                            <li class="js-has-lvl2">
                                                <a href="/select/{$aBaseGroup.code_name}">{$aBaseGroup.name}</a>
                                                <ul class="lvl2">
                                                {foreach item=aItem from=$aBaseGroup.childs}
                                                {if $aItem.is_menu}
                                                    {if $aItem.childs}
                                                    <li class="js-has-lvl3">
                                                        <a href="/select/{$aItem.code_name|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$aItem.name}</a>

                                                        <ul class="lvl3">
                                                        {foreach item=aItemChild from=$aItem.childs}
	                                                    {if $aItemChild.is_menu}
                                                            <li><a href="/select/{$aItemChild.code_name|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$aItemChild.name}</a></li>
                                                        {/if}
	                                                    {/foreach}
                                                        </ul>
                                                    </li>
                                                    {else}
                                                    <li>
                                                        <a href="/select/{$aItem.code_name|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$aItem.name}</a>
                                                    </li>
                                                    {/if}
                                                {/if}
                                                {/foreach}
                                                </ul>
                                            </li>
                                            {/if}
                                            {/foreach}
                                            
                                        </ul>
                                        
                                    </div>
                            </div>
                        </div>
                       


                       {*<div class="post-header-part part-button">
                           <a class="at-top-button" href="/pages/price_search_log">{$oLanguage->GetMessage('You searched')}</a>
                       </div>

                       <div class="post-header-part part-button">
                           <a class="at-top-button" href="/pages/vin_request_add">{$oLanguage->GetMessage('VIN request')}</a>
                       </div>*}
                   </div>
               </div>
           </div>
           
           <!-- PANEL FOR MOBILE END-->
           
           
       </div>
   </div>
