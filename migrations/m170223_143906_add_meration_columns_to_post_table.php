<?php

use yii\db\Migration;

/**
 * Handles adding meration to table `post`.
 */
class m170223_143906_add_meration_columns_to_post_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('posts', 'status', $this->smallInteger());
        $this->addColumn('posts', 'moderated_by', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('posts', 'status');
        $this->dropColumn('posts', 'moderated_by');
    }
}
