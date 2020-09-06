<tr>
   		<td width=50%><b>{$oLanguage->GetMessage("Passport image preview")}:</b></td>
   		<td>
   		{if $aData.passport_image_name}
   			<a href='{$aData.passport_image_name}' target=_blank>
   			{if substr($aData.passport_image_name_small, -3, 3)=='pdf'}
   			 	<img src='/image/design/pdf.png ' border=0>
   			{else}
				<img src='{$aData.passport_image_name_small}' width='{$oLanguage->GetConstant('passport_image:small_width',150)}'
					border=0>
			{/if}</a>
   		{else}
   			{$oLanguage->getMessage("not uploaded")}
   		{/if}
   		</td>
</tr>