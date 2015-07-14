<?php
class Order extends CI_Model
{
	function get_all_cart($person_id)
	{
		$this->db->from('cart');
		$this->db->where('person_id' , $person_id);
		//$this->db->order_by();
		return $this->db->get();
	}

	function get_count_cart_products($person_id)
	{
		$this->db->from('cart');
		$this->db->where('person_id' , $person_id);
		return $this->db->count_all_results();
	}

	function get_product($prod_id)
	{
		$this->db->from('product');
		$this->db->where('prod_id' , $prod_id);
		return $this->db->get()->row();
	}

	function from_addr_mail()
	{
		$this->db->from('app_config');
		$this->db->where('key' , 'email');
		$result = $this->db->get()->row();
		$mail_addr = $result->value;

		$this->db->from('app_config');
		$this->db->where('key' , 'company');
		$result = $this->db->get()->row();
		$company_name = $result->value;

		$this->db->from('app_config');
		$this->db->where('key' , 'seller_mail_addr');
		$result = $this->db->get()->row();
		$seller_mail_addr = $result->value;

		$addr = array('email_addr' => $mail_addr , 'company_name' => $company_name , 'seller_mail_addr' => $seller_mail_addr);
		return $addr;
	}

	function from_message_mail($person_id)
	{
		$this->db->from('cart');
		$this->db->where('person_id' , $person_id);
		$results_cart = $this->db->get();
		$nCount = 0;
		$query_opened_order_id = "SELECT * FROM epos_orders WHERE person_id='".$person_id."' and opened='1'";
		$result_opened_order_id = $this->db->query($query_opened_order_id);
		if($result_opened_order_id->num_rows() == 0)
		{
			$query_order_id = "SELECT MAX(order_id) as max_id FROM epos_orders";
			$result_order_id = $this->db->query($query_order_id);
			$res_order_id = $result_order_id->row();
			$order_id = $res_order_id->max_id + 1;
		}
		else
		{
			$order_id = $result_opened_order_id->row()->order_id;
		}

		$message = "<html><body>";
		$message .= "<span style='font-family:Arial; font-size:18px;'>";
		$message .= "Your order ref wo-".$order_id."<span>";
		$message .= "<table cellspacing='1px' style='width:98%; border-left: 1px solid gray; border-right:1px solid gray; border-bottom:2px solid gray;'>";
		$message .= "<thead><tr style='background-color:#11ccdd;'><th>No</th><th>Product</th><th>Description</th><th>Size</th><th>UOS</th><th>Price</th><th>Qty</th><th>Total</th></tr></thead>";
		$message .= "<tbody>";
		$total_amount = 0;
		$total_quantity = 0;
		foreach($results_cart->result() as $res_cart)
		{
			$nCount ++;
			$this->db->from('product');
			$this->db->where('prod_id' , $res_cart->prod_id);
			$res_prod = $this->db->get()->row();

			$message .= "<tr>";
			$message .= "<td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$nCount."</td>";
			$message .= "<td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$res_prod->prod_code."</td>";
			$message .= "<td style='width:30%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$res_prod->prod_desc."</td>";
			$message .= "<td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$res_prod->prod_pack_desc."</td>";
			$message .= "<td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$res_prod->prod_uos."</td>";
			$message .= "<td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$res_prod->prod_sell."</td>";
			$message .= "<td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$res_cart->quantity."</td>";


			$line_total = $res_prod->prod_sell * $res_cart->quantity;
			$total_amount = $total_amount + $line_total;
			$total_quantity = $total_quantity + $res_cart->quantity;

			$line_total1 = explode(".", $line_total);
			if(strlen($line_total1[1]) == 0) $line_total2 = "00";
			if(strlen($line_total1[1]) == 1) $line_total2 = $line_total1[1]."0";
			if(strlen($line_total1[1]) == 2) $line_total2 = $line_total1[1];
			$line_total = $line_total1[0].".".$line_total2;

			$message .= "<td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$line_total."</td>";
			$message .= "</tr>";
		}

		$message .= "<tr style='background-color:#EEEEEE;'><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
		$message .= "<td>Total</td><td style='text-align:right;'>".$total_quantity."</td><td style='text-align:right;'>".$total_amount."</td></tr>";
		$message .= "</table></body></html>";
		return $message;
	}

