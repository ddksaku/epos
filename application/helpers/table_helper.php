<?php

function get_customer_manage_table($people , $controller , $sort_key = 1 , $user_info)
{
	$CI =& get_instance();
	$table='<table class="tablesorter_user" id="sortable_table">';

	$headers = array($CI->lang->line('common_user_name'),
	$CI->lang->line('common_email'),
	'&nbsp;');

	$table.='<thead><tr>';
	$nCount = 1;
	$nCount2 = 0;
	foreach($headers as $header)
	{
		if($header == '&nbsp;')
		{
			$table .= "<th>".$header."</th>";
			$nCount2 ++;
			continue;
		}
/*
		if($nCount2 == 0)
		{
			$table .= "<th>".$header."</th>";
			$nCount2 ++;
			continue;

		}
*/
		$nCount1 = $nCount + 1;

		if($nCount == $sort_key)
			$table .= "<th class='headerSortDown' onclick='sort_product(this);'>".$header."</th>";
		else if($nCount1 == $sort_key)
			$table .= "<th class='headerSortUp' onclick='sort_product(this);'>".$header."</th>";
		else if($nCount < 5)
			$table .= "<th class='header' onclick='sort_product(this);'>".$header."</th>";

		$nCount += 2;
	}
	$table.='</tr></thead><tbody>';
	$table.=get_customer_manage_table_data_rows($people , $controller , $user_info);
	$table.='</tbody></table>';
	return $table;
}


function get_customer_manage_table_data_rows($people , $controller , $user_info)
{
	$CI =& get_instance();
	$table_data_rows = '';
	$nCount = 0;

	foreach($people->result() as $person)
	{
		if($person->username != "admin")
		{
			$table_data_rows .= get_customer_data_row($person , $controller , $nCount);
			$nCount ++;
		}
		else if($person->username == "admin")
		{
			if($user_info->username == "admin")
			{
				$table_data_rows .= get_customer_data_row($person , $controller , $nCount);
				$nCount ++;
			}
		}
	}

	if($people->num_rows() == 0)
	{
		$table_data_rows .= "<tr><td colspan='3'><div class='warning_message' style='padding:7px;'>".$CI->lang->line('common_no_persons_to_display')."</div></tr></tr>";
	}

	return $table_data_rows;
}

function get_customer_data_row($person , $controller , $nCount = 0)
{
	$CI =& get_instance();
	$controller_name = strtolower(get_class($CI));
	$width = $controller->get_form_width();
	if($nCount % 2 == 0)
		$table_data_row = '<tr style="background-color:#E4E4FF;">';
	else
		$table_data_row = '<tr style="background-color:#FFFFFF;">';
	$table_data_row .= '<td width="45%">'.character_limiter($person->username,13).'</td>';
	$table_data_row .= '<td width="45%">'.mailto($person->email,character_limiter($person->email,22)).'</td>';
	$table_data_row .= '<td width="10%" style="text-align:center;">';
	$table_data_row .= "<div onclick='popup_dialog(".$person->person_id.")' class='tiny_long_button' onmouseover='this.className=\"tiny_long_button_over\"' onmouseout='this.className=\"tiny_long_button\"'><span>".$CI->lang->line('common_view')."&nbsp;/&nbsp;".$CI->lang->line('common_edit')."</span></div>";
	$table_data_row .= '</tr>';
	return $table_data_row;
}



/*
Gets the html table to manage people.
*/
function get_people_manage_table($people , $controller)
{
	$CI =& get_instance();
	$table='<table class="tablesorter" id="sortable_table">';

	$headers = array('<input type="checkbox" id="select_all" />',
	$CI->lang->line('common_name'),
	$CI->lang->line('common_email'),
	$CI->lang->line('common_user_name'),
	'&nbsp');

	$table.='<thead><tr>';
	foreach($headers as $header)
	{
		$table.="<th>$header</th>";
	}
	$table.='</tr></thead><tbody>';
	$table.=get_people_manage_table_data_rows($people,$controller);
	$table.='</tbody></table>';
	return $table;
}

/*
Gets the html data rows for the people.
*/
function get_people_manage_table_data_rows($people,$controller)
{
	$CI =& get_instance();
	$table_data_rows='';

	foreach($people->result() as $person)
	{
		$table_data_rows.=get_person_data_row($person,$controller);
	}

	if($people->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='6'><div class='warning_message' style='padding:7px;'>".$CI->lang->line('common_no_persons_to_display')."</div></tr></tr>";
	}

	return $table_data_rows;
}

