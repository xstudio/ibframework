<?php

Class UserController extends Controller
{
    public function index()
    {
        $this->render('index');
    }

    public function center()
    {
        $this->render('center');
    }
}
