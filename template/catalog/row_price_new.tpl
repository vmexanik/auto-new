<div class="at-list-element">
{if $aRow.separator}<h2 style="margin: 10px;">{$aRow.separator_header}</h2>
{else}
{assign var=iRowSpan value=$aRow.childs|@count}
{assign var=iRowSpan value=$iRowSpan+1}
                           <div class="element-part brand-part">
								{if $aRow.image_logo}<a class="image-brand"  href='#' rel="nofollow" 
									onclick="xajax_process_browse_url('/?action=catalog_view_brand&amp;pref={$aRow.pref}');$('#popup_id').show();returnfalse;">
									<imgsrc="{$aRow.image_logo}" alt="{$aRow.brand}" title="{$aRow.brand}" ></a>
									{else}
										<aclass="image-brand" href="#" rel="nofollow"
											onclick="xajax_process_browse_url('/?action=catalog_view_brand&amp;pref={$aRow.pref}');$('#popup_id').show();returnfalse;"
									><b>{$aRow.brand}</b></a>
									{/if}
								{if $aRow.pn!=""}
								<br><spanclass="hov" style="white-space:nowrap;">
								<a href="javascript: mt.ShowTr('{$aRow.pn}','{$aRow.iGrp}')" style="text-decoration:underline;"
								title="{$oLanguage->getMessage("ShowCross")}">{$oLanguage->GetMessage("PriceCross")}&nbsp;&gt;&gt;</a>
									<a href="javascript: mt.ShowTr('{$aRow.pn}','{$aRow.iGrp}')" title="{$oLanguage->getMessage("Show Cross")}">
									<imgsrc="/image/expandall.png" alt="" /></a>
									<a href="javascript: mt.HideTr('{$aRow.pn}')" title="{$oLanguage->getMessage("Hide Cross")}">
									<imgsrc="/image/collapseall.png" alt="" /></a>
									</span>
								{/if}
                               {*<div class="action-links">
                                   <div class="aligment">
                                       <a href="#" class="fav"></a>
                                       <a href="#" class="com"></a>
                                   </div>
                               </div>*}
                           </div>

                           <div class="element-part code-part">
                               {if $aRow.hide_code<>1 && $aRow.cat_name != ''}
									{if $oLanguage->getConstant('global:url_is_lower',0)}
										<a href="/buy/{$oContent->Translit($aRow.cat_name)|@lower}_{$aRow.code|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">
									{else}
										<a href="/buy/{$oContent->Translit($aRow.cat_name)}_{$aRow.code}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">
									{/if}
								{/if}
								{$aRow.code}
								{if $aRow.hide_code<>1 && $aRow.cat_name != ''}</a>{/if}
                           </div>

                           <div class="element-part photo-part">
                           {if $aRow.image}
                               <div class="photo">
                               	 <div class="photo-view">
                                       <i>
                                           <img src="{$aRow.image}"
			alt="{if $aRow.price_group_name}{$aRow.price_group_name} {/if}{if $aRow.name_translate}{$aRow.name_translate} {/if}{if $aRow.brand}{$aRowPrice.brand} {/if}{$oLanguage->GetMessage('art.')} {$aRow.code}"
			title="{if $aRow.price_group_name}{$aRow.price_group_name} {/if}{if $aRow.name_translate}{$aRow.name_translate} {/if}{if $aRow.brand}{$aRowPrice.brand} {/if}{$oLanguage->GetMessage('art.')} {$aRow.code}">
                                       </i>
                                   </div>
                               </div>
                            {else}
                                <div class="photo nophoto">
                               </div>
                            {/if}   
                           </div>

                           <div class="element-part name-part">
                               <a href="javascript:void(0)">{$aRow.name_translate}  <i>{$aRow.information}</i>
								<br>{if $aRow.store_number}<b>{$oLanguage->GetMessage('at store')}: {$aRow.store_number}</b>{/if}
								{if $aRow.store_reserve_number} <b>{$oLanguage->GetMessage('reserved')}: {$aRow.store_reserve_number}</b>{/if} </a>

								{if $aAuthUser.type_=='manager'}
								<spanstyle="color:red;">{$aRow.zzz_code}</span>
									<strong><a href="#" onmouseover="show_hide('history_{$aRow.id}','inline')" onmouseout="show_hide('history_{$aRow.id}','none')"
										onclick="returnfalse" style="color:gray;"><b>{$aRow.provider}</b></a></strong>
									<divstyle="display: none; " align=left class="tip_div" id="history_{$aRow.id}">
											<div>
											{$aRow.history}
											</div>
									</div>
								{/if}
                           </div>

                           <div class="element-part data-part">
                               <table class="at-list-data-table">
                                   <tr>
                                       <td class="amount-cell">
                                           <a href="#" class="at-link-dashed amount-link">
                                               {$aRow.stock} шт
                                               <span class="tip">Кол-во на складе</span>
                                           </a>
                                       </td>
                                       <td class="days-cell">
                                           <a class="at-link-dashed days-link" href="#">
                                               {$aRow.term} дн.
                                               <span class="tip">Дней на доставку</span>
                                           </a>
                                       </td>
                                       <td>
                                       <div class="price">
                                       {if $aRow.price>0}
											{if $aRow.user_view}{/if}{$oCurrency->PrintPrice($aRow.price)}{if $aRow.user_view}{/if}
											{if $aAuthUser.type_=='manager'}
											<br><spanstyle="color:grey;">{$oCurrency->PrintSymbol($aRow.price_original,$aRow.id_currency)}
											<br>margin_id:{$aRow.margin_id}</span>
											{/if}
										{else}---{/if}
										</div>
                                       </td>
                                       <td class="count-cell">
                                           <div class="count">
                                           {if $aRow.price>0}
											<inputtype=text name='n[{$aRow.code_provider}]' id='number_{$aRow.item_code}_{$aRow.id_provider}'
													value="{if $aRow.request_number}{$aRow.request_number}{else}1{/if}">
											<inputtype='hidden' name='r[{$aRow.code_provider}]' id='reference_{$aRow.item_code}_{$aRow.id_provider}' value=''>
											<div class="unit">шт</div>
											{else}---{/if}
                                           </div>
                                       </td>
                                       <td class="at-btn-cell">
                                       {if $aRow.price>0}
										{assign var='bAddCartVisible' value=true}
										
										{if $smarty.request.action=='catalog_part_opt'
										}
										
										{assign var='bAddCartVisible' value=false}
										{/if}
										
										{if $bAddCartVisible }
										<spanid='add_link_{$aRow.item_code}_{$aRow.id_provider}'>
										{include file="catalog/link_add_cart.tpl"}
										</span>
										{/if}
										{else}---{/if}
                                       </td>
                                   </tr>
                               </table>
                           </div>
                           
                           {/if}
                       </div>
                       