function get_person_data_row($person,$controller)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));
	$width = $controller->get_form_width();

	$table_data_row='<tr>';
	$table_data_row.="<td width='5%'><input type='checkbox' id='person_$person->person_id' value='".$person->person_id."'/></td>";
	$table_data_row.='<td width="20%">'.character_limiter($person->first_name,13).'</td>';
	$table_data_row.='<td width="30%">'.mailto($person->email,character_limiter($person->email,22)).'</td>';
	$table_data_row.='<td width="20%">'.character_limiter($person->username,13).'</td>';
	$table_data_row.='<td width="5%">'.anchor($controller_name."/view/$person->person_id/width:$width", $CI->lang->line('common_edit'),array('class'=>'thickbox','title'=>$CI->lang->line($controller_name.'_update'))).'</td>';
	$table_data_row.='</tr>';

	return $table_data_row;
}

/*
Gets the html table to manage suppliers.
*/
function get_supplier_manage_table($suppliers,$controller)
{
	$CI =& get_instance();
	$table='<table class="tablesorter" id="sortable_table">';

	$headers = array('<input type="checkbox" id="select_all" />',
	$CI->lang->line('suppliers_company_name'),
	$CI->lang->line('common_name'),
	$CI->lang->line('common_email'),
	$CI->lang->line('common_account_number'),
	'&nbsp');

	$table.='<thead><tr>';
	foreach($headers as $header)
	{
		$table.="<th>$header</th>";
	}
	$table.='</tr></thead><tbody>';
	$table.=get_supplier_manage_table_data_rows($suppliers,$controller);
	$table.='</tbody></table>';
	return $table;
}

/*
Gets the html data rows for the supplier.
*/
function get_supplier_manage_table_data_rows($suppliers,$controller)
{
	$CI =& get_instance();
	$table_data_rows='';

	foreach($suppliers->result() as $supplier)
	{
		$table_data_rows.=get_supplier_data_row($supplier,$controller);
	}

	if($suppliers->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='7'><div class='warning_message' style='padding:7px;'>".$CI->lang->line('common_no_persons_to_display')."</div></tr></tr>";
	}

	return $table_data_rows;
}

function get_supplier_data_row($supplier,$controller)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));
	$width = $controller->get_form_width();

	$table_data_row='<tr>';
	$table_data_row.="<td width='5%'><input type='checkbox' id='person_$supplier->person_id' value='".$supplier->person_id."'/></td>";
	$table_data_row.='<td width="17%">'.character_limiter($supplier->company_name,13).'</td>';
	$table_data_row.='<td width="17%">'.character_limiter($supplier->first_name,13).'</td>';
	$table_data_row.='<td width="22%">'.mailto($supplier->email,character_limiter($supplier->email,22)).'</td>';
	$table_data_row.='<td width="17%">'.character_limiter($supplier->account_number,13).'</td>';
	$table_data_row.='<td width="5%">'.anchor($controller_name."/view/$supplier->person_id/width:$width", $CI->lang->line('common_edit'),array('class'=>'thickbox','title'=>$CI->lang->line($controller_name.'_update'))).'</td>';
	$table_data_row.='</tr>';

	return $table_data_row;
}

/*
Gets the html table to manage items.
*/
function get_products_manage_table($products , $controller , $sort_key)
{
	$CI =& get_instance();
	$table = '<table class="tablesorter1" id="sortable_table">';

	$headers = array($CI->lang->line('products_product_code') ,
		$CI->lang->line('products_description') ,
		$CI->lang->line('products_unit_pk_size'),
		$CI->lang->line('products_product_uos') ,
		$CI->lang->line('products_packs') ,
		$CI->lang->line('products_qty') ,
		$CI->lang->line('products_ordered')
	);

	$table .= '<thead><tr>';

	$nCount = 1;

	foreach($headers as $header)
	{
		$nCount1 = $nCount + 1;

		if($nCount == $sort_key)
			//$table .= "<th class='headerSortDown' onclick='sort_product(this , ".$nCount1.");'>".$header."</th>";
			$table .= "<th class='headerSortDown' onclick='sort_product(this);'>".$header."</th>";
		else if($nCount1 == $sort_key)
			//$table .= "<th class='headerSortUp' onclick='sort_product(this , ".$nCount.");'>".$header."</th>";
			$table .= "<th class='headerSortUp' onclick='sort_product(this);'>".$header."</th>";
		else if($nCount < 5)
			//$table .= "<th class='header' onclick='sort_product(this , ".$nCount.");'>".$header."</th>";
			$table .= "<th class='header' onclick='sort_product(this);'>".$header."</th>";
		else if($nCount == 13)
			$table .= "<th colspan='3'>".$header."</th>";
		else
			$table .= "<th>".$header."</th>";
		$nCount += 2;
	}
	$table .= '</tr></thead><tbody>';
	$table .= get_products_manage_table_data_rows($products , $controller);
	$table .= '</tbody></table>';
	return $table;
}

