<?php

    // function get all from [v1.0] 
    // this function get any information from any table
    function getAllFrom($faild, $table, $where = null, $orederFaild, $ordering = 'DESC'){
        global $connect;
        $stmt = $connect->prepare("SELECT $faild FROM $table $where ORDER BY $orederFaild $ordering");
        $stmt->execute();
        $all =  $stmt->fetchAll();
        return $all;
    }
    // function fetch data [v1.0] 
    // this function get any information from any table
    function fetchdata($faild, $table, $where = null, $orederFaild, $ordering = 'DESC'){
        global $connect;
        $stmt = $connect->prepare("SELECT $faild FROM $table $where ORDER BY $orederFaild $ordering");
        $stmt->execute();
        $all =  $stmt->fetch();
        return $all;
    }
    // function checkUserStatus [v1.0]
    // this function use to check user status is 1 or 0
    // [0] => activate || [1] => Not Activate
    // $user => the user in database

    function checkUserStatus($user){
        global $connect;
        $stmt = $connect->prepare("SELECT * FROM users WHERE UserName = ? AND RegStatus = 0");
        $stmt->execute([$user]);
        $count = $stmt->rowCount();
        return $count;
    }

    /*
    ** Set Title Function [v1.0]
    ** this function use to set title 
    ** if the pages Containing Varable Title
    ** else Set Title Page Default
    */
    function setTitle()
    {
        global $title;
        if (isset($title)) {
            echo $title;
        } else {
            echo 'Default';
        }
    }

    /*
    ** Redirect Home Function [v2.0]
    ** this is function use to get error msg if you open any page directly
    ** Function Accepted Parameters 
    ** theMsg = Echo theMsg
    ** url = that link of page [redirect link by url]
    ** Second = Echo Second Used To Redirect To Home Page after [count] of second 
    */
    function redirectHome($theMsg, $url = null, $second = 3){
        
        if ($url == null) {
            $url = 'index.php';
            $link = 'Home Page';
        } else{
            if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
                $url = $_SERVER['HTTP_REFERER'];
                $link = 'Previous Page';
            } else {
                $url = 'index.php';
                $link = 'Home Page';
            }
        }
        
        echo $theMsg; 
        echo "<div class='alert alert-info'> We Redirect to $link After $second Second</div>";
        header("Refresh:$second; url=$url");
        exit();
    }

    /*
    ** This Is Function checkItem [v2.0]
    ** select Item in database 
    ** This Function Accepted Three Parameters
    ** $select  = Item From Table [Examples: name of Columes]
    ** $from    = Name Of Table [Examples: users, catogery]
    ** $value   = The Value Of Select 
    ** if value is null query select column else addtion to query where item = ?
    */

    /* this is function checkItem [v1.0]
    function checkItem($select, $from, $value) {
        global $connect;
        $statment = $connect->prepare("SELECT $select FROM $from WHERE $select = ?");
        $statment->execute([$value]);
        $count = $statment->rowCount();
        return $count;
    }
    */

    function checkItem($item, $table, $value = '') {
        global $connect;
        if ($value === '') {
            // check if Value is null execute query else addtion [WHERE Item = ?]
            $statment = $connect->prepare("SELECT $item FROM $table");
            $statment->execute();
        } else {
            $statment = $connect->prepare("SELECT $item FROM $table WHERE $item = ?");
            $statment->execute([$value]);
        }
        $count = $statment->rowCount();
        return $count;
    }

    /*
    ** CountItems function [v1.0]
    ** this function use to count of items in row
    ** accepted two patameters
    ** $item = name Of Columes
    ** $table = name Of table used
    */
    function countItems($item, $table){
        global $connect;
        $stat2 = $connect->prepare("SELECT COUNT($item) FROM $table");
        $stat2->execute();
        return $stat2->fetchColumn();
    }



    /*
    ** latestItems function [v1.0]
    ** this function get latest items from database
    ** Accepted 4 Parameters
    ** items = name of faild in database
    ** table = name of table in database
    ** order = name of faild that ordered by
    ** limit = count of users we need to show in dashbord page
    */
    function latestItems($item, $table ,$order, $limit = 5){

        global $connect;

        $stmt = $connect->prepare("SELECT $item FROM $table ORDER BY $order DESC LIMIT $limit");

        $stmt->execute();

        $rows = $stmt->fetchAll();
        
        return $rows;
    }