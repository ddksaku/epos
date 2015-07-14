function checkbox_click(event)
{
	event.stopPropagation();
	do_email(enable_email.url);
	if($(event.target).attr('checked'))
	{
		$(event.target).parent().parent().find("td").addClass('selected').css("backgroundColor","");
	}
	else
	{
		$(event.target).parent().parent().find("td").removeClass();
	}
}

function enable_search(suggest_url,confirm_search_message)
{
	//Keep track of enable_email has been called
	if(!enable_search.enabled)
		enable_search.enabled=true;

	$('#search').click(function()
    {
    	$(this).attr('value','');
    });

    $("#search").autocomplete(suggest_url,{max:100,delay:10, selectFirst: false});
    $("#search").result(function(event, data, formatted)
    {
		do_search(true);
    });

	$('#search_form').submit(function(event)
	{
		event.preventDefault();

		if(get_selected_values().length >0)
		{
			if(!confirm(confirm_search_message))
				return;
		}
		do_search(true);
	});
}



function enable_search_orders(suggest_url , confirm_search_message)
{
	if(!enable_search.enabled)
		enable_search.enabled=true;

	$('#search').click(function()
    {
    	$(this).attr('value','');
    });



    $("#search").autocomplete(suggest_url , {max:100 , delay:10 , selectFirst: false});
    $("#search").result(function(event , data , formatted)
    {
		do_search_orders(true);
    });


	$('#search_form').submit(function(event)
	{
		event.preventDefault();

		if(get_selected_values().length >0)
		{
			if(!confirm(confirm_search_message))
				return;
		}
		do_search_orders(true);
	});
}






function enable_search0(suggest_url0 , suggest_url1 , suggest_url2 , confirm_search_message)
{
	//Keep track of enable_email has been called
	if(!enable_search.enabled)
		enable_search.enabled=true;

	$('#search0').click(function()
    {
    	//$(this).attr('value','');
		$(this).val('');
    });



//    $("#search0").autocomplete(suggest_url0,{max:100,delay:10, selectFirst: false});
//    $("#search0").result(function(event, data, formatted)
//    {
//		do_search0(true);
//    });


	$('#search1').click(function()
    {
    	//$(this).attr('value','');
		$(this).val('');
    });

//    $("#search1").autocomplete(suggest_url1,{max:100,delay:10, selectFirst: false});
//    $("#search1").result(function(event, data, formatted)
//    {
//		do_search0(true);
//    });


	$('#search2').click(function()
    {
    	//$(this).attr('value','');
		$(this).val('');
    });


    $("#search2").autocomplete({minLength:2 ,
    	source: function( request, response ) {
    		$.ajax({
    			type : "POST" ,
    			url: suggest_url2 ,
    			dataType: "json" ,
    			data: {term:request.term} ,
    			error : function(request, status, error) {
    		         alert("code");
    		        },
    			success: function(data) {

    				response(data);
    			}
    		});
    	}// ,
//    	select: function(event , ui) {
//   		do_search0(true);
//    	}
    });



	$('#search_form').submit(function(event)
	{
		event.preventDefault();

		if(get_selected_values().length >0)
		{
			if(!confirm(confirm_search_message))
				return;
		}
		do_search0(true);
	});
}


function enable_search1(suggest_url , confirm_search_message)
{
	//Keep track of enable_email has been called
	if(!enable_search.enabled)
		enable_search.enabled=true;

	$('#search').click(function()
    {
    	$(this).val('');
    });

    $("#search").autocomplete({minLength:2 ,
    	source: function( request, response ) {
    		$.ajax({
    			type : "POST" ,
    			url: suggest_url ,
    			dataType: "json" ,
    			data: {term:request.term} ,
    			error : function(request, status, error) {
    		         alert("code");
    		        },
    			success: function(data) {

    				response(data);
    			}
    		});
    	}
    });


	$('#search_form').submit(function(event)
	{
		event.preventDefault();

		if(get_selected_values().length > 0)
		{
			if(!confirm(confirm_search_message))
				return;
		}
		do_search1(true);
	});
}





enable_search.enabled=false;


