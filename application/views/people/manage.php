<?php $this->load->view("partial/header_user"); ?>
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
			height: 450 ,
			width: 680 ,
			modal: true ,
			buttons:
			{
				"Go": function()
				{
					var bValid = true;
					var user_id = $('#person_id');
					var username = $('#username') , email = $( "#email" ) , password = $( "#password" ) , repeat_password = $('#repeat_password');
					var admin_privilege , price_list005 , price_list010 , price_list011 , price_list999;
					var msg = $('#error_message_box');
					bValid = bValid && check_empty_field(username, "<? echo $this->lang->line('employees_username'); ?>");
			        bValid = bValid && check_empty_field(email, "<? echo $this->lang->line('common_email'); ?>");
					if(user_id.val() == '0')
					{
			        	bValid = bValid && check_empty_field(password, "<? echo $this->lang->line('employees_password'); ?>");
			        	bValid = bValid && check_empty_field(repeat_password, "<? echo $this->lang->line('employees_repeat_password'); ?>");
					}

					if(password.val() != repeat_password.val())
					{
						msg.html("<li><? echo $this->lang->line('employees_password_must_match'); ?></li>");
						bValid = false;
					}


					if(bValid)
					{
						if($('#admin_privilege').prop('checked')) admin_privilege = '1';
						else admin_privilege = '';
						if($('#price_list005').prop('checked')) price_list005 = '1';
						else price_list005 = '';
						if($('#price_list010').prop('checked')) price_list010 = '1';
						else price_list010 = '';
						if($('#price_list011').prop('checked')) price_list011 = '1';
						else price_list011 = '';
						if($('#price_list999').prop('checked')) price_list999 = '1';
						else price_list999 = '';
					    $.ajax({
					        type : "POST"
					        , async : true
					        , url : "<?php echo site_url("$controller_name/save");?>"
					        , dataType : "json"
					        , timeout : 30000
					        , cache : false
					        , data : "person_id=" + user_id.val() +
					        			"&username=" + username.val() +
					        			"&email=" + email.val() +
					        			"&password=" + password.val() +
					        			"&admin_privilege=" + admin_privilege +
					        			"&price_list005=" + price_list005 +
					        			"&price_list010=" + price_list010 +
					        			"&price_list011=" + price_list011 +
					        			"&price_list999=" + price_list999
					        , error : function(request, status, error) {
						         //alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
						    }
					        , success : function(response, status, request) {
					        }
					    });
			        	$(this).dialog('close');
			        	post_person_form_submit();

					}


				}
			}

		}
	);
    enable_search1('<?php echo site_url("$controller_name/suggest")?>','<?php echo $this->lang->line("common_confirm_search")?>');
});

function popup_dialog(user_id)
{
	if(user_id == 0)
	{
		$('#username').val('');
		$('#email').val('');
		$('#password').val('');
		$('#repeat_password').val('');
		$('#admin_privilege').prop('checked' , false);
		$('#price_list005').prop('checked' , false);
		$('#price_list010').prop('checked' , false);
		$('#price_list011').prop('checked' , false);
		$('#price_list999').prop('checked' , false);
		$('#error_message_box').html('');
		$('#person_id').val('0');
	}
	else
	{
	    $.ajax({
	        type : "POST"
	        , async : true
	        , url : "<?php echo site_url("$controller_name/get_user_info");?>"
	        , dataType : "json"
	        , timeout : 30000
	        , cache : false
	        , data : "person_id=" + user_id
	        , error : function(request, status, error) {
		         alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
	        }
	        , success : function(response, status, request) {

	    		$('#username').val(response[0]);
	    		$('#email').val(response[1]);
	    		$('#label_password').attr('class' , 'wide');
	    		$('#label_repeat_password').attr('class' , 'wide');
	    		$('#repeat_password').val('');
	    		$('#password').val('');
	    		$('#admin_privilege').prop('checked' , response[2]);
	    		$('#price_list005').prop('checked' , Number(response[3]));
	    		$('#price_list010').prop('checked' , Number(response[4]));
	    		$('#price_list011').prop('checked' , Number(response[5]));
	    		$('#price_list999').prop('checked' , Number(response[6]));
	    		$('#error_message_box').html('');
	    		$('#person_id').val(user_id);
	        }
	    });
	}
	$('#dialog_form').dialog('open');
}

