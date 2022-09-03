<?php
namespace App\Models\Notifications;

use \Tricki\Notification\Models\Notification as TrickiNotification; 
use User;

class PhotoLikedNotification extends \Tricki\Notification\Models\Notification
{
    public static $type = 'photo_liked';

    public function render() {
        return array($this->getTypes(),
                     $this->getSender(),
                     $this->getPhotoID(),
                     $this->getDate(),
                     $this->getTime(),
                     $this->getSenderID(),
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

    public function getPhotoID() {
        return $this->object_id;
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
