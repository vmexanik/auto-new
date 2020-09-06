<?php /* Smarty version 2.6.18, created on 2020-04-05 17:44:44
         compiled from index_include/popular_products.tpl */ ?>
<?php if ($this->_tpl_vars['aPopularProducts']): ?>
<h2><?php echo $this->_tpl_vars['oLanguage']->GetMessage('popular products'); ?>
</h2>

<div class="at-product-carousel js-product-carousel">
	<div class="line">
		<?php $_from = $this->_tpl_vars['aPopularProducts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aRow']):
?>
		<div>
			<div class="at-thumb-element ready">
				<div class="inner-wrap">
					<a href="<?php echo $this->_tpl_vars['aRow']['url']; ?>
" class="image"> 
						<img class="fake" src="/image/plist-thumb-mask.png" alt="<?php echo $this->_tpl_vars['aRow']['name']; ?>
" title="<?php echo $this->_tpl_vars['aRow']['name']; ?>
"> 
						<?php if ($this->_tpl_vars['aRow']['image']): ?>
						<img class="real" src="<?php echo $this->_tpl_vars['aRow']['image']; ?>
" alt="<?php echo $this->_tpl_vars['aRow']['name']; ?>
" title="<?php echo $this->_tpl_vars['aRow']['name']; ?>
"> 
						 <?php else: ?>
						<img class="real" src="/image/media/no-photo-thumbs.png" alt="<?php echo $this->_tpl_vars['aRow']['name']; ?>
" title="<?php echo $this->_tpl_vars['aRow']['name']; ?>
">
						<?php endif; ?>
																		<?php if ($this->_tpl_vars['aRow']['bage'] == 'new'): ?><span class="action new"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('badge new'); ?>
</span><?php endif; ?>
						<?php if ($this->_tpl_vars['aRow']['bage'] == 'action'): ?><span class="action"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('badge action'); ?>
</span><?php endif; ?>
						<?php if ($this->_tpl_vars['aRow']['bage'] == 'recommend'): ?><span class="action recommend"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('badge recommend'); ?>
</span><?php endif; ?>
					</a>

					<div class="name x3-overflow"><?php echo $this->_tpl_vars['aRow']['name']; ?>
</div>

					<div class="price">
						<span><?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['price'],0,0,'strong'); ?>
</span> 					</div>
					<?php if ($this->_tpl_vars['aRow']['old_price'] > 0): ?>
					<div class="price-old">
						<span><?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['old_price']); ?>
</span>
					</div>
					<?php endif; ?>
				</div>

				<div class="extend">
					<div class="buy">
						<a href="<?php echo $this->_tpl_vars['aRow']['url']; ?>
" class="at-btn">Купить</a>
					</div>

									</div>
			</div>
		</div>
		<?php endforeach; endif; unset($_from); ?>
	</div>
</div>
<?php endif; ?>