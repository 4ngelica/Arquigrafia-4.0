<ul>
    <?php $counter=0; ?>
        @foreach($user->notifications()->orderBy('created_at')->get()->reverse() as $notification)
            <?php
                if($counter >= $max) break;
                else $counter++;
                $info_array = $notification->render();
                if ( ! is_null($notification->deleted_at) ) {
                    continue;
                }
            ?>
            @if($info_array[0] == "photo_liked")
                <div id={{$notification->id}} class="notes {{$notification->id}}<?php if($notification->read_at == null) echo ' not-read'?>" >
                    <li>
                        <div class="read-button" title="Marcar como lida" onclick="markRead(this);"></div>
                        <div class="notification-container" onclick="markRead(this);">
                            <a href= {{URL::to('photos/' . $info_array[2])}}>
                                <img class="mini"
                                src={{asset("/arquigrafia-images/" . $info_array[2] . "_home.jpg") }}>
                            </a>
                            @if($info_array[6] == null)
                            <a href={{URL::to('users/' . $info_array[5])}}>{{$info_array[1]}}</a>{{" curtiu sua " }} <a href={{URL::to('photos/' . $info_array[2])}}>{{"foto"}}</a>{{"."}}
                            @else
                            <?php
                                $users = explode(":", $info_array[6]);
                                $users_size = count($users) - 1;
                                for ($i = 0; $i < $users_size; $i++) {
                                    $user[$i] = User::find($users[$i+1]);
                                }
                            ?>
                            @if($users_size < 2)
                            <a href={{URL::to('users/' . $info_array[5])}}>{{$info_array[1]}}</a>{{" e "}}<a href={{URL::to("users/" . $user[0]->id)}}>{{$user[0]->name}}</a>{{" curtiram sua " }} <a href={{URL::to("photos/" . $info_array[2])}}>{{"foto"}}</a>{{"."}}
                            @else
                            <a href={{URL::to('users/' . $info_array[5])}}>{{$info_array[1]}}</a>{{" e mais "}}<a class="fancybox" href={{"#users-from-note-" . $notification->id}}>{{$users_size . " pessoas"}}</a>{{" curtiram sua "}}<a href={{URL::to("photos/" . $info_array[2])}}>{{"foto"}}</a>{{"."}}
                            <div class="additional-users" id={{"users-from-note-" . $notification->id}}>
                                <ul>
                                    @for($i = 0; $i < $users_size; $i++)
                                    <li class="additional-user"><a href={{URL::to("users/" . $user[$i]->id)}}>{{$user[$i]->name}}</a></li>
                                    @endfor
                                </ul>
                            </div>
                            @endif
                            @endif
                            </br>
                            <p class="date-and-time">{{"$info_array[3], às $info_array[4]."}}</p>
                            <a class="link-block" href={{URL::to("photos/" . $info_array[2])}}></a>
                        </div>
                    </li>
                </div>
            @elseif($info_array[0] == "comment_liked")
                <div id={{$notification->id}} class="notes {{$notification->id}}<?php if($notification->read_at == null) echo ' not-read'?>">
                    <li>
                        <div class="read-button" title="Marcar como lida" onclick="markRead(this);"></div>
                        <div onclick="markRead(this);">
                            <a href={{URL::to("photos/" . $info_array[2])}}><img class="mini" src={{"/arquigrafia-images/" . $info_array[2] . "_home.jpg"}}></a>
                            @if($info_array[9] == null)
                            <a href={{URL::to("users/" . $info_array[5])}}>{{ $info_array[1]}}</a>{{" curtiu seu "}}<a href={{URL::to("photos/" . $info_array[2] . "#" . $info_array[8])}}>{{"comentário"}}</a>{{", na "}}<a href={{URL::to("photos/" . $info_array[2])}}>{{"foto"}}</a>{{" de "}}<a href={{URL::to("users/" . $info_array[6])}}>{{$info_array[7]}}</a>{{"."}}
                            @else
                            <?php
                                $users = explode(":", $info_array[9]);
                                $users_size = count($users) - 1;
                                for ($i = 0; $i < $users_size; $i++) {
                                    $user[$i] = User::find($users[$i+1]);
                                }
                            ?>
                            @if($users_size < 2)
                            <a href={{URL::to("users/" . $info_array[5])}}>{{ $info_array[1]}}</a>{{" e "}}<a href={{URL::to("users/" . $user[0]->id)}}>{{$user[0]->name}}</a>{{" curtiram seu "}}<a href={{URL::to("photos/" . $info_array[2] . "#" . $info_array[8])}}>{{"comentário"}}</a>{{", na "}}<a href={{URL::to("photos/" . $info_array[2])}}>{{"foto"}}</a>{{" de "}}<a href={{URL::to("users/" . $info_array[6])}}>{{$info_array[7]}}</a>{{"."}}
                            @else
                            <a href={{URL::to("users/" . $info_array[5])}}>{{ $info_array[1]}}</a>{{" e mais "}}<a class="fancybox" href={{"#users-from-note-" . $notification->id}}>{{$users_size . " pessoas"}}</a>{{" curtiram seu "}}<a href={{URL::to("photos/" . $info_array[2] . "#" . $info_array[8])}}>{{"comentário"}}</a>{{", na "}}<a href={{URL::to("photos/" . $info_array[2])}}>{{"foto"}}</a>{{" de "}}<a href={{URL::to("users/" . $info_array[6])}}>{{$info_array[7]}}</a>{{"."}}
                            <div class="additional-users" id={{"users-from-note-" . $notification->id}}>
                                <ul>
                                    @for($i = 0; $i < $users_size; $i++)
                                    <li class="additional-user"><a href={{URL::to("users/" . $user[$i]->id)}}>{{$user[$i]->name}}</a></li>
                                    @endfor
                                </ul>
                            </div>
                            @endif
                            @endif
                            </br>
                            <p class="date">{{"$info_array[3], às $info_array[4]."}}</p>
                            <a class="link-block" href={{URL::to("photos/" . $info_array[2] . "#" . $info_array[8])}}></a>
                        </div>
                    </li>
                </div>
            @elseif($info_array[0] == "comment_posted")
                <div id={{$notification->id}} class="notes {{$notification->id}}<?php if($notification->read_at == null) echo ' not-read'?>">
                    <li>
                        <div class="read-button" title="Marcar como lida"  onclick="markRead(this);"></div>
                        <div onclick="markRead(this);">
                            <a href={{URL::to("photos/" . $info_array[2])}}><img class="mini" src={{"/arquigrafia-images/" . $info_array[2] . "_home.jpg"}}></a>
                            @if($info_array[9] == null)
                            <a href={{URL::to("users/" . $info_array[5])}}>{{ $info_array[1]}}</a>{{" "}}<a href={{URL::to("photos/" . $info_array[2] . "#" . $info_array[8])}}>{{"comentou"}}</a>{{" sua "}}<a href={{URL::to("photos/" . $info_array[2])}}>{{"foto"}}</a>{{"."}}
                            @else
                            <?php
                                $users = explode(":", $info_array[9]);
                                $users_size = count($users) - 1;
                                for ($i = 0; $i < $users_size; $i++) {
                                    $user[$i] = User::find($users[$i+1]);
                                }
                            ?>
                            @if($users_size < 2)
                            <a href={{URL::to("users/" . $info_array[5])}}>{{ $info_array[1]}}</a>{{" e "}}<a href={{URL::to("users/" . $user[0]->id)}}>{{$user[0]->name}}</a>{{" "}}<a href={{URL::to("photos/" . $info_array[2] . "#" . $info_array[8])}}>{{"comentaram"}}</a>{{" sua "}}<a href={{URL::to("photos/" . $info_array[2])}}>{{"foto"}}</a>{{"."}}
                            @else
                            <a href={{URL::to("users/" . $info_array[5])}}>{{ $info_array[1]}}</a>{{" e mais "}}<a class="fancybox" href={{"#users-from-note-" . $notification->id}}>{{$users_size . " pessoas"}}</a>{{" "}}<a href={{URL::to("photos/" . $info_array[2] . "#" . $info_array[8])}}>{{"comentaram"}}</a>{{" sua "}}<a href={{URL::to("photos/" . $info_array[2])}}>{{"foto"}}</a>{{"."}}
                            <div class="additional-users" id={{"users-from-note-" . $notification->id}}>
                                <ul>
                                    @for($i = 0; $i < $users_size; $i++)
                                    <li class="additional-user"><a href={{URL::to("users/" . $user[$i]->id)}}>{{$user[$i]->name}}</a></li>
                                    @endfor
                                </ul>
                            </div>
                            @endif
                            @endif
                            </br>
                            <p class="date">{{"$info_array[3], às $info_array[4]."}}</p>
                            <a class="link-block" href={{URL::to("photos/" . $info_array[2])}}></a>
                        </div>
                    </li>
                </div>
            @elseif($info_array[0] == "follow")
                <div id={{$notification->id}} class="notes {{$notification->id}}<?php if($notification->read_at == null) echo ' not-read'?>">
                    <li>
                        <div class="read-button" title="Marcar como lida"  onclick="markRead(this);"></div>
                        <div onclick="markRead(this);">
                            <a href={{URL::to("users/" . $info_array[4])}}>
                                @if(User::find($info_array[4])->photo != "")
                                <img class="mini" src="{{ asset(User::find($info_array[4])->photo); }}">
                                @else
                                <img class="mini" src="{{ URL::to("/") }}/img/avatar-48.png">
                                @endif
                            </a>
                            @if($info_array[5] == null)
                            <a href={{URL::to("users/" . $info_array[4])}}>{{ $info_array[1]}}</a>{{" começou a seguir você."}}
                            @else
                            <?php
                                $users = explode(":", $info_array[5]);
                                $users_size = count($users) - 1;
                                for ($i = 0; $i < $users_size; $i++) {
                                    $user[$i] = User::find($users[$i+1]);
                                }
                            ?>
                            @if($users_size < 2)
                            <a href={{URL::to("users/" . $info_array[4])}}>{{ $info_array[1]}}</a>{{" e "}}<a href={{URL::to("users/" . $user[0]->id)}}>{{$user[0]->name}}</a>{{" começaram a seguir você."}}
                            @else
                            <a href={{URL::to("users/" . $info_array[4])}}>{{ $info_array[1]}}</a>{{" e mais "}}<a class="fancybox" href={{"#users-from-note-" . $notification->id}}>{{$users_size . " pessoas"}}</a>{{" começaram a seguir você."}}
                            <div class="additional-users" id={{"users-from-note-" . $notification->id}}>
                                <ul>
                                    @for($i = 0; $i < $users_size; $i++)
                                    <li class="additional-user"><a href={{URL::to("users/" . $user[$i]->id)}}>{{$user[$i]->name}}</a></li>
                                    @endfor
                                </ul>
                            </div>
                            @endif
                            @endif
                            </br>
                            <p class="date">{{"$info_array[2], às $info_array[3]."}}</p>
                            <a class="link-block" href={{URL::to("users/" . $info_array[4])}}></a>
                        </div>
                    </li>
                </div>
            @elseif($info_array[0] == "badge_earned")
                <div id={{$notification->id}} class="notes {{$notification->id}}<?php if($notification->read_at == null) echo ' not-read'?>">
                    <li>
                        <div class="read-button" title="Marcar como lida"  onclick="markRead(this);"></div>
                        <div onclick="markRead(this);">
                            <a href={{URL::to("badges/" . $info_array[3])}}>
                              <img class="mini" src="{{ asset('img/badges/' . $info_array[4]) }}" >
                            </a>
                            Você ganhou o troféu
                            <a href={{URL::to("badges/" . $info_array[3])}}>"{{ $info_array[2]}}"</a>
                            </br>
                            <a class="link-block" href={{URL::to("badges/" . $info_array[3])}}></a>
                        </div>
                    </li>
                </div>
            @elseif($info_array[0] == "suggestionSent")
                <div id={{$notification->id}} class="notes {{$notification->id}}<?php if($notification->read_at == null) echo ' not-read'?>">
                    <li>
                        <div class="read-button" title="Marcar como lida"  onclick="markRead(this);"></div>
                        <div onclick="markRead(this);">
                          <img class="mini" src="{{ URL::to("/") }}/img/avatar-48.png" >
                          Você teve sua sugestão enviada
                          </br>
                        </div>
                    </li>
                </div>
            @elseif($info_array[0] == "suggestionReceived")
                <div id={{$notification->id}} class="notes {{$notification->id}}<?php if($notification->read_at == null) echo ' not-read'?>">
                    <li>
                      <a class="suggestion-received-notification-link" data-id={{ $notification->id }} href="{{URL::to("/users/suggestions")}}">
                        <div class="read-button" title="Marcar como lida"  onclick="markRead(this);"></div>
                        <div onclick="markRead(this);">
                          <img class="mini" src="{{ URL::to("/") }}/img/avatar-48.png" >
                            Você recebeu uma sugestão para imagem "{{ $info_array[6]->name }}" do usuário "{{ $info_array[1] }}"
                            </br>
                        </div>
                      </a>
                    </li>
                </div>
            @elseif($info_array[0] == "suggestionAccepted")
                <div id={{$notification->id}} class="notes {{$notification->id}}<?php if($notification->read_at == null) echo ' not-read'?>">
                    <li>
                        <a class="suggestion-analysed-notification-link" data-id={{ $notification->id }} href="{{URL::to("/users/contributions")}}">
                            <div class="read-button" title="Marcar como lida"  onclick="markRead(this);"></div>
                            <div onclick="markRead(this);">
                            <img class="mini" src="{{ URL::to("/") }}/img/avatar-48.png" >
                                Sua sugestão para imagem "{{ $info_array[6]->name }}" do usuário "{{ $info_array[1] }}" foi analisada.
                                </br>
                            </div>
                        </a>
                    </li>
                </div>
            @elseif($info_array[0] == "suggestionDenied")
                <div id={{$notification->id}} class="notes {{$notification->id}}<?php if($notification->read_at == null) echo ' not-read'?>">
                    <li>
                        <a class="suggestion-analysed-notification-link" data-id={{ $notification->id }} href="{{URL::to("/users/contributions")}}">
                            <div class="read-button" title="Marcar como lida"  onclick="markRead(this);"></div>
                            <div onclick="markRead(this);">
                            <img class="mini" src="{{ URL::to("/") }}/img/avatar-48.png" >
                                Sua sugestão para imagem "{{ $info_array[6]->name }}" do usuário "{{ $info_array[1] }}" foi analisada.
                                </br>
                            </div>
                        </a>
                    </li>
                </div>
            @endif
        @endforeach
    </ul>
