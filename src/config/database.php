<?php
class database {
    public function getConnection() {
        $dbdriver = getenv('DB_DRIVER');
        $dbhost   = getenv('DB_HOST');
        $dbname   = getenv('DB_NAME');
        $dbuser   = getenv('DB_USER');
        $dbpass   = getenv('DB_PASSWORD');

        $connection = new PDO("$dbdriver:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $connection;
    }
}
?>
