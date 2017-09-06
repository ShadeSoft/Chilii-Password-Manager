<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller {

    public function index(Request $request) {
        return $this->render('Page/index.html.twig');
    }
}