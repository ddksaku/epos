<?php $this->load->view("partial/header_product"); ?>
<style type="text/css">
	.ui-dialog {font-family:Arial; font-size:12px;}
</style>

<script type="text/javascript">
$(document).ready(function()
{
	$( "#dialog_form" ).dialog
	(
		{
			autoOpen:false ,
			height: 250 ,
			width: 480 ,
			modal: true ,
			buttons:
			{
				"Start": function()
				{
					$('#img_wait').css('visibility' , 'visible');
					$(this).dialog({buttons: {}});

				    $.ajax({
				        type : "POST"
				        , async : true
				        , url : "<?php echo site_url("$controller_name/reload_product/");?>"
				        , dataType : "text"
				        , timeout : 30000
				        , cache : false
				        , data : "person_id=0"
				        , error : function(request, status, error) {
					         alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
					    }
				        , success : function(response, status, request) {
					        alert(response);
				        }
				    });
		        	$(this).dialog('close');
		        	post_product_form_submit();

				}
			}

		}
	);
    enable_search0('<?php echo site_url("$controller_name/suggest0")?>','<?php echo site_url("$controller_name/suggest1")?>','<?php echo site_url("$controller_name/suggest2")?>','<?php echo $this->lang->line("common_confirm_search")?>');


});

function popup_dialog(user_id)
{
	$('#img_wait').css('visibility' , 'hidden');
	$('#dialog_form').dialog('open');
}

function post_product_form_submit()
{
		location.reload();
}

function select_per_page(url)
{
	var nCurrentSortKey = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search0 = $('#search0').val();
	var search1 = $('#search1').val();
	var search2 = $('#search2').val();

	var category_id = $('#category').val();
	var per_page = $('#per_page').val();

	var uri_segment;

	category_id = Number(category_id);
	var location_site = url;
	var page_num = $('#curd_page').val();

	location_site = location_site + "/" + search_mode + "/";
	uri_segment = (Number(page_num) - 1) * Number(per_page);


	if(search_mode == 'default')
	{
		location_site = location_site + nCurrentSortKey + "/" + category_id + "/" + uri_segment + "/" + per_page;
	}
	else if(search_mode == 'search')
	{
		if(search0 == '')
			search0 = "12345678901234567890";
		if(search1 == '')
			search1 = "12345678901234567890";
		if(search2 == '')
			search2 = "12345678901234567890";
		location_site = location_site + search0 + "/" + search1 + "/" + search2 + "/" + nCurrentSortKey + "/" + category_id + "/" + uri_segment + "/" + per_page;
	}


	location.replace(location_site);


}

function select_category(link , category_id)
{
	var nCurrentSortKey = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search0 = $('#search0').val();
	var search1 = $('#search1').val();
	var search2 = $('#search2').val();
	var per_page = $('#per_page').val();

	$('#category').val(category_id);

	var span_text = $(link).find('>div>span').text();

	$(link).parent().children().each(function(nIndex){
		var nm = $(this).find('>div>span').text();
		if(nm == '')
		{
			$(this).attr('class' , 'bg_list');
		}
		else
		{
			$(this).find('>div').css('color' , '#999999');
		}
		//alert(nm);
	});

	if(span_text == '')
		$(link).attr('class' , 'bg_list_selected');
	else
		$(link).find('>div').css('color' , '#FF0000');


    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo site_url("$controller_name/select_category");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : "sort_key=" + nCurrentSortKey + "&search0=" + search0 + "&search1=" + search1 + "&search2=" + search2 + "&search_mode=" + search_mode + "&category_id=" + category_id + "&per_page=" + per_page
        , error : function(request, status, error) {

         alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success : function(response, status, request) {


            var strArray = response.split('********************');
            //$('#search_mode').val($.trim(strArray[1]));

            $('#product_pagination_div').html(strArray[0]);

            $('#product_pagination_div1').html(strArray[0]);
            $('#sortable_table tbody').html(strArray[1]);

        }
    });

}


