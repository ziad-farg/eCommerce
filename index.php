<?php
    ob_start();
    session_start();
    $title = 'Home Page';
    include 'init.php';
?>
<div class="home-item">
    <div class="container py-5">
        <div class="row ">
            <?php
                $items = getAllFrom('*', 'items', 'WHERE Approved = 1','Item_ID');
                foreach ($items as $item) {
                    echo '<div class="col-sm-6 col-md-3 my-3">';
                        echo '<div class="card card-home">';
                            echo '<span class="card-text price-tag">';
                            echo '$<span class="live-price">'.$item['Price'].'</span>';
                            echo '</span>';
                            if (empty($item['Image'])) {
                                echo '<img src="admin/upload/item/default.png" alt="image">';
                            } else {
                                echo '<img src="admin/upload/item/'.$item['Image'].'" alt="image">';
                            }
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title"><a href="items.php?itemid='.$item['Item_ID'].'">'.$item['Name'].'</a></h5>';
                            echo '<p class="card-text m-0 p-0 description">'.$item['Description'].'</p>';
                            echo '<p class="card-text m-0 p-0 date">'.$item['Add_Date'].'</p>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
            ?>
        </div>
    </div>
</div>
<?php    
    include $tmp . 'footer.php';
    ob_end_flush();
?>