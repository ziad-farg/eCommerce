<?php
ob_start();
session_start();
if (isset($_SESSION['username'])) {
    // Title Page
    $title = 'Dashbord';
    // include header
    include 'init.php';

    // Varaible Count Of Users To Calc Count Of Latest Users
    $countuser = 6;
    // Lastest Users Is Array Of Users
    $lasttUser = latestItems('*', 'users', 'UserID', $countuser);
    // Varaible Count Of Itmes To Calc Count Of Latest Items
    $countitmes = 6;
    // Varaible Latest Items Is Array Of Items
    $latestitems = latestItems('*', 'items', 'Item_ID', $countitmes);
    // count of comments this show in latest comments
    $countcom = 4;
    ?>
    <div class="home-stat text-center">

        <div class="container  ">

            <h1>Dashbord</h1>

            <div class="row">
                <!-- Start Total Members -->
                <div class="col-md">
                    <div class="stat st-member">
                        <i class="fas fa-users"></i>
                        <div class="info">
                            Total Members
                            <span><a href="members.php"><?php echo checkItem('UserID', 'users') ?></a></span>
                        </div>
                    </div>
                </div>
                <!-- End Total Members -->

                <!-- Start Painding Members -->
                <div class="col-md">
                    <div class="stat st-pending">
                        <i class="fas fa-user-plus"></i>
                        <div class="info">
                            Pending Members
                            <span><a href="members.php?do=Manage&page=pending"><?php echo checkItem('RegStatus', 'users', 0)?></a></span>
                        </div>
                    </div>
                </div>
                <!-- End Pending Members -->

                <!-- Start Totla Itmes -->
                <div class="col-md">
                    <div class="stat st-item">
                    <i class="fas fa-tags"></i>
                        <div class="info">
                            Total Items
                            <span><a href="items.php"><?php echo checkItem('Item_ID', 'items'); ?></a></span>
                        </div>
                    </div>
                </div>
                <!-- End Total Items -->

                <!-- Start Total Comments -->
                <div class="col-md">
                    <div class="stat st-comment">
                        <i class="fas fa-comments"></i>
                        <div class="info">
                            Total Comment
                            <span><a href="comment.php"><?php echo checkItem('C_ID', 'comments') ?></a></span>
                        </div>
                    </div>
                </div>
                <!-- End Total Comments -->

            </div>

        </div>

    </div>
    
    <div class="home-latest">

        <div class="container">

            <!-- Start First Row -->
            <div class="row">
                
                <!-- Start Card lastest Users -->
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-users"></i> Latest <?php echo $countuser; ?> Register Users
                            <span class="toggle-info float-right">
                                <i class="fas fa-minus"></i>
                            </span>
                        </div>
                        <div class="card-body">
                            <ul>
                                <?php
                                    // check if user is exist
                                    if (!empty($lasttUser)) {
                                        // Preduce User In List
                                        foreach ($lasttUser as $user) {
                                            echo '<li>';
                                                echo $user['UserName'];
                                                // Button To Edit Users
                                                echo '<a href="members.php?do=Edit&userid=' . $user['UserID'] . '"><span class="btn btn-success"><i class="fas fa-edit"></i> Edit</span></a>';
                                                // Check If User Is Not Approved Show This Button
                                                if ($user['RegStatus'] == 0) {
                                                    echo '<a class="btn btn-info activate" href="members.php?do=Activate&userid=' . $user['UserID'] . '" role="button"><i class="fas fa-check"></i> Activate</a>';
                                                }
                                            echo '</li>';
                                        }
                                    } else {
                                        // Print Msg There's Not Users To Show
                                        echo '<div class="p-3">There\'s Not Items To Show</div>';
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>              
                </div>
                <!-- End Card lastest Users -->
                
                <!-- Start Card lastest Items -->
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-tags"></i> Latest <?php echo $countitmes; ?> Items
                            <span class="toggle-info float-right">
                                <i class="fas fa-minus"></i>
                            </span>
                        </div>
                        <div class="card-body">
                            <ul>
                                <?php 
                                    // Check If Exist Items 
                                    if (!empty($latestitems)) {
                                        // Preduce Items In List
                                        foreach ($latestitems as $item) {
                                            echo '<li>';
                                                // Items Name
                                                echo $item['Name'];
                                                // Button Edit Items
                                                echo '<a href="items.php?do=Edit&itemid=' . $item['Item_ID'] . '" class="btn btn-success"><i class="fas fa-edit"></i> Edit</a>';
                                                // Check If Items Is Not Approved Show This Button
                                                if ($item['Approved'] == 0) {
                                                    echo '<a href="items.php?do=Approved&itemid=' . $item['Item_ID'] . '" class="btn btn-info mr-2"><i class="fas fa-check"></i> Approved</a>';
                                                }
                                            echo '</li>';
                                        }
                                    } else {
                                        // Print Msg There's Not Itmes To Show
                                        echo '<div class="p-3">There\'s Not Items To Show</div>';
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>              
                </div>
                <!-- End Card lastest Items -->

            </div>
            <!-- End First Row -->
            
            <!-- Start Second Row -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">

                        <div class="card-header">
                            <i class="fas fa-comments"></i> Latest <?php echo $countcom; ?> Comments
                            <span class="toggle-info float-right">
                                <i class="fas fa-minus"></i>
                            </span>
                        </div>

                        <div class="card-body">
                            
                            <?php
                                // statment comments
                                $stmt = $connect->prepare("SELECT
                                                                comments.*, users.UserName
                                                            FROM
                                                                comments 
                                                            INNER JOIN
                                                                users
                                                            ON
                                                                users.UserID = comments.User_ID
                                                            ORDER BY C_ID DESC
                                                            LIMIT $countcom");
                                // execute statment comments
                                $stmt->execute();
                                // fetch all comment from DB
                                $comments =  $stmt->fetchAll();
                                // check if comments is exist
                                if (!empty($comments)) {
                                    // preduce comments
                                    echo '<div class="comment container row py-2">';
                                        foreach ($comments as $comment) {
                                            echo '<a href="comment.php?do=Edit&comid=' . $comment['C_ID'] . '" class="col-3 d-flex align-items-center justify-content-center  mb-2 ">' . $comment['UserName'] . '</a>';
                                            echo '<p class="col-9 p-2 mb-2">'. $comment['Comments'] .'</p>'; 
                                            }
                                    echo '</div>';
                                } else {
                                    // Print Msg There's Not comments To Show
                                    echo '<div class="p-3">There\'s Not Comments To Show</div>';
                                }
                            ?>
                            
                        </div>

                    </div>              
                </div>
            </div>
            <!-- End Second Row -->
            
        </div>
        
    </div>
    <?php
    include $tmp . 'footer.php';
} else {
    header('location: index.php');
}
ob_end_flush();