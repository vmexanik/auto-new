<div class="col-sm-4">
	<h3 class="text-danger">{$oLanguage->getMessage('new order')}</h3>
	<div class="panel panel-default" style="margin-top:5px;">
		<div class="panel-body">
			<span style="color:grey;text-transform:uppercase;font-weight:bold;">{$oLanguage->getMessage('info_client')}</span>
			<span class="glyphicon glyphicon-edit" aria-hidden="true" style="float:right;cursor:pointer;" title="{$oLanguage->getMessage('info_client_edit')}"
				onclick="xajax_process_browse_url('/?action=manager_panel_create_order_edit_user&return={$sReturn|escape:"url"}'); return false;"></span>
			<br>
			<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
			{if $aUser}
				<b>ID:</b>{$aUser.id}&nbsp;&nbsp;
				<div style="padding-left:10px;padding-bottom: 10px;">
					<span style="color:blue;">
					{if $aUser.new_name}
						{$aUser.new_name}
					{else}
						{$aUser.login}
					{/if}
					</span>
					{if $aUser.email}
					<br>
					<span style="color:blue;">
						{$aUser.email}
					</span>
					{/if}
					{if $aUser.phone}
						<br>
						<span style="color:blue;">
							{$aUser.phone}
						</span>
						&nbsp;&nbsp;
						<span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>
					{/if}
					<br><br>
					{$oLanguage->getMessage('custamount')}: 
						<span id="balance">{$sBalance}</span>
					<br>
				</div>
				{/if}
			<br><br>
			<div style="padding-top: 10px; border-top: 1px solid; border-color: #ddd;">
				<span style="color:grey;text-transform:uppercase;font-weight:bold;">{$oLanguage->getMessage('info_delivery')}</span>
				<span class="glyphicon glyphicon-edit" aria-hidden="true" style="float:right;cursor:pointer;"
					onclick="xajax_process_browse_url('/?action=manager_panel_create_order_edit_user&return={$sReturn|escape:"url"}'); return false;"></span>
				<br>
				<span class="glyphicon glyphicon-send" aria-hidden="true"></span>
				&nbsp;<b>{$oLanguage->getMessage('type_delivery')}:</b>&nbsp;{$aUser.delivery_type_name}
				<br><b style="padding-left:22px;">{$oLanguage->getMessage('address')}:</b>&nbsp;{$aUser.city}
				<br><b style="padding-left:22px;">{$oLanguage->getMessage('type payment')}:</b>&nbsp;{$aUser.payment_name}				
			</div>
		</div>
	</div>
</div>