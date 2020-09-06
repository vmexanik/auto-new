<link rel="stylesheet" href="/css/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="/libp/jquery/jquery.thickbox.js"></script>
<form>
<table width=100% border=0>
   	<tr>	
   		<td width="100px"><b>{$oLanguage->getMessage("Type mine")}:</b></td>
   		<td align="center" width="50%" nowrap><input name=data[type_number] type="text" value="{$smarty.request.data.type_number}"  style="width:187px">
			<input name="action" value="catalog_detail_model_view" type="hidden">
			{if !$bShowButtonTypemines}&nbsp;&nbsp;<input class="at-btn" value="{$oLanguage->getMessage("Search")}" type="submit">{/if}
   		</td>
   		{if $bShowButtonTypemines}
   		<td rowspan="2">
   		<a class="thickbox" href="/image/design/typemine.jpg" title="{$oLanguage->getMessage("Type mine info under picture")}">
   		<img src="/image/passport.gif" height="100px"></a>   		
   		</td>
   		{/if}
  	</tr>
  	{if $bShowButtonTypemines}
   	<tr>	
   		<td width="100px"></td>
   		<td align="center" width="40%"><input class="at-btn" value="{$oLanguage->getMessage("Search")}" type="submit"> &nbsp;&nbsp;&nbsp;</td>
  	</tr>
  	{/if}
</table>
</form>
