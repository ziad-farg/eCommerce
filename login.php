<?php 
    ob_start();
    session_start();
    $title = 'login'; // page title
    include 'init.php';
    // check if session exist
    if (isset($_SESSION['user'])) {
        header('location: index.php');
    }
    // check request method post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // login process
        if(isset($_POST['login'])){
            // get data of the form login
            $user = $_POST['username'];
            $pass = $_POST['password'];
            $hashpass = sha1($pass);
            // check data that get from form is exist
            $stmt = $connect->prepare("SELECT * FROM users WHERE UserName = ? AND Pass = ?");
            $stmt->execute([$user, $hashpass]);
            $info = $stmt->fetch();
            $count = $stmt->rowCount();
            if ($count > 0) {
                $_SESSION['user'] = $user;
                $_SESSION['userid'] = $info['UserID'];
                header('location: index.php');
                exit();
            }
        } else {
            // signup process
            $formError = []; // array error msg

            $username   = $_POST['username'];
            $password1  = $_POST['password1']; 
            $password2  = $_POST['password2']; 
            $email      = $_POST['email'];
            $hashpass   = sha1($password1);

            // input faild username
            if (isset($username)) {
                // filter user name string
                $user = filter_var($username, FILTER_SANITIZE_STRING);
                // check lenth user more than 4
                if (strlen($user) < 4) {
                    $formError[] = 'You Must Write More 4 Characters';
                }
            }
            // input faild password
            if (isset($password1) && isset($password2)) {
                if (empty($_POST['password1'])) {
                    $formError[] = 'You Must Write A Password';
                }
                $hashpass1 = sha1($password1);
                $hashpass2 = sha1($password2);
                // check the two input faild is identical
                if ($hashpass1 !== $hashpass2) {
                    $formError[] = 'You Must Write Identical Password Corrected';
                }
            }
            if (isset($email)) {
                $filteremail = filter_var($email, FILTER_SANITIZE_EMAIL);
                if (filter_var($filteremail, FILTER_VALIDATE_EMAIL) != true) {
                    $formError[] = 'You Must Write Valid Email';
                }
            }
            // check if form error is empty
            if (empty($formError)) {
                // check user is exist or not
                $check = checkItem('UserName', 'users', $username);
                // if check == 1 that mean the user exist
                if ($check == 1) {
                    $formError[] = 'Sorry! This User Name is Exist';
                } else {
                    // add the user in data base
                    $stmt = $connect->prepare("INSERT INTO 
                                                    users (UserName, Pass, Email, RegStatus, DateEntry)
                                                VALUES
                                                     (:zuser, :zpass, :zemail, 0, now())");
                    $stmt->execute([
                        'zuser'     => $username,
                        'zpass'     => $hashpass,
                        'zemail'    => $email
                    ]);
                    $success = 'Congratoration';
                }
            }
        }
    }
?>

<h1 class="text-center head"><span class="active" data-class="login">Login</span> | <span data-class="signup">Signup</span></h1>
<div class="container ">
    <!-- start login form -->
    <form class="w-50 m-auto login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group">
            <!-- Start input email -->
            <label for="username">User Name</label>
            <input 
                type="text" 
                class="form-control" 
                id="username"
                name="username"
                required>
        </div>
        <!-- End input email -->
        <!-- Start input password -->
        <div class="form-group">
            <label for="password">Password</label>
            <input 
                type="password"
                class="form-control" 
                name="password"
                id="password" 
                autocomplete="new-password"
                required>
        </div>
        <!-- End input password -->
        <button type="submit" class="btn btn-primary" name="login">Login</button>
    </form>
    <!-- end login form -->
    <!-- start signup -->
    <form class="w-50 m-auto signup" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
        <div class="form-group">
            <label for="username">User Name</label>
            <input 
            pattern=".{4,}"
            title="You Must write More 4 Charaters"
            type="text"
            class="form-control"
            name="username" 
            id="username" 
            autocomplete="off"
            required>
        </div>
        <div class="form-group">
            <label for="password1">Password</label>
            <input
            minlength="4"
            type="password"
            name="password1"
            class="form-control" 
            id="password1" 
            autocomplete="new-password"
            required>
        </div>
        <div class="form-group">
            <label for="password2">Repeat Password</label>
            <input 
            type="password"
            class="form-control"
            name="password2"
            id="password2" 
            autocomplete="new-password"
            required>
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <input 
            type="text"
            class="form-control"
            name="email"
            id="email" 
            autocomplete="off"
            required>
        </div>
        <button type="submit" class="btn btn-success" name="signup">Sign Up</button>
    </form>
    <!-- End signup -->
    <div class="msg text-center my-3">
        <?php
            // forloop at form errors
            if (isset($formError)) {
                foreach($formError as $error){
                    echo "<p class='alert alert-danger w-50 m-auto'> $error </p>";
                }
            } 
            // is exist varable success that mean the user added in database
            if (isset($success)) {
                echo "<p class='alert alert-success w-50 m-auto'> $success </p>";
            }
        ?>
    </div>
</div>

<?php 
    include $tmp . 'footer.php';
    ob_end_flush();
?>