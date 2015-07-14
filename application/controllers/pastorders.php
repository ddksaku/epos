<?php
require_once ("secure_area.php");
require_once ("interfaces/idata_controller.php");
class Pastorders extends Secure_area implements iData_controller
{
	function __construct()
	{
		parent::__construct('pastorders');
	}

	function index($mode = 'default' , $search_page = '' , $sort_key = 3 , $per_page = 30)
	{
		$user_info = $this->Employee->get_logged_in_employee_info();

		if($mode == 'default')
		{
			$uri_segment = 6;
			$sort_key = $this->uri->segment(4);
			$per_page = $this->uri->segment(5);
			if($per_page == 0) $per_page = 30;


			if($sort_key > 6 || $sort_key < 1)
			{
				if($user_info->username == "admin") $sort_key = 3;
				else $sort_key = 1;
			}

			if($user_info->username == "admin")
			{
				$data['total_rows'] = $this->Pastorder->count_all_admin();
				$data['total_page'] = floor($data['total_rows'] / $per_page) + 1;
			}
			else
			{
				$data['total_rows'] = $this->Pastorder->count_all($user_info->person_id);
				$data['total_page'] = floor($data['total_rows'] / $per_page) + 1;

			}

			$data['per_page'] = $per_page;
			$data['uri_segment'] = 6;

		}
		else if($mode == "search")
		{
			$uri_segment = 7;
			$sort_key = $this->uri->segment(5);
			$per_page = $this->uri->segment(6);
			if($per_page == 0) $per_page = 30;

			if($sort_key > 6 || $sort_key < 1)
			{
				if($user_info->username == "admin") $sort_key = 3;
				else $sort_key = 1;
			}

			if($search_page == "12345678901234567890") $search = "";
			else $search = $search_page;

			$data['total_rows'] = $this->Product->total_search_count_all($search , $user_info);
			$data['total_page'] = floor($data['total_rows'] / $per_page) + 1;

			$data['per_page'] = $per_page;
			$data['uri_segment'] = $uri_segment;


		}
		$data['controller_name'] = strtolower(get_class());
		$data['form_width'] = $this->get_form_width();

		if($mode == 'default')
		{
			if($user_info->username == "admin")
				$data['manage_table'] = get_orders_manage_table_admin(
						$this->Pastorder->get_all_admin(
								$data['per_page'] ,
								$this->uri->segment( $data['uri_segment']) ,
								$sort_key) ,
						$this ,
						$sort_key ,
						$this->uri->segment( $data['uri_segment']));

			else
				$data['manage_table'] = get_orders_manage_table(
						$this->Pastorder->get_all(
								$data['per_page'],
								$this->uri->segment( $data['uri_segment']) ,
								$user_info->person_id ,
								$sort_key),
						$this ,
						$sort_key ,
						$this->uri->segment( $data['uri_segment']));

		}
		else if($mode == "search")
		{
			if($search_page == "12345678901234567890")
				$search = "";
			else
				$search = $search_page;
			if($user_info->username == "admin")
				$data['manage_table'] = get_orders_manage_table_admin(
						$this->Pastorder->search_admin(
								$search ,
								$data['per_page'],
								$this->uri->segment( $data['uri_segment']) , $sort_key) ,
						$this ,
						$sort_key ,
						$this->uri->segment( $data['uri_segment']));
			else
				$data['manage_table'] = get_orders_manage_table(
						$this->Pastorder->search(
								$search ,
								$user_info ,
								$data['per_page'] ,
								$this->uri->segment($data['uri_segment']) ,
								$sort_key) ,
						$this ,
						$sort_key ,
						$this->uri->segment( $data['uri_segment']));

			$data['search'] = $search;
		}

		$data['sort_key'] = $sort_key;
		$data['curd_page'] = $this->uri->segment($uri_segment) / $per_page + 1;
		$data['search_mode'] = $mode;
		$data['user_info'] = $user_info;
		$this->load->view('pastorders/manage',$data);
	}




