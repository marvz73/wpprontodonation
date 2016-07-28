<?php 
	require_once('../../../../../wp-blog-header.php' );

    if ( isset( $_GET['donation_meta_key'] ) ) {

    	global $wpdb;
    	$meta_key = $_GET['donation_meta_key'];
        $result = $wpdb->get_results("Select * FROM $wpdb->postmeta where meta_key='pronto_donation_donor' AND meta_id=" . $meta_key );
        
        $donation_details = unserialize( $result[0]->meta_value );

        // $donation_details = 'a:22:{s:9:"pd_amount";s:1:"0";s:16:"pd_custom_amount";s:6:"100.51";s:13:"donation_type";s:6:"single";s:10:"donor_type";s:1:"I";s:11:"companyName";s:0:"";s:5:"email";s:17:"marvz73@gmail.com";s:10:"first_name";s:6:"Marvin";s:9:"last_name";s:5:"Ayaay";s:5:"phone";s:10:"9101765857";s:7:"address";s:31:"Brgy. 1, Alima Apartment Door B";s:7:"country";s:6:"Select";s:5:"state";s:6:"Select";s:9:"post_code";s:4:"7214";s:6:"suburb";s:11:"Tangub City";s:7:"payment";s:8:"Ezidebit";s:5:"nonce";s:10:"37d80083b0";s:17:"donation_campaign";s:2:"11";s:6:"action";s:14:"process_donate";s:6:"status";s:18:"INSUFFICIENT_FUNDS";s:12:"payment_info";O:8:"ezidebit":2:{s:7:"payment";a:4:{s:4:"logo";s:8:"logo.png";s:12:"payment_name";s:8:"Ezidebit";s:19:"payment_description";s:35:"This is a payment description here.";s:3:"url";s:0:"";}s:6:"option";a:7:{s:11:"sandboxmode";s:2:"on";s:4:"logo";s:2:"on";s:6:"enable";s:2:"on";s:3:"url";s:45:"https://alphasys-new.pay.demo.ezidebit.com.au";s:12:"payment_type";s:8:"ezidebit";s:6:"action";s:13:"save_settings";s:5:"nonce";s:10:"72fe8642a6";}}s:11:"redirectURL";s:32:"http://localhost/wordpress/?p=61";s:16:"payment_response";a:7:{s:16:"PaymentReference";s:3:"123";s:8:"BillerID";s:8:"10039371";s:13:"TransactionID";s:7:"2669759";s:13:"PaymentAmount";s:6:"100.51";s:10:"ResultCode";s:2:"51";s:10:"ResultText";s:18:"INSUFFICIENT_FUNDS";s:22:"TransactionFeeCustomer";s:4:"0.00";}}';
        // $donation_details = unserialize( $donation_details );

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
                                <input type="text" class="regular-text donation-details-value" value="<?php echo $currency_val .''. number_format( (int) $donation_details['pd_custom_amount'], 2, '.', ',') ?>" readonly>
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
                                <input type="text" class="regular-text donation-details-value" value="<?php echo $currency_val .''. number_format( (int) $donation_details['pd_amount'], 2, '.', ',') ?>" readonly>
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