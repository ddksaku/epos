<fieldset id="order_info">
	<legend><?php echo $this->lang->line("pastorders_product_info"); ?></legend>
	<?php echo $manage_table;?>
</fieldset>

<br><br>
<fieldset id="how_many_qty_info" style='background-color:#FFFFFF; position: absolute; width:400px; height:40px;padding-top:10px; padding-right:10px; text-align:right; visibility:hidden;'>
	<?php echo form_label($this->lang->line('pastorders_how_many').':', 'how_many',array('class'=>'required')); ?>
	<?php echo form_input(array('name'=>'how_many_qty' , 'id'=>'how_many_qty' , 'class'=>'product_search_cell' , 'style'=>'width:120px; height:18px;'));?>
	&nbsp;&nbsp;&nbsp;<div class='tiny_button' style='float: right;' onmouseover="this.className='tiny_button_over'" onmouseout="this.className='tiny_button'" onclick="$('#how_many_qty_info').css('visibility' , 'hidden');"><span>Cancel</span></div>
&nbsp;&nbsp;&nbsp;<div class='tiny_button' style='float: right; margin-right:20px;' onmouseover="this.className='tiny_button_over'" onmouseout="this.className='tiny_button'" onclick="set_qty_trolley();"><span>OK</span></div>
</fieldset>
<input type='hidden' name='prod_id' id='prod_id' value='0'>
<?php
	if($completed == 0)
	{
?>

		<div class='tiny_button' style='float: right;' onmouseover="this.className='tiny_button_over'" onmouseout="this.className='tiny_button'" onclick='go_back();'><span><?php echo $this->lang->line('pastorders_go_back');?></span></div>
		<div class='tiny_long_long_button' style='float: right; margin-right:20px;' onmouseover="this.className='tiny_long_long_button_over'" onmouseout="this.className='tiny_long_long_button'" onclick="continue_order(<?php echo $order_id;?> , 1);"><span><?php echo $this->lang->line('pastorders_continue_this_order');?></span></div>

<?php
	}
	else if($completed == 1)
	{
?>

		<div class='tiny_button' style='float: right;' onmouseover="this.className='tiny_button_over'" onmouseout="this.className='tiny_button'" onclick='go_back();'><span><?php echo $this->lang->line('pastorders_go_back');?></span></div>
		<div class='tiny_long_long_button' style='float: right; margin-right:20px;' onmouseover="this.className='tiny_long_long_button_over'" onmouseout="this.className='tiny_long_long_button'" onclick="continue_order(0 , 2);"><span><?php echo $this->lang->line('pastorders_go_my_trolley');?></span></div>


<?php
	}
?>

<script type='text/javascript'>
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


function delay(gap)
{
	var then , now;
	then = new Date().getTime();
	now = then;
	while((now - then) < gap)
	{
		now = new Date().getTime();
	}
}

function DisplayPad(gx , gy)
{
    $('#how_many_qty_info').css('left' , gx - 430);
    $('#how_many_qty_info').css('top' , gy - 20);
    $('#how_many_qty_info').css('visibility' , 'visible');
    $('#how_many_qty').attr('value' , '');
    $('#how_many_qty').focus();
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
	tb_remove();
}

function set_qty_trolley()
{
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
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : "prod_id=" + prod_id + "&quantity=" + qty
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