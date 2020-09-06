source price_group
{ldelim}
	type = mysql

	sql_host = {$aDbConf.Host}
	sql_user = {$aDbConf.User}
	sql_pass = {$aDbConf.Password}
	sql_db = {$aDbConf.Database}
	sql_query_pre = SET NAMES utf8
	sql_query_pre = SET CHARACTER SET utf8

	sql_query = \
		select p.id \
		, p.code as code \
		, c.title as brand \
		, if(ifnull(cp.name_rus,'')<>'', cp.name_rus, ifnull(p.part_rus,'')) as part_name \
		, pgr.name as price_group_name \
		, p.id_price_group as id_price_group \
	 from price as p \
		 left join cat_part as cp on cp.item_code=p.item_code \
		 inner join cat as c on p.pref=c.pref \
		 inner join provider_virtual as pv on p.id_provider=pv.id_provider \
		 inner join user_provider as up on pv.id_provider_virtual=up.id_user \
		 inner join provider_group as pg on up.id_provider_group=pg.id \
		 inner join user as u on up.id_user=u.id and u.visible=1 \
		 inner join currency as cu on up.id_currency=cu.id \
		 inner join price_group as pgr on pgr.id=p.id_price_group \
		 where 1=1

	sql_attr_uint = id_price_group

	sql_query_info = SELECT * FROM price WHERE id=$id
{rdelim}


index price_group
{ldelim}
	source = price_group
	path = {$sDataFilePath}price_group/index
	morphology = stem_ru
	min_word_len = 3
	charset_type = utf-8

	min_infix_len = 3
	#min_prefix_len = 3
	enable_star = 1
{rdelim}