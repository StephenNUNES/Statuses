<!DOCTYPE html>
<head></head>
<body>
    <h1>One status</h1>
    <p> <?= $status ?> </p>
    <?php if (isset($login) && $status->getNameCreator() === $login): ?>
        <form action="/statuses/<?= $id ?>" method="POST">
        <input type="hidden" name="_method" value="DELETE">
        <input type="submit" value="Delete">
        </form>
    <?php endif ?>
</body>
</html>
