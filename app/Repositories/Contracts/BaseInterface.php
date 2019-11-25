<?php
namespace Repositories\Contracts;
 
interface BaseInterface {
	
	public function all($columns = array('*'));
	public function paginate($perPage = 15, $columns = array('*'));
	public function find($id, $columns = array('*'));
	public function store(array $data);
	public function update(array $data, $id, $attribute="id");
	public function delete($id);
	public function findBy($attribute, $value, $columns = array('*'));
	public function where($attribute, $value, $symbal="=",$columns = array('*'));
	//public function where($attribute,$value, $columns = array('*'));
	//public function whereIn($attribute, array $data, $columns = array('*'));
	
}