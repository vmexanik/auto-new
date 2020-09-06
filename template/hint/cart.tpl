{if $aCart}
<span>
<a href="javascript:;" onclick="show_hide('cart_{$sSection}_{$iRefId}','none')"
	><img src='/image/comment.png' hspace=1 align=absmiddle
		>&nbsp;<span id='cart_link_popup_id_{$iRefId}'
		>{$oLanguage->getMessage("Cart Details")}</span></a>

<div align=left
	style=" display: none;	width: 500px; z-index:10000;" class="tip_div" id="cart_{$sSection}_{$iRefId}">

	<p>
{assign var=i value=0}
{foreach from=$aCart item=aItem}
	{assign var=i value=$i+1}

	{$i}) <b>{$aItem.id}</b> <font color=blue>{$aItem.cat_name} {$aItem.code}</font>: <b>{$aItem.pr_name} {$aItem.prw_name}</b>
	<font size=-2>{$aItem.name}<font color=green>{$aItem.russian_name}</font></font>
	<font color=blue>
	{if $aItem.is_store_kiev}&nbsp;{$oLanguage->getMessage("is_store_kiev")}{/if}
	{if $aItem.is_store_germany}&nbsp;{$oLanguage->getMessage("is_store_germany")}{/if}
	{if $aItem.is_store_germany_center}&nbsp;{$oLanguage->getMessage("is_store_germany_center")}{/if}
	{if $aItem.is_store_aoe}&nbsp;{$oLanguage->getMessage("is_store_aoe")}{/if}
	{if $aItem.is_in_road}&nbsp;{$oLanguage->getMessage("is_in_road")}{/if}
	</font>
	<br>
{/foreach}
	</p>

</div>
</span>
{/if}