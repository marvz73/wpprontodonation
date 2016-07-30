<?php

if(is_admin())
{
    new Pronto_Donation_Campaign_WP_list_Table();
}

class Pronto_Donation_Campaign_WP_list_Table {


	public function __construct() {

        /**
         *  This will load the donation details thickbox
         */

        add_action( 'admin_menu', array($this, 'pronto_donation_campaign_table_page' ));
        add_action( 'admin_head', array($this, 'pronto_donation_table_head_css' ));
    }

    public function pronto_donation_campaign_table_page() {
    	
    	$donation_menu = add_menu_page(
	        'Pronto Donation',
	        'Pronto Donation',
	        'administrator',
	        'donation_page',
	        array( $this, 'pronto_donation_campaign_list_table_page' ),
	       	'dashicons-money',	   
	        '83.7'
	    );
    }

    public function pronto_donation_table_head_css() {
        echo '<style>
            .column-id {width: 5%}
            .column-donor_name {width: 20%}
            .column-email {width: 20%}
            .column-campaign_name {width: 17%}
            .column-amount {width: 8%}
            .column-donation_type {width: 8%; text-transform: capitalize; text-align: center !important;}
            .column-payment {width: 8%; text-align: center !important;}
            .column-status {width:12%; text-align: center !important;}
        </style>';
    }

