<div class="ak-taber-block">
	<a {if $smarty.request.action=='binotel'}class="selected" {/if}href="/?action=binotel">Все</a>
	<a {if $smarty.request.action=='binotel_input'}class="selected" {/if}href="/?action=binotel_input">поступившие</a>
	<a {if $smarty.request.action=='binotel_output'}class="selected" {/if}href="/?action=binotel_output">совершенные</a>
	<a {if $smarty.request.action=='binotel_lost'}class="selected" {/if}href="/?action=binotel_lost">потерянные</a>
	<a {if $smarty.request.action=='binotel_by_manager'}class="selected" {/if}href="/?action=binotel_by_manager">по сотруднику</a>
	<a {if $smarty.request.action=='binotel_by_number'}class="selected" {/if}href="/?action=binotel_by_number">по номеру</a>
	<a {if $smarty.request.action=='binotel_now'}class="selected" {/if}href="/?action=binotel_now">прямо сейчас</a>
	<div class="clear"></div>
</div>