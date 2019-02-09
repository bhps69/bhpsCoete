
var $j = jQuery.noConflict();

$j(document).ready(function() {
        var APP_URL = 'http://dev.coete.co';
	"use strict";
        $j('.modal').appendTo("body");
        /**
	 * Show / Hide branch form based on selection
         *
	 */ 

	$j('#mepr_would_you_like_to_setup_branches').on('change',function(){
            var opt = $j(this).val();
            
            if (opt === 'yes') {
                $j('.branch').css('display','block');
            } else {
                $j('.branch').css('display','none');
            }
        });

        /**
         * Show / hide trainer id field based on selection
         *
         */
         $j('.training').on('change',function(){
            var opt = $j(this).val();
            
            if (opt === 'Yes') {
                $j('.show_training').css('display','block');
            } else {
                $j('.show_training').css('display','none');
            }
         });

         /**
         * Show / hide evaluator id field based on selection
         *
         */
         $j('.evaluation').on('change',function(){
            var opt = $j(this).val();
            
            if (opt === 'Yes') {
                $j('.show_evaluation').css('display','block');
            } else {
                $j('.show_evaluation').css('display','none');
            }
         });

         /**
         * Show / hide crane type forms
         *
         */
         $j('#mepr_crane_type').on('change',function(){
            
            var opt = $j(this).val();
            
            if (opt === 'mobile') {
                $j('.mobileCrane').css('display','block');
                $j('.towerCrane').css('display','none');
                $j('.mobileCraneType').css('display','block');
                $j('.towerCraneType').css('display','none');
            } else {
                $j('.mobileCrane').css('display','none');
                $j('.towerCrane').css('display','block');
                $j('.mobileCraneType').css('display','none');
                $j('.towerCraneType').css('display','block');
            }
         });

        /* validate individual signup form */
        $j('#individual').validate({
            ignore: [],
            errorElement: "span",
            errorClass: "alert-danger",
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
            $j(element).addClass(errorClass);
                $j(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            unhighlight: function (element, errorClass, validClass) {
            $j(element).removeClass(errorClass);
                $j(element).closest('.form-group').removeClass('has-error').addClass('has-success');
            },

            errorPlacement: function(error, element) {
                if(element.parent('.input-group').length) {
                   error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }

            }
        });
         
         $j('#operator_roster').dataTable();
         $j('#assoc').dataTable();

         $j('.getOperator').on('click',function(){
            $j('#operatorId').val($j(this).attr('data-value'));
            $j('#trainerId').val($j(this).attr('data-id'));
         });
         $j('#saveComment').on('click',function(){
            var comment = $j('#commenttext').val();
            var operatorId = $j('#operatorId').val();
            var trainerId = $j('#trainerId').val();
            var role = $j('#userrole').val();
            
            $j.ajax({
                url: APP_URL + '/wp-content/themes/bridge-child/add-comment.php',
                dataType: 'json',
                type: "POST",
                data: {'comment': comment, 'operator' : operatorId, 'trainer' : trainerId, 'role' : role},
                success: function(data) {
                    if (data.message) {
                        $j('#comment').modal('toggle');
                        $j('.responseSuccess').css('display','block');
                        $j('.responseSuccess').append(data.message);
                        /* hide bootstrap alert after 5 sec */
                        $j(".responseSuccess").fadeTo(5000, 500).slideUp(500, function(){
                            $j(".responseSuccess").slideUp(500);
                        });
                    } else {
                        $j('#comment').modal('toggle');
                        $j('.responseError').css('display','block');
                        $j('.responseError').append(data.error);
                        /* hide bootstrap alert after 5 sec */
                        $j(".responseError").fadeTo(5000, 500).slideUp(500, function(){
                            $j(".responseError").slideUp(500);
                        });
                    }
                },
                error: function (xhr) {
                   
                },
	    });
         });

         /* check for dirty form for add notes */
         $j("#addnote").dirrty().on("dirty", function(){
            $j(".saveNote").removeAttr("disabled");
        }).on("clean", function(){
            $j(".saveNote").attr("disabled", "disabled");
        });

        $j('.forgotAccount').on('click', function(){
            var fname = $j('#fname').val();
            var lname = $j('#lname').val();
            var email = $j('#user_email').val();
            $j.ajax({
                url: APP_URL + '/wp-content/themes/bridge-child/forgot-account.php',
                dataType: 'json',
                type: "POST",
                data: {'email': email, 'firstname' : fname, 'lastname' : lname, 'type' : 'account'},
                beforeSend: function() {
                    $j('.accountResponse').css('display','none');
                    $j('.accountSuccess').css('display','none');
                },
                success: function(data) {
                    if (data.error) {
                        $j('.accountResponse').css('display','block');
                        $j('.accountResponse').append(data.error);
                    } else {
                        $j('.accountSuccess').css('display','block');
                        $j('.accountSuccess').append(data.message);
                    }
                },
                error: function (xhr) {
                   
                },
	    });
        });

        $j('.reset_password').on('click', function(){
            var fname = $j('#fname').val();
            var lname = $j('#lname').val();
            var email = $j('#useremail').val();
            $j.ajax({
                url: APP_URL + '/wp-content/themes/bridge-child/forgot-account.php',
                dataType: 'json',
                type: "POST",
                data: {'email': email, 'firstname' : fname, 'lastname' : lname, 'type' : 'getData'},
                success: function(data) {
                    var html = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                    if (data.message) {
                        html += data.message;
                        $j('.passSuccess').css('display','block');
                        $j('.passSuccess').html(html);
                    } else {
                        html += data.error;
                        $j('#invalid').css('display','block');
                        $j('#invalid').html(html);
                    }
                },
                error: function (xhr) {
                   
                },
	    });
        });

        $j('.savePassword').on('click', function(){
            var form = $j("#resetPass");
            form.validate({
                        errorElement: "span",
                        errorClass: "alert-danger",
                        highlight: function (element, errorClass, validClass) {
                        jQuery(element).addClass(errorClass);
                            jQuery(element).closest('.form-group').addClass('has-error');
                        },
                        unhighlight: function (element, errorClass, validClass) {
                        jQuery(element).removeClass(errorClass);
                            jQuery(element).closest('.form-group').removeClass('has-error');
                        },
                        rules: {
                            newPassword: {
                                required: true,
                                minlength: 6
                            },
                            confirmPassword: {
                                required: true,
                                minlength: 6,
                                equalTo: '#newPassword'
                            }
                        }
            });

            if (form.valid() === true){
            var newPass = $j('#newPassword').val();
            var accId = $j('#account_id').val();

            $j.ajax({
                url: APP_URL + '/wp-content/themes/bridge-child/forgot-account.php',
                dataType: 'json',
                type: "POST",
                data: {'password': newPass, 'accountId' : accId, 'type' : 'updatePassword'},
                beforeSend: function() {
                    $j('.passResponse').css('display','none');
                    $j('.passSuccess').css('display','none');
                },
                success: function(data) {
                    if (data.error) {
                        $j('.passResponse').css('display','block');
                        $j('.passResponse').text(data.error);
                    } else {
                        $j('.passSuccess').css('display','block');
                        $j('.passSuccess').text(data.message);
                    }
                    setTimeout(function(){ window.location.href = APP_URL + '/signin'; }, 5000);
                },
                error: function (xhr) {
                   
                },
	    });
            }
        });

        $j('.approve').on('click', function(){
            var expid = $j(this).attr('data-id');
            var opid = $j(this).attr('data-value');
            $j.ajax({
                url: APP_URL + '/wp-content/themes/bridge-child/generate-pdf.php',
                dataType: 'json',
                type: "POST",
                data: {'experience' : expid, 'operator' : opid},
                success: function(data) {
                    if (data.file) {
                        $j(this).css("display", 'none');
                        $j('#certificate').css('display','block');
                        $j('#certificate').text(data.file);
                        /* hide bootstrap alert after 10 sec */
                        $j(".alert").fadeTo(9000, 500).slideUp(500, function(){
                            $j(".alert").slideUp(500);
                        });
                    }
                },
                error: function (xhr) {
                   
                },
	    });
        });

        $j('#mepr_date').datepicker();

        $j('.opNote').on('click',function(){
            var operatorId = $j(this).attr('data-value');
            var trainerId = $j(this).attr('data-id');

            $j.ajax({
                url: APP_URL + '/wp-content/themes/bridge-child/add-comment.php',
                dataType: 'json',
                type: "POST",
                data: {'type': 'view', 'operator' : operatorId, 'trainer' : trainerId},
                success: function(data) {
                    console.log(data);
                    $j('.allNotes').html(data);
                },
                error: function (xhr) {
                   
                },
	    });
        });

        $j('.mepr_phone').inputmask({"mask": "(999) 999-9999"});

        $j(".mepr_zip_postal_code").inputmask({"mask": "99999"});

        /* auto complete company */
        $j(".auto").autocomplete({
                source: APP_URL + '/wp-content/themes/bridge-child/get-company.php',
                minLength: 1,
                select: function(event, ui) {
                    $j('#mepr_company_id').val(ui.item.id);            
                }
        });
        $j(".branch").autocomplete({
            source: APP_URL + '/wp-content/themes/bridge-child/get-branch.php',
            minLength: 1,
            select: function(event, ui) {
                $j('#mepr_branch_id').val(ui.item.id);            
            }
        });

        $j(".local").autocomplete({
            source: APP_URL + '/wp-content/themes/bridge-child/get-local.php',
            minLength: 1,
            select: function(event, ui) {
                $j('#mepr_local_id').val(ui.item.id);            
            }
        });

        /* populate branch based on selected company */
        $j("#mepr_company_name").on('change',function(){
            var companyId = $j(this).val();
            
            $j.ajax({
                type: "POST",
                url: APP_URL + '/wp-content/themes/bridge-child/get-branch.php',
                data: {'company' : companyId, 'type' : 'getBranch'},
                success: function(data)
                {
                    $j("#mepr_branch_name").html(data);
                }
            });
        });

        /* populate local based on selected union */
        $j("#mepr_union").on('change',function(){
            var unionId = $j(this).val();
            
            $j.ajax({
                type: "POST",
                url: APP_URL + '/wp-content/themes/bridge-child/get-local.php',
                data: {'union' : unionId, 'type' : 'getLocal'},
                success: function(data)
                {
                    $j("#mepr_local").html(data);
                }
            });
        });

        /* assign slots to branch */
        $j(".slotsAssign").on('click',function(){
            var branchId = [];
            $j(".updateSlots").each(function () {
                branchId[$j(this).attr("data-id")] = $j(this).val();
            });
      
            var branch = $j.extend({}, branchId);

            $j.ajax({
                url: APP_URL + '/wp-content/themes/bridge-child/assign-slots.php',
                dataType: 'json',
                type: "POST",
                data: {'branch': branch},
                success: function(data) {
                    if (data.message) {
                        $j('.slotSuccess').css('display','block');
                        $j('.slotSuccess').text(data.message);
                    } else {
                        $j('.slotError').css('display','block');
                        $j('.slotError').text(data.error);
                    }
                },
                error: function (xhr) {
                   
                },
	    });
        });

        /* deactivate account of user */
        $j(".deactiveAcc").on('click',function(){
            var val = [];
            $j(':checkbox:checked').each(function(i){
              val[i] = $j(this).val();
            });
            var action = $j(':checkbox:checked').attr("data-action");

            if (action == 'deactive') {
                var message = "Are you sure you want to Deactivate selected profile?";
            } else {
                var message = "Are you sure you want to Activate selected profile?";
            }
            
            swal({
                title: 'Are you sure?',
                text: message,
                icon: "success",
                button: "Yes",
            }).then((result) => {
                if (result === true) {
                    $j.ajax({
                        url: APP_URL + '/wp-content/themes/bridge-child/account-deactivate.php',
                        dataType: 'json',
                        type: "POST",
                        data: {'accounts': val, 'action': action},
                        success: function(data) {
                            if (data.error) {
                                $j('.responseError').css('display','block');
                                $j('.responseError').text(data.error);
                            } else {
                                $j('.responseSuccess').css('display','block');
                                $j('.responseSuccess').text(data.success);
                            }
                        },
                        error: function (xhr) {

                        },
                    });
                }
            });
        });

        /* validate edit profile page */
        $j('#editform').validate({
            errorElement: "span",
            errorClass: "alert-danger",
            highlight: function (element, errorClass, validClass) {
            $j(element).addClass(errorClass);
                $j(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element, errorClass, validClass) {
            $j(element).removeClass(errorClass);
                $j(element).closest('.form-group').removeClass('has-error');
            },
            rules: {
                mepr_firstname: {
                    required: true,
                },
                mepr_lastname: {
                    required: true,
                },
                mepr_hours: {
                    number: true
                },
                mepr_main_boom_length: {
                    number: true
                },
                mepr_jib_length: {
                    number: true
                },
                mepr_mjib_length: {
                    number: true
                },
                mepr_tjib_length: {
                    number: true
                },
                mepr_superlift: {
                    number: true
                },
                mepr_counterweight: {
                    number: true
                },
                mepr_tower_height: {
                    number: true
                },
                mepr_maximum_capacity_tons: {
                    number: true
                },
                mepr_company_name: {
                    required: true,
                },
                mepr_branch_nm: {
                    required: true,
                },
                company_name: {
                    required: true,
                },
                user_email: {
                    required: true,
                    email: true
                },
                useremail: {
                    required: true,
                    email: true
                },
                mepr_primary_administrator_name: {
                    required: true,
                },
                mepr_primary_administrator_email: {
                    required: true,
                    email: true
                },
                mepr_secondary_administrator_email: {
                    email: true
                },
                mepr_user_password: {
                    required: function(element){
                        return $j("#mepr_user_password").length > 1;
                    },
                    minlength: 6
                },
                user_role: {
                    required: true,
                },
            },
            
        });

        /* check for form dirty */
        $j("#editform").dirrty().on("dirty", function(){
            $j("#userupdate").removeAttr("disabled");
        }).on("clean", function(){
            $j("#userupdate").attr("disabled", "disabled");
        });

        /* operator search */
        var myTable = $j('#operatorSearch').DataTable({
            "processing": true,
            "serverSide": true,
            "sortable": true,
            "ajax": {
                "type" : "POST",
                "url" : APP_URL + "/wp-content/themes/bridge-child/get-operator.php",
            },
            'columnDefs': [{
                'targets': 0,
                'searchable': false,
                'orderable': false,
            },
            {
                'targets': 3,
                'searchable': false,
                'orderable': false,
            },
            {
                'targets': 4,
                'visible': false
            },
            {
                'targets': 5,
                'visible': false
            }],
        });

        $j('.opSearch').on('click', function(){
            var srch = $j(this).text();
            myTable.columns(3).search(srch,'').draw();
        });

        /* experience datatable */
        var expTable = $j('#expTable').DataTable({
            "columnDefs": [
                {'targets': 2, 'visible': false},
                {'targets': 5, 'visible': false},
                {'targets': 6, 'visible': false},
                {'targets': 10, 'visible': false},
                {'targets': 12, 'visible': false},
                {'targets': 13, 'visible': false},
                {'targets': 14, 'visible': false},
                {'targets': 15, 'visible': false},
                {'targets': 16, 'visible': false},
                {'targets': 17, 'visible': false},
                {'targets': 18, 'visible': false},
                {'targets': 20, 'searchable': false, 'orderable': false},
            ]
        });

        $j('.apply').on('click',function(){
            $j( ".filter" ).each(function( index ) {
                var col = $j(this).attr('data-id');
                if (col === 19 || col === 6 || col === 10) {
                    if (this.value != '') {
                        $j(this).addClass('active');
                        expTable.columns(col).search(this.value).draw();
                    }
                } else {
                    if (this.value != '') {
                        $j(this).addClass('active');
                        expTable.columns(col).search("^" + this.value + "$", true, true, false).draw();
                    }
                }
            });
        });

        $j('.reset').on('click',function(){
            expTable.columns('').search('').draw();
            $j( ".filter" ).prop('selectedIndex',0);
            $j( ".filter" ).removeClass('active');
        });

        var open = $j('.open-nav'),
            close = $j('.close'),
            overlay = $j('.overlay');

        open.click(function() {
            overlay.show();
            $j('#wrapper').addClass('toggled');
        });

        close.click(function() {
            overlay.hide();
            $j('#wrapper').removeClass('toggled');
        });

        /* auto complete make and model */
        $j(".make").autocomplete({
            source: APP_URL + '/wp-content/themes/bridge-child/autocomplete.php?type=make',
            minLength: 1,
            select: function(event, ui) {
                $j('.make').val(ui.item.id);            
            }
        });
        $j(".model").autocomplete({
            source: APP_URL + '/wp-content/themes/bridge-child/autocomplete.php?type=model',
            minLength: 1,
            select: function(event, ui) {
                $j('.model').val(ui.item.id);            
            }
        });

        var table = $j('#requests').DataTable();
        /* approve latest requests */
        $j(".companyApprove").on('click',function(){
            var ids = [];

            $j(':checkbox:checked').each(function(i){
              ids[i] = $j(this).val();
            });

            $j.ajax({
                url: APP_URL + '/wp-content/themes/bridge-child/approve-requests.php',
                dataType: 'json',
                type: "POST",
                data: {'ids': ids},
                success: function(data) {
                    if (data.error) {
                        $j('.responseError').css('display','block');
                        $j('.responseError').append(data.error);
                        $j(".responseError").fadeTo(5000, 500).slideUp(500, function(){
                            $j(".responseError").slideUp(500);
                    });
                    } else {
                        $j('.responseSuccess').css('display','block');
                        $j('.responseSuccess').append(data.success);
                        $j(".responseSuccess").fadeTo(5000, 500).slideUp(500, function(){
                            $j(".responseSuccess").slideUp(500);
                    });
                    }
                    
                    setTimeout(function(){ window.location.reload(); }, 6000);
                },
                error: function (xhr) {
                   
                },
	    });
        });

        /* disable active checkbox until any value selected */
        $j("#mepr_company_name").on('change',function(){
            $j("#company").removeAttr("disabled");
        });
        $j("#mepr_branch_name").on('change',function(){
            $j("#branch").removeAttr("disabled");
        });
        $j("#mepr_union").on('change',function(){
            $j("#union").removeAttr("disabled");
        });
        $j("#mepr_local").on('change',function(){
            $j("#local").removeAttr("disabled");
        });
});