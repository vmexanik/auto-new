<table>
			{foreach from=$aFilter item=aItem}
			<tr>
				<td width="100%">{$aItem.name}:</td>
				{assign var=sTable value='id_'|cat:$aItem.table_}
				<td>{html_options name=data[$sTable] options=$aItem.params selected=$aData.$sTable}</td>
			</tr>
			{/foreach}
</table>