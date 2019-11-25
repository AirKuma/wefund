<?php
namespace Repositories\Criteria;
use Repositories\Contracts\BaseInterface;
use App\Club;

class BaseRepository implements BaseInterface{

	protected $modelName;
	protected $tenantKey = 'id';
	protected $tenantId;

	public function all($columns = array('*'))
	{
		$instance = $this->getNewInstance();

		return $instance->get($columns);
	}
	
	public function paginate($perPage = 15, $columns = array('*'))
	{
		$instance = $this->getNewInstance();

		return $instance->paginate($perPage, $columns);
	}

	public function find($id, $columns = array('*'))
	{
		$instance = $this->getNewInstance();

		return $instance->find($id);
	}

	public function store(array $data)
	{
		$instance = $this->getNewInstance();
		return $instance->create($data);
	}

	public function update(array $data, $id, $attribute="id")
	{
		$instance = $this->getNewInstance();
		return $instance->where($attribute, '=', $id)->update($data);
	}

	public function delete($id)
	{
		$instance = $this->getNewInstance();
		return $instance->destroy($id);
	}

	public function findBy($attribute, $value, $columns = array('*'))
	{
		$instance = $this->getNewInstance();
		return $instance->where($attribute, '=', $value)->first($columns);
	}

	public function whereId($id, $columns = array('*'))
	{
		$instance = $this->getNewInstance();

		return $instance->whereId($id);
	}

	public function whereIn($attribute, array $data, $columns = array('*'))
	{
		$instance = $this->getNewInstance();

		return $instance->whereIn($attribute, $data);
	}

	public function where($attribute, $symbal="=", $value, $columns = array('*'))
	{
		$instance = $this->getNewInstance();
		return $instance->where($attribute, $symbal, $value);
	}

	public function where($attribute, $value, $columns = array('*'))
	{
		$instance = $this->getNewInstance();
		return $instance->where($attribute, "=", $value);
	}

	public function getNewInstance()
	{
		$model = $this->modelName;
		return new $model;
	}
}
