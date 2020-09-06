<div id="reg_error_popup"></div>
	<div class="row">
		<div class="col">
			<b>{$oLanguage->getMessage('client_data')}</b><br><br>
			<form method="post">
				<div class="form-group row" style="padding-left: 100px;">
				    <label for="colFormLabel" class="col-sm-2 col-form-label">{$oLanguage->getMessage('phone')}:</label>
				    <div class="col-sm-9" style="width:250px;">
				    	<input class="form-control js-masked-input" type="text" placeholder="(___)___ __ __" 
				    		id="user_phone_popup" name=data[phone] value="{$aData.phone}">
				    </div>
			    	<img src="/image/design/refresh.png" style="padding-top: 6px;cursor:pointer;" alt="{$oLanguage->getMessage('Search')}" title="{$oLanguage->getMessage('Search')}" 
			    		onclick="xajax_process_browse_url('{strip}
							/?action=manager_panel_create_order_find_user&phone='+encodeURIComponent($('#user_phone_popup').val())
							{/strip}); return false;">
			  	</div>
			  	<div class="form-group row" style="padding-left: 100px;">
				    <label for="colFormLabel" class="col-sm-2 col-form-label">{$oLanguage->getMessage('name user')}:</label>
				    <div class="col-sm-9" style="width:250px;">
				    	<input class="form-control" type="text" placeholder="" 
				    		id="data_name" name=data[name] value="{$aData.name}">
				    </div>
			  	</div>
			</form>
		</div>
		<div class="col">
			<b>{$oLanguage->getMessage('data_delivery')}</b><br><br>
			<form method="post">
				<div class="form-group row" style="padding-left: 100px;">
				    <label for="colFormLabel" class="col-sm-2 col-form-label">{$oLanguage->getMessage('city')}:</label>
				    <div class="col-sm-9" style="width:250px;">
				    	<input class="form-control js-masked-input" type="text" placeholder="" 
				    		id="data_city" name=data[city] value="{$aData.city}">
				    </div>
			  	</div>
			  	<div class="form-group row" style="padding-left: 100px;">
				    <label for="colFormLabel" class="col-sm-2 col-form-label">{$oLanguage->getMessage('type delivery')}:</label>
				    <div class="col-sm-9" style="width:250px;">
						<select class="selectpicker" name="data[id_delivery]" id="id_delivery">
							<option value="">{$oLanguage->getMessage('not selected')}</option>
							{foreach key=name item=value from=$aDeliveryType}
								<option value="{$value.id}" {if $value.id==$iIdDeliveryType}selected{/if}>{$value.name}</option>
							{/foreach}
				      	</select>
				    </div>
			  	</div>
			  	<div class="form-group row" style="padding-left: 100px;">
				    <label for="colFormLabel" class="col-sm-2 col-form-label">{$oLanguage->getMessage('type payment')}:</label>
				    <div class="col-sm-9" style="width:250px;">
						<select class="selectpicker" name="data[id_payment]" id="id_payment">
							<option value="">{$oLanguage->getMessage('not selected')}</option>
							{foreach key=name item=value from=$aPaymentType}
								<option value="{$value.id}" {if $value.id==$iIdPaymentType}selected{/if}>{$value.name}</option>
							{/foreach}
				      	</select>
				    </div>
			  	</div>
			</form>
		</div>
	</div>
</div>
<hr>
<span style="margin: 0 0 0 10px;float:right;">
	<input type="button" class="btn" value="{$oLanguage->getMessage('close')}" onclick="$('.js_manager_panel_popup').hide();">
</span>

<span style="margin: 0 0 0 10px;float:right;">
	<input type="submit" class="btn btn-info" value="{$oLanguage->getMessage('save')}"
	onclick="xajax_process_browse_url('{strip}/?action=manager_panel_create_order_user_info_apply
			&is_post=1
			&phone='+$('#user_phone_popup').val()+'&name='+$('#data_name').val()+
			'&city='+$('#data_city').val()+'&id_delivery='+$('#id_delivery').val()+
			'&id_payment='+$('#id_payment').val()+'&return={$sReturn}'
			{/strip}); return false;">
</span>