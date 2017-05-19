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
  /** @noinspection PhpIncludeInspection */
  $path = ABS_PATH.'/application/controllers/' . $controller . '_controller.php';
  if (file_exists($path)) {
      require_once($path);
      $controller = new NavigationController();
      $controller->go_to($action);
  } else {
      call('pages', 'error');
  }
}

if(isset($controller) and isset($action)) {
    call($controller, $action);
} else {
    call('pages', 'error');
}