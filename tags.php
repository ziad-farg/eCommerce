<?php
    ob_start();
    session_start();
    $title = 'tags';
    include 'init.php';
    if (isset($_GET['tagname'])) {
        $tagname = $_GET['tagname'];
        echo '<h1 class="text-center">' . $tagname . '</h1>';
        $items = getAllFrom('*', 'items', "WHERE tags LIKE '%$tagname%'", 'Item_ID');
        echo '<div class="container">';
            echo '<div class="row">';
                foreach ($items as $item) {
                    echo '<div class="col-sm-6 col-md-3">';
                        echo '<div class="card card-home">';
                            echo '<span class="price-tag">$'.$item['Price'].'</span>';
                            if (empty($item['Image'])) {
                                echo '<img src="admin/upload/item/default.png" alt="image">';
                            } else {
                                echo '<img src="admin/upload/item/'.$item['Image'].'" alt="image">';
                            }
                            echo '<div class="card-body">';
                                    echo '<h5 class="card-title"><a href="items.php?itemid='.$item['Item_ID'].'">'.$item['Name'].'</a></h5>';
                                    echo '<p class="card-text description">'.$item['Description'].'</p>';
                                    echo '<p class="card-text m-0 p-0 date">'.$item['Add_Date'].'</p>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
            echo '</div>';
        echo '</div>';
        ?>
        <?php
    } else {
        echo '<div class="container py-5">';
            echo '<div class="alert alert-danger">You Ca\'nt acess this is page becasue this is page don\'t have tagname</div>';
        echo '</div>';
    }
    ob_end_flush();
?>