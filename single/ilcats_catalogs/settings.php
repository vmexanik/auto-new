<?
define ("apiClientId",15014); // Ваш ID клиента на сайте https://my.neoriginal.ru
define ("apiKey", "ddec345781341fdbb27ffb17d5a82b0b"); //Ваш API ключ 
define ("apiDomain" , "irbisredesign.mstarproject.com"); //Название Вашего домена, для которого сгенерирован API ключ
define ("apiStaticContentHost","//static.ilcats.ru"); //Название домена со статическим контентом (на данный момент используются только изображения с isStaticImage = 1
define ("apiImagesHost","//images.ilcats.ru"); //Название домена с изображениями
define ("apiVersion","2.0"); //Версия АПИ
define ("apiArticlePartLink","http://irbisredesign.mstarproject.com/price/<%API_URL_PART_NUMBER%>"); //Шаблон ссылки на поисковую форму Вашего сайта

define ("partInfo",1); //Флаг выдачи АПИ дополнительной информации о запчасти в методе getParts
// 1: Выдавать всю доступную информацию (изображение, применяемость и аналоги)
// 2: Применяемость и изображение
// 3: Применяемость и аналоги
// 4: Только применяемость
// Другие значения (в т.ч. пустое): не выдавать ничего

define ("clientIpAddress",'88.99.167.208'); // ip адрес клиента Вашего сайта. HTTP_X_REAL_IP нужно заменить на название название соответствующего параметра Вашего сайта.

?>