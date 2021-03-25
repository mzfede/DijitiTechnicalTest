<?php
  // src/Controller/LuckyController.php
  namespace App\Controller;
  use PDO;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  //use Symfony\Component\Routing\Annotation\Route;
  //require("dbConnect.php");
    try {
        $hostname = "127.0.0.1";
        $dbname = "dijiti";
        $user = "root";
        $pass = "root";
        $db = new PDO ("mysql:host=$hostname;dbname=$dbname", $user, $pass);
    } catch (PDOException $e) {
        echo "Errore: " . $e->getMessage();
    die();
    }
    $res = $db->query("SELECT * FROM user");
    $rows = $res->fetchAll(PDO::FETCH_ASSOC);

  class DijitiController extends AbstractController
  {
      public function number($rows): Response
      {
        print json_encode($rows);
          $number = random_int(0, 100);

          return $this -> render('number.html.twig',[
            'number' => $number,
          ]);
      }

      public function text(): Response
      {

          return $this -> render('text.html.twig',[
            'elem' => $elem
          ]);
      }
  }
?>
