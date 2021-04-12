<?php

class ConvertInfotexts extends Migration
{
    public function description()
    {
        return 'Convert templates_id of infotexts from 0 to 1';
    }

    public function up()
    {
        $db = DBManager::get();

        $db->exec('
            UPDATE
                `du_infotexts`
            SET 
                `templates_id` = "1"
            WHERE
                `du_infotexts`. `id` = 0
          ');
  
        SimpleORMap::expireTableScheme();


    }

    public function down()
    {
    }
}
