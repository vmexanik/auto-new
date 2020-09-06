<?php /* Smarty version 2.6.18, created on 2020-08-13 11:00:10
         compiled from header.tpl */ ?>
<!DOCTYPE html>
<html>
<head>
   <title><?php echo ''; ?><?php if ($this->_tpl_vars['template']['sPageTitle']): ?><?php echo ''; ?><?php echo $this->_tpl_vars['template']['sPageTitle']; ?><?php echo ''; ?><?php else: ?><?php echo ''; ?><?php echo $this->_tpl_vars['oLanguage']->GetConstant('global:title','global:titleconstant'); ?><?php echo ''; ?><?php endif; ?><?php echo ''; ?>
</title>
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
   	<meta name="description" content="<?php echo ''; ?><?php if ($this->_tpl_vars['template']['sPageDescription']): ?><?php echo ''; ?><?php echo $this->_tpl_vars['template']['sPageDescription']; ?><?php echo ''; ?><?php else: ?><?php echo ''; ?><?php echo $this->_tpl_vars['oLanguage']->GetConstant('global:meta_description','global:meta_descriptionconstant'); ?><?php echo ''; ?><?php endif; ?><?php echo ''; ?>
" />
	<meta name="keywords" content="<?php echo ''; ?><?php if ($this->_tpl_vars['template']['sPageKeyword']): ?><?php echo ''; ?><?php echo $this->_tpl_vars['template']['sPageKeyword']; ?><?php echo ''; ?><?php else: ?><?php echo ''; ?><?php echo $this->_tpl_vars['oLanguage']->GetConstant('global:meta_keyword','global:meta_keywordconstant'); ?><?php echo ''; ?><?php endif; ?><?php echo ''; ?>
" />
   <meta name="format-detection" content="telephone=no"/>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
   <link rel="SHORTCUT ICON" <?php if ($this->_tpl_vars['sFaviconType']): ?>type="<?php echo $this->_tpl_vars['sFaviconType']; ?>
"<?php endif; ?> href="<?php echo $this->_tpl_vars['oLanguage']->GetConstant('favicon','/favicon.ico'); ?>
" />

   <link href="https://fonts.googleapis.com/css?family=Rubik:300,300i,400,400i,500&amp;subset=cyrillic" rel="stylesheet">
   <?php echo $this->_tpl_vars['template']['sHeaderResource']; ?>

   <?php if ($this->_tpl_vars['bHeaderPrint']): ?> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header_print.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <?php endif; ?>
   
<?php if (isset ( $this->_tpl_vars['sNextUrl'] )): ?>
<link rel="next" href="<?php echo $this->_tpl_vars['sNextUrl']; ?>
">
<?php endif; ?>

<?php if (isset ( $this->_tpl_vars['sPrevUrl'] )): ?>
<link rel="prev" href="<?php echo $this->_tpl_vars['sPrevUrl']; ?>
">
<?php endif; ?>

<?php if (isset ( $this->_tpl_vars['sUrlCanonical'] )): ?>
<link rel="canonical" href="<?php echo $this->_tpl_vars['sUrlCanonical']; ?>
">
<?php else: ?>
  <?php if ($this->_tpl_vars['bNoFollow'] && $this->_tpl_vars['bNoIndex']): ?>
  <meta name="robots" content="noindex, follow"/>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['bNoIndexNoFollow']): ?>
  <meta name="robots" content="noindex, nofollow"/>
  <?php endif; ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['sAlternateUrl'] && $this->_tpl_vars['sAlternateUrlUa']): ?>
<link rel="alternate" href="<?php echo $this->_tpl_vars['sAlternateUrl']; ?>
" hreflang="ru-UA"> 
<link rel="alternate" href="<?php echo $this->_tpl_vars['sAlternateUrlUa']; ?>
" hreflang="uk-UA">
<?php endif; ?>
   
   <script src="https://www.google.com/recaptcha/api.js" async defer></script>
   
</head>