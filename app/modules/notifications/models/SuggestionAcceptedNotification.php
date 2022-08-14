<?php
namespace modules\notifications\models;

use \Tricki\Notification\Models\Notification as TrickiNotification;
use User;
use Photo;

class SuggestionAcceptedNotification extends \Tricki\Notification\Models\Notification
{
	public static $type = 'suggestionAccepted';

    public function render() {
        return array($this->getTypes(),
                     $this->getSender(),
                     $this->getDate(),
                     $this->getTime(),
                     $this->getSenderID(),
                     $this->getData(),
                     $this->getPhoto());
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

    public function getPhoto() {
        return Photo::find($this->object_id);
    }
}
