<?php
/*
 * @author Alex Belogura
 */
require_once (SERVER_PATH . '/class/core/Admin.php');
class AHandbook extends Admin {
	//-----------------------------------------------------------------------------------------------
	function AHandbook() {
		$this->sTableName = 'handbook';
		$this->sTablePrefix = 'h';
		$this->sAction = 'handbook';
		$this->sWinHead = Language::getDMessage('Handbooks');
		$this->sPath=Language::GetDMessage('>>Auto catalog >');
		//$this->sBeforeAddMethod='BeforeAdd';
		$this->aCheckField = array('name','table_');
		$this->sNumSql="select max(id) from ".$this->sTableName."";
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {

		$sTablePref = $this->sTablePrefix.".";
		$this->PreIndex();

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong',0)) {
                if ($this->aSearch['id'])$this->sSearchSQL .= " and h.id = '".$this->aSearch['id']."'";
                if ($this->aSearch['name'])	$this->sSearchSQL .= " and h.name = '".$this->aSearch['name']."'";
                if ($this->aSearch['table_'])	$this->sSearchSQL .= " and h.table_ = '".$this->aSearch['table_']."'";
                if ($this->aSearch['number'])	$this->sSearchSQL .= " and h.number = '".$this->aSearch['number']."'";
                if ($this->aSearch['is_collapsed']=='1')	$this->sSearchSQL .= " and h.is_collapsed = '1'";
                if ($this->aSearch['is_collapsed']=='0')	$this->sSearchSQL .= " and h.is_collapsed = '0'";
                //with else "ignore" will not be is_collapsed
                switch($this->aSearch['is_collapsed']){
                    case '1':
                        $this->sSearchSQL.=" and h.is_collapsed>='1'";
                        break;
                    case '0':
                        $this->sSearchSQL.=" and h.is_collapsed>='0'";
                        break;
                    case  '':
                        break;
                }
            }
            else {
                if ($this->aSearch['id'])$this->sSearchSQL .= " and h.id like '%".$this->aSearch['id']."%'";
                if ($this->aSearch['name'])	$this->sSearchSQL .= " and h.name like '%".$this->aSearch['name']."%'";
                if ($this->aSearch['table_'])	$this->sSearchSQL .= " and h.table_ like '%".$this->aSearch['table_']."%'";
                if ($this->aSearch['number'])	$this->sSearchSQL .= " and h.number like '%".$this->aSearch['number']."%'";
                if ($this->aSearch['is_collapsed']=='1')	$this->sSearchSQL .= " and h.is_collapsed = '1'";
                if ($this->aSearch['is_collapsed']=='0')	$this->sSearchSQL .= " and h.is_collapsed = '0'";
                //with else "ignore" will not be found
                switch($this->aSearch['is_collapsed']){
                    case '1':
                        $this->sSearchSQL.=" and h.is_collapsed>='1'";
                        break;
                    case '0':
                        $this->sSearchSQL.=" and h.is_collapsed>='0'";
                        break;
                    case  '':
                        break;
                }            }
        }

		$this->initLocaleGlobal ();
		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn=array(
			'id'=>array ('sTitle' => 'Id', 'sOrder' => $sTablePref.'id' ),
			'name'=>array ('sTitle' => 'Name', 'sOrder' => $sTablePref.'name' ),
			'table_'=>array ('sTitle' => 'Table', 'sOrder' => $sTablePref.'table_' ),
			'number'=>array ('sTitle' => 'Order', 'sOrder' => $sTablePref.'number' ),
			'is_collapsed'=>array ('sTitle' => 'Collapsed', 'sOrder' => $sTablePref.'is_collapsed' ),
			'language'=>array ('sTitle' => 'Lang' ),			
			'action'=>array(),
		);
		
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
		
		if (Base::$aRequest['aMessage'])
			Admin::Message(Base::$aRequest['aMessage']['type'],Base::$aRequest['aMessage']['message']);
		
	}
	//-----------------------------------------------------------------------------------------------
	public function Apply(){
		if(!Base::$aRequest['data']['table_']) Base::$aRequest['data']['table_']="handbook__".strtolower(Content::Translit(Base::$aRequest['data']['name']));
		$aData=Base::$aRequest['data'];
		
		if (!$aData['id']&&$aData['table_']) {
			//$sSql="describe `".$aData['table_']."`";
			$sSql="SHOW TABLES LIKE '".$aData['table_']."'";
			$oNewTable=Db::GetRow($sSql);
			if ($oNewTable) {
				Base::$aRequest['data']['table_']=Base::$aRequest['data']['table_']."_hb";
				$aData['table_']=Base::$aRequest['data']['table_'];
			}
			$sSql="CREATE TABLE IF NOT EXISTS `".$aData['table_']."` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`name` varchar(255) NOT NULL DEFAULT '',
				`visible` int(11) NOT NULL DEFAULT '1',
				PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
			Db::Execute($sSql);
			
			$sSql="ALTER TABLE `price_group_param` ADD `id_".$aData['table_']."` INT( 11 ) NOT NULL DEFAULT '0'";
			Db::Execute($sSql);
		}
		elseif ($aData['id']) {
			$aInfo = Db::getRow("Select * from handbook where id=".$aData['id']);
			// rename table
			if ($aInfo['table_']!=$aData['table_']) {
				$sSql="SHOW TABLES LIKE '".$aInfo['table_']."'";
				$oOldTable=Db::GetRow($sSql);
				$sSql="SHOW TABLES LIKE '".$aData['table_']."'";
				$oNewTable=Db::GetRow($sSql);
				if ($oNewTable) {
					$aMessage = array ('type' => 'MT_ERROR', 'message' => Language::getMessage('name_table_already_exist'));
					$this->AdminRedirect ( $this->sAction, $aMessage);
					return;
				}
				$sSql = "Rename table `".$aInfo['table_']."` to `".$aData['table_']."`";
				Db::Execute($sSql);
				$sSql="SHOW TABLES LIKE '".$aData['table_']."'";
				$oNewTable=Db::GetRow($sSql);
				if (!$oNewTable) {
					$aMessage = array ('type' => 'MT_ERROR', 'message' => Language::getMessage('error_rename_table'));
					$this->AdminRedirect ( $this->sAction, $aMessage);
					return;
				}	
				$sSql="ALTER TABLE `price_group_param` CHANGE COLUMN `id_".$aInfo['table_']."` `id_".$aData['table_']."` INT( 11 ) NOT NULL DEFAULT '0'";
				Db::Execute($sSql);
			}
		}
		parent::Apply();
	}
	//-----------------------------------------------------------------------------------------------
	public function Delete() {
		if (Base::$aRequest['id']){
			$sSql="select table_ from handbook where id='".Base::$aRequest['id']."'";
			$sTable=Db::GetOne($sSql);
			$sSql="drop table `".$sTable."`";
			Db::Execute($sSql);
			$sSql="ALTER TABLE `price_group_param` DROP `id_".$sTable."`;";
			Db::Execute($sSql);
		}
		parent::Delete();
	}
}
?>