<?php
/**
 * Title: Artists Model
 * Purpose: To provide database access for getting, adding, updating, and
 *          deleting artists
 */

namespace artists;

/**
 * Validate an artist
 */
function validateArtist(array $artist)
{
    $errors = array();

    // Ensure a name was submitted
    if (!is_string($artist['name']) || $artist['name'] == '')
    {
        $errors[] = 'Name is required.';
    }
    else
    {
        // Ensure the name is unique
        global $db;
        $query = 'SELECT COUNT(*) count
                  FROM artists
                  WHERE name = :name
                      AND id != :id';
        $statement = $db->prepare($query);
        $statement->bindValue(':name', $artist['name']);
        $statement->bindValue(':id', $artist['id']);
        $statement->execute();
        $count = $statement->fetch()['count'];
        $statement->closeCursor();

        if ($count > 0)
        {
            $errors[] = 'An artist with that name already exists.';
        }
    }

    // Ensure monthly listeners is valid
    if (!is_integer($artist['monthlyListeners']) ||
        $artist['monthlyListeners'] < 0)
    {
        $errors[] = 'Monthly listeners must be a positive number.';
    }

    // Ensure an image path was submitted
    if (!is_string($artist['imagePath']) || $artist['imagePath'] == '')
    {
        $errors[] = 'Image path is required.';
    }

    return $errors;
}

/**
 * Validate a list of artist ids
 */
function validateArtistIds(array $artistIds)
{
    if (count($artistIds) == 0) return array();

    global $db;

    // Ensure the artist ids are valid integers
    foreach ($artistIds as $artistId)
    {
        if (!is_integer($artistId) || $artistId < 1)
        {
            return array('Invalid artist ids.');
        }
    }

    // Ensure the artist ids exist
    $idList = implode(',', $artistIds);

    $query = "SELECT COUNT(*) count
              FROM artists
              WHERE id IN ($idList)";

    $statement = $db->prepare($query);
    $statement->execute();
    $count = $statement->fetch()['count'];
    $statement->closeCursor();

    $errors = array();
    if ($count != count($artistIds))
    {
        $errors[] = 'Invalid artist ids.';
    }
    return $errors;
}

/**
 * Get all artists
 */
function getAllArtists()
{
    global $db;
    $query = 'SELECT id, name, monthlyListeners, imagePath
              FROM artists
              ORDER BY LOWER(name)';
    $statement = $db->prepare($query);
    $statement->execute();
    $artists = $statement->fetchAll();
    $statement->closeCursor();
    return $artists;
}

/**
 * Get an artist by id
 */
function getArtist(int $artistId)
{
    global $db;
    $query = 'SELECT id, name, monthlyListeners, imagePath
              FROM artists
              WHERE id = :artistId';
    $statement = $db->prepare($query);
    $statement->bindValue(':artistId', $artistId);
    $statement->execute();
    $artist = $statement->fetch();
    $statement->closeCursor();
    return $artist;
}

/**
 * Add an artist to the database
 */
function addArtist(array $artist)
{
    global $db;
    $query = 'INSERT INTO artists (name, monthlyListeners, imagePath)
              VALUES (:name, :monthlyListeners, :imagePath)';
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $artist['name']);
    $statement->bindValue(':monthlyListeners', $artist['monthlyListeners']);
    $statement->bindValue(':imagePath', $artist['imagePath']);
    $statement->execute();
    $artistId = $db->lastInsertId();
    $statement->closeCursor();
    return $artistId;
}

/**
 * Update an artist in the database
 */
function updateArtist(array $artist)
{
    global $db;
    $query = 'UPDATE artists
              SET name = :name, monthlyListeners = :monthlyListeners,
                  imagePath = :imagePath
              WHERE id = :artistId';
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $artist['name']);
    $statement->bindValue(':monthlyListeners', $artist['monthlyListeners']);
    $statement->bindValue(':imagePath', $artist['imagePath']);
    $statement->bindValue(':artistId', $artist['id']);
    $statement->execute();
    $statement->closeCursor();
}

/**
 * Delete an artist from the database
 */
function deleteArtist(array $artist)
{
    global $db;
    $query = 'DELETE FROM artists
              WHERE id = :artistId';
    $statement = $db->prepare($query);
    $statement->bindValue(':artistId', $artist['id']);
    $statement->execute();
    $statement->closeCursor();
}
?>
