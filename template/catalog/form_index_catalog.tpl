{literal}<script type="text/javascript" charset="utf8">
		$(function(){
			$("select#ctlMake").change(function(){
				$.getJSON("/?action=catalog_json_get",{id_make: $(this).val()}, function(j){
					var options = '<option value="0">{/literal} {$oLanguage->getMessage("Choose model")}{literal}</option>';
					for (var i = 0; i < j.length; i++) {
						options += '<option value="' + j[i].id + '">' + j[i].name + '</option>';
					}
					$("#ctlModel").html(options);
					$("#ctlModelDetail").html("");
				})
			})
		})
		$(function(){
			$("select#ctlModel").change(function(){
				location.href="/?action=catalog_detail_model_view&data[id_make]="+$("#ctlMake").val()+"&data[id_brand]="+$("#id_brand").val()+"&data[id_model]="+$(this).val();
			})
		})
</script>{/literal}

<table width=100% border=0>
   	<tr>	
   		<td width="100px"><b>{$oLanguage->getMessage("Make")}:</b></td>
   		<td><select id="ctlMake" name="data[id_make]" style="width: 390px;">
			{html_options options=$aMake selected=$smarty.request.data.id_make}
			</select>
		</td>
  	</tr>
  	<tr>	
   		<td><b>{$oLanguage->getMessage("Model")}:</b></td>
   		<td><select id="ctlModel" name="data[id_model]" style="width: 390px;"">
			{html_options options=$aModel selected=$smarty.request.data.id_model}
			</select>
		</td>
  	</tr>
  	<input type="hidden" value="{$smarty.request.data.id_brand}" id="id_brand">
</table>