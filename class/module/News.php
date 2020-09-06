<?
/**
 * @author Mikhail Strovoyt
 * @version 4.5.2
 */

class News extends Base
{
	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
		Repository::InitDatabase('sms_delayed');
		Base::$bXajaxPresent = true;
		Base::$aData['template']['bWidthLimit']=true;
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		Base::$oContent->AddCrumb(Language::GetMessage('news'),'');
		//require_once(SERVER_PATH.'/class/module/CustomTable.php');
		$oTable=new Table();
		$oTable->sSql=Base::$language->getLocalizedAll(array(
		'table'=>'news',
		'where'=>" and section='site'and visible=1",
		),true);
		$oTable->aOrdered="order by post_date desc, id desc";
		$oTable->aColumn=array(
		'full'=>array('sTitle'=>'Site News','sWidth'=>'100%'),
		);
		$oTable->sDataTemplate='news/row_news.tpl';
		$oTable->sTemplateName='news/table_template.tpl';
		$oTable->bStepperVisible=true;
		$oTable->bHeaderVisible=false;
		$oTable->iRowPerPage=10;
		Base::$sText.=$oTable->getTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function Preview()
	{
		if (!Base::$aRequest['xajaxr']) {
			Base::$oContent->AddCrumb(Language::GetMessage('news'),'/pages/news');
			$aNews=Base::$language->getLocalizedRow(array(
			'table'=>'news',
			'where'=>" and id='".Base::$aRequest['id']."' and visible='1'",
			));
			Base::$oContent->AddCrumb($aNews['short'],'');
			Base::$tpl->assign('aNewsRow',$aNews);
			Base::$aData['template']['sPageTitle'] = $aNews['title'];
			Base::$aData['template']['sPageDescription'] = $aNews['page_description'];
			Base::$aData['template']['sPageKeyword'] = $aNews['page_keyword'];
			Base::$sText.=Base::$tpl->fetch("news/preview.tpl");
		}
		//$this->CommentList();
	}
	//-----------------------------------------------------------------------------------------------
	public function CommentList()
	{

		$oComment=new CommentTree();
		if (Base::$aRequest['xajaxr'])
		Base::$oResponse->addAssign('comment_div','innerHTML',$oComment->GetCommentList('news',Base::$aRequest['id'],true));
		else Base::$sText.=$oComment->GetCommentListTree('news',Base::$aRequest['id']);
	}
	//-----------------------------------------------------------------------------------------------

}
?>