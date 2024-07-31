<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Summary of Supgategory
 */
class Supgategory extends Model
{
    use HasFactory;
    /**
     * Summary of table
     * @var string
     */
    protected $table = 'supgategories';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
 	  /**
 	   * Summary of fillable
 	   * @var array
 	   */
    protected $fillable = array('supName','categoryId','user_id');

	/**
	 * Summary of table
	 * @return string
	 */
	public function getTable() {
		return $this->table;
	}

	/**
	 * Summary of table
	 * @param string $table Summary of table
	 * @return self
	 */
	public function setTable($table): self {
		$this->table = $table;
		return $this;
	}
}
