<script language="javascript" type="text/javascript" src="/js/form.js?3284"></script>
<table width="99%">
	<tr><td colspan="2">{$oLanguage->getMessage("Add info for part")}</td>
	</tr>
	<tr>
	   	<td width=50%><b>{$oLanguage->getMessage("Make")}:{$sZir}</b></td>
   		<td nowrap><select id=pref name=data[pref] style="width: 270px;">
   		{html_options  options=$aPref selected=$aData.pref}
		</select>
   		</td>
   	</tr>
   	<tr>
	   	<td><b>{$oLanguage->getMessage("Code Part")}:{$sZir}</b></td>
   		<td nowrap><input id=code type="text" name=data[code] value="{$aData.code}" style="width: 270px;">
   		</td>
   	</tr>
   	<tr><td colspan="2">{$oLanguage->getMessage("Get info from part")}</td>
	</tr>
   	<tr>
	   	<td width=50%><b>{$oLanguage->getMessage("Make From")}:{$sZir}</b></td>
   		<td nowrap><select id=pref name=data[pref_from] style="width: 270px;">
   		{html_options  options=$aPrefFrom selected=$aData.pref_from}
		</select>
   		</td>
   	</tr>
   	<tr>
	   	<td><b>{$oLanguage->getMessage("Code Part from")}:{$sZir}</b></td>
   		<td nowrap><input id=code type="text" name=data[code_from] value="{$aData.code_from}" style="width: 270px;">
   		</td>
   	</tr>
   	<tr>
	   	<td><b>{$oLanguage->getMessage("load_image")}:</b></td>
   		<td nowrap>{include file='addon/mpanel/form_checkbox.tpl' sFieldName='load_image' bChecked=$aData.load_image}
   		</td>
   	</tr>
   	
   	<tr>
	   	<td><b>{$oLanguage->getMessage("load_characteristics")}:</b></td>
   		<td nowrap>{include file='addon/mpanel/form_checkbox.tpl' sFieldName='load_characteristics' bChecked=$aData.load_characteristics}
   		</td>
   	</tr>
   	<tr>
	   	<td><b>{$oLanguage->getMessage("load_cross")}:</b></td>
   		<td nowrap>{include file='addon/mpanel/form_checkbox.tpl' sFieldName='load_cross' bChecked=$aData.load_cross}
   		</td>
   	</tr>
   	<tr>
	   	<td><b>{$oLanguage->getMessage("load_applicability")}:</b></td>
   		<td nowrap>{include file='addon/mpanel/form_checkbox.tpl' sFieldName='load_applicability' bChecked=$aData.load_applicability}
   		</td>
   	</tr>
</table>