<?php /* Smarty version 2.6.18, created on 2019-09-20 12:11:40
         compiled from rubricator/index.tpl */ ?>


<ul class="at-index-cats">
<?php $_from = $this->_tpl_vars['aMainRubric']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aSubMenuItems']):
?>
<?php if ($this->_tpl_vars['aSubMenuItems']['url']): ?>
<li>
	<div class="at-index-cat-thumb"
	    style="background-image: url('<?php echo $this->_tpl_vars['aSubMenuItems']['image']; ?>
')">
	    <div class="name">
	    	<?php if ($this->_tpl_vars['aSubMenuItems']['is_price_group']): ?>
	    		<a id="catagory_parts" href="/select/<?php echo $this->_tpl_vars['aSubMenuItems']['url']; ?>
"><?php echo $this->_tpl_vars['aSubMenuItems']['name']; ?>
</a>
	    	<?php else: ?>
	    	    <a id="catagory_parts" href="/rubricator/<?php echo $this->_tpl_vars['aSubMenuItems']['url']; ?>
/<?php echo $this->_tpl_vars['sAutoPreSelected']; ?>
"><?php echo $this->_tpl_vars['aSubMenuItems']['name']; ?>
</a>
	    	<?php endif; ?>
	    </div>
	    <?php $this->assign('iCount', 0); ?>
	    <?php $_from = $this->_tpl_vars['aSubMenuItems']['childs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aItem']):
?>
	    <?php $this->assign('iCount', $this->_tpl_vars['iCount']+1); ?>
			   <label <?php if ($this->_tpl_vars['iCount'] > 5): ?> class="hidden"<?php endif; ?> style="padding-right:100%;">
				    <?php if ($this->_tpl_vars['aSubMenuItems']['is_price_group']): ?>
			    		<a href="/select/<?php echo $this->_tpl_vars['aItem']['url']; ?>
"><?php echo $this->_tpl_vars['aItem']['name']; ?>
</a>
			    	<?php else: ?>
			    	     <a href="/rubricator/<?php echo $this->_tpl_vars['aItem']['url']; ?>
/<?php echo $this->_tpl_vars['sAutoPreSelected']; ?>
"><?php echo $this->_tpl_vars['aItem']['name']; ?>
</a>
			    	<?php endif; ?>
			   </label>
	    <?php endforeach; endif; unset($_from); ?>
	    <?php if ($this->_tpl_vars['aSubMenuItems']['is_price_group']): ?>
	    <?php if ($this->_tpl_vars['iCount']): ?><div class="show-more"><a href="/select/<?php echo $this->_tpl_vars['aSubMenuItems']['url']; ?>
">Показать все</a></div><?php endif; ?>
	    	<?php else: ?>
	    <?php if ($this->_tpl_vars['iCount']): ?><div class="show-more"><a href="/rubricator/<?php echo $this->_tpl_vars['aSubMenuItems']['url']; ?>
/<?php echo $this->_tpl_vars['sAutoPreSelected']; ?>
">Показать все</a></div><?php endif; ?>
			<?php endif; ?>
	</div>
</li>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>   
 </ul>