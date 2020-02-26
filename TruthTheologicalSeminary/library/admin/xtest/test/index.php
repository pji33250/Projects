<?php
//include('../login/session.php');
include($_SERVER['DOCUMENT_ROOT'].'/library/admin/scripts/header.php');
?>

<div id="tabs">
    <ul>
        <ul>
            <!-- <li title="Circulation"><a href="../circulation"><img alt="" src="../../images/icirculation.png" /></a></li> -->
            <li title="Users"><a href="../user"><img alt="" src="../../images/iusers.png" /></a></li>
            <li title="Books"><a href="../book"><img alt="" src="../../images/ibooks.png" /></a></li>
            <li title="Authors" class="selected"><a href="index.php"><img alt="" src="../../images/iauthors.png" /></a></li>
            <li title="Publishers"><a href="../publisher"><img alt="" src="../../images/ipublishers.png" /></a></li>
            <li title="Subjects"><a href="../subject"><img alt="" src="../../images/isubjects.png" /></a></li>
            <li title="Logout" style="float: right"><a href="../login/logout.php"><img alt="" src="../../images/ilogout.png" /></a></li>
        </ul>
    </ul>
</div>

<script>
		$(document).ready(function() {
		    var mytable = $('#table_companies').DataTable( {
				"processing": true,
				"serverSide": true,
		        "ajax": {
		            "url": "server-json-data.php?job=get_records",
		            "type": "POST",
		            "dataSrc": "records"
		        },
		        "columns": [
		            { "data": "ID" },
		            { "data": "NAME" },
		            { "data": "Action" },
		        ],
				"columnDefs": [
					{
			        	"targets": 0,
			            "render": function ( data, type, row ) {
			                return '<a href="/library/search/booklist.php?category=author&id=' + data + '">' + data + '</a>';
			            }
		        	},
		        	{
		      		 	"targets": 2,
                        "orderable": false,
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
                },

		    });
		});
		</script>

        <!-- START CONTENT -->
        <div id="page_container_short">
            <button type="button" class="button" id="add_record">Add new author</button>
            <table class="datatable" id="table_companies">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- Add/Edit popups -->
        <div class="lightbox_bg"></div>

        <div class="lightbox_container">
            <div class="lightbox_close"></div>
            <div class="lightbox_content">

                <h2>Add New Item</h2>
                <form class="form add" id="form_company" data-id="" novalidate>
                <table>
                    <tr>
                        <td>
                            <div class="input_container">
                                <label for="company_name">Name: <span class="required">*</span></label>
                                <div class="field_container">
                                    <input type="text" class="ui-autocomplete-input" name="AUTHOR_NAME" id="AUTHOR_NAME" value="" required autofocus />
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="button_container">
                                <button type="submit">Add Item</button>
                            </div>
                        </td>
                    </tr>
                </table>
                </form>
                <p style="color: red">The drop-down-list (if there is any, as you are typing) are existing authors for your reference</p>

            </div>
        </div>
        <!--  *END* CONTENT -->

<?php
include($_SERVER['DOCUMENT_ROOT'].'/library/admin/scripts/footer.php');
?>
