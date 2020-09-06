<?

class AAccountLog extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * On search action return search result form
	 *
	 * @param String $sSearchLoginField - name of the login field in the search table
	 * @param array $aData - previously set where parameter
	 * @param boolean $bIsUserAccount - Need for get data by account log. $bIsUserAccount==true for user_account_log.
	 * @return  assign to Base::$sText search result
	 */
	protected function GetResultByFindLogin($sSearchLoginField='login', $aData= array(), $bIsUserAccount= false)
	{
		if ($this->aSearch [$sSearchLoginField]){
			$aData['where'] = $this->sSearchSQL;

			$bIsOne = false;
			if ($bIsUserAccount){
				$aSearchResult = Base::$db->GetAll(Base::GetSql('UserAccountLog', $aData));
				$bIsOne = $aSearchResult && count($aSearchResult)>0 ? true : false;
				if ($aSearchResult) {
					$iId = $aSearchResult[0]['id_user'];
					foreach ($aSearchResult as $key => $aValue){
						if ($iId != $aValue['id_user']){
							$bIsOne = false;
							break;
						}
					}
				}
			}

			$aFind = array();
			if ($this->aSearch['user_type'] == 'customer') {
				if ($bIsOne){
					$aFind = Base::$db->GetRow (Base::GetSql ( 'Customer',array ('has_debt_log'=>1, 'login' => $aSearchResult[0]['user'] ) ) );
				}elseif (count($aSearchResult) == 0){
					$aFind = Base::$db->GetRow (Base::GetSql ( 'Customer',array ('has_debt_log'=>1, 'login' => $this->aSearch [$sSearchLoginField]) ) );
				}
			}
			else {
				if ($bIsOne){
					$aFind = Base::$db->GetRow (Base::GetSql ( 'Provider',array ('has_debt_log'=>1, 'login' => $aSearchResult[0]['user'] ) ) );
				}elseif (count($aSearchResult) == 0){
					$aFind = Base::$db->GetRow (Base::GetSql ( 'Provider',array ('has_debt_log'=>1, 'login' => $this->aSearch [$sSearchLoginField]) ) );
				}
			}
			if($aFind){
			$aFind['amount_debt_end']=Db::GetOne("select sum(if(ld.is_payed=0,ld.amount,0)) amount_debt_end
				from log_debt as ld inner join cart c on c.id=ld.custom_id and c.order_status='end' where ld.id_user='".$aFind['id_user']."'");
			if(!$aFind['amount_debt_end'])$aFind['amount_debt_end']=0;
			$aFind['amount_store']=Db::GetOne("select sum(price*number) 
				from cart where order_status='store' and id_user='".$aFind['id_user']."'");
			}
			if(!$aFind['amount_store'])$aFind['amount_store']=0;
			Base::$tpl->assign( 'aSearchResult', $aFind);
			Base::$sText .= Base::$tpl->fetch('mpanel/user_account_log/form_search_result.tpl');
		}
	}
	//-----------------------------------------------------------------------------------------------
}
?>