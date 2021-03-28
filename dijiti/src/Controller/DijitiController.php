<?php

  namespace App\Controller;
  use PDO;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  //use Symfony\Component\Routing\Annotation\Route;
  //require("dbConnect.php");

  class DijitiController extends AbstractController
  {
      public function index(): Response
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
        if ($res) {
          $res = $res->fetchAll(PDO::FETCH_ASSOC);
        }
        else{  
          print_r($res);
          $res = array();
        }
        foreach ($res as $value) {
          foreach ($value as $e) {
            array_push($tmp, $e);
          }
          array_push($toSend, $tmp);
          $tmp = array();
        }
        $firstRow = array_shift($toSend);
        $db = null;
        return $this -> render('index.html.twig',[
          'tableHead' => $firstRow, 'sent' => $toSend,
        ]);
      }

      public function insert(): Response
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
        if (isset($_POST['submit']))
        {
          $nome = $_POST['nome'];
          $cognome = $_POST['cognome'];
          $email = $_POST['email'];
          $username = $_POST['username'];
          $password = $_POST['password'];
          $telefono = $_POST['telefono'];
        }
        //$res = $db->query("INSERT INTO `dijiti`.`users` (`Nome`, `Cognome`, `Email`, `Username`, `Password`, `Numero di telefono`) VALUES ('$nome', '$cognome', '$email', '$username', '$password', '$telefono')");
        $db = null;
        return $this -> render('inserito.html.twig',[
          'nome' => $nome,
          'cognome' => $cognome,
          'email' =>  $email,
          'username' =>  $username,
          'password' =>  $password,
          'telefono' =>  $telefono,
        ]);
      }
  }
?>
