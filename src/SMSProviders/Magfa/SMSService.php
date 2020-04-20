<?php
namespace Niyam\ACL\SMSProviders\Magfa;

use SoapClient;
use SoapFault;

/**
 * Class : WebServiceSample
 * each method of this class describes a sample usage of a WebService request
 * note : this class uses "nusoap" library inorder to send requests via webservice,
 *          you can download the latest version of this library from : "http://sourceforge.net/projects/nusoap/" 
 * before start, please set the prerequisites correctly (such as USERNAME,PASSWORD, & DOMAIN )
 */

class SMSService
{

    // your username (fill it with your username)
    private  $USERNAME = "barname";

    // your password (fill it with your password)
    private  $PASSWORD = "HqK6FjDJAbFNXoAC";

    // your domain (fill it with your domain - usually: "magfa")
    private  $DOMAIN = "shirazfava";

    // base webservice url
    private  $BASE_WEBSERVICE_URL = "http://sms.magfa.com/services/urn:SOAPSmsQueue?wsdl";

    private $client; // SoapClient object

    private $ERROR_MAX_VALUE = 1000;
    private $errors;

    private $outputSeparator = "\n";





    /**
     * method : constructor
     * the constructor method of the class
     * @return void
     */
    public function __construct()
    {

        if (PHP_SAPI !== 'cli') {
            // Pretty output
            $this->outputSeparator .= '<br>';
        }

        $this->setErrors();

        try {
            // $this->client = new SoapClient(
            //     $this->BASE_WEBSERVICE_URL,
            //     array(
            //         'login' => $this->USERNAME, 'password' => $this->PASSWORD, // Credientials
            //         'features' => SOAP_USE_XSI_ARRAY_TYPE // Required
            //         //,'trace' => true // Optional (debug)
            //     )
            // );
            $this->client = new \nusoap_client($this->BASE_WEBSERVICE_URL); // creating an instance of nusoap client object
            // set the character set to utf-8 (inorder to prevent corrupting persian messages sending via webservice )
            $this->client->soap_defencoding = 'UTF-8';
            $this->client->decode_utf8 = false;
            $this->client->setCredentials($this->USERNAME,$this->PASSWORD,"basic"); // authentication
            // Get list of functions
            //var_dump($this->client->__getFunctions());
        } catch (SoapFault $soapFault) {
            echo $soapFault . $this->outputSeparator;
        }
    }




    /**
     * method : simpleEnqueueSample
     * this method provides a sample usage of "enqueue" service in the simplest format (one receiver)
     * @return void
     */

