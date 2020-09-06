		<hr>
		<p align='center'>{$oLanguage->getMessage('link price data to col profile field')}</p>
		<br>
		<table>
		<tr><td>
			<p align='left'>
				{$oLanguage->getMessage('see_count_cols')}: {$oLanguage->GetConstant('limit_load_lines_view_create_profile',10)}<br>
				{$oLanguage->getMessage('see_offset_cols')}: {if ($see_offset_cols)}{$see_offset_cols}{else}1
				{assign var='see_offset_cols' value='1'}
				{/if}<br>
				{$oLanguage->getMessage('see_codepage')}: {if ($see_codepage)}{$see_codepage}{else}<span style="color:grey">{$oLanguage->getMessage('not set')}</span>{/if}<br>
				{$oLanguage->getMessage('see_delimiter')}: {if ($see_delimiter)}{$see_delimiter}{else}<span style="color:grey">{$oLanguage->getMessage('not set')}</span>{/if}
			</p>
		</td>
		<td>
		<a href="javascript:xajax_process_browse_url('?action=price_profile_change_view_loaded_price&data[delimiter]='+
			$('#delimiter option:selected').val()+'&data[row_start]='+
			$('#row_start').val()+'&data[charset]='+
			$('#charset').val()+'&data[file]={$sLocalFile}');return false;"><img src="/image/reload_page.png"></a>
		</td>
		</tr>
		</table>
		<br>
	    <table style="border:1px solid black;">
	    	<tr>
	    		<td>{$oLanguage->getMessage("№")}</td>
	    		{foreach from=$aData.aInData item=aItem name=menu key=sKey}
	    			{assign var='iKey' value=$smarty.foreach.menu.index+1}
	    			<td><select name="col[{$iKey}]">
	    				{html_options options=$aSelectCol selected=$aData.aCol.$iKey}
	    				</select>
	    			</td>
	    		{/foreach}			
	    	</tr>
		{foreach from=$aData.aInData item=aItem name=menu key=sKey}
		    <tr>
		    <td>
		    	{$see_offset_cols++}
		    </td>
			{if $aItem|@count > 0}
			    {foreach from=$aItem item=aItemRow name=menuRow key=sKeyRow}
				<td style="border:1px solid black;"align='center'>{$aItemRow}</td>
			    {/foreach}
			{/if}
		    </tr>
		{/foreach}
	    </table>