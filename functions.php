<?php

    session_start();

    $link = mysqli_connect("localhost", "yousef", "", "acrypt");

    if (mysqli_connect_errno()) {
        
        print_r(mysqli_connect_error());
        exit();
        
    }
    function getActivePage(){
        $x = array("","","","","","","");
        if($_GET['page'] == "home"){
            if($_GET['sort'] == ""){
                $x[0] = "active";
            }
            else if($_GET['sort'] == "'mostbuy'"){
                $x[4] = "active";
            }
            else if($_GET['sort'] == "'mostsell'"){
                $x[5] = "active";
            }
            else{
                $x[6] = "active";
            }
            
        }
        else if($_GET['page'] == "about"){
            $x[1] = "active";
        }
        else if($_GET['page'] == "profile"){
            $x[2] = "active";
        }
        else if($_GET['page'] == "feed"){
            $x[3] = "active";
        }
        else{
            $x[0] = "active";
        }
        return $x;
    }
    function format_number($number){
        if ($number < 1000000) {
            // Anything less than a million
            $format = number_format($number);
        } else if ($number < 1000000000) {
            // Anything less than a billion
            $format = number_format($number / 1000000, 2) . 'M';
        } else {
            // At least a billion
            $format = number_format($number / 1000000000, 2) . 'B';
        }

        return $format;
    }
    function time_since($since) {
        $chunks = array(
            array(60 * 60 * 24 * 365 , 'year'),
            array(60 * 60 * 24 * 30 , 'month'),
            array(60 * 60 * 24 * 7, 'week'),
            array(60 * 60 * 24 , 'day'),
            array(60 * 60 , 'hour'),
            array(60 , 'min'),
            array(1 , 'sec')
        );

        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = floor($since / $seconds)) != 0) {
                break;
            }
        }

        $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
        return $print;
    }
    function getTotalVotes($parent){
        $buyTotalVoteQuery = "SELECT COUNT(*) as count FROM buyvotes WHERE parent= '".$parent."'";
        $buyTotalVoteResult = mysqli_query($link, $buyTotalVoteQuery);
        $buyCount = mysqli_fetch_assoc($buyTotalVoteResult);
        
        $sellTotalVoteQuery = "SELECT COUNT(*) as count FROM sellvotes WHERE parent= '".$parent."'";
        $sellTotalVoteResult = mysqli_query($link, $sellTotalVoteQuery);
        $sellCount = mysqli_fetch_assoc($sellTotalVoteResult);
        
        return ($buyCount['count'] + $sellCount['count']);
    }
    function displayFollow($WhereClause, $userid, $con, $page){ ?>
        <div class="container">
        <?php
            if($page == "profile"){
                $query = "SELECT * FROM `followuser` WHERE ".$WhereClause." = '".$userid."'";
            }
            else{
                $query = "SELECT * FROM `followcrypt` WHERE ".$WhereClause." = '".$userid."'";
            }
            
            $result = mysqli_query($con, $query); 
            if(mysqli_num_rows($result) <= 0){
                echo "<div class=card-body>";
                echo "No followers yet";
                echo "</div>";
            }?>
            <div class="mt-2">
                <div class="row">
                    <?php 
                    while($col = mysqli_fetch_assoc($result)){
                      
                        if($WhereClause == "userid" && $page != "profile"){
                            $query = "SELECT * FROM users WHERE id = ".$col['following'];
                        }
                        else if($WhereClause == "userid" && $page == "profile"){
                            $query = "SELECT * FROM users WHERE id = ".$col['following'];
                        }
                        else{
                            $query = "SELECT * FROM users WHERE id = ".$col['userid'];
                        }
                        
    
                        
                        
                        $uresult = mysqli_query($con, $query);
                        $userRow = mysqli_fetch_assoc($uresult); ?>


                            <div class="col-md-4" style="margin-top:20px;">
                              <div class="card card-profile">
                                <div class="card-block text-xs-center">
                                  <img class="media-object mr-3 align-self-start rounded-circle" src=" <?php echo $userRow['image']; ?>" style="object-fit:cover;object-postion:center right;
                                                  width: 100px;
                                                  height: 100px; margin-top: 10px;">
                                  <h5 class="card-title"><a href='?page=profile&username=<?php echo $userRow['username']; ?>' > <?php echo "@".$userRow['username']; ?></a></h5>
                                  <h5 class="card-title"> <?php echo $userRow['firstname']." ".$userRow['lastname']; ?></h5>
                                  <?php 
                                  if(strlen($userRow['description']) > 45){?>
                                    <p class="mb-4"><?php echo substr($userRow['description'],0,45)."...";?></p>
                                  <?php } 
                                  else{ ?>
                                    <p class="mb-4"><?php echo $userRow['description'];?></p>
                                  <?php }
                                  $query = "SELECT * FROM followuser WHERE userid = '".$_SESSION['id']."' AND following = '".$userRow['id']."'";
                                  $fresult = mysqli_query($con, $query);
                                  if(mysqli_num_rows($fresult) < 0){ ?>
                                      <button class="btn btn-primary followuser-button" style="margin-top: 20px;" id="<?php echo $row['id']."f"; ?>">Follow</button>
                                  <?php }
                                  else{ ?>
                                      <button class="btn btn-primary followuser-button" style="margin-top: 20px;" id="<?php echo $row['id']."f"; ?>">Unfollow</button>
                                  <?php } ?>
                                </div>
                              </div>
                            </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } 
    function displayComments($parent, $page, $function, $period){
        global $link;
        if($page == "profile"){
            if($function == "comment"){
                $query = "SELECT * FROM comments WHERE userid = '".mysqli_real_escape_string($link, $parent)."' ORDER BY datetime DESC";
                
            }
            else if($function == "upvote"){
                $query = "SELECT * FROM upvotes WHERE id = ".$parent;
                $result = mysqli_query($link, $query);
                $urow = mysqli_fetch_assoc($result);
                $query = "SELECT * FROM comments WHERE id = ".$urow['commentid'];
            }
            else if($function == "sellvote"){
                $query = "SELECT * FROM sellvotes WHERE userid = ".$parent." ORDER BY datetime DESC";
            }
            else if($function == "buyvote"){
                $query = "SELECT * FROM buyvotes WHERE userid = ".$parent." ORDER BY datetime DESC";
            }
            
            
        }
        else{
            if($page == "timeline"){
                $query = "SELECT * FROM comments WHERE parent = 'Bitcoin'";
            }
            else{
                $query = "SELECT * FROM comments WHERE parent = '".mysqli_real_escape_string($link, $parent)."'".$period." ORDER BY datetime DESC";
                
            }
            
        }
        
        
        $result = mysqli_query($link, $query);
        
        
        
        if(mysqli_num_rows($result) == 0 && $page != "timeline"){
            if($function == "comment"){ ?>
                <div class="card-body">
                <?php echo "<p> No comments yet</p>";?>
                </div>
            <?php }
            else if($function == "sellvote"){ ?>
                <div class="card-body">
                <?php echo "<p> No sell votes yet</p>";?>
                </div>
            <?php }
            else if($function == "buyvote"){ ?>
                <div class="card-body">
                <?php echo "<p> No buy votes yet</p>";?>
                </div>
            <?php }
            else { ?>
                <div class="card-body">
                <?php echo "<p> No comments yet</p>";?>
                </div>
            <?php }
       
        }
        else{
            $i = 0;
            
            while ($row = mysqli_fetch_assoc($result)) {
                if($page == "timeline"){
                    $row = $parent;
                }
                $userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $row['userid']);
                $userResult = mysqli_query($link, $userQuery); 
                $userRow = mysqli_fetch_assoc($userResult); 
                if($i >= 10){
                    if($page == "profile" || $page == "timeline" || $page == "cryptocurrency"){?>
                        <div class="card card-attributes card-body more-comments displayNone"> 
                    <?php }
                    else{ ?>
                        <div class="card-body more-comments displayNone">
                    <?php }
                }
                else{
                    if($page == "profile" || $page == "timeline" || $page == "cryptocurrency"){?>
                        <div class="card card-attributes card-body more-comments" id="<?php echo $row['id']."card" ?>"> 
                    <?php }
                    else{ ?>
                        <div class="card-body more-comments" id="<?php echo $row['id']."card" ?>">
                    <?php }
                } ?>
                
            
                        <li class="media">
                          <img class="media-object mr-3 align-self-start rounded-circle" src="<?php echo $userRow['image']; ?>" style="object-fit:cover;object-postion:center right;
                          width: 30px;
                          height: 30px;">
                          <div class="media-body comments-font-family">
                            <?php echo "<small class='text-muted'><a href='?page=profile&username=".$userRow['username']."'>". $userRow['username']."</a></small>";
                            if($page == "profile" || $page == "timeline"){
                                if($function == "comment"){
                                    echo "<small><h6> commented on <a href='?page=cryptocurrency&parent=".$row['parent']."'>".$row['parent']."</a></h6></small>";
                                }
                                else if($function == "upvote"){
                                    if($page == "profile"){
                                         echo "<small><h6> upvoted <a href=?page=profile&username=".$userRow['username'].">".$userRow['username']."</a> comment on <a href='?page=cryptocurrency&parent=".$row['parent']."'>".$row['parent']."</a> ".time_since(time() - strtotime($urow['datetime']))." ago</h6></small>";
                                    }
                                    else{
                                        
                                        $cresult = mysqli_query($link, "SELECT * FROM comments where id = '".$row['commentid']."'");
                                        $crow = mysqli_fetch_assoc($cresult);
                                        $uresult = mysqli_query($link, "SELECT * FROM users where id = '".$crow['userid']."'");
                                        $urow = mysqli_fetch_assoc($uresult);
                                        echo "<small><h6> upvoted <a href=?page=profile&username=".$crow['userid'].">".$urow['username']."</a> comment on <a href='?page=cryptocurrency&parent=".$crow['parent']."'>".$crow['parent']."</a> ".time_since(time() - strtotime($row['datetime']))." ago</h6></small>";
                                    }
                                   
                                }
                                else if($function == "sellvote"){
                                     echo "<small><h6> voted to sell <a href='?page=cryptocurrency&parent=".$row['parent']."'>".$row['parent']."</a></h6></small>";
                                }
                                else if($function == "buyvote"){
                                     echo "<small><h6> voted to buy <a href='?page=cryptocurrency&parent=".$row['parent']."'>".$row['parent']."</a></h6></small>";
                                }
                                
                                
                                 
                            }
                            if($function == "upvote" && $page == "timeline"){
                                echo "<p class='comments-font-family p'>".$crow['comment']."</p>"; 
                            }
                            else{
                                echo "<p class='comments-font-family p'>".$row['comment']."</p>";
                            }
                
                            if($page == "home" && $_SESSION['id'] > 0 &&  $_SESSION['id'] == $userRow['id']){
                                echo "<small class='text-muted float-right deleteComment' id='".$row['id']."dc'><a>Delete</a></small>"; 
                            }
                            else if($page == "profile" && $function == "comment" &&  $_SESSION['id'] == $userRow['id']){
                                 echo "<small class='text-muted float-right deleteComment' id='".$row['id']."dc'><a>Delete</a></small>";
                            }
                            else if($page == "cryptocurrency" &&  $_SESSION['id'] == $userRow['id']){
                                 echo "<small class='text-muted float-right deleteComment' href='' id='".$row['id']."dc'><a>Delete</a></small>";
                            }
                              
                              
                              ?>
                            
                          </div>   
                        </li>
                        <div class="media-heading">
                            <?php 
                            echo "<small class='float-right text-muted'>".time_since(time() - strtotime($row['datetime']))." ago</small>";?>
                            <?php $currentComment = $row['id'];
                            
                            /** Implementing upvote **/
                            $_userid = $_SESSION['id'];
                            $_commentid = $row['id'];
                            $upvoteQuery = "SELECT * FROM upvotes WHERE userid='$_userid' AND commentid='$_commentid'";
                            $upvoteResult = mysqli_query($link, $upvoteQuery);
                            
                            $upvoteCountQuery = "SELECT COUNT(*) as count FROM upvotes WHERE commentid='$_commentid'";
                            $upvoteCountResult = mysqli_query($link, $upvoteCountQuery);
                            $upvoteCount = mysqli_fetch_assoc($upvoteCountResult);
                             
                            if($function != "sellvote" && $function != "buyvote" && $function != "upvote"){
                                 if(mysqli_fetch_array($upvoteResult) != false){
                                        if($_SESSION['id'] > 0){
                                            echo "<button id='".$currentComment."upvotes' class='btn red btn-link view-comments-margin upvotes' value=".$upvoteCount['count']." >Upvoted(".$upvoteCount['count'].")</button>";
                                        }
                                        else{
                                             echo "<p id='".$currentComment."upvotes' class='btn red btn-link view-comments-margin upvotes' value=".$upvoteCount['count']." >Upvoted(".$upvoteCount['count'].")</p>";
                                        }
                                    }
                                    else{
                                        if($_SESSION['id'] > 0){
                                            echo "<button id='".$currentComment."upvotes' class='btn btn-link view-comments-margin upvotes' value=".$upvoteCount['count'].">Upvote(".$upvoteCount['count'].")</button>";
                                        }
                                        else{
                                            echo "<p id='".$currentComment."upvotes' class='btn btn-link view-comments-margin upvotes' value=".$upvoteCount['count'].">Upvote(".$upvoteCount['count'].")</p>";
                                        }

                                    } 
                            } ?>
                           
                            
                            
                            <?php if($page == "home" && $_SESSION['id'] > 0){?>
                                <button class="btn btn-link view-comments-margin replyButton" id= <?php echo $row['id']."r".$parent; ?> >Reply</button>
                                <button class="btn btn-link view-comments-margin displayNone commentButton" id= <?php echo $row['id']."c".$parent; ?> >Comment</button>    
                            <?php } ?>
                            
                            <?php if($page == "cryptocurrency" || $page == "profile" || $page == "timeline"){ ?>
                                <?php 
                                 if($_SESSION['id'] > 0 && $function != "sellvote" && $function != "buyvote" && $function != "upvote"){?>
                                    <button class="btn btn-link view-comments-margin creplyButton" id= <?php echo $row['id']."r".$parent; ?> >Reply</button>
                                    <div class="container">
                                        <div row>
                                            <textarea id= <?php echo "comment".$row['id']; ?> oninput='this.style.height = "";this.style.height = this.scrollHeight + "px"' class="textarea textareacomments bg-light text-secondary displayNone replyContent col-10" placeholder="Write a reply..."></textarea>

                                            <button  class="btn btn-outline-primary my-2 my-sm-0 displayNone postReplyButton col-auto float-right" id= <?php echo $row['id']; ?>>Post</button>
                            
                                        
                                        </div>
                                        
                            
                                    </div>
                                    
                        
                                    <?php 
                                } 
                                if($function != "upvote" && $function != "sellvote" && $function != "buyvote"){
                                    displayReplies($row['id'], $page);
                                }
                                $countQuery = "SELECT COUNT(*) as count FROM replies WHERE parent='$_commentid'";
                                $countResult = mysqli_query($link, $countQuery);
                                $count = mysqli_fetch_assoc($countResult);
                                                          
                                if($count['count'] > 1 && $function != "upvote"){?>
                                    <button id=<?php echo "rbutton".$row['id']; ?> class="btn btn-link view-replies-margin viewReplyButton view-more-replies" > <?php echo "View more replies(".($count['count'] - 1).")"; ?> </button>
                                <?php } ?>
                                
                                
                                
                           
                                 
                            <?php } 
                            if($page != "profile" && $page != "timeline" && $page != "cryptocurrency"){?>
                                <hr class="my-3">
                            <?php } ?>
                            
                            
                        </div>
                    

                            
                </div>
                
                
             
        <?php  
                if($page == "home" || $page == "timeline"){
                    break;
                }
                
               
                $i = $i + 1;
            } ?> 
            
            
             <?php 
            $countQuery = "SELECT COUNT(*) as count FROM comments WHERE parent='$parent'".$period;
            $countResult = mysqli_query($link, $countQuery);
            $count = mysqli_fetch_assoc($countResult);
            if($page != "profile" && $page != "timeline"){
                if($page == "home"){
                 echo '<button class="btn btn-link view-comments-margin"><a href="?page=cryptocurrency&parent='.$parent.'">View more comments('.($count['count'] - 1).')</a></button>';     
                }
                else{
                    if($i >= 10){
                        
                        echo '<button class="btn btn-link view-comments-margin view-more-comments"><a>View more comments('.($count['count'] - 10).')</a></button>'; 
                    }
                    else{
                        if(($count['count'] - $i) > 0){
                            echo '<button class="btn btn-link view-comments-margin view-more-comments"><a>View more comments('.($count['count'] - $i).')</a></button>'; 
                        }
                         
                    }

                }
            }
            
            
           
            
            if($_SESSION['id'] > 0 && $page == "home"){?>
                <textarea id= <?php echo "comment".$row['id']; ?> oninput='this.style.height = "";this.style.height = this.scrollHeight + "px"' class="textarea textareahome bg-light text-secondary displayNone replyContent float-right" placeholder="Write a reply..."></textarea>
                        
                <button  class="btn btn-outline-primary my-2 my-sm-0 post-button displayNone postReplyButton" id= <?php echo $row['id']; ?>>Post</button>
           <?php  }
            

            
           
        }
        
    }
    function displayReplies($parent, $page){
        global $link;
        if($page == "profile"){
            $query = "SELECT * FROM replies WHERE parent = '".mysqli_real_escape_string($link, $parent)."' ORDER BY datetime DESC";
        }
        else{
            $query = "SELECT * FROM replies WHERE parent = '".mysqli_real_escape_string($link, $parent)."'";
        }
       
        $result = mysqli_query($link, $query);
        
        if(mysqli_num_rows($result) == 0){?>
        <?php   
        }
        else{
            $i = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $row['userid']);
                $userResult = mysqli_query($link, $userQuery); 
                $userRow = mysqli_fetch_assoc($userResult); 
                if($i >= 1){ 
                echo "<div class='card-body reply-attributes displayNone more-replies more-replies-rbutton".$parent."' id='".$row['id']."card'>"; }
                else{ ?> 
                 <div class="card-body reply-attributes" id="<?php echo $row['id'].'card'; ?>">
                <?php } ?>
            
                        <li class="media">
                          <img class="media-object mr-3 align-self-start rounded-circle" src="<?php echo $userRow['image']; ?>" style="object-fit:cover;object-postion:center right;
                          width: 30px;
                          height: 30px;">
                          <div class="media-body comments-font-family">
                            <?php echo "<small class='text-muted'><a href='?page=profile&username=".$userRow['username']."'>". $userRow['username']."</a></small>";?>
                            <?php echo "<p>".$row['reply']."</p>"; ?>
                          </div> 
                            <?php
                            if($page == "home" && $_SESSION['id'] > 0 &&  $_SESSION['id'] == $userRow['id']){
                                echo "<small class='text-muted float-right deleteReply' id='".$row['id']."dr'><a>Delete</a></small>"; 
                            }
                            else if($page == "profile" && $function == "comment" &&  $_SESSION['id'] == $userRow['id']){
                                 echo "<small class='text-muted float-right deleteReply' id='".$row['id']."dr'><a>Delete</a></small>";
                            }
                            else if($page == "cryptocurrency" &&  $_SESSION['id'] == $userRow['id']){
                                 echo "<small class='text-muted float-right deleteReply' id='".$row['id']."dr'><a>Delete</a></small>";
                            }
                        ?>
                        </li>
                        <div class="media-heading">
                            <?php 
                            echo "<small class='float-right text-muted'>".time_since(time() - strtotime($row['datetime']))." ago</small>";
                            ?> 
                          
         
                        </div>
                          
                </div>
                
                
             
        <?php  
                $i = $i + 1;
            } ?>  
            

            
        <?php    
        }
    }
    function displayUpvotes($name, $con, $page, $period){
        $buyTotalVoteQuery = "SELECT COUNT(*) as count FROM buyvotes WHERE parent='".$name."'".$period;
        $buyTotalVoteResult = mysqli_query($con, $buyTotalVoteQuery);
        $buyCount = mysqli_fetch_assoc($buyTotalVoteResult);

        $sellTotalVoteQuery = "SELECT COUNT(*) as count FROM sellvotes WHERE parent='".$name."'".$period;
        $sellTotalVoteResult = mysqli_query($con, $sellTotalVoteQuery);
        $sellCount = mysqli_fetch_assoc($sellTotalVoteResult);

        if($buyCount['count'] != 0){
            $buyPercent = round(($buyCount['count']/($buyCount['count'] + $sellCount['count'])) * 100, 1);
        }
        else{
            $buyPercent = 0;
        }
        if($sellCount['count'] != 0){
            $sellPercent = round(($sellCount['count']/($buyCount['count'] + $sellCount['count'])) * 100, 1);
        }
        else{
            $sellPercent = 0;
        }

        $totalVotes = $buyCount['count'] + $sellCount['count'];
        $_userid = $_SESSION['id'];
        $buyclass = "";
        $sellclass = "";

        $sellQuery = "SELECT * FROM `sellvotes` WHERE userid='$_userid' AND parent='$name' AND DATE(datetime) = DATE(NOW())";
        $sellResult = mysqli_query($con, $sellQuery);

        $buyQuery = "SELECT * FROM `buyvotes` WHERE userid='$_userid' AND parent='$name' AND DATE(datetime) = DATE(NOW())";
        $buyResult = mysqli_query($con, $buyQuery);

        if(mysqli_fetch_array($buyResult) != false){
            $buyclass = "btn-success";
            $sellclass = "btn-info";
        }
        else{
            $buyclass = "btn-info";
        }
        if(mysqli_fetch_array($sellResult) != false){
            $sellclass = "btn-success";
            $buyclass = "btn-info";
        }
        else{
            $sellclass = "btn-info";
        }
        if($page == "cryptocurrency"){?>
            <nav class="profile-header-bio font-family" style="margin-bottom:10px;">
                <ul class="nav justify-content-center">
                  <li class="nav-item">
                    <a class="nav-link active" style="font-weight:bold; font-size:15px; color:grey;"><?php echo "BUY(".$buyPercent."%)"; ?></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" style="font-weight:bold; font-size:15px; color:grey;"><?php echo "SELL(".$sellPercent."%)"; ?></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" style="font-weight:bold; font-size:15px; color:grey;"><?php echo "VT(".$totalVotes.")"; ?></a>
                  </li>

                </ul>
            </nav>
        <?php }
        else if($page == "cryptocurrency-h"){?>
            <div class="container" style="margin-left: 5px; margin-top:5px; margin-bottom: 5px; font-weight: bold;">
                <div class="container">
                  <p class="font-family" style="color:grey; font-weight:bold;">
                    <?php echo " BUY(".$buyPercent."%)".str_repeat('&nbsp;', 5)."SELL(".$sellPercent."%)".str_repeat('&nbsp;', 5)."VOTES(".$totalVotes.")"; ?>
                  </p>
                    </div>
                  
  
            </div>
        <?php }
        else{
            echo '<div class="col-1 float-right vote-attributes" id="'.$name.'totalvotes">'.$totalVotes.'</div>';
            if($_SESSION['id'] > 0){
                echo '<button class="col-1 float-right buy-attributes buyButton btn '.$buyclass.'" id="'.$name.'bbuy">'.$buyPercent.'%</button>';
                echo '<button class="col-1 float-right sell-attributes sellButton btn '.$sellclass.'" id="'.$name.'ssell">'.$sellPercent.'%</button>';
            }
            else{
                echo '<button class="col-1 float-right buy-attributes btn '.$buyclass.'" id="'.$name.'bbuy">'.$buyPercent.'%</button>';
                echo '<button class="col-1 float-right sell-attributes btn '.$sellclass.'" id="'.$name.'ssell">'.$sellPercent.'%</button>';
            }
        }
        

                         
                               
                                
    }
    function getMetadata($name, $con){
        $query = "SELECT * FROM slugs WHERE name = '".$name."' LIMIT 1";
        $result = mysqli_query($con, $query);
        $slug = mysqli_fetch_assoc($result);
        
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/info';
        $parameters = [
          'slug' => $slug['slug']
        ];

        $headers = [
          'Accepts: application/json',
          'X-CMC_PRO_API_KEY:  35e53081-c0db-47e2-bf86-d3304c67c29f'
        ];
        $qs = http_build_query($parameters); // query string encode the parameters
        $request = "{$url}?{$qs}"; // create the request URL


        $curl = curl_init(); // Get cURL resource
        // Set cURL options
        curl_setopt_array($curl, array(
          CURLOPT_URL => $request,            // set the request URL
          CURLOPT_HTTPHEADER => $headers,     // set the headers 
          CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
        ));

        $response = curl_exec($curl); // Send the request, save the response
        return json_decode($response, true); // print json decoded response

    }
    function displayCryptoInfo($i, $con, $coins){
        $name = preg_replace('/\s+/', '', $coins[data][$i]['name']);
        $slug = $coins[data][$i]['slug'];
        $j = $i + 1;
        $query = "UPDATE `slugs` SET `name` = '".$name."' WHERE `id` = '".$j."' LIMIT 1";
        mysqli_query($con, $query);
        $query = "UPDATE `slugs` SET `slug` = '".$slug."' WHERE `id` = '".$j."' LIMIT 1";
        mysqli_query($con, $query);
      
        /**mysqli_query($con, $query);
        $metadata = getMetadata($name, $con);
        foreach($metadata[data] as $i => $item) {
            $id = $i;
            // $array[$i] is same as $item
        }**/
        ?>
        <div class="card card-attributes">
            <div class="card-header" id="headingOne">
              <p class="mb-0 bold">
                 <?php 
                    $coin_lowercase = strtolower($coins[data][$i]['slug']);
                    $dir = "cryptocurrency-logos/coins/128x128/".$coin_lowercase.".png"; 
                    echo '<div class="col-auto float-left"><img src="'.$dir.'"height=20;width=20></div>'; 

                    echo '<div class="col-2 float-left bold"><a class="table-font-size showComments" href="?page=cryptocurrency&parent='.$coins[data][$i]['name'].'">'.$coins[data][$i]['name'].'</a></div>'; 

                    echo '<div class="col-1 float-left bold table-font-size">'."$".format_number
                    ($coins[data][$i]['quote']['USD']['volume_24h']).'</div>';

                    echo '<div class="col-2 float-left bold table-font-size">'."$".$coins[data][$i]['quote']['USD']['price'].'</div>';

                    if($coins[data][$i]['quote']['USD']['percent_change_24h'] < 0){
                        echo '<div class="col-2 float-left bold table-font-size" id="percent-change-red">'.$coins[data][$i]['quote']['USD']['percent_change_24h']."%".'</div>';
                    } else{
                        echo '<div class="col-2 float-left bold table-font-size" id="percent-change-green">'.$coins[data][$i]['quote']['USD']['percent_change_24h']."%".'</div>';
                    }
                    displayUpvotes($name, $con, "home", " AND DATE(datetime) = DATE(NOW())");
                    ?>
              </p>
            </div>
            <?php displayComments($name, "home", "", " AND DATE(datetime) = DATE(NOW())");

            if($_SESSION['id'] > 0){?>
                <textarea id=<?php echo $name."11";?> oninput='this.style.height = "";this.style.height = this.scrollHeight + "px"' class="textarea bg-light text-secondary commentContent" placeholder="Write a comment..."></textarea>


                <button id=<?php echo $name."1";?> name="postCommentButton"  class="btn btn-outline-primary my-2 my-sm-0 post-button postCommentButton">Post</button>

            <?php } ?>




        </div>
    <?php }
    
?>
