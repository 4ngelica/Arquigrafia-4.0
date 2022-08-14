<?php
namespace App\lib\utils;
use Occupation;
use UsersRole;
use Carbon\Carbon;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Illuminate\Filesystem\Filesystem;
use Auth;

class ActionUser {
    public static function convertArrayObjectToString($array, $atribute) {
        $string = "";
        $numElement = count($array);
        $i = 1;
        $separator = ', ';
        if(!empty($array)) {
            foreach ($array as $value) {
                if($numElement == $i) {
                    $separator = '';
                }
                $string = $string.''.$value->$atribute.$separator;
                $i++;
            }
        }
        return $string;
    }

    public static function printInitialStatment($file_path, $user_id, $source_page) {
        $occupation_array = Occupation::userOccupation($user_id);
        $roles_array = UsersRole::valueUserRole($user_id);
        $user_occupation ="";
        $user_roles ="";
        $user_occupation = ActionUser::convertArrayObjectToString($occupation_array,'occupation');
        $user_roles = ActionUser::convertArrayObjectToString($roles_array,'name');
        $date_and_time = Carbon::now('America/Sao_Paulo')->toDateTimeString();
        $info = sprintf('[%s] Acesso do usuário de ID nº: [%d], com ocupação [%s] e role [%s], a partir de [%s].', $date_and_time, $user_id, $user_occupation, $user_roles, $source_page);

        $log = new Logger('Access_logger');
        ActionUser::addInfoToLog($log, $file_path, $info);
    }

    public static function createDirectoryAndFile($date, $user_id, $source_page, $user_or_visitor) {
        $dir_path =  storage_path() . '/logs/' . $date;
        if ($user_or_visitor == "user") {
            $file_path = storage_path() . '/logs/' . $date . '/' . 'user_' . $user_id . '.log';
        }
        elseif ($user_or_visitor == "visitor") {
            $file_path = storage_path() . '/logs/' . $date . '/' . 'visitor_' . $user_id . '.log';
        }

        $filesystem = new Filesystem();
        if(!$filesystem->exists($dir_path)) {
            $dir_created = $filesystem->makeDirectory($dir_path);
        }
        if(!$filesystem->exists($file_path)) {
            $handle = fopen($file_path, 'a+');
            fclose($handle);
            ActionUser::printInitialStatment($file_path, $user_id, $source_page);
        }
        return $file_path;
    }

    public static function verifyTimeout($file_path, $user_id, $source_page) {
        $data = file($file_path);
        $line = $data[count($data)-1];
        sscanf($line, "[%s %s]", $date, $time);
        list($last_year, $last_month, $last_day) = explode("-", $date);
        list($last_hour, $last_minutes, $last_seconds) = explode(":", $time);
        list($last_seconds, $trash) = explode("]", $last_seconds);
        $date_and_time_now = Carbon::now('America/Sao_Paulo');
        $date_and_time_last = Carbon::create($last_year, $last_month, $last_day, $last_hour, $last_minutes, $last_seconds, 'America/Sao_Paulo');
        if ($date_and_time_now->diffInMinutes($date_and_time_last) > 20) {
            $result = "Timeout atingido, novo acesso detectado";
            $log = new Logger('Timeout_logger');
            ActionUser::addInfoToLog($log, $file_path, $result);
            ActionUser::printInitialStatment($file_path, $user_id, $source_page);
        }
    }

    public static function addInfoToLog($log, $file_path, $info) {
        $formatter = new LineFormatter("%message%\n", null, false, true);
        $handler = new StreamHandler($file_path, Logger::INFO);
        $handler->setFormatter($formatter);
        $log->pushHandler($handler);
        $log->addInfo($info);
    }

