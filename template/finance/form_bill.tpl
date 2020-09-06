<table>
	<tr>
		<td><b>{$oLanguage->getMessage("Date")}:</b></td>
		<td><input id=date name=post_date  style='width:100px;'
				readonly value='{if $smarty.request.post_date}{$smarty.request.post_date}
					{else}{$smarty.now|date_format:"%d.%m.%Y"}{/if}'
   			 	onclick="popUpCalendar(this, this, 'dd.mm.yyyy')">&nbsp;
		</td>
	</tr>
	<tr>
   		<td><b>{$oLanguage->getMessage("Id cart package")}:</b></td>
   		<td>
	   		{if $smarty.request.action == 'finance_bill_edit'}
	   			{$aData.id_cart_package}
	   		{else}
	   			<input type=text name=data[id_cart_package] value='{$aData.id_cart_package}' style='width:180px'>
	   		{/if}
   		</td>
  	</tr>
{if $smarty.request.code_template=='factura_bill' || $sCodeTemplate=='factura_bill'}
	<tr>
   		<td><b>{$oLanguage->getMessage("Number document")}:</b></td>
   		<td><input type=text name=data[id_factura_bill] value='{if $aData.id_factura_bill}{$aData.id_factura_bill}{else}{$id_factura_number}{/if}' style='width:180px'></td>
  	</tr>
{/if}
{if $aAuthUser.type_=='manager'}
	<tr>
   		<td width=50%><b>{$oLanguage->getMessage("Login")}:</b></td>
   		<td nowrap>
   			{if $smarty.request.action == 'finance_bill_edit'}
   				{$aData.login}
   			{else}
   				<input type=text name=data[login] value='{$aData.login}' maxlength=50 style='width:180px' id="user_login_input2" placeholder="(___)___ __ __"></td>
   			{/if}
  	</tr>
{/if}

  	{*<tr>
   		<td width=50%><b>{$oLanguage->getMessage("Account")}:</b> {$sZir}</td>
   		<td>
   		{html_options name=data[id_account] options=$aAccount selected=$aData.id_account}
   		</td>
  	</tr>*}

	<tr>
   		<td width=50%><b>{$oLanguage->getMessage("Amount")}:</b>{$sZir}</td>
   		<td nowrap><input type=text name=data[amount]
			value='{if $aData.amount}{$aData.amount}{else}{$smarty.request.amount}{/if}'
			maxlength=50 style='width:180px'></td>
  	</tr>
</table>

<input type=hidden name=data[code_template] value='{$sCodeTemplate}'>
{if $aData.id}
<input type=hidden name=id value='{$aData.id}'>
{/if}