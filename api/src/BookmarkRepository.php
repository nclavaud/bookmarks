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

    public function save(Bookmark $bookmark)
    {
        $statement = $this->connection->prepare(
            'INSERT INTO bookmarks (uuid, url, data) VALUES (:uuid, :url, :data);'
        );

        $statement->execute(
            array(
                ':uuid' => (string) $bookmark->getUuid(),
                ':url' => (string) $bookmark->getUrl(),
                ':data' => json_encode($bookmark->getData()),
            )
        );

        return $bookmark;
    }
}
