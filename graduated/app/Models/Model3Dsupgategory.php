<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Summary of Model3Dsupgategory
 */
class Model3Dsupgategory extends Model
{
    use HasFactory;
    /**
     * Summary of table
     * @var string
     */
    protected $table = 'model3d_supgategories';
    protected $fillable=['supCategoryId','models3DId'];
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

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
