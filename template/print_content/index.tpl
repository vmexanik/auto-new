<HTML>
<HEAD>
<title>Print</title>
<META http-equiv=content-type content="text/html; charset={$aGeneralConf.Charset}">
</HEAD>
<BODY>


{$sContent}


<table border=0 width=700>
	<TR>
		<TD align=center colspan=3>
			<INPUT onclick="javascript:window.print();" style='width=100' type=button class='at-btn'
				value="{$oLanguage->GetMessage('Print')}">

			<input type=button class='at-btn' name=submit value='{$oLanguage->GetMessage('Close')}'
				style='width=100' onclick="window.close();">

			{if $smarty.get.return}
			<input type=button class='at-btn' name=submit value='{$oLanguage->GetMessage('Return')}'
				style='width=100' onclick="location.href='/?action={$smarty.get.return}'">
			{/if}
		</TD>

	</TR>
</table>


</BODY>
</HTML>
