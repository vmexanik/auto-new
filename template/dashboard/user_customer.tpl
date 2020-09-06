	<h1>{$oLanguage->getMessage("Dashboard")} - <b>{$aUserCustomer.login}</b></h1>
			<div id="account-info">
				<div id="user-avatar">
        			<div id="avatar-ico"></div>
        		</div>
				<span class="balance">{$oLanguage->getMessage("Total Account")}:
					<b>${$aUserCustomer.amount}</b>
				</span>
				<br><br><br>{$oLanguage->AddOldParser('comment_customer_popup',$aUserCustomer.id)}
			</div>
			<table class="tbMessages" style="float:right;">
				<tbody>
					<tr>
						<th class="tbMessages-header" colspan="3">
							{$oLanguage->GetMessage("Useful links")}</th>
					</tr>
					<tr>
						<td>
						<div style="line-height: 18px;">
							{$oLanguage->getMessage("part changes")}:
							<a target="_blank" href="./?search_login={$aUserCustomer.login}
							&search[customer_type]=-1
							&search[date_type]=cart
							&search_date=1
							&search[date_from]={$smarty.now-120*86400|date_format:'%d.%m.%Y'}
							&search[date_to]={$smarty.now+86400|date_format:'%d.%m.%Y'}
							&show_table=1
							&action=manager_order&search[change_period]=1">1&nbsp;{$oLanguage->getMessage("day")}</a>&nbsp;
							<a target="_blank" href="./?search_login={$aUserCustomer.login}
							&search[customer_type]=-1
							&search[date_type]=cart
							&search_date=1
							&search[date_from]={$smarty.now-120*86400|date_format:'%d.%m.%Y'}
							&search[date_to]={$smarty.now+86400|date_format:'%d.%m.%Y'}
							&show_table=1
							&action=manager_order&search[change_period]=2">2&nbsp;{$oLanguage->getMessage("days")}</a>&nbsp;
							<a target="_blank" href="./?search_login={$aUserCustomer.login}
							&search[customer_type]=-1
							&search[date_type]=cart
							&search_date=1
							&search[date_from]={$smarty.now-120*86400|date_format:'%d.%m.%Y'}
							&search[date_to]={$smarty.now+86400|date_format:'%d.%m.%Y'}
							&show_table=1
							&action=manager_order&search[change_period]=3">3&nbsp;{$oLanguage->getMessage("days")}</a>&nbsp;
							<a target="_blank" href="./?search_login={$aUserCustomer.login}
							&search[customer_type]=-1
							&search[date_type]=cart
							&search_date=1
							&search[date_from]={$smarty.now-120*86400|date_format:'%d.%m.%Y'}
							&search[date_to]={$smarty.now+86400|date_format:'%d.%m.%Y'}
							&show_table=1
							&action=manager_order&search[change_period]=7">7&nbsp;{$oLanguage->getMessage("days")}</a>&nbsp;
							<a target="_blank" href="./?search_login={$aUserCustomer.login}
							&search[customer_type]=-1
							&search[date_type]=cart
							&search_date=1
							&search[date_from]={$smarty.now-120*86400|date_format:'%d.%m.%Y'}
							&search[date_to]={$smarty.now+86400|date_format:'%d.%m.%Y'}
							&show_table=1
							&action=manager_order&search[change_period]=30">30&nbsp;{$oLanguage->getMessage("days")}</a><br/>
							<a target="_blank" href="./?search[login]={$aUserCustomer.login}
							&search[search_date]=1
							&search[date_from]={$smarty.now-60*86400|date_format:'%d.%m.%Y'}
							&search[date_to]={$smarty.now+86400|date_format:'%d.%m.%Y'}
							&action=manager_invoice_account_log">{$oLanguage->GetMessage('Manager Inovice account log')}</a><br/>
							{*<a target="_blank" href="/?action=message_preview&id={$aItem.id}">test link</a><br/>*}
						</div>
						{if $sLoginLink}
						<div>
							<span onclick="$('#ta_link_login').css('display','block');"
							style="cursor:pointer;font-size: 12px;line-height: 20px;color: blue;">
								<img src="/image/icn_arrow_anchor.gif"/>
								{$oLanguage->getMessage("login link")}
							</span>
				<textarea cols="40" rows="3" style="display:none;" id="ta_link_login" onclick="this.focus(); this.select();"
							>{$sServerName}/?action=user_manager_login&id={
							$aUserCustomer.id}&hash={$sLoginLink}</textarea>
						</div>
						{else}
							<font color="red">
								{$oLanguage->getMessage("client closed access to his page for managers")}
							</font>
						{/if}
						</td>
					</tr>
				</tbody>
			</table>
			<div class="clear"></div>
			{$sSoundCustomerUploadContent}
			<div style="float:left;">
				<table>
					<tr>
						<td><b>{$oLanguage->getMessage("Login")}:</b></td>
						<td>
							<font color="{$sLoginColor}">
								{$aUserCustomer.login}
							</font>
							{if $aUserCustomer.login_parent}{$oLanguage->getMessage("LoginParent")}:
								<font color=green>
								<b>{$aUserCustomer.login_parent}</b>
								</font>
							{/if}
							<br>
							{if $aUserCustomer.customer_name}
								{assign var='sCustomerName' value=$aUserCustomer.customer_name}
							{else}
								{assign var='sCustomerName' value=$aUserCustomer.name}
							{/if}
						</td>
					</tr>
					<tr>
						<td><b>{$oLanguage->getMessage("Group")}:</b></td>
						<td>
							{$aUserCustomer.code_customer_group}
							<b>{$aUserCustomer.price_type}</b>
						</td>
					</tr>
					<tr>
					{if $aUserCustomer.price_type=='discount'}
							<td><b>{$oLanguage->getMessage("Discount")}:</b></td>
							<td>
				{math equation="max(x,y,z)"
				x=$aUserCustomer.discount_static
				y=$aUserCustomer.discount_dynamic
				z=$aUserCustomer.group_discount}  %
							</td>
							{else}
							<td><b>{$oLanguage->getMessage('Margin')}</b>:</td>
							<td>
				{math equation="x + y"
				x=$aUserCustomer.customer_group_margin
				y=$aUserCustomer.parent_margin} %
							</td>
							{/if}
					</tr>
					<tr>
						<td><b>{$oLanguage->getMessage("Email")}:</b></td>
						<td> {$aUserCustomer.email}</td>
					</tr>
					<tr>
						<td><b>{$oLanguage->getMessage("Country")}:</b> </td>
						<td>{$aUserCustomer.country}</td>
					</tr>
					<tr>
						<td><b>{$oLanguage->getMessage("Region")}:</b> </td>
						<td id='partner_region_td_id'>
							{if $aUserCustomer.partner_region_name}
								{$aUserCustomer.partner_region_name}
							{else}
