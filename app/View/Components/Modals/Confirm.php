<?php

namespace App\View\Components\Modals;

use Illuminate\View\Component;

class Confirm extends Component
{
    public $model;
    public $form;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($model, string $form)
    {
        $this->model = $model;
        $this->form = $form;
    }

    public function title()
    {
        switch($this->form)
        {
            case 'add_operators':
                return __('common.confirm_add_operators');
                break;

            case 'delete_operators':
                return __('common.confirm_delete_operators');
                break;

            case 'add_members':
                return __('common.confirm_add_members');
                break;

            case 'delete_members':
                return __('common.confirm_delete_members');
                break;

            case 'leave_federations':
                return __('common.confirm_leave_federations');
                break;

            case 'edugain':
                return $this->model->edugain
                    ? __('common.drop_from_edugain')
                    : __('common.export_to_edugain');
                break;

            case 'rs':
                return $this->model->rs
                    ? __('common.delete_rs')
                    : __('common.add_rs');
                break;

            case 'status':
                return $this->model->active
                    ? __('common.deactivate_model', [
                        'name' => $this->model->name ?? $this->model->name_en ?? $this->model->entityid,
                    ])
                    : __('common.activate_model', [
                        'name' => $this->model->name ?? $this->model->name_en ?? $this->model->entityid,
                    ]);
                break;

            case 'state':
                return $this->model->trashed()
                    ? __('common.restore_model', [
                        'name' => $this->model->name ?? $this->model->name_en ?? $this->model->entityid,
                    ])
                    : __('common.delete_model', [
                        'name' => $this->model->name ?? $this->model->name_en ?? $this->model->entityid,
                    ]);
                break;

            case 'destroy':
                return __('common.destroy_model', [
                    'name' => $this->model->name ?? $this->model->name_en ?? $this->model->entityid,
                ]);
                break;

            case 'role':
                return $this->model->admin
                    ? __('common.revoke_admin_rights')
                    : __('common.grant_admin_rights');
                break;

            case 'hfd':
                return $this->model->hfd
                    ? __('entities.confirm_drop_hfd')
                    : __('entities.confirm_add_hfd');
                break;
        }
    }

    public function text()
    {
        switch($this->form)
        {
            case 'add_operators':
                return __('common.confirm_add_operators_body');
                break;

            case 'delete_operators':
                return __('common.confirm_delete_operators_body');
                break;

            case 'add_members':
                return __('common.confirm_add_members_body');
                break;

            case 'delete_members':
                return __('common.confirm_delete_members_body');
                break;

            case 'leave_federations':
                return __('common.confirm_leave_federations_body');
                break;

            case 'edugain':
                return $this->model->edugain
                    ? __('common.drop_from_edugain_body', [
                        'name' => $this->model->name_en,
                    ])
                    : __('common.export_to_edugain_body', [
                        'name' => $this->model->name_en,
                    ]);
                break;

            case 'rs':
                return $this->model->rs
                    ? __('common.delete_rs_body', [
                        'name' => $this->model->name_en,
                    ])
                    : __('common.add_rs_body', [
                        'name' => $this->model->name_en,
                    ]);
                break;

            case 'status':
                return $this->model->active
                    ? __('common.deactivate_model_body', [
                        'name' => $this->model->name ?? $this->model->name_en,
                        'type' => strtolower(substr(get_class($this->model), 11)),
                    ])
                    : __('common.activate_model_body', [
                        'name' => $this->model->name ?? $this->model->name_en,
                        'type' => strtolower(substr(get_class($this->model), 11)),
                    ]);
                break;

            case 'state':
                return $this->model->trashed()
                    ? __('common.restore_model_body', [
                        'name' => $this->model->name ?? $this->model->name_en,
                        'type' => strtolower(substr(get_class($this->model), 11)),
                    ])
                    : __('common.delete_model_body', [
                        'name' => $this->model->name ?? $this->model->name_en,
                        'type' => strtolower(substr(get_class($this->model), 11)),
                    ]);
                break;

            case 'destroy':
                return __('common.destroy_model_body', [
                    'name' => $this->model->name ?? $this->model->name_en,
                    'type' => strtolower(substr(get_class($this->model), 11)),
                ]);
                break;

            case 'role':
                return $this->model->admin
                    ? __('common.revoke_admin_rights_body', [
                        'name' => $this->model->name,
                    ])
                    : __('common.grant_admin_rights_body', [
                        'name' => $this->model->name,
                    ]);
                break;

            case 'hfd':
                return $this->model->hfd
                    ? __('entities.confirm_drop_hfd_body')
                    : __('entities.confirm_add_hfd_body');
                break;
        }
    }

    public function action()
    {
        switch($this->form)
        {
            case 'add_operators':
                return __('common.add');
                break;

            case 'delete_operators':
                return __('common.delete');
                break;

            case 'add_members':
                return __('common.add');
                break;

            case 'delete_members':
                return __('common.delete');
                break;

            case 'leave_federations':
                return __('common.leave');
                break;

            case 'edugain':
                return $this->model->edugain
                    ? __('common.drop')
                    : __('common.export');
                break;

            case 'rs':
                return $this->model->rs
                    ? __('common.delete')
                    : __('common.add');
                break;

            case 'status':
                return $this->model->active
                    ? __('common.deactivate')
                    : __('common.activate');
                break;

            case 'state':
                return $this->model->trashed()
                    ? __('common.restore')
                    : __('common.delete');
                break;

            case 'destroy':
                return __('common.destroy');
                break;

            case 'role':
                return $this->model->admin
                    ? __('common.revoke')
                    : __('common.grant');
                break;

            case 'hfd':
                return $this->model->hfd
                    ? __('common.drop')
                    : __('common.add');
                break;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.modals.confirm');
    }
}