/*
Gets the html data rows for the items.
*/
function get_products_manage_table_data_rows($products , $controller)
{
	$CI =& get_instance();
	$table_data_rows = '';

	$nCount = 0;
	foreach($products->result() as $product)
	{
		$nCount ++;
		$table_data_rows .= get_product_data_row($product , $controller , $nCount);

	}

	if($products->num_rows() == 0)
	{
		$table_data_rows .= "<tr><td colspan='8'><div class='warning_message' style='padding:7px;'>".$CI->lang->line('products_no_products_to_display')."</div></tr></tr>";
	}

	return $table_data_rows;
}

function get_product_data_row($product , $controller , $nCount1)
{
	$CI =& get_instance();

	$controller_name = strtolower(get_class($CI));
	$width = $controller->get_form_width();

	$price = $product->prod_sell;
	$price1 = explode(".", $price);

	if(strlen($price1[1]) == 0)
		$price2 = "00";
	if(strlen($price1[1]) == 1)
		$price2 = $price1[1]."0";
	if(strlen($price1[1]) == 2)
		$price2 = $price1[1];

	$price = $price1[0].".".$price2;

	$cart_prod_quantity = $controller->get_cart_quantities($product->prod_id);

	if($nCount1 % 2 == 0)
	{
		$table_data_row = "<tr style='background-color:#FFFFFF;'>";
		//$table_data_row .= '<td width="15%" style="background-color:#EAEBFF;">'.$product->prod_code.'</td>';
	}
	else
	{
		$table_data_row = "<tr style='background-color:#E4E4FF;'>";
		//$table_data_row .= '<td width="15%" style="background-color:#D3D6FF;">'.$product->prod_code.'</td>';
	}
	$table_data_row .= '<td width="12%">'.$product->prod_code.'</td>';
	$table_data_row .= '<td width="33%">'.$product->prod_desc.'</td>';

	$table_data_row .= '<td width="15%" style="text-align:right;">'.$product->prod_pack_desc.'</td>';
	$table_data_row .= '<td width="8%" style="text-align:right;">'.$product->prod_uos.'</td>';
	$table_data_row .= '<td width="8%" style="text-align:right;">'.$price.'</td>';

	if($cart_prod_quantity == 0)
		$table_data_row .= '<td width="5%" class="price_per_pack_empty" id="prod_';
	else
		$table_data_row .= '<td width="5%" class="price_per_pack" id="prod_';

	$table_data_row .= $product->prod_id;
	$table_data_row .= '" onclick="edit_quantity('.$product->prod_id.');" style="cursor:pointer;"><span id="span_'.$product->prod_id.'">';

	if($cart_prod_quantity == 0)
		$table_data_row .= "0";
	else
		$table_data_row .= $cart_prod_quantity;

	$table_data_row .= '</span><input type="text" class="quantity_cell" id="input_';
	$table_data_row .= $product->prod_id;
	$table_data_row .= '" value="'.$cart_prod_quantity.'" style="display:none;" onkeyup="change_quantity('.$product->prod_id.' , event);"></td>';
	$table_data_row .= '<td width="5%" style="text-align:center;"><div class="numeric_type_order" onclick="set_qty(this , '.$product->prod_id.');">&nbsp;</div></td>';
	$table_data_row .= '<td width="5%" style="text-align:center;"><div class="inc_order" onclick="inc_quantity(1 , '.$product->prod_id.');">&nbsp;</div></td><td width="5%" style="text-align:center;"><div class="dec_order" onclick="inc_quantity(2 , '.$product->prod_id.');">&nbsp;</div></td>';

	$table_data_row.='</tr>';
	return $table_data_row;
}

