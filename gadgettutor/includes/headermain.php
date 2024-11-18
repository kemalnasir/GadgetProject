<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
.dropbtn {
  background-color: #04AA6D;
  color: white;
  padding: 16px;
  font-size: 16px;
  border: none;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #040112;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {background-color: #ddd;}

.dropdown:hover .dropdown-content {display: block;}

.dropdown:hover .dropbtn {background-color: #3e8e41;}
</style>
</head>

<header>
  <nav id="navigation_bar" class="navbar navbar-default">
        <a href="#" class="logo"  style="font-size:35px; margin-left: 1%; margin-top: 0.5%; color:#fff">Gadget Tutor</a>
        <div class="container">
            <div class="navbar-header">
                <button id="menu_slide" data-target="#navigation" aria-expanded="false" data-toggle="collapse" class="navbar-toggle collapsed" type="button"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            </div>

          
            
            <div class="header_wrap">
                <div class="user_login">
                    <ul>
                    <li class="dropdown"> 
                    <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user-circle" ></i>  Register Here
                    <i class="fa fa-angle-down"></i>
                    </a>                     
                    <div class="dropdown-content">
                        <a href="user_signup.php">Sign Up</a>
                        <a href="user_login.php">Login</a>
                    </div>
                </div>     
               
            </div>
       
      
  </nav>  
</header>