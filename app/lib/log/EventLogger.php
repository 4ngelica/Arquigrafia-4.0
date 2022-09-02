<?php

namespace App\lib\log;

use App\Models\Users\Occupation;
use App\Models\Users\UsersRole;
use Carbon\Carbon;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Illuminate\Filesystem\Filesystem;
use App\lib\utils\ActionUser;
use Auth;
use App\Models\Gamification\Gamified;
use File;
use Storage;

class EventLogger {
	public static function printInitialStatment($file_path, $user_id, $source_page, $user_or_visitor) {
        $occupation_array = Occupation::userOccupation($user_id);
        $roles_array = UsersRole::valueUserRole($user_id);
        $user_occupation ="";
        $user_roles ="";
        $user_occupation = ActionUser::convertArrayObjectToString($occupation_array,'occupation');
        $user_roles = ActionUser::convertArrayObjectToString($roles_array,'name');
        $date_and_time = Carbon::now('America/Sao_Paulo')->toDateTimeString();
        if ($user_or_visitor == "user") {
            $info = sprintf('[%s] Acesso do usuário de ID nº: [%d], com ocupação [%s] e role [%s], a partir de [%s].', $date_and_time, $user_id, $user_occupation, $user_roles, $source_page);
        }
        else {
            $info = sprintf('[%s] Acesso de visitante, a partir de [%s].', $date_and_time, $source_page);
        }
        $log = new Logger('Access_logger');
        EventLogger::addInfoToLog($log, $file_path, $info);
    }

    public static function createDirectoryAndFile($date, $user_id, $source_page, $user_or_visitor) {
        $dir_path =  storage_path() . '/logs/' . $date;
        if ($user_or_visitor == "user") {
            $file_path = 'logs/' . $date . '/' . 'user_' . $user_id . '.log';
        }
        elseif ($user_or_visitor == "visitor") {
            $file_path = 'logs/' . $date . '/' . 'visitor_' . $user_id . '.log';
        }

        $filesystem = new Filesystem();
        if(!$filesystem->exists($dir_path)) {
            $dir_created = $filesystem->makeDirectory($dir_path);
        }
        if(!$filesystem->exists($file_path)) {
            $handle = fopen(storage_path() . '/' . $file_path, 'a+');
            fclose($handle);
            EventLogger::printInitialStatment($file_path, $user_id, $source_page, $user_or_visitor);
        }
        return $file_path;
    }

