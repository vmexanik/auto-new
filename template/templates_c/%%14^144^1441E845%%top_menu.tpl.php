<?php /* Smarty version 2.6.18, created on 2019-09-25 12:23:17
         compiled from manager_panel/top_menu.tpl */ ?>
<nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Сайт</a>
          </div>
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
            	<?php $_from = $this->_tpl_vars['aMenuTop']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sKey'] => $this->_tpl_vars['sTitle']):
?>
			  		<li id="<?php echo $this->_tpl_vars['sKey']; ?>
"><a href="#" onclick="xajax_process_browse_url('?action=<?php echo $this->_tpl_vars['sKey']; ?>
&click_from_menu=1'); return false;"><?php echo $this->_tpl_vars['sTitle']; ?>
</a></li>
			  	<?php endforeach; endif; unset($_from); ?>
            </ul>
            <div class="bell">
          		<a href="#" onclick="xajax_process_browse_url('?action=manager_panel_manager_package_list_view_logcp&click_from_menu=1&display='+$('.js_manager_panel_popup_log').css('display')); return false;">
			    <img id="id_ding" src="/image/design/<?php if ($this->_tpl_vars['aAuthUser']['is_warning']): ?>ding_alarm.png<?php else: ?>ding.png<?php endif; ?>" style="border:0px;vertical-align: middle;">
          		</a>
	    <div class="js_manager_panel_popup_log" style="display: none;">
		<div class="block-popup panel-default">
    		    <div class="panel-body" id="body_popup_logcp" style="overflow:auto;"></div>
    		</div>
	    </div>

          		<div class="currency">
	          		<?php $_from = $this->_tpl_vars['aListCurrency']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['sItem']):
?>
	          			<span><?php echo $this->_tpl_vars['key']; ?>
: <?php echo $this->_tpl_vars['sItem']; ?>
</span>
	          		<?php endforeach; endif; unset($_from); ?>
          		</div>
          		<div class="logout">
          			<a href="./?action=quit"><?php echo $this->_tpl_vars['oLanguage']->getDMessage('logout'); ?>
</a>
          		</div>
          	</div>
          </div><!--/.nav-collapse -->
        </div>
      </nav>