<?php
require_once ("person_controller.php");
class Customers extends Person_controller
{
	function __construct()
	{
		parent::__construct('customers');
	}

	function index($mode = 'default' , $search_page = '' , $sort_key = 1 , $per_page = 30)
	{
		$user_info = $this->Employee->get_logged_in_employee_info();
		if($mode == 'default')
		{
			$uri_segment = 6;
			$sort_key = $this->uri->segment(4);
			$per_page = $this->uri->segment(5);
			if($per_page == 0) $per_page = 30;
			if($sort_key > 4 || $sort_key < 1) $sort_key = 1;

			$data['total_rows'] = $this->Customer->count_all();
			$data['total_page'] = floor($data['total_rows'] / $per_page) + 1;
			$data['per_page'] = $per_page;
			$data['uri_segment'] = 6;

		}
		else if($mode == 'search')
		{
			$uri_segment = 7;
			$sort_key = $this->uri->segment(5);
			$per_page = $this->uri->segment(6);
			if($per_page == 0) $per_page = 30;

			if($sort_key > 4 || $sort_key < 1) $sort_key = 1;

			if($search_page == "12345678901234567890") $search = "";
			else $search = $search_page;

			$data['total_rows'] = $this->Customer->total_search_num_rows($search);
			$data['total_page'] = floor($data['total_rows'] / $per_page) + 1;

			$data['per_page'] = $per_page;
			$data['uri_segment'] = $uri_segment;
		}

		$data['controller_name']=strtolower(get_class());
		$data['form_width']=$this->get_form_width();

		if($mode == 'default')
			$data['manage_table'] =	get_customer_manage_table(
					$this->Customer->get_all(
							$data['per_page'] ,
							$this->uri->segment( $data['uri_segment']) ,
							$sort_key) ,
					$this ,
					$sort_key ,
					$user_info);
		else if($mode == 'search')
		{
			if($search_page == "12345678901234567890")
				$search = "";
			else
				$search = $search_page;

			$data['manage_table'] = get_customer_manage_table(
					$this->Customer->search(
							$search ,
							$data['per_page'] ,
							$this->uri->segment($data['uri_segment']) ,
							$sort_key) ,
					$this ,
					$sort_key ,
					$user_info);
			$data['search'] = $search;
		}

		$data['sort_key'] = $sort_key;
		$data['curd_page'] = $this->uri->segment($uri_segment) / $per_page + 1;
		$data['search_mode'] = $mode;
		$this->load->view('people/manage',$data);
	}

