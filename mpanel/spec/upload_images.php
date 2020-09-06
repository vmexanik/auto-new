<?

/**
 * @author Mikhail Starovoyt
 */

class AUploadImages extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sAction='upload_images';
		$this->sWinHead=Language::getDMessage('upload_images');
		$this->sPath=Language::GetDMessage('>>Content >');
		$this->aCheckField=array();
		//$this->aFCKEditors = array ('description');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
}
?>