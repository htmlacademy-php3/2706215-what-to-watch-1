<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FilmListCollection extends ResourceCollection
{
    public $collects = FilmListResource::class;
}
