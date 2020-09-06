<?

$aInitSql=array(
'CREATE TABLE IF NOT EXISTS `template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `type_` enum(\'letter\',\'bill\',\'content\') NOT NULL DEFAULT \'letter\',
  `is_smarty` int(1) NOT NULL DEFAULT \'0\',
  `priority` int(11) NOT NULL DEFAULT \'3\',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_2` (`code`),
  KEY `code` (`code`),
  KEY `type_` (`type_`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;
',
'INSERT INTO `template` (`id`, `code`, `name`, `content`, `type_`, `is_smarty`, `priority`) VALUES
(1, \'confirmation_letter\', \'Активация учетной записи\', \'<table width="100%" cellspacing="1" cellpadding="1" border="0">\n    <tbody>\n        <tr>\n            <td><a href="http://www.altinet.ru"><img width="224" height="88" border="0" alt="" src="http://altinet.mstarproject.com/image/design/logo.png" /></a></td>\n            <td style="text-align: right;">По всем вопросам обращайтесь: г.Москва, Верейская ул, д.32, стр.32А, <br />\n            тел./факс: (495) 662-47-12<br />\n            e-mail: <a href="mailto:info@altinet.ru" target="_blank">info@altinet.ru</a></td>\n        </tr>\n    </tbody>\n</table>\n<h3>Письмо подтверждения Вашей учетной записи на сайте altinet.RU</h3>\n<br />\n<p>Ваш логин на сайте: {$info.login}</p>\n<p>Ваш пароль: {$info.password}</p>\n<p>Зарегистрированный E-mail: {$info.email}</p>\n<p>&nbsp;</p>\n<p>Ваш менеджер на сайте: <strong>{$aManager.name}</strong>. Все текущие вопросы по работе на сайте задавайте ему через систему внутренних <a href="../.././?action=message"><strong>сообщений</strong></a> (<strong>ник: {$aManager.login}</strong>): либо по телефону <strong>{$aManager.phone}</strong>.&nbsp;</p>\n<p>Настроить систему почтовых оповещений вы можете на странице &laquo;<a href="../.././?action=customer_notification"><strong>Оповещения</strong></a>&raquo;. Используйте Ваш логин и пароль для доступа к этой странице.</p>\n<p>Вы можете ответить на это письмо, если у Вас возникли вопросы. При ответе просьба цитировать всю переписку.</p>\', \'letter\', 1, 3),
(2, \'unregistered_vin_request\', \'unregistered_vin_request\', \'<h1>VIN запрос пользователя</h1>\n<div class="notes">Ваш запрос отправлен!</div>\n<p class="info">После обработки - Вам будет выслано уведомление на телефон или почту. Вы сможете просмотреть обработаный ВИН запрос после того, как залогинитесь. Далеее нужно проценить подобраные менеджером коды запчастей, выбрать наиболее подходящий Вам вариант цена, срок доставки, поставщик и положить запчасти себе в корзину. Из запчастей в корзине формируются заказы, за них надо внести предоплату - только в такой последовательности Ваш заказ пойдет в работу и будет доставлен в указанный в прайсах срок.</p>\', \'letter\', 0, 3),
(3, \'vin_request_sent\', \'Ответ на запрос по VIN \', \'<table width="100%" cellspacing="1" cellpadding="1" border="0">\n    <tbody>\n        <tr>\n            <td><a href="http://www.altinet.ru"><img width="224" height="88" border="0" src="http://altinet.mstarproject.com/image/design/es_top_left.jpg" alt="" /></a></td>\n            <td style="text-align: right;">По всем вопросам обращайтесь: г.Москва, Верейская ул, д.32, стр.32А, <br />\n            тел./факс: (495) 662-47-12<br />\n            e-mail: <a target="_blank" href="mailto:sales@altinet.ru">sales@altinet.ru</a></td>\n        </tr>\n    </tbody>\n</table>\n<h3>Ответ на запрос по VIN № {$aVinRequest.id}</h3>\n<p>Ваш менеджер обработал Ваш VIN-запрос и подобрал коды к запрашиваемым Вами запчастям<br />\n<br />\nВаш логин на сайте: {$aVinRequest.login}<br />\n<br />\nВаш пароль: {$aVinRequest.password}<br />\n<br />\nВам необходимо открыть обработаный VIN-запрос<br />\nhttp://www.altinet.ru/?action=vin_request<br />\n<br />\nи проценив подобраные детали в нашем <a href="http://www.altinet.ru/?action=catalog">каталоге</a>, выбрать подходящую Вам цену, поставщика и срок доставки запчасти и отправить ее в корзину. После этого вы сможете оформить заказ, он поступит в работу и Ваш менеджер приступит к выполнению Вашего заказа.</p>\', \'letter\', 0, 3),
(6, \'forgot_password_sent\', \'forgot_password_sent\', \'<p>Пароль выслан владельцу на зарегистрированный e-mail</p>\', \'letter\', 0, 3),
(7, \'forgot_password\', \'Восстановление пароля от личного кабинета\', \'<table width="100%" cellspacing="1" cellpadding="1" border="0">\n    <tbody>\n        <tr>\n            <td><a href="http://www.altinet.ru"><img width="224" height="88" border="0" src="http://altinet.mstarproject.com/image/design/es_top_left.jpg" alt="" /></a></td>\n            <td style="text-align: right;">По всем вопросам обращайтесь: г.Москва, Верейская ул, д.32, стр.32А, <br />\n            тел./факс: (495) 662-47-12<br />\n            e-mail: <a target="_blank" href="mailto:info@altinet.ru">info@altinet.ru</a></td>\n        </tr>\n    </tbody>\n</table>\n<h3>Восстановление пароля от личного кабинета altinet.RU</h3>\n<p>Ваш логин на сайте: {$info.login}</p>\n<p>Ваш пароль: {$info.password}</p>\n<p>&nbsp;</p>\n<p>Настроить систему почтовых оповещений вы можете на странице &laquo;<a href="../.././?action=customer_notification"><strong>Оповещения</strong></a>&raquo;. Используйте Ваш логин и пароль для доступа к этой странице.</p>\', \'letter\', 1, 3),
(8, \'vin_request_refused\', \'Отказ на запрос по VIN \', \'<table width="100%" cellspacing="1" cellpadding="1" border="0">\n    <tbody>\n        <tr>\n            <td><a href="http://www.altinet.ru"><img width="224" height="88" border="0" src="http://altinet.mstarproject.com/image/design/es_top_left.jpg" alt="" /></a></td>\n            <td style="text-align: right;">По всем вопросам обращайтесь: г.Москва, Верейская ул, д.32, стр.32А, <br />\n            тел./факс: (495) 662-47-12<br />\n            e-mail: <a target="_blank" href="mailto:info@altinet.ru">info@altinet.ru</a></td>\n        </tr>\n    </tbody>\n</table>\n<p>&nbsp;&nbsp;</p>\n<p><b>Ответ на запрос по Vin  № </b><strong>{$aVinRequest.id}</strong></p>\n<p>Ваш менеджер обработал Ваш VIN-запрос и отказал Вашу заявку</p>\n<p><strong>Ваш логин на сайте</strong>: {$aVinRequest.login}<br />\n<br />\n<span style="font-weight: bold;">Ваш пароль</span>: {$aVinRequest.password}</p>\n<p>Что бы увидеть причину отказа, зайдите в <a target="_blank" href="http://www.altinet.ru/?action=vin_request">VIN запросы</a>&nbsp; под своим логином и паролем<br />\n<a target="_blank" href="../.././?action=vin_request">http://www.altinet.ru/?action=vin_request</a></p>\n<p>&nbsp;</p>\n<p>Комментарий менеджера: {$aVinRequest.manager_comment}</p>\n<p>&nbsp;</p>\n<p>Ваш менеджер на сайте: <strong>{$aManager.name}</strong>. Все текущие вопросы по работе на сайте задавайте ему через систему внутренних <a href="../.././?action=message"><strong>сообщений</strong></a> (<strong>ник: {$aManager.login}</strong>): либо по телефону <strong>{$aManager.phone}</strong>.&nbsp;</p>\n<p>Настроить систему почтовых оповещений вы можете на странице &laquo;<a href="../.././?action=customer_notification"><strong>Оповещения</strong></a>&raquo;. Используйте Ваш логин и пароль для доступа к этой странице.</p>\n<p>Вы можете ответить на это письмо, если у Вас возникли вопросы. При ответе просьба цитировать всю переписку.</p>\', \'letter\', 1, 3),
(9, \'approve_text\', \'Ваша учетная запись была активирована!\', \'<p>Ваша учетная запись была активирована. Вам присвоена скидка: <a href="http://www.altinet.ru/?action=dealer"><strong>Интернет</strong></a></p>\n<p>Ваш менеджер на сайте: {$manager.name}. Все текущие вопросы по работе на сайте задавайте ему через систему внутренних сообщений (ник: {$manager.login}): http://linestamp.ru/?action=message либо по телефону {$manager.phone}.</p>\', \'letter\', 0, 3),
(10, \'approve_error\', \'approve_error\', \'approve_error\', \'letter\', 0, 3),
(21, \'order_is_work\', \'Оповещение: Деталь поступила в работу\', \'<table width="100%" cellspacing="1" cellpadding="1" border="0">\n    <tbody>\n        <tr>\n            <td><a href="http://www.altinet.ru"><img width="224" height="88" border="0" alt="" src="http://altinet.mstarproject.com/image/design/es_top_left.jpg" /></a></td>\n            <td style="text-align: right;">По всем вопросам обращайтесь:&nbsp; г.Москва, Верейская ул, д.32, стр.32А, <br />\n            тел./факс: (495) 662-47-12<br />\n            e-mail: <a href="mailto:info@altinet.ru" target="_blank">info@altinet.ru</a></td>\n        </tr>\n    </tbody>\n</table>\n<p><strong>Уважаемый(-ая)</strong> {$aCustomer.name}</p>\n<p>Ваша заказанная деталь&nbsp;№{$aCart.id} поступила в работу и скоро будет обработана менеджером.</p>\n<p>&nbsp;</p>\n<p>\n<table cellspacing="&quot;0&quot;" cellpadding="&quot;1&quot;" border="&quot;1&quot;" border-color="&quot;blue&quot;">\n    <tbody>\n        <tr>\n            <td>&nbsp;№&nbsp;Заказа&nbsp;</td>\n            <td>&nbsp;Марка&nbsp;</td>\n            <td>&nbsp;Наименование&nbsp;</td>\n            <td>&nbsp;№&nbsp;по&nbsp;Каталогу&nbsp;</td>\n            <td>&nbsp;Кол-во&nbsp;</td>\n            <td>&nbsp;Цена&nbsp;</td>\n            <td>&nbsp;Состояние&nbsp;</td>\n            <td>&nbsp;Дата&nbsp;</td>\n        </tr>\n        <tr>\n            <td>&nbsp;{$aCart.id_cart_package}&nbsp;</td>\n            <td>&nbsp;{$aCart.cat_name}&nbsp;</td>\n            <td>\n            <p>{$aCart.name} {$aCart.name_translate}</p>\n            </td>\n            <td>&nbsp;{$aCart.code}&nbsp;</td>\n            <td>&nbsp;{$aCart.number}&nbsp;</td>\n            <td>&nbsp;{$aCart.price}&nbsp;</td>\n            <td>&nbsp;В работе&nbsp;</td>\n            <td>&nbsp;{$aCart.post_date}&nbsp;</td>\n        </tr>\n    </tbody>\n</table>\n</p>\n<p>Ваш менеджер на сайте: <strong>{$aManager.name}</strong>. Все текущие вопросы по работе на сайте задавайте ему через систему внутренних <a href="http://www.altinet.ru/?action=message"><strong>сообщений</strong></a> (<strong>ник: {$aManager.login}</strong>): либо по телефону <strong>{$aManager.phone}</strong>.</p>\n<p>&nbsp;</p>\n<p>Настроить систему почтовых оповещений вы можете на странице &laquo;<a href="http://www.altinet.ru/?action=customer_notification"><strong>Оповещения</strong></a>&raquo;. Используйте Ваш логин и пароль для доступа к этой странице.</p>\n<p>Вы можете ответить на это письмо, если у Вас возникли вопросы. При ответе просьба цитировать всю переписку.<br />\n&nbsp;</p>\', \'letter\', 1, 3),
(22, \'order_is_confirmed\', \'Оповещение: Деталь подтверждена поставщиком\', \'<table width="100%" cellspacing="1" cellpadding="1" border="0">\n    <tbody>\n        <tr>\n            <td><a href="http://www.altinet.ru"><img width="224" height="88" border="0" src="http://altinet.mstarproject.com/image/design/es_top_left.jpg" alt="" /></a></td>\n            <td style="text-align: right;">По всем вопросам обращайтесь: г.Москва, Верейская ул, д.32, стр.32А, <br />\n            тел./факс: (495) 662-47-12<br />\n            e-mail: <a target="_blank" href="mailto:info@altinet.ru">info@altinet.ru</a></td>\n        </tr>\n    </tbody>\n</table>\n<p><strong>Уважаемый(-ая)</strong> {$info.name}</p>\n<p>Ваша деталь №{$aCart.id} подтверждена поставщиком и будет доставлена к нам на склад в ближайшее время.</p>\n<p>&nbsp;</p>\n<table cellspacing="&quot;0&quot;" cellpadding="&quot;1&quot;" border="&quot;1&quot;" border-color="&quot;blue&quot;">\n    <tbody>\n        <tr>\n            <td>&nbsp;№&nbsp;Заказа&nbsp;</td>\n            <td>&nbsp;Марка&nbsp;</td>\n            <td>&nbsp;Наименование&nbsp;</td>\n            <td>&nbsp;№&nbsp;по&nbsp;Каталогу&nbsp;</td>\n            <td>&nbsp;Кол-во&nbsp;</td>\n            <td>&nbsp;Цена&nbsp;</td>\n            <td>&nbsp;Состояние&nbsp;</td>\n            <td>&nbsp;Дата&nbsp;</td>\n        </tr>\n        <tr>\n            <td>&nbsp;{$aCart.id_cart_package}&nbsp;</td>\n            <td>&nbsp;{$aCart.cat_name}&nbsp;</td>\n            <td>\n            <p>{$aCart.name} {$aCart.name_translate}</p>\n            </td>\n            <td>nbsp;{$aCart.code}&nbsp;</td>\n            <td>&nbsp;{$aCart.number}&nbsp;</td>\n            <td>&nbsp;{$aCart.price}&nbsp;</td>\n            <td>&nbsp;Подтверждено&nbsp;</td>\n            <td>&nbsp;{$aCart.date}&nbsp;</td>\n        </tr>\n    </tbody>\n</table>\n<p>&nbsp;</p>\n<p>Ваш менеджер на сайте: <strong>{$aManager.name}</strong>. Все текущие вопросы по работе на сайте задавайте ему через систему внутренних <a href="../.././?action=message"><strong>сообщений</strong></a> (<strong>ник: {$aManager.login}</strong>): либо по телефону <strong>{$aManager.phone}</strong>.</p>\n<p>&nbsp;</p>\n<p>Настроить систему почтовых оповещений вы можете на странице &laquo;<a href="../.././?action=customer_notification"><strong>Оповещения</strong></a>&raquo;. Используйте Ваш логин и пароль для доступа к этой странице.</p>\n<p>Вы можете ответить на это письмо, если у Вас возникли вопросы. При ответе просьба цитировать всю переписку.</p>\n<p>&nbsp;</p>\', \'letter\', 1, 3),
(23, \'order_is_store\', \'Оповещение: Деталь на складе\', \'<table width="100%" cellspacing="1" cellpadding="1" border="0">\n    <tbody>\n        <tr>\n            <td><a href="http://www.altinet.ru"><img width="224" height="88" border="0" alt="" src="http://altinet.mstarproject.com/image/design/es_top_left.jpg" /></a></td>\n            <td style="text-align: right;">По всем вопросам обращайтесь: г.Москва, Верейская ул, д.32, стр.32А, <br />\n            тел./факс: (495) 662-47-12<br />\n            e-mail: <a href="mailto:info@altinet.ru" target="_blank">info@altinet.ru</a></td>\n        </tr>\n    </tbody>\n</table>\n<p><strong>Уважаемый(-ая)</strong> {$info.name}</p>\n<p>Ваша деталь № {$aCart.id} обработана и находится на нашем складе.</p>\n<p>&nbsp;</p>\n<table cellspacing="&quot;0&quot;" cellpadding="&quot;1&quot;" border="&quot;1&quot;" border-color="&quot;blue&quot;">\n    <tbody>\n        <tr>\n            <td>&nbsp;№&nbsp;Заказа&nbsp;</td>\n            <td>&nbsp;Марка&nbsp;</td>\n            <td>&nbsp;Наименование&nbsp;</td>\n            <td>&nbsp;№&nbsp;по&nbsp;Каталогу&nbsp;</td>\n            <td>&nbsp;Кол-во&nbsp;</td>\n            <td>&nbsp;Цена&nbsp;</td>\n            <td>&nbsp;Состояние&nbsp;</td>\n            <td>&nbsp;Дата&nbsp;</td>\n        </tr>\n        <tr>\n            <td>&nbsp;{$aCart.id_cart_package}&nbsp;</td>\n            <td>&nbsp;{$aCart.cat_name}&nbsp;</td>\n            <td>\n            <p>{$aCart.name} {$aCart.name_translate}</p>\n            </td>\n            <td>&nbsp;{$aCart.code}&nbsp;</td>\n            <td>&nbsp;{$aCart.number}&nbsp;</td>\n            <td>&nbsp;{$aCart.price}&nbsp;</td>\n            <td>&nbsp;На складе&nbsp;</td>\n            <td>&nbsp;{$aCart.date}&nbsp;</td>\n        </tr>\n    </tbody>\n</table>\n<p>&nbsp;</p>\n<p>Ваш менеджер на сайте: <strong>{$aManager.name}</strong>. Все текущие вопросы по работе на сайте задавайте ему через систему внутренних <a href="../.././?action=message"><strong>сообщений</strong></a> (<strong>ник: {$aManager.login}</strong>): либо по телефону <strong>{$aManager.phone}</strong>.</p>\n<p>&nbsp;</p>\n<p>Настроить систему почтовых оповещений вы можете на странице &laquo;<a href="../.././?action=customer_notification"><strong>Оповещения</strong></a>&raquo;. Используйте Ваш логин и пароль для доступа к этой странице.</p>\n<p>Вы можете ответить на это письмо, если у Вас возникли вопросы. При ответе просьба цитировать всю переписку.</p>\n<p>&nbsp;</p>\', \'letter\', 1, 3),
(24, \'order_is_refused\', \'Оповещение: Деталь отказана поставщиком\', \'<table width="100%" cellspacing="1" cellpadding="1" border="0">\n    <tbody>\n        <tr>\n            <td><a href="http://www.altinet.ru"><img width="224" height="88" border="0" alt="" src="http://altinet.mstarproject.com/image/design/es_top_left.jpg" /></a></td>\n            <td style="text-align: right;">По всем вопросам обращайтесь: г.Москва, Верейская ул, д.32, стр.32А, <br />\n            тел./факс: (495) 662-47-12<br />\n            e-mail: <a href="mailto:info@altinet.ru" target="_blank">info@altinet.ru</a></td>\n        </tr>\n    </tbody>\n</table>\n<p><strong>Уважаемый(-ая)</strong> {$info.name}</p>\n<p>Деталь № {$aCart.id} отказана в поставке.</p>\n<p>&nbsp;</p>\n<p>\n<table cellspacing="&quot;0&quot;" cellpadding="&quot;1&quot;" border="&quot;1&quot;" border-color="&quot;blue&quot;">\n    <tbody>\n        <tr>\n            <td>&nbsp;№&nbsp;Заказа&nbsp;</td>\n            <td>&nbsp;Марка&nbsp;</td>\n            <td>&nbsp;Наименование&nbsp;</td>\n            <td>&nbsp;№&nbsp;по&nbsp;Каталогу&nbsp;</td>\n            <td>&nbsp;Кол-во&nbsp;</td>\n            <td>&nbsp;Цена&nbsp;</td>\n            <td>&nbsp;Состояние&nbsp;</td>\n            <td>&nbsp;Дата&nbsp;</td>\n        </tr>\n        <tr>\n            <td>&nbsp;{$aCart.id_cart_package}&nbsp;</td>\n            <td>&nbsp;{$aCart.cat_name}&nbsp;</td>\n            <td>\n            <p>{$aCart.name} {$aCart.name_translate}</p>\n            </td>\n            <td>&nbsp;{$aCart.code}&nbsp;</td>\n            <td>&nbsp;{$aCart.number}&nbsp;</td>\n            <td>&nbsp;{$aCart.price}&nbsp;</td>\n            <td>&nbsp;Отказано&nbsp;</td>\n            <td>&nbsp;{$aCart.date}&nbsp;</td>\n        </tr>\n    </tbody>\n</table>\n</p>\n<p>Ваш менеджер на сайте: <strong>{$aManager.name}</strong>. Все текущие вопросы по работе на сайте задавайте ему через систему внутренних <a href="../.././?action=message"><strong>сообщений</strong></a> (<strong>ник: {$aManager.login}</strong>): либо по телефону <strong>{$aManager.phone}</strong>.</p>\n<p>&nbsp;</p>\n<p>Настроить систему почтовых оповещений вы можете на странице &laquo;<a href="../.././?action=customer_notification"><strong>Оповещения</strong></a>&raquo;. Используйте Ваш логин и пароль для доступа к этой странице.</p>\n<p>Вы можете ответить на это письмо, если у Вас возникли вопросы. При ответе просьба цитировать всю переписку.</p>\n<p>&nbsp;</p>\', \'letter\', 1, 2),
(25, \'order_is_road\', \'Оповещение: Деталь выкуплена и находится в пути к нам на склад\', \'<table width="100%" cellspacing="1" cellpadding="1" border="0">\n    <tbody>\n        <tr>\n            <td><a href="http://www.altinet.ru"><img width="224" height="88" border="0" src="http://altinet.mstarproject.com/image/design/es_top_left.jpg" alt="" /></a></td>\n            <td style="text-align: right;">По всем вопросам обращайтесь: г.Москва, Верейская ул, д.32, стр.32А, <br />\n            тел./факс: (495) 662-47-12<br />\n            e-mail: <a target="_blank" href="mailto:info@altinet.ru">info@altinet.ru</a></td>\n        </tr>\n    </tbody>\n</table>\n<p><strong>Уважаемый(-ая)</strong> {$info.name}</p>\n<p>Ваша деталь № {$aCart.id} выкуплена и скоро будет доставлена на наш склад.</p>\n<p>&nbsp;</p>\n<p>\n<table cellspacing="&quot;0&quot;" cellpadding="&quot;1&quot;" border="&quot;1&quot;" border-color="&quot;blue&quot;">\n    <tbody>\n        <tr>\n            <td>&nbsp;№&nbsp;Заказа&nbsp;</td>\n            <td>&nbsp;Марка&nbsp;</td>\n            <td>&nbsp;Наименование&nbsp;</td>\n            <td>&nbsp;№&nbsp;по&nbsp;Каталогу&nbsp;</td>\n            <td>&nbsp;Кол-во&nbsp;</td>\n            <td>&nbsp;Цена&nbsp;</td>\n            <td>&nbsp;Состояние&nbsp;</td>\n            <td>&nbsp;Дата&nbsp;</td>\n        </tr>\n        <tr>\n            <td>&nbsp;{$aCart.id_cart_package}&nbsp;</td>\n            <td>&nbsp;{$aCart.cat_name}&nbsp;</td>\n            <td>\n            <p>{$aCart.name} {$aCart.name_translate}</p>\n            </td>\n            <td>&nbsp;{$aCart.code}&nbsp;</td>\n            <td>&nbsp;{$aCart.number}&nbsp;</td>\n            <td>&nbsp;{$aCart.price}&nbsp;</td>\n            <td>&nbsp;В работе&nbsp;</td>\n            <td>&nbsp;{$aCart.date}&nbsp;</td>\n        </tr>\n    </tbody>\n</table>\n</p>\n<p>Ваш менеджер на сайте: <strong>{$aManager.name}</strong>. Все текущие вопросы по работе на сайте задавайте ему через систему внутренних <a href="../.././?action=message"><strong>сообщений</strong></a> (<strong>ник: {$aManager.login}</strong>): либо по телефону <strong>{$aManager.phone}</strong>.</p>\n<p>&nbsp;</p>\n<p>Настроить систему почтовых оповещений вы можете на странице &laquo;<a href="../.././?action=customer_notification"><strong>Оповещения</strong></a>&raquo;. Используйте Ваш логин и пароль для доступа к этой странице.</p>\n<p>Вы можете ответить на это письмо, если у Вас возникли вопросы. При ответе просьба цитировать всю переписку.</p>\n<p>&nbsp;</p>\', \'letter\', 1, 3),
(26, \'change_price\', \'Оповещение: Уведомление об изменение цены запчасти\', \'<table width="100%" cellspacing="1" cellpadding="1" border="0">\n    <tbody>\n        <tr>\n            <td><a href="http://www.altinet.ru"><img width="224" height="88" border="0" alt="" src="http://altinet.mstarproject.com/image/design/es_top_left.jpg" /></a></td>\n            <td style="text-align: right;">По всем вопросам обращайтесь: г.Москва, Верейская ул, д.32, стр.32А, <br />\n            тел./факс: (495) 662-47-12<br />\n            e-mail: <a href="mailto:info@altinet.ru" target="_blank">info@altinet.ru</a></td>\n        </tr>\n    </tbody>\n</table>\n<p><strong>Уважаемый(-ая)</strong> {$info.name}</p>\n<p>В вашем заказе ID {$aCart.id_cart_package} была изменена цена запчасти {$aCart.code} {$aCart.cat_name}. Цена изменена с {$aCart.price} на {$aCart.new_price}.</p>\n<p>&nbsp;</p>\n<p>\n<table cellspacing="&quot;0&quot;" cellpadding="&quot;1&quot;" border="&quot;1&quot;" border-color="&quot;blue&quot;">\n    <tbody>\n        <tr>\n            <td>&nbsp;№&nbsp;Заказа&nbsp;</td>\n            <td>&nbsp;Марка&nbsp;</td>\n            <td>&nbsp;Наименование&nbsp;</td>\n            <td>&nbsp;№&nbsp;по&nbsp;Каталогу&nbsp;</td>\n            <td>&nbsp;Кол-во&nbsp;</td>\n            <td>&nbsp;Цена&nbsp;</td>\n            <td>&nbsp;Состояние&nbsp;</td>\n            <td>&nbsp;Дата&nbsp;</td>\n        </tr>\n        <tr>\n            <td>&nbsp;{$aCart.id_cart_package}&nbsp;</td>\n            <td>&nbsp;{$aCart.cat_name}&nbsp;</td>\n            <td>\n            <p>{$aCart.name} {$aCart.name_translate}</p>\n            </td>\n            <td>&nbsp;{$aCart.code}&nbsp;</td>\n            <td>&nbsp;{$aCart.number}&nbsp;</td>\n            <td>&nbsp;<strike>{$aCart.price}</strike>&nbsp;<br />\n            &nbsp;<font color="#ff0000">{$aCart.new_price}&nbsp;</font></td>\n            <td>&nbsp;В работе&nbsp;</td>\n            <td>&nbsp;{$aCart.date}&nbsp;</td>\n        </tr>\n    </tbody>\n</table>\n</p>\n<p>Ваш менеджер на сайте: <strong>{$aManager.name}</strong>. Все текущие вопросы по работе на сайте задавайте ему через систему внутренних <a href="../.././?action=message"><strong>сообщений</strong></a> (<strong>ник: {$aManager.login}</strong>): либо по телефону <strong>{$aManager.phone}</strong>.</p>\n<p>&nbsp;</p>\n<p>Настроить систему почтовых оповещений вы можете на странице &laquo;<a href="../.././?action=customer_notification"><strong>Оповещения</strong></a>&raquo;. Используйте Ваш логин и пароль для доступа к этой странице.</p>\n<p>Вы можете ответить на это письмо, если у Вас возникли вопросы. При ответе просьба цитировать всю переписку.</p>\n<p>&nbsp;</p>\', \'letter\', 1, 3),
(27, \'change_code\', \'Оповещение: Уведомление об изменении кода запчасти\', \'<table width="100%" cellspacing="1" cellpadding="1" border="0">\n    <tbody>\n        <tr>\n            <td><a href="http://www.altinet.ru"><img width="224" height="88" border="0" alt="" src="http://altinet.mstarproject.com/image/design/es_top_left.jpg" /></a></td>\n            <td style="text-align: right;">По всем вопросам обращайтесь: г.Москва, Верейская ул, д.32, стр.32А, <br />\n            тел./факс: (495) 662-47-12<br />\n            e-mail: <a href="mailto:info@altinet.ru" target="_blank">info@altinet.ru</a></td>\n        </tr>\n    </tbody>\n</table>\n<p><strong>Уважаемый(-ая)</strong> {$info.name}</p>\n<p>В вашем заказе ID {$aCart.id_cart_package} была изменен код запчасти {$aCart.code} {$aCart.cat_name}. Новый код запчасти {$aCart.code_changed}.</p>\n<p>&nbsp;</p>\n<p>\n<table cellspacing="&quot;0&quot;" cellpadding="&quot;1&quot;" border="&quot;1&quot;" border-color="&quot;blue&quot;">\n    <tbody>\n        <tr>\n            <td>&nbsp;№&nbsp;Заказа&nbsp;</td>\n            <td>&nbsp;Марка&nbsp;</td>\n            <td>&nbsp;Наименование&nbsp;</td>\n            <td>&nbsp;№&nbsp;по&nbsp;Каталогу&nbsp;</td>\n            <td>&nbsp;Кол-во&nbsp;</td>\n            <td>&nbsp;Цена&nbsp;</td>\n            <td>&nbsp;Состояние&nbsp;</td>\n            <td>&nbsp;Дата&nbsp;</td>\n        </tr>\n        <tr>\n            <td>&nbsp;{$aCart.id_cart_package}&nbsp;</td>\n            <td>&nbsp;{$aCart.cat_name}&nbsp;</td>\n            <td>\n            <p>{$aCart.name} {$aCart.name_translate}</p>\n            </td>\n            <td>&nbsp;<strike>{$aCart.code}</strike>&nbsp;<br />\n            &nbsp;<font color="#0000ff">{$aCart.code_changed}&nbsp;</font></td>\n            <td>&nbsp;{$aCart.number}&nbsp;</td>\n            <td>&nbsp;{$aCart.price}&nbsp;</td>\n            <td>&nbsp;В работе&nbsp;</td>\n            <td>&nbsp;{$aCart.date}&nbsp;</td>\n        </tr>\n    </tbody>\n</table>\n</p>\n<p>Ваш менеджер на сайте: <strong>{$aManager.name}</strong>. Все текущие вопросы по работе на сайте задавайте ему через систему внутренних <a href="../.././?action=message"><strong>сообщений</strong></a> (<strong>ник: {$aManager.login}</strong>): либо по телефону <strong>{$aManager.phone}</strong>.</p>\n<p>&nbsp;</p>\n<p>Настроить систему почтовых оповещений вы можете на странице &laquo;<a href="../.././?action=customer_notification"><strong>Оповещения</strong></a>&raquo;. Используйте Ваш логин и пароль для доступа к этой странице.</p>\n<p>Вы можете ответить на это письмо, если у Вас возникли вопросы. При ответе просьба цитировать всю переписку.</p>\n<p>&nbsp;</p>\', \'letter\', 1, 3),
(28, \'change_quantity\', \'Оповещение: Изменение количества запчастей\', \'<table width="100%" cellspacing="1" cellpadding="1" border="0">\n    <tbody>\n        <tr>\n            <td><a href="http://www.altinet.ru"><img width="224" height="88" border="0" src="http://altinet.mstarproject.com/image/design/es_top_left.jpg" alt="" /></a></td>\n            <td style="text-align: right;">По всем вопросам обращайтесь: г.Москва, Верейская ул, д.32, стр.32А, <br />\n            тел./факс: (495) 662-47-12<br />\n            e-mail: <a target="_blank" href="mailto:info@altinet.ru">info@altinet.ru</a></td>\n        </tr>\n    </tbody>\n</table>\n<p><strong>Уважаемый(-ая)</strong> {$info.name}</p>\n<p>В вашем заказе ID {$aCart.id_cart_package} было изменено колличество запчастей {$aCart.code} {$aCart.cat_name}. Вами было заказано {$aCart.number} запчастей, от поставщика пришло наличие только {$aCart.new_num} запчастей.</p>\n<p>&nbsp;</p>\n<p>\n<table cellspacing="&quot;0&quot;" cellpadding="&quot;1&quot;" border="&quot;1&quot;" border-color="&quot;blue&quot;">\n    <tbody>\n        <tr>\n            <td>&nbsp;№&nbsp;Заказа&nbsp;</td>\n            <td>&nbsp;Марка&nbsp;</td>\n            <td>&nbsp;Наименование&nbsp;</td>\n            <td>&nbsp;№&nbsp;по&nbsp;Каталогу&nbsp;</td>\n            <td>&nbsp;Кол-во&nbsp;</td>\n            <td>&nbsp;Цена&nbsp;</td>\n            <td>&nbsp;Состояние&nbsp;</td>\n            <td>&nbsp;Дата&nbsp;</td>\n        </tr>\n        <tr>\n            <td>&nbsp;{$aCart.id_cart_package}&nbsp;</td>\n            <td>&nbsp;{$aCart.cat_name}&nbsp;</td>\n            <td>\n            <p>{$aCart.name} {$aCart.name_translate}</p>\n            </td>\n            <td>&nbsp;{$aCart.code}&nbsp;</td>\n            <td>&nbsp;<strike>{$aCart.number}</strike>&nbsp;<br />\n            <font color="#ff0000">&nbsp;{$aCart.new_num}&nbsp;</font></td>\n            <td>&nbsp;{$aCart.price}&nbsp;</td>\n            <td>&nbsp;В работе&nbsp;</td>\n            <td>&nbsp;{$aCart.date}&nbsp;</td>\n        </tr>\n    </tbody>\n</table>\n</p>\n<p>Ваш менеджер на сайте: <strong>{$aManager.name}</strong>. Все текущие вопросы по работе на сайте задавайте ему через систему внутренних <a href="../.././?action=message"><strong>сообщений</strong></a> (<strong>ник: {$aManager.login}</strong>): либо по телефону <strong>{$aManager.phone}</strong>.</p>\n<p>&nbsp;</p>\n<p>Настроить систему почтовых оповещений вы можете на странице &laquo;<a href="../.././?action=customer_notification"><strong>Оповещения</strong></a>&raquo;. Используйте Ваш логин и пароль для доступа к этой странице.</p>\n<p>Вы можете ответить на это письмо, если у Вас возникли вопросы. При ответе просьба цитировать всю переписку.</p>\n<p>&nbsp;</p>\', \'letter\', 1, 3),
(29, \'order_is_end\', \'Оповещение: Деталь выдана со склада\', \'<table width="100%" cellspacing="1" cellpadding="1" border="0">\n    <tbody>\n        <tr>\n            <td><a href="http://www.altinet.ru"><img width="224" height="88" border="0" alt="" src="http://altinet.mstarproject.com/image/design/es_top_left.jpg" /></a></td>\n            <td style="text-align: right;">По всем вопросам обращайтесь: г.Москва, Верейская ул, д.32, стр.32А, <br />\n            тел./факс: (495) 662-47-12<br />\n            e-mail: <a href="mailto:info@altinet.ru" target="_blank">info@altinet.ru</a></td>\n        </tr>\n    </tbody>\n</table>\n<p><strong>Уважаемый(-ая)</strong> {$info.name}</p>\n<p>Ваша заказаная деталь&nbsp;№ {$aCart.id} выдана.</p>\n<p>\n<table cellspacing="&quot;0&quot;" cellpadding="&quot;1&quot;" border="&quot;1&quot;" border-color="&quot;blue&quot;">\n    <tbody>\n        <tr>\n            <td>&nbsp;№&nbsp;Заказа&nbsp;</td>\n            <td>&nbsp;Марка&nbsp;</td>\n            <td>&nbsp;Наименование&nbsp;</td>\n            <td>&nbsp;№&nbsp;по&nbsp;Каталогу&nbsp;</td>\n            <td>&nbsp;Количество&nbsp;</td>\n            <td>&nbsp;Цена&nbsp;</td>\n            <td>&nbsp;Состояние&nbsp;</td>\n            <td>&nbsp;Дата&nbsp;</td>\n        </tr>\n        <tr>\n            <td>&nbsp;{$aCart.id_cart_package}&nbsp;</td>\n            <td>&nbsp;{$aCart.cat_name}&nbsp;</td>\n            <td>\n            <p>{$aCart.name} {$aCart.name_translate}</p>\n            </td>\n            <td>&nbsp;{$aCart.code}&nbsp;</td>\n            <td>&nbsp;{$aCart.number}&nbsp;</td>\n            <td>&nbsp;{$aCart.price}&nbsp;</td>\n            <td>&nbsp;Выдано&nbsp;</td>\n            <td>&nbsp;{$aCart.date}&nbsp;</td>\n        </tr>\n    </tbody>\n</table>\n</p>\n<p>Ваш менеджер на сайте: <strong>{$aManager.name}</strong>. Все текущие вопросы по работе на сайте задавайте ему через систему внутренних <a href="../.././?action=message"><strong>сообщений</strong></a> (<strong>ник: {$aManager.login}</strong>): либо по телефону <strong>{$aManager.phone}</strong>.</p>\n<p>&nbsp;</p>\n<p>Настроить систему почтовых оповещений вы можете на странице &laquo;<a href="../.././?action=customer_notification"><strong>Оповещения</strong></a>&raquo;. Используйте Ваш логин и пароль для доступа к этой странице.</p>\n<p>Вы можете ответить на это письмо, если у Вас возникли вопросы. При ответе просьба цитировать всю переписку.</p>\n<p>&nbsp;</p>\', \'letter\', 0, 3);
',
'CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_` enum(\'customer\',\'provider\',\'manager\') NOT NULL DEFAULT \'customer\',
  `post` int(11) NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `receive_notification` int(11) NOT NULL DEFAULT \'1\',
  `login` varchar(15) DEFAULT NULL,
  `password` varchar(100) NOT NULL DEFAULT \'\',
  `email` varchar(50) NOT NULL DEFAULT \'\',
  `visible` int(11) NOT NULL DEFAULT \'0\',
  `has_forum` int(11) NOT NULL DEFAULT \'0\',
  `approved` int(11) NOT NULL DEFAULT \'0\',
  `last_visit_date` timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\',
  `signature` varchar(50) NOT NULL DEFAULT \'\',
  `session` varchar(50) DEFAULT NULL,
  `cookie` varchar(50) NOT NULL,
  `copy_message` int(11) NOT NULL DEFAULT \'1\',
  `ip` varchar(20) NOT NULL,
  `id_language` int(11) NOT NULL DEFAULT \'1\',
  `is_test` int(11) NOT NULL DEFAULT \'0\',
  `notification_type` enum(\'single\',\'bulk\') NOT NULL DEFAULT \'single\',
  `is_last_visit_notified` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  KEY `password` (`password`),
  KEY `type_` (`type_`),
  KEY `email` (`email`),
  KEY `visible` (`visible`),
  KEY `approved` (`approved`),
  KEY `signature` (`signature`),
  KEY `session` (`session`),
  KEY `cookie` (`cookie`),
  KEY `id_language` (`id_language`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=255 ;
',
'
INSERT INTO `user` (`id`, `type_`, `post`, `post_date`, `receive_notification`, `login`, `password`, `email`, `visible`, `has_forum`, `approved`, `last_visit_date`, `signature`, `session`, `cookie`, `copy_message`, `ip`, `id_language`, `is_test`, `notification_type`, `is_last_visit_notified`) VALUES
(2, \'customer\', 0, \'2009-11-21 00:38:31\', 1, \'mstar\', \'09031975\', \'mstarrr@gmail.com\', 1, 0, 0, \'2010-11-29 11:17:03\', \'\', \'5bfaa8f87120e743a445d55bd29928bb\', \'beb8a2fc1946d27fcc6ce97b0ec8d3f4\', 1, \'\', 1, 0, \'single\', 0),
(252, \'manager\', 0, \'2010-08-17 12:16:21\', 1, \'manager\', \'manager02\', \'mstarrr@gmail.com\', 1, 0, 1, \'2010-09-30 10:12:54\', \'\', \'530d203451e09c916ea47fcc4f4ef25e\', \'8dbf1e54b5804d4966df8c9463e073ed\', 1, \'\', 1, 0, \'single\', 0),
(253, \'provider\', 0, \'2010-11-29 08:42:45\', 1, \'provider_test\', \'12345\', \'\', 1, 0, 0, \'0000-00-00 00:00:00\', \'\', NULL, \'\', 1, \'\', 1, 0, \'single\', 0),
(254, \'customer\', 0, \'2010-11-29 11:17:03\', 1, \'mstar2\', \'09031975\', \'ms.ta.rrr@gmail.com\', 1, 0, 0, \'2010-11-29 11:17:03\', \'97b0e3dbe54e61ee742c4e01286f6bf5\', NULL, \'\', 1, \'193.37.156.146\', 0, 0, \'single\', 0);
',
'
CREATE TABLE IF NOT EXISTS `user_account` (
  `id_user` int(11) NOT NULL,
  `amount` double(10,2) NOT NULL,
  UNIQUE KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
,
'INSERT INTO `user_account` (`id_user`, `amount`) VALUES
(2, 35882.60),
(253, 0.00),
(254, 0.00);
',
'CREATE TABLE IF NOT EXISTS `user_customer` (
  `id_user` int(11) NOT NULL,
  `id_manager` int(11) NOT NULL,
  `id_manager_partner` int(11) NOT NULL,
  `id_user_referer` int(11) NOT NULL,
  `id_user_referer_old` int(11) NOT NULL,
  `id_referer_manager` int(11) NOT NULL,
  `id_parent` int(11) NOT NULL,
  `id_customer_group` int(11) NOT NULL DEFAULT \'1\',
  `finance_type` enum(\'fiz\',\'nds\',\'beznds\') NOT NULL DEFAULT \'fiz\',
  `name` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `phone2` varchar(50) DEFAULT NULL,
  `phone3` varchar(50) DEFAULT NULL,
  `icq` varchar(50) DEFAULT NULL,
  `skype` varchar(50) DEFAULT NULL,
  `remark` text,
  `code_currency` varchar(5) DEFAULT \'RUB\',
  `discount_static` int(11) DEFAULT NULL,
  `discount_dynamic` int(11) DEFAULT NULL,
  `user_debt` double(10,2) DEFAULT NULL,
  `profile_notified` int(11) DEFAULT NULL,
  `vip` int(11) DEFAULT NULL,
  `parent_default_margin` int(11) DEFAULT \'10\',
  `parent_margin` int(11) DEFAULT NULL,
  `is_locked` int(11) DEFAULT NULL,
  `vip_remark` text,
  `login_parent` varchar(15) DEFAULT NULL,
  `manager_comment` varchar(255) DEFAULT NULL,
  `additional_field1` varchar(255) DEFAULT NULL,
  `additional_field2` varchar(255) DEFAULT NULL,
  `additional_field3` varchar(255) DEFAULT NULL,
  `additional_field4` varchar(255) DEFAULT NULL,
  `additional_field5` varchar(255) DEFAULT NULL,
  `address2` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  KEY `name` (`name`),
  KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
,
'
INSERT INTO `user_customer` (`id_user`, `id_manager`, `id_manager_partner`, `id_user_referer`, `id_user_referer_old`, `id_referer_manager`, `id_parent`, `id_customer_group`,  `finance_type`, `name`, `country`, `state`, `city`, `zip`, `company`, `address`, `phone`, `phone2`, `phone3`, `icq`, `skype`, `remark`, `code_currency`, `discount_static`, `discount_dynamic`, `user_debt`, `profile_notified`, `vip`, `vip`, `parent_default_margin`, `parent_margin`, `is_locked`, `vip_remark`, `login_parent`, `manager_comment`, `additional_field1`, `additional_field2`, `additional_field3`, `additional_field4`, `additional_field5`, `address2`) VALUES
(2, 252, 0, 0, 0, 0, 0, 1,  \'fiz\', \'Старовойт Михаил\', \'123\', \'\', \'Чернигов\', \'14033\', NULL, \'ул. Красногвардейская 25б, кв. 83\', \'0504652966\', \'\', \'\', \'\', \'\', \'комментарий\', \'RUB\', 0, 3, 0.00, 0, -1, 0, 10, 0, 0, \'\', \'\', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(254, 252, 0, 0, 0, 0, 0, 1, \'fiz\', \'Старовойт Михаил\', NULL, NULL, \'Чернигов\', NULL, NULL, \'ул. Красногвардейская 25б, кв. 83 2\', \'+38050-465-29-66\', NULL, NULL, NULL, NULL, \'\', \'RUB\', NULL, NULL, NULL, NULL, NULL, NULL, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
',
'CREATE TABLE IF NOT EXISTS `user_manager` (
  `id_user` int(11) NOT NULL,
  `id_customer_partner` int(11) NOT NULL,
  `id_vin_request_fixed` int(11) NOT NULL,
  `id_office` int(11) NOT NULL DEFAULT \'2\',
  `name` varchar(50) DEFAULT NULL,
  `country` varchar(50) NOT NULL DEFAULT \'\',
  `state` varchar(50) NOT NULL DEFAULT \'\',
  `city` varchar(50) NOT NULL DEFAULT \'\',
  `zip` varchar(10) NOT NULL DEFAULT \'\',
  `company` varchar(100) NOT NULL DEFAULT \'\',
  `address` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `phone2` varchar(50) NOT NULL DEFAULT \'\',
  `phone3` varchar(50) NOT NULL DEFAULT \'\',
  `icq` varchar(50) NOT NULL DEFAULT \'\',
  `skype` varchar(50) NOT NULL DEFAULT \'\',
  `remark` text,
  `image` varchar(255) NOT NULL,
  `has_customer` int(11) NOT NULL DEFAULT \'1\',
  `is_super_manager` int(11) NOT NULL,
  `is_sub_manager` int(11) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
',
'
INSERT INTO `user_manager` (`id_user`, `id_customer_partner`, `id_vin_request_fixed`, `id_office`, `name`, `country`, `state`, `city`, `zip`, `company`, `address`, `phone`, `phone2`, `phone3`, `icq`, `skype`, `remark`, `image`, `has_customer`, `is_super_manager`, `is_sub_manager`) VALUES
(252, 0, 0, 2, \'Михаил Старовойт\', \'\', \'\', \'\', \'\', \'\', \'\', \'+380504652966\', \'\', \'\', \'\', \'\', \'\', \'\', 1, 1, 1);
',
'CREATE TABLE IF NOT EXISTS `user_provider` (
  `id_user` int(11) NOT NULL,
  `id_provider_group` int(11) NOT NULL DEFAULT \'1\',
  `id_provider_region` int(11) NOT NULL DEFAULT \'1\',
  `id_currency` int(11) NOT NULL DEFAULT \'1\',
  `name` varchar(50) NOT NULL,
  `description` longtext,
  `code_name` varchar(50) DEFAULT NULL,
  `code_delivery` varchar(10) DEFAULT NULL,
  `country` varchar(50) DEFAULT \'\',
  `state` varchar(50) DEFAULT \'\',
  `city` varchar(50) DEFAULT \'\',
  `zip` varchar(10) DEFAULT \'\',
  `company` varchar(100) DEFAULT \'\',
  `address` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `phone2` varchar(50) DEFAULT \'\',
  `phone3` varchar(50) DEFAULT \'\',
  `icq` varchar(50) DEFAULT \'\',
  `skype` varchar(50) DEFAULT \'\',
  `remark` text,
  `code_currency` varchar(5) DEFAULT NULL,
  `statistic_visible` int(11) DEFAULT NULL,
  `statistic_manual` int(11) DEFAULT NULL,
  `is_auction` int(11) DEFAULT \'0\',
  `is_our_store` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
',
'
INSERT INTO `user_provider` (`id_user`, `id_provider_group`, `id_provider_region`, `id_currency`, `name`, `description`, `code_name`, `code_delivery`, `country`, `state`, `city`, `zip`, `company`, `address`, `phone`, `phone2`, `phone3`, `icq`, `skype`, `remark`, `code_currency`, `statistic_visible`, `statistic_manual`, `is_auction`, `is_our_store`) VALUES
(253, 1, 1, 2, \'provider_test\', \'\', \'provider_test_code\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', \'\', 0, 0, 0, 0);
',
)
;

?>