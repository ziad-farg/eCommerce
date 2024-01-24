<?php
    ob_start();
    session_start();
    $titlt = 'item';
    include 'init.php';
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
    $stmt = $connect->prepare("SELECT
                                    items.*, categories.Name AS CatName, users.UserName
                                FROM
                                    items
                                INNER JOIN
                                    categories
                                ON
                                    categories.ID = items.Cat_ID
                                INNER JOIN
                                    users
                                ON
                                    users.UserID = items.Member_ID
                                WHERE 
                                    Item_ID = ?
                                AND
                                    Approved = 1");
    $stmt->execute([$itemid]);
    $item = $stmt->fetch();
    $count = $stmt->rowCount();
    if ($count > 0) {    
        ?>
        <h1 class="text-center"><?php echo $item['Name'] ?></h1>
        <div class="main-item">
            <div class="container">
                <div class="row">
                    <div class="col-md-2">
                        <?php
                            if (empty($item['Image'])) {
                                echo '<img src="admin/upload/item/default.png" alt="image">';
                            } else {
                                echo '<img src="admin/upload/item/'.$item['Image'].'" alt="image">';
                            }
                        ?>
                    </div>
                    <div class="col-md-10 item-info">
                        <ul class="list-unstyled">
                            <li><h2><?php echo $item['Name']; ?></h2></li>
                            <li><?php echo $item['Description']; ?></li>
                            <li><span>Price</span>: $<?php echo $item['Price'] ?></li>
                            <li><span>Made In</span>: <?php echo $item['Country_Made'] ?></li>
                            <li><span>Category</span>: <a href="categories.php?catid=<?php echo $item['Cat_ID']; ?>"><?php echo $item['CatName'] ?></a></li>
                            <li><span>Added By</span>: <a href="#"><?php echo $item['UserName'] ?></a></li>
                            <li><span>tags</span>: 
                                <?php
                                    $tags = str_replace(' ', '', explode(',', $item['tags']));
                                    foreach ($tags as $tag) {
                                        echo '<a href="tags.php?tagname='.strtolower($tag).'"> ' .$tag. ' </a> <b>|</b>';
                                    }
                                ?>
                            </li>
                        </ul>
                    </div>
                </div>
                <hr>
                <!-- Start comment session -->
                <div class="row">
                    <div class="offset-md-3">
                        <?php 
                            // check if user exist show the form else show the msg login or regeister
                            if (isset($_SESSION['user'])) {
                                if ($item['UserName'] != $_SESSION['user']) {    
                                    ?>
                                    <div class="comment">
                                        <!-- form text area -->
                                        <!-- we add concat about php self becase the page request is items.php and that will show error msg -->
                                        <form action="<?php $_SERVER['PHP_SELF'] . '?itemid=' . $item['Item_ID'] ?>" method="POST">
                                            <label for="comment">Comment</label>
                                            <textarea name="comment" id="comment" class="form-control mb-3" required></textarea>
                                            <input type="submit" value="Add Comment" class="btn btn-primary mb-2">
                                        </form>
                                        
                                        <?php 
                                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                                $comment    = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                                                $itemid     = $item['Item_ID'];
                                                $userid     = $_SESSION['userid'];
                                                if (!empty($comment)) {
                                                    $stmt = $connect->prepare("INSERT INTO comments (Comments, `Status`, C_Date, Item_ID, `User_ID`)
                                                                                VALUES (:zcomment, 0, now(), :zitem, :zuser)");
                                                    $stmt->execute([
                                                        'zcomment'  => $comment,
                                                        'zitem'     => $itemid,
                                                        'zuser'     => $userid
                                                    ]);
                                                    $count = $stmt->rowCount();
                                                    if ($count > 0) {
                                                    echo '<div class="alert alert-success">Comment Added</div>';
                                                    }
                                                } else {
                                                    echo '<div class="alert alert-danger">The Comment Faild Is Empty</div>';
                                                }
                                            }
                                        ?>
                                    </div>
                                    <?php 
                                    } 
                                    ?>
                    </div>
                </div>
                <hr>
                                    <?php
                                        $stmt = $connect->prepare("SELECT
                                                                        comments.*, users.UserName, users.avatar
                                                                    FROM 
                                                                        comments
                                                                    INNER JOIN
                                                                        users 
                                                                    ON 
                                                                        users.UserID = comments.User_ID
                                                                    WHERE 
                                                                        Item_ID = ?
                                                                    AND 
                                                                        `Status` = 1
                                                                    ORDER BY
                                                                        C_ID DESC");
                                        $stmt->execute([$item['Item_ID']]);
                                        $comments = $stmt->fetchAll();
                                        foreach($comments as $comment){
                                    
                                            echo '<div class="comment-box">';
                                                echo '<div class="row">';
                                                    echo '<div class="col-sm-2 text-center mb-3">';
                                                    if (empty($comment['avatar'])) {
                                                        echo '<img src="admin/upload/avatar/default.png" alt="image" class="rounded-circle">';
                                                    } else {
                                                        echo '<img src="admin/upload/avatar/'.$comment['avatar'].'" alt="image" class="rounded-circle">';
                                                    }
                                                        echo '<div class="com-user">';
                                                            echo $comment['UserName'];
                                                        echo '</div>';
                                                    echo '</div>';
                                                    echo '<div class="col-sm-10">';
                                                        echo '<p class="lead p-2">';
                                                            echo $comment['Comments'];
                                                        echo '</p>';
                                                    echo '</div>';
                                                echo '</div>';
                                            echo '</div>';
                                        } 
                                    ?>  
                            <!-- End comment session -->
                        </div>
                    </div>
                    <?php
        } else {
            echo '<div class="alert alert-info my-5 ">If You Want Add Comment Must Be <a href="login.php">Login</a> Or <a href="login.php">Register</a> In Site</div>';
        }
    } else {
        echo '<div class="container">';
            echo '<div class="alert alert-danger my-5">There Is No Such ID Or This Item Isn\'t Approved Or You Browse This Page Directly</div>';
        echo '</div>';
    }
    include $tmp . 'footer.php';
    ob_end_flush();
?>