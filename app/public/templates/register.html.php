<form class="centermaster" action="" method="post">

                        <label for="email">Email Address</label>
    
                        <input name="author[email]" id="email" type="email" value="<?=$author['email'] ?? ''?>">
            
                        <label for="name">User Name</label>
   
                        <input name="author[name]" id="name" type="text" value="<?=$author['name'] ?? ''?>">

                        <label for="password">Password</label>
 
                        <input name="author[password]" id="password" type="password" value="<?=$author['password'] ?? ''?>">
          
                        <label for="passwordConfirm">Confirm Password</label>
  
                        <input name="author[passwordConfirm]" id="passwordConfirm" type="password" value="<?=$author['passwordConfirm'] ?? ''?>">
         
                <input class="navmaster2" type="submit" name="submit" value="Register Account">

</form>