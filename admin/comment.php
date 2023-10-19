<?php

    ob_start();
    session_start();
    // check if exsit session of username
    if ($_SESSION['username']) {
        // title page
        $title = 'Comment';
        // include header
        include 'init.php';
        // check requestMethod is get['do'] to open the browse
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if ($do == 'Manage') { // Manage Comment
            $stmt = $connect->prepare("SELECT
                                            comments.*, items.Name AS Item_Name , users.UserName
                                        FROM
                                            Comments
                                        INNER JOIN items ON 
                                            items.Item_ID = comments.Item_ID
                                        INNER JOIN users ON 
                                            users.UserID = comments.User_ID
                                        ORDER BY
                                            C_ID DESC");
            $stmt->execute();
            $comments = $stmt->fetchAll();
            if(!empty($comments)){

?>
                <h1 class="text-center">Manage Comment</h1>
                <div class="container">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered text-center">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="col-1">#ID</th>
                                    <th class="col-3">Comments</th>
                                    <th class="col-2">Date Regiester</th>
                                    <th class="col-1">Item Name</th>
                                    <th class="col-1">User</th>
                                    <th class="col-3">Control</th>
                                </tr>
                            </thead>

                            <tbody>
<?php
                                foreach ($comments as $comment) {
                                    echo '<tr>';
                                        echo '<td>' . $comment['C_ID'] . '</td>';
                                        echo '<td>' . $comment['Comments'] . '</td>';
                                        echo '<td>' . $comment['C_Date'] . '</td>';
                                        echo '<td>' . $comment['Item_Name'] . '</td>';
                                        echo '<td>' . $comment['UserName'] . '</td>';
                                        echo '<td>';
                                            echo '<a href="comment.php?do=Edit&comid=' . $comment['C_ID'] . '" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>';
                                            echo ' <a href="comment.php?do=Delete&comid=' . $comment['C_ID'] . '" class="btn btn-danger confirm"><i class="fa fa-trash-alt"></i> Delete</a>';
                                            if ($comment['Status'] == 0) {
                                                echo ' <a href="comment.php?do=Approve&comid=' . $comment['C_ID'] . '" class="btn btn-info"><i class="fa fa-check"></i> Approve</a>';
                                            }
                                        echo '</td>';
                                    echo '</tr>';
                                }
?>
                        </tbody>
                    </table>
                </div>
            </div>
<?php
            } else {
                // Print Msg There's Not users To Show
                echo '<div class="container py-5">';
                    echo '<div class="alert alert-info">There\'s Not Comment To Show</div>';
                echo '</div>';
            }
?>
<?php
    } elseif ($do == 'Edit'){ //Edit Comments

        // get comid from requestMethod get
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
        // check if exsit comment in database by id comment
        $stmt = $connect->prepare("SELECT * FROM comments WHERE C_ID = ?");
        $stmt->execute([$comid]);
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0) {
?>
            <h1 class="text-center">Edit Comment</h1>
            <div class="container">
                <div class="row">
                    <form class="edit-page my-0  w-75 " action="?do=Update" method="POST">

                        <!-- Start fiald ID [Hidden] -->
                        <input type="hidden" name="comid" value="<?php echo $comid; ?>">
                        <!-- End fiald ID [Hidden] -->

                        <!-- Start comment Faild -->
                        <div class="form-group row ">
                            <label for="comment" class="col-sm offset-1 col-form-label">Comment</label>
                            <div class="col-sm-9 ">
                                <textarea name="comment" id="comment" class="form-control" required><?php echo $row['Comments']; ?></textarea>
                            </div>
                        </div>
                        <!-- End comment Faild -->

                        
                            <div class="form-group row  ">
                                <div class="col-sm-3 offset-sm-3 ">
                                    <input type="submit" value="Save" class="btn btn-primary">
                                </div>
                            </div>
                    </form>
                </div>
            </div>
<?php
        } else {
            echo '<div class="container py-5">';
                $theMsg = '<div class="alert alert-danger"> You Can\'t Access this Page Directly </div>';
                redirectHome($theMsg);
            echo '</div>';
        }
    } elseif($do == 'Update') { // Update Comments
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $comid = $_POST['comid'];
            $comment = $_POST['comment'];

            if (!empty($comment)) {
                $stmt = $connect->prepare("UPDATE comments SET Comments = ? WHERE C_ID = ? ");
                $stmt->execute([$comment, $comid]);
                $count = $stmt->rowCount();
                if ($count > 0) {
                    echo '<h1 class="text-center">Update Comment</h1>';
                    echo '<div class="container">';
                        $theMsg = '<div class="alert alert-success"> You Add '. $count . ' Record</div>';
                        redirectHome($theMsg, 'back');
                    echo '</div>';
                } 
            } else {
                echo '<div class="container py-5">';
                    $theMsg = '<div class="alert alert-danger"> You Must Full The Faild </div>';
                    redirectHome($theMsg);
                echo '</div>';
            }
        } else {
            echo '<div class="container py-5">';
                $theMsg = '<div class="alert alert-danger"> You Can\'t Access this Page Directly </div>';
                redirectHome($theMsg);
            echo '</div>';
        }
    } elseif ($do == 'Delete'){
        // get comid from requestMethod get
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
        // check if exsit comment in database by id comment
        $check = checkItem('C_ID', 'comments', $comid);

        if ($check > 0) {
            $stmt = $connect->prepare("DELETE FROM comments WHERE C_ID = ?");
            $stmt->execute([$comid]);
            $count = $stmt->rowCount();
            echo '<h1 class="text-center">Delete Comment</h1>';
                echo '<div class="container">';
                $theMsg = '<div class="alert alert-success"> You Add '. $count . ' Record</div>';
                redirectHome($theMsg, 'back');
            echo '</div>';
        } else {
            echo '<div class="container py-5">';
                $theMsg = '<div class="alert alert-danger"> You Can\'t Access this Page Directly </div>';
                redirectHome($theMsg);
            echo '</div>';
        }
    } elseif ($do == 'Approve') {
        // get comid from requestMethod get
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
        // check if exsit comment in database by id comment
        $check = checkItem('C_ID', 'comments', $comid);

        if ($check > 0) {
            $stmt = $connect->prepare("UPDATE comments SET `Status` = 1 WHERE C_ID = ?");
            $stmt->execute([$comid]);
            $count = $stmt->rowCount();
            echo '<h1 class="text-center">Approved Comment</h1>';
                echo '<div class="container">';
                $theMsg = '<div class="alert alert-success"> You Add '. $count . ' Record</div>';
                redirectHome($theMsg, 'back');
            echo '</div>';
        } else {
            echo '<div class="container py-5">';
                $theMsg = '<div class="alert alert-danger"> You Can\'t Access this Page Directly </div>';
                redirectHome($theMsg);
            echo '</div>';
        }
    }
    // include footer
    include $tmp . 'footer.php';
} else {
    header('LOCATION: index.php');
}

ob_end_flush();
?>