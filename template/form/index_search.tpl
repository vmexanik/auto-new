<table>
<tbody>
{foreach name=main from=$aField item=aItem}
{assign var="field_template" value='form/'|cat:$aItem.type|cat:'.tpl'}
	{if $aItem.type == 'hidden'}
		{include file=$field_template aInput=$aItem}
	{else}
    <tr>
        {if $aItem.title}
        <td>
            <b>{$oLanguage->getMessage($aItem.title)}:</b>{if $aItem.szir}{$sZir}{/if}
			{if $aItem.contexthint} {$oLanguage->getContextHint($aItem.contexthint)}{/if}
		</td>
		{/if}
		<td>
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
</tbody>
</table>