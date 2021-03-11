<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class AGeneralConstant extends Admin {

	//-----------------------------------------------------------------------------------------------
	function AGeneralConstant() {
		$this->sTableName='constant';
		$this->sTablePrefix='c';
		$this->sAction='general_constant';
		$this->sWinHead=Language::getDMessage('General constants');
		$this->sPath=Language::GetDMessage('>>Configuration >');
		$this->aCheckField=array('key_','value');
		$this->sSqlPath='GeneralConstant';
		$this->sPathRobots = SERVER_PATH.'/imgbank/Image/seo/robots.txt';
				
		// check if text value
		if (Base::$aRequest['id'] == -1) {
			//$this->aFCKEditors = array ('value');
		}
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
//		Base::$sText .= Base::$tpl->fetch("mpanel/general_constant/_before_table.tpl");
		$this->PreIndex();

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong',0)) {
                if ($this->aSearch['key'])$this->sSearchSQL .= " and c.key = '".$this->aSearch['key']."'";
                if ($this->aSearch['value'])	$this->sSearchSQL .= " and c.value = '".$this->aSearch['value']."'";
                if ($this->aSearch['description'])	$this->sSearchSQL .= " and c.description = '".$this->aSearch['description']."'";
            }
            else {
                if ($this->aSearch['key'])$this->sSearchSQL .= " and c.key like '%".$this->aSearch['key']."%'";
                if ($this->aSearch['value'])	$this->sSearchSQL .= " and c.value like '%".$this->aSearch['value']."%'";
                if ($this->aSearch['description'])	$this->sSearchSQL .= " and c.description like '%".$this->aSearch['description']."%'";
            }
        }

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		
		$oTable->aColumn=array(
		/*'id'=>array('sTitle'=>'Id','sOrder'=>'c.id'),*/
		'key_'=>array('sTitle'=>'Key','sOrder'=>'c.key_'),
		'value'=>array('sTitle'=>'Value','sOrder'=>'c.value'),
		'description'=>array('sTitle'=>'Description','sOrder'=>'c.description','databreakpoints'=>"xs"),
		'action'=>array('databreakpoints'=>"xs"),
		);
		$oTable->aCallback=array($this,'CallParse');
		
		$this->SetDefaultTable($oTable);
		$oTable->bCheckVisible = false;
		Base::$sText.=$oTable->getTable();
		
		$this->AfterIndex();
		Base::$oResponse->AddScript("$('#admin_itemslist_table').footable({'expandFirst': false});");
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeApply() {
		if (Base::$aRequest['data']['type']=='checkbox') {
			if (Base::$aRequest['data']['new_value'])
				Base::$aRequest['data']['value'] = 1;
			else 
				Base::$aRequest['data']['value'] = 0;
		}
		elseif (Base::$aRequest['data']['type'] == 'favicon') {
			if (Base::$aRequest['data']['favicon'] != '')
				Base::$aRequest['data']['value'] = Base::$aRequest['data']['favicon'];
			elseif (Base::$aRequest['data']['value'] == '')
				Base::$aRequest['data']['value'] = '/favicon.ico';
		}
		elseif (Base::$aRequest['data']['type'] == 'logo') {
		    if (Base::$aRequest['data']['logo'] != '')
		        Base::$aRequest['data']['value'] = Base::$aRequest['data']['logo'];
		        elseif (Base::$aRequest['data']['value'] == '')
		        Base::$aRequest['data']['value'] = '/image/logo-top.png';
		}

		// added variables
		switch (Base::$aRequest['data']['id']) {
			case -1:
					$sKey = 'added_no_reply';
					//Base::$aRequest['data']['value'] = Base::$aRequest['data_value'];
					Language::SetText($sKey, Base::$aRequest['data']['value']);
					break;
			case -2:
					$sKey = 'Файл robots.txt';
					$this->SetContentFileRobots(Base::$aRequest['data']['value']);
					break;
			case -3:
					$sKey = 'site_counters';
					Language::SetText($sKey, Base::$aRequest['data']['value']);
					break;
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign(&$aData)
	{
		// added variable
		if (!$aData) {
			switch (Base::$aRequest['id']) {
				case -1: // added_no_replay
					$sTextNameValue = 'added_no_reply';
					Base::$tpl->assign('sType','text');
					$aData = array('id' => Base::$aRequest['id'], 'key_' => $sTextNameValue, 'value' => Language::GetText($sTextNameValue));
					break;
				case -2: // robots.txt
					$sTextNameValue = 'Файл robots.txt';
					Base::$tpl->assign('sType','only_text');
					$aData = array('id' => Base::$aRequest['id'], 'key_' => $sTextNameValue, 'value' => $this->getContentFileRobots());
					break;
				case -3: // site_counters
					$sTextNameValue = 'site_counters';
					Base::$tpl->assign('sType','only_text');
					$aData = array('id' => Base::$aRequest['id'], 'key_' => $sTextNameValue, 'value' => Language::GetText($sTextNameValue));
					break;
			}
			return;
		}
		
		$sType='';
		$sText = trim ($aData['type_data']);
		if ($sText == 'checkbox')
			$sType = $sText;
		elseif (strpos($sText,'enum') !== false) {
			$sType = 'enum'; 
			$aMass = explode('::',$sText);
			if (count($aMass) > 2) {
				$aOptions = explode("|",$aMass[1]);
				if ($aOptions < 1)
					$sType = '';
				else
					Base::$tpl->assign('aOptions',$aOptions);
				if ($aData['value'] && in_array($aData['value'],$aOptions))
					Base::$tpl->assign('sOptionCheck',$aData['value']);
				elseif ($aMass[2] && in_array($aMass[2],$aOptions)) {
					Base::$tpl->assign('sOptionCheck',$aMass[2]);
				}
			}
			else 
				$sType = '';
		}
		elseif ($aData['key_'] == 'favicon')
			$sType = $aData['key_'];
		elseif ($aData['key_'] == 'logo')
			$sType = $aData['key_'];
		else
			$sType='';
		
		Base::$tpl->assign('sType',$sType);
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParse(&$aItem) {
		$sTextNameValue = 'added_no_reply';
		$sTextNameValueData = strip_tags(Language::GetText($sTextNameValue));
		if (mb_strlen($sTextNameValueData) > 50)
			$sTextNameValueData = mb_substr($sTextNameValueData, 0, 50) . ' ...';
			
		$aItem[] = array('id' => -1, 'key_' => $sTextNameValue, 'type_data' => 'text', 
							'value' => $sTextNameValueData, 'description' => Language::getDMessage($sTextNameValue));
		
		$sTextNameValue = 'Файл robots.txt';
		$sTextNameValueData = $this->getContentFileRobots();
		if (mb_strlen($sTextNameValueData) > 50)
			$sTextNameValueData = mb_substr($sTextNameValueData, 0, 50) . ' ...';
		$aItem[] = array('id' => -2, 'key_' => $sTextNameValue, 'type_data' => 'only_text',
				'value' => $sTextNameValueData,'description' => Language::getDMessage($sTextNameValue));
		
		$sTextNameValue = 'site_counters';
		$sTextNameValueData = strip_tags(Language::GetText($sTextNameValue));
		if (mb_strlen($sTextNameValueData) > 50)
			$sTextNameValueData = mb_substr($sTextNameValueData, 0, 50) . ' ...';
			
		$aItem[] = array('id' => -3, 'key_' => $sTextNameValue, 'type_data' => 'only_text',
				'value' => $sTextNameValueData, 'description' => Language::getDMessage($sTextNameValue));
	}
	//-----------------------------------------------------------------------------------------------
	public function getContentFileRobots() {
		$sContent = file_get_contents($this->sPathRobots);
		return $sContent;
	}
	//-----------------------------------------------------------------------------------------------
	public function setContentFileRobots($sContent) {
		$iResult = file_put_contents($this->sPathRobots,$sContent);
		return $iResult;
	}
}
?>