{if $sTitle || $sAdditionalTitle}
<div class="at-user-details">
    <div class="header">
        {$sTitle}{$sAdditionalTitle}{$sHint}
    </div>
</div>
{/if}

{if $sFormError || $smarty.get.form_error}<div class=error_message>{$sFormError}
	{$oLanguage->getMessage($smarty.get.form_error)}</div>{/if}

{if $sFormMessage}<div class="{$sFormMessageClass}">{$sFormMessage}</div>{/if}

<FORM {$sHeader}>
{$sHidden}

<div class="inner-part">
    <div class="at-block-form">
    {$sBeforeContent}
    	{$sContent}
    {$sAfterContent}
    </div>
</div>

{if $sButtonDivClass}<div class="{$sButtonDivClass}">{/if}

{if $sReturnButton && !$bReturnAfterSubmit}
<span {if $sButtonSpanClass}class="button"{else} style="padding:{$sButtonsPadding}px 0 0 0;" {/if}>
<input type=button class='{$sReturnButtonClass}' value="{$sReturnButton}"
	onclick="location.href='{if !$bNoneDotUrl}.{/if}/{if !$bAutoReturn}?action={/if}{$sReturnAction}'" >
</span>
{/if}


<span {if $sButtonSpanClass && $sSubmitButton}class="button"{else} style="padding:{$sButtonsPadding}px 0 0 0;" {/if}>
{if $sSubmitButton}
<input style="width:100%;" type=submit class='{$sSubmitButtonClass}' value="{$sSubmitButton}"
	{if $bConfirmSubmit}
		onclick="if (!confirm('{$oLanguage->getMessage($sConfirmText)}')) return false;"
	{/if}
	 >
{/if}

{if $sReturnButton && $bReturnAfterSubmit}
<span {if $sButtonSpanClass}class="button"{else} style="padding:{$sButtonsPadding}px 0 0 0;" {/if}>
<input type=button class='{$sReturnButtonClass}' value="{$sReturnButton}"
	onclick="location.href='{if !$bNoneDotUrl}.{/if}/{if !$bAutoReturn}?action={/if}{$sReturnAction}'" >
</span>
{/if}




{if $sSubmitAction}<input type=hidden name=action value='{$sSubmitAction}'>{/if}

{if $bAutoReturn}
	<input type=hidden name=return value='{$sReturnAction}'>
{/if}

{if $sAdditionalButtonTemplate} {include file=$sAdditionalButtonTemplate} {/if}

{$sAdditionalButton}
</span>

{if $bIsPost}
<input type=hidden name=is_post value='1'>
{/if}


{if $sButtonDivClass}</div>{/if}

{if $bShowBottomForm}
</FORM>
{/if}

<br>