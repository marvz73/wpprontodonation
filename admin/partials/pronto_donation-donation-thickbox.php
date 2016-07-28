<?php 
	require_once('../../../../../wp-blog-header.php' );

    if ( isset( $_GET['donation_meta_key'] ) && isset( $_GET['currency_symbol'] ) ) {

    	global $wpdb;
    	$meta_key = $_GET['donation_meta_key'];
        $result = $wpdb->get_results("Select * FROM $wpdb->postmeta where meta_key='pronto_donation_donor' AND meta_id=" . $meta_key );
        
        $donation_details = unserialize( $result[0]->meta_value );

        $pronto_donation_settings = get_option('pronto_donation_settings', '');
        $currency_val = $pronto_donation_settings['SetCurrencySymbol'];

        ?>

        <div class="wrapper">

        	<h2>Donation Details</h2>

            <table>
                <tbody>
                    <tr>
                        <th>
                            <label class="">First Name</label>
                        </th>
                        <td>
                            <input type="text" class="regular-text donation-details-value" value="<?php echo (isset( $donation_details['first_name'] ) ) ? $donation_details['first_name'] : '' ?>" readonly>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label class="">Last Name</label>
                        </th>
                        <td>
                            <input type="text" class="regular-text donation-details-value" value="<?php echo (isset( $donation_details['last_name'] ) ) ? $donation_details['last_name'] : '' ?>" readonly>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label class="">Email Address</label>
                        </th>
                        <td>
                            <input type="text" class="regular-text" style="margin-left: 10px;" value="<?php echo (isset( $donation_details['email'] ) ) ? $donation_details['email'] : '' ?>" readonly>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label class="">Phone</label>
                        </th>
                        <td>
                              <input type="text" class="regular-text donation-details-value" value="<?php echo (isset( $donation_details['phone'] ) ) ? $donation_details['phone'] : '' ?>" readonly>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label class="">Address</label>
                        </th>
                        <td>
                            <input type="text" class="regular-text donation-details-value" value="<?php echo (isset( $donation_details['address'] ) ) ? $donation_details['address'] : '' ?>" readonly>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label class=""> Company Name</label>
                        </th>
                        <td>
                            <input type="text" class="regular-text donation-details-value" value="<?php echo (isset( $donation_details['companyName'] ) ) ? $donation_details['companyName'] : '' ?>" readonly>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label class="">Donor Type</label>
                        </th>
                        <td>
                           <input type="text" class="regular-text donation-details-value" value="<?php echo (isset( $donation_details['donor_type'] ) && $donation_details['donor_type'] == 'B' ) ? 'Business' : 'Individual' ?>" readonly>
                       </td>
                   </tr>
                   <tr>
                        <th>
                            <label class="">Donation Type</label>
                        </th>
                        <td>
                           <input type="text" class="regular-text donation-details-value" value="<?php echo (isset( $donation_details['donation_type'] ) ) ? $donation_details['donation_type'] : '' ?>" readonly>
                       </td>
                   </tr>
                    <tr>
                        <th>
                            <label class=""> Payment</label>
                        </th>
                        <td>
                           <input type="text" class="regular-text donation-details-value" value="<?php echo (isset( $donation_details['payment'] ) ) ? $donation_details['payment'] : '' ?>" readonly>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label class=""> Campaign</label>
                        </th>
                        <td>
                          <input type="text" class="regular-text donation-details-value" value="<?php echo (isset( $donation_details['donation_campaign'] ) ) ? get_the_title($donation_details['donation_campaign']) : '' ?>" readonly>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label class=""> Status</label>
                        </th>
                        <td>
                          <input type="text" class="regular-text donation-details-value" value="<?php echo (isset( $donation_details['donation_campaign'] ) ) ? $donation_details['status'] : '' ?>" readonly>
                      </td>
                    </tr>
                    <?php 
                    if( array_key_exists('pd_custom_amount', $donation_details) && isset( $donation_details['pd_custom_amount'] ) && (int) $donation_details['pd_custom_amount'] > 0 ) {
                        ?>
                        <tr>
                            <th>
                                <label class=""> Amount</label>
                            </th>
                            <td>
                                <input type="text" class="regular-text donation-details-value" value="<?php echo $_GET['currency_symbol'] .''. number_format( (int) $donation_details['pd_custom_amount'], 2, '.', ',') ?>" readonly>
                            </td>
                        </tr>
                        <?php
                    } else {
                        ?>
                        <tr>
                            <th>
                                <label class=""> Amount</label>
                            </th>
                            <td>
                                <input type="text" class="regular-text donation-details-value" value="<?php echo $_GET['currency_symbol'] .''. number_format( (int) $donation_details['pd_amount'], 2, '.', ',') ?>" readonly>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>

                    <?php 

                    if( array_key_exists( 'payment_response', $donation_details ) ) {

                        foreach ($donation_details['payment_response'] as $key => $payment_response) {
                            ?>
                            <tr>
                                <th>
                                    <label class=""> <?php echo $key ?></label>
                                </th>
                                <td>
                                    <input type="text" class="regular-text donation-details-value" value="<?php echo (isset( $payment_response ) ) ? $payment_response : '' ?>" readonly>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
         </div>

        <?php
    }
 
?>