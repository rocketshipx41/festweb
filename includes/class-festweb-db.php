<?php

class Festweb_Db
{

    private $festweb_db = NULL;
    private $last_query = '';

    public function __construct( $db_name )
    {
        $this->festweb_db = new wpdb(DB_USER, DB_PASSWORD, $db_name, DB_HOST);
//        echo 'init ' . $this->biblio_db->last_error; exit;
    }

    public function get_last_query()
    {
        return $this->last_query;
    }

    public function get_last_error()
    {
        return $this->last_error;
    }

    public function get_festival_details( $slug, $include_artists = FALSE )
    {
        $result = NULL;
        $query = $this->festweb_db->prepare("SELECT name, slug, description, startdate, enddate, history, 
current, sequence, show_schedule FROM festival WHERE slug = %s", $slug);
        $this->last_query = $query;
        $result_row = $this->festweb_db->get_row($query);
//        echo $result_row;
        if ( $result_row ) {
            $result = new Festweb_Festival($result_row);
            if ( $include_artists ) {
                $result->artists = $this->get_festival_artists( $slug ) ;
            }
        }
        return $result;
    }

    public function get_festival_artists( $slug )
    {
        $result = array();
        $query = $this->festweb_db->prepare("SELECT artist_slug, artist, photo, artist_description, personnel 
from vw_festival_artist where festival_slug = %s order by artist", $slug);
        $this->last_query = $query;
        foreach ($this->festweb_db->get_results($query) as $row) {
            $result[] = $row;
        }
        return $result;
    }

    public function get_artist_details( $artist_slug )
    {
        $result = NULL;
        $query = $this->festweb_db->prepare("SELECT id, slug, name, photo, description, personnel, video 
from artist where slug = %s", $artist_slug);
        $this->last_query = $query;
        $result_row = $this->festweb_db->get_row($query);
//        echo $result_row;
        if ( $result_row ) {
            $result = new Festweb_Artist($result_row);
            $result->links = $this->get_artist_links($result->id);
            $result->performances = $this->get_artist_performances($result->id);
        }
        return $result;
    }

    public function get_artist_links( $artist_id )
    {
        $result = array();
        $query = $this->festweb_db->prepare("SELECT distinct url, `default`, name, artist_id 
from vw_artist_links where artist_id = %s", $artist_id);
        $this->last_query = $query;
        foreach ($this->festweb_db->get_results($query) as $row) {
            $result[] = $row;
        }
        return $result;
    }

    public function get_artist_performances( $artist_id )
    {
        $result = array();
        $query = $this->festweb_db->prepare("SELECT event_id, artist, photo, event, festival, festival_slug 
from vw_festival_artist where artist_id = %s", $artist_id);
        $this->last_query = $query;
        foreach ($this->festweb_db->get_results($query) as $row) {
            $result[] = $row;
        }
        return $result;
    }

}
