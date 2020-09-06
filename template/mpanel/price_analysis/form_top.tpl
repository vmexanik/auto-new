<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form()">
<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('Price analysis')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1 width=850>
  <tr>
    <td nowrap>{$oLanguage->getDMessage('Make')}:</td>
    <td><select name=pref style='width:110px'>
    {html_options options=$pref selected=$aRequest.pref}
	</td>
    <td>{$oLanguage->getDMessage('Provider')}1:</td>
    <td><select name=provider1 style='width:110px'>
    {html_options options=$provider selected=$aRequest.provider1}
	</td>
	<td>{$oLanguage->getDMessage('Provider')}2:</td>
    <td><select name=provider2 style='width:110px'>
    {html_options options=$provider selected=$aRequest.provider2}
	</td>
	<td>{$oLanguage->getDMessage('Provider')}3:</td>
    <td><select name=provider3 style='width:110px'>
    {html_options options=$provider selected=$aRequest.provider3}
	</td>
	<td>{$oLanguage->getDMessage('Provider')}4:</td>
    <td><select name=provider4 style='width:110px'>
    {html_options options=$provider selected=$aRequest.provider4}
	</td>
  </tr>
    <tr>
   <td width=50%>{$oLanguage->getDMessage('Price_from')}:</td>
   <td><input type=text name=price_from value="{$aRequest.price_from}" style="width:108" ></td>
   <td width=50%>{$oLanguage->getDMessage('Price_to')}:</td>
   <td><input type=text name=price_to value="{$aRequest.price_to}" style="width:108" ></td>
  </tr>
</table>

</td></tr>
</table>

<input type=submit value={$oLanguage->getDMessage('Analysis')}
 onclick=" update_input('main_form','action','{$sBaseAction}_analysis');  "
 class='bttn'>
<br>