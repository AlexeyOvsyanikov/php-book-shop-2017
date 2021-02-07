<?php

namespace Application\Controllers;


class patternConst{

    public $LoginPattern = '/^[a-zA-ZА-Яа-я\d]{4,16}$/iu';
    public $NamesPattern = '/^[a-zA-ZА-Яа-я]{2,16}$/iu';
    public $PhoneNumberPattern = '/^\+\d{2}\(\d{3}\)\d{3}-\d{2}-\d{2}$/';
    public $EmailPattern = '/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/i';
    public $PasswordPattern = '/^[a-z0-9_?!^%()\d]{6,30}$/i';

    public $statusOrderInProsess = 1;
    public $statusOrderNew = 2;

}//patternConst