<textarea 
name='{$aInput.name}'
{if $aInput.disabled} disabled {/if}
{if $aInput.class}class='{$aInput.class}'{/if} 
{if $aInput.style}style='{$aInput.style}'{/if} 
{if $aInput.cols}cols='{$aInput.cols}'{/if} 
{if $aInput.rows}rows='{$aInput.rows}'{/if} 
>
{if $aInput.value}{$aInput.value}{/if}
</textarea>