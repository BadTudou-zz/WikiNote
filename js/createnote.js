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
		$('#stype1').empty();
		$.each(json,function(index, el) {
			$('#stype1').append('<option value="1">'+el['title']+'</option>');
			console.log('title'+el['title']);
		});
	})
	.error(function() 
	{
		/* Act on the event */
	})
};

function AddNote(title)
{
	$.ajax({
		url: '../php/user_web.php',
		type: 'POST',
		dataType: 'JSON',
		data: {action: 'addtype', title:title}
	})
	.done(function(json) 
	{
		if (json.stateCode == 0)
		{
			alert(json.msgText);
			location.href = '../html/index.html?login=true';
		}
	})
	.fail(function() {
		console.log("error");
	})
}
$(document).ready(function()
{
	console.log('create note');
	GetTypes(Array());

	$('.select').change(function(event) 
	{
		console.log('change');
	});

	$('#note').submit(function(event) {
		/* Act on the event */
		var title = $('#note_title').val();
		AddNote(title);
		console.log('submit');
		return false
	});
});