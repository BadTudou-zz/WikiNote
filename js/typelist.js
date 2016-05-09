/*
    Copyright © JiangTaoTao, 2016
    All rights reserved

    Name    :   userlist.js
    By      :   BadTudu
    Date    :   2016年5月06日11:55:05
    Note    :   WikiNote主界面
*/

/**
 * [移除指定节点的所有子节点]
 * @param {[type]} node [节点]
 */
function RemoveAllChilds(node)
{
	while (node.children.length != 0)
     {
     	for (var i=0; i < node.children.length; i++)
     	{
    		$('#typeTree').tree('removeNode', node.children[i]);
		}
     }
}

function GetTypes(arType, node)
{
	console.log(arType.join('/'));
	$.ajax(
	{
		url: '../php/manage_web.php',
		type: 'POST',
		dataType: 'JSON',
		data: {action: 'gettypes', type:arType.join('/')}
	})
	.done(function(json) 
	{
		$.each(json,function(index, el) {
			console.log('title'+el['title']);
		});
		if (node != null)
		{
			SetSubTree(json, node);
		}
		else
		{

		InitTree(json);
		}
		return json;
		$('#userlist-uID').empty();
		$('#userlist-uNickName').empty();
	})
	.fail(function() 
	{
		console.log("error");
	})
}

function ShowSubDiv(pos)
{
	$('#div_add').hide();
	$('#div_search').hide();
	$('#div_del').hide();
	switch(pos)
	{
		case 1:
			$('#div_add').show();
			break;
		case 2:
			$('#div_search').show();
			break;
		case 3:
			$('#div_del').show();
			break;
	}
}
function SetSubTree(json, node)
{
	$.each(json, function(index, el) 
	{
		$('#typeTree').tree(
    	'appendNode',
    	{
        	label: el['title'],
	    },
    	node
		);	
	});
	

}
function InitTree(json)
{
	console.log('inittree');
	var a = new Array();
		$.each(json, function(idx, obj)
		{
			a.push(obj['title']);
		})
		$('#typeTree').tree(
		{
    		data: a,
    		autoOpen: true,
    		dragAndDrop: true
		});
/*	$.each(json, function(index, el) 
	{
		console.log(el['title']);
	});*/
	
}


$(document).ready(function()
{
	console.log('type list');
	GetTypes(Array());
	ShowSubDiv(0);
	$('#allUser').click(function(event) 
	{
		ShowSubDiv(0);
		GetUsers(0, 100);
	});
	$('#addUser').click(function(event) {
		/* Act on the event */
		if ($("#div_add").is(":hidden") )
		{
			ShowSubDiv(1);
		}
		else
		{
			ShowSubDiv(0);
		}
	});
	$('#searchUser').click(function(event) {
		/* Act on the event */
		if ($("#div_search").is(":hidden") )
		{
			ShowSubDiv(2);
		}
		else
		{
			ShowSubDiv(0);
		}
	});
	$('#delUser').click(function(event) {
		/* Act on the event */
		if ($("#div_del").is(":hidden") )
		{
			ShowSubDiv(3);
		}
		else
		{
			ShowSubDiv(0);
		}
	});

	$('#typeTree').bind
	(
    	'tree.click',
    	function(event)
    	{
        	var node = event.node;
        	var path = new Array();
        	var tmp = node;

        	while(tmp.name != '')
        	{
        		path.push(tmp.name);
        		tmp = tmp.parent;
        	}

        	path.reverse();
        	RemoveAllChilds(node);
        	GetTypes(path, node);
        	var dirname=path.join('');
        	console.log(dirname);
     });
	/*$('#addnew').click(function(event) 
	{
		var user = $('#input_user').val();
		var pwd = $('#input_pwd').val();
		console.log('addnew');
		if (user == '' || pwd == '')
		{
			return false;
		}
		AddUser(user, pwd);
	});

	$('#delete').click(function(event) 
	{
		var id = $('#input_id').val();
		console.log('uid'+id);
		DelUser(id);
	});

	$('#search').click(function(event) 
	{
		var nickname = $('#input_nickname').val();
		console.log('nickname');
		GetUsersByNickname(nickname);
	});*/
});
