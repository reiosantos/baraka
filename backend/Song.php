<?php
/**
 * Created by PhpStorm.
 * User: ronaldsekitto
 * Date: 24/08/2018
 * Time: 21:18
 */

class Song
{
    private $handle;

    /**
     * songs constructor.
     */
    public function __construct()
    {
        $this->handle = Connection::getConnection();
        if($this->handle == null){
            return null;
        }
        $this->handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @return array|string
     */
    public function getSongs(){
        $songs_list = array();
        try{
            $this->handle->beginTransaction();

            $songs = $this->handle->prepare("SELECT * FROM songs LEFT JOIN artists ON artist_id_fk=artist_id_fk ORDER BY song_name ASC, artist_name ASC ");

            if($songs->execute([])){

                if($songs->rowCount() < 1){ return "No Songs available"; }

                foreach ($songs->fetchAll() as $row) {
                    $temp = [
                        'song_id' => $row['song_id'],
                        'song_name' => $row['song_name'],
                        'upload_date' => $row['upload_date'],
                        'song_file_name' => $row['song_file_name'],
                        'song_album_art_name' => $row['song_album_art_name'],

                        'song_album_art_file' => $this->downloadFile($row['file_name'], "album_art"),
                        'artist_name' => $row['artist_name'],
                        'artist_photo_name' => $row['artist_photo_name'],
                        'artist_photo_file' => $this->downloadFile($row['artist_photo_name'], "artist_photos"),
                    ];
                    array_push($songs_list, $temp);
                }
            }
            $this->handle->commit();

            return $songs_list;

        }catch (PDOException $e){
            $this->handle->rollBack();
            return $this->handle->errorInfo()[2];
        }
    }

    /**
     * @param $song
     * @return bool|string
     */
    public function insertSong($song){
        try{
            $this->handle->beginTransaction();

            $insert = $this->handle->prepare("INSERT INTO songs(song_name, artist_id_fk, song_file_name, song_album_art_name)
 VALUES(:song_name, :artist, :file_name, :album_art_name)");
            if(!$insert->execute(
                [
                    'song_name' => $song['song_name'],
                    'artist' => $song['artist_id'],
                    'file_name' => $song['song_file_name'],
                    'album_art_name' => $song['song_album_art_name']
                ]
            )){
                return $insert->errorInfo()[2];
            }
            $this->handle->commit();

        }catch (PDOException $e){
            $this->handle->rollBack();
            return $this->handle->errorInfo()[2];
        }
        return true;
    }

    /**
     * @param $songId
     * @return bool
     */
    public function downloadOneSong($songId){
        try{
            $this->handle->beginTransaction();

            $songs = $this->handle->prepare("SELECT * FROM songs where song_id=:id");

            if(!$songs->execute([":id"=>$songId])) {
                return $songs->errorInfo()[2];
            }

            if ($songs->rowCount() !== 1) {
                return "Song does not exist";
            }

            $song = $songs->fetch(PDO::FETCH_BOTH, PDO::FETCH_ORI_FIRST);
            $song_name = $song['song_name'];

            $result = "uploads/song_uploads/".$song_name;

            if (file_exists($result)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/force-download');
                header('Content-Disposition: attachment; filename=' . basename($result));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . filesize($result));
                ob_clean();
                flush();
                readfile($result);
                @unlink($result);

            }else {
                return "Requested Song does not exists";
            }

            $this->handle->commit();

        }catch (PDOException $e){
            $this->handle->rollBack();
            return $this->handle->errorInfo()[2];
        }
        return true;
    }

    /**
     * @param $songId
     * @return bool|string
     */
    public function deleteSong($songId){
        try{
            $this->handle->beginTransaction();

            $result = $this->handle->prepare("DELETE FROM songs WHERE song_id=:id" );

            if(!$result->execute([ ':id'=>$songId ])){
                return $result->errorInfo()[2];
            }
            if ($result->rowCount() !== 1) {
                return "Could not delete the song. It was not found";
            }
            $this->handle->commit();

        }catch (PDOException $e){
            $this->handle->rollBack();
            return $this->handle->errorInfo()[2];
        }
        return true;
    }

    /**
     * @param $file_name
     * @param $location
     * @return string
     */
    function downloadFile($file_name, $location){
        $path = "uploads/" . $location. "/" . $file_name;
        if (file_exists($path)){
            $data = file_get_contents($path);
            return base64_encode($data);
        }
        return false;
    }

}