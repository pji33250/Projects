<?php
include('../login/session.php');
include($_SERVER['DOCUMENT_ROOT'].'/library/admin/scripts/header.php');
?>

<div id="tabs">
    <ul>
        <ul>
            <!-- <li title="Circulation"><a href="../circulation"><img alt="" src="../../images/icirculation.png" /></a></li> -->
            <li title="Users" class="selected"><a href="index.php"><img alt="User" src="../../images/iusers.png" /></a></li>
            <li title="Books"><a href="../book"><img alt="Book" src="../../images/ibooks.png" /></a></li>
            <li title="Authors"><a href="../author"><img alt="" src="../../images/iauthors.png" /></a></li>
            <li title="Publishers"><a href="../publisher"><img alt="" src="../../images/ipublishers.png" /></a></li>
            <li title="Subjects"><a href="../subject"><img alt="" src="../../images/isubjects.png" /></a></li>
            <li title="Logout" style="float: right"><a href="../login/logout.php"><img alt="" src="../../images/ilogout.png" /></a></li>
        </ul>
    </ul>
</div>

<!-- START CONTENT -->
<div id="page_container_mid">
    <button type="button" class="button" id="add_record">Add New User</button>
    <table class="datatable" id="table_companies">
        <thead>
            <tr>
                <th>PERSONAL ID</th>
                <th>ID TYPE</th>
                <!-- <th>TITLE</th> -->
                <th>CHINESE NAME</th>
                <th>ENGLISH NAME</th>
                <th>PHONE</th>
                <th>EMAIL</th>
                <th>ADMIN</th>
                <th>ACTIVE</th>
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

        <h2>Add company</h2>
        <form id="form_company" data-id="" novalidate>
        <table>
            <tr>
                <td>
                    <div class="input_container">
                        <label for="company_name">Personal ID: <span class="required">*</span></label>
                        <div class="field_container">
                            <input type="text" class="text" name="PID" id="PID" value="" autofocus required>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input_container">
                        <label for="company_name">ID Type: </label>
                        <div class="field_container">
                            <select id="TYPE" name="TYPE">
                                <option value="Driver License">Driver License</option>
                                <option value="Faculty ID">Faculty ID</option>
                                <option value="Student ID" selected="true">Student ID</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                    </div>
                </td>
            </tr>
            <!--
            <tr>
                <td>
                    <div class="input_container">
                        <label for="company_name">Title: </label>
                        <div class="field_container">
                            <input type="text" class="text" name="TITLE" id="TITLE" value="" >
                        </div>
                    </div>
                </td>
                <td>&nbsp;</td>
            </tr>
             -->
            <tr>
                <td>
                    <div class="input_container">
                        <label for="company_name">Chinese Name: </label>
                        <div class="field_container">
                            <input type="text" class="text" name="CHN_NAME" id="CHN_NAME" value="" >
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input_container">
                        <label for="company_name">English Name: </label>
                        <div class="field_container">
                            <input type="text" class="text" name="ENG_NAME" id="ENG_NAME" value="" >
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="input_container">
                    <label for="company_name">Phone: </label>
                        <div class="field_container">
                            <input type="text" class="text" name="PHONE" id="PHONE" value="" >
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input_container">
                        <label for="company_name">Email: </label>
                        <div class="field_container">
                            <input type="text" class="text" name="EMAIL" id="EMAIL" value="" >
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="input_container">
                        <label for="company_name">Admin: </label>
                        <div class="field_container">
                            <select id="ADMIN" name="ADMIN" onchange="return showLogon(this.value)">
                                <option value="1">Yes</option>
                                <option value="0" selected="true">No</option>
                            </select>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input_container">
                        <label for="company_name">Active: </label>
                        <div class="field_container">
                            <select id="ACTIVE" name="ACTIVE">
                                <option value="1" selected="true">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                </td>
            </tr>
            <tr id="AdminInfo" style="visibility: hidden">
                <td>
                    <div class="input_container">
                        <label for="company_name">Logon ID: </label>
                        <div class="field_container">
                            <input type="text" class="text" name="LOGON" id="LOGON" value="" >
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input_container">
                        <label for="company_name">Password: </label>
                        <div class="field_container">
                            <input type="text" class="text" name="PASSWORD" id="PASSWORD" value="" >
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="button_container">
                        <button type="submit">Add User</button>
                    </div>
                </td>
            </tr>
        </table>
        </form>
    </div>
</div>

<!--  *END* CONTENT -->


<?php
include($_SERVER['DOCUMENT_ROOT'].'/library/admin/scripts/footer.php');
?>
