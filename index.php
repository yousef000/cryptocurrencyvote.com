<?php   
    session_start();
    $timezone = $_SESSION['time'];

    if($_SESSION['id'] > 0){
        echo $timezone;
        date_default_timezone_set($timezone);
        
    }
    
    $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
    if(!isset($_GET['start'])){
        $start = 1;
    }
    else{
        $start = $_GET['start'];
    }
    if($_GET['sort'] == ""){
        $parameters = [
        'start' => $start,
        'limit' => '30',
        'convert'=> 'USD',
        //'sort' => 'percent_change_24h',
        ];
    }
    else if($_GET['sort'] == "'gainers'"){
        $parameters = [
        'start' => $start,
        'limit' => '100',
        'convert'=> 'USD',
        'sort' => 'percent_change_24h',
        'sort_dir' => "desc"
        ];
    }
    else if($_GET['sort'] == "'losers'"){
        $parameters = [
        'start' => $start,
        'limit' => '100',
        'convert'=> 'USD',
        'sort' => 'percent_change_24h',
        'sort_dir' => "asc"
        ];
    }
    else if($_GET['sort'] == "'following'" || $_GET['sort'] == "'mostbuy'" || $_GET['sort'] == "'mostsell'"){
        $parameters = [
        'start' => $start,
        'limit' => '100',
        'convert'=> 'USD',
        ];
    }
    
    $headers = [
    'Accepts: application/json',
    'X-CMC_PRO_API_KEY: 35e53081-c0db-47e2-bf86-d3304c67c29f'
    ];
    $qs = http_build_query($parameters);
    $request = "{$url}?{$qs}"; // create the request URL
    
    $curl = curl_init(); // Get cURL resource
    // Set cURL options
    curl_setopt_array($curl, array(
                                   CURLOPT_URL => $request,            // set the request URL
                                   CURLOPT_HTTPHEADER => $headers,     // set the headers
                                   CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
                                   ));
    
    $response = curl_exec($curl); // Send the request, save the response
    $coins = json_decode($response, true); // print json decoded response

    curl_close($curl); // Close request
    include("functions.php");
    include("views/header.php");
    $active = getActivePage();
    include("views/navbar.php");

    if($_GET['page'] == 'cryptocurrency'){
        include("views/cryptocurrency.php");
    }
    else if($_GET['page'] == 'profile'){
        include("views/profile.php");
    }
    else if($_GET['page'] == 'feed'){
        include("views/feed.php");
    }
    else{ 
        include("views/home.php");
            
    }
   
    include("views/footer.php");
      
?>
    
