<link rel="stylesheet" href="/css/binotel.css">

<table class="load-during-the-day">
	<tr>
	{assign var=cnt value=0}
	{foreach from=$aData item=item key=key name=load}
		
			<td class="cell first-level first-cell">
				<table cellspacing="0" width="100%">
					<tr>
						<td class="time-range">{$key}</td>
					</tr>
					<tr>
						<td class="amount-of-calls"><span rel="tooltip" title="Всего входящих звонков">{$aData.$key.incoming}</span></td>
					</tr>
					<tr>
						<td class="amount-of-billsec-missed-calls">
							<div class="billsec" rel="tooltip" title="Длительность разговоров">{$aData.$key.sBillsec}</div>
							<div class="missed-calls" rel="tooltip" title="Непринятых звонков">{$aData.$key.lost}</div>
						</td>
					</tr>
				</table>
			</td>
			{assign var=cnt value=$cnt+1}
	{if $cnt == 6}{assign var=cnt value=0}</tr><tr>{/if}		
{* {if $smarty.foreach.load.index % 5 == 0 && $smarty.foreach.load.index!=0}</tr><tr>{/if} *}
	{/foreach}
	</table>