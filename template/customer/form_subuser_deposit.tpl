<table>
	<tr>
   		<td width=50%><b>{$oLanguage->GetMessage("FromLogin")}:</b></td>
   		<td>{$aAuthUser.login} <b>${$aAuthUser.amount}</b></td>
  	</tr>
  	<tr>
   		<td width=50%><b>{$oLanguage->GetMessage("ToLogin")}:</b></td>
   		<td>{$aSubuser.login} <b>${$aSubuser.amount}</b></td>
  	</tr>

	<tr>
		<td><b>{$oLanguage->GetMessage("DepositAmount")}:</b> {$sZir}</td>
		<td> <b>$</b>&nbsp;<input type=text name=data[amount] value='{$aData.amount}' maxlength=10 style='width:50px'>

		<font color=gray>&nbsp;&nbsp;{$oLanguage->GetMessage('Amount comment')}</font>
		</td>
	</tr>
</table>