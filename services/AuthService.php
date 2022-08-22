<?php
namespace Services;


class AuthService extends BaseService
{
    private $authenticatedUser = null;

    public function authenticate($email, $password)
    {
        $user = $this->getUser($email);
        if (!$user)
            throw new \InvalidArgumentException("Nieprawidłowy login.");
        if (!password_verify($password, $user->password))
            throw new \InvalidArgumentException("Nieprawidłowe hasło.");

        $this->startAuthSession($user);
        $this->updateLastLogin($user->id);
        return $this->getAuthenticatedUser();
    }

    public function logout(): bool
    {
        $this->authenticatedUser = null;
        $this->endAuthSession();
        return true;
    }

    protected function startAuthSession($user)
    {
        $session = $this->get('session');
        $session->id(true);
        $session->set('auth_user', $user->id);
        $session->set('auth_email', $user->email);
    }

    protected function endAuthSession()
    {
        $session = $this->get('session');
        $session->delete('auth_user');
        $session->delete('auth_email');
        $session->id(true);
    }

    public function isAuthenticated(): bool
    {
        return (bool)$this->authenticatedUser;
    }

    public function getAuthenticatedUser()
    {
        $session = $this->get('session');
        if (!$this->authenticatedUser && $session->exists('auth_user')) {
            $user = $this->getUserById($session->get('auth_user'));
            if (!$user)
                throw new \InvalidArgumentException(
                    sprintf("User account ID `%s` does not exists!", $session->get('auth_user'))
                );
            $this->authenticatedUser = $user;
        }
        return $this->authenticatedUser;
    }

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

    public function getUserById(int $id)
    {
        return $this->get('db')->fetch('user', ['id' => $id]);
    }

    public function createUser($data)
    {
        return $this->get('db')->insert('user', $data);
    }

    public function updateLastLogin(int $id)
    {
        $this->get('db')->update('user', ['last_login' => date_create()->format('Y-m-d H:i:s')], ['id' => $id], 1);
    }

    public function passwordHash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);
    }
}
