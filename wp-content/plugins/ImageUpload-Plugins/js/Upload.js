jQuery(document).ready(function($) {
    
      jQuery('#form').on('submit',function(e) {
                        
            e.preventDefault();
            var formData = new FormData(this);
             alert('hi');   
            console.log(formData);
                jQuery.ajax({
                    type: "POST",     
                    url: "http://localhost/coete/wp-content/plugins/ImageUpload-Plugins/imgRead.php",
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