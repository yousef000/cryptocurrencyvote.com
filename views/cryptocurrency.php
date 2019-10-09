<?php session_start(); 

?>

<div id="commentSuccess" class="alert alert-primary popup float-inherit">Your response was posted successfully.</div>
<div id="commentFail" class="alert alert-secondary popup float-inherit"></div>
<pre class="tab">


</pre>

<div class="profile-header text-center" style="background-color: white; color: black; height: 20; width: 20;">
    <div class="container-fluid">
    <div class="container-inner">
       <?php 
        $result = mysqli_query($link, "SELECT * FROM slugs WHERE name = '".$_GET['parent']."' LIMIT 1");
        $nameRow = mysqli_fetch_assoc($result);
        $dir = "cryptocurrency-logos/coins/128x128/".strtolower($nameRow['slug']).".png";
        echo "<title>".$_GET['parent']." (".$nameRow['slug'].") Buy, Sell, Votes, Comments, History, Followers | CryptocurrencyVote</title>";
        echo '<img src="'.$dir.'" style="object-fit:cover;object-postion:center right;
  width: 150px;
  height: 150px;" >';
        echo '<h4 class="profile-header-user black-font">@'.$_GET['parent'].'</h4>' ?>
        <?php 
        displayUpvotes($_GET['parent'], $link, "cryptocurrency", "AND DATE(datetime) = DATE(NOW())");
        if($_SESSION['id'] > 0 && $_SESSION['id'] != $row['id']){ 
            $query = "SELECT * FROM followcrypt WHERE userid = '".$_SESSION['id']."' AND following = '".$_GET['parent']."'";
            $fresult = mysqli_query($link, $query);
            if(mysqli_num_rows($fresult) <= 0){ ?>
                <button class="btn btn-primary followcrypt-button" id="<?php echo $_GET['parent']."f"; ?>">Follow</button>
            <?php }
            else{ ?>
                <button class="btn btn-primary followcrypt-button" id="<?php echo $_GET['parent']."f"; ?>">Unfollow</button>
            <?php } 
            
        } ?>
        
    </div>
   
    
        
    </div>
    <nav class="profile-header-nav" style="color:black;">
        <ul class="nav font-family justify-content-center" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="comment-tab" data-toggle="tab" href="#comment" role="tab" aria-controls="comment" >Comments</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">History</a>
              </li>
              <li class="nav-item">
                <a class="nav-link"id="followers-tab" data-toggle="tab" href="#followers" role="tab" aria-controls="followers" aria-selected="false">Followers</a>
              </li>

        </ul>
    </nav>
</div>
    
