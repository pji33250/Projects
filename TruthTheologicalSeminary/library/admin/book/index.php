<?php
include('../login/session.php');
include($_SERVER['DOCUMENT_ROOT'].'/library/admin/scripts/header.php');
?>

<div id="tabs">
    <ul>
        <ul>
            <!-- <li title="Circulation"><a href="../circulation"><img alt="" src="../../images/icirculation.png" /></a></li> -->
            <li title="Users"><a href="../user"><img alt="" src="../../images/iusers.png" /></a></li>
            <li title="Books" class="selected"><a href="index.php"><img alt="" src="../../images/ibooks.png" /></a></li>
            <li title="Authors"><a href="../author"><img alt="" src="../../images/iauthors.png" /></a></li>
            <li title="Publishers"><a href="../publisher"><img alt="" src="../../images/ipublishers.png" /></a></li>
            <li title="Subjects"><a href="../subject"><img alt="" src="../../images/isubjects.png" /></a></li>
            <li title="Logout" style="float: right"><a href="../login/logout.php"><img alt="" src="../../images/ilogout.png" /></a></li>
        </ul>
    </ul>
</div>

<!-- START CONTENT -->
<div id="page_container">
    <button type="button" class="button" id="add_record">Add new book</button>
    <table class="datatable" id="table_companies">
        <thead>
            <tr>
                <th>CALL_ID</th><th>TITLE</th><th>ENG TITLE</th><th>Chn Author</th><th>Eng Author</th>
                <th>Subject</th><th>Publisher</th><th>Actions</th>
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
        <form class="form add" id="form_company" data-id="" novalidate>
        <table>
            <tr>
                <td>
                    <div class="input_container2">
                        <label for="company_name">Call ID:<span class="required">*</span></label>
                        <div class="field_container2">
                            <input type="text" class="text" name="CALL_ID" id="CALL_ID" value="" required autofocus />
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input_container">
                        <label for="company_name" style="font-weight: bold; color: red">Status: </label>
                        <div class="field_container">
                            <select id="CHECKED_OUT" name="CHECKED_OUT" disabled="true" value="0" >
                                <option value="1">Checked Out</option>
                                <option value="0" selected="true">Available</option>
                            </select>
                        </div>
                    </div>
                </td>
                <td>&nbsp;</td>

            </tr>
            <tr>
                <td>
                    <div class="input_container2">
                        <label for="company_name">Title: </label>
                        <div class="field_container2">
                            <input type="text" class="text" name="CHN_NAME" id="CHN_NAME" value="" />
                        </div>
                    </div>
                </td>
                <td colspan="2">
                    <div class="input_container2">
                        <label for="company_name">Eng.Title: </label>
                        <div class="field_container2">
                            <input type="text" class="text" name="ENG_NAME" id="ENG_NAME" value="" />
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="input_container2">
                        <label for="company_name"><span>*</span>Author: </label>
                        <div class="field_container2">
                            <input hidden="true" type="text" class="text" name="AUTHOR_ID" id="AUTHOR_ID" value="" />
                            <input type="text" class="ui-autocomplete-input" name="AUTHOR_NAME" id="AUTHOR_NAME" value="" />
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input_container">
                        <label for="company_name">Edtion: </label>
                        <div class="field_container">
                            <input type="text" class="text" name="EDITION" id="EDITION" value="" />
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input_container">
                        <label for="company_name">Qty: </label>
                        <div class="field_container">
                            <input type="text" class="text" name="QTY" id="QTY" value="" />
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="input_container2">
                        <label for="company_name"><span>*</span>Eng.Author: </label>
                        <div class="field_container2">
                            <input hidden="true" type="text" class="text" name="ENGLISH_AUTHOR_ID" id="ENGLISH_AUTHOR_ID" value="" />
                            <input type="text" class="ui-autocomplete-input" name="ENGLISH_AUTHOR_NAME" id="ENGLISH_AUTHOR_NAME" value="" />
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input_container">
                        <label for="company_name">Reserved:</label>
                        <div class="field_container">
                            <select id="RESERVED" name="RESERVED" value="0">
                            	<option value="1">Yes</option>
                            	<option value="0" selected="true">No</option>
                            </select>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input_container">
                        <label for="company_name">Reference: </label>
                        <div class="field_container">
                            <select id="REFERENCE" name="REFERENCE" value="0">
                            	<option value="1">Yes</option>
                            	<option value="0" selected="true">No</option>
                            </select>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="input_container2">
                        <label for="company_name"><span>*</span>Translator: </label>
                        <div class="field_container2">
                            <input hidden="true" type="text" class="text" name="TRANSLATOR_ID" id="TRANSLATOR_ID" value="" />
                            <input type="text" class="ui-autocomplete-input" name="TRANSLATOR_NAME" id="TRANSLATOR_NAME" value="" />
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input_container">
                        <label for="company_name">ISBN:</label>
                        <div class="field_container">
                            <input type="text" class="text" name="ISBN" id="ISBN" value="" />
                        </div>
                    </div>
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                    <div class="input_container2">
                        <label for="company_name"><span>*</span>Publisher: </label>
                        <div class="field_container2">
                            <input hidden="true" type="text" class="text" name="PUBLISHER_ID" id="PUBLISHER_ID" value="" />
                            <input type="text" class="ui-autocomplete-input" name="PUBLISHER_NAME" id="PUBLISHER_NAME" value="" />
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input_container">
                        <label for="company_name">Pub.City: </label>
                        <div class="field_container">
                            <input type="text" class="text" name="PUBLISHED_CITY" id="PUBLISHED_CITY" value="" />
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input_container">
                        <label for="company_name">Pub.Year: </label>
                        <div class="field_container">
                            <input type="text" class="text" name="PUBLISHED_YEAR" id="PUBLISHED_YEAR" value="" />
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="input_container2">
                        <label for="company_name"><span>*</span>Subject: </label>
                        <div class="field_container2">
                            <input hidden="true" type="text" class="text" name="SUBJECT_ID" id="SUBJECT_ID" value="" />
                            <input type="text" class="ui-autocomplete-input" name="SUBJECT_NAME" id="SUBJECT_NAME" value="" />
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input_container">
                        <label for="company_name">Subject2: </label>
                        <div class="field_container">
                            <input type="text" class="text" name="SUBJECTII" id="SUBJECTII" value="" />
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input_container">
                        <label for="company_name">Subject3: </label>
                        <div class="field_container">
                            <input type="text" class="text" name="SUBJECTIII" id="SUBJECTIII" value="" />
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="input_container2 ">
                        <label for="company_name">Note: </label>
                        <div class="field_container2">
                            <input type="text" class="text" name="NOTE1" id="NOTE1" value="" />
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input_container">
                        <label for="company_name">Note2: </label>
                        <div class="field_container">
                            <input type="text" class="text" name="NOTE2" id="NOTE2" value="" />
                        </div>
                    </div>
                </td>

            </tr>
            <tr>
                <td colspan="2">
                    <div class="button_container">
                        <button id="add" type="submit">Add BOOK</button>
                    </div>
                </td>
            </tr>
        </table>
        </form>
        <p style="color: red">For Author/Eng. Author/Translator/Publisher/Subject fields, you must pick it from the drop-down-list, which will appear as you are typing</p>
  	</div>
</div>
<!--  *END* CONTENT -->

<?php
include($_SERVER['DOCUMENT_ROOT'].'/library/admin/scripts/footer.php');
?>
