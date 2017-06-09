<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m170608_072729_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
//            id primaryKey
            'name'=>$this->string(50)->notNull()->comment('名称'),
//name varchar﴾50﴿ 名称
            'intro'=>$this->text()->comment('简介'),
//intro text 简介
            'logo'=>$this->string(255)->comment('logo'),
//logo varchar﴾255﴿ LOGO图片
            'sort'=>$this->integer()->notNull()->comment('排序'),
//sort int﴾11﴿ 排序
            //'status'=>$this->smallInteger(2)->notNull()->comment('状态'),
//status int﴾2﴿ 状态﴾‐1删除 0隐藏 1正常﴿
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
