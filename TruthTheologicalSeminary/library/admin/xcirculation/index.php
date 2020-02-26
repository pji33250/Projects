<?php
include('../login/session.php');
include($_SERVER['DOCUMENT_ROOT'].'/library/admin/scripts/header.php');
?>

<!-- <link rel="stylesheet" type="text/css" href="mystyles.css" /> -->

<div id="tabs">
	<ul>
		<ul>
			<li class="selected"><a href="index.php"><img alt="Circulation" src="../../images/circulation.png" />Circulation</a></li>
			<li><a href="../user"><img alt="User" src="../../images/users.svg" />User</a></li>
			<li><a href="../book"><img alt="Book" src="../../images/book.jpeg" />Book</a></li>
      <li><a href="../author"><img alt="Author" src="../../images/author.png" />Author</a></li>
      <li><a href="../publisher"><img alt="Publisher" src="../../images/publisher.jpeg" />Publisher</a></li>
			<li><a href="../subject"><img alt="Subject" src="../../images/subjects.png" />Subject</a></li>
			<li style="float: right"><a href="../login/logout.php"><img alt="Logout" src="../../images/logout.png" />Logout</a></li>
		</ul>
	</ul>
</div>

<!-- START CONTENT -->
<div id="page_container">
  <button type="button" class="button" id="add_record">Add New User</button>
  <table class="datatable" id="table_companies">
    <thead>
      <tr>
				<th>CALL ID</th>
				<th>TITLE</th>
	      <th>PID</th>
	      <th>NAME</th>
	      <th>CHECK DATE</th>
	      <th>DUE DATE</th>
				<th>RETURN DATE</th>
	      <th>Actions</th>
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
	              <input type="text" class="text" name="PID" id="PID" value="" required>
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
				<tr>
	        <td colspan="2">
	          <div class="input_container">
	            <label for="company_name">Title: </label>
	            <div class="field_container">
	              <input type="text" class="text" name="TITLE" id="TITLE" value="" >
	            </div>
	          </div>
	        </td>
	      </tr>
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
