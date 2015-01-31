<?php

class DOIFDAdminMessage {

    public $adminMessage = '';
    public $message = '';
    public $type = null;

    public function __construct( $message ) {

        if( empty( $message ) || (!is_array( $message )) ) {
            try {
                throw new Exception( "Invalid Admin Message" );
            } catch ( Exception $e ) {
                echo $e->getMessage() . '<br>';
                echo 'From ' . $e->getFile()  . ' on line ' . $e->getline(), '<br>';
                echo 'The error was intiated at ' . $e->getTraceAsString();
            }
        }
        
        $this->type = $message[1];
        $this->message = $message[0];
        $this->adminMessage = $this->setAdminMessage();
    }

    public function setAdminMessage() {
        if( $this->type == true ) {
            $this->adminMessage = '<div id="message" class="error"><p><strong>' . $this->message . '</strong></p></div>';
        }elseif ( $this->type == false ) {
            $this->adminMessage = '<div id="message" class="updated"><p><strong>' . $this->message . '</strong></p></div>';    
        }else{
            $this->adminMessage = '<div id="message" class="error"><p><strong>Invalid Admin Message Format</strong></p></div>';
        }
        return $this->adminMessage;
        
    }

    public function getAdminMessage() {
        return $this->adminMessage;
    }

}
