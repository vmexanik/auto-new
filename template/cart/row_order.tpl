<td>
    <div class="order-num">{$oLanguage->GetMessage('#')}</div>
    {$aRow.id}
    <div class="order-num">{$oLanguage->GetMessage('id cart package')}</div>
    {$aRow.id_cart_package}
</td>
<td>{if $aRow.code_visible || $aRow.order_status!='pending'}
        <div class="order-num">{$oLanguage->GetMessage('code')}</div>
		{$aRow.code}
		{if $aRow.code_changed}
			<font color=red>{$aRow.code_changed}</font><br>
		{/if}
	{else}
		<i>{$oLanguage->getMessage("cart_invisible")}</i>
	{/if}
	<br>
	<div class="order-num">{$oLanguage->GetMessage('brand')}</div>
	<b>{$aRow.brand_title}</b>
	{if $aRow.new_brand}
			<font color=red>{$aRow.new_brand_title}</font><br>
	{/if}
</td>
<td>
<div class="order-num">{$oLanguage->GetMessage('order status')}</div>
{if $aRow.is_endable}<font color="blue"><b>{$oLanguage->GetMessage('ie')}</b></font>&nbsp;{/if}

{if $aRow.order_status=='refused'}<nobr><a
	href="{strip}/price/{$aRow.code}/{/strip}" target=_blank
	onmouseover="show_hide('reorder_{$aRow.id}','inline')" onmouseout="show_hide('reorder_{$aRow.id}','none')"
	><img src="/image/redo.png" border=0 width=16 align=absmiddle />
<font color=red><b>{$oLanguage->getMessage("CReorder")}</b></font></a></nobr>

<div style="display: none; " align=left class="tip_div" id="reorder_{$aRow.id}">
	<div>{$oLanguage->getText('Reorder hint')}</div>
</div>
{/if}


{$oLanguage->getOrderStatus($aRow.order_status)}
{if $aRow.history}
<br>
<strong><a href="#" onmouseover="show_hide('history_{$aRow.id}','inline')" onmouseout="show_hide('history_{$aRow.id}','none')"
	onclick="return false"><img src='/image/comment.png' border=0 align=absmiddle hspace=2>
	{$oLanguage->getMessage("History")}</a>&nbsp;</strong>
<div style="display: none; " align=left class="tip_div" id="history_{$aRow.id}">
	{foreach from=$aRow.history item=aHistory}
		<div>
		 {$oLanguage->getOrderStatus($aHistory.order_status)} -  {$oLanguage->getDateTime($aHistory.post)}<br>
		{$aHistory.comment}
		</div>
	{/foreach}
</div>

{/if}
</td>
<td>
<div class="order-num">{$oLanguage->GetMessage('part name')}</div>
<div style="width:270px;overflow:auto;word-break:word-break:break-word;">
{if $aRow.is_archive} <font color=silver>{/if}
	{$oContent->PrintPartName($aRow)}

	{if $aRow.manager_comment}
		<br><font color=brown><b>{$oLanguage->getMessage("LastManagerComment")}</b>: {$aRow.manager_comment}</font>
	{/if}
	{if $aRow.sign!='' || $aRow.customer_id}<br>{/if}
	<font color=braun><b>{$aRow.sign}</b></font>
	<font color=red>{$aRow.customer_id}</font>
	{if $aRow.customer_comment && $aRow.customer_comment != ''}
		<br><font color="#9B9B9B">{$aRow.customer_comment}</font>
	{/if}

{if $aRow.is_private_parsed || $aRow.private_comment}
	<br><font color=blue>{$oLanguage->getMessage("PendingParsed")}:
	{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_private_parsed}
	</font>
{/if}
</div>
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('term')}</div>
    {$aRow.term}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('number')}</div>
    {$aRow.number}

    <div class="order-num">{$oLanguage->GetMessage('total')}</div>
    {$oCurrency->PrintPrice($aRow.total,1)}
</td>

{*<td nowrap style="width: 100px;">
{if $aRow.order_status=='new'}
<a href="/?action=cart_order_edit&id={$aRow.id}"
	><img src="/image/edit.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("Edit")}</a>
<br>
{/if*}


{*if $aRow.order_status=='pending'}


<!--a href="/?action=cart_package_print_bill&id={$aRow.id_cart_package}" target=_blank
	><img src="/image/fileprint.png" border=0 width=16 align=absmiddle hspace=1/>{$oLanguage->getMessage("Print Bill Package")}
		#{$aRow.id_cart_package}</a>
<br-->

<a href="/?action=cart_package_order&id={$aRow.id_cart_package}&section=work"
	><img src="/image/apply.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("Send to Work Package")}
		#{$aRow.id_cart_package}</a>
{/if}

</td>*}