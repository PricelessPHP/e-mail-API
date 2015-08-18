<?php
/**
 * Priceless PHP
 * e-mail API
 *
 * @author      BizLogic <hire@bizlogicdev.com>
 * @copyright   2015 BizLogic
 * @link        http://bizlogicdev.com
 * @link		http://pricelessphp.com
 * @license     GNU Affero General Public License v3
 *
 * @since  	    Thursday, May 28, 2015, 14:24 GMT+1
 * @modified    $Date$ $Author$
 * @version     $Id$
 *
 * @category    API
 * @package     Priceless PHP e-mail API
*/
 
error_reporting( E_ALL );
ini_set( 'display_errors', false );

// header
header( 'Content-Type: application/json; charset=utf-8' );

// constants
define( 'API_KEY', 'YOUR_API_KEY_HERE' );

if( empty( $_POST ) ) {
    $error = array(
        'status'    => 'ERROR',
        'error'     => 'Invalid Request',
    );
    
    exit( json_encode( $error ) );    
} else {
    $required = array(
        'apiKey',
        'mailFrom',
        'mailTo',
        'mailReplyTo',
        'mailSubject',
        'mailText',
    );
    
    // error placeholder
    $error = array();
    
    // START:   Check required values
    foreach( $required AS $key => $value ) {
        if( !strlen( trim( $value ) ) ) {
            $error[] = $value.' not specified';
        }
    }
    
    if( !empty( $error ) ) {
        $response = array(
            'status'    => 'ERROR',
            'error'     => $error,
        );
        
        exit( json_encode( $response ) );
    }
    // END:     Check required values
    
    if( $_POST['apiKey'] != API_KEY ) {
        $error = array(
            'status'    => 'ERROR',
            'error'     => 'Invalid API Key',
        );
        
        exit( json_encode( $error ) );
    } else {
        $from       = $_POST['mailFrom'];
        $message    = $_POST['mailText'];
        $replyTo    = $_POST['mailReplyTo'];
        $subject    = $_POST['mailSubject'];
        $to         = $_POST['mailTo'];
        
        // the e-mail message
        $message = $_POST['mailText'];
        
        // the e-mail mail headers
        $headers = "From: ".$from."\r\n";
        $headers .= "Reply-To: ".$mailReplyTo."\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // send the email
        $result = (int)mail( $to, $subject, $message, $headers );
        
        if( $result == 1 ) {
            $response = array(
                'status' => 'OK',
            );
            
            exit( json_encode( $response ) );            
        } else {
            $response = array(
                'status'    => 'ERROR',
                'error'     => 'e-mail not sent',
            );
            
            exit( json_encode( $response ) );            
        }     
    }
}
