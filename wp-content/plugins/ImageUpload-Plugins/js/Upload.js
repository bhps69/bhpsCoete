jQuery(document).ready(function($) {
	var url;
		
      jQuery('#form').on('submit',function(e) {
                        
            e.preventDefault();
			
            var formData = new FormData(this);
			var getUrl = window.location;
			alert(getUrl.pathname.split('/')[1]);
var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
             alert('hi');
alert(baseUrl+"/wp-content/plugins/ImageUpload-Plugins/imgRead.php");			 
$path = baseUrl+"/wp-content/plugins/ImageUpload-Plugins/imgRead.php";
            console.log(formData);
                jQuery.ajax({
                    type: "POST",     
                    url: $path,
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        console.log(data);
						alert("success");
                        jQuery("#rightPanel-div").html(data);
						
                    }
                });
                
            });
});