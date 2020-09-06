{literal}
<script type="text/javascript">
$(document).ready(function () {
	{/literal}
	setTimeout(hide_status, {$iTimeShow});
		var $current={if $iCurrentScannedCart}{$iCurrentScannedCart}{else}-1{/if};
	
	{literal}
	//change colors...
	var $inputs = $('#table_form :input[name=row_check[]]');
	$inputs.each(function() {
		if($(this).is(':checked'))
			$(this).parent().parent().css("background-color","#99CC66");
		else 
			if($(this).val()==$current)
				$(this).parent().parent().css("background-color","#FF9966");
	});
});
function hide_status(){
	$("#scan_status_div_ok").fadeOut("slow");
	$("#scan_status_div_fail").fadeOut("slow");
	$("#scan_status_div_many").fadeOut("slow");
}
 </script>
 <style type="text/css">
 	#scan_statuses div{
 		height:50px;
 		width:100%;
 		font-size:2em;
 		margin-top:20px;
 	}
 </style>
 {/literal}
 <div id="scan_statuses">
 <div id="scan_status_div_ok" style="color: green;display:{if $sRes=='ok'} block {else} none {/if};">
 {$oLanguage->getMessage("Scan ok")}
 </div>
 
 <div id="scan_status_div_fail" style="color: red;display:{if $sRes=='error'} block {else} none {/if};">
{$oLanguage->getMessage("Scan error")}
 </div>
 
 <div id="scan_status_div_many" style="color:red; display:{if $sRes=='many'} block {else} none {/if};">
{$oLanguage->getMessage("Scan too many")}
 </div>
 </div>
 
 {$sSound}