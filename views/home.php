<div id="commentSuccess" class="alert alert-primary popup float-inherit">Your response was posted successfully.</div>
<div id="commentFail" class="alert alert-secondary popup float-inherit"></div>
<div class="row align-items-center justify-content-center text-primary">
   <a style="width: 75px; margin-bottom:10px; font-weight: bold; margin-top: 100px;">TODAY</a>

</div>
<div class="container-fluid stick-to-top fix-width" style="max-width: 880px;">
    <div class="card">
        <div class="card-header" id="headingOne">
            <p class="mb-0">
                <?php
                    echo '<div class="col-2 float-left bold table-font-size" id="table-header-name-margins">Name</div>'; 
                    echo '<div class="col-1 float-left bold table-font-size" id="table-header-volume-margins">Volume</div>';
                    echo '<div class="col-2 float-left bold table-font-size" id="table-header-price-margins">Price</div>';
                    echo '<div class="col-2 float-left bold table-header-change-margins table-font-size">Change (24h)</div>';
                    
                    
                    echo '<div class="col-1 float-right bold table-header-change-margins table-font-size" >Votes</div>';
                    echo '<div class="col-1 float-right bold table-header-change-margins table-font-size">Buy</div>';
                    echo '<div class="col-1 float-right bold table-header-change-margins table-font-size">Sell</div>';
                    
                ?>
            </p>
        </div>
    </div>
</div>

    
        <div class="container fix-width" id="accordionExample" style="max-width: 880px;">
           
            <?php
            
            if($_GET['sort'] == "'following'"){
                echo "<title>Following | CryptocurrencyVote</title>";
                $query = "SELECT * FROM followcrypt where userid = '".$_SESSION['id']."'";
                $result = mysqli_query($link, $query);
            
                while($row = mysqli_fetch_assoc($result)){
                    $query = "SELECT * FROM slugs WHERE name = '".$row['following']."' LIMIT 1";
                    $sresult = mysqli_query($link, $query);
                    $slug = mysqli_fetch_assoc($sresult);
                    displayCryptoInfo($slug['id']-1, $link, $coins);

                }
                
            }
            else if($_GET['sort'] == "'mostbuy'"){
                echo "<title>Most Buy | CryptocurrencyVote</title>";
                $query = "SELECT count(parent), parent
                          FROM buyvotes WHERE DATE(datetime) = DATE(NOW())
                          GROUP BY parent
                          ORDER BY count(parent) DESC";
                $result = mysqli_query($link, $query);
                if(mysqli_num_rows($result) <= 0){
                    echo "No buy votes yet today";
                }
                
                while($row = mysqli_fetch_assoc($result)){
                    $query = "SELECT * FROM slugs WHERE name = '".$row['parent']."' LIMIT 1";
                    $sresult = mysqli_query($link, $query);
                    $slug = mysqli_fetch_assoc($sresult);
                    displayCryptoInfo($slug['id']-1, $link, $coins);
                }
            }
            else if($_GET['sort'] == "'mostsell'"){
                echo "<title>Most Sell | CryptocurrencyVote</title>";
                $query = "SELECT count(parent), parent
                          FROM sellvotes WHERE DATE(datetime) = DATE(NOW())
                          GROUP BY parent
                          ORDER BY count(parent) DESC";
                $result = mysqli_query($link, $query);
                if(mysqli_num_rows($result) <= 0){
                    echo "No sell votes yet today";
                }
                
                while($row = mysqli_fetch_assoc($result)){
                    $query = "SELECT * FROM slugs WHERE name = '".$row['parent']."' LIMIT 1";
                    $sresult = mysqli_query($link, $query);
                    $slug = mysqli_fetch_assoc($sresult);
                    displayCryptoInfo($slug['id']-1, $link, $coins);
                }
            }
            else{
                for($i = 0; $i < count($coins[data]); $i++){ 
                    if($_GET['sort'] == "'gainers'"){
                        echo "<title>Biggest Gainers | CryptocurrencyVote</title>";
                        if($coins[data][$i]['quote']['USD']['percent_change_24h'] < 0 || $coins[data][$i]['quote']['USD']['volume_24h'] <= 50000 || $coins[data][$i]['quote']['USD']['volume_24h'] == ""){
                            continue;
                        }

                    }
                    else if($_GET['sort'] == "'losers'"){
                        echo "<title>Biggest Losers | CryptocurrencyVote</title>";
                        if($coins[data][$i]['quote']['USD']['percent_change_24h'] > 0 || $coins[data][$i]['quote']['USD']['volume_24h'] <= 50000 || is_nan($coins[data][$i]['quote']['USD']['volume_24h']) == true){
                            continue;
                        }
                    }
                    else{
                        echo "<title>Home | Top 100 | CryptocurrencyVote</title>";
                    }
                ?>
                  
                    <?php displayCryptoInfo($i, $link, $coins); ?>
               
                     
            <?php
                }
            }
                    
                    
                    
            
            $sort = $_GET['sort']; 
            
            if($_GET['sort'] == ""){ ?> 
                <a class="btn btn-outline-primary my-2 my-sm-0 float-right" href="?page=home&start=<?php echo $start+30; ?>&sort=<?php echo $sort;?>">Next</a>
            <?php } 
            else {?>
                <a class="btn btn-outline-primary my-2 my-sm-0 float-right" href="?page=home&start=<?php echo $start+100; ?>&sort=<?php echo $sort;?>">Next</a>
            
            <?php } ?>
            
        </div>
       