<select name=search[id_partner_region] style="width: 150px;" id='partner_region_select_id'
	onChange="{strip}
			xajax_process_browse_url('?action=dashboard_partner_region_change
			&id='+$('#partner_region_select_id').val()
			+'&id_user={$aUserCustomer.id}');
			{/strip}"
	>
	<option value=0>{$oLanguage->getMessage("Choose")}</option>
	{foreach from=$aPartnerRegion item=aItem}
		<option value={$aItem.id}
			{if $aItem.id == $smarty.request.search.id_partner_region} selected {/if}
			> {$aItem.name}</option>
	{/foreach}
</select>
							{/if}
						</td>
					</tr>
					<tr>
						<td><b>{$oLanguage->getMessage("City")}:</b> </td>
						<td>
							<font color=blue>{$aUserCustomer.city} / {$aUserCustomer.delivery_type_name}</font>
						{if $aUserCustomer.is_cargo_ensured}
							<font color=brown>{$oLanguage->GetMessage('cargo_ensured:yes')}</font>
						{/if}
						</td>
					</tr>
					<tr>
						<td><b>{$oLanguage->getMessage("FLName Delivery")}:</b></td>
						<td>
							{if $aUserCustomer.name_delivery}
								<font color=blue><b>{$aUserCustomer.name_delivery}</b></font>
							{/if}
							{$sCustomerName}
						</td>
					</tr>
					<tr>
						<td><b>{$oLanguage->getMessage("Address")}:</b></td>
						<td>{$aUserCustomer.address}</td>
					</tr>
					<tr>
						<td><b>{$oLanguage->getMessage("Phone")}:</b></td>
						<td>
							{$aUserCustomer.phone}
							{$aUserCustomer.phone2}
							{$aUserCustomer.phone3}
						</td>
					</tr>
					<tr>
						<td><b>{$oLanguage->getMessage("Debt")}:</b></td>
						<td>
							{math equation="max(x,y)"
							x=$aUserCustomer.user_debt
							y=$aUserCustomer.group_debt}
						</td>
					</tr>
					<tr>
						<td><b>{$oLanguage->getMessage("On account ")}:</b></td>
						<td>
							{$oLanguage->PrintPrice($aUserCustomer.amount)}<br/>
							{$oLanguage->PrintPrice($aUserCustomer.amount,false,false,false,UAH)}
						</td>
					</tr>
					<tr>
						<td colspan="2"><hr/></td>
					</tr>
					<tr>
						<td><b>{$oLanguage->getMessage("Store Rating")}:</b> </td>
						<td>{$aUserCustomer.rating_name}</td>
					</tr>
					<tr>
						<td colspan="2">
							<b>{$oLanguage->getMessage("Manager comment")}:</b>
							{$aUserCustomer.manager_comment}
						</td>
					</tr>
				</table>
			</div>
			<div class="clear"></div>
		<br>
			<span class="span_h1">{$oLanguage->getMessage("Current parts in work")}</span>
			<div id="wrapCO">
				<ul id="navCO">
					<li {if !$smarty.request.status || $smarty.request.status=='all_except_archive'} class="active"{/if}>
						<a href="/?action=dashboard_user&id={$sId}&status=all_except_archive">
							<span>{$oLanguage->GetMessage('All except Archive')} ({$aDashboardOrder.all_except_archive})</span>
						</a>
					</li>
					<li {if $smarty.request.status && $smarty.request.status=='refused'} class="active"{/if}>
						<a href="/?action=dashboard_user&id={$sId}&status=refused">
							<span>{$oLanguage->GetMessage('Refused')} ({$aDashboardOrder.refused})</span>
						</a>
					</li>
					<li {if $smarty.request.status && $smarty.request.status=='pending'} class="active"{/if}>
						<a href="/?action=dashboard_user&id={$sId}&status=pending">
							<span>{$oLanguage->GetMessage('Pending')} ({$aDashboardOrder.pending})</span>
						</a>
					</li>
					<li {if $smarty.request.status && $smarty.request.status=='store'} class="active"{/if}>
						<a href="/?action=dashboard_user&id={$sId}&status=store">
							<span>{$oLanguage->GetMessage('store')} ({$aDashboardOrder.store})</span>
						</a>
					</li>
				</ul>
				<div class="clear"></div>
				<div id="current-orders">
					{$sDashboardOrder}
				</div>
			</div>
