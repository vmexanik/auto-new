<div style=" background-color: #ffffff; box-shadow: 0 0 10px #cadae2; margin: 0 0 20px 0;">
<script type="text/javascript" src="/js/vin_request.js?845"></script>
<br>
<br>

{if $smarty.request.form_message}<div class=error_message>{$smarty.request.form_message}</div>{/if}

<table width="99%" cellspacing=0 cellpadding=5>
<tr>
	<th width=6%><nobr>{$oLanguage->getMessage("#")}</th>
	<th width=5%><nobr>{$oLanguage->getMessage("Visible")}</th>
	<th width=20%><nobr>{$oLanguage->getMessage("Name")}</th>
	<th width=30%><nobr>{$oLanguage->getMessage("Code")}</th>
	{*<th><nobr>{$oLanguage->getMessage("UserInputCode")}</th>*}
	<th width=8%><nobr>{$oLanguage->getMessage("Number")}</th>
	<th width=10%><nobr>{$oLanguage->getMessage("Weight")}</th>
	<th><nobr>{$oLanguage->getMessage("Price")}</th>
</tr>
{foreach item=aPart from=$aPartList}
<tr class="{cycle values="even,none"}">
	<td style="padding: 8px 2px 8px 20px;" >{$aPart.i} <input class="js-checkbox" type=checkbox name="part[{$aPart.i}][i]" value='1'
		{if $aPart.i_visible}checked{/if}></td>
	<td style="padding: 8px 2px 8px 0px;"align=center><input class="js-checkbox" type=checkbox name="part[{$aPart.i}][code_visible]" value='1'
		{if $aPart.code_visible}checked{/if}></td>
	<td style="padding: 8px 2px 8px 20px;"><input type=text name="part[{$aPart.i}][name]" value="{$aPart.name}" style="width:250px;"></td>
	<td style="padding: 8px 2px 8px 20px;"><input type=text name="part[{$aPart.i}][code]" value="{$aPart.code}">
	    <input type=hidden name="part[{$aPart.i}][user_input_code]" value="{$aPart.user_input_code}">
	</td>
	{*<td><input type=text name="part[{$aPart.i}][user_input_code]" value="{$aPart.user_input_code}"></td>*}
	<td style="padding: 8px 2px 8px 20px;"><input type=text name="part[{$aPart.i}][number]" value="{$aPart.number}" style="width:55px;"></td>
	<td style="padding: 8px 2px 8px 20px;"><input type=text name="part[{$aPart.i}][weight]" value="{$aPart.weight}" style="width:50px;"
		> {$oLanguage->GetMessage('kg')}</td>
	<td align=center style="color: #da5931; padding: 8px 2px 8px 20px;">{if $aPart.price}{$oCurrency->PrintPrice($aPart.price)}{/if}</td>
</tr>
{/foreach}
</table>

<input type="hidden" name="RowCount" value="{$iRowCount}">

<table width="99%" cellspacing=0 cellpadding=7  id="queryByVIN">
    <tbody>
<tr class="even">
	<td style="padding: 8px 2px 8px 20px;" width=6%></td>
	<td style="padding: 8px 26px 8px 30px;" align='center' width=5%></td>
	<td style="padding: 8px 2px 8px 20px;" width=20%></td>
	<td style="padding: 8px 2px 8px 20px;" width=30%></td>
	<td style="padding: 8px 2px 8px 20px;" width=8%></td>
	<td style="padding: 8px 2px 8px 20px;" width=10%></td>
	<td style="padding: 8px 2px 8px 20px;"></td>
</tr>
    </tbody>
</table>

<div style="padding:5px 0 0 0;">
<input type="button" class='at-btn'  value="{$oLanguage->getMessage("Add line")}" onclick="javascript:mvr.AddManagerRow(this.form);"
		/>&nbsp;&nbsp;
</div>


<div style="padding:5px 0 0 0;">
<input type=button class='at-btn' style="min-width: 185px;" value="{$oLanguage->getMessage(" << Return")}"
		onclick="location.href='./?{if $smarty.request.return}{$smarty.request.return}{else}action=vin_request_manager{/if}'" >
<input type=button class='at-btn' style="min-width: 190px;" value="{$oLanguage->getMessage("Save")}"
	onclick="this.form.elements['action'].value='vin_request_manager_save'; this.form.submit();">

<input type=button class='at-btn' style="min-width: 190px;" value="{$oLanguage->getMessage("Send to customer")}"
	onclick="
this.form.elements['section'].value='customer';
this.form.elements['action'].value='vin_request_manager_send'; this.form.submit();">

<input type=button class='at-btn' style="min-width: 190px; color: red;" value="{$oLanguage->getMessage("Refuse Request")}" 
	onclick="if (confirm('{$oLanguage->getMessage("Are you sure to refuse?")}')) {ldelim}
		this.form.elements['action'].value='vin_request_manager_refuse'; this.form.submit();{rdelim}">


<input type=button class='at-btn' style="min-width: 190px;" value="{$oLanguage->getMessage("Send preview to customer")}"
	onclick="
this.form.elements['section'].value='customer';
this.form.elements['action'].value='vin_request_manager_send_preview'; this.form.submit();">



<input type=hidden name=action value=''>
<input type=hidden name=section value='cart'>
<input type=hidden name=is_post value='1'>

</div>



{*if $smarty.get.id==$aAuthUser.id_vin_request_fixed}
<br>
<div align=center>
<input type=button class='at-btn' value="{$oLanguage->getMessage("Refuse for")}"
	onclick="
this.form.elements['section'].value='customer';
this.form.elements['action'].value='vin_request_manager_refuse_for'; this.form.submit();">
<select name="data[refuse_for]">
{html_options options=$aManagerLogin}
</select>

</div>
{/if*}

</FORM>
</div>