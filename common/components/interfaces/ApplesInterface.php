<?php

namespace common\components\interfaces;

use common\models\Apple;
use common\models\ApplePosition;

/**
 *
 */
interface ApplesInterface
{

    /**
     * @param integer $id
     * @return Apple
     */
    public function get(integer $id): Apple;

    /**
     * @return Apple
     */
    public function add(): Apple;

    /**
     * @param integer $id
     * @return Apple
     */
    public function fell(integer $id): Apple;

    /**
     * @return Apple
     */
    public function eat(integer $id): Apple;

    /**
     * @param integer $id
     */
    public function delete(integer $id);
}