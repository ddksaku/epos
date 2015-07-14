<?php
class Customer extends Person
{
	/*
	Determines if a given person_id is a customer
	*/
	function exists($person_id)
	{
		$this->db->from('employees');
		$this->db->join('people', 'people.person_id = employees.person_id');
		$this->db->where('employees.person_id',$person_id);

		$query = $this->db->get();

		return ($query->num_rows()==1);
	}

	/*
	Returns all the customers
	*/
	function get_all($limit = 30 , $offset = 0 , $sort_key = 1)
	{
		$this->db->from('employees');
		$this->db->join('people','employees.person_id=people.person_id');
		$this->db->where('deleted',0);
		//$this->db->where_not_in('username' , 'admin');

		switch($sort_key)
		{
			case 1:
				$this->db->order_by("username", "asc");
				break;
			case 2:
				$this->db->order_by("username", "desc");
				break;
			case 3:
				$this->db->order_by("email", "asc");
				break;
			case 4:
				$this->db->order_by("email", "desc");
				break;
			default:
				break;
		}

//		$this->db->order_by("first_name", "asc");
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
	}

	function count_all()
	{
		$this->db->from('employees');
		$this->db->where('deleted',0);
		return $this->db->count_all_results();
	}


	function total_search_num_rows($search)
	{
		$this->db->from('employees');
		$this->db->join('people','employees.person_id=people.person_id');
		$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or
		last_name LIKE '%".$this->db->escape_like_str($search)."%' or
		email LIKE '%".$this->db->escape_like_str($search)."%' or
		phone_number LIKE '%".$this->db->escape_like_str($search)."%' or
		account_number LIKE '%".$this->db->escape_like_str($search)."%' or
		CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");
		return $this->db->count_all_results();

	}


	/*
	Gets information about a particular customer
	*/
	function get_info($customer_id)
	{
		$this->db->from('employees');
		$this->db->join('people', 'people.person_id = employees.person_id');
		$this->db->where('employees.person_id',$customer_id);
		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $customer_id is NOT an customer
			$person_obj=parent::get_info(-1);

			//Get all the fields from customer table
			$fields = $this->db->list_fields('employees');

			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$person_obj->$field='';
			}

