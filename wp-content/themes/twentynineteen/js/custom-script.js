$(document).ready(function() {

	/**
	 * Show / Hide branch form based on selection
         *
	 */
	$('#mepr_would_you_like_to_setup_branches').on('change',function(){
            var opt = $(this).val();
            
            if (opt === 'yes') {
                $('.branch').css('display','block');
            } else {
                $('.branch').css('display','none');
            }
        });

        /**
         * Show / hide trainer id field based on selection
         *
         */
         $('.training').on('change',function(){
            var opt = $(this).val();
            
            if (opt === 'Yes') {
                $('.show_training').css('display','block');
            } else {
                $('.show_training').css('display','none');
            }
         });

         /**
         * Show / hide evaluator id field based on selection
         *
         */
         $('.evaluation').on('change',function(){
            var opt = $(this).val();
            
            if (opt === 'Yes') {
                $('.show_evaluation').css('display','block');
            } else {
                $('.show_evaluation').css('display','none');
            }
         });

         /**
         * Show / hide crane type forms
         *
         */
         $('#mepr_crane_type').on('change',function(){
            var opt = $(this).val();
            
            if (opt === 'mobile') {
                $('.mobileCraneType').css('display','block');
                $('.towerCraneType').css('display','none');
            } else {
                $('.mobileCraneType').css('display','none');
                $('.towerCraneType').css('display','block');
            }
         });

        /* validate individual signup form */
        $('#individual').validate({
            ignore: [],
            errorElement: "span",
            errorClass: "error",
            rules: {
                mepr_firstname: {
                    required: true,
                },
                mepr_lastname: {
                    required: true,
                },
                user_email: {
                    required: true,
                    email: true
                },
                mepr_zip_postal_code: {
                    number: true,
                },
                mepr_cell_phone_with_area_code: {
                    minlength: 10,
                    number: true,
                },
                mepr_user_password: {
                    required: true,
                    minlength: 6
                },
                mepr_user_password_confirm: {
                    required: true,
                    minlength: 6,
                    equalTo: '#mepr_user_password'
                }
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass(errorClass);
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass(errorClass);
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
            },

            errorPlacement: function(error, element) {
                if(element.parent('.input-group').length) {
                   error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }

            }
        });

         /* validate company signup form */
         $('#company').validate({
            ignore: [],
            errorElement: "span",
            errorClass: "error",
            rules: {
                user_email: {
                    required: true,
                    email: true
                },
                mepr_zip_postal_code: {
                    number: true,
                },
                mepr_phone: {
                    minlength: 10,
                    number: true,
                },
                mepr_primary_administrator_email: {
                    email: true
                },
                mepr_secondary_administrator_email: {
                    email: true
                },
                mepr_user_password: {
                    required: true,
                    minlength: 6
                },
                mepr_user_password_confirm: {
                    required: true,
                    minlength: 6,
                    equalTo: '#mepr_user_password'
                }
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass(errorClass);
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass(errorClass);
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
            },

            errorPlacement: function(error, element) {
                if(element.parent('.input-group').length) {
                   error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }

            }
        });

         /* validate operator signup form */
         $('#operator').validate({
            ignore: [],
            errorElement: "span",
            errorClass: "error",
            rules: {
                mepr_firstname: {
                    required: true,
                },
                mepr_lastname: {
                    required: true,
                },
                user_email: {
                    required: true,
                    email: true
                },
                mepr_user_password: {
                    required: true,
                    minlength: 6
                },
                mepr_user_password_confirm: {
                    required: true,
                    minlength: 6,
                    equalTo: '#mepr_user_password'
                }
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass(errorClass);
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass(errorClass);
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
            },

            errorPlacement: function(error, element) {
                if(element.parent('.input-group').length) {
                   error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }

            }
         });

         $('#operator_roster').DataTable();
});
