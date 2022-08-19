<?php
namespace Services;


class AccountService extends BaseService
{
    public function create($data)
    {
        $this->get('db')->connect();
    }

    public function getUser($email)
    {

    }
}
