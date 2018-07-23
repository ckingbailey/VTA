<?php
  $base = $_SERVER['DOCUMENT_ROOT'] . '/..';
  require "$base/vendor/autoload.php";

  session_start();
  
  // instantiate Twig
  $loader = new Twig_Loader_Filesystem("$base/templates");
  $twig = new Twig_Environment($loader,
      [
          'debug' => true
      ]
  );
  $twig->addExtension(new Twig_Extension_Debug());
  
  // base context
  $context = [
      'title' => 'Under Construction - Come back soon',
      'navbarHeading' => null,
      'navHeadingLink' => '/',
      'navItems' => [],
      'pageHeading' => '🚧 Under Construction 🚧'
  ];
  
  $twig->display('page.html', $context);
?>