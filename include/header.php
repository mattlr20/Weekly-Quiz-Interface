<!DOCTYPE html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cyber Security Program</title>
<link rel="stylesheet" href="css/cyber.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
$(function() {
    $('.selectpicker').selectpicker();
    $('.datepicker').datepicker({});
    $( "input[class='date']" ).datepicker({
		showAnim: '',
		showOn: "button",      
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		showOtherMonths: true,
		selectOtherMonths: true
	});
});
function fNAV(element) {
    document.getElementById('CurrentStep').value = element.value;
}
function fScroll() {
    var scrolly = typeof window.pageYOffset != 'undefined' ? window.pageYOffset : document.documentElement.scrollTop;
    document.getElementById("scrolly").value = scrolly;
}
</script>
</head>
<body>
