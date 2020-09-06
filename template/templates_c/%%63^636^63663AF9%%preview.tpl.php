<?php /* Smarty version 2.6.18, created on 2019-10-07 11:29:07
         compiled from news/preview.tpl */ ?>
<h1><?php echo $this->_tpl_vars['aNewsRow']['short']; ?>
</h1>
<div class="ms-news-element">
    <div class="date" style="display:none">
	<div class="day"><?php echo $this->_tpl_vars['oLanguage']->GetMonthDayFromPostDate($this->_tpl_vars['aNewsRow']['post_date']); ?>
</div>
	<?php echo $this->_tpl_vars['oLanguage']->GetYearFromPostDate($this->_tpl_vars['aNewsRow']['post_date']); ?>

    </div>
    <div class="news-item">
	    <?php echo $this->_tpl_vars['aNewsRow']['full']; ?>

    </div>
    <div class="clear"></div>
</div>