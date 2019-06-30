<?php
require_once 'ErrorMessage.php';
require_once 'ConverterExecutor.php';

class RestAPI
{
    private $requestMapper;

    public function __construct()
    {
        $this->requestMapper = [];

        //POST requests
        $postRequests = [];
        $postRequests['convert'] = new ConverterExecutor();

        $this->requestMapper['POST'] = $postRequests;
    }

    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if (key_exists($method, $this->requestMapper) && key_exists('PATH_INFO', $_SERVER)) {
            $availableRequests = $this->requestMapper[$method];
            $currentPath = $_SERVER['PATH_INFO'];
            $requests = preg_split('@/@', $currentPath,NULL,PREG_SPLIT_NO_EMPTY);

            if ($requests[0] == $currentPath || !key_exists($requests[0], $availableRequests)) {
                ErrorMessage::sendMessage(400, 'Unknown parameters');
            }

            $currentRequest = $requests[0];
            $availableRequests[$currentRequest]->exec();
        } else {
            ErrorMessage::sendMessage(404, "No request available.");
        }
    }
}