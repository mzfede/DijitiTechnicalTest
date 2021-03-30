<?php

  namespace App\Controller;
  use PDO;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\Routing\Annotation\Route;


  class DijitiController extends AbstractController
  {
    /**
     * @var bool
     */
    private $firstLoad;

    public function __construct(bool $firstLoad)
    {
        $this->first_load = true;
    }
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
          if($this->firstLoad){ // primo caricamento da csv
            print_r($this->firstLoad);
            $this->first_load = false;
            $csvFile = file('../src/Imports/users.csv');
            $data = [];
            foreach ($csvFile as $line) {
              $data[] = str_getcsv($line);
            }
            $firstLoad = false;
            $tmp = array();
            $toSend = array();
            $firstRow = array();
            foreach ($data as $value) {
              foreach ($value as $e) {
                array_push($tmp, $e);
              }
              $res = $db->query("INSERT INTO `dijiti`.`users` (`Nome`, `Cognome`, `Email`, `Username`, `Password`, `Numero`) VALUES ('$tmp[0]', '$tmp[1]', '$tmp[2]', '$tmp[3]', '$tmp[4]', '$tmp[5]')");
              $tmp = array();

            }
            $res = $db->query("SELECT * FROM users");
            $res = $res->fetchAll(PDO::FETCH_ASSOC);
            $firstRow = ['Nome', 'Cognome', 'Email', 'Username', 'Password', 'Numero'];
            return $this -> render('index.html.twig',[
              'tableHead' => $firstRow, 'sent' => $toSend,
            ]);
        }elseif (isset($_POST['orderBy'])){// caricamento ordinato in base al bottone cliccato
          $toOrder = $_POST['orderBy'];
          $tmp = array();
          $toSend = array();
          $firstRow = array();
          $res = array();
          $res = $db->query("SELECT * FROM `users` ORDER BY `users`.`$toOrder` ASC");
          $res = $res->fetchAll(PDO::FETCH_ASSOC);
          foreach ($res as $value) {
            foreach ($value as $e) {
              array_push($tmp, $e);
            }
            array_push($toSend, $tmp);
            $tmp = array();
          }
          $firstRow = ['Nome', 'Cognome', 'Email', 'Username', 'Password', 'Numero'];
          return $this -> render('index.html.twig',[
            'tableHead' => $firstRow, 'sent' => $toSend,
          ]);
        }elseif (!($this->firstLoad)) { // dal secondo caricamento in poi
          $tmp = array();
          $toSend = array();
          $firstRow = array();
          $res = $db->query("SELECT * FROM users");
          $res = $res->fetchAll(PDO::FETCH_ASSOC);
          foreach ($res as $value) {
            foreach ($value as $e) {
              array_push($tmp, $e);
            }
            array_push($toSend, $tmp);
            $tmp = array();
          }
          $firstRow = ['Nome', 'Cognome', 'Email', 'Username', 'Password', 'Numero'];
          $db = null;
          return $this -> render('index.html.twig',[
            'tableHead' => $firstRow, 'sent' => $toSend,
          ]);
        }
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
        if (isset($_POST['submitInsert']))
        {
          $nome = $_POST['nome'];
          $cognome = $_POST['cognome'];
          $email = $_POST['email'];
          $username = $_POST['username'];
          $password = $_POST['password'];
          $telefono = $_POST['telefono'];
        }
        $res = $db->query("INSERT INTO `dijiti`.`users` (`Nome`, `Cognome`, `Email`, `Username`, `Password`, `Numero`) VALUES ('$nome', '$cognome', '$email', '$username', '$password', '$telefono')");
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

      public function delete(): Response
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
        $users = array();
        if (isset($_POST['submitDelete']))
        {
          if(!empty($_POST['toDelete'])) {
            foreach($_POST['toDelete'] as $value){
              array_push($users, $value);
            }
          }
        }

        foreach ($users as $e) {
          $res = $db->query("DELETE FROM `dijiti`.`users` WHERE `Username` = '$e'");
        }
        $db = null;
        return $this -> render('eliminato.html.twig',[
          'usersToDelete' => $users,
        ]);
      }
  }
?>
