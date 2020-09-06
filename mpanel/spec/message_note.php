<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class AMessageNote extends Admin {

	//-----------------------------------------------------------------------------------------------
	function AMessageNote() {
		$this->sTableName='message_note';
		$this->sTablePrefix='mn';
		$this->sAction='message_note';
		$this->sWinHead=Language::getDMessage('Message Notes');
		$this->sPath=Language::GetDMessage('>>Customer support >');
		$this->aCheckField=array('login','name','description');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id','sOrder'=>'mn.id'),
		'login'=>array('sTitle'=>'Login','sOrder'=>'u.login'),
		'reply_to'=>array('sTitle'=>'ReplyTo','sOrder'=>'mn.reply_to'),
		'name'=>array('sTitle'=>'Name','sOrder'=>'mn.name'),
		'description'=>array('sTitle'=>'Description','sOrder'=>'mn.description'),
		'is_closed'=>array('sTitle'=>'IsClosed','sOrder'=>'mn.is_closed'),
		'url'=>array('sTitle'=>'Url','sOrder'=>'mn.url'),
		'post_date'=>array('sTitle'=>'Date','sOrder'=>'mn.post_date'),
		'action'=>array(),
		);
		$this->SetDefaultTable($oTable);
		$oTable->sDefaultOrder=" order by mn.id";
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function Apply() {
		if (!$this->CheckField()) {
			$this->Message('MT_ERROR', Language::getDMessage('Please fill out all fields'));
			return;
		}
		else {
			$aUser=Base::$db->getRow(Base::GetSql('User',array('login'=>Base::$aRequest['data']['login'])));
			if (!$aUser) {
				$this->Message('MT_ERROR', Language::getDMessage('No such user with login: ').Base::$aRequest['data']['login']);
				return;
			}

			Base::$aRequest['data']['id_user']=$aUser['id'];
			Base::$aRequest['data']['post']=time();
			if (Base::$aRequest['data']['id']) {
				$sMode='UPDATE';
				$sWhere=$this->sTableId."='".Base::$aRequest['data']['id']."'";
			}
			else {
				$sMode='INSERT';
				$sWhere=false;
			}
			Base::$db->AutoExecute($this->sTableName, Base::$aRequest['data'], $sMode, $sWhere, true, true);
		}
		$this->AdminRedirect($this->sAction);
	}
	//-----------------------------------------------------------------------------------------------
}
?>