
<form class="centermaster larger" style="margin-top: 8px;" action="" method="post">

                        <label for="email">Email Address</label>

                        <input style="background-color: #a0be9d;" name="author[email]" id="email" type="email" value="<?=$author['email'] ?? ''?>" class="box" REQUIRED>

                        <label for="password">Password</label>

                        <input style="background-color: #a0be9d;" name="author[password]" id="password" type="password" value="<?=$author['password'] ?? ''?>" REQUIRED>

                <input style="margin-top: 8px;" class="navmaster2" type="submit" name="submit" value="Login">

                <p  style="margin-top: 8px;">Don't have an account? <a href="/author/registrationform">Click here to register</a></p>

</form>