    //Photos Controller
    public static function printUploadOrDownloadLog($user_id, $photo_id, $source_page, $up_or_down, $user_or_visitor) {
        $date_and_time = Carbon::now('America/Sao_Paulo')->toDateTimeString();
        list($date_only) = explode(" ", $date_and_time);
        $file_path = ActionUser::createDirectoryAndFile($date_only, $user_id, $source_page, $user_or_visitor);
        ActionUser::verifyTimeout($file_path, $user_id, $source_page);
        $info = sprintf('[%s] ' . $up_or_down . ' da foto de ID nº: %d, pela página %s', $date_and_time, $photo_id, $source_page);

        $log = new Logger('UpOrDownload_logger');
        ActionUser::addInfoToLog($log, $file_path, $info);
    }
    //Photos Controller
    public static function printEditOrDeletePhotoLog($user_id, $photo_id, $source_page, $edit_or_delete, $user_or_visitor) {
        $date_and_time = Carbon::now('America/Sao_Paulo')->toDateTimeString();
        list($date_only) = explode(" ", $date_and_time);
        $file_path = ActionUser::createDirectoryAndFile($date_only, $user_id, $source_page, $user_or_visitor);
        ActionUser::verifyTimeout($file_path, $user_id, $source_page);
        $info = sprintf('[%s] ' . $edit_or_delete . ' da foto de ID nº: %d, pela página %s', $date_and_time, $photo_id, $source_page);

        $log = new Logger('EditOrDelete_logger');
        ActionUser::addInfoToLog($log, $file_path, $info);
    }
    //Users Controller
    public static function printFollowOrUnfollowLog($user_id, $target_user_id, $source_page, $follow_or_unfollow, $user_or_visitor) {
        $date_and_time = Carbon::now('America/Sao_Paulo')->toDateTimeString();
        list($date_only) = explode(" ", $date_and_time);
        $file_path = ActionUser::createDirectoryAndFile($date_only, $user_id, $source_page, $user_or_visitor);
        ActionUser::verifyTimeout($file_path, $user_id, $source_page);
        $info = sprintf('[%s] Usuário de ID nº: %d ' . $follow_or_unfollow . ' o usuário de ID nº: %d, pela página %s', $date_and_time, $user_id, $target_user_id, $source_page);

        $log = new Logger('FollowOrUnfollow_logger');
        ActionUser::addInfoToLog($log, $file_path, $info);
    }
    //Photos Controller
    public static function printSelectPhoto($user_id, $photo_id, $source_page, $user_or_visitor) {
        $date_and_time = Carbon::now('America/Sao_Paulo')->toDateTimeString();
        list($date_only) = explode(" ", $date_and_time);
        $file_path = ActionUser::createDirectoryAndFile($date_only, $user_id, $source_page, $user_or_visitor);
        ActionUser::verifyTimeout($file_path, $user_id, $source_page);
        $info = sprintf('[%s] Selecionou a foto de ID nº: %d, pela página %s', $date_and_time, $photo_id, $source_page);

        $log = new Logger('Select logger');
        ActionUser::addInfoToLog($log, $file_path, $info);
    }
    //Users Controller
    public static function printLoginOrLogout($user_id, $source_page, $login_or_logout, $arquigrafia_facebook_stoa, $user_or_visitor) {
        $date_and_time = Carbon::now('America/Sao_Paulo')->toDateTimeString();
        list($date_only) = explode(" ", $date_and_time);
        $file_path = ActionUser::createDirectoryAndFile($date_only, $user_id, $source_page, $user_or_visitor);
        ActionUser::verifyTimeout($file_path, $user_id, $source_page);
        $info = sprintf('[%s] ' . $login_or_logout .' através do ' . $arquigrafia_facebook_stoa . ', pela página %s', $date_and_time, $source_page);

        $log = new Logger('LoginOrLogout logger');
        ActionUser::addInfoToLog($log, $file_path, $info);
    }
    //Users Controller
    public static function printSelectUser($user_id, $target_user_id, $source_page, $user_or_visitor) {
        $date_and_time = Carbon::now('America/Sao_Paulo')->toDateTimeString();
        list($date_only) = explode(" ", $date_and_time);
        $file_path = ActionUser::createDirectoryAndFile($date_only, $user_id, $source_page, $user_or_visitor);
        ActionUser::verifyTimeout($file_path, $user_id, $source_page);
        $info = sprintf('[%s] Selecionou o usuário de ID nº: %d, pela página %s', $date_and_time, $target_user_id, $source_page);

        $log = new Logger('SelectUser logger');
        ActionUser::addInfoToLog($log, $file_path, $info);
    }
    //Photos Controller
    public static function printComment($user_id, $source_page, $inserted_edited_deleted, $comment_id, $photo_id, $user_or_visitor) {
        $date_and_time = Carbon::now('America/Sao_Paulo')->toDateTimeString();
        list($date_only) = explode(" ", $date_and_time);
        $file_path = ActionUser::createDirectoryAndFile($date_only, $user_id, $source_page, $user_or_visitor);
        ActionUser::verifyTimeout($file_path, $user_id, $source_page);
        $info = sprintf('[%s] '. $inserted_edited_deleted . ' o comentário de ID nº: %d, na foto de ID nº: %d, pela página %s', $date_and_time, $comment_id, $photo_id, $source_page);

        $log = new Logger('Comment logger');
        ActionUser::addInfoToLog($log, $file_path, $info);
    }
    //Pages Controller
    public static function printSearch($user_id, $source_page, $key_words, $user_or_visitor) {
        $date_and_time = Carbon::now('America/Sao_Paulo')->toDateTimeString();
        list($date_only) = explode(" ", $date_and_time);
        $file_path = ActionUser::createDirectoryAndFile($date_only, $user_id, $source_page, $user_or_visitor);
        ActionUser::verifyTimeout($file_path, $user_id, $source_page);
        $size = str_word_count($key_words);
        $info = sprintf('[%s] Buscou por %d palavra(s): ' . $key_words . '; pela página %s', $date_and_time, $size, $source_page);

        $log = new Logger('Search logger');
        ActionUser::addInfoToLog($log, $file_path, $info);
    }

