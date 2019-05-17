<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AdviceSearch represents the model behind the search form of `livan\distribution\models\ars\Advice`.
 */
class OperationLogSearch extends OperationLog
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['admin_name', 'ip', 'admin_agent', 'path', 'method'], 'string'],
            [['params'], 'safe'],
            ['created_at', 'integer']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = OperationLog::find()->orderBy('created_at DESC');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'admin_id' => $this->admin_id,
        ]);

        $query->andFilterWhere(['like', 'admin_name', $this->admin_name])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'method', $this->method])
            ->andFilterWhere(['like', 'path', $this->path]);

        return $dataProvider;
    }
}
