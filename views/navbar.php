<nav class="navbar navbar-expand-sm navbar-light bg-light navbar-position font-family">
          <img src="logo.png" width="25" height="25" class="d-inline-block align-top" style="margin-right:5px;">
          <a class="navbar-brand" href="#">
            CCV
          </a>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo $active[0]; ?>" id="home-nav" href="?page=home" style="font-weight: bold;">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $active[1]; ?>" id="about-nav" href="?page=about" style="font-weight: bold;">About</a>
                </li>
                <?php if($_SESSION['id'] > 0){?>
                    <li class="nav-item">
                    <?php echo '<a class="nav-link '.$active[2].'"  id="Profile-nav" href="?page=profile&username='.$_SESSION['username'].'"  style="font-weight: bold;">Profile</a>' ?>
                    </li>
                <?php } ?>
                <?php if($_SESSION['id'] > 0){?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $active[3]; ?>" href="?page=feed" id="feed-nav" style="font-weight: bold;">Feed</a>
                    </li>
                <?php } ?>
                
                <li class="nav-item float-right">
                    <a class="nav-link <?php echo $active[4]; ?>"  href="?page=home&sort='mostbuy'"  id="mostbuy-nav" style="font-weight: bold;">Most Buy</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $active[5]; ?>" href="?page=home&sort='mostsell'" id="mostsell-nav" style="font-weight: bold;">Most Sell</a>
                </li>
                 <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo $active[6]; ?>"  id="crypt-nav" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-weight: bold;">
                      Cryptocurrency
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                      <a class="dropdown-item" href="?page=home&sort='gainers'">Gainers</a>
                      <a class="dropdown-item"  href="?page=home&sort='losers'">Losers</a>
                      <a class="dropdown-item" href="?page=home">Top 100</a>
                        <?php if($_SESSION['id'] > 0){ ?>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item"  href="?page=home&sort='following'">Following</a>
                        <?php } ?>
                    </div>
                  </li>
                <li class="nav-item">
                    <form id="bloodhound">
                        <input class="form-control mr-sm-4 round-border navbar-input typeahead" id="search" type="text" placeholder="Search cryptocurrencies" autocomplete="off" aria-label="search" name="search" >

                    </form>
                </li>
            
            </ul>
            <div>
                <?php if($_SESSION['id']) { ?>
                    <a class="btn btn-outline-success my-2 my-sm-0" href="actions.php?function=logout">Logout</a> 
                <?php } else { ?>
                    <button class="btn btn-outline-success my-2 my-sm-0" data-toggle="modal" data-target="#myModal">Login</button>
                <?php } ?>

            </div>

        </div>
</nav>
    

                      