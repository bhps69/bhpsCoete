jQuery(document).ready(function(){
	jQuery("*").delegate("#imgUpload","change",function(e){
		alert('in upload1.js');
		e.preventDefault();
		if('#imgUpload').val()){
			alert('in upload1.js');
	$('#loader-icon').show();
			$('#targetLayer').hide();
			$(this).ajaxSubmit({
				target:'#targetLayer',
				beforeSubmit:function(){
					$('.progress-bar').width('0%');
				},
				uploadProgress:function(event, total, position,perentageComplete){
					$('.progress-bar').animate({
						width:percentageComplete+'%'
					},{
						duration:1000
					});
				},
				success: function(){
					$('#loader-icon').hide();
					$('#targetLayer').show();
				},
				resetForm: true
			});
		}
	)};
)};