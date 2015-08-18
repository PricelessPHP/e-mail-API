<?php
/**
 * Priceless PHP
 * e-mail API Client
 *
 * @author      MarQuis Knox <opensource@marquisknox.com>
 * @copyright   2015 MarQuis Knox
 * @link        http://marquisknox.com
 * @license     GNU Affero General Public License v3
 *
 * @since  	    Tuesday, August 18, 2015, 17:30 GMT+1
 * @modified    $Date$ $Author$
 * @version     $Id$
 *
 * @category    API
 * @package     Priceless PHP
*/
 
error_reporting( E_ALL );
ini_set( 'display_errors', true );

$url    = 'http://domain.tld/api.php';
$data   = array(
    'apiKey'        => null,
    'mailFrom'      => null,
    'mailReplyTo'   => null,
    'mailSubject'   => '[TEST] API Test',
    'mailText'      => 'Testing...',
    'mailTo'        => null,    
);

$result = curl_post_url( 
    $url, 
    $data 
);

echo "Result:  ".$result."\n";

/**
 * Post to a URL via cURL
 *
 * @link	http://bavotasan.com/2011/post-url-using-curl-php
 * @link	http://davidwalsh.name/curl-post
 *
 * @param   string  $url
 * @param	array	$data
 * @param	array	$headers
 * @param   boolean $returnCurlInfo
 * @param   int     $timeout
 * @param   int     $maxRedirs
 * @return  array
*/
function curl_post_url( $url, $data = array(), $headers = array(), $returnCurlInfo = false, $timeout = 60, $maxRedirs = 10 )
{
    if( empty( $data ) ) {
        return array();
    }

    $fields = '';
    foreach( $data AS $key => $value ) {
        // decode if already URL encoded,
        // this insures that the URL is URL encoded
        $fields .= $key . '=' . urlencode( urldecode( $value ) ) . '&';
    }

    rtrim( $fields, '&' );

    $post = curl_init();

    if( !empty( $headers ) ) {
        curl_setopt( $post, CURLOPT_HTTPHEADER, $headers );
    }
    curl_setopt( $post, CURLOPT_USERAGENT, 'PHP '. phpversion() );
    curl_setopt( $post, CURLOPT_URL, $url );
    curl_setopt( $post, CURLOPT_POST, count( $data ) );
    curl_setopt( $post, CURLOPT_POSTFIELDS, $fields );
    curl_setopt( $post, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $post, CURLOPT_MAXREDIRS, $maxRedirs );
    curl_setopt( $post, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $post, CURLOPT_BINARYTRANSFER, true );
    curl_setopt( $post, CURLOPT_HEADER, false );

    if( $returnCurlInfo ) {
        curl_setopt( $post, CURLINFO_HEADER_OUT, true );
    }

    curl_setopt( $post, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $post, CURLOPT_TIMEOUT, $timeout );

    $response = curl_exec( $post );

    if( $returnCurlInfo ) {
        $originalResponse   = $response;
        $response           = array();
        $response['info']   = curl_getinfo( $post );
        $response['html']   = $originalResponse;
    }

    if( $error = curl_error( $post ) ) {
        $response['error']      = $error;
        $response['errorno']    = curl_errno( $post );
    }

    curl_close( $post );

    return $response;
}
