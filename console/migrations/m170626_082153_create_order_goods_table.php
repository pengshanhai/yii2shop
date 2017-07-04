<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_goods`.
 */
class m170626_082153_create_order_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order_goods', [
            'id' => $this->primaryKey(),
//            id	primaryKey
        'order_id'=>$this->integer()->notNull()->comment('����id'),
//            order_id	int	����id
        'goods_id'=>$this->integer()->notNull()->comment('��Ʒid'),
//            goods_id	int	��Ʒid
        'goods_name'=>$this->string(255)->notNull()->comment('��Ʒ����'),
//            goods_name	varchar(255)	��Ʒ����
        'logo'=>$this->string(255)->comment('ͼƬ'),
//            logo	varchar(255)	ͼƬ
        'price'=>$this->decimal(10,2)->comment('�۸�'),
//            price	decimal	�۸�
        'amount'=>$this->integer()->comment('����'),
//            amount	int	����
        'total'=>$this->decimal(10,2)->comment('С��'),
//            total	decimal	С��
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order_goods');
    }
}
