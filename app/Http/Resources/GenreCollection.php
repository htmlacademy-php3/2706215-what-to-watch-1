<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GenreCollection extends ResourceCollection
{
    public $collects = GenreResource::class;
}
