<?php

namespace Jarenal\Core;

interface ViewInterface
{
    public function render($template, array $data);
}