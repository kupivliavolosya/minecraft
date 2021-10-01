$(document).ready(function(){
	$("#sel-servers").change(function(){
		var key_server = $(this).val();
		$("#view-server:not(.class-"+key_server+")").hide();
		$("#view-server.class-"+key_server+"").show();
	});
});