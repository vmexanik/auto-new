{if $smarty.get.table_error}
<div class="error_message">{$oLanguage->getMessage($smarty.get.table_error)}</div>
{/if}

{if $sTableMessage}<div class="{$sTableMessageClass}">{$sTableMessage}</div>{/if}

<div class="at-plist-list">
{assign var="iTr" value="0"}
{section name=d loop=$aItem}
{assign var=aRow value=$aItem[d]}
{assign var=iTr value=$iTr+1}
{include file=$sDataTemplate}
{/section}
</div>