{literal}
<script type="text/javascript">
$(document).ready(function() {
	//popup close
	$('.pt-popup-block .close').click(function() {
		$(this).parent().parent().parent().fadeOut('slow');
		return false;
	});
});
</script>
{/literal}
<div id="opaco2" style="display:none; background-color: #777; z-index: 101; left:0; top:0; position: fixed; width: 100%;height: 4000px;
	 filter:progid:DXImageTransform.Microsoft.Alpha(opacity=50);-moz-opacity: 0.5;-khtml-opacity: 0.5;opacity: 0.5;"></div>
<div class="pt-popup-block" id="popup_id"  style="display: none;">
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