<?php
class Pastorder extends CI_Model
{

	function get_all_admin($limit = 30 , $offset = 0 , $sort_key = 3)
	{

		$this->db->from('orders');
		$this->db->join('employees' , 'employees.person_id=orders.person_id');

		switch($sort_key)
		{
			case 1:
				$this->db->order_by("username", "asc");
				break;
			case 2:
				$this->db->order_by("username", "desc");
				break;
			case 3:
				$this->db->order_by("order_date", "asc");
				break;
			case 4:
				$this->db->order_by("order_date", "desc");
				break;
			case 5:
				$this->db->order_by("completed", "asc");
				break;
			case 6:
				$this->db->order_by("completed", "desc");
				break;
			default:
				break;

		}

		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
	}

	function count_all_admin()
	{
		$this->db->from('orders');
		return $this->db->count_all_results();
	}

	function get_all($limit = 30 , $offset = 0 , $person_id , $sort_key = 1)
	{
		$this->db->from('orders');
		//$this->db->join('employees' , 'employees.person_id=orders.person_id');
		$this->db->where('person_id' , $person_id);

		switch($sort_key)
		{
			case 1:
				$this->db->order_by("order_date", "asc");
				break;
			case 2:
				$this->db->order_by("order_date", "desc");
				break;
			case 3:
				$this->db->order_by("completed", "asc");
				break;
			case 4:
				$this->db->order_by("completed", "desc");
				break;
			default:
				break;

		}
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
	}

	function count_all($person_id)
	{
		$this->db->from('orders');
		$this->db->where('person_id' , $person_id);
		return $this->db->count_all_results();
	}

	function get_total_amount($order_id)
	{
		$query = "SELECT epos_product.prod_sell,epos_orders_products.quantity FROM epos_product,epos_orders_products WHERE epos_orders_products.order_id='".$order_id."' AND epos_orders_products.prod_id=epos_product.prod_id";

		$results = $this->db->query($query);
		$total = 0;
		foreach($results->result() as $res)
		{
			$total = $total + $res->prod_sell * $res->quantity;
		}

		return $total;
	}

	function total_search_count_all($search , $user_info)
	{
		if($user_info->username == "admin")
		{
			//$query = "SELECT epos_orders.* FROM epos_orders,epos_employees WHERE epos_orders.person_id=epos_employees.person_id AND (epos_orders.order_date LIKE '%".$this->db->escape_like_str($search)."%' OR epos_employees.username LIKE '%".$this->db->escape_like_str($search)."%'";
			//$query = "SELECT * FROM epos_orders WHERE order_date LIKE '%".$this->db->escape_like_str($search)."%'";
			//$results = $this->db->query($query);
			//return $results->num_rows();
			$this->db->from('orders');
			$this->db->like('order_date' , $search);
			return $this->db->count_all_results();

		}
		else
		{
			//$query = "SELECT epos_orders.* FROM epos_orders,epos_employees WHERE epos_orders.person_id='".$user_info->person_id."' AND epos_orders.person_id=epos_employees.person_id AND (epos_orders.order_date LIKE '%".$this->db->escape_like_str($search)."%' OR epos_employees.username LIKE '%".$this->db->escape_like_str($search)."%'";
			//$query = "SELECT * FROM epos_orders WHERE person_id='".$user_info->person_id."' AND order_date LIKE '%".$this->db->escape_like_str($search)."%'";
			//$results = $this->db->query($query);
			//return $results->num_rows();
			$this->db->from('orders');
			$this->db->where('person_id' , $user_info->person_id);
			$this->db->like('order_date' , $search);
			return $this->db->count_all_results();

		}
	}

	function search_admin($search , $limit = 30 , $offset = 0 , $sort_key = 3)
	{
		$this->db->from('orders');
		$this->db->join('employees' , 'employees.person_id=orders.person_id');
//		$this->db->like('order_date' , $this->db->escape_like_str($search));
//		$this->db->like('username' , $this->db->escape_like_str($search));

		switch($sort_key)
		{
			case 1:
				$this->db->order_by("username", "asc");
				break;
			case 2:
				$this->db->order_by("username", "desc");
				break;
			case 3:
				$this->db->order_by("order_date", "asc");
				break;
			case 4:
				$this->db->order_by("order_date", "desc");
				break;
			case 5:
				$this->db->order_by("completed", "asc");
				break;
			case 6:
				$this->db->order_by("completed", "desc");
				break;
			default:
				break;

		}

		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();

	}

