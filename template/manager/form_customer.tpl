<table>
  	<tr>
   		<td width=50%><b>{$oLanguage->getMessage("Name")}:</b></td>
   		<td><input type=text name=data[name] value='{$aData.name|escape}' maxlength=50 style='width:270px'></td>
  	</tr>
	<tr>
		<td><b>{$oLanguage->getMessage("Manager Comment")}:</b></td>
		<td><textarea name=data[manager_comment] style='width:270px'>{$aData.manager_comment|escape}</textarea></td>
	</tr>
</table>