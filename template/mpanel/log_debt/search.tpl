<form id="filter_form" name="filter_form" class="form-inline" action="javascript:void(null)" onsubmit="submit_form(this)">

 <div class="form-group">
				<label >Customer:</label>
				<input type=text name=search[customer_login]
					value="{$aSearch.customer_login|escape}" maxlength=20
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
				<label >Custom ID:</label>
				<input type="text" maxlength="20" name="search[custom_id]
				value="{$aSearch.custom_id}" class="form-control btn-sm">
 </div>
 <div class="form-group">				
				<label >Description:</label>
				<td colspan=2><input type=text name=search[description]
					value="{$aSearch.description|escape}" maxlength=40
					class="form-control btn-sm">
 </div>
 <div class="form-group">

<input type=button class="btn btn-danger btn-sm" value="{$oLanguage->getDMessage('Clear')}"
	onclick="xajax_process_browse_url('?{$sSearchReturn|escape}')">
<input type=submit value='Search' class="btn btn-success btn-sm">

<input type=hidden name=action value={$sBaseAction}_search>
<input type=hidden name=return value="{$sSearchReturn|escape}">
</div>
</form>