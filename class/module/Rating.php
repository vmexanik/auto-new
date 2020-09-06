<?

/**
 * @author Oleg Makienko
 * @author Mikhail Starovoyt
 *
 */

class Rating extends Base
{
	//-----------------------------------------------------------------------------------------------
	public function __contruct()
	{
		Base::$aData['template']['bWidthLimit']=true;
	}
	//-----------------------------------------------------------------------------------------------
	public function Change($sSection,$iRefId,$iNumRating)
	{
		switch($sSection) {
			case 'user_quality':
				$aData = array(
				'num_rating_quality'=>$iNumRating,
				);
				Db::AutoExecute('user_customer',$aData,'UPDATE', "id_user='".$iRefId."'");
				break;
							
			case 'store_customer':
				$aData = array(
				'num_rating'=>$iNumRating,
				);
				Db::AutoExecute('user_customer',$aData,'UPDATE', "id_user='".$iRefId."'");
				break;

			case 'provider':
				$aData = array(
				'num_rating'=>$iNumRating,
				);
				Db::AutoExecute('user_provider',$aData,'UPDATE', "id_user='".$iRefId."'");
				break;
		}

		if (!Auth::$aUser['id']) Auth::$aUser['id']=-1;

		$aData = array(
		'num_rating'=>$iNumRating,
		'section'=>$sSection,
		'ref_id'=>$iRefId,
		'id_user_manager'=>Auth::$aUser['id'],
		);
		Db::AutoExecute('rating_log',$aData);
	}
	//-----------------------------------------------------------------------------------------------

}
?>