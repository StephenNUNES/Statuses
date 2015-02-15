<html>
<head></head>
<body>
    <h1>Login as an user</h1>
    <form action="/login" method="POST">
    <label for="message">Login </label>
    <input type="text" name="login"/>
    <label for="message"> Password </label>
    <input type="password" name="password"/>
    <input type="submit" value="Log in">
    <input type="hidden" name="_method" value="POST">
    </form>
    <p><?php if (isset($error)): echo $error; endif ?></p>
    <a href="/statuses">List all statuses</a>
</body>
</html>
