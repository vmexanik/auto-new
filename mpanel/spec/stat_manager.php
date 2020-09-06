<?

class AStatManager extends Admin {

	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName = 'cart_log';
		$this->sTablePrefix = 'c';
		$this->sAction = 'stat_manager';
		$this->sWinHead = Language::getDMessage('Vin money');
		$this->sPath = Language::GetDMessage('>>Logs >');
		$this->aCheckField = array ();
		$this->Admin ();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->UpdateProviderPrice();
		$this->PreIndex ();

		$aTableSqlParams = array();
		//
		$sOldIdUser = 0;
		if( isset(Base::$aRequest ['filter']) &&
		(Base::$aRequest ['filter'] == 'id_user') &&
		isset(Base::$aRequest ['filter_value']) &&
		(strlen(Base::$aRequest ['filter_value']) > 0)
		) {
			$sOldIdUser = Base::$aRequest['filter_value'];
			$iNewIdUser = Base::$db->getOne("SELECT `id` FROM `user` WHERE login = '" . Base::$aRequest ['filter_value'] . "'");
			if( $iNewIdUser ) {
				// $aTableSqlParams ['id_user'] = $iNewIdUser;
				$this->sSearchSQL .= " and c.id_user = '{$iNewIdUser}' ";
				Base::$aRequest ['filter_value'] = $iNewIdUser;
			}
		}

		//--------------------------------------------------
		$sJoin = '';
		$sWhere = '';
		$aSearchData = Base::$aRequest ['search'];
		if( !is_array($aSearchData) && (strlen($aSearchData) > 0) ) {
			parse_str($aSearchData, $aTmpData);
			if( is_array($aTmpData) && (count($aTmpData) > 0) ) {
				$aSearchData = $aTmpData;
				$aSearchFilterField = array('order_status', 'manager_login');
				foreach($aSearchFilterField as $sCurFilterName) {
					if( isset($aSearchData[ $sCurFilterName ]) && (strlen($aSearchData[ $sCurFilterName ]) > 0) ) {
						Base::$aRequest['filter'] = $sCurFilterName;
						Base::$aRequest['filter_value'] = $aSearchData[ $sCurFilterName ];
					}
				}
			} else {
				$aSearchData = array ();
			}
		}
		if(!isset($aSearchData['date_from'])) {
			$aSearchData['default_date_from'] = date( "d.m.Y", mktime(0, 0, 0, date("m")-6, date("d"), date("Y")) );
		}
		if(!isset($aSearchData['date_to'])) {
			$aSearchData['default_date_to']   = date( "d.m.Y", mktime(0, 0, 0, date("m"), date("d")+1, date("Y")) );
		}
		Base::$tpl->assign ( 'aSearchData', $aSearchData );
		$aOrderStatus = Base::$db->getAssoc("SELECT `id`, `code` FROM `cart_order_status` WHERE code NOT IN ('refused', 'pending')");
		if($aOrderStatus) {
			$aNewOrderStatus = array();
			$aNewOrderStatus[ '' ] = 'Не указан';
			if( is_array($aOrderStatus) && (count($aOrderStatus) > 0) ) {
				foreach($aOrderStatus as $k => $sStatus) {
					$aNewOrderStatus[ $sStatus ] = Language::GetDMessage( $sStatus );
				}
			}
			Base::$tpl->assign ( 'aOrderStatus',    $aNewOrderStatus );
			Base::$tpl->assign ( 'iStatusSelected', $aSearchData['order_status'] );
		}
		if ( is_array($aSearchData) && (count($aSearchData) > 0) ) {
			if ( isset($aSearchData['manager_login']) && (strlen($aSearchData['manager_login']) > 0)  ) {
				$sWhere .= " and c.login_vin_request = '" . $aSearchData['manager_login'] . "' ";
				// $this->sSearchSQL .= " and c.login_vin_request = '" . $aSearchData['manager_login'] . "' ";
			}
			if ( isset($aSearchData['order_status']) && (strlen($aSearchData['order_status']) > 0) ) {
				$sJoin .= " inner join cart_log cl on (c.id = cl.id_cart and cl.order_status = '{$aSearchData['order_status']}')";
			} else {
				$sJoin .= " inner join cart_log cl on (c.id = cl.id_cart)";
			}
			$aTableSqlParams['join'] = $sJoin;
			if ( isset($aSearchData['date_from']) && (strlen($aSearchData['date_from']) > 0) ) {
				$sWhere .= " and cl.post >= '" . strtotime($aSearchData['date_from']) . "' ";
				$this->sSearchSQL .= " and cl.post >= '" . strtotime($aSearchData['date_from']) . "' ";
			}
			if ( isset($aSearchData['date_to']) && (strlen($aSearchData['date_to']) > 0) ) {
				$sWhere .= " and cl.post <= '" . strtotime($aSearchData['date_to']) . "' ";
				$this->sSearchSQL .= " and cl.post <= '" . strtotime($aSearchData['date_to']) . "' ";
			}
		}
		$sQuery = "select u.login as customer_login, c. *
						,cl.post_date as cart_log_post_date
		             from cart c
		               inner join user_customer uc on c.id_user = uc.id_user
		               inner join user u on uc.id_user = u.id
		                  ".$sJoin."
		            where c.type_ = 'order'
		               and c.order_status not in ('refused', 'pending')
		               and price > 0
		               and provider_price > 0
		               and c.login_vin_request != ''
		                  ".$sWhere."
		               group by c.id";
		$sSumSql = "SELECT SUM(s.number * (s.price - s.provider_price)) AS sum FROM (" . $sQuery . ") s";
		$sSum    = Base::$db->getOne($sSumSql);
		if( is_numeric($sSum) ) {
			$sSum = (double) $sSum;
			Base::$tpl->assign ( 'dTotalSum', $sSum );
		} else {
			Base::$tpl->assign ( 'dTotalSum', 0 );
		}
		//
		Base::$sText .= $this->SearchForm ();
		//--------------------------------------------------

