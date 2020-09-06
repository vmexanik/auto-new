<table style="border-left: 1px solid rgb(221, 221, 221); border-bottom: 1px solid rgb(221, 221, 221); margin-top: 5px;
		font-size: 11px; line-height: normal; clear: both;">
    <tbody>
        <tr>
            <td style="border-left: 1px solid rgb(238, 238, 238); padding: 3px 4px;
				background: rgb(178, 205, 223) none repeat scroll 0% 0%;
				-moz-background-clip: border; -moz-background-origin: padding; text-align: left;
				-moz-background-inline-policy: continuous;"><span style="font-size: small;"><span style="font-family: Arial;">
            	<strong>{$oLanguage->GetMessage('Description')}</strong></span></span></td>
            <td style="border-left: 1px solid rgb(238, 238, 238); padding: 3px 4px;
				background: rgb(178, 205, 223) none repeat scroll 0% 0%; -moz-background-clip: border;
				-moz-background-origin: padding; text-align: left; -moz-background-inline-policy: continuous;">
            	<span style="font-size: small;"><span style="font-family: Arial;">
            		<strong>{$oLanguage->GetMessage('Part code')}<br />
            </strong></span></span></td>
            <td style="border-left: 1px solid rgb(238, 238, 238); padding: 3px 4px;
				background: rgb(178, 205, 223) none repeat scroll 0% 0%; -moz-background-clip: border;
				-moz-background-origin: padding; text-align: left; -moz-background-inline-policy: continuous;">
            	<span style="font-size: small;"><span style="font-family: Arial;"><strong>
            		{$oLanguage->GetMessage('Price')}<br />
            </strong></span></span></td>
            <td style="border-left: 1px solid rgb(238, 238, 238); padding: 3px 4px;
				background: rgb(178, 205, 223) none repeat scroll 0% 0%; -moz-background-clip: border;
				-moz-background-origin: padding; text-align: left; -moz-background-inline-policy: continuous;">
            	<span style="font-size: small;"><span style="font-family: Arial;"><strong>
            		{$oLanguage->GetMessage('Price link')}<br />
            </strong></span></span></td>
        </tr>
	{foreach from=$aVinRequest.part_list item=aItem}
{if $aItem.i_visible}
		{if $aItem.code_visible}
			{assign var=sTemplateCode value=$aItem.user_input_code}
		{else}
			{assign var=sTemplateCode value=$aItem.code}
		{/if}
        <tr>
            <td style="border-right: 1px solid rgb(221, 221, 221); border-bottom: 1px solid rgb(221, 221, 221); padding: 3px 4px;"
				><span style="font-size: small;"><span style="font-family: Arial;">{$aItem.name}</span></span></td>
            <td style="border-right: 1px solid rgb(221, 221, 221); border-bottom: 1px solid rgb(221, 221, 221); padding: 3px 4px;"
				><span style="font-size: small;"><span style="font-family: Arial;">{$sTemplateCode}</span></span></td>
            <td style="border-right: 1px solid rgb(221, 221, 221); border-bottom: 1px solid rgb(221, 221, 221); padding: 3px 4px;"
				><span style="font-size: small;"><span style="font-family: Arial;"
				>{if $aItem.price}{$aItem.print_price}{/if}</span></span></td>
            <td style="border-right: 1px solid rgb(221, 221, 221); border-bottom: 1px solid rgb(221, 221, 221); padding: 3px 4px;"
				><a href="http://{$SERVER_NAME}/?action=catalog_price_view&amp;code={$sTemplateCode}" target="_blank"
					><span style="font-size: small;"><span style="font-family: Arial;"
					>{$oLanguage->GetMessage('View in price online')}</span></span></a></td>
        </tr>
{/if}
    {/foreach}
    </tbody>
</table>