/*
Gets the html table to manage giftcards.
*/
function get_giftcards_manage_table( $giftcards, $controller )
{
	$CI =& get_instance();

	$table='<table class="tablesorter" id="sortable_table">';

	$headers = array('<input type="checkbox" id="select_all" />',
	$CI->lang->line('giftcards_giftcard_number'),
	$CI->lang->line('giftcards_card_value'),
	'&nbsp',
	);

	$table.='<thead><tr>';
	foreach($headers as $header)
	{
		$table.="<th>$header</th>";
	}
	$table.='</tr></thead><tbody>';
	$table.=get_giftcards_manage_table_data_rows( $giftcards, $controller );
	$table.='</tbody></table>';
	return $table;
}

/*
Gets the html data rows for the giftcard.
*/
function get_giftcards_manage_table_data_rows( $giftcards, $controller )
{
	$CI =& get_instance();
	$table_data_rows='';

	foreach($giftcards->result() as $giftcard)
	{
		$table_data_rows.=get_giftcard_data_row( $giftcard, $controller );
	}

	if($giftcards->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='11'><div class='warning_message' style='padding:7px;'>".$CI->lang->line('giftcards_no_giftcards_to_display')."</div></tr></tr>";
	}

	return $table_data_rows;
}

function get_giftcard_data_row($giftcard,$controller)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));
	$width = $controller->get_form_width();

	$table_data_row='<tr>';
	$table_data_row.="<td width='3%'><input type='checkbox' id='giftcard_$giftcard->giftcard_id' value='".$giftcard->giftcard_id."'/></td>";
	$table_data_row.='<td width="15%">'.$giftcard->giftcard_number.'</td>';
	$table_data_row.='<td width="20%">'.to_currency($giftcard->value).'</td>';
	$table_data_row.='<td width="5%">'.anchor($controller_name."/view/$giftcard->giftcard_id/width:$width", $CI->lang->line('common_edit'),array('class'=>'thickbox','title'=>$CI->lang->line($controller_name.'_update'))).'</td>';

	$table_data_row.='</tr>';
	return $table_data_row;
}

/*
Gets the html table to manage item kits.
*/
function get_item_kits_manage_table( $item_kits, $controller )
{
	$CI =& get_instance();

	$table='<table class="tablesorter" id="sortable_table">';

	$headers = array('<input type="checkbox" id="select_all" />',
	$CI->lang->line('item_kits_name'),
	$CI->lang->line('item_kits_description'),
	'&nbsp',
	);

	$table.='<thead><tr>';
	foreach($headers as $header)
	{
		$table.="<th>$header</th>";
	}
	$table.='</tr></thead><tbody>';
	$table.=get_item_kits_manage_table_data_rows( $item_kits, $controller );
	$table.='</tbody></table>';
	return $table;
}

/*
Gets the html data rows for the item kits.
*/
function get_item_kits_manage_table_data_rows( $item_kits, $controller )
{
	$CI =& get_instance();
	$table_data_rows='';

	foreach($item_kits->result() as $item_kit)
	{
		$table_data_rows.=get_item_kit_data_row( $item_kit, $controller );
	}

	if($item_kits->num_rows()==0)
	{
		$table_data_rows.="<tr><td colspan='11'><div class='warning_message' style='padding:7px;'>".$CI->lang->line('item_kits_no_item_kits_to_display')."</div></tr></tr>";
	}

	return $table_data_rows;
}

function get_item_kit_data_row($item_kit,$controller)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));
	$width = $controller->get_form_width();

	$table_data_row='<tr>';
	$table_data_row.="<td width='3%'><input type='checkbox' id='item_kit_$item_kit->item_kit_id' value='".$item_kit->item_kit_id."'/></td>";
	$table_data_row.='<td width="15%">'.$item_kit->name.'</td>';
	$table_data_row.='<td width="20%">'.character_limiter($item_kit->description, 25).'</td>';
	$table_data_row.='<td width="5%">'.anchor($controller_name."/view/$item_kit->item_kit_id/width:$width", $CI->lang->line('common_edit'),array('class'=>'thickbox','title'=>$CI->lang->line($controller_name.'_update'))).'</td>';

	$table_data_row.='</tr>';
	return $table_data_row;
}

