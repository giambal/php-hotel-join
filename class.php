
 <?php

  include "databaseInfo.php";

  class Prenotazione {

    private $id;
    private $stanza_id;
    private $configurazione_id;
    private $create_at;

    public function __construct($id, $stanza_id, $configurazione_id, $create_at) {

      $this->id = $id;
      $this->stanza_id = $stanza_id;
      $this->configurazione_id = $configurazione_id;
      $this->create_at = $create_at;
    }

    public function getId() {

      return $this->id;
    }
    public function getStanzaId() {

      return $this->stanza_id;
    }
    public function getConfigurazioneId() {

      return $this->configurazione_id;
    }

    public static function getAllPrenotazioni($conn) {

      $sql = "

        SELECT *
        FROM prenotazioni

      ";

      $result = $conn->query($sql);

      // var_dump($sql); die();

      if ($result->num_rows > 0) {
        $prenotazioni = [];
        while($row = $result->fetch_assoc()) {
          $prenotazioni[] =
              new Prenotazione($row["id"],
                               $row["stanza_id"],
                               $row["configurazione_id"],
                               $row["created_at"]);
        }
      }

      return $prenotazioni;
    }
  }
  
  class Stanza {

    private $id;
    private $room_number;
    private $floor;
    private $beds;

    function __construct($id, $room_number, $floor, $beds) {

      $this->id = $id;
      $this->room_number = $room_number;
      $this->floor = $floor;
      $this->beds = $beds;
    }

    function getStanzAId() {
      return $this->id;
    }

    function getRoomNumber() {

      return $this->room_number;
    }

    function getFloor() {

      return $this->floor;
    }

    function getBeds() {
      return $this->beds;
    }

    public static function getStanzaById($conn, $id) {

      $sql = "

        SELECT *
        FROM stanze
        WHERE id = $id

      ";

      $result = $conn->query($sql);

      // var_dump($sql); die();

      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stanza = new Stanza(
                      $row["id"],
                      $row["room_number"],
                      $row["floor"],
                      $row["beds"]);

        return $stanza;
      }
    }
  }

  class Configurazione{

    private $id;
    private $title;
    private $description;

    function __construct($id,$title,$description){

      $this->id=$id;
      $this->title=$title;
      $this->description=$description;

    }

    function getId(){

      return $this->id;
    }
    function getTitle() {

      return $this->title;
    }
    function getDesc() {

      return $this->description;
    }

    public static function getConfigurazioneById($conn, $id) {

        $sql = "

          SELECT *
          FROM configurazioni
          WHERE id = $id

        ";

          $result = $conn->query($sql);

          // var_dump($sql); die();

          if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $configurazione = new Configurazione(
                                                $row["id"],
                                                $row["title"],
                                                $row["description"]);

            return $configurazione;
          }
        }
    }

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_errno) {

    echo $conn->connect_error;
    return;
  }

  // var_dump($conn); die();

  $prenotazioni = Prenotazione::getAllPrenotazioni($conn);

  foreach ($prenotazioni as $prenotazione) {

    $stanza_id = $prenotazione->getStanzaId();
    $stanza = Stanza::getStanzaById($conn, $stanza_id);

    $configurazione_id = $prenotazione->getConfigurazioneId();
    $configurazione = Configurazione::getConfigurazioneById($conn,$configurazione_id);

    echo "prenotazione id: " . $prenotazione->getId() . "<br>" .
          "-Stanza: " . $stanza->getStanzAId() . " ; number: " . $stanza->getRoomNumber() . " ; floor: " . $stanza->getFloor() .  " ; Beds: " . $stanza->getBeds() . "<br>" .
          "-Configurazione: " . $prenotazione->getConfigurazioneId() . " ; " . $configurazione->getTitle() . " ; " . $configurazione->getDesc() .
          "<br><br>";
  }

 ?>
