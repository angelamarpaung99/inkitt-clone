<?php defined('BASEPATH') OR exit('No direct script access allowed');

class baca_model extends CI_Model
{
    private $_table = "isi_novel";

    public function getAll($id)
    {
        $this->db->where('id',$id);
        return $this->db->get($this->_table)->result();
    }

}
