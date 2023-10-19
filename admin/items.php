<?php
    /////////////////////////////////////////////////////////////////
    // this Is Page Item For [ Mange | Edit | Add | Delete Items ] //
    /////////////////////////////////////////////////////////////////
    ob_start();
    session_start();
    // page title
    $title = 'Items';
    // is exists sessin
    if (isset($_SESSION['username'])) {
        include 'init.php';
        // check if exist [do request] from url => get request [do] else [direct to manage item] 
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        if ($do == 'Manage') { // manage items
            // check if exist [page request] from url => get request [page] and addtion to query the database
            $query = isset($_GET['page']) && $_GET['page'] == 'Aproved' ? 'AND Approved = 0' : ''; 
            $stmt = $connect->prepare("SELECT 
                                            items.*,
                                            categories.Name AS Cat_Name,
                                            users.UserName
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
                                        $query
                                        ORDER BY
                                            Item_ID DESC ");
            $stmt->execute();
            $items = $stmt->fetchAll();
            if(!empty($items)){
                ?>
                <h1 class="text-center">Manage Item</h1>
                <div class="item-table">
                    <div class="container">
                        <div class="row">
                            <table class="table table-bordered text-center">
                                <thead class="thead-dark">
                                    <tr class="">
                                        <th class="col-1">#ID</th>
                                        <th class="col-1">Image</th>
                                        <th class="col-2">Name</th>
                                        <th class="col-2">Description</th>
                                        <th class="col-1">Price</th>
                                        <th class="col-2">Registerd Date</th>
                                        <th class="col-1">Category</th>
                                        <th class="col-1">Member</th>
                                        <th class="col-1">Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        // put data in database into my table that where in Manage page
                                        foreach ($items as $item) {
                                            echo '<tr>';
                                                echo '<td class="align-middle">' . $item['Item_ID'] . '</td>';
                                                echo '<td class="align-middle">';
                                                    if (empty($item['Image'])) {
                                                        echo '<img src="upload/item/default.png" alt="">';
                                                    } else {
                                                        echo '<img src="upload/item/' . $item['Image'] . '" alt="">';
                                                    }
                                                echo'</td>';
                                                echo '<td class="align-middle">' . $item['Name'] . '</td>';
                                                echo '<td class="align-middle">' . $item['Description'] . '</td>';
                                                echo '<td class="align-middle">' . $item['Price'] . '</td>';
                                                echo '<td class="align-middle">' . $item['Add_Date'] . '</td>';
                                                echo '<td class="align-middle">' . $item['Cat_Name'] . '</td>';
                                                echo '<td class="align-middle">' . $item['UserName'] . '</td>';
                                                echo '<td>
                                                        <a class="btn btn-success mb-1" href="items.php?do=Edit&itemid=' . $item['Item_ID'] . '" role="button"><i class="fas fa-edit"></i> Edit</a>
                                                        <a class="btn btn-danger confirm mb-1" href="items.php?do=Delete&itemid=' . $item['Item_ID'] . '" role="button"><i class="fas fa-trash-alt"></i> Remove</a>';
                                                        if ($item['Approved'] == 0) {
                                                        echo ' <a class="btn btn-info" href="items.php?do=Approved&itemid=' . $item['Item_ID'] . '" role="button"><i class="fas fa-check"></i> Approved</a>';
                                                        }
                                                        '</td>';
                                            echo '</tr>';
                                        }
                                    ?>
                                </tbody>
                            </table>
                        <a href="?do=Add" class="btn btn-primary"><i class="fas fa-plus"></i> New Item</a>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                // Print Msg There's Not items To Show
                echo '<div class="container py-5">';
                    echo '<div class="alert alert-info">There\'s Not items To Show</div>';
                    echo '<a href="?do=Add" class="btn btn-primary"><i class="fas fa-plus"></i> New Item</a>';
                echo '</div>';
            }
        } elseif ($do == 'Add') { // Add Page
            ?>
            <h1 class="text-center">Add Items</h1>
            <div class="container">
                <form class="my-0 w-75 " action="?do=Insert" method="POST" enctype="multipart/form-data">
                    <!-- Start Name Faild -->
                    <div class="form-group row">
                        <label for="name" class="col-sm offset-1 col-form-label">Name</label>
                        <div class="col-sm-9 ">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Item Name" required>
                        </div>
                    </div>
                    <!-- End Name Faild -->
                    <!-- Start Description Faild -->
                    <div class="form-group row">
                        <label for="description" class="col-sm offset-1 col-form-label">Description</label>
                        <div class="col-sm-9 ">
                            <input type="text" class="form-control " name="description" placeholder="Add Item Description" required>
                        </div>
                    </div>
                    <!-- End Description Faild -->
                    <!-- Start Price Faild -->
                    <div class="form-group row">
                        <label for="price" class="col-sm offset-1 col-form-label">Price</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="price" id="price" placeholder="Add The Item Price" required>
                        </div>
                    </div>
                    <!-- End Price Faild -->
                    <!-- Start Country Faild -->
                    <div class="form-group row">
                        <label for="country" class="col-sm offset-1 col-form-label">Country Made</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="country" id="country" placeholder="The Country Made">
                        </div>
                    </div>
                    <!-- End Country Faild -->
                    <!-- Start image Faild -->
                    <div class="form-group row">
                        <label for="image" class="col-sm offset-1 col-form-label">Image</label>
                        <div class="col-sm-9">
                            <input type="file" class="form-control" name="image" id="image" placeholder="Enter Iamge Item">
                        </div>
                    </div>
                    <!-- End image Faild -->
                    <!-- Start Status Faild -->
                    <div class="form-group row">
                        <label for="status" class="col-sm offset-1 col-form-label">Status</label>
                        <div class="col-sm-9">
                            <select name="status" required>
                                <option value="0">...</option>
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="3">Used</option>
                                <option value="4">Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status Faild -->
                    <!-- Start Member Faild -->
                    <div class="form-group row">
                        <label for="status" class="col-sm offset-1 col-form-label">Member</label>
                        <div class="col-sm-9">
                            <select name="member" required>
                                <option value="0">...</option>
                                    <?php
                                        // get users to select any of user add the new item
                                        $users = getAllFrom('*', 'users', '', 'UserID');
                                        foreach($users as $user) {
                                            echo '<option value="' . $user['UserID'] . '">'. $user['UserName'] . '</option>';
                                        }
                                    ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Member Faild -->
                    <!-- Start Category Faild -->
                    <div class="form-group row">
                        <label for="status" class="col-sm offset-1 col-form-label">Category</label>
                        <div class="col-sm-9">
                            <select name="Category" required>
                                <option value="0">...</option>
                                    <?php
                                        // get main gategories to select any item that added
                                        $cats = getAllFrom('*', 'categories', 'WHERE parent = 0', 'ID');
                                        foreach($cats as $cat) {
                                            echo '<option value="' . $cat['ID'] . '">'. $cat['Name'] . '</option>';
                                            // get sub gategories and put this under all Main categories
                                            $childs =getAllFrom('*', 'categories', "WHERE parent = {$cat['ID']}", 'ID');
                                            foreach ($childs as $child) {
                                                echo '<option value="' . $child['ID'] . '"> --- '. $child['Name'] . '</option>';
                                            }
                                        }
                                    ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Category Faild -->
                    <!-- Start Tag Faild -->
                    <div class="form-group row">
                        <label for="tags" class="col-sm offset-1 col-form-label mt-1">Tags</label>
                        <div class="col-sm-9">
                            <input type="text" name="tags" id="tags" class="form-control" placeholder="write some tags and sperate tags with comma (,)">
                        </div>
                    </div>
                    <!-- End Tag Faild -->
                    <div class="form-group row">
                        <div class="col-sm-3 offset-sm-3 ">
                            <input type="submit" value="Add Item" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
            <?php
        } elseif ($do == 'Insert') { //Insert Items
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // collection of image
                $imageName  = $_FILES['image']['name'];
                $imageTmp   = $_FILES['image']['tmp_name'];
                $imageType  = $_FILES['image']['type'];
                $imageSize  = $_FILES['image']['size'];
                // this array of extention allowed 
                $imageExtentionAllowed = ['jpg', 'jpeg', 'png', 'gif'];
                // get extention of the file upload
                $explodeImage       = explode('.', $imageName);
                $imageExtention  =  strtolower(end($explodeImage));  
                
                $name       = $_POST['name'];
                $desc       = $_POST['description'];
                $price      = $_POST['price'];
                $country    = $_POST['country'];
                $status     = $_POST['status'];
                $member     = $_POST['member'];
                $cat        = $_POST['Category'];
                $tags       = $_POST['tags'];

                $formErrors = [];
                if (empty($name)) {
                    $formErrors[] = 'The Name Items Can\'t Be <strong>Empty</strong>';
                }
                if (empty($desc)) {
                    $formErrors[] = 'The Description Items Can\'t Be <strong>Empty</strong>';
                }
                if (empty($price)) {
                    $formErrors[] = 'The Price Items Can\'t Be <strong>Empty</strong>';
                }
                if ($status == 0) {
                    $formErrors[] = 'You Must Choose The <strong>Status</strong>';
                }
                if ($member == 0) {
                    $formErrors[] = 'You Must Choose The <strong>Member</strong>';
                }
                if ($cat == 0) {
                    $formErrors[] = 'You Must Choose The <strong>Category</strong>';
                }
                if (!empty($imageName) && !in_array($imageExtention, $imageExtentionAllowed)) {
                    $formErrors[] = 'This Is Iamge Is Not <strong>Not Allow</strong>';
                }
                if (empty($imageName)) {
                    $formErrors[] = 'This Is Input Field Of Image Is <strong>Empty</strong>';
                }
                if ($imageSize > 4194304) {
                    $formErrors[] ='This Image Is More Than <strong>4MB</strong>';
                }
                
                foreach ($formErrors as $error) {
                    $theMsg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirectHome($theMsg, 'back');
                }

                if (empty($formErrors)) {
                    $image = rand(0, 1000000) . "_" . $imageName;
                    move_uploaded_file($imageTmp, 'upload/item/' . $image);
                    $stmt = $connect->prepare("INSERT INTO items 
                                                    (`Name`, `Description`, Price, Add_Date, Country_Made, `Image`, `Status`, Cat_ID, Member_ID, tags)
                                                VALUES 
                                                    (:zname, :zdesc, :zprice, now(), :zcountry, :zimage, :zstatus, :zcat, :zmember, :ztags) ");
                    $stmt->execute([
                        'zname'     => $name,
                        'zdesc'     => $desc,
                        'zprice'    => $price,
                        'zcountry'  => $country,
                        'zimage'    => $image,
                        'zstatus'   => $status,
                        'zcat'      => $cat,
                        'zmember'   => $member,
                        'ztags'     => $tags
                    ]);
                    $count = $stmt->rowCount();
                    if ($count > 0) {
                        echo ' <h1 class="text-center">Insert Member</h1>';
                        echo '<div class="container">';
                            $theMsg = '<div class="alert alert-success"> You Add '. $count . ' Record</div>';
                            redirectHome($theMsg, 'back');                  
                        echo '</div>'; 
                    }
                }
            } else {
                echo '<div class="container py-5">';
                    $theMsg = '<div class="alert alert-danger">You Can\'t Access This Page Directly</div>';
                    redirectHome($theMsg);
                echo '</div>';
            }
        } elseif ($do == 'Edit'){ // Edit Page
            // check is exist request method get and consider on items id
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? $_GET['itemid'] : 0;
            $stmt = $connect->prepare("SELECT * FROM items WHERE Item_ID = ?");
            $stmt->execute([$itemid]);
            $item = $stmt->fetch();
            $count = $stmt->rowCount();
            if ($count > 0) {       
                ?>
                <h1 class="text-center">Edit Item</h1>
                <div class="container">
                    <form class="my-0 w-75 " action="?do=Update" method="POST">
                        <!-- Start ID Faild This Faild Is Hidden-->
                        <input type="hidden" name="id"  value="<?php echo $item['Item_ID']; ?>" required>
                        <!-- End ID Faild -->
                        <!-- Start Name Faild -->
                        <div class="form-group row">
                            <label for="name" class="col-sm offset-1 col-form-label">Name</label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="name" id="name" placeholder="Item Name"  value="<?php echo $item['Name']; ?>" required>
                            </div>
                        </div>
                        <!-- End Name Faild -->
                        <!-- Start Description Faild -->
                        <div class="form-group row">
                            <label for="description" class="col-sm offset-1 col-form-label">Description</label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control " name="description" placeholder="Add Item Description"  value="<?php echo $item['Description']; ?>" required>
                            </div>
                        </div>
                        <!-- End Description Faild -->
                        <!-- Start Price Faild -->
                        <div class="form-group row">
                            <label for="price" class="col-sm offset-1 col-form-label">Price</label>
                            <div class="col-sm-9">
                            <input type="text" class="form-control" name="price" id="price" placeholder="Add The Item Price" value="<?php echo $item['Price']; ?>" required>
                            </div>
                        </div>
                        <!-- End Price Faild -->
                        <!-- Start Country Faild -->
                        <div class="form-group row">
                            <label for="country" class="col-sm offset-1 col-form-label">Country Made</label>
                            <div class="col-sm-9">
                            <input type="text" class="form-control" name="country" id="country" placeholder="The Country Made" value="<?php echo $item['Country_Made']; ?>">
                            </div>
                        </div>
                        <!-- End Country Faild -->
                        <!-- Start Status Faild -->
                        <div class="form-group row">
                            <label for="status" class="col-sm offset-1 col-form-label">Status</label>
                            <div class="col-sm-9">
                            <select name="status" required>
                                <option value="1" <?php if($item['Status'] == 1){ echo 'selected'; }?> >New</option>
                                <option value="2" <?php if($item['Status'] == 2){ echo 'selected'; }?> >Like New</option>
                                <option value="3" <?php if($item['Status'] == 3){ echo 'selected'; }?> >Used</option>
                                <option value="4" <?php if($item['Status'] == 4){ echo 'selected'; }?> >Old</option>
                            </select>
                            </div>
                        </div>
                        <!-- End Status Faild -->
                        <!-- Start Member Faild -->
                        <div class="form-group row">
                            <label for="status" class="col-sm offset-1 col-form-label">Member</label>
                            <div class="col-sm-9">
                                <select name="member" required>
                                    <?php
                                        // get users from DB And IF member_id == user_id selected this user
                                        $users = getAllFrom('*', 'users', '', 'UserID');
                                        foreach($users as $user) {
                                            echo '<option value="' . $user['UserID'] . '"';
                                            if ($item['Member_ID'] == $user['UserID']) { echo 'selected'; }
                                            echo '>'. $user['UserName'] . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- End Member Faild -->
                        <!-- Start Category Faild -->
                        <div class="form-group row">
                            <label for="status" class="col-sm offset-1 col-form-label">Category</label>
                            <div class="col-sm-9">
                                <select name="category" required>
                                    <?php
                                        // get categories from DB And IF Cat_ID == ID selected this user
                                        $cats = getAllFrom('*', 'categories', '', 'ID');
                                        foreach($cats as $cat) {
                                            echo '<option value="' . $cat['ID'] . '"';
                                            if ($item['Cat_ID'] == $cat['ID']) { echo 'selected'; }
                                            echo '>'. $cat['Name'] . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- End Category Faild -->
                        <!-- Start Tag Faild -->
                        <div class="form-group row">
                            <label for="tag" class="col-sm offset-1 col-form-label mt-1">Tags</label>
                            <div class="col-sm-9">
                            <input type="text" name="tags" id="tag" class="form-control" placeholder="write some tags" value="<?php echo $item['tags'] ;?>">
                            </div>
                        </div>
                        <!-- End Tag Faild -->
                        <div class="form-group row">
                            <div class="col-sm-3 offset-sm-3 ">
                                <input type="submit" value="Save" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                    <?php
                        //  Start Request comment Fialdes 
                        $stmt = $connect->prepare("SELECT 
                                                        comments.*, users.UserName 
                                                    FROM
                                                        comments
                                                    INNER JOIN 
                                                        users
                                                    ON
                                                        users.UserID = comments.User_ID
                                                    WHERE
                                                        Item_ID = ?");
                        $stmt->execute([$itemid]);
                        $items = $stmt->fetchAll();
                        $count = $stmt->rowCount();
                        if ($count > 0) {
                            // End Request comment Fialdes
                            ?>
                            <!-- Start Table Comment -->
                            <h1 class="text-center">Manage [<?php echo $item['Name'] ?>] Comment</h1>
                            <div class="table-responsive-sm">
                                <table class="table table-bordered text-center">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Comments</th>
                                            <th scope="col">Date Regiester</th>
                                            <th scope="col">User</th>
                                            <th scope="col">Control</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach ($items as $item) {
                                                echo '<tr>';
                                                    echo '<td>' . $item['Comments'] . '</td>';
                                                    echo '<td>' . $item['C_Date'] . '</td>';
                                                    echo '<td>' . $item['UserName'] . '</td>';
                                                    echo '<td>';
                                                    echo '<a href="comment.php?do=Edit&comid=' . $item['C_ID'] . '" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>';
                                                    echo ' <a href="comment.php?do=Delete&comid=' . $item['C_ID'] . '" class="btn btn-danger confirm"><i class="fa fa-trash-alt"></i> Delete</a>';
                                                    if ($item['Status'] == 0) {
                                                        echo ' <a href="comment.php?do=Approve&comid=' . $item['C_ID'] . '" class="btn btn-info"><i class="fa fa-check"></i> Approve</a>';
                                                    }
                                                    echo '</td>';
                                                echo '</tr>';
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                <?php   } ?>
                </div>
                    <!-- End Table Comment -->
                <?php
            } else {
                echo '<div class="container py-5">';
                    $theMsg = '<div class="alert alert-danger">You Can\'t Access This Page Directly</div>';
                    redirectHome($theMsg);
                echo '</div>';
            }
        } elseif($do == 'Update') { // Update Itmes 
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo '<h1 class="text-center">Update Item</h1>';
                echo '<div class="container">';
                    $id         = $_POST['id'];
                    $name       = $_POST['name'];
                    $desc       = $_POST['description'];
                    $price      = $_POST['price'];
                    $country    = $_POST['country'];
                    $status     = $_POST['status'];
                    $member     = $_POST['member'];
                    $cat        = $_POST['category'];
                    $tags       = $_POST['tags'];

                    $formErrors = [];
                    if (empty($name)) {
                        $formErrors[] = 'The Name Items Can\'t Be <strong>Empty</strong>';
                    }
                    if (empty($desc)) {
                        $formErrors[] = 'The Description Items Can\'t Be <strong>Empty</strong>';
                    }
                    if (empty($price)) {
                        $formErrors[] = 'The Price Items Can\'t Be <strong>Empty</strong>';
                    }
                    if ($status == 0) {
                        $formErrors[] = 'You Must Choose The <strong>Status</strong>';
                    }
                    if ($member == 0) {
                        $formErrors[] = 'You Must Choose The <strong>Member</strong>';
                    }
                    if ($cat == 0) {
                        $formErrors[] = 'You Must Choose The <strong>Category</strong>';
                    }
                    
                    foreach ($formErrors as $error) {
                        $theMsg = '<div class="alert alert-danger">' . $error . '</div>';
                        redirectHome($theMsg, 'back');
                    }

                    if (empty($formErrors)) {
                        $stmt = $connect->prepare('UPDATE
                                                        items
                                                    SET
                                                        `Name` = ?, 
                                                        `Description` = ?,
                                                        Price =?,
                                                        Country_Made = ?, 
                                                        `Status` = ?, 
                                                        Member_ID = ?, 
                                                        Cat_ID = ?, 
                                                        tags = ?
                                                    WHERE 
                                                        Item_ID = ?');
                        $stmt->execute([$name, $desc, $price, $country, $status, $member, $cat, $tags, $id]);
                        $count = $stmt->rowCount();
                        if ($count > 0) {
                            $theMsg = '<div class="alert alert-success"> You Add '. $count . ' Record</div>';
                            redirectHome($theMsg, 'back');  
                        }
                    }
                echo '</div>';
            } else {
                echo '<div class="container py-5">';
                    $theMsg = '<div class="alert alert-danger">You Can\'t Access This Page Directly</div>';
                    redirectHome($theMsg);
                echo '</div>';
            }
        } elseif($do == 'Delete'){ // Delete Items
            // check request By Get Request At Item id
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
            // check is exists in database this itemsid
            $check = checkItem('Item_ID', 'items', $itemid);

            if ($check > 0) {
                    $stmt = $connect->prepare("DELETE FROM items WHERE Item_ID = ?");
                    $stmt->execute([$itemid]);
                    $count = $stmt->rowCount();

                    echo '<h1 class="text-center">Delete Item</h1>' ;
                    echo '<div class="container">';
                        $theMsg = '<div class="alert alert-success"> You Add '. $count . ' Record</div>';
                        redirectHome($theMsg, 'back');  
                    echo '</div>';
            } else {
                echo '<div class="container py-5">';
                    $theMsg = '<div class="alert alert-danger">You Can\'t Access This Page Directly</div>';
                    redirectHome($theMsg);
                echo '</div>';
            }
        } elseif($do == 'Approved') { // Approve items
            // check request By Get Request At Item id
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
            // check is exists in database this itemsid
            $check = checkItem('Item_ID', 'items', $itemid);
            if ($check > 0) {
                $stmt = $connect->prepare("UPDATE items SET Approved = 1 WHERE Item_ID = ?");
                $stmt->execute([$itemid]);
                $count = $stmt->rowCount();

                echo '<h1 class="text-center">Approved Item</h1>' ;
                echo '<div class="container">';
                    $theMsg = '<div class="alert alert-success"> You Add '. $count . ' Record</div>';
                    redirectHome($theMsg, 'back');  
                echo '</div>';
            } else {
                echo '<div class="container py-5">';
                    $theMsg = '<div class="alert alert-danger">You Can\'t Access This Page Directly</div>';
                    redirectHome($theMsg);
                echo '</div>';
            }
        }
        include $tmp . 'footer.php';    
    } else {
        header('location: index.php');
        exit();
    }
    ob_end_flush();
?>