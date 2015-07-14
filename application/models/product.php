<?php
class Product extends CI_Model
{
	/*
	Determines if a given item_id is an item
	*/
	function exists($item_id)
	{
		$this->db->from('product');
		$this->db->where('prod_id',$item_id);
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}


	function get_all_categories($category_id = 0)
	{
		if($category_id == 0)
			$table_rows = "<table><tr><td><ul style='list-style: none; margin: 0px 0px 0px 5px; padding: 0;'><li class='bg_list_selected' onclick='select_category(this , 0);'>>&nbsp;All</li>";
		else
			$table_rows = "<table><tr><td><ul style='list-style: none; margin: 0px 0px 0px 5px; padding: 0;'><li class='bg_list' onclick='select_category(this , 0);'>>&nbsp;All</li>";

		$results = $this->db->query("SELECT * FROM epos_categories WHERE parent_id=0 ORDER BY category_name ASC");

		foreach($results->result() as $res)
		{
			if($category_id == $res->category_id)
				$table_rows .= "<li class='bg_list_selected' onclick='select_category(this , $res->category_id);'>>&nbsp;$res->category_name</li>";
			else
				$table_rows .= "<li class='bg_list' onclick='select_category(this , $res->category_id);'>>&nbsp;$res->category_name</li>";

			$query = "select * from epos_categories where parent_id='".$res->category_id."' ORDER BY category_name ASC";
			$results_sub = $this->db->query($query);
			foreach($results_sub->result() as $res_sub)
			{
				if($category_id == $res_sub->category_id)
					$table_rows .= "<li class='bg_list_selected' onclick='select_category(this , $res_sub->category_id);'><div style='color:#FF0000'><span>&nbsp;".$res_sub->category_name."</span></div></li>";
				else
					$table_rows .= "<li class='bg_list' onclick='select_category(this , $res_sub->category_id);'><div style='color:#999999'><span>&nbsp;".$res_sub->category_name."</span></div></li>";
			}
		}
		$table_rows .= "</ul></td></tr></table>";
		return $table_rows;
	}

	function get_all($limit = 10000 ,  $offset = 0 , $user_info , $sort_key = 3)
	{
		$query = "price_list IN ('0'";
		if ($user_info->price_list005)
			$query .= ",'05'";
		if ($user_info->price_list010)
			$query .= ",'10'";
		if ($user_info->price_list011)
			$query .= ",'11'";
		if ($user_info->price_list999)
			$query .= ",'999'";
		$query .=  ")";

		$this->db->from('product');
		$this->db->where($query);

		switch($sort_key)
		{
			case 1:
				$this->db->order_by("prod_code", "asc");
				break;
			case 2:
				$this->db->order_by("prod_code", "desc");
				break;
			case 3:
				$this->db->order_by("prod_desc", "asc");
				break;
			case 4:
				$this->db->order_by("prod_desc", "desc");
				break;
			case 5:
				$this->db->order_by("prod_uos", "asc");
				break;
			case 6:
				$this->db->order_by("prod_uos", "desc");
				break;
			default:
				break;

		}
		//$this->db->order_by("prod_list", "asc");
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();

	}


