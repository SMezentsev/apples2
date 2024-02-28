<?php

namespace common\components\helper;

use yii\helpers\BaseArrayHelper;

class ArrayHelper extends BaseArrayHelper
{
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
