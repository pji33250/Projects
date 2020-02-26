<?php
require_once ( $_SERVER['DOCUMENT_ROOT'].'/library/lib/sqlapis.php');
include($_SERVER['DOCUMENT_ROOT'].'/library/snippet/pageheader.php');

$bookid='';
if (isset($_GET['id'])) { $bookid = $_GET['id']; }
?>

<style>


#page_container_mid label {
    width: 100px;
    float: right;
    font-weight: bold;
    font-size: 1rem;
    line-height: 22px;
    text-align: right;
}
#page_container_mid p {
    background-color: #f9f9f9;
    line-height: 30px;
    color: #666;
    padding: 0 0 0 10px;
    border: 1px solid #ccc;
    -webkit-box-sizing: border-box;
    -moz-box-sizing:    border-box;
    box-sizing:         border-box;
    -webkit-border-radius: 6px;
    -moz-border-radius:    6px;
    border-radius:         6px;
}

table td {
  font-weight: normal;
  text-align: left;
  vertical-align: middle;
  padding: 6px 2px 6px 2px;
}
</style>

<!-- START CONTENT -->
<?php
if ($bookid)
{
    $book = fetchBookByID($bookid);
    if ($book)
    {
        echo "<br>";
?>

<!-- DETAIL -->
<div id="page_container_mid" >
    <table>
		<tr>
			<td><label>Call ID:</label></td>
            <td><p><?php echo $book['CALL_ID'] ?></p></td>
            <td><label style="color: #f70;">Status:</label></td>
            <td><p><?php echo (($book['CHECKED_OUT'] == 0) ? 'Available' : 'Checked-Out') ?></p></td>
            <td>&nbsp;</td><td>&nbsp;</td>
		</tr>
		<tr>
            <td><label>Title:</label></td>
            <td><p><?php echo $book['CHN_NAME'] ?></p></td>
            <td><label>Eng.Title:</label></td>
            <td colspan="3"><p><?php echo $book['ENG_NAME'] ?></p></td>
		</tr>
		<tr>
            <td><label>Author:</label></td>
            <td><p><?php echo $book['ANAME'] ?></p></td>
            <td><label>Eng.Author:</label></td>
            <td><p><?php echo $book['ENAME'] ?></p></td>
            <td><label>Translator:</label></td>
            <td><p><?php echo $book['TNAME'] ?></p></td>
		</tr>
        <tr>
            <td><label>ISBN:</label></td>
            <td><p><?php echo $book['ISBN'] ?></p></td>
            <td><label>Edtion:</label></td>
            <td><p><?php echo $book['EDITION'] ?></p></td>
            <td><label>Qty:</label></td>
            <td><p><?php echo $book['QTY'] ?></p></td>
		</tr>
		<tr>
            <td><label>Reserved:</label></td>
            <td><p><?php echo (($book['RESERVED'] == 0) ? 'No' : 'Yes') ?></p></td>
            <td><label>Reference:</label></td>
            <td><p><?php echo (($book['REFERENCE'] == 0) ? 'No' : 'Yes') ?></p></td>
		</tr>
		<tr>
            <td><label>Publisher:</label></td>
            <td><p><?php echo $book['PNAME'] ?></p></td>
            <td><label>Pub.City:</label></td>
            <td><p><?php echo $book['PUBLISHED_CITY'] ?></p></td>
            <td><label>Pub.Year:</label></td>
            <td><p><?php echo $book['PUBLISHED_YEAR'] ?></p></td>
		</tr>
		<tr>
            <td><label>Subject:</label></td>
            <td><p><?php echo $book['SNAME'] ?></p></td>
            <td><label>Subject2:</label></td>
            <td><p><?php echo $book['SUBJECTII'] ?></p></td>
            <td><label>Subject3:</label></td>
            <td><p><?php echo $book['SUBJECTIII'] ?></p></td>
		</tr>
		<tr>
            <td><label>Note:</label></td>
            <td><p><?php echo $book['NOTE1'] ?></p></td>
            <td><label>Note2:</label></td>
            <td><p><?php echo $book['NOTE2'] ?></p></td>
            <td>&nbsp;</td><td>&nbsp;</td>
		</tr>
        <tr>
            <td><label>Added Date:</label></td>
            <td><p>
                <script type="text/javascript">document.write(ToLocalDate("<?php echo $book['ADDED_DATE'] ?>"))</script>
            </p></td>
            <td><label>Upd.Date:</label></td>
            <td><p>
                <script type="text/javascript">document.write(ToLocalDateTime("<?php echo $book['UPDATED_DATE'] ?>"))</script>
            </p></td>
            <td>&nbsp;</td><td>&nbsp;</td>
		</tr>
	</table>
</div>
<!-- *END* DETAIL -->
<?php
    }
}
?>
<!--  *END* CONTENT -->

<?php
include($_SERVER['DOCUMENT_ROOT'].'/library/snippet/pagefooter.php');
?>