    public function setErrors()
    {
        $errors = array();

        $errors[1]['title'] = 'INVALID_RECIPIENT_NUMBER';
        $errors[1]['desc'] = 'the string you presented as recipient numbers are not valid phone numbers, please check them again';

        $errors[2]['title'] = 'INVALID_SENDER_NUMBER';
        $errors[2]['desc'] = 'the string you presented as sender numbers(3000-xxx) are not valid numbers, please check them again';

        $errors[3]['title'] = 'INVALID_ENCODING';
        $errors[3]['desc'] = 'are You sure You\'ve entered the right encoding for this message? You can try other encodings to bypass this error code';

        $errors[4]['title'] = 'INVALID_MESSAGE_CLASS';
        $errors[4]['desc'] = 'entered MessageClass is not valid. for a normal MClass, leave this entry empty';

        $errors[6]['title'] = 'INVALID_UDH';
        $errors[6]['desc'] = 'entered UDH is invalid. in order to send a simple message, leave this entry empty';

        $errors[12]['title'] = 'INVALID_ACCOUNT_ID';
        $errors[12]['desc'] = 'you\'re trying to use a service from another account??? check your UN/Password/NumberRange again';

        $errors[13]['title'] = 'NULL_MESSAGE';
        $errors[13]['desc'] = 'check the text of your message. it seems to be null';

        $errors[14]['title'] = 'CREDIT_NOT_ENOUGH';
        $errors[14]['desc'] = 'Your credit\'s not enough to send this message. you might want to buy some credit.call';

        $errors[15]['title'] = 'SERVER_ERROR';
        $errors[15]['desc'] = 'something bad happened on server side, you might want to call MAGFA Support about this:';

        $errors[16]['title'] = 'ACCOUNT_INACTIVE';
        $errors[16]['desc'] = 'Your account is not active right now, call -- to activate it';

        $errors[17]['title'] = 'ACCOUNT_EXPIRED';
        $errors[17]['desc'] = 'looks like Your account\'s reached its expiration time, call -- for more information';

        $errors[18]['title'] = 'INVALID_USERNAME_PASSWORD_DOMAIN'; // todo : note : one of them are empty
        $errors[18]['desc'] = 'the combination of entered Username/Password/Domain is not valid. check\'em again';

        $errors[19]['title'] = 'AUTHENTICATION_FAILED'; // todo : note : wrong arguments supplied ...
        $errors[19]['desc'] = 'You\'re not entering the correct combination of Username/Password';

        $errors[20]['title'] = 'SERVICE_TYPE_NOT_FOUND';
        $errors[20]['desc'] = 'check the service type you\'re requesting. we don\'t get what service you want to use. your sender number might be wrong, too.';

        $errors[22]['title'] = 'ACCOUNT_SERVICE_NOT_FOUND';
        $errors[22]['desc'] = 'your current number range doesn\'t have the permission to use Webservices';

        $errors[23]['title'] = 'SERVER_BUSY';
        $errors[23]['desc'] = 'Sorry, Server\'s under heavy traffic pressure, try testing another time please';

        $errors[24]['title'] = 'INVALID_MESSAGE_ID';
        $errors[24]['desc'] = 'entered message-id seems to be invalid, are you sure You entered the right thing?';

        $errors[102]['title'] = 'WEB_RECIPIENT_NUMBER_ARRAY_SIZE_NOT_EQUAL_MESSAGE_CLASS_ARRAY';
        $errors[102]['desc'] = 'this happens when you try to define MClasses for your messages. in this case you must define one recipient number for each MClass';

        $errors[103]['title'] = 'WEB_RECIPIENT_NUMBER_ARRAY_SIZE_NOT_EQUAL_SENDER_NUMBER_ARRAY';
        $errors[103]['desc'] = 'This error happens when you have more than one sender-number for message. when you have more than one sender number, for each sender-number you must define a recipient number...';

        $errors[104]['title'] = 'WEB_RECIPIENT_NUMBER_ARRAY_SIZE_NOT_EQUAL_MESSAGE_ARRAY';
        $errors[104]['desc'] = 'this happens when you try to define UDHs for your messages. in this case you must define one recipient number for each udh';

        $errors[106]['title'] = 'WEB_RECIPIENT_NUMBER_ARRAY_IS_NULL';
        $errors[106]['desc'] = 'array of recipient numbers must have at least one member';

        $errors[107]['title'] = 'WEB_RECIPIENT_NUMBER_ARRAY_TOO_LONG';
        $errors[107]['desc'] = 'the maximum number of recipients per message is 90';

        $errors[108]['title'] = 'WEB_SENDER_NUMBER_ARRAY_IS_NULL';
        $errors[108]['desc'] = 'array of sender numbers must have at least one member';

        $errors[109]['title'] = 'WEB_RECIPIENT_NUMBER_ARRAY_SIZE_NOT_EQUAL_ENCODING_ARRAY';
        $errors[109]['desc'] = 'this happens when you try to define encodings for your messages. in this case you must define one recipient number for each Encoding';

        $errors[110]['title'] = 'WEB_RECIPIENT_NUMBER_ARRAY_SIZE_NOT_EQUAL_CHECKING_MESSAGE_IDS__ARRAY';
        $errors[110]['desc'] = 'this happens when you try to define checking-message-ids for your messages. in this case you must define one recipient number for each checking-message-id';

        $errors[-1]['title'] = 'NOT_AVAILABLE';
        $errors[-1]['desc'] = 'The target of report is not available(e.g. no message is associated with entered IDs)';
        $this->errors = $errors;
    }
    public function simpleEnqueueSample()
    {
        $method = "enqueue"; // name of the service
        $message = "MAGFA webservice-enqueue test"; // [FILL] your message to send
        $senderNumber = "3000xxx"; // [FILL] sender number; which is your 3000xxx number
        $recipientNumber = "09xxxxxxxx"; // [FILL] recipient number; the mobile number which will receive the message (e.g 0912XXXXXXX)
        // creating the parameter array
        $params = array(
            'domain' => $this->DOMAIN,
            'messageBodies' => array($message),
            'recipientNumbers' => array($recipientNumber),
            'senderNumbers' => array($senderNumber)
        );
        // sending the request via webservice
        $response = $this->call($method, $params);

        $result = $response[0];
        // compare the response with the ERROR_MAX_VALUE
        if ($result <= $this->ERROR_MAX_VALUE) {
            echo "An error occured" . $this->outputSeparator;
            echo "Error Code : $result ; Error Title : " . $this->errors[$result]['title'] . ' {' . $this->errors[$result]['desc'] . '}' . $this->outputSeparator;
        } else {
            echo "Message has been successfully sent ; MessageId : $result" . $this->outputSeparator;
        }
    }





