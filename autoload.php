<?php

spl_autoload_register(function ($classNameBeforeModifications)
{
    $CACHEFILE_NAME = __DIR__ . "/vendor/cache.php";

    // Gestion des caracètres '_' et '\' entre les noms de classes et namespaces pour être PSR-0 complient
    if (!strpos($classNameBeforeModifications, "_"))
    {
        $fullQualifiedNameClass = str_replace("\\", "/", $classNameBeforeModifications);
    } 
    else if (!strpos($classNameBeforeModifications, "\\"))
    {
        $fullQualifiedNameClass = str_replace("_", "/", $classNameBeforeModifications);
    }

    // Ouverture du fichier de cache
    if (is_file($CACHEFILE_NAME))
    {
        $fileResource = require $CACHEFILE_NAME;
    }
    else
    {
        $fileResource = [];
    }

    if (!isset($fileResource[$fullQualifiedNameClass]))
    {
        $rootClass = ["src/", "tests/", ""];
        foreach ($rootClass as $root)
        {
            $classNameToLoad = __DIR__ . DIRECTORY_SEPARATOR . $root .  $fullQualifiedNameClass . ".php";
            if (is_file($classNameToLoad))
            {
                break;
            }
            else
            {
                $classNameToLoad = null;
            }
        }
        if ($classNameToLoad === null)
        {
            return false;
        }
        

        // Enregistrement dans la map de cache
        $fileResource[$fullQualifiedNameClass] = $classNameToLoad;

        // Enregistrement dans le fichier de cache
        //file_put_contents($CACHEFILE_NAME, sprintf("<?php \n return %s;", var_export($fileResource, true)));

        require_once $fileResource[$fullQualifiedNameClass];

        return true;
    }
    return false;
}
);

