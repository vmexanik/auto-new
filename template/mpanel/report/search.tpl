{$oLanguage->GetText('Top Report Conten1t')}

<form id="filter_form" name="filter_form" class="form-inline" action="javascript:void(null)" onsubmit="submit_form(this)">

<div class="form-group">
			<label >{$oLanguage->getDMessage('Report List')}:</label>
			
			{html_options name=search[filter_name] values=$aFilterName output=$aFilterName selected=$aSearch.filter_name class="form-control btn-sm"}
			
 </div>
 <div class="form-group">			
			<label >{$oLanguage->getDMessage('Date from')}:</label>
			<input id=date_from name=search[date_from] class="form-control btn-sm"
					readonly="readonly" value="{$aSearch.date_from|escape}"
					onclick="popUpCalendar(this, this, 'dd.mm.yyyy');">
 </div>
 <div class="form-group">					
			<label >{$oLanguage->getDMessage('Date To')}:</label>
			<input id=date_to name=search[date_to] class="form-control btn-sm"
					readonly="readonly" value="{$aSearch.date_to|escape}"
					onclick="popUpCalendar(this, this, 'dd.mm.yyyy');">
 </div>
 <div class="form-group">
 
			<label >{$oLanguage->getDMessage('Region')}:</label>
	<select name=search[id_partner_region] class="form-control btn-sm">
   		<option value=0>{$oLanguage->getDMessage("Other region")}</option>
		{foreach from=$aPartnerRegion item=aItem}
		<option value={$aItem.id}
			{if $aItem.id == $aSearch.id_partner_region} selected {/if}
				> {$aItem.name}</option>
		{/foreach}
	</select>
 </div>
 <div class="form-group">

<input type=button class='btn btn-primary btn-sm' value="{$oLanguage->getDMessage('Clear')}"
	onclick="xajax_process_browse_url('?{$sSearchReturn|escape}')">
<input type=submit value='{$oLanguage->GetDMessage('Create Report')}' class='btn btn-primary btn-sm'>

<input type=hidden name=action value={$sBaseAction}_search>
<input type=hidden name=return value="{$sSearchReturn|escape}">
</div>
</form>