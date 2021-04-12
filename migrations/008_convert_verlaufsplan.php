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
                    'col0' => 'Zeit',
                    'col1' => 'Phase',
                    'col2' => 'Handlungsschritte',
                    'col3' => 'Methodik',
                    'col4' => 'Medien',
                    'col5' => '',
                    'col6' => ''
                ];

                foreach ($content as $row_num => $row) {
                    $new_content['rows'][$row_num] = [
                        'col0' => $row['time'],
                        'col1' => $row['phase'],
                        'col2' => $row['step'],
                        'col3' => $row['method'],
                        'col4' => $row['media'],
                        'col5' => '',
                        'col6' => ''
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
