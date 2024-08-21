<?php

    spl_autoload_register(function ($className) {
        $classFile = __DIR__ . "/../classes/" . $className . ".class.php";

        if (file_exists($classFile)) {
            include $classFile;
        }
    });
