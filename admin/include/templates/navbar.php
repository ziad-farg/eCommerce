<nav class="navbar navbar-expand-lg navbar-dark bg-dark  ">
        <div class="container ">
            <a class="navbar-brand" href="#">eCommerce</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapes" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse " id="collapes">
                <ul class="navbar-nav mr-auto w-100">
                <li class="nav-item">
                    <a class="nav-link" href="dashbord.php">
                        <?php echo langs('HOME-ADMIN'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="categories.php">
                        <?php echo langs('CATOGERIES'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="items.php">
                        <?php echo langs('ITEMS'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="members.php">
                        <?php echo langs('MEMBERS'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="comment.php">
                        <?php echo langs('COMMENT'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <?php echo langs('STATISTICS'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <?php echo langs('LOGS'); ?>
                    </a>
                </li>
                <li class="nav-item dropdown ml-auto text-right">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                    <?php 
                        $admin = fetchdata('*', 'users', 'WHERE GroupID = 1', 'UserID');
                        echo $admin['UserName'];
                    ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="../index.php">Visit Shop</a>
                        <a class="dropdown-item" href="members.php?do=Edit&userid=<?php echo $_SESSION['ID']; ?>">Edit Profile</a>
                        <a class="dropdown-item" href="#">Setting</a>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </li>
                </ul>
            </div>
        </div>
    </nav>