	function to_cart_quantity($prod_id , $mode , $person_id , $quantity = 1)
	{
		if($mode == 3)
		{
			$query = "DELETE FROM epos_cart WHERE person_id='".$person_id."' and prod_id='".$prod_id."'";
			$this->db->trans_start();
			$this->db->query($query);
			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE)
				return -1;
			else
				return true;
		}

		$query = "SELECT * FROM epos_cart WHERE prod_id='".$prod_id."' and person_id='".$person_id."'";
		$res = $this->db->query($query);

		if($res->num_rows() == 0)
		{
			if($mode == 1 || $mode == 4)
			{
				//$quantity = 1;
				$this->db->from('product');
				$this->db->where('prod_id' , $prod_id);
				$res_prod = $this->db->get()->row();
				$cart_data = array('prod_id'=>$prod_id ,
						'quantity'=>$quantity ,
						'person_id'=>$person_id ,
						'prod_code'=>$res_prod->prod_code ,
						'prod_uos'=>$res_prod->prod_uos ,
						'start_date'=>$res_prod->start_date ,
						'prod_desc'=>$res_prod->prod_desc ,
						'prod_pack_desc'=>$res_prod->prod_pack_desc ,
						'vat_code'=>$res_prod->vat_code ,
						'prod_price'=>$res_prod->prod_price ,
						'group_desc'=>$res_prod->group_desc ,
						'prod_code1'=>$res_prod->prod_code1 ,
						'price_list'=>$res_prod->price_list ,
						'prod_level1'=>$res_prod->prod_level1 ,
						'prod_level2'=>$res_prod->prod_level2 ,
						'prod_level3'=>$res_prod->prod_level3 ,
						'prod_sell'=>$res_prod->prod_sell ,
						'prod_rrp'=>$res_prod->prod_rrp ,
						'wholesale'=>$res_prod->wholesale ,
						'retail'=>$res_prod->retail ,
						'p_size'=>$res_prod->p_size
				);

				$this->db->trans_start();
				$this->db->insert('cart' , $cart_data);
				$this->db->trans_complete();
				if ($this->db->trans_status() === FALSE)
					return -1;
				else
					return 1;


			}
			else if($mode == 2)
				return 0;
			else
				return -1;
		}
		else if($res->num_rows() == 1)
		{
			$res_row = $res->row();
			$quantity1 = $res_row->quantity;
			if($mode == 1)
				$quantity1 = $quantity1 + 1;
			else if($mode == 2)
			{
				if($quantity1 > 0) $quantity1 = $quantity1 - 1;
			}
			else if($mode == 4)
			{
				$quantity1 = $quantity;
			}

			if($quantity1 == 0)
			{
				$this->db->trans_start();
				$this->db->query("DELETE FROM epos_cart WHERE prod_id='".$prod_id."' and person_id='".$person_id."'");
				$this->db->trans_complete();
				if ($this->db->trans_status() === FALSE)
					return -1;
				else
					return true;
			}
			else
			{
				$cart_data = array('quantity'=>$quantity1);
				$this->db->trans_start();
				$this->db->where('prod_id' , $prod_id);
				$this->db->where('person_id' , $person_id);
				$this->db->update('cart' , $cart_data);
				$this->db->trans_complete();
				if ($this->db->trans_status() === FALSE)
					return -1;
				else
					return true;

			}
		}
		else
			return -1;

	}

	function get_total_amount_cart($person_id)
	{
		$query = "SELECT * FROM epos_cart WHERE person_id='".$person_id."'";
		$results = $this->db->query($query);
		$total_quantity = 0;
		$total_amount = 0;
		foreach($results->result() as $res)
		{
			$total_quantity += $res->quantity;
			$query1 = "SELECT prod_sell FROM epos_product WHERE prod_id='".$res->prod_id."'";
			$res1 = $this->db->query($query1)->row();
			$total_amount = $total_amount + $res->quantity * $res1->prod_sell;
		}

		$return_str = "********************";
		$return_str .= $total_quantity;
		$return_str .= "********************";
		$return_str .= $total_amount;

		return $return_str;
	}


	function save_for_later($person_id , $opened)
	{
		$this->db->from('orders');
		$this->db->where('person_id' , $person_id);
		$this->db->where('opened' , 1);
		$results = $this->db->get();


		if($results->num_rows() == 0)
		{
			$order_date = date("dmY");
			$order_time = date("his");
			srand((double)microtime()*1000000);
			$rand16_str = "";
			$nCount = 0;
			while(1)
			{
				$nn = rand(48 , 122);
				if($nn > 57 && $nn < 65)
					continue;
				if($nn >90 && $nn < 97)
					continue;

				$rand16_str .= chr($nn);
				$nCount ++;

				if($nCount == 16) break;
			}

			$order_data = array('person_id' => $person_id ,
					'order_date' => $order_date ,
					'order_time' => $order_time ,
					'rand_characters' => $rand16_str ,
					'completed' => 0 ,
					'opened' => $opened
				);



			$this->db->trans_start();
			$this->db->insert('orders' , $order_data);
			$order_id = $this->db->insert_id();
			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE)
				return -1;




		}
		else
		{

			$res = $results->row();
			$order_id = $res->order_id;
			$order_date = date("dmY");
			$order_time = date("his");
			$order_data = array(
					'order_date' => $order_date ,
					'order_time' => $order_time ,
					'opened' => $opened ,
					'completed' => 0
				);

			$this->db->trans_start();
			$this->db->where('order_id' , $order_id);
			$this->db->update('orders' , $order_data);
			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE)
				return -1;

		}

		$query = "DELETE FROM epos_orders_products WHERE order_id='".$order_id."'";

		$this->db->trans_start();
		$this->db->query($query);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
			return -1;

		$query1 = "SELECT * FROM epos_cart WHERE person_id='".$person_id."'";

		$results1 = $this->db->query($query1);


		foreach($results1->result() as $res1)
		{
			$order_product_data = array(
					'order_id' => $order_id ,
					'prod_id' => $res1->prod_id ,
					'quantity' => $res1->quantity ,
					'prod_code'=>$res1->prod_code ,
					'prod_uos'=>$res1->prod_uos ,
					'start_date'=>$res1->start_date ,
					'prod_desc'=>$res1->prod_desc ,
					'prod_pack_desc'=>$res1->prod_pack_desc ,
					'vat_code'=>$res1->vat_code ,
					'prod_price'=>$res1->prod_price ,
					'group_desc'=>$res1->group_desc ,
					'prod_code1'=>$res1->prod_code1 ,
					'price_list'=>$res1->price_list ,
					'prod_level1'=>$res1->prod_level1 ,
					'prod_level2'=>$res1->prod_level2 ,
					'prod_level3'=>$res1->prod_level3 ,
					'prod_sell'=>$res1->prod_sell ,
					'prod_rrp'=>$res1->prod_rrp ,
					'wholesale'=>$res1->wholesale ,
					'retail'=>$res1->retail ,
					'p_size'=>$res1->p_size
				);
			$this->db->trans_start();
			$this->db->insert('orders_products' , $order_product_data);
			$this->db->trans_complete();
			if($this->db->trans_status() == FALSE)
				return -1;
		}

		$query = "DELETE FROM epos_cart WHERE person_id='".$person_id."'";
		$this->db->trans_start();
		$this->db->query($query);
		$this->db->trans_complete();
		if($this->db->trans_status() == FALSE)
			return -1;

		return true;

	}

	function get_order_file_data($person_id , $option)
	{
		if($option == 0)	//get file name
		{
			$this->db->from('employees');
			$this->db->where('person_id' , $person_id);
			$res = $this->db->get()->row();
			$account_number = $res->account_number;

			$this->db->from('orders');
			$this->db->where('person_id' , $person_id);
			$this->db->where('opened' , 1);
			$result = $this->db->get();
			if($result->num_rows() == 0) return -1;

			$res = $result->row();
			$order_date = $res->order_date;
			$order_time = $res->order_time;
			$rand_characters = $res->rand_characters;
			$ext = "ord";
			$file_name = $account_number;
			$file_name .= "_";
			$file_name .= $order_date;
			$file_name .= "_";
			$file_name .= $order_time;
			$file_name .= "_";
			$file_name .= $rand_characters;
			$file_name .= ".";
			$file_name .= $ext;
			return $file_name;
		}
		else if($option == 1)	//get first line
		{
			$this->db->from('employees');
			$this->db->where('person_id' , $person_id);
			$res = $this->db->get()->row();
			$account_number = $res->account_number;

			$this->db->from('orders');
			$this->db->where('person_id' , $person_id);
			$this->db->where('opened' , 1);
			$result = $this->db->get();
			if($result->num_rows() == 0) return -1;

			$res = $result->row();
			$order_date = $res->order_date;
			$order_time = $res->order_time;
			$first_line = $order_date;
			$first_line .= $order_time;
			$first_line .= $account_number;
			$first_line .= "\r\n";
			return $first_line;
		}
		else if($option == 2)	//get file data
		{
			$this->db->from('orders');
			$this->db->where('person_id' , $person_id);
			$this->db->where('opened' , 1);
			$result = $this->db->get();
			if($result->num_rows() == 0) return -1;

			$res = $result->row();
			$order_id = $res->order_id;

			$query = "SELECT epos_product.*,epos_orders_products.quantity FROM epos_orders_products,epos_product WHERE epos_orders_products.order_id='".$order_id."' and epos_product.prod_id=epos_orders_products.prod_id";
			$results = $this->db->query($query);
			$file_data = "";
			$nCount = 1;
			foreach($results->result() as $res_prod)
			{
				if(strlen($nCount) == 1)
					$nCount1 = "00".$nCount;
				else if(strlen($nCount) == 2)
					$nCount1 = "0".$nCount;
				else if(strlen($nCount) == 3)
					$nCount1 = $nCount;
				else if(strlen($nCount) > 3) return -1;

				if(strlen($res_prod->prod_code) == 1)
					$prod_code = "000000".$res_prod->prod_code;
				else if(strlen($res_prod->prod_code) == 2)
					$prod_code = "00000".$res_prod->prod_code;
				else if(strlen($res_prod->prod_code) == 3)
					$prod_code = "0000".$res_prod->prod_code;
				else if(strlen($res_prod->prod_code) == 4)
					$prod_code = "000".$res_prod->prod_code;
				else if(strlen($res_prod->prod_code) == 5)
					$prod_code = "00".$res_prod->prod_code;
				else if(strlen($res_prod->prod_code) == 6)
					$prod_code = "0".$res_prod->prod_code;
				else if(strlen($res_prod->prod_code) == 7)
					$prod_code = $res_prod->prod_code;
				else if(strlen($nCount) > 7) return -1;

				$prod_code = $res_prod->prod_code;

				if(strlen($res_prod->quantity) == 1)
					$quantity = "000".$res_prod->quantity;
				else if(strlen($res_prod->quantity) == 2)
					$quantity = "00".$res_prod->quantity;
				else if(strlen($res_prod->quantity) == 3)
					$quantity = "0".$res_prod->quantity;
				else if(strlen($res_prod->quantity) == 4)
					$quantity = $res_prod->quantity;
				else if(strlen($res_prod->quantity) > 4) return -1;

				$price = explode(".", $res_prod->prod_sell);

				if(strlen($price[0]) == 1)
					$price1 = "0000".$price[0];
				else if(strlen($price[0]) == 2)
					$price1 = "000".$price[0];
				else if(strlen($price[0]) == 3)
					$price1 = "00".$price[0];
				else if(strlen($price[0]) == 4)
					$price1 = "0".$price[0];
				else if(strlen($price[0]) == 5)
					$price1 = $price[0];
				else if(strlen($price[0]) > 5) return -1;

				if(strlen($price[1]) == 0)
					$price2 = "00";
				else if(strlen($price[1]) == 1)
					$price2 = $price[1]."0";
				else if(strlen($price[1]) == 2)
					$price2 = $price[1];
				else if(strlen($price[1]) > 2) return -1;

				$data_line = $nCount1;
				$data_line .= $prod_code;
				$data_line .= $quantity;
				$data_line .= $price1;
				$data_line .= ".";
				$data_line .= $price2;
				$data_line .= "\r\n";

				$file_data .= $data_line;

				$nCount ++;
				if($nCount == 1000) break;
			}
			return $file_data;
		}
	}

	function close_and_complete_order($person_id)
	{
		$this->db->from('orders');
		$this->db->where('person_id' , $person_id);
		$this->db->where('opened' , 1);
		$results = $this->db->get();
		if($results->num_rows() == 0) return -1;

		$res = $results->row();

		$order_id = $res->order_id;

		$order_data = array(
			'opened' => 0 ,
			'completed' => 1
		);


		$this->db->where('order_id' , $order_id);
		return $this->db->update('orders' , $order_data);

	}

	function save_excel($barcode , $person_id)
	{
		$this->db->from('product');
		$this->db->where("(wholesale LIKE '%".$this->db->escape_like_str($barcode)."%' OR retail LIKE '%".$this->db->escape_like_str($barcode)."%')");
		$res = $this->db->get();

		if($res->num_rows() == 0) return true;
		else
		{
			foreach($res->result() as $res_row)
			{
				$this->db->from('cart');
				$this->db->where('prod_id' , $res_row->prod_id);
				$res1 = $this->db->get();

				if($res1->num_rows() == 0)
				{
					$insert_data = array('prod_id' => $res_row->prod_id ,
							'quantity' => 1 ,
							'person_id' => $person_id ,
							'prod_code'=>$res_row->prod_code ,
							'prod_uos'=>$res_row->prod_uos ,
							'start_date'=>$res_row->start_date ,
							'prod_desc'=>$res_row->prod_desc ,
							'prod_pack_desc'=>$res_row->prod_pack_desc ,
							'vat_code'=>$res_row->vat_code ,
							'prod_price'=>$res_row->prod_price ,
							'group_desc'=>$res_row->group_desc ,
							'prod_code1'=>$res_row->prod_code1 ,
							'price_list'=>$res_row->price_list ,
							'prod_level1'=>$res_row->prod_level1 ,
							'prod_level2'=>$res_row->prod_level2 ,
							'prod_level3'=>$res_row->prod_level3 ,
							'prod_sell'=>$res_row->prod_sell ,
							'prod_rrp'=>$res_row->prod_rrp ,
							'wholesale'=>$res_row->wholesale ,
							'retail'=>$res_row->retail ,
							'p_size'=>$res_row->p_size
					);
					$this->db->insert('cart' , $insert_data);
					////return true;
				}
				else return true;
			}
		}
	}

	function get_ftp_info()
	{
		$this->db->from('app_config');
		$this->db->where('key' , 'ftp_location');
		$result = $this->db->get()->row();
		$ftp_location = $result->value;

		$this->db->from('app_config');
		$this->db->where('key' , 'ftp_username');
		$result = $this->db->get()->row();
		$ftp_username = $result->value;

		$this->db->from('app_config');
		$this->db->where('key' , 'ftp_password');
		$result = $this->db->get()->row();
		$ftp_password = $result->value;

		$ftp_info = array('ftp_location' => $ftp_location , 'ftp_username' => $ftp_username , 'ftp_password' => $ftp_password);
		return $ftp_info;

	}

	function empty_cart($person_id)
	{
		$query = "DELETE FROM epos_cart WHERE person_id='".$person_id."'";
		$this->db->trans_start();
		$this->db->query($query);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
			return -1;
		else
			return true;
	}
}
?>