	/*
	Returns customer table data rows. This will be called with AJAX.
	*/
	function search()
	{
		$search = $this->input->post('search');
		$sort_key = $this->input->post('sort_key');
		$per_page = $this->input->post('per_page');
		if($search == "")
			$search_page = "12345678901234567890";
		else
			$search_page = $search;

		$user_info = $this->Employee->get_logged_in_employee_info();
		$total_rows = $this->Customer->total_search_num_rows($search);
		$total_page = floor($total_rows / $per_page) + 1;
		$uri_segment = 7;

		$data_rows = "search";
		$data_rows .= "********************";
		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'><span style='font-family:Arial;'>Show&nbsp;</span><select name='per_page' id='per_page' onchange=\"select_per_page('";
		$data_rows .= site_url("customers/index/");
		$data_rows .= "');\">";
		$data_rows .= "<option value='10' ";
		if($per_page == 10) $data_rows .= "selected='true'";
		$data_rows .= ">10</option><option value='25'";
		if($per_page == 25) $data_rows .= "selected='true'";
		$data_rows .= ">25</option><option value='30' ";
		if($per_page == 30) $data_rows .= "selected='true'";
		$data_rows .= ">30</option><option value='40' ";
		if($per_page == 40) $data_rows .= "selected='true'";
		$data_rows .= ">40</option><option value='50' ";
		if($per_page == 50) $data_rows .= "selected='true'";
		$data_rows .= ">50</option><option value='75' ";
		if($per_page == 75) $data_rows .= "selected='true'";
		$data_rows .= ">75</option><option value='100' ";
		if($per_page == 100) $data_rows .= "selected='true'";
		$data_rows .= ">100</option><option value='150' ";
		if($per_page == 150) $data_rows .= "selected='true'";
		$data_rows .= ">150</option><option value='200' ";
		if($per_page == 200) $data_rows .= "selected='true'";
		$data_rows .= ">200</option></select><span style='font-family:Arial;'>&nbsp;Rows&nbsp;Per&nbsp;Page</span></div>";

		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'><div class='pFirst pButton' onclick=\"first_page('";
		$data_rows .= site_url("customers/index/");
		$data_rows .= "');\"><span></span></div>";

		$data_rows .= "<div class='pPrev pButton' onclick=\"prev_page('";
		$data_rows .= site_url("customers/index/");
		$data_rows .= "');\"><span></span></div></div><div class='btnseparator'></div>";
		$data_rows .= "<div class='pGroup'><span class='pcontrol'>Page&nbsp;<input type='text' name='page' id='curd_page' value='1' size='4' class='product_search_cell_page' onkeyup=\"set_direct_page(event ,'";
		$data_rows .= site_url("customers/index/");
		$data_rows .= "');\">";
		$data_rows .= "&nbsp;of&nbsp;<span id='last_page_number'>$total_page</span></span></div><div class='btnseparator'></div>";
		$data_rows .= "<div><div class='pNext pButton' onclick=\"next_page('";
		$data_rows .= site_url("customers/index/");
		$data_rows .= "');\"><span></span></div><div class='pLast pButton' onclick=\"last_page('";
		$data_rows .= site_url("cucstomers/index/");
		$data_rows .= "');\"><span></span></div>";
		$data_rows .= "</div><div class='btnseparator'></div>";
/*
		$data_rows .= "<div style='float:right;'><img src='";
		$data_rows .= base_url();
		$data_rows .= "images/spinner_small.gif' alt='spinner' id='spinner' />";
		$data_rows .= form_open("customers/search",array('id'=>'search_form'));
		$data_rows .= "<input type='text' name ='search' id='search' class='cell1'/></form></div>";
*/
		$data_rows .= "********************";

		$data_rows .= get_customer_manage_table_data_rows(
				$this->Customer->search(
						$search ,
						$per_page ,
						$this->uri->segment($uri_segment) ,
						$sort_key) ,
				$this ,
				$user_info);
		echo $data_rows;
	}

	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
		$q = $this->input->post('term');
		$user_info = $this->Employee->get_logged_in_employee_info();
		$suggestions = $this->Customer->get_search_suggestions($q , 30 , $user_info);
		//echo implode("\n",$suggestions);
		echo json_encode($suggestions);
	}

	/*
	Loads the customer edit form
	*/
	function view($customer_id=-1)
	{

		$data['person_info']=$this->Customer->get_info($customer_id);
//		$data['mode'] = $this->uri->segment(3);
//		$data['customer_id'] = $this->uri->segment(4);
		$this->load->view("customers/form",$data);
	}

	function get_user_info()
	{
		$person_id = $this->input->post('person_id');
		$user_info = $this->Customer->get_info($person_id);
		$user_array = array();
		$user_array[] = $user_info->username;
		$user_array[] = $user_info->email;
		if($user_info->account_category == 2) $user_array[] = 1;
		else $user_array[] = 0;
		$user_array[] = $user_info->price_list005;
		$user_array[] = $user_info->price_list010;
		$user_array[] = $user_info->price_list011;
		$user_array[] = $user_info->price_list999;
		echo json_encode($user_array);
	}

	/*
	Inserts/updates a customer
	*/
	function save($customer_id=-1)
	{
		$customer_id = $this->input->post('person_id');
		if($customer_id == 0 || $customer_id == '') $customer_id = -1;
		$person_data = array(
		'first_name'=>$this->input->post('username'),
		'last_name'=>$this->input->post('last_name'),
		'email'=>$this->input->post('email'),
		'phone_number'=>$this->input->post('phone_number'),
		'address_1'=>$this->input->post('address_1'),
		'address_2'=>$this->input->post('address_2'),
		'city'=>$this->input->post('city'),
		'state'=>$this->input->post('state'),
		'zip'=>$this->input->post('zip'),
		'country'=>$this->input->post('country'),
		'comments'=>$this->input->post('comments')
		);

		//Password has been changed OR first time password set
		if($this->input->post('password')!='')
		{

			$customer_data = array(
				'username'=>$this->input->post('username'),
				'password'=>md5($this->input->post('password')),
				'account_number'=>$this->input->post('username'),
				'account_category'=> $this->input->post('admin_privilege') == '' ? 0:2,
				'price_list005'=>$this->input->post('price_list005')=='' ? 0:1,
				'price_list010'=>$this->input->post('price_list010')=='' ? 0:1,
				'price_list011'=>$this->input->post('price_list011')=='' ? 0:1,
				'price_list999'=>$this->input->post('price_list999')=='' ? 0:1
			);
		}
		else //Password not changed
		{
			$customer_data = array(
					'username'=>$this->input->post('username'),
					'account_number'=>$this->input->post('username'),
					'account_category'=> $this->input->post('admin_privilege') == '' ? 0:2,
					'price_list005'=>$this->input->post('price_list005')=='' ? 0:1,
					'price_list010'=>$this->input->post('price_list010')=='' ? 0:1,
					'price_list011'=>$this->input->post('price_list011')=='' ? 0:1,
					'price_list999'=>$this->input->post('price_list999')=='' ? 0:1
			);
		}
		if($this->Customer->save($person_data , $customer_data , $customer_id))
		{
			//New customer
			if($customer_id==-1)
			{
				echo json_encode(array('success'=>true,'message'=>$this->lang->line('customers_successful_adding').' '.
				$person_data['first_name'] , 'person_id'=>$customer_data['person_id']));
			}
			else //previous customer
			{
				echo json_encode(array('success'=>true,'message'=>$this->lang->line('customers_successful_updating').' '.
				$person_data['first_name'] , 'person_id'=>$customer_id));
			}
		}
		else//failure
		{
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('customers_error_adding_updating').' '.
			$person_data['first_name'] , 'person_id'=>-1));
		}
	}

	/*
	This deletes customers from the customers table
	*/
	function delete()
	{
		$customers_to_delete=$this->input->post('ids');

		if($this->Customer->delete_list($customers_to_delete))
		{
			echo json_encode(array('success'=>true,'message'=>$this->lang->line('customers_successful_deleted').' '.
			count($customers_to_delete).' '.$this->lang->line('customers_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('customers_cannot_be_deleted')));
		}
	}

	function excel()
	{
		$data = file_get_contents("import_customers.csv");
		$name = 'import_customers.csv';
		force_download($name, $data);
	}

	function excel_import()
	{
		$this->load->view("customers/excel_import", null);
	}

	function do_excel_import()
	{
		$msg = 'do_excel_import';
		$failCodes = array();
		if ($_FILES['file_path']['error']!=UPLOAD_ERR_OK)
		{
			$msg = $this->lang->line('items_excel_import_failed');
			echo json_encode( array('success'=>false,'message'=>$msg) );
			return;
		}
		else
		{
			if (($handle = fopen($_FILES['file_path']['tmp_name'], "r")) !== FALSE)
			{
				//Skip first row
				fgetcsv($handle);

				$i=1;
				while (($data = fgetcsv($handle)) !== FALSE)
				{
					$person_data = array(
					'first_name'=>$data[0],
					'last_name'=>$data[1],
					'email'=>$data[2],
					'phone_number'=>$data[3],
					'address_1'=>$data[4],
					'address_2'=>$data[5],
					'city'=>$data[6],
					'state'=>$data[7],
					'zip'=>$data[8],
					'country'=>$data[9],
					'comments'=>$data[10]
					);

					$customer_data=array(
					'account_number'=>$data[11]=='' ? null:$data[11],
					'taxable'=>$data[12]=='' ? 0:1,
					);

					if(!$this->Customer->save($person_data,$customer_data))
					{
						$failCodes[] = $i;
					}

					$i++;
				}
			}
			else
			{
				echo json_encode( array('success'=>false,'message'=>'Your upload file has no data or not in supported format.') );
				return;
			}
		}

		$success = true;
		if(count($failCodes) > 1)
		{
			$msg = "Most customers imported. But some were not, here is list of their CODE (" .count($failCodes) ."): ".implode(", ", $failCodes);
			$success = false;
		}
		else
		{
			$msg = "Import Customers successful";
		}

		echo json_encode( array('success'=>$success,'message'=>$msg) );
	}

	/*
	get the width for the add/edit form
	*/
	function get_form_width()
	{
		return 650;
	}

	function sort_user()
	{
		$sort_key = $this->input->post('sort_key');
		$search = $this->input->post('search');
		$search_mode = $this->input->post('search_mode');
		$per_page = $this->input->post('per_page');

		if($per_page == 0) $per_page = 30;

		$user_info = $this->Employee->get_logged_in_employee_info();

		if($search_mode == "default")
		{
			$total_rows = $this->Customer->total_search_num_rows($search);
			$total_page = floor($total_rows / $per_page) + 1;
			$uri_segment = 6;
		}
		else if($search_mode == "search")
		{
			if($search == "")
				$search_page = "12345678901234567890";
			else
				$search_page = $search;

			$total_rows = $this->Customer->total_search_num_rows($search);
			$uri_segment = 7;
			$total_page = floor($total_rows / $per_page) + 1;
		}

		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'><span style='font-family:Arial;'>Show&nbsp;</span><select name='per_page' id='per_page' onchange=\"select_per_page('";
		$data_rows .= site_url("customers/index/");
		$data_rows .= "');\">";
		$data_rows .= "<option value='10' ";
		if($per_page == 10) $data_rows .= "selected='true'";
		$data_rows .= ">10</option><option value='25'";
		if($per_page == 25) $data_rows .= "selected='true'";
		$data_rows .= ">25</option><option value='30' ";
		if($per_page == 30) $data_rows .= "selected='true'";
		$data_rows .= ">30</option><option value='40' ";
		if($per_page == 40) $data_rows .= "selected='true'";
		$data_rows .= ">40</option><option value='50' ";
		if($per_page == 50) $data_rows .= "selected='true'";
		$data_rows .= ">50</option><option value='75' ";
		if($per_page == 75) $data_rows .= "selected='true'";
		$data_rows .= ">75</option><option value='100' ";
		if($per_page == 100) $data_rows .= "selected='true'";
		$data_rows .= ">100</option><option value='150' ";
		if($per_page == 150) $data_rows .= "selected='true'";
		$data_rows .= ">150</option><option value='200' ";
		if($per_page == 200) $data_rows .= "selected='true'";
		$data_rows .= ">200</option></select><span style='font-family:Arial;'>&nbsp;Rows&nbsp;Per&nbsp;Page</span></div>";

		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'><div class='pFirst pButton' onclick=\"first_page('";
		$data_rows .= site_url("customers/index/");
		$data_rows .= "');\"><span></span></div>";

		$data_rows .= "<div class='pPrev pButton' onclick=\"prev_page('";
		$data_rows .= site_url("customers/index/");
		$data_rows .= "');\"><span></span></div></div><div class='btnseparator'></div>";
		$data_rows .= "<div class='pGroup'><span class='pcontrol'>Page&nbsp;<input type='text' name='page' id='curd_page' value='1' size='4' class='product_search_cell_page' onkeyup=\"set_direct_page(event ,'";
		$data_rows .= site_url("customers/index/");
		$data_rows .= "');\">";
		$data_rows .= "&nbsp;of&nbsp;<span id='last_page_number'>$total_page</span></span></div><div class='btnseparator'></div>";
		$data_rows .= "<div><div class='pNext pButton' onclick=\"next_page('";
		$data_rows .= site_url("customers/index/");
		$data_rows .= "');\"><span></span></div><div class='pLast pButton' onclick=\"last_page('";
		$data_rows .= site_url("customers/index/");
		$data_rows .= "');\"><span></span></div>";
		$data_rows .= "</div><div class='btnseparator'></div>";
/*
		$data_rows .= "<div style='float:right;'><img src='";
		$data_rows .= base_url();
		$data_rows .= "images/spinner_small.gif' alt='spinner' id='spinner' />";
		$data_rows .= form_open("customers/search",array('id'=>'search_form'));
		$data_rows .= "<input type='text' name ='search' id='search' class='cell1'/></form></div>";
*/

		//		$data_rows .= $this->pagination->create_links();
		$data_rows .= "********************";

		$data_rows .= get_customer_manage_table_data_rows(
				$this->Customer->search(
						$search ,
						$per_page ,
						$this->uri->segment($uri_segment) ,
						$sort_key) ,
				$this ,
				$user_info);
		echo $data_rows;

	}
}
?>