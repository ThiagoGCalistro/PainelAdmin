<?php
if(Session::exists('home')) {
      echo '<p>' . Session::flash('home'). '</p>';
  }
?> 