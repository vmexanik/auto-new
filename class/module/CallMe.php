<?
/**
 * @author Mikhail Strovoyt
 */

class CallMe extends Base
{
	public function Send()
	{
	    //google capcha
	    $capcha = $_POST['g-recaptcha-response'];
	    $url_to_google_api = "https://www.google.com/recaptcha/api/siteverify";
	    $secret_key = Language::GetConstant('captcha:privat_key','6LdJj9UUAAAAAAEI9b67EP3ZJJhu10MvBgNTJ2bs');
	    $query = $url_to_google_api . '?secret=' . $secret_key . '&response=' . $capcha . '&remoteip=' . $_SERVER['REMOTE_ADDR'];
	    $data = json_decode(file_get_contents($query));
    
	if ($data->success){

	    
     Base::$aGeneralConf['StripXss']=1;
     Base::EscapeAll(Base::$aRequest);
	   $date=date('d.m.Y H:i');
	   $sToEmail=Base::GetConstant('global:to_email');
      $sSubject=Language::GetText('Call me request')." ".SERVER_NAME.$_SERVER['REQUEST_URI'];
      $sBodyHtml="<h5>".$date."</h5><br>";
      $sBodyHtml.=Language::GetText('Client name').": <b>".Base::$aRequest['name']."</b><br>";
      $sBodyHtml.=Language::GetText('Phone').": <b>".Base::$aRequest['phone']."</b><br>";
      $sFromEmail=Base::GetConstant('global:email_from');
      Mail::$bAddedNoRply=false;
	   $bSendResult=Mail::SendNow($sToEmail,$sSubject,$sBodyHtml,$sFromEmail);
	   
	   
	   
	   $aCallMe['fio']= Base::$aRequest['name'];
	   $aCallMe['phone']= Base::$aRequest['phone'];
	   

	   Db::autoExecute('call_me', $aCallMe);
	   
	   if($bSendResult) {
	        Base::$oContent->AddCrumb(Language::GetText('Your message is successfully sent'));
			Base::$sText.=Language::GetText('Your message is successfully sent.');
			return; 		    
		}
	 }else{
	        Base::$oContent->AddCrumb(Language::GetText('Your message not sent'));
		    Base::$sText.=Language::GetText('Your message is not sent.');
		    return;
		}
	 
	 
	}
	
	public function ShowManager()
	{
	    Auth::NeedAuth('manager');
	    if(Base::$aRequest['id']){
	        Db::Execute("UPDATE call_me SET resolved = 1 WHERE id =".Base::$aRequest['id']);
	    }
	    $oTable = new Table();
	    $oTable->iRowPerPage=50;
	    $oTable->bStepperVisible = true;
	    $oTable->bCountStepper = 1;
	    $oTable->sSql = "SELECT * FROM call_me";
        $oTable->aColumn['id']=array('sTitle'=>'#', 'sOrder'=>'id','sDefaultOrderWay' => 'desc');
		$oTable->aColumn['fio']=array('sTitle'=>'fio', 'sOrder'=>'fio');
		$oTable->aColumn['phone']=array('sTitle'=>'phone', 'sOrder'=>'phone');
		$oTable->aColumn['post_date']=array('sTitle'=>'post date', 'sOrder'=>'post_date');
		$oTable->aColumn['resolved']=array('sTitle'=>'Resolution', 'sOrder'=>'resolved');
		$oTable->aColumn['action']=array();
		$oTable->sDefaultOrder="order by id desc";
	    $oTable->sDataTemplate = "call_me/row_call.tpl";
	     
	    Base::$sText.=$oTable->getTable("Calls");
	     
	}
}
?>