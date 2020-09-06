<div id="reg_error_popup"></div>
<form method="post">
<div class="at-block-form" style="background-color: #ffffff;box-shadow: 0 0 10px #cadae2;margin: 0 0 20px 0;">
	<table width="100%" border="0">
		<tbody>
			<tr>
				<td>
					<div class="field-name">{$oLanguage->getMessage('correct balance to customer')}:</div>
   				</td>
				<td>{$aUser.login}</td>
			</tr>
			<tr>
			    <td>
		    		<div class="field-name">{$oLanguage->getMessage('date')}:</div>
				</td>
				<td>{$sDate}</td>
			</tr>
			<tr>
				<td colspan="2"><hr></td>
			</tr>
			<tr>
			    <td>
		    		<div class="field-name">{$oLanguage->getMessage('total')}:<i>*</i></div>
				</td>
				<td>
					<input type="text" id="data_amount" name="data[amount]" value="" style="max-width: 100%;">							
				</td>
			</tr>
			<tr>
			    <td>
		    		<div class="field-name">{$oLanguage->getMessage('type')}:<i>*</i></div>
				</td>
				<td>
					<select id="data_pay_type" name="data[pay_type]" class="js-select">
						{html_options options=$aPayType selected=''}
					</select>
				</td>
			</tr>
			<tr>
			    <td>
		    		<div class="field-name">{$oLanguage->getMessage('comment')}:</div>
				</td>
				<td>
					<textarea id=data_comment name="data[comment]"></textarea>							
				</td>
			</tr>
			</tbody>
</table>
</div>
<span style="padding:5px 0 0 0;">
	<input type="button" class="at-btn" value="{$oLanguage->getMessage('apply')}"
		onclick="xajax_process_browse_url('{strip}
			/?action=manager_panel_user_edit_correct_balance&is_post=1&id_user={$aUser.id}&post_date={$sDate}
			&data_amount='+$('#data_amount').val()+'&data_pay_type='+$('#data_pay_type').val()+
			'&data_comment='+encodeURIComponent($('#data_comment').val())
			{/strip}); return false;">
</span>
</form>