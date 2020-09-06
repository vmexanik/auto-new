<label><input 
type="radio" 
value='{$aInput.value}' 
name='{$aInput.name}'
{if $aInput.onclick}onClick="{$aInput.onclick}"{/if} 
{if $aInput.checked} checked {/if}
> 
{$aInput.caption}
</label>