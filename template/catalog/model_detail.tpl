<table>
<tbody>
	<tr>
		<td><img style="max-width:150px" src="{if $aInfo.image}{$aInfo.image}{else}/imgbank/Image/no_picture.gif{/if}"></td>
		<td>&nbsp;</td>
		<td>
			<span style="font: bold 130% serif;">{$aInfo.name}</span><br>
			{$aInfo.year_start} - {$aInfo.year_end}<br>
			<p>{$aInfo.description}</p>
		</td>
	</tr>
</tbody>
</table>