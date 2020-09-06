<?
/**
 * @author Mikhail Starovoyt
 *
 */

class AUser extends Admin
{
	//-----------------------------------------------------------------------------------------------
	public function BeforeApply()
	{
		if (!Base::$aRequest['data']['id']) {
			$sSalt=String::GenerateSalt();
			Base::$aRequest['data']['salt']=$sSalt;
			Base::$aRequest['data']['password']=String::Md5Salt(Base::$aRequest['data']['password'],$sSalt);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function ChangePassword()
	{
		Base::$tpl->assign('sFormType', $action);
		Base::$tpl->assign('sReturn', stripslashes(Base::$aRequest['return']));
		Base::$tpl->assign('aData',array('id'=>Base::$aRequest['id']));

		$this->sAction = "user/change_password";
		Admin::ProcessTemplateForm('>>Users > Change password');
	}
	//-----------------------------------------------------------------------------------------------
	public function ChangePasswordApply()
	{
		if (strlen(trim(Base::$aRequest['data']['password']))<=5 ){
			Admin::Message('MT_ERROR','Password must more than 5 digits');
			return;
		}

		if (Base::$aRequest['data']['password']!=Base::$aRequest['data']['retype_password']){
			Admin::Message('MT_ERROR','Passwords are not the same');
			return;
		}

		$sSalt=String::GenerateSalt();
		$aUserUpdate=array(
		'password'=>String::Md5Salt(Base::$aRequest['data']['password'],$sSalt),
		'salt'=>$sSalt,
		'password_temp'=>'',
		);
		Db::AutoExecute('user',$aUserUpdate,"UPDATE"," id='".Base::$aRequest['data']['id']."'");

		parent::AdminRedirect($this->sAction);
	}
	//-----------------------------------------------------------------------------------------------
	public function Apply()
	{
		if (!Base::$aRequest['data']['id']){
			$aData = Base::$db->GetAll("select * from user where login='".Base::$aRequest['data']['login']."' and id<>'".
			Base::$aRequest['data']['id']."'");
			if (count($aData) > 0){
				Admin::Message('MT_ERROR', Base::$aRequest['data']['login'].Language::GetDMessage(' login is occupied.' ) );
				return;
			}
			if (Base::$aRequest['data']['login']==Base::$aRequest['data']['password']){
				Admin::Message('MT_ERROR',Language::getDMessage('Login and password are the same. Please choose another password'));
				return;
			}
		}
		parent::Apply();
	}
	//-----------------------------------------------------------------------------------------------
}

?>