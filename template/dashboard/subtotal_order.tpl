{if $aRow.id}<tr>
	<td colspan=7 class="tbDet-footer" align=right>
		<a class="show"
href="/?action=cart_order&search[order_status]={if !$smarty.request.status}all_except_archive{else}{$smarty.request.status}{/if}">
		{$oLanguage->getMessage("Open all")}
		</a>
	</td>
</tr>{/if}
