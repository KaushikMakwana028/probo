<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class General_model extends CI_Model {

	public function insert($table, $data)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}

	public function update($table, $data, $where)
	{
		$this->db->where($where);
		return $this->db->update($table, $data);
	}

	public function getrow($table, $where = array())
	{
		if (!empty($where)) {
			$this->db->where($where);
		}

		return $this->db->get($table)->row();
	}

	public function getall($table, $where = array(), $order_by = '', $order = 'DESC')
	{
		if (!empty($where)) {
			$this->db->where($where);
		}

		if ($order_by !== '') {
			$this->db->order_by($order_by, $order);
		}

		return $this->db->get($table)->result();
	}

	public function delete($table, $where)
	{
		$this->db->where($where);
		return $this->db->delete($table);
	}
}
