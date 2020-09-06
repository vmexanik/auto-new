{literal}
<script  type="text/javascript">
function addSelect(ii) { 
	var oTr;
	oTr=$('#id_select').clone();
	oTr.removeAttr("id").attr("id","select"+ii).attr("name","u["+ii+"]").insertBefore("#a"+ii);
	$("#a"+ii).remove();
	$("#add"+ii).show();

	$("#select"+ii).searchable({
		maxListSize: 100,
		maxMultiMatch: 150,
		wildcards: true,
		ignoreCase: true,
		latency: 10,
		warnNoMatch: 'no matches ...',
		zIndex: 'auto'
	});
}
function showNew(ii) { 
	$("#form_new_cat").show();
	$("#opaco2").show();
	$("#text_id").val(ii);
}
function hideNew() { 
	$("#form_new_cat").hide();
	$("#opaco2").hide();
	$("#text_id").val(0);
}
function addNew() {
	var ii;
	ii=$("#text_id").val();
	if($("#text_pref").val().length<2 || $("#text_pref").val().length>3){
		alert("{/literal}{$oLanguage->getMessage("Pref 2-3 symbol")}{literal}");
		return;
	}
	if($("#text_name").val().length<2 || $("#text_title").val().length<2){
		alert("{/literal}{$oLanguage->getMessage("More 3 symbol")}{literal}");
		return;
	}
	if(ii>0){
		try{
		$.ajax({
			type: "POST",
			url: "/?action=price_add_cat",
			data: "name="+$("#text_name").val()+"&pref="+$("#text_pref").val()+"&title="+$("#text_title").val(),
			success:function(response){
				if(response[0]!='<'){
					alert(response);
					return;
				}else{
					$("#id_select").append( $(response));
					$("#select"+ii).append( $(response));
					$("#select"+ii+" :last").attr("selected", "selected");
					$("#add"+ii).hide();
					hideNew();
				}
			},
			error: function (request, status, error) {
				alert(request.responseText);
				hideNew();
			} 
		});
		}catch(err){
			alert("Error: "+err.message);
		}
	}
}
function addSelectWithPref(ii,response) { 
	var oTr;
	oTr=$('#id_select').clone();
	oTr.removeAttr("id").attr("id","select"+ii).attr("name","u["+ii+"]").insertBefore("#a"+ii);
	$("#a"+ii).remove();
	$("#adel"+ii).remove();
	
	$("#id_select").append(response);
	$("#select"+ii).append(response);
	$("#select"+ii+" :last").attr("selected", "selected");
}
$("#opaco2").click(function() {
	hideNew();
});
</script>
{/literal}
<div id="form_new_cat" style="z-index: 102;position: fixed;top:200px;left:300px;display:none;background-color: #C2D8F7;text-align: right;padding:5px;">
	{$oLanguage->getMessage("Name")}{$sZir}:<input type="text" id="text_name"><br>
	{$oLanguage->getMessage("Pref")}{$sZir}:<input type="text" id="text_pref"><br>
	{$oLanguage->getMessage("Title")}{$sZir}:<input type="text" id="text_title"><br>
	<center><a href="javascript: addNew()">{$oLanguage->getMessage("Add")}</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: hideNew()">{$oLanguage->getMessage("Return")}</a></center>
	<input type="hidden" id="text_id" value="0">
</div>
<table cellspacing=0 cellpadding=5 class="datatable">
<tbody>
<tr>
<th width="30%">{$oLanguage->getMessage("Catalog in price")}</th>
<th width="290px" style="min-width: 290px;">{$oLanguage->getMessage("Catalog in system")}</th>
<th>&nbsp;</th>
</tr>
<tr style="display: none;"><td colspan="2">{html_options name=sampl options=$aPref id="id_select" style='width:270px'}</td></tr>

{foreach item=con key=key from=$aCat}
<tr id="tr_{$key}">
<td>{$con|escape:'html'}</td>
<td>
	<a id="a{$key}" href="javascript: addSelect({$key})">{$oLanguage->getMessage("Check")}</a>
	{*<a style="display:none" id="add{$key}" href="javascript: showNew({$key})">[+]</a>*}
</td>
<td nowrap>
	<a href="" onclick="document.getElementById('form_conformity').submit(); return false;">
	<img border="0" class="action_image" src="/libp/mpanel/images/small/checks.png" hspace="3" align="absmiddle"> {$oLanguage->getMessage("price_conformity")}</a>
	<br>
	<a href="/?action=price_add_auto_pref&cat={$con}&id={$key}" onclick="
		if (confirm('{$oLanguage->getMessage("you shure to add auto pref?")}'))
		{literal} { {/literal}
		xajax_process_browse_url(this.href);$('#tr_{$key}').hide();
		{literal} } {/literal}
		return false;">
	<img border="0" class="action_image" src="/libp/mpanel/images/small/add2.png" hspace="3" align="absmiddle"> {$oLanguage->getMessage("Add auto pref")}</a>
	<br>
	<a id="adel{$key}" href="/?action=price_remove_pref&pref={$key}" onclick="if (confirm('{$oLanguage->getMessage("you shure to delete pref?")}'))
xajax_process_browse_url(this.href);  return false;">
	<img border="0" class="action_image" src="/libp/mpanel/images/small/del.png" hspace="3" align="absmiddle"> {$oLanguage->getMessage("Delete")}</a>
</td>
</tr>    
{/foreach}
</tbody>
</table>
