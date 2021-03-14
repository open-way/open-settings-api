<?php

namespace App\Http\Resources\SetupSystem;

use Illuminate\Http\Resources\Json\JsonResource;
use Jenssegers\Date\Date;

class Modulo extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */

     public function toArray($request)
    {
        return [
            'modulo_id' => $this->modulo_id,
            'activo' => $this->activo,
            'icon' => $this->icon,
            'link' => $this->link,
            'title' => $this->title,
            'order' => $this->order,
            'group' => $this->group,
            'home' => $this->home,
            'parent_id' => $this->parent_id,
            'parent_id' => $this->parent_id,
            'is_mobile' => $this->is_mobile,
            'codigo' => $this->codigo,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
