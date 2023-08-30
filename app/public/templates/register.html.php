<form class="centermaster" style="margin-top: 8px;" action="" method="post">

                        <label for="email">Email Address</label>
    
                        <input style="background-color: #a0be9d;" name="author[email]" id="email" type="email" value="<?=$author['email'] ?? ''?>" REQUIRED>
            
                        <label for="name">User Name</label>
   
                        <input style="background-color: #a0be9d;" name="author[name]" id="name" type="text" value="<?=$author['name'] ?? ''?>" REQUIRED>

                        <label for="password">Password</label>
 
                        <input style="background-color: #a0be9d;" name="author[password]" id="password" type="password" value="<?=$author['password'] ?? ''?>" REQUIRED>
          
                        <label for="passwordConfirm">Confirm Password</label>
  
                        <input style="background-color: #a0be9d;" name="author[passwordConfirm]" id="passwordConfirm" type="password" value="<?=$author['passwordConfirm'] ?? ''?>" REQUIRED>
                        
                        <label for="acceptedprivacypolicy">Accept <a href="/about/index">Privacy Policy</a></label>
                        
                        <input name="author[acceptedprivacypolicy]" id="acceptedprivacypolicy" type="checkbox" <?=!empty($author['acceptedprivacypolicy'])?'checked':''?> REQUIRED>
         
                <input class="navmaster2" style="margin-top: 8px;" type="submit" name="submit" value="Register Account">

</form>