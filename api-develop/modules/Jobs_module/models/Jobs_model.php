<?php
// donot cast to int while the column is varchar. it will cause so many security hole. juggling type
if( ! defined('BASEPATH')) exit ('No direct script access allowed');

class Jobs_model extends CI_Model
{
    public function __construct()
    {
        if (ENVIRONMENT == "development")
        $this->db->conn_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    function check($check, $graph)
    {
        try
        {
            $this->db->select($graph->select)->from('tbl_jobs');
            $this->db->where(array('jobs_code' => $check));

            if (!empty($graph->filter))
            {
                foreach ($graph->filter as $field => $each) {
                    $this->db->group_start();
                    foreach ($each as $key => $value) {
                        $this->db->or_where($field, $value);
                    }
                    $this->db->group_end();
                }
            }
            $query = $this->db->limit(1)->get();
        }   
        catch (PDOException $e){
            log_message("error", 'Error check: '.$check."<||>" . $e->getMessage());
            throw new PDOException($e->getMessage(), 1);
        }
        $row = $query->row();
        
        return $row;
    }


    function get_job_by_id($job_id, $graph)
    {
        try
        {
            $this->db->select($graph->select)->from('tbl_jobs');
            $this->db->where(array('jobs_id' => $job_id));

            // filter    
            if (!empty($graph->filter))
            {
                foreach ($graph->filter as $field =>    $each) {
                    $this->db->group_start();
                    foreach ($each as $key => $value) {
                        $this->db->or_where($field,     $value);
                    }
                    $this->db->group_end();
                }
            }

        $query = $this->db->limit(1)->get();
        }
        catch (PDOException $e) {
            log_message("error", 'Error get_job_by_id: '.$tuk_id."<||>".$e->getmessage());
            throw new PDOException($e->getMessage(),1);
        }

        $row = $query->row();

        return $row;
    }

    function get_job_list($graph)
    {

        try{
            //select
            $this->db->select($graph->select)->from('tbl_jobs');

            //datetime filter
            if (!empty($graph->created_time))
            {

                $this->db->group_start();
                $this->db->where(" created_date BETWEEN '".$graph->created_time[0]."' AND '".$graph->created_time[1]."'", NULL, FALSE);
                $this->db->group_end();

            }

            if (!empty($graph->modified_time))
            {
                $this->db->or_group_start();
                $this->db->where(" modified_date BETWEEN '".$graph->modified_time[0]."' AND '".$graph->modified_time[1]."'", NULL, FALSE);
                $this->db->group_end();
            }

            // filter
            if (!empty($graph->filter))
            {
                foreach($graph->filter as $field => $each) {
                    $this->db->group_start();
                    foreach ($each as $key => $value) {
                        $this->db->or_where($field, $value);
                    }
                    $this->db->group_end();
                }
            }

            // filter in
            if (!empty($graph->filter_in))
            {
                foreach ($graph->filter_in as $field => $value) {
                    $this->db->group_start();
                    $this->db->or_where_in($field, $value);
                    $this->db->group_end();
                }
            }

            // filter not in
            if (!empty($graph->filter_not_in))
            {
                foreach ($graph->filter_not_in as $field => $value) {
                    $this->db->group_start();
                    $this->db->or_where_not_in($field, $value);
                    $this->db->group_end();
                }
            }

            // search
            if (!empty($graph->search))
            {
                foreach ($graph->search as $key => $value) {
                    $this->db->group_start();
                    $this->db->or_like($value, NULL, 'after');
                    $this->db->group_end();
                }
            }

            // grouping
            if (!empty($graph->group))
            {
                $this->db->group_by($graph->group);
            }

            // sorting
            if (!empty($graph->sort))
            {
                foreach ($graph->sort as $key => $value) {
                    $this->db->order_by($key, $value);
                }
            }
            
            // limit
            $this->db->limit($graph->limit, $graph->offset);

            // execute
            $query = $this->db->get();
        }
        catch (PDOException $e) {
            log_message("error", 'Error get_job_list: '.json_encode($graph)."<||>". $e->getMessage());
            throw new PDOException($e->getMessage(),1);
        }

        $row = $query->result();

        return $row;
    }

    function create_job($params, $auto_commit = TRUE)
    {
        try
        {
            if ($auto_commit) $this->db->trans_start();
            $job_id = $this->db->insert('tbl_jobs', $params);
        }
        catch (PDOException $e) {
            log_message("error", 'Error create_job:'.json_encode($params)."<||>".$e->getMessage());
            $this->db->trans_rollback();
            throw new PDOException($e->getMessage(), 1);
        }

        $job_id = $this->db->insert_id();
        if ($auto_commit) $this->db->trans_complete();
        return $job_id;
    }

    function get_job_count($graph)
    {
        try
        {
            //  select
            $this->db->select("COUNT(*) as count", FALSE)
                ->from('tbl_jobs');

            //  datetime filter
            if  (!empty($graph->created_time))
            {
                $this->db->group_start();
                $this->db->where(" created_date BETWEET '".$graph->created_time[0]."' AND '".$graph->created_time[1]."'", NULL, FALSE);
                $this->db->group_end();
            }

            if  (!empty($graph->modified_time))
            {
                $this->db->or_group_start();
                $this->db->where(" modified_date BETWEEN '".$graph->modified_time[0]."' AND '".$graph->modified_time[1]."'", NULL, FALSE);
                $this->db->group_end();
            }

            //  filter

            if  (!empty($graph->filter))
            {
                foreach ($graph->filter as $field => $each) {
                    $this->db->group_start();
                    foreach ($each as $key => $value) {
                        $this->db->or_where($field, $value);
                    }
                    $this->db->group_end();
                }
            }

            //  search
            if  (!empty($graph->search))
            {
                foreach ($graph->search as $key => $value) {
                    $this->db->group_start();
                    $this->db->or_like($value, NULL, 'after'); // pro index
                    $this->db->group_end();
                }
            }

            //  grouping
            if  (!empty($graph->sort))
            {
                $this->db->group_by($graph->group);
            }

            //  sorting
            if  (!empty($graph->sort))
            {
                foreach ($graph->sort as $key => $value) {
                    $this->db->order_by($key, $value);
                }
            }

            //  execute
            $query = $this->db->get();
        }
        catch (PDOException $e) {
            log_message("error", 'Error get_job_count: '.json_encode($graph)."<||>" . $e->getMessage());
            throw new PDOException($e->getMessage(),1);
        }

        $row = $query->row();

        return $row;
    }
}