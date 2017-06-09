<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m170608_101420_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
//            id primaryKey
            'name'=>$this->string(50)->notNull()->comment('名称'),
//            name varchar﴾50﴿ 名称
            'intro'=>$this->text()->comment('简介'),
//            intro text 简介
            'sort'=>$this->integer(11)->notNull()->comment('排序'),
//            sort int﴾11﴿ 排序
            'status'=>$this->smallInteger(2)->notNull()->comment('状态'),
//            status int﴾2﴿ 状态﴾‐1删除 0隐藏 1正常﴿
            'is_help'=>$this->smallInteger(1)->notNull()->comment('类型'),
//            is_help int﴾1﴿ 类型
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
