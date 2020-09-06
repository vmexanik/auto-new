<form id="filter_form" name="filter_form" class="form-inline" action="javascript:void(null)" onsubmit="submit_form(this)">

<div class="form-group">
				<label >{$oLanguage->GetDmessage('user login')}:</label>
				<input type=text name=search[user_login]
					value="{$aSearch.user_login|escape}" maxlength=20
					class="form-control btn-sm">
 </div>
 <div class="form-group">
				<label >Date from:</label>
				<input id=date_from name=search[date_from] class="form-control btn-sm"
					readonly="readonly" value="{$aSearch.date_from|escape}"
					onclick="popUpCalendar(this, this, 'dd.mm.yyyy');">
 </div>
 <div class="form-group">					
				<label >Date To:</label>
				<input id=date_to name=search[date_to] class="form-control btn-sm"
					readonly="readonly" value="{$aSearch.date_to|escape}"
					onclick="popUpCalendar(this, this, 'dd.mm.yyyy');">
 </div>
 <div class="form-group">
				<label >Section:</label>
				<select name=search[section] class="form-control btn-sm">
					<option value="">All</option>
					{foreach from=$aSection item=aItem}
					<option value="{$aItem.section}"{if $aItem.section==$aSearch.section} selected{/if}>{$aItem.section}</option>
					{/foreach} 
 </div>
 <div class="form-group">

<input type=button class='btn btn-primary btn-sm' value="{$oLanguage->getDMessage('Clear')}"
	onclick="xajax_process_browse_url('?{$sSearchReturn|escape}')">
<input type=submit value='Search' class='btn btn-primary btn-sm'>

<input type=hidden name=action value={$sBaseAction}_search>
<input type=hidden name=return value="{$sSearchReturn|escape}">
</div>
</form>