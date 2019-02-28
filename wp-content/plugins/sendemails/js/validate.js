jQuery(document).ready(function($) {
    jQuery(document).ready(function() { 
        // DataTable
        var table = jQuery('#tbl').DataTable();
     
        // Apply the search
        table.columns().every( function () {
            var that = this;
     
            jQuery( 'input', this.footer() ).on( 'keyup change', function () {
                if ( that.search() !== this.value ) {
                    that
                        .search( this.value )
                        .draw();
                }
            } );
        } );
    } );
        jQuery('#tbl').DataTable();
});

jQuery(document).ready(function($) {
 

  jQuery("button").click(function(){
     // alert("hi");
      jQuery('#formid').validate({
        rules:{
            "nam":{
                required:true
            },
            "phone":{
                required:true,
                minlength:10,
                digits:true
            },
            "msg":{
                required:true 
            }
        },       
        messages:{
            required: "this field is required",
            minlength: "this field must contain at least {0} characters",
            digits: "this field can only contain numbers"
        }

 });
}); 
 });
