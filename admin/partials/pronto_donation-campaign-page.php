<?php

if(is_admin())
{
    new Pronto_Donation_Campaign_WP_list_Table();
}

class Pronto_Donation_Campaign_WP_list_Table {

	public function __construct() {
        add_action( 'admin_menu', array($this, 'pronto_donation_campaign_table_page' ));
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
            'donation_date' => 'Date',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'campaign_name' => 'Campaign Name',
            'amount' => 'Amount',
            'country' => 'Country'
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

    function csv_to_array($filename='', $delimiter=',')
	{
	    if(!file_exists($filename) || !is_readable($filename))
	        return FALSE;

	    $header = NULL;
	    $data = array();
	    if (($handle = fopen($filename, 'r')) !== FALSE)
	    {
	        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
	        {
	            if(!$header)
	                $header = $row;
	            else
	                $data[] = array_combine($header, $row);
	        }
	        fclose($handle);
	    }
	    return $data;
	}

    private function table_data()
    {
 		$data = array();


 		date_default_timezone_set('Australia/Melbourne');
		$date = date('M d, Y h:i:s a', time());

		$data_sample = array(
				array(
					'donation_date' => $date,
					'first_name' => 'Danryl',
					'last_name' => 'Carpio',
					'campaign_name' => 'LMFB Campaign',
					'amount' =>  50000,
					'country' => 'Australia'
				),
				array(
					'donation_date' => $date,
					'first_name' => 'Marvin',
					'last_name' => 'Aya-ay',
					'campaign_name' => 'LMFB Campaign',
					'amount' => 755600,
					'country' => 'Australia'
				),
				array(
					'donation_date' => $date,
					'first_name' => 'Junjie',
					'last_name' => 'Canonio',
					'campaign_name' => 'LMFB Campaign',
					'amount' => 7556232,
					'country' => 'Australia'
				),
				array(
					'donation_date' => $date,
					'first_name' => 'John Rendhon',
					'last_name' => 'Gerona',
					'campaign_name' => 'LMFB Campaign',
					'amount' => 522872,
					'country' => 'Australia'
				),
			 	array(
					'donation_date' => $date,
					'first_name' => 'John',
					'last_name' => 'Snow',
					'campaign_name' => 'LMFB Campaign',
					'amount' =>  665520,
					'country' => 'Singapore'
				),
				array(
					'donation_date' => $date,
					'first_name' => 'Sansa',
					'last_name' => 'Stark',
					'campaign_name' => 'LMFB Campaign',
					'amount' => 755600,
					'country' => 'USA'
				),
				array(
					'donation_date' => $date,
					'first_name' => 'Super',
					'last_name' => 'Mario',
					'campaign_name' => 'LMFB Campaign',
					'amount' => 80330,
					'country' => 'Australia'
				),
				array(
					'donation_date' => $date,
					'first_name' => 'Jet',
					'last_name' => 'Cow',
					'campaign_name' => 'LMFB Campaign',
					'amount' => 96000,
					'country' => 'Australia'
				),
				array(
					'donation_date' => $date,
					'first_name' => 'Joe',
					'last_name' => 'Smith',
					'campaign_name' => 'LMFB Campaign',
					'amount' => 50000,
					'country' => 'USA'
				),
				array(
					'donation_date' => $date,
					'first_name' => 'Lebron',
					'last_name' => 'James',
					'campaign_name' => 'LMFB Campaign',
					'amount' => 700000,
					'country' => 'USA'
				),
				array(
					'donation_date' => $date,
					'first_name' => 'Carmelo',
					'last_name' => 'Anthony',
					'campaign_name' => 'LMFB Campaign',
					'amount' => 600000,
					'country' => 'USA'
				)
			);

 		if(!empty($data_sample)) {

 			foreach ($data_sample as $data_value) {
 				$table_data = array();
 				$table_data['donation_date'] = $data_value['donation_date'];
 				$table_data['first_name'] = $data_value['first_name'];
 				$table_data['last_name'] = $data_value['last_name'];
 				$table_data['campaign_name'] = $data_value['campaign_name'];
 				$table_data['amount'] = number_format( (int) $data_value['amount'], 2 , '.', ',' );
 				$table_data['country'] = $data_value['country'];
 				$data[] = $table_data;
 			}
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
        	case 'donation_date':
			case 'first_name':
			case 'last_name':
			case 'campaign_name':
			case 'amount':
			case 'country':
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
        $orderby = 'donation_date';
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

}

?>