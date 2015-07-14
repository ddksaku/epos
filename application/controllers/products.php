<?php
require_once ("secure_area.php");
require_once ("interfaces/idata_controller.php");
class Products extends Secure_area implements iData_controller
{
	function __construct()
	{
		parent::__construct('products');
	}

	function index($mode = 'default' , $search0_page = '' , $search1_page = '' , $search2_page = '' , $sort_key = 3 , $category_id = 0 , $per_page = 30)
	{
		$user_info = $this->Employee->get_logged_in_employee_info();

		if($mode == 'default')
		{
			$uri_segment = 6;
			$sort_key = $this->uri->segment(4);
			$category_id = $this->uri->segment(5);
			$per_page = $this->uri->segment(7);

			if($per_page == 0)
				$per_page = 30;

			if($sort_key > 6 || $sort_key < 1) $sort_key = 3;
			$config['base_url'] = site_url('/products/index/default/'.$sort_key."//".$category_id."//");
			$data['base_url'] = site_url('/products/index/default/'.$sort_key."//".$category_id."//");
			if($user_info->username == "admin")
			{
				//$config['total_rows'] = $this->Product->count_all_admin();
				//$data['total_rows'] = $this->Product->count_all_admin();
				$config['total_rows'] = $this->Product->count_all_admin_category($category_id);
				$data['total_rows'] = $this->Product->count_all_admin_category($category_id);
				$data['total_page'] = floor($data['total_rows'] / $per_page) + 1;
			}
			else
			{
				//$config['total_rows'] = $this->Product->count_all($user_info);
				//$data['total_rows'] = $this->Product->count_all($user_info);
				$config['total_rows'] = $this->Product->count_all_category($user_info , $category_id);
				$data['total_rows'] = $this->Product->count_all_category($user_info , $category_id);
				$data['total_page'] = floor($data['total_rows'] / $per_page) + 1;
			}
			$config['per_page'] = $per_page;
			$config['uri_segment'] = 6;

			$data['per_page'] = $per_page;
			$data['uri_segment'] = 6;


		}
		else if($mode == 'search')
		{
			$per_page = 30;
			$uri_segment = 9;
			$sort_key = $this->uri->segment(7);
			$category_id = $this->uri->segment(8);
			$per_page = $this->uri->segment(10);
			if($per_page == 0) $per_page = 30;
			if($sort_key > 6 || $sort_key < 1) $sort_key = 3;
			$config['base_url'] = site_url("products/index/search/".$search0_page."//".$search1_page."//".$search2_page."//".$sort_key."//".$category_id."//");
			$data['base_url'] = site_url("products/index/search/".$search0_page."//".$search1_page."//".$search2_page."//".$sort_key."//".$category_id."//");
			if($search0_page == "12345678901234567890")
				$search0 = "";
			else
				$search0 = $search0_page;

			if($search1_page == "12345678901234567890")
				$search1 = "";
			else
				$search1 = $search1_page;

			if($search2_page == "12345678901234567890")
				$search2 = "";
			else
				$search2 = $search2_page;

			//$config['total_rows'] = $this->Product->total_search_num_rows($search0 , $search1 , $search2 , $user_info);
			//$data['total_rows'] = $this->Product->total_search_num_rows($search0 , $search1 , $search2 , $user_info);
			$config['total_rows'] = $this->Product->total_search_num_rows_category($search0 , $search1 , $search2 , $user_info , $category_id);
			$data['total_rows'] = $this->Product->total_search_num_rows_category($search0 , $search1 , $search2 , $user_info , $category_id);
			$data['total_page'] = floor($data['total_rows'] / $per_page) + 1;
			$config['per_page'] = $per_page;
			$config['uri_segment'] = 9;

			$data['per_page'] = $per_page;
			$data['uri_segment'] = 9;
		}



		//$config['use_page_numbers'] = TRUE;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = '<<';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '>>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['prev_link'] = '<';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li><b>';
        $config['cur_tag_close'] = '</b></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);


		$data['controller_name'] = strtolower(get_class());
		$data['form_width'] = $this->get_form_width();

		if($mode == 'default')
		{
			if($user_info->username == "admin")
				$data['manage_table'] = get_products_manage_table($this->Product->get_all_admin_category($config['per_page'] , $this->uri->segment($config['uri_segment']) , $sort_key , $category_id) , $this , $sort_key);
				//$data['manage_table'] = get_products_manage_table($this->Product->get_all_admin($config['per_page'] , $this->uri->segment($config['uri_segment']) , $sort_key) , $this , $sort_key);
			else
				$data['manage_table'] = get_products_manage_table( $this->Product->get_all_category( $config['per_page'] , $this->uri->segment($config['uri_segment']) , $user_info , $sort_key , $category_id) , $this , $sort_key);
				//$data['manage_table'] = get_products_manage_table( $this->Product->get_all( $config['per_page'] , $this->uri->segment($config['uri_segment']) , $user_info , $sort_key) , $this , $sort_key);
		}
		else if($mode == 'search')
		{
			if($search0_page == "12345678901234567890")
				$search0 = "";
			else
				$search0 = $search0_page;

			if($search1_page == "12345678901234567890")
				$search1 = "";
			else
				$search1 = $search1_page;

			if($search2_page == "12345678901234567890")
				$search2 = "";
			else
				$search2 = $search2_page;


			//$data['manage_table'] = get_products_manage_table($this->Product->search($search0 , $search1 , $search2 , $user_info , $config['per_page'] , $this->uri->segment($config['uri_segment']) , $sort_key) , $this , $sort_key);
			//$data['manage_table'] = get_products_manage_table($this->Product->search($search0 , $search1 , $search2 , $user_info , $config['per_page'] , $this->uri->segment($config['uri_segment']) , $sort_key , $category_id) , $this , $sort_key);
			$data['manage_table'] = get_products_manage_table($this->Product->search_category($search0 , $search1 , $search2 , $user_info , $config['per_page'] , $this->uri->segment($config['uri_segment']) , $sort_key , $category_id) , $this , $sort_key);
			$data['search0'] = $search0;
			$data['search1'] = $search1;
			$data['search2'] = $search2;

		}

		$data['sort_key'] = $sort_key;
		$data['curd_page'] = $this->uri->segment($uri_segment) / $per_page + 1;
		$data['categories'] = $this->Product->get_all_categories($category_id);
		$data['category_id'] = $category_id;
		$data['search_mode'] = $mode;


		$this->load->view('products/manage' , $data);
	}