		$oTable = new Table();
		$oTable->aColumn=array();
		$oTable->aColumn['id']=array('sTitle' => 'Id','sOrder' => 'c.id' );
		$oTable->aColumn['item_code']= array ( 'sTitle' => 'Item Code',      'sOrder' => 'c.item_code' );
		$oTable->aColumn['c.number*c.price'] = array ( 'sTitle' => 'Price',          'sOrder' => 'c.number*c.price' );
		$oTable->aColumn['manager_login']=array('sTitle'=>'Manager Login','sOrder'=>'c.login_vin_request','sMethod'=>'exact');
		$oTable->aColumn['id_user']= array ( 'sTitle' => 'Customer Login', 'sOrder' => 'c.id_user' );
		$oTable->aColumn['post']= array ( 'sTitle' => 'Post Date',      'sOrder' => 'c.post' );

		$oTable->aColumn['order_status']= array ( 'sTitle' => 'Order Status',   'sOrder' => 'c.order_status' );


		$this->SetDefaultTable ( $oTable );
		$oTable->sSql=$sQuery;

		Base::$aRequest ['filter_value'] = $sOldIdUser;
		Base::$sText .= $oTable->getTable ();

		$this->AfterIndex ();
	}
	//-----------------------------------------------------------------------------------------------
	public function SearchClear() {
		if( isset(Base::$aRequest['search']) ) {
			unset(Base::$aRequest['search']);
		}

		$this->Index();
	}
	//-----------------------------------------------------------------------------------------------
	public function UpdateProviderPrice()
	{
		Db::Execute("update cart set provider_price=price_original
				where provider_price in (0, NULL) and price_original>0 and order_status in ('store', 'end')");
	}
	//-----------------------------------------------------------------------------------------------

}

?>