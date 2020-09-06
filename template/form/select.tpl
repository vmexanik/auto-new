{if $aInput.checkbox}<input type=checkbox name=search[method_is] value='1' {if $smarty.request.search.method_is}checked{/if}class="js-checkbox">{/if}

<select 
name='{$aInput.name}' 
{if $aInput.class}class='{$aInput.class}'{else}class="js-select"{/if}
{if $aInput.id}id='{$aInput.id}'{/if}
{if $aInput.onchange}onChange="{strip}{$aInput.onchange}{/strip}"{/if}
{if $aInput.disabled} disabled {/if}

{if $aInput.style}style='{$aInput.style}'{elseif $aInput.checkbox} style='width: 130px;'{/if}
>
	{html_options options=$aInput.options selected=$aInput.selected}
</select>