function get_cart_order_manage_table($cart_orders , $controller)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));

	$table = '<table class="tablesorter" id="sortable_table" style="border-top:3px solid #CCCCCC;">';

	$headers = array($CI->lang->line('orders_line_no') ,
			$CI->lang->line('products_product_code') ,
			$CI->lang->line('products_description') ,
			$CI->lang->line('products_unit_pk_size') ,
			$CI->lang->line('products_product_uos') ,
			$CI->lang->line('orders_per') ,
			$CI->lang->line('orders_qty') ,
			$CI->lang->line('orders_line_total') ,
			'Adjust'
	);

	$table.='<thead><tr>';
	foreach($headers as $header)
	{
		if($header == 'Adjust')
			$table .= "<th colspan='4' style='text-align:center;'>".$header."</th>";
		else
			$table .= "<th>".$header."</th>";
	}

	$table .= '</tr></thead><tbody>';
	$table .= get_cart_orders_manage_table_data_rows($cart_orders , $controller);
	$table .= '</tbody></table>';

	$total = $controller->get_total_amount_cart();
	$total1 = explode(".", $total);
	if(strlen($total1[1]) == 0) $total2 = "00";
	if(strlen($total1[1]) == 1) $total2 = $total1[1]."0";
	if(strlen($total1[1]) == 2) $total2 = $total1[1];
	$total = $total1[0].".".$total2;

	$table .= $total;
	return $table;
}

function get_cart_orders_manage_table_data_rows($cart_orders , $controller)
{
	$CI =& get_instance();
	$table_data_rows = '';

	$nCount = 0;
	foreach($cart_orders->result() as $cart_order)
	{
		$nCount ++;
		$table_data_rows .= get_cart_order_data_row($cart_order , $controller , $nCount);

	}

	if($cart_orders->num_rows() == 0)
	{
		$table_data_rows .= "<tr><td colspan='12'><div class='warning_message' style='padding:7px;'>".$CI->lang->line('orders_no_orders_to_display')."</div></tr></tr>";
	}

	return $table_data_rows;
}


function get_cart_order_data_row($cart_order , $controller , $nCount)
{
	$CI =& get_instance();

	$controller_name = strtolower(get_class($CI));

	$product = $controller->get_product($cart_order->prod_id);

	if($nCount % 2 == 0)
	{
		$table_data_row = "<tr style='background-color:#FFFFFF;'>";
		//$table_data_row .= '<td width="15%" style="background-color:#EAEBFF;">'.$product->prod_code.'</td>';
	}
	else
	{
		$table_data_row = "<tr style='background-color:#E4E4FF;'>";
		//$table_data_row .= '<td width="15%" style="background-color:#D3D6FF;">'.$product->prod_code.'</td>';
	}

	$total = $product->prod_sell * $cart_order->quantity;
	$total1 = explode(".", $total);
	if(strlen($total1[1]) == 0) $total2 = "00";
	if(strlen($total1[1]) == 1) $total2 = $total1[1]."0";
	if(strlen($total1[1]) == 2) $total2 = $total1[1];
	$total = $total1[0].".".$total2;

	$price = $product->prod_sell;
	$price1 = explode(".", $price);
	if(strlen($price1[1]) == 0) $price2 = "00";
	if(strlen($price1[1]) == 1) $price2 = $price1[1]."0";
	if(strlen($price1[1]) == 2) $price2 = $price1[1];
	$price = $price1[0].".".$price2;
/*
	if($cart_prod_quantity == 0)
		$table_data_row .= '<td width="5%" class="price_per_pack_empty" id="prod_';
	else
		$table_data_row .= '<td width="5%" class="price_per_pack" id="prod_';

	$table_data_row .= $product->prod_id;
	$table_data_row .= '" onclick="edit_quantity('.$product->prod_id.');" style="cursor:pointer;"><span id="span_'.$product->prod_id.'">';

	if($cart_prod_quantity == 0)
		$table_data_row .= "0";
	else
		$table_data_row .= $cart_prod_quantity;

	$table_data_row .= '</span><input type="text" class="quantity_cell" id="input_';
	$table_data_row .= $product->prod_id;
	$table_data_row .= '" value="'.$cart_prod_quantity.'" style="display:none;" onkeyup="change_quantity('.$product->prod_id.' , event);"></td>';
*/
	$table_data_row .= '<td width="5%">'.$nCount.'</td>';
	$table_data_row .= '<td width="10%">'.$product->prod_code.'</td>';
	$table_data_row .= '<td width="30%">'.$product->prod_desc.'</td>';
	$table_data_row .= '<td width="10%" style="text-align:right;">'.$product->prod_pack_desc.'</td>';
	$table_data_row .= '<td width="5%" style="text-align:right;">'.$product->prod_uos.'</td>';
	$table_data_row .= '<td width="7%" style="text-align:right;">'.$price.'</td>';
	$table_data_row .= '<td width="5%" style="text-align:right; cursor:pointer;" onclick="edit_quantity('.$product->prod_id.');"><span id="span_'.$product->prod_id.'">';
	$table_data_row .= $cart_order->quantity.'</span><input type="text" class="quantity_cell" id="input_';
	$table_data_row .= $product->prod_id;
	$table_data_row .= '" value="'.$cart_order->quantity.'" style="display:none;" onkeyup="change_quantity('.$product->prod_id.' , event);"></td>';
	$table_data_row .= '<td width="8%" style="text-align:right;" id="product_total">'.$total.'</td>';
	$table_data_row .= '<td width="5%" style="text-align:center;"><div class="numeric_type_order" onclick="set_qty(this , '.$product->prod_id.');">&nbsp;</div></td>';

//	$table_data_row .= '<td width="5%" style="text-align:center;"><div class="inc_order" onclick="inc_quantity(1 , '.$product->prod_id.');">&nbsp;</div></td>';
	$table_data_row .= '<td width="5%" style="text-align:center;"><div class="inc_order" onclick="inc_quantity(1 , '.$product->prod_id.');">&nbsp;</div></td>';
	$table_data_row .= '<td width="5%" style="text-align:center;"><div class="dec_order" onclick="inc_quantity(2 , '.$product->prod_id.');">&nbsp;</div></td>';
	$table_data_row .= '<td width="5%" style="text-align:center;"><div class="tiny_button" onmouseover="this.className=\'tiny_button_over\'" onmouseout="this.className=\'tiny_button\'" onclick="inc_quantity(3 ,'.$product->prod_id.');"><span>';
	$table_data_row .= $CI->lang->line('orders_remove');
	$table_data_row .= '</span></div></td>';

	$table_data_row.='</tr>';
	return $table_data_row;
}


