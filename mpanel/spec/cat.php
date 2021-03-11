<?
require_once (SERVER_PATH . '/class/core/Admin.php');

class ACat extends Admin {
	//-----------------------------------------------------------------------------------------------
	function ACat() {
		$this->sSqlPath = 'Cat';
		$this->sTableName = 'cat';
		$this->sTablePrefix = 'c';
		$this->sAction = 'cat';
		$this->sWinHead = Language::getDMessage ('Catalog list');
		$this->sPath = Language::GetDMessage('>>Auto catalog >');
		$this->aCheckField = array ('name', 'pref', 'title');
		$this->aUniqueField = array('id_sup', 'id_mfa');
		//$this->aFCKEditors = array ('descr');
		$this->sBeforeAddMethod='BeforeAdd';
		$this->Admin ();
		Base::$tpl->assign("aAdminUser",$this->aAdmin);
		Base::$tpl->assign("sTecDocUrl",Base::$aGeneralConf['TecDocUrl']."/imgbank/tcd/");
	}
	
	
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();
		
		Base::$sText .= $this->SearchForm ();
		if ($this->aSearch) {
			if (Language::getConstant('mpanel_search_strong',0)) {
				if ($this->aSearch['id'])$this->sSearchSQL .= " and c.id = '".$this->aSearch['id']."'";
				if ($this->aSearch['name'])	$this->sSearchSQL .= " and c.name = '".$this->aSearch['name']."'";
				if ($this->aSearch['pref'])	$this->sSearchSQL .= " and c.pref = '".$this->aSearch['pref']."'";
				if ($this->aSearch['title'])	$this->sSearchSQL .= " and c.title = '".$this->aSearch['title']."'";
				if ($this->aSearch['id_sup'])	$this->sSearchSQL .= " and c.id_sup = '".$this->aSearch['id_sup']."'";
				if ($this->aSearch['id_mfa'])	$this->sSearchSQL .= " and c.id_mfa = '".$this->aSearch['id_mfa']."'";
			}
			else {
			    if ($this->aSearch['id'])$this->sSearchSQL .= " and c.id like '%".$this->aSearch['id']."%'";
			    if ($this->aSearch['name'])	$this->sSearchSQL .= " and c.name like '%".$this->aSearch['name']."%'";
			    if ($this->aSearch['pref'])	$this->sSearchSQL .= " and c.pref like '%".$this->aSearch['pref']."%'";
			    if ($this->aSearch['title'])	$this->sSearchSQL .= " and c.title like '%".$this->aSearch['title']."%'";    
			    if ($this->aSearch['id_sup'])	$this->sSearchSQL .= " and c.id_sup like '%".$this->aSearch['id_sup']."%'";
			    if ($this->aSearch['id_mfa'])	$this->sSearchSQL .= " and c.id_mfa like '%".$this->aSearch['id_mfa']."%'";
			}
			if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and c.visible = '1'";
			if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and c.visible = '0'";
			//with else "ignore" will not be found
			switch($this->aSearch['visible']){
			    case '1':
			        $this->sSearchSQL.=" and c.visible>='1'";
			        break;
			    case '0':
			        $this->sSearchSQL.=" and c.visible>='0'";
			        break;
			    case  '':
			        break;
			}
			if ($this->aSearch['is_main']=='1')	$this->sSearchSQL .= " and c.is_main = '1'";
			if ($this->aSearch['is_main']=='0')	$this->sSearchSQL .= " and c.is_main = '0'";
			switch($this->aSearch['is_main']){
			    case '1':
			        $this->sSearchSQL.=" and c.is_main>='1'";
			        break;
			    case '0':
			        $this->sSearchSQL.=" and c.is_main>='0'";
			        break;
			    case  '':
			        break;
			}
			if ($this->aSearch['is_vin_brand']=='1')	$this->sSearchSQL .= " and c.is_vin_brand = '1'";
			if ($this->aSearch['is_vin_brand']=='0')	$this->sSearchSQL .= " and c.is_vin_brand = '0'";
			switch($this->aSearch['is_vin_brand']){
			    case '1':
			        $this->sSearchSQL.=" and c.is_vin_brand>='1'";
			        break;
			    case '0':
			        $this->sSearchSQL.=" and c.is_vin_brand>='0'";
			        break;
			    case  '':
			        break;
			}
			if ($this->aSearch['is_brand']=='1') $this->sSearchSQL .= " and c.is_brand='1' ";
			if ($this->aSearch['is_brand']=='0') $this->sSearchSQL .= " and c.is_brand='0' ";
			switch($this->aSearch['is_brand']){
			    case '1':
			        $this->sSearchSQL.=" and c.is_brand>='1'";
			        break;
			    case '0':
			        $this->sSearchSQL.=" and c.is_brand>='0'";
			        break;
			    case  '':
			        break;
			}
		}
		
