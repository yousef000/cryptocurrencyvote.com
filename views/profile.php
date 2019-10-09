<pre class="tab">


</pre>
<?php 
    $query = "SELECT * FROM users WHERE username = '". mysqli_real_escape_string($link, $_GET['username'])."' LIMIT 1";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_assoc($result);

?>
<div class="profile-header text-center" style="background-color: white; color: black; height: 20; width: 20;">
    <div class="container-fluid">
    <div class="container-inner">
      <img class="rounded-circle media-object" src=" <?php echo $row['image']; ?>" style="object-fit:cover;object-postion:center right;
  width: 150px;
  height: 150px;">
        <?php 
        if($_SESSION['id'] > 0 && $_SESSION['id'] == $row['id']){ ?>
            <a data-toggle="modal" data-target="#imageModal" >
            <img src="upload.png" style="height:15px;margin-top:125px;">
            </a>
        <?php }
          echo '<h3 class="profile-header-user black-font">'.$row['firstname'].' '.$row['lastname'].'</h3>';
          echo "<title>".$row['firstname']." ".$row['lastname']." | CryptocurrencyVote</title>";
        ?>
    <?php echo '<h4 class="profile-header-user black-font">@'.$row['username'].'</h4>' ?>
      <p class="profile-header-bio black-font" id=<?php echo $row['username'].'d';?>> <?php echo $row['description'];?>  </p>
        <?php 
        if($_SESSION['id'] > 0 && $_SESSION['id'] == $row['id']){ ?>
            <a data-toggle="modal" data-target="#exampleModal"><img src="edit.png" height="15"></a>
        <?php }
        if($_SESSION['id'] > 0 && $_SESSION['id'] != $row['id']){ 
            $query = "SELECT * FROM followuser WHERE userid = '".$_SESSION['id']."' AND following = '".$row['id']."'";
            $fresult = mysqli_query($link, $query);
            if(mysqli_num_rows($fresult) <= 0){ ?>
                <button class="btn btn-primary followuser-button" id="<?php echo $row['id']."f"; ?>">Follow</button>
            <?php }
            else{ ?>
                <button class="btn btn-primary followuser-button" id="<?php echo $row['id']."f"; ?>">Unfollow</button>
            <?php } 
            
        } ?>
        
        
    </div>
        <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Write Description</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <textarea id=<?php echo $row['username'].'t';?> oninput='this.style.height = "";this.style.height = this.scrollHeight + "px"' class="textarea bg-light text-secondary col-11 " placeholder="e.g expertise, work, school, what cryptocurrency you own etc"></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <?php echo '<button type="button" class="btn btn-primary desc-save-button" id="'.$row['username'].'" data-dismiss="modal">Save changes</button>' ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="imageModalLabel">Upload image</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              
                  <form action="upload.php?parent=<?php echo $row['username']; ?>" method="post" enctype="multipart/form-data">
                      <div class="form-group">
                        Select image to upload:
                        <input type="file" name="fileToUpload" id="fileToUpload">
                          </div>
                      <input class= "btn btn-primary" type="submit" value="Upload Image" name="submit">
                  </form>
              
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
        
    
        
    </div>
    <nav class="profile-header-nav font-family">
        <ul class="nav justify-content-center" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="comment-tab" data-toggle="tab" href="#comment" role="tab" aria-controls="comment" >Comments</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="upvote-tab" data-toggle="tab" href="#upvote" role="tab" aria-controls="upvote" aria-selected="false">Upvotes</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="buyvote-tab" data-toggle="tab" href="#buyvote" role="tab" aria-controls="buyvote" aria-selected="false">Buy</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="sellvote-tab" data-toggle="tab" href="#sellvote" role="tab" aria-controls="sellvote" aria-selected="false">Sell</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="following-tab" data-toggle="tab" href="#following" role="tab" aria-controls="following" aria-selected="false">Following</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="followers-tab" data-toggle="tab" href="#followers" role="tab" aria-controls="followers" aria-selected="false">Followers</a>
              </li>

        </ul>
    </nav>
</div>
<div id="commentSuccess" class="alert alert-primary popup float-inherit">Your response was posted successfully.</div>
<div id="commentFail" class="alert alert-secondary popup float-inherit"></div>
<div class="tab-content" id="myTabContent">
     <div class="tab-pane fade show active" id="comment" role="tabpanel" aria-labelledby="comment-tab">
        <?php
            echo "<div class=container>";
            echo "<div class='container col-md-8 float-left'>";
            displayComments($row['id'], "profile", "comment", "");
            echo "</div>";
            echo "</div>";
        ?>
    </div>
    <div class="tab-pane fade" id="upvote" role="tabpanel" aria-labelledby="upvote-tab">
        <?php
            $query = "SELECT * FROM upvotes WHERE userid = ".$row['id']." ORDER BY datetime DESC";
            $uresult = mysqli_query($link, $query);
            while($urow = mysqli_fetch_assoc($uresult)){
                echo "<div class=container>";
                echo "<div class='container col-md-8 float-left'>";
                    displayComments($urow['id'], "profile", "upvote", "");
                echo "</div>";
                echo "</div>";

            }
        ?>
    
    </div>
    <div class="tab-pane fade" id="buyvote" role="tabpanel" aria-labelledby="buyvote-tab">
        <?php
            echo "<div class=container>";
            echo "<div class='container col-md-8 float-left'>";
            displayComments($row['id'], "profile", "buyvote", "");
            echo "</div>";
            echo "</div>";
        ?>
    </div>
    <div class="tab-pane fade" id="sellvote" role="tabpanel" aria-labelledby="sellvote-tab">
        <?php
            echo "<div class=container>";
            echo "<div class='container col-md-8 float-left'>";
            displayComments($row['id'], "profile", "sellvote", "");
            echo "</div>";
            echo "</div>";
        ?>
    </div>
    <div class="tab-pane fade" id="following" role="tabpanel" aria-labelledby="following-tab">
        <?php 
            displayFollow("userid", $row['id'], $link, "profile"); 
        ?>
    </div>
    <div class="tab-pane fade" id="followers" role="tabpanel" aria-labelledby="followers-tab">
        <?php 
            displayFollow("following", $row['id'], $link, "profile"); 
        ?>
    </div>
</div>







    
