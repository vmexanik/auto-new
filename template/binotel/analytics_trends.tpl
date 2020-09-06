<div class="ak-taber-block">
	{foreach from=$aWeeks item=week key=key}
		<a {if $smarty.request.week==$key}class="selected" {/if}href="/?action=binotel_analytics_trends&week={$key}">{$week.caption}</a>
	{/foreach}
	<div class="clear"></div>
</div> 
{assign var='current_week' value=$smarty.request.week|default:'0'}

<link rel="stylesheet" href="/css/binotel.css">

<div class="list-of-tables">
		<div class="data-table">
			<table width="650px">
				<tr>
	<td class="first-block-amount-of-calls">
		<table width="100%">
			<tr>
				<td class="block-title">
					<table width="100%">
						<tr>
							<td class="main-title">Объем звонков</td>
						</tr>
						<tr>
							<td class="additional-title">в сравнении с предыдущей неделей</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="trends-block">
					<table width="100%">
						<tr>
							<td class="header incoming">Поступивших</td>
							<td class="header outgoing">Совершенных</td>
							<td class="header overall">Всех звонков</td>
						</tr>
						<tr>
							<td class="row incoming">{if $aData.amount_of_calls.percent.incoming>0}+{/if}{$aData.amount_of_calls.percent.incoming}%</td>
							<td class="row outgoing">{if $aData.amount_of_calls.percent.outgoing>0}+{/if}{$aData.amount_of_calls.percent.outgoing}%</td>
							<td class="row overall">{if $aData.amount_of_calls.percent.all>0}+{/if}{$aData.amount_of_calls.percent.all}%</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="amount-block">
					<table width="100%">
						<tr>
							<td class="header title"></td>
							<td class="header incoming">Поступивших</td>
							<td class="header outgoing">Совершенных</td>
							<td class="header overall">Всех звонков</td>
						</tr>
						<tr>
							<td class="row title">{$aWeeks.$current_week.caption}&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td class="row incoming">{$aData.amount_of_calls.this_week.incoming|default:'0'}</td>
							<td class="row outgoing">{$aData.amount_of_calls.this_week.outgoing|default:'0'}</td>
							<td class="row overall">{$aData.amount_of_calls.this_week.all|default:'0'}</td>
						</tr>
						<tr>
							<td class="row title">Неделей ранее&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td class="row incoming">{$aData.amount_of_calls.last_week.incoming|default:'0'}</td>
							<td class="row outgoing">{$aData.amount_of_calls.last_week.outgoing|default:'0'}</td>
							<td class="row overall">{$aData.amount_of_calls.last_week.all|default:'0'}</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
		</div>



<div class="data-table">
			<table width="650px">
				<tr>
	<td class="second-block-new-calls">
		<table width="100%">
			<tr>
				<td class="block-title">
					<table width="100%">
						<tr>
							<td class="main-title">Звонили впервые</td>
						</tr>
						<tr>
							<td class="additional-title">в сравнении с предыдущей неделей</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="trends-block">
					<table width="100%">
						<tr>
							<td class="header new-calls">Звонили впервые</td>
							<td class="header missed-calls">Из них пропущенных</td>
						</tr>
						<tr>
							<td class="row new-calls">{if $aData.new_calls.percent_new>0}+{/if}{$aData.new_calls.percent_new}%</td>
							<td class="row missed-calls">{$aData.new_calls.this_week.lost|default:'0'}</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="amount-block">
					<table width="100%">
						<tr>
							<td class="header title"></td>
							<td class="header new-calls">Звонили впервые</td>
							<td class="header missed-calls">Из них пропущенных</td>
						</tr>
						<tr>
							<td class="row title">{$aWeeks.$current_week.caption}&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td class="row new-calls">{$aData.new_calls.this_week.new|default:'0'}</td>
							<td class="row missed-calls">{$aData.new_calls.this_week.lost|default:'0'} <span class="percent">({$aData.new_calls.this_week.percent_lost}%)</span></td>
						</tr>
						<tr>
							<td class="row title">Неделей ранее&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td class="row new-calls">{$aData.new_calls.last_week.new|default:'0'}</td>
							<td class="row missed-calls">{$aData.new_calls.last_week.lost|default:'0'} <span class="percent">({$aData.new_calls.last_week.percent_lost}%)</span></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</td>
