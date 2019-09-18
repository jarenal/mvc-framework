<?php

namespace Jarenal\Core;

class View implements ViewInterface
{
    public function render($template, array $data)
    {
        if (strpos($template, ".tpl")) {
            $html = file_get_contents(PROJECT_ROOT_DIR . "/templates/" . $template);
        } else {
            $html = $template;
        }

        $cleanedData = [];

        foreach ($data as $key => $value) {
            if (!is_array($value) && !is_object($value)) {
                $cleanedData[$key] = $value;
            }
        }

        $keys = array_keys($cleanedData);
        foreach ($keys as $index => $value) {
            $keys[$index] = "{".$value."}";
        }
        $values = array_values($cleanedData);

        $output = str_replace($keys, $values, $html);

        $matches = [];
        preg_match('/{% foreach\((\w+) as (\w+)\) %}\n([\s\S]+){% endforeach %}/', $output, $matches);

        if (is_array($matches) && (count($matches) === 4)) {
            if (key_exists($matches[1], $data)) {
                if ($data[$matches[1]] && is_array($data[$matches[1]])) {
                    $foreachOutput = "";
                    foreach ($data[$matches[1]] as $item) {
                        if (is_object($item)) {
                            $itemData = get_object_vars($item);
                        } else {
                            $itemData = $item;
                        }

                        $foreachData = [];
                        if ($itemData && is_array($itemData)) {
                            foreach ($itemData as $key => $value) {
                                $foreachData["{$matches[2]}.$key"] = (string)$value;
                            }
                        }
                        $foreachOutput .= $this->render($matches[3], $foreachData);
                    }
                    $output = preg_replace('/{% foreach\(\w+ as \w+\) %}\n[\s\S]+{% endforeach %}/', $foreachOutput, $output);
                }
            }
        }
        return $output;
    }
}