	function search($search , $user_info , $limit = 30 , $offset = 0 , $sort_key = 1)
	{
		$this->db->from('orders');
		$this->db->where('person_id' , $user_info->person_id);
//		$this->db->like('order_date' , $this->db->escape_like_str($search));

//		$this->db->join('employees' , 'employees.person_id=orders.person_id');
//		$this->db->where("(order_date LIKE '%".$this->db->escape_like_str($search)."%' OR username LIKE '%".$this->db->escape_like_str($search)."%')");

		switch($sort_key)
		{
			case 1:
				$this->db->order_by("order_date", "asc");
				break;
			case 2:
				$this->db->order_by("order_date", "desc");
				break;
			case 3:
				$this->db->order_by("completed", "asc");
				break;
			case 4:
				$this->db->order_by("completed", "desc");
				break;
			default:
				break;

		}

		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
	}

	function get_search_suggestions($search , $limit = 30 , $user_info)
	{
		$suggestions = array();
/*
		$this->db->from('orders');
		if($user_info->username != "admin")
			$this->db->where('person_id' , $user_info->person_id);
		$this->join('employees' , 'employees.person_id=orders.person_id');
		$this->db->like('order_date', $search);
		if($user_info->username == "admin")
			$this->db->like('username' , $search);
*/

		$this->db->from('orders');
		//$this->db->where('person_id' , $user_info->person_id);
		//$this->db->join('employees' , 'employees.person_id=orders.person_id');
		//$this->db->where("(order_date LIKE '%".$this->db->escape_like_str($search)."%'");

//		$this->db->from('orders');
		$this->db->like('order_date', $search);

		$this->db->order_by("order_date", "asc");
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$order_date = $row->order_date;
			$order_date1 = substr($order_date, 0 , 2);
			$order_date2 = substr($order_date, 2 , 2);
			$order_date3 = substr($order_date, 4 , 4);
			$order_date4 = $order_date1."/".$order_date2."/".$order_date3;
			$suggestions[] = $order_date4;

		}

		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;
	}

	function get_order_product($order_id , $completed)
	{

		$this->db->from('orders_products');
		$this->db->where('order_id' , $order_id);
		$results = $this->db->get();
		$data = "<tbody>";
		$nCount = 0;
		foreach($results->result() as $res)
		{
			if($completed == 0)
			{
				if($nCount % 2 == 0)
					$data .= "<tr style='background-color:#dddddd;'><td style='width:15%;'>";
				else
					$data .= "<tr style='background-color:#ffffff;'><td style='width:15%;'>";
				$data .= $res->prod_code;
				$data .= "</td><td style='width:42%;'>";
				$data .= $res->prod_desc;
				$data .= "</td><td style='width:20%;'>";
				$data .= $res->prod_pack_desc;
				$data .= "</td><td style='width:5%;'>";
				$data .= $res->prod_uos;

				$data .= "</td><td style='width:10%;'>";
				$data .= $res->prod_sell;
				$data .= "</td><td style='width:8%;'>";
				$data .= $res->quantity;
				$data .= "</td></tr>";

			}
			else
			{
				if($nCount % 2 == 0)
					$data .= "<tr style='background-color:#dddddd;'><td style='width:15%;'>";
				else
					$data .= "<tr style='background-color:#ffffff;'><td style='width:15%;'>";
				$data .= $res->prod_code;
				$data .= "</td><td style='width:32%;'>";
				$data .= $res->prod_desc;
				$data .= "</td><td style='width:10%;'>";
				$data .= $res->prod_pack_desc;
				$data .= "</td><td style='width:5%;'>";
				$data .= $res->prod_uos;
				$data .= "</td><td style='width:10%;'>";
				$data .= $res->prod_sell;
				$data .= "</td><td style='width:8%;'>";
				$data .= $res->quantity;
				$data .= "</td><td style='width:20%;'>";
				$data .= "<div class='tiny_long_long_button' style='float: left;' onmouseover='this.className=\"tiny_long_long_button_over\"' onmouseout='this.className=\"tiny_long_long_button\"' onclick='set_qty(this , $res->prod_id);'><span>".$this->lang->line('pastorders_add_to_current_trolley')."</span></div>";
				$data .= "</td></tr>";

			}
			$nCount ++;
		}
		$data .= "</tbody></table>";
		return $data;
	}

	function get_order_completed($order_id)
	{
		$this->db->from('orders');
		$this->db->where('order_id' , $order_id);
		$res = $this->db->get()->row();
		return $res->completed;
	}

	function get_order_opened($order_id)
	{
		$this->db->from('orders');
		$this->db->where('order_id' , $order_id);
		$res = $this->db->get()->row();
		return $res->opened;

	}

	function set_my_trolley($order_id)
	{
		$this->db->from('orders');
		$this->db->where('order_id' , $order_id);
		$res = $this->db->get()->row();
		$person_id = $res->person_id;

		$this->db->where('order_id' , $order_id);
		$this->db->update('orders' , array('opened' => 1));



		$this->db->from('orders_products');
		$this->db->where('order_id' , $order_id);
		$results = $this->db->get();

		foreach($results->result() as $res)
		{
			$this->db->from('cart');
			$this->db->where('person_id' , $person_id);
			$this->db->where('prod_id' , $res->prod_id);
			$res1 = $this->db->get();

			if($res1->num_rows() == 0)
			{
				$insert_data = array('prod_id' => $res->prod_id , 'quantity' => $res->quantity , 'person_id' => $person_id);
				$this->db->insert('cart' , $insert_data);
			}
			else if($res1->num_rows() == 1)
			{
				$res1_row = $res1->row();
				$quantity = $res1_row->quantity + $res->quantity;
				$insert_data = array('quantity' => $quantity);
				$this->db->where('prod_id' , $res1_row->prod_id);
				$this->db->where('person_id' , $res1_row->person_id);
				$this->db->update('cart' , $insert_data);
			}
			else return -1;

		}

		$query = "DELETE FROM epos_orders_products WHERE order_id='".$order_id."'";
		$this->db->query($query);

		return true;
	}

	function add_to_cart1($prod_id , $quantity , $person_id , $order_id)
	{
		$query = "SELECT * FROM epos_orders_products WHERE epos_orders_products.order_id='".$order_id."' and epos_orders_products.prod_id='".$prod_id."'";
		$res_op = $this->db->query($query);
		if($res_op->num_rows() == 0 || $res_op->num_rows() > 1)
			return -1;
		$row_op = $res_op->row();

		$this->db->from('product');
		$this->db->where('prod_code' , $row_op->prod_code);
		$this->db->where('prod_desc' , $row_op->prod_desc);
		$this->db->where('prod_uos' , $row_op->prod_uos);
		$this->db->where('prod_sell' , $row_op->prod_sell);
		$res_prod = $this->db->get();
		if($res_prod->num_rows() == 0) return -2;
		else if($res_prod->num_rows() > 1) return -3;

		$res_prod_row = $res_prod->row();
		$this->db->from('cart');
		$this->db->where('person_id' , $person_id);
		$this->db->where('prod_id' , $prod_id);
		$res = $this->db->get();
		if($res->num_rows() == 0)
		{
			$insert_data = array('prod_id' => $prod_id ,
					'quantity' => $quantity ,
					'person_id' => $person_id ,
					'prod_code'=>$res_prod_row->prod_code ,
					'prod_uos'=>$res_prod_row->prod_uos ,
					'start_date'=>$res_prod_row->start_date ,
					'prod_desc'=>$res_prod_row->prod_desc ,
					'prod_pack_desc'=>$res_prod_row->prod_pack_desc ,
					'vat_code'=>$res_prod_row->vat_code ,
					'prod_price'=>$res_prod_row->prod_price ,
					'group_desc'=>$res_prod_row->group_desc ,
					'prod_code1'=>$res_prod_row->prod_code1 ,
					'price_list'=>$res_prod_row->price_list ,
					'prod_level1'=>$res_prod_row->prod_level1 ,
					'prod_level2'=>$res_prod_row->prod_level2 ,
					'prod_level3'=>$res_prod_row->prod_level3 ,
					'prod_sell'=>$res_prod_row->prod_sell ,
					'prod_rrp'=>$res_prod_row->prod_rrp ,
					'wholesale'=>$res_prod_row->wholesale ,
					'retail'=>$res_prod_row->retail ,
					'p_size'=>$res_prod_row->p_size
			);
			$this->db->insert('cart' , $insert_data);
			return true;
		}
		else if($res->num_rows() == 1)
		{
			$res_row = $res->row();
			$quantity1 = $res_row->quantity + $quantity;
			$insert_data = array('quantity' => $quantity1);
			$this->db->where('person_id' , $person_id);
			$this->db->where('prod_id' , $prod_id);
			$this->db->update('cart' , $insert_data);
			return true;
		}
		else return -4;

		return true;
	}

	function add_to_cart($prod_id , $quantity , $person_id)
	{
		$this->db->from('cart');
		$this->db->where('person_id' , $person_id);
		$this->db->where('prod_id' , $prod_id);
		$res = $this->db->get();

		if($res->num_rows() == 0)
		{
			$insert_data = array('prod_id' => $prod_id , 'quantity' => $quantity , 'person_id' => $person_id);
			$this->db->insert('cart' , $insert_data);
		}
		else if($res->num_rows() == 1)
		{
			$res_row = $res->row();
			$quantity1 = $res_row->quantity + $quantity;
			$insert_data = array('quantity' => $quantity1);
			$this->db->where('person_id' , $person_id);
			$this->db->where('prod_id' , $prod_id);
			$this->db->update('cart' , $insert_data);
		}
		else return -1;

		return true;

	}

	function get_order_file_name($person_id , $order_id)
	{
		$this->db->from('employees');
		$this->db->where('person_id' , $person_id);
		$res = $this->db->get()->row();
		$account_number = $res->account_number;

		$this->db->from('orders');
		$this->db->where('person_id' , $person_id);
		$this->db->where('order_id' , $order_id);
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
}
?>
