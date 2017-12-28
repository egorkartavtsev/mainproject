<?php

class ControllerDonorList extends Controller {
    public function index(){
        $this->load->model("common/donor");
        $filter = array();
        $donors = $this->model_common_donor->getDonors($filter);
        $this->load->model('tool/image');
        foreach ($donors as $donor) {
            if (is_file(DIR_IMAGE.$donor['image'])) {
                    $image = $this->model_tool_image->resize($donor['image'], 40, 40);
            } else {
                    $image = $this->model_tool_image->resize('no_image.png', 40, 40);
            }
            $data['donors'][] = array(
                'numb' => $donor['numb'],
                'image' => $image,
                'vin' => $donor['vin'],
                'dvs' => $donor['dvs'],
                'price' => $donor['price'],
                'color' => $donor['color'],
                'ctype' => $donor['ctype'],
                'year' => $donor['year'],
                'brand' => $donor['brand'],
                'model' => $donor['model'],
                'mod_row' => $donor['mod_row'],
                'kmeters' => $donor['kmeters'],
                'trmiss' => $donor['trmiss'],
                'priv' => $donor['priv'],
                'name' => $donor['name'],
                'delete' => $this->url->link('donor/list/delete', 'token=' . $this->session->data['token'].'&donor_id='.$donor['id'], true),
                'edit' => $this->url->link('donor/edit', 'token=' . $this->session->data['token'].'&donor_id='.$donor['id'], true)
            );
        }
        $this->document->setTitle('Список доноров');

        $data['heading_title'] = 'Список доноров';

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
                'text' => 'Главная',
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
                'text' => 'Список доноров',
                'href' => $this->url->link('donor/list', 'token=' . $this->session->data['token'], true)
        );
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['token_add'] = $this->session->data['token'];
        
        $this->response->setOutput($this->load->view('donor/list', $data));
    }
    
    public function delete() {
        $id = $this->request->get['donor_id'];
        $this->load->model("common/donor");
        $this->model_common_donor->deleteDonor($id);
        $this->response->redirect($this->url->link('donor/list', 'token=' . $this->session->data['token'], true));
    }
}