    /**
     * method : enqueueSample
     * this method provides a sample usage of "enqueue" service
     * @see simpleEnqueueSample()
     * @return void
     */
    public function enqueueSample($recipientNumbers = [])
    {
        $method = "enqueue"; // name of the service
        $message = "سلام تست پیامک سامانه ی PMIS"; // [FILL] your message to send
        $senderNumber = "3000137720"; // [FILL] sender number; which is your 3000xxx number
        // [FILL] recipient number; here we have multiple recipients (2)
        $recipientNumbers = $recipientNumbers ?? array('09138562838'); // [FILL] you can add more items here ...
        $checkingMessageIds = array(100, 101);

        // creating the parameter array
        $params = array(
            'domain'=>$this->DOMAIN,
            'messageBodies'=>array($message),
            'recipientNumbers'=>$recipientNumbers,
            'senderNumbers'=>array($senderNumber)
        );


        // sending the request via webservice
        $response = $this->call($method, $params);

        foreach ($response as $result) {
            // compare the response with the ERROR_MAX_VALUE
            if ($result <= $this->ERROR_MAX_VALUE) {
                echo "An error occured <br> ";
                echo "Error Code : $result ; Error Title : " . $this->errors[$result]['title'] . ' {' . $this->errors[$result]['desc'] . '}';
            } else {
                echo "Message has been successfully sent ; MessageId : $result";
            }
            echo "<br>";
        }
    }





    /**
     * method : getAllMessagesSample
     * this method provides a sample usage of "getAllMessages" service
     * @return void
     */
    public function getAllMessagesSample()
    {
        $method = "getAllMessages"; // name of the service
        $numberOfMessasges = 10; // [FILL] number of the messages to fetch

        // creating the parameter array
        $params = array(
            'domain' => $this->DOMAIN,
            'numberOfMessages' => $numberOfMessasges
        );

        // sending the request via webservice
        $response = $this->call($method, $params);

        if (count($response) == 0) {
            echo "No new message" . $this->outputSeparator;
        } else {
            // display the incoming message(s)
            foreach ($response as $result) {
                echo "Message:" . $this->outputSeparator;
                var_dump($result);
            }
        }
    }





    /**
     * method : getAllMessagesWithNumberSample
     * this method provides a sample usage of "getAllMessagesWithNumber" service
     * @return void
     */
    public function getAllMessagesWithNumberSample()
    {
        $method = "getAllMessagesWithNumber"; // name of the service
        $numberOfMessages = 10; // [FILL] number of the messages to fetch
        $destinationNumber = "983000XXX"; // [FILL] the 983000xxx number

        // creating the parameter array
        $params = array(
            'domain' => $this->DOMAIN,
            'numberOfMessages' => $numberOfMessages,
            'destNumber' => $destinationNumber
        );

        // sending the request via webservice
        $response = $this->call($method, $params);

        if (count($response) == 0) {
            echo "No new message" . $this->outputSeparator;
        } else {
            // display the incoming message(s)
            foreach ($response as $result) {
                echo "Message:" . $this->outputSeparator;
                var_dump($result);
            }
        }
    }






    /**
     * method : getCreditSample
     * this method provides a sample usage of "getCredit" service
     * @return void
     */
    public function getCreditSample()
    {
        $method = "getCredit"; // name of the service

        // creating the parameter array
        $params = array(
            'domain' => $this->DOMAIN
        );

        // sending the request via webservice
        $response = $this->call($method, $params);

        // display the result
        echo 'Your Credit : ' . $response . $this->outputSeparator;
    }






