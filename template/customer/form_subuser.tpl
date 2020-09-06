<table>
	<tr>
   		<td width=50%><b>{$oLanguage->GetMessage("Login")}:</b></td>
   		<td>{$aData.login}</td>
  	</tr>
  	{if !$aData.is_locked}
	<tr>
		<td><b>{$oLanguage->GetMessage("Password")}:</b> {$sZir}</td>
		<td><input type=text name=data[password] value='{$aData.password}' maxlength=50 style='width:270px'></td>
	</tr>
	<tr>
		<td><b>{$oLanguage->GetMessage("CustomerName")}:</b></td>
		<td><input type=text name=data[name] value='{$aData.name}' maxlength=50 style='width:270px'></td>
	</tr>
	<tr>
		<td><b>{$oLanguage->GetMessage("Email")}:</b></td>
		<td><input type=text name=data[email] value='{$aData.email}' maxlength=50 style='width:270px'></td>
	</tr>
	{/if}
	<tr>
		<td><b>{$oLanguage->GetMessage("ParentMargin")}:</b> {$sZir}</td>
		<td><input type=text name=data[parent_margin] value='{$aData.parent_margin}' maxlength=50 style='width:50px'>%</td>
	</tr>
</table>