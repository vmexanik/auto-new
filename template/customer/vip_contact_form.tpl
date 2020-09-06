<div class="ak-taber-block">
	<a class="selected" href="#">{$oLanguage->GetMessage('Vip contacts')}</a>
	<div class="clear"></div>
</div>


{$oLanguage->GetText('Top vip contact text')}
<p>
<table width=500>
<tr><td><b>{$oLanguage->GetMessage('FLName')}</b>:</td><td>{$aParentUser.name}</td></tr>
<tr><td><b>{$oLanguage->GetMessage('Phone')}</b>:</td><td>{$aParentUser.phone}</td></tr>

{if $aParentUser.skype}
<tr><td><b>{$oLanguage->GetMessage('Skype')}</b>:</td><td>{$aParentUser.skype}</td></tr>
{/if}

<tr><td><b>{$oLanguage->GetMessage('Email')}</b>:</td><td>{$aParentUser.email}</td></tr>

{if $aParentUser.icq}
<tr><td><b>{$oLanguage->GetMessage('Icq')}</b>:</td><td>{$aParentUser.icq}</td></tr>
{/if}

{if $aParentUser.vip_remark}
<tr><td colspan=2><br><b>{$oLanguage->GetMessage('contact vip remarks')}</b>:</td></tr>
<tr><td colspan=2>{$aParentUser.vip_remark|nl2br}</td></tr>
{/if}

</table>
</p>