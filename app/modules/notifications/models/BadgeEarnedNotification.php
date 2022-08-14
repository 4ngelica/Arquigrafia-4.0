<?php
namespace modules\notifications\models;

use \Tricki\Notification\Models\Notification as TrickiNotification;
use User;
use modules\gamification\models\Badge;

class BadgeEarnedNotification extends \Tricki\Notification\Models\Notification
{
    public static $type = 'badge_earned';

    private $badge;

    public function render() {
        return array($this->getTypes(),
                     $this->getSender(),
                     $this->getBadgeName(),
                     $this->getBadgeID(),
                     $this->getImage()
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

    public function getBadgeID() {
        return $this->object_id;
    }

    public function getBadgeName() {
        return $this->getBadge()->name;
    }

    public function getImage() {
        return $this->getBadge()->image;
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

    protected function getBadge() {
        if ( ! $this->badge ) {
            $this->badge = Badge::find($this->object_id);
        }
        return $this->badge;
    }
}
