<!doctype html>


<html>
<head>
    <meta charset="UTF-8">
    
    
</head>
<body>
    <h1>All Users</h1>
    
    @foreach($users as $users)
    	<li> {{ link_to ("/groups/{$users->id}",$users->name) }} </li>
    @endforeach

</body>
</html>
