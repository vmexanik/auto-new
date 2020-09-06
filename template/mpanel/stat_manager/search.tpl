<form id="filter_form" name="filter_form" class="form-inline" action="javascript:void(null)" onsubmit="submit_form(this)">

<div class="form-group">
 </div>
 <div class="form-group">
    			<label >Manager:</label>
    			<input type="text" class="form-control btn-sm" maxlength="20" value="{$aSearchData.manager_login|escape}" name="search[manager_login]">
 </div>
 <div class="form-group">    			
    			<label >Status:</label>
    			{html_options name="search[order_status]" options=$aOrderStatus selected=$iStatusSelected class="form-control btn-sm"}
 </div>
 <div class="form-group">    			
    			<label >Date from:
    			<input id="date_from" onclick="popUpCalendar(this, this, 'dd.mm.yyyy');" value="{if $aSearchData.date_from}{$aSearchData.date_from}{else}{$aSearchData.default_date_from}{/if}" readonly="" class="form-control btn-sm" name="search[date_from]">
 </div>
 <div class="form-group">    			
    			<label >Date To:
    			<input id="date_to" onclick="popUpCalendar(this, this, 'dd.mm.yyyy');" value="{if $aSearchData.date_to}{$aSearchData.date_to}{else}{$aSearchData.default_date_to}{/if}" readonly="" class="form-control btn-sm" name="search[date_to]">
 </div>
 <div class="form-group">

<input type="button" value="{$oLanguage->getDMessage('Clear')}"
	onclick="xajax_process_browse_url('?{$sSearchReturn|escape}')"
	class='btn btn-primary btn-sm'>
<input type="submit" value="Search" class='btn btn-primary btn-sm'>

<input type="hidden" name="action" value="{$sBaseAction}_search">
<input type="hidden" name="return" value="{$sSearchReturn|escape}">
</div>
</form>

<h3 style="padding:0 0 0 10px;">Total: <font color="red">$ {$dTotalSum}</font></h3>