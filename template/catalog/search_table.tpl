{literal}
<script type="text/javascript">
   $(document).ready(function(){
       $(".pt-popup-block .close").click(function() {
           $(this).parent().parent().parent().fadeOut("slow");
           return false;
       });
   });
</script>
{/literal}
{if $sHeader}
    <div class="at-user-details">
	    <div class="header">
	        {$sHeader}{if $sHint}{$sHint}{/if}
	    </div>
	</div>    
{/if}

<div class="at-layer-mid" style="padding:0">
    <div class="at-plist-list">
		{assign var="iTr" value="0"}
		{section name=d loop=$aItem}
		{assign var=aRow value=$aItem[d]}
		{assign var=iTr value=$iTr+1}
			{include file=$sDataTemplate}
		{/section}
    </div>
    
    {if !$aItem}

        {if $sNoItem}
    		{$oLanguage->getMessage($sNoItem)}
    	{else}
    		{$oLanguage->getMessage("No items found")}
    	{/if}
    	
    {/if}

	{if $sStepper}
		{$sStepper}
	{/if}
</div>
<div class="clear"></div>
<br>

<div class="pt-popup-block" id="popup_id" style="display: none;">
    <div class="dark" onclick='$("#popup_id").hide();'>&nbsp;</div>
    <div class="block">
        <div class="caption drag">
            <a href="#" class="close">&nbsp;</a>
            <span id="popup_caption_id">{if $sPopupCaption}{$sPopupCaption}{else}Popup{/if}</span>
        </div>
        <div class="content">
			<div id="popup_content_id">
            	{$sPopupContent}
			</div>
        </div>
    </div>
</div>