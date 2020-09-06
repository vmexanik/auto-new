<div class="inner-part">
<div class="links js-client-tabs">
{if $aAuthUser.type_=='manager'}
    <a href="#" {if !isset($bFromCheckLogged)}class="selected"{/if}>{$oLanguage->getMessage("Create New account")}</a>
    <span>|</span>
    <a href="#" {if isset($bFromCheckLogged)}class="selected"{/if}>{$oLanguage->getMessage("Select account")}</a>
{else}
    <a href="#" {if !isset($bFromCheckLogged)}class="selected"{/if}>{$oLanguage->getMessage("Im new customer")}</a>
    <span>|</span>
    <a href="#" {if isset($bFromCheckLogged)}class="selected"{/if}>{$oLanguage->getMessage("Im regular customer")}</a>
{/if}
</div>
</div>