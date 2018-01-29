<?php
namespace AppBundle\Handler;

use Symfony\Component\HttpFoundation\Request;

interface CoreHandlerInterface
{
    public function handle(Request $request);
}