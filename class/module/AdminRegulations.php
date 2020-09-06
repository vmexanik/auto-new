<?
/**
 * @author Vladimir Fedorov
 */
class AdminRegulations extends Base
{
	var $sPrefix="admin_regulations";
	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
		if (Language::$sLocale=='en') {
			Db::Execute("SET @lng_id = 4");
		} else {
			Db::Execute("SET @lng_id = 16");
		}
		Db::Execute("SET @cou_id = 187");
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		Base:Redirect("/");
	}
	//-----------------------------------------------------------------------------------------------
	public function SinxroTranslate()
	{
		if (Base::$aGeneralConf['AdminRegulationsUrl'] != 'http://'.$_SERVER['HTTP_HOST'])
			Base::Redirect("/");
		
		$sData = json_encode(
			array(
				'constant' => Db::GetAll("Select * from constant where key_ != value"),
				'context_hint' => Db::GetAll("Select * from context_hint where key_ != content"),
				'template' => Db::GetAll("Select * from template where code != content"),
				'translate_message' => Db::GetAll("Select * from translate_message where code != content"),
				'translate_text' => Db::GetAll("Select * from translate_text where code != content")
			)
		);
		echo $sData;
		die();
	}
	//-----------------------------------------------------------------------------------------------
	public function InsertIrbis()
	{
		$sResult = 'InsertIrbisError:unknown_error';
		if (Base::$aRequest['type'] && Base::$aRequest['data']) {
			$aData = json_decode(base64_decode(Base::$aRequest['data']),true);
			if (!is_array($aData))
				$sResult = Language::getMessage('InsertIrbisError:not_found_type_or_data');
			else {
				switch (Base::$aRequest['type']) {
					case 'message':if(!$aData['code'])
										$sResult = Language::getMessage('InsertIrbisError:not_found_key_field');
									else {
										$aData['code'] = mysql_real_escape_string($aData['code']);
										$aDataIrbis = Db::GetRow("Select * from translate_message where code = '".$aData['code']."'");
										if ($aDataIrbis && $aDataIrbis['code'] != $aDataIrbis['content'])
											$sResult = Language::getMessage('InsertIrbisError:this_key_already_exist');
										elseif ($aDataIrbis && $aDataIrbis['code'] == $aDataIrbis['content']) {
											Db::Execute("Update translate_message set 
												content = '".mysql_real_escape_string($aData['content'])."', 
												page = '".mysql_real_escape_string($aData['page'])."', 
												post_date = '".date("Y-m-d H:i:s")."' 
												where code = '".$aData['code']."'");
											$sResult = Language::getMessage('InsertIrbisOk:key_update');
										}
										else {
											Db::Execute("insert ignore into translate_message (code, content, page, post_date) values 
													('".$aData['code']."','".mysql_real_escape_string($aData['content'])."','".
												mysql_real_escape_string($aData['page'])."','".date("Y-m-d H:i:s")."')");
																												
											$sResult = Language::getMessage('InsertIrbisOk:key_inserted');
										}
									}						
									break;
					case 'text':if(!$aData['code'])
										$sResult = Language::getMessage('InsertIrbisError:not_found_key_field');
									else {
										$aData['code'] = mysql_real_escape_string($aData['code']);
										$aDataIrbis = Db::GetRow("Select * from translate_text where code = '".$aData['code']."'");
										if ($aDataIrbis && $aDataIrbis['code'] != $aDataIrbis['content'])
											$sResult = Language::getMessage('InsertIrbisError:this_key_already_exist');
										elseif ($aDataIrbis && $aDataIrbis['code'] == $aDataIrbis['content']) {
											Db::Execute("Update translate_text set
												content = '".mysql_real_escape_string($aData['content'])."',
												page = '".mysql_real_escape_string($aData['page'])."',
												post_date = '".date("Y-m-d H:i:s")."'
												where code = '".$aData['code']."'");
											$sResult = Language::getMessage('InsertIrbisOk:key_update');
										}
										else {
											Db::Execute("insert ignore into translate_text (code, content, page, post_date) values
													('".$aData['code']."','".mysql_real_escape_string($aData['content'])."','".
																						mysql_real_escape_string($aData['page'])."','".date("Y-m-d H:i:s")."')");
									
											$sResult = Language::getMessage('InsertIrbisOk:key_inserted');
										}
									}
									break;
					case 'template':if(!$aData['code'])
										$sResult = Language::getMessage('InsertIrbisError:not_found_key_field');
									else {
										$aData['code'] = mysql_real_escape_string($aData['code']);
										$aDataIrbis = Db::GetRow("Select * from template where code = '".$aData['code']."'");
										if ($aDataIrbis && $aDataIrbis['code'] != $aDataIrbis['content'])
											$sResult = Language::getMessage('InsertIrbisError:this_key_already_exist');
										elseif ($aDataIrbis && $aDataIrbis['code'] == $aDataIrbis['content']) {
											Db::Execute("Update template set
												content = '".mysql_real_escape_string($aData['content'])."',
												name = '".mysql_real_escape_string($aData['name'])."',
												type_ = '".$aData['type_']."',
												is_smarty = '".$aData['is_smarty']."',
												priority = '".$aData['priority']."',
												post_date = '".date("Y-m-d H:i:s")."'
												where code = '".$aData['code']."'");
											$sResult = Language::getMessage('InsertIrbisOk:key_update');
										}
										else {
											Db::Execute("insert ignore into template (
													code, content, name, type_, is_smarty, priority, post_date) values
													('".$aData['code']."','".mysql_real_escape_string($aData['content'])."','".
													mysql_real_escape_string($aData['name'])."','".$aData['type_']."','".
													$aData['is_smarty']."','".$aData['priority']."','".
													date("Y-m-d H:i:s")."')");
									
											$sResult = Language::getMessage('InsertIrbisOk:key_inserted');
										}
									}
									break;
				}
			}
		}
		else 
			$sResult = Language::getMessage('InsertIrbisError:not_found_type');
		
		echo $sResult;
		die();
	}
	//-----------------------------------------------------------------------------------------------
	public function GetFromIrbis()
	{
		$sResult = 'GetFromIrbisError:unknown_error';
		if (Base::$aRequest['type'] && Base::$aRequest['data']) {
			$aData = json_decode(base64_decode(Base::$aRequest['data']),true);
			if (!is_array($aData))
				$sResult = Language::getMessage('GetFromIrbisError:not_found_type_or_data');
			else {
				switch (Base::$aRequest['type']) {
					case 'message':if(!$aData['code'])
									$sResult = Language::getMessage('GetFromIrbisError:not_found_key_field');
									else {
										$aData['code'] = mysql_real_escape_string($aData['code']);
										$aDataIrbis = Db::GetRow("Select * from translate_message where code = '".$aData['code']."'");
										if ($aDataIrbis && $aDataIrbis['code'] == $aDataIrbis['content'])
											$sResult = Language::getMessage('GetFromIrbisError:this_key_not_fill');
										elseif ($aDataIrbis && $aDataIrbis['code'] != $aDataIrbis['content']) {
											$sResult = base64_encode(json_encode($aDataIrbis));
										}
										else 
											$sResult = Language::getMessage('GetFromIrbisError:not_found_key_field');
									}
									break;
					case 'text':if(!$aData['code'])
									$sResult = Language::getMessage('GetFromIrbisError:not_found_key_field');
									else {
										$aData['code'] = mysql_real_escape_string($aData['code']);
										$aDataIrbis = Db::GetRow("Select * from translate_text where code = '".$aData['code']."'");
										if ($aDataIrbis && $aDataIrbis['code'] == $aDataIrbis['content'])
											$sResult = Language::getMessage('GetFromIrbisError:this_key_not_fill');
										elseif ($aDataIrbis && $aDataIrbis['code'] != $aDataIrbis['content']) {
											$sResult = base64_encode(json_encode($aDataIrbis));
										}
										else
											$sResult = Language::getMessage('GetFromIrbisError:not_found_key_field');
									}
									break;
					case 'template':if(!$aData['code'])
										$sResult = Language::getMessage('GetFromIrbisError:not_found_key_field');
									else {
										$aData['code'] = mysql_real_escape_string($aData['code']);
										$aDataIrbis = Db::GetRow("Select * from template where code = '".$aData['code']."'");
										if ($aDataIrbis && $aDataIrbis['code'] == $aDataIrbis['content'])
											$sResult = Language::getMessage('GetFromIrbisError:this_key_not_fill');
										elseif ($aDataIrbis && $aDataIrbis['code'] != $aDataIrbis['content']) {
											$sResult = base64_encode(json_encode($aDataIrbis));
										}
										else
											$sResult = Language::getMessage('GetFromIrbisError:not_found_key_field');
									}
									break;
				}
			}
		}
		else
			$sResult = Language::getMessage('InsertIrbisError:not_found_type');
	
		echo $sResult;
		die();
	}
	
}
?>