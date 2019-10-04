<?php

class AddRoles extends Migration
{
    const rolename = 'Unterrichtsplanung_Admin';

    public function description()
    {
        return 'add admin roles for plugin Unterrichtsplanung';
    }

    public function up()
    {
        $role = new Role();

        $role->setRolename(self::rolename);
        $role->setSystemtype(false);

        RolePersistence::saveRole($role);
    }

    public function down()
    {
        $db = DBManager::get();

        $stmt = $db->prepare('SELECT * FROM roles WHERE rolename = ?');
        $stmt->execute([self::rolename]);

        while ($data = $stmt->fetch()) {
            $role = new Role($data['roleid'], $data['rolename']);

            RolePersistence::deleteRole($role);
        }
    }
}
