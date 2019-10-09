<?php include("header.php");?>

<style>
    body{
        margin-top: 200px;
        background-color:aliceblue;
    }
</style>

<div class="row align-items-center justify-content-center">
    <form>
      <div class="form-group">
        <label for="email">Email address</label>
        <input type="email" class="form-control login-input" id="email" aria-describedby="emailHelp" placeholder="Enter email" name="email">
        <small id="emailHelp" class="form-text text-muted">We will never share your email with anyone else.</small>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control login-input" id="password" placeholder="Password" name="password">
      </div>
      <button type="Login" id="loginButton" class="btn btn-primary">Login</button>
    </form>
</div>
<script type=text/javascript>
    $("#loginButton").click(function(){
        
        $.ajax({
            type: "POST",
            url: "actions.php?action=login",
            data: "email=" + $("#email").val() + "&password=" + $("#password").val(),
            success: function(result) {
                alert(result);
            }
            
        })
        alert("Hi");
    })



</script>
<?php include("footer.php");?>