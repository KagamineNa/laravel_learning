<?php
use App\Models\Group;

function isUppercase($value, $message, $fail)
{
    if ($value !== mb_strtoupper($value, 'UTF-8')) {
        $fail($message);
    }
}

function getAllGroups()
{
    return(new Group())->getAllGroup();
}