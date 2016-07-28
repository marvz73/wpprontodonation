<?php 

  	global $wpdb;
 
    $result = $wpdb->get_results("Select * FROM $wpdb->posts where post_type='campaign' AND post_status='publish'");
 	
 	$query_size = sizeof( $result );

 	for ($i=0; $i < $query_size; $i++) { 
 		?>
 	 	<li> <?php echo $result[$i]->post_title ?> </li>
		<?php
 	}
 
?>