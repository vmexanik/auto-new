<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('Constant')}</h3>
            </div>
		
		<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this,Array())">
<div class="card-body">
				<!-- body begin -->
	
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
		<label>{$oLanguage->getDMessage('Key')}:{$sZir}</label>
	        <input style="font-weight:bold;" readonly="readonly" type=text name=data[key_] value="{$aData.key_|escape}"  class="form-control btn-sm">
	    </div>
	</div>
</div>

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Value')}:{$sZir}</label>
			{if $sType == 'checkbox'}
	        <input type=hidden name=data[value] value='{$aData.value}'>
	        <input style="width: 13px;" type=checkbox name=data[new_value] value='1' {if $aData.value=='1' }checked{/if}  class="form-control btn-sm">
    		{elseif $sType == 'enum'} {foreach item=aItem from=$aOptions}
		    <input style="width: 13px;" type="radio" name="data[value]" value="{$aItem}" {if $aItem==$sOptionCheck}checked{/if}  class="form-control btn-sm"> {$aItem}
		    <br> {/foreach} {elseif $sType == 'text'} {$oAdmin->getCKEditor('data[value]',$aData.value)} {elseif $sType == 'only_text'}
		    <textarea rows="16" cols="50" style="width:500px;" name="data[value]"  class="form-control btn-sm">{$aData.value}</textarea>
		    {elseif $sType == 'favicon'}
            <img id='{$sType}' style="max-width:100px" border=0 align=absmiddle hspace=5 src='{if $aData.value}{$aData.value}{else}favicon.ico{/if}'>
            <input type=hidden name=data[value] id='{$sType}_input' value='{$aData.value}'>
            <table>
                <tr>
                    <td><img hspace=1 align=absmiddle src='/libp/mpanel/images/small/inbox.png'>
                        <a href="#" onclick="{strip}
			javascript:OpenFileBrowser('/libp/mpanel/imgmanager/browser/default/browser.php
			?Type=Image&Connector=php_connector/connector.php&return_id={$sType}', 600, 400); return false;
			{/strip}" style='font-weight:normal'>{$oLanguage->GetDMessage('Change')}</a></td>
                    <td><img hspace=1 align=absmiddle src='/libp/mpanel/images/small/outbox.png'>
                        <a href=# onclick="javascript:ClearImageURL('{$sType}');return false;" style='font-weight:normal'>{$oLanguage->GetDMessage('Clear')}</a></td>
            </table>
            {elseif $sType == 'logo'}
            <img id='{$sType}' style="max-width:100px" border=0 align=absmiddle hspace=5 src='{if $aData.value}{$aData.value}{else}/image/logo-top.png{/if}'>
            <input type=hidden name=data[value] id='{$sType}_input' value='{$aData.value}'>
            <table>
                <tr>
                    <td><img hspace=1 align=absmiddle src='/libp/mpanel/images/small/inbox.png'>
                        <a href="#" onclick="{strip}
javascript:OpenFileBrowser('/libp/mpanel/imgmanager/browser/default/browser.php
?Type=Image&Connector=php_connector/connector.php&return_id={$sType}', 600, 400); return false;
{/strip}" style='font-weight:normal'>{$oLanguage->GetDMessage('Change')}</a></td>
                    <td><img hspace=1 align=absmiddle src='/libp/mpanel/images/small/outbox.png'>
                        <a href=# onclick="javascript:ClearImageURL('{$sType}');return false;" style='font-weight:normal'>{$oLanguage->GetDMessage('Clear')}</a></td>
            </table>
			{else}
			<input type=text name=data[value] value="{$aData.value|escape}" class="form-control btn-sm"> 
			{/if}
		</div>
	</div>
</div>

{if $aData.id > 0}
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Description')}: {$sZir}</label>
            <textarea name=data[description] class="form-control btn-sm">{$aData.description}</textarea>
		</div>
	</div>
</div>
{/if}

{if $aData.id == -2}
	<a href="http://manual.mstarproject.com/index.php/%D0%A7%D1%82%D0%BE_%D1%82%D0%B0%D0%BA%D0%BE%D0%B5_robots.txt" target="_blank">{$oLanguage->getDMessage('What is robots.txt?')}</a>
{/if}

<!-- body end -->
			</div>
<!-- /.card-body -->
		    <div class="card-footer">
		        <input type=hidden name=data[type] value='{$sType}'>
				<input type=hidden name=data[id] value="{$aData.id|escape}">
				{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}
		   </div>
		   <!-- /.card-footer -->
		   </form>
		   
		</div>
	</div>
</div>