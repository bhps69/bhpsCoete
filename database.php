<?php

    $replace_array = array( 'local_link' => 'remote_link');
	
    $mysql_link = mysqli_connect( 'trainin.gkblabs.com', 'adityars_phanico', 'PhaniCo' , 'adityars_wp_phanicoete');


    

    if( ! $mysql_link) {
        die( 'Could not connect: ' . mysql_error() );
    }

 
    //$mysql_db = mysql_select_db( 'beryl', $mysql_link );
    //if(! $mysql_db ) {
        //die( 'Can\'t select database: ' . mysql_error() );
    //}
 
    // Traverse all tables
    $tables_query = 'SHOW TABLES';
    $tables_result = mysqli_query( $mysql_link, $tables_query );
    while( $tables_rows = mysqli_fetch_row( $tables_result ) ) {
        foreach( $tables_rows as $table ) {
 
            // Traverse all columns
            $columns_query = 'SHOW COLUMNS FROM ' . $table;
            $columns_result = mysqli_query( $mysql_link, $columns_query );
            while( $columns_row = mysqli_fetch_assoc( $columns_result ) ) {
 
                $column = $columns_row['Field'];
                $type = $columns_row['Type'];
 
                // Process only text-based columns
                if( strpos( $type, 'char' ) !== false || strpos( $type, 'text' ) !== false ) {
                    // Process all replacements for the specific column                    
                    foreach( $replace_array as $old_string => $new_string ) {
                       echo $replace_query = 'UPDATE ' . $table . 
                            ' SET ' .  $column . ' = REPLACE(' . $column . 
                            ', \'' . $old_string . '\', \'' . $new_string . '\')';
							echo "<hr>";
                       mysqli_query( $mysql_link, $replace_query );
						echo mysqli_error($mysql_link);
                    }
                }
            }
        }
    }
 
    mysqli_free_result( $columns_result );
    mysqli_free_result( $tables_result );
    mysqli_close( $mysql_link );
 
    echo 'Done!';
 
?>