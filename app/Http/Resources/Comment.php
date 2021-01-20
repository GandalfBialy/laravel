<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CommentUser as CommentUserResource;

class Comment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'created_at_str' => (string)$this->created_at,
            'updated_at' => $this->updated_at,
            // 'user' => new CommentUserResource($this->user),
            'user' => new CommentUserResource($this->whenLoaded('user')),
            // 'user' => [
            //     'id' => $this->user->id,
            // ]
        ];
    }
}
