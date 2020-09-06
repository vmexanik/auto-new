<table>
<tr>
	<td width=50%><b>{$oLanguage->getMessage("Name Profile")}:</b>{$sZir}</td>
	<td><input type=text name=data[name] value='{$aData.name}' maxlength=50 style='width:270px'></td>
	</tr>
	<input type="hidden" name="data[type_]" value="">
	{if $sLocalFile != ''} 
		<input type="hidden" name="sLocalFile" value="{$sLocalFile}">
	{/if}
	{* AT-543 *}
<tr>
	<td width=50%><b>{$oLanguage->getMessage("list count")}:</b></td>
	<td><input type=text name=data[list_count] value='{$aData.list_count}' maxlength=50 style='width:270px'></td>
	</tr>		
<tr>
	<td width=50%><b>{$oLanguage->getMessage("Provider or blank")} :</b></td>
	<td>{html_options id="provider_select" name=data[id_provider] options=$aProvider selected=$aData.id_provider style='width:270px'}
	</td>
	<td>
		<a href="" onclick="xajax_process_browse_url('/?action=price_profile_provider_add');$('#popup_id').show();return false;">
  		<img src="/image/plus.png" border=0 width=16 align=absmiddle /></a>
	</td>
	</tr>

<tr>
	<td width=50%><b>{$oLanguage->getMessage("Coefficient")}:</b></td>
	<td><input type=text name=data[coef] value='{$aData.coef}' maxlength=10 style='width:270px'></td>
	</tr>
	
<tr>
	<td width=50%><b>{$oLanguage->getMessage("Delimiter")}:</b></td>
	<td>{html_options id=delimiter name=data[delimiter] options=$aDelimiter selected=$aData.delimiter style='width:270px'}</td>
	</tr>
	
<tr>
	<td width=50%><b>{$oLanguage->getMessage("Row Start")}:</b></td>
	<td><input type=text id=row_start name=data[row_start] value='{$aData.row_start}' maxlength=50 style='width:270px'></td>
	</tr>
	
<tr>
	<td width=50%><b>{$oLanguage->getMessage("Name of Catalog or blank")}:</b></td>
	<td>{html_options name=data[pref] options=$aPref selected=$aData.pref style='width:270px'}</td>
	</tr>
	
<tr>
	<td width=50%><b>{$oLanguage->getMessage("Charset")}:</b></td>
	<td><input type=text id="charset" name=data[charset] value='{$aData.charset}' maxlength=50 style='width:270px'></td>
	</tr>
<tr>
	<td width=50%><b>{$oLanguage->getMessage("Delete Before Insert")}:</b></td>
	<td><input type="hidden" name=data[delete_before] value="0">
   <input type=checkbox name=data[delete_before] value='1' style="width:22px;" {if $aData.delete_before}checked{/if}></td></td>
	</tr>
	<input type=hidden name=data[num] value='{$aData.num}'>
<!--
<tr>
	<td width=50%><b>{$oLanguage->getMessage("Num")}:</b></td>
	<td><input type=text name=data[num] value='{$aData.num}' maxlength=50 style='width:270px'></td>
	</tr>
-->
<tr>
	<td width=50%><b>{$oLanguage->getMessage("Update group")}:</b></td>
	<td><input type="hidden" name=data[update_group] value="0">
   <input type=checkbox name=data[update_group] value='1' style="width:22px;" {if $aData.update_group}checked{/if}></td></td>
	</tr>
<tr>
	<td width=50%><b>{$oLanguage->getMessage("Auto update upload")}:</b></td>
	<td><input type="hidden" name=data[auto_set_price] value="0">
   <input type=checkbox name=data[auto_set_price] value='1' style="width:22px;" {if $aData.auto_set_price}checked{/if}></td></td>
</tr>
<tr><td colspan="2"><hr></td></tr>
<tr><td colspan="2" style="text-align: center;">{$oLanguage->getMessage('option get price from email')}</td></tr>
<tr>
	<td width=50%><b>{$oLanguage->getMessage("FileName on mail")} {$oLanguage->GetContextHint('price_profile_filename_template')}:</b></td>
	<td><input type=text name=data[file_name] value='{$aData.file_name}' maxlength=50 style='width:270px'></td>
	</tr>

<tr>
	<td width=50%><b>{$oLanguage->getMessage("Email")}:</b>&nbsp;&nbsp;&nbsp;<a class="view_more" href="javascript:;">{$oLanguage->getMessage('view more')}</a></td>
	<td><input type=text name=data[email] value='{$aData.email}' maxlength=50 style='width:270px'></td>
	</tr>
<tr id="email_profile_2" style="display:none">
	<td width=50%><b>{$oLanguage->getMessage("Email")}2:</b></td>
	<td><input type=text name=data[email2] value='{$aData.email2}' maxlength=50 style='width:270px'></td>
	</tr>
<tr id="email_profile_3" style="display:none">
	<td width=50%><b>{$oLanguage->getMessage("Email")}3:</b></td>
	<td><input type=text name=data[email3] value='{$aData.email3}' maxlength=50 style='width:270px'></td>
	</tr>
<tr id="email_profile_4" style="display:none">
	<td width=50%><b>{$oLanguage->getMessage("Email")}4:</b></td>
	<td><input type=text name=data[email4] value='{$aData.email4}' maxlength=50 style='width:270px'></td>
	</tr>
<tr id="email_profile_5" style="display:none">
	<td width=50%><b>{$oLanguage->getMessage("Email")}5:</b></td>
	<td><input type=text name=data[email5] value='{$aData.email5}' maxlength=50 style='width:270px'></td>
	</tr>
</table>

<!-- indata array-->
{if $aData.aInData|@count > 0}
	<div id="in_data" style="width:1000px;overflow:overlay;">
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
    </div>
{/if}
