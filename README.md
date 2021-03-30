# DijitiTechnicalTest
Vi spiego in breve il mio approccio a questo test. 
Partendo da una conoscenza nulla del framework, ho deciso di gestire tramite Controller (DijitiController.php) tutte le azioni che vengono svolte nella pagina.
Il path iniziale è http://localhost:8000/index , essendo svolto tutto in locale con, appunto, un server locale MAMP, il quale è controllato dalla sua pagina web di gestione.

Il db iniziale presenta il nome 'dijiti', ed è già creata la TABLE 'users', con gli attributi richiesti e la chiave username che viene usata per bloccare i duplicati.

Il controller per la pagina iniziale si suddivide tra: 
  - primo caricamento dei dati su db conseguente a click su button 'Carica Per La prima volta';
  - caricamento pagina vuota e in condizioni non conseguenti a nessuna azione;
  - caricamento in base all'ordinamento scelto (ASC), dopo aver azionato il valore secondo il quale ordinare (e.g. 'Telefono')
Nel controller, le altre due function di Response, portano a index/eliminato ed index/inserito, dove si ha un ricapitolo dell'azione eseguita.

La password è richiesta da 8 a 12 caratteri, con almeno maiuscola, minuscola e numero; il numero 10 cifre senza prefisso, e-mail con solita struttura base, il resto senza vincoli.

Requisiti eseguiti:
  - importazione utenti da file CSV riportante,senza i nomi delle
  colonne dei dati in esso contenuti come prima riga, essendo poche colonne non lo ritengo necessario per l'ottimizzazione;
  - visualizzazione della lista degli utenti paginata e con possibilità di ordinamento
  per tutti i campi (tranne password);
  - creazione di nuovi utenti;
  - cancellazione degli utenti esistenti tramite apposito flag di stato;
  - notifica, tramite email, all'espletamento delle due precedenti senza riepilogo dei dati.

Possibili migliorie, non eseguite per mancate conoscenze/tempo:
  - modifica e ricerca tra utenti;
  - ricapitolo dati nella mail;
  - gestione migliore delle pagine di reindirizzamento (index/inserito ed index/eliminato);
  - generare html direttamente nel Controller
  - parte grafica