function get_orders_manage_table($orders , $controller , $sort_key = 3 , $segment = 0)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));

	$table = '<table class="tablesorter" id="sortable_table">';

	$headers = array(
			$CI->lang->line('orders_line_no') ,
			$CI->lang->line('pastorders_date') ,
			$CI->lang->line('pastorders_time') ,
			$CI->lang->line('pastorders_total_amount') ,
			$CI->lang->line('pastorders_status') ,
			$CI->lang->line('pastorders_file_name') ,
			'&nbsp;'
	);

	$table.='<thead><tr>';
	$nCount = 1;

	foreach($headers as $header)
	{

		if(
		$header == $CI->lang->line('orders_line_no') ||
		$header == $CI->lang->line('pastorders_time') ||
		$header == $CI->lang->line('pastorders_total_amount') ||
		$header == $CI->lang->line('pastorders_file_name') ||
		$header == '&nbsp;'
		  )
		{
			$table .= "<th>".$header."</th>";
			continue;
		}


		$nCount1 = $nCount + 1;

		if($nCount == $sort_key)
			$table .= "<th class='headerSortDown' onclick='sort_product(this);'>".$header."</th>";
		else if($nCount1 == $sort_key)
			$table .= "<th class='headerSortUp' onclick='sort_product(this);'>".$header."</th>";
		else if($nCount < 5)
			$table .= "<th class='header' onclick='sort_product(this);'>".$header."</th>";

		$nCount += 2;
	}

	$table .= '</tr></thead><tbody>';
	if($segment == '') $segment = 0;
	$table .= get_orders_manage_table_data_rows($orders , $controller , $segment);
	$table .= '</tbody></table>';

	return $table;
}


function get_orders_manage_table_data_rows($orders , $controller , $segment)
{
	$CI =& get_instance();
	$table_data_rows = '';

	if($segment == '') $segment = 0;
	$nCount = $segment;
	foreach($orders->result() as $order)
	{
		$nCount ++;
		$table_data_rows .= get_order_data_row($order , $controller , $nCount);

	}

	if($orders->num_rows() == 0)
	{
		$table_data_rows .= "<tr><td colspan='7'><div class='warning_message' style='padding:7px;'>".$CI->lang->line('orders_no_orders_to_display')."</div></tr></tr>";
	}

	return $table_data_rows;
}



