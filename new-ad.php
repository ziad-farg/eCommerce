<?php
    // output buffer
    ob_start();
    session_start();
    // title page
    $title = 'Create New Item';
    include 'init.php';
    // check if session exist show the form
    if (isset($_SESSION['user'])) {
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        if ($do == 'Manage') {
            // error form array
            $formError = [];
            // check reqest method is post
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $name       = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
                $desc       = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
                $price      = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
                $country    = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
                $status     = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
                $category   = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
                $tags       = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

                // info of image
                $imageName  = $_FILES['image']['name'];
                $imageTmp  = $_FILES['image']['tmp_name'];
                $imageType  = $_FILES['image']['type'];
                $imageSize  = $_FILES['image']['size'];

                // this is array of the allowed extentions of the type of image
                $imageAllowedExtentions = ['jpg', 'jpeg', 'png', 'gif'];

                // get the image exstrion to compare if in array allowed or not
                $explodedImage = explode('.', $imageName);
                $extentionImage = strtolower(end($explodedImage));
                
                // check if any input faild is not identical the required specifications
                if (strlen($name) < 4) {
                    $formError[] = 'You Must Be Write Less Than 4 Characters In The Name Faild';
                }
                if (strlen($desc) < 10) {
                    $formError[] = 'You Must Be Write Less Than 10 Characters In The Description Faild';
                }
                if (empty($price)) {
                    $formError[] = 'You Mustn\'t Be Leave the Price field blank In The Price Faild';
                }
                if (strlen($country) < 2) {
                    $formError[] = 'You Must Be Write Less Than 2 Characters In The Country Faild';
                }
                if (!empty($imageName) && !in_array($extentionImage, $imageAllowedExtentions)) {
                    $formError[] = 'You Must Be Add Image Have Extention [jpg, jpeg, gif, png]';
                }
                if (empty($imageName)) {
                    $formError[] = 'You Must Be Add Image';
                }
                if ($imageSize > 4194304) {
                    $formError[] = 'You Must Be Add Image Have Size Less Than 4MB';
                }
                if (empty($status)) {
                    $formError[] = 'You Mustn\'t Be Leave the Price field blank In The Status Faild';
                }
                if (empty($category)) {
                    $formError[] = 'You Mustn\'t Be Leave the Price field blank In The Category Faild';
                }
                if (empty($formError)) {
                    $image = rand(0, 1000000) . '_' . $imageName;
                    move_uploaded_file($imageTmp, 'admin/upload/item/' . $image);
                    $stmt = $connect->prepare("INSERT INTO 
                                                    items (`Name`, `Description`, Price, Add_Date, Country_Made, `Image`,  `Status`, Cat_ID, Member_ID, tags)
                                                VALUES
                                                    (:zname, :zdesc, :zprice, now(), :zcountry, :zimage, :zstatus, :zcat, :zmember, :ztags)");
                    $stmt->execute([
                        'zname'     => $name,
                        'zdesc'     => $desc,
                        'zprice'    => $price,
                        'zcountry'  => $country,
                        'zimage'    => $image,
                        'zstatus'   => $status,
                        'zcat'      => $category,
                        'zmember'   => $_SESSION['userid'],
                        'ztags'     => $tags
                    ]);

                    $count = $stmt->rowCount();
                    if ($count > 0) {
                    $success = 'You Item Is Added';
                    }
                }
            }
            ?>
            <h1 class="text-center"><?php echo $title; ?></h1>
            <div class="container my-5">
                <div class="card">
                <div class="card-header">
                    New Ad
                </div>
                <div class="card-body live-home">
                    <div class="row">
                        <div class="col-md-8">
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                                <!-- start name faild -->
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input 
                                        pattern=".{4,}"
                                        title="Write Less Than 4 Characters" 
                                        type="text" 
                                        class="form-control live" 
                                        name="name" 
                                        id="name" 
                                        data-class=".live-name" 
                                        required>
                                </div>
                                <!-- end name faild -->
                                <!-- start description faild -->
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input 
                                        pattern=".{10,}" 
                                        title="Write Less Than 10 Characters" 
                                        type="text" class="form-control live" 
                                        name="description" 
                                        id="description" 
                                        data-class=".live-desc" 
                                        required>
                                </div>
                                <!-- end description faild -->
                                <!-- start price faild -->
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input 
                                        type="text" 
                                        class="form-control live" 
                                        name="price" 
                                        id="price" 
                                        data-class=".live-price" 
                                        required>
                                </div>
                                <!-- end price faild -->
                                <!-- start Country faild -->
                                <div class="form-group">
                                    <label for="country">Country</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="country" 
                                        name="country" 
                                        required>
                                </div>
                                <!-- end Country faild -->
                                <!-- Start image Field -->
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <input 
                                        type="file" 
                                        class="form-control" 
                                        id="image" 
                                        name="image"
                                        onchange="loadFile(event)"
                                        required>
                                </div>
                                <!-- End image Field -->
                                <!-- start status faild -->
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="custom-select" name="status" required>
                                        <option value="">...</option>
                                        <option value="1">New</option>
                                        <option value="2">Like New</option>
                                        <option value="3">Used</option>
                                        <option value="4">Old</option>
                                    </select>
                                </div>
                                <!-- end status faild -->
                                <!-- start Categories faild -->
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="custom-select" name="category" required>
                                        <option value="">...</option>
                                            <?php 
                                                $cats = getAllFrom('*','categories', 'WHERE parent = 0', 'ID', 'ASC');
                                                foreach($cats as $cat){
                                                    echo '<option value="'.$cat['ID'].'">'. $cat['Name'] . '</option>';
                                                    $childs =getAllFrom('*', 'categories', "WHERE parent = {$cat['ID']}", 'ID');
                                                    foreach($childs as $child){
                                                        echo '<option value="'.$child['ID'].'"> --- '. $child['Name'] . '</option>';
                                                    }
                                                }
                                            ?>
                                    </select>
                                </div>
                                <!-- end Categories faild -->
                                <!-- start tags faild -->
                                <div class="form-group">
                                    <label for="tags">tags</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="tags" 
                                        name="tags" 
                                        placeholder="write some tags and sperator with comma (,)">
                                </div>
                                <!-- end tags faild -->     
                                <button type="submit" class="btn btn-primary">Add Item</button>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <div class="card live-home2 card-home">
                            <span class="card-text price-tag">
                                $<span class="live-price">0</span>
                            </span>
                                <img src="admin/upload/item/default.png" class="card-img-top w-100" alt="image" id="output">
                                <div class="card-body">
                                    <h5 class="card-title live-name">Card title</h5>
                                    <p class="card-text live-desc">Description Item</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    if(!empty($formError)){
                        foreach($formError as $error){
                            echo '<div class="alert alert-danger">' . $error . '</div>';
                        }
                    }
                    // if the request done this msg is show for him
                    if(isset($success)){
                        echo '<div class="alert alert-success">' . $success . '</div>';
                    }
                ?>
                </div>
            </div>
            <?php
        } elseif ($do == 'Edit') {
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
            $stmt = $connect->prepare("SELECT * FROM items WHERE Item_ID = ?");
            $stmt->execute([$itemid]);
            $item = $stmt->fetch();
            $count = $stmt->rowCount();
            if ($count > 0) {
                ?>
                <h1 class="text-center">Edit Page</h1>
                <div class="container my-5">
                    <div class="card">
                    <div class="card-header">
                        New Ad
                    </div>
                    <div class="card-body live-home">
                        <div class="row">
                            <div class="col-md-8">
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                                    <!-- start name faild -->
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input 
                                            pattern=".{4,}"
                                            title="Write Less Than 4 Characters" 
                                            type="text" 
                                            class="form-control live" 
                                            name="name" 
                                            id="name" 
                                            data-class=".live-name"
                                            value="<?php echo $item['Name'] ?>"
                                            required>
                                    </div>
                                    <!-- end name faild -->
                                    <!-- start description faild -->
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <input 
                                            pattern=".{10,}" 
                                            title="Write Less Than 10 Characters" 
                                            type="text" class="form-control live" 
                                            name="description" 
                                            id="description" 
                                            data-class=".live-desc" 
                                            value="<?php echo $item['Description'] ?>"
                                            required>
                                    </div>
                                    <!-- end description faild -->
                                    <!-- start price faild -->
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input 
                                            type="text" 
                                            class="form-control live" 
                                            name="price" 
                                            id="price" 
                                            data-class=".live-price" 
                                            value="<?php echo $item['Price'] ?>"
                                            required>
                                    </div>
                                    <!-- end price faild -->
                                    <!-- start Country faild -->
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="country" 
                                            name="country" 
                                            value="<?php echo $item['Country_Made'] ?>"
                                            required>
                                    </div>
                                    <!-- end Country faild -->
                                    <!-- Start image Field -->
                                    <div class="form-group">
                                        <label for="image">Image</label>
                                        <input 
                                            type="file" 
                                            class="form-control" 
                                            id="image" 
                                            name="image"
                                            onchange="loadFile(event)"
                                            required>
                                    </div>
                                    <!-- End image Field -->
                                    <!-- start status faild -->
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="custom-select" name="status" required>
                                            <option value="">...</option>
                                            <option value="1" <?php if($item['Status'] == 1) {echo 'selected' ;} ?>>New</option>
                                            <option value="2" <?php if($item['Status'] == 2) {echo 'selected' ;} ?>>Like New</option>
                                            <option value="3" <?php if($item['Status'] == 3) {echo 'selected' ;} ?>>Used</option>
                                            <option value="4" <?php if($item['Status'] == 4) {echo 'selected' ;} ?>>Old</option>
                                        </select>
                                    </div>
                                    <!-- end status faild -->
                                    <!-- start Categories faild -->
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select class="custom-select" name="category" required>
                                            <option value="">...</option>
                                                <?php 
                                                    $cats = getAllFrom('*','categories', 'WHERE parent = 0', 'ID', 'ASC');
                                                    foreach($cats as $cat){
                                                        echo '<option value="'.$cat['ID'].'"';
                                                            if($item['Cat_ID'] == $cat['ID']){echo 'selected' ;}
                                                        echo'>'. $cat['Name'] . '</option>';
                                                        $childs =getAllFrom('*', 'categories', "WHERE parent = {$cat['ID']}", 'ID');
                                                        foreach($childs as $child){
                                                            echo '<option value="'.$child['ID'].'"';
                                                                if($item['Cat_ID'] == $child['ID']){echo 'selected' ;}
                                                            echo'> --- '. $child['Name'] . '</option>';
                                                        }
                                                    }
                                                ?>
                                        </select>
                                    </div>
                                    <!-- end Categories faild -->
                                    <!-- start tags faild -->
                                    <div class="form-group">
                                        <label for="tags">tags</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="tags" 
                                            name="tags" 
                                            placeholder="write some tags and sperator with comma (,)"
                                            value="<?php echo $item['tags'] ?>">
                                    </div>
                                    <!-- end tags faild -->     
                                    <button type="submit" class="btn btn-primary">Add Item</button>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <div class="card live-home2 card-home">
                                    <span class="card-text price-tag">
                                        $<span class="live-price">0</span>
                                    </span>
                                    <img src="admin/upload/item/default.png" class="card-img-top w-100" alt="image" id="output">
                                    <div class="card-body">
                                        <h5 class="card-title live-name">Card title</h5>
                                        <p class="card-text live-desc">Description Item</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        if(!empty($formError)){
                            foreach($formError as $error){
                                echo '<div class="alert alert-danger">' . $error . '</div>';
                            }
                        }
                        // if the request done this msg is show for him
                        if(isset($success)){
                            echo '<div class="alert alert-success">' . $success . '</div>';
                        }
                    ?>
                    </div>
                </div>
                <?php
            } else {
                echo '<div class="container">';
                    echo '<div class="alert alert-danger">This Item There Is Not Such ID</div>';
                echo '</div>';
            }
            
        }
        
    } else {
        // else go to the login page
        header('location: login.php');
        exit;
    }
    include $tmp . 'footer.php';
    ob_end_flush();
?>