function check_empty_field(link , label)
{
	var msg = $('#error_message_box');
	if(link.val().length < 1)
	{
		msg.html("<li>The " + label + " is a required field.</li>");
		return false;
	}
	else return true;
}

function post_person_form_submit()
{
	var nCurrentSortKey = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search = $('#search').val();
	var per_page = $('#per_page').val();
	var uri_segment;
	var location_site = "<?php echo site_url("$controller_name/index");?>";
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

			if(nIndex > 1) return;
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
        , url : "<?php echo site_url("$controller_name/sort_user");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : "sort_key=" + nCurrentSortKey + "&search=" + search + "&search_mode=" + search_mode + "&per_page=" + per_page
        , error : function(request, status, error) {

         alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success : function(response, status, request) {
            var strArray = response.split('********************');
            $('#product_pagination_div_left_div').html(strArray[0]);
            $('#sortable_table tbody').html(strArray[1]);
    		tb_init('#sortable_table a.thickbox');
//    		update_sortable_table();

        }
    });

	return;
}


</script>

<div id="title_bar" style="text-align:center;">
	<div id="title" class="float_left" style="font-family:Arial;"><?php echo $this->lang->line('common_list_of').' '.$this->lang->line('module_'.$controller_name); ?></div>
	<div id="new_button" onclick="popup_dialog(0);">
		<div class='small_button' style='float: left;' onmouseover='this.className=\"small_button_over\"' onmouseout='this.className=\"small_button\"'>
			<span><?php echo $this->lang->line($controller_name.'_new'); ?></span>
		</div>
	</div>
</div>
<div id="product_pagination_div" style="height:30px; border-top:3px solid #CCCCCC;">
	<div id="product_pagination_div_left_div">
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
		<div class="btnseparator"></div>
		<div class="pGroup">
			<div class="pFirst pButton" onclick="first_page('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
			<div class="pPrev pButton" onclick="prev_page('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
		</div>

		<div class="btnseparator"></div>

		<div class="pGroup">
			<span class="pcontrol">
				Page&nbsp;
				<input type="text" name="page" id="curd_page" value="<?php echo $curd_page;?>" size="4" class="product_search_cell_page" onkeyup="set_direct_page(event , '<?php  echo site_url("$controller_name/index/");?>');">
				&nbsp;of&nbsp;
				<span id="last_page_number"><?php echo $total_page;?></span>
			</span>
		</div>

		<div class="btnseparator"></div>

		<div>
			<div class="pNext pButton" onclick="next_page('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
			<div class="pLast pButton" onclick="last_page('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
		</div>

		<div class="btnseparator"></div>
	</div>
	<div id="product_pagination_div_right_div">
			<img src='<?php echo base_url()?>images/spinner_small.gif' alt='spinner' id='spinner' />
			<?php echo form_open("$controller_name/search",array('id'=>'search_form')); ?>
				<input type="text" name ='search' id='search' class='cell1'/>
				<input type="hidden" name="sort_key" id="sort_key" value="<?php echo $sort_key;?>">
				<input type="hidden" name="search_mode" id="search_mode" value="<?php echo $search_mode;?>">
				<input type="hidden" name="uri_segment" id="uri_segment" value="<?php echo $uri_segment;?>">
			</form>

	</div>
</div>


<div id="table_holder">
<?php echo $manage_table; ?>
</div>
<div id="product_pagination_div1">
	<div id="product_pagination_div_left_div1">
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
		<div class="btnseparator"></div>
		<div class="pGroup">
			<div class="pFirst pButton" onclick="first_page('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
			<div class="pPrev pButton" onclick="prev_page('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
		</div>

		<div class="btnseparator"></div>

		<div class="pGroup">
			<span class="pcontrol">
				Page&nbsp;
				<input type="text" name="page" id="curd_page" value="<?php echo $curd_page;?>" size="4" class="product_search_cell_page" onkeyup="set_direct_page(event , '<?php  echo site_url("$controller_name/index/");?>');">
				&nbsp;of&nbsp;
				<span id="last_page_number"><?php echo $total_page;?></span>
			</span>
		</div>

		<div class="btnseparator"></div>

		<div>
			<div class="pNext pButton" onclick="next_page('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
			<div class="pLast pButton" onclick="last_page('<? echo site_url("$controller_name/index/");?>');"><span></span></div>
		</div>

		<div class="btnseparator"></div>
	</div>
	<div id="product_pagination_div_right_div1">&nbsp;</div>
