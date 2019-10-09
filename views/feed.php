

<?php
    $query = "SELECT * FROM users WHERE id = '".$_SESSION['id']."'";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_assoc($result);
    echo "<title>".$row['firstname']." ".$row['lastname']." Feed | CryptocurrencyVote</title>";
    $query = "SELECT * FROM followuser WHERE userid = '".$_SESSION['id']."'";
    $result = mysqli_query($link, $query);
    $users = array();
    echo "<div class = container>";
            echo "<div class='container col-md-9 float-left' >"; ?>
            <div class="row align-items-center justify-content-center text-primary">
               <a style="width: 75px; margin-bottom:10px; font-weight: bold; margin-top: 100px;">FEED</a>

            </div> <?php
    echo "</div>";
    echo "</div>";
    if(mysqli_num_rows($result) <= 0){
        echo "<div class = container>";
        echo "<div class='container col-md-9 float-left' >";
        echo "No feed yet";
        echo "</div>";
        echo "</div>";
        return;
    }
    while($row = mysqli_fetch_assoc($result)){
        $users[] = $row['following'];
    }
    $query = "SELECT comment, id, datetime, userid, parent, NULL as commentid FROM comments WHERE userid IN (".implode(",",$users).") UNION ALL SELECT NULL as comment, id, datetime, userid, NULL as parent, commentid FROM upvotes WHERE userid IN (".implode(",",$users).")  UNION ALL SELECT NULL as comment, id, datetime, userid, parent, NULL as commentid FROM sellvotes WHERE userid IN (".implode(",",$users).") UNION ALL SELECT NULL as comment, id, datetime, userid, parent, NULL as commentid FROM buyvotes WHERE userid IN (".implode(",",$users).") ORDER BY datetime DESC";
    $result = mysqli_query($link, $query);
    while($col = mysqli_fetch_assoc($result)){
        if(isset($col['comment'])){
            echo "<div class = container>";
            echo "<div class='container col-md-9 float-left' >";
            displayComments($col, "timeline", "comment", "");
            echo "</div>";
            echo "</div>";
        }
        else if(isset($col['commentid'])){
            echo "<div class = container>";
            echo "<div class='container col-md-9 float-left'>";
            displayComments($col, "timeline", "upvote", "");
            echo "</div>";
            echo "</div>";
        }
        else{
            $sell = true;
            $query = "SELECT * FROM sellvotes WHERE id = '".$col['id']."' AND parent = '".$col['parent']."' AND userid = '".$col['userid']."' LIMIT 1"; 
            $_result = mysqli_query($link, $query);
            if(mysqli_num_rows($_result) == 0){
                $sell = false;
                $query = "SELECT * FROM buyvotes WHERE id = '".$col['id']."' AND parent = '".$col['parent']."' AND userid = '".$col['userid']."' LIMIT 1"; 
                $_result = mysqli_query($link, $query);
            }
            $_col = mysqli_fetch_assoc($_result);
            echo "<div class = container>";
            echo "<div class='container col-md-9 float-left'>";
            if($sell == true){
                displayComments($_col, "timeline", "sellvote", "");
            }
            else{
                displayComments($_col, "timeline", "buyvote", "");
            }

            echo "</div>";
            echo "</div>";
        }
        
       

    }

  
    



//WHERE `userid` IN (' . implode(',', array_map('intval', $users)) . ')'
?>