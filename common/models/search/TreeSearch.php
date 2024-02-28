<?php

namespace common\models\search;

use common\models\Servers;
use common\models\ServerStatuses;
use common\exceptions\ValidationErrorException;
use common\models\Tree;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TreeSearch represents the model behind the search form of `common\models\Tree`.
 */
class TreeSearch extends Model
{

    public $id;
    public $created_at;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['created_at'], 'string'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     */
    public function search(array $params = [])
    {

        if (!empty($params) && (!$this->load($params) || !$this->validate())) {
            throw ValidationErrorException::create($this->errors);
        }

        $query =  Tree::find();

        return new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => false,
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_DESC,
                    ],
                    'attributes' => [
                        'id'
                    ],
                ],
            ]
        );
    }

    public function formName()
    {
        return '';
    }
}
