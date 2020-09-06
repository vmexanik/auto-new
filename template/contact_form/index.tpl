<div>{$sOutput}</div>

<div align=center>
<FORM method=post>

<font><b>{$aForm.name}</b></font><br><br>
<table class='contact_form' cellspacing=0 cellpadding=0>

{section name=d loop=$aFormItem}
<tr>
	<td width=50% valign=top><b>{$aFormItem[d].caption}</b></td>
	<td>
	{if $aFormItem[d].type=='input'}
<input type=text id='id_field{$aFormItem[d].id}' name='field{$aFormItem[d].id}' class=contact_form style='width:250px;height:18px;'>
	{/if}

	{if $aFormItem[d].type=='textarea'}
<textarea name='field{$aFormItem[d].id}' class=contact_form  style='width:250px;height:100px;'></textarea>
	{/if}

	{if $aFormItem[d].type=='select'}
{assign var=aFormValue value=$aFormItem[d].value }
<select type=radio name='field{$aFormItem[d].id}' class=contact_form style='width:250px;height:18px;'>
	{section name=i loop=$aFormValue}
	<option value='{$aFormValue[i].caption}'>{$aFormValue[i].caption}</option>
	{/section}
</select>
	{/if}

	{if $aFormItem[d].type=='email_select'}
	{assign var=aFormValue value=$aFormItem[d].value }
<select type=radio name='field{$aFormItem[d].id}' class=contact_form style='width:250px;height:18px;'>
	{section name=i loop=$aFormValue}
	<option value='{$aFormValue.caption}'>{$aFormValue.caption}</option>
	{/section}
</select>
	{/if}

	{if $aFormItem[d].type=='checkbox'}
<input type=checkbox name='field{$aFormItem[d].id}' value='Yes' class=no>
	{/if}
	{if $aFormItem[d].type=='separator'}
	{/if}
	</td>
</tr>
{/section}

	<tr><td colspan=2 align=center style='border-bottom:0; text-align:center'>
	<input type=submit id='submit_button' class='at-btn' value='{$aForm.caption}' onclick="this.form.elements['not_bot'].value='1'; " style='width:120px;'>
	</td></tr>
</table>

      <input type=hidden name=form_code value='{$aForm.code}'>

      <input type=hidden name=action value='{$smarty.request.action}'>
      <input type=hidden name=is_post value='1'>
      <input type=hidden name=not_bot value='0'>

</FORM>

</div>