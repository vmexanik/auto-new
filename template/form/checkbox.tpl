<input 
type=checkbox 
{if $aInput.name}name='{$aInput.name}'{/if} 
value='{$aInput.value}'
{if $aInput.checked}checked{/if}
{if $aInput.disabled} disabled {/if}
{if $aInput.class} class='{$aInput.class}'{else} class="js-checkbox"{/if}
{if $aInput.onclick} onclick="{$aInput.onclick}"{/if}
>