<?

/**
 * @author Mikhail Starovoyt
 *
 */
class ATranslateMessage extends Admin
{

	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName = 'translate_message';
		$this->sTablePrefix = 't';
		$this->sAction = 'translate_message';
		$this->sWinHead = Language::getDMessage('Message Translate');
		$this->sPath = Language::GetDMessage('>>Content >');
		$this->aCheckField=array('code');
		$this->sSqlPath='CoreTranslateMessage';
		$this->Admin ();

		$this->sAdminRegulationsUrl = 'http://irbis.mstarproject.com';
		if (Base::$aGeneralConf['AdminRegulationsUrl'])
			$this->sAdminRegulationsUrl = Base::$aGeneralConf['AdminRegulationsUrl'];
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex ();
		$this->initLocaleGlobal();

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong',0)) {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and t.id = '".$this->aSearch['id']."'";
                if ($this->aSearch['code'])
                    $this->sSearchSQL .= " and t.code = '".$this->aSearch['code']."'";
                if ($this->aSearch['content'])
                    $this->sSearchSQL .= " and t.content = '".$this->aSearch['content']."'";
            }
            else {
                if ($this->aSearch['id'])$this->sSearchSQL .= " and t.id like '%".$this->aSearch['id']."%'";
                if ($this->aSearch['code'])	
                    $this->sSearchSQL .= " and t.code like '%".$this->aSearch['code']."%'";
                if ($this->aSearch['content'])	
                    $this->sSearchSQL .= " and t.content like '%".$this->aSearch['content']."%'";
            }
            if ($this->aSearch['date_from'])
                $this->sSearchSQL .= " and UNIX_TIMESTAMP(t.post_date)>='".strtotime($this->aSearch['date_from'])."'";
            if ($this->aSearch['date_to'])
                $this->sSearchSQL .= " and UNIX_TIMESTAMP(t.post_date)<='".strtotime($this->aSearch['date_to'])."'";
        }

		$oTable = new Table();
		$oTable->aColumn = array (
		'id'=>array('sTitle' => 'Id', 'sOrder' => 't.id'),
		'code'=>array('sTitle' => 'Code', 'sOrder' => 't.code'),
		'content'=>array('sTitle' => 'Content', 'sOrder' => 't.content'),
		'post_date'=>array('sTitle' => 'Post date', 'sOrder' => 't.post_date'),
		'language'=>array('sTitle' => 'Lang'),
		'action'=>array (),
		);
		$this->SetDefaultTable ( $oTable );
		Base::$sText.=$oTable->getTable ();
		$this->AfterIndex ();
		
		if (Base::$aRequest['aMessage'])
			Admin::Message(Base::$aRequest['aMessage']['type'],Base::$aRequest['aMessage']['message']);
	}
	//-----------------------------------------------------------------------------------------------
	public function SendIrbis() {
		if (Base::$aRequest['id']) {
			$aRow = Db::GetRow("Select * from translate_message where id=".Base::$aRequest['id']);
			if ($aRow) {
				if ($aRow['code'] == $aRow['content'])
					Admin::Message('MT_ERROR',Language::getMessage('SendIrbisError:key_not_fill'));
				else {
					$sUrl = $this->sAdminRegulationsUrl . '/pages/admin_regulations_insert_irbis/';
					$ch=curl_init();
					curl_setopt($ch,CURLOPT_URL,$sUrl);
					curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
					curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
					curl_setopt($ch,CURLOPT_TIMEOUT,3);
					curl_setopt($ch,CURLOPT_POST,1);
					curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query(array(
							'type'=>'message',
							'data'=>base64_encode(json_encode($aRow)),
					)));
					$sContent=curl_exec($ch);
					$iErrCode=curl_errno($ch);
					if ($iErrCode == 0 && $sContent)
						Admin::Message('MT_NOTICE',$sContent);
					else 
						Admin::Message('MT_ERROR',Language::getMessage('SendIrbisError:code') .' '. $iErrCode);
				}
			}
			else
				Admin::Message('MT_ERROR',Language::getMessage('SendIrbisError:NotData'));
		}
		else
			Admin::Message('MT_ERROR',Language::getMessage('SendIrbisError:NotData'));
	}
	//-----------------------------------------------------------------------------------------------
	public function GetFromIrbis() {
		if (Base::$aRequest['id']) {
			$aRow = Db::GetRow("Select * from translate_message where id=".Base::$aRequest['id']);
			if ($aRow) {
				$sUrl = $this->sAdminRegulationsUrl . '/pages/admin_regulations_get_from_irbis/';
				$ch=curl_init();
				curl_setopt($ch,CURLOPT_URL,$sUrl);
				curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
				curl_setopt($ch,CURLOPT_TIMEOUT,3);
				curl_setopt($ch,CURLOPT_POST,1);
				curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query(array(
				'type'=>'message',
				'data'=>base64_encode(json_encode($aRow)),
				)));
				$sContent=curl_exec($ch);
				$iErrCode=curl_errno($ch);
				if ($iErrCode == 0 && $sContent) {
					$aData = json_decode(base64_decode($sContent),true);
					if (!is_array($aData) || !$aData['content'] || !isset($aData['page'])) 
						Admin::Message('MT_ERROR',$sContent);
					else {
						Db::Execute("Update translate_message set
							content = '".mysql_real_escape_string($aData['content'])."',
							page = '".mysql_real_escape_string($aData['page'])."',
							post_date = '".date("Y-m-d H:i:s")."'
							where id = '".$aRow['id']."'");

						$aMessage = array ('type' => 'MT_NOTICE', 'message' => Language::getMessage('GetFromIrbisOk:key_update'));
						$this->AdminRedirect ( $this->sAction, $aMessage);
					}
				}
				else
					Admin::Message('MT_ERROR',Language::getMessage('GetFromIrbisError:code') .' '. $iErrCode);
			}
			else 
				Admin::Message('MT_ERROR',Language::getMessage('GetFromIrbisError:not_set_data'));
		}
		else
			Admin::Message('MT_ERROR',Language::getMessage('GetFromIrbisError:not_set_data'));
	}
	//------------------------------------------------------------------------------------------------------
	public function BeforeApply()
	{
		/*if (Base::$aRequest['data']['content'] && Base::$aRequest['data']['id'])
			Db::Execute("update locale_global set content='".Base::$aRequest['data']['content']."' where id_reference='".Base::$aRequest['data']['id']."'");
		if (Base::$aRequest['data']['content'] && Base::$aRequest['data']['id']){
		    Db::Execute("update locale_global set content='".mysql_real_escape_string(Base::$aRequest['data']['content'])."'
	             where id_reference='".Base::$aRequest['data']['id']."' and table_name='".$this->sTableName."' and locale='".Base::$aRequest['locale']."'");
		}*/
	}
}
?>