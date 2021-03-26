<?php

  namespace App\Controller;
  use PDO;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  //use Symfony\Component\Routing\Annotation\Route;
  //require("dbConnect.php");

  class DijitiController extends AbstractController
  {
      public function number(): Response
      {
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
        $tmp = array();
        $toSend = array();
        $firstRow = array();
        $res = $db->query("SELECT * FROM user");
        $rows = $res->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $value) {
          foreach ($value as $e) {
            array_push($tmp, $e);
          }
          array_push($toSend, $tmp);
          $tmp = array();
        }
        $firstRow = array_shift($toSend);

        return $this -> render('number.html.twig',[
          'tableHead' => $firstRow, 'sent' => $toSend,
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
