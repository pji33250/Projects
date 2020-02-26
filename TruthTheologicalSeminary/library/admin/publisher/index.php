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
            <li title="Publishers" class="selected"><a href="index.php"><img alt="" src="../../images/ipublishers.png" /></a></li>
            <li title="Subjects"><a href="../subject"><img alt="" src="../../images/isubjects.png" /></a></li>
            <li title="Logout" style="float: right"><a href="../login/logout.php"><img alt="" src="../../images/ilogout.png" /></a></li>
        </ul>
    </ul>
</div>

<!-- START CONTENT -->
<div id="page_container_mid">
    <button type="button" class="button" id="add_record">Add New Publisher</button>
    <table class="datatable" id="table_companies">
        <thead>
            <tr><th>ID</th><th>Name</th><th>Phone#</th><th>Website</th><th>City</th><th>Country</th><th>Actions</th></tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Add/Edit popups -->
<div class="lightbox_bg"></div>

<div class="lightbox_container">
    <div class="lightbox_close"></div>
    <div class="lightbox_content">

        <h2>Add company</h2>
        <form id="form_company" data-id="" novalidate>
        <table>
            <tr>
                <td colspan="2">
                    <div class="input_container2">
                        <label for="company_name">Name: <span class="required">*</span></label>
                        <div class="field_container2">
                            <input type="text" class="ui-autocomplete-input" name="PUBLISHER_NAME" id="PUBLISHER_NAME" width="400px" value="" autofocus required />
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="input_container">
                        <label for="company_name">Phone:&nbsp;</label>
                        <div class="field_container">
                            <input type="text" class="text" name="PHONE_NUMBER" id="PHONE_NUMBER" value="" >
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input_container">
                        <label for="company_name">Fax:&nbsp;</label>
                        <div class="field_container">
                            <input type="text" class="text" name="FAX_NUMBER" id="FAX_NUMBER" value="" >
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="input_container">
                        <label for="company_name">Email:&nbsp;</label>
                        <div class="field_container">
                            <input type="text" class="text" name="EMAIL" id="EMAIL" value="" >
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input_container">
                        <label for="company_name">Website:&nbsp;</label>
                        <div class="field_container">
                            <input type="text" class="text" name="WEBSITE" id="WEBSITE" value="" >
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="input_container">
                        <label for="company_name">Address:&nbsp;</label>
                        <div class="field_container">
                            <input type="text" class="text" name="ADDRESS" id="ADDRESS" value="" >
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input_container">
                        <label for="company_name">City:&nbsp;</label>
                        <div class="field_container">
                            <input type="text" class="text" name="CITY" id="CITY" value="" >
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="input_container">
                        <label for="company_name">State:&nbsp;</label>
                        <div class="field_container">
                            <input type="text" class="text" name="STATE" id="STATE" value="" >
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input_container">
                        <label for="company_name">Zip:&nbsp;</label>
                        <div class="field_container">
                            <input type="text" class="text" name="ZIP" id="ZIP" value="" >
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="input_container">
                        <label for="company_name">Country:&nbsp;</label>
                        <div class="field_container">
                            <input type="text" class="text" name="COUNTRY" id="COUNTRY" value="" >
                        </div>
                    </div>
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="button_container">
                        <button type="submit">Add Publisher</button>
                    </div>
                </td>
            </tr>
        </table>
        </form>
        <p style="color: red">For the "Name" field, the drop-down-list (if there is any, as you are typing) are existing publishers for your reference</p>
    </div>
</div>
<!--  *END* CONTENT -->

<?php
include($_SERVER['DOCUMENT_ROOT'].'/library/admin/scripts/footer.php');
?>
