<td>
    <div class="order-num">{$oLanguage->GetMessage('#')}</div>
    {$aRow.id}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('customer')}</div>
    {assign var="Id" value=$aRow.id_user|cat:"_"|cat:$aRow.id}
    {$oLanguage->AddOldParser('customer_uniq',$Id)}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('date')}</div>
    {$aRow.post_date}<br>
    <div class="order-num">{$oLanguage->GetMessage('account_name')}</div>
    <i>{$aRow.account_name}</i>
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('total')}</div>
    {$aRow.total}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('post manager')}</div>
    {$aRow.post_manager}</b>
    <br>
    <div class="order-num">{$oLanguage->GetMessage('end manager')}</div>
    {$aRow.end_manager}
    {include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_end}
</td>
<td nowrap>

<a href="/?action=manager_invoice_customer_print&nodata=yes&id={$aRow.id}" target=_blank
	><img src="/image/fileprint.png" border=0  width=16 align=absmiddle />
		{$oLanguage->getMessage("Print without data")}</a>

<a href="/?action=manager_invoice_customer_print&id={$aRow.id}" target=_blank
	><img src="/image/fileprint.png" border=0  width=16 align=absmiddle />
		{$oLanguage->getMessage("Print")}</a>

<br>
{if !$aRow.is_end}
<a href="/?action=manager_invoice_customer_invoice&subaction=end&id={$aRow.id}&return={$sReturn|escape:"url"}"
	><img src="/image/tooloptions.png" border=0  width=16 align=absmiddle />{$oLanguage->getMessage("End")}</a>
{/if}


<a href="/?action=manager_invoice_customer_invoice_edit&id={$aRow.id}&return={$sReturn|escape:"url"}"
	><img src="/image/edit.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("Invoice Edit")}</a>


{if !$aRow.is_sent && !$aRow.is_end}
<br>
<a href="/?action=manager_invoice_customer_invoice&subaction=cancel&id={$aRow.id}&return={$sReturn|escape:"url"}"
	onClick="return (confirm('{$oLanguage->getMessage("Are you sure?")}'))"
	><img src="/image/delete.png" border=0 width=16 align=absmiddle hspace=1/>{$oLanguage->getMessage("Cancel invoice")}</a>
{/if}


</td>