	function get_all_category($limit = 10000 ,  $offset = 0 , $user_info , $sort_key = 3 , $category_id = 0)
	{
		if($category_id == 0)
			$query1 = "";
		else
		{
			$query = "SELECT * FROM epos_categories WHERE category_id='".$category_id."'";
			$results_category = $this->db->query($query);
			$res_category = $results_category->row();

			$query1 = "(";

			if($res_category->parent_id == 0)
			{
				$query = "SELECT * FROM epos_categories WHERE parent_id='".$res_category->category_id."'";
				$results_subcategory = $this->db->query($query);
				$nCount = 0;
				foreach($results_subcategory->result() as $res_subcategory)
				{
					if($nCount == 0)
					{
						$query1 .= "group_desc like '%";
						$query1 .= $res_subcategory->filter_desc;
						$query1 .= "%'";
					}
					else
					{
						$query1 .= " or group_desc like '%";
						$query1 .= $res_subcategory->filter_desc;
						$query1 .= "%'";
					}
					$nCount ++;

				}
			}
			else
			{
				$query1 .= "group_desc like '%";
				$query1 .= $res_category->filter_desc;
				$query1 .= "%'";

			}

			$query1 .= ")";
		}


		$query = "price_list IN ('0'";
		if ($user_info->price_list005)
			$query .= ",'05'";
		if ($user_info->price_list010)
			$query .= ",'10'";
		if ($user_info->price_list011)
			$query .= ",'11'";
		if ($user_info->price_list999)
			$query .= ",'999'";
		$query .=  ")";

		$this->db->from('product');
		$this->db->where($query);

		if($query1 != "")
			$this->db->where($query1);

		switch($sort_key)
		{
			case 1:
				$this->db->order_by("prod_code", "asc");
				break;
			case 2:
				$this->db->order_by("prod_code", "desc");
				break;
			case 3:
				$this->db->order_by("prod_desc", "asc");
				break;
			case 4:
				$this->db->order_by("prod_desc", "desc");
				break;
			case 5:
				$this->db->order_by("prod_uos", "asc");
				break;
			case 6:
				$this->db->order_by("prod_uos", "desc");
				break;
			default:
				break;

		}
		//$this->db->order_by("prod_list", "asc");
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
	}

	function get_all_admin_category($limit = 10000 ,  $offset = 0 , $sort_key = 3 , $category_id = 0)
	{
		if($category_id == 0)
			$query1 = "";
		else
		{
			$query = "SELECT * FROM epos_categories WHERE category_id='".$category_id."'";
			$results_category = $this->db->query($query);
			$res_category = $results_category->row();

			$query1 = "(";

			if($res_category->parent_id == 0)
			{
				$query = "SELECT * FROM epos_categories WHERE parent_id='".$res_category->category_id."'";
				$results_subcategory = $this->db->query($query);
				$nCount = 0;
				foreach($results_subcategory->result() as $res_subcategory)
				{
					if($nCount == 0)
					{
						$query1 .= "group_desc like '%";
						$query1 .= $res_subcategory->filter_desc;
						$query1 .= "%'";
					}
					else
					{
						$query1 .= " or group_desc like '%";
						$query1 .= $res_subcategory->filter_desc;
						$query1 .= "%'";
					}
					$nCount ++;

				}
			}
			else
			{
				$query1 .= "group_desc like '%";
				$query1 .= $res_category->filter_desc;
				$query1 .= "%'";

			}

			$query1 .= ")";
		}




		$this->db->from('product');

		if($query1 != "")
			$this->db->where($query1);

		switch($sort_key)
		{
			case 1:
				$this->db->order_by("prod_code", "asc");
				break;
			case 2:
				$this->db->order_by("prod_code", "desc");
				break;
			case 3:
				$this->db->order_by("prod_desc", "asc");
				break;
			case 4:
				$this->db->order_by("prod_desc", "desc");
				break;
			case 5:
				$this->db->order_by("prod_uos", "asc");
				break;
			case 6:
				$this->db->order_by("prod_uos", "desc");
				break;
			default:
				break;

		}

		//$this->db->order_by("prod_code", "asc");
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
	}


	function get_all_admin($limit = 10000 ,  $offset = 0 , $sort_key = 3)
	{
		$this->db->from('product');

		switch($sort_key)
		{
			case 1:
				$this->db->order_by("prod_code", "asc");
				break;
			case 2:
				$this->db->order_by("prod_code", "desc");
				break;
			case 3:
				$this->db->order_by("prod_desc", "asc");
				break;
			case 4:
				$this->db->order_by("prod_desc", "desc");
				break;
			case 5:
				$this->db->order_by("prod_uos", "asc");
				break;
			case 6:
				$this->db->order_by("prod_uos", "desc");
				break;
			default:
				break;

		}

		//$this->db->order_by("prod_code", "asc");
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
	}


	function count_all_admin()
	{
		$this->db->from('product');
		return $this->db->count_all_results();
	}


