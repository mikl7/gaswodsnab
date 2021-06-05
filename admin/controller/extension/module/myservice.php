<?php

class ControllerExtensionModuleMyservice extends Controller
{
    private $error = array();


    public function index()
    {
        $this->load->language('extension/module/myservice');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/myservice/myservice');


        $this->getList();
    }

    public function add()
    {
        $this->load->language('extension/module/myservice');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/myservice/myservice');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_extension_myservice_myservice->addService($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';


            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('extension/module/myservice', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function edit()
    {
        $this->load->language('extension/module/myservice');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/myservice/myservice');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_extension_myservice_myservice->editService($this->request->get['service_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';


            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('extension/module/myservice', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getForm();
    }

    public function delete()
    {
        $this->load->language('extension/module/myservice');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/myservice/myservice');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $service_id) {
                $this->model_extension_myservice_myservice->deleteService($service_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';


            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('extension/module/myservice', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getList();
    }

    protected function getForm()
    {
        $data['text_form'] = !isset($this->request->get['service_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = array();
        }

        if (isset($this->error['meta_title'])) {
            $data['error_meta_title'] = $this->error['meta_title'];
        } else {
            $data['error_meta_title'] = array();
        }

        if (isset($this->error['keyword'])) {
            $data['error_keyword'] = $this->error['keyword'];
        } else {
            $data['error_keyword'] = '';
        }

        $url = '';


        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/myservice', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        if (!isset($this->request->get['service_id'])) {
            $data['action'] = $this->url->link('extension/module/myservice/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        } else {
            $data['action'] = $this->url->link('extension/module/myservice/edit', 'user_token=' . $this->session->data['user_token'] . '&service_id=' . $this->request->get['service_id'] . $url, true);
        }

        $data['cancel'] = $this->url->link('extension/module/myservice', 'user_token=' . $this->session->data['user_token'] . $url, true);

        if (isset($this->request->get['service_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $service_info = $this->model_extension_myservice_myservice->getService($this->request->get['service_id']);
        }

        $data['user_token'] = $this->session->data['user_token'];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();


        if (isset($this->request->post['service_description'])) {
            $data['service_description'] = $this->request->post['service_description'];
        } elseif (isset($this->request->get['service_id'])) {
            $data['service_description'] = $this->model_extension_myservice_myservice->getServiceDescriptions($this->request->get['service_id']);
        } else {
            $data['service_description'] = array();
        }


        if (isset($this->request->post['price'])) {
            $data['price'] = $this->request->post['price'];
        } elseif (!empty($service_info)) {
            $data['price'] = $service_info['price'];
        } else {
            $data['price'] = '';
        }



        // Image
        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($service_info)) {
            $data['image'] = $service_info['image'];
        } else {
            $data['image'] = '';
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($service_info) && is_file(DIR_IMAGE . $service_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($service_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);



        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/myservice_form', $data));
    }
    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'extension/module/myservice')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['service_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 255)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }

            if ((utf8_strlen($value['meta_title']) < 1) || (utf8_strlen($value['meta_title']) > 255)) {
                $this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'extension/module/myservice')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }


    protected function getList()
    {

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'sd.name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';



        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/myservice', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );



        $data['add'] = $this->url->link('extension/module/myservice/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['delete'] = $this->url->link('extension/module/myservice/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);



        $data['services'] = array();


        $filter_data = array(
            'sort'            => $sort,
            'order'           => $order,
            'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'           => $this->config->get('config_limit_admin')
        );

        $this->load->model('tool/image');

        $service_total = $this->model_extension_myservice_myservice->getTotalServices($filter_data);

        $results = $this->model_extension_myservice_myservice->getServices($filter_data);


        foreach ($results as $result) {
            if (is_file(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], 40, 40);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 40, 40);
            }

            $data['services'][] = array(
                'service_id'    => $result['service_id'],
                'image'         => $image,
                'name'          => $result['name'],
                'description'   => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 200),
                'price'         => $this->currency->format($result['price'], $this->config->get('config_currency')),
                'edit'          => $this->url->link('extension/module/myservice/edit', 'user_token=' . $this->session->data['user_token'] . '&service_id=' . $result['service_id'] . $url, true)
            );
        }

        $data['user_token'] = $this->session->data['user_token'];


        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';


        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('extension/module/myservice', 'user_token=' . $this->session->data['user_token'] . '&sort=sd.name' . $url, true);
        $data['sort_price'] = $this->url->link('extension/module/myservice', 'user_token=' . $this->session->data['user_token'] . '&sort=s.price' . $url, true);
        $data['sort_order'] = $this->url->link('extension/module/myservice', 'user_token=' . $this->session->data['user_token'] . '&sort=s.sort_order' . $url, true);

        $url = '';



        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $service_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('extension/module/myservice', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($service_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($service_total - $this->config->get('config_limit_admin'))) ? $service_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $service_total, ceil($service_total / $this->config->get('config_limit_admin')));


        $data['sort'] = $sort;
        $data['order'] = $order;


        $this->model_extension_myservice_myservice;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/myservice', $data));
    }
}