function do_search0(show_feedback,on_complete)
{
	//If search is not enabled, don't do anything
	if(!enable_search.enabled)
		return;

	if(show_feedback)
		$('#spinner1').show();
	var controller_url = $('#search_form').attr('action');
	var search0 = $('#search0').val();
	var search1 = $('#search1').val();
	var search2 = $('#search2').val();
	var sort_key = $('#sort_key').val();
	var per_page = $('#per_page').val();
	var category_id = $('#category').val();

//	$('#search0').val('');
//	$('#search1').val('');
//	$('#search2').val('');

    $.ajax({
        type : "POST"
        , async : true
        , url : controller_url
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : "search0=" + search0 + "&search1=" + search1 + "&search2=" + search2 + "&sort_key=" + sort_key + "&per_page=" + per_page + "&category_id=" + category_id
        , success : function(response, status, request) {
            var strArray = response.split('********************');
            //$('#search_mode').val(strArray[0]);
            $('#search_mode').val("search");
            $('#product_pagination_div').html(strArray[1]);
            $('#product_pagination_div1').html(strArray[1]);
            $('#sortable_table tbody').html(strArray[2]);
    		$('#spinner1').hide();

//    		tb_init('#sortable_table a.thickbox');
//    		update_sortable_table();
//    		enable_row_selection();
//    		$('#sortable_table tbody :checkbox').click(checkbox_click);
//    		$("#select_all").attr('checked',false);


        }
    });
/*
	$('#sortable_table tbody').load($('#search_form').attr('action'),{'search0':$('#search0').val() , 'search1':$('#search1').val() , 'search2':$('#search2').val()},function()
	{
		if(typeof on_complete=='function')
			on_complete();

		$('#spinner1').hide();
		//re-init elements in new table, as table tbody children were replaced
		tb_init('#sortable_table a.thickbox');
		update_sortable_table();
		enable_row_selection();
		$('#sortable_table tbody :checkbox').click(checkbox_click);
		$("#select_all").attr('checked',false);
	});
*/
}



function do_search_orders(show_feedback , on_complete)
{
	if(!enable_search.enabled)
		return;

	if(show_feedback)
		$('#spinner').show();
	var controller_url = $('#search_form').attr('action');
	var search = $('#search').val();
	var sort_key = $('#sort_key').val();
	var per_page = $('#per_page').val();

    $.ajax({
        type : "POST"
        , async : true
        , url : controller_url
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : "search=" + search + "&sort_key=" + sort_key + "&per_page=" + per_page
        , success : function(response, status, request) {
            var strArray = response.split('********************');
            $('#search_mode').val("search");
            $('#product_pagination_div').html(strArray[1]);
            $('#sortable_table tbody').html(strArray[2]);
    		$('#spinner').hide();

    		tb_init('#sortable_table a.thickbox');
    		update_sortable_table();
    		enable_row_selection();
    		$('#sortable_table tbody :checkbox').click(checkbox_click);
    		$("#select_all").attr('checked',false);
        }
    });
/*
	$('#sortable_table tbody').load($('#search_form').attr('action'),{'search0':$('#search0').val() , 'search1':$('#search1').val() , 'search2':$('#search2').val()},function()
	{
		if(typeof on_complete=='function')
			on_complete();

		$('#spinner1').hide();
		//re-init elements in new table, as table tbody children were replaced
		tb_init('#sortable_table a.thickbox');
		update_sortable_table();
		enable_row_selection();
		$('#sortable_table tbody :checkbox').click(checkbox_click);
		$("#select_all").attr('checked',false);
	});
*/
}




function do_search1(show_feedback , on_complete)
{
	if(!enable_search.enabled)
		return;

	if(show_feedback)
		$('#spinner').show();
	var controller_url = $('#search_form').attr('action');
	var search = $('#search').val();
	var sort_key = $('#sort_key').val();
	var per_page = $('#per_page').val();

    $.ajax({
        type : "POST"
        , async : true
        , url : controller_url
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : "search=" + search + "&sort_key=" + sort_key + "&per_page=" + per_page
        , success : function(response, status, request) {
            var strArray = response.split('********************');
            $('#search_mode').val("search");
            $('#product_pagination_div_left_div').html(strArray[1]);
            $('#sortable_table tbody').html(strArray[2]);
    		$('#spinner').hide();
    		tb_init('#sortable_table a.thickbox');

        }
    });
}

