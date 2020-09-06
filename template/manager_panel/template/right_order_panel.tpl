<div class="col-sm-4">
	<b>{$oLanguage->getMessage('cartpackage #')} #{$aCartPackage.id} {$oLanguage->getMessage('from_s')} {$oLanguage->GetPostDate($aCartPackage.post_date)}</b>
	<a href="/?action=manager_panel_print_order&id={$aCartPackage.id}&return={$sReturn}" onclick="xajax_process_browse_url(this.href); return false;"><img src="/image/fileprint.png" border="0" width="16" align="absmiddle" hspace="1" alt="{$oLanguage->getMessage('print_order_article')}" title="{$oLanguage->getMessage('print_order_article')}"></a>
	<br>
	<a href="/?action=manager_panel_print_order&id={$aCartPackage.id}&no_article=1&return={$sReturn}" onclick="xajax_process_browse_url(this.href); return false;"><img src="/image/print_black.png" border="0" width="16" align="absmiddle" hspace="1" alt="{$oLanguage->getMessage('print_order_no_article')}" title="{$oLanguage->getMessage('print_order_no_article')}"></a>
	<div class="panel panel-default" style="margin-top:5px;">
		<div class="panel-body">
			<b>{$oLanguage->getMessage('order status')}</b>
			{if $aCartPackage.order_status_select}
				{$aCartPackage.order_status_select}
			{else}
				{$oContent->getOrderStatus($aCartPackage.order_status)}
			{/if}
			<a href="/?action=manager_panel_manager_package_list_view_log&id={$aCartPackage.id}" onclick="xajax_process_browse_url(this.href); return false;">
				<img src="/image/design/clock.png" border="0" width="16" align="absmiddle" style="margin-left:10px;" 
					hspace="1" alt="{$oLanguage->getMessage('history')}" title="{$oLanguage->getMessage('history')}">
			</a>
			<br><br>
			<b>{$oLanguage->getMessage('manager')}:</b>
			<span id="name_manager"> 
				{if $aCartPackage.id_manager}
					{if $aCartPackage.name_manager}
						{$aCartPackage.name_manager}
					{elseif $aCartPackage.manager_login}
						{$aCartPackage.manager_login}
					{/if}
				{else}
					<a href="/?action=manager_panel_manager_package_list_set_package_own&id={$aCartPackage.id}" onclick="xajax_process_browse_url(this.href); return false;">
						{$oLanguage->getMessage('set_package_own')}
					</a>
				{/if}
			</span>
			{if $aCartPackage.is_web_order}
				<span><img src="/image/design/globe_icon.png" border=0 width=16 align=absmiddle></span>
			{/if}
			<br><br>
			<span style="color:grey;text-transform:uppercase;font-weight:bold;">{$oLanguage->getMessage('info_client')}</span>
			<span class="glyphicon glyphicon-edit" aria-hidden="true" style="float:right;"></span>
			<br>
			<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
			<b>ID:</b>{$aCartPackage.id_user}&nbsp;&nbsp;
			<span class="glyphicon glyphicon-search" aria-hidden="true" 
				title="{$oLanguage->getMessage('history_client_orders')}" style="cursor:pointer;"
				onclick="xajax_process_browse_url('/?action=manager_panel_manager_package_list&search_id_user={$aCartPackage.id_user}'); return false;"></span>
			&nbsp;
			<a href="/?action=manager_panel_user_edit_car&id_cp={$aCartPackage.id}&id_user={$aCartPackage.id_user}" onclick="xajax_process_browse_url(this.href); return false;">
				<img src="/image/design/car.png" border="0" width="16" align="absmiddle" hspace="1" alt="{$oLanguage->getMessage('car_client')}" title="{$oLanguage->getMessage('car_client')}">
			</a>
			<div style="padding-left:10px;padding-bottom: 10px;">
				<span style="color:blue;">
				{if $aCartPackage.name}
					{$aCartPackage.name}
				{else}
					{$aCartPackage.login}
				{/if}
				</span>
				{if $aCartPackage.email}
				<br>
				<span style="color:blue;">
					{$aCartPackage.email}
				</span>
				{/if}
				{if $aCartPackage.phone}
					<br>
					<span style="color:blue;">
						{$aCartPackage.phone}
					</span>
					&nbsp;&nbsp;
					<span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>
				{/if}
				<br><br>
				{$oLanguage->getMessage('custamount')}: 
					<span id="balance">{$sBalance}</span>
				<br>
				<span style="cursor:pointer;" class="glyphicon glyphicon-usd" aria-hidden="true" title="{$oLanguage->getMessage('correct balance')}"
					onclick="xajax_process_browse_url('/?action=manager_panel_user_edit_correct_balance&amp;id_cp={$aCartPackage.id}&amp;id_user={$aCartPackage.id_user}'); return false;">
				</span>
				&nbsp;
				<a href="/?action=manager_panel_user_edit_bill&amp;code_template=order_bill&amp;id_cp={$aCartPackage.id}&amp;id_user={$aCartPackage.id_user}&return={$sReturn}"
					onclick="xajax_process_browse_url(this.href); return false;">
					<img src="/image/design/edit.png" border="0" width="16" align="absmiddle" 
						hspace="1" alt="{$oLanguage->getMessage('s_pko')}" title="{$oLanguage->getMessage('s_pko')}"
						>{$oLanguage->getMessage('pko')}
				</a>
				&nbsp;
				<a href="/?action=manager_panel_user_edit_bill&amp;code_template=order_bill_bv&amp;id_cp={$aCartPackage.id}&amp;id_user={$aCartPackage.id_user}&return={$sReturn}"
					onclick="xajax_process_browse_url(this.href); return false;">
					<img src="/image/design/edit.png" border="0" width="16" align="absmiddle" 
						hspace="1" alt="{$oLanguage->getMessage('order bill bv')}" title="{$oLanguage->getMessage('order bill bv')}"
						>{$oLanguage->getMessage('bv')}
				</a>
				&nbsp;
				<a href="/?action=manager_panel_user_edit_bill&amp;code_template=order_bill_rko&amp;id_cp={$aCartPackage.id}&amp;id_user={$aCartPackage.id_user}&return={$sReturn}"
					onclick="xajax_process_browse_url(this.href); return false;">
					<img src="/image/design/edit.png" border="0" width="16" align="absmiddle" 
						hspace="1" alt="{$oLanguage->getMessage('order bill rko')}" title="{$oLanguage->getMessage('order bill rko')}"
					>{$oLanguage->getMessage('rko')}
				</a>				
				&nbsp;&nbsp;
				<a href="/?action=manager_panel_user_send_sms&amp;id_cp=15&amp;id_user=7" onclick="xajax_process_browse_url(this.href); return false;">
					<img src="/image/design/spech.png" border="0" width="16" align="absmiddle" hspace="1" alt="{$oLanguage->getMessage('send_sms')}" title="{$oLanguage->getMessage('send_sms')}">
				</a>
			</div>
			<div style="padding-top: 10px; border-top: 1px solid; border-color: #ddd;">
				<span style="color:grey;text-transform:uppercase;font-weight:bold;">{$oLanguage->getMessage('info_delivery')}</span>
				<span class="glyphicon glyphicon-edit" aria-hidden="true" style="float:right;"></span>
				<br>
				<span class="glyphicon glyphicon-send" aria-hidden="true"></span>
				&nbsp;<b>{$oLanguage->getMessage('type_delivery')}:</b>&nbsp;{$aCartPackage.delivery_type_name}
				<br><b style="padding-left:22px;">{$oLanguage->getMessage('address')}:</b>&nbsp;{$aCartPackage.user_contact_city}&nbsp;{$aCartPackage.user_contact_address}
			</div>
		</div>
	</div>
</div>