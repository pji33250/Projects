
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
          { "data": "CALL_ID" },
          { "data": "CHN_NAME" },
          { "data": "ENG_NAME" },
          { "data": "ANAME" },
          { "data": "ENAME" },
          { "data": "SNAME" },
          { "data": "PNAME" },
          { "data": "Actions" }
        ],
        "columnDefs": [
            {
                "targets": 0,
                "width": "15%",
                "render": function ( data, type, row ) {
                    return '<a href="/library/search/bookdetail.php?id=' + row.ID + '">' + data + '</a>';
                }
            },
            
            {
                "targets": -1,
                "width": "6%",
                "orderable": false,
                "searchable": false,
                "sClass": "functions",
                "render": function ( data, type, row ) {
                    var myaction  = '<div class="function_buttons"><ul>';
                    myaction += '<li class="function_edit"><a data-id="' + row.ID + '" data-name="' + row.CHN_NAME + '"><span>Edit</span></a></li>';
                    myaction += '<li class="function_delete"><a data-id="' + row.ID + '" data-name="' + row.CHN_NAME + '"><span>Delete</span></a></li>';
                    myaction += '</ul></div>';
                    return myaction;
                }
            }
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
        $('#CALL_ID').focus();
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
        $('.lightbox_content h2').text('Add Book');
        $('#form_company button').text('Add Book');
        $('#form_company').attr('class', 'form add');
        $('#form_company').attr('data-id', '');
        $('#form_company .field_container label.error').hide();
        $('#form_company .field_container').removeClass('valid').removeClass('error');
        $('#form_company #CALL_ID').val('');
        $('#form_company #CHN_NAME').val('');
        $('#form_company #ENG_NAME').val('');
        $('#form_company #AUTHOR_ID').val('');
        $('#form_company #AUTHOR_NAME').val('');
        $('#form_company #ENGLISH_AUTHOR_ID').val('');
        $('#form_company #ENGLISH_AUTHOR_NAME').val('');
        $('#form_company #TRANSLATOR_ID').val('');
        $('#form_company #TRANSLATOR_NAME').val('');
        $('#form_company #EDITION').val('');
        $('#form_company #ISBN').val('');
        $('#form_company #QTY').val('1');
        $('#form_company #PUBLISHER_ID').val('');
        $('#form_company #PUBLISHER_NAME').val('');
        $('#form_company #PUBLISHED_CITY').val('');
        $('#form_company #PUBLISHED_YEAR').val('');
        $('#form_company #SUBJECT_ID').val('');
        $('#form_company #SUBJECT_NAME').val('');
        $('#form_company #SUBJECTII').val('');
        $('#form_company #SUBJECTIII').val('');
        $('#form_company #NOTE1').val('');
        $('#form_company #NOTE2').val('');
        $('#form_company #RESERVED').val('0');
        $('#form_company #REFERENCE').val('0');
        $('#form_company #CHECKED_OUT').val('0');
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
                        var myname = $('#CHN_NAME').val();
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
                $('.lightbox_content h2').text('Edit book');
                $('#form_company button').text('Edit book');
                $('#form_company').attr('class', 'form edit');
                $('#form_company').attr('data-id', myid);
                $('#form_company .field_container label.error').hide();
                $('#form_company .field_container').removeClass('valid').removeClass('error');
                $('#form_company #CALL_ID').val(output.data[0].CALL_ID);
                $('#form_company #CHN_NAME').val(output.data[0].CHN_NAME);
                $('#form_company #ENG_NAME').val(output.data[0].ENG_NAME);
                $('#form_company #EDITION').val(output.data[0].EDITION);
                $('#form_company #QTY').val(output.data[0].QTY);
                $('#form_company #AUTHOR_ID').val(output.data[0].AUTHOR_ID);
                $('#form_company #AUTHOR_NAME').val(output.data[0].AUTHOR_NAME);
                $('#form_company #ENGLISH_AUTHOR_ID').val(output.data[0].ENGLISH_AUTHOR_ID);
                $('#form_company #ENGLISH_AUTHOR_NAME').val(output.data[0].ENGLISH_AUTHOR_NAME);
                $('#form_company #TRANSLATOR_ID').val(output.data[0].TRANSLATOR_ID);
                $('#form_company #TRANSLATOR_NAME').val(output.data[0].TRANSLATOR_NAME);
                $('#form_company #PUBLISHER_ID').val(output.data[0].PUBLISHER_ID);
                $('#form_company #PUBLISHER_NAME').val(output.data[0].PUBLISHER_NAME);
                $('#form_company #PUBLISHED_CITY').val(output.data[0].PUBLISHED_CITY);
                $('#form_company #PUBLISHED_YEAR').val(output.data[0].PUBLISHED_YEAR);
                $('#form_company #ISBN').val(output.data[0].ISBN);
                $('#form_company #SUBJECT_ID').val(output.data[0].SUBJECT_ID);
                $('#form_company #SUBJECT_NAME').val(output.data[0].SUBJECT_NAME);
                $('#form_company #SUBJECTII').val(output.data[0].SUBJECTII);
                $('#form_company #SUBJECTIII').val(output.data[0].SUBJECTIII);
                $('#form_company #NOTE1').val(output.data[0].NOTE1);
                $('#form_company #NOTE2').val(output.data[0].NOTE2);
                $('#form_company #RESERVED').val(output.data[0].RESERVED);
                $('#form_company #REFERENCE').val(output.data[0].REFERENCE);
                $('#form_company #CHECKED_OUT').val(output.data[0].CHECKED_OUT);
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
                        var myname = $('#CHN_NAME').val();
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
