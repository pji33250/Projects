
$(document).ready(function(){

    // On page load: datatable
    var table_companies = $('#table_companies').dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "data.php?job=get_records",
            "type": "POST",
            "dataSrc": "records"
        },
        "columns": [
          { "data": "PID" },
          { "data": "TYPE" },
          { "data": "CHN_NAME" },
          { "data": "ENG_NAME" },
          { "data": "PHONE" },
          { "data": "EMAIL" },
          { "data": "ADMIN" },
          { "data": "ACTIVE" },
          { "data": "Actions" }
        ],
        "columnDefs": [
            {
                "targets": 6,
                "orderable": false,
                "searchable": false,
                "render": function ( data, type, row ) {
                    return (data == '0') ? "No" : "Yes";
                }
            },
            {
                "targets": 7,
                "orderable": false,
                "searchable": false,
                "render": function ( data, type, row ) {
                    return (data == '0') ? "No" : "Yes";
                }
            },
            {
                "targets": -1,
                "width": "7%",
                "orderable": false,
                "searchable": false,
                "sClass": "functions",
                "render": function ( data, type, row ) {
                    var myaction  = '<div class="function_buttons"><ul>';
                    myaction += '<li class="function_edit"><a data-id="' + row.ID + '" data-name="' + row.PID + '"><span>Edit</span></a></li>';
                    myaction += '<li class="function_delete"><a data-id="' + row.ID + '" data-name="' + row.PID + '"><span>Delete</span></a></li>';
                    myaction += '</ul></div>';
                    return myaction;
                }
            },
        ],

        "lengthMenu": [[10, 20, 50, 100], [10, 20, 50, 100]],
        "oLanguage": {
            "oPaginate": {
                "sFirst":       " ",
                "sPrevious":    " ",
                "sNext":        " ",
                "sLast":        " ",
            },
            "sLengthMenu":    "Records per page: _MENU_",
            "sInfo":          "Total of _TOTAL_ records (showing _START_ to _END_)",
            "sInfoFiltered":  "(filtered from _MAX_ total records)"
        }


    });



    // Show message
    function show_message(message_text, message_type){
      $('#message').html('<p>' + message_text + '</p>').attr('class', message_type);
      $('#message_container').show();

      //Showing message for a given time period (8 seconds)
      if (typeof timeout_message !== 'undefined') { window.clearTimeout(timeout_message); }
      timeout_message = setTimeout(function(){ hide_message(); }, 8000);
    }
    // Hide message
    function hide_message(){
        $('#message').html('').attr('class', '');
        $('#message_container').hide();
    }
    // Show loading message
    function show_loading_message(){
        $('#loading_container').show();
    }
    // Hide loading message
    function hide_loading_message(){
        $('#loading_container').hide();
    }
    // Show lightbox
    function show_lightbox(){
        $('.lightbox_bg').show();
        $('.lightbox_container').show();
        $('#PID').focus();
    }
    // Hide lightbox
    function hide_lightbox(){
        $('.lightbox_bg').hide();
        $('.lightbox_container').hide();
    }
    // Lightbox background
    $(document).on('click', '.lightbox_bg', function(){
        hide_lightbox();
    });
    // Lightbox close button
    $(document).on('click', '.lightbox_close', function(){
        hide_lightbox();
    });
    // Escape keyboard key
    $(document).keyup(function(e){
        if (e.keyCode == 27) { hide_lightbox(); }
    });
    // Hide iPad keyboard
    function hide_ipad_keyboard(){
        document.activeElement.blur();
        $('input').blur();
    }




    var form_company = $('#form_company');
    form_company.validate();


    ///////////////////////////////////
    // Add new record button clicked //
    ///////////////////////////////////
    $(document).on('click', '#add_record', function(e)
    {
        e.preventDefault();
        $('.lightbox_content h2').text('Add User');
        $('#form_company button').text('Add User');
        $('#form_company').attr('class', 'form add');
        $('#form_company').attr('data-id', '');
        $('#form_company .field_container label.error').hide();
        $('#form_company .field_container').removeClass('valid').removeClass('error');
        $('#form_company #PID').val('');
        $('#form_company #TYPE').val('Student ID');
        // $('#form_company #TITLE').val('');
        $('#form_company #CHN_NAME').val('');
        $('#form_company #ENG_NAME').val('');
        $('#form_company #PHONE').val('');
        $('#form_company #EMAIL').val('');
        $('#form_company #LOGON').val('');
        $('#form_company #PASSWORD').val('');
        $('#form_company #ADMIN').val('0');
        $('#form_company #ACTIVE').val('1');

        //PETER: show [LOGON/PASSWORD] for admin user, or they will be hidden
        showLogon('0');
        show_lightbox();
    });

    //////////////////////////////
    // Add new record submitted //
    //////////////////////////////
    $(document).on('submit', '#form_company.add', function(e)
    {
        e.preventDefault();
        // Validate form
        if (form_company.valid() == true)
        {
            // Send company information to database
            hide_ipad_keyboard();
            hide_lightbox();
            show_loading_message();
            var form_data = $('#form_company').serialize();
            var request   = $.ajax({
                url:          'data.php?job=add_record',
                cache:        false,
                data:         form_data,
                dataType:     'json',
                contentType:  'application/json; charset=utf-8',
                type:         'get'
            });

            request.done(function(output){
                if (output.result == 'success') {
                    // Reload datable
                    table_companies.api().ajax.reload(function(){
                        hide_loading_message();
                        var myname = $('#PID').val();
                        show_message("Record (" + myname + ") added successfully.", output.result);
                    }, true);
                }
                else {
                    hide_loading_message();
                    show_message(output.message, output.result);
                }
            });

            request.fail(function(jqXHR, textStatus){
                hide_loading_message();
                show_message('Add request failed: ' + textStatus, 'error');
            });
        }
    });

    ////////////////////////
    // Edit record button //
    ////////////////////////
    $(document).on('click', '.function_edit a', function(e){
        e.preventDefault();

        // Get company information from database
        show_loading_message();
        var authorid      = $(this).data('id');
        //confirm("my author id: '" + authorid + "'");
        var request = $.ajax({
            url:          'data.php?job=get_record&id=' + authorid,
            cache:        false,
            dataType:     'json',
            contentType:  'application/json; charset=utf-8',
            type:         'get'
        });
        request.done(function(output){
            if (output.result == 'success'){
                //confirm("return result: '" + output.data[0].NAME + "'");
                $('.lightbox_content h2').text('Edit User');
                $('#form_company button').text('Edit User');
                $('#form_company').attr('class', 'form edit');
                $('#form_company').attr('data-id', authorid);
                $('#form_company .field_container label.error').hide();
                $('#form_company .field_container').removeClass('valid').removeClass('error');
                $('#form_company #PID').val(output.data[0].PID);
                $('#form_company #TYPE').val(output.data[0].TYPE);
                // $('#form_company #TITLE').val(output.data[0].TITLE);
                $('#form_company #CHN_NAME').val(output.data[0].CHN_NAME);
                $('#form_company #ENG_NAME').val(output.data[0].ENG_NAME);
                $('#form_company #PHONE').val(output.data[0].PHONE);
                $('#form_company #EMAIL').val(output.data[0].EMAIL);
                $('#form_company #LOGON').val(output.data[0].LOGON);
                $('#form_company #PASSWORD').val(output.data[0].PASSWORD);
                $('#form_company #ADMIN').val(output.data[0].ADMIN);
                $('#form_company #ACTIVE').val(output.data[0].ACTIVE);

                //PETER: show [LOGON/PASSWORD] for admin user, or they will be hidden
                showLogon(output.data[0].ADMIN);

                hide_loading_message();
                show_lightbox();
            }
            else
            {
                hide_loading_message();
                show_message(output.message, output.result);
            }
        });
        request.fail(function(jqXHR, textStatus){
            hide_loading_message();
            show_message('Information request failed: ' + textStatus, 'error');
        });
    });

    /////////////////////////////
    // Edit record submit form //
    /////////////////////////////
    $(document).on('submit', '#form_company.edit', function(e){
        e.preventDefault();
        // Validate form
        if (form_company.valid() == true){
            // Send company information to database
            hide_ipad_keyboard();
            hide_lightbox();
            show_loading_message();
            var id        = $('#form_company').attr('data-id');
            var form_data = $('#form_company').serialize();
            var request   = $.ajax({
                url:          'data.php?job=edit_record&id=' + id,
                cache:        false,
                data:         form_data,
                dataType:     'json',
                contentType:  'application/json; charset=utf-8',
                type:         'get'
            });
            request.done(function(output){
                if (output.result == 'success'){
                    // Reload datable
                    table_companies.api().ajax.reload(function(){
                        hide_loading_message();
                        var myname = $('#PID').val();
                        show_message("Record (" + myname + ") edited successfully.", output.result);
                    }, true);
                } else {
                    hide_loading_message();
                    show_message(output.message, output.result);
                }
            });
            request.fail(function(jqXHR, textStatus){
                hide_loading_message();
                show_message('Edit request failed: ' + textStatus, 'error');
            });
        }
    });

    ///////////////////
    // Delete record //
    ///////////////////
    $(document).on('click', '.function_delete a', function(e){
        e.preventDefault();
        var myid = $(this).data('id');
        var myname = $(this).data('name');

        if (confirm("Are you sure you want to delete '" + myname + "'?"))
        {
            show_loading_message();
            var request = $.ajax({
                url:          'data.php?job=delete_record&id='+ myid,
                cache:        false,
                dataType:     'json',
                contentType:  'application/json; charset=utf-8',
                type:         'POST'
            });

            request.done(function(output){
                if (output.result == 'success'){
                    // Reload datable
                    table_companies.api().ajax.reload(function(){
                        hide_loading_message();
                        show_message("Record (" + myname + ") deleted successfully.", output.result);
                    }, true);
                } else {
                    hide_loading_message();
                    show_message(output.message, output.result);
                }
            });

            request.fail(function(jqXHR, textStatus){
                hide_loading_message();
                show_message('Delete request failed: ' + textStatus, 'error');
            });
        }
    });



});

//OTHERS
function showLogon(val) {
    if (val == '1') {
        document.getElementById('AdminInfo').style.visibility = 'visible';
    }
    else {
        document.getElementById('AdminInfo').style.visibility = 'hidden';
    }

    return false;
}
