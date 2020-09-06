<table>
	<tr>
   		<td width=50%><b>{$oLanguage->getMessage("Code")}:</b></td>
   		<td>
   	{if $aData.code_visible}
		{$aData.code}
	{else}
		<i>{$oLanguage->getMessage("cart_invisible")}</i>
	{/if}</td>
  	</tr>
  	<tr>
   		<td width=50%><b>{$oLanguage->getMessage("Name")}:</b></td>
   		<td>{$aData.name}</td>
  	</tr>
	<tr>
   		<td width=50%><b>{$oLanguage->getMessage("Number")}:</b></td>
   		<td><input type=text name=number value='{$aData.number}' maxlength=50 style='width:270px'></td>
  	</tr>
	<tr>
		<td><b>{$oLanguage->getMessage("Customer comment")}:</b></td>
		<td><textarea name=customer_comment style='width:270px'>{$aData.customer_comment}</textarea></td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getMessage("Customer Database ID")}:</b></td>
		<td><input type=text name=customer_id value='{$aData.customer_id}' maxlength=50 style='width:270px'></td>
	</tr>
	<td><a href='#' onclick='window.open("{$aRow.url}");'
	>{$oLanguage->getMessage('download excel')}</a>
</td>
</table>