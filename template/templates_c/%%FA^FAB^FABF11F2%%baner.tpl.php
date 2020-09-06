<?php /* Smarty version 2.6.18, created on 2019-09-20 12:11:41
         compiled from index_include/baner.tpl */ ?>

<div class="at-index-banner-wrap">
         <div class="at-index-banner js-index-banner">
        	  <?php $_from = $this->_tpl_vars['aBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aSingleBanner']):
?>
               <div>
                   <a href="<?php echo $this->_tpl_vars['aSingleBanner']['link']; ?>
"><img src="<?php echo $this->_tpl_vars['aSingleBanner']['image']; ?>
" alt="<?php echo $this->_tpl_vars['aSingleBanner']['name']; ?>
"></a>
          	   </div>
              <?php endforeach; endif; unset($_from); ?> 
         </div>
 </div>