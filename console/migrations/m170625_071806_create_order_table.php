<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m170625_071806_create_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
//            member_id	int	�û�id
        'member_id'=>$this->integer()->notNull()->comment('�û�id'),
//            name	varchar(50)	�ջ���
        'name'=>$this->string(50)->notNull()->comment('�ջ���'),
//            province	varchar(20)	ʡ
        'province'=>$this->string(20)->notNull()->comment('ʡ'),
//            city	varchar(20)	��
        'city'=>$this->string(20)->notNull()->comment('��'),
//            area	varchar(20)	��
        'area'=>$this->string(20)->notNull()->comment('��'),
//            address	varchar(255)	��ϸ��ַ
        'address'=>$this->string(255)->notNull()->comment('��ϸ��ַ'),
//            tel	char(11)	�绰����
        'tel'=>$this->char(11)->notNull()->comment('�绰����'),
//            delivery_id	int	���ͷ�ʽid
        'delivery_id'=>$this->integer()->notNull()->comment('���ͷ�ʽid'),
//            delivery_name	varchar	���ͷ�ʽ����
        'delivery_name'=>$this->string()->comment('���ͷ�ʽ����'),
//            delivery_price	float	���ͷ�ʽ�۸�
        'delivery_price'=>$this->float()->comment('���ͷ�ʽ�۸�'),
//            payment_id	int	֧����ʽid
        'payment_id'=>$this->integer()->notNull()->comment('֧����ʽid'),
//            payment_name	varchar	֧����ʽ����
        'payment_name'=>$this->string()->comment('֧����ʽ����'),
//            total	decimal	�������
        'total'=>$this->decimal(10,2)->comment('�������'),
//            status	int	����״̬��0��ȡ��1������2������3���ջ�4��ɣ�
        'status'=>$this->integer()->comment('����״̬'),
//            trade_no	varchar	������֧�����׺�
        'trade_no'=>$this->string()->comment('������֧�����׺�'),
//            create_time	int	����ʱ��
        'create_time'=>$this->integer()->comment('����ʱ��')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order');
    }
}
