<?php $this->load->view("partial/header"); ?>
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
			height: 540 ,
			width: 750 ,
			modal: true ,
			open:function()
			{
				var order_id = $('#order_id').val();
			    $.ajax({
			        type : "POST"
			        , async : true
			        , url : "<?php echo site_url("$controller_name/get_order");?>"
			        , dataType : "json"
			        , timeout : 30000
			        , cache : false
			        , data : "order_id=" + order_id
			        , error : function(request, status, error) {
						alert("Order data is not readable.");
						$(this).dialog("close");
			        }
			        , success : function(response, status, request) {
			        	var contents = "<legend><?php echo $this->lang->line("pastorders_product_info"); ?></legend>";
			        	contents = contents + response.manage_table;
			        	$('#order_info').html(contents);
			        	if(response.completed == 0)
			        	{
//							var buttons = $(this).dialog('option' , 'buttons');
//							$.extend(buttons, { 'foo': function () { alert('foo'); } });
//							$(this).dialog("option", "buttons", buttons);
			        	}
			        }
			    });
			} ,
			buttons:{}

		}
	);
});

function popup_dialog(order_id , completed)
{

	$('#order_id').val(order_id);
	var buttons = $( "#dialog_form" ).dialog('option' , 'buttons');
	if(completed == 0)
	{
		$.extend(buttons ,
			{
				'<?php echo $this->lang->line('pastorders_continue_this_order');?>': function ()
				{
					continue_order(order_id , 1);
				},
				'<?php echo $this->lang->line('pastorders_go_back');?>': function()
				{
					go_back();
				}
			}
		);

	}
	else
	{
		$.extend(buttons ,
			{
				'<?php echo $this->lang->line('pastorders_go_my_trolley');?>': function ()
				{
					continue_order(0 , 2);
				},
				'<?php echo $this->lang->line('pastorders_go_back');?>': function()
				{
					go_back();
				}
			}
		);

	}
	$( "#dialog_form" ).dialog("option", "buttons", buttons);
	$( "#dialog_form" ).dialog("open");
}


function continue_order(order_id , mode)
{
	var vis = $('#how_many_qty_info').css('visibility');
	if(vis == "visible")
	{
		$('#how_many_qty').focus();
		return;
	}
	location.replace("<?php echo site_url("$controller_name/continue_order")?>" + "/" + mode + "/" + order_id);

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


function go_back()
{
	var vis = $('#how_many_qty_info').css('visibility');
	if(vis == "visible")
	{
		$('#how_many_qty').focus();
		return;
	}

	$('#prod_id').val('');
	$('#dialog_form').dialog('close');
}

function DisplayPad(gx , gy)
{
    $('#how_many_qty_info').css('left' , gx - 430);
    $('#how_many_qty_info').css('top' , gy - 20);
    $('#how_many_qty_info').css('visibility' , 'visible');
    $('#how_many_qty').attr('value' , '');
    $('#how_many_qty').focus();
}


function set_qty_trolley()
{
	var order_id = $('#order_id').val();

	var qty = $('#how_many_qty').val();
	var prod_id = $('#prod_id').val();
	var num;
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

    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo site_url("$controller_name/add_to_cart");?>"
        , dataType : "json"
        , timeout : 30000
        , cache : false
        , data : "prod_id=" + prod_id + "&quantity=" + qty + "&order_id=" + order_id
        , error : function(request, status, error) {

         alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success : function(response, status, request) {
            if(response == -1 || response == -3 || response == -4) alert("Order product is empty.");
        	else if(response == -2) alert("There is not this product.");

            $('#how_many_qty').val('');
        	$('#how_many_qty_info').css('visibility' , 'hidden');
        	$('#prod_id').val('');
        }
    });
}

function select_per_page(url)
{

	var nCurrentSortKey = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search = $('#search').val();
	var per_page = $('#per_page').val();
	var uri_segment;
	var location_site = url;
	var page_num = $('#curd_page').val();

	location_site = location_site + "/" + search_mode + "/";
	uri_segment = (Number(page_num) - 1) * Number(per_page);
	if(search_mode == 'default')
		location_site = location_site + nCurrentSortKey + "/" + per_page + "/" + uri_segment;
	else if(search_mode == 'search')
	{
		if(search == '') search = "12345678901234567890";
		location_site = location_site + search + "/" + nCurrentSortKey + "/" + per_page + "/" + uri_segment;
	}
	location.replace(location_site);
}

function first_page(url)
{
	var sort_key = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search = $('#search').val();
	var per_page = $('#per_page').val();
	var uri_segment;
	var curd_page = $('#curd_page').val();

	if(curd_page == '1') return;
	else curd_page = 1;
	var location_site = url;

	location_site = location_site + "/" + search_mode + "/";
	uri_segment = (Number(curd_page) - 1) * Number(per_page);
	if(search_mode == 'default')
		location_site = location_site + sort_key + "/" + per_page + "/" + uri_segment;
	else if(search_mode == 'search')
	{
		if(search == '') search = "12345678901234567890";
		location_site = location_site + search + "/" + sort_key + "/" + per_page + "/" + uri_segment;
	}
	location.replace(location_site);
}

