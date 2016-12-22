<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Post;

/**
 * PostSearch represents the model behind the search form about `app\models\Post`.
 */
class PostSearch extends Post
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'votos'], 'integer'],
            [['titulo', 'ruta', 'extension', 'usuario_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Post::find()->joinWith('usuario');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // $dataProvider->sort->attributes['usuario_id'] = [
        //     'asc' => ['usuarios.nick' => SORT_ASC],
        //     'desc' => ['usuarios.nick' => SORT_DESC],
        // ];

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'votos' => $this->votos,
            'usuario_nick' => $this->usuario_id,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'ruta', $this->ruta])
            ->andFilterWhere(['like', 'extension', $this->extension])
            ->andFilterWhere(['ilike', 'usuarios.nick', $this->usuario_id]);

        return $dataProvider;
    }
}