function sort_product(link)
{


	var nCurrentSortKey = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search0 = $('#search0').val();
	var search1 = $('#search1').val();
	var search2 = $('#search2').val();
	var category_id = $('#category').val();
	var per_page = $('#per_page').val();

	var nSortIndex = $(link).parent().children().index($(link));
	var nSortIndex1 = (nSortIndex + 1) * 2 - 1;
	var nSortIndex2 = (nSortIndex + 1) * 2;
	var classStr;

	$(link).parent().children().each(function(nIndex){
			if(nIndex > 1) return;
			if(nIndex == nSortIndex)
			{
				classStr = $(this).attr('class');
				if(classStr == 'header' || classStr == 'headerSortUp')
				{
					$(this).attr('class' , 'headerSortDown');
					nCurrentSortKey = nSortIndex1;
				}
				else if(classStr == 'headerSortDown')
				{
					$(this).attr('class' , 'headerSortUp');
					nCurrentSortKey = nSortIndex2;
				}
			}
			else
				$(this).attr('class' , 'header');



		});
	$('#sort_key').val(nCurrentSortKey);

    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo site_url("$controller_name/sort_product");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : "sort_key=" + nCurrentSortKey + "&search0=" + search0 + "&search1=" + search1 + "&search2=" + search2 + "&search_mode=" + search_mode + "&category_id=" + category_id + "&per_page=" + per_page
        , error : function(request, status, error) {

         alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success : function(response, status, request) {
            var strArray = response.split('********************');
            //$('#search_mode').val(strArray[0]);
            $('#product_pagination_div').html(strArray[0]);
            $('#product_pagination_div1').html(strArray[0]);
            $('#sortable_table tbody').html(strArray[1]);
        }
    });


	return;
}

function set_direct_page(e , url)
{
	var result;

	if(window.event)
	{
		 result = window.event.keyCode;
	}
	else if(e)
	{
		 result = e.which;
	}

	if(result == 13)
	{
		var page_num = $('#curd_page').val();

		var total_page = $('#last_page_number').text();
		if(isNaN(Number(page_num)))
		{
			alert("You must input the numeric.");
			$('#curd_page').val("");
			return;
		}



		if(Number(page_num) > Number(total_page))
		{
			alert("Page number is too big.");
			$('#curd_page').val("");
			return;
		}

		if(Number(page_num) < 1)
		{
			alert("Page Number must be a integer.");
			$('#curd_page').val("");
			return;
		}
		if(Math.round(Number(page_num)) < 1)
		{
			$('#curd_page').val("");
			return;
		}
		var sort_key = $('#sort_key').val();
		var search_mode = $('#search_mode').val();
		var search0 = $('#search0').val();
		var search1 = $('#search1').val();
		var search2 = $('#search2').val();
		var category_id = $('#category').val();
		var per_page = $('#per_page').val();
		var uri_segment;

		category_id = Number(category_id);
		var location_site = url;

		location_site = location_site + "/" + search_mode + "/";
		uri_segment = ( Math.round(Number(page_num)) - 1) * Number(per_page);


		if(search_mode == 'default')
		{
			location_site = location_site + sort_key + "/" + category_id + "/" + uri_segment + "/" + per_page;
		}
		else if(search_mode == 'search')
		{
			if(search0 == '')
				search0 = "12345678901234567890";
			if(search1 == '')
				search1 = "12345678901234567890";
			if(search2 == '')
				search2 = "12345678901234567890";
			location_site = location_site + search0 + "/" + search1 + "/" + search2 + "/" + sort_key + "/" + category_id + "/" + uri_segment + "/" + per_page;
		}


		location.replace(location_site);

	}
}

function inc_quantity(mode , prod_id)
{
	var prod_td = "#prod_" + prod_id;
	var post_data = "prod_id=" + prod_id + "&mode=" + mode + "&quantity=1";

    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo site_url("$controller_name/to_cart");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : post_data
        , error : function(request, status, error) {

         alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success : function(response, status, request) {


			if(response < 0)
				return;

			if(response == 0)
				$(prod_td).attr('class' , 'price_per_pack_empty');
			else
				$(prod_td).attr('class' , 'price_per_pack');
			$(prod_td).find('>span').text(response);

        }
    });
}

function go_search(e)
{
	var result;

	if(window.event)
	{
		 result = window.event.keyCode;
	}
	else if(e)
	{
		 result = e.which;
	}

	if(result == 13)
	{
		do_search0(true);
	}
}

function go_quantity(e)
{
	var result;

	if(window.event)
	{
		 result = window.event.keyCode;
	}
	else if(e)
	{
		 result = e.which;
	}

	if(result == 13)
	{
		set_qty_trolley();
	}


}

