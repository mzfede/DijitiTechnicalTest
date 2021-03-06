<?php

  namespace App\Controller;
  use PDO;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\Routing\Annotation\Route;
  use Symfony\Component\Mailer\MailerInterface;
  use Symfony\Component\Mime\Email;

  class DijitiController extends AbstractController
  {
     /**
     * @var string
     */
    private $show;

    public function __construct(string $show)
    {
        $this->show = $show; // controlla il pulsante 'Carica per la prima volta', dopo la prima azione non viene più mostrato
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
        if (isset($_POST['caricaPrimaVolta'])){
          $csvFile = file('../src/Imports/users.csv');
          $data = [];
          foreach ($csvFile as $line) {
            $data[] = str_getcsv($line);
          }
          $tmp = array();
          $toSend = array();
          $firstRow = array();
         foreach ($data as $value) {
           foreach ($value as $e) {
             array_push($tmp, $e);
           }
          //gestione dubplicati
          try {
            $sql = "INSERT INTO `users` (`Nome`, `Cognome`, `Email`, `Username`, `Password`, `Numero`) VALUES (?,?,?,?,?,?)";
            $db->prepare($sql)->execute([$tmp[0], $tmp[1], $tmp[2], $tmp[3], $tmp[4], $tmp[5]]);
          }
          catch (PDOException $e)
          {
            echo "Errore: " . $e->getMessage();
          }
          $tmp = array();
         }
         $res = $db->query("SELECT * FROM users");
         $res = $res->fetchAll(PDO::FETCH_ASSOC);
         $firstRow = ['Nome', 'Cognome', 'Email', 'Username', 'Password', 'Numero'];
         $db = null;
         foreach ($res as $value) {
           foreach ($value as $e) {
             array_push($tmp, $e);
           }
           array_push($toSend, $tmp);
           $tmp = array();
         }
         $this->show = 'none';
         return $this -> render('index.html.twig',[
           'tableHead' => $firstRow, 'sent' => $toSend, 'show' => $this->show ,
         ]);
        }
        elseif (isset($_POST['orderBy'])){// caricamento ordinato in base al bottone cliccato
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
          $this->show  = 'none';
          $firstRow = ['Nome', 'Cognome', 'Email', 'Username', 'Password', 'Numero'];
          return $this -> render('index.html.twig',[
            'tableHead' => $firstRow, 'sent' => $toSend, 'show' => $this->show,
          ]);
        }
        else{
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
              'tableHead' => $firstRow, 'sent' => $toSend, 'show' => $this->show ,
            ]);
          }

      }

      public function insert(MailerInterface $mailer): Response
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
          $mail = $_POST['email'];
          $username = $_POST['username'];
          $password = $_POST['password'];
          $telefono = $_POST['telefono'];
        }
        //gestione dubplicati
        try {
          $res = $db->query("INSERT INTO `dijiti`.`users` (`Nome`, `Cognome`, `Email`, `Username`, `Password`, `Numero`) VALUES ('$nome', '$cognome', '$mail', '$username', '$password', '$telefono')");
        }
        catch (PDOException $e)
        {
          echo "Errore: " . $e->getMessage();
        }
        $db = null;
        $email = (new Email())
            ->from('from@example.com')
            ->to('626816995d-587658@inbox.mailtrap.io')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>Inserito utente</p>');
        $mailer->send($email);
        return $this -> render('inserito.html.twig',[
          'nome' => $nome,
          'cognome' => $cognome,
          'email' =>  $mail,
          'username' =>  $username,
          'telefono' =>  $telefono,
        ]);
      }

      public function delete(MailerInterface $mailer): Response
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
        $email = (new Email())
            ->from('from@example.com')
            ->to('626816995d-587658@inbox.mailtrap.io')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>Eliminato utente</p>');
        $mailer->send($email);
        return $this -> render('eliminato.html.twig',[
          'usersToDelete' => $users,
        ]);
      }
  }
?>
