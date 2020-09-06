<table width="100%" border="0">
{foreach name=main from=$aField item=aItem}
{assign var="field_template" value='form/'|cat:$aItem.type|cat:'.tpl'}
	{if $aItem.type == 'hidden'}
		{include file=$field_template aInput=$aItem}
	{else}
		<tr 
		  {if $aItem.tr_class}class='{$aItem.tr_class}'{/if}
		  {if $aItem.tr_id}id='{$aItem.tr_id}'{/if}
		  {if $aItem.tr_style}style='{$aItem.tr_style}'{/if}
		>
			{if $aItem.title}
				<td>
					<div class="field-name">{$oLanguage->getMessage($aItem.title)}:{if $aItem.szir}<i>*</i>{/if}</div>
					{if $aItem.contexthint} {$oLanguage->getContextHint($aItem.contexthint)}{/if}
				</td>
			{/if}
			<td 
				{if $aItem.colspan}colspan={$aItem.colspan}{/if}
				{if $aItem.td_style}style='{$aItem.td_style}'{/if}
				{if $aItem.td_class}class='{$aItem.td_class}'{/if}
				{if $aItem.nowrap} nowrap {/if}
			>
				{include file=$field_template aInput=$aItem}{if $aItem.br}<br />{/if}
				{if $aItem.add_to_td}
					{foreach name=add_to_td from=$aItem.add_to_td item=aItem2}
						{assign var="field_template2" value='form/'|cat:$aItem2.type|cat:'.tpl'}
						{include file=$field_template2 aInput=$aItem2}{if $aItem2.br}<br />{/if}
					{/foreach}  
				{/if}
			</td>
		</tr>
	{/if}
{/foreach}
</table>