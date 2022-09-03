<?php
namespace App\Models\Notifications;

use \Tricki\Notification\Models\Notification as TrickiNotification;
use \App\Models\Collaborative\Comment;
use User;
use Photo;

class CommentLikedNotification extends \Tricki\Notification\Models\Notification
{
    public static $type = 'comment_liked';

    public function render() {
        return array($this->getTypes(),
                     $this->getSender(),
                     $this->getPhotoID(),
                     $this->getDate(),
                     $this->getTime(),
                     $this->getSenderID(),
                     $this->getPhotoOwnerID(),
                     $this->getPhotoOwnerName(),
                     $this->getCommentID(),
                     $this->getData()
                     );
    }

    public function getDate() {
        $created_at = $this->created_at;
        list($date, $time) = explode(" ", $created_at);
        list($year, $month, $day) = explode("-", $date);
        return $day . "/" . $month . "/" . $year;
    }

    public function getTime() {
        $created_at = $this->created_at;
        list($date, $time) = explode(" ", $created_at);
        list($hour, $minutes, $seconds) = explode(":", $time);
        return $hour . ":" . $minutes;
    }

    public function getTypes() {
        return $this->type;
    }

    public function getCommentID() {
        return $this->object_id;
    }

    public function getPhotoID() {
        $comment = Comment::find($this->object_id);
        return $comment->photo_id;
    }

    public function getPhotoOwnerID() {
        $photo = Photo::find($this->getPhotoID());
        return $photo->user_id;
    }

    public function getPhotoOwnerName() {
        return User::find($this->getPhotoOwnerID())->name;
    }

    public function getObjectType() {
        return $this->object_type;
    }

    public function getSenderID() {
        return $this->sender_id;
    }

    public function getSender() {
        return User::find($this->sender_id)->name;
    }

    public function getData() {
        return $this->data;
    }
}
