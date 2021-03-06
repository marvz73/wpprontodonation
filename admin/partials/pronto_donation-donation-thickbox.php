<?php 
	require_once('../../../../../wp-blog-header.php' );

    if ( isset( $_GET['donation_meta_key'] ) && isset( $_GET['currency_symbol'] ) ) {

    	global $wpdb;
    	$meta_key = $_GET['donation_meta_key'];
        $result = $wpdb->get_results("Select * FROM $wpdb->postmeta where meta_key='pronto_donation_donor' AND meta_id=" . $meta_key );
        
        $donation_details = unserialize( $result[0]->meta_value );



        $data_address = ( !empty( $donation_details['address'] ) ) ? $donation_details['address'] ." ": '';
        $data_suburb = ( !empty( $donation_details['suburb'] ) ) ? $donation_details['suburb'] .", ": '';
        $data_state = ( !empty( $donation_details['state'] ) ) ? $donation_details['state'] .", " : '';
        $data_post_code = ( !empty( $donation_details['post_code'] ) ) ? $donation_details['post_code'] . ", " : '';
        $data_country = ( !empty( $donation_details['country'] ) ) ? $donation_details['country'] : '';

        $data_address_concat = $data_address.$data_suburb.$data_state.$data_post_code.$data_country;


        $logs_result = $wpdb->get_results("Select * FROM $wpdb->postmeta where meta_key='pronto_donation_logs".$meta_key."'" );
        $the_donation_logs = ( !empty( $logs_result ) ) ? unserialize( $logs_result[0]->meta_value ) : '';

        // echo "<pre>";
        // print_r( $the_donation_logs );

        ?>

        <script type="text/javascript">

        jQuery(document).ready(function($){
            var oldvalue;

            $('#api-data-option').click(function(){
                $('#data-payment-api').toggle();
            });

            $("#donation_status").attr('prev', $("#donation_status").val());
            $('#donation_status').change(function(){

                oldvalue = $("#donation_status").attr('prev');
                var status = $(this).val();
                $("#donation_status").attr('prev', $("#donation_status").val());

                $.ajax({
                    type:"POST",
                    url: ajaxurl,
                    data: { 
                        action: "change_donation_status",
                        param : {
                            'donation_meta_key' : "<?php echo $_GET['donation_meta_key'] ?>",
                            'donation_new_status' : status,
                            'donation_old_status' : oldvalue
                        }
                    },
                    success: function (data) {
                        // console.log(data)
                        $('#status'+"<?php echo $_GET['donation_meta_key'] ?>").text(status);
                        $('.status-ajax').text('Donation status already updated');
                        $('.status-ajax').css('color','#409c3a');
                        $('.status-ajax').show();

                        setTimeout(function(){
                            $('.status-ajax').hide();
                        },2000);
                    },
                    error : function (data) {

                        $('.status-ajax').text('Donation status is not updated');
                        $('.status-ajax').css('color','#e44b4b')
                        $('.status-ajax').show();

                        setTimeout(function(){
                            $('.status-ajax').hide();
                        },2000);
                    }
                });
            });
        });

        </script>

        <style type="text/css">

            .status-ajax {
                font-size: 13px;
                font-family: sans-serif;
            }

            #api-data-option {
                font-weight: bold;
            }
            #api-data-option:hover {
                text-decoration: underline;
                cursor: pointer;
            }
        </style>
        <div class="wrapper">

        	<h2>Donation Details</h2>

            <table>
                <tbody>
                    <tr>
                        <th>
                        <label class="">Date</label>
                        </th>
                        <td>
                            <input type="text" class="regular-text donation-details-value" value="<?php echo (isset( $donation_details['timestamp'] ) ) ? date('M d, Y h:m:s' , $donation_details['timestamp'] ) : '' ?>" readonly>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label class="">This gift is in memory</label>
                        </th>
                        <td>
                           <label class="" for="donation_gift"><input class="donation-details-value" name="donation_gift" type="checkbox" id="donation_gift" disabled <?php if ( isset( $donation_details['donation_gift'] ) && $donation_details['donation_gift'] !== 0 ) echo "checked='checked'" ?> > </label>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label class="">This donation is in memory of</label>
                        </th>
                        <td>
                            <textarea readonly rows="3" cols="46" class="donation-details-value" name="donation_gift_msg"><?php echo ( isset( $donation_details['donation_gift_message'] ) ) ? $donation_details['donation_gift_message'] : '' ?></textarea>
                        </td>
                     </tr>
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
                            <input type="text" class="regular-text donation-details-value" value="<?php echo $data_address_concat ?>" readonly>
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
                            <select class="regular-text donation-details-value" name="donation_status" id="donation_status">

                                <option value="pending" 
                                <?php
                                if( (array_key_exists('statusText', $donation_details )
                                    && strtolower( $donation_details['statusText'] ) == 'pending'
                                    && $donation_details['statusCode'] == 0 ) )
                                {
                                    echo "selected='selected'";
                                } else if( (!array_key_exists('statusText', $donation_details )
                                    && $donation_details['status'] == 'pending') ||
                                    (!array_key_exists('statusText', $donation_details )
                                    && $donation_details['status'] == 'Ezidebit')
                                ) {
                                    echo "selected='selected'";
                                }
                                ?>
                                >Pending</option>

                                <option value="cancelled"

                                <?php
                                if( (array_key_exists('statusText', $donation_details)
                                    && strtolower( $donation_details['statusText'] ) == 'cancelled'
                                    && $donation_details['statusCode'] == 0 ) )
                                {
                                    echo "selected='selected'";
                                } else if(!array_key_exists('statusText', $donation_details )
                                    && strtolower($donation_details['status'] ) == 'cancelled' )
                                {
                                    echo "selected='selected'";
                                }
                                ?>
                                >Cancelled</option>

                                <option value="approved"
                                <?php
                                if( array_key_exists('statusText', $donation_details)
                                    && $donation_details['statusCode'] == 1 )
                                {
                                    echo "selected='selected'";
                                }
                                ?>
                                >Approved</option>

                            </select>
                            <span class="status-ajax"></span>
                        </td>
                    </tr>
                    <?php 
                    if( isset( $donation_details['pd_custom_amount'] ) && (int) $donation_details['pd_custom_amount'] > 0 ) {
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

                    <tr>
                        <th>
                            <h3 class=""> Payment Response</h3>
                        </th>
                    </tr>
                    <?php

                    if( isset($donation_details['payment_response']) && !empty($donation_details['payment_response'])) {

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
                    } else {
                        ?>
                        <th>
                            <h5 class=""> No payment response</h5>
                        </th>
                        <?php
                    }
                    ?>

                    <tr>
                        <th>
                            <h3 class="">Opportunity response</h3>
                        </th>
                    </tr>

                    <?php 

                    if( isset($donation_details['opportunityStatus']) ) {
                        ?>
                            <tr>
                                <th>
                                    <label class="">opportunityStatus</label>
                                </th>
                                <td>
                                    <input type="text" class="regular-text donation-details-value" value="<?php echo $donation_details['opportunityStatus'] ?>" readonly>
                                </td>
                            </tr>
                        <?php
                    }

                    if( isset($donation_details['opportunityMessage']) ) {
                        ?>
                            <tr>
                                <th>
                                    <label class="">opportunityMessage</label>
                                </th>
                                <td>
                                    <input type="text" class="regular-text donation-details-value" value="<?php echo $donation_details['opportunityMessage'] ?>" readonly>
                                </td>
                            </tr>
                        <?php
                    }

                    if( isset($donation_details['opportunityId']) ) {
                        ?>
                            <tr>
                                <th>
                                    <label class="">opportunityId</label>
                                </th>
                                <td>
                                    <input type="text" class="regular-text donation-details-value" value="<?php echo $donation_details['opportunityId'] ?>" readonly>
                                </td>
                            </tr>
                        <?php
                    }

                    ?>

                    <tr>
                        <th>
                            <h3 class="">Status Logs</h3>
                        </th>
                    </tr>

                    <?php 
                    if( isset($donation_details['StatusLogs'])  && !empty($donation_details['StatusLogs']) ) {
                        foreach ($donation_details['StatusLogs'] as $key => $status_logs) {
                            ?>
                            <tr>
                                <th>
                                    <small style="font-weight: bold;"> <?php echo $status_logs['date'] ?></small>
                                </th>
                                <td>
                                    <small><?php echo $status_logs['user'] .' changed the status of this donation from '.$status_logs['old_status']. ' to '. $status_logs['new_status'] ?></small>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <th>
                            <h5 class=""> No status logs</h5>
                        </th>
                        <?php
                    }
                    ?>

                    <tr>
                        <th>
                            <small id="api-data-option" style=""> Show salesforce api logs </small>
                        </th>
                        <td>
                            <div id="data-payment-api" style="display:none;">
                                <textarea readonly rows="3" cols="46" class="donation-details-value" name="api_logs"><?php print_r( $the_donation_logs ); ?></textarea>
                            </div>
                        </td>
                    </tr>


                </tbody>
            </table>
         </div>

        <?php
    }
 
?>