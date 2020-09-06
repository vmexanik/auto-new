<body>
<div id="reg_error_popup"></div>
<form method="post">
	<div class="form" style="width:500px;text-align:left;">
		<table width="100%" border="0">
			<tbody>
				<tr>
					<td><b>{$oLanguage->getMessage('date')}:</b></td>
					<td>
						<input class="form-control" id="date" name="post_date" 
							style="width:100% !important; max-width: 150px;background-color:white;" readonly 
							value="{if $smarty.request.post_date}{$smarty.request.post_date}
								{else}{$smarty.now|date_format:"%d.%m.%Y"}{/if}" 
							onclick="popUpCalendar(this, this, 'dd.mm.yyyy')">							
					</td>
				</tr>
				<tr>
					<td><b>{$oLanguage->getMessage('id cart package')}:</b>{$sZir}</td>
					<td><input class="form-control disabled" readonly type="text" 
						id="data_id_cart_package" name="data[id_cart_package]" 
						value="{$aCartPackage.id}" 
						style="width:100% !important; max-width: 300px;"></td>
				</tr>
				<tr>
					<td><b>{$oLanguage->getMessage('login')}:</b>{$sZir}</td>
					<td><input class="form-control disabled" readonly type="text" 
						id="data_login" name="data[login]" value="{$aUser.login}" 
						style="width:100% !important; max-width: 300px;"></td>
				</tr>
				<tr>
					<td><b>{$oLanguage->getMessage('account_name')}:</b>{$sZir}</td>
					<td>
						<select class="form-control" id="data_id_account" name="data[id_account]" style="width:300px">
							{html_options options=$aAccount selected=$aData.id_account}
						</select>
					</td>
				</tr>
				<tr>
					<td><b>{$oLanguage->getMessage('amount')}:</b>{$sZir}</td>
					<td><input class="form-control" type="text" id="data_amount" name="data[amount]" value="{if $aCartPackage.price_total}{$aCartPackage.price_total}{else}{$smarty.request.amount}{/if}" style="width:100% !important; max-width: 300px;"></td>
				</tr>
				<tr>
					<td><b>{$oLanguage->getMessage('comment')}:</b></td>
					<td><textarea class="form-control" id="data_comment" name="data[comment]"></textarea>							</td>
				</tr>
			</tbody>
		</table>
	</div>

<span style="padding:5px 0 0 0;">
	<input type="button" class="btn" value="{$oLanguage->getMessage('close')}" onclick="$('.js_manager_panel_popup').hide();">
</span>

<span style="padding:5px 0 0 0;">
	<input type="submit" class="btn" value="{$oLanguage->getMessage('save')}"
	onclick="xajax_process_browse_url('{strip}/?action=manager_panel_user_edit_bill
			&is_post=1&code_template={$sCodeTemplate}&id_user={$aUser.id}
			&data_date='+$('#date').val()+'&id_cp='+$('#data_id_cart_package').val()+
			'&data_id_account='+$('#data_id_account').val()+'&data_amount='+$('#data_amount').val()+
			'&comment='+encodeURIComponent($('#data_comment').val())+'&return={$sReturn}'
			{/strip}); return false;">
</span>
</form>
</body>