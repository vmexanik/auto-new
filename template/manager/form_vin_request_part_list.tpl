<script type="text/javascript" src="/js/vin_request.js?844"></script>
<br>
<br>

<!--FORM method=post-->
{if $smarty.request.form_message}<div class=error_message>{$smarty.request.form_message}</div>{/if}
<table width="99%" cellspacing=0 cellpadding=5 class="datatable">
<tr>
	<th><nobr>{$oLanguage->getMessage("#")}</th>
	<!--th><nobr>{$oLanguage->getMessage("Marka")}</th-->
	<!--th><nobr>{$oLanguage->getMessage("Marka")}</th-->
	<th width=5><nobr>{$oLanguage->getMessage("Visible")}</th>
	<th width=20%><nobr>{$oLanguage->getMessage("Name")}</th>
	<th><nobr>{$oLanguage->getMessage("Code")}</th>
	<th><nobr>{$oLanguage->getMessage("UserInputCode")}</th>
	<th><nobr>{$oLanguage->getMessage("Number")}</th>
	<!--th><nobr>{$oLanguage->getMessage("Price")}</th>
	<th><nobr>{$oLanguage->getMessage("PriceOriginal")}</th>
	<th><nobr>{$oLanguage->getMessage("Term")}</th>
	<th width=5><nobr>{$oLanguage->getMessage("Provider")}</th-->
	<th><nobr>{$oLanguage->getMessage("Weight")}</th>
</tr>
{foreach item=aPart from=$aPartList}
<tr class="{cycle values="even,none"}">
	<td>{$aPart.i} <input type=checkbox name="part[{$aPart.i}][i]" value='1'
		{if $aPart.i_visible}checked{/if}></td>
	{*<td><input type=text name="part[{$aPart.i}][marka]" value="{$aPart.marka}" style="width:50px;"></td>
	<td>
	<select name="part[{$aPart.i}][cat_name]" id='cat_name_select_{$aPart.i}'>
	{foreach item=aItem from=$aCat}
		<option value="{$aItem.name}" {if $aItem.name==$aPart.cat_name}selected{/if}
			>{if $aAuthUser.is_super_manager || $aAuthUser.is_sub_manager_partner}{$aItem.name}{else}{$aItem.code_name}{/if}</option>
	{/foreach}
		</select>
	</td>*}
	<td align=center><input type=checkbox name="part[{$aPart.i}][code_visible]" value='1'
		{if $aPart.code_visible}checked{/if}></td>
	<td><input type=text name="part[{$aPart.i}][name]" value="{$aPart.name}" style="width:250px;"></td>
	<td><input type=text name="part[{$aPart.i}][code]" value="{$aPart.code}"></td>
	<td><input type=text name="part[{$aPart.i}][user_input_code]" value="{$aPart.user_input_code}"></td>
	<td><input type=text name="part[{$aPart.i}][number]" value="{$aPart.number}" style="width:50px;"></td>
	{*<td><b>$</b> <input type=text name="part[{$aPart.i}][price]" value="{$aPart.price}" style="width:50px;"></td>
	<td><b>$</b> <input type=text name="part[{$aPart.i}][price_original]" value="{$aPart.price_original}" style="width:50px;"></td>
	<td><input type=text name="part[{$aPart.i}][term]" value="{$aPart.term}" style="width:30px;"></td>
	<td><select name="part[{$aPart.i}][id_provider]" id='provider_select_{$aPart.i}'>
	{foreach item=aItem from=$aProvider}
		<option value="{$aItem.id_user}" {if $aItem.id_user==$aPart.id_provider}selected{/if}
			>{if $aAuthUser.is_super_manager || $aAuthUser.is_sub_manager_partner}{$aItem.name}{else}{$aItem.code_name}{/if}</option>
	{/foreach}
		</select>
	</td>*}
	<td><input type=text name="part[{$aPart.i}][weight]" value="{$aPart.weight}" style="width:30px;"
		> {$oLanguage->GetMessage('kg')}</td>
</tr>
{/foreach}
{*<tr class="even">
	<td colspan=5 align=right>{$oLanguage->getMessage('Subtotal')}:</td>
	<td><b>$ {$dSubtotal}</b></td>
	<td>&nbsp;</td>
</tr>*}
</table>
<input type="hidden" name="RowCount" value="{$iRowCount}">
<table style="width:99%;padding:5px;" class="datatable"  id="queryByVIN">
    <tbody>
<tr class="even">
	<td></td>
	<td></td>
	<td align=center></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
    </tbody>
</table>
<div align=center style="padding:5px 0 0 0;">
<input type="button" class='at-btn' value="{$oLanguage->getMessage("Add line")}" onclick="javascript:mvr.AddManagerRow(this.form);"
		/>&nbsp;&nbsp;
</div>
<div style="padding:5px 0 0 0;">
<input type=button class='at-btn' value="{$oLanguage->getMessage(" << Return")}"
		onclick="location.href='/?action=manager_vin_request'" >
<input type=button class='at-btn' value="{$oLanguage->getMessage("Save")}"
	onclick="this.form.elements['action'].value='manager_vin_request_save'; this.form.submit();">
{*<input type=button class='at-btn' value="{$oLanguage->getMessage("Send to cart")}"
	onclick="
this.form.elements['section'].value='cart';
this.form.elements['action'].value='manager_vin_request_send'; this.form.submit();">*}
<input type=button class='at-btn' value="{$oLanguage->getMessage("Send to customer")}"
	onclick="
this.form.elements['section'].value='customer';
this.form.elements['action'].value='manager_vin_request_send'; this.form.submit();">
<input type=button class='at-btn' value="{$oLanguage->getMessage("Refuse Request")}" style="color: red;"
	onclick="this.form.elements['action'].value='manager_vin_request_refuse'; this.form.submit();">
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
this.form.elements['action'].value='manager_vin_request_refuse_for'; this.form.submit();">
<select name="data[refuse_for]">
{html_options options=$aManagerLogin}
</select>
</div>
{/if*}
</FORM>