<div class="tab-content" id="myTabContent">
     <div class="tab-pane fade show active" id="comment" role="tabpanel" aria-labelledby="comment-tab">
        <div class="container">
            <div class="container col-10 float-left" style="margin-top:10px;">
                <?php 
                    if($_SESSION['id'] > 0){ ?>
                    <textarea id=<?php echo $_GET["parent"]."11";?> oninput='this.style.height = "";this.style.height = this.scrollHeight + "px"' class="textarea textareacomments bg-light text-secondary commentContent col-9 c-content float-left" placeholder="Write a comment..."></textarea>

                    <button id=<?php echo $_GET["parent"]."1";?> class="btn btn-outline-primary my-2 my-sm-0 postCommentButton col-auto float-left p-button">Post</button>

                <?php } ?>
            </div>
            <div class="container col-9 float-left">
                <?php
                    displayComments($_GET['parent'], "cryptocurrency", "", " AND DATE(datetime) = DATE(NOW())");
                 ?>

            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
        <div class="container">
            <div class="container col-9 float-left">
                <div class="container" style="margin-left:5px;">
                    <nav class="font-family" style="color:black;">
                        <ul class="nav col-9 float-left" id="xTab" role="tablist">
                              <li class="nav-item">
                                <a class="nav-link active" id="yesterday-tab" data-toggle="tab" href="#yesterday" role="tab" aria-controls="yesterday" >Yesterday</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" id="lastweek-tab" data-toggle="tab" href="#lastweek" role="tab" aria-controls="lastweek" aria-selected="false">Last 7 Days</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" id="lastmonth-tab" data-toggle="tab" href="#lastmonth" role="tab" aria-controls="lastmonth" aria-selected="false">Last 30 Days</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" id="currentyear-tab" data-toggle="tab" href="#currentyear" role="tab" aria-controls="currentyear" aria-selected="false">Current Year</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" id="lastyear-tab" data-toggle="tab" href="#lastyear" role="tab" aria-controls="lastyear" aria-selected="false">Last Year</a>
                              </li>

                        </ul>
                    </nav>
                    </div>
            </div>
        </div>
        <div class="tab-content" id="xTabContent">
            <div class="tab-pane fade show active" id="yesterday" role="tabpanel" aria-labelledby="yesterday-tab">
                <div class="container">
                    <div class="container col-9 float-left">
                         <?php
                            displayUpvotes($_GET['parent'], $link, "cryptocurrency-h", " AND DATE(datetime) = DATE(NOW() - INTERVAL 1 DAY)");
                         ?>
                    </div>
                </div>
                <div class="container">
                    <div class="container col-9 float-left">
                         <?php
                            displayComments($_GET['parent'], "cryptocurrency", ""," AND DATE(datetime) = DATE(NOW() - INTERVAL 1 DAY)");
                         ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="lastweek" role="tabpanel" aria-labelledby="lastweek-tab">
                <div class="container">
                    <div class="container col-9 float-left">
                         <?php
                            displayUpvotes($_GET['parent'], $link, "cryptocurrency-h", " AND DATE(datetime) between SUBDATE(NOW(), 7) AND SUBDATE(NOW(), 1)");
                         ?>
                    </div>
                </div>
                <div class="container">
                    <div class="container col-9 float-left">
                         <?php
                            displayComments($_GET['parent'], "cryptocurrency", ""," AND DATE(datetime) between SUBDATE(NOW(), 7) AND SUBDATE(NOW(), 1)");
                         ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="lastmonth" role="tabpanel" aria-labelledby="lastmonth-tab">
                <div class="container">
                    <div class="container col-9 float-left">
                         <?php
                            displayUpvotes($_GET['parent'], $link, "cryptocurrency-h", " AND DATE(datetime) between SUBDATE(NOW(), 30) AND SUBDATE(NOW(), 1)");
                         ?>
                    </div>
                </div>
                <div class="container">
                    <div class="container col-9 float-left">
                         <?php
                            displayComments($_GET['parent'], "cryptocurrency", ""," AND DATE(datetime) between SUBDATE(NOW(), 30) AND SUBDATE(NOW(), 1)");
                         ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="currentyear" role="tabpanel" aria-labelledby="currentyear-tab">
                <div class="container">
                    <div class="container col-9 float-left">
                         <?php
                            displayUpvotes($_GET['parent'], $link, "cryptocurrency-h", " AND YEAR(datetime) = YEAR(NOW())");
                         ?>
                    </div>
                </div>
                <div class="container">
                    <div class="container col-9 float-left">
                         <?php
                            displayComments($_GET['parent'], "cryptocurrency", ""," AND YEAR(datetime) = YEAR(NOW())");
                         ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="lastyear" role="tabpanel" aria-labelledby="lastyear-tab">
                <div class="container">
                    <div class="container col-9 float-left">
                         <?php
                            displayUpvotes($_GET['parent'], $link, "cryptocurrency-h", " AND YEAR(datetime) = YEAR(DATE_SUB(NOW(), INTERVAL 1 YEAR))");
                         ?>
                    </div>
                </div>
                <div class="container">
                    <div class="container col-9 float-left">
                         <?php
                            displayComments($_GET['parent'], "cryptocurrency", ""," AND YEAR(datetime) = YEAR(DATE_SUB(NOW(), INTERVAL 1 YEAR))");
                         ?>
                    </div>
                </div>
            </div>
    </div>
</div>
    <div class="tab-pane fade" id="followers" role="tabpanel" aria-labelledby="followers-tab">
        <div class="container">
            <div class="container col-9 float-left">
                <?php
                    displayFollow("following", $_GET['parent'], $link, "cryptocurrency"); 
                 ?>

            </div>
        </div>
    </div>
<pre class="tab">




</pre>

