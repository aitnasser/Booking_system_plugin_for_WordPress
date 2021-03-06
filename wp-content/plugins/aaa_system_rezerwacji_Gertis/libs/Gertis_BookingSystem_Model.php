<?php


class Gertis_BookingSystem_Model{

    private $table_guest = 'gertis_booking_system_guest';
    private $table_event = 'gertis_booking_system_event';
    private $table_email = 'gertis_booking_system_email';
    private $wpdb;


    function __construct(){
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    function getTableNameGuest(){
        return $this->wpdb->prefix.$this->table_guest;
    }

    function getTableNameEvent(){
        return $this->wpdb->prefix.$this->table_event;
    }

    function getTableNameEmail(){
        return $this->wpdb->prefix.$this->table_email;
    }

    function createDbTableGuest(){

        $table_name = $this->getTableNameGuest();

        $sql = '
            CREATE TABLE IF NOT EXISTS '.$table_name.'(
                id INT NOT NULL AUTO_INCREMENT,
                event_turn VARCHAR(20) NOT NULL,
                guest_name VARCHAR(255) NOT NULL,
                guest_surname VARCHAR(255) NOT NULL,
                birth_date DATE NOT NULL,
                email VARCHAR(255) NOT NULL,
                phone BIGINT NOT NULL,
    			personal_no VARCHAR(255) NOT NULL,
                city VARCHAR(255) NOT NULL,
                street VARCHAR(255) NOT NULL,
                zip_code VARCHAR(20) NOT NULL,
                from_who VARCHAR(255) DEFAULT NULL,
    			more_info TEXT DEFAULT NULL,
    			staff_info TEXT DEFAULT NULL,
                money INT DEFAULT NULL,
                status enum("waiting", "confirm", "resign", "old", "advance", "paid", "send") NOT NULL DEFAULT "waiting",
                register_date DATE NOT NULL,
                PRIMARY KEY(id)
            )ENGINE=InnoDB DEFAULT CHARSET=utf8';

        require_once ABSPATH.'wp-admin/includes/upgrade.php';

        dbDelta($sql);
    }


    function createDbTableEvent(){

        $table_name = $this->getTableNameEvent();

        $sql = '
            CREATE TABLE IF NOT EXISTS '.$table_name.'(
                id INT NOT NULL AUTO_INCREMENT,
                event_code VARCHAR(20) NOT NULL,
                event_turn VARCHAR(20) NOT NULL,
                start_date DATE NOT NULL,
                end_date DATE NOT NULL,
                price INT NOT NULL,
                seat_no INT NOT NULL,
                status enum("yes", "no", "old") NOT NULL DEFAULT "yes",
                PRIMARY KEY(id)
            )ENGINE=InnoDB DEFAULT CHARSET=utf8';

        require_once ABSPATH.'wp-admin/includes/upgrade.php';

        dbDelta($sql);
    }

    function createDbTableEmail(){

        $table_name = $this->getTableNameEmail();

        $sql = '
            CREATE TABLE IF NOT EXISTS '.$table_name.'(
                id INT NOT NULL AUTO_INCREMENT,
                event_code VARCHAR(20) NOT NULL,
                register_mail TEXT NOT NULL,
                confirm_mail TEXT NOT NULL,
                advance_mail TEXT NOT NULL,
                paid_mail TEXT NOT NULL,
                cancel_mail TEXT NOT NULL,
                PRIMARY KEY(id)
            )ENGINE=InnoDB DEFAULT CHARSET=utf8';

        require_once ABSPATH.'wp-admin/includes/upgrade.php';

        dbDelta($sql);
    }

    //Zapisanie danych z formularza o rejsach do bazy danych
    function saveEventEntry(Gertis_EventEntry $EventEntry){

        $toSave = array(
            'event_code' => $EventEntry->getField('event_code'),
            'event_turn' => $EventEntry->getField('event_turn'),
            'start_date' => $EventEntry->getField('start_date'),
            'end_date' => $EventEntry->getField('end_date'),
            'price' => $EventEntry->getField('price'),
            'seat_no' => $EventEntry->getField('seat_no'),
            'status' => $EventEntry->getField('status'),
        );

        $maps = array('%s', '%s', '%s', '%s', '%d', '%d', '%s');

        $table_name = $this->getTableNameEvent();

        if($EventEntry->hasId()){

            if($this->wpdb->update($table_name, $toSave, array('id' => $EventEntry->getField('id')), $maps, '%d')){
                return $EventEntry->getField('id');
            }
            else{
                return FALSE;
            }
        }
        else{

            if($this->wpdb->insert($table_name, $toSave, $maps)){
                return $this->wpdb->insert_id;
            }
            else{
                return FALSE;
            }
        }
    }

    //Zapisanie danych z formularza o rejsach do bazy danych
    function saveGuestEntry(Gertis_GuestEntry $GuestEntry){

        $toSave = array(
            'event_turn' => $GuestEntry->getField('event_turn'),
            'guest_name' => $GuestEntry->getField('guest_name'),
            'guest_surname' => $GuestEntry->getField('guest_surname'),
            'birth_date' => $GuestEntry->getField('birth_date'),
            'email' => $GuestEntry->getField('email'),
            'phone' => $GuestEntry->getField('phone'),
            'personal_no' => $GuestEntry->getField('personal_no'),
            'city' => $GuestEntry->getField('city'),
            'street' => $GuestEntry->getField('street'),
            'zip_code' => $GuestEntry->getField('zip_code'),
            'from_who' => $GuestEntry->getField('from_who'),
            'more_info' => $GuestEntry->getField('more_info'),
            'staff_info' => $GuestEntry->getField('staff_info'),
            'money' => $GuestEntry->getField('money'),
            'status' => $GuestEntry->getField('status'),
            'register_date' => $GuestEntry->getField('register_date'),
        );


        $maps = array('%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s');

        $table_name = $this->getTableNameGuest();

        if($GuestEntry->hasId()){

            if($this->wpdb->update($table_name, $toSave, array('id' => $GuestEntry->getField('id')), $maps, '%d')){
                return $GuestEntry->getField('id');
            }
            else{
                return FALSE;
            }
        }
        else{

            $toSave['register_date'] = date("Y-m-d");

            if($this->wpdb->insert($table_name, $toSave, $maps)){
                return $this->wpdb->insert_id;
            }
            else{
                return FALSE;
            }
        }
    }

    function saveEmailEntry(Gertis_EmailEntry $EmailEntry){

        $toSave = array(
            'event_code' => $EmailEntry->getField('event_code'),
            'register_mail' => $EmailEntry->getField('register_mail'),
            'confirm_mail' => $EmailEntry->getField('confirm_mail'),
            'advance_mail' => $EmailEntry->getField('advance_mail'),
            'paid_mail' => $EmailEntry->getField('paid_mail'),
            'cancel_mail' => $EmailEntry->getField('cancel_mail'),
        );

        $maps = array('%s', '%s', '%s', '%s', '%s', '%s');

        $table_name = $this->getTableNameEmail();

        if($EmailEntry->hasId()){

            if($this->wpdb->update($table_name, $toSave, array('id' => $EmailEntry->getField('id')), $maps, '%d')){
                return $EmailEntry->getField('id');
            }
            else{
                return FALSE;
            }
        }
        else{

            if($this->wpdb->insert($table_name, $toSave, $maps)){
                return $this->wpdb->insert_id;
            }
            else{
                return FALSE;
            }
        }
    }

    //Pobiera oraz zwraca wydarzenie o konkretnym id
    function fetchEventRow($id){
        $table_name = $this->getTableNameEvent();
        $sql = "SELECT * FROM {$table_name} WHERE id = %d";
        $prep = $this->wpdb->prepare($sql, $id);
        return $this->wpdb->get_row($prep);
    }

    //Pobiera oraz zwraca uczestnika (cały wiersz) o konkretnym id
    function fetchGuestRow($id){
        $table_name = $this->getTableNameGuest();
        $sql = "SELECT * FROM {$table_name} WHERE id = %d";
        $prep = $this->wpdb->prepare($sql, $id);
        return $this->wpdb->get_row($prep);
    }

    //Pobiera oraz zwraca mail (cały wiersz) o konkretnym id
    function fetchEmailRow($id){
        $table_name = $this->getTableNameEmail();
        $sql = "SELECT * FROM {$table_name} WHERE id = %d";
        $prep = $this->wpdb->prepare($sql, $id);
        return $this->wpdb->get_row($prep);
    }


    function getEventPagination($curr_page, $limit = 10, $order_by = 'id', $order_dir = 'asc', $event_code='', $search='', $archive = false){

        $curr_page = (int)$curr_page;
        if($curr_page < 1){
            $curr_page = 1;
        }

        $limit = (int)$limit;
        $order_by_opts = static::getEventOrderByOpts();
        $order_by = in_array($order_by, $order_by_opts) ? $order_by : 'id';
        $order_dir = in_array($order_dir, array('asc', 'desc')) ? $order_dir : 'asc';
        $offset = ($curr_page-1)*$limit;

        $table_name = $this->getTableNameEvent();

        //Sprawdzenie czy mają być wydarzenia archiwalne
        if($archive){
            $status1 = 'old';
            $status2 = '';
            $status3 = '';
        }
        //czy być wydarzenia aktualne
        else{
            $status1 = 'yes';
            $status2 = 'no';
            $status3 = '';
        }

        //Sprawdzenie czy mają być wyciągane wszystkie wydarzenia czy z konkretnego event_turn lub dla wyszukiwarki
        if($event_code != ''){
            $sql = "SELECT * FROM {$table_name} WHERE event_code = '{$event_code}' AND status IN ('{$status1}', '{$status2}', '{$status3}') ORDER BY {$order_by} {$order_dir} LIMIT {$offset}, {$limit}";
            $count_sql = "SELECT COUNT(*) FROM {$table_name} WHERE event_code = '{$event_code}' AND status IN ('{$status1}', '{$status2}', '{$status3}')";
        }
        else if($search != ''){
            $sql = "SELECT * FROM {$table_name} WHERE status IN ('{$status1}', '{$status2}', '{$status3}') 
              AND (event_code LIKE '%{$search}%' OR event_turn LIKE '%{$search}%')
              ORDER BY {$order_by} {$order_dir} LIMIT {$offset}, {$limit}";

            $count_sql = "SELECT COUNT(*) FROM {$table_name} WHERE status IN ('{$status1}', '{$status2}', '{$status3}') 
              AND (event_code LIKE '%{$search}%' OR event_turn LIKE '%{$search}%')";
        }
        else{
            $sql = "SELECT * FROM {$table_name} WHERE status IN ('{$status1}', '{$status2}', '{$status3}') ORDER BY {$order_by} {$order_dir} LIMIT {$offset}, {$limit}";
            $count_sql = "SELECT COUNT(*) FROM {$table_name} WHERE status IN ('{$status1}', '{$status2}', '{$status3}')";
        }

        $total_count = $this->wpdb->get_var($count_sql);
        $last_page = ceil($total_count/$limit);

        $event_list = $this->wpdb->get_results($sql, ARRAY_A);
        //$event_list = $this->wpdb->get_results($sql);

        //Dodanie zajętych miejsc to listy wydarzeń
        foreach ($event_list as $key => $val){
            $event_list[$key]['taken_seats'] = $this->countTakenSeats($event_list[$key]['event_turn']);
        }

        $Pagination = new Gertis_Pagination($event_list, $order_by, $order_dir, $limit, $total_count, $curr_page, $last_page);

        return $Pagination;
    }


    function getGuestPagination($curr_page, $limit = 10, $order_by = 'id', $order_dir = 'desc', $event_turn = '', $search = ''){

        $curr_page = (int)$curr_page;
        if($curr_page < 1){
            $curr_page = 1;
        }

        $limit = (int)$limit;

        $order_by_opts = static::getGuestOrderByOpts();
        $order_by = in_array($order_by, $order_by_opts) ? $order_by : 'id';

//        $filter_opts = static::getEventForFilter();
//        $filter = in_array($filter, $filter_opts) ? $order_by : 'all';

        $order_dir = in_array($order_dir, array('asc', 'desc')) ? $order_dir : 'desc';
        $offset = ($curr_page-1)*$limit;

        $table_name = $this->getTableNameGuest();

        if($event_turn != ''){
            $sql = "SELECT * FROM {$table_name} 
            WHERE event_turn = '{$event_turn}' 
            AND status IN (\"waiting\", \"confirm\", \"advance\", \"paid\", \"send\") 
            ORDER BY {$order_by} {$order_dir} LIMIT {$offset}, {$limit}";

            $count_sql = "SELECT COUNT(*) FROM {$table_name} 
            WHERE event_turn = '{$event_turn}' 
            AND status IN (\"waiting\", \"confirm\", \"advance\", \"paid\", \"send\")";
        }
        else if($search != ''){
            $sql = "SELECT * FROM {$table_name} 
              WHERE guest_name LIKE '%{$search}%' 
              OR guest_surname LIKE '%{$search}%' 
              OR email LIKE '%{$search}%'
              OR personal_no LIKE '%{$search}%'
              OR event_turn LIKE '%{$search}%' 
              ORDER BY {$order_by} {$order_dir} LIMIT {$offset}, {$limit}";

            $count_sql = "SELECT COUNT(*) FROM {$table_name} 
              WHERE guest_name LIKE '%{$search}%' 
              OR guest_surname LIKE '%{$search}%' 
              OR email LIKE '%{$search}%'
              OR personal_no LIKE '%{$search}%'
              OR event_turn LIKE '%{$search}%'";
        }
        else{
            $sql = "SELECT * FROM {$table_name} ORDER BY {$order_by} {$order_dir} LIMIT {$offset}, {$limit}";
            $count_sql = "SELECT COUNT(*) FROM {$table_name}";
        }


        $total_count = $this->wpdb->get_var($count_sql);
        $last_page = ceil($total_count/$limit);

        //$event_list = $this->wpdb->get_results($sql, ARRAY_A);
        $guest_list = $this->wpdb->get_results($sql);

        $Pagination = new Gertis_Pagination($guest_list, $order_by, $order_dir, $limit, $total_count, $curr_page, $last_page);

        return $Pagination;
    }

    function getEmailPagination($curr_page, $limit = 10, $order_by = 'id', $order_dir = 'asc'){

        $curr_page = (int)$curr_page;
        if($curr_page < 1){
            $curr_page = 1;
        }

        $limit = (int)$limit;

        $order_by_opts = static::getEmailsOrderByOpts();
        $order_by = in_array($order_by, $order_by_opts) ? $order_by : 'id';

        $order_dir = in_array($order_dir, array('asc', 'desc')) ? $order_dir : 'asc';

        $offset = ($curr_page-1)*$limit;

        $table_name = $this->getTableNameEmail();

        $count_sql = "SELECT COUNT(*) FROM {$table_name}";
        $total_count = $this->wpdb->get_var($count_sql);

        $last_page = ceil($total_count/$limit);

        $sql = "SELECT * FROM {$table_name} ORDER BY {$order_by} {$order_dir} LIMIT {$offset}, {$limit}";


        $email_list = $this->wpdb->get_results($sql);

        $Pagination = new Gertis_Pagination($email_list, $order_by, $order_dir, $limit, $total_count, $curr_page, $last_page);

        return $Pagination;
    }

    static function getEventOrderByOpts(){
        return array(
            'ID' => 'id',
            'Kod wydarzenia' => 'event_code',
            'Turnus' => 'event_turn',
            'Cena' => 'price',
            'Liczba miejsc' => 'seat_no',
            'Status' => 'status'
        );
    }

    static function getGuestOrderByOpts(){
        return array(
//            'ID' => 'id',
            'Data rejestracji' => 'register_date',
            'Kod imprezy [turnus]' => 'event_turn',
            'Imie' => 'guest_name',
            'Nazwisko' => 'guest_surname',
            'Status' => 'status'
        );
    }

    static function getEmailsOrderByOpts(){
        return array(
            'ID' => 'id',
            'Kod imprezy' => 'event_code',
        );
    }

    //Usuwa rekord z bazy danych o id = $id z tabeli $table (event/guest)
    function deleteRow($id, $table){
        $id = (int)$id;

        if($table == 'event'){
            $table_name = $this->getTableNameEvent();
        }
        elseif ($table == 'guest'){
            $table_name = $this->getTableNameGuest();
        }
        elseif ($table == 'email'){
            $table_name = $this->getTableNameEmail();
        }

        $sql = "DELETE FROM {$table_name} WHERE id = %d";
        $prep = $this->wpdb->prepare($sql, $id);

        return $this->wpdb->query($prep);
    }

    //Masowe usuwanie zaznaczonych id z tabeli $table (event/guest)
    function bulkDelete(array $ids_list, $table){
        $ids_list = array_map('intval', $ids_list);

        if($table == 'event'){
            $table_name = $this->getTableNameEvent();
        }
        elseif ($table == 'guest'){
            $table_name = $this->getTableNameGuest();
        }
        elseif ($table == 'email'){
            $table_name = $this->getTableNameEmail();
        }

        $ids_str = implode(',', $ids_list);
        $sql = "DELETE FROM {$table_name} WHERE id IN ({$ids_str})";
        return $this->wpdb->query($sql);
    }

    //Masowa zmiana statusu
    function bulkChangeStatus(array $ids_list, $change_to, $table){
        $ids_list = array_map('intval', $ids_list);

        if($table == 'event'){
            $table_name = $this->getTableNameEvent();
        }
        elseif ($table == 'guest'){
            $table_name = $this->getTableNameGuest();
        }

        $status = '';
        switch($change_to){
            default:
            case 'actual': $status = 'yes'; break;
            case 'no_actual': $status = 'no'; break;
            case 'resign': $status = 'resign'; break;
            case 'old': $status = 'old'; break;
            case 'waiting': $status = 'waiting'; break;
        }

        $ids_str = implode(',', $ids_list);

        $sql = "UPDATE {$table_name} SET status = '{$status}' WHERE id IN ({$ids_str})";
        return $this->wpdb->query($sql);
    }

    //Zmiana statusu  uczestnictwa na potwierdzony
    function confirmGuest($id){
        $id = (int)$id;
        $table_name = $this->getTableNameGuest();

        $sql = "UPDATE {$table_name} SET status = 'confirm' WHERE id = %d";
        $prep = $this->wpdb->prepare($sql, $id);

        return $this->wpdb->query($prep);
    }

    //Zmiana statusu  uczestnictwa na zapłacono zaliczkę
    function advanceGuest($id){
        $id = (int)$id;
        $table_name = $this->getTableNameGuest();

        $sql = "UPDATE {$table_name} SET status = 'advance' WHERE id = %d";
        $prep = $this->wpdb->prepare($sql, $id);

        return $this->wpdb->query($prep);
    }

    //Zmiana statusu  uczestnictwa na zapłcono całość
    function paidGuest($id){
        $id = (int)$id;
        $table_name = $this->getTableNameGuest();

        $sql = "UPDATE {$table_name} SET status = 'paid' WHERE id = %d";
        $prep = $this->wpdb->prepare($sql, $id);

        return $this->wpdb->query($prep);
    }

    //Zmiana statusu  uczestnictwa na anulowano (zrezygnował)
    function cancelGuest($id){
        $id = (int)$id;
        $table_name = $this->getTableNameGuest();

        $sql = "UPDATE {$table_name} SET status = 'resign' WHERE id = %d";
        $prep = $this->wpdb->prepare($sql, $id);

        return $this->wpdb->query($prep);
    }

    //Zmiana statusu  uczestnictwa na  przesłany dalej
    function sendGuest($id){
        $id = (int)$id;
        $table_name = $this->getTableNameGuest();

        $sql = "UPDATE {$table_name} SET status = 'send' WHERE id = %d";
        $prep = $this->wpdb->prepare($sql, $id);

        return $this->wpdb->query($prep);
    }


    //zwraca liczbę zajętych miejsc na danym turnusie
    function countTakenSeats($event_turn){

        $table_name = $this->getTableNameGuest();
        $sql = 'SELECT COUNT(event_turn) FROM '.$table_name.' WHERE event_turn="'.$event_turn.'" AND status IN ("waiting", "confirm", "advance", "paid")';
        return $this->wpdb->get_var($sql);
    }

    //Zwraca listę z turnusami o podanym kodzie wydarzenia
    function getEventTurn($event_code, $column_name='*'){

        //SELECT * FROM `wp_gertis_booking_system_event` WHERE `event_code` = 'OPT' AND `status` = 'yes' ORDER BY event_turn DESC
        $table_name = $this->getTableNameEvent();

        $sql = 'SELECT '.$column_name.' FROM '.$table_name.' WHERE event_code="'.$event_code.'" AND status = "yes" ORDER BY event_turn ASC';
        $event_list = $this->wpdb->get_results($sql, ARRAY_A);

        if($column_name=='*'){
            //Dodanie zajętych miejsc to listy wydarzeń
            foreach ($event_list as $key => $val){
                $event_list[$key]['taken_seats'] = $this->countTakenSeats($event_list[$key]['event_turn']);
            }
        }

        return $event_list;
    }

    //Zwraca id turnusu o okreśolonym $event_turn
    function getEventTurnId($event_turn){

        $table_name = $this->getTableNameEvent();
        $sql = 'SELECT id FROM '.$table_name.' WHERE event_turn="'.$event_turn.'"';
        return $this->wpdb->get_var($sql);

    }

    //Zwraca listę z wszystkimi kodami wydarzeń
    function getEventCodeList(){

        $table_name = $this->getTableNameEvent();
        $sql = 'SELECT DISTINCT event_code FROM '.$table_name.' WHERE status != "old"' ;
        $event_list = $this->wpdb->get_results($sql, ARRAY_A);

        return $event_list;
        
    }

    //Funkcja zwraca wszystkie dostępne terminy
    function getAllPossibleEventTurns(){

        $table_name = $this->getTableNameEvent();
        $sql = 'SELECT event_turn, start_date, end_date FROM '.$table_name.' WHERE status = "yes" ORDER BY event_turn ASC';
        $event_list = $this->wpdb->get_results($sql, ARRAY_A);
        $check_event_list = array();

        foreach ($event_list as $key => $val){
             if($this->checkEventTurn($event_list[$key]['event_turn'])){
                 array_push($check_event_list, $event_list[$key]);
             }
        }

        return $check_event_list;

    }

    //Sprawdza czy na podany event_turn można się rejestrować. True - można, false - niemożna
    function checkEventTurn($event_turn){

        $table_name = $this->getTableNameEvent();
        $sql = 'SELECT * FROM '.$table_name.' WHERE event_turn="'.$event_turn.'" AND status = "yes"';
        $result_row = $this->wpdb->get_row($sql, ARRAY_A);

        if(!empty($result_row)){
            $free_seats = ((int)$result_row['seat_no'] - (int)$this->countTakenSeats($event_turn));
            if($free_seats > 0){
                return TRUE;
            }
            else{
                return FALSE;
            }
        }
        else{
            return FALSE;
        }
    }

    //Zwraca przedział daty konkretnego wydarzenia
    public function getEventDate($event_turn){

        $table_name = $this->getTableNameEvent();
        $sql = 'SELECT * FROM '.$table_name.' WHERE event_turn="'.$event_turn.'" ';
        $result_row = $this->wpdb->get_row($sql, ARRAY_A);

        if(!empty($result_row)){

            $start_date = date_create($result_row['start_date']);
            $end_date = date_create($result_row['end_date']);

            $event_date = date_format($start_date, 'd-m-Y').' - '.date_format($end_date, 'd-m-Y');

            return $event_date;
        }
        else{
            return FALSE;
        }
    }

    //Zwraca kod imrezy dla określonego turnusu wydarzenia
     private function getEventCode($event_turn){

        $table_name = $this->getTableNameEvent();
        $sql = 'SELECT event_code FROM '.$table_name.' WHERE event_turn="'.$event_turn.'" ';
        return $this->wpdb->get_var($sql);
    }

    //Zwraca szablon maila po rejestracji. Gdy nie ma maila to ładuje szablon domyślny
    function getRegisterMail($event_turn){
        $table_name = $this->getTableNameEmail();
        $event_code = $this->getEventCode($event_turn);

        $sql = 'SELECT register_mail FROM '.$table_name.' WHERE event_code="'.$event_code.'" ';

        if($this->wpdb->get_var($sql) != NULL){
            return $this->wpdb->get_var($sql);
        }
        else{
            $sql = 'SELECT register_mail FROM '.$table_name.' WHERE event_code="DOMYSLNY" ';
            return $this->wpdb->get_var($sql);
        }
    }

    //Zwraca szablon maila po akcji potwierdzenia. Gdy nie ma maila to ładuje szablon domyślny
    function getConfirmMail($event_turn){
        $table_name = $this->getTableNameEmail();
        $event_code = $this->getEventCode($event_turn);

        $sql = 'SELECT confirm_mail FROM '.$table_name.' WHERE event_code="'.$event_code.'" ';

        if($this->wpdb->get_var($sql) != NULL){
            return $this->wpdb->get_var($sql);
        }
        else{
            $sql = 'SELECT confirm_mail FROM '.$table_name.' WHERE event_code="DOMYSLNY" ';
            return $this->wpdb->get_var($sql);
        }
    }

    //Zwraca szablon maila po akcji zapłacono zaliczkę. Gdy nie ma maila to ładuje szablon domyślny
    function getAdvanceMail($event_turn){
        $table_name = $this->getTableNameEmail();
        $event_code = $this->getEventCode($event_turn);

        $sql = 'SELECT advance_mail FROM '.$table_name.' WHERE event_code="'.$event_code.'" ';

        if($this->wpdb->get_var($sql) != NULL){
            return $this->wpdb->get_var($sql);
        }
        else{
            $sql = 'SELECT advance_mail FROM '.$table_name.' WHERE event_code="DOMYSLNY" ';
            return $this->wpdb->get_var($sql);
        }
    }

    //Zwraca szablon maila po akcji zapłacono całość. Gdy nie ma maila to ładuje szablon domyślny
    function getPaidMail($event_turn){
        $table_name = $this->getTableNameEmail();
        $event_code = $this->getEventCode($event_turn);

        $sql = 'SELECT paid_mail FROM '.$table_name.' WHERE event_code="'.$event_code.'" ';

        if($this->wpdb->get_var($sql) != NULL){
            return $this->wpdb->get_var($sql);
        }
        else{
            $sql = 'SELECT paid_mail FROM '.$table_name.' WHERE event_code="DOMYSLNY" ';
            return $this->wpdb->get_var($sql);
        }
    }

    //Zwraca szablon maila po akcji anuluj. Gdy nie ma maila to ładuje szablon domyślny
    function getCancelMail($event_turn){
        $table_name = $this->getTableNameEmail();
        $event_code = $this->getEventCode($event_turn);

        $sql = 'SELECT cancel_mail FROM '.$table_name.' WHERE event_code="'.$event_code.'" ';

        if($this->wpdb->get_var($sql) != NULL){
            return $this->wpdb->get_var($sql);
        }
        else{
            $sql = 'SELECT cancel_mail FROM '.$table_name.' WHERE event_code="DOMYSLNY" ';
            return $this->wpdb->get_var($sql);
        }
    }

    //Zwraca listę maili uczestników konkretnej imprezy
    function getGuestEmials($event_turn){

        $table_name = $this->getTableNameGuest();
        $sql = 'SELECT email FROM '.$table_name.' WHERE event_turn="'.$event_turn.'" AND status IN ("waiting", "confirm", "advance", "paid") ';
        $result = $this->wpdb->get_results($sql, ARRAY_A);

        return $result;
    }


    //Zmienia kod turnusu dla uczestników ze starym/nieaktualnym kodem turnusu
    public function changeEventCodeforMembers($oldEventCode, $newEventCode){

        $table_name = $this->getTableNameGuest();
        $sql = "UPDATE {$table_name} SET event_turn = %s WHERE event_turn = %s";
        $prep = $this->wpdb->prepare($sql, $newEventCode, $oldEventCode);

        return $this->wpdb->query($prep);
    }


    //Zmienia status na zakończony dla wszystkich wydarzeń które data startu jest starsza niż dzień dzisiejszy (wykorzystanie CRON)
    public function changeOldEventsStatus(){

        $table_name = $this->getTableNameEvent();
        $sql = "UPDATE {$table_name} SET status = 'old' WHERE end_date < CURRENT_DATE()";

        return $this->wpdb->query($sql);

    }


}
