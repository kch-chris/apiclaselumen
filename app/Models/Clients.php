<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{ 
    protected $table="clients";
    protected $primaryKey = 'clientsID';

    protected $fillable = [
        'name' , 'lastname', 'email', 'phone', 'age', 'sex', 'credit'
    ];   
}
