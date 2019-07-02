<?php


class ErrorMessage implements JsonSerializable
{

    private static $appendError = "";
    private $error;

    public function __construct($error)
    {
        $this->error = $error;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public static function sendMessage($code, $message) {
        $error = new ErrorMessage($message);
        http_response_code($code);
        echo json_encode($error);
        exit();
    }

    public static function appendMessage($message) {
        self::$appendError .= $message;
    }

    public static function sendAppendedMessage($code) {
        self::sendMessage($code,self::$appendError);
    }

    public static function getAppendedMessage() {
        return self::$appendError;
    }
}