<?php

namespace App\Models;

use DateTime;

class Post extends Model
{

    protected $table = 'tags';

    public function getTags()
    {
        return $this->query("
        SELECT p.*FROM posts p
        INNER JOIN post_tag pt ON pt.post_id = p.id
        WHERE pt.tag_id = ?
        ", $this->id);
    }
    /*public $id;
    //public $title;
    //public $content;
    //public $created_at;*/

}
