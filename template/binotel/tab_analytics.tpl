<div class="ak-taber-block">
	<a {if $smarty.request.action=='binotel_analytics_trends'}class="selected" {/if}href="/?action=binotel_analytics_trends">Тенденции</a>
	<a {if $smarty.request.action=='binotel_analytics_load'}class="selected" {/if}href="/?action=binotel_analytics_load">средняя нагрузка в течении дня</a>
	<a {if $smarty.request.action=='binotel_analytics_productivity'}class="selected" {/if}href="/?action=binotel_analytics_productivity">продуктивность сотрудников</a>
	<a {if $smarty.request.action=='binotel_analytics_timeline'}class="selected" {/if}href="/?action=binotel_analytics_timeline">продуктивность на временной шкале</a>
	<a {if $smarty.request.action=='binotel_analytics_outgoing'}class="selected" {/if}href="/?action=binotel_analytics_outgoing">данные об исходящих звонках</a>
	<div class="clear"></div>
</div>