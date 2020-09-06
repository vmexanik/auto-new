<script src="/js/popcalendar.js"></script>

<table width=100% border=0>
	<!-- <tr>
		<td colspan="2" nowrap><b>{$oLanguage->getMessage("DFrom")}:</b>
			<input id=date_from name=date_from  style='width:60px;'
				readonly value='{if $smarty.request.date_from}{$smarty.request.date_from}{else}{$smarty.now-24*60*60|date_format:"$date_format"}{/if}'
   			 	onclick="popUpCalendar(this, document.getElementById('date_from'), 'dd.mm.yyyy')">
		
		&nbsp;<b>{$oLanguage->getMessage("DTo")}:</b>
		<input id=date_to name=date_to  style='width:60px;'
				readonly value='{if $smarty.request.date_to}{$smarty.request.date_to}{else}{$smarty.now|date_format:"$date_format"}{/if}'
   			 	onclick="popUpCalendar(this, document.getElementById('date_to'), 'dd.mm.yyyy')">
	</td> -->
	</tr>
	   	<tr>	
   		<td><b>{$oLanguage->getMessage("Number Query")}:</b></td>
   		<td><input type="text" name="_type"></td>
  	</tr>
  	<!--</tr>
	   	<tr>	
   		<td><b>{$oLanguage->getMessage("Description")}:</b></td>
   		<td><textarea name="data[description]" cols="23"></textarea></td>
  	</tr>
	</tr>
	   	<tr>	
   		<td><b>{$oLanguage->getMessage("File to import")}:</b></td>
   		<td><input type=file name=import_file></td>
  	</tr>-->
</table>