{*--------------------------------------------------------- childs ------------------------------------------------- *}
{if $aRow.childs}
{foreach from=$aRow.childs item=aItem}
<div class="at-list-element">
                           <div class="element-part brand-part">
								{if $aRow.image_logo}<a class="image-brand"  href='#' rel="nofollow" 
									onclick="xajax_process_browse_url('/?action=catalog_view_brand&amp;pref={$aRow.pref}');$('#popup_id').show();returnfalse;">
									<imgsrc="{$aRow.image_logo}" alt="{$aRow.brand}" title="{$aRow.brand}" ></a>
									{else}
										<aclass="image-brand" href="#" rel="nofollow"
											onclick="xajax_process_browse_url('/?action=catalog_view_brand&amp;pref={$aRow.pref}');$('#popup_id').show();returnfalse;"
									><b>{$aRow.brand}</b></a>
									{/if}
								{if $aRow.pn!=""}
								<br><spanclass="hov" style="white-space:nowrap;">
								<a href="javascript: mt.ShowTr('{$aRow.pn}','{$aRow.iGrp}')" style="text-decoration:underline;"
								title="{$oLanguage->getMessage("ShowCross")}">{$oLanguage->GetMessage("PriceCross")}&nbsp;&gt;&gt;</a>
									<a href="javascript: mt.ShowTr('{$aRow.pn}','{$aRow.iGrp}')" title="{$oLanguage->getMessage("Show Cross")}">
									<imgsrc="/image/expandall.png" alt="" /></a>
									<a href="javascript: mt.HideTr('{$aRow.pn}')" title="{$oLanguage->getMessage("Hide Cross")}">
									<imgsrc="/image/collapseall.png" alt="" /></a>
									</span>
								{/if}
                               {*<div class="action-links">
                                   <div class="aligment">
                                       <a href="#" class="fav"></a>
                                       <a href="#" class="com"></a>
                                   </div>
                               </div>*}
                           </div>

                           <div class="element-part code-part">
                               {if $aRow.hide_code<>1 && $aRow.cat_name != ''}
									{if $oLanguage->getConstant('global:url_is_lower',0)}
										<a href="/buy/{$oContent->Translit($aRow.cat_name)|@lower}_{$aRow.code|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">
									{else}
										<a href="/buy/{$oContent->Translit($aRow.cat_name)}_{$aRow.code}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">
									{/if}
								{/if}
								{$aRow.code}
								{if $aRow.hide_code<>1 && $aRow.cat_name != ''}</a>{/if}
                           </div>

                           <div class="element-part photo-part">
                           {if $aRow.image}
                               <div class="photo">
                               	 <div class="photo-view">
                                       <i>
                                           <img src="{$aRow.image}"
			alt="{if $aRow.price_group_name}{$aRow.price_group_name} {/if}{if $aRow.name_translate}{$aRow.name_translate} {/if}{if $aRow.brand}{$aRowPrice.brand} {/if}{$oLanguage->GetMessage('art.')} {$aRow.code}"
			title="{if $aRow.price_group_name}{$aRow.price_group_name} {/if}{if $aRow.name_translate}{$aRow.name_translate} {/if}{if $aRow.brand}{$aRowPrice.brand} {/if}{$oLanguage->GetMessage('art.')} {$aRow.code}">
                                       </i>
                                   </div>
                               </div>
                            {else}
                                <div class="photo nophoto">
                               </div>
                            {/if}   
                           </div>

                           <div class="element-part name-part">
                               <a href="javascript:void(0)">{$aItem.name_translate}  <i>{$aItem.information}</i>
								<br>{if $aItem.store_number}<b>{$oLanguage->GetMessage('at store')}: {$aItem.store_number}</b>{/if}
								{if $aItem.store_reserve_number} <b>{$oLanguage->GetMessage('reserved')}: {$aItem.store_reserve_number}</b>{/if} </a>

								{if $aAuthUser.type_=='manager'}
								<spanstyle="color:red;">{$aItem.zzz_code}</span>
									<strong><a href="#" onmouseover="show_hide('history_{$aItem.id}','inline')" onmouseout="show_hide('history_{$aItem.id}','none')"
										onclick="returnfalse" style="color:gray;"><b>{$aItem.provider}</b></a></strong>
									<divstyle="display: none; " align=left class="tip_div" id="history_{$aItem.id}">
											<div>
											{$aItem.history}
											</div>
									</div>
								{/if}
                           </div>

                           <div class="element-part data-part">
                               <table class="at-list-data-table">
                                   <tr>
                                       <td class="amount-cell">
                                           <a href="#" class="at-link-dashed amount-link">
                                               {$aItem.stock} шт
                                               <span class="tip">Кол-во на складе</span>
                                           </a>
                                       </td>
                                       <td class="days-cell">
                                           <a class="at-link-dashed days-link" href="#">
                                               {$aItem.term} дн.
                                               <span class="tip">Дней на доставку</span>
                                           </a>
                                       </td>
                                       <td>
                                       <div class="price">
                                       {if $aItem.price>0}
											{if $aItem.user_view}{/if}{$oCurrency->PrintPrice($aItem.price)}{if $aItem.user_view}{/if}
											{if $aAuthUser.type_=='manager'}
											<br><spanstyle="color:grey;">{$oCurrency->PrintSymbol($aItem.price_original,$aItem.id_currency)}
											<br>margin_id:{$aItem.margin_id}</span>
											{/if}
										{else}---{/if}
										</div>
                                       </td>
                                       <td class="count-cell">
                                           <div class="count">
                                           {if $aItem.price>0}
											<inputtype=text name='n[{$aItem.code_provider}]' id='number_{$aItem.item_code}_{$aItem.id_provider}'
													value="{if $aItem.request_number}{$aItem.request_number}{else}1{/if}">
											<inputtype='hidden' name='r[{$aItem.code_provider}]' id='reference_{$aItem.item_code}_{$aItem.id_provider}' value=''>
											<div class="unit">шт</div>
											{else}---{/if}
                                           </div>
                                       </td>
                                       <td class="at-btn-cell">
                                       {if $aItem.price>0}
										{assign var='bAddCartVisible' value=true}
										
										{if $smarty.request.action=='catalog_part_opt'
										}
										
										{assign var='bAddCartVisible' value=false}
										{/if}
										
										{if $bAddCartVisible }
										<spanid='add_link_{$aItem.item_code}_{$aItem.id_provider}'>
										{include file="catalog/link_add_cart.tpl"}
										</span>
										{/if}
										{else}---{/if}
                                       </td>
                                   </tr>
                               </table>
                           </div>
                       </div>


{/foreach}
{/if}
{*------------------------------------------------------- childs end ----------------------------------------------- *}
