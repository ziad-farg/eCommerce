<?php 
    ob_start();
    session_start();
    $title = "Profile";
    include 'init.php';
    // check is the session user exist do the request
    if(isset($_SESSION['user'])){
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        if ($do == 'Manage') {
            $stmt = $connect->prepare("SELECT * FROM users WHERE UserName = ?");
            $stmt->execute([$_SESSION['user']]);
            $info = $stmt->fetch();
            $userid = $info['UserID'];
            ?>
            <h1 class="text-center">My Profile</h1>
            <div class="container info">
                <div class="card mb-3">
                    <div class="card-header bg-secondary">
                        My Information
                    </div>
                    <div class="card-body pb-2">
                        <ul class="list-unstyled">
                            <li>
                                <i class="fas fa-unlock"></i>
                                <span> Name </span> : <?php echo $info['UserName']; ?>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <span> Email</span> : <?php echo $info['Email']; ?>
                            </li>
                            <li>
                                <i class="fas fa-user"></i>
                                <span> Full Name</span> : <?php echo $info['FullName']; ?>
                            </li>
                            <li>
                                <i class="fas fa-calendar-week"></i>
                                <span> Regester Date</span> : <?php echo $info['DateEntry']; ?>
                            </li>
                            <a href="?do=Edit" class="btn btn-primary mt-2 mb-0">Edit Information</a>
                        </ul>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header bg-secondary">
                        My Ads
                    </div>
                    <div class="card-body" id="my-item">
                        <?php 
                            $items = getAllFrom('*', 'items', "WHERE Member_ID = $userid", 'Item_ID');
                            if (!empty($items)) {
                                echo '<div class="row">';
                                    foreach($items as $item){
                                        echo '<div class="col-sm-6 col-md-3 my-3">';
                                            echo '<div class="card card-home">';
                                                if ($item['Approved'] == 0) {
                                                    echo '<span class="approve-ad">this item wating approved</span>';
                                                }
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
                                                echo '<a href="new-ad.php?do=Edit&itemid='.$item['Item_ID'].'" class="btn btn-primary mx-5 mb-3 text-white">Edit Item</a>';
                                            echo '</div>';
                                        echo '</div>';
                                    }
                                echo '</div>';
                                } else {
                                    echo 'You Don\'t Have Any Items Create <a href="new-ad.php">New Ad</a>';
                                }
                            ?>
                    </div>
                </div>
            </div>
            <?php
        } elseif ($do == 'Edit'){ // edit page
            // get value from database to set in value of the input feild
            $stmt = $connect->prepare("SELECT * FROM users WHERE UserName = ?");
            $stmt->execute([$_SESSION['user']]);
            $info = $stmt->fetch();
            $count = $stmt->rowCount();
            $userid = $info['UserID']; 
            // check if exist user in database get this form
            if($count > 0) {
                ?>
                <div class="edit-profile">
                    <h1 class="text-center">Edit Page</h1>
                    <div class="container w-25">
                        <form action="?do=Update" method="post" enctype="multipart/form-data"> 
                            <input type="hidden" name="userid" value="<?php echo $info['UserID'] ?>">
                            <div class="form-group">
                                <label for="username">User Name</label>
                                <input pattern=".{3,}" title="You Must write More 3 Charaters" type="text" class="form-control" name="username" 
                                    id="username" autocomplete="off" value="<?php echo $info['UserName'] ?>" placeholder="Enter Your Name" required>
                            </div>
                            <div class="form-group">
                                <label for="password1">Password</label>
                                <input type="hidden" name="hidepass1" value="<?php echo $info['Pass'] ?>">
                                <input type="password" name="password1" class="form-control" id="password1" 
                                    autocomplete="new-password" placeholder="You can leave this Feild blank" >
                            </div>
                            <div class="form-group">
                                <label for="password2">Repeat Password</label>
                                <input type="hidden" name="hidepass2" value="<?php echo $info['Pass'] ?>">
                                <input type="password" name="password2" class="form-control" id="password2" 
                                    autocomplete="new-password" placeholder="You can leave this Feild blank" >
                            </div>
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="text" class="form-control" name="email" id="email" autocomplete="off" value="<?php echo $info['Email'] ?>" 
                                    placeholder="Enter Your Email" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Full Name</label>
                                <input type="text" class="form-control" name="fullName" id="email" autocomplete="off" value="<?php echo $info['FullName'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="avatar">Avatar</label>
                                <input type="file" class="form-control" name="avatar" id="avatar">
                            </div>
                            <button type="submit" class="btn btn-primary" name="signup">Save</button>
                        </form>
                    </div>
                </div>
                <?php
            }
            
        } elseif ($do == 'Update'){ // update page
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $userid     = $_POST['userid']; 
                $username   = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                $email      = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $fullName   = filter_var($_POST['fullName'], FILTER_SANITIZE_STRING);
                $pass1      = empty($_POST['password1']) ? $_POST['hidepass1'] : sha1($_POST['password1']);
                $pass2      = empty($_POST['password2']) ? $_POST['hidepass2'] : sha1($_POST['password2']);

                $avatarName = $_FILES['avatar']['name'];
                $avatarTmp  = $_FILES['avatar']['tmp_name'];
                $avatarSize = $_FILES['avatar']['size'];
                $avatarType = $_FILES['avatar']['type'];

                $allowedExtentions = ['jpg', 'jpeg', 'png', 'gif'];

                $explodAvatar = explode('.', $avatarName);
                $extentionAvatar = strtolower(end($explodAvatar));

                
                // array from errors
                $formError = [];

                // check error feild
                if (empty($username)) {
                    $formError[] = 'The User Name Feild Is <b>Empty</b>';
                } elseif(strlen($username) < 3) {
                    $formError[] = 'You Must Write Name More Than <b>3 Characters</b>';
                }
                if (empty($email)) {
                    $formError[] = 'The Email Feild Is <b>Empty</b>';
                } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) != true) {
                    $formError[] = 'This Is Email <b> Invalid </b>';
                }
                if (empty($fullName)) {
                    $formError[] = 'The Full Name Feild Is <b>Empty</b>';
                } elseif(strlen($fullName) < 6) {
                    $formError[] = 'The Full Name Must Be More Than <b>4 Character</b>';
                }
                if ($pass1 !== $pass2) {
                    $formError[] = 'The Password Is <b>Not Identical</b>';
                }
                if (!empty($avatarName) && !in_array($extentionAvatar, $allowedExtentions)) {
                    $formError[] = 'This Is Iamge Is Not <strong>Not Allow</strong>';
                }
                if (empty($avatarName)) {
                    $formError[] = 'This Is Input Field Of Image Is <strong>Empty</strong>';
                }
                // check of the size Image
                if ($avatarSize > 4194304) {
                    $formError[] = 'This Image Is More Than <strong>4MB</strong>';
                }

                // loop from errors
                echo '<div class="container py-5">';
                    foreach ($formError as $error) {
                        echo '<div class="alert alert-danger">' . $error . '</div>';
                    }
                    
                    // check if the form error don't have any error
                    if (empty($formError)) {
                        ?>
                        <h1 class="text-center">Update Page</h1>
                        <?php
                            // this is query use to check if the username is exist in another id 
                            $stmt = $connect->prepare("SELECT * FROM users WHERE UserName = ? AND UserID != ?");
                            $stmt->execute([$username, $userid]);
                            $count = $stmt->rowCount();
                            // the count more than 0 this main the username exist in anthor id 
                            // and the main use can't used this is username bescase this is unique value
                            if ($count > 0) {
                                echo '<div class="container">';
                                    $theMsg = '<div class="alert alert-danger">Sorry This User Is Exists</div>';
                                    redirectHome($theMsg, 'back');
                                echo '</div>';
                            } else {
                                $avatar = rand(0, 1000000) . '_' . $avatarName;
                                move_uploaded_file($avatarTmp, 'admin/upload/avatar/' . $avatar);
                                $stmt = $connect->prepare("UPDATE users SET UserName = ?, Pass = ?, Email = ?, FullName = ?, avatar = ? WHERE UserID = ?");
                                $stmt->execute([$username, $pass1, $email, $fullName, $avatar, $userid]);
                                $count = $stmt->rowCount();
                                if ($count > 0) {
                                    $_SESSION['user'] = $username;
                                    $theMsg = '<div class="alert alert-success"> You Update '. $count . ' Record</div>';
                                    redirectHome($theMsg, 'back');
                                }
                            }
                    }
                    
                echo '</div>';
               
            } else {
                echo '<div class="container py-5">';
                    $theMsg = '<div class="alert alert-danger"> You Can\'t Access this Page Directly</div>';
                    redirectHome($theMsg);
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