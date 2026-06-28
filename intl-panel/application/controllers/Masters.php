<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Masters extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        // Access Control (Only Super Admin can view master settings)
        if ($this->session->userdata('role_id') != 1) {
            $this->session->set_flashdata('error', 'Access Denied. Only Super Admin can access Master Settings.');
            redirect('dashboard');
        }
        $this->load->model('Master_model');
        $this->load->model('Audit_model');
    }

    // --- BRANCHES ---
    public function branches() {
        $data['page_title'] = 'Manage Branches';
        $data['branches'] = $this->Master_model->get_branches();
        
        // Fetch roles to display in the create user modal
        $all_roles = $this->Master_model->get_roles();
        $filtered_roles = array();
        foreach ($all_roles as $r) {
            // Exclude Super Admin (1), Franchise User (3), and Customer (4)
            if ($r->id != 1 && $r->id != 3 && $r->id != 4) {
                $filtered_roles[] = $r;
            }
        }
        $data['roles'] = $filtered_roles;
        
        $data['view_path'] = 'masters/branches_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_branch() {
        $this->form_validation->set_rules('name', 'Branch Name', 'required');
        $this->form_validation->set_rules('branch_code', 'Branch Code', 'required|is_unique[branches.branch_code]');
        $this->form_validation->set_rules('email', 'Email Address', 'valid_email');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Add New Branch';
            $data['view_path'] = 'masters/branch_add';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            $this->Master_model->add_branch($post);
            $this->session->set_flashdata('success', 'Branch created successfully.');
            redirect('branches');
        }
    }

    public function edit_branch($id) {
        $this->form_validation->set_rules('name', 'Branch Name', 'required');
        $this->form_validation->set_rules('email', 'Email Address', 'valid_email');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Edit Branch';
            $data['branch'] = $this->Master_model->get_branches($id);
            $data['view_path'] = 'masters/branch_edit';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            $this->Master_model->update_branch($id, $post);
            $this->session->set_flashdata('success', 'Branch details updated.');
            redirect('branches');
        }
    }

    public function delete_branch($id) {
        if ($this->session->userdata('role_id') != 1) {
            $this->session->set_flashdata('error', 'Only Super Admin can delete records.');
            redirect('branches');
        }
        $this->Master_model->delete_branch($id);
        $this->session->set_flashdata('success', 'Branch deleted.');
        redirect('branches');
    }

    public function create_branch_user() {
        $this->form_validation->set_rules('branch_id', 'Branch', 'required|numeric');
        $this->form_validation->set_rules('role_id', 'Role', 'required|numeric');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[4]|is_unique_active[users.username]');
        $this->form_validation->set_rules('email', 'Email Address', 'required|trim|valid_email|is_unique_active[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $post = $this->input->post(NULL, TRUE);
            
            $user_data = array(
                'username' => $post['username'],
                'email' => $post['email'],
                'password' => $post['password'],
                'role_id' => $post['role_id'],
                'branch_id' => $post['branch_id']
            );
            
            if ($this->Master_model->add_branch_user($user_data)) {
                $this->session->set_flashdata('success', 'Branch user created successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to create branch user.');
            }
        }
        redirect('branches');
    }
    public function branch_users($branch_id) {
        $branch = $this->Master_model->get_branches($branch_id);
        if (!$branch) {
            $this->session->set_flashdata('error', 'Branch not found.');
            redirect('branches');
        }

        $data['page_title'] = 'Users for Branch: ' . $branch->name;
        $data['branch'] = $branch;
        $data['users'] = $this->Master_model->get_branch_users($branch_id);
        
        $all_roles = $this->Master_model->get_roles();
        $filtered_roles = array();
        foreach ($all_roles as $r) {
            if ($r->id != 1 && $r->id != 3 && $r->id != 4) {
                $filtered_roles[] = $r;
            }
        }
        $data['roles'] = $filtered_roles;

        $data['view_path'] = 'masters/branch_users_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function edit_branch_user($user_id) {
        $user = $this->Master_model->get_user($user_id);
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('branches');
        }

        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[4]');
        $this->form_validation->set_rules('email', 'Email Address', 'required|trim|valid_email');
        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
        }
        $this->form_validation->set_rules('role_id', 'Role', 'required|numeric');
        $this->form_validation->set_rules('status', 'Status', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $post = $this->input->post(NULL, TRUE);
            
            // Check unique active only if changed
            if ($post['email'] !== $user->email) {
                $exists = $this->db->get_where('users', array('email' => $post['email'], 'deleted_at IS NULL' => null))->row();
                if ($exists) {
                    $this->session->set_flashdata('error', 'The Email Address is already in use.');
                    redirect('branches/users/' . $user->branch_id);
                }
            }
            if ($post['username'] !== $user->username) {
                $exists = $this->db->get_where('users', array('username' => $post['username'], 'deleted_at IS NULL' => null))->row();
                if ($exists) {
                    $this->session->set_flashdata('error', 'The Username is already in use.');
                    redirect('branches/users/' . $user->branch_id);
                }
            }

            $update_data = array(
                'username' => $post['username'],
                'email' => $post['email'],
                'role_id' => $post['role_id'],
                'status' => $post['status']
            );
            if (!empty($post['password'])) {
                $update_data['password'] = $post['password'];
            }

            if ($this->Master_model->update_user($user_id, $update_data)) {
                $this->session->set_flashdata('success', 'User updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to update user.');
            }
        }
        redirect('branches/users/' . $user->branch_id);
    }
    public function delete_branch_user($user_id) {
        if ($this->session->userdata('role_id') != 1) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('branches');
        }

        $user = $this->Master_model->get_user($user_id);
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('branches');
        }

        if ($this->Master_model->delete_user($user_id)) {
            $this->session->set_flashdata('success', 'User deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user.');
        }
        redirect('branches/users/' . $user->branch_id);
    }

    // --- FRANCHISES ---
    public function franchises() {
        $data['page_title'] = 'Manage Franchises';
        $data['franchises'] = $this->Master_model->get_franchises();
        $data['view_path'] = 'masters/franchises_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_franchise() {
        $this->form_validation->set_rules('name', 'Franchise Name', 'required');
        $this->form_validation->set_rules('franchise_code', 'Franchise Code', 'required|is_unique[franchises.franchise_code]');
        $this->form_validation->set_rules('email', 'Login Email', 'required|valid_email|is_unique_active[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('deposit_amount', 'Deposit Amount', 'numeric');
        $this->form_validation->set_rules('revenue_sharing_percentage', 'Revenue Split', 'numeric');
        $this->form_validation->set_rules('commission_percentage', 'Commission', 'numeric');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Register New Franchise';
            $data['view_path'] = 'masters/franchise_add';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            
            $user_data = array(
                'username' => $post['name'],
                'email' => $post['email'],
                'password' => $post['password'],
                'branch_id' => 1 // Headquarters by default
            );

            $franchise_data = array(
                'name' => $post['name'],
                'franchise_code' => $post['franchise_code'],
                'deposit_amount' => $post['deposit_amount'],
                'agreement_date' => $post['agreement_date'],
                'revenue_sharing_percentage' => $post['revenue_sharing_percentage'],
                'commission_percentage' => $post['commission_percentage'],
                'status' => 'Active'
            );

            $this->Master_model->add_franchise($franchise_data, $user_data);
            $this->session->set_flashdata('success', 'Franchise added successfully.');
            redirect('franchises');
        }
    }

    public function edit_franchise($id) {
        $franchise = $this->Master_model->get_franchises($id);
        if (!$franchise) {
            $this->session->set_flashdata('error', 'Franchise not found.');
            redirect('franchises');
        }

        $this->form_validation->set_rules('name', 'Franchise Name', 'required');
        $this->form_validation->set_rules('deposit_amount', 'Deposit Amount', 'numeric');
        $this->form_validation->set_rules('revenue_sharing_percentage', 'Revenue Split', 'numeric');
        $this->form_validation->set_rules('commission_percentage', 'Commission', 'numeric');
        $this->form_validation->set_rules('email', 'Login Email', 'required|valid_email');
        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('franchises');
        } else {
            $post = $this->input->post(NULL, TRUE);
            
            if ($post['email'] !== $franchise->user_email) {
                $exists = $this->db->get_where('users', array('email' => $post['email'], 'deleted_at IS NULL' => null))->row();
                if ($exists) {
                    $this->session->set_flashdata('error', 'The Login Email is already in use.');
                    redirect('franchises');
                }
            }

            $franchise_data = array(
                'name' => $post['name'],
                'deposit_amount' => $post['deposit_amount'],
                'agreement_date' => $post['agreement_date'],
                'revenue_sharing_percentage' => $post['revenue_sharing_percentage'],
                'commission_percentage' => $post['commission_percentage'],
                'status' => $post['status']
            );

            $user_data = array(
                'email' => $post['email'],
                'status' => $post['status']
            );
            if (!empty($post['password'])) {
                $user_data['password'] = $post['password'];
            }

            $this->Master_model->update_franchise($id, $franchise_data, $user_data);
            $this->session->set_flashdata('success', 'Franchise details updated.');
            redirect('franchises');
        }
    }

    public function delete_franchise($id) {
        if ($this->session->userdata('role_id') != 1) {
            $this->session->set_flashdata('error', 'Only Super Admin can delete records.');
            redirect('franchises');
        }
        $this->Master_model->delete_franchise($id);
        $this->session->set_flashdata('success', 'Franchise profile removed.');
        redirect('franchises');
    }

    // --- COUNTRIES ---
    public function countries() {
        $data['page_title'] = 'Country Master';
        $data['countries'] = $this->Master_model->get_countries();
        $data['view_path'] = 'masters/countries_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_country() {
        $this->form_validation->set_rules('country_name', 'Country Name', 'required|is_unique[countries.country_name]');
        $this->form_validation->set_rules('iso_code', 'ISO Code', 'required|exact_length[3]|is_unique[countries.iso_code]');
        $this->form_validation->set_rules('country_code', 'Country dialing Code', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Add New Country';
            $data['view_path'] = 'masters/country_add';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            $post['customs_required'] = isset($post['customs_required']) ? 1 : 0;
            $this->Master_model->add_country($post);
            $this->session->set_flashdata('success', 'Country added.');
            redirect('countries');
        }
    }

    public function edit_country($id) {
        $this->form_validation->set_rules('country_name', 'Country Name', 'required');
        $this->form_validation->set_rules('country_code', 'Country dialing Code', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Edit Country';
            $data['country'] = $this->Master_model->get_countries($id);
            $data['view_path'] = 'masters/country_edit';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            $post['customs_required'] = isset($post['customs_required']) ? 1 : 0;
            $this->Master_model->update_country($id, $post);
            $this->session->set_flashdata('success', 'Country updated.');
            redirect('countries');
        }
    }

    // --- COURIER PARTNERS ---
    public function partners() {
        $data['page_title'] = 'Courier Partners';
        $data['partners'] = $this->Master_model->get_courier_partners();
        $data['view_path'] = 'masters/partners_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_partner() {
        $this->form_validation->set_rules('partner_name', 'Partner Name', 'required|is_unique[courier_partners.partner_name]');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Add Courier Partner';
            $data['view_path'] = 'masters/partner_add';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            $this->Master_model->add_courier_partner($post);
            $this->session->set_flashdata('success', 'Courier partner added.');
            redirect('partners');
        }
    }

    public function edit_partner($id) {
        $this->form_validation->set_rules('partner_name', 'Partner Name', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Edit Courier Partner';
            $data['partner'] = $this->Master_model->get_courier_partners($id);
            $data['view_path'] = 'masters/partner_edit';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            $this->Master_model->update_courier_partner($id, $post);
            $this->session->set_flashdata('success', 'Courier partner updated.');
            redirect('partners');
        }
    }

    // --- SHIPPING RATES ---
    public function rates() {
        $data['page_title'] = 'Shipping Rates Matrix';
        $data['countries'] = $this->Master_model->get_countries();
        $data['service_types'] = $this->Master_model->get_service_types();
        $data['rates'] = $this->Master_model->get_rates();
        $data['view_path'] = 'masters/rates_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_rate() {
        $this->form_validation->set_rules('origin_country_id', 'Origin Country', 'required');
        $this->form_validation->set_rules('destination_country_id', 'Destination Country', 'required');
        $this->form_validation->set_rules('service_type', 'Service Type', 'required');
        $this->form_validation->set_rules('weight_slab_start', 'Weight Slab Start', 'required|numeric');
        $this->form_validation->set_rules('weight_slab_end', 'Weight Slab End', 'required|numeric');
        $this->form_validation->set_rules('base_rate', 'Base Rate', 'required|numeric');
        $this->form_validation->set_rules('fuel_surcharge', 'Fuel Surcharge %', 'numeric');
        $this->form_validation->set_rules('handling_charges', 'Handling Fee', 'numeric');
        $this->form_validation->set_rules('insurance_charges', 'Insurance Fee', 'numeric');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Add New Shipping Rate Slab';
            $data['countries'] = $this->Master_model->get_countries();
            $data['service_types'] = $this->Master_model->get_service_types();
            $data['view_path'] = 'masters/rate_add';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            $this->Master_model->add_rate($post);
            $this->session->set_flashdata('success', 'Rate slab added to matrix.');
            redirect('rates');
        }
    }

    public function edit_rate($id) {
        $this->form_validation->set_rules('weight_slab_start', 'Weight Slab Start', 'required|numeric');
        $this->form_validation->set_rules('weight_slab_end', 'Weight Slab End', 'required|numeric');
        $this->form_validation->set_rules('base_rate', 'Base Rate', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Edit Rate Slab';
            $data['rate'] = $this->Master_model->get_rates($id);
            $data['countries'] = $this->Master_model->get_countries();
            $data['service_types'] = $this->Master_model->get_service_types();
            $data['view_path'] = 'masters/rate_edit';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            $this->Master_model->update_rate($id, $post);
            $this->session->set_flashdata('success', 'Rate slab modified.');
            redirect('rates');
        }
    }

    public function delete_rate($id) {
        $this->Master_model->delete_rate($id);
        $this->session->set_flashdata('success', 'Rate slab deleted.');
        redirect('rates');
    }

    // --- TERMS & CONDITIONS ---
    public function terms() {
        $data['page_title'] = 'Terms & Conditions Versions';
        $data['terms'] = $this->Master_model->get_terms();
        $data['view_path'] = 'masters/terms_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_terms() {
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('version_number', 'Version Number', 'required');
        $this->form_validation->set_rules('effective_date', 'Effective Date', 'required');
        $this->form_validation->set_rules('terms_content', 'Content', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Create Terms Version';
            $data['view_path'] = 'masters/terms_add';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            $this->Master_model->add_terms($post);
            $this->session->set_flashdata('success', 'Terms & Conditions version added.');
            redirect('terms');
        }
    }

    public function edit_terms($id) {
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('terms_content', 'Content', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Edit Terms Version';
            $data['terms'] = $this->Master_model->get_terms($id);
            $data['view_path'] = 'masters/terms_edit';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            $this->Master_model->update_terms($id, $post);
            $this->session->set_flashdata('success', 'Terms & Conditions version updated.');
            redirect('terms');
        }
    }

    // --- RESTRICTED ITEMS LIST ---
    public function restricted_items() {
        $data['page_title'] = 'Restricted Items Directory';
        $data['countries'] = $this->Master_model->get_countries();
        $data['view_path'] = 'masters/restricted_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_restricted_item() {
        $this->form_validation->set_rules('country_id', 'Country', 'required|numeric');
        $this->form_validation->set_rules('item', 'Restricted Item', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $country_id = $this->input->post('country_id');
            $new_item = trim($this->input->post('item'));
            
            $country = $this->Master_model->get_countries($country_id);
            if ($country) {
                $current_items = trim($country->restricted_items);
                if (empty($current_items)) {
                    $updated_items = $new_item;
                } else {
                    // Check if item is already in the list
                    $items_arr = array_map('trim', explode(',', $current_items));
                    if (!in_array($new_item, $items_arr)) {
                        $updated_items = $current_items . ', ' . $new_item;
                    } else {
                        $updated_items = $current_items;
                    }
                }
                
                $this->Master_model->update_country($country_id, array('restricted_items' => $updated_items));
                $this->session->set_flashdata('success', 'Restricted item added successfully.');
            } else {
                $this->session->set_flashdata('error', 'Country not found.');
            }
        }
        redirect('restricted-items');
    }

    public function edit_restricted_items($id) {
        $this->form_validation->set_rules('restricted_items', 'Restricted Items', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $items = $this->input->post('restricted_items');
            // Clean up comma formatting: trim all items
            if (!empty($items)) {
                $items_arr = array_map('trim', explode(',', $items));
                // Filter out empty items
                $items_arr = array_filter($items_arr, function($value) { return $value !== ''; });
                $cleaned_items = implode(', ', $items_arr);
            } else {
                $cleaned_items = NULL;
            }
            
            $this->Master_model->update_country($id, array('restricted_items' => $cleaned_items));
            $this->session->set_flashdata('success', 'Restricted items list updated successfully.');
        }
        redirect('restricted-items');
    }

    public function delete_restricted_item($country_id, $item_name) {
        $item_to_delete = trim(urldecode($item_name));
        $country = $this->Master_model->get_countries($country_id);
        
        if ($country) {
            $current_items = trim($country->restricted_items);
            if (!empty($current_items)) {
                $items_arr = array_map('trim', explode(',', $current_items));
                // Find and remove the item
                $new_items_arr = array_filter($items_arr, function($value) use ($item_to_delete) {
                    return strcasecmp($value, $item_to_delete) !== 0;
                });
                
                $updated_items = !empty($new_items_arr) ? implode(', ', $new_items_arr) : NULL;
                $this->Master_model->update_country($country_id, array('restricted_items' => $updated_items));
                $this->session->set_flashdata('success', 'Restricted item deleted successfully.');
            }
        } else {
            $this->session->set_flashdata('error', 'Country not found.');
        }
        redirect('restricted-items');
    }

    // --- MOVEMENT STAGES ---
    public function movement_stages() {
        $data['page_title'] = 'Movement Status Stages';
        $data['stages'] = $this->Master_model->get_movement_stages();
        $data['view_path'] = 'masters/movement_stages_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_movement_stage() {
        $this->form_validation->set_rules('stage_name', 'Stage Name', 'required|is_unique[movement_stages.stage_name]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'stage_name' => $this->input->post('stage_name'),
                'description' => $this->input->post('description')
            );
            $this->Master_model->add_movement_stage($data);
            $this->session->set_flashdata('success', 'Movement status stage added successfully.');
        }
        redirect('movement-stages');
    }

    public function edit_movement_stage($id) {
        $original = $this->Master_model->get_movement_stages($id);
        $is_unique = '';
        if ($this->input->post('stage_name') != $original->stage_name) {
            $is_unique = '|is_unique[movement_stages.stage_name]';
        }
        $this->form_validation->set_rules('stage_name', 'Stage Name', 'required' . $is_unique);

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'stage_name' => $this->input->post('stage_name'),
                'description' => $this->input->post('description')
            );
            $this->Master_model->update_movement_stage($id, $data);
            $this->session->set_flashdata('success', 'Movement status stage updated successfully.');
        }
        redirect('movement-stages');
    }

    public function delete_movement_stage($id) {
        $this->Master_model->delete_movement_stage($id);
        $this->session->set_flashdata('success', 'Movement status stage deleted successfully.');
        redirect('movement-stages');
    }

    // --- SERVICE TYPES ---
    public function service_types() {
        $data['page_title'] = 'Service Types Master';
        $data['service_types'] = $this->Master_model->get_service_types();
        $data['view_path'] = 'masters/service_types_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_service_type() {
        $this->form_validation->set_rules('service_name', 'Service Name', 'required|is_unique[service_types.service_name]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'service_name' => $this->input->post('service_name'),
                'description' => $this->input->post('description')
            );
            $this->Master_model->add_service_type($data);
            $this->session->set_flashdata('success', 'Service Type added successfully.');
        }
        redirect('service-types');
    }

    public function edit_service_type($id) {
        $original = $this->Master_model->get_service_types($id);
        $is_unique = '';
        if ($this->input->post('service_name') != $original->service_name) {
            $is_unique = '|is_unique[service_types.service_name]';
        }
        $this->form_validation->set_rules('service_name', 'Service Name', 'required' . $is_unique);

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'service_name' => $this->input->post('service_name'),
                'description' => $this->input->post('description')
            );
            $this->Master_model->update_service_type($id, $data);
            $this->session->set_flashdata('success', 'Service Type updated successfully.');
        }
        redirect('service-types');
    }

    public function delete_service_type($id) {
        $this->Master_model->delete_service_type($id);
        $this->session->set_flashdata('success', 'Service Type deleted successfully.');
        redirect('service-types');
    }

    // --- DOCUMENT TYPES ---
    public function document_types() {
        $data['page_title'] = 'Document Types Master';
        $data['document_types'] = $this->Master_model->get_document_types();
        $data['view_path'] = 'masters/document_types_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_document_type() {
        $this->form_validation->set_rules('doc_type_name', 'Document Type Name', 'required|is_unique[document_types.doc_type_name]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'doc_type_name' => $this->input->post('doc_type_name'),
                'description' => $this->input->post('description')
            );
            $this->Master_model->add_document_type($data);
            $this->session->set_flashdata('success', 'Document Type added successfully.');
        }
        redirect('document-types');
    }

    public function edit_document_type($id) {
        $original = $this->Master_model->get_document_types($id);
        $is_unique = '';
        if ($this->input->post('doc_type_name') != $original->doc_type_name) {
            $is_unique = '|is_unique[document_types.doc_type_name]';
        }
        $this->form_validation->set_rules('doc_type_name', 'Document Type Name', 'required' . $is_unique);

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'doc_type_name' => $this->input->post('doc_type_name'),
                'description' => $this->input->post('description')
            );
            $this->Master_model->update_document_type($id, $data);
            $this->session->set_flashdata('success', 'Document Type updated successfully.');
        }
        redirect('document-types');
    }

    public function delete_document_type($id) {
        $this->Master_model->delete_document_type($id);
        $this->session->set_flashdata('success', 'Document Type deleted successfully.');
        redirect('document-types');
    }

    // --- GENERAL APP SETTINGS ---
    public function app_settings() {
        $this->form_validation->set_rules('company_name', 'Company Name', 'required');
        $this->form_validation->set_rules('company_email', 'Company Email', 'valid_email');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Application & Gateway Settings';
            $data['settings'] = $this->Master_model->get_app_settings();
            $data['view_path'] = 'masters/app_settings';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            
            // Handle unchecked checkboxes
            $checkboxes = array('smtp_enabled', 'sms_enabled', 'whatsapp_enabled');
            foreach ($checkboxes as $cb) {
                if (!isset($post[$cb])) {
                    $post[$cb] = '0';
                }
            }

            // Handle company logo upload
            if (!empty($_FILES['company_logo']['name'])) {
                $config['upload_path'] = './assets/img/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png|svg';
                $config['max_size'] = 2048; // 2MB max
                $config['file_name'] = 'logo_' . time();
                
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, TRUE);
                }

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('company_logo')) {
                    $uploadData = $this->upload->data();
                    $post['company_logo'] = $uploadData['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('app-settings');
                    return;
                }
            }

            $this->Master_model->update_app_settings($post);
            $this->session->set_flashdata('success', 'Application settings updated successfully.');
            redirect('app-settings');
        }
    }

    public function notification_logs() {
        // Access Control (Only Super Admin, Branch Admin and Branch Staff can view logs, i.e., not customer)
        if ($this->session->userdata('role_id') == 4) {
            $this->session->set_flashdata('error', 'Access Denied.');
            redirect('dashboard');
        }
        
        $data['page_title'] = 'Notification Logs';
        
        // Fetch notifications sorted by id DESC (newest first)
        $this->db->select('notifications.*, users.username');
        $this->db->from('notifications');
        $this->db->join('users', 'users.id = notifications.user_id', 'left');
        $this->db->order_by('notifications.id', 'DESC');
        $data['logs'] = $this->db->get()->result();
        
        $data['view_path'] = 'masters/notification_logs';
        $this->load->view('templates/dashboard_layout', $data);
    }

    // --- ROLES & PERMISSIONS ---
    public function roles() {
        $data['page_title'] = 'Roles & Permissions';
        $data['roles'] = $this->Master_model->get_roles();
        $data['permissions'] = $this->Master_model->get_permissions();
        
        // Build an array of permission IDs per role
        $data['role_permissions'] = array();
        foreach ($data['roles'] as $role) {
            $data['role_permissions'][$role->id] = $this->Master_model->get_role_permissions($role->id);
        }
        
        $data['view_path'] = 'masters/roles_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_role() {
        $this->form_validation->set_rules('name', 'Role Name', 'required|is_unique[roles.name]');
        $this->form_validation->set_rules('description', 'Description', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $post = $this->input->post(NULL, TRUE);
            $role_data = array(
                'name' => $post['name'],
                'description' => $post['description']
            );
            $this->Master_model->add_role($role_data);
            $this->session->set_flashdata('success', 'New role created successfully.');
        }
        redirect('roles');
    }

    public function edit_role($id) {
        // Prevent editing default system roles (1 to 4)
        if ($id <= 4) {
            $this->session->set_flashdata('error', 'Default system roles cannot be modified.');
            redirect('roles');
        }

        $this->form_validation->set_rules('name', 'Role Name', 'required');
        $this->form_validation->set_rules('description', 'Description', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $post = $this->input->post(NULL, TRUE);
            $role_data = array(
                'name' => $post['name'],
                'description' => $post['description']
            );
            $this->Master_model->update_role($id, $role_data);
            $this->session->set_flashdata('success', 'Role details updated successfully.');
        }
        redirect('roles');
    }

    public function delete_role($id) {
        // Prevent deleting default system roles (1 to 4)
        if ($id <= 4) {
            $this->session->set_flashdata('error', 'Default system roles cannot be deleted.');
            redirect('roles');
        }

        if ($this->Master_model->delete_role($id)) {
            $this->session->set_flashdata('success', 'Role and its permission mappings deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete role.');
        }
        redirect('roles');
    }

    public function save_role_permissions() {
        $role_id = $this->input->post('role_id');
        $permission_ids = $this->input->post('permissions');
        
        if (empty($permission_ids)) {
            $permission_ids = array();
        }

        if ($role_id) {
            $this->Master_model->update_role_permissions($role_id, $permission_ids);
            $this->session->set_flashdata('success', 'Permissions mapped to role successfully.');
        } else {
            $this->session->set_flashdata('error', 'Invalid role selection.');
        }
        redirect('roles');
    }

    // --- ADDITIONAL CHARGES ---
    public function additional_charges() {
        $data['page_title'] = 'Manage Additional Charges Types';
        $data['charges'] = $this->Master_model->get_additional_charge_types();
        $data['view_path'] = 'masters/additional_charges';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_additional_charge() {
        if ($this->input->post()) {
            $data = array(
                'charge_name' => $this->input->post('charge_name'),
                'status' => $this->input->post('status')
            );
            if ($this->Master_model->insert_additional_charge_type($data)) {
                $this->session->set_flashdata('success', 'Additional charge type added successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to add charge type.');
            }
        }
        redirect('masters/additional_charges');
    }

    public function edit_additional_charge($id) {
        if ($this->input->post()) {
            $data = array(
                'charge_name' => $this->input->post('charge_name'),
                'status' => $this->input->post('status')
            );
            if ($this->Master_model->update_additional_charge_type($id, $data)) {
                $this->session->set_flashdata('success', 'Additional charge type updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to update charge type.');
            }
        }
        redirect('masters/additional_charges');
    }

    public function delete_additional_charge($id) {
        if ($this->Master_model->delete_additional_charge_type($id)) {
            $this->session->set_flashdata('success', 'Additional charge type deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete charge type.');
        }
        redirect('masters/additional_charges');
    }
}