    public static function printHomePage($user_id, $source_page, $user_or_visitor) {
        $date_and_time = Carbon::now('America/Sao_Paulo')->toDateTimeString();
        list($date_only) = explode(" ", $date_and_time);
        $file_path = ActionUser::createDirectoryAndFile($date_only, $user_id, $source_page, $user_or_visitor);
        ActionUser::verifyTimeout($file_path, $user_id, $source_page);
        $info = sprintf('[%s] Acessou a home page, pela página %s', $date_and_time, $source_page);

        $log = new Logger('Home logger');
        ActionUser::addInfoToLog($log, $file_path, $info);
    }

    public static function printNewAccount($user_id, $source_page, $arquigrafia_or_facebook, $user_or_visitor) {
        $date_and_time = Carbon::now('America/Sao_Paulo')->toDateTimeString();
        list($date_only) = explode(" ", $date_and_time);
        $file_path = ActionUser::createDirectoryAndFile($date_only, $user_id, $source_page, $user_or_visitor);
        ActionUser::verifyTimeout($file_path, $user_id, $source_page);
        $info = sprintf('[%s] Nova conta criada pelo ' . $arquigrafia_or_facebook . ', ID nº: %d', $date_and_time, $user_id);

        $log = new Logger('NewAccount logger');
        ActionUser::addInfoToLog($log, $file_path, $info);
    }

    public static function printLikeDislike($user_id, $photo_or_comment_id, $source_page, $photo_or_comment, $like_or_dislike, $user_or_visitor) {
        $date_and_time = Carbon::now('America/Sao_Paulo')->toDateTimeString();
        list($date_only) = explode(" ", $date_and_time);
        $file_path = ActionUser::createDirectoryAndFile($date_only, $user_id, $source_page, $user_or_visitor);
        ActionUser::verifyTimeout($file_path, $user_id, $source_page);
        $info = sprintf('[%s] ' . $like_or_dislike . " " . $photo_or_comment . ', ID nº: %d', $date_and_time, $photo_or_comment_id);

        $log = new Logger('LikeDislike logger');
        ActionUser::addInfoToLog($log, $file_path, $info);
    }

