{if $aInput.checkbox}<input type=checkbox name=search[amount] value='1' {if $smarty.request.search.amount}checked{/if} class="js-checkbox">{/if}

<input 
type='text' 
{if $aInput.name}name='{$aInput.name}'{/if} 
value='{$aInput.value}'
{if $aInput.style}style="{$aInput.style}"
{else}
 style='{if $aInput.checkbox}max-width: 870px;{else}max-width: 100%;{/if}'
{/if}
{if $aInput.id}id='{$aInput.id}'{/if} 
{if $aInput.onclick}onclick="{$aInput.onclick}"{/if}
{if $aInput.placeholder}placeholder="{$aInput.placeholder}"{/if}
{if $aInput.class}class="{$aInput.class}"{/if}
{if $aInput.onfocus}onfocus="{$aInput.onfocus}"{/if}
{if $aInput.autocomplete}autocomplete='{$aInput.autocomplete}'{/if}
{if $aInput.onblur}onblur="{$aInput.onblur}"{/if}
{if $aInput.maxlength}maxlength="{$aInput.maxlength}"{/if}
{if $aInput.readonly} readonly {/if} 
>