		$oTable=new Table();
		$oTable->aColumn=array(
		'id' => array('sTitle'=>'Id', 'sOrder'=>$this->sTablePrefix.'.id'),
		'name' => array('sTitle'=>'Name', 'sOrder'=>$this->sTablePrefix.'.name'),
		//'description' => array('sTitle'=>'Description', 'sOrder'=>$this->sTablePrefix.'.description'),
		'pref' => array('sTitle'=>'Prefix', 'sOrder'=>$this->sTablePrefix.'.pref'),
		'title' => array('sTitle'=>'Title', 'sOrder'=>$this->sTablePrefix.'.title'),
		'image' => array('sTitle'=>'Image', 'sOrder'=>$this->sTablePrefix.'.image'),
		'image_tecdoc' => array('sTitle'=>'Image tecdoc', 'sOrder'=>$this->sTablePrefix.'.image_tecdoc'),
	    'id_sup' => array('sTitle'=>'id_sup', 'sOrder'=>$this->sTablePrefix.'.id_sup'),
	    'id_mfa' => array('sTitle'=>'id_mfa', 'sOrder'=>$this->sTablePrefix.'.id_mfa'),
	    'is_brand' => array('sTitle'=>'Is brand', 'sOrder'=>$this->sTablePrefix.'.is_brand'),
		'is_vin_brand' => array('sTitle'=>'Is vin brand', 'sOrder'=>$this->sTablePrefix.'.is_vin_brand'),
		'is_main' => array('sTitle'=>'Is main', 'sOrder'=>$this->sTablePrefix.'.is_main'),
		'virtual_title' => array('sTitle'=>'Virtual', 'sOrder'=>'cv.title'),
		'virtual_pref' => array('sTitle'=>'Virtual pref', 'sOrder'=>'cv.pref'),
		'visible' => array('sTitle'=>'Visible', 'sOrder'=>$this->sTablePrefix.'.visible'),
		'action' => array(),
		);
		
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();

