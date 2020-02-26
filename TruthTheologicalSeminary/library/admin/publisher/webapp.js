
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
          { "data": "ID" },
          { "data": "NAME" },
          { "data": "PHONE_NUMBER" },
          { "data": "WEBSITE" },
          { "data": "CITY" },
          { "data": "COUNTRY" },
          { "data": "Actions" }
        ],
        "columnDefs": [
            {
                "targets": 0,
                "width": "6%",
                "render": function ( data, type, row ) {
                    return '<a href="/library/search/booklist.php?category=publisher&id=' + data + '">' + data + '</a>';
                }
            },
            {
                "targets": -1,
                "width": "7.5%",
                "orderable": false,
                "searchable": false,
                "sClass": "functions",
                "render": function ( data, type, row ) {
                    var myaction  = '<div class="function_buttons"><ul>';
                    myaction += '<li class="function_edit"><a data-id="' + row.ID + '" data-name="' + row.NAME + '"><span>Edit</span></a></li>';
                    myaction += '<li class="function_delete"><a data-id="' + row.ID + '" data-name="' + row.NAME + '"><span>Delete</span></a></li>';
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
        $('#PUBLISHER_NAME').focus();
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
        $('.lightbox_content h2').text('Add Publisher');
        $('#form_company button').text('Add Publisher');
        $('#form_company').attr('class', 'form add');
        $('#form_company').attr('data-id', '');
        $('#form_company .field_container label.error').hide();
        $('#form_company .field_container').removeClass('valid').removeClass('error');
        $('#form_company #PUBLISHER_NAME').val('');
        $('#form_company #PHONE_NUMBER').val('');
        $('#form_company #FAX_NUMBER').val('');
        $('#form_company #WEBSITE').val('');
        $('#form_company #ADDRESS').val('');
        $('#form_company #CITY').val('');
        $('#form_company #STATE').val('');
        $('#form_company #ZIP').val('');
        $('#form_company #COUNTRY').val('');
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
                        var myname = $('#PUBLISHER_NAME').val();
                        show_message('Record (' + myname + ') added successfully', output.result);
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
        var myid      = $(this).data('id');
        var request = $.ajax({
            url:          'data.php?job=get_record&id=' + myid,
            cache:        false,
            dataType:     'json',
            contentType:  'application/json; charset=utf-8',
            type:         'get'
        });
        request.done(function(output){
            if (output.result == 'success'){
                $('.lightbox_content h2').text('Edit Publisher');
                $('#form_company button').text('Edit Publisher');
                $('#form_company').attr('class', 'form edit');
                $('#form_company').attr('data-id', myid);
                $('#form_company .field_container label.error').hide();
                $('#form_company .field_container').removeClass('valid').removeClass('error');
                $('#form_company #PUBLISHER_NAME').val(output.data[0].NAME);
                $('#form_company #PHONE_NUMBER').val(output.data[0].PHONE_NUMBER);
                $('#form_company #FAX_NUMBER').val(output.data[0].FAX_NUMBER);
                $('#form_company #WEBSITE').val(output.data[0].WEBSITE);
                $('#form_company #ADDRESS').val(output.data[0].ADDRESS);
                $('#form_company #CITY').val(output.data[0].CITY);
                $('#form_company #STATE').val(output.data[0].STATE);
                $('#form_company #ZIP').val(output.data[0].ZIP);
                $('#form_company #COUNTRY').val(output.data[0].COUNTRY);
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
            var myid      = $('#form_company').attr('data-id');
            var form_data = $('#form_company').serialize();
            var request   = $.ajax({
                url:          'data.php?job=edit_record&id=' + myid,
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
                        var myname = $('#PUBLISHER_NAME').val();
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
                type:         'get'
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
