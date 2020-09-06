<table>
	<tr>
	   <td>{$oLanguage->getMessage("Is private parsed")}:</td>
	   <td>
	   {include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_private_parsed' bChecked=$aData.is_private_parsed}
	   </td>
  	</tr>
  	<tr>
		<td><b>{$oLanguage->getMessage("Private Comment")}:</b>
		</td>
		<td><textarea name=data[private_comment] style='width:270px'>{$aData.private_comment|escape}</textarea></td>
	</tr>
</table>