<span class="span_h1">{$oLanguage->getMessage("Cart package list")}</span>
{$sDashboardCartPackage}
<span class="span_h1">{$oLanguage->getMessage("Vin requests")}</span>
<table class="datatable">
	<thead>
		<tr>
			<th>{$oLanguage->getMessage("#")}</th>
			<th>{$oLanguage->getMessage("Order Status")}</th>
			<th>{$oLanguage->getMessage("VIN")}</th>
			<th>{$oLanguage->getMessage("Post")}</th>
			<th>{$oLanguage->getMessage("Status")}</th>
			<th>{$oLanguage->getMessage("Marka")}</th>
			<th>{$oLanguage->getMessage("Manager Comment/Remember")}</th>
			<th>{$oLanguage->getMessage("Refuse For")}</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$aDashboardVinRequest item=aValue name=vinRequestForeach}
		<tr class="{if $smarty.foreach.vinRequestForeach.index % 2== 0}even{else}none{/if}">
			<td>{$aValue.id}</td>
			<td align=center> {$oLanguage->getOrderStatus($aValue.order_status)}</td>
			<td>{$aValue.vin}
				{if $aValue.manager_comment}
				<br><font color=red>{$aValue.manager_comment}&nbsp;</font>
				{/if}
			</td>
			<td>{$oLanguage->getDateTime($aValue.post)}</td>
			<td>{$aValue.order_status}</td>
			<td>{$aValue.marka}</td>
			<td>{$aValue.manager_comment}&nbsp;
				{if $aValue.order_status=='refused' || $aValue.order_status=='parsed'}
 				<br><input type=checkbox value=1 {if $aValue.is_remember}checked{/if}
