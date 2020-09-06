<h3>{$oLanguage->getMessage("Preview Vin request")} # {$aData.id}</h3>
<div class="form" style="width: 500px;" align="left">
<table>
	<tr>
		<td><b>{$oLanguage->getMessage("Marka")}:</b></td>
		<td>{$aData.marka}</td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getMessage("VIN")}:</b></td>
		<td>{$aData.vin}</td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getMessage("Model")}:</b></td>
		<td>{$aData.model}</td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getMessage("Engine")}:</b></td>
		<td>{$aData.engine}</td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getMessage("Country producer")}:</b></td>
		<td>{$aData.country_producer}</td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getMessage("Month/Year")}:</b></td>
		<td>{$oLanguage->getMessage($aData.month)} / {$aData.year} </td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getMessage("Volume")}:</b></td>
		<td>{$aData.volume}</td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getMessage("Body")}:</b></td>
		<td>{$aData.body}</td>
	</tr>
	{if $aData.wheel}
	<tr>
		<td><b>{$oLanguage->getMessage("Wheel")}:</b></td>
		<td>{$aData.wheel}</td>
	</tr>
	{/if}
	<tr>
		<td><b>{$oLanguage->getMessage("KPP")}:</b></td>
		<td>{$aData.kpp}</td>
	</tr>

	{if $aData.kpp_number}
		<tr>
			<td><b>{$oLanguage->getMessage("kpp_number")}:</b></td>
			<td>{$aData.kpp_number}</td>
		</tr>
	{/if}

	{if $aData.utable}
		<tr>
			<td><b>{$oLanguage->getMessage("VinUtable")}:</b></td>
			<td>{$aData.utable}</td>
		</tr>
	{/if}

	{if $aData.engine_number}
		<tr>
			<td><b>{$oLanguage->getMessage("VinEngineNumber")}:</b></td>
			<td>{$aData.engine_number}</td>
		</tr>
	{/if}

	{if $aData.engine_code}
		<tr>
			<td><b>{$oLanguage->getMessage("engine_code")}:</b></td>
			<td>{$aData.engine_code}</td>
		</tr>
	{/if}

	{if $aData.engine_volume}
		<tr>
			<td><b>{$oLanguage->getMessage("engine_volume")}:</b></td>
			<td>{$aData.engine_volume}</td>
		</tr>
	{/if}

	<tr>
   		<td width=50%><b>{$oLanguage->GetMessage("Passport image preview")}:</b></td>
   		<td>
   		{if $aData.passport_image_name}
   			<a href='{$aData.passport_image_name}' target=_blank
				><img src='{$aData.passport_image_name_small}' width='{$oLanguage->GetConstant('passport_image:small_width',150)}'
				border=0></a>
   		{else}
   			{$oLanguage->getMessage("not uploaded")}
   		{/if}
   		</td>
  	</tr>


	<tr>
		<td><b>{$oLanguage->getMessage("Additional")}:</b></td>
		<td>{$aData.additional}</td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getMessage("Customer Comment")}:</b></td>
		<td>{$aData.customer_comment}</td>
	</tr>
</table>
</div>
<br>

<FORM method=post>
<table width="99%" cellspacing=0 cellpadding=5 class="datatable">
<tr>
	<th></th>
	<th><nobr>{$oLanguage->getMessage("Name")}</th>
	<!--th><nobr>{$oLanguage->getMessage("CatName")}</th-->
	<th><nobr>{$oLanguage->getMessage("Code")}</th>
	<th><nobr>{$oLanguage->getMessage("Number")}</th>
	<!--th><nobr>{$oLanguage->getMessage("Price")}</th>
	<th><nobr>{$oLanguage->getMessage("Term")}</th-->
	<!--th><nobr>{$oLanguage->getMessage("Provider")}</th-->
	<th><nobr>{$oLanguage->getMessage("Weight")}</th>
	<th>&nbsp;</th>
</tr>

{if $aAuthUser.price_type=='discount'}
	{assign var=sAddedPriceType value='&price_type=retail'}
{/if}


{foreach item=aPart from=$aPartList}
{if $aPart.i_visible}
<tr class="{cycle values="even,none"}">
	<td width="1px"> {if $aPart.price>0}<input type=checkbox name="part[{$aPart.i}][i]" value='1' checked>{/if}</td>
	<td>{$aPart.name}</td>
	{*<td>{$aPart.user_input_code}</td>*}
	<td>{if $aPart.number}{$aPart.number}{else}&nbsp;{/if}</td>
	<td>{if $aPart.weight}{$aPart.weight}{else}&nbsp;{/if}</td>
	<td><a
href='/?action=catalog_price_view&form_request=1&code={$aPart.user_input_code}&manager_login={$aData.manager_login}{$sAddedPriceType}'
			 target=_blank>{$oLanguage->getMessage('View in price online')}</a></td>
</tr>
{/if}
{/foreach}
<!--tr class="even">
	<td colspan=4 align=right>{$oLanguage->getMessage('Subtotal')}:</td>
	<td><b>{$oCurrency->PrintPrice($dSubtotal)}</b></td>
	<td colspan=3>&nbsp;</td>
</tr-->
</table>

<div style="padding:5px 0 0 0;">
<input type=button class='at-btn' value="{$oLanguage->getMessage(" << Return")}" onclick="location.href='/?action=cart_vin_request'" >

<!--input type=button class='at-btn' value="{$oLanguage->getMessage("Send to my cart")}"
	onclick="this.form.elements['action'].value='cart_vin_request_send'; this.form.submit();"-->

<input type=button class='at-btn' value="{$oLanguage->getMessage('View all in price online')}"
{strip}
 onclick="location.href='/?action=catalog_part&form_request=1&cod={$sMultipleCode}
	&manager_login={$aData.manager_login}{$sAddedPriceType}'" >
{/strip}
<input type=hidden name=action value=''>
<input type=hidden name=is_post value='1'>
</div>


</FORM>
<br>
