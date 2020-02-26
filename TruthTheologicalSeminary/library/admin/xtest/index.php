<?php

include($_SERVER['DOCUMENT_ROOT'].'/admin/scripts/header.php');

?>
<!--
<!doctype html>
<html lang="en">
<head>
  <title>autocomplete demo</title>

  <meta charset="utf-8">
  <meta name="viewport" content="width=1000, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">


  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
  <link rel="stylesheet" type="text/css" href="/admin/scripts/FontOxygen.css" />

  <link rel="stylesheet" href="/admin/scripts/jquery-1.12.1.ui.css">

  <script src="/admin/scripts/jquery-3.2.1.min.js"></script>
  <script src="/admin/scripts/jquery-3.2.1.js"></script>
  <script src="/admin/scripts/jquery-1.12.1.ui.js"></script>

  <script charset="utf-8" type="text/javascript" src="/admin/scripts/jquery.validate.min.js"></script>
  <script charset="utf-8" type="text/javascript" src="/admin/scripts/jquery.dataTables.min.js"></script>

  <link rel="stylesheet" type="text/css" href="/snippet/layout.css" />
  <link rel="stylesheet" type="text/css" href="/snippet/styles.css" />
  <script charset="utf-8" src="webapp.js"></script>
  <script charset="utf-8" type="text/javascript" src="search.js"></script>

</head>
<body>
 -->


<script charset="utf-8" type="text/javascript" src="autocomplete.js"></script>

<script>
$(function() {
  $('#NAME').autocomplete({
    source: function( request, response )
    {
  		$.ajax({
  			url :     'ajax.php',
        method:   "POST",
  			dataType: "json",
			  data:     { query: request.term, table: 'AUTHOR', rownum : 10 },
			  success:  function( data )
                  {
                    response( $.map( data, function( item ) {
            				 	var myname = item.split("|");
            					return { label: myname[1], value: myname[1], data : item }
          				  }));
			            }
  		});
  	},
  	autoFocus: true,
  	minLength: 0,
  	select: function( event, ui )
            {
          		var myid = ui.item.data.split("|");
          		$('#ID').val(myid[0]);
            }
  });
});
</script>


<div class="ui-widget">
   <input type="text" name="NAME" id="NAME" class="ui-autocomplete-input">
   <input type="text" name="ID" id="ID" class="ui-autocomplete-input">
</div>


</body>
</html>
