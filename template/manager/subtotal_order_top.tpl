<tr class="filter">
    <th class="sep" align="center" width="2%"><a href="/?action=manager_order"><img src="/image/design/cancel.png" 
alt="{$oLanguage->getMessage("cancel")}" title="{$oLanguage->getMessage("cancel")}"/></a>
    <script language="javascript" type="text/javascript" src="/js/form.js?588"></script>
    </th>
{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}
    <th>
    {*<a href="/?action=manager_order"><img src="/image/design/right.png"></a>*} 
    {*<input type="button" class="at-btn" value="ok" style="height:22px">*}
    <a id="sLink" href="/?action=manager_order"
				onclick="{strip}
				this.href+='&search[id_cart_package]='+$('#id_cart_package').val()+'
				&search[pref]='+$('#pref').val()+'
				&search[code]='+$('#code').val()+'
				&search[name]='+$('#name').val()+'
				&search[id_provider]='+$('#id_provider').val()+'
				&search[uc_name]='+$('#uc_name').val()+'
				&search[price_original]='+$('#price_original').val()+'
				&search[period]='+$('#period').val()+'
				&search_order_status='+$('#search_order_status').val()+'
				&search[price]='+$('#price').val()+'
				&search[price2]='+$('#price2').val()
				"{/strip}
    ><img src="/image/design/search.png" alt="{$oLanguage->getMessage("search")}" title="{$oLanguage->getMessage("search")}" /></a>
    </th>
{elseif $sKey=='id_cart_package'}
    <th class="sep"><input id="id_cart_package" type="text" name="search[id_cart_package]" value="{$smarty.request.search.id_cart_package}" 
					class="input_global" style="width: 30px;" onkeydown="if(mf.SetFocusOnEnter(event,'sLink')) return true;" />
    </th>
{elseif $sKey=='cat_name'}
    <th class="sep"><select id="pref" name='search[pref]' style='width:80px' class="select_global" onchange="mf.SetFocus('sLink')">
		{html_options options=$aPref selected=$smarty.request.search.pref}
		</select>
    </th>
{elseif $sKey=='code'}
    <th class="sep"><input id="code" type="text" name="search[code]" value="{$smarty.request.search.code}" class="input_global" onkeydown="if(mf.SetFocusOnEnter(event,'sLink')) return true;"  style='width:80px'/></th>
{elseif $sKey=='name'}
    <th class="sep"><input id="name" type="text" name="search[name]" value="{$smarty.request.search.name}" class="input_global" onkeydown="if(mf.SetFocusOnEnter(event,'sLink')) return true;"  style='width:100px'/></th>
{elseif $sKey=='provider'}
    <th class="sep"><select id="id_provider" name='search[id_provider]' style='width:80px' class="select_global" onchange="mf.SetFocus('sLink')">
	<option value='0'>{$oLanguage->getMessage("Show All")}</option>
	{foreach from=$aProvider item=aItem}
	<option value='{$aItem.id}' {if $smarty.request.search.id_provider==$aItem.id}selected{/if}
	>{if $aAuthUser.is_super_manager || $aAuthUser.is_sub_manager_partner}{$aItem.name}{else}{$aItem.code_name}{/if}</option>
	{/foreach}
	</select>
    </th>
{elseif $sKey=='user'}
    <th class="sep"><input id="uc_name" type="text" name="search[uc_name]" value="{$smarty.request.search.uc_name}" class="input_global" onkeydown="if(mf.SetFocusOnEnter(event,'sLink')) return true;"  style='width:80px'/></th>
{elseif $sKey=='cp_post_date_f'}
    <th class="sep" align="center">
    <script type="text/javascript">{literal} 
    $(function() { 
    	$('#period').datepicker({ 
			range: 'period', 
	    	dateFormat: 'dd.mm.yy',
			onSelect: function(dateText, inst, extensionRange) {
		      $('#period').val(extensionRange.startDateText+'-'+extensionRange.endDateText);
			}
    	});
    }); 
    {/literal} </script>
    <input id="period" type="text" name="search[period]" value="{$smarty.request.search.period}" class="input_global" onkeydown="if(mf.SetFocusOnEnter(event,'sLink')) return true;" style='width:62px' readonly />
    {*<a href="#"><img src="/image/design3/calendar.png" width="16" height="16" alt="" /></a>*}
    </th>
{elseif $sKey=='dayend'}
    <th class="sep"><input id="dayend" type="text" name="search[dayend]" value="{$smarty.request.search.dayend}" class="input_global" onkeydown="if(mf.SetFocusOnEnter(event,'sLink')) return true;" /></th>
{elseif $sKey=='price_original'}    
    <th class="sep"><input id="price_original" type="text" name="search[price_original]" value="{$smarty.request.search.price_original}" class="input_global" onkeydown="if(mf.SetFocusOnEnter(event,'sLink')) return true;" /></th>
{elseif $sKey=='price'}    
    <th class="sep" style="white-space:nowrap;">
	<input id="price" type="text" name="search[price]" value="{$smarty.request.search.price}" class="input_global" onkeydown="if(mf.SetFocusOnEnter(event,'sLink')) return true;" style='width:40px' /> - 
	<input id="price2" type="text" name="search[price2]" value="{$smarty.request.search.price2}" class="input_global" onkeydown="if(mf.SetFocusOnEnter(event,'sLink')) return true;" style='width:40px' /></th>
{elseif $sKey=='order_status'}    
    <th class="sep">
    	<select id="search_order_status" name='search_order_status' style='width:90px' class="select_global" onchange="mf.SetFocus('sLink')">
			<option value='notend' {if $smarty.request.search_order_status=='notend'}selected{/if}
				>{$oLanguage->getMessage("notend")}</option>
			<option value='0' {if $smarty.request.search_order_status=='0'}selected{/if}
			    >{$oLanguage->getMessage("All")}</option>
			<option value='new' {if $smarty.request.search_order_status=='new'}selected{/if}
				>{$oLanguage->getMessage("new")}</option>
			<option value='work' {if $smarty.request.search_order_status=='work'}selected{/if}
				>{$oLanguage->getMessage("work")}</option>
			<option value='confirmed' {if $smarty.request.search_order_status=='confirmed'}selected{/if}
				>{$oLanguage->getMessage("confirmed")}</option>
			<option value='road' {if $smarty.request.search_order_status=='road'}selected{/if}
				>{$oLanguage->getMessage("road")}</option>
			<option value='store' {if $smarty.request.search_order_status=='store'}selected{/if}
				>{$oLanguage->getMessage("store")}</option>
			<option value='end' {if $smarty.request.search_order_status=='end'}selected{/if}
				>{$oLanguage->getMessage("end")}</option>
			<option value='return_customer' {if $smarty.request.search_order_status=='return_customer'}selected{/if}
				>{$oLanguage->getMessage("return_customer")}</option>
			<option value='return_provider' {if $smarty.request.search_order_status=='return_provider'}selected{/if}
				>{$oLanguage->getMessage("return_provider")}</option>
			<option value='return_store' {if $smarty.request.search_order_status=='return_store'}selected{/if}
			>{$oLanguage->getMessage("return_store")}</option>
			{*<option value='reclamation' {if $smarty.request.search_order_status=='reclamation'}selected{/if}
				>{$oLanguage->getMessage("reclamation")}</option>*}
			<option value='refused' {if $smarty.request.search_order_status=='refused'}selected{/if}
				>{$oLanguage->getMessage("refused")}</option>
			<option value='pending' {if $smarty.request.search_order_status=='pending'}selected{/if}
				>{$oLanguage->getMessage("pending")}</option>
		</select>
    </th>
{else}<th>&nbsp;</th>
{/if}
{/foreach}    
</tr>