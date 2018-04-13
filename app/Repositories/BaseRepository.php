<?php

namespace App\Repositories;

use App\Exceptions\GeneralException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Facade;

abstract class BaseRepository implements RepositoryContract
{
	/**
	 * the repository model
	 * 
	 * @var \Illuminate\Database\Eloquent\Model
	 */
	protected $model;

	/**
	 * the query builder
	 * 
	 * @var \Illuminate\Database\Eloquent\Builder
	 */
	protected $query;

	/**
	 * alias for the query limit
	 * 
	 * @var int
	 */
	protected $take;

	/**
	 * array of related models to eager load
	 * 
	 * @var array
	 */
	protected $with = [];

	/**
	 * Array of one or more where clause parameters
	 * 
	 * @var array
	 */
	protected $wheres = [];

	/**
	 * array of one or more where clause parameters
	 * 
	 * @var array
	 */
	protected $whereIns = [];

	/**
	 * Array of one or more Order by column/value pairs
	 * 
	 * @var array
	 */
	protected $orderBys = [];

	/**
	 *	Array of scope methods to call the model 
	 *
	 * @var array
	 */
	protected $scopes = [];

	/**
	 * 	明确 model 的类名
	 * 
	 * @return [type] [description]
	 */
	abstract public function model();

	/**
	 * BaseRepository constructor
	 */
	public function __construct()
	{
		$this->makeModel();
	}

	/**
	 * @return Model|mixted
	 * @throws GeneralException 
	 */
	public function makeModel()
	{
		$model = app()->make($this->model());

		if(! $model instanceof Model){
			throw new GeneralException("Class {$this->model} must be an instance of ".Model::class);
		}

		return $this->model = $model;
	} 

	/**
	 * 获取数据库里的全部记录
	 * 
	 * @param  array  $column 
	 *
	 * @return Collections|static[]
	 */
	public function all(array $column = ['*'])
	{
		$this->newQuery()->eagerLoad();

		$models = $this->query->get($column);

		$this->unsetClauses();

		return $models;
	}
	/**
	 * 从数据库获取指定 model的记录条数
	 * 
	 * @return int
	 */
	public function count() : int
	{
		return $this->get()->count();
	}

	/**
	 * 写入新的记录到数据库
	 * 
	 * @param  array  $data 
	 * 
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function create(array $data)
	{
		$this->unsetClauses();

		return $this->model->create($data);
	}
	/**
	 * 写入一条或者多条记录到数据库
	 * 
	 * @param  array  $data
	 * 
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function createMultiple(array $data)
	{
		$models = new Collection();

		foreach ($data as $d) {
			$models->push($this->create($d));
		}

		return $models;
	}

	/**
	 * 删除一条指定记录
	 * 
	 * @return [type] [description]
	 */
	public function delete()
	{
		$this->newQuery()->setClauses()->setScopes();

		$result = $this->query->delete();

		$this->unsetClauses();

		return $result;
	}

    /**
     * 说明: 删除指定的数据库记录
     *
     * @param $id
     * @return bool
     * @throws \Exception
     * @author 罗振
     */
	public function deleteById($id) : bool
	{
		$this->unsetClauses();

		return $this->getById($id)->delete();
	}

	/**
	 * 删除指定的多条记录
	 * 
	 * @param  array  $ids 
	 * @return int
	 */
	public function deleteMultipleById(array $ids) : int
	{
		return $this->model->destory($ids);
	}

	/**
	 * 从数据库获取指定的一条数据
	 * 
	 * @param  array  $columns
	 * @return Model| static
	 */
	public function first(array $columns = ['*'])
	{
		$this->newQuery()->eagerLoad()->setClauses()->setScopes();

		$model = $this->query->firstOrFail($columns);

		$this->unsetClauses();

		return $model;
	}

	/**
	 * 从数据库获取指定条件的所有记录
	 * 
	 * @param  array  $columns
	 * @return Collection |static[]
	 */
	public function get(array $columns = ['*'])
	{
		$this->newQuery()->eagerLoad()->setClauses()->setScopes();

		$models = $this->query->get($columns);

		$this->unsetClauses();

		return $models;
	}

	/**
	 *
	 *
	 * @param  [type] $id
	 * @param  array  $columns
	 * @return Collection | model
	 */
	public function getById($id, array $columns = ['*'])
	{
		$this->unsetClauses();

		$this->newQuery()->eagerLoad();

		return $this->query->findOrFail($id, $columns);
	}

	/**
	 * [getByColumn description]
	 * @param  [type] $item    [description]
	 * @param  [type] $column  [description]
	 * @param  array  $columns [description]
	 * @return [type]          [description]
	 */
	public function getByColumn($item, $column, array $columns = ['*'])
	{
		$this->unsetClauses();

		$this->newQuery()->eagerLoad();

		return $this->query->where($column, $item)->first();
	}

