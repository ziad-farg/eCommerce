<?php
    $noNavbar = ''; // this varaible to hide navebar
    $title = 'logout';
    session_start();
    if (isset($_SESSION['username'])) {
        header('location: dashbord.php');
    }
    include 'init.php';
    // check method if user comming from http Post Request
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userName = $_POST['user'];
        $password = $_POST['pass'];
        $hashpass = sha1($password);
        // check if user is found and Are You Admain
        $stmt = $connect->prepare(
            'SELECT UserID, UserName , Pass FROM users WHERE UserName = ? AND Pass = ? AND GroupID = 1 LIMIT 1'
        );
        $stmt->execute([$userName, $hashpass]);
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        // if count > 0 The Database contain record of this user
        if ($count > 0) {
            $_SESSION['username'] = $userName;
            $_SESSION['ID'] = $row['UserID'];
            header('location: dashbord.php');
        }
    }
?>
<div class="container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <h4 class="text-center">Admin Login</h4>
        <input class="form-control" type="text" name='user' autocomplete="off" placeholder="User Name">
        <!-- we use autocomplete for name to don't complete the name -->
        <input class="form-control" type="password" name="pass" autocomplete="new-password" placeholder="Password">
        <!-- we use autocomplete new-password for password to don't complete the password and this used with google chrome -->
        <input class="btn btn-primary btn-block" type="submit" value="Login">
    </form>
</div>
<?php include $tmp . 'footer.php'; ?>