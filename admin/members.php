<?php
    //////////////////////////////////////////////////////////////
    // this Is Page For [ Manage | Edit | Add | Delete Members ] //
    //////////////////////////////////////////////////////////////
    ob_start(); // output buffering start
    session_start();
    // title page
    $title = 'Members';
    if ($_SESSION['username']) {
        include 'init.php';
        // check request method get[do] and is number redirect this url else redirect to manege
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        if ($do == 'Manage') { // Manage Page
            // this request to pending members
            // is exists pending members in other page
            // add to requsest the varaible query
            // else varaible query null
            // this pending page For dashbord to show pending members in other browse
            $query = isset($_GET['page']) && $_GET['page'] == 'pending' ? 'AND RegStatus = 0' : '';
            // request select all data from database
            $stmt = $connect->prepare("SELECT * FROM users where GroupID != 1 $query ORDER BY UserID DESC");
            // executed this request
            $stmt->execute();
            // fetch all data form database
            $users = $stmt->fetchAll();
            // check exists users
            if (!empty($users)) {
                ?>
                <!-- preduce table -->
                <h1 class="text-center">Manage Members Page</h1>
                <div class="manage-member">
                    <div class="container text-center">
                        <div class="row">
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="col-1">#ID</th>
                                        <th class="col-1">avatar</th>
                                        <th class="col-2">User Name</th>
                                        <th class="col-2">E-Mail</th>
                                        <th class="col-2">Full Name</th>
                                        <th class="col-2">Registerd Date</th>
                                        <th class="col-1">Control</th>
                                    </tr>
                                </thead>
                                <?php
                                    // put data in database into my table that where in Manage page
                                    foreach ($users as $user) {
                                        echo '<tr>';
                                            echo '<td class="align-middle">' . $user['UserID'] . '</td>';
                                            echo '<td class="align-middle">';
                                                // check if no image echo default image else echo the image exist in database by dirfile of file upload
                                                if (empty($user['avatar'])) {
                                                    echo '<img src="upload/avatar/default.png" alt="">';
                                                } else {
                                                    echo '<img src="upload/avatar/'.$user['avatar'].'" alt="">';
                                                }
                                            echo '</td>';
                                            echo '<td class="align-middle">' . $user['UserName']  . '</td>';
                                            echo '<td class="align-middle">' . $user['Email']     . '</td>';
                                            echo '<td class="align-middle">' . $user['FullName']  . '</td>';
                                            echo '<td class="align-middle">' . $user['DateEntry'] . '</td>';
                                            echo '<td class="align-middle">
                                                <a class="btn btn-success mb-1" href="members.php?do=Edit&userid=' . $user['UserID'] . '" role="button"><i class="fas fa-edit"></i> Edit</a>
                                                <a class="btn btn-danger confirm mb-1" href="members.php?do=Delete&userid=' . $user['UserID'] . '" role="button"><i class="fas fa-trash-alt"></i> Remove</a>';
                                                // if RegStatus = 0
                                                // echo the btn activate for peinding members
                                                if ($user['RegStatus'] == 0) {
                                                    echo '<a class="btn btn-info activate" href="members.php?do=Activate&userid=' . $user['UserID'] . '" role="button"><i class="fas fa-check"></i> Activate</a>';
                                                } 
                                            '</td>';
                                        echo '</tr>';
                                    }
                                ?>
                            </table>
                            <a href="?do=Add" class="btn btn-primary"><i class="fas fa-plus"></i> New Member</a>
                        </div>
                    </div>
                </div>
                <?php       
            } else {
                // Print Msg There's Not users To Show
                echo '<div class="container py-5">';
                    echo '<div class="alert alert-info">There\'s Not Users To Show</div>';
                    echo '<a href="?do=Add" class="btn btn-primary"><i class="fas fa-plus"></i> New Member</a>';
                echo '</div>';
            }
                ?>
            <?php 
        } elseif ($do == 'Edit') { // Edit Page
            // check from request of userID Is Exists and Number
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? $_GET['userid'] : 0;
            // select all data consder of user ID
            $stmt = $connect->prepare('SELECT * from users where UserID = ?');
            // execute the query
            $stmt->execute([$userid]);
            // fetch the data and this is the array your key is the faild of database and the value is the data of users
            $user = $stmt->fetch();
            // count of the users
            $count = $stmt->rowCount();
            // check is exists in DB
            if ($count > 0) {
    ?>
            <h1 class="text-center">Edit Page</h1>
            <div class="container">
                <div class="row">
                    <form class="edit-page my-0  w-75 " action="?do=Update" method="POST">
                        <!-- start faild id is hidden -->
                        <input type="hidden" name="id" value="<?php echo $userid ?>">
                        <!-- end faild id  -->
                        <!-- start faild username -->
                        <div class="form-group row ">
                            <label for="username" class="col-sm offset-1 col-form-label">User Name</label>
                            <div class="col-sm-9 ">
                                <!-- the key of the row in the value is the User Name of field in database -->
                                <input type="text" class="form-control" name="username" id="username" value="<?php echo $user['UserName']; ?>" autocomplete="off" required>
                            </div>
                        </div>
                        <!-- end faild username -->
                        <!-- start password faild -->
                        <div class="form-group row">
                            <label for="password" class="col-sm offset-1 col-form-label">Password</label>
                            <div class="col-sm-9 ">
                                <!-- this trick off password -->
                                <input type="hidden" class="form-control" name="oldpassword" value="<?php echo $user['Pass'] ?>">
                                <input type="password" class="form-control" name="newpassword" id="password" autocomplete="new-password" placeholder="Leave Blank If You Don't Change">
                            </div>
                        </div>
                        <!-- end password faild -->
                        <!-- start email faild -->
                        <div class="form-group row">
                            <label for="email" class="col-sm  offset-1 col-form-label">E-Mail</label>
                            <div class="col-sm-9">
                                <!-- the key of the row in the value is the Email of field in database -->
                                <input type="email" class="form-control" name="email" id="email" value="<?php echo $user['Email'] ?>" autocomplete="off" required>
                            </div>
                        </div>
                        <!-- end email faild -->
                        <!-- start full name faild-->
                        <div class="form-group row">
                            <label for="fullname" class="col-sm offset-1 col-form-label">Full Name</label>
                            <div class="col-sm-9">
                                <!-- the key of the row in the value is the Full Name of field in database -->
                                <input type="text" class="form-control" name="fullname" value="<?php echo $user['FullName'] ?>" id="fullname" autocomplete="off" required>
                            </div>
                        </div>
                        <!-- end full name faild -->
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
                // this msg preduce while users open this page directly or is not exists in database
                echo '<div class="container py-5">';
                    $theMsg = '<div class="alert alert-danger"> You Can\'t Access this Page Directly </div>';
                    redirectHome($theMsg);
                echo '</div>';
            }
        }elseif($do == 'Add'){ // Add Page
    ?>
        <h1 class="text-center">Add Page</h1>
        <div class="container">
            <form class="my-0 w-75 " action="?do=Insert" method="POST" enctype="multipart/form-data">
                <!-- start username faild -->
                <div class="form-group row">
                    <label for="username" class="col-sm offset-1 col-form-label">User Name</label>
                    <div class="col-sm-9 ">
                        <!-- the key of the row in the value is the name of field in database -->
                        <input type="text" class="form-control" name="username" id="username"  autocomplete="off"  placeholder="User Name" required>
                    </div>
                </div>
                <!-- end username faild -->
                <!--start password faild -->
                <div class="form-group row">
                    <label for="password" class="col-sm offset-1 col-form-label">Password</label>
                    <div class="col-sm-9 ">
                            <input type="password" class="form-control password" name="password" id="password" autocomplete="new-password" placeholder="Password" required>
                            <i class="fas fa-eye show-pass"></i>
                    </div>
                </div>
                <!--end password faild -->
                <!-- start email faild -->
                <div class="form-group row">
                    <label for="email" class="col-sm offset-1 col-form-label">E-Mail</label>
                    <div class="col-sm-9">
                        <!-- the key of the row in the value is the name of field in database -->
                        <input type="email" class="form-control" name="email" id="email" autocomplete="off" placeholder="E-Mail" required>
                    </div>
                </div>
                <!-- end email faild -->
                <!-- start fullname faild -->
                <div class="form-group row">
                    <label for="fullname" class="col-sm offset-1 col-form-label">Full Name</label>
                    <div class="col-sm-9">
                        <!-- the key of the row in the value is the name of field in database -->
                        <input type="text" class="form-control" name="fullname" id="fullname" autocomplete="off" placeholder="Full Name" required>
                    </div>
                </div>
                <!-- end fullname faild -->
                <!-- Start Avatar Faild -->
                <div class="form-group row">
                    <label for="avatar" class="col-sm offset-1 col-form-label">Image Profile</label>
                    <div class="col-sm-9">
                        <!-- the key of the row in the value is the name of field in database -->
                        <input type="file" class="form-control" name="avatar" id="avatar" autocomplete="off" >
                    </div>
                </div>
                <!-- End Avatar Faild -->
                <!-- start add member btn -->
                <div class="form-group row  ">
                    <div class="col-sm-3 offset-sm-3 ">
                        <input type="submit" value="Add Member" class="btn btn-primary">
                    </div>
                </div>
                <!-- end add member btn -->
            </form>
        </div>
    <?php
    }elseif($do == 'Insert'){ // Insert page
        // check request method post
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // the info of upload files
            $avatarName     = $_FILES['avatar']['name'];
            $avatarType     = $_FILES['avatar']['type'];
            $avatarTmp      = $_FILES['avatar']['tmp_name'];
            $avatarSize     = $_FILES['avatar']['size'];
            // list of type image allow upload
            $avaterExtenstionAllow = ['jpg', 'jpeg', 'png', 'gif'];
            // get end of extention of the image upload
            $explodeAvatarName = explode('.', $avatarName);
            $avatarExtentstion =  strtolower(end($explodeAvatarName));     
            // value get from input
            $username   = $_POST['username'];
            $password   = $_POST['password'];
            $email      = $_POST['email'];
            $fullname   = $_POST['fullname'];
            // this is hashpass
            $hashpass = sha1($password);
            // errors array 
            $formErrors = [];
            if (empty($username)) {
                $formErrors[] = 'The User Name Is <strong>Empty</strong>';
            }
            if (empty($password)) {
                $formErrors[] = 'The Password Is  <strong>Empty</strong>';
            }
            if (empty($email)) {
                $formErrors[] = 'The E-mail Is  <strong>Empty</strong>';
            }
            if (empty($fullname)) {
                $formErrors[] = 'The Full Name Is  <strong>Empty</strong>';
            }
            // we shoud add the two condtion because check the first the input is not empty and the extention is exist in array of exstentions
            if (!empty($avatarName) && !in_array($avatarExtentstion, $avaterExtenstionAllow)) {
                $formErrors[] = 'This Is Iamge Is Not <strong>Not Allow</strong>';
            }
            if (empty($avatarName)) {
                $formErrors[] = 'This Is Input Field Of Image Is <strong>Empty</strong>';
            }
            // check of the size Image
            if ($avatarSize > 4194304) {
                $formErrors[] = 'This Image Is More Than <strong>4MB</strong>';
            }
            // loop in array error
            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            // check all input not empty
            if (empty($formErrors)) {
                // funtion checkItem if User Exist In DataBase
                $check = checkItem('UserName', 'users', $username);
                if ($check == 1) {
                    $theMsg = '<div class="alert alert-danger">Sorry! Change The User Name Because Is Exist In DataBase</div>';
                    redirectHome($theMsg, 'back', 6);
                } else {
                    $avatar = rand(0, 1000000) . '_' . $avatarName;
                    move_uploaded_file($avatarTmp, "upload/avatar/" . $avatar);
                    // insert data info members
                    // into () in table users is the feild of data base 
                    // the value is the any thing of name from me
                    $stmt = $connect->prepare("INSERT INTO 
                                                    users (UserName, Pass, Email, FullName, RegStatus, DateEntry, avatar) 
                                                VALUES 
                                                    (:zusername, :zpassword, :zmail, :zname, 1, now(), :zavatar)");

                    // this array is deferent this array is assosative array
                    // the key is the values of execution and the value is the data comming from users
                    $stmt->execute([
                        'zusername' => $username,
                        'zpassword' => $hashpass,
                        'zmail'     => $email,
                        'zname'     => $fullname,
                        'zavatar'   => $avatar
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
            }
        } else {
            echo '<div class="container py-5">';
                $theMsg = '<div class="alert alert-danger"> You Can\'t Access this Page Directly </div>';
                redirectHome($theMsg);
            echo '</div>';
        }
    } elseif ($do == 'Update') { // Update page

        // check if the method request post 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {      
            // Get Info Users Frm Database
            $id         = $_POST['id'];
            $username   = $_POST['username'];
            $email      = $_POST['email'];
            $fullname   = $_POST['fullname'];
            // tric password
            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']) ;
            // validation the input
            $formErrors = [];
            if (empty($username)) {
                $formErrors[] = 'The User Name Is  <strong>Empty</strong>';
            }
            if (empty($email)) {
                $formErrors[] = 'The E-mail Is  <strong>Empty</strong>';
            }
            if (empty($fullname)) {
                $formErrors[] = 'The Full Name Is  <strong>Empty</strong>';
            }
            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            // check at the error => is fount don't acess to update data else update data in database
            if (empty($formErrors)) {
                // check in update if user name is exists in DB and user ID != this user name ID
                $stmt2 = $connect->prepare("SELECT * FROM users WHERE UserName = ? AND UserID != ?");
                $stmt2->execute([$username, $id]);
                $count = $stmt2->rowCount();
                if ($count == 1) {
                    echo '<div class="container py-5">';
                        $theMsg = '<div class="alert alert-danger">Sorry This User Is Exists</div>';
                        redirectHome($theMsg, 'back');
                    echo '</div>';
                } else {
                    // Update Info Users Into Database by UserID
                    $stmt = $connect->prepare('UPDATE users set UserName = ?, Email = ?, FullName =?, Pass = ? where UserID =?');
                    // execute the prepare statment
                    $stmt->execute([$username, $email, $fullname, $pass, $id]);
                    // The row Count
                    $count = $stmt->rowCount();             
                    echo '<h1 class="text-center">Update Page</h1>';
                    echo '<div class="container">';
                        $theMsg = '<div class="alert alert-success"> You Update '. $count . ' Record</div>';
                        redirectHome($theMsg, 'back');
                    echo '</div>';
                }
            } 
        } else {
            echo '<div class="container py-5">';
                $theMsg = '<div class="alert alert-danger"> You Can\'t Access this Page Directly</div>';
                redirectHome($theMsg);
            echo '</div>';
        }
    } elseif ($do == 'Delete'){ // Delete Members Page
        // check URL request get userid
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        // check is userid is exist in DB
        $checked = checkItem('UserID', 'users', $userid);
        // if checked > 0 request from database to Delete User By User ID
        if ($checked > 0) {
            $stmt = $connect->prepare('DELETE FROM users WHERE UserID = :zuserid');
            // we use BlindParam to connect :zuserid to Varaible $userid
            $stmt->bindParam('zuserid', $userid);
            $stmt->execute();
            $count = $stmt->rowCount();
            echo '<h1 class="text-center">Delete Page</h1>';
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
    } elseif ($do == 'Activate'){ // Activate Page
        // check URL request get userid
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        // check is userid is exist in DB
        $check = checkItem('UserID', 'users', $userid);
        // is Exists
        if ($check > 0) {
            // convert regstatus to approve
            $stmt = $connect->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
            // execute stmt
            $stmt->execute([$userid]);
            // count rows
            $count = $stmt->rowCount();
            echo '<h1 class="text-center">Activate Page</h1>';
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
        header('location: index.php');
    }
    ob_end_flush(); // end buffering and flush
?>
