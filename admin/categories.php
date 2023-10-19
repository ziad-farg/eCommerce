<?php
    /*
    //////////////////////////////////////////////////////////////
    // this Is Page For [ Mange | Edit | Add | Delete Categories ] //
    //////////////////////////////////////////////////////////////
    */

    ob_start(); // start output buffering
    session_start();
    if ($_SESSION['username']) {
        // page title
        $title = 'Categories';
        include 'init.php';    
        // check request method get[do] and is number redirect this url else redirect to manege
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        if ($do == 'Manage') { // Manage Page
            $sort = 'ASC';
            $sortArr = ['ASC', 'DESC'];
            // if request is get['sort'] and in array sort get this request
            if (isset($_GET['sort']) && in_array($_GET['sort'], $sortArr)) {
                $sort = $_GET['sort'];
            }

            $stmt = $connect->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY Ordring $sort");
            $stmt->execute();
            $cats = $stmt->fetchAll();

            // check exists categories in DB
            if (!empty($cats)) {
    ?>
                <h1 class="text-center">Manage Category</h1>
                <div class="container categories">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-edit"></i>
                            <b>Manage Category</b>
                            <div class="option float-right">
                                <i class="fas fa-sort"></i>
                                <b>Ordring :</b> [
                                <!-- check is click in ASC Add attribute active -->
                                <a href="?sort=ASC" class="<?php if($sort == 'ASC'){ echo 'active'; } ?>">Asc </a>|
                                <!-- check is click in DESC Add attribute active -->
                                <a href="?sort=DESC" class="<?php if($sort == 'DESC'){ echo 'active'; } ?>">Desc</a> ]
                                <i class="fas fa-eye"></i>
                                <b>View :</b> [
                                <span data-view="full" class="active">Full</span> |
                                <span>Classic</span> ]
                            </div>
                        </div>
                        <div class="card-body">
                            <?php
                                foreach ($cats as $cat) {
                                    echo '<div class="cat">';
                                        echo '<div class="hidden-button">';
                                            echo '<a href="?do=Edit&catid=' . $cat['ID'] . '" class="btn btn-primary mr-2"><i class="fa fa-edit"></i> Edit</a>';
                                            echo '<a href="?do=Delete&catid=' . $cat['ID'] . '" class="btn btn-danger confirm"><i class="fas fa-trash-alt"></i> Delete</a>';
                                        echo  '</div>';
                                        echo ' <h3>' . $cat['Name'] . '</h3>';
                                        echo '<div class="full-view">';
                                            //  check description is empty
                                            if ($cat['Description']) {
                                                echo '<p>' . $cat['Description'] . '</p>';
                                            } else {
                                                echo '<p> This Categories Don\'t Have Description </p>';
                                            }
                                            //  check visiblity is hidden
                                            if ($cat['Visiblity'] == 1) {
                                                echo '<span class="vis"><i class="far fa-eye-slash"></i> Visiblity Is Hidden</span>';
                                            }
                                            //  check Comment is hidden
                                            if ($cat['Comment'] == 1) {
                                                echo '<span class="com"><i class="fas fa-trash-alt"></i> Comment Is Hidden</span>';
                                            }
                                            //  check Ads is hidden
                                            if ($cat['Ads'] == 1) {
                                                echo '<span class="ads"><i class="fas fa-trash-alt"></i> Ads Is Hidden</span>';
                                            }
                                        echo '</div>';
                                        echo '<div class="sub-cat">';
                                            // this is for sub categories for get
                                            $childs = getAllFrom('*', 'categories', "WHERE parent = {$cat['ID']}", 'ID', 'ASC');
                                            if (!empty($childs)) {
                                                echo '<h5>Sub '.$cat['Name'].' Categories</h5>';
                                                echo '<ul class="list-unstyled">';
                                                foreach ($childs as $child) {
                                                    echo '<li>';
                                                        echo '<a href="categories.php?do=Edit&catid='.$child['ID'].'">'.$child['Name'].'</a>';
                                                        echo '<a href="?do=Delete&catid=' . $child['ID'] . '" class="del confirm"> Delete</a>';
                                                    echo '</li>';
                                                }
                                                echo '</ul>';
                                            }
                                        echo '</div>';
                                    echo '</div>';
                                   
                                    echo '<hr>';
                                }
                            ?>
                        </div>
                    </div>
                    <a class="btn btn-primary mt-2 mb-3" href="?do=Add"><i class="fa fa-plus"></i> Add New Category</a>
                </div>
    <?php 
            } else {
                // Print Msg There's Not Categories To Show
                echo '<div class="container py-5">';
                    echo '<div class="alert alert-info">There\'s Not Category To Show</div>';
                    echo'<a class="btn btn-primary mt-2 mb-3" href="?do=Add"><i class="fa fa-plus"></i> Add New Category</a>';
                echo '</div>';
            }
    ?>
    <?php
        
        } elseif ($do == 'Add'){ // Add Page

    ?>
        <h1 class="text-center">Add New Category</h1>
        <div class="container">
            <div class="row">
                <form class="my-0 w-75" action="?do=Insert" method="POST">
                    <!-- Start Name Faild -->
                    <div class="form-group row justify-content-center">
                        <label for="name" class="col-sm col-form-label">Name</label>
                        <div class="col-sm-9 ">
                            <!-- the key of the row in the value is the name of field in database -->
                            <input type="text" class="form-control" name="name" id="name"  autocomplete="off"  placeholder="New Category" required>
                        </div>
                    </div>
                    <!-- End Name Faild -->
                    <!-- Start Description Faild -->
                    <div class="form-group row justify-content-center">
                        <label for="description" class="col-sm col-form-label">Description</label>
                        <div class="col-sm-9 ">
                            <input type="text" class="form-control" name="description" id="description" placeholder="Description">
                        </div>
                    </div>
                    <!-- End Description Faild -->
                    <!-- Start Parent Faild -->
                    <div class="form-group row justify-content-center">
                        <label for="parent" class="col-sm col-form-label">Parent</label>
                        <div class="col-sm-9">
                            <select name="parent" id="">
                                <option value="0">none</option>
                                <?php 
                                    // show all main categories not sub categories
                                    $cats = getAllFrom('*', 'categories', 'WHERE parent = 0', 'ID', 'ASC');
                                    foreach ($cats as $cat) {
                                        echo '<option value="'.$cat['ID'].'">'.$cat['Name'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Parent Faild -->
                    <!-- Start Ordering Faild -->
                    <div class="form-group row justify-content-center">
                        <label for="ordering" class="col-sm col-form-label">Ordering</label>
                        <div class="col-sm-9">
                            <!-- the key of the row in the value is the name of field in database -->
                            <input type="text" class="form-control" name="ordering" id="ordering" placeholder="Number Of Ordring">
                        </div>
                    </div>
                    <!-- End Ordering Faild -->
                    <!-- Start Visible Faild -->
                    <fieldset class="form-group row">
                        <legend class="col-form-label col-sm-2 float-sm-left pt-0">Visiblity</legend>
                        <div class="col-sm-9 offset-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="visible" id="vs-yes" value="0" checked>
                            <label class="form-check-label" for="vs-yes">
                            Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="visible" id="vs-no" value="1" >
                            <label class="form-check-label" for="vs-no">
                            No
                            </label>
                        </div>
                    </fieldset>
                    <!-- End Visible Faild -->
                    <!-- Start Allow Comment Faild -->
                    <fieldset class="form-group row">
                        <legend class="col-form-label col-sm-2 float-sm-left pt-0">Comment</legend>
                        <div class="col-sm-9 offset-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="comment" id="com-yes" value="0" checked>
                            <label class="form-check-label" for="com-yes">
                            Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="comment" id="com-no" value="1" >
                            <label class="form-check-label" for="com-no">
                            No
                            </label>
                        </div>
                    </fieldset>
                    <!-- End Allow Comment Faild -->
                    <!-- Start Visible Faild -->
                    <fieldset class="form-group row">
                        <legend class="col-form-label col-sm-2 float-sm-left pt-0">ADS</legend>
                        <div class="col-sm-9 offset-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ads" id="ads-yes" value="0" checked>
                            <label class="form-check-label" for="ads-yes">
                            Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ads" id="ads-no" value="1" >
                            <label class="form-check-label" for="ads-no">
                            No
                            </label>
                        </div>
                    </fieldset>
                    <!-- End Visible Faild -->
                    <div class="form-group row  ">
                        <div class="col-sm-3 offset-sm-3 ">
                            <input type="submit" value="Add Category" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php

        } elseif ($do == 'Insert') {

            // check REQUEST_METHOD is POST
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $name = $_POST['name'];
                    $desc = $_POST['description'];
                    $parent = $_POST['parent'];
                    $order = $_POST['ordering'];        
                    $visible = $_POST['visible'];
                    $comment = $_POST['comment'];
                    $ads = $_POST['ads'];
                    
                    if (!empty($name)) {

                        // check of name Is Exists in DataBase
                        $check = checkItem('Name', 'categories', $name);
                        if ($check == 1) {
                            echo '<h1 class="text-center">Insert Page</h1>';
                            echo '<div class="container">';
                                $theMsg = '<div class="alert alert-danger">Sorry This Category Is Found</div>';                     
                                redirectHome($theMsg, 'back');
                            echo '</div>';
                        } else {
                            $stmt = $connect->prepare("INSERT INTO categories (`Name`, `Description`, parent, Ordring, `Visiblity`, `Comment`, Ads)
                                                VALUES (:zname, :zdesc, :zparent, :zorder, :zvs, :zcom, :zads)");
                            $stmt->execute([
                                'zname'     => $name,
                                'zdesc'     => $desc,
                                'zparent'   => $parent,
                                'zorder'    => $order,
                                'zvs'       => $visible,
                                'zcom'      => $comment,
                                'zads'      => $ads
                            ]);

                            $count = $stmt->rowCount();

                            if ($count > 0) {
                                echo '<h1 class="text-center">Insert Page</h1>';
                                echo '<div class="container">';
                                    $theMsg = '<div class="alert alert-success"> You Add '. $count . ' Record</div>';
                                    redirectHome($theMsg, 'back');
                                echo '</div>';
                            }

                        }
                    } 
            } else {

                // If get request method dirctly 

                echo '<div class="container">';

                $theMsg = '<div class="alert alert-danger">Sorry You Can\'t Access This Page Directly</div>';

                redirectHome($theMsg);

                echo '</div>';
            }
            ?>
            <?php
        } elseif ($do == 'Edit'){ // Edit Category

            // check catid is Exist And Is Numeric
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

            // Select All Info From Database 
            $stmt = $connect->prepare("SELECT * FROM categories WHERE ID = ?");

            // Execute The Query
            $stmt->execute([$catid]);
            
            // Fetch Info From DB
            $row = $stmt->fetch();

            // The Row Count
            $count = $stmt->rowCount();

            // check if this category exist in database Show The Form
            if ($count > 0) {    
                ?>
                    <h1 class="text-center">Edit Category</h1>
                    <div class="container">
                        <form class="my-0 w-75" action="?do=Update" method="POST">
                            <input type="hidden" name="id" value="<?php echo $row['ID']; ?>">
                            <!-- Start Name Faild -->
                            <div class="form-group row">
                                <label for="name" class="offset-1 col-sm col-form-label">Name</label>
                                <div class="col-sm-9 ">
                                    <!-- the key of the row in the value is the name of field in database -->
                                <input type="text" class="form-control" name="name" id="name"   placeholder="New Category" required value="<?php echo $row['Name'] ;?>">
                                </div>
                            </div>
                            <!-- End Name Faild -->
                            <!-- Start Description Faild -->
                            <div class="form-group row">
                                <label for="description" class="offset-1 col-sm col-form-label">Description</label>
                                <div class="col-sm-9 ">
                                    <input type="text" class="form-control" name="description" id="description" placeholder="Description" value="<?php echo $row['Description']?>">
                                </div>
                            </div>
                            <!-- End Description Faild -->
                            <!-- Start Ordering Faild -->
                            <div class="form-group row">
                                <label for="ordering" class="offset-1 col-sm col-form-label">Ordering</label>
                                <div class="col-sm-9">
                                    <!-- the key of the row in the value is the name of field in database -->
                                    <input type="text" class="form-control" name="ordering" id="ordering" placeholder="Number Of Ordring"value="<?php echo $row['Ordring']?>" >
                                </div>
                            </div>
                            <!-- End Ordering Faild -->
                            <!-- Start Parent Faild -->
                            <div class="form-group row">
                                <label for="parent" class="offset-1 col-sm col-form-label">Parent</label>
                                <div class="col-sm-9">
                                   <select name="parent" id="">
                                       <option value="0">None</option>
                                       <?php 
                                            $childs = getAllFrom('*', 'categories', "WHERE parent = 0", 'ID', 'ASC');
                                            foreach ($childs as $child){
                                                echo '<option value="'.$child['ID'].'"';
                                                if ($row['parent'] == $child['ID']) {
                                                    echo 'selected';
                                                }
                                                echo '>'.$child['Name'].'</option>';
                                            }
                                       ?>
                                   </select>
                                </div>
                            </div>
                            <!-- End Parent Faild -->
                            <!-- Start Visible Faild -->
                            <fieldset class="form-group row">
                                <legend class="offset-1 col-form-label col-sm-1 float-sm-right pt-0 ">Visiblity</legend>
                                <div class="col-sm offset-sm-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="visible" id="vs-yes" value="0" <?php if($row['Visiblity'] == 0) { echo 'checked';} ?> >
                                        <label class="form-check-label" for="vs-yes" >
                                        Yes
                                        </label>
                                    </div>
                                
                                    <div class="form-check">
                                    <input class="form-check-input" type="radio" name="visible" id="vs-no" value="1" <?php if($row['Visiblity'] == 1) { echo 'checked';} ?>>
                                    <label class="form-check-label" for="vs-no">
                                    No
                                    </label>
                                    </div>
                                </div>
                            </fieldset>
                            <!-- End Visible Faild -->
                            <!-- Start Allow Comment Faild -->
                            <fieldset class="form-group row">
                                <legend class="offset-sm-1 col-form-label col-sm-1 float-sm-left pt-0">Comment</legend>
                                <div class="col-sm offset-sm-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="comment" id="com-yes" value="0" <?php if($row['Comment'] == 0) { echo 'checked';} ?>>
                                    <label class="form-check-label" for="com-yes">
                                    Yes
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="comment" id="com-no" value="1" <?php if($row['Comment'] == 1) { echo 'checked';} ?>>
                                    <label class="form-check-label" for="com-no">
                                    No
                                    </label>
                                    </div>
                                </div>
                            </fieldset>
                            <!-- End Allow Comment Faild -->
                            <!-- Start Visible Faild -->
                            <fieldset class="form-group row">
                                <legend class="offset-sm-1 col-form-label col-sm-1 float-sm-left pt-0">ADS</legend>
                                <div class="col-sm offset-sm-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ads" id="ads-yes" value="0" <?php if($row['Ads'] == 0) { echo 'checked';} ?>>
                                    <label class="form-check-label" for="ads-yes">
                                    Yes
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ads" id="ads-no" value="1" <?php if($row['Ads'] == 1) { echo 'checked';} ?>>
                                    <label class="form-check-label" for="ads-no">
                                    No
                                    </label>
                                    </div>
                                </div>
                            </fieldset>
                            <!-- End Visible Faild -->
                            <div class="form-group row">
                                <div class="col-sm-3 offset-sm-3 ">
                                    <input type="submit" value="Save" class="btn btn-primary">
                                </div>
                            </div>
                        </form>
                    </div>
                <?php
                    } else {

                        // check else No Id Or Open Browse Dirctly
                        echo '<div class="container py-5">';
                            $theMsg = '<div class="alert alert-danger"> You Can\'t Access this Page Directly </div>';
                            redirectHome($theMsg);
                        echo '</div>';
                    }
        } elseif ($do == 'Update') { // Update category

            // Check Of The Request Method
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                

                // Get Values From The Form
                $id      = $_POST['id'];
                $name    = $_POST['name'];
                $des     = $_POST['description'];
                $order   = $_POST['ordering'];
                $parent  = $_POST['parent'];
                $visible = $_POST['visible'];
                $comment = $_POST['comment'];
                $ads     = $_POST['ads'];

                if (!empty($name)) {
                    
                    $stmt = $connect->prepare("UPDATE categories 
                                                SET 
                                                    `Name` = ?,
                                                    `Description` = ?,
                                                    Ordring = ?,
                                                    parent = ?,
                                                    `Visiblity` = ?,
                                                    `Comment` = ?,
                                                    Ads = ? 
                                                WHERE
                                                    ID = ?");

                    $stmt->execute([$name, $des, $order, $parent, $visible, $comment, $ads, $id]);
                    $count = $stmt->rowCount();
                    echo '<h1 class="text-center">Update Category</h1>';
                    echo '<div class="container">';
                        $theMsg = '<div class="alert alert-success"> You Update '. $count . ' Record</div>';
                        redirectHome($theMsg, 'back');
                    echo '</div>';
                }
            } else {
                    // check else No Id Or Open Browse Dirctly
                    echo '<div class="container py-5">';
                        $theMsg = '<div class="alert alert-danger"> You Can\'t Access this Page Directly </div>';
                        redirectHome($theMsg);
                    echo '</div>';
                    }

        } elseif ($do == 'Delete') { // Delete Category
            // check of category id get by get request
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
            // check id categories exists in BD
            $check = checkItem('ID', 'categories', $catid);
            if ($check > 0) {
                $stmt = $connect->prepare("DELETE FROM categories WHERE ID = ?");
                $stmt->execute([$catid]);
                $count = $stmt->rowCount();
                echo '<h1 class="text-center">Delete Category</h1>';
                echo '<div class="container">';
                    $theMsg = '<div class="alert alert-success"> You Delete '. $count . ' Record</div>';
                    redirectHome($theMsg, 'back');
                echo '</div>';
            } else {
                echo '<div class="container py-5">';
                    $theMsg = '<div class="alert alert-danger"> You Can\'t Access this</div>';
                    redirectHome($theMsg);
                echo '</div>';
            }
        }
        include $tmp . 'footer.php';

    } else {
        header('LOACTION: index.php');
        exit();
    }
    ob_end_flush(); // end buffering and flush
?>