<span id="id_synonym">
<script src="/js/jquery.scrollTo.min.js?2" type="text/javascript"></script>
<table style="width: 200px">
	{if $aSynonym}
	{foreach from=$aSynonym item=synonym key=key}
	<tr>
		<td>
			{if $synonym.is_main>0}
				<b>{$synonym.name}</b>
			{else}
				{$synonym.name}
			{/if}
		</td>
		<td>
			{if $synonym.name && $key>=0}
			<a href="#" onclick="if(!confirm('{$oLanguage->GetMessage('are you sure you want to delete this items?')}')) return false;
				xajax_process_browse_url('?action=manager_synonym&delete={if $synonym.is_main}2{else}1{/if}&brand={$synonym.name|escape:'url'}');"
				><img src="/image/delete.png"</a>
			{/if}
		</td>
	</tr>
	{/foreach}
	{else}
	<tr>
		<td>
			{$oLanguage->GetMessage('not found linked prefix')}
		</td>
	</tr>
	{/if}
</table>
<input type="hidden" name="cat_id" value="{if $iCatId}{$iCatId}{else}0{/if}">
</span>