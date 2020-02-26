<?php
include('../login/session.php');
include($_SERVER['DOCUMENT_ROOT'].'/library/admin/scripts/header.php');
?>

<div id="tabs">
    <ul>
        <ul>
            <!-- <li title="Circulation"><a href="../circulation"><img alt="" src="../../images/icirculation.png" /></a></li> -->
            <li title="Users"><a href="../user"><img alt="" src="../../images/iusers.png" /></a></li>
            <li title="Books"><a href="../book"><img alt="" src="../../images/ibooks.png" /></a></li>
            <li title="Authors"><a href="../author"><img alt="" src="../../images/iauthors.png" /></a></li>
            <li title="Publishers"><a href="../publisher"><img alt="" src="../../images/ipublishers.png" /></a></li>
            <li title="Subjects" class="selected"><a href="index.php"><img alt="" src="../../images/isubjects.png" /></a></li>
            <li title="Logout" style="float: right"><a href="../login/logout.php"><img alt="" src="../../images/ilogout.png" /></a></li>
        </ul>
    </ul>
</div>

<!-- START CONTENT -->
<div id="page_container_short">
    <button type="button" class="button" id="add_record">Add new subject</button>
    <table class="datatable" id="table_companies">
        <thead>
            <tr><th>ID</th><th>Name</th><th>Actions</th></tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Add/Edit popups -->
<div class="lightbox_bg"></div>

<div class="lightbox_container">
    <div class="lightbox_close"></div>
    <div class="lightbox_content">
        <h2>Add Item</h2>
        <form class="form add" id="form_company" data-id="" novalidate>
        <table>
            <tr>
                <td>
                    <div class="input_container">
                        <label for="company_name">Name: <span class="required">*</span></label>
                        <div class="field_container">
                            <input type="text" class="ui-autocomplete-input" name="SUBJECT_NAME" id="SUBJECT_NAME" value="" autofocus required />
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
        <p style="color: red">The drop-down-list (if there is any, as you are typing) are existing subjects for your reference</p>

    </div>
</div>
<!--  *END* CONTENT -->

<?php
include($_SERVER['DOCUMENT_ROOT'].'/library/admin/scripts/footer.php');
?>
