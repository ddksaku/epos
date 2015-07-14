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
			height: 250 ,
			width: 480 ,
			modal: true ,
			buttons:
			{
				"Go": function()
				{
					if($('#file_path').val() == '')
					{
						alert('You must select the CSV file.');
						return;
					}
					$(this).dialog('close');
					$('#product_form').submit();
				}
			}

		}
	);

});


function popup_dialog(user_id)
{
	$('#dialog_form').dialog('open');
}

function inc_quantity(mode , prod_id)
{
	var post_data = "prod_id=" + prod_id + "&mode=" + mode + "&quantity=1";

    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo site_url("$controller_name/to_cart_quantity");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : post_data
        , error : function(request, status, error) {

         alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success : function(response, status, request) {
			var strArray = response.split('********************');
			$('#table_holder').html(strArray[0]);
			$('#total_quantity').text(strArray[1]);
			$('#total_amount').text(strArray[2]);
        }
    });
}


function edit_quantity(prod_id)
{
	var input_id = "#input_" + prod_id;
	var span_id = "#span_" + prod_id;
	var current_id = $('#current_id').val();
	var current_input = "#input_" + current_id;
	var current_span = "#span_" + current_id;
	if($(current_input).css('display') != 'none')
	{
		$(current_input).css('display' , 'none');
		$(current_span).css('display' , '');
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

		post_data = "prod_id=" + prod_id + "&mode=4" + "&quantity=" + Math.round(Number(current_qty));
	    $.ajax({
	        type : "POST"
	        , async : true
	        , url : "<?php echo site_url("$controller_name/to_cart_quantity");?>"
	        , dataType : "html"
	        , timeout : 30000
	        , cache : false
	        , data : post_data
	        , error : function(request, status, error) {

	         alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
	        }
	        , success : function(response, status, request) {
				var strArray = response.split('********************');
				$('#table_holder').html(strArray[0]);
				$('#total_quantity').text(strArray[1]);
				$('#total_amount').text(strArray[2]);
	        }
	    });
	}
}

function save_for_later()
{
	var post_data = "order_action=1";
    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo site_url("$controller_name/save_for_later");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : post_data
        , error : function(request, status, error) {

         alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success : function(response, status, request) {
			if(response == true)
				alert("Order save success.");
			else if(response == 100)
				alert('You must added products to cart.');
			else
				alert("Save fail.");

			location.replace("<?php echo site_url("$controller_name/index");?>");
        }
    });
}

function send_order()
{
	$('#spinner2').show();

	var post_data = "order_action=2";
    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo site_url("$controller_name/send_order");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : post_data
        , error : function(request, status, error) {
        	$('#spinner2').hide();
         	alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success : function(response, status, request) {
				if(response == 100)
				{
					alert("You must added products to cart.");
					return;
				}
				else if(response == -1)
				{
					alert("Send fail.");
					return;
				}

				$('#spinner2').hide();
				alert(response);
        }
    });


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
    $('#how_many_qty_info').css('top' , gy + 50);
    $('#how_many_qty_info').css('visibility' , 'visible');
    $('#how_many_qty').attr('value' , '');
    $('#how_many_qty').focus();
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
//	if(Number(qty) != 0) $(span_id).parent().attr('class' , 'price_per_pack');
//	else $(span_id).parent().attr('class' , 'price_per_pack_empty');

    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo site_url("$controller_name/to_cart_quantity");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : "prod_id=" + prod_id + "&mode=4" + "&quantity=" + Math.round(Number(qty))
        , error : function(request, status, error) {

         alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success : function(response, status, request) {
			var strArray = response.split('********************');
			$('#table_holder').html(strArray[0]);
			$('#total_quantity').text(strArray[1]);
			$('#total_amount').text(strArray[2]);
        	$('#how_many_qty').val('');
        	$('#how_many_qty_info').css('visibility' , 'hidden');
        	$('#prod_id').val('');
        }
    });
}

</script>
<div id="title_bar">
	<div id="new_button" onclick="popup_dialog();">
		<div class='small_button' style='float: left;' onmouseover='this.className=\"small_button_over\"' onmouseout='this.className=\"small_button\"'>
			<span><?php echo $this->lang->line($controller_name.'_import'); ?></span>
		</div>
	</div>
</div>
<input type="hidden" name="current_id" id="current_id" value="0">
<div id="table_holder">
<?php echo $manage_table; ?>
</div>
<div id="order_total_div" style="border-left:3px solid #CCCCCC; border-right:3px solid #CCCCCC; border-bottom:0px;">
	<table style="width:100%;">
		<tr>
			<td style="width:5%;">&nbsp;</td>
			<td style="width:10%;">&nbsp;</td>
			<td style="width:30%;">&nbsp;</td>
			<td style="width:5%;">&nbsp;</td>
			<td style="width:10%;">&nbsp;</td>
			<td style="width:7%;">Total</td>
			<td style="width:5%; text-align:right;" id="total_quantity"><?php echo $total_quantity;?></td>
			<td style="width:8%; text-align:right;" id="total_amount"><?php echo $total_amount;?></td>
			<td style="width:5%;">&nbsp;</td>
			<td style="width:5%;">&nbsp;</td>
			<td style="width:5%;">&nbsp;</td>
			<td style="width:5%;">&nbsp;</td>
		</tr>
	</table>
</div>
<div id="order_action_div" style="border-left:3px solid #CCCCCC; border-right:3px solid #CCCCCC; border-bottom:3px solid #CCCCCC;">
	<img src='<?php echo base_url()?>images/spinner_small.gif' alt='spinner' id='spinner2' />
	<div class='tiny_long_long_button' style="float: right;" onmouseover="this.className='tiny_long_long_button_over'" onmouseout="this.className='tiny_long_long_button'" onclick="location.replace('<?php echo site_url("$controller_name/add_another_item");?>')">
		<span>
			<?php echo $this->lang->line($controller_name.'_add_another_item');?>
		</span>
	</div>

	<div class="tiny_long_button" style="float:right; visibility:hidden"></div>

	<div class='tiny_long_button' style="float: right;" onmouseover="this.className='tiny_long_button_over'" onmouseout="this.className='tiny_long_button'" onclick="save_for_later();">
		<span>
			<?php echo $this->lang->line($controller_name.'_save_for_later');?>
		</span>
	</div>

	<div class="tiny_long_button" style="float:right; visibility:hidden"></div>

	<div class='tiny_long_button' style="float: right;" onmouseover="this.className='tiny_long_button_over'" onmouseout="this.className='tiny_long_button'" onclick="send_order();">
		<span>
			<?php echo $this->lang->line($controller_name.'_send_order');?>
		</span>
	</div>

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
	<?php echo form_open_multipart(site_url('orders/do_excel_import/'),array('id'=>'product_form')); ?>

		<ul id="error_message_box"></ul>
		<fieldset id="product_basic_info">
			<legend>Import</legend>
			<div class="field_row clearfix">

				<div class='form_field'>
				<?php echo form_upload(array(
					'name'=>'file_path',
					'id'=>'file_path',
					'value'=>'')
				);?>
				</div>
			</div>
			<div class="field_row clearfix">
				<div class='form_field'>
						<?php echo form_checkbox(array('name'=>'empty_trolley' , 'id'=>'empty_trolley') , '1' , FALSE); ?>
						<span class="medium"><?php echo $this->lang->line('orders_empty_trolley');?></span>
				</div>
			</div>
		</fieldset>
	<?php echo form_close(); ?>
</div>
	</div></div>