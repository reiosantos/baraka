<?php
/**
 * Created by PhpStorm.
 * User: reiosantos
 * Date: 1/4/18
 * Time: 10:55 PM
 */

require 'autoload.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$db= new Database();

if ($db == null){
    $error = 'Unable to connect to database. Please consult the system administrator.';
}

else{

    /*
     * Fetch:
     * Delete:
     * Add:
     * Feedback
     */

    /**
     * Fetch all feedback messages, make a GET request.
     * @Like: http://your_site_url/?feedback
     * @results are accessed through `$feedback_data` array
     */
    if (isset($_GET['feedback'])) {

        $feedback_data = $db->feedback->getAllFeedback();
    }

    /**
     * Delete a message, make a get request:
     * @Like: http://your_site_url/?delete_feedback&id=<feedback_id>
     * @returns `$error` or `$success`
     */
    else if (isset($_GET['delete_feedback'])) {

        $id = isset($_GET['id']) && trim($_GET['id']) !== "" ? trim($_GET['id']) : null;
        if ($id) {
            $result = $db->feedback->deleteFeedback($id);
            if (is_bool($result) && $result === true) {
                $success = "Massage has been successfully deleted";
            } else {
                $error = $result;
            }
        } else {
            $error = "Please set an id of the message to delete";
        }
    }

    /**
     * Add a new feedback message to the catalogue.
     * Make a POST request with the following attributes:
     * @form with name : add_feedback
     * @input field (type text) with name : name : required
     * @input field (type text) with name : email: optional
     * @input field (type text) with name : location: required
     * @textarea field with name : message: required
     * @returns $error` or `$success`
     */
    else if (isset($_POST['add_feedback'])) {

        $user_name = isset($_POST['name']) && trim($_POST['name']) !== "" ? cleanData(trim($_POST['name'])) : null;
        $user_email = isset($_POST['email']) && trim($_POST['email']) !== "" ? cleanData(trim($_POST['email'])) : null;
        $user_location = isset($_POST['location']) && trim($_POST['location']) !== "" ? cleanData(trim($_POST['location'])) : null;
        $user_message = isset($_POST['message']) && trim($_POST['message']) !== "" ? cleanData(trim($_POST['message'])) : null;

        if (!validate_email($user_email) && trim($user_name) === "") {
            $error = "Please put a correct email or fill in the username";
        } else {
            if (($user_email || $user_name) && $user_location && $user_message) {

                $feedback = [
                    "user_name" => $user_name,
                    "user_email" => $user_email,
                    "location" => $user_location,
                    "message" => $user_message,
                ];

                $result = $db->feedback->insertFeedback($feedback);

                if (is_bool($result) && $result === true) {
                    $success = "feedback has been received. Thank you.";
                }else {
                    $error = $result;
                }
            } else {
                $error = "All other fields are required (Email is optional).";
            }
        }
    }

    /*
     * Fetch:
     * Delete:
     * Add:
     * Artists
     */

    /**
     * Fetch all artists, make a GET request.
     * @Like: http://your_site_url/?artists
     * @results are accessed through `$artist_data` array
     */
    else if (isset($_GET['artists'])) {

        $artist_data = $db->artists->getArtists();
    }

    /**
     * Delete an artist, make a get request:
     * @Like: http://your_site_url/?delete_artist&id=<artist_id>
     * @returns `$error` or `$success`
     */
    else if (isset($_GET['delete_artist'])) {

        $id = isset($_GET['artist_id']) && trim($_GET['artist_id']) !== "" ? trim($_GET['artist_id']) : null;
        if ($id) {
            $result = $db->artists->deleteArtist($id);
            if (is_bool($result) && $result === true) {
                $success = "Artist has been successfully deleted";
            } else {
                $error = $result;
            }
        } else {
            $error = "Please set an id of the artist to delete.";
        }
    }

    /**
     * Add a new artist to the catalogue.
     * Make a POST request with the following attributes:
     * @form with name : add_artist : enctype="multipart/form-data"
     * @input field (type text) with name : artist_name : required
     * @textarea field with name : artist_details : required
     * @input field (type file) with name : artist_photo : optional
     * @returns $error` or `$success`
     */
    else if (isset($_POST['add_artist'])) {

        $artist_name = isset($_POST['artist_name']) && trim($_POST['artist_name']) !== "" ? cleanData(trim($_POST['artist_name'])) : null;
        $artist_details = isset($_POST['artist_details']) && trim($_POST['artist_details']) !== "" ? cleanData(trim($_POST['artist_details'])) : null;
        $artist_photo_file = isset($_FILES['artist_photo']) ? $_FILES['artist_photo'] : null;

        if ($artist_name && $artist_details) {

            $photo_name = "";
            $ret = true;

            if ($artist_photo_file) {
                $photo_name = "P" . time() . "_" . $artist_photo_file["name"];

                $ret = move_uploaded_file($artist_photo_file["tmp_name"], "uploads/artist_photos/" . $photo_name);
            }
            if ($ret) {
                $artist = [
                    "artist_name" => $artist_name,
                    "artist_details" => $artist_details,
                    "artist_photo_name" => $photo_name,
                ];

                $result = $db->artists->updateInsertArtist($artist);

                if (is_bool($result) && $result === true) {
                    $success = "New Artist has been added.";
                }else {
                    $error = $result;
                }
            } else {
                $error = "Unable to upload the file.";
            }
        } else {
            $error = "All other fields are required (Artist photo is optional).";
        }
    }

    /*
     * Fetch:
     * Delete:
     * Add:
     * Download:
     * Songs
     */

    /**
     * Fetch all songs, make a GET request.
     * @Like: http://your_site_url/?songs
     * @results are accessed through `$songs_data` array
     */
    else if (isset($_GET['songs'])) {

        $songs_data = $db->songs->getSongs();
    }

    /**
     * Delete a song, make a get request:
     * @Like: http://your_site_url/?delete_song&id=<song_id>
     * @returns `$error` or `$success`
     */
    else if (isset($_GET['delete_song'])) {

        $id = isset($_GET['id']) && trim($_GET['id']) !== "" ? trim($_GET['id']) : null;
        if ($id) {
            $result = $db->songs->deleteSong($id);
            if (is_bool($result) && $result === true) {
                $success = "Song has been successfully deleted";
            } else {
                $error = $result;
            }
        } else {
            $error = "Please set an id of the song to delete.";
        }
    }

    /**
     * Add a new song to the catalogue.
     * Make a POST request with the following attributes:
     * @form with name : add_song : enctype="multipart/form-data"
     * @input field (type text) with name : song_name : required
     * @input field (type file) with name : song_file : required
     * @input field (type file) with name : album_art : optional
     * @select field (drop down of all artists) with name : artist_id : required
     * @returns $error` or `$success`
     */
    else if (isset($_POST['add_song'])) {

        $song_name = isset($_POST['song_name']) && trim($_POST['song_name']) !== "" ? cleanData(trim($_POST['song_name'])) : null;
        $artist_id = isset($_POST['artist_id']) && trim($_POST['artist_id']) !== "" ? cleanData(trim($_POST['artist_id'])) : null;
        $song_file = isset($_FILES['song_file']) ? $_FILES['song_file'] : null;
        $album_art_file = isset($_FILES['album_art']) ? $_FILES['album_art'] : null;

        if ($song_name && $artist_id && $song_file) {

            $photo_name = "";
            $ret = true;

            if ($album_art_file) {
                $photo_name = "A" . time() . "_" . $album_art_file["name"];

                $ret = move_uploaded_file($album_art_file["tmp_name"], "uploads/album_art/" . $photo_name);
            }
            if ($ret) {

                $song_file_name = "S" . time() . "_" . $song_file["name"];

                $ret_1 = move_uploaded_file($song_file["tmp_name"], "uploads/song_uploads/" . $song_file_name);

                if ($ret_1) {

                    $song = [
                        "song_name" => $song_name,
                        "artist_id" => $artist_id,
                        "song_file_name" => $song_file_name,
                        "song_album_art_name" => $photo_name,
                    ];

                    $result = $db->songs->insertSong($song);

                    if (is_bool($result) && $result === true) {
                        $success = "New song has been added.";
                    }else {
                        $error = $result;
                    }
                }else{
                    $error = "Unable to upload the Song.";
                }
            } else {
                $error = "Unable to upload the Album art. Failed to process the request.";
            }
        } else {
            $error = "All other fields are required (Album art photo is optional).";
        }
    }

    /**
     * Download a song. make a GET request:
     * @Like: http://your_site_url/?download_song&id=<song_id>
     * @returns `$error` or `$success`
     */
    else if (isset($_GET['download_song'])) {

        $id = isset($_POST['id']) && trim($_POST['id']) !== "" ? cleanData(trim($_POST['sid'])) : null;

        if ($id) {

            $result = $db->songs->downloadOneSong($id);

            if (is_bool($result) && $result === true) {
                $success = "Thank you for downloading.";
            }else {
                $error = $result;
            }
        }else {
            $error = "unknown song requested";
        }
    }
}

/**
 * @param $data
 * @return string
 */
function cleanData($data){
    return htmlentities(htmlspecialchars($data), ENT_QUOTES);
}
