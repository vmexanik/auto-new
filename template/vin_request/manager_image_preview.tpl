{if $aData.manager_image_name}
  	<tr>
   		<td width=50%><b>{$oLanguage->GetMessage("Manager image preview")}:</b></td>
   		<td>
   			<a href='{$aData.manager_image_name}' target=_blank>
   			{if substr($aData.manager_image_name_small, -3, 3)=='pdf'}
   			 	<img src='/image/design/pdf.png ' border=0>
   			{else}<img src='{$aData.manager_image_name_small}'
				width='{$oLanguage->GetConstant('passport_image:small_width',150)}'	border=0>
			{/if}
   			</a>
   		</td>
  	</tr>
{/if}