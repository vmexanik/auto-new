<?php /* Smarty version 2.6.18, created on 2020-06-08 19:23:05
         compiled from car_select/chosen_modification.tpl */ ?>
<div class="at-selected-car">
    <div class="at-mainer">
        <div class="selected-car-inner">
            <div class="selected-car-part first">
                <div class="caption">Ваше авто</div>
                <a href="<?php if ($_REQUEST['category']): ?>/rubricator/<?php echo $_REQUEST['category']; ?>
/?clear_auto=1<?php else: ?>/pages/catalog/?clear_auto=1<?php endif; ?>" class="at-link-dashed">Удалить авто</a>
                		            </div>

            
            <div class="selected-car-part third">
                <?php if ($this->_tpl_vars['sTecdocUrl']): ?><a href="<?php echo $this->_tpl_vars['sTecdocUrl']; ?>
"><?php endif; ?>	
                    <b><?php echo $this->_tpl_vars['aModelDetailChosen']['name']; ?>
</b>
                <?php if ($this->_tpl_vars['sTecdocUrl']): ?></a><?php endif; ?>
                    <br>
                    Период выпуска: <?php echo $this->_tpl_vars['aModelDetailChosen']['month_start']; ?>
.<?php echo $this->_tpl_vars['aModelDetailChosen']['year_start']; ?>
 - <?php echo $this->_tpl_vars['aModelDetailChosen']['month_end']; ?>
.<?php echo $this->_tpl_vars['aModelDetailChosen']['year_end']; ?>
<br>
                    <?php if ($this->_tpl_vars['aModelDetailChosen']['Engines']): ?>Двигатели: <?php echo $this->_tpl_vars['aModelDetailChosen']['Engines']; ?>
<br><?php endif; ?>
                    Мощность двигателя: <?php echo $this->_tpl_vars['aModelDetailChosen']['hp_from']; ?>
 л.с. / <?php echo $this->_tpl_vars['aModelDetailChosen']['kw_from']; ?>
 кВт<br>
                    Объем двигателя: <?php echo $this->_tpl_vars['aModelDetailChosen']['ccm']; ?>
 см3 <?php echo $this->_tpl_vars['aModelDetailChosen']['Fuel']; ?>
<br>
                    <?php if ($this->_tpl_vars['aModelDetailChosen']['cylinder']): ?>Цилиндров: <?php echo $this->_tpl_vars['aModelDetailChosen']['cylinder']; ?>
<br><?php endif; ?>
                    Кузов: <?php echo $this->_tpl_vars['aModelDetailChosen']['body']; ?>
/<?php echo $this->_tpl_vars['aModelDetailChosen']['Drive']; ?>

            </div>

            <div class="selected-car-part forth">
                <a href="<?php echo $this->_tpl_vars['sTecdocUrl']; ?>
" class="image">
                    <?php if ($this->_tpl_vars['aModelDetailChosen']['image']): ?><img src="<?php echo $this->_tpl_vars['aModelDetailChosen']['image']; ?>
" alt="" >
                    <?php else: ?><img src="/image/media/no-photo.png" alt=""><?php endif; ?>
                </a>
            </div>
        </div>
    </div>
</div>