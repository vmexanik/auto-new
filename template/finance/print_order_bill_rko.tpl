{assign var="print_language_prefix" value=$oLanguage->GetConstant('global:print_language_prefix','ua')}
<TABLE WIDTH=686 CELLPADDING=0 CELLSPACING=0>
	<COL WIDTH=119>
	<COL WIDTH=61>
	<COL WIDTH=61>
	<COL WIDTH=61>
	<COL WIDTH=128>
	<COL WIDTH=61>
	<COL WIDTH=61>
	<COL WIDTH=67>
	<COL WIDTH=64>
	<TR>
		<TD WIDTH=119 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=128 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=67 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=64 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
	</TR>
	<TR>
		<TD ROWSPAN=2 COLSPAN=5 WIDTH=431 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Склад"}
			<P><FONT SIZE=2 STYLE="font-size: 9pt">{$oLanguage->GetMessage($variable_lang)}:<SPAN LANG="uk-UA">
			<b>Центральний</b></SPAN></FONT></P>
		</TD>
		<TD COLSPAN=4 WIDTH=254 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Типова форма № КО-1"}
			<P ALIGN=RIGHT><FONT SIZE=2 STYLE="font-size: 9pt">{$oLanguage->GetMessage($variable_lang)}</FONT></P>
		</TD>
	</TR>
	<TR>
		<TD COLSPAN=4 WIDTH=254 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Затверджена наказом Мінстату"}
			<P ALIGN=RIGHT><FONT SIZE=2 STYLE="font-size: 9pt">{$oLanguage->GetMessage($variable_lang)}</FONT></P>
		</TD>
	</TR>
	<TR>
		<TD ROWSPAN=2 WIDTH=119 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Код за ЄДРПОУ"}
			<P ALIGN=RIGHT><FONT SIZE=2 STYLE="font-size: 9pt">{$oLanguage->GetMessage($variable_lang)}</FONT></P>
		</TD>
		<TD ROWSPAN=2 COLSPAN=2 WIDTH=123 STYLE="border: none; padding: 0cm">
			<P ALIGN=CENTER><BR>
			</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=128 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD COLSPAN=4 WIDTH=254 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::України від 15.02.96р. №54"}
			<P ALIGN=RIGHT><FONT SIZE=2 STYLE="font-size: 9pt">{$oLanguage->GetMessage($variable_lang)}</FONT></P>
		</TD>
	</TR>
	<TR>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=128 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD COLSPAN=3 WIDTH=190 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Код за УКУД"}
			<P><FONT SIZE=2 STYLE="font-size: 9pt">{$oLanguage->GetMessage($variable_lang)}</FONT></P>
		</TD>
		<TD WIDTH=64 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
	</TR>
	<TR>
		<TD COLSPAN=9 WIDTH=686 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::ВИДАТКОВИЙ КАСОВИЙ ОРДЕР"}
			<P ALIGN=CENTER><FONT SIZE=2><B>{$oLanguage->GetMessage($variable_lang)}</B></FONT>
			</P>
		</TD>
	</TR>
	<TR>
		<TD WIDTH=119 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Номер документу"}
			<P ALIGN=CENTER><FONT SIZE=2 STYLE="font-size: 9pt">{$oLanguage->GetMessage($variable_lang)}</FONT></P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Дата складання"}
			<P ALIGN=CENTER><FONT SIZE=2 STYLE="font-size: 9pt">{$oLanguage->GetMessage($variable_lang)}</FONT></P>
		</TD>
		<TD COLSPAN=2 WIDTH=123 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Кореспондуючий рахунок, субрахунок"}
			<P ALIGN=CENTER><FONT SIZE=2 STYLE="font-size: 9pt">{$oLanguage->GetMessage($variable_lang)}</FONT></P>
		</TD>
		<TD WIDTH=128 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Код аналітичного обліку"}
			<P ALIGN=CENTER><FONT SIZE=2 STYLE="font-size: 9pt">{$oLanguage->GetMessage($variable_lang)}</FONT></P>
		</TD>
		<TD COLSPAN=2 WIDTH=123 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Сума"}
			<P ALIGN=CENTER><FONT SIZE=2 STYLE="font-size: 9pt">{$oLanguage->GetMessage($variable_lang)}</FONT></P>
		</TD>
		<TD COLSPAN=2 WIDTH=132 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Код цільового призначення"}
			<P ALIGN=CENTER><FONT SIZE=2 STYLE="font-size: 9pt">{$oLanguage->GetMessage($variable_lang)}</FONT></P>
		</TD>
	</TR>
	<TR>
		<TD WIDTH=119 STYLE="border: none; padding: 0cm">
			<P ALIGN=CENTER><FONT SIZE=2>{$aBill.id}</FONT></P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P ALIGN=CENTER><FONT SIZE=2>{$aBill.post_date|date_format:"%d.%m.%Y"}</FONT></P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=128 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD COLSPAN=2 WIDTH=123 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::грн."}
			<P ALIGN=CENTER><FONT SIZE=2><B><SPAN LANG="uk-UA">{$aBill.amount}</SPAN>{$oLanguage->GetMessage($variable_lang)}</B></FONT></P>
		</TD>
		<TD WIDTH=67 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=64 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
	</TR>
	<TR>
		<TD COLSPAN=9 WIDTH=686 STYLE="border: none; padding: 0cm">
			<P><FONT SIZE=2><B>Видати </B>{$aUser.name}&nbsp; ({$aUser.login})</FONT></P>
		</TD>
	</TR>
	<TR>
		<TD COLSPAN=9 WIDTH=686 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::прізвище, ім`я, по-батькові"}
			<P ALIGN=CENTER><FONT SIZE=2 STYLE="font-size: 9pt">{$oLanguage->GetMessage($variable_lang)}</FONT></P>
		</TD>
	</TR>
	<TR>
		<TD COLSPAN=9 WIDTH=686 STYLE="border: none; padding: 0cm">
			<P><FONT SIZE=2><B>Підстава<SPAN LANG="uk-UA">:</SPAN>
			<SPAN LANG="uk-UA">Повернення коштів клієнту</SPAN></B></FONT></P>
		</TD>
	</TR>
	<TR>
		<TD WIDTH=119 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=128 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=67 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=64 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
	</TR>
	<TR>
		<TD COLSPAN=9 WIDTH=686 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Сума"}
			<P><FONT SIZE=2><B>{$oLanguage->GetMessage($variable_lang)}: <SPAN LANG="uk-UA">{$aBill.amount_string}</SPAN></B></FONT></P>
		</TD>
	</TR>
	<TR>
		<TD COLSPAN=9 WIDTH=686 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::без ПДВ"}
			<P ALIGN=CENTER><FONT SIZE=2><B>{$oLanguage->GetMessage($variable_lang)}</B></FONT></P>
		</TD>
	</TR>
	<TR>
		<TD COLSPAN=9 WIDTH=686 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Додаток"}
			<P><FONT SIZE=2 STYLE="font-size: 9pt">{$oLanguage->GetMessage($variable_lang)}</FONT></P>
		</TD>
	</TR>
	<TR>
		<TD COLSPAN=5 WIDTH=431 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Керівник"}
			<P><FONT SIZE=2 STYLE="font-size: 9pt">{$oLanguage->GetMessage($variable_lang)}</FONT>
			</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD COLSPAN=3 WIDTH=193 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Головний бухгалтер"}
			<P><FONT SIZE=2 STYLE="font-size: 9pt">{$oLanguage->GetMessage($variable_lang)}</FONT></P>
		</TD>
	</TR>
	<TR>
		<TD WIDTH=119 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Одержав"}
			<P><FONT SIZE=2 STYLE="font-size: 9pt">{$oLanguage->GetMessage($variable_lang)}</FONT></P>
		</TD>
		<TD COLSPAN=8 WIDTH=567 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::грн."}
			{assign var="variable_lang2" value=$print_language_prefix|cat:"_doc_print::коп."}
			<P><FONT SIZE=2 STYLE="font-size: 9pt">______________________________________________________________
			{$oLanguage->GetMessage($variable_lang)} __________ {$oLanguage->GetMessage($variable_lang2)}</FONT></P>
		</TD>
	</TR>
	<TR>
		<TD COLSPAN=5 WIDTH=431 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::р."}
			<P><FONT SIZE=2 STYLE="font-size: 9pt">&quot; ____ &quot;
			_______________ 20&nbsp&nbsp{$oLanguage->GetMessage($variable_lang)}</FONT></P>
		</TD>
		<TD COLSPAN=3 WIDTH=190 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Підпис"}
			<P><FONT SIZE=2 STYLE="font-size: 9pt">{$oLanguage->GetMessage($variable_lang)} __________ </FONT>
			</P>
		</TD>
		<TD WIDTH=64 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
	</TR>
	<TR>
		<TD WIDTH=119 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=128 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=67 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=64 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
	</TR>
	<TR>
		<TD WIDTH=119 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=128 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=67 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=64 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
	</TR>
	<TR>
		<TD COLSPAN=8 WIDTH=621 STYLE="border: none; padding: 0cm">
			<P><FONT SIZE=2 STYLE="font-size: 9pt">Назва, номер,
			дата та мiсце видачi документу, який
			засвідчує особу одержувача</FONT></P>
		</TD>
		<TD WIDTH=64 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
	</TR>
	<TR>
		<TD COLSPAN=9 WIDTH=686 STYLE="border: none; padding: 0cm">
			{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Видав касир"}
			<P><FONT SIZE=2 STYLE="font-size: 9pt">{$oLanguage->GetMessage($variable_lang)}</FONT></P>
		</TD>
	</TR>
	<TR>
		<TD WIDTH=119 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=128 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=67 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=64 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
	</TR>
	<TR>
		<TD WIDTH=119 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=128 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=61 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=67 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
		<TD WIDTH=64 STYLE="border: none; padding: 0cm">
			<P>&nbsp;</P>
		</TD>
	</TR>
</TABLE>
<P CLASS="western" STYLE="margin-bottom: 0cm"><BR>
</P>