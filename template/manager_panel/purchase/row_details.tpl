{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}
<td nowrap>
{*
	<span class="glyphicon glyphicon-file" aria-hidden="true" style="font-size:14px;cursor:pointer"
		onclick="xajax_process_browse_url('/?action=manager_panel_manager_package_list_view&id={$aRow.id}&return={$sReturn|escape:"url"}'); return false;">
	</span>
	<a href="/?action=manager_panel_print_order&id={$aRow.id}&return={$sReturn}" onclick="xajax_process_browse_url(this.href); return false;">
	<img src="/image/fileprint.png" border="0" width="16" align="absmiddle" hspace="1" style="padding-bottom:3px;">
	</a>
*}
</td>
{elseif $sKey=='id_cart_package'}
	<td style="white-space: nowrap;">
		#{$aRow.id_cart_package}
	</td>
{elseif $sKey=='cat_name'}
	<td>
		{if $aRow.cat_name_changed}
			{$aRow.cat_name_changed}
		{else}
			{$aRow.cat_name}
		{/if}
	</td>
{elseif $sKey=='code'}
	<td>
		{if $aRow.code_changed}
			{$aRow.code_changed}
		{else}
			{$aRow.code}
		{/if}
	</td>
{elseif $sKey=='provider'}
	<td>
		{$aRow.provider_name}
	</td>
{elseif $sKey=='term'}
	<td>
		{$aRow.term} {$oLanguage->getMessage('ds.')}
	</td>
{elseif $sKey=='profit'}
	<td> 
		<span id="id_profit_{$aRow.id}">{$aRow.profit}</span>
	</td>
{elseif $sKey=='order_status'}
<td style="white-space:nowrap;">
	{*if $aRow.order_status_select}
		{$aRow.order_status_select}
	{/if*}
    <select style="width:140px;" class="selectpicker" name="data[order_status]" id="os_{$aRow.id}"
    	onchange="xajax_process_browse_url('/?action=manager_panel_purchase_change_status&id={$aRow.id}&sel='+this.options[this.selectedIndex].value+'&return={$sReturn|escape:"url"}'); return false;">
    	{foreach key=sKey item=item from=$aPurchaseDetailOrderStatus}
    		{if ($item)}
    			{assign var='status' value='status_ps_'|cat:$item}
    		{else}
    			{assign var='status' value=''}
    		{/if}
    		<option value="{$item}" {if $item==$aRow.order_status}selected{/if}>{if $status}{$oLanguage->getMessage($status)}{/if}</option>
    	{/foreach}
  	</select>
</td>
{elseif $sKey=='incoming'}
<td>
<div class="container" style="width:234px;">
    <div class="row">
        <div class='col-sm-3' style="width:234px;">
            <div class="form-group">
                <div class='input-group date' id='datetimepicker_{$aRow.id}'>
                    <input type='text' class="form-control" value="{$aRow.post_date_incoming}"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar" onclick="InitCalendar({$aRow.id});return false;"></span>
                    </span>
                    <span class="glyphicon glyphicon-ok-circle" onclick="SaveCalendar({$aRow.id});return false;" 
                    	style="float:right;font-size:20px;padding: 0 0 7px 5px;cursor:pointer;" title="{$oLanguage->getMessage('save')}"></span>
                </div>
            </div>
        </div>
    </div>
</div>
</td>
{elseif $sKey=='kurs_currency'}
<td><div style="width:78px;">
		<span id="id_kurs_{$aRow.id}" style="cursor:pointer;" title="{$oLanguage->getMessage('change2')}"
			onclick="change_value({$aRow.id},'kurs');return false;">{$aRow.$sKey}</span>
		<span id="id_kurs_edit_{$aRow.id}" style="display:none;">
			<input id="id_kurs_value_{$aRow.id}" value="{$aRow.$sKey}" style="width:50px;color:black;">
			<span class="glyphicon glyphicon-ok-circle" onclick="change_value_apply({$aRow.id},'kurs');return false;" 
				style="float:right;font-size:20px;padding: 0 0 7px 5px;cursor:pointer;" title="{$oLanguage->getMessage('save')}"></span>
		</span>
	</div>
</td>
{elseif $sKey=='price_original'}
<td><div style="width:78px;">
		<span id="id_price_original_{$aRow.id}" style="cursor:pointer;" title="{$oLanguage->getMessage('change2')}"
			onclick="change_value({$aRow.id},'price_original');return false;">{$aRow.$sKey}</span>
		<span id="id_price_original_edit_{$aRow.id}" style="display:none;">
			<input id="id_price_original_value_{$aRow.id}" value="{$aRow.$sKey}" style="width:50px;color:black;">
			<span class="glyphicon glyphicon-ok-circle" onclick="change_value_apply({$aRow.id},'price_original');return false;" 
				style="float:right;font-size:20px;padding: 0 0 7px 5px;cursor:pointer;" title="{$oLanguage->getMessage('save')}"></span>
		</span>
	</div>
</td>
{else}<td>{$aRow.$sKey}</td>
{/if}
{/foreach}