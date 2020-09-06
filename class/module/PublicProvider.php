<?
/**
 * @author Kristina Kiparoidze
 *
 */
class PublicProvider extends Base {
	public function __construct() {
	}
	// -----------------------------------------------------------------------------------------------
	public function Add() {
		if (Base::$aRequest ['is_post']) {
			
			$sQuery = "insert into user(login, password, email, visible) values
		('" . Base::$aRequest ['data'] ['login'] = 'm' . Auth::GenerateLogin () . "'
		,'" . Base::$aRequest ['data'] ['password'] = Auth::GeneratePassword () . "'
		,'" . Base::$aRequest ['data'] ['email'] . "','0')";
			Db::Execute ( $sQuery );
			$sIdUser = Db::InsertId ();
			
			if ($_FILES ['price'] ['tmp_name']) {
				$uploaddir = '/imgbank/public_provider/';
				if (! is_dir ( SERVER_PATH . $uploaddir )) {
					mkdir ( SERVER_PATH . $uploaddir, 0777 );
				}
				// 
				$aFilePart = pathinfo ( $_FILES ['price'] ['name'] );
				$sFullPath = $uploaddir . $sIdUser . "_" . $aFilePart ['basename'];
				$file = SERVER_PATH . $sFullPath;
			}	
				//if (move_uploaded_file ( $_FILES ['price'] ['tmp_name'], $file )) {
					$sQuery = "insert into user_provider(name, city, phone, price_url, id_user, is_public) values
				('" . Base::$aRequest ['data'] ['name'] . "','" . Base::$aRequest ['data'] ['city'] . "'
				,'" . Base::$aRequest ['data'] ['phone'] . "','" . $sFullPath . "','" . $sIdUser . "', '1')";
					Db::Execute ( $sQuery );
					Base::$sText .= Language::GetText ( 'Заявка подана и будет рассмотрена' );
				//}
			
		} 

		else {
		    $aField['name']=array('title'=>'Name','type'=>'input','value'=>Base::$aRequest['name'],'name'=>'data[name]','szir'=>1);
		    $aField['city']=array('title'=>'City','type'=>'input','value'=>Base::$aRequest['city'],'name'=>'data[city]','szir'=>1);
		    $aField['email']=array('title'=>'Email','type'=>'input','value'=>Base::$aRequest['email'],'name'=>'data[email]','szir'=>1);
		    $aField['phone']=array('title'=>'Phone','type'=>'input','value'=>Auth::$aUser['name']?Auth::$aUser['phone']:Base::$aRequest['data']['phone'],'name'=>'data[phone]','id'=>'user_phone','placeholder'=>'(___)___ __ __');
		    $aField['add_price']=array('title'=>'Add price','type'=>'file','name'=>'price');
		    
			$aData = array (
					'sHeader' => "method=post enctype='multipart/form-data'",
					'sTitle' => "Add public provider",
					//'sContent' => Base::$tpl->fetch ( 'public_provider/form_add_public_provider.tpl' ),
			        'aField'=>$aField,
			        'bType'=>'generate',
					'sSubmitButton' => 'Add provider',
					'sSubmitAction' => 'public_provider_add',
					'sError' => $sError 
			);
			$oForm = new Form ( $aData );
			Base::$sText .= $oForm->getForm ();
		}
	}
	public function Show() {
		Auth::NeedAuth('customer');
			$sQuery = "Select up.*, u.email 
					FROM user_provider AS up 
					INNER JOIN user AS u ON up.id_user=u.id 
					where 1=1 and up.is_public = 1 and u.visible = 1";
			//Debug::PrintPre($sQuery,false);
			$oTable=new Table();
			$oTable->sSql=$sQuery;

			$oTable->aColumn=array(
			'name'=>array('sTitle'=>'name'),
			'city'=>array('sTitle'=>'city'),
			'phone'=>array('sTitle'=>'phone'),
			'email'=>array('sTitle'=>'email'),
			'price_url'=>array('sTitle'=>'price'),
			);
			if(!Auth::$aUser['is_provider_paid'])
			$oTable->aCallback=array($this,'CallParseProvider');
			$oTable->sDataTemplate="public_provider/row_public_provider.tpl";
			$oTable->iRowPerPage=500;
			$oTable->bStepperVisible=false;

			Base::$sText.=$oTable->getTable("List providers");
			Base::$sText.=Base::$tpl->fetch('public_provider/download_excel.tpl');
	
	}
	
	public function CallParseProvider(&$aItem)
	{
		
		foreach($aItem as $sKey => $aValue){

			$aValue[name] = substr_replace($aValue[name],'**',2);
			$aValue[phone] = substr_replace($aValue[phone],'**',2);
			$aValue[email] = substr_replace($aValue[email],'**',2);
			$aItem[$sKey] = $aValue;
		}
	}
	
	public function CreateExcel()
	{
		$oExcel = new Excel();
		$aHeader=array(
				'A'=>array("value"=>'name', 'autosize'=>true),
				'B'=>array("value"=>'city', 'autosize'=>true),
				'C'=>array("value"=>'phone', 'autosize'=>true),
				'D'=>array("value"=>'email', 'autosize'=>true),
				'E'=>array("value"=>'price'),
		);
		
		$oExcel->SetHeaderValue($aHeader,1);
		$oExcel->SetAutoSize($aHeader);
		$oExcel->DuplicateStyleArray("A1:E1");
		
		$sSql = "Select up.*, u.email
					FROM user_provider AS up
					INNER JOIN user AS u ON up.id_user=u.id
					where 1=1 and up.is_public = 1 and u.visible = 1";
		$aData =Db::GetAll($sSql);
		if ($aData) {
			$i=$j=2;

			if(!Auth::$aUser['is_provider_paid'])
			$this->CallParseProvider($aData);
			foreach ($aData as $aValue) {
				$oExcel->setCellValue('A'.$i, $aValue['name']);
				$oExcel->setCellValue('B'.$i, $aValue['city']);
				$oExcel->setCellValue('C'.$i, $aValue['phone']);
				$oExcel->setCellValue('D'.$i, $aValue['email']);
				$oExcel->setCellValue('E'.$i, $aValue['price_url']);
				$i++;
			}
			$sFileName='list_providers.xls';
			$sFullFileName='/imgbank/temp_upload/attachment/'.$sFileName;
			$oExcel->WriterExcel5(SERVER_PATH.$sFullFileName);

			Base::Redirect($sFullFileName);
			
		}
}
}