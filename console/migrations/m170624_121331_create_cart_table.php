<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart`.
 */
class m170624_121331_create_cart_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('cart', [
            'member_id' => $this->integer()->notNull()->comment('用户id'),
            'goods_id' => $this->integer()->notNull()->comment('商品id'),
            'amount' => $this->integer()->notNull()->comment('商品数量')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('cart');
    }
}
