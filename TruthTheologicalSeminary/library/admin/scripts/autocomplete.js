//autocomplete functions for author/publisher/subject
$(function()
{
    //Search for English Author
    $('#AUTHOR_NAME').autocomplete({
        source: function( request, response ) {
            //pre-set the author-id to zero
            $('#AUTHOR_ID').val(0);
            $.ajax({
                url :     '/library/lib/ajax.php',
                method:   "POST",
                dataType: "json",
                data:     { query: request.term, table: 'AUTHOR', rownum : 10 },
                success: function(data) {
                    response( $.map( data, function(item) {
                        var myname = item.split("|");
                        return { label: myname[1], value: myname[1], data : item }
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function( event, ui ) {
            var myid = ui.item.data.split("|");
            $('#AUTHOR_ID').val(myid[0]);
        }
    });

    //Search for English Author
    $('#ENGLISH_AUTHOR_NAME').autocomplete({
        source: function( request, response ) {
            //pre-set the author-id to zero
            $('#ENGLISH_AUTHOR_ID').val(0);
            $.ajax({
                url :     '/library/lib/ajax.php',
                method:   "POST",
                dataType: "json",
                data:     { query: request.term, table: 'AUTHOR', rownum : 10 },
                success: function(data) {
                    response( $.map( data, function(item) {
                        var myname = item.split("|");
                        return { label: myname[1], value: myname[1], data : item }
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function( event, ui ) {
            var myid = ui.item.data.split("|");
            $('#ENGLISH_AUTHOR_ID').val(myid[0]);
        }
    });

    //Search for Translator
    $('#TRANSLATOR_NAME').autocomplete({
        source: function( request, response ) {
            //pre-set the author-id to zero
            $('#TRANSLATOR_ID').val(0);
            $.ajax({
                url :     '/library/lib/ajax.php',
                method:   "POST",
                dataType: "json",
                data:     { query: request.term, table: 'AUTHOR', rownum : 10 },
                success: function(data) {
                    response( $.map( data, function(item) {
                        var myname = item.split("|");
                        return { label: myname[1], value: myname[1], data : item }
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function( event, ui ) {
            var myid = ui.item.data.split("|");
            $('#TRANSLATOR_ID').val(myid[0]);
        }
    });

    //Search for Publisher
    $('#PUBLISHER_NAME').autocomplete({
        source: function( request, response ) {
            //pre-set the author-id to zero
            $('#PUBLISHER_ID').val(0);
            $.ajax({
                url :     '/library/lib/ajax.php',
                method:   "POST",
                dataType: "json",
                data:     { query: request.term, table: 'PUBLISHER', rownum : 10 },
                success: function(data) {
                    response( $.map( data, function(item) {
                        var myname = item.split("|");
                        return { label: myname[1], value: myname[1], data : item }
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function( event, ui ) {
            var myid = ui.item.data.split("|");
            $('#PUBLISHER_ID').val(myid[0]);
        }
    });

    //Search for Subject
    $('#SUBJECT_NAME').autocomplete({
        source: function( request, response ) {
            //pre-set the author-id to zero
            $('#SUBJECT_ID').val(0);
            $.ajax({
                url :     '/library/lib/ajax.php',
                method:   "POST",
                dataType: "json",
                data:     { query: request.term, table: 'SUBJECT', rownum : 10 },
                success: function(data) {
                    response( $.map( data, function(item) {
                        var myname = item.split("|");
                        return { label: myname[1], value: myname[1], data : item }
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function( event, ui ) {
            var myid = ui.item.data.split("|");
            $('#SUBJECT_ID').val(myid[0]);
        }
    });


});
