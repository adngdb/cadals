<?php

class User
{
    public $id;
    public $login;
    public $role;

    public function __construct($id, $login, $role = 'user')
    {
        $this->id = $id;
        $this->login = $login;
        $this->role = $role;
    }
}

class AuthPlugin
{
    public static $config = array(
        'allowed_pages' => '*',
        'forbidden_pages' => array(),
        'login_page' => 'admin/login',
        'redirect_page' => 'admin/home',
        'all_admin' => false,
        'db' => array(
            'table' => 'users',
            'fields' => array(
                'id' => 'id',
                'login' => 'login',
                'password' => 'password',
                'role' => 'role',
            ),
        ),
    );

    static public function start($config)
    {
        self::$config = array_merge(self::$config, $config);
    }

    static public function onAtomikDispatchUri()
    {
        if (!isset($_SESSION['user_login']) || empty($_SESSION['user_login']))
        {
            $currentPage = A('request_uri');

            if (is_string(self::$config['allowed_pages']))
                $allow = str_replace(
                            array('*', '/'),
                            array('[a-zA-Z0-9._-]+', '\/'),
                            self::$config['allowed_pages']);
            else
                $allow = self::$config['allowed_pages'];

            if (is_string(self::$config['forbidden_pages']))
                $forbid = str_replace(
                            array('*', '/'),
                            array('[a-zA-Z0-9._-]+', '\/'),
                            self::$config['forbidden_pages']);
            else
                $allow = self::$config['forbidden_pages'];

            $accessible = true;

            if ($currentPage == self::$config['login_page'])
            {
                // This page is accessible, nothing to do
            }
            else if (self::$config['allowed_pages'] == '*')
            {
                if (self::$config['forbidden_pages'] == '*')
                {
                    // Exception
                    throw new Exception('Auth Plugin: allowed_pages and forbidden_pages cannot be all (*) together. ');
                }
                else if (
                        (is_array($forbid) && in_array($currentPage, $forbid) ) ||
                        (is_string($forbid) && preg_match('#^' . $forbid . '$#', $currentPage) != 0)
                    )
                {
                    $accessible = false;
                }
            }
            else if (self::$config['forbidden_pages'] == '*')
            {
                if  (
                        (is_array($allow) && !in_array($currentPage, $allow) ) ||
                        (is_string($allow) && preg_match('#^' . $allow . '$#', $currentPage) == 0)
                    )
                {
                    $accessible = false;
                }
            }
            else
            {
                if (
                        (is_array($forbid) && in_array($currentPage, $forbid) ) ||
                        (is_string($forbid) && preg_match('#^' . $forbid . '$#', $currentPage) != 0)
                    )
                {
                    $accessible = false;
                }
                else if (
                        (is_array($allow) && !in_array($currentPage, $allow) ) ||
                        (is_string($allow) && preg_match('#^' . $allow . '$#', $currentPage) == 0)
                    )
                {
                    $accessible = false;
                }
            }

            if (!$accessible)
            {
                Atomik::flash('Vous devez &ecirc;tre connect&eacute; pour acc&eacute;der &agrave; cette page. ', 'error');
                Atomik::redirect( self::$config['login_page'] . '?goto=' . A('request_uri') );
            }
        }
        else if ($_SESSION['user_role'] != 'admin') //self::$config['role_admin']
        {
            // Là on gère en fonction du rôle de l'utilisateur
        }
    }

    static public function login($login, $password, $goto = null)
    {
        $password = self::secure($password);

        $user = Atomik_Db::find(
            self::$config['db']['table'],
            array(
                self::$config['db']['fields']['login'] => $login,
                self::$config['db']['fields']['password'] => $password
            )
        );

        if ($user != false)
        {
            // Connexion réussie
            $_SESSION['user_login'] = $login;
            $_SESSION['user_id'] = $user[ self::$config['db']['fields']['id'] ];
            $_SESSION['user_role'] = self::$config['all_admin'] ? 'admin' : $user[ self::$config['db']['fields']['role'] ];

            setcookie('user_login', $login, time() + 24*3600, null, null, false, true);
            setcookie('user_password', $password, time() + 24*3600, null, null, false, true);

            if (!is_null($goto))
                Atomik::redirect($goto);
            else
                Atomik::redirect(self::$config['redirect_page']);

            return true;
        }

        return false;
    }

    static public function logout()
    {
        session_destroy();
        setcookie('user_login');
        setcookie('user_password');

    }

    static public function register($login, $password = null, $role = null)
    {
        if (is_null($password))
        {
            $password = self::generate_random_password();
        }

        $res = Atomik_Db::insert(
            self::$config['db']['table'],
            array(
                self::$config['db']['fields']['login'] => $login,
                self::$config['db']['fields']['password'] => self::secure($password)
            )
        );

        if ($res !== false)
            return array('id' => $res, 'password' => $password);

        return false;
    }

    static public function user()
    {
        return new User($_SESSION['user_id'], $_SESSION['user_login'], $_SESSION['user_role']);
    }

    static public function isUser()
    {
        return !empty($_SESSION['user_login']);
    }

    static public function getId()
    {
        return $_SESSION['user_id'];
    }

    static public function getLogin()
    {
        return $_SESSION['user_login'];
    }

    static public function getRole()
    {
        return $_SESSION['user_role'];
    }

    static private function secure($text)
    {
        return md5($text);
    }

    static private function generate_random_password()
    {
        $pass = md5(uniqid(rand(), true));
        return substr($pass, 0, 10);
    }
}
