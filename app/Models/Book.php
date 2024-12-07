<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'book_name',
        'author',
        'description',
        'cover_image',
        'status'
    ];

    public function borrows()
    {
        return $this->morphMany(Borrower::class, 'borrowable');
    }

    public function currentBorrower()
    {
        return $this->morphMany(Borrower::class, 'borrowable')
            ->whereNull('returned_at');
    }

}