	function search()
	{
		$search = $this->input->post('search');
		$sort_key = $this->input->post('sort_key');
		$per_page = $this->input->post('per_page');
		if($search == "")
			$search_page = "12345678901234567890";
		else
			$search_page = $search;

		$search1 = explode("/", $search);

		if(strlen($search1[1]) != 0 && strlen($search) == 10)
		{
			$search = $search1[0].$search1[1].$search1[2];
			$search_page = $search;
		}


		$user_info = $this->Employee->get_logged_in_employee_info();
		$total_rows = $this->Pastorder->total_search_count_all($search , $user_info);

		$total_page = floor($total_rows / $per_page) + 1;
		$uri_segment = 7;


		$data_rows = "search";
		$data_rows .= "********************";

		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'></span>Show&nbsp;</span><select name='per_page' id='per_page' onchange=\"select_per_page('";
		$data_rows .= site_url("pastorders/index/");
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

		$data_rows .= "<div class='btnseparator' style='fload:right;'></div><div class='pGroup' style='float:right;'>";
		$data_rows .= "<div class='pNext pButton' onclick=\"next_page('";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\"><span></span></div><div class='pLast pButton' onclick=\"last_page('";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\"><span></span></div></div><div class='btnseparator' style='float:right;'></div><div class='pGroup' style='float:right;'>";
		$data_rows .= "<span class='pcontrol'>Page&nbsp;<input type='text' name='page' id='curd_page' value='1' size='4' class='product_search_cell_page'";
		$data_rows .= "onkeyup=\"set_direct_page(event , '";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\">&nbsp;of&nbsp;<span id='last_page_number'>".$total_page."</span></span></div><div class='btnseparator' style='float:right;'></div>";
		$data_rows .= "<div class='pGroup' style='float:right;'><div class='pFirst pButton' onclick=\"first_page('";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\"><span></span></div><div class='pPrev pButton' onclick=\"prev_page('";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\"><span></span></div></div><div class='btnseparator' style='float:right;'></div>";

/*
		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'><div class='pFirst pButton' onclick=\"first_page('";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\"><span></span></div>";

		$data_rows .= "<div class='pPrev pButton' onclick=\"prev_page('";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\"><span></span></div></div><div class='btnseparator'></div>";
		$data_rows .= "<div class='pGroup'><span class='pcontrol'>Page&nbsp;<input type='text' name='page' id='curd_page' value='1' size='4' class='product_search_cell_page' onkeyup=\"set_direct_page(event ,'";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\">";
		$data_rows .= "&nbsp;of&nbsp;<span id='last_page_number'>$total_page</span></span></div><div class='btnseparator'></div>";
		$data_rows .= "<div><div class='pNext pButton' onclick=\"next_page('";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\"><span></span></div><div class='pLast pButton' onclick=\"last_page('";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\"><span></span></div>";
		$data_rows .= "</div><div class='btnseparator'></div>";
*/
		//$data_rows .= $this->pagination->create_links();
		$data_rows .= "********************";
		if($user_info->username == "admin")
			$data_rows .= get_orders_manage_table_data_rows_admin(
					$this->Pastorder->search_admin(
							$search ,
							$per_page ,
							0 ,
							$sort_key) ,
					$this ,
					$this->uri->segment($uri_segment));
		else
			$data_rows .= get_orders_manage_table_data_rows(
					$this->Pastorder->search(
							$search0 ,
							$user_info ,
							$per_page ,
							0 ,
							$sort_key) ,
					$this ,
					$this->uri->segment($uri_segment));

		echo $data_rows;

	}

	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
		$user_info = $this->Employee->get_logged_in_employee_info();
		$suggestions = $this->Pastorder->get_search_suggestions($this->input->post('q'),$this->input->post('limit') , $user_info);
		echo implode("\n",$suggestions);
	}


	function get_row()
	{
	}

	function view($order_id=-1)
	{
		$user_info = $this->Employee->get_logged_in_employee_info();
		$completed = $this->Pastorder->get_order_completed($order_id);
		$opened = $this->Pastorder->get_order_opened($order_id);
		if($completed == 0)
			$data1 = "<table cellspacing='1px' style='width:98%; margin: 5px 5px 5px 5px; border-left: 1px solid gray; border-right:1px solid gray; border-bottom:2px solid gray;'><thead><tr style='background-color:#11ccdd;'><th>Product</th><th>Description</th><th>Size</th><th>UOS</th><th>Price</th><th>Qty</th></tr></thead>";
		else if($completed == 1)
			$data1 = "<table cellspacing='1px' style='width:98%; margin: 5px 5px 5px 5px; border-left: 1px solid gray; border-right:1px solid gray; border-bottom:2px solid gray;'><thead><tr style='background-color:#11ccdd;'><th>Product</th><th>Description</th><th>Size</th><th>UOS</th><th>Price</th><th>Qty</th><th>&nbsp;</th></tr></thead>";
		$res_order = $this->Pastorder->get_order_product($order_id , $completed);

		if($opened == 1)
			$data1 .= "<tbody><tr><td colspan='6' style='text-align:center;'><span style='color:#FF0000;'>You have been editing this order.</span></td></tr></tbody></table>";
		else
			$data1 .= $res_order;

		$data['manage_table'] = $data1;
		$data['completed'] = $completed;
		$data['order_id'] = $order_id;
		$data['user_info'] = $user_info;
		$data['controller_name'] = strtolower(get_class());
		$this->load->view("pastorders/form",$data);

	}

	function get_order()
	{
		$user_info = $this->Employee->get_logged_in_employee_info();
		$order_id = $this->input->post('order_id');
		$completed = $this->Pastorder->get_order_completed($order_id);
		$opened = $this->Pastorder->get_order_opened($order_id);
		if($completed == 0)
			$data1 = "<table cellspacing='1px' style='width:98%; margin: 5px 5px 5px 5px; border-left: 1px solid gray; border-right:1px solid gray; border-bottom:2px solid gray;'><thead><tr style='background-color:#11ccdd;'><th>Product</th><th>Description</th><th>Size</th><th>UOS</th><th>Price</th><th>Qty</th></tr></thead>";
		else if($completed == 1)
			$data1 = "<table cellspacing='1px' style='width:98%; margin: 5px 5px 5px 5px; border-left: 1px solid gray; border-right:1px solid gray; border-bottom:2px solid gray;'><thead><tr style='background-color:#11ccdd;'><th>Product</th><th>Description</th><th>Size</th><th>UOS</th><th>Price</th><th>Qty</th><th>&nbsp;</th></tr></thead>";
		$res_order = $this->Pastorder->get_order_product($order_id , $completed);

		if($opened == 1)
			$data1 .= "<tbody><tr><td colspan='6' style='text-align:center;'><span style='color:#FF0000;'>You have been editing this order.</span></td></tr></tbody></table>";
		else
			$data1 .= $res_order;

		echo json_encode(array('manage_table'=>$data1 , 'completed'=>$completed));
	}

	function save($item_id=-1)
	{

	}


	function delete()
	{
	}


	/*
	get the width for the add/edit form
	*/
	function get_form_width()
	{
		return 640;
	}

	function get_person($person_id)
	{
		return $this->Employee->get_info($person_id);
	}

	function get_total_amount($order_id)
	{
		return $this->Pastorder->get_total_amount($order_id);
	}

	function sort_order()
	{
		$sort_key = $this->input->post('sort_key');
		$search = $this->input->post('search');
		$search_mode = $this->input->post('search_mode');
		$per_page = $this->input->post('per_page');

		if($per_page == 0) $per_page = 30;

		$user_info = $this->Employee->get_logged_in_employee_info();

		if($search_mode == "default")
		{
			if($user_info->username == "admin")
				$total_rows = $this->Pastorder->count_all_admin();
			else
				$total_rows = $this->Pastorder->count_all($user_info->person_id);

			$total_page = floor($total_rows / $per_page) + 1;
			$uri_segment = 6;
			$total_page = floor($total_rows / $per_page) + 1;
		}
		else if($search_mode == "search")
		{
			if($search == "")
				$search_page = "12345678901234567890";
			else
				$search_page = $search;

			$search1 = explode("/", $search);

			if(strlen($search1[1]) != 0 && strlen($search) == 10)
			{
				$search = $search1[0].$search1[1].$search1[2];
				$search_page = $search;
			}


			$total_rows = $this->Pastorder->total_search_count_all($search , $user_info);
			$uri_segment = 7;
			$total_page = floor($total_rows / $per_page) + 1;
		}




		//		$data_rows = $search_mode;
		//		$data_rows .= "********************";

		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'><span style='font-family:Arial;'>Show&nbsp;</span><select name='per_page' id='per_page' onchange=\"select_per_page('";
		$data_rows .= site_url("pastorders/index/");
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

		$data_rows .= "<div class='btnseparator' style='fload:right;'></div><div class='pGroup' style='float:right;'>";
		$data_rows .= "<div class='pNext pButton' onclick=\"next_page('";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\"><span></span></div><div class='pLast pButton' onclick=\"last_page('";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\"><span></span></div></div><div class='btnseparator' style='float:right;'></div><div class='pGroup' style='float:right;'>";
		$data_rows .= "<span class='pcontrol'>Page&nbsp;<input type='text' name='page' id='curd_page' value='1' size='4' class='product_search_cell_page'";
		$data_rows .= "onkeyup=\"set_direct_page(event , '";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\">&nbsp;of&nbsp;<span id='last_page_number'>".$total_page."</span></span></div><div class='btnseparator' style='float:right;'></div>";
		$data_rows .= "<div class='pGroup' style='float:right;'><div class='pFirst pButton' onclick=\"first_page('";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\"><span></span></div><div class='pPrev pButton' onclick=\"prev_page('";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\"><span></span></div></div><div class='btnseparator' style='float:right;'></div>";
/*
		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'><div class='pFirst pButton' onclick=\"first_page('";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\"><span></span></div>";

		$data_rows .= "<div class='pPrev pButton' onclick=\"prev_page('";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\"><span></span></div></div><div class='btnseparator'></div>";
		$data_rows .= "<div class='pGroup'><span class='pcontrol'>Page&nbsp;<input type='text' name='page' id='curd_page' value='1' size='4' class='product_search_cell_page' onkeyup=\"set_direct_page(event ,'";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\">";
		$data_rows .= "&nbsp;of&nbsp;<span id='last_page_number'>$total_page</span></span></div><div class='btnseparator'></div>";
		$data_rows .= "<div><div class='pNext pButton' onclick=\"next_page('";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\"><span></span></div><div class='pLast pButton' onclick=\"last_page('";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\"><span></span></div>";
		$data_rows .= "</div><div class='btnseparator'></div>";
*/
		//		$data_rows .= $this->pagination->create_links();
		$data_rows .= "********************";

		if($user_info->username == "admin")
			$data_rows .= get_orders_manage_table_data_rows_admin(
					$this->Pastorder->search_admin(
							$search ,
							$per_page ,
							0 ,
							$sort_key) ,
					$this ,
					$this->uri->segment($uri_segment));
		else
			$data_rows .= get_orders_manage_table_data_rows(
					$this->Pastorder->search(
							$search ,
							$user_info ,
							$per_page ,
							0 ,
							$sort_key) ,
					$this ,
					$this->uri->segment($uri_segment));

		echo $data_rows;

	}

	function continue_order($mode , $order_id)
	{
		if($mode == 1)
		{
			$this->Pastorder->set_my_trolley($order_id);
		}

		redirect("orders");

	}

	function add_to_cart()
	{
		$prod_id = $this->input->post('prod_id');
		$quantity = $this->input->post('quantity');
		$order_id = $this->input->post('order_id');

		$user_info = $this->Employee->get_logged_in_employee_info();
		//$this->Pastorder->add_to_cart($prod_id , $quantity , $user_info->person_id);
		echo json_encode($this->Pastorder->add_to_cart1($prod_id , $quantity , $user_info->person_id , $order_id));
	}
}
?>