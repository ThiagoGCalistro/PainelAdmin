<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>ATQWEB-3x</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        
    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
<?php 
require_once 'core/init.php';

$errors = null;
if(Input::exists()) { // if there was anything posted
    if(Token::check(Input::get('token'))) { // will check if CSRF token is correct
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array('required' => true),
            'password' => array('required' => true)
        ));

        if($validate->passed()) { // if validation succeeded
            $user = new User();

            // This is for the field to remember login credentials
            $remember = (Input::get('remember') === 'on') ? true : false;
            // Call the login method of user with username, password and remember (true / false).
            // The login() method will return true if login was success, false otherwise.
            $login = $user->login(Input::get('username'), Input::get('password'), $remember);

            if($login) {
                Redirect::to('index.php');
            } else {
                $errors[] = 'Incorrect username or password';
            }
        } else {
            $errors = $validate->errors();
        }
    }
}
?>

	  <div id="login-page">
	  	<div class="container">
	  	
		      <form class="form-login" faceaction="" autocomplete="on" method="post" action="">
		        <h2 class="form-login-heading">LOGIN</h2>
		        <?php
                            if ($errors != null) {
                                foreach ($errors as $error) { ?>
                                    <div class="alert alert-danger">
                                        <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                                    </div>
                        <?php
                                }
                            }
                            if(Session::exists('home')) { ?>
                                <div class="alert alert-success">
                                    <i class="glyphicon glyphicon-ok"></i> &nbsp; <?php echo Session::flash('home'); ?>
                                </div>
                                <?php
                            }
                        ?>
		        <div class="login-wrap">
		            <input type="text" class="form-control" name="username" placeholder="Usuário" autofocus value="<?php echo Input::get('username'); ?>">
		            <br>
		            <input type="password" name="password" class="form-control" placeholder="Senha">
		            <label class="checkbox">
		                <span class="pull-right">
		                    <a data-toggle="modal" href="login.html#myModal"> Esqueceu a senha?</a>
		
		                </span>
		            </label>
		            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
		            <button class="btn btn-theme btn-block" href="index.html" type="submit"><i class="fa fa-lock"></i> ENTRAR</button>
		            <hr>
		            
		            <!-- <div class="login-social-link centered">
		            <p>or you can sign in via your social network</p>
		                <button class="btn btn-facebook" type="submit"><i class="fa fa-facebook"></i> Facebook</button>
		                <button class="btn btn-twitter" type="submit"><i class="fa fa-twitter"></i> Twitter</button>
		            </div> -->
		            <div class="registration">
		                Ainda não tem conta? Peça agora mesmo.<br/>
		                <a class="" href="#">
		                    Ataque Propaganda
		                </a>
		            </div>
		
		        </div>
		
		          <!-- Modal -->
		          <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
		              <div class="modal-dialog">
		                  <div class="modal-content">
		                      <div class="modal-header">
		                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		                          <h4 class="modal-title">Forgot Password ?</h4>
		                      </div>
		                      <div class="modal-body">
		                          <p>Enter your e-mail address below to reset your password.</p>
		                          <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">
		
		                      </div>
		                      <div class="modal-footer">
		                          <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
		                          <button class="btn btn-theme" type="button">Submit</button>
		                      </div>
		                  </div>
		              </div>
		          </div>
		          <!-- modal -->
		
		      </form>	  	
	  	
	  	</div>
	  </div>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!--BACKSTRETCH-->
    <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
    <script type="text/javascript" src="assets/js/jquery.backstretch.min.js"></script>
    <script>
        $.backstretch("assets/img/login-bg.jpg", {speed: 500});
    </script>


  </body>
</html>
