<?php
/**
 * Exceptions file. Contains the various exceptions CakePHP will throw until they are
 * moved into their permanent location.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://book.cakephp.org/2.0/en/development/testing.html
 * @package       Cake.Error
 * @since         CakePHP(tm) v 2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Base class that all Exceptions extend.
 *
 * @package       Cake.Error
 */
class CakeBaseException extends RuntimeException {

/**
 * Array of headers to be passed to CakeResponse::header()
 *
 * @var array
 */
    protected $_responseHeaders = null;

/**
 * Get/set the response header to be used
 *
 * @param string|array $header An array of header strings or a single header string
 *  - an associative array of "header name" => "header value"
 *  - an array of string headers is also accepted
 * @param string $value The header value.
 * @return array
 * @see CakeResponse::header()
 */
    public function responseHeader($header = null, $value = null) {
        if ($header) {
            if (is_array($header)) {
                return $this->_responseHeaders = $header;
            }
            $this->_responseHeaders = array($header => $value);
        }
        return $this->_responseHeaders;
    }

}

/**
 * Parent class for all of the HTTP related exceptions in CakePHP.
 * All HTTP status/error related exceptions should extend this class so
 * catch blocks can be specifically typed.
 *
 * @package       Cake.Error
 */
if (!class_exists('HttpException', false)) {
    class HttpException extends CakeBaseException {
    }
}

/**
 * Represents an HTTP 400 error.
 *
 * @package       Cake.Error
 */
class BadRequestException extends HttpException {

/**
 * Constructor
 *
 * @param string $message If no message is given 'Bad Request' will be the message
 * @param int $code Status code, defaults to 400
 */
    public function __construct($message = null, $code = 400) {
        if (empty($message)) {
            $message = 'Bad Request';
        }
        parent::__construct($message, $code);
    }

}

/**
 * Represents an HTTP 401 error.
 *
 * @package       Cake.Error
 */
class UnauthorizedException extends HttpException {

/**
 * Constructor
 *
 * @param string $message If no message is given 'Unauthorized' will be the message
 * @param int $code Status code, defaults to 401
 */
    public function __construct($message = null, $code = 401) {
        if (empty($message)) {
            $message = 'Unauthorized';
        }
        parent::__construct($message, $code);
    }

}

/**
 * Represents an HTTP 403 error.
 *
 * @package       Cake.Error
 */
class ForbiddenException extends HttpException {

/**
 * Constructor
 *
 * @param string $message If no message is given 'Forbidden' will be the message
 * @param int $code Status code, defaults to 403
 */
    public function __construct($message = null, $code = 403) {
        if (empty($message)) {
            $message = 'Forbidden';
        }
        parent::__construct($message, $code);
    }

}

/**
 * Represents an HTTP 404 error.
 *
 * @package       Cake.Error
 */
class NotFoundException extends HttpException {

/**
 * Constructor
 *
 * @param string $message If no message is given 'Not Found' will be the message
 * @param int $code Status code, defaults to 404
 */
    public function __construct($message = null, $code = 404) {
        if (empty($message)) {
            $message = 'Not Found';
        }

        echo 'exception not found';
        parent::__construct($message, $code);
    }

}

/**
 * Represents an HTTP 405 error.
 *
 * @package       Cake.Error
 */
class MethodNotAllowedException extends HttpException {

/**
 * Constructor
 *
 * @param string $message If no message is given 'Method Not Allowed' will be the message
 * @param int $code Status code, defaults to 405
 */
    public function __construct($message = null, $code = 405) {
        if (empty($message)) {
            $message = 'Method Not Allowed';
        }
        parent::__construct($message, $code);
    }

}

/**
 * Represents an HTTP 500 error.
 *
 * @package       Cake.Error
 */
class InternalErrorException extends HttpException {

/**
 * Constructor
 *
 * @param string $message If no message is given 'Internal Server Error' will be the message
 * @param int $code Status code, defaults to 500
 */
    public function __construct($message = null, $code = 500) {
        if (empty($message)) {
            $message = 'Internal Server Error';
        }
        parent::__construct($message, $code);
    }

}
