<?
require_once(SERVER_PATH.'/class/core/Admin.php');
class ACatPart extends Admin {

	//-----------------------------------------------------------------------------------------------
	function ACatPart() {
		$this->sTableName='cat_part';
		$this->sTablePrefix='cp';
		$this->sTableId='id';
		$this->sAction='cat_part';
		$this->sWinHead=Language::getDMessage('Parts parameters');
		$this->sPath=Language::GetDMessage('>>Auto catalog >');
		$this->aCheckField=array('code');

		$this->sBeforeAddMethod='BeforeAdd';
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		if (Language::getConstant('use_price_control',0)) {
    		$oTable->aColumn=array(
			'id'=>array('sTitle'=>Language::getDMessage('Id'), 'sOrder'=>'cp.id', 'sWidth'=>5, 'sMethod'=>'exact'),
			'pref'=>array('sTitle'=>Language::getDMessage('Pref'), 'sOrder'=>'cp.pref', 'sWidth'=>10),
		    'brand'=>array('sTitle'=>'Brand', 'sWidth'=>'10%'),
			'code'=>array('sTitle'=>Language::getDMessage('Code'), 'sOrder'=>'cp.code', 'sWidth'=>10),
			'name'=>array('sTitle'=>'Name price', 'sWidth'=>'40%'),
			'name_rus'=>array('sTitle'=>'Name Rus', 'sOrder'=>'cp.name_rus', 'sWidth'=>'40%'),
			'weight'=>array('sTitle'=>Language::getDMessage('Weight'), 'sOrder'=>'cp.weight', 'sWidth'=>10),
			//'size_name'=>array('sTitle'=>Language::getDMessage('Size Name'), 'sOrder'=>'cp.size_name', 'sWidth'=>10),
			'is_checked_code_ok'=>array('sTitle'=>'is_checked_code_ok', 'sOrder'=>'cp.is_checked_code_ok', 'sWidth'=>10),
			'is_checked_code_ok_date'=>array('sTitle'=>'is_checked_code_ok_date', 'sOrder'=>'cp.is_checked_code_ok_date', 'sWidth'=>10),
			'is_checked_code_ok_manager'=>array('sTitle'=>'is_checked_code_ok_manager', 'sOrder'=>'cp.is_checked_code_ok_manager', 'sWidth'=>10),
			'is_checked_code_ok_admin'=>array('sTitle'=>'is_checked_code_ok_admin', 'sOrder'=>'cp.is_checked_code_ok_admin', 'sWidth'=>10),
			'action' => array ()
			);
			$oTable->aCallbackAfter=array($this,'CallParseAfter');
		}
		else {
			$oTable->aColumn=array(
			'id'=>array('sTitle'=>Language::getDMessage('Id'), 'sOrder'=>'cp.id', 'sWidth'=>5, 'sMethod'=>'exact'),
			'pref'=>array('sTitle'=>Language::getDMessage('Pref'), 'sOrder'=>'cp.pref', 'sWidth'=>10),
			'code'=>array('sTitle'=>Language::getDMessage('Code'), 'sOrder'=>'cp.code', 'sWidth'=>10),
			//'name'=>array('sTitle'=>'Name',Language::getDMessage('Name'), 'sOrder'=>'cp.name', 'sWidth'=>'40%'),
			'name_rus'=>array('sTitle'=>Language::getDMessage('Name Rus'), 'sOrder'=>'cp.name_rus', 'sWidth'=>'40%'),
			'weight'=>array('sTitle'=>Language::getDMessage('Weight'), 'sOrder'=>'cp.weight', 'sWidth'=>10),
			//'size_name'=>array('sTitle'=>Language::getDMessage('Size Name'), 'sOrder'=>'cp.size_name', 'sWidth'=>10),
			'action' => array ()
			);
		}

		$this->SetDefaultTable ( $oTable);

		$oTable->bCheckVisible=false;
		$oTable->bCacheStepper=true;

		//$oTable->sSql=Base::GetSql('CatPart',array('where'=>$sWhere ));

		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAdd() {
		Base::$tpl->assign('aPref',Base::$db->getAssoc("select pref, concat(pref,' ',title) as name from cat order by name"));
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign(&$aData) {
	    $sName = Db::getOne("Select part_rus from price where item_code = '".$aData['item_code']."' and part_rus!='' order by id desc");
	    if ($sName)
	        $aData['name'] = $sName;
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeApply() {
		Base::$aRequest['data']['item_code']=Base::$aRequest['data']['pref']."_".Base::$aRequest['data']['code'];
		Base::$aRequest['data']['weight']=  str_replace(',', '.', Base::$aRequest['data']['weight']);
		if (!Base::$aRequest['data']['name_rus']){
			Base::$aRequest['data']['name_rus']=DB::GetOne("select part_rus from price where item_code='".Base::$aRequest['data']['item_code']."' and part_rus!='' order by id desc");
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseAfter(&$aItem) {
	    if (!$aItem)
	        return;
	    
	    $aItemCode = array();
	    foreach ($aItem as $aValue) {
	       if (!$aItemCode[$aValue['item_code']])
	           $aItemCode[$aValue['item_code']] = 1;
	    }
	    if ($aItemCode) {
	        $aData = Db::getAssoc("Select item_code, part_rus from price where item_code in ('".implode("','",array_keys($aItemCode))."') and part_rus!='' group by item_code order by id desc");
	        if ($aData) {
	            foreach ($aItem as $iKey => $aValue) {
	                if ($aData[$aValue['item_code']])
	                    $aItem[$iKey]['name'] = $aData[$aValue['item_code']];  
	            }
	        }
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function AfterApply($aBeforeRow,$aAfterRow) {
		if (Language::getConstant('use_price_control',0)) {
			if($aAfterRow['is_checked_code_ok'] != $aBeforeRow['is_checked_code_ok']) {
				Base::$db->Execute("update cat_part set is_checked_code_ok_date='".date("Y-m-d H:i:s")."', is_checked_code_ok_manager=0, is_checked_code_ok_admin=".$this->aAdmin['id']." where id=".$aAfterRow['id']);
			}
		}
	}

}
?>
