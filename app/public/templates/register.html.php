<form style="align-items: center; justify-content: center; display: flex;" action="" method="post">
<table >
        <thead>
        </thead>
        <tbody>
            <tr>
                <tr>
                    <th>
                        <label for="email">Email Address</label>
                    </th>
                </tr>
                <tr>
                    <td>
                        <input name="author[email]" id="email" type="email" value="<?=$author['email'] ?? ''?>">
                    </td>
                </tr>
                <tr style="margin-bottom: 8px;">
                    <td>
                        <small></small>
                    </td>
                </tr>
            </tr>
            <tr>
                <tr>
                    <th>
                        <label for="name">User Name</label>
                    </th>
                </tr>
                <tr>
                    <td>
                        <input name="author[name]" id="name" type="text" value="<?=$author['name'] ?? ''?>">
                    </td>
                </tr>
                <tr style="margin-bottom: 8px;">
                    <td>
                        <small></small>
                    </td>
                </tr>
            </tr>
            <tr>
                <tr>
                    <th>
                        <label for="password">Password</label>
                    </th>
                </tr>
                <tr>
                    <td>
                        <input name="author[password]" id="password" type="password" value="<?=$author['password'] ?? ''?>">
                    </td>
                </tr>
                <tr style="margin-bottom: 8px;">
                    <td>
                        <small></small>
                    </td>
                </tr>
            </tr>
            <tr>
                <tr>
                    <th>
                        <label for="passwordConfirm">Confirm Password</label>
                    </th>
                </tr>
                <tr>
                    <td>
                        <input name="author[passwordConfirm]" id="passwordConfirm" type="password" value="<?=$author['passwordConfirm'] ?? ''?>">
                    </td>
                </tr>
                <tr style="margin-bottom: 8px;">
                    <td>
                        <small></small>
                    </td>
                </tr>
            </tr>
        </tbody>
        <tfoot>
            <td>
                <input class="navmaster2" type="submit" name="submit" value="Register Account">
            </td>
        </tfoot>
    </table>
</form>