function edit_quantity(prod_id)
{
	var input_id = "#input_" + prod_id;
	var span_id = "#span_" + prod_id;
	var current_id = $('#current_id').val();
	var current_input , current_span , current_qty , post_data;
	if(isNaN(Number(current_id))) current_id = 0;
	if(current_id != 0)
	{
		current_input = "#input_" + current_id;
		current_span = "#span_" + current_id;
		current_qty = $(current_input).val();

		if($(current_input).css('display') != 'none')
		{
			$(current_input).css('display' , 'none');
			if(!isNaN(Number(current_qty)))
			{
				$(current_span).text(Math.round(Number(current_qty)));
				$(current_span).css('display' , '');
				if(Number(current_qty) != 0) $(current_span).parent().attr('class' , 'price_per_pack');
				else $(current_span).parent().attr('class' , 'price_per_pack_empty');

				post_data = "prod_id=" + current_id + "&mode=3" + "&quantity=" + Math.round(Number(current_qty));
			    $.ajax({
			        type : "POST"
			        , async : true
			        , url : "<?php echo site_url("$controller_name/to_cart");?>"
			        , dataType : "html"
			        , timeout : 30000
			        , cache : false
			        , data : post_data
			        , error : function(request, status, error) {

			         alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
			        }
			        , success : function(response, status, request) {
			        }
			    });
			}

		}
	}
	$(span_id).css('display' , 'none');
	$(input_id).css('display' , '');
	$(input_id).val($(span_id).text());
	$('#current_id').val(prod_id);
	$(input_id).focus();
}

function change_quantity(prod_id , e)
{
	var result , span_id;
	var input_id , current_qty , post_data;
	if(window.event) result = window.event.keyCode;
	else if(e) result = e.which;

	if(result == 13)
	{
		input_id = "#input_" + prod_id;
		span_id = "#span_" + prod_id;
		current_qty = $(input_id).val();
		if(isNaN(Number(current_qty)))
		{
			$(input_id).val('');
			return;
		}
		$('#current_id').val('0');
		$(span_id).text(Math.round(Number(current_qty)));
		$(span_id).css('display' , '');
		$(input_id).css('display' , 'none');
		if(Number(current_qty) != 0) $(span_id).parent().attr('class' , 'price_per_pack');
		else $(span_id).parent().attr('class' , 'price_per_pack_empty');
		post_data = "prod_id=" + prod_id + "&mode=3" + "&quantity=" + Math.round(Number(current_qty));
	    $.ajax({
	        type : "POST"
	        , async : true
	        , url : "<?php echo site_url("$controller_name/to_cart");?>"
	        , dataType : "html"
	        , timeout : 30000
	        , cache : false
	        , data : post_data
	        , error : function(request, status, error) {

	         alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
	        }
	        , success : function(response, status, request) {
	        }
	    });
	}
}

function set_qty(link , prod_id)
{
	$('#prod_id').val(prod_id);
	var x = $(link).position();
    if($('#how_many_qty_info').css('visibility') == 'visible')
        $('#how_many_qty_info').css('visibility' , 'hidden');
    else
        DisplayPad(x.left , x.top);

}
function DisplayPad(gx , gy)
{
    $('#how_many_qty_info').css('left' , gx - 430);
    $('#how_many_qty_info').css('top' , gy + 110);
    $('#how_many_qty_info').css('visibility' , 'visible');
    $('#how_many_qty').attr('value' , '');
    $('#how_many_qty').focus();
}


function set_qty_trolley()
{
	var qty = $('#how_many_qty').val();
	var prod_id = $('#prod_id').val();
	var num;
	var input_id , span_id;
	if(isNaN(Number(qty)))
	{
		$('#how_many_qty').val('');
		 return;
	}

	if(Math.round(Number(qty)) < 1)
	{
		$('#how_many_qty').val('');
		return;
	}

	num = Math.round(Number(qty));
	input_id = "#input_" + prod_id;
	span_id = "#span_" + prod_id;
	$(span_id).text(Math.round(Number(qty)));
	$(span_id).css('display' , '');
	$(input_id).css('display' , 'none');
	if(Number(qty) != 0) $(span_id).parent().attr('class' , 'price_per_pack');
	else $(span_id).parent().attr('class' , 'price_per_pack_empty');

    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo site_url("$controller_name/to_cart");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : "prod_id=" + prod_id + "&mode=3" + "&quantity=" + Math.round(Number(qty))
        , error : function(request, status, error) {

         alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success : function(response, status, request) {
        	$('#how_many_qty').val('');
        	$('#how_many_qty_info').css('visibility' , 'hidden');
        	$('#prod_id').val('');
        }
    });
}


</script>
<div id="content_categories_area">
 	<div style="margin-top:25px;">&nbsp;</div>
 	<div id="categories_border_round">
		<div class="infoBoxHeading_td">Categories</div>
		<?php echo $categories;?>
	</div>
</div>
<div id="content_main_area">
	<div id="title_bar1">
