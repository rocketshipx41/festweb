<?php

class Festweb_Artist
{
    public $id = '0';
    public $slug = '';
    public $name = '';
    public $photo = '';
    public $description = '';
    public $personnel = '';
    public $video = '';
    public $links = array();
    public $performances = array();

    public function __construct( $artist_row  = null)
    {
        if ( is_object( $artist_row ) ) {
            $this->id = $artist_row->id;
            $this->slug = $artist_row->slug;
            $this->name = $artist_row->name;
            $this->photo = $artist_row->photo;
            $this->description = $artist_row->description;
            $this->personnel = $artist_row->personnel;
            $this->video = $artist_row->video;
        }
    }

}
