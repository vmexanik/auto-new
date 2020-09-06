<?

/**
 * @author Mikhail Starovoyt
 */

class ContactForm extends Base
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
// 		$oCpacha= new Capcha();
// 		Base::$tpl->assign('sCapcha',$oCpacha->GetMathematic('user/capcha.tpl'));
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		if (Base::$aRequest['is_post']) {
		    //google capcha
// 		    $capcha = $_POST['g-recaptcha-response'];
// 		    $url_to_google_api = "https://www.google.com/recaptcha/api/siteverify";
// 		    $secret_key = Base::GetConstant('capcha:privat_key','6LeZKLUUAAAAANtatls2ux-2aXGzHqYinRiwfbS7');
// 		    $query = $url_to_google_api . '?secret=' . $secret_key . '&response=' . $capcha . '&remoteip=' . $_SERVER['REMOTE_ADDR'];
// 		    $data = json_decode(file_get_contents($query));
// 		    if (!$data->success)
// 		        $sError = "Check capcha value";
		    
		    $capcha = $_POST['g-recaptcha-response'];
		    $url_to_google_api = "https://www.google.com/recaptcha/api/siteverify";
		    $secret_key = Language::GetConstant('captcha:privat_key','6LdJj9UUAAAAAAEI9b67EP3ZJJhu10MvBgNTJ2bs');
		    $query = $url_to_google_api . '?secret=' . $secret_key . '&response=' . $capcha . '&remoteip=' . $_SERVER['REMOTE_ADDR'];
		    $data = json_decode(file_get_contents($query));
		    if (!$data->success)
		        $sError = "Check capcha value";
		    else {
		        Base::$aRequest['data']['phone'] = Catalog::StripCode(Base::$aRequest['data']['phone']);

		        if (Base::$aRequest['data']['name'] && Base::$aRequest['data']['email'] && (preg_match('/(067|068|096|097|098|050|066|095|099|063|073|093|091|092|094)([0-9]{7})/',Base::$aRequest['data']['phone']))) {
		    
		            foreach (Base::$aRequest['data'] as $sKey => $aValue) {
		                $sMessage.=Language::GetMessage($sKey).' : '.$aValue.'<br />';
		            }
		    
		            Mail::AddDelayed(Base::GetConstant('global:to_email','mstarrr@gmail.com'),Language::GetMessage('contact_form'),
		            $sMessage);
		    
		            Base::$sText.=Language::GetText('Message is successfully sent.');
		            return;
		        }
		        else $sError = "Please, fill the required fields";
		    }
		    
// 			if (!Capcha::CheckMathematic()) 
// 				$sError = "Check capcha value";
// 			else {
// 				if (Base::$aRequest['data']['name'] && Base::$aRequest['data']['email']) {

// 					foreach (Base::$aRequest['data'] as $sKey => $aValue) {
// 						$sMessage.=Language::GetMessage($sKey).' : '.$aValue.'<br />';
// 					}

// 					Mail::AddDelayed(Base::GetConstant('global:to_email','mstarrr@gmail.com'),Language::GetMessage('contact_form'),
// 					$sMessage);

// 					Base::$sText.=Language::GetText('Message is successfully sent.');
// 					return;
// 				}
// 				else $sError = "Please, fill the required fields";
// 			}
		}

		$aData=array(
		'sHeader'=>"method=post",
		//'sTitle'=>"Static Contact Form",
		'sContent'=>Base::$tpl->fetch('contact_form/form_static.tpl'),
		'sSubmitButton'=>'Send',
		'sSubmitAction'=>'contact_form',
		'sError'=>$sError,
		'sTemplatePath' =>'form/main_reg.tpl',
		);
		$oForm=new Form($aData);

		Base::$tpl->assign('sContactForm',$oForm->getForm());
		Base::$sText.=Base::$tpl->fetch('contact_form/template.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function Call()
	{
		if (Base::$aRequest['is_post']) {
			if (!Capcha::CheckMathematic()) Form::ShowError("Check capcha value");
			else {
				if (Base::$aRequest['data']['name'] && Base::$aRequest['data']['phone']) {
					foreach (Base::$aRequest['data'] as $sKey => $aValue) {
						$sMessage.=$sKey.' : '.$aValue.'<br />';
					}

					Mail::AddDelayed(Base::GetConstant('global:to_email','mstarrr@gmail.com'),Language::GetMessage('contact_call'),
					$sMessage);

					Base::$sText.=Language::GetText('Message is successfully sent.');
					return;
				}
				else Form::ShowError("Please, fill the required fields");
			}
		}

		$aField['name']=array('title'=>'Ваше имя','type'=>'input','value'=>Base::$aRequest['data']['name'],'name'=>'data[name]','szir'=>1);
		$aField['email']=array('title'=>'Ваш e-mail','type'=>'input','value'=>Base::$aRequest['data']['email'],'name'=>'data[email]');
		$aField['phone']=array('title'=>'Номер вашего телефона','type'=>'input','value'=>Base::$aRequest['data']['phone'],'name'=>'data[phone]','id'=>'user_phone','placeholder'=>'(___)___ __ __','szir'=>1);
		$aField['time_call']=array('title'=>'Время звонка','type'=>'text','value'=>Language::GetMessage('c').':','add_to_td'=>array(
		    'time_from'=>array('type'=>'input','value'=>Base::$aRequest['data']['time_from'],'name'=>'data[time_from]'),
		    'time_to_text'=>array('type'=>'text','value'=>Language::GetMessage('до').':'),
		    'time_to'=>array('title'=>'Время звонка до','type'=>'input','value'=>Base::$aRequest['data']['time_to'],'name'=>'data[time_to]')
		));
		$aField['subject']=array('title'=>'Тема','type'=>'input','value'=>Base::$aRequest['data']['subject'],'name'=>'data[subject]');
// 		$oCpacha= new Capcha();
// 		$aField['capcha']=array('title'=>'Capcha field','type'=>'text','value'=>$oCpacha->GetMathematic('contact_form/mathematic.tpl'),'szir'=>1);
		$aField['description']=array('type'=>'textarea','name'=>'data[description]','value'=>Base::$aRequest['data']['description'],'colspan'=>2);
		$aField['call_text']=array('type'=>'text','value'=>Language::GetText('call_text'),'colspan'=>2);
		
		$aData=array(
		'sHeader'=>"method=post",
		'sTitle'=>"Contact Form Call",
		//'sContent'=>Base::$tpl->fetch('contact_form/form_call.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sSubmitButton'=>'Send',
		'sSubmitAction'=>'contact_form_call',
		'sError'=>$sError,
		);
		$oForm=new Form($aData);
		
		Base::$sText.=$oForm->getForm();
	}
	//-----------------------------------------------------------------------------------------------

}
?>