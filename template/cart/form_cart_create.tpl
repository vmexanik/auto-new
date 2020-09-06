<table style="width:1000px;border-spacing:0;padding:0px;" class="datatable">
	<tr>
	{*<th style="text-align: center;">{$oLanguage->getMessage("id_price")}</th>*}
	<th style="text-align: center;">{$oLanguage->getMessage("Brand")}</th>
	<th style="text-align: center;">{$oLanguage->getMessage("Code")} {$sZir}</th>	
	<th style="text-align: center;">{$oLanguage->getMessage("Name")}</th>
	<th style="text-align: center;">{$oLanguage->getMessage("Provider1")}</th>
	<th style="text-align: center;">{$oLanguage->getMessage("Term")}</th>
	<th style="text-align: center;">{$oLanguage->getMessage("Number")},шт. {$sZir}</th>
	<th style="text-align: center;">{$oLanguage->getMessage("Price")},грн {$sZir}</th>
	<th style="text-align: center;">{$oLanguage->getMessage("Total")},грн</th>
	{* <th>&nbsp;</th> *}
	</tr>
	<tr>
		<td>
			{* <input type=text name=data[brand] value='{$aData.brand}' style="width: 80px"> *}
			{html_options name=data[pref] options=$aPref selected=$aData.pref style='width:100px'}
		</td>
		<td>
			<input type=text name=data[code] value='{$aData.code}' style="width: 70px !important" >
		</td>
		<td>
			<input type=text name=data[name] value='{$aData.name}' style="width: 180px !important" >
		</td>
		<td>
			{html_options name=data[id_provider] options=$aProvider selected=$aData.id_provider style='width:100px'}
			{*<input type=text name=data[provider_name1] value='{$aData.provider_name1}' style="width: 80px" >*}
		</td>
		<td>
			<input type=text name=data[term] value='{$aData.term}' style="width: 30px !important">
		</td>
		<td>
			<input id=number type=text name=data[number] value='{if $aData.number}{$aData.number}{else}1{/if}' style="width: 30px !important" onkeyup="GetTotal();" onkeypress='validate(event,false)'> 
		</td>
		<td style="text-align: right;">
		    закупка <input id=price_original type=text name=data[price_original] value='{$aData.price_original}' style="width: 40px !important" onkeyup="" onkeypress='validate(event,true)'><br>
			продажа <input id=price type=text name=data[price] value='{$aData.price}' style="width: 40px !important" onkeyup="GetTotal();" onkeypress='validate(event,true)'>
		</td>
		<td>
			<input id=total type=text name=data[total] value='{$aData.total}' style="width: 40px !important" readonly>
		</td>
	</tr>

</table>
{literal}
<style type="text/css">
	.form {
		border: 1px solid white;
	    position: relative;
	    margin-bottom: 1px;
	    background: white;
	}

</style>
<script type="text/javascript">
	function GetTotal() {
		var number = document.getElementById('number').value;
		var price = document.getElementById('price').value;
		if(price) var total = number * price;
		else var total = 0;
		document.getElementById('total').value = total;
	}
	function validate(evt,dot) {
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		key = String.fromCharCode( key );
		if(dot) var regex = /[0-9]|\./;
		else var regex = /[1-9]/;
		if( !regex.test(key) ) {
		theEvent.returnValue = false;
		if(theEvent.preventDefault) theEvent.preventDefault();
	}
}
</script>
{/literal}