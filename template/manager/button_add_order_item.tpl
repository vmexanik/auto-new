{if $aData.order_status=='pending' || $aData.order_status=='work'}
<form action="/">
	<div class="at-block-form" style="background-color: #ffffff;box-shadow: 0 0 10px #cadae2;margin: 0 0 20px 0;">
		<div class="at-user-details" style="box-shadow: none;">
		    <div class="header">
		        {$oLanguage->GetMessage('add product to order')}
		    </div>
		</div>
	
		
			<input type="hidden" name="action" value="manager_package_add_order_item">
			<input type="hidden" name="id_cart_package" value="{$aData.id}">
			<input type="hidden" name="return" value="{$sReturn|escape:"url"}">
		
			<table>
				<tr>
					<td><div class="field-name">{$oLanguage->GetMessage('zzz_code')}</div></td>
					<td><input type="text" name="zzz_code" value=""></td>
				</tr>
				<tr>
					<td><div class="field-name">{$oLanguage->GetMessage('number')}</div></td>
					<td><input type="text" name="number" value="1" maxlength="3"></td>
				</tr>
			</table>
	</div>
	<div class="buttons">
		<input type="submit" class="at-btn" value="{$oLanguage->GetMessage('add')}">
	</div>
</form>
{/if}