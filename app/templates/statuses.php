<!DOCTYPE html>
<head></head>
<body>
    <?php if (isset($login)): $username = $login; else: $username = "Anonymous"; endif ?>
    
    <?php if (isset($login)): ?>   
    <form action="/logout" method="POST">
    <?= $username ?> is connected. 
    <input type="submit" value="Log out">
    <input type="hidden" name="_method" value="POST">
    <a href="/statuses?where=<?= $username ?>">List own statuses</a>
    </form>
    <?php endif ?>
    <h1>All statuses</h1>
    <table>
        <?php foreach ($statuses as $key => $value): ?>
            <tr><td><a href="statuses/<?= $value->getId() ?>" ><?= $value ?></a></td></tr>
        <?php endforeach; ?>
    </table>
    <br />
    <br />
    <br />
    <form action="/statuses" method="POST">
    
    <label for="message"><?= $username ?> would says</label>
    <input type="hidden" name="username" value="<?= $username ?>">
    <br />
    <textarea name="message" placeholder="I'm a teapot"></textarea>

    <input type="submit" value="Send your words !">
    <input type="hidden" name="_method" value="POST">
    </form>
    <?php if (!isset($login)): ?>
    <a href="/login">Log in</a>
    <a href="/register">Register</a>
    <?php endif ?>
   
</body>
</html>


   



 
