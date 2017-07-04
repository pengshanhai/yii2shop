<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "cart".
 *
 * @property integer $member_id
 * @property integer $goods_id
 * @property integer $amount
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cart';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'goods_id', 'amount'], 'required'],
            [['member_id', 'goods_id', 'amount'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'member_id' => '用户id',
            'goods_id' => '商品id',
            'amount' => '商品数量',
        ];
    }
}