			return $person_obj;
		}
	}

	/*
	Gets information about multiple customers
	*/
	function get_multiple_info($customer_ids)
	{
		$this->db->from('employees');
		$this->db->join('people', 'people.person_id = employees.person_id');
		$this->db->where_in('employees.person_id',$customer_ids);
		$this->db->order_by("last_name", "asc");
		return $this->db->get();
	}

	/*
	Inserts or updates a customer
	*/
	function save(&$person_data, &$customer_data,$customer_id=false)
	{
		$success=false;
		//Run these queries as a transaction, we want to make sure we do all or nothing

		if(!$customer_id or !$this->exists($customer_id))
		{
			$this->db->from('employees');
			$this->db->where('deleted' , 0);
			$this->db->where('account_category' , 0);
			$this->db->where('account_number' , $customer_data['account_number']);
			$query = $this->db->get();
			if($query->num_rows()==1) return false;
		}

		$this->db->trans_start();

		if(parent::save($person_data,$customer_id))
		{
			if (!$customer_id or !$this->exists($customer_id))
			{
				$customer_data['person_id'] = $person_data['person_id'];
				$success = $this->db->insert('employees',$customer_data);
				$customer_id = $person_data['person_id'];

				$permission_orders = array('module_id' => 'orders' , 'person_id' => $customer_id);
				$this->db->insert('permissions' , $permission_orders);
				$permission_products = array('module_id' => 'products' , 'person_id' => $customer_id);
				$this->db->insert('permissions' , $permission_products);
				$permission_pastorders = array('module_id' => 'pastorders' , 'person_id' => $customer_id);
				$this->db->insert('permissions' , $permission_pastorders);
				$permission_contactus = array('module_id' => 'contactus' , 'person_id' => $customer_id);
				$this->db->insert('permissions' , $permission_contactus);


				if($customer_data['account_category'] == 2)
				{
					$permission_customers = array('module_id' => 'customers' , 'person_id' => $customer_id);
					$this->db->insert('permissions' , $permission_customers);
				}
			}
			else
			{
				$this->db->where('person_id', $customer_id);
				$success = $this->db->update('employees' , $customer_data);
				if($customer_data['account_category'] == 2)
				{
					$this->db->from('permissions');
					$this->db->where('module_id' , 'customers');
					$this->db->where('person_id' , $customer_id);
					$results = $this->db->get();
					if($results->num_rows() == 0)
					{
						$permission_customers = array('module_id' => 'customers' , 'person_id' => $customer_id);
						$this->db->insert('permissions' , $permission_customers);
					}
				}
				else
				{
					$this->db->from('permissions');
					$this->db->where('module_id' , 'customers');
					$this->db->where('person_id' , $customer_id);
					$results = $this->db->get();
					if($results->num_rows() == 1)
					{
						$this->db->where('person_id' , $customer_id);
						$this->db->where('module_id' , 'customers');
						$this->db->delete('permissions');
					}
				}

			}

		}

		$this->db->trans_complete();
		return $success;
	}

	/*
	Deletes one customer
	*/
	function delete($customer_id)
	{
		$this->db->where('person_id', $customer_id);
		return $this->db->update('employees', array('deleted' => 1));
	}

	/*
	Deletes a list of customers
	*/
	function delete_list($customer_ids)
	{
//		$this->db->where_in('person_id',$customer_ids);
//		return $this->db->update('employees', array('deleted' => 1));
		$this->db->where_in('person_id',$customer_ids);
		$success = $this->db->delete('employees');
		if($success == false)
			return $success;
		$this->db->where_in('person_id' , $customer_ids);
		$success = $this->db->delete('people');

		$this->db->where_in('person_id' , $customer_ids);
		$success = $this->db->delete('permissions');
		return $success;
 	}

 	/*
	Get search suggestions to find customers
	*/
	function get_search_suggestions($search , $limit=25 , $user_info)
	{
		$suggestions = array();

		$this->db->from('employees');
		$this->db->join('people','employees.person_id=people.person_id');
		if($user_info->username != "admin")
			$this->db->where_not_in("username" , array("admin"));
		$this->db->where("(username LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");
		$this->db->order_by("username", "asc");
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=$row->username;
		}

		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;

	}

	/*
	Get search suggestions to find customers
	*/
	function get_customer_search_suggestions($search,$limit=25)
	{
		$suggestions = array();

		$this->db->from('employees');
		$this->db->join('people','employees.person_id=people.person_id');
		$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or
		last_name LIKE '%".$this->db->escape_like_str($search)."%' or
		CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");
		$this->db->order_by("first_name", "asc");
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=$row->person_id.'|'.$row->first_name;
		}

		$this->db->from('employees');
		$this->db->join('people','employees.person_id=people.person_id');
		$this->db->where('deleted',0);
		$this->db->like("account_number",$search);
		$this->db->order_by("account_number", "asc");
		$by_account_number = $this->db->get();
		foreach($by_account_number->result() as $row)
		{
			$suggestions[]=$row->person_id.'|'.$row->account_number;
		}

		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;

	}
	/*
	Preform a search on customers
	*/
	function search($search , $limit = 20 , $offset = 0 , $sort_key = 1)
	{
		$this->db->from('employees');
		$this->db->join('people','employees.person_id=people.person_id');
		$this->db->where("(username LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");

		switch($sort_key)
		{
			case 1:
				$this->db->order_by("username", "asc");
				break;
			case 2:
				$this->db->order_by("username", "desc");
				break;
			case 3:
				$this->db->order_by("email", "asc");
				break;
			case 4:
				$this->db->order_by("email", "desc");
				break;
			default:
				break;
		}
		//$this->db->order_by("first_name", "asc");
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
	}

}
?>
