<?php
//require 'config.php';

if (!isValidSession())
{
    redirectToMain(true);
}