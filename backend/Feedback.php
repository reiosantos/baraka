<?php
/**
 * Created by PhpStorm.
 * User: ronaldsekitto
 * Date: 11/08/2018
 * Time: 21:26
 */


class Feedback
{
    private $handle;

    /**
     * Feedback constructor.
     */
    public function __construct()
    {
        $this->handle = Connection::getConnection();
    }

    /**
     * @return array|bool
     */
    public function getAllFeedback(){
        $feedback_list = array();
        try{
            $this->handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->handle->beginTransaction();

            $feedback = $this->handle->prepare("SELECT *  FROM feedback ORDER BY feedback_date DESC");
            if($feedback->execute([])){

                if($feedback->rowCount() < 1){ return "No data available"; }

                foreach ($feedback->fetchAll() as $row) {
                    $temp = [
                        'feedback_id' => $row['feedback_id'],
                        'user_name' => $row['user_name'],
                        'user_email' => $row['user_email'],
                        'user_location' => $row['user_location'],
                        'feedback_date' => $row['feedback_date'],
                        'message' => $row['message'],
                    ];
                    array_push($feedback_list, $temp);
                }
                return $feedback_list;
            }
            $this->handle->commit();

        }catch (PDOException $e){
            $this->handle->rollBack();
            return $this->handle->errorInfo()[2];
        }
        return $this->handle->errorInfo()[2];
    }

    /**
     * @param $feedback
     * @return bool|string
     */
    public function insertFeedback($feedback){
        try{
            $this->handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->handle->beginTransaction();

            $insert = $this->handle->prepare("INSERT INTO feedback(user_name, user_email, user_location, message) 
VALUES(:user_name, :email, :location, :message)");
            if(!$insert->execute(
                [
                    ':user_name'=>$feedback['user_name'],
                    ':email'=>$feedback['user_email'],
                    ':location'=>$feedback['user_location'],
                    ':message'=>$feedback['message']
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
     * @param $feedbackId
     * @return bool|string
     */
    public function deleteFeedback($feedbackId){
        try{
            $this->handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->handle->beginTransaction();

            $result = $this->handle->prepare("DELETE FROM feedback WHERE feedback_id=:id" );

            if(!$result->execute([ ':id'=>$feedbackId ])){
                return $result->errorInfo()[2];
            }
            if ($result->rowCount() !== 1) {
                return "Could not delete the message. It was not found";
            }
            $this->handle->commit();

        }catch (PDOException $e){
            $this->handle->rollBack();
            return $this->handle->errorInfo()[2];
        }
        return true;
    }

}
