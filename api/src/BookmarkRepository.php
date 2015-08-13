<?php

namespace App;

class BookmarkRepository
{
    /**
     * @var \PDO
     */
    private $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function findAll()
    {
        $bookmarks = array();

        foreach ($this->connection->query('SELECT * FROM bookmarks;') as $row) {
            $bookmark = json_decode($row['data']);
            $bookmark->uuid = $row['uuid'];
            $bookmark->url = $row['url'];
            $bookmark->title = !empty($bookmark->title) ?: $bookmark->url;
            $bookmarks[] = $bookmark;
        }

        return $bookmarks;
    }
}
