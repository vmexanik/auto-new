<?php /* Smarty version 2.6.18, created on 2019-09-20 12:11:41
         compiled from index_include/news.tpl */ ?>
<?php if ($this->_tpl_vars['aNews']): ?>
<div class="at-index-blog controls">
	<h2><?php echo $this->_tpl_vars['oLanguage']->GetMessage('news'); ?>
</h2>
	<div class="blog-list js-blog-slider">
	<?php $_from = $this->_tpl_vars['aNews']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aItem']):
?>
		<div class="blog-item">
			<a href="/pages/news/<?php echo $this->_tpl_vars['aItem']['id']; ?>
<?php if ($this->_tpl_vars['oLanguage']->getConstant('global:url_is_not_last_slash',0)): ?><?php else: ?>/<?php endif; ?>" class="image"
               style="background-image: url('<?php if ($this->_tpl_vars['aItem']['image']): ?><?php echo $this->_tpl_vars['aItem']['image']; ?>
<?php else: ?>/image/media/index-blog-1.png<?php endif; ?>')">
                <img src="/image/blog-mask.png" alt="">
            </a>

            <a href="/pages/news/<?php echo $this->_tpl_vars['aItem']['id']; ?>
<?php if ($this->_tpl_vars['oLanguage']->getConstant('global:url_is_not_last_slash',0)): ?><?php else: ?>/<?php endif; ?>" class="name"><?php echo $this->_tpl_vars['aItem']['name']; ?>
</a>

            <div class="date"><?php echo $this->_tpl_vars['oLanguage']->GetPostDate($this->_tpl_vars['aItem']['post_date']); ?>
</div>

            <div class="text">
                <?php echo $this->_tpl_vars['aItem']['short']; ?>

            </div>
		</div>
	<?php endforeach; endif; unset($_from); ?>
	</div>
</div>
<?php endif; ?>