function prev_page(url)
{
	var sort_key = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search = $('#search').val();
	var per_page = $('#per_page').val();
	var uri_segment;
	var curd_page = $('#curd_page').val();
	var location_site = url;

	if(curd_page == '1') return;
	location_site = location_site + "/" + search_mode + "/";
	uri_segment = (Number(curd_page) - 2) * Number(per_page);

	if(search_mode == 'default')
		location_site = location_site + sort_key + "/" + per_page + "/" + uri_segment;
	else if(search_mode == 'search')
	{
		if(search == '') search = "12345678901234567890";
		location_site = location_site + search + "/" + sort_key + "/" + per_page + "/" + uri_segment;
	}
	location.replace(location_site);
}

function next_page(url)
{
	var sort_key = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search = $('#search').val();
	var per_page = $('#per_page').val();
	var uri_segment;
	var curd_page = $('#curd_page').val();
	var total_page = $('#last_page_number').text();
	var location_site = url;

	if(curd_page == total_page) return;
	location_site = location_site + "/" + search_mode + "/";
	uri_segment = Number(curd_page) * Number(per_page);
	if(search_mode == 'default')
		location_site = location_site + sort_key + "/" + per_page + "/" + uri_segment;
	else if(search_mode == 'search')
	{
		if(search == '') search = "12345678901234567890";
		location_site = location_site + search + "/" + sort_key + "/" + per_page + "/" + uri_segment;
	}
	location.replace(location_site);
}

function last_page(url)
{
	var sort_key = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search = $('#search').val();
	var per_page = $('#per_page').val();
	var uri_segment;
	var curd_page = $('#curd_page').val();
	var total_page = $('#last_page_number').text();
	var location_site = url;

	if(curd_page == total_page) return;
	else curd_page = total_page;
	location_site = location_site + "/" + search_mode + "/";
	uri_segment = (Number(curd_page) - 1) * Number(per_page);

	if(search_mode == 'default')
		location_site = location_site + sort_key + "/" + per_page + "/" + uri_segment;
	else if(search_mode == 'search')
	{
		if(search == '') search = "12345678901234567890";
		location_site = location_site + search + "/" + sort_key + "/" + per_page + "/" + uri_segment;
	}

	location.replace(location_site);
}

<?php
if($user_info->username == "admin")
{
?>
function sort_product(link)
{
	var nCurrentSortKey = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search = $('#search').val();
	var per_page = $('#per_page').val();
	var nSortIndex = $(link).parent().children().index($(link));
	var nSortIndex1 = -1;
	var nSortIndex2;
	var classStr;

	$(link).parent().children().each(function(nIndex){

			if(nIndex > 5) return;
			if(nIndex == 0 || nIndex == 3 || nIndex == 4) return;
			if($(this).attr('class') != "")
			{
				nSortIndex1 += 2;
				nSortIndex2 = nSortIndex1 + 1;
			}
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
			{
				if($(this).attr('class') != "")
					$(this).attr('class' , 'header');
			}
		});
	$('#sort_key').val(nCurrentSortKey);



    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo site_url("$controller_name/sort_order");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : "sort_key=" + nCurrentSortKey + "&search=" + search + "&search_mode=" + search_mode + "&per_page=" + per_page
        , error : function(request, status, error) {

         alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success : function(response, status, request) {
            var strArray = response.split('********************');
            $('#product_pagination_div').html(strArray[0]);
            $('#product_pagination_div1').html(strArray[0]);
            $('#sortable_table tbody').html(strArray[1]);
    		tb_init('#sortable_table a.thickbox');
//    		update_sortable_table();

        }
    });

	return;
}
<?php
}
else
{
?>
function sort_product(link)
{
	var nCurrentSortKey = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search = $('#search').val();
	var per_page = $('#per_page').val();
	var nSortIndex = $(link).parent().children().index($(link));
	var nSortIndex1 = -1;
	var nSortIndex2;
	var classStr;

	$(link).parent().children().each(function(nIndex){

			if(nIndex > 5) return;
			if($(this).attr('class') != "")
			{
				nSortIndex1 += 2;
				nSortIndex2 = nSortIndex1 + 1;
			}
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
			{
				if($(this).attr('class') != "")
					$(this).attr('class' , 'header');
			}
		});
	$('#sort_key').val(nCurrentSortKey);
//alert(nCurrentSortKey);


    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo site_url("$controller_name/sort_order");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : "sort_key=" + nCurrentSortKey + "&search=" + search + "&search_mode=" + search_mode + "&per_page=" + per_page
        , error : function(request, status, error) {

         alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success : function(response, status, request) {
            var strArray = response.split('********************');
            $('#product_pagination_div').html(strArray[0]);
            $('#product_pagination_div1').html(strArray[0]);
            $('#sortable_table tbody').html(strArray[1]);
    		tb_init('#sortable_table a.thickbox');
//    		update_sortable_table();

        }
    });

	return;
}
<?php
}
?>
function set_direct_page(e , url)
{
	var result;
	if(window.event) result = window.event.keyCode;
	else if(e) result = e.which;
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
			$('#curd_page').val("1");
			return;
		}
		var sort_key = $('#sort_key').val();
		var search_mode = $('#search_mode').val();
		var search = $('#search').val();
		var per_page = $('#per_page').val();
		var uri_segment;
		category_id = Number(category_id);
		var location_site = url;
		location_site = location_site + "/" + search_mode + "/";
		uri_segment = ( Math.round(Number(page_num)) - 1) * Number(per_page);
		if(search_mode == 'default') location_site = location_site + sort_key + "/" + per_page + "/" + uri_segment;
		else if(search_mode == 'search')
		{
			if(search == '') search = "12345678901234567890";
			location_site = location_site + search + "/" + sort_key + "/" + per_page + "/" + uri_segment;
		}
		location.replace(location_site);
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
</script>