function get_order_data_row($order , $controller , $nCount)
{
	$CI =& get_instance();

	$controller_name = strtolower(get_class($CI));

	$width = $controller->get_form_width();
	$user_info = $controller->get_person($order->person_id);
	$total = $controller->get_total_amount($order->order_id);
	$total1 = explode("." , $total);
	if(strlen($total1[1]) == 0)
		$total2 = "00";
	else if(strlen($total1[1]) == 1)
		$total2 = $total1[1]."0";
	else if(strlen($total1[1]) == 2)
		$total2 = $total1[1];
	$total = $total1[0].".".$total2;

	$day = substr($order->order_date , 0 , 2);
	$month = substr($order->order_date , 2 , 2);
	$year = substr($order->order_date , 4 , 4);
	$hour = substr($order->order_time , 0 , 2);
	$minute = substr($order->order_time , 2 , 2);
	$second = substr($order->order_time , 4 , 2);
	$order_date = $day."/".$month."/".$year;
	$order_time = $hour.":".$minute.":".$second;

	$file_name = $controller->Pastorder->get_order_file_name($order->person_id , $order->order_id);

	if($nCount % 2 == 0)
	{
		$table_data_row = "<tr style='background-color:#FFFFFF;'>";
		//$table_data_row .= '<td width="15%" style="background-color:#EAEBFF;">'.$product->prod_code.'</td>';
	}
	else
	{
		$table_data_row = "<tr style='background-color:#E4E4FF;'>";
		//$table_data_row .= '<td width="15%" style="background-color:#D3D6FF;">'.$product->prod_code.'</td>';
	}
	$table_data_row .= '<td width="10%">wo-'.$order->order_id.'</td>';
	$table_data_row .= '<td width="15%" style="text-align:center;">'.$order_date.'</td>';
	$table_data_row .= '<td width="10%" style="text-align:center;">'.$order_time.'</td>';
	$table_data_row .= '<td width="10%" style="text-align:right;">'.$total.'</td>';

	if($order->completed == 1)
	{
		$table_data_row .= '<td width="15%" style="text-align:center;">'.$CI->lang->line('pastorders_placed').'</td>';
		$table_data_row .= "<td width='20%' style='text-align:left;'>".$file_name."</td>";
	}
	else
	{
		$table_data_row .= '<td width="15%" style="text-align:center;">'.$CI->lang->line('pastorders_not_placed').'</td>';
		$table_data_row .= "<td width='20%' style='text-align:left;'>&nbsp;</td>";
	}

	$table_data_row .= '<td width="5%" style="text-align:center;">';

	$table_data_row .= anchor("$controller_name/view/$order->order_id/width:$width" ,
			"<div class='tiny_long_button' onmouseover='this.className=\"tiny_long_button_over\"' onmouseout='this.className=\"tiny_long_button\"'><span>".$CI->lang->line('pastorders_show_me')."</span></div>",
			array('class'=>'thickbox none','title'=> 'User:&nbsp;'.$user_info->username.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Order Date:&nbsp;'.$order_date.'&nbsp;'.$order_time.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Order No:&nbsp;#'.$order->order_id));

	$table_data_row .= '</td>';

	$table_data_row.='</tr>';
	return $table_data_row;
}



function get_orders_manage_table_admin($orders , $controller , $sort_key = 3 , $segment = 0)
{
	$CI =& get_instance();
	$controller_name=strtolower(get_class($CI));

	$table = '<table class="tablesorter" id="sortable_table">';

	$headers = array(
			$CI->lang->line('orders_line_no') ,
			$CI->lang->line('pastorders_username') ,
			$CI->lang->line('pastorders_date') ,
			$CI->lang->line('pastorders_time') ,
			$CI->lang->line('pastorders_total_amount') ,
			$CI->lang->line('pastorders_status') ,
			$CI->lang->line('pastorders_file_name') ,
			'&nbsp;'
	);

	$table.='<thead><tr>';
	$nCount = 1;

	foreach($headers as $header)
	{

		if(
			$header == $CI->lang->line('orders_line_no') ||
			$header == $CI->lang->line('pastorders_time') ||
			$header == $CI->lang->line('pastorders_total_amount') ||
			$header == $CI->lang->line('pastorders_file_name') ||
			$header == '&nbsp;'
		  )
		{
			$table .= "<th>".$header."</th>";
			continue;
		}


		$nCount1 = $nCount + 1;

		if($nCount == $sort_key)
			$table .= "<th class='headerSortDown' onclick='sort_product(this);'>".$header."</th>";
		else if($nCount1 == $sort_key)
			$table .= "<th class='headerSortUp' onclick='sort_product(this);'>".$header."</th>";
		else if($nCount < 7)
			$table .= "<th class='header' onclick='sort_product(this);'>".$header."</th>";

		$nCount += 2;
	}

	$table .= '</tr></thead><tbody>';
	if($segment == '') $segment = 0;
	$table .= get_orders_manage_table_data_rows_admin($orders , $controller , $segment);
	$table .= '</tbody></table>';

	return $table;
}

