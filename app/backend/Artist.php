<?php
/**
 * Created by PhpStorm.
 * Artist: ronaldsekitto
 * Date: 24/08/2018
 * Time: 20:36
 */


class Artist
{
    private $handle;

    /**
     * Artists constructor.
     */
    public function __construct()
    {
        $this->handle = Connection::getConnection();
    }

    /**
     * @return array|bool
     */
    public function getArtists(){
        $artists_list = array();
        try{
            $this->handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->handle->beginTransaction();

            $artists = $this->handle->prepare("SELECT *  FROM artists ORDER BY artist_name ASC");
            if($artists->execute([])){

                if($artists->rowCount() < 1){ return "No Artist data available"; }

                foreach ($artists->fetchAll() as $row) {
                    $temp = [
                        'artist_id' => $row['artist_id'],
                        'artist_name' => $row['artist_name'],
                        'artist_details' => $row['artist_details'],
                        'artist_photo_name' => $row['artist_photo_name'],
                        'artist_photo' => $this->downloadFile($row['artist_photo_name']),
                    ];
                    array_push($artists_list, $temp);
                }
                return $artists_list;
            }
            $this->handle->commit();

        }catch (PDOException $e){
            $this->handle->rollBack();
            return $this->handle->errorInfo()[2];
        }
        return $this->handle->errorInfo()[2];
    }

    /**
     * @param $artist
     * @return bool|string
     */
    public function updateInsertArtist($artist){
        try{
            $this->handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->handle->beginTransaction();

            if (isset($artist['id']) && $artist['id'] != null){
                $update = $this->handle->prepare("UPDATE artists SET artist_name=:artist_name, artist_details=:artist_details, artist_photo_name=:photo WHERE artist_id=:id");
                if(!$update->execute(
                    [
                        ':artist_name'=>$artist['artist_name'],
                        ':artist_details'=>$artist['artist_details'],
                        ':photo'=>$artist['artist_photo_name'],
                        ':id'=>$artist['artist_id']
                    ]
                )){
                    return $update->errorInfo()[2];
                }
            }else{
                $insert = $this->handle->prepare("INSERT INTO artists(artist_name, artist_details, artist_photo_name) VALUES(:artist_name, :artist_details, :photo)");
                if(!$insert->execute(
                    [
                        ':artist_name'=>$artist['artist_name'],
                        ':artist_details'=>$artist['artist_details'],
                        ':photo'=>$artist['artist_photo_name']
                    ]
                )){
                    return $insert->errorInfo()[2];
                }
            }
            $this->handle->commit();

        }catch (PDOException $e){
            $this->handle->rollBack();
            return $this->handle->errorInfo()[2];
        }
        return true;
    }

    /**
     * @param $artistId
     * @return bool|string
     */
    public function deleteArtist($artistId){
        try{
            $this->handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->handle->beginTransaction();

            $result = $this->handle->prepare("DELETE FROM artists WHERE artist_id=:id" );

            if(!$result->execute([ ':id'=>$artistId ])){
                return $this->handle->errorInfo()[2];
            }

            if ($result->rowCount() !== 1) {
                return "Could not delete the Artist. Record was not found";
            }            $this->handle->commit();

        }catch (PDOException $e){
            $this->handle->rollBack();
            return $this->handle->errorInfo()[2];
        }
        return true;
    }

    /**
     * @param $file_name
     * @return string
     */
    function downloadFile($file_name){
        $path = "uploads/artist_photos/" . $file_name;
        if (file_exists($path)){
            $data = file_get_contents($path);
            return base64_encode($data);
        }
        return false;
    }
}
