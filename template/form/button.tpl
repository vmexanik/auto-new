<input 
type=button 
{if $aInput.class}class='{$aInput.class}'{/if} 
{if $aInput.id}id='{$aInput.id}'{/if}
value="{$oLanguage->GetMessage($aInput.value)}"				
{if $aInput.onclick}onclick="{strip}{$aInput.onclick}{/strip}"{/if}
{if $aInput.readonly}readonly='{$aInput.readonly}'{/if} 
>