</tr>
			</table>
		</div>



	<div class="data-table">
			<table width="650px">
				<tr>
	<td class="third-block-quality-indicators">
		<table width="100%">
			<tr>
				<td class="block-title">
					<table width="100%">
						<tr>
							<td class="main-title">Показатели качества</td>
						</tr>
						<tr>
							<td class="additional-title">в сравнении с предыдущей неделей</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="trends-block">
					<table width="100%">
						<tr>
							<td class="header average-waiting-time">Средняя длительность ожидания</td>
							<td class="header missed-calls">Пропущенных звонков</td>
						</tr>
						<tr>
							<td class="row average-waiting-time">{$aData.quality.this_week.sWaitsec} <span class="minutes">мин</span></td>
							<td class="row missed-calls">{$aData.quality.percent_lost}%</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="amount-block">
					<table width="100%">
						<tr>
							<td class="header title"></td>
							<td class="header average-waiting-time">Ср. длительность ожидания</td>
							<td class="header missed-calls">Пропущенных звонков</td>
						</tr>
						<tr>
							<td class="row title">{$aWeeks.$current_week.caption}&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td class="row average-waiting-time">{$aData.quality.this_week.sWaitsec} мин</td>
							<td class="row missed-calls">{$aData.quality.this_week.lost|default:'0'}</td>
						</tr>
						<tr>
							<td class="row title">Неделей ранее&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td class="row average-waiting-time">{$aData.quality.last_week.sWaitsec} мин</td>
							<td class="row missed-calls">{$aData.quality.last_week.lost|default:'0'}</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</td>
</tr>
			</table>
		</div>

		


		<div class="data-table">
			<table width="650px">
				<tr>
	<td class="fourth-block-average-load">
		<table width="100%">
			<tr>
				<td class="block-title">
					<table width="100%">
						<tr>
							<td class="main-title">Средняя нагрузка в течение дня</td>
						</tr>
						<tr>
							<td class="additional-title"><span class="amount-of-incoming">&nbsp; Объем поступивших &nbsp;</span>&nbsp; + из них  <span class="missed">&nbsp; пропущенных &nbsp;</span>&nbsp; по часам</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="statistics-block">
					<table width="100%">
			
					{foreach from=$aData.average_load item=item key=key name=load}
						{if $smarty.foreach.load.index == 4 || $smarty.foreach.load.index==8}<tr>{/if}
							<td class="cell">
								<table width="100%">
									<tr>
										<td class="time-range">{$key}</td>
									</tr>
									<tr>
										<td class="amount-of-calls">{$item.incoming}</td>
									</tr>
									<tr>
										<td class="amount-of-missed-calls">
											<span>{$item.lost}</span>
										</td>
									</tr>
								</table>
							</td>
						{if $smarty.foreach.foo.index==4 || $smarty.foreach.foo.index==8}</tr>{/if}	
					{/foreach}
							
					</table>
				</td>
			</tr>
		</table>
	</td>
</tr>
			</table>
		</div>



<div class="data-table">
			<table width="650px">
				 <tr>
	<td class="fifth-block-duration-calls">
		<table width="100%">
			<tr>
				<td class="block-title">
					<table width="100%">
						<tr>
							<td class="main-title">Длительность разговоров</td>
						</tr>
						<tr>
							<td class="additional-title">в сравнении с предыдущей неделей</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="trends-block">
					<table width="100%">
						<tr>
							<td class="header incoming">Средняя длит.<br>1 поступившего</td>
							<td class="header outgoing">Средняя длит.<br>1 совершенного</td>
							<td class="header overall">Средняя длит.<br>1 разговора</td>
						</tr>
						<tr>
							<td class="row incoming">{if $aData.duration_calls.percent.avg_in>0}+{/if}{$aData.duration_calls.percent.avg_in}%</td>
							<td class="row outgoing">{if $aData.duration_calls.percent.avg_out>0}+{/if}{$aData.duration_calls.percent.avg_out}%</td>
							<td class="row overall">{if $aData.duration_calls.percent.avg_all>0}+{/if}{$aData.duration_calls.percent.avg_all}%</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="amount-block">
					<table width="100%">
						<tr>
							<td class="header title"></td>
							<td class="header incoming">Средняя длит.<br>1 поступившего</td>
							<td class="header outgoing">Средняя длит.<br>1 совершенного</td>
							<td class="header overall">Средняя длит.<br>1 разговора</td>
						</tr>
						<tr>
							<td class="row title">1{$aWeeks.$current_week.caption}&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td class="row incoming">{$aData.duration_calls.this_week.sAvg_in} мин</td>
							<td class="row outgoing">{$aData.duration_calls.this_week.sAvg_out} мин</td>
							<td class="row overall">{$aData.duration_calls.this_week.sAvg_all} мин</td>
						</tr>
						<tr>
							<td class="row title">Неделей ранее&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td class="row incoming">{$aData.duration_calls.last_week.sAvg_in} мин</td>
							<td class="row outgoing">{$aData.duration_calls.last_week.sAvg_out} мин</td>
							<td class="row overall">{$aData.duration_calls.last_week.sAvg_all} мин</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</td>
</tr>
			</table>
		</div>

	
</div>