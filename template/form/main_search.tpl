<div class="form_body">

<div class="at-user-details js-form-button">
    <div class="header">
       
        <span onclick="$('.js-form-content').toggle();" class="filtr">Фильтр 
       
            <i style="position: absolute;width: 19px;height: 11px;margin: 8px 0 0 6px;background: url(/image/icons.png) no-repeat 0px -65px;"></i>
        </span>
    </div>
</div>

{if $sFormMessage}<div class="{$sFormMessageClass}">{$sFormMessage}</div>{/if}

<div class="js-form-content" {if !$smarty.request.search}style="display: none;"{/if}>
    {if $sTitle || $sAdditionalTitle}
    <div class="at-user-details">
        <div class="header">
            {$sTitle}{$sAdditionalTitle}{$sHint}
        </div>
    </div>
    {/if}
    
    {if $sFormError || $smarty.get.form_error}<div class=error_message>{$sFormError}
    	{$oLanguage->getMessage($smarty.get.form_error)}</div>{/if}
    
    <FORM {$sHeader}>
    {$sHidden}
    
    <div class="at-block-form" style="background-color: #ffffff;box-shadow: 0 0 10px #cadae2;margin: 0 0 20px 0;">
    {$sBeforeContent}
    	{$sContent}
    {$sAfterContent}
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
    <input type=submit class='{$sSubmitButtonClass}' value="{$sSubmitButton}"
    	{if $bConfirmSubmit}
    		onclick="if (!confirm('{$oLanguage->getMessage($sConfirmText)}')) return false;"
    	{else}
            {if $sHeader|strpos:"id_order_report_form"}onclick="change_form_action('id_order_report_form','{$sSubmitAction}');"{/if}
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

</div>
</div>
<br>