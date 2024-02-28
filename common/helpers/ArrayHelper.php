<?php

namespace common\Helper;

class ArrayHelper
{

    function balance(string $a, string $b, array $replacements = [
        '?' => 3,
        '!' => 2
    ]): string
    {

        $solve = [$a, $b];
        array_walk($solve, function (&$item) use ($replacements) {
            foreach ($replacements as $key => $replacement) {
                $item = str_replace($key, $replacement, $item);
            }
            $item = array_sum(str_split($item));
        });

        if (($diff = $solve[0] - $solve[1]) === 0) {
            return 'Balance';
        }
        return $diff > 0 ? 'Left' : 'Right';
    }

    /**
     * Выравнивает значения массива в один уровень
     *
     * Пример использования:
     * $array = ['name' => 'Joe', 'languages' => ['PHP', 'Ruby']];
     * $flattened = ArrayHelper::flatten($array);
     * $flattened = ['Joe', 'PHP', 'Ruby']
     *
     * @param array $collection
     *                          Исходный массив
     * @param int   $depth
     *                          Глубина объединения, по умолчанию INF
     */
    public static function flatten(array $collection, $depth = INF): array
    {
        $flattened = [];

        foreach ($collection as $item) {
            if (!is_array($item)) {
                $flattened[] = $item;
            } else {
                $values = 1 === $depth
                    ? array_values($item)
                    : self::flatten($item, $depth - 1);

                foreach ($values as $value) {
                    $flattened[] = $value;
                }
            }
        }

        return $flattened;
    }
}
