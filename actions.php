<?php
    include("functions.php");

    if ($_GET['action'] == "loginSignup") {
        
        $error = "";
        
        if (!$_POST['email']) {
            
            $error = "An email address is required.";
            
        } else if (!$_POST['password']) {
            
            $error = "A password is required";
            
        } 
        else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
  
            $error = "Please enter a valid email address.";
            
        }
        
        if ($error != "") {
            
            echo $error;
            exit();
            
        }
        
        
        if ($_POST['loginActive'] == "0") {
            if (!$_POST['firstname']) {
            
            $error = "A first name is required";
            
            } else if (!$_POST['lastname']) {

                $error = "A last name is required";

            } else if (!$_POST['username']) {

                $error = "A username is required";

            } else if ( !preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $_POST['username']) ) {

                $error = "Please enter a valid username.";

            }
            if ($error != "") {
            
            echo $error;
            exit();
            
            }
            
            $query = "SELECT * FROM users WHERE email = '". mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
            $result = mysqli_query($link, $query);
            $uquery = "SELECT * FROM users WHERE username = '". mysqli_real_escape_string($link, $_POST['username'])."' LIMIT 1";
            $uresult = mysqli_query($link, $uquery);
            if (mysqli_num_rows($result) > 0) $error = "That email address is already taken.";
            else if (mysqli_num_rows($uresult) > 0) $error = "That username is already taken.";
            else {
                
                //$query = "INSERT INTO users (`email`, `password`) VALUES ('". mysqli_real_escape_string($link, $_POST['email'])."', '". mysqli_real_escape_string($link, $_POST['password'])."')";
                
                $query = "INSERT INTO users (`email`, `password`, `username`, `firstname`,`lastname`, `description`, `image` ) VALUES ('". mysqli_real_escape_string($link, $_POST['email'])."', '". mysqli_real_escape_string($link, $_POST['password'])."', '". mysqli_real_escape_string($link, $_POST['username'])."', '". mysqli_real_escape_string($link, $_POST['firstname'])."', '". mysqli_real_escape_string($link, $_POST['lastname'])."', '". mysqli_real_escape_string($link, "No bio")."', '". mysqli_real_escape_string($link, "profile.png")."')";
                
                if (mysqli_query($link, $query)) {
                    
                    $_SESSION['id'] = mysqli_insert_id($link);
                    $_SESSION['username'] = $_POST['username'];
                    $query = "UPDATE users SET password = '". md5(md5($_SESSION['id']).$_POST['password']) ."' WHERE id = ".$_SESSION['id']." LIMIT 1";
                    mysqli_query($link, $query);
                    
                    echo 1;
                    
                    
                    
                } else {
                    
                    $error = "Couldn't create user - please try again later";
                    
                }
                
            }
            
        } else {
            
            $query = "SELECT * FROM users WHERE email = '". mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
            
            $result = mysqli_query($link, $query);
            
            $row = mysqli_fetch_assoc($result);
                
            if ($row['password'] == md5(md5($row['id']).$_POST['password'])) {

                echo 1;

                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];

            } else {

                $error = "Could not find that email/password combination. Please try again.";

            }

            
        }
        
         if ($error != "") {
            
            echo $error;
            exit();
            
        }

        
    }
    if ($_GET['function'] == 'logout') {
        session_unset();
        header("Location: index.php");
        exit;
    }
    if ($_GET['function'] == "next") {
        
        
        
    }

    if ($_GET['action'] == 'postComment') {
        
        if (!$_POST['commentContent']) {
                    
            echo "Your comment is empty!";
                    
        } else if (strlen($_POST['commentContent']) > 10000) {
            
            echo "Your comment is too long!";
            
        } else {
            
            mysqli_query($link, "INSERT INTO comments (`parent`, `comment`, `userid`, `datetime`) VALUES ('". mysqli_real_escape_string($link, $_POST['parent'])."', '". mysqli_real_escape_string($link, $_POST['commentContent'])."', ". mysqli_real_escape_string($link, $_SESSION['id']).", NOW())");
            
            $query = "SELECT * FROM comments WHERE comment = '".$_POST['commentContent']."'";
            $result = mysqli_query($query, $link);
            $row = mysqli_fetch_assoc($result);
           
            echo 1;  
        }
        
    }
    if ($_GET['action'] == 'postReply') {
        
        if (!$_POST['replyContent']) {
                    
            echo "Your reply is empty!";
                    
        } else if (strlen($_POST['replyContent']) > 10000) {
            
            echo "Your reply is too long!";
            
        } else {
            
            mysqli_query($link, "INSERT INTO replies (`parent`, `reply`, `userid`, `datetime`) VALUES ('". mysqli_real_escape_string($link, $_POST['parent'])."', '". mysqli_real_escape_string($link, $_POST['replyContent'])."', ". mysqli_real_escape_string($link, $_SESSION['id']).", NOW())");
            
            echo "1";
            
        }
        
    }
    if($_GET['action'] == 'postUpvote'){
        $_commentid = $_POST['commentid'];
        $_userid = $_SESSION['id'];
        $upvoteQuery = "SELECT * FROM upvotes WHERE userid='$_userid' AND commentid='$_commentid'";
        $upvoteResult = mysqli_query($link, $upvoteQuery);
        
        
        if(mysqli_fetch_array($upvoteResult) != false){
            mysqli_query($link, "DELETE FROM `upvotes` WHERE userid='$_userid' AND commentid='$_commentid'");
        }
        else{
            mysqli_query($link, "INSERT INTO upvotes (`commentid`, `userid`, `datetime`) VALUES (".mysqli_real_escape_string($link, $_POST['commentid']).",".mysqli_real_escape_string($link,$_SESSION['id']).", NOW())");
        }
        
        $upvoteCountQuery = "SELECT COUNT(*) as count FROM upvotes WHERE commentid='$_commentid'";
        $upvoteCountResult = mysqli_query($link, $upvoteCountQuery);
        $upvoteCount = mysqli_fetch_assoc($upvoteCountResult);
        echo $upvoteCount['count'];
        
        
    }
    if($_GET['action'] == 'postBuyVote'){
        $_parent = $_POST['parent'];
        $_userid = $_SESSION['id'];
        $switchvote = 0;
        $voteexist = 0;
        $buyQuery = "SELECT * FROM `buyvotes` WHERE userid='$_userid' AND parent='$_parent' AND DATE(datetime) = DATE(NOW())";
        $buyResult = mysqli_query($link, $buyQuery);
        
        
        if(mysqli_fetch_array($buyResult) != false){
            mysqli_query($link, "DELETE FROM `buyvotes` WHERE userid='$_userid' AND parent='$_parent' AND DATE(datetime) = DATE(NOW())");
            $voteexist = 1;
        }
        else{
            mysqli_query($link, "INSERT INTO `buyvotes` (`parent`, `userid`, `datetime`) VALUES ('".mysqli_real_escape_string($link, $_parent)."','".mysqli_real_escape_string($link, $_userid)."', NOW())");
            
            $sellQuery = "SELECT * FROM `sellvotes` WHERE userid='$_userid' AND parent='$_parent' AND DATE(datetime) = DATE(NOW())";
            $sellResult = mysqli_query($link, $sellQuery);
        
            if(mysqli_fetch_array($sellResult) != false){
                mysqli_query($link, "DELETE FROM `sellvotes` WHERE userid='$_userid' AND parent='$_parent' AND DATE(datetime) = DATE(NOW())");
                $switchvote = 1;
            }
        }
        
        $buyTotalVoteQuery = "SELECT COUNT(*) as count FROM buyvotes WHERE parent= '".$_parent."' AND DATE(datetime) = DATE(NOW())";
        $buyTotalVoteResult = mysqli_query($link, $buyTotalVoteQuery);
        $buyCount = mysqli_fetch_assoc($buyTotalVoteResult);
        
        $sellTotalVoteQuery = "SELECT COUNT(*) as count FROM sellvotes WHERE parent= '".$_parent."' AND DATE(datetime) = DATE(NOW())";
        $sellTotalVoteResult = mysqli_query($link, $sellTotalVoteQuery);
        $sellCount = mysqli_fetch_assoc($sellTotalVoteResult);
        
        
        if($buyCount['count'] != 0){
            $x = round(($buyCount['count']/($buyCount['count'] + $sellCount['count'])) * 100, 1);
            $ar = array($x, $switchvote, $voteexist);
            echo json_encode($ar);
        }
        else{
            $ar = array(0, $switchvote, $voteexist);
            echo json_encode($ar);
        }
        
        
    }
    if($_GET['action'] == 'postSellVote'){
        $_parent = $_POST['parent'];
        $_userid = $_SESSION['id'];
        $switchvote = 0;
        $voteexist = 0;
        $sellQuery = "SELECT * FROM `sellvotes` WHERE userid='$_userid' AND parent='$_parent' AND DATE(datetime) = DATE(NOW())";
        $sellResult = mysqli_query($link, $sellQuery);
        
        
        if(mysqli_fetch_array($sellResult) != false){
            mysqli_query($link, "DELETE FROM `sellvotes` WHERE userid='$_userid' AND parent='$_parent' AND DATE(datetime) = DATE(NOW())");
            $voteexist = 1;
        }
        else{
            mysqli_query($link, "INSERT INTO `sellvotes` (`userid`, `parent`, `datetime`) VALUES ('".mysqli_real_escape_string($link, $_userid)."','".mysqli_real_escape_string($link, $_parent)."', NOW())");
            
            //if voted for sell, then remove buy vote
            $buyQuery = "SELECT * FROM `buyvotes` WHERE userid='$_userid' AND parent='$_parent' AND DATE(datetime) = DATE(NOW())";
            $buyResult = mysqli_query($link, $buyQuery);
        
            if(mysqli_fetch_array($buyResult) != false){
                mysqli_query($link, "DELETE FROM `buyvotes` WHERE userid='$_userid' AND parent='$_parent' AND DATE(datetime) = DATE(NOW())");
                $switchvote = 1;
            }
            
            
        }
        
        $buyTotalVoteQuery = "SELECT COUNT(*) as count FROM buyvotes WHERE parent= '".$_parent."' AND DATE(datetime) = DATE(NOW())";
        $buyTotalVoteResult = mysqli_query($link, $buyTotalVoteQuery);
        $buyCount = mysqli_fetch_assoc($buyTotalVoteResult);
        
        $sellTotalVoteQuery = "SELECT COUNT(*) as count FROM sellvotes WHERE parent= '".$_parent."' AND DATE(datetime) = DATE(NOW())";
        $sellTotalVoteResult = mysqli_query($link, $sellTotalVoteQuery);
        $sellCount = mysqli_fetch_assoc($sellTotalVoteResult);
        
        if($sellCount['count'] != 0){
            $x = round(($sellCount['count']/($buyCount['count'] + $sellCount['count'])) * 100, 1);
            $ar = array($x, $switchvote, $voteexist);
            echo json_encode($ar);
        }
        else{
            $ar = array(0, $switchvote, $voteexist);
            echo json_encode($ar);
        }
        
        
        
    }
    if ($_GET['action'] == 'postDescription') {
        $descContent = $_POST['descContent'];
        $parent = $_POST['parent'];
        if (!$_POST['descContent']) {
                    
            echo "Your description is empty!";
                    
        } else if (strlen($_POST['descContent']) > 10000) {
            
            echo "Your description is too long!";
            
        } else {
            
            $query = "UPDATE `users` SET `description` = '".$descContent."' WHERE `username` = '".$parent."' LIMIT 1";
            mysqli_query($link, $query);
            
            echo "1";
            
        }
        
    }
    if ($_GET['action'] == 'postFollowUser') {
        $_userid = $_SESSION['id'];
        $_following = $_POST['following'];
        $query = "SELECT * FROM followuser WHERE userid = ". mysqli_real_escape_string($link, $_SESSION['id'])." AND following = ". mysqli_real_escape_string($link, $_POST['following'])." LIMIT 1";
        $result = mysqli_query($link, $query);
           
        if (mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            $query = "DELETE FROM followuser WHERE id = ". mysqli_real_escape_string($link, $row['id'])." LIMIT 1";
            mysqli_query($link, $query);
            echo 0;
        }
        else{
            mysqli_query($link, "INSERT INTO `followuser` (`userid`, `following`) VALUES ('".mysqli_real_escape_string($link, $_SESSION['id'])."','".mysqli_real_escape_string($link, $_POST['following'])."')");
            echo 1;
        }
      
    }
    if ($_GET['action'] == 'postFollowCrypt') {
        $_userid = $_SESSION['id'];
        $_following = $_POST['following'];
        $query = "SELECT * FROM followcrypt WHERE userid = ". mysqli_real_escape_string($link, $_SESSION['id'])." AND following = '". mysqli_real_escape_string($link, $_POST['following'])."' LIMIT 1";
        $result = mysqli_query($link, $query);
           
        if (mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            $query = "DELETE FROM followcrypt WHERE id = ". mysqli_real_escape_string($link, $row['id'])." LIMIT 1";
            mysqli_query($link, $query);
            echo 0;
        }
        else{
            mysqli_query($link, "INSERT INTO `followcrypt` (`userid`, `following`) VALUES ('".mysqli_real_escape_string($link, $_SESSION['id'])."','".mysqli_real_escape_string($link, $_POST['following'])."')");
            echo 1;
        }
      
    }
    if ($_GET['action'] == 'postDeleteComment') {
        $commentid = $_POST['commentid'];
        $query = "DELETE FROM comments WHERE id = '".mysqli_real_escape_string($link, $commentid)."' LIMIT 1";
        echo $query;
        $result = mysqli_query($link, $query);
        
        $query = "DELETE * FROM replies WHERE parent = '".mysqli_real_escape_string($link, $commentid)."'";
        $result = mysqli_query($link, $query);
        
        $query = "DELETE FROM upvotes WHERE commentid = '".mysqli_real_escape_string($link, $commentid)."' LIMIT 1";
        $result = mysqli_query($link, $query);
       
    }
    if ($_GET['action'] == 'postDeleteReply') {
        $replyid = $_POST['replyid'];
        $query = "DELETE FROM replies WHERE id = '".mysqli_real_escape_string($link, $replyid)."'";
        $result = mysqli_query($link, $query);
        
       
    }
    if ($_GET['action'] == 'typeahead') {
        $query = "SELECT * FROM slugs where name LIKE '%".$_POST['query']."%'";
        $result = mysqli_query($link, $query);
        $data = array();
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row["name"];
            }
        }
        echo json_encode($data);
        
       
    }
    if ($_GET['action'] == 'getSlug') {
        $query = "SELECT * FROM slugs WHERE name = '".$_POST['parent']."'";
        $result = mysqli_query($link, $query);
        
        if(mysqli_num_rows($result) > 0){
           $row = mysqli_fetch_assoc($result);
           echo $row["slug"];
            
        }
  
        
       
    }
    if ($_GET['action'] == 'getTimezone') {
        $_SESSION['time'] = $_POST['time'];
    }

   

?>
