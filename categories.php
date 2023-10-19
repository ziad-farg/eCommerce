<?php 
    ob_start();
    session_start();
    $title = 'category';
    include 'init.php';
    if (isset($_SESSION['user'])) {
            
    } 
    // we use this is query to get cateogry name
    if (isset($_GET['catid']) && is_numeric($_GET['catid'])) {
        $catid = intval($_GET['catid']);
        $stmt = $connect->prepare("SELECT * FROM categories WHERE ID = ?");
        $stmt->execute([$catid]);
        $cat = $stmt->fetch();
    }
?>
<h1 class="text-center"><?php echo $cat['Name'] ;?></h1> <!-- category name -->
<div class="container">
    <div class="row">
        <?php
            // check if found catid
            if (isset($_GET['catid']) && is_numeric($_GET['catid'])) {
                // get catid to get item in all gategories
                $catid = intval($_GET['catid']);
                // get items by catid
                $items = getAllFrom('*', 'items', "WHERE Cat_ID = {$catid} AND Approved = 1", 'Item_ID', 'ASC');
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
            } else {
                echo '<div class="alert alert-danger col-12">You Can\'t Access this Page Directly</div>';
            }   
        ?>
    </div>
</div>

<?php 
    include $tmp .'footer.php';
    ob_end_flush();
?>