		if (Base::$aRequest['aMessage'])
			Admin::Message(Base::$aRequest['aMessage']['type'],Base::$aRequest['aMessage']['message']);

	}
	//-----------------------------------------------------------------------------------------------
		public function BeforeAdd() {
		$aCatVirtual=Base::$db->getAssoc("select id, title from cat where is_cat_virtual=1 order by title");
		if(!$aCatVirtual)$aCatVirtual=array();
		Base::$tpl->assign('aCatVirtual',array(0=>'')+$aCatVirtual);
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeApply() {
		Base::$aRequest['data']['name']=$this->BrandReplace(Base::$aRequest['data']['name']);
		Base::$aRequest['data']['pref']=substr(mb_strtoupper(str_replace(array(' ','-','#','.','/',',','_',':','[',']','(',')','*','&','+','`','\'','"','\\','<','>','?','!','$','%','^','@','~','|','=',';','{','}','№'), '', trim(Content::Translit(Base::$aRequest['data']['pref']))),'UTF-8'),0,3);
		
		Base::$aRequest['data']['parser_patern']=mysql_real_escape_string(trim(Base::$aRequest['data']['parser_patern']));
		Base::$aRequest['data']['parser_before']=mysql_real_escape_string(trim(Base::$aRequest['data']['parser_before']));
		Base::$aRequest['data']['trim_left_by']=mysql_real_escape_string(trim(Base::$aRequest['data']['trim_left_by']));
		Base::$aRequest['data']['trim_right_by']=mysql_real_escape_string(trim(Base::$aRequest['data']['trim_right_by']));
		Base::$aRequest['data']['parser_after']=mysql_real_escape_string(trim(Base::$aRequest['data']['parser_after']));
	}
	//-----------------------------------------------------------------------------------------------
	public function AfterApply($aBeforeRow,$aAfterRow) {
		if($aAfterRow) {
			Base::$db->Execute("update cat set pref=upper(pref) where id=".$aAfterRow['id']);
			//Base::$db->Execute("insert ignore into `cat_pref` (`name`, `pref`) VALUES (upper('".$aAfterRow['name']."'), upper('".$aAfterRow['pref']."'));");
			Base::$db->Execute("insert ignore into `cat_pref` (`name`, `cat_id`) VALUES (upper('".$aAfterRow['name']."'), '".$aAfterRow['id']."');");
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function CheckField()
	{
		if ($this->aCheckField)
		foreach ( $this->aCheckField as $value ) {
			if (strlen(Base::$aRequest ['data'] [$value]) == 0) 
				return false;
			
			if ($value=='pref'){
				//check existing pref
				if (Base::$aRequest['data']['id']) $sWhere=" and id<>'".Base::$aRequest['data']['id']."'";
				$bExist=Db::GetOne("select count(*) from cat where pref='".Base::$aRequest['data']['pref']."' ".$sWhere);
				if ($bExist) {
					$this->Message('MT_ERROR',Language::getDMessage ( 'This pref already exists' ));
					$this->bAlreadySetMessage = true;
					return false;
				}
			}
			
			if($value=='name') {
			    if (!Base::$aRequest['data']['id']) {
			        $iIdExistCat=Db::GetOne("select id from cat where name like '".Base::$aRequest['data']['name']."'");
			        if($iIdExistCat) {
			            //error cat exist
			            $this->Message ( 'MT_ERROR', Language::getDMessage ( 'this brand is exist' ) );
			            $this->bAlreadySetMessage=1;
			             
			            return false;
			        }
			    }
			}
		}
		return true;
	}
	

	//-----------------------------------------------------------------------------------------------
	public function BrandReplace($sBrand='') {
		return mb_strtoupper(str_replace(array(' ','-','#','.','/',',','_',':','[',']','(',')','*','&','+','`','\'','"','\\','<','>','?','!','$','%','^','@','~','|','=',';','{','}','№'), '', trim(Content::Translit($sBrand))),'UTF-8');
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign(&$aData) {
		if ($aData['parser_patern'])
			$aData['parser_patern'] = stripslashes($aData['parser_patern']);
	}
	//-----------------------------------------------------------------------------------------------
	public function CheckName()
	{
		$aCat = Db::GetAssoc("Select id,name from cat");
		$i=0;
		$is_lower = 0;
		if (Language::getConstant('admin_regulations:cat_name_is_lower','1'))
			$is_lower = 1;
		
		foreach($aCat as $iKey => $sValue) {
			$sName = str_replace(array('_','%','#','.','/',',',':','[',']','(',')','*','`','\'','"','\\'),"",trim($sValue));
			if ($is_lower)
				$sName = mb_strtolower(str_replace(array('-','&','+',' '),"_",$sName));
			else
				$sName = mb_strtoupper(str_replace(array('-','&','+',' '),"_",$sName));

			$sName = Content::Translit($sName);
			if ($sName != $sValue) {
				$i+=1;
				Db::Execute("Update cat set name='".$sName."' where id=".$iKey);
			}
		}
		$this->Message ( 'MT_NOTICE', Language::getDMessage ( 'cat name checked, found and update items count' ).': '.$i );
	}
	//-----------------------------------------------------------------------------------------------
	public function DelCat()
	{
		if (is_array ( Base::$aRequest ['row_check'] )) {
			$aId = Base::$aRequest ['row_check'];
		} else {
			$aId = array (Base::$aRequest ['id'] );
		}
		foreach ( $aId as $sValue ) {
			if (! $sDelQuery) {
				$sDelQuery = " `id`='$sValue'";
			} else {
				$sDelQuery .= " or `id`='$sValue'";
			}

		}
		if ($sDelQuery) {
			DB::StartTrans();
			$sQuery = "update cat set id_cat_virtual=0 where id_cat_virtual in (".implode(",",$aId).")";
			Db::Execute ( $sQuery );
			$sQuery = "delete from price_import where pref in (select pref from cat where " . $sDelQuery.")";
			Db::Execute ( $sQuery );
			$sQuery = "delete from price where pref in (select pref from cat where " . $sDelQuery.")";
			Db::Execute ( $sQuery );
			$sQuery = "delete from cat_pref where cat_id in (select id from cat where " . $sDelQuery.")";
			Db::Execute ( $sQuery );
			
			Db::Execute("delete from `price_group_assign` where pref in (select pref from cat where " . $sDelQuery.")");
			Db::Execute("DELETE cpw FROM cat_part_weight AS cpw INNER JOIN cat_part cp ON cp.id = cpw.id_cat_part AND cp.pref in (select pref from cat where " . $sDelQuery.")");
			Db::Execute("DELETE pi FROM cat_pic AS pi INNER JOIN cat_part cp ON cp.id = pi.id_cat_part AND cp.pref in (select pref from cat where " . $sDelQuery.")");
			Db::Execute("delete from cat_part where pref in (select pref from cat where " . $sDelQuery.")");
			Db::Execute("delete from cat_cross where pref in (select pref from cat where " . $sDelQuery.")");
			Db::Execute("delete from cat_cross where pref_crs in (select pref from cat where " . $sDelQuery.")");
			Db::Execute("delete from cat_cross_stop where pref in (select pref from cat where " . $sDelQuery.")");
			Db::Execute("delete from cat_cross_stop where pref_crs in (select pref from cat where " . $sDelQuery.")");
			Db::Execute("delete from cart where type_='cart' and pref in (select pref from cat where " . $sDelQuery.")");
			Db::Execute("delete from cart_deleted where pref in (select pref from cat where " . $sDelQuery.")");

			$sQuery = "delete from `$this->sTableName` where " . $sDelQuery;
			Db::Execute ( $sQuery );
			Db::CompleteTrans();
			
			Language::UpdateConstant('global:auto_pref_last','AAA');
		}
		
		$this->AdminRedirect ( $this->sAction );
	}
	//-----------------------------------------------------------------------------------------------
	public function Replace() {
		Base::$tpl->assign('sReturn', stripslashes(Base::$aRequest['return']));
		
		$aCat=Db::getRow("Select * from cat where id=".Base::$aRequest['id']);
		if ($aCat)
			Base::$tpl->assign('aCat',$aCat);
		$iCntPrice = Db::getOne("Select count(*) from price where pref='".$aCat['pref']."'");
		Base::$tpl->assign('iCntPrice',$iCntPrice);
		
		$aCats=Db::getAssoc("Select id,concat(if(title!='',title,''),' (',pref,') ',' id_sup=',if(id_sup,id_sup,'0'),' id_mfa=',if(id_mfa,id_mfa,'0')) from cat where id!=".Base::$aRequest['id']." order by title");
		Base::$tpl->assign('aCatReplace',$aCats);
		
		// from Admin - ProcessTemplateForm
		Base::$oResponse->addAssign ( 'sub_menu', 'innerHTML', '' );
		Base::$oResponse->addAssign ( 'win_head', 'innerHTML', Language::getDMessage ( 'replace brand' ) );
		Base::$sText .= Base::$tpl->fetch($this->sAddonPath.'mpanel/'.$this->sAction.'/form_replace_brand.tpl');
		Base::$oResponse->addScript ( '__FCKeditorNS = null;' );
		Base::$oResponse->addScript ( 'FCKeditorAPI = null;' );
		Base::$oResponse->addAssign ( 'sub_menu', 'innerHTML', '' );
		Base::$oResponse->addAssign ( 'win_text', 'innerHTML', Base::$sText );
		$this->Message ();
	}
	//-----------------------------------------------------------------------------------------------
	public function ReplaceApply() {
		set_time_limit(0);
		$aOldCat = Db::getRow("Select * from cat where id=".Base::$aRequest['data']['id']);
		$aCatNew = Db::getRow("Select * from cat where id=".Base::$aRequest['data']['id_cat_replace']);
		$sAddText = '';
		if ($aOldCat && $aCatNew) {
			DB::StartTrans();

			// replace price
			Db::Execute("UPDATE IGNORE `price` SET `item_code`= REPLACE(`item_code`, '".$aOldCat['pref']."_', '".$aCatNew['pref']."_')");
			Db::Execute("UPDATE `price` SET pref='".$aCatNew['pref']."' where pref='".$aOldCat['pref']."'");
			// del not update records - incorrect doubles
			Db::Execute("delete FROM `price` WHERE pref='".$aOldCat['pref']."' and left(item_code,3)!='".$aOldCat['pref']."'");
					
			// replace price_group_assing
			Db::Execute("UPDATE IGNORE `price_group_assign` SET `item_code`= REPLACE(`item_code`, '".$aOldCat['pref']."_', '".$aCatNew['pref']."_')");
			Db::Execute("UPDATE IGNORE `price_group_assign` SET pref='".$aCatNew['pref']."' where pref='".$aOldCat['pref']."'");
			// del not update records - incorrect doubles
			Db::Execute("DELETE from `price_group_assign` where pref='".$aOldCat['pref']."' or left(item_code,3)='".$aOldCat['pref']."'");
				
			// replace cat_part
			Db::Execute("UPDATE IGNORE `cat_part` SET `item_code`= REPLACE(`item_code`, '".$aOldCat['pref']."_', '".$aCatNew['pref']."_')");
			Db::Execute("UPDATE IGNORE `cat_part` SET pref='".$aCatNew['pref']."' where pref='".$aOldCat['pref']."'");
			// del not update records - incorrect doubles
			Db::Execute("DELETE from `cat_part` where pref='".$aOldCat['pref']."' or left(item_code,3)='".$aOldCat['pref']."'");
				
			// cat_cross
			Db::Execute("UPDATE IGNORE `cat_cross` SET pref='".$aCatNew['pref']."' where pref='".$aOldCat['pref']."'");
			Db::Execute("UPDATE IGNORE `cat_cross` SET pref_crs='".$aCatNew['pref']."' where pref_crs='".$aOldCat['pref']."'");
			// del not update records - incorrect doubles
			Db::Execute("DELETE from `cat_cross` where pref='".$aOldCat['pref']."' or pref_crs='".$aOldCat['pref']."'");
			
			// cat_cross_stop
			Db::Execute("UPDATE IGNORE `cat_cross_stop` SET pref='".$aCatNew['pref']."' where pref='".$aOldCat['pref']."'");
			Db::Execute("UPDATE IGNORE `cat_cross_stop` SET pref_crs='".$aCatNew['pref']."' where pref_crs='".$aOldCat['pref']."'");
			// del not update records - incorrect doubles
			Db::Execute("DELETE from `cat_cross_stop` where pref='".$aOldCat['pref']."' or pref_crs='".$aOldCat['pref']."'");
				
			//cat_part_weight
			Db::Execute("UPDATE cat_part_weight set id_cat_part=".$aCatNew['id']." where id_cat_part=".$aOldCat['id']);			
			
			//cat_pic
			Db::Execute("UPDATE cat_pic set id_cat_part=".$aCatNew['id']." where id_cat_part=".$aOldCat['id']);
			
			//cart - only item basket
			Db::Execute("UPDATE `cart` SET `item_code`= REPLACE(`item_code`, '".$aOldCat['pref']."_', '".$aCatNew['pref']."_') where type_='cart'");			
			Db::Execute("UPDATE `cart` SET pref='".$aCatNew['pref']."' where pref='".$aOldCat['pref']."' and type_='cart'");

			//cart_deleted
			Db::Execute("UPDATE `cart_deleted` SET `item_code`= REPLACE(`item_code`, '".$aOldCat['pref']."_', '".$aCatNew['pref']."_') where type_='cart'");
			Db::Execute("UPDATE `cart_deleted` SET pref='".$aCatNew['pref']."' where pref='".$aOldCat['pref']."' and type_='cart'");
				
			if (Base::$aRequest['data']['is_link_selected']) {
				$sAddText .= Language::getMessage('old_brand_set_sinonym_new_brand');
				Db::Execute("UPDATE cat_pref set cat_id=".$aCatNew['id']." where cat_id=".$aOldCat['id']);
			}
			else 
				Db::Execute("delete from cat_pref where cat_id=".$aOldCat['id']);

			Db::Execute("delete from cat where id=".$aOldCat['id']);

			$aMessage = array ('type' => 'MT_NOTICE', 'message' => Language::getMessage('Replace brand')." [".$aOldCat['title'].'] => ['.$aCatNew['title'].'] '.$sAddText);
			Db::CompleteTrans();			
		}
		else 
			$aMessage = array ('type' => 'MT_ERROR', 'message' => Language::getMessage('incorrect select brand'));
					
		$this->AdminRedirect ( $this->sAction, $aMessage );		
	}
	//-----------------------------------------------------------------------------------------------
	public function SyncVirtual() {
	    set_time_limit(0);
	    $aCat=Db::GetRow("select pref,id_cat_virtual,id from cat where id='".Base::$aRequest['id']."' ");
	    if($aCat) {
	        $sPrefVirtual=Db::GetRow("select pref,id from cat where id='".$aCat['id_cat_virtual']."' ");
	        if($sPrefVirtual) {
	            Db::StartTrans();
	            Db::Execute("UPDATE IGNORE price set item_code=REPLACE(item_code,'".$aCat['pref']."','".$sPrefVirtual['pref']."') WHERE pref = '".$aCat['pref']."'");
	            Db::Execute("UPDATE IGNORE price set pref='".$sPrefVirtual['pref']."' WHERE `pref` = '".$aCat['pref']."'");
	            Db::Execute("delete from price where `pref` = '".$aCat['pref']."'");
	            Db::Execute("delete FROM `price` WHERE item_code like '".$aCat['pref']."%' and pref = '".$sPrefVirtual['pref']."'");
	            Db::Execute("update cat_pref set cat_id='".$sPrefVirtual['id']."' where cat_id='".$aCat['id']."' ");
	            Db::CompleteTrans();
	        }
	    }
	    
	    $this->AdminRedirect ( $this->sAction );
	}
	//-----------------------------------------------------------------------------------------------
}
?>