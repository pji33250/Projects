<?php
include($_SERVER['DOCUMENT_ROOT'].'/library/snippet/pageheader.php');
?>

<!-- CONTENT -->
<div id="main" class="signInBox">
    <form action="/library/search/booklist.php" method="POST">
        <table>
            <tbody>
                <tr>
                    <!-- <td><label>&nbsp;</label></td> -->
                    <td colspan="4"><h1>中文圖書查詢系統</h1></td>
                </tr>
                <tr>
                    <td><label>Browse:</label></td>
                    <td>
                        <select name="category">
                            <option value="title" selected="true">Title</option>
                            <option value="author">Author</option>
                            <option value="subject">Subject</option>
                            <option value="publisher">Publisher</option>
                            <option value="callid">Call ID</option>
                        </select>
                    </td>
                    <td><label>For</label></td>
                    <td><input type="text" id="term" name="term" value="" autofocus /></td>
                    <td><input type="submit" id="submit" name="submit" value="Search" /></td>
                </tr>
            </tbody>
        </table>
    <form>
</div>

<?php
include($_SERVER['DOCUMENT_ROOT'].'/library/snippet/pagefooter.php');
?>
