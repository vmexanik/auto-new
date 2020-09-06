<input 
type='hidden' 
{if $aInput.name}name='{$aInput.name}'{/if}
value='{$aInput.value}'
{if $aInput.id}id='{$aInput.id}'{/if}
{if $aInput.readonly} readonly {/if} 
>