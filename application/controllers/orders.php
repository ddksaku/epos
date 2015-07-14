<?php
require_once ("secure_area.php");
require_once ("interfaces/idata_controller.php");
class Orders extends Secure_area implements iData_controller
{
	function __construct()
	{
		parent::__construct('orders');
	}

	function index()
	{
		$user_info = $this->Employee->get_logged_in_employee_info();
		$data['controller_name'] = strtolower(get_class());
		$manage_table = get_cart_order_manage_table($this->Order->get_all_cart($user_info->person_id) , $this);
		$arr = explode("********************", $manage_table);
		$data['manage_table'] = $arr[0];
		$data['total_quantity'] = $arr[1];
		$data['total_amount'] = $arr[2];
		$data['form_width'] = $this->get_form_width();
		$this->load->view('orders/manage' , $data);
	}

	function search()
	{

	}

	function suggest()
	{

	}

	function get_row()
	{

	}

	function view($data_item_id=-1)
	{

	}

	function save($data_item_id=-1)
	{

	}

	function delete()
	{

	}

	function get_form_width()
	{
		return 350;
	}

	function get_product($prod_id)
	{
		return $this->Order->get_product($prod_id);
	}

	function to_cart_quantity()
	{
		$mode = $this->input->post('mode');
		$prod_id = $this->input->post('prod_id');
		$quantity = $this->input->post('quantity');
		$user_info = $this->Employee->get_logged_in_employee_info();
		$result = $this->Order->to_cart_quantity($prod_id , $mode , $user_info->person_id , $quantity);

		$table_data = get_cart_order_manage_table($this->Order->get_all_cart($user_info->person_id), $this);
		echo $table_data;
	}


	function add_another_item()
	{
		redirect("products");
	}

	function get_total_amount_cart()
	{
		$user_info = $this->Employee->get_logged_in_employee_info();
		return $this->Order->get_total_amount_cart($user_info->person_id);
	}

	function save_for_later()
	{
		$user_info = $this->Employee->get_logged_in_employee_info();
		if($this->Order->get_count_cart_products($user_info->person_id) == 0)
			echo 100;
		else echo $this->Order->save_for_later($user_info->person_id , 0);
	}

	function send_order()
	{

		$user_info = $this->Employee->get_logged_in_employee_info();

		if($this->Order->get_count_cart_products($user_info->person_id) == 0)
		{
			echo 100;
			return;
		}

		$send_message = $this->Order->from_message_mail($user_info->person_id);

		$res = $this->Order->save_for_later($user_info->person_id , 1);
		if($res != true)
		{
			echo -1;
			return;
		}

		$file_name = $this->Order->get_order_file_data($user_info->person_id , 0);
		if($file_name == -1)
		{
			echo -1;
			return;
		}

		$first_line = $this->Order->get_order_file_data($user_info->person_id , 1);
		if($first_line == -1)
		{
			echo -1;
			return;
		}

		$file_data = $this->Order->get_order_file_data($user_info->person_id , 2);
		if($file_data == -1)
		{
			echo -1;
			return;
		}

		$file_data = $first_line.$file_data;
		$file_path = "./temp/".$file_name;
		$ftp_path = "/public_html/".$file_name;

		if(!write_file($file_path, $file_data))
		{
			echo -1;
			return;
		}

		if(!$this->Order->close_and_complete_order($user_info->person_id))
		{
			echo -1;
			return;
		}

		$ftp_info = $this->Order->get_ftp_info();
		$config_ftp['hostname'] = $ftp_info['ftp_location'];
		$config_ftp['username'] = $ftp_info['ftp_username'];
		$config_ftp['password'] = $ftp_info['ftp_password'];
		$config_ftp['debug'] = TRUE;
		$this->ftp->connect($config_ftp);
		$this->ftp->upload($file_path , $ftp_path , 'binary', 0777);
		$this->ftp->close();

		$addr_mail = $this->Order->from_addr_mail();
		$mail_subject = $this->lang->line('orders_email_subject').$user_info->username;

//		echo nl2br($send_message , false);

		$config_mailtype['mailtype'] = "html";
		$this->email->initialize($config_mailtype);
		$this->email->from($addr_mail['email_addr'], $addr_mail['company_name']);
		$this->email->to($user_info->email);
		$this->email->subject($mail_subject);
		$this->email->message($send_message);

		$this->email->send();

		$this->email->clear();

		$config_mailtype['mailtype'] = "html";
		$this->email->initialize($config_mailtype);
		$this->email->from($addr_mail['email_addr'], $addr_mail['company_name']);
		$this->email->to($addr_mail['seller_mail_addr']);
		$this->email->subject($mail_subject);
		$this->email->message($send_message);
		$this->email->send();

		echo "Send Order success.";

	}

	function excel_import()
	{
		$this->load->view("orders/excel_import", null);
	}

	function do_excel_import()
	{
		$user_info = $this->Employee->get_logged_in_employee_info();
		$is_empty = $this->input->post('empty_trolley')=='' ? 0:1;
		if($is_empty == 1)
		{
			$this->Order->empty_cart($user_info->person_id);
		}
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

				$i = 0;
				while (($data = fgetcsv($handle)) !== FALSE)
				{
					$barcode = iconv("Windows-1252" , "UTF-8//IGNORE" , trim($data[0]));




					if($this->Order->save_excel($barcode , $user_info->person_id))
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
		redirect("orders");

	}

}
?>