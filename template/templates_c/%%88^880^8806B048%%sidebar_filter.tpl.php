<?php /* Smarty version 2.6.18, created on 2020-06-22 17:57:19
         compiled from table/sidebar_filter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strip', 'table/sidebar_filter.tpl', 60, false),)), $this); ?>
<?php if (! $this->_tpl_vars['sTopPriceGroup']): ?>
<?php if ($this->_tpl_vars['aFilter'] || $this->_tpl_vars['aPriceGroupBrand']): ?>

<div class="at-filters-selected mob-filter-head">
    <div class="mob-filter-toggle">
        <a href="javascript:void(0);" class="at-btn" onclick="atFiltersMenuOpen();">Фильтры</a>
    </div>
    
 <?php if ($this->_tpl_vars['aBrandSelected'] || $this->_tpl_vars['aFilterSelected'] || $this->_tpl_vars['aPriceSelected']['url']): ?>   
    <div class="caption">Вы выбрали фильтр</div>
    <?php if ($this->_tpl_vars['aPriceSelected'] && $this->_tpl_vars['aPriceSelected']['url']): ?>
    <a class="link" href="<?php echo $this->_tpl_vars['aPriceSelected']['url']; ?>
" class="del-filter">Цена <?php echo $this->_tpl_vars['aPriceSelected']['min_price']; ?>
 - <?php echo $this->_tpl_vars['aPriceSelected']['max_price']; ?>
 грн</a>
    <?php endif; ?>
    <?php $_from = $this->_tpl_vars['aBrandSelected']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aBrand']):
?>
        <a class="link" href="<?php echo $this->_tpl_vars['aBrand']['url']; ?>
">Бренд: <?php echo $this->_tpl_vars['aBrand']['title']; ?>
</a> <br/>
    <?php endforeach; endif; unset($_from); ?>
    <?php $_from = $this->_tpl_vars['aFilterSelected']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aItem']):
?>
        <a class="link" href="<?php echo $this->_tpl_vars['aItem']['url']; ?>
"><?php echo $this->_tpl_vars['aItem']['name']; ?>
: <?php echo $this->_tpl_vars['aItem']['value']; ?>
</a> <br/>
    <?php endforeach; endif; unset($_from); ?>
    
    <div class="clear-filters">
<?php if ($this->_tpl_vars['aFilterSelected'] || $this->_tpl_vars['aBrandSelected'] || $this->_tpl_vars['aPriceSelected']['url']): ?><a href="<?php echo $this->_tpl_vars['sUrlRemoveAll']; ?>
" class="at-link-dashed ">Сбросить фильтры</a><?php endif; ?>
    </div>
    <?php else: ?>
    <div class="caption mob-filter" style="margin:0; text-align:center;">Фильтры</div>
    <?php endif; ?>
</div>

<div class="at-filters js-mob-filters">
    <div class="mob-filter-head">
        Фильтры
        <a href="javascript:void(0);" class="close" onclick="atFiltersMenuClose();"></a>
    </div>

    <div class="body-filters">
       
      <div class="block-filter">
            <div class="caption" title="Свернуть/Показать">Цена <?php echo $this->_tpl_vars['oCurrency']->PrintCurrencySymbol('',1); ?>
</div>
                <div class="labels-list">

                    <div class="at-filter-slider">

                        <div id="slider"></div>
                    <?php echo '
                        <script type="text/javascript">
                            $(function () {
                                jQuery("#slider").slider({
                                	'; ?>

                                    min: <?php echo ((is_array($_tmp=$this->_tpl_vars['aPriceForFilter']['min_price'])) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)); ?>
,
                                    max: <?php echo ((is_array($_tmp=$this->_tpl_vars['aPriceForFilter']['max_price'])) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)); ?>
,
                                    values: [<?php if ($this->_tpl_vars['aPriceSelected']['min_price']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['aPriceSelected']['min_price'])) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)); ?>
<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['aPriceForFilter']['min_price'])) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)); ?>
<?php endif; ?>, <?php if ($this->_tpl_vars['aPriceSelected']['max_price']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['aPriceSelected']['max_price'])) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)); ?>
<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['aPriceForFilter']['max_price'])) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)); ?>
<?php endif; ?>],
                                    <?php echo '
                                    range: true,
                                    stop: function (event, ui) {
                                        minVal = ui.values[0];
                                        maxVal = ui.values[1];

                                        jQuery("input#minCost").val(minVal);
                                        jQuery("input#maxCost").val(maxVal);
                                    },
                                    slide: function (event, ui) {
                                        for (var i = 0; i < ui.values.length; ++i) {
                                            $("input.sliderValue[data-index=" + i + "]").val(ui.values[i]);
                                        }
                                    }
                                });

                                $("input.sliderValue").change(function () {
                                    var $this = $(this);
                                    $("#slider").slider("values", $this.data("index"), $this.val());
                                });
                            });
                        </script>
                    '; ?>

                        <div class="fields">
							                            
                          	<div class="cell">
	                            <input value="<?php if ($this->_tpl_vars['aPriceSelected']['min_price']): ?><?php echo $this->_tpl_vars['aPriceSelected']['min_price']; ?>
<?php else: ?><?php echo $this->_tpl_vars['aPriceForFilter']['min_price']; ?>
<?php endif; ?>" id="minCost" class="sliderValue"
	                                                     data-index="0" type="text"/>
							</div>

                            <div class="cell dash"><span></span></div>
                            
	                        <div class="cell">
								    <input value="<?php if ($this->_tpl_vars['aPriceSelected']['max_price']): ?><?php echo $this->_tpl_vars['aPriceSelected']['max_price']; ?>
<?php else: ?><?php echo $this->_tpl_vars['aPriceForFilter']['max_price']; ?>
<?php endif; ?>" id="maxCost" class="sliderValue"
	                                                     data-index="1" type="text"/>
                            </div>
	                        
                            <div class="cell button-cell">
                                <a class="at-btn filters-btn" href="javascript:void(0);" 
                                onclick="var link='<?php echo $this->_tpl_vars['aPriceSelected']['url_2']; ?>
'; link2=link.replace('min_price', +$('#minCost').val()); link3=link2.replace('max_price', +$('#maxCost').val());location.href=link3"></a>
                            </div>
                    	</div>
                </div>
            </div>
     </div>
    
        <div class="block-filter">
            <div class="caption" title="Свернуть/Показать">Бренд</div>

            <div class="labels-list">
            <?php $this->assign('iCount', 0); ?><?php $this->assign('iSum', 0); ?>
            <?php $_from = $this->_tpl_vars['aPriceGroupBrand']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['aItemBrand']):
?><?php $this->assign('iSum', $this->_tpl_vars['iSum']+1); ?>
                <label <?php if ($this->_tpl_vars['key'] > 4): ?> class="hidden hiddened"<?php $this->assign('iCount', $this->_tpl_vars['iCount']+1); ?><?php endif; ?>>
                    <input type="checkbox" class="js-checkbox" <?php if ($this->_tpl_vars['aItemBrand']['checked']): ?>checked="checked"<?php endif; ?> onclick="document.location='<?php echo $this->_tpl_vars['aItemBrand']['url']; ?>
'">
                    <a href="<?php echo $this->_tpl_vars['aItemBrand']['url']; ?>
"><?php echo $this->_tpl_vars['aItemBrand']['c_title']; ?>
 <span>(<?php echo $this->_tpl_vars['aItemBrand']['count']; ?>
)</span></a>
               </label><br <?php if ($this->_tpl_vars['key'] > 4): ?> class="hidden hiddened"<?php endif; ?> />
                
            <?php endforeach; endif; unset($_from); ?>
                <?php if ($this->_tpl_vars['iCount']): ?><a class="at-link-dashed alltypes" href="javascript:void(0);"><span id="state_show_all">Показать все</span><span id="state_hide_all" style="display:none;">Скрыть все</span></a><?php endif; ?>
            </div>
        </div>
        
        <?php $_from = $this->_tpl_vars['aFilter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aItem']):
?>
        <div class="block-filter">
            <div class="caption<?php if ($this->_tpl_vars['aItem']['is_collapsed']): ?> collapsed<?php endif; ?>" title="Свернуть/Показать"><?php echo $this->_tpl_vars['aItem']['name']; ?>
</div>

            <div class="labels-list<?php if ($this->_tpl_vars['aItem']['is_collapsed']): ?> hide<?php endif; ?>">
                <?php $this->assign('iCount', 0); ?>
	            <?php $_from = $this->_tpl_vars['aItem']['params']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['aParam']):
?>
                <label <?php if ($this->_tpl_vars['key'] > 4): ?> class="hidden hiddened"<?php $this->assign('iCount', $this->_tpl_vars['iCount']+1); ?><?php endif; ?>>
                    <input type="checkbox" class="js-checkbox" <?php if ($this->_tpl_vars['aParam']['checked']): ?>checked="checked"<?php endif; ?> onclick="document.location='<?php echo $this->_tpl_vars['aParam']['url']; ?>
'">
                    <a href="<?php echo $this->_tpl_vars['aParam']['url']; ?>
"><?php echo $this->_tpl_vars['aParam']['name']; ?>
 <span>(<?php echo $this->_tpl_vars['aParam']['count']; ?>
)</span></a>
                </label><br <?php if ($this->_tpl_vars['key'] > 4): ?> class="hidden hiddened"<?php endif; ?> />
                
                <?php endforeach; endif; unset($_from); ?>
                <?php if ($this->_tpl_vars['iCount']): ?><a class="at-link-dashed alltypes" href="javascript:void(0);"><span id="state_show_all">Все типы</span><span id="state_hide_all" style="display:none;">Скрыть</span></a><?php endif; ?>
            </div>
        </div>
        <?php endforeach; endif; unset($_from); ?>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>