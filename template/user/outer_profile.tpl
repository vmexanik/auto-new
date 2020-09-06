<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>

<td width="450" valign="top">

{$sForm}

</td>

<td valign="top" style="padding:0 15px">

{assign var="sCode" value='instruction_profile_'|cat:$aAuthUser.type_}

{$oLanguage->GetText($sCode)}
</td>

</tr>
</table>




