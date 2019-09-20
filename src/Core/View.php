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

        $output = $this->parseForeachBlocks($html, $data);

        $output = $this->parseSwitchBlocks($output, $data);

        preg_match_all("/{([\w.?]+)}/", $output, $tags);

        if (is_array($tags) && $tags) {
            foreach ($tags[1] as $tag) {
                $tagPath = explode(".", $tag);
                $value = $this->getValueFromDotNotation($tagPath, $data);
                $output = preg_replace("/{" . $tag . "}/", $value, $output);
            }
        }

        return $output;
    }

    private function parseForeachBlocks($template, $data)
    {
        $matches = [];
        preg_match('/{% foreach\((\w+) as (\w+)\) %}\n([\s\S]+){% endforeach %}/', $template, $matches);

        if (is_array($matches) && (count($matches) === 4)) {
            $pathKeys = explode(".", $matches[1]);
            if ($this->multiArrayKeyExist($pathKeys, $data, count($pathKeys) - 1)) {
                $foreachOutput = "";
                $counter = 0;
                foreach ($this->getValueFromDotNotation($pathKeys, $data) as $item) {
                    if (is_object($item)) {
                        $itemData = get_object_vars($item);
                    } else {
                        $itemData = $item;
                    }

                    $foreachData = [];
                    if ($itemData && is_array($itemData)) {
                        $foreachData["{$matches[2]}"]["_index"] = $counter++;
                        foreach ($itemData as $key => $value) {
                            $foreachData["{$matches[2]}"][$key] = is_array($value) ? $value : (string)$value;
                        }
                    }
                    $foreachOutput .= $this->render($matches[3], $foreachData);
                }
                $template = preg_replace(
                    '/{% foreach\(\w+ as \w+\) %}\n[\s\S]+{% endforeach %}/',
                    $foreachOutput,
                    $template
                );
            }
        }

        return $template;
    }

    public function parseSwitchBlocks($template, $data)
    {
        preg_match("/{% switch ([\w.]+) %}[\s\S]+{% endswitch %}/", $template, $matches);

        if (is_array($matches) && $matches) {
            $pathKeys = explode(".", $matches[1]);

            if ($this->multiArrayKeyExist($pathKeys, $data, count($pathKeys) - 1)) {
                $cases = [];
                preg_match_all("/(?:(?:{% case \"?'?(\w+)\"?'? %})+)/", $template, $cases);

                $caseKey = false;
                foreach ($cases[1] as $index => $currentCase) {
                    if ($currentCase == $this->getValueFromDotNotation($pathKeys, $data)) {
                        $caseKey = $index;
                        break;
                    }
                }

                if ($caseKey === false) {
                    return $template;
                }

                $tempTemplate = preg_replace(
                    ["/{% switch [\w.]+ %}/", "/{% endswitch %}/", "/\n/", "/\t/"],
                    "",
                    $matches[0]
                );
                $tempTemplate = preg_replace("/\s*{% case \"?'?\w+\"?'? %}/", "|", $tempTemplate);
                $tempTemplate = trim($tempTemplate, "|");

                $casesTemplates = explode("|", $tempTemplate);

                $switchOutput = $this->render($casesTemplates[$caseKey], $data);

                $template = preg_replace("/{% switch [\w.]+ %}[\s\S]+{% endswitch %}/", $switchOutput, $template);
            }
        }

        return $template;
    }

    private function multiArrayKeyExist($pathKeys, $array, $levelMax, $currentLevel = 0)
    {
        $currentKey = array_shift($pathKeys);
        if (array_key_exists($currentKey, $array)) {
            if ($currentLevel === $levelMax) {
                return true;
            } else {
                $currentLevel++;
                return $this->multiArrayKeyExist($pathKeys, $array[$currentKey], $levelMax, $currentLevel);
            }
        } else {
            return false;
        }
    }

    private function getValueFromDotNotation($path, $array)
    {
        $current = $array;
        foreach ($path as $key) {
            if (is_array($current)) {
                $current = $current[$key];
            } elseif (is_object($current)) {
                $current = $current->$key;
            }
        }
        return $current;
    }
}
