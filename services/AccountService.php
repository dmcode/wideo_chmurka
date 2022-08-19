<?php
namespace Services;


class AccountService extends BaseService
{
    public function create($data)
    {
        if ($this->getUser($data['email']))
            throw new \InvalidArgumentException("Istnieje konto powiązane z podanym adresem email");
        return $this->createUser([
            'email' => $data['email'],
            'password' => $this->passwordHash($data['password'])
        ]);
    }

    public function getUser($email)
    {
        return $this->get('db')->fetch('user', ['email' => $email]);
    }

    public function createUser($data)
    {
        return $this->get('db')->insert('user', $data);
    }

    public function passwordHash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);
    }
}
