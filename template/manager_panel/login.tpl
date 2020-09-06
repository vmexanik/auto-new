<HTML>
<HEAD>
<TITLE>{$sProjectName} mp v{$sManagerVersion} - ManagerPanel</TITLE>
	<META content="text/html; charset={$aGeneralConf.Charset}" http-equiv=Content-Type>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="SHORTCUT ICON" href="{$sMainUrlHttp}favicon.ico">
 
 <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="../css/bootstrap/bootstrap-theme.min.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../css/bootstrap/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/bootstrap/theme.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../js/bootstrap/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <link href="../css/bootstrap/bootstrap-select.css" rel="stylesheet">
    <link href="../css/bootstrap/bootstrap-table.css" rel="stylesheet">
    <link href="../css/bootstrap/bootstrap-datetimepicker.css" rel="stylesheet">
    <link href="../css/manager_panel_popup.css" rel=stylesheet type=text/css>
    <LINK href="../css/manager_panel.css" rel=stylesheet type=text/css>

    {$sHeadAdditional}

</HEAD>
<BODY>
	{$sTopMenu}
	<div id="message_javascript"></div>
	<div class="container theme-showcase" id="win_text">{$sText}</div>
	<div id="result_text"></div>
	<div style="display: none" class="at-block-popup js_manager_panel_popup">
	    <div class="dark js-popup-toggle"></div>
	    <div class="block-popup panel panel-default">
    		<div class="panel-heading">
        		<h3 class="panel-title" id="title_popup"></h3>
        		<a class="close js-popup-toggle" href="javascript: void(0);">&nbsp;</a>
    		</div>
    		<div class="panel-body" id="body_popup"></div>
		<div id="reg_error"></div>
    	    </div>
	</div>
	<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
	<!-- @@@@@@@@@@@@@@@@@@  XAJAX Javascript Code @@@@@@@@@@@@@@@ -->
	<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
	
	{$sXajaxJavascript}
	<script>
	/*xajax.loadingFunction = show_loading;
	xajax.doneLoadingFunction = hide_loading;*/
	</script>
	
	<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
	
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../js/jquery-1.9.1.js"><\/script>')</script>
    <script src="../js/bootstrap/bootstrap.min.js"></script>
    <script src="../js/bootstrap/docs.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../js/bootstrap/ie10-viewport-bug-workaround.js"></script>
    <script src="../js/jquery.maskedinput.min.js"></script>
    <script src="../libp/mpanel/js/opacity.js"></script>
    <script src="../js/manager_panel.js"></script>
    <script src="../js/functions.js"></script>
    <script src="../js/bootstrap/bootstrap-select.js"></script>
    <script src="../js/i18n/defaults-ru_RU.js"></script>
    <script src="../js/popcalendar/popcalendar_ru.js"></script>
    <script src="../libp/js/table.js"></script>
    <!--<script src="../js/bootstrap/moment.js"></script>-->
    <script src="../js/bootstrap/moment-with-locales.js"></script>
    <script src="../js/bootstrap/bootstrap-datetimepicker.js"></script>
</BODY>
</HTML>