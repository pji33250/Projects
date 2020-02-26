
<?php
require_once ( $_SERVER['DOCUMENT_ROOT'].'/library/lib/sqlapis.php');
include ($_SERVER['DOCUMENT_ROOT'].'/library/snippet/pageheader.php');

ob_start();
$catetory = $term = $id = '';
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if (isset($_POST["category"])) {$catetory = $_POST["category"]; }
	if (isset($_POST["term"])) { $term = $_POST["term"]; }
}
else if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    if (isset($_GET['category'])) { $catetory = $_GET['category']; }
    if (isset($_GET['id'])) { $id = $_GET['id']; }
}
?>

<style>
    table.datatable td { padding: 6px; }
</style>

<!-- START CONTENT -->
<div id="page_container">
    <table class="datatable">
    <thead>
        <tr>
            <th>CALL_ID</th>
            <th>TITLE</th>
            <th>TITLE(Eng)</th>
            <th>AUTHOR</th>
            <th>Author(Eng)</th>
            <th>Subject</th>
            <th>Publisher</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $books = '';
    	if ($catetory && $term) {
            $books = fetchSearchBooksByTerm($catetory, $term);
        }
        else if ($catetory && $id) {
            $books = fetchSearchBooksById($catetory, $id);
        }

        if ($books)
        {
            $num = mysqli_num_rows($books);
            //print_r ($num);
            if ($num > 0)
            {
                foreach ($books as $book)
                {
    	?>

        <!-- DETAIL -->
        <tr>
            <td><a href="bookdetail.php?id=<?php echo $book['ID'] ?>"><?php echo $book['CALL_ID'] ?></td>
            <td><?php echo $book['CHN_NAME'] ?></td>
            <td><?php echo $book['ENG_NAME'] ?></td>
            <td><?php echo $book['ANAME'] ?></td>
            <td><?php echo $book['ENAME'] ?></td>
            <td><?php echo $book['SNAME'] ?></td>
            <td><?php echo $book['PNAME'] ?></td>
            <td><?php echo (($book['CHECKED_OUT'] == 0) ? 'Avail.' : 'CheckedOut') ?></td>
        </tr>
        <!-- *END* DETAIL -->

        <?php
                }
            }
        }
        ?>
    </tbody>
    </table>
</div>


<?php
include($_SERVER['DOCUMENT_ROOT'].'/library/snippet/pagefooter.php');
?>