    /**
     * method : getMessageIdSample
     * this method provides a sample usage of "getMessageId" service
     * @return void
     */
    public function getMessageIdSample()
    {
        $method = "getMessageId"; // name of the service
        $checkingMessageId = 100; // [FILL] your checkingMessageId

        // creating the parameter array
        $params = array(
            'domain' => $this->DOMAIN,
            'checkingMessageId' => $checkingMessageId
        );

        // sending the request via webservice
        $result = $this->call($method, $params);

        // compare the response with the ERROR_MAX_VALUE
        if ($result <= $this->ERROR_MAX_VALUE) {
            echo "An error occured" . $this->outputSeparator;
            echo "Error Code : $result ; Error Title : " . $this->errors[$result]['title'] . ' {' . $this->errors[$result]['desc'] . '}' . $this->outputSeparator;
        } else {
            echo "MessageId : $result" . $this->outputSeparator;
        }
    }



    /**
     * method : getMessageStatusSample
     * this method provides a sample usage of "getMessageStatus" service
     * @return void
     */
    public function getMessageStatusSample()
    {
        $method = 'getMessageStatus'; // name of the service
        $messageId = 718570969; // [FILL] your messageId

        // creating the parameter array
        $params = array(
            'messageId' => $messageId
        );

        //sending request via webservice
        $result = $this->call($method, $params);

        // checking the response
        if ($result == -1) {
            echo "An error occured" . $this->outputSeparator;
            echo "Error Code : $result ; Error Title : " . $this->errors[$result]['title'] . ' {' . $this->errors[$result]['desc'] . '}' . $this->outputSeparator;
        } else {
            echo "Message Status : $result" . $this->outputSeparator;
        }
    }






    /**
     * method : getMessageStatusesSample
     * this method provides a sample usage of "getMessageStatuses" service
     * @return void
     */
    public function getMessageStatusesSample()
    {
        $method = 'getMessageStatuses'; // name of the service
        // [FILL] an array of messageIds to check
        $messageIds = array(11728027728); //  [FILL] your messageID here

        // creating the parameter array
        $params = array(
            'messagesId' => $messageIds
        );

        // sending the request via webservice
        $response = $this->call($method, $params);

        // checking the response
        foreach ($response as $result) {
            if ($result == -1) {
                echo "An error occured" . $this->outputSeparator;
                echo "Error Code : $result ; Error Title : " . $this->errors[$result]['title'] . ' {' . $this->errors[$result]['desc'] . '}' . $this->outputSeparator;
            } else {
                echo "Message Status : $result" . $this->outputSeparator;
            }
        }
    }





    /**
     * method : getRealMessageStatuses
     * this method provides a sample usage of "getRealMessageStatuses" service
     * @return void
     */
    public function getRealMessageStatusesSample()
    {
        $method = 'getRealMessageStatuses'; // name of the service
        // [FILL] an array of messageIds to check
        $messageIds = array(11728027728); // [FILL] your messageID here

        // creating the parameter array
        $params = array(
            'arg0' => $messageIds
        );

        // sending the request via webservice
        $response = $this->call($method, $params);

        // checking the response
        foreach ($response as $result) {
            if ($result == -1) {
                echo "An error occured" . $this->outputSeparator;
                echo "Error Code : $result ; Error Title : " . $this->errors[$result]['title'] . ' {' . $this->errors[$result]['desc'] . '}' . $this->outputSeparator;
            } else {
                echo "Message Status : $result" . $this->outputSeparator;
            }
        }
    }





    /**
     * method : call
     * this method calls method of the webservice client object
     * @access private
     * @param  String $method    service name
     * @param  Array $params     webservice parameters in the form of an array
     * @return mixed             result
     */
    private function call($method, $params)
    {

        // try {
        //     $result = $this->client->__soapCall($method, $params);
        // } catch (SoapFault $soapFault) {
        //     echo $soapFault . $this->outputSeparator;
        //     echo "REQUEST: " . $this->client->__getLastRequest() . $this->outputSeparator;
        //     echo "RESPONSE: " . $this->client->__getLastResponse() . $this->outputSeparator;
        // }
        // return $result;
        $result = $this->client->call($method,$params);
        if($this->client->fault || ((bool)$this->client->getError()) ){
            echo '<br>';
            echo "nusoap error: ".$this->client->getError();
            echo '<br>';
        }
        //var_dump($result); echo "<br>";
        return $result;

    }
}
