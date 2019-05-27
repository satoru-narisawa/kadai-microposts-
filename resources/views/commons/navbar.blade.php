<header class="mb-4">
    <nav class="navnar navbar-expand-sm navbar-dark bg-dark">
        <a class="navbar-brand" href="/">Microposts</a>
        
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#nav-bar">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="nav-bar">
            <ul class="navbar-nav mr-auto"></ul>
            <ul class="navbar-nav navbar-right">
                <li>{!! link_to_route("signup.get","Signup",[],["class"=>"nav-link"]) !!}</li>
                <li><a herf="#">Login</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Signup</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Login</a></li>
            </ul>
        </div>
    </nav>
</header>