	/**
	 * 获取分页数据
	 * 
	 * @param  integer $limit    [description]
	 * @param  array   $columns  [description]
	 * @param  string  $pageName [description]
	 * @param  [type]  $page     [description]
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function paginate($limit = 25, array $columns = ['*'], $pageName = 'page', $page = null)
	{
		$this->newQuery()->eagerLoad()->setClauses()->setScopes();

		$models = $this->query->paginate($limit, $columns, $pageName, $page);

		$this->unsetClauses();

		return $models;
	}

	/**
	 * 更新指定的数据库记录
	 * 
	 * @param  [type] $id      [description]
	 * @param  array  $data    [description]
	 * @param  array  $options [description]
	 * @return  Collection | Model
	 */
	public function updateById($id, array $data, array $options = [])
	{
		$this->unsetClauses();

		$model = $this->getById($id);

		$model->update($data, $options);

		return $model;
	}

	/**
	 * 设置查询限制条数
	 * 
	 * @param  [int] $limit [description]
	 * 
	 * @return $this
	 */
	public function limit($limit)
	{
		$this->take = $limit;

		return $this;
	}

	/**
	 * 设置一个排序闭包参数
	 * 
	 * @param  [type] $column    [description]
	 * @param  string $direction [description]
	 * @return $this
	 */
	public function orderBy($column, $direction = 'asc')
	{
		$this->orderBys[] = compact('column', 'direction');

		return $this;
	}

	/**
	 * 添加一个单条件查询条件参数
	 *
	 * @param  [type] $column   [description]
	 * @param  [type] $value    [description]
	 * @return $this
	 */
	public function where()
	{
        $args = func_get_args();
        if (empty($args)) return $this;

        // 参数为数组
        if(is_array($args[0])) {
            $constructors = array();
            foreach ($args[0] as $key => $v) {
                $constructor = array();
                $constructor['column'] = $key;
                $constructor['value'] = $v;
                $constructor['operator'] = '=';
                $constructors[] = $constructor;
            }
            $this->wheres = array_merge($this->wheres, $constructors);
        } elseif (count($args)==2){
            // 参数为两个
            $column = $args[0];
            $value = $args[1];
            $operator = '=';
            $this->wheres[] = compact('column', 'value', 'operator');
        } elseif (count($args)==3) {
            // 参数为三个
            $column = $args[0];
            $value = $args[2];
            $operator = $args[1];
            $this->wheres[] = compact('column', 'value', 'operator');
        }
		return $this;
	}

	/**
	 * 添加一个单条件查询条件参数
	 * 
	 * @param  [type] $column [description]
	 * @param  [type] $values [description]
	 * @return [type]         [description]
	 */
	public function whereIn($column, $values)
	{
		$values = is_array($values) ?? [$values];

		$this->whereIns[] = compact('column', 'values');

		return $this;
	}

	/**
	 * 设置关系映射的热加载
	 * 
	 * @param  [type] $relations [description]
	 * 
	 * @return $this
	 */
	public function with($relations)
	{
		if(is_string($relations)){
			$relations = func_get_args();
		}

		$this->with = $relations;

		return $this;
	}
	/**
	 *	创建一个新的 model 查询构建器实例
	 * 
	 * @return $this
	 */
	protected function newQuery()
	{
		$this->query = $this->model->newQuery();

		return $this;
	}

	/**
	 * 添加查询构造器 对应关系 热加载
	 * 
	 * @return $this
	 */
	protected function eagerLoad()
	{
		foreach ($this->with as $relation) {
			$this->query->with($relation);
		}

		return $this;
	}

	/**
	 * 重置查询构造器查询参数数组
	 * 
	 * @return [type] [description]
	 */
	protected function unsetClauses()
	{
		$this->wheres = [];
		$this->whereIns = [];
		$this->scopes = [];
		$this->take = null;

		return $this;
	}

	/**
	 * 设置查询构建器的闭包属性
	 */
	protected function setClauses()
	{
		foreach ($this->wheres as $where) {
			$this->query->where($where['column'], $where['operator'], $where['value']);
		}

		foreach ($this->whereIns as $whereIn) {
			$this->query->whereIn($whereIn['column'], $whereIn['values']);
		}

		foreach($this->orderBys as $orders){
			$this->query->orderBy($orders['column'], $orders['direction']);
		}

		if(isset($this->take) and ! is_null($this->take)){
			$this->query->take($this->take);
		}

		return $this;
	}

	/**
	 * 设置查询作用域
	 */
	protected function setScopes()
	{
		foreach ($this->scopes as $method => $args) {
			$this->query->$method(implode(', ',$args));
		}

		return $this;
	}




}