	function count_all_admin_category($category_id = 0)
	{
		if($category_id == 0)
			$query1 = "";
		else
		{
			$query = "SELECT * FROM epos_categories WHERE category_id='".$category_id."'";
			$results_category = $this->db->query($query);
			$res_category = $results_category->row();

			$query1 = "(";

			if($res_category->parent_id == 0)
			{
				$query = "SELECT * FROM epos_categories WHERE parent_id='".$res_category->category_id."'";
				$results_subcategory = $this->db->query($query);
				$nCount = 0;
				foreach($results_subcategory->result() as $res_subcategory)
				{
					if($nCount == 0)
					{
						$query1 .= "group_desc like '%";
						$query1 .= $res_subcategory->filter_desc;
						$query1 .= "%'";
					}
					else
					{
						$query1 .= " or group_desc like '%";
						$query1 .= $res_subcategory->filter_desc;
						$query1 .= "%'";
					}
					$nCount ++;

				}
			}
			else
			{
				$query1 .= "group_desc like '%";
				$query1 .= $res_category->filter_desc;
				$query1 .= "%'";

			}

			$query1 .= ")";
		}

		$this->db->from('product');

		if($query1 != "")
			$this->db->where($query1);

		return $this->db->count_all_results();
	}

	function count_all_category($user_info , $category_id = 0)
	{
		if($category_id == 0)
			$query1 = "";
		else
		{
			$query = "SELECT * FROM epos_categories WHERE category_id='".$category_id."'";
			$results_category = $this->db->query($query);
			$res_category = $results_category->row();

			$query1 = "(";

			if($res_category->parent_id == 0)
			{
				$query = "SELECT * FROM epos_categories WHERE parent_id='".$res_category->category_id."'";
				$results_subcategory = $this->db->query($query);
				$nCount = 0;
				foreach($results_subcategory->result() as $res_subcategory)
				{
					if($nCount == 0)
					{
						$query1 .= "group_desc like '%";
						$query1 .= $res_subcategory->filter_desc;
						$query1 .= "%'";
					}
					else
					{
						$query1 .= " or group_desc like '%";
						$query1 .= $res_subcategory->filter_desc;
						$query1 .= "%'";
					}
					$nCount ++;

				}
							}
			else
			{
				$query1 .= "group_desc like '%";
				$query1 .= $res_category->filter_desc;
				$query1 .= "%'";

			}

			$query1 .= ")";
		}

		$query="price_list IN ('0'";
		if ($user_info->price_list005)
			$query.=",'05'";
		if ($user_info->price_list010)
			$query.=",'10'";
		if ($user_info->price_list011)
			$query.=",'11'";
		if ($user_info->price_list999)
			$query.=",'999'";
		$query.= ")";

		$this->db->from('product');
		$this->db->where($query);

		if($query1 != "")
			$this->db->where($query1);
		return $this->db->count_all_results();
	}

	function count_all($user_info)
	{
		$query="price_list IN ('0'";
		if ($user_info->price_list005)
			$query.=",'05'";
		if ($user_info->price_list010)
			$query.=",'10'";
		if ($user_info->price_list011)
			$query.=",'11'";
		if ($user_info->price_list999)
			$query.=",'999'";
		$query.= ")";

		$this->db->from('product');
		$this->db->where($query);
		return $this->db->count_all_results();
	}

