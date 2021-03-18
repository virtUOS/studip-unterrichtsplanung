<?php

class ConvertVerlaufsplan extends Migration
{
    public function description()
    {
        return 'Convert contents of verlaufsplan to new structure';
    }

    public function up()
    {
        $db = DBManager::get();

        $result = $db->query("SELECT * FROM du_schedules");
        $stmt = $db->prepare("UPDATE du_schedules
            SET content = ?
            WHERE id = ?");

        while($data = $result->fetch()) {
            $content = json_decode($data['content'], true);

            if (!empty($content)) {
                $new_content['titles'] = [
                    'col1' => 'Zeit',
                    'col2' => 'Phase',
                    'col3' => 'Handlungsschritte',
                    'col4' => 'Methodik',
                    'col5' => 'Medien',
                    'col6' => '',
                    'col7' => ''
                ];

                foreach ($content as $row_num => $row) {
                    $new_content[$row_num] = [
                        'col1' => $row['time'],
                        'col2' => $row['phase'],
                        'col3' => $row['step'],
                        'col4' => $row['method'],
                        'col5' => $row['media'],
                        'col6' => '',
                        'col7' => ''
                    ];
                }

                $stmt->execute([json_encode($new_content), $data['id']]);
            }
        }
    }

    public function down()
    {
    }
}
