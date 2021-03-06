<?php
class View extends CI_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->load->model("novel_model");
		$this->load->library("form_validation");
	}
    
    public function view()
	{

        $data['judul'] = 'Novels list';
        $data['isi_novel'] = $this->novel_model->getAllNovel();
		if ($this->input->post('keyword')) {
			$data['isi_novel'] = $this->novel_model->cariDataNovel();
		}
		$this->load->view('headerlogged', $data);
		$this->load->view('view', $data);
		$this->load->view('Landing/Footer');
    }

    public function tambah()
	{
		$data['judul'] = 'Add Novels';

		$this->form_validation->set_rules('title', 'Title','required');
		$this->form_validation->set_rules('auth', 'Author','required');
        $this->form_validation->set_rules('content', 'content','required');
        $this->form_validation->set_rules('harga', 'harga','required');


        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 1024;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;

        $this->load->library('upload', $config);

		if ($this->form_validation->run() == false) {
			$this->load->view('headerlogged', $data);
			$this->load->view('add');
			$this->load->view('Landing/Footer');
		}else{
            if ( ! $this->upload->do_upload('images'))
                {
                        $error = array('error' => $this->upload->display_errors());

                        $this->load->view('headerlogged', $data);
			            $this->load->view('add', $error);
			            $this->load->view('Landing/Footer');
                }
                else
                {
                        $upload_data = $this->upload->data();

                        $this->novel_model->tambahNovel($upload_data['file_name']);
			            $this->session->set_flashdata('flash','added success');
			            redirect('View/view');
                }
		}
    }
    
    public function hapus($id)
	{
		$this->novel_model->hapusNovel($id);
		$this->session->set_flashdata('flash', 'deleted');
		redirect('View/view');
    }
    
    public function ubah($id)
	{
		$data['judul'] = 'Edit Novel';

        $data['isi_novel'] = $this->novel_model->getNovelById($id);
        $data['id_novel'] = $id;

		$this->form_validation->set_rules('title', 'Title','required');
		$this->form_validation->set_rules('auth', 'Author','required');
        $this->form_validation->set_rules('content', 'content','required');
        $this->form_validation->set_rules('harga', 'harga','required');



        if ($this->form_validation->run() == false) {
			$this->load->view('headerlogged', $data);
			$this->load->view('edit', $data);
			$this->load->view('Landing/Footer');
		}else{
            $hasil = $this->novel_model->ubahNovel($this->input->post('id'));
            var_dump($this->input->post('id'));

			            $this->session->set_flashdata('flash','edited success');
			            redirect('View/view');
		}
	}
}