</div>
<div id="dialog_form" title="Create/Edit User" style="font-family:Arial; font-size:12px;">
	<?php echo form_open('#' , array('id'=>'customer_form'));?>
	<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
	<ul id="error_message_box"></ul>
	<fieldset id="customer_login_info">
		<legend><?php echo $this->lang->line("customers_login_info"); ?></legend>
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('common_email').':', 'email' , array('class'=>'required')); ?>

			<div class='form_field'>
			<?php echo form_input(array('name' => 'email' , 'id' => 'email' , 'value' => '')); ?>
			</div>

		</div>
		<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('employees_username').':', 'username',array('class'=>'required')); ?>
			<div class='form_field'><?php echo form_input(array('name' => 'username' , 'id' => 'username' , 'value' => ''));?></div>
		</div>
		<?php $password_label_attributes = array('class'=>'required' , 'id' => 'label_password'); ?>

		<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('employees_password').':', 'password',$password_label_attributes); ?>
			<div class='form_field'><?php echo form_password(array('name'=>'password' , 'id'=>'password'));?></div>
		</div>
		<?php $repeat_password_label_attributes = array('class'=>'required' , 'id' => 'label_repeat_password'); ?>
		<div class="field_row clearfix"><?php echo form_label($this->lang->line('employees_repeat_password').':', 'repeat_password',$repeat_password_label_attributes); ?>
			<div class='form_field'><?php echo form_password(array('name'=>'repeat_password' , 'id'=>'repeat_password'));?></div>
		</div>
		<div class="field_row clearfix"><?php echo form_label($this->lang->line('employees_admin_privilege').':', 'admin_privilege',array('class' => 'wide')); ?>
			<div class='form_field'><?php echo form_checkbox(array('name'=>'admin_privilege' , 'id'=>'admin_privilege') , '2' , $person_info->account_category == '' ? FALSE : (boolean)$person_info->account_category);?></div>
		</div>

	</fieldset>

	<fieldset id="customer_permission_info">
		<legend><?php echo $this->lang->line("customers_price_list"); ?></legend>
		<p><?php echo $this->lang->line("customers_choise_desc"); ?></p>
		<ul id="permission_list">
			<li style="width:30px;"></li>
			<li>
				<?php echo form_checkbox(array('name'=>'price_list005' , 'id'=>'price_list005') , '005' , $person_info->price_list005 == '' ? FALSE : (boolean)$person_info->price_list005); ?>
				<span class="medium"><?php echo $this->lang->line('customers_price_list0');?></span>
			</li>
			<li>
				<?php echo form_checkbox(array('name'=>'price_list010' , 'id'=>'price_list010') , '010' , $person_info->price_list010 == '' ? FALSE : (boolean)$person_info->price_list010); ?>
				<span class="medium"><?php echo $this->lang->line('customers_price_list1');?></span>
			</li>
			<li>
				<?php echo form_checkbox(array('name'=>'price_list011' , 'id'=>'price_list011') , '011' , $person_info->price_list011 == '' ? FALSE : (boolean)$person_info->price_list011); ?>
				<span class="medium"><?php echo $this->lang->line('customers_price_list2');?></span>
			</li>
			<li>
				<?php echo form_checkbox(array('name'=>'price_list999' , 'id'=>'price_list999') , '999' , $person_info->price_list999 == '' ? FALSE : (boolean)$person_info->price_list999); ?>
				<span class="medium"><?php echo $this->lang->line('customers_price_list3');?></span>
			</li>
			<li>&nbsp;</li>
		</ul>
	</fieldset>
	<input type="hidden" id="person_id" value="">
	<?php echo form_close();?>
</div>
</div></div>