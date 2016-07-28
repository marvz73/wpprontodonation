<?php 
	require_once('../../../../../wp-blog-header.php' );

    if ( isset( $_GET['donation_meta_key'] ) ) {
    	global $wpdb;
    	$meta_key = $_GET['donation_meta_key'];
        $result = $wpdb->get_results("Select * FROM $wpdb->postmeta where meta_key='pronto_donation_donor' AND meta_id=" . $meta_key );
       
        $donation_details = unserialize( $result[0]->meta_value );
        $pronto_donation_settings = get_option('pronto_donation_settings', '');
        $currency_val = $pronto_donation_settings['SetCurrencySymbol'];
        ?>

        <div class="card">
        	<h2>Donation Details</h2>
 
        	<div class="donation-details">
        		<span class="donation-details-header"> First Name:</span>
        		<span class="donation-details-value"> <?php echo (isset( $donation_details['first_name'] ) ) ? $donation_details['first_name'] : '' ?> </span>
        	</div>
        	<div class="donation-details">
        		<span class="donation-details-header"> Last Name:</span>
        		<span class="donation-details-value"> <?php echo (isset( $donation_details['last_name'] ) ) ? $donation_details['last_name'] : '' ?> </span>
        	</div>
        	<div class="donation-details">
        		<span class="donation-details-header"> Email Address:</span>
        		<span class="donation-details-value"> <?php echo (isset( $donation_details['email'] ) ) ? $donation_details['email'] : '' ?> </span>
        	</div>
        	<div class="donation-details">
        		<span class="donation-details-header"> Phone:</span>
        		<span class="donation-details-value"> <?php echo (isset( $donation_details['phone'] ) ) ? $donation_details['phone'] : '' ?> </span>
        	</div>
        	<div class="donation-details">
        		<span class="donation-details-header"> Address:</span>
        		<span class="donation-details-value"> <?php echo (isset( $donation_details['address'] ) ) ? $donation_details['address'] : '' ?> </span>
        	</div>
        	<div class="donation-details">
        		<span class="donation-details-header"> Donor Type:</span>
        		<span class="donation-details-value"> <?php echo ( isset($donation_details['donor_type']) && $donation_details['donor_type'] == 'I') ? 'Individual' : 'Business' ?> </span>
        	</div>
          	<div class="donation-details">
        		<span class="donation-details-header"> Donation Type:</span>
        		<span class="donation-details-value"> <?php echo (isset( $donation_details['donation_type'] ) ) ? $donation_details['donation_type'] : '' ?> </span>
        	</div>

        	<div class="donation-details">
        		<span class="donation-details-header"> Company Name:</span>
        		<span class="donation-details-value"> <?php echo (isset( $donation_details['companyName'] ) ) ? $donation_details['companyName'] : '' ?> </span>
        	</div>
        	<div class="donation-details">
        		<span class="donation-details-header"> Payment:</span>
        		<span class="donation-details-value"> <?php echo (isset($donation_details['payment'] ) ) ? $donation_details['payment'] : '' ?> </span>
        	</div>
        	<div class="donation-details">
        		<span class="donation-details-header"> Campaign:</span>
        		<span class="donation-details-value"> <?php echo (isset( $donation_details['donation_campaign'] ) ) ? get_the_title( $donation_details['donation_campaign'] ): '' ?> </span>
        	</div>
            <?php 
            if( array_key_exists('donation_details', $donation_details) && isset( $donation_details['pd_custom_amount'] ) ) {
                ?>
                <div class="donation-details">
                    <span class="donation-details-header"> Amount:</span>
                    <span class="donation-details-value"> <?php echo $currency_val .''. number_format( (int) $donation_details['pd_custom_amount'], 2, '.', ',') ?> </span>
                </div>
                <?php
            } else {
                ?>
                <div class="donation-details">
                    <span class="donation-details-header"> Amount:</span>
                    <span class="donation-details-value"> <?php echo $currency_val .''. number_format( (int) $donation_details['pd_amount'] , 2, '.', ',') ?> </span>
                </div>
                <?php
            }
            ?>
        	<div class="donation-details">
        		<span class="donation-details-header"> Payment Reference:</span>
        		<span class="donation-details-value"> <?php echo (isset( $donation_details['payment_response']['PaymentReference'] ) ) ? $donation_details['payment_response']['PaymentReference'] : '' ?> </span>
        	</div>
         	<div class="donation-details">
        		<span class="donation-details-header"> Biller ID:</span>
        		<span class="donation-details-value"> <?php echo (isset( $donation_details['payment_response']['BillerID'] ) ) ? $donation_details['payment_response']['BillerID'] : '' ?> </span>
        	</div>
        	<div class="donation-details">
        		<span class="donation-details-header">Transaction ID:</span>
        		<span class="donation-details-value"> <?php echo (isset( $donation_details['payment_response']['TransactionID'] ) ) ? $donation_details['payment_response']['TransactionID'] : '' ?> </span>
        	</div>
        	<div class="donation-details">
        		<span class="donation-details-header"> Payment Amount:</span>
        		<span class="donation-details-value"> <?php echo (isset( $donation_details['payment_response']['PaymentAmount'] ) ) ? $donation_details['payment_response']['PaymentAmount'] : '' ?> </span>
        	</div>
        	<div class="donation-details">
        		<span class="donation-details-header"> Result Code:</span>
        		<span class="donation-details-value"> <?php echo (isset( $donation_details['payment_response']['ResultCode'] ) ) ? $donation_details['payment_response']['ResultCode'] : '' ?> </span>
        	</div>
         	<div class="donation-details">
        		<span class="donation-details-header">Result Text:</span>
        		<span class="donation-details-value"> <?php echo (isset( $donation_details['payment_response']['ResultText'] ) ) ? $donation_details['payment_response']['ResultText'] : '' ?> </span>
        	</div>
         	<div class="donation-details">
        		<span class="donation-details-header">Transaction Fee Customer:</span>
        		<span class="donation-details-value"> <?php echo (isset( $donation_details['payment_response']['TransactionFeeCustomer'] ) ) ? $currency_val .''. $donation_details['payment_response']['TransactionFeeCustomer'] : '' ?> </span>
        	</div>
        </div>

        <?php
    }
 
?>