$link = mysqli_connect("localhost", "yousef", "171120101990Qq!", "acrypt");
 if (mysqli_connect_error()) {
 
 die ("There was an error connecting to the database");
 }
 $query = "INSERT INTO users (email, password) VALUES('heldddlo@gmail.com', 'heyhey')";
 
 
 
 mysqli_query($link, $query);
 
 
 $query = "UPDATE users SET email = 'hey@gmail' WHERE id = 1 LIMIT 1";
 mysqli_query($link, $query);
 $query = "UPDATE users SET password = '123edede' WHERE email = 'hey@gmail' LIMIT 1";
 mysqli_query($link, $query);
 
 $query = "SELECT * FROM users";
 
 if ($result = mysqli_query($link, $query)){
 while($row = mysqli_fetch_array($result)){
 echo "Your email is ".$row['email']." and your password is ".$row['password']."<br><br>";
 }
 
 }
 
 $query = "SELECT `email` FROM users WHERE email LIKE '%g%'";
 if ($result = mysqli_query($link, $query)){
 while($row = mysqli_fetch_array($result)){
 echo "Your email is ".$row['email']."<br><br>";
 }
 
 }
 $query = "SELECT * FROM users WHERE name LIKE 'yusuf%'";
 if ($result = mysqli_query($link, $query)){
 while($row = mysqli_fetch_array($result)){
 echo "Your name is ".$row['name']."<br><br>";
 }
 
 }
 $name = "yusuf dost";
 $query = "SELECT `email` FROM users WHERE name = '".mysqli_real_escape_string($link,$name)."'";
 if ($result = mysqli_query($link, $query)){
 while($row = mysqli_fetch_array($result)){
 echo "Your name is ".$row['email']."<br><br>";
 }
 
 }
 
 
 session_start();
 if (array_key_exists('email', $_POST) OR array_key_exists('password', $_POST)) {
 
 $link = mysqli_connect("localhost", "yousef", "171120101990Qq!", "acrypt");
 
 if (mysqli_connect_error()) {
 
 die ("There was an error connecting to the database");
 
 }
 
 
 if ($_POST['email'] == '') {
 
 echo "<p>Email address is required.</p>";
 
 } else if ($_POST['password'] == '') {
 
 echo "<p>Password is required.</p>";
 
 } else {
 
 $query = "SELECT `id` FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
 
 $result = mysqli_query($link, $query);
 
 if (mysqli_num_rows($result) > 0) {
 
 echo "<p>That email address has already been taken.</p>";
 
 } else {
 
 $query = "INSERT INTO `users` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";
 
 if (mysqli_query($link, $query)) {
 
 $_SESSION['email'] = $_POST['email'];
 header("Location: session.php");
 
 } else {
 
 echo "<p>There was a problem signing you up - please try again later.</p>";
 
 }
 
 }
 
 }
 
 
 }
 /**
 setcookie("customerid", "1234", time() + (60*60*24)); //set cookie
 $_COOKIE["customerid"] = "test";
 echo $_COOKIE['customerid'];
 setcookie("customerid", "", time() - 60*60); //delete cookie
 **/
