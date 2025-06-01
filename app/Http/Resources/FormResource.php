<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $json_data = [
            'title'         => $this->title,
            'method'        => $this->method,
            'action'        => $this->action,
            'fields'        => []
        ];
        foreach ($this->configs as $config) {
            $json_data['fields'][] = [
                'type'        => $config->type,
                'name'        => $config->name,
                'label'       => $config->label,
                'placeholder' => $config->placeholder,
                'required'    => (bool) $config->required,
            ];
        }
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'method'        => $this->method,
            'action'        => $this->action,
            'configs_count' => $this->configs_count,
            'json_data'     => json_encode($json_data),
        ];
    }
}
