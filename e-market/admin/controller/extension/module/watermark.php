<?php

/*
    Module: Watermark for OpenCart 2.3
    Author: webengenier@gmail.com
    Version: 1.0
    Date: Y.2015 M.11 D.24
      
*/

class ControllerExtensionModuleWatermark extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/module/watermark');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/watermark');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            //We need image size
            $info = getimagesize(DIR_IMAGE . $this->request->post['image']);
            $data['size_x'] = $info[0];
            $data['size_y'] = $info[1];
            $data['active'] = (int)$this->request->post['active'];
            $data['image'] = $this->request->post['image'];
            $data['zoom'] = (string)$this->request->post['zoom'];
            $data['pos_x'] = (int)$this->request->post['pos_x'];
            $data['pos_y'] = (int)$this->request->post['pos_y'];
            $data['pos_x_center'] = (int)$this->request->post['pos_x_center'];
            $data['pos_y_center'] = (int)$this->request->post['pos_y_center'];
            $data['opacity'] = (string)round((float)$this->request->post['opacity'],1);
            $data['category_image'] = (int)$this->request->post['category_image'];
            $data['product_thumb'] = (int)$this->request->post['product_thumb'];
            $data['product_popup'] = (int)$this->request->post['product_popup'];
            $data['product_list'] = (int)$this->request->post['product_list'];
            $data['product_additional'] = (int)$this->request->post['product_additional'];
            $data['product_related'] = (int)$this->request->post['product_related'];
            $data['product_in_compare'] = (int)$this->request->post['product_in_compare'];
            $data['product_in_wish_list'] = (int)$this->request->post['product_in_wish_list'];
            $data['product_in_cart'] = (int)$this->request->post['product_in_cart'];

            foreach ($data as $key => $value) {
                $this->model_extension_module_watermark->change( $key, $value );
            }

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

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

        $data['heading_title'] = $this->language->get('heading_title_for_oc');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/watermark', 'token=' . $this->session->data['token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/watermark', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		$data['action'] = $this->url->link('extension/module/watermark', 'token=' . $this->session->data['token'], true);
		$data['clear'] = $this->url->link('extension/module/watermark/clear', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_clear'] = $this->language->get('button_clear');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');

        $data['options'] = $this->model_extension_module_watermark->getOptions();
        unset($data['options']['size_x']);
        unset($data['options']['size_y']);
        $data['options_lang'] = array();
        foreach ($data['options'] as $key => $value) {
            $data['options_lang'][$key] = $this->language->get($key);
        }

        $data['active'] = $data['options']['active'];
		
        $this->load->model('tool/image');
        $data['thumb'] = $this->model_tool_image->resize($data['options']['image'], 100, 100);
        $data['placeholder'] = $this->model_tool_image->resize($data['options']['image'], 250, 250);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/watermark', $data));
    }

	public function clear() {
		$this->load->language('extension/modification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/modification');

		if ($this->validate()) {
			$files = array();

			// Make path into an array
			$path = array(DIR_IMAGE . 'cache/*');

			// While the path array is still populated keep looping through
			while (count($path) != 0) {
				$next = array_shift($path);

				foreach (glob($next) as $file) {
					// If directory add to path array
					if (is_dir($file)) {
						$path[] = $file . '/*';
					}

					// Add the file to the files to be deleted array
					$files[] = $file;
				}
			}

			// Reverse sort the file array
			rsort($files);

			// Clear all modification files
			foreach ($files as $file) {
				if ($file != DIR_IMAGE . 'cache/index.html') {
					// If file just delete
					if (is_file($file)) {
						unlink($file);

					// If directory use the remove directory function
					} elseif (is_dir($file)) {
						rmdir($file);
					}
				}
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			$this->response->redirect($this->url->link('extension/module/watermark', 'token=' . $this->session->data['token'] . $url, true));
		}

	}

    public function install() {

        $this->load->model('extension/module/watermark');
        $this->model_extension_module_watermark->install();

    }

    public function uninstall() {

        $this->load->model('extension/module/watermark');
        $this->model_extension_module_watermark->uninstall();

    }

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/watermark')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}