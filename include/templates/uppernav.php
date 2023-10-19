<div class="upper-nav bg-white">
    <div class="container p-2">
        <?php
        // check if is set session of user
            if (isset($_SESSION['user'])) {
                // check if the user approved
                $checkstatus = checkUserStatus($_SESSION['user']);
                if ($checkstatus != 1) {
                    $username = $_SESSION['user'];
                    // echo $username;
                    $user = fetchdata('*', 'users', "WHERE UserName = '{$username}'", 'UserID', 'ASC');
                    ?>
                        <nav class="navbar navbar-expand-lg navbar-light">
                            <?php
                                if (!empty($user['avatar'])) {
                                  echo '<img src="admin/upload/avatar/' . $user['avatar'] . '" alt="" class="rounded-circle">';
                                } else {
                                    echo '<img src="admin/upload/avatar/default.png" alt="" class="rounded-circle">';
                                }
                            ?>
                            
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav mr-auto">
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                                            <?php echo $_SESSION['user'] ?>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="profile.php">Profile</a>
                                            <a class="dropdown-item" href="new-ad.php">New Item</a>
                                            <a class="dropdown-item" href="profile.php?do=Edit">Edit Profile</a>
                                            <a class="dropdown-item" href="profile.php#my-item">My Item</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="logout.php">Logout</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                        
                    <?php
                } else {
                    // if not approved echo this Msg
                    ?>
                    <div class="alert alert-danger float-left mt-3">The personal data is under review by the administration and a response will be made within 24 hours</div>
                    <div class="float-right mt-4">
                        <a href="logout.php">Logout</a>
                    </div>
                    <!-- this is div for delete the float -->
                    <div class="clearfix"></div>
                    <?php
                }
                // if not isset session of user 
            } else {
                ?>
                <div class="text-right">
                    <a href="login.php" class="">Login | Signup</a>
                </div>
                <?php
            }
        ?>
    </div>
</div>