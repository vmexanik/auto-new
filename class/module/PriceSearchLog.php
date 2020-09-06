<?
/**
 * @author Mikhail Kuleshov
 */
class PriceSearchLog extends Base
{

	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
		Repository::InitDatabase('price_search_log', false);
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
	    $aField['cat_name']=array('title'=>'Make','type'=>'input','value'=>Base::$aRequest['search']['cat_name'],'name'=>'search[cat_name]');
	    $aField['code']=array('title'=>'Code','type'=>'input','value'=>Base::$aRequest['search']['code'],'name'=>'search[code]');
	    $aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("1.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
		$aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		$aData=array(
	        'sHeader'=>"method=get",
	        //'sContent'=>Base::$tpl->fetch('price_search_log/form_price_search_log_search.tpl'),
	        'aField'=>$aField,
	        'bType'=>'generate',
		    'sGenerateTpl'=>'form/index_search.tpl',
	        'sSubmitButton'=>'Search',
	        'sSubmitAction'=>'price_search_log',
	        'sReturnButton'=>'Clear',
	        'bIsPost'=>0,
	        'sWidth'=>'30%',
	        'sError'=>$sError,
	    );
	    $oForm=new Form($aData);
	    
	    Base::$sText .= $oForm->getForm();
	    
		Base::$oContent->AddCrumb(Language::GetMessage('price_search_log'),'');
		// --- search ---
		if (Base::$aRequest['search']['cat_name']) $sWhere.=" and psl.cat_name like '%".Base::$aRequest['search']['cat_name']."%'";
		if (Base::$aRequest['search']['code'])
		    $sWhere.=" and psl.code ='".Base::$aRequest['search']['code']."'";
		if (Base::$aRequest['search']['date']) {
		    $sWhere.=" and (psl.post_date >= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
	            and psl.post_date <= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."') ";
		}
		// --- search ---
		$oTable=new Table();
		$oTable->iRowPerPage=20;
		$oTable->iStartStep=1;
		if (Auth::$aUser['id'])
		{
			$oTable->sSql="select psl.*	from price_search_log as psl where psl.id_user='".Auth::$aUser['id']."'".$sWhere;
		}
		else
		{
			$oTable->sSql="select psl.*	from price_search_log as psl where psl.id_session='".session_id()."'".$sWhere;
		}
		$oTable->aOrdered="order by psl.post_date desc";
		$oTable->aColumn=array(
		'cat_name'=>array('sTitle'=>'Make'),
		'code'=>array('sTitle'=>'Code'),
		'post'=>array('sTitle'=>'Date'),
		'action'=>array('sTitle'=>''),
		);
		$oTable->sDataTemplate='price_search_log/row_price_search_log.tpl';

		Base::$sText.=$oTable->getTable("Price Search Log",'Price Search Log');
	}
	//-----------------------------------------------------------------------------------------------
	public function AddSearch($sPref=false,$sCode=false)
	{
		if (!Base::GetConstant('price_search_log:is_available',1)) return;
		if (!Base::$aRequest['code']) return;
		$aLog=array(
		'id_user'=>Auth::$aUser['id']
		,'pref'=>$sPref?$sPref:Base::$aRequest['pref']
		,'code'=>$sCode?$sCode:Base::$aRequest['code']
		);
		if (!Auth::$aUser['id'])
		{
			$aLog['id_session']=session_id();
		}
		if (Base::$aRequest['pref'])
		{
			$sCatName=Db::GetOne("select name from cat where pref='".Base::$aRequest['pref']."'");
			if ($sCatName)
			{
				$aLog['cat_name']=$sCatName;
			}
		}

		Db::AutoExecute('price_search_log', $aLog, 'INSERT');

	}
	//-----------------------------------------------------------------------------------------------

}
?>