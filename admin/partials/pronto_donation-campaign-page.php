<?php

if(is_admin())
{
    new Pronto_Donation_Campaign_WP_list_Table();
}

class Pronto_Donation_Campaign_WP_list_Table {

	public function __construct() {
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
            .column-donor_name {width: 20%}
            .column-email {width: 20%}
            .column-campaign_name {width: 20%}
            .column-amount {width: 9%}
            .column-country {width: 12%}
            .column-payment {width: 10%}
            .column-status {width: 9%}
        </style>';
    }

    public function pronto_donation_campaign_list_table_page()
    {
        $exampleListTable = new Pronto_Donation_Campaign_WP_Table();
        $exampleListTable->prepare_items();
        ?>
            <div class="wrap">
           		<h2>Donation List</h2>

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

	public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $data = $this->table_data();
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
            'donor_name' => 'Donor Name',
            'email' => 'Email Address',
            'campaign_name' => 'Campaign Name',
            'amount' => 'Amount',
            'country' => 'Country',
            'payment' => 'Payment',
            'status' => 'Donation Status'
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
 		$data = array();
		$args = array( 'post_type' => 'campaign');
		$loop = new WP_Query( $args );
        
        $ezidebit_url = plugins_url( '/pronto_donation/payments/ezidebit/logo.png' );
        $ezidebit_url = '<img src="'.$ezidebit_url.'" width="70" height="30" alt="">';

        $eway_url = plugins_url( '/pronto_donation/payments/eway/logo.png' );
        $eway_url = '<img src="'.$eway_url.'" width="70" height="30" alt="">';
		
		while ( $loop->have_posts() ) : $loop->the_post();
		    $campaigns = get_post_meta( get_the_ID() );
				if( array_key_exists( 'pronto_donation_donor', $campaigns )  ) {
					foreach ($campaigns['pronto_donation_donor'] as $donors) {

						$donor_data = unserialize( $donors );
						$table_data = array();

                        $table_data['donor_name'] = ( array_key_exists( 'donor_type', $donor_data ) && $donor_data['donor_type'] == 'B' ) ? 
                        $donor_data['companyName'] : $table_data['donor_name'] = $donor_data['first_name'] . " " .  $donor_data['last_name'];

                        $table_data['payment'] = ( $donor_data['payment'] == 'Ezidebit' ) ? $ezidebit_url : $eway_url;
						$table_data['email'] = $donor_data['email'];
						$table_data['campaign_name'] = get_the_title( $donor_data['donation_campaign'] );
						$table_data['amount'] = number_format( $donor_data['pd_amount'], 2, '.', ',');

						$table_data['country'] = ( !isset( $donor_data['country'] ) || $donor_data['country'] == 'Select' ) ? 
                        "<p class='description'>Not Specified</p>" : $donor_data['country'];

                        if($donor_data['status'] == 'pending') {
                            $table_data['status'] = '<div style="
                            background-color: #026aa7;
                            padding: 5px;
                            text-align: center;
                            text-transform: capitalize;
                            color: #ffffff;
                            font-weight: bold;
                            border-radius: 4px;">'. $donor_data['status'] . '</div>';
                        } else {
                            $table_data['status'] = $donor_data['status'];
                        }

						
						$data[] = $table_data;
					}
				}
		endwhile;
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
			case 'donor_name':
			case 'email':
			case 'campaign_name':
			case 'amount':
			case 'country':
			case 'payment':
			case 'status':
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
        $orderby = 'campaign_name';
        $order = 'asc';
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

}

?>