    public static function verifyTimeout($file_path, $user_id, $source_page, $user_or_visitor) {
			// dd(storage_path('logs'));
			// dd(file($file_path));
			// dd(file_get_contents($file_path));

				// dd(File::files('/var/www/html/storage/logs/2022-08-14'));
				// dd(Storage::disk('logs'));
				// dd(Storage::get($file_path));


				 // dd(Storage::get('/var/www/html/storage/logs/2022-08-14/visitor_0659d8d6f8ab4cbdd6a16e0cfb3b790c.log'));
				// /var/www/html/storage/logs/2022-08-14/
				//"/var/www/html/storage/logs/2022-08-14/visitor_0659d8d6f8ab4cbdd6a16e0cfb3b790c.log"
				$data = file(storage_path() . '/' . $file_path);
				// $data = Storage::get($file_path);
				// $data = File::getRequire(storage_path() . '/' . $file_path);

				// dd($data);


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
            EventLogger::addInfoToLog($log, $file_path, $result);
            EventLogger::printInitialStatment($file_path, $user_id, $source_page, $user_or_visitor);
        }
    }

    public static function addInfoToLog($channel, $file_path, $info) {
        $log = new Logger('Access_logger');
        $formatter = new LineFormatter("%message%\n", null, false, true);
        $handler = new StreamHandler(storage_path() . '/' . $file_path , Logger::INFO);

        $handler->setFormatter($formatter);
        $log->pushHandler($handler);
        $log->info($info);
    }

    public static function printEventLogs($photoId, $eventType, $eventContent, $device) {
        $date_and_time = Carbon::now('America/Sao_Paulo')->toDateTimeString();
        list($date_only) = explode(" ", $date_and_time);
        $gamified_tag = Gamified::getGamifiedVariationTag();

        if (!isset($_SESSION)) session_start();
        $userId = session_id();
        $userType = 'visitor';
        if (Auth::check()) {
          $userType = 'user';
          $userId = Auth::user()->id;
        } elseif ($device == 'mobile') {
          $userType = 'user';
          $userId = $eventContent['user'];
        }
        $sourcePage = \Request::server('HTTP_REFERER');

        $filePath = EventLogger::createDirectoryAndFile($date_only, $userId, $sourcePage, $userType);
        EventLogger::verifyTimeout($filePath, $userId, $sourcePage, $userType);


        switch ($eventType) {
        	case "home":
        		$info = sprintf('[%s] Acessou a home page, pela página %s, via %s - [%s]',
        						$date_and_time, $sourcePage, $device, $gamified_tag);
                break;
            case "new_account":
            	$info = sprintf('[%s] Nova conta criada pelo %s, ID nº: %d - [%s]',
            					$date_and_time, $eventContent['origin'], $userId, $gamified_tag);
                break;
            case "upload":
                $info = sprintf('[%s] Upload da foto de ID nº: %d, pela página %s, via %s - [%s]',
                	$date_and_time, $photoId, $sourcePage, $device, $gamified_tag);
                break;
            case "download":
            	$info = sprintf('[%s] Download da foto de ID nº: %d, pela página %s, via %s - [%s]',
                	$date_and_time, $photoId, $sourcePage, $device, $gamified_tag);
                break;
            case "edit":
            	$info = sprintf('[%s] Edição da foto de ID nº: %d, pela página %s, via %s - [%s]',
                				$date_and_time, $photoId, $sourcePage, $device, $gamified_tag);
                break;
            case "delete":
            	$info = sprintf('[%s] Deleção da foto de ID nº: %d, pela página %s, via %s - [%s]',
                				$date_and_time, $photoId, $sourcePage, $device, $gamified_tag);
                break;
            case "follow":
            	$info = sprintf('[%s] Usuário de ID nº: %d passou a seguir o usuário de ID nº: %d, pela página %s, via %s - [%s]',
            					$date_and_time, $userId, $eventContent['target_userId'], $sourcePage, $device, $gamified_tag);
                break;
            case "unfollow":
                $info = sprintf('[%s] Usuário de ID nº: %d deixou de seguir o usuário de ID nº: %d, pela página %s, via %s - [%s]',
                				$date_and_time, $userId, $eventContent['target_userId'], $sourcePage, $device, $gamified_tag);
                break;
            case "select_photo":
            	$info = sprintf('[%s] Selecionou a foto de ID nº: %d, pela página %s, via %s - [%s]',
            					$date_and_time, $photoId, $sourcePage, $device, $gamified_tag);
                break;
            case "edit_photo":
                $info = sprintf('[%s] Editou a foto de ID nº: %d, pela página %s, via %s - [%s]',
                                $date_and_time, $photoId, $sourcePage, $device, $gamified_tag);
                break;
            case "insert_tags":
                $info = sprintf('[%s] Inseriu as tags: %s. Pertencentes a foto de ID nº: %d, via %s - [%s]',
                				$date_and_time, $eventContent['tags'], $photoId, $device, $gamified_tag);
                break;
            case "edit_tags":
                $info = sprintf('[%s] Editou as tags: %s. Pertencentes a foto de ID nº: %d, via %s - [%s]',
                				$date_and_time, $eventContent['tags'], $photoId, $device, $gamified_tag);
                break;
            case "login":
            	$info = sprintf('[%s] Login através do %s, pela página %s, via %s - [%s]',
            					$date_and_time, $eventContent['origin'], $sourcePage, $device, $gamified_tag);
                break;
            case "logout":
            	$info = sprintf('[%s] Logout pela página %s, via %s - [%s]',
            					$date_and_time, $sourcePage, $device, $gamified_tag);
                break;
            case "select_user":
            	$info = sprintf('[%s] Selecionou o usuário de ID nº: %d, pela página %s, via %s - [%s]',
            		            $date_and_time, $eventContent['target_userId'], $sourcePage, $device, $gamified_tag);
                break;
            case "insert_comment":
             	$info = sprintf('[%s] Inseriu o comentário de ID nº: %d, na foto de ID nº: %d, pela página %s, via %s - [%s]',
             					$date_and_time, $eventContent['comment_id'], $photoId, $sourcePage, $device, $gamified_tag);
                break;
            case "edit_comment":
             	$info = sprintf('[%s] Editou o comentário de ID nº: %d, na foto de ID nº: %d, pela página %s, via %s - [%s]',
             					$date_and_time, $eventContent['comment_id'], $photoId, $sourcePage, $device, $gamified_tag);
                break;
            case "delete_comment":
             	$info = sprintf('[%s] Deletou o comentário de ID nº: %d, na foto de ID nº: %d, pela página %s, via %s - [%s]',
             					$date_and_time, $eventContent['comment_id'], $photoId, $sourcePage, $device, $gamified_tag);
                break;
            case "search":
            	$info = sprintf('[%s] Buscou por %d palavra(s): %s; pela página %s, via %s - [%s]',
            					$date_and_time, $eventContent['search_size'], $eventContent['search_query'], $sourcePage, $device, $gamified_tag);
                break;
            case "like":
            	$info = sprintf('[%s] Curtiu %s, ID nº: %d, pela página %s, via %s - [%s]',
                                $date_and_time, $eventContent['target_type'], $eventContent['target_id'], $sourcePage, $device, $gamified_tag);
                break;
            case "dislike":
            	$info = sprintf('[%s] Descurtiu %s, ID nº: %d, pela página %s, via %s - [%s]',
                                $date_and_time, $eventContent['target_type'], $eventContent['target_id'], $sourcePage, $device, $gamified_tag);
                break;
            case "insert_evaluation":
            	$info = sprintf('[%s] Inseriu avaliação na foto de ID nº: %d, com os seguintes valores %s pela página: %s, via %s - [%s]',
                                $date_and_time, $photoId, $eventContent['evaluation'], $sourcePage, $device, $gamified_tag);
                break;
            case "edit_evaluation":
            	$info = sprintf('[%s] Editou avaliação na foto de ID nº: %d, com os seguintes valores %s pela página: %s, via %s - [%s]',
                                $date_and_time, $photoId, $eventContent['evaluation'], $sourcePage, $device, $gamified_tag);
                break;
            case "access_evaluation_page":
            	$info = sprintf('[%s] Acessou a página de avaliação %s da página %s, via %s - [%s]',
                                $date_and_time, $eventContent['object_source'], $sourcePage, $device, $gamified_tag);
                break;
            case "access_notification_page":
            	$info = sprintf('[%s] Acessou a página de notificações pela página %s, via %s - [%s]',
                                $date_and_time, $sourcePage, $device, $gamified_tag);
                break;
            case "new_thread":
                $info = sprintf('[%s] Criou o chat %s com os usuários %s pela página %s, via %s - [%s]',
                                $date_and_time, $eventContent['thread'], $eventContent['participants'], $sourcePage, $device, $gamified_tag);
                break;
            case "new_message":
                $info = sprintf('[%s] Criou a mensagem %s para o chat %s pela página %s, via %s - [%s]',
                                $date_and_time, $eventContent['message'], $eventContent['thread'], $sourcePage, $device, $gamified_tag);
                break;
            case "completion":
                $info = sprintf('[%s] Completude de dados de ID nº: %s, pela página %s, via %s - [%s]',
                                $date_and_time, $eventContent['suggestion'], $sourcePage, $device, $gamified_tag);
                break;
						case "completion-none":
								$info = sprintf('[%s] Fluxo de cartões - Abandono sem ação pela página %s, via %s - [%s]',
																$date_and_time, $sourcePage, $device, $gamified_tag);
								break;
						case "completion-incomplete":
								$info = sprintf('[%s] Fluxo de cartões - Abandono com %s sugestões pela página %s, via %s - [%s]',
																$date_and_time, $eventContent['suggestions'], $sourcePage, $device, $gamified_tag);
								break;
						case "completion-complete":
								$info = sprintf('[%s] Fluxo de cartões - Finalizado com %s sugestões pela página %s, via %s - [%s]',
																$date_and_time, $eventContent['suggestions'], $sourcePage, $device, $gamified_tag);
								break;
            case "moderation":
                $info = sprintf('[%s] Moderacao de dados no Id: %s, pela página %s, via %s - [%s]',
                                $date_and_time, $eventContent['suggestion'], $sourcePage, $device, $gamified_tag);
                break;
            case "card-chat":
              $info = sprintf('[%s] Chat - Acesso pelo fluxo de cartões na página %s, via %s - [%s]',
                $date_and_time, $sourcePage, $device, $gamified_tag);
              break;
            case "open-modal":
              $info = sprintf('[%s] Open Modal - Abriu o modal na página %s, pela origem %s, via %s - [%s]',
                $date_and_time, $sourcePage, $eventContent['origin'], $device, $gamified_tag);
              break;
            case "pressed-final-modal-photo":
              $info = sprintf('[%s] Fluxo de cartões - Acesso imagem %s na última página. Finalizado com %s sugestões pela página %s, via %s - [%s]',
                $date_and_time, $eventContent['redirect_photo_id'], $eventContent['num_suggestions'], $sourcePage, $device, $gamified_tag);
              break;
            case "pressed-final-modal-profile-link":
              $info = sprintf('[%s] Fluxo de cartões - Acesso ao perfil de dono da imagem %s pela última página. Finalizado com %s sugestões pela página %s, via %s - [%s]',
                $date_and_time, $eventContent['photo_id'], $eventContent['num_suggestions'], $sourcePage, $device, $gamified_tag);
              break;
            case "create-chat-user":
              $info = sprintf('[%s] Criou um chat com o usuário %s pela página %s, via %s - [%s]',
                $date_and_time, $eventContent['chat_user_id'], $sourcePage, $device, $gamified_tag);
              break;
            case "pressed-suggestion-analysed-notification":
              $info = sprintf('[%s] Clique em Notificação %s de sugestão analisada pela página %s, via %s - [%s]',
                $date_and_time, $eventContent['notification_id'], $sourcePage, $device, $gamified_tag);
              break;
            case "pressed-suggestion-received-notification":
              $info = sprintf('[%s] Clique em Notificação %s de sugestão recebida pela página %s, via %s - [%s]',
                $date_and_time, $eventContent['notification_id'], $sourcePage, $device, $gamified_tag);
              break;
            case "accepted-rejected-suggestion":
              $info = sprintf('[%s] Completude de dados de ID nº %s com revisão %s pelo autor da imagem pela página %s, via %s - [%s]',
                $date_and_time, $eventContent['suggestion_id'], $eventContent['status'], $sourcePage, $device, $gamified_tag);
              break;
            case "redirect-users-contributions":
              $info = sprintf('[%s] Redirecionado para as Contribuições pela página %s, via %s - [%s]',
                $date_and_time, $sourcePage, $device, $gamified_tag);
              break;
            case "redirect-my-points":
              $info = sprintf('[%s] Redirecionado para #my_points no perfil pela página %s, via %s - [%s]',
                $date_and_time, $sourcePage, $device, $gamified_tag);
              break;
            default:
                break;
        }
        EventLogger::addInfoToLog('Logger', $filePath, $info);
    }

}