	/*
	Gets information about a particular item
	*/
	function get_info($product_id)
	{
		$this->db->from('product');
		$this->db->where('prod_id',$product_id);

		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj = new stdClass();

			//Get all the fields from items table
			$fields = $this->db->list_fields('product');

			foreach ($fields as $field)
			{
				$product_obj->$field = '';
			}

			return $product_obj;
		}
	}

	/*
	Get an item id given an item number
	*/
	function get_item_id($item_number)
	{
		$this->db->from('items');
		$this->db->where('item_number',$item_number);

		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row()->item_id;
		}

		return false;
	}

	/*
	Gets information about multiple items
	*/
	function get_multiple_info($item_ids)
	{
		$this->db->from('items');
		$this->db->where_in('item_id',$item_ids);
		$this->db->order_by("item", "asc");
		return $this->db->get();
	}

	/*
	Inserts or updates a item
	*/
	function save(&$prod_data,$prod_id=false)
	{

		if (!$prod_id or !$this->exists($prod_id))
		{
/*
			$this->db->from('product');
			$this->db->where('prod_code' , $prod_data['prod_code']);
			$this->db->where('prod_uos' , $prod_data['prod_uos']);
			$this->db->where('start_date' , $prod_data['start_date']);
			//$this->db->where('prod_desc' , $prod_data['prod_desc']);
			//$this->db->where('prod_pack_desc' , $prod_data['prod_pack_desc']);
			$this->db->where('vat_code' , $prod_data['vat_code']);
			$this->db->where('prod_price' , $prod_data['prod_price']);
			//$this->db->where('group_desc' , $prod_data['group_desc']);
			$this->db->where('prod_code1' , $prod_data['prod_code1']);
			$this->db->where('price_list' , $prod_data['price_list']);
			$this->db->where('prod_level1' , $prod_data['prod_level1']);
			$this->db->where('prod_level2' , $prod_data['prod_level2']);
			$this->db->where('prod_level3' , $prod_data['prod_level3']);
			$this->db->where('prod_sell' , $prod_data['prod_sell']);
			$this->db->where('prod_rrp' , $prod_data['prod_rrp']);
			$this->db->where('wholesale' , $prod_data['wholesale']);
			$this->db->where('retail' , $prod_data['retail']);
			$this->db->where('p_size' , $prod_data['p_size']);
			$query = $this->db->get();

			if($query->num_rows() == 1)
			{
				//$update_prod_id = $query->row()->prod_id;
				return true;
			}
			else if($query->num_rows() == 0)
			{
*/
				if($this->db->insert('product' , $prod_data))
				{
					$prod_data['prod_id'] = $this->db->insert_id();
					return true;
				}
				return false;
//			}
//			else
//				return false;

		}

		$this->db->where('prod_id', $prod_id);
		return $this->db->update('products',$prod_data);

	}

	function delete_all()
	{
		$res = $this->db->query("DELETE FROM epos_product");
		if(!$res) return false;
		$res = $this->db->query("TRUNCATE TABLE epos_product");
		return true;
	}

	function save_excel(&$prod_data,$prod_id=false)
	{
		if($this->db->insert('product' , $prod_data))
		{
			$prod_data['prod_id'] = $this->db->insert_id();
			return true;
		}
		return false;
	}




	/*
	Updates multiple items at once
	*/
	function update_multiple($item_data,$item_ids)
	{
		$this->db->where_in('item_id',$item_ids);
		return $this->db->update('items',$item_data);
	}

	/*
	Deletes one item
	*/
	function delete($item_id)
	{
		$this->db->where('item_id', $item_id);
		return $this->db->update('items', array('deleted' => 1));
	}

	/*
	Deletes a list of items
	*/
	function delete_list($item_ids)
	{
		$this->db->where_in('item_id',$item_ids);
		return $this->db->update('items', array('deleted' => 1));
 	}

 	/*
	Get search suggestions to find items
	*/
	function get_search_suggestions($search,$limit=25)
	{
		$suggestions = array();

		$this->db->from('items');
		$this->db->like('name', $search);
		$this->db->where('deleted',0);
		$this->db->order_by("name", "asc");
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=$row->name;
		}

		$this->db->select('category');
		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->distinct();
		$this->db->like('category', $search);
		$this->db->order_by("category", "asc");
		$by_category = $this->db->get();
		foreach($by_category->result() as $row)
		{
			$suggestions[]=$row->category;
		}

		$this->db->from('items');
		$this->db->like('item_number', $search);
		$this->db->where('deleted',0);
		$this->db->order_by("item_number", "asc");
		$by_item_number = $this->db->get();
		foreach($by_item_number->result() as $row)
		{
			$suggestions[]=$row->item_number;
		}


		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;

	}

	function get_search_suggestions0($search , $limit=30 , $user_info)
	{

		$suggestions = array();

		$this->db->from('product');
		$this->db->like('prod_code', $search);



		if($user_info->username != "admin")
		{
			$query = "price_list IN ('0'";
			if ($user_info->price_list005)
				$query .= ",'05'";
			if ($user_info->price_list010)
				$query .= ",'10'";
			if ($user_info->price_list011)
				$query .= ",'11'";
			if ($user_info->price_list999)
				$query .= ",'999'";
			$query .=  ")";
			$this->db->where($query);
		}

		$this->db->order_by("prod_code", "asc");
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=$row->prod_code;
		}

		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;

	}

	function get_search_suggestions1($search , $limit=30 , $user_info)
	{
		$suggestions = array();

		$this->db->from('product');
		//$this->db->where("(wholesale LIKE '%".$this->db->escape_like_str($search)."%' or retail LIKE '%".$this->db->escape_like_str($search)."%')");
		$this->db->like('wholesale' , $search);
		if($user_info->username != "admin")
		{
			$query = "price_list IN ('0'";
			if ($user_info->price_list005)
				$query .= ",'05'";
			if ($user_info->price_list010)
				$query .= ",'10'";
			if ($user_info->price_list011)
				$query .= ",'11'";
			if ($user_info->price_list999)
				$query .= ",'999'";
			$query .=  ")";
			$this->db->where($query);
		}
		$this->db->order_by("prod_code", "asc");
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=$row->wholesale;
		}

		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;

	}



	function get_search_suggestions2($search , $limit=30 , $user_info)
	{
		$suggestions = array();

		$this->db->from('product');
		//$this->db->where("(wholesale LIKE '%".$this->db->escape_like_str($search)."%' or retail LIKE '%".$this->db->escape_like_str($search)."%')");
		$this->db->like('prod_desc' , $this->db->escape_like_str($search));
		if($user_info->username != "admin")
		{
			$query = "price_list IN ('0'";
			if ($user_info->price_list005)
				$query .= ",'05'";
			if ($user_info->price_list010)
				$query .= ",'10'";
			if ($user_info->price_list011)
				$query .= ",'11'";
			if ($user_info->price_list999)
				$query .= ",'999'";
			$query .=  ")";
			$this->db->where($query);
		}
		$this->db->order_by("prod_code", "asc");
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=$row->prod_desc;
		}

		//only return $limit suggestions
		if(count($suggestions) > $limit)
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;

	}




	function get_item_search_suggestions($search,$limit=25)
	{
		$suggestions = array();

		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->like('name', $search);
		$this->db->order_by("name", "asc");
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=$row->item_id.'|'.$row->name;
		}

		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->like('item_number', $search);
		$this->db->order_by("item_number", "asc");
		$by_item_number = $this->db->get();
		foreach($by_item_number->result() as $row)
		{
			$suggestions[]=$row->item_id.'|'.$row->item_number;
		}

		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;

	}

	function get_category_suggestions($search)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select('category');
		$this->db->from('items');
		$this->db->like('category', $search);
		$this->db->where('deleted', 0);
		$this->db->order_by("category", "asc");
		$by_category = $this->db->get();
		foreach($by_category->result() as $row)
		{
			$suggestions[]=$row->category;
		}

		return $suggestions;
	}


	function total_search_num_rows($search0 , $search1 , $search2 , $user_info)
	{
		$this->db->from('product');
		$this->db->where("(prod_code LIKE '%".$this->db->escape_like_str($search0)."%' and
		wholesale LIKE '%".$this->db->escape_like_str($search1)."%' and
		prod_desc LIKE '%".$this->db->escape_like_str($search2)."%')");
		if($user_info->username != "admin")
		{
			$query = "price_list IN ('0'";
			if ($user_info->price_list005)
				$query .= ",'05'";
			if ($user_info->price_list010)
				$query .= ",'10'";
			if ($user_info->price_list011)
				$query .= ",'11'";
			if ($user_info->price_list999)
				$query .= ",'999'";
			$query .=  ")";
			$this->db->where($query);
		}
		return $this->db->count_all_results();
	}


	function total_search_num_rows_category($search0 , $search1 , $search2 , $user_info , $category_id = 0)
	{
		if($category_id == 0)
			$query1 = "";
		else
		{
			$query = "SELECT * FROM epos_categories WHERE category_id='".$category_id."'";
			$results_category = $this->db->query($query);
			$res_category = $results_category->row();

			$query1 = "(";

			if($res_category->parent_id == 0)
			{
				$query = "SELECT * FROM epos_categories WHERE parent_id='".$res_category->category_id."'";
				$results_subcategory = $this->db->query($query);
				$nCount = 0;
				foreach($results_subcategory->result() as $res_subcategory)
				{
					if($nCount == 0)
					{
						$query1 .= "group_desc like '%";
						$query1 .= $res_subcategory->filter_desc;
						$query1 .= "%'";
					}
					else
					{
						$query1 .= " or group_desc like '%";
						$query1 .= $res_subcategory->filter_desc;
						$query1 .= "%'";
					}
					$nCount ++;

				}
			}
			else
			{
				$query1 .= "group_desc like '%";
				$query1 .= $res_category->filter_desc;
				$query1 .= "%'";

			}

			$query1 .= ")";
		}

		$this->db->from('product');
		$this->db->where("(prod_code LIKE '%".$this->db->escape_like_str($search0)."%' and
		wholesale LIKE '%".$this->db->escape_like_str($search1)."%' and
		prod_desc LIKE '%".$this->db->escape_like_str($search2)."%')");
		if($user_info->username != "admin")
		{
			$query = "price_list IN ('0'";
			if ($user_info->price_list005)
				$query .= ",'05'";
			if ($user_info->price_list010)
				$query .= ",'10'";
			if ($user_info->price_list011)
				$query .= ",'11'";
			if ($user_info->price_list999)
				$query .= ",'999'";
			$query .=  ")";
			$this->db->where($query);
		}

		if($query1 != "")
			$this->db->where($query1);

		return $this->db->count_all_results();
	}


	/*
	Preform a search on items
	*/
	function search($search0 , $search1 , $search2 , $user_info , $limit = 30 ,  $offset = 0 , $sort_key = 3)
	{
		$this->db->from('product');
		$this->db->where("(prod_code LIKE '%".$this->db->escape_like_str($search0)."%' and
		wholesale LIKE '%".$this->db->escape_like_str($search1)."%' and
		prod_desc LIKE '%".$this->db->escape_like_str($search2)."%')");
		if($user_info->username != "admin")
		{
			$query = "price_list IN ('0'";
			if ($user_info->price_list005)
				$query .= ",'05'";
			if ($user_info->price_list010)
				$query .= ",'10'";
			if ($user_info->price_list011)
				$query .= ",'11'";
			if ($user_info->price_list999)
				$query .= ",'999'";
			$query .=  ")";
			$this->db->where($query);
		}

		switch($sort_key)
		{
			case 1:
				$this->db->order_by("prod_code", "asc");
				break;
			case 2:
				$this->db->order_by("prod_code", "desc");
				break;
			case 3:
				$this->db->order_by("prod_desc", "asc");
				break;
			case 4:
				$this->db->order_by("prod_desc", "desc");
				break;
			case 5:
				$this->db->order_by("prod_uos", "asc");
				break;
			case 6:
				$this->db->order_by("prod_uos", "desc");
				break;
			default:
				break;

		}
		//$this->db->order_by("prod_code", "asc");
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
	}

	function search_category($search0 , $search1 , $search2 , $user_info , $limit = 30 ,  $offset = 0 , $sort_key = 3 , $category_id = 0)
	{
		if($category_id == 0)
			$query1 = "";
		else
		{
			$query = "SELECT * FROM epos_categories WHERE category_id='".$category_id."'";
			$results_category = $this->db->query($query);
			$res_category = $results_category->row();

			$query1 = "(";

			if($res_category->parent_id == 0)
			{
				$query = "SELECT * FROM epos_categories WHERE parent_id='".$res_category->category_id."'";
				$results_subcategory = $this->db->query($query);
				$nCount = 0;
				foreach($results_subcategory->result() as $res_subcategory)
				{
					if($nCount == 0)
					{
						$query1 .= "group_desc like '%";
						$query1 .= $res_subcategory->filter_desc;
						$query1 .= "%'";
					}
					else
					{
						$query1 .= " or group_desc like '%";
						$query1 .= $res_subcategory->filter_desc;
						$query1 .= "%'";
					}
					$nCount ++;

				}
			}
			else
			{
				$query1 .= "group_desc like '%";
				$query1 .= $res_category->filter_desc;
				$query1 .= "%'";

			}

			$query1 .= ")";
		}

		$this->db->from('product');

		if($query1 != "")
			$this->db->where($query1);

////		$this->db->like('prod_code' , $this->db->escape_like_str($search0));
////		$this->db->like('prod_desc' , $this->db->escape_like_str($search2));
////		$this->db->like('retail' , $this->db->escape_like_str($search1));
		$this->db->where("(prod_code LIKE '%".$this->db->escape_like_str($search0)."%' AND
		prod_desc LIKE '%".$this->db->escape_like_str($search2)."%' AND
		(retail LIKE '%".$this->db->escape_like_str($search1)."%' OR wholesale LIKE '%".$this->db->escape_like_str($search1)."%'))");
		if($user_info->username != "admin")
		{
			$query = "price_list IN ('0'";
			if ($user_info->price_list005)
				$query .= ",'05'";
			if ($user_info->price_list010)
				$query .= ",'10'";
			if ($user_info->price_list011)
				$query .= ",'11'";
			if ($user_info->price_list999)
				$query .= ",'999'";
			$query .=  ")";
			$this->db->where($query);
		}

		switch($sort_key)
		{
			case 1:
				$this->db->order_by("prod_code", "asc");
				break;
			case 2:
				$this->db->order_by("prod_code", "desc");
				break;
			case 3:
				$this->db->order_by("prod_desc", "asc");
				break;
			case 4:
				$this->db->order_by("prod_desc", "desc");
				break;
			case 5:
				$this->db->order_by("prod_uos", "asc");
				break;
			case 6:
				$this->db->order_by("prod_uos", "desc");
				break;
			default:
				break;

		}



		//$this->db->order_by("prod_code", "asc");
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
	}


	function get_categories()
	{
		$this->db->select('category');
		$this->db->from('items');
		$this->db->where('deleted',0);
		$this->db->distinct();
		$this->db->order_by("category", "asc");

		return $this->db->get();
	}

	function to_cart($prod_id , $mode , $person_id , $quantity = 1)
	{
		$query = "SELECT * FROM epos_cart WHERE prod_id='".$prod_id."' and person_id='".$person_id."'";
		$res = $this->db->query($query);

		if($res->num_rows() == 0)
		{
			if($mode == 1 || $mode == 3)
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
				$this->db->insert('cart' , $cart_data);
				return $quantity;
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
			else if($mode == 3)
			{
				$quantity1 = $quantity;
			}

			if($quantity1 == 0)
			{
				$this->db->query("DELETE FROM epos_cart WHERE prod_id='".$prod_id."' and person_id='".$person_id."'");
				return 0;
			}
			else
			{
				$cart_data = array('quantity'=>$quantity1);
				$this->db->where('prod_id' , $prod_id);
				$this->db->where('person_id' , $person_id);
				$this->db->update('cart' , $cart_data);
				return $quantity1;
			}
		}
		else
			return -1;
	}

	function get_cart_quantity($prod_id , $person_id)
	{
		$query = "SELECT * FROM epos_cart WHERE prod_id='".$prod_id."' and person_id='".$person_id."'";
		$results = $this->db->query($query)->row();
		return $results->quantity;
	}

	function get_ftp_location()
	{
		$this->db->from('app_config');
		$this->db->where('key' , 'ftp_location');
		$result = $this->db->get()->row();
		return $result->value;
	}
}
?>