	function refresh()
	{
		$low_inventory=$this->input->post('low_inventory');
		$is_serialized=$this->input->post('is_serialized');
		$no_description=$this->input->post('no_description');

		$data['search_section_state']=$this->input->post('search_section_state');
		$data['low_inventory']=$this->input->post('low_inventory');
		$data['is_serialized']=$this->input->post('is_serialized');
		$data['no_description']=$this->input->post('no_description');
		$data['controller_name']=strtolower(get_class());
		$data['form_width']=$this->get_form_width();
		$data['manage_table']=get_items_manage_table($this->Item->get_all_filtered($low_inventory,$is_serialized,$no_description),$this);
		$this->load->view('items/manage',$data);
	}

	function find_item_info()
	{
		$item_number=$this->input->post('scan_item_number');
		echo json_encode($this->Item->find_item_info($item_number));
	}

	function search()
	{
		$search0 = $this->input->post('search0');
		$search1 = $this->input->post('search1');
		$search2 = $this->input->post('search2');
		$sort_key = $this->input->post('sort_key');
		$category_id = $this->input->post('category_id');
		$per_page = $this->input->post('per_page');

		if($search0 == "")
			$search0_page = "12345678901234567890";
		else
			$search0_page = $search0;

		if($search1 == "")
			$search1_page = "12345678901234567890";
		else
			$search1_page = $search1;

		if($search2 == "")
			$search2_page = "12345678901234567890";
		else
			$search2_page = $search2;
		$user_info = $this->Employee->get_logged_in_employee_info();

		$config['base_url'] = site_url("products/index/search/".$search0_page."//".$search1_page."//".$search2_page."//".$sort_key."//".$category_id."//");
		$config['total_rows'] = $this->Product->total_search_num_rows_category($search0 , $search1 , $search2 , $user_info , $category_id);
		$total_rows = $config['total_rows'];
		$total_page = floor($total_rows / $per_page) + 1;
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 9;

		//$config['use_page_numbers'] = TRUE;
		$config['full_tag_open'] = '<ul>';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = '<<';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = '>>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['prev_link'] = '<';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li><b>';
		$config['cur_tag_close'] = '</b></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);
		$data_rows = "search";
		$data_rows .= "********************";

		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'><span style='font-family:Arial;'>Show&nbsp;&nbsp;</span><select name='per_page' id='per_page' onchange=\"select_per_page('";
		$data_rows .= site_url("products/index/");
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

		$data_rows .= "<div class='btnseparator' style='float:right;'></div>";
		$data_rows .= "<div class='pGroup' style='float:right;'>";
		$data_rows .= "<div class='pNext pButton' onclick=\"pNext('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div>";
		$data_rows .= "<div class='pLast pButton' onclick=\"pLast('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div>";
		$data_rows .= "</div><div class='btnseparator' style='float:right;'></div><div class='pGroup' style='float:right;'>";
		$data_rows .= "<span class='pcontrol'>Page&nbsp;";
		$data_rows .= "<input type='text' name='page' id='curd_page' value='1' size='4' class='product_search_cell_page' onkeyup=\"set_direct_page(event , '";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\">&nbsp;of&nbsp;<span id='last_page_number'>".$total_page."</span></span></div><div class='btnseparator' style='float:right;'></div>";
		$data_rows .= "<div class='pGroup' style='float:right;'><div class='pFirst pButton' onclick=\"pFirst('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div><div class='pPrev pButton'  onclick=\"pPrev('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div></div><div class='btnseparator' style='float:right;'></div></div>";





/*
		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'><div class='pFirst pButton' onclick=\"pFirst('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div>";

		$data_rows .= "<div class='pPrev pButton' onclick=\"pPrev('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div></div><div class='btnseparator'></div>";
		$data_rows .= "<div class='pGroup'><span class='pcontrol'>Page&nbsp;<input type='text' name='page' id='curd_page' value='1' size='4' class='product_search_cell_page' onkeyup=\"set_direct_page(event ,'";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\">";
		$data_rows .= "&nbsp;of&nbsp;<span id='last_page_number'>$total_page</span></span></div><div class='btnseparator'></div>";
		$data_rows .= "<div><div class='pNext pButton' onclick=\"pNext('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div><div class='pLast pButton' onclick=\"pLast('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div>";
		$data_rows .= "</div><div class='btnseparator'></div>";
*/


		//$data_rows .= $this->pagination->create_links();
		$data_rows .= "********************";
		//$data_rows .= get_products_manage_table_data_rows($this->Product->search($search0 , $search1 , $search2 , $user_info , $config['per_page'] , $this->uri->segment($config['uri_segment']) , $sort_key) , $this);
		$data_rows .= get_products_manage_table_data_rows($this->Product->search_category($search0 , $search1 , $search2 , $user_info , $config['per_page'] , 0 , $sort_key , $category_id) , $this);
		echo $data_rows;
	}

	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
		$suggestions = $this->Item->get_search_suggestions($this->input->post('q'),$this->input->post('limit'));
		echo implode("\n",$suggestions);
	}

	function suggest0()
	{
		$user_info = $this->Employee->get_logged_in_employee_info();
		$suggestions = $this->Product->get_search_suggestions0($this->input->post('q'),$this->input->post('limit') , $user_info);
		echo implode("\n",$suggestions);

	}


	function suggest1()
	{
		$user_info = $this->Employee->get_logged_in_employee_info();
		$suggestions = $this->Product->get_search_suggestions1($this->input->post('q'),$this->input->post('limit') , $user_info);
		echo implode("\n",$suggestions);
	}


	function suggest2()
	{
		$q = $this->input->post('term');
		$user_info = $this->Employee->get_logged_in_employee_info();
		$suggestions = $this->Product->get_search_suggestions2($q , 30 , $user_info);
		//echo json_encode(implode(",",$suggestions));
		echo json_encode($suggestions);
	}

	function item_search()
	{

		$suggestions = $this->Item->get_item_search_suggestions($this->input->post('q'),$this->input->post('limit'));
		echo implode("\n",$suggestions);
	}

	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest_category()
	{
		$suggestions = $this->Item->get_category_suggestions($this->input->post('q'));
		echo implode("\n",$suggestions);
	}

	function get_row()
	{
		$item_id = $this->input->post('row_id');
		$data_row=get_item_data_row($this->Item->get_info($item_id),$this);
		echo $data_row;
	}

	function view($item_id=-1)
	{
		$data['ftp_location'] = $this->Product->get_ftp_location();
		$this->load->view("products/form",$data);
	}

	//Ramel Inventory Tracking
	function inventory($item_id=-1)
	{
		$data['item_info']=$this->Item->get_info($item_id);
		$this->load->view("items/inventory",$data);
	}

	function count_details($item_id=-1)
	{
		$data['item_info']=$this->Item->get_info($item_id);
		$this->load->view("items/count_details",$data);
	}

	function generate_barcodes($product_ids)
	{
		$result = array();

		$product_ids = explode(':', $product_ids);
		foreach ($product_ids as $product_id)
		{
			$product_info = $this->Product->get_info($product_id);

			$result[] = array('prod_desc'=>$product_info->prod_desc, 'wholesale'=>$product_info->wholesale , 'retail'=>$product_info->retail);
		}

		$data['products'] = $result;
		$this->load->view("barcode_sheet", $data);
	}

	function bulk_edit()
	{
		$data = array();
		$suppliers = array('' => $this->lang->line('items_none'));
		foreach($this->Supplier->get_all()->result_array() as $row)
		{
			$suppliers[$row['person_id']] = $row['first_name'] .' '. $row['last_name'];
		}
		$data['suppliers'] = $suppliers;
		$data['allow_alt_desciption_choices'] = array(
			''=>$this->lang->line('items_do_nothing'),
			1 =>$this->lang->line('items_change_all_to_allow_alt_desc'),
			0 =>$this->lang->line('items_change_all_to_not_allow_allow_desc'));

		$data['serialization_choices'] = array(
			''=>$this->lang->line('items_do_nothing'),
			1 =>$this->lang->line('items_change_all_to_serialized'),
			0 =>$this->lang->line('items_change_all_to_unserialized'));
		$this->load->view("items/form_bulk", $data);
	}

	function save($item_id=-1)
	{
		$item_data = array(
		'name'=>$this->input->post('name'),
		'description'=>$this->input->post('description'),
		'category'=>$this->input->post('category'),
		'supplier_id'=>$this->input->post('supplier_id')=='' ? null:$this->input->post('supplier_id'),
		'item_number'=>$this->input->post('item_number')=='' ? null:$this->input->post('item_number'),
		'cost_price'=>$this->input->post('cost_price'),
		'unit_price'=>$this->input->post('unit_price'),
		'quantity'=>$this->input->post('quantity'),
		'reorder_level'=>$this->input->post('reorder_level'),
		'location'=>$this->input->post('location'),
		'allow_alt_description'=>$this->input->post('allow_alt_description'),
		'is_serialized'=>$this->input->post('is_serialized')
		);

		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$cur_item_info = $this->Item->get_info($item_id);


		if($this->Item->save($item_data,$item_id))
		{
			//New item
			if($item_id==-1)
			{
				echo json_encode(array('success'=>true,'message'=>$this->lang->line('items_successful_adding').' '.
				$item_data['name'],'item_id'=>$item_data['item_id']));
				$item_id = $item_data['item_id'];
			}
			else //previous item
			{
				echo json_encode(array('success'=>true,'message'=>$this->lang->line('items_successful_updating').' '.
				$item_data['name'],'item_id'=>$item_id));
			}

			$inv_data = array
			(
				'trans_date'=>date('Y-m-d H:i:s'),
				'trans_items'=>$item_id,
				'trans_user'=>$employee_id,
				'trans_comment'=>$this->lang->line('items_manually_editing_of_quantity'),
				'trans_inventory'=>$cur_item_info ? $this->input->post('quantity') - $cur_item_info->quantity : $this->input->post('quantity')
			);
			$this->Inventory->insert($inv_data);
			$items_taxes_data = array();
			$tax_names = $this->input->post('tax_names');
			$tax_percents = $this->input->post('tax_percents');
			for($k=0;$k<count($tax_percents);$k++)
			{
				if (is_numeric($tax_percents[$k]))
				{
					$items_taxes_data[] = array('name'=>$tax_names[$k], 'percent'=>$tax_percents[$k] );
				}
			}
			$this->Item_taxes->save($items_taxes_data, $item_id);
		}
		else//failure
		{
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('items_error_adding_updating').' '.
			$item_data['name'],'item_id'=>-1));
		}

	}

	//Ramel Inventory Tracking
	function save_inventory($item_id=-1)
	{
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$cur_item_info = $this->Item->get_info($item_id);
		$inv_data = array
		(
			'trans_date'=>date('Y-m-d H:i:s'),
			'trans_items'=>$item_id,
			'trans_user'=>$employee_id,
			'trans_comment'=>$this->input->post('trans_comment'),
			'trans_inventory'=>$this->input->post('newquantity')
		);
		$this->Inventory->insert($inv_data);

		//Update stock quantity
		$item_data = array(
		'quantity'=>$cur_item_info->quantity + $this->input->post('newquantity')
		);
		if($this->Item->save($item_data,$item_id))
		{
			echo json_encode(array('success'=>true,'message'=>$this->lang->line('items_successful_updating').' '.
			$cur_item_info->name,'item_id'=>$item_id));
		}
		else//failure
		{
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('items_error_adding_updating').' '.
			$cur_item_info->name,'item_id'=>-1));
		}

	}//---------------------------------------------------------------------Ramel

	function bulk_update()
	{
		$items_to_update=$this->input->post('item_ids');
		$item_data = array();

		foreach($_POST as $key=>$value)
		{
			//This field is nullable, so treat it differently
			if ($key == 'supplier_id')
			{
				$item_data["$key"]=$value == '' ? null : $value;
			}
			elseif($value!='' and !(in_array($key, array('item_ids', 'tax_names', 'tax_percents'))))
			{
				$item_data["$key"]=$value;
			}
		}

		//Item data could be empty if tax information is being updated
		if(empty($item_data) || $this->Item->update_multiple($item_data,$items_to_update))
		{
			$items_taxes_data = array();
			$tax_names = $this->input->post('tax_names');
			$tax_percents = $this->input->post('tax_percents');
			for($k=0;$k<count($tax_percents);$k++)
			{
				if (is_numeric($tax_percents[$k]))
				{
					$items_taxes_data[] = array('name'=>$tax_names[$k], 'percent'=>$tax_percents[$k] );
				}
			}
			$this->Item_taxes->save_multiple($items_taxes_data, $items_to_update);

			echo json_encode(array('success'=>true,'message'=>$this->lang->line('items_successful_bulk_edit')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('items_error_updating_multiple')));
		}
	}

	function delete()
	{
		$items_to_delete=$this->input->post('ids');

		if($this->Item->delete_list($items_to_delete))
		{
			echo json_encode(array('success'=>true,'message'=>$this->lang->line('items_successful_deleted').' '.
			count($items_to_delete).' '.$this->lang->line('items_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('items_cannot_be_deleted')));
		}
	}

	function excel()
	{
		$data = file_get_contents("import_items.csv");
		$name = 'import_items.csv';
		force_download($name, $data);
	}

	function excel_import()
	{
		$this->load->view("products/excel_import", null);
	}



	function do_excel_import()
	{

  		$msg = 'do_excel_import';
		$failCodes = array();
		if ($_FILES['file_path']['error']!=UPLOAD_ERR_OK)
		{
			$msg = $this->lang->line('products_excel_import_failed');
			echo array('success'=>false,'message'=>$msg);
			return;
		}
		else
		{


			if (($handle = fopen($_FILES['file_path']['tmp_name'], "r")) !== FALSE)
			{
				//Skip first row

				if(!$this->Product->delete_all())
				{
					$msg = $this->lang->line('products_excel_import_failed');
					echo array('success'=>false,'message'=>$msg);
					return;

				}

				fgetcsv($handle);

				$i = 1;
				while (($data = fgetcsv($handle)) !== FALSE)
				{
					$prod_data = array(
						'prod_code'			=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[0])),
						'prod_uos'			=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[1])),
						'start_date'		=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[2])),
						'prod_desc'			=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[3])),
						'prod_pack_desc'	=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[4])),
						'vat_code'			=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[5])),
						'prod_price'		=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[6])),
						'group_desc'		=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[7])),
						'prod_code1'		=>  iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[8])),
						'price_list'		=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[9])),
						'prod_level1'		=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[10])),
						'prod_level2'		=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[11])),
						'prod_level3'		=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[12])),
						'prod_sell'			=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[13])),
						'prod_rrp'			=>  iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[14])),
						'wholesale'			=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[15])),
						'retail'			=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[16])),
						'p_size'			=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[17]))
					);


					if($this->Product->save_excel($prod_data))
					{
						$j ++;

					}
					else//insert or update item failure
					{
						$failCodes[] = $i;
					}
				}

				$i++;

			}
			else
			{
				echo array('success'=>false,'message'=>'Your upload file has no data or not in supported format.');
				return;
			}
			fclose($handle);

		}


		$success = true;
		if(count($failCodes) > 1)
		{
			$msg = "Most products imported. But some were not, here is list of their CODE (" .count($failCodes) ."): ".implode(", ", $failCodes);
			$success = false;
		}
		else
		{
			$msg = "Import products successful";
		}

		echo array('success'=>$success,'message'=>$msg);

	}

	/*
	get the width for the add/edit form
	*/
	function get_form_width()
	{
		return 360;
	}

	function sort_product()
	{
		$sort_key = $this->input->post('sort_key');
		$search0 = $this->input->post('search0');
		$search1 = $this->input->post('search1');
		$search2 = $this->input->post('search2');
		$search_mode = $this->input->post('search_mode');
		$category_id = $this->input->post('category_id');
		$per_page = $this->input->post('per_page');

		if($per_page == 0) $per_page = 30;

		$user_info = $this->Employee->get_logged_in_employee_info();


		if($search_mode == "default")
		{
			$config['base_url'] = site_url('/products/index/default/'.$sort_key."//".$category_id."//");
			if($user_info->username == "admin")
				$config['total_rows'] = $this->Product->count_all_admin_category($category_id);
			else
				$config['total_rows'] = $this->Product->count_all_category($user_info , $category_id);

			$total_rows = $config['total_rows'];
			$total_page = floor($total_rows / $per_page) + 1;

			$config['per_page'] = $per_page;
			$config['uri_segment'] = 6;
		}
		else if($search_mode == "search")
		{
			if($search0 == "")
				$search0_page = "12345678901234567890";
			else
				$search0_page = $search0;

			if($search1 == "")
				$search1_page = "12345678901234567890";
			else
				$search1_page = $search1;

			if($search2 == "")
				$search2_page = "12345678901234567890";
			else
				$search2_page = $search2;


			$config['base_url'] = site_url("products/index/search/".$search0_page."//".$search1_page."//".$search2_page."//".$sort_key."//".$category_id."//");
			$config['total_rows'] = $this->Product->total_search_num_rows_category($search0 , $search1 , $search2 , $user_info , $category_id);
			$config['per_page'] = $per_page;
			$config['uri_segment'] = 9;
			$total_rows = $config['total_rows'];
			$total_page = floor($total_rows / $per_page) + 1;
		}



		//$config['use_page_numbers'] = TRUE;
		$config['full_tag_open'] = '<ul>';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = '<<';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = '>>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['prev_link'] = '<';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li><b>';
		$config['cur_tag_close'] = '</b></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);

//		$data_rows = $search_mode;
//		$data_rows .= "********************";

		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'><span style='font-family:Arial;'>Show&nbsp;&nbsp;</span><select name='per_page' id='per_page' onchange=\"select_per_page('";
		$data_rows .= site_url("products/index/");
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
		$data_rows .= ">200</option></select><span style='font-family:Arial;>&nbsp;Rows&nbsp;Per&nbsp;Page</span></div>";

		$data_rows .= "<div class='btnseparator' style='float:right;'></div>";
		$data_rows .= "<div class='pGroup' style='float:right;'>";
		$data_rows .= "<div class='pNext pButton' onclick=\"pNext('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div>";
		$data_rows .= "<div class='pLast pButton' onclick=\"pLast('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div>";
		$data_rows .= "</div><div class='btnseparator' style='float:right;'></div><div class='pGroup' style='float:right;'>";
		$data_rows .= "<span class='pcontrol'>Page&nbsp;";
		$data_rows .= "<input type='text' name='page' id='curd_page' value='1' size='4' class='product_search_cell_page' onkeyup=\"set_direct_page(event , '";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\">&nbsp;of&nbsp;<span id='last_page_number'>".$total_page."</span></span></div><div class='btnseparator' style='float:right;'></div>";
		$data_rows .= "<div class='pGroup' style='float:right;'><div class='pFirst pButton' onclick=\"pFirst('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div><div class='pPrev pButton'  onclick=\"pPrev('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div></div><div class='btnseparator' style='float:right;'></div></div>";
/*
		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'><div class='pFirst pButton' onclick=\"pFirst('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div>";

		$data_rows .= "<div class='pPrev pButton' onclick=\"pPrev('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div></div><div class='btnseparator'></div>";
		$data_rows .= "<div class='pGroup'><span class='pcontrol'>Page&nbsp;<input type='text' name='page' id='curd_page' value='1' size='4' class='product_search_cell_page' onkeyup=\"set_direct_page(event ,'";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\">";
		$data_rows .= "&nbsp;of&nbsp;<span id='last_page_number'>$total_page</span></span></div><div class='btnseparator'></div>";
		$data_rows .= "<div><div class='pNext pButton' onclick=\"pNext('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div><div class='pLast pButton' onclick=\"pLast('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div>";
		$data_rows .= "</div><div class='btnseparator'></div>";
*/
//		$data_rows .= $this->pagination->create_links();
		$data_rows .= "********************";

		$data_rows .= get_products_manage_table_data_rows($this->Product->search_category($search0 , $search1 , $search2 , $user_info , $config['per_page'] , $this->uri->segment($config['uri_segment']) , $sort_key , $category_id) , $this);
		//$data_rows = get_products_manage_table_data_rows($this->Product->search($search0 , $search1 , $search2 , $user_info , $limit , $offset , $sort_key) , $this);
		echo $data_rows;

	}

	function select_category()
	{
		$search_mode = $this->input->post('search_mode');
		$sort_key = $this->input->post('sort_key');
		$search0 = $this->input->post('search0');
		$search1 = $this->input->post('search1');
		$search2 = $this->input->post('search2');

		$category_id = $this->input->post('category_id');
		$per_page = $this->input->post('per_page');

//		echo $category_id;
		trim($search_mode , "\0x00..\0x1F");
		if($per_page == 0) $per_page = 30;

		$user_info = $this->Employee->get_logged_in_employee_info();


		if($search_mode == "default")
		{
			$config['base_url'] = site_url('/products/index/default/'.$sort_key."//".$category_id."//");
			if($user_info->username == "admin")
				$config['total_rows'] = $this->Product->count_all_admin_category($category_id);
			else
				$config['total_rows'] = $this->Product->count_all_category($user_info , $category_id);

			$total_rows = $config['total_rows'];
			$total_page = floor($total_rows / $per_page) + 1;

			$config['per_page'] = $per_page;
			$config['uri_segment'] = 6;
		}
		else if($search_mode == "search")
		{
			if($search0 == "")
				$search0_page = "12345678901234567890";
			else
				$search0_page = $search0;

			if($search1 == "")
				$search1_page = "12345678901234567890";
			else
				$search1_page = $search1;

			if($search2 == "")
				$search2_page = "12345678901234567890";
			else
				$search2_page = $search2;


			$config['base_url'] = site_url("products/index/search/".$search0_page."//".$search1_page."//".$search2_page."//".$sort_key."//".$category_id."//");
			$config['total_rows'] = $this->Product->total_search_num_rows_category($search0 , $search1 , $search2 , $user_info , $category_id);
			$config['per_page'] = $per_page;
			$config['uri_segment'] = 9;
			$total_rows = $config['total_rows'];
			$total_page = floor($total_rows / $per_page) + 1;
		}



		//$config['use_page_numbers'] = TRUE;
		$config['full_tag_open'] = '<ul>';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = '<<';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = '>>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['prev_link'] = '<';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li><b>';
		$config['cur_tag_close'] = '</b></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);


		$data_rows = "";

		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'><span style='font-family:Arial;'>Show&nbsp;&nbsp;</span><select name='per_page' id='per_page' onchange=\"select_per_page('";
		$data_rows .= site_url("products/index/");
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

		$data_rows .= "<div class='btnseparator' style='float:right;'></div>";
		$data_rows .= "<div class='pGroup' style='float:right;'>";
		$data_rows .= "<div class='pNext pButton' onclick=\"pNext('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div>";
		$data_rows .= "<div class='pLast pButton' onclick=\"pLast('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div>";
		$data_rows .= "</div><div class='btnseparator' style='float:right;'></div><div class='pGroup' style='float:right;'>";
		$data_rows .= "<span class='pcontrol'>Page&nbsp;";
		$data_rows .= "<input type='text' name='page' id='curd_page' value='1' size='4' class='product_search_cell_page' onkeyup=\"set_direct_page(event , '";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\">&nbsp;of&nbsp;<span id='last_page_number'>".$total_page."</span></span></div><div class='btnseparator' style='float:right;'></div>";
		$data_rows .= "<div class='pGroup' style='float:right;'><div class='pFirst pButton' onclick=\"pFirst('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div><div class='pPrev pButton'  onclick=\"pPrev('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div></div><div class='btnseparator' style='float:right;'></div></div>";

/*
		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'><div class='pFirst pButton' onclick=\"pFirst('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div>";

		$data_rows .= "<div class='pPrev pButton' onclick=\"pPrev('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div></div><div class='btnseparator'></div>";
		$data_rows .= "<div class='pGroup'><span class='pcontrol'>Page&nbsp;<input type='text' name='page' id='curd_page' value='1' size='4' class='product_search_cell_page' onkeyup=\"set_direct_page(event ,'";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\">";
		$data_rows .= "&nbsp;of&nbsp;<span id='last_page_number'>$total_page</span></span></div><div class='btnseparator'></div>";
		$data_rows .= "<div><div class='pNext pButton' onclick=\"pNext('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div><div class='pLast pButton' onclick=\"pLast('";
		$data_rows .= site_url("products/index/");
		$data_rows .= "');\"><span></span></div>";
		$data_rows .= "</div><div class='btnseparator'></div>";
*/
		//		$data_rows .= $this->pagination->create_links();
		$data_rows .= "********************";

		$data_rows .= get_products_manage_table_data_rows($this->Product->search_category($search0 , $search1 , $search2 , $user_info , $config['per_page'] , 0 , $sort_key , $category_id) , $this);
		//$data_rows = get_products_manage_table_data_rows($this->Product->search($search0 , $search1 , $search2 , $user_info , $limit , $offset , $sort_key) , $this);


		echo $data_rows;

	}

	function to_cart()
	{
		$prod_id = $this->input->post('prod_id');
		$mode = $this->input->post('mode');
		$quantity = $this->input->post('quantity');
		$user_info = $this->Employee->get_logged_in_employee_info();
		$quantity = $this->Product->to_cart($prod_id , $mode , $user_info->person_id , $quantity);
		echo $quantity;
	}

	function get_cart_quantities($prod_id)
	{
		$user_info = $this->Employee->get_logged_in_employee_info();
		return $this->Product->get_cart_quantity($prod_id , $user_info->person_id);
	}

	function reload_product()
	{
		$file_path = "./temp/temp_product.csv";
		$ftp_path = "/public_html/temp_product.csv";
		$ftp_info = $this->Order->get_ftp_info();
		$config_ftp['hostname'] = $ftp_info['ftp_location'];
		$config_ftp['username'] = $ftp_info['ftp_username'];
		$config_ftp['password'] = $ftp_info['ftp_password'];
		$config_ftp['debug'] = TRUE;
		$this->ftp->connect($config_ftp);
		if(!$this->ftp->download($ftp_path , $file_path , 'binary'))
		{
			echo "FTP download fail.";
			$this->ftp->close();
			return;
		}
		$this->ftp->close();

		if (($handle = fopen($file_path , "r")) !== FALSE)
		{
			//Skip first row

			if(!$this->Product->delete_all())
			{
				$msg = $this->lang->line('products_excel_import_failed');
				echo "Delte Product Fail";
				return;

			}

			fgetcsv($handle);

			$i = 1;
			while (($data = fgetcsv($handle)) !== FALSE)
			{
				$prod_data = array(
						'prod_code'			=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[0])),
						'prod_uos'			=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[1])),
						'start_date'		=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[2])),
						'prod_desc'			=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[3])),
						'prod_pack_desc'	=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[4])),
						'vat_code'			=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[5])),
						'prod_price'		=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[6])),
						'group_desc'		=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[7])),
						'prod_code1'		=>  iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[8])),
						'price_list'		=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[9])),
						'prod_level1'		=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[10])),
						'prod_level2'		=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[11])),
						'prod_level3'		=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[12])),
						'prod_sell'			=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[13])),
						'prod_rrp'			=>  iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[14])),
						'wholesale'			=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[15])),
						'retail'			=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[16])),
						'p_size'			=>	iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[17]))
				);


				if($this->Product->save_excel($prod_data))
				{
					$j ++;

				}
				else//insert or update item failure
				{
					$failCodes[] = $i;
				}
			}

			$i++;
			fclose($handle);
		}
		else
		{
			echo "CSV File read error.";
			return;
		}


	}

	function add_to_cart()
	{
		$prod_id = $this->input->post('prod_id');
		$quantity = $this->input->post('quantity');

		$user_info = $this->Employee->get_logged_in_employee_info();
		$this->Pastorder->add_to_cart($prod_id , $quantity , $user_info->person_id);
	}
}
?>