<?php
/**
 * Created by PhpStorm.
 * User: Francesco
 * Date: 22/03/2017
 * Time: 23:22
 * Thanks to http://requiremind.com/a-most-simple-php-mvc-beginners-tutorial/
 */

 /**
 * Calls the proper controller and executing the selected action
 * @param string $controller
 * @param string $action
 **/
  function call($controller = 'pages', $action = 'home') {
      // require the file that matches the controller name
      require_once('application/controllers/' . $controller . '_controller.php');

      // create a new instance of the needed controller
      switch($controller) {
          case 'pages':
              $controller = new PagesController();
              break;
      }

      // call the action
      $controller->{ $action }();
  }

  // allowed controllers
  $controllers = array('pages' => ['home', 'error']);

  // check that the requested controller and action are both allowed
  if (array_key_exists($controller, $controllers)) {
      if (in_array($action, $controllers[$controller])) {
          call($controller, $action);
      } else {
          call('pages', 'error');
      }
  } else {
      call('pages', 'error');
  }