    public static function printTags($user_id, $photo_id, $tags, $source_page, $user_or_visitor, $inserted_edited) {
        $date_and_time = Carbon::now('America/Sao_Paulo')->toDateTimeString();
        list($date_only) = explode(" ", $date_and_time);
        $file_path = ActionUser::createDirectoryAndFile($date_only, $user_id, $source_page, $user_or_visitor);
        ActionUser::verifyTimeout($file_path, $user_id, $source_page);
        $info = sprintf('[%s] ' . $inserted_edited . ' as tags: ' . $tags . '. Pertencentes a foto de ID nº: %d', $date_and_time, $photo_id);

        $log = new Logger('Tags logger');
        ActionUser::addInfoToLog($log, $file_path, $info);
    }

    public static function printEvaluation($user_id, $photo_id, $source_page, $user_or_visitor, $inserted_edited, $evaluation) {
        $date_and_time = Carbon::now('America/Sao_Paulo')->toDateTimeString();
        list($date_only) = explode(" ", $date_and_time);
        $file_path = ActionUser::createDirectoryAndFile($date_only, $user_id, $source_page, $user_or_visitor);
        ActionUser::verifyTimeout($file_path, $user_id, $source_page);
        $info = sprintf('[%s] ' . $inserted_edited . ' avaliação na foto de ID nº: %d, com os seguintes valores %spela página: %s', $date_and_time, $photo_id, $evaluation, $source_page);

        $log = new Logger('Evaluation logger');
        ActionUser::addInfoToLog($log, $file_path, $info);
    }

    public static function printEvaluationAccess($user_id, $photo_id, $source_page, $user_or_visitor, $local) {
        $date_and_time = Carbon::now('America/Sao_Paulo')->toDateTimeString();
        list($date_only) = explode(" ", $date_and_time);
        $file_path = ActionUser::createDirectoryAndFile($date_only, $user_id, $source_page, $user_or_visitor);
        ActionUser::verifyTimeout($file_path, $user_id, $source_page);
        $info = sprintf('[%s] Acessou a página de avaliação %s da página %s', $date_and_time, $local, $source_page);

        $log = new Logger('EvaluationAccess logger');
        ActionUser::addInfoToLog($log, $file_path, $info);
    }

    public static function printNotification($user_id, $source_page, $user_or_visitor) {
        $date_and_time = Carbon::now('America/Sao_Paulo')->toDateTimeString();
        list($date_only) = explode(" ", $date_and_time);
        $file_path = ActionUser::createDirectoryAndFile($date_only, $user_id, $source_page, $user_or_visitor);
        ActionUser::verifyTimeout($file_path, $user_id, $source_page);
        $info = sprintf('[%s] Acessou a página de notificações pela página %s', $date_and_time, $source_page);

        $log = new Logger('Notification logger');
        ActionUser::addInfoToLog($log, $file_path, $info);
    }

   // $photo->user_id, $photo->id, $source_page, $actionContent, 'mobile'
    public static function printEventLogs($userId, $photoId, $sourcePage, $actionContent, $device) {
        $date_and_time = Carbon::now('America/Sao_Paulo')->toDateTimeString();
        list($date_only) = explode(" ", $date_and_time);
        if(Auth::check())
            $userType = 'user';
        else
            $userType = 'visitor';

        $filePath = ActionUser::createDirectoryAndFile($date_only, $userId, $sourcePage, $userType);

        ActionUser::verifyTimeout($filePath, $userId, $sourcePage);

        foreach($actionContent as $action=>$content)
          {
              switch ($action) {
                case "upload":
                    $info = sprintf('[%s] ' . $action . ' da foto de ID nº: %d, pela página %s', $date_and_time, $photoId, $sourcePage);
                    ActionUser::generalAddInfoLogs('UpOrDownload_logger', $filePath, $info);
                    break;
                case "tags_insert":
                    $info = sprintf('[%s] Inseriu as tags: ' . $content . '. Pertencentes a foto de ID nº: %d', $date_and_time, $photoId);
                    ActionUser::generalAddInfoLogs('Tags logger', $filePath, $info);
                    break;
              }
          }

    }

    public static function generalAddInfoLogs($titleLog, $filePath, $info) {
        $log = new Logger($titleLog);
        ActionUser::addInfoToLog($log, $filePath, $info);
    }

}