function get_orders_manage_table_data_rows_admin($orders , $controller , $segment)
{
	$CI =& get_instance();
	$table_data_rows = '';

	//$nCount = 0;
	if($segment == '') $segment = 0;
	$nCount = $segment;
	foreach($orders->result() as $order)
	{
		$nCount ++;
		$table_data_rows .= get_order_data_row_admin($order , $controller , $nCount);

	}

	if($orders->num_rows() == 0)
	{
		$table_data_rows .= "<tr><td colspan='8'><div class='warning_message' style='padding:7px;'>".$CI->lang->line('orders_no_orders_to_display')."</div></tr></tr>";
	}

	return $table_data_rows;
}

function get_order_data_row_admin($order , $controller , $nCount)
{
	$CI =& get_instance();

	$controller_name = strtolower(get_class($CI));

	$width = $controller->get_form_width();
	$user_info = $controller->get_person($order->person_id);
	$total = $controller->get_total_amount($order->order_id);
	$total1 = explode("." , $total);
	if(strlen($total1[1]) == 0)
		$total2 = "00";
	else if(strlen($total1[1]) == 1)
		$total2 = $total1[1]."0";
	else if(strlen($total1[1]) == 2)
		$total2 = $total1[1];
	$total = $total1[0].".".$total2;

	$day = substr($order->order_date , 0 , 2);
	$month = substr($order->order_date , 2 , 2);
	$year = substr($order->order_date , 4 , 4);
	$hour = substr($order->order_time , 0 , 2);
	$minute = substr($order->order_time , 2 , 2);
	$second = substr($order->order_time , 4 , 2);
	$order_date = $day."/".$month."/".$year;
	$order_time = $hour.":".$minute.":".$second;

	$file_name = $controller->Pastorder->get_order_file_name($order->person_id , $order->order_id);

	if($nCount % 2 == 0)
	{
		$table_data_row = "<tr style='background-color:#FFFFFF;'>";
		//$table_data_row .= '<td width="15%" style="background-color:#EAEBFF;">'.$product->prod_code.'</td>';
	}
	else
	{
		$table_data_row = "<tr style='background-color:#E4E4FF;'>";
		//$table_data_row .= '<td width="15%" style="background-color:#D3D6FF;">'.$product->prod_code.'</td>';
	}
	$table_data_row .= '<td width="10%">wo-'.$order->order_id.'</td>';
	$table_data_row .= '<td width="10%">'.$user_info->username.'</td>';
	$table_data_row .= '<td width="10%" style="text-align:center;">'.$order_date.'</td>';
	$table_data_row .= '<td width="10%" style="text-align:center;">'.$order_time.'</td>';
	$table_data_row .= '<td width="10%" style="text-align:right;">'.$total.'</td>';

	if($order->completed == 1)
	{
		$table_data_row .= '<td width="10%" style="text-align:center;">'.$CI->lang->line('pastorders_placed').'</td>';
		$table_data_row .= "<td width='20%' style='text-align:left;'>".$file_name."</td>";
	}
	else
	{
		$table_data_row .= '<td width="10%" style="text-align:center;">'.$CI->lang->line('pastorders_not_placed').'</td>';
		$table_data_row .= "<td width='20%' style='text-align:left;'>&nbsp;</td>";
	}

	$table_data_row .= '<td width="5%" style="text-align:center;">';

	$table_data_row .= "<div  onclick='popup_dialog(".$order->order_id." , ".$order->completed.")'class='tiny_long_button' style='float:center;' onmouseover='this.className=\"tiny_long_button_over\"' onmouseout='this.className=\"tiny_long_button\"'><span>".$CI->lang->line('pastorders_show_me')."</span></div>";


	$table_data_row .= '</td>';

	$table_data_row.='</tr>';
	return $table_data_row;
}
?>