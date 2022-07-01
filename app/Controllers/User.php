<?php

namespace App\Controllers;

class User extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect('dbread');

	$query = $db->query('SELECT @@hostname');
	$host = $query->getRowArray();

	$query = $db->query('SELECT * FROM users');
	$result = $query->getResultArray();

	return $this->response->setJSON([
	    "host" => $host,
	    "users" => $result
	]);
    }

    public function save()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $data = [
            'email' => random_string('alpha', 6) . '@mail.com',
            'name'  => random_string('alpha', 10),
            'password'  => password_hash('secret', PASSWORD_BCRYPT),
            'created_at'  => date('Y-m-d H:i:s'),
        ];

        $result = $builder->insert($data);
	$user = $builder->where("id", $db->insertID())->get()->getRowArray();

	$host = $db->query('SELECT @@hostname')->getRowArray();

        return $this->response->setJSON([
	    "success" => $result,
	    "host" => $host,
	    "user" => $user
	]);
    }
}
