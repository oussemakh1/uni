<?php 

namespace cgc\platform\interfaces;

use cgc\platform\database\Mysql;

interface Crud {

    public function __construct();
    
    public function index();

    public function store(array $data);

    public function find(int $id);

    public function update(array $data,int $id);

    public function delete(int $id);
}