<select name=data[city] id="city" style='width:270px' onchange="javascript:
xajax_process_browse_url('?action=novaposhta&amp;city='+$('#city :selected').val() +'&amp;area='+ $('#state :selected').val() +'&amp;ServiceType='+ $(&quot;input[name*='ServiceType']:checked&quot;).val());
return false;">
{html_options options=$aCity selected=$smarty.request.data.city}
</select>