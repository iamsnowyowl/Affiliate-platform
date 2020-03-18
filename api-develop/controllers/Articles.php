<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Articles extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_article_detail($article_id)
	{
		$this->my_parameter = $this->parameter;

		$data = $this->article_detail($article_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_article_list() 
	{
		$this->my_parameter = $this->parameter;
		$data = $this->article_list();	
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function article_detail($article_id)
	{
		$articles = modules::run("Article_module/get_article_by_id", $this->my_parameter, $article_id);

		$this->load->helper("url");

		if ($articles === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ARTICLE",
							"reason" => "ArticleNotFound"
						),
					)
				)
			);
		}

		return array("data" => $articles);
	}

	protected function article_list()
	{
		return modules::run("Article_module/get_article_list", $this->my_parameter);
	}

	public function get_article_count() 
	{
		$this->my_parameter = $this->parameter;

		$data = $this->article_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function article_count()
	{
		$count = modules::run("Article_module/get_article_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create article
	public function create_article_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_article();
	}

	public function create_article_session()
	{
		if (!modules::run("Permission_module/require_permission", "ARTICLE_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ARTICLE_CREATE");
		
		$this->my_parameter = $this->parameter;
		$created_by = $this->userdata['user_id'];

		$this->create_article($created_by);
	}

	protected function create_article($created_by = 0)
	{
		$article_id = modules::run("Article_module/create_article", $this->my_parameter, $created_by);
			
		if ($article_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ARTICLE",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("article_id" => $article_id));

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_article_by_id($article_id)
	{
		$article = modules::run("Article_module/get_article_by_id", array(), $article_id);
		
		if (!(modules::run("Permission_module/require_permission", "ARTICLE_CREATE_OWN", FALSE) && $article->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ARTICLE_UPDATE");
		
		$this->my_parameter = $this->parameter;
		
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_article($article_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ARTICLE",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_article($article, $modified_by)
	{
		return modules::run("Article_module/update_article_by_id", $article, $this->my_parameter, $modified_by);
	}

	public function delete_soft_article_by_id($article_id)
	{
		$article = modules::run("Article_module/get_article_by_id", array(), $article_id);

		if (!(modules::run("Permission_module/require_permission", "ARTICLE_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ARTICLE_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_article($article_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ARTICLE",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_article($article_id, $modified_by = 0)
	{
		return modules::run("Article_module/delete_soft_article_by_id", $article_id, $modified_by);
	}

	public function delete_hard_article_by_id($article_id, $confirmation)
	{
		$article = modules::run("Article_module/get_article_by_id", array(), $article_id);

		if (!(modules::run("Permission_module/require_permission", "ARTICLE_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ARTICLE_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_article($article_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ARTICLE",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_article($article_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Article_module/delete_hard_article_by_id", $article_id, $confirmation, $modified_by);
	}
}