<div id="title_bar">&nbsp;</div>
<input type="hidden" name="sort_key" id="sort_key" value="<?php echo $sort_key;?>">
<input type="hidden" name="search_mode" id="search_mode" value="<?php echo $search_mode;?>">
<input type="hidden" name="uri_segment" id="uri_segment" value="<?php echo $uri_segment;?>">
<input type="hidden" name="order_id" id="order_id" value="">
<div id="product_pagination_div" style="border-top:3px solid #CCCCCC;">
		<div class="btnseparator"></div>
		<div class="pGroup"><span style='font-family:Arial;'>Show&nbsp;</span>
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
			</select><span style='font-family:Arial;'>&nbsp;Rows&nbsp;Per&nbsp;Page</span>
		</div>
		<div class="btnseparator" style="fload:right;"></div>
		<div class="pGroup" style="float:right;">
			<div class="pNext pButton" onclick="next_page('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
			<div class="pLast pButton" onclick="last_page('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
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
			<div class="pFirst pButton" onclick="first_page('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
			<div class="pPrev pButton" onclick="prev_page('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
		</div>

		<div class="btnseparator" style="float:right;"></div>
</div>

<div id="table_holder">
<?php echo $manage_table; ?>
</div>
<div id="product_pagination_div1">
		<div class="btnseparator"></div>
		<div class="pGroup"><span style='font-family:Arial;'>Show&nbsp;</span>
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
			</select><span style='font-family:Arial;'>&nbsp;Rows&nbsp;Per&nbsp;Page</span>
		</div>
		<div class="btnseparator" style="fload:right;"></div>
		<div class="pGroup" style="float:right;">
			<div class="pNext pButton" onclick="next_page('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
			<div class="pLast pButton" onclick="last_page('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
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
			<div class="pFirst pButton" onclick="first_page('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
			<div class="pPrev pButton" onclick="prev_page('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
		</div>

		<div class="btnseparator" style="float:right;"></div>


</div>
<div id="feedback_bar"></div>

<div id="dialog_form" title="Show Order" style="font-family:Arial; font-size:12px;">
	<fieldset id="order_info">
	</fieldset>
	<br><br>
	<fieldset id="how_many_qty_info" style='background-color:#FFFFFF; position: absolute; width:400px; height:40px;padding-top:10px; padding-right:10px; text-align:right; visibility:hidden;'>
		<?php echo form_label($this->lang->line('pastorders_how_many').':', 'how_many',array('class'=>'required')); ?>
		<?php echo form_input(array('name'=>'how_many_qty' , 'id'=>'how_many_qty' , 'class'=>'product_search_cell' , 'style'=>'width:120px; height:18px;' , 'onkeyup'=>'go_quantity(event);'));?>
		&nbsp;&nbsp;&nbsp;<div class='tiny_button' style='float: right;' onmouseover="this.className='tiny_button_over'" onmouseout="this.className='tiny_button'" onclick="$('#how_many_qty_info').css('visibility' , 'hidden');"><span>Cancel</span></div>
	&nbsp;&nbsp;&nbsp;<div class='tiny_button' style='float: right; margin-right:20px;' onmouseover="this.className='tiny_button_over'" onmouseout="this.className='tiny_button'" onclick="set_qty_trolley();"><span>OK</span></div>
	</fieldset>
	<input type='hidden' name='prod_id' id='prod_id' value='0'>
</div>

</div></div>