<?php /* Smarty version 2.6.18, created on 2019-09-27 17:14:58
         compiled from cart/package_print.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', 'cart/package_print.tpl', 6, false),array('function', 'cycle', 'cart/package_print.tpl', 114, false),)), $this); ?>
<?php $this->assign('print_language_prefix', $this->_tpl_vars['oLanguage']->GetConstant('global:print_language_prefix','ua')); ?>
<center><table width="677px" border="0" cellspacing="0"><tbody><tr><td>
<table width="100%" border="0"><tbody><tr>
<td width="133px">&nbsp;</td>
<td><span style='font-family: Arial;font-size: 8pt;font-style: normal'>
<?php $this->assign('desc_lang', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::cart package print description") : smarty_modifier_cat($_tmp, "_doc_print::cart package print description"))); ?>
<?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['desc_lang']); ?>

</span></td>
</tr></tbody></table>

<br>
<div align="left">
  	<span style='font-family: Arial; font-size: 14pt; font-style: normal;font-weight: bold;'>
	<?php $this->assign('variable_lang', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::Рахунок-фактура №") : smarty_modifier_cat($_tmp, "_doc_print::Рахунок-фактура №"))); ?>
	<?php $this->assign('variable_lang2', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::від") : smarty_modifier_cat($_tmp, "_doc_print::від"))); ?>
    <?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang']); ?>
 <?php echo $this->_tpl_vars['aCartPackage']['id']; ?>
 <?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang2']); ?>
 <?php echo $this->_tpl_vars['oLanguage']->GetPostDate($this->_tpl_vars['aCartPackage']['post_date']); ?>

    </span>
</div>
<br>
<hr color='#000000' size='2px'>

<center>
</center>

<table border="0">
	<tr>
		<td>
			<span style='font-family: Arial; font-size: 10pt; font-style: normal;'>
			<?php $this->assign('variable_lang', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::Recipient_2") : smarty_modifier_cat($_tmp, "_doc_print::Recipient_2"))); ?>
			<?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang']); ?>
:
			</span>
		</td>
		<td>
			<span style='font-family: Arial; font-size: 10pt; font-style: normal;font-weight: bold;'>
			<?php echo $this->_tpl_vars['aActiveAccount']['holder_name']; ?>

			</span>
		</td>
	</tr>
	<tr><td style='height: 7pt;' colspan="1">&nbsp;</td></tr>
	<tr>
		<td>
			<span style='font-family: Arial; font-size: 10pt; font-style: normal;'>
			<?php $this->assign('variable_lang', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::Buyer") : smarty_modifier_cat($_tmp, "_doc_print::Buyer"))); ?>
			<?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang']); ?>
:
			</span>
		</td>
		<td>
			<span style='font-family: Arial; font-size: 10pt; font-style: normal;font-weight: bold;'>
			<?php echo $this->_tpl_vars['aCustomer']['name']; ?>
, <?php echo $this->_tpl_vars['aCustomer']['phone']; ?>
, <?php echo $this->_tpl_vars['aCustomer']['city']; ?>
, <?php echo $this->_tpl_vars['aCustomer']['address']; ?>

			</span>
		</td>
	</tr>
</table>
<br>
<table border="0" width="100%" cellspacing="0" >
	<tr >
    	<td align="center" style='border-top:2px solid #000000;border-left:2px solid #000000;
			border-bottom:1px solid #000000;' >
			<span style='font-family: Arial; font-size: 10pt; font-style: normal;font-weight: bold;'>
			<?php $this->assign('variable_lang', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::№") : smarty_modifier_cat($_tmp, "_doc_print::№"))); ?>
			<?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang']); ?>

			</span>
		</td>
		<td align="center" style='border-top:2px solid #000000;border-left:1px solid #000000;
			border-bottom:1px solid #000000;'>
			<span style='font-family: Arial; font-size: 10pt; font-style: normal;font-weight: bold;'>
			<?php $this->assign('variable_lang', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::Товар") : smarty_modifier_cat($_tmp, "_doc_print::Товар"))); ?>
			<?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang']); ?>

			</span>
		</td>
		<td align="center" style='border-top:2px solid #000000;border-left:1px solid #000000;
			border-bottom:1px solid #000000;'>
			<span style='font-family: Arial; font-size: 10pt; font-style: normal;font-weight: bold;'>
			<?php $this->assign('variable_lang', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::Бренд") : smarty_modifier_cat($_tmp, "_doc_print::Бренд"))); ?>
			<?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang']); ?>

		</td>
		<td align="center" style='border-top:2px solid #000000;border-left:1px solid #000000;
			border-bottom:1px solid #000000;'>
			<span style='font-family: Arial; font-size: 10pt; font-style: normal;font-weight: bold;'>
			<?php $this->assign('variable_lang', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::№ кат.") : smarty_modifier_cat($_tmp, "_doc_print::№ кат."))); ?>
			<?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang']); ?>

			</span>
		</td>
		<td align="center" style='border-top:2px solid #000000;border-left:1px solid #000000;
			border-bottom:1px solid #000000;'>
			<span style='font-family: Arial; font-size: 10pt; font-style: normal;font-weight: bold;'>
			<?php $this->assign('variable_lang', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::Кол-во") : smarty_modifier_cat($_tmp, "_doc_print::Кол-во"))); ?>
			<?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang']); ?>

			</span>
		</td>
		<td align="center" style='border-top:2px solid #000000;border-left:1px solid #000000;
			border-bottom:1px solid #000000;'>
			<span style='font-family: Arial; font-size: 10pt; font-style: normal;font-weight: bold;'>
			<?php $this->assign('variable_lang', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::Ед.") : smarty_modifier_cat($_tmp, "_doc_print::Ед."))); ?>
			<?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang']); ?>

			</span>
		</td>
		<td align="center" style='border-top:2px solid #000000;border-left:1px solid #000000;
			border-bottom:1px solid #000000;'>
			<span style='font-family: Arial; font-size: 10pt; font-style: normal;font-weight: bold;'>
			<?php $this->assign('variable_lang', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::Цена") : smarty_modifier_cat($_tmp, "_doc_print::Цена"))); ?>
			<?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang']); ?>

			</span>
		</td>
		<td align="center" style='border-top:2px solid #000000;border-left:1px solid #000000;
			border-bottom:1px solid #000000;border-right:2px solid #000000;'>
			<span style='font-family: Arial; font-size: 10pt; font-style: normal;font-weight: bold;'>
			<?php $this->assign('variable_lang', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::Сумма") : smarty_modifier_cat($_tmp, "_doc_print::Сумма"))); ?>
			<?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang']); ?>

			</span>
		</td>

		<?php $_from = $this->_tpl_vars['aUserCart']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['iKey'] => $this->_tpl_vars['aItem']):
?>
			<tr class="<?php echo smarty_function_cycle(array('values' => "even,none"), $this);?>
">
				<td align="center" style='border-left:2px solid #000000;
					border-bottom:1px solid #000000;'>
					<span style='font-family: Arial; font-size: 8pt; font-style: normal;'>
					<?php echo $this->_tpl_vars['iKey']+1; ?>

					</span>
				</td>
				<td style='border-left:1px solid #000000; border-bottom:1px solid #000000;'>
					<span style='font-family: Arial; font-size: 8pt; font-style: normal;'>
					<?php echo $this->_tpl_vars['oContent']->PrintPartName($this->_tpl_vars['aItem']); ?>

					</span>
				</td>
				<td style='border-left:1px solid #000000; border-bottom:1px solid #000000;'>
					<span style='font-family: Arial; font-size: 8pt; font-style: normal;'>
					<?php echo $this->_tpl_vars['aItem']['cat_name']; ?>
<?php if ($this->_tpl_vars['aItem']['cat_name_changed']): ?> => <?php echo $this->_tpl_vars['aItem']['cat_name_changed']; ?>
<?php endif; ?>
					</span>
				</td>
				<td style='border-left:1px solid #000000; border-bottom:1px solid #000000;'>
					<span style='font-family: Arial; font-size: 8pt; font-style: normal;'>
					<?php if ($this->_tpl_vars['aItem']['code_visible']): ?> <?php echo $this->_tpl_vars['aItem']['code']; ?>
 <?php if ($this->_tpl_vars['aItem']['code_changed']): ?>=> <?php echo $this->_tpl_vars['aItem']['code_changed']; ?>
<?php endif; ?><?php else: ?><i><?php echo $this->_tpl_vars['oLanguage']->getMessage('cart_invisible'); ?>
</i><?php endif; ?>
					</span>
				</td>
				<td align="center" style='border-left:1px solid #000000; border-bottom:1px solid #000000;'>
					<span style='font-family: Arial; font-size: 8pt; font-style: normal;'>
					<?php echo $this->_tpl_vars['aItem']['number']; ?>

					</span>
				</td>
				<td align="center" style='border-left:1px solid #000000; border-bottom:1px solid #000000;'>
					<span style='font-family: Arial; font-size: 8pt; font-style: normal;'>
					<?php if ($this->_tpl_vars['aItem']['unit_name']): ?>
						<?php $this->assign('variable_lang', ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::") : smarty_modifier_cat($_tmp, "_doc_print::")))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['aItem']['unit_name']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['aItem']['unit_name']))); ?>
					<?php else: ?>
						<?php $this->assign('variable_lang', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::шт.") : smarty_modifier_cat($_tmp, "_doc_print::шт."))); ?>
					<?php endif; ?>
					<?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang']); ?>

					</span>
				</td>
				<td align="right" style='border-left:1px solid #000000; border-bottom:1px solid #000000;'>
					<span style='font-family: Arial; font-size: 8pt; font-style: normal;'>
					<?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aItem']['price'],1); ?>

					</span>
				</td>
				<td align="right" style='border-left:1px solid #000000;
					border-bottom:1px solid #000000;border-right:2px solid #000000;'>
					<span style='font-family: Arial; font-size: 8pt; font-style: normal;'>
					<?php $this->assign('price', $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aItem']['price'],1,2,'<none>')); ?>
					<?php $this->assign('price_number', $this->_tpl_vars['price']*$this->_tpl_vars['aItem']['number']); ?>
					<?php echo $this->_tpl_vars['oCurrency']->PrintSymbol($this->_tpl_vars['price_number'],1); ?>

					</span>
				</td>
			</tr>
		<?php endforeach; endif; unset($_from); ?>
			<tr>
				<td colspan="8" style='border-top:1px solid #000000;'>&nbsp;</td>
			</tr>
			<tr>
			<td colspan="7" align="right" >
	    	<span style='font-family: Arial; font-size: 10pt; font-style: normal;font-weight: bold;'>
			<?php $this->assign('variable_lang', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::Сума по документу") : smarty_modifier_cat($_tmp, "_doc_print::Сума по документу"))); ?>
			<?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang']); ?>

	    	</span>
            </td>
            <td align="center">
            <span style='font-family: Arial; font-size: 10pt; font-style: normal;font-weight: bold;'>
	    	<?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aCartPackage']['price_total']-$this->_tpl_vars['aCartPackage']['price_delivery'],1); ?>

	    	</span>
	    	
	    </td>
			</tr>
	<?php if ($this->_tpl_vars['aCartPackage']['price_delivery'] > 0): ?>
	<tr>
	    <td colspan="7" align="right" >
	    	<span style='font-family: Arial; font-size: 10pt; font-style: normal;font-weight: bold;'>
			<?php $this->assign('variable_lang', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::Доставка") : smarty_modifier_cat($_tmp, "_doc_print::Доставка"))); ?>
			<?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang']); ?>

	    	</span>
	    </td>
	    <td align="center">
	    	<span style='font-family: Arial; font-size: 10pt; font-style: normal;font-weight: bold;'>
	    	<?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aCartPackage']['price_delivery'],1); ?>

	    	</span>
	    </td>
    </tr>
    <?php endif; ?>
    <tr>
	    <td colspan="7" align="right">
	    	<span style='font-family: Arial; font-size: 10pt; font-style: normal;font-weight: bold;'>
			<?php $this->assign('variable_lang', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::Итого:") : smarty_modifier_cat($_tmp, "_doc_print::Итого:"))); ?>
			<?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang']); ?>

	    	</span>
	    </td>
	    <td align="center">
	    	<span style='font-family: Arial; font-size: 10pt; font-style: normal;font-weight: bold;'>
	    	<?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aCartPackage']['price_total'],1); ?>

	    	</span>
	    </td>
    </tr>
    <tr>
	    <td colspan="7" align="right">
	    	<span style='font-family: Arial; font-size: 10pt; font-style: normal;font-weight: bold;'>
			<?php $this->assign('variable_lang', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::Без НДС") : smarty_modifier_cat($_tmp, "_doc_print::Без НДС"))); ?>
			<?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang']); ?>

	    	</span>
	    </td>
	    <td align="center">&nbsp;</td>
    </tr>
</table>

<span style='font-family: Arial; font-size: 10pt; font-style: normal;'>
<?php $this->assign('variable_lang', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::Всего наименований") : smarty_modifier_cat($_tmp, "_doc_print::Всего наименований"))); ?>
<?php $this->assign('variable_lang2', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::на сумму") : smarty_modifier_cat($_tmp, "_doc_print::на сумму"))); ?>
<?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang']); ?>
 <?php echo $this->_tpl_vars['iKey']+1; ?>
, <?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang2']); ?>
 <?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aCartPackage']['price_total']-$this->_tpl_vars['aCartPackage']['price_delivery'],1); ?>

</span>
<br>
<span style='font-family: Arial; font-size: 10pt; font-style: normal;font-weight: bold;'>
<?php $this->assign('variable_lang', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::Разом сума по документу") : smarty_modifier_cat($_tmp, "_doc_print::Разом сума по документу"))); ?>
<?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang']); ?>

<?php echo $this->_tpl_vars['aCartPackage']['price_total_string']; ?>

</span>

<br>
<hr color='#000000' size='2px'>

<div style="position: relative; height: 140px;">
	<div style="position: absolute; z-index: 10; font-family: Arial; font-size: 10pt; font-style: normal;">
		<br><br><br>
		<?php $this->assign('variable_lang', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::Руководитель") : smarty_modifier_cat($_tmp, "_doc_print::Руководитель"))); ?>
		<?php $this->assign('variable_lang2', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::одержав") : smarty_modifier_cat($_tmp, "_doc_print::одержав"))); ?>
		<?php $this->assign('variable_lang3', ((is_array($_tmp=$this->_tpl_vars['print_language_prefix'])) ? $this->_run_mod_handler('cat', true, $_tmp, "_doc_print::претензия") : smarty_modifier_cat($_tmp, "_doc_print::претензия"))); ?>
		<?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang']); ?>
 _______________________ <!--(<?php echo $this->_tpl_vars['aActiveAccount']['holder_sign']; ?>
)-->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang2']); ?>
_______________________
		<div style="position: absolute;right:0px;z-index: 10; font-family: Arial; font-size: 10pt; font-style: normal;">
		<br><?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['variable_lang3']); ?>

		</div>
	</div>

		<!--img src="<?php echo $this->_tpl_vars['aActiveAccount']['holder_stamp']; ?>
" style="position: absolute; z-index: 5; left: 80px; top: 0px;"-->
</div>
	<div style="position: relative; height: 100px;"></div>

</td></tr></tbody></table></center>
<?php echo '
<style>
<!--
@page {
  margin: 0;
}

-->
</style>
'; ?>