onclick=" xajax_process_browse_url('?action=manager_vin_request_remember&id={$aValue.id}&checked='+this.checked);"
				>{$aValue.remember_text}
				{/if}
			</td>
			<td>{$aValue.refuse_for}&nbsp;</td>
			<td nowrap>
				{capture name=sPreviewLink}
				<a target="_blank" href="/?action=manager_vin_request_edit&id={$aValue.id}"
					{if !$aValue.id_manager_fixed} style="font-color:green;"{/if}
				><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle />
				{$oLanguage->getMessage("Preview")}</a>
				{/capture}
				{if $aUserCustomer.is_super_manager || $aUserCustomer.is_sub_manager || !$aUserCustomer.is_technical}
					{if $aValue.order_status=='refused' || $aValue.order_status=='parsed'}
						{$smarty.capture.sPreviewLink}
					{/if}
				{else}
					{if $aValue.id_manager_fixed || (!$aValue.id_manager_fixed && !$aUserCustomer.id_vin_request_fixed)}
						{$smarty.capture.sPreviewLink}
					{/if}
				{/if}
			</td>
		</tr>
		{/foreach}
		<tr>
			<td colspan=5 class="tbDet-footer" align=right>
				<a target="_blank" class="show" href="./?search[login]={$aUserCustomer.login}&action=manager_vin_request">
				{$oLanguage->getMessage("Open all")}</a>
			</td>
		</tr>
	</tbody>
</table>
<span class="span_h1">{$oLanguage->getMessage("Invoice list")}</span>
<table class="datatable">
		<tr>
			<th>{$oLanguage->getMessage("CustID")}</th>
			<th>{$oLanguage->getMessage("Login")}</th>
			<th>{$oLanguage->getMessage("PostDate")}</th>
			<th>{$oLanguage->getMessage("Invoice Total")}</th>
			<th>{$oLanguage->getMessage("Travel sheet")}</th>
			<th>{$oLanguage->getMessage("Is Sent")}</th>
			<th>{$oLanguage->getMessage("IsEnd")}</th>
			<th></th>
		</tr>
		{foreach from=$aDashboardInvoiceList item=aValue name=invoiceForeach}
		<tr class="{if $smarty.foreach.invoiceForeach.index % 2== 0}even{else}none{/if}">
			<td>{$aValue.id}</td>
			<td>{$oLanguage->AddOldParser('customer',$aValue.id_user)}
				{if $aValue.is_office_client}
					<br><b>{$aValue.office_code}</b>
				{/if}
			</td>
			<td>{$aValue.post_date}<br>
				<i>{$aValue.account_name}</i><br>
				{if $aValue.region_name}<b>{$aValue.region_name}</b>{/if}
			</td>
			<td>{$aValue.total}
				<br>{$oLanguage->GetMessage('post manager')}:<b> {$aValue.post_manager}</b>
			</td>
			<td>{$aValue.id_travel_sheet}</td>
			<td>{include file='addon/mpanel/yes_no.tpl' bData=$aValue.is_sent}</td>
			<td>{include file='addon/mpanel/yes_no.tpl' bData=$aValue.is_end}
				<br>{$oLanguage->GetMessage('end manager')}:<br/>
				<b> {$aValue.end_manager}</b>
			</td>
			<td>
				<a href="/?action=manager_invoice_customer_invoice&subaction=send_end&id={$aValue.id}&return={$sReturn|escape:"url"}"
			onClick="return (confirm('{$oLanguage->getMessage("Are you sure?")}'))">
				<img src="/image/tooloptions.png" border=0  width=16 align=absmiddle/>
				{$oLanguage->getMessage("Send and end all invoice carts")}</a>
			</td>
		</tr>
		{/foreach}
		<tr>
			<td colspan=3 class="tbDet-footer" align=right>
				<a class="show" target="_blank" href="{strip}
				/?action=manager_invoice_customer_invoice
				&search[login]={$aUserCustomer.login}
				&search[date_type]=cart
				&search_date=1
				&search[date_from]={$smarty.now-120*86400|date_format:"%d.%m.%Y"}
				&search[date_to]={$smarty.now|date_format:"%d.%m.%Y"}{/strip}">
					{$oLanguage->GetMessage("Open all")}
				</a>
			</td>
		</tr>
</table>
<span class="span_h1">{$oLanguage->getMessage("Price search log")}</span>
<table class="datatable">
		<tr>
			<th>{$oLanguage->getMessage("Data")}</th>
			<th>{$oLanguage->getMessage("Make")}</th>
			<th>{$oLanguage->getMessage("Code")}</th>
		</tr>
		{foreach from=$aDashboardPriceSearchLog item=aValue name=priceSearchForeach}
		<tr class="{if $smarty.foreach.priceSearchForeach.index % 2== 0}even{else}none{/if}">
			<td>{$aValue.post_date}</td>
			<td>{$aValue.cat_name}</td>
			<td>{$aValue.code}</td>
		</tr>
		{/foreach}
		<tr>
			<td colspan=3 class="tbDet-footer" align=right>
				<a target="_blank" class="show" href="/?action=price_search_log&id_user={$aUserCustomer.id}">
				{$oLanguage->GetMessage("Open all")}</a>
			</td>
		</tr>
</table>