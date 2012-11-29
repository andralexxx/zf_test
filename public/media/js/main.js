$(document).ready(function() {
	initEditFirstname();
	initEditLastname();
	$('form#user-name-ajax-edit').focusout(function(){
		$(this).find('input').hide();
		$(this).submit();
	});
});

function submitform(id, firstname, lastname) {
	$.getJSON(
		'/index/ajaxedit/id/' + id,
		{ id: id, firstname: firstname, lastname: lastname},
		function(results) {
			if (results.result == 'success') {
				$('table#guestbook tr#id-' + id + ' td.firstname span').text(firstname).show();
				$('table#guestbook tr#id-' + id + ' td.lastname span').text(lastname).show();
				//window.location = '/';
			} else {
				$('#errors').html($.makeArray(results.errors).join('<br />'));
				$('table#guestbook tr#id-' + id + ' td.firstname span').text(firstname).show();
				$('table#guestbook tr#id-' + id + ' td.lastname span').text(lastname).show();
			}
		}
	);
}
function initEditFirstname(){
    $('table#guestbook td.firstname').click(function(){
		var id = $(this).parent('tr').attr('id'),
			f_name = $(this).find('span').text(),
			l_name = $(this).parent('tr').find('td.lastname span').text(),
			form = $('form#user-name-ajax-edit');
		id = id.substr(3);

		form.find('input').hide();
		$(this).find('span').hide();
		$(this).append(form);
		form.find('input#edit-id').val(id);
		form.find('input#edit-firstname').val(f_name).show().focus();
		form.find('input#edit-lastname').val(l_name);
	});
}
function initEditLastname(){
    $('table#guestbook td.lastname').click(function(){
		var id = $(this).parent('tr').attr('id'),
			f_name = $(this).parent('tr').find('td.firstname span').text(),
			l_name = $(this).find('span').text(),
			form = $('form#user-name-ajax-edit');
		id = id.substr(3);

		form.find('input').hide();
		$(this).find('span').hide();
		$(this).append(form);
		form.find('input#edit-id').val(id);
		form.find('input#edit-firstname').val(f_name);
		form.find('input#edit-lastname').val(l_name).show().focus();
	});
}