function pFirst(url)
{
	var sort_key = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search0 = $('#search0').val();
	var search1 = $('#search1').val();
	var search2 = $('#search2').val();
	var category_id = $('#category').val();

	var per_page = $('#per_page').val();
	var uri_segment;
	var curd_page = $('#curd_page').val();

	if(curd_page == '1') return;
	else curd_page = 1;
	var location_site = url;

	category_id = Number(category_id);

	location_site = location_site + "/" + search_mode + "/";
	uri_segment = (Number(curd_page) - 1) * Number(per_page);
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


function pPrev(url)
{
	var sort_key = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search0 = $('#search0').val();
	var search1 = $('#search1').val();
	var search2 = $('#search2').val();
	var category_id = $('#category').val();
	var per_page = $('#per_page').val();
	var uri_segment;
	var curd_page = $('#curd_page').val();

	if(curd_page == '1') return;

	var location_site = url;

	category_id = Number(category_id);

	location_site = location_site + "/" + search_mode + "/";
	uri_segment = (Number(curd_page) - 2) * Number(per_page);

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


function pLast(url)
{
	var sort_key = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search0 = $('#search0').val();
	var search1 = $('#search1').val();
	var search2 = $('#search2').val();
	var category_id = $('#category').val();
	var per_page = $('#per_page').val();
	var uri_segment;
	var curd_page = $('#curd_page').val();
	var total_page = $('#last_page_number').text();

	if(curd_page == total_page) return;
	else curd_page = total_page;

	category_id = Number(category_id);
	var location_site = url;

	location_site = location_site + "/" + search_mode + "/";
	uri_segment = (Number(curd_page) - 1) * Number(per_page);

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

function pNext(url)
{
	var sort_key = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search0 = $('#search0').val();
	var search1 = $('#search1').val();
	var search2 = $('#search2').val();
	var category_id = $('#category').val();
	var per_page = $('#per_page').val();
	var uri_segment;
	var curd_page = $('#curd_page').val();
	var total_page = $('#last_page_number').text();

	if(curd_page == total_page) return;

	category_id = Number(category_id);

	var location_site = url;

	location_site = location_site + "/" + search_mode + "/";
	uri_segment = Number(curd_page) * Number(per_page);
//alert(search_mode);
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



function do_search(show_feedback,on_complete)
{
	//If search is not enabled, don't do anything
	if(!enable_search.enabled)
		return;

	if(show_feedback)
		$('#spinner').show();

	$('#sortable_table tbody').load($('#search_form').attr('action'),{'search':$('#search').val()},function()
	{
		if(typeof on_complete=='function')
			on_complete();

		$('#spinner').hide();
		//re-init elements in new table, as table tbody children were replaced
		tb_init('#sortable_table a.thickbox');
		update_sortable_table();
		enable_row_selection();
		$('#sortable_table tbody :checkbox').click(checkbox_click);
		$("#select_all").attr('checked',false);
	});
}

function enable_email(email_url)
{
	//Keep track of enable_email has been called
	if(!enable_email.enabled)
		enable_email.enabled=true;

	//store url in function cache
	if(!enable_email.url)
	{
		enable_email.url=email_url;
	}

	$('#select_all, #sortable_table tbody :checkbox').click(checkbox_click);
}
enable_email.enabled=false;
enable_email.url=false;

function do_email(url)
{
	//If email is not enabled, don't do anything
	if(!enable_email.enabled)
		return;

	$.post(url, { 'ids[]': get_selected_values() },function(response)
	{
		$('#email').attr('href',response);
	});

}

function enable_checkboxes()
{
	$('#sortable_table tbody :checkbox').click(checkbox_click);
}

function enable_delete(confirm_message,none_selected_message)
{
	//Keep track of enable_delete has been called
	if(!enable_delete.enabled)
		enable_delete.enabled=true;

	$('#delete').click(function(event)
	{
		event.preventDefault();
		if($("#sortable_table tbody :checkbox:checked").length >0)
		{
			if(confirm(confirm_message))
			{
				do_delete($("#delete").attr('href'));
			}
		}
		else
		{
			alert(none_selected_message);
		}
	});
}


function enable_delete_user(index_url , confirm_message , none_selected_message)
{
	//Keep track of enable_delete has been called
	if(!enable_delete.enabled)
		enable_delete.enabled=true;

	$('#delete').click(function(event)
	{
		event.preventDefault();
		if($("#sortable_table tbody :checkbox:checked").length >0)
		{
			if(confirm(confirm_message))
			{
				do_delete_user($("#delete").attr('href') , index_url);
			}
		}
		else
		{
			alert(none_selected_message);
		}
	});
}



enable_delete.enabled=false;

function do_delete(url)
{
	//If delete is not enabled, don't do anything
	if(!enable_delete.enabled)
		return;

	var row_ids = get_selected_values();
	var selected_rows = get_selected_rows();
	$.post(url, { 'ids[]': row_ids },function(response)
	{
		//delete was successful, remove checkbox rows
		if(response.success)
		{
			$(selected_rows).each(function(index, dom)
			{
				$(this).find("td").animate({backgroundColor:"green"},1200,"linear")
				.end().animate({opacity:0},1200,"linear",function()
				{
					$(this).remove();
					//Re-init sortable table as we removed a row
					update_sortable_table();

				});
			});
			set_feedback(response.message,'success_message',false);
		}
		else
		{
			set_feedback(response.message,'error_message',true);
		}


	},"json");
}


function do_delete_user(url , index_url)
{
	//If delete is not enabled, don't do anything
	if(!enable_delete.enabled)
		return;

	var row_ids = get_selected_values();
	var selected_rows = get_selected_rows();
	$.post(url, { 'ids[]': row_ids },function(response)
	{
		//delete was successful, remove checkbox rows
		if(response.success)
		{
			var nCurrentSortKey = $('#sort_key').val();
			var search_mode = $('#search_mode').val();
			var search = $('#search').val();
			var per_page = $('#per_page').val();
			var uri_segment;
			var location_site = index_url;
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
		else
		{
			set_feedback(response.message,'error_message',true);
		}


	},"json");
}



function enable_bulk_edit(none_selected_message)
{
	//Keep track of enable_bulk_edit has been called
	if(!enable_bulk_edit.enabled)
		enable_bulk_edit.enabled=true;

	$('#bulk_edit').click(function(event)
	{
		event.preventDefault();
		if($("#sortable_table tbody :checkbox:checked").length >0)
		{
			tb_show($(this).attr('title'),$(this).attr('href'),false);
			$(this).blur();
		}
		else
		{
			alert(none_selected_message);
		}
	});
}
enable_bulk_edit.enabled=false;

function enable_select_all()
{
	//Keep track of enable_select_all has been called
	if(!enable_select_all.enabled)
		enable_select_all.enabled=true;

	$('#select_all').click(function()
	{
		if($(this).attr('checked'))
		{
			$("#sortable_table tbody :checkbox").each(function()
			{
				$(this).attr('checked',true);
				$(this).parent().parent().find("td").addClass('selected').css("backgroundColor","");

			});
		}
		else
		{
			$("#sortable_table tbody :checkbox").each(function()
			{
				$(this).attr('checked',false);
				$(this).parent().parent().find("td").removeClass();
			});
		}
	 });
}
enable_select_all.enabled=false;

function enable_row_selection(rows)
{
	//Keep track of enable_row_selection has been called
	if(!enable_row_selection.enabled)
		enable_row_selection.enabled=true;

	if(typeof rows =="undefined")
		rows=$("#sortable_table tbody tr");

	rows.hover(
		function row_over()
		{
			$(this).find("td").addClass('over').css("backgroundColor","");
			$(this).css("cursor","pointer");
		},

		function row_out()
		{
			if(!$(this).find("td").hasClass("selected"))
			{
				$(this).find("td").removeClass();
			}
		}
	);

	rows.click(function row_click(event)
	{

		var checkbox = $(this).find(":checkbox");
		checkbox.attr('checked',!checkbox.attr('checked'));
		do_email(enable_email.url);

		if(checkbox.attr('checked'))
		{
			$(this).find("td").addClass('selected').css("backgroundColor","");
		}
		else
		{
			$(this).find("td").removeClass();
		}
	});
}
enable_row_selection.enabled=false;

function update_sortable_table()
{
	//let tablesorter know we changed <tbody> and then triger a resort
	$("#sortable_table").trigger("update");

	if(typeof $("#sortable_table")[0].config!="undefined")
	{
		var sorting = $("#sortable_table")[0].config.sortList;
		$("#sortable_table").trigger("sorton",[sorting]);
	}
}

function update_row(row_id,url)
{
	$.post(url, { 'row_id': row_id },function(response)
	{
		//Replace previous row
		var row_to_update = $("#sortable_table tbody tr :checkbox[value="+row_id+"]").parent().parent();
		row_to_update.replaceWith(response);
		reinit_row(row_id);
		hightlight_row(row_id);
	});
}

function reinit_row(checkbox_id)
{
	var new_checkbox = $("#sortable_table tbody tr :checkbox[value="+checkbox_id+"]");
	var new_row = new_checkbox.parent().parent();
	enable_row_selection(new_row);
	//Re-init some stuff as we replaced row
	update_sortable_table();
	tb_init(new_row.find("a.thickbox"));
	//re-enable e-mail
	new_checkbox.click(checkbox_click);
}

function hightlight_row(checkbox_id)
{
	var new_checkbox = $("#sortable_table tbody tr :checkbox[value="+checkbox_id+"]");
	var new_row = new_checkbox.parent().parent();

	new_row.find("td").animate({backgroundColor:"#e1ffdd"},"slow","linear")
		.animate({backgroundColor:"#e1ffdd"},5000)
		.animate({backgroundColor:"#ffffff"},"slow","linear");
}

function get_selected_values()
{
	var selected_values = new Array();
	$("#sortable_table tbody :checkbox:checked").each(function()
	{
		selected_values.push($(this).val());
	});
	return selected_values;
}

function get_selected_rows()
{
	var selected_rows = new Array();
	$("#sortable_table tbody :checkbox:checked").each(function()
	{
		selected_rows.push($(this).parent().parent());
	});
	return selected_rows;
}

function get_visible_checkbox_ids()
{
	var row_ids = new Array();
	$("#sortable_table tbody :checkbox").each(function()
	{
		row_ids.push($(this).val());
	});
	return row_ids;
}

