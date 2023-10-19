<div class="main-nav">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark  ">
        <div class="container">
            <a class="navbar-brand" href="#">ECommerce</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapes" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapes">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">Home</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <?php
                        // get categories in link 
                        $cats = getAllFrom('*', 'categories','WHERE parent = 0','ID','ASC');
                        // when get categories name we attach category id with name
                        // we use catid to get items
                        foreach ($cats as $cat) {
                            echo '<li class="nav-item">
                                    <a href="categories.php?catid=' . $cat['ID'] .'" class="nav-link">' . $cat['Name'] . '</a>
                                </li>';
                            $childs = getAllFrom('*', 'categories', "WHERE parent = {$cat['ID']}", 'ID');
                            if (!empty($childs)) {
                                ?>
                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav mr-auto">
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle arrow" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                <?php
                                                    foreach ($childs as $child) {
                                                        echo '<a class="dropdown-item" href="categories.php?catid='.$child['ID'].'">'.$child['Name'].'</a>';
                                                    }
                                                ?>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <?php
                            }
                        }
                            
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</div>