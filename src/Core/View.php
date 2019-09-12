<?php

namespace Jarenal\Core;

class View implements ViewInterface
{
    public function render($template, array $data)
    {
        $html = file_get_contents(PROJECT_ROOT_DIR . "/templates/" . $template);
        $keys = array_keys($data);
        foreach ($keys as $index => $value) {
            $keys[$index] = "{".$value."}";
        }
        $values = array_values($data);

        return str_replace($keys, $values, $html);
    }
}
