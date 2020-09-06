/**
* @author Alexander Belogura
*/
var CommentTree=function (data) {
};

CommentTree.prototype.NewComment = function(parent_id)
{
	var parentIdField = document.getElementById('parent_id');
	var contentField = document.getElementById('content');
	parentIdField.value = parent_id;
	document.getElementById('content').focus();
}
var ct=new CommentTree();
