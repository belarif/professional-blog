<?php

namespace ProfessionalBlog\Model;

use PDO;

class Manager
{
    protected function dbConnect()
    {
        $db = new \PDO('mysql:host=localhost;dbname=blog-hocine;charset=utf8','root','',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        return $db;
    }
}