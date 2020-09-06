<?php /* Smarty version 2.6.18, created on 2020-07-27 10:43:03
         compiled from manager/link_package_search.tpl */ ?>
<div class="at-user-details">

    <div class="at-tabs">
        <div class="tabs-head">
            <a href="/?action=manager_package_list" class="js-tab <?php if (! $_REQUEST['search_order_status']): ?>selected<?php endif; ?>" data-tab="1">
                <?php echo $this->_tpl_vars['oLanguage']->GetMessage('All'); ?>

            </a>
            <a href="/?action=manager_package_list&search_order_status=work" class="js-tab <?php if ($_REQUEST['search_order_status'] == 'work'): ?>selected<?php endif; ?>" data-tab="1">
                <?php echo $this->_tpl_vars['oLanguage']->GetMessage('Work'); ?>

            </a>
            <a href="/?action=manager_package_list&search_order_status=pending" class="js-tab <?php if ($_REQUEST['search_order_status'] == 'pending'): ?>selected<?php endif; ?>" data-tab="1">
                <?php echo $this->_tpl_vars['oLanguage']->GetMessage('Pending'); ?>

            </a>
            <a href="/?action=manager_package_list&search_order_status=end" class="js-tab <?php if ($_REQUEST['search_order_status'] == 'end'): ?>selected<?php endif; ?>" data-tab="1">
                <?php echo $this->_tpl_vars['oLanguage']->GetMessage('End'); ?>

            </a>
            <a href="/?action=manager_package_list&search_order_status=refused" class="js-tab <?php if ($_REQUEST['search_order_status'] == 'refused'): ?>selected<?php endif; ?>" data-tab="1">
                <?php echo $this->_tpl_vars['oLanguage']->GetMessage('Refused'); ?>

            </a>
        </div>

        <div class="mob-tabs-select">
            <select class="js-select" onchange="document.location=this.options[this.selectedIndex].value;">
                <option value="/?action=manager_package_list"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('All'); ?>
</option>
                <option value="/?action=manager_package_list&search_order_status=work"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Work'); ?>
</option>
                <option value="/?action=manager_package_list&search_order_status=pending"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Pending'); ?>
</option>
                <option value="/?action=manager_package_list&search_order_status=end"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('End'); ?>
</option>
                <option value="/?action=manager_package_list&search_order_status=refused"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Refused'); ?>
</option>
            </select>
        </div>

    </div>
</div>