    public function pronto_donation_campaign_list_table_page()
    {
        $exampleListTable = new Pronto_Donation_Campaign_WP_Table();

        if( isset($_POST['s']) ){
            $exampleListTable->prepare_items($_POST['s']);
        } else {
            $exampleListTable->prepare_items();
        }
     
        ?>
            <div class="wrap">
           		<h2>Donation List</h2>

                <form method="post">
                  <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                  <?php $exampleListTable->search_box('search', 'search_id'); ?>
                </form>

                <?php $exampleListTable->display(); ?>
            </div>
            
        <?php
    }

}

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Pronto_Donation_Campaign_WP_Table extends WP_List_Table
{

	public function prepare_items($search = NULL)
    {
        global $wpdb;
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();

        // execute seach donation
        if( $search != NULL ){
            $search = trim($search);
            $result = $wpdb->get_results("Select * FROM $wpdb->postmeta where meta_key='pronto_donation_donor'");
            $data = $this->get_seach_donation_list( $result , $search );
        }

        usort( $data, array( &$this, 'sort_data' ) );
        $perPage = 10;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    public function get_columns()
    {
        $columns = array(
            'id' => 'ID',
            'date' => 'Date',
            'donor_name' => 'Donor Name',
            'email' => 'Email Address',
            'campaign_name' => 'Campaign Name',
            'amount' => 'Amount',
            'donation_type' => 'Donation Type',
            'payment' => 'Payment',
            'status' => 'Status',
        );
        return $columns;
    }

    public function get_hidden_columns()
    {
        return array();
    }

    public function get_sortable_columns()
    {
        return array('title' => array('title', false));
    }

    private function table_data()
    {
        global $wpdb;
        $result = $wpdb->get_results("Select * FROM $wpdb->postmeta where meta_key='pronto_donation_donor'");
        
        $data = array();

        $ezidebit_url = plugin_dir_url( __FILE__ ).'../../payments/ezidebit/logo.png';
        $ezidebit_url = '<img src="'.$ezidebit_url.'" width="70" height="30" alt="Ezidibit">';

        $eway_url = plugin_dir_url( __FILE__ ).'../../payments/eway/logo.png';
        $eway_url = '<img src="'.$eway_url.'" width="70" height="30" alt="Eway">';

        $redirect_url = plugin_dir_url( __FILE__ ) . "pronto_donation-donation-thickbox.php";

        foreach ($result as $key => $donor_value) {

            $donor_data = unserialize( $donor_value->meta_value );
            $table_data = array();

            $currencycode = ( isset($donor_data['CurrencyCode']) ? $donor_data['CurrencyCode'] : '' );
            $currency_val = $this->pronto_donation_get_currency_symbol( $currencycode );

            $table_data['id'] = $donor_value->meta_id;

            $table_data['date'] = ( isset( $donor_data['timestamp'] ) ) ? date('M d, Y h:m:s', $donor_data['timestamp'] ) : '';

            $table_data['donor_name'] = ( array_key_exists( 'donor_type', $donor_data ) && $donor_data['donor_type'] == 'B' ) ? 

            $donor_data['companyName'] : $table_data['donor_name'] = $donor_data['first_name'] . " " .  $donor_data['last_name'];

            $table_data['payment'] = ( $donor_data['payment'] == 'Ezidebit' ) ? $ezidebit_url : $eway_url;

            $table_data['email'] = $donor_data['email'];

            $table_data['campaignid'] = $donor_data['donation_campaign'];

            $table_data['campaign_name'] = get_the_title( $donor_data['donation_campaign'] );

            if(array_key_exists('pd_amount', $donor_data) && isset( $donor_data['pd_amount'] ) && (int) $donor_data['pd_amount'] > 0 ) {
                $table_data['amount'] = $currency_val .''. number_format( (int) $donor_data['pd_amount'], 2, '.', ',');
            } else {
                $table_data['amount'] = $currency_val .''. number_format( (int) $donor_data['pd_custom_amount'], 2, '.', ',');
            }
            
            $table_data['donation_type'] =  ( isset( $donor_data['donation_type'] ) ) ? $donor_data['donation_type'] : '';

            $status = "";
            if( array_key_exists('statusCode', $donor_data) ) {
                $status = $donor_data['statusText'];
            } else {
                $status = "Pending";
            }

            $table_data['status'] = '<div id="status'.$donor_value->meta_id.'" class="donation-status-pending">'. $status . '</div>
            <a href="'.$redirect_url.'?donation_meta_key='.$donor_value->meta_id.'&currency_symbol='.$currency_val.'&height=550&width=753" id="thickbox-my" class="thickbox donation-view-details">view details</a>';

            $data[] = $table_data;
          
        }
        return $data;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'id':
            case 'date':
			case 'donor_name':
			case 'email':
			case 'campaign_name':
			case 'amount':
			case 'donation_type':
			case 'payment':
			case 'status':
            case 'campaignid':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ;
        }
    }
    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'id';
        $order = 'desc';
        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }
        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }
        $result = strcmp( $a[$orderby], $b[$orderby] );
        if($order === 'asc')
        {
            return $result;
        }
        return -$result;
    }

    public function pronto_donation_get_currency_symbol( $countrycode ) {

        $currency_symbols = array(
            'AED' => '&#1583;.&#1573;', // ?
            'AFN' => '&#65;&#102;',
            'ALL' => '&#76;&#101;&#107;',
            'AMD' => '',
            'ANG' => '&#402;',
            'AOA' => '&#75;&#122;', // ?
            'ARS' => '&#36;',
            'AUD' => '&#36;',
            'AWG' => '&#402;',
            'AZN' => '&#1084;&#1072;&#1085;',
            'BAM' => '&#75;&#77;',
            'BBD' => '&#36;',
            'BDT' => '&#2547;', // ?
            'BGN' => '&#1083;&#1074;',
            'BHD' => '.&#1583;.&#1576;', // ?
            'BIF' => '&#70;&#66;&#117;', // ?
            'BMD' => '&#36;',
            'BND' => '&#36;',
            'BOB' => '&#36;&#98;',
            'BRL' => '&#82;&#36;',
            'BSD' => '&#36;',
            'BTN' => '&#78;&#117;&#46;', // ?
            'BWP' => '&#80;',
            'BYR' => '&#112;&#46;',
            'BZD' => '&#66;&#90;&#36;',
            'CAD' => '&#36;',
            'CDF' => '&#70;&#67;',
            'CHF' => '&#67;&#72;&#70;',
            'CLF' => '', // ?
            'CLP' => '&#36;',
            'CNY' => '&#165;',
            'COP' => '&#36;',
            'CRC' => '&#8353;',
            'CUP' => '&#8396;',
            'CVE' => '&#36;', // ?
            'CZK' => '&#75;&#269;',
            'DJF' => '&#70;&#100;&#106;', // ?
            'DKK' => '&#107;&#114;',
            'DOP' => '&#82;&#68;&#36;',
            'DZD' => '&#1583;&#1580;', // ?
            'EGP' => '&#163;',
            'ETB' => '&#66;&#114;',
            'EUR' => '&#8364;',
            'FJD' => '&#36;',
            'FKP' => '&#163;',
            'GBP' => '&#163;',
            'GEL' => '&#4314;', // ?
            'GHS' => '&#162;',
            'GIP' => '&#163;',
            'GMD' => '&#68;', // ?
            'GNF' => '&#70;&#71;', // ?
            'GTQ' => '&#81;',
            'GYD' => '&#36;',
            'HKD' => '&#36;',
            'HNL' => '&#76;',
            'HRK' => '&#107;&#110;',
            'HTG' => '&#71;', // ?
            'HUF' => '&#70;&#116;',
            'IDR' => '&#82;&#112;',
            'ILS' => '&#8362;',
            'INR' => '&#8377;',
            'IQD' => '&#1593;.&#1583;', // ?
            'IRR' => '&#65020;',
            'ISK' => '&#107;&#114;',
            'JEP' => '&#163;',
            'JMD' => '&#74;&#36;',
            'JOD' => '&#74;&#68;', // ?
            'JPY' => '&#165;',
            'KES' => '&#75;&#83;&#104;', // ?
            'KGS' => '&#1083;&#1074;',
            'KHR' => '&#6107;',
            'KMF' => '&#67;&#70;', // ?
            'KPW' => '&#8361;',
            'KRW' => '&#8361;',
            'KWD' => '&#1583;.&#1603;', // ?
            'KYD' => '&#36;',
            'KZT' => '&#1083;&#1074;',
            'LAK' => '&#8365;',
            'LBP' => '&#163;',
            'LKR' => '&#8360;',
            'LRD' => '&#36;',
            'LSL' => '&#76;', // ?
            'LTL' => '&#76;&#116;',
            'LVL' => '&#76;&#115;',
            'LYD' => '&#1604;.&#1583;', // ?
            'MAD' => '&#1583;.&#1605;.', //?
            'MDL' => '&#76;',
            'MGA' => '&#65;&#114;', // ?
            'MKD' => '&#1076;&#1077;&#1085;',
            'MMK' => '&#75;',
            'MNT' => '&#8366;',
            'MOP' => '&#77;&#79;&#80;&#36;', // ?
            'MRO' => '&#85;&#77;', // ?
            'MUR' => '&#8360;', // ?
            'MVR' => '.&#1923;', // ?
            'MWK' => '&#77;&#75;',
            'MXN' => '&#36;',
            'MYR' => '&#82;&#77;',
            'MZN' => '&#77;&#84;',
            'NAD' => '&#36;',
            'NGN' => '&#8358;',
            'NIO' => '&#67;&#36;',
            'NOK' => '&#107;&#114;',
            'NPR' => '&#8360;',
            'NZD' => '&#36;',
            'OMR' => '&#65020;',
            'PAB' => '&#66;&#47;&#46;',
            'PEN' => '&#83;&#47;&#46;',
            'PGK' => '&#75;', // ?
            'PHP' => '&#8369;',
            'PKR' => '&#8360;',
            'PLN' => '&#122;&#322;',
            'PYG' => '&#71;&#115;',
            'QAR' => '&#65020;',
            'RON' => '&#108;&#101;&#105;',
            'RSD' => '&#1044;&#1080;&#1085;&#46;',
            'RUB' => '&#1088;&#1091;&#1073;',
            'RWF' => '&#1585;.&#1587;',
            'SAR' => '&#65020;',
            'SBD' => '&#36;',
            'SCR' => '&#8360;',
            'SDG' => '&#163;', // ?
            'SEK' => '&#107;&#114;',
            'SGD' => '&#36;',
            'SHP' => '&#163;',
            'SLL' => '&#76;&#101;', // ?
            'SOS' => '&#83;',
            'SRD' => '&#36;',
            'STD' => '&#68;&#98;', // ?
            'SVC' => '&#36;',
            'SYP' => '&#163;',
            'SZL' => '&#76;', // ?
            'THB' => '&#3647;',
            'TJS' => '&#84;&#74;&#83;', // ? TJS (guess)
            'TMT' => '&#109;',
            'TND' => '&#1583;.&#1578;',
            'TOP' => '&#84;&#36;',
            'TRY' => '&#8356;', // New Turkey Lira (old symbol used)
            'TTD' => '&#36;',
            'TWD' => '&#78;&#84;&#36;',
            'TZS' => '',
            'UAH' => '&#8372;',
            'UGX' => '&#85;&#83;&#104;',
            'USD' => '&#36;',
            'UYU' => '&#36;&#85;',
            'UZS' => '&#1083;&#1074;',
            'VEF' => '&#66;&#115;',
            'VND' => '&#8363;',
            'VUV' => '&#86;&#84;',
            'WST' => '&#87;&#83;&#36;',
            'XAF' => '&#70;&#67;&#70;&#65;',
            'XCD' => '&#36;',
            'XDR' => '',
            'XOF' => '',
            'XPF' => '&#70;',
            'YER' => '&#65020;',
            'ZAR' => '&#82;',
            'ZMK' => '&#90;&#75;', // ?
            'ZWL' => '&#90;&#36;',
        );
        
        $data_symbol = "";
        foreach ($currency_symbols as $key => $value) {
            if($key === $countrycode) {
                $data_symbol = $value;
                break;
            }
        }
        return $data_symbol;
    }

    public function pronto_search_donation( $array, $search ) {
        $detect_like = 0;
        $accepted_keylist = array(
            'pd_amount',
            'donation_type',
            'donor_type',
            'companyName',
            'email',
            'first_name',
            'last_name',
            'address',
            'country',
            'state',
            'post_code',
            'suburb',
            'payment',
            'donation_campaign',
            'CurrencyCode',
            'statusText'
            );

        foreach ($array as $key => $value) {
            if( in_array($key, $accepted_keylist) ) {
                if( $key === 'donation_campaign' ) {
                    $the_title = get_the_title( $value );
                    if( stripos( strtolower( $the_title ) , strtolower( $search ) ) !== false ) {
                        $detect_like+=1;
                    }
                } else if( ($key === 'donation_type'
                    || $key === 'first_name'
                    || $key === 'last_name'
                    || $key === 'statusText'
                    || $key === 'address' ) && stripos( strtolower( $value ) , strtolower( $search ) ) ) 
                {
                    $detect_like+=1;
                } else if( stripos($value, $search) !== false ) {
                    $detect_like+=1;
                }
            }
        }
        return $detect_like;
    }

    public function get_seach_donation_list( $result, $search ) {
        $search_data = array();
        $ezidebit_url = plugin_dir_url( __FILE__ ).'../../payments/ezidebit/logo.png';
        $ezidebit_url = '<img src="'.$ezidebit_url.'" width="70" height="30" alt="Ezidibit">';

        $eway_url = plugin_dir_url( __FILE__ ).'../../payments/eway/logo.png';
        $eway_url = '<img src="'.$eway_url.'" width="70" height="30" alt="Eway">';

        $redirect_url = plugin_dir_url( __FILE__ ) . "pronto_donation-donation-thickbox.php";

        foreach ($result as $key => $donor_value) {
            $donor_data = unserialize( $donor_value->meta_value );
            $detect_like = $this->pronto_search_donation( $donor_data, $search );

            if( $detect_like > 0 ) {
                $table_search = array();
                $currencycode = ( isset($donor_data['CurrencyCode']) ? $donor_data['CurrencyCode'] : '' );
                $currency_val = $this->pronto_donation_get_currency_symbol( $currencycode );

                $table_search['id'] = $donor_value->meta_id;

                $table_search['date'] = ( isset( $donor_data['timestamp'] ) ) ? date('M d, Y', $donor_data['timestamp'] ) : '';

                $table_search['donor_name'] = ( array_key_exists( 'donor_type', $donor_data ) && $donor_data['donor_type'] == 'B' ) ? 

                $donor_data['companyName'] : $table_search['donor_name'] = $donor_data['first_name'] . " " .  $donor_data['last_name'];

                $table_search['payment'] = ( $donor_data['payment'] == 'Ezidebit' ) ? $ezidebit_url : $eway_url;

                $table_search['email'] = $donor_data['email'];

                $table_search['campaignid'] = $donor_data['donation_campaign'];

                $table_search['campaign_name'] = get_the_title( $donor_data['donation_campaign'] );

                if(array_key_exists('pd_amount', $donor_data) && isset( $donor_data['pd_amount'] ) && (int) $donor_data['pd_amount'] > 0 ) {
                    $table_search['amount'] = $currency_val .''. number_format( (int) $donor_data['pd_amount'], 2, '.', ',');
                } else {
                    $table_search['amount'] = $currency_val .''. number_format( (int) $donor_data['pd_custom_amount'], 2, '.', ',');
                }

                $table_search['donation_type'] =  ( isset( $donor_data['donation_type'] ) ) ? $donor_data['donation_type'] : '';

                $status = "";
                if( array_key_exists('statusCode', $donor_data) ) {
                    $status = $donor_data['statusText'];
                } else {
                    $status = "Pending";
                }

                $table_search['status'] = '<div id="status'.$donor_value->meta_id.'" class="donation-status-pending">'. $status . '</div>
                <a href="'.$redirect_url.'?donation_meta_key='.$donor_value->meta_id.'&currency_symbol='.$currency_val.'&height=550&width=753" id="thickbox-my" class="thickbox donation-view-details">view details</a>';

                $search_data[] = $table_search;
            }
        }
        return$search_data;
    }

}
?>