<?php
namespace App\Http\Controllers\Api\V1\Front;

use App\Http\Controllers\Controller;
use App\Services\StalkalotParserService;

class TestController extends Controller
{
    public function databaseMigration()
    {
        $parser = new StalkalotParserService();
        $parser->execute();
    }
}

