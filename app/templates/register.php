<html>
<head></head>
<body>
    <h1>Register as an user</h1>
    <form action="/register" method="POST">
    <label for="message">Login </label>
    <input type="text" name="login"/>
    <label for="message"> Password </label>
    <input type="password" name="password"/>
    <input type="submit" value="Sign in">
    <input type="hidden" name="_method" value="POST">
    </form>
    <a href="/statuses">List all statuses</a>
</body>
</html>