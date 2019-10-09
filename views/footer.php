
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="bower_components/jquery/dist/jquery.min.js"></script>
        <script src="typeahead.js"></script> 
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        
        <style>
        
        #loginAlert{
            display: none;
        }
        </style>
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="loginModalTitle">Login</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="alert alert-danger" id="loginAlert"></div>
                <form>
                  <input type="hidden" id="loginActive" name="loginActive" value="1">
                  <div class="form-group hiddenforlogin">
                    <label for="firstname">First Name</label>
                    <input type="firstname" class="form-control" id="firstname" placeholder="First Name" name="firstname">
                  </div>
                  <div class="form-group hiddenforlogin">
                    <label for="lastname">Last Name</label>
                    <input type="lastname" class="form-control" id="lastname" placeholder="Last Name" name="lastname">
                  </div>
                  <div class="form-group hiddenforlogin">
                    <label for="username">Username</label>
                    <input type="username" class="form-control" id="username" aria-describedby="usernameHelp" placeholder="Enter username" name="username">
                    <small id="usernameHelp" class="form-text text-muted">Only letters, numbers, and underscores.</small>
                  </div>
                  <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email" name="email">
                    <small id="emailHelp" class="form-text text-muted">We will never share your email with anyone else.</small>
                  </div>
                  <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <a class="text-primary" id="toggleLogin">Sign up</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="loginSignupButton" class="btn btn-primary">Login</button>
              </div>
            </div>
          </div>
        </div>

        <script>
            $("#toggleLogin").click(function() {
        
                if ($("#loginActive").val() == "1") {

                    $("#loginActive").val("0");
                    $("#loginModalTitle").html("Sign Up");
                    $("#loginSignupButton").html("Sign Up");
                    $("#toggleLogin").html("Login");
                    $(".hiddenforlogin").css("display", "block");
            
            
                } else {

                    $("#loginActive").val("1");
                    $("#loginModalTitle").html("Login");
                    $("#loginSignupButton").html("Login");
                    $("#toggleLogin").html("Sign up");
                    $(".hiddenforlogin").css("display", "none");

                }
        
        
            })
            
            $("#loginSignupButton").click(function() {
                $.ajax({
                    type: "POST",
                    url: "actions.php?action=loginSignup",
                    data: "email=" + $("#email").val() + "&password=" + $("#password").val() + "&firstname=" + $("#firstname").val() + "&lastname=" + $("#lastname").val() + "&username=" + $("#username").val() + "&loginActive=" + $("#loginActive").val(),
                    success: function(result) {
                        if (result == "1") {
                            window.location.assign("index.php");
                    
                        } else {
                    
                            $("#loginAlert").html(result).show();
                    
                        }

                    }
                
                    

                })

            })
            $(".postCommentButton").click(function(){
                var x;
                 $.ajax({
                    type: "POST",
                    url: "actions.php?action=postComment",
                    data: "commentContent=" + $("#" + this.id + "1").val() + "&parent=" + (this.id).split(1,1),
                    success: function(result) {
                        if(result != "") {
                            $("#commentSuccess").show();
                            setTimeout(function(){ $('#commentSuccess').fadeOut() }, 5000);
                            $("#commentFail").hide();
                            x = result;
                            
                            
                            
                        } else if(result != "") {
                            $("#commentFail").html(result).show();
                            setTimeout(function(){ $('#commentFail').fadeOut() }, 5000);
                            $("#commentSuccess").hide();
                            
                        }
                    
                    }
                 
                     
                
                    

                })
                
                $("#" + this.id + "1").val("");
                $("#" + this.id + "1").height(40);
                
                
                
            })
            $(".creplyButton").click(function(){        //for comment page
                var index = (this.id).indexOf("r");
                var id = (this.id).substr(0, index);
                var parent = (this.id).substr(index+1);
                
                $("#comment" + id).show();              //replyContent
                $("#" + id).show();                     //postReplyButton
                $("#" + id + "c" + parent).show();      //commentButton
                
                $("#commentFail").hide();
                $("#commentSuccess").hide();
            })
            $(".replyButton").click(function(){
                    
                    var index = (this.id).indexOf("r");
                    var id = (this.id).substr(0, index);
                    var parent = (this.id).substr(index+1);
                    
                    $("#comment" + id).show();              //replyContent
                    $("#" + id).show();                     //postReplyButton
                    $("#" + id + "c" + parent).show();              //commentButton
                    $("#" + this.id).hide();                //replyButton
                    $("#" + parent + "11").hide();          //commentContent
                    $("#" + parent + "1").hide();           //postCommentButton
                
                    $("#commentFail").hide();
                    $("#commentSuccess").hide();
                
                
            })
            $(".commentButton").click(function(){
                var index = (this.id).indexOf("c");
                var id = (this.id).substr(0, index);
                var parent = (this.id).substr(index+1);
                
                $("#comment" + id).hide();              //replyContent
                $("#" + id).hide();                     //postReplyButton
                $("#" + this.id).hide();               //commentButton
                $("#" + id + "r" + parent).show();                //replyButton
                $("#" + parent + "11").show();          //commentContent
                $("#" + parent + "1").show();           //postCommentButton
                
                $("#commentFail").hide();
                $("#commentSuccess").hide();
                
                
            })
            $(".postReplyButton").click(function(){
                $.ajax({
                    type: "POST",
                    url: "actions.php?action=postReply",
                    data: "replyContent=" + $("#comment" + this.id).val() + "&parent=" + this.id,
                    success: function(result) {
                        if(result == "1") {
                            $("#commentSuccess").show();
                            setTimeout(function(){ $('#commentSuccess').fadeOut() }, 5000);
                            $("#commentFail").hide();
                            
                    
                        } else if(result != "") {
                            $("#commentFail").html(result).show();
                            setTimeout(function(){ $('#commentFail').fadeOut() }, 5000);
                            $("#commentSuccess").hide(); 
                        }
                    
                    }
                
                    

                })
                $("#comment" + this.id).val("")
                $("#comment" + this.id).height(40)
            })
            
            $(".upvotes").click(function(){
                var upvotes;
                var $t = $("#" + this.id);
              
                $.ajax({
                    type: 'POST',
                    url: "actions.php?action=postUpvote",
                    data: "commentid=" + (this.id).split("u",1),
                    success: function(result) {
                        
                        upvotes = result;
                        upvotes--;
                        
                        if($t.html() == "Upvote(" + upvotes +")"){
                            upvotes++;
                            $t.html("Upvoted(" + upvotes + ")");
                            $t.css("color", "red");

                        }
                        else{
                            upvotes++;
                            $t.html("Upvote(" + upvotes + ")");
                            $t.css("color", "blue");
                        }
                    }
                    

                })
                
                
            })
            $(".buyButton").click(function(){
                var buyVote;
                var sellVote;
                var $returnedarr = new Array();
                var $t = $("#" + this.id);
                var $x = $("#" + (this.id).split("bb",1) + "ssell");
                var $y = $("#" + (this.id).split("bb",1) + "totalvotes");
                var initialbuy = $t.html().split("%",1);
                var initialsell = $x.html().split("%",1);
                initialbuy = parseFloat(initialbuy);
                var initialtotalvote = parseFloat($y.html());
                $.ajax({
                    type: 'POST',
                    url: "actions.php?action=postBuyVote",
                    data: "parent=" + (this.id).split("bb",1),
                    success: function(result) {  
                        returnedarr = result;
                        buyVote = returnedarr[0];
                        switchvote = returnedarr[1];
                        voteexisted = returnedarr[2];
                        sellVote = 0;
                        $t.html(buyVote + "%");
                        if($x.html() != "0%"){
                            sellVote = 100 - buyVote;
                            $x.html(sellVote + "%");
                        }
                        if(buyVote < initialbuy){
                            $y.html(initialtotalvote-1);
                        }
                        else if(buyVote > initialbuy && sellVote < initialsell){
                            if(switchvote == 1){
                                $y.html(initialtotalvote);
                            }
                            else{
                                $y.html(initialtotalvote+1);
                            }
                        }
                        else if(buyVote == initialbuy){
                            if(voteexisted == 0){
                                $y.html(initialtotalvote+1);
                            }
                            else{
                                $y.html(initialtotalvote-1);
                            }
                            
                        }
                        else{
                            $y.html(initialtotalvote+1);
                        }
                        if(voteexisted == 0){
                            $t.removeClass('btn-info');
                            $t.addClass('btn-success');
                            $x.removeClass('btn-success');
                            $x.addClass('btn-info');
                            
                        }
                        else{
                            $t.removeClass('btn-success');
                            $t.addClass('btn-info');
                           
                           
                        }
                        
                        
                        
                    },
                    dataType:"json"
                    

                })
                
                
            })
            $(".sellButton").click(function(){
                var sellVote;
                var buyVote;
                var $returnedarr = new Array();
                var $t = $("#" + this.id);
                var $x = $("#" + (this.id).split("ss",1) + "bbuy");
                var $y = $("#" + (this.id).split("ss",1) + "totalvotes");
                var initialsell = $t.html().split("%",1);
                var initialbuy = $x.html().split("%",1);
                initialsell = parseFloat(initialsell);
                var initialtotalvote = parseFloat($y.html());
                $.ajax({
                    type: 'POST',
                    url: "actions.php?action=postSellVote",
                    data: "parent=" + (this.id).split("ss",1),
                    success: function(result) {  
                        returnedarr = result;
                        sellVote = returnedarr[0];
                        switchvote = returnedarr[1];
                        voteexisted = returnedarr[2];
                        buyVote = 0;
                        $t.html(sellVote + "%");
                        if($x.html() != "0%"){
                            buyVote = 100 - sellVote;
                            $x.html(buyVote + "%");
                        }
                        if(sellVote < initialsell){
                            $y.html(initialtotalvote-1);
                        }
                        else if(sellVote > initialsell && buyVote < initialbuy){
                            if(switchvote == 1){
                                $y.html(initialtotalvote);
                            }
                            else{
                                $y.html(initialtotalvote+1);
                            }
                            
                        }
                        else if(sellVote == initialsell){
                            if(voteexisted == 0){
                                $y.html(initialtotalvote+1);
                            }
                            else{
                                $y.html(initialtotalvote-1);
                            }
                            
                        }
                        else{
                            $y.html(initialtotalvote+1);
                        }
                        if(voteexisted == 0){
                            $t.removeClass('btn-info');
                            $t.addClass('btn-success');
                            $x.removeClass('btn-success');
                            $x.addClass('btn-info');
                        
                        }
                        else{
                            $t.removeClass('btn-success');
                            $t.addClass('btn-info');
                          
                        }
                        
                    },
                    dataType:"json"
                    

                })
                
                
            })
            $(".view-more-comments").click(function(){
                $(".more-comments").css("display", "block");
                $(".view-more-comments").hide();
               
            });
             $(".view-more-replies").click(function(){
                $(".more-replies-" + this.id ).css("display", "block");
                $("#" + this.id).hide();
               
            });
            
            $(".desc-save-button").click(function(){
                $x = $("#" + this.id + "d");
                $y = $("#" + this.id + "t");
                $.ajax({
                    type: "POST",
                    url: "actions.php?action=postDescription",
                    data: "descContent=" + $("#" + this.id + "t").val() + "&parent=" + this.id,
                    success: function(result) {
                        if(result == "1") {
                            $x.text($y.val());
                        }
            
                    }
                
                    

                })
                
            })
             $(".followuser-button").click(function(){
                var $x = $("#" + this.id);
                var userid = (this.id).split("f",1);
                $.ajax({
                    type: "POST",
                    url: "actions.php?action=postFollowUser",
                    data: "following=" + userid,
                    success: function(result) {
                        if(result == 1){
                            $x.text("Unfollow");
                        }
                        else{
                            $x.text("Follow");
                        }
            
                    }
                
                    

                })
                
            })
            $(".followcrypt-button").click(function(){
                var $x = $("#" + this.id);
                var userid = (this.id).split("f",1);
                $.ajax({
                    type: "POST",
                    url: "actions.php?action=postFollowCrypt",
                    data: "following=" + userid,
                    success: function(result) {
                        if(result == 1){
                            $x.text("Unfollow");
                        }
                        else{
                            $x.text("Follow");
                        }
            
                    }
                
                    

                })
                
            })
            $(".deleteComment").click(function(){
                alert(this.id);
                var $x = $("#" + (this.id).split("d", 1) + "card");
                var commentid = (this.id).split("d",1);
                $.ajax({
                    type: "POST",
                    url: "actions.php?action=postDeleteComment",
                    data: "commentid=" + commentid,
                    success: function(result) {
                        $x.css("display", "none");
            
                    }
                
                    

                })
            })
            $(".deleteReply").click(function(){
                var $x = $("#" + (this.id).split("d", 1) + "card");
                var replyid = (this.id).split("d",1);
                $.ajax({
                    type: "POST",
                    url: "actions.php?action=postDeleteReply",
                    data: "replyid=" + replyid,
                    success: function(result) {
                        $x.css("display", "none");
            
                    }
                
                    

                })
            })
            $.fn.enterKey = function (fnc) {
                return this.each(function () {
                    $(this).keypress(function (ev) {
                        var keycode = (ev.keyCode ? ev.keyCode : ev.which);
                        if (keycode == '13') {
                            fnc.call(this, ev);
                        }
                    })
                })
            }
            $("#bloodhound").enterKey(function () {
                $.ajax({
                    type: "POST",
                    url: "actions.php?action=getSlug",
                    data: "parent=" + $("#search").val(),
                    success: function(result) {
                         window.location.replace("index.php?page=cryptocurrency&parent=" + result);
            
                    }
                
                    

                })
               
                
            })
            $(document).ready(function() {
                if("<?php echo $timezone; ?>".length==0){
                    var visitortime = new Date();
                    var visitortimezone = visitortime.getTimezoneOffset()/60;
                    $.ajax({
                        type: "POST",
                        url: "actions.php?action=getTimezone",
                        data: 'time='+ visitortimezone,
                        success: function(){
                            location.reload();
                        }
                    });
                }
            });
            var cryptocurrencies = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.whitespace,
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                // url points to a json file that contains an array of country names, see
                // https://github.com/twitter/typeahead.js/blob/gh-pages/data/countries.json
                prefetch: 'countries.json'
            });

                // passing in `null` for the `options` arguments will result in the default
                // options being used
            $('#bloodhound .typeahead').typeahead(null, {
                name: 'cryptocurrencies',
                source: cryptocurrencies
            });
            
            


        </script>

    </body>
</html>
 