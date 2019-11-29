jQuery(document).ready(function($) {

	$("#custom-mails-template-format").change(function() {
		var location = window.location.href;
		var selected = $("#custom-mails-template-format").val();
		if (selected == 'text') {
			location += '&format=text';
		} else {
			location += '&format=html';
		}
		window.location.href = location;
	});

	$("#custom-mails-reset").click(function() {
		if (confirm("WARNING! All changes will be lost! Are you sure?")) {
			var location = window.location.href;
			location += "&reset=true";
			window.location.href = location;
		}
	});

});

