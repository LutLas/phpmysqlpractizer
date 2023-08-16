<form class="centermaster" action="" method="post">

                        <label for="email">Email Address</label>

                        <input name="author[email]" id="email" type="email" value="<?=$author['email'] ?? ''?>">

                        <label for="password">Password</label>

                        <input name="author[password]" id="password" type="password" value="<?=$author['password'] ?? ''?>">

                <input class="navmaster2" type="submit" name="submit" value="Login">

                <p>Don't have an account? <a href="/author/registrationform">Click here to register</a></p>

</form>