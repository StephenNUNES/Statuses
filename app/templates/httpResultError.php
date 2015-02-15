<!DOCTYPE html>
<head></head>
<body>
    <h1>Oops, I'm so sad because</h1>
    <p>
        <?= $httpException->getStatusCode() ?> 
        <?= $httpException->getMessage() ?> 
    </p>
        
</body>
</html>