<?php
		if($user_info->username == "admin")
		{
?>
			<div id="new_button" onclick="popup_dialog();">
				<div class='small_button' style='float: left;' onmouseover='this.className=\"small_button_over\"' onmouseout='this.className=\"small_button\"'>
					<span><?php echo $this->lang->line('products_reload_products'); ?></span>
				</div>
			</div>
<?php
		}
?>
	</div>

	<div id="product_search_div">

		<img src='<?php echo base_url()?>images/spinner_small.gif' alt='spinner' id='spinner1' />
		<?php echo form_open("$controller_name/search",array('id'=>'search_form' , 'style'=>'font-family:Arial;')); ?>
			<?php echo form_label($this->lang->line('products_product_code').' '.':', 'product_code');?>
			<?php echo form_input(array('name'=>'search0' , 'id'=>'search0' , 'class'=>'product_search_cell' , 'style'=>'width:70px;' , 'value'=>$search0 , 'onkeyup'=>'go_search(event);'));?>
			<?php echo form_label('&nbsp;&nbsp;&nbsp;', 'product_code');?>
			<?php echo form_label($this->lang->line('products_barcode').' '.':', 'barcode');?>
			<?php echo form_input(array('name'=>'search1' , 'id'=>'search1' , 'size'=>'5' , 'class'=>'product_search_cell' , 'style'=>'width:130px;' , 'value'=>$search1 , 'onkeyup'=>'go_search(event);'));?>
			<?php echo form_label('&nbsp;&nbsp;&nbsp;', 'product_code');?>
			<?php echo form_label($this->lang->line('products_description').' '.':', 'product_description');?>
			<?php echo form_input(array('name'=>'search2' , 'id'=>'search2' , 'class'=>'product_search_cell' , 'style'=>'width:230px;' , 'value'=>$search2 , 'onkeyup'=>'go_search(event);'));?>
			<input type="hidden" name="sort_key" id="sort_key" value="<?php echo $sort_key;?>">
			<input type="hidden" name="search_mode" id="search_mode" value="<?php echo $search_mode;?>">
			<!-- <input type="hidden" name="per_page" id="per_page" value="<?php echo $per_page;?>"> -->
			<input type="hidden" name="uri_segment" id="uri_segment" value="<?php echo $uri_segment;?>">
			<input type="hidden" name="category" id="category" value="<?php echo $category_id;?>">
			<input type="hidden" name="current_id" id="current_id" value="0">
			<input type="button" style="-moz-border-radius : 4px; -webkit-border-radius : 4px; border-radius : 4px;" class="tiny_button" value="<?php echo $this->lang->line('common_search'); ?>" onmouseover="this.className='tiny_button_over'" onmouseout="this.className='tiny_button'" onclick="do_search0(true);" />
		</form>
	</div>
	<div id="product_pagination_div">
		<?php //echo $this->pagination->create_links();?>
		<div class="btnseparator"></div>
		<div class="pGroup"><span style="font-family:Arial;">Show&nbsp;&nbsp;</span>
			<select name='per_page' id='per_page' onchange="select_per_page('<? echo site_url("$controller_name/index/");?>');">
				<option value='10' <?php if($per_page == 10) echo "selected='true'";?>>10</option>
				<option value='25' <?php if($per_page == 25) echo "selected='true'";?>>25</option>
				<option value='30' <?php if($per_page == 30) echo "selected='true'";?>>30</option>
				<option value='40' <?php if($per_page == 40) echo "selected='true'";?>>40</option>
				<option value='50' <?php if($per_page == 50) echo "selected='true'";?>>50</option>
				<option value='75' <?php if($per_page == 75) echo "selected='true'";?>>75</option>
				<option value='100' <?php if($per_page == 100) echo "selected='true'";?>>100</option>
				<option value='150' <?php if($per_page == 150) echo "selected='true'";?>>150</option>
				<option value='200' <?php if($per_page == 200) echo "selected='true'";?>>200</option>
			</select><span style="font-family:Arial;">&nbsp;Rows&nbsp;Per&nbsp;Page</span>
		</div>

		<div class="btnseparator" style="float:right;"></div>
		<div class="pGroup" style="float:right;">
			<div class="pNext pButton" onclick="pNext('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
			<div class="pLast pButton" onclick="pLast('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
		</div>
		<div class="btnseparator" style="float:right;"></div>
		<div class="pGroup" style="float:right;">
			<span class="pcontrol">
				Page&nbsp;
				<input type="text" name="page" id="curd_page" value="<?php echo $curd_page;?>" size="4" class="product_search_cell_page" onkeyup="set_direct_page(event , '<?php  echo site_url("$controller_name/index/");?>');">
				&nbsp;of&nbsp;
				<span id="last_page_number"><?php echo $total_page;?></span>
			</span>
		</div>
		<div class="btnseparator" style="float:right;"></div>
		<div class="pGroup" style="float:right;">
			<div class="pFirst pButton" onclick="pFirst('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
			<div class="pPrev pButton"  onclick="pPrev('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
		</div>
		<div class="btnseparator" style="float:right;"></div>
	</div>

	<div id="table_holder">
	<?php echo $manage_table; ?>
	</div>
	<div id="product_pagination_div1">
		<div class="btnseparator"></div>
		<div class="pGroup"><span style="font-family:Arial;">Show&nbsp;&nbsp;</span>
			<select name='per_page' id='per_page' onchange="select_per_page('<? echo site_url("$controller_name/index/");?>');">
				<option value='10' <?php if($per_page == 10) echo "selected='true'";?>>10</option>
				<option value='25' <?php if($per_page == 25) echo "selected='true'";?>>25</option>
				<option value='30' <?php if($per_page == 30) echo "selected='true'";?>>30</option>
				<option value='40' <?php if($per_page == 40) echo "selected='true'";?>>40</option>
				<option value='50' <?php if($per_page == 50) echo "selected='true'";?>>50</option>
				<option value='75' <?php if($per_page == 75) echo "selected='true'";?>>75</option>
				<option value='100' <?php if($per_page == 100) echo "selected='true'";?>>100</option>
				<option value='150' <?php if($per_page == 150) echo "selected='true'";?>>150</option>
				<option value='200' <?php if($per_page == 200) echo "selected='true'";?>>200</option>
			</select><span style="font-family:Arial;">&nbsp;Rows&nbsp;Per&nbsp;Page</span>
		</div>

		<div class="btnseparator" style="float:right;"></div>
		<div class="pGroup" style="float:right;">
			<div class="pNext pButton" onclick="pNext('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
			<div class="pLast pButton" onclick="pLast('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
		</div>
		<div class="btnseparator" style="float:right;"></div>
		<div class="pGroup" style="float:right;">
			<span class="pcontrol">
				Page&nbsp;
				<input type="text" name="page" id="curd_page" value="<?php echo $curd_page;?>" size="4" class="product_search_cell_page" onkeyup="set_direct_page(event , '<?php  echo site_url("$controller_name/index/");?>');">
				&nbsp;of&nbsp;
				<span id="last_page_number"><?php echo $total_page;?></span>
			</span>
		</div>
		<div class="btnseparator" style="float:right;"></div>
		<div class="pGroup" style="float:right;">
			<div class="pFirst pButton" onclick="pFirst('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
			<div class="pPrev pButton"  onclick="pPrev('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
		</div>
		<div class="btnseparator" style="float:right;"></div>

	</div>
	<div id="feedback_bar"></div>
	<fieldset id="how_many_qty_info" style='background-color:#FFFFFF; position: absolute; width:400px; height:40px;padding-top:10px; padding-right:10px; text-align:right; visibility:hidden;'>
		<?php echo form_label($this->lang->line('pastorders_how_many').':', 'how_many',array('class'=>'required')); ?>
		<?php echo form_input(array('name'=>'how_many_qty' , 'id'=>'how_many_qty' , 'class'=>'product_search_cell' , 'style'=>'width:120px; height:18px;' , 'onkeyup'=>'go_quantity(event);'));?>
		&nbsp;&nbsp;&nbsp;<div class='tiny_button' style='float: right;' onmouseover="this.className='tiny_button_over'" onmouseout="this.className='tiny_button'" onclick="$('#how_many_qty_info').css('visibility' , 'hidden');"><span>Cancel</span></div>
	&nbsp;&nbsp;&nbsp;<div class='tiny_button' style='float: right; margin-right:20px;' onmouseover="this.className='tiny_button_over'" onmouseout="this.className='tiny_button'" onclick="set_qty_trolley();"><span>OK</span></div>
		<input type='hidden' name='prod_id' id='prod_id' value='0'>
	</fieldset>
	<div id="dialog_form" title="Reload Products" style="font-family:Arial; font-size:12px;">
		<?php echo form_open('#' , array('id'=>'reload_form'));?>
			<fieldset id="ftp_location_info">
				<div class="field_row clearfix">
					<div id="please_wait">
						<img src="<?php echo base_url("/images/spinner_load.gif");?>" style="width:100px; height:100px; visibility:hidden;" id="img_wait">
					</div>
				</div>
			</fieldset>
		<?php echo form_close(); ?>
	</div>
</div>
</div>
</div>

<?php //$this->load->view("partial/footer_product"); ?>