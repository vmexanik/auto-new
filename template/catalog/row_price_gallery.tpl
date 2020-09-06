<li>
<div class="js-thumb-height">
    <div class="at-thumb-element">
        <div class="inner-wrap see">
            <a href="/buy/{$oContent->Translit($aRow.cat_name)|@lower}_{$aRow.code|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}" class="image">
                <img class="fake" src="/image/plist-thumb-mask.png" alt="{$aRow.name_translate} {$aRow.brand} {$aRow.code}" title="{$aRow.name_translate} {$aRow.brand} {$aRow.code}">
                <img class="real" src="{if $aRow.image}{$aRow.image}{else}/image/media/no-photo-thumbs.png{/if}" alt="{$aRow.name_translate} {$aRow.brand} {$aRow.code}" title="{$aRow.name_translate} {$aRow.brand} {$aRow.code}">
            </a>

            <div class="name">
                {$aRow.name_translate}
            </div>

            <div class="brand-name">
                <a href="/buy/{$oContent->Translit($aRow.cat_name)|@lower}_{$aRow.code|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$aRow.brand} {$aRow.code}</a>
            </div>


            <div class="options">
                <a class="at-link-dashed green" href="javascript:void(0);">{if $aRow.stock}{$aRow.stock}{else}-{/if} шт</a>
                <a class="at-link-dashed grey" href="javascript:void(0);">{if $aRow.term}{$aRow.term}{else}-{/if} дн.</a>
            </div>

            <div class="price">
                <span>{$oCurrency->PrintPrice($aRow.price)}</span>
            </div>

            {*<div class="price-old">
                <span>177.15</span>
                <span class="cur">грн</span>
            </div>*}
        </div>

        <div class="extend">
            <input type='hidden' name='n[{$aRow.code_provider}]' id='number_{$aRow.item_code}_{$aRow.id_provider}'>
            <input type='hidden' name='r[{$aRow.code_provider}]' id='reference_{$aRow.item_code}_{$aRow.id_provider}' value=''>
            <div class="buy" id='add_link_{$aRow.item_code}_{$aRow.id_provider}'>
                {include file="catalog/link_add_cart.tpl" bLabel=true}
            </div>

            {*<div class="extra">
                Вес [кг]: 0.03 <br />
                Внутренний диаметр: 25.00 <br />
                Длина [мм]: 25.00 <br />
                Материал: резина <br />
            </div>*}

            {if $aRow.childs}
            {foreach from=$aRow.childs item=aRowchild}
                <div class="inner-wrap">
                    <div class="options">
                        <a class="at-link-dashed green" href="javascript:void(0);">{if $aRowchild.stock}{$aRowchild.stock}{else}-{/if} шт</a>
                        <a class="at-link-dashed grey" href="javascript:void(0);">{if $aRowchild.term}{$aRowchild.term}{else}-{/if} дн.</a>
                    </div>

                    <div class="price">
                        <span>{$oCurrency->PrintPrice($aRowchild.price)}</span>
                    </div>
                </div>

                <input type='hidden' name='n[{$aRowchild.code_provider}]' id='number_{$aRow.item_code}_{$aRowchild.id_provider}'>
                <input type='hidden' name='r[{$aRowchild.code_provider}]' id='reference_{$aRow.item_code}_{$aRowchild.id_provider}' value=''>
                <div class="buy" id='add_link_{$aRow.item_code}_{$aRowchild.id_provider}'>
                    {include file="catalog/link_add_cart.tpl" bLabel=true aRow=$aRowchild}
                </div>
            {/foreach}
            {/if}
        </div>
    </div>
</div>
</li>
{* {if $aRow.childs}
{foreach from=$aRow.childs item=aRowchild}
<li>
<div class="js-thumb-height">
    <div class="at-thumb-element">
        <div class="inner-wrap">
            <a href="/buy/{$oContent->Translit($aRow.cat_name)|@lower}_{$aRow.code|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}" class="image">
                <img class="fake" src="/image/plist-thumb-mask.png" alt="">
                <img class="real" src="{if $aRow.image}{$aRow.image}{else}/image/media/no-photo-thumbs.png{/if}" alt="">
            </a>

            <div class="name">
                {$aRow.name_translate}
            </div>

            <div class="brand-name">
                <a href="/buy/{$oContent->Translit($aRow.cat_name)|@lower}_{$aRow.code|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$aRow.brand} {$aRow.code}</a>
            </div>


            <div class="options">
                <a class="at-link-dashed green" href="javascript:void(0);">{if $aRowchild.stock}{$aRowchild.stock}{else}-{/if} шт</a>
                <a class="at-link-dashed grey" href="javascript:void(0);">{if $aRowchild.term}{$aRowchild.term}{else}-{/if} дн.</a>
            </div>

            <div class="price">
                <span>{$oCurrency->PrintPrice($aRowchild.price)}</span>
            </div>


        </div>

        <div class="extend">
            <input type='hidden' name='n[{$aRowchild.code_provider}]' id='number_{$aRow.item_code}_{$aRowchild.id_provider}'>
            <input type='hidden' name='r[{$aRowchild.code_provider}]' id='reference_{$aRow.item_code}_{$aRowchild.id_provider}' value=''>
            <div class="buy" id='add_link_{$aRow.item_code}_{$aRowchild.id_provider}'>
                {include file="catalog/link_add_cart.tpl" bLabel=true aRow=$aRowchild}
            </div>

            
        </div>
    </div>
</div>
</li>
{/foreach}
{/if} *}