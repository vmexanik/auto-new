{foreach key=sKey item=item from=$oTable->aColumn}

{if $sKey=='action'}
<td>
<div class="order-num">
	<a href="/?action=price_control_change_code_edit&id={$aRow.id}&return={$sReturn|escape:"url"}"
		><img src="/image/edit.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("Edit")}</a>
	</br>
	<a href="/?action=price_control_change_code_del&id={$aRow.id}"
	   onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete this item?")}')) return false;"
		><img src="/image/delete.png" border=0 width=16 align=absmiddle alt="{$oLanguage->getMessage("Delete")}" 
		  title="{$oLanguage->getMessage("Delete")}" />{$oLanguage->getMessage("Delete")}</a>
</div>
</td>
{else}
<td>
<div class="order-num">
  <span>{$item.sTitle}</span>
    <p>{$aRow.$sKey}</p>
</div>
</td>
{/if}
{/foreach}