{if $aInput.checkbox}<input type=checkbox name=search[date] value=1 {if $smarty.request.search.date}checked{/if} class="js-checkbox">{/if}

<input 
{if $aInput.id}id='{$aInput.id}'{/if}  
name='{$aInput.name}' 
type='text' 
style='width:100% !important; {if $aInput.checkbox}max-width: 122px;{else}max-width: 145px;{/if}'
{if $aInput.readonly}readonly{/if} 
value='{$aInput.value}'
{if $aInput.onclick}onclick="{$aInput.onclick}"{/if}
>