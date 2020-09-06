{$oLanguage->getMessage("sample file")}: <a href="/imgbank/cat_info_import.xls">cat_info_import.xls</a>
<table width="99%">
{*<tr>	
	<td width=50%><b>{$oLanguage->getMessage("Cat_info_import file type")}:</b></td>
	<td>{html_options name=data[pref] options=$aPref selected=$aData.pref style='width:270px'}</td>
</tr>*}
<tr>	
	<td><b>{$oLanguage->getMessage("File to import")}:</b></td>
	<td><input type=file name=import_file></td></td>
</tr>
</table>