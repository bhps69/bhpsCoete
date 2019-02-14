<?php /** Template Name:Adv*/
if (!is_user_logged_in()) {
    wp_redirect(site_url() . '/signin');
    exit;
}
$current_user = wp_get_current_user();

?>
<?php include( get_stylesheet_directory() . '/dash-header.php'); ?>
<html>
<head>
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">
	
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
	
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<script type="text/javascript" src="/media/js/dynamic.php?comments-page=examples%2Fapi%2Fmulti_filter_select.html" async></script>
	<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

	
<script>
$(document).ready(function() {
    $('#example').DataTable( {
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
    } );
} );

</script>
</head>
<body>
<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Account#</th>
                <th>Name</th>	
                <th>Email</th>
                <th>City</th>
                <th>State</th>
				<th>Country</th>
            </tr>
        </thead>
        <tbody>
		<?php
		$args = array(
                    'role__in' => ['trainer','evaluator'],
                    'meta_query' => array(
                        array(
                            'key'     => 'mepr_company_name',
                            'value'   => get_user_meta($current_user->ID,'mepr_company_name',true),
                            'compare' => 'LIKE'
                        )
                    )
                );    
                
                $users = get_users( $args );
                if (!empty($users)) {
                    foreach ($users as $key=>$user) {
            ?>

            <tr>	
	    	<td><?php echo $user->user_login; ?></td>
	    	<td><?php echo $user->display_name; ?></td>
	    	<td><?php echo $user->user_email;?> </td>
                <td><?php echo get_user_meta($user->ID,'mepr_city',true);?></td>
                <td><?php echo get_user_meta($user->ID,'mepr_state_province',true);?></td>
				<td><?php echo get_user_meta($user->ID,'mepr_country',true);?></td>
	    </tr>
	    <?php
                    }
                }
            ?>
	</tbody>   
        <tfoot>
            <tr>
                <th>Account#</th>
                <th>Name</th>
                <th>Email</th>
                <th>City</th>
                <th>State</th>
                <th>Country</th>
            </tr>
        </tfoot>
    </table>
	</body>
	</html>