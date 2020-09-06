<td>{$aRow.id}</td>
<td>{$aRow.number}</td>
<td>{$aRow.message}</td>
<td>{$oLanguage->GetDateTime($aRow.post)}</td>
<td>{if ($aRow.sent_time>=0)} {$oLanguage->GetDateTime($aRow.sent_time)} {elseif ($aRow.sent_time==-1)} {$oLanguage->getDMessage('Declined')} {elseif ($aRow.sent_time==-3)} {$oLanguage->getDMessage('Empty balance')} {else} {$oLanguage->getDMessage('Incorrect Number')} {/if}</td>