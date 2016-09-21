<?php require_once 'core/init.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
    

    <?php require_once ("includes/head.php"); ?>

  <body>
<?php

  require_once("bd/connect.php");
  require_once("includes/session_existing.php");

  $pdo = Database::conexao();
  $user = new User();


$errors = null;
if (Input::exists()) { // check if there was anything posted
    if(Token::check(Input::get('token'))) { // check if token was valid to prevent CSRF attack
        $validate = new Validate(); // validation
        $validation = $validate->check($_POST, array(
            'name' => array(
                'name' => 'Name',
                'required' => true,
                'min' => 2,
                'max' => 50
            ),
            'email' => array(
                'name' => 'Email',
                'required' => true,
                'min' => 10,
                'max' => 50
            ),
            'username' => array(
                'name' => 'Username',
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'users'
            ),
            'password' => array(
                'name' => 'Password',
                'required' => true,
                'min' => 6
            ),
            'password_confirmation' => array(
                'required' => true,
                'matches' => 'password'
            ),
        ));

        if ($validate->passed()) { // if validation succeeded
            $user = new User();

            if (!$user->isAlreadyRegistered(Input::get('email'))) {
                $salt = Hash::salt(32); // create a unique salt for password hashing

                try {
                    $user->create(array(
                        'name' => Input::get('name'),
                        'email' => Input::get('email'),
                        'username' => Input::get('username'),
                        'password' => Hash::make(Input::get('password'), $salt),
                        'salt' => $salt,
                        'joined' => date('Y-m-d H:i:s'),
                        'group' => 1
                    ));

                    // Make flash message to tell registering was success, will show up on index.php after redirect.
                    Session::flash('home', 'Pronto! agora o ' . Input::get('username') . ' já pode acessar sua conta :D');
                    Redirect::to('cadastro_usuario.php');
                } catch(Exception $e) {
                    echo $error, '<br>';
                }
            } else {
                $errors[] = "A user with this email address already exists!";
            }
        } else { // show validation errors
            $errors = $validate->errors();
        }
    }
}


  if($user->isLoggedIn()) { // verifica o login
?>
  <section id="container" >
      <!-- **********************************************************************************************************************************************************
      TOP BAR CONTENT & NOTIFICATIONS
      *********************************************************************************************************************************************************** -->
      <!--header start-->
      <?php require_once ("includes/menu-top.php"); ?>
      <!--header end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN SIDEBAR MENU
      *********************************************************************************************************************************************************** -->
      <!--sidebar start-->
      <?php require_once ("includes/menu-lateral.php"); ?>
      <!--sidebar end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper site-min-height">
          	<h3><i class="fa fa-angle-right"></i> Cadastro de usuário</h3>
          	<div class="row mt">
              <div class="col-lg-12">
              <?php
                            if ($errors != null) {
                                foreach ($errors as $error) { ?>
                                    <div class="alert alert-danger text-center">
                                        <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                                    </div>
                        <?php
                                }
                            }
                            ?>
                  <div class="form-panel">
                      <h4 class="mb"><i class="fa fa-angle-right"></i> Usuário</h4>
                      <form class="form-horizontal style-form" action="" autocomplete="on" method="post">
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Nome</label>
                              <div class="col-sm-10">
                                  <input type="text" class="form-control" id="name" name="name" required="required" value="<?php echo escape(Input::get('name')); ?>">
                              </div>
                          </div>
                          
                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Usuário</label>
                              <div class="col-sm-10">
                                  <input type="text" class="form-control" name="username" id="username" required="required" value="<?php echo escape(Input::get('username')); ?>">
                                  <span class="help-block">Coloque nesse campo o nome que vai usar para o login.</span>
                              </div>
                          </div>

                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Email</label>
                              <div class="col-sm-10">
                                  <input type="text"  class="form-control" placeholder="Ex: admin@admin.com" name="email" id="email" required="required"value="<?php echo escape(Input::get('email')); ?>">
                              </div>
                          </div>

                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Senha</label>
                              <div class="col-sm-10">
                                  <input type="password" name="password" id="password" required="required" class="form-control" placeholder="">
                              </div>
                          </div>

                          <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Confirme a senha</label>
                              <div class="col-sm-10">
                                  <input type="password" name="password_confirmation" required="required" id="password_again" class="form-control" placeholder="">
                              </div>
                          </div>
                          <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                          <div class="btn-group">
                            <input type="submit" class="btn btn-theme" value="CADASTRAR">
                          </div>    
                      </form>
                  </div>
              </div><!-- col-lg-12-->       
            </div><!-- /row -->
			
		</section><!--/wrapper -->
      </section><!-- /MAIN CONTENT -->

      <!--main content end-->
      <!--footer start-->

      <!--footer end-->
  </section>
<?php
    if($user->hasPermission('admin')) {
        echo '<p>You are a Administrator!</p>';
    }

} else { // get this when not logged in
    Redirect::to("login.php");
}
?>
    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery-ui-1.9.2.custom.min.js"></script>
    <script src="assets/js/jquery.ui.touch-punch.min.js"></script>
    <script class="include" type="text/javascript" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>
    <script src="assets/js/jquery.nicescroll.js" type="text/javascript"></script>


    <!--common script for all pages-->
    <script src="assets/js/common-scripts.js"></script>

    <!--script for this page-->
    
  <script>
      //custom select box

      $(function(){
          $('select.styled').customSelect();
      });

  </script>

  </body>
</html>
