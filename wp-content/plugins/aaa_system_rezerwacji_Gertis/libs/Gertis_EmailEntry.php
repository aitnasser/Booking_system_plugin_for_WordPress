<?php

class Gertis_EmailEntry{

    private $id = NULL;
    private $event_code = NULL;
    private $register_mail = NULL;
    private $confirm_mail = NULL;
    private $advance_mail = NULL;
    private $paid_mail = NULL;
    private $cancel_mail = NULL;

    private $errors = array();
    private $exists = FALSE;


    function __construct($id = NULL) {
        $this->id = $id;
        $this->load();
    }


    private function load(){
        if(isset($this->id)){
            $Model = new Gertis_BookingSystem_Model();
            $row = $Model->fetchEmailRow($this->id);

            if(isset($row)){
                $this->setFields($row);
                $this->exists = TRUE;
            }
        }
    }

    function exist(){
        return $this->exists;
    }


    function getField($field){
        if(isset($this->{$field})){
            return $this->{$field};
        }

        return NULL;
    }

    function setFields($fields){
        foreach($fields as $key => $val){
            $this->{$key} = $val;
        }
    }

    function hasId(){
        return isset($this->id);
    }


    function setError($field, $error){
        $this->errors[$field] = $error;
    }

    function getError($field){
        if(isset($this->errors[$field])){
            return $this->errors[$field];
        }

        return NULL;
    }

    function hasError($field){
        return isset($this->errors[$field]);
    }

    // Return true if errors > 0
    function hasErrors(){
        return (count($this->errors) > 0);
    }


    //Funkcja walidująca poprawność pul w formularzu z mail-ami. Zwraca true jeżeli nie ma błędów
    function validate(){

        $allowed_tags = array(
            'a' => array(
                'href' => array()
            ),
            'img' => array(
                'src' => array(),
                'border' => array(),
                'alt' => array(),
                'width' => array(),
                'height' => array()
            ),
            'form' => array(
                'action' => array(),
                'method' => array(),
                'target' => array()
            ),
            'input' => array(
                'type' => array(),
                'name' => array(),
                'value' => array(),
                'src' => array(),
                'border' => array(),
                'alt' => array()
            ),
            'select' => array(
                'name' => array(),
            ),
            'option' => array(
                'value' => array(),
            ),
            'h1' => array(),
            'table' => array(),
            'tr' => array(),
            'td' => array(),
            'h2' => array(),
            'p' => array(),
            'ul' => array(),
            'li' => array(),
            'br' => array(),
            'strong' => array(),
        );

        /*
         * event_code:
         * - nie może być puste
         * - po oczyszczeniu kod nie może być dłuższy niż 20 znaków
         */

        if (empty($this->event_code)) {
            $this->setError('event_code', 'To pole nie może być puste');
        }
        else if (strlen($this->event_code) > 20) {
            $this->setError('event_code', 'To pole nie może być dłuższe niż 20 znaków.');
        }
        else{
            $this->event_code = wp_kses_data($this->event_code);
        }


        /*
         * register_mail:
         * - nie może być puste
         *- po wyczyszczeniu  nie może być dłuższy niż 50000 znaków
         */
        if (empty($this->register_mail)) {
            $this->setError('register_mail', 'To pole nie może być puste');
        }
        else if(strlen($this->register_mail) > 50000){
            $this->setError('register_mail', 'To pole nie może być dłuższe niż 50 000 znaków.');
        }
        else{
            $this->register_mail = wp_kses($this->register_mail, $allowed_tags);
        }

        /*
         * confirm_mail:
         * - nie może być puste
         *- po wyczyszczeniu  nie może być dłuższy niż 50000 znaków
         */
        if (empty($this->confirm_mail)) {
            $this->setError('confirm_mail', 'To pole nie może być puste');
        }
        else if(strlen($this->confirm_mail) > 50000){
            $this->setError('confirm_mail', 'To pole nie może być dłuższe niż 50 000 znaków.');
        }
        else{
            $this->confirm_mail = wp_kses($this->confirm_mail, $allowed_tags);

        }

        /*
         * advance_mail:
         * - nie może być puste
         *- po wyczyszczeniu  nie może być dłuższy niż 50000 znaków
         */
        if (empty($this->advance_mail)) {
            $this->setError('advance_mail', 'To pole nie może być puste');
        }
        else if(strlen($this->advance_mail) > 50000){
            $this->setError('advance_mail', 'To pole nie może być dłuższe niż 50 000 znaków.');
        }
        else{
            $this->advance_mail = wp_kses($this->advance_mail, $allowed_tags);
        }

        /*
         * paid_mail:
         * - nie może być puste
         *- po wyczyszczeniu  nie może być dłuższy niż 50000 znaków
         */
        if (empty($this->paid_mail)) {
            $this->setError('paid_mail', 'To pole nie może być puste');
        }
        else if(strlen($this->paid_mail) > 50000){
            $this->setError('paid_mail', 'To pole nie może być dłuższe niż 50 000 znaków.');
        }
        else{
            $this->paid_mail = wp_kses($this->paid_mail, $allowed_tags);
        }

        /*
         * cancel_mail:
         * - nie może być puste
         *- po wyczyszczeniu  nie może być dłuższy niż 50000 znaków
         */
        if (empty($this->cancel_mail)) {
            $this->setError('cancel_mail', 'To pole nie może być puste');
        }
        else if(strlen($this->cancel_mail) > 50000){
            $this->setError('cancel_mail', 'To pole nie może być dłuższe niż 50 000 znaków.');
        }
        else{
            $this->cancel_mail = wp_kses($this->cancel_mail, $allowed_tags);
        }


        return (!$this->hasErrors());
    }



}