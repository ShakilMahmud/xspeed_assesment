<?php
class Helper
{
    private $operation;

    function __construct()
    {
        include_once __DIR__ . '/controllers/Operation.php';
        $this->operation = new Operation();
    }

    function data_insert()
    {
        $result = $this->operation->data_insert_controller();

    }
    function data_report()
    {
        $result = $this->operation->data_report_controller();

    }
    function landing()
    {
        $this->operation->landing_controller();
    }
    function data_entry_handler_submit()
    {
        $this->operation->data_submit_controller();
       
    }

   
}
