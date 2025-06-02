<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CustomerDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', function ($user) {
                return '<input type="checkbox" name="users[]" class="users_checkbox" value="' . $user->id . '" data-item-id="' . $user->id . '">';
            })
            ->editColumn('name', function ($user) {
                return '<span class="text-capitalize">' . $user->name . '</span>';
            })
            ->editColumn('kid_name', function ($user) {
                return isset($user->kid->name) ? '<span class="text-capitalize">' . $user->kid->name . '</span>' : '--';
            })
            ->addColumn('kid_name', function ($user) {
                return isset($user->kid->name) ? '<span class="text-capitalize">' . $user->kid->name . '</span>' : '--';
            })
            ->editColumn('date', function ($user) {
                $kidDob = $user->userDetails->dob ?? null;
                if ($kidDob) {
                    $bookingDate = \Carbon\Carbon::parse($kidDob, 'Asia/Kolkata');
                    if ($bookingDate->isToday()) {
                        return 'Today';
                    } elseif ($bookingDate->isYesterday()) {
                        return 'Yesterday';
                    } else {
                        return $bookingDate->format('d M, Y');
                    }
                } else {
                    return '--';
                }
            })
            ->addColumn('date', function ($user) {
                $kidDob = $user->userDetails->dob ?? null;
                if ($kidDob) {
                    $bookingDate = \Carbon\Carbon::parse($kidDob, 'Asia/Kolkata');
                    if ($bookingDate->isToday()) {
                        return 'Today';
                    } elseif ($bookingDate->isYesterday()) {
                        return 'Yesterday';
                    } else {
                        return $bookingDate->format('d M, Y');
                    }
                } else {
                    return '--';
                }
            })
            ->addColumn('amount_spent', function ($user) {
                return  isset( $user->paymentInfo->amounts)?$user->paymentInfo->amounts:'--';
            })
            ->editColumn('amount_spent', function ($user) {
                return \App\Models\PaymentMethod::where('user_id', $user->id)->sum('amounts');
            })
            ->editColumn('phone', function ($user) {
                return isset($user->userDetails->phone_no) ? $user->userDetails->phone_no : '';
            })
            ->addColumn('phone', function ($user) {
                return isset($user->userDetails->phone_no) ? $user->userDetails->phone_no : '';
            })
            ->filterColumn('phone', function ($query, $keyword) {
                $query->whereHas('userDetails', function ($query) use ($keyword) {
                    $query->where('phone_no', 'like', "%$keyword%");
                });
            })
            ->editColumn('updated_at', function ($user) {
                return $user->updated_at ? $user->updated_at->format('d-m-Y H:i a') : '';
            })
            ->addColumn('branch', function ($user) {
                return isset($user->branch->name) ? $user->branch->name : '';
            })
            ->addColumn('action', function ($user) {
                $actions = '';
                if (can('tickets-edit')) {
                    $actions .= '<a style="width: 30;height:30px;border-radius: 50%;display:flex;align-items: center;justify-content: center" class="btn btn-sm btn-primary" href="' . url('/tickets-edit-view/' . $user->id) . '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }
                $actions .= '<a style="width: 30;height:30px;border-radius: 50%;display:flex;align-items: center;justify-content: center" class="btn btn-sm btn-dark" href="' . url('/invoice-pdf/' . $user->id) . '" target="_blank"><i class="fa fa-file" aria-hidden="true"></i></a>';

                return '<div class="d-flex gap-2">'.$actions.'</div>';
            })
            ->escapeColumns([]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        $branchId = auth('admin')->user()->branch_id ?? null;
        $model = $model->with('paymentInfo', 'kid', 'userDetails','branch');
        if ($branchId) {
            $model->where('branch_id', $branchId);
        }
        $model = $model->latest();
        return $model;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
        ->setTableId('customer-table')
        ->columns($this->getColumns())
        ->minifiedAjax()
        ->dom('lBfrtip')
        ->orderBy(1)
        ->buttons([
            'extend' => 'reload',
            ['extend' => 'csv', 'className' => 'btn btn-sm btn-primary'],
            ['extend' => 'copy', 'className' => 'btn btn-sm btn-primary'],
            ['extend' => 'excel', 'className' => 'btn btn-sm btn-primary'],
            ['extend' => 'print', 'className' => 'btn btn-sm btn-primary'],
            ['extend' => 'pdf', 'className' => 'btn btn-sm btn-primary'],
            'colvis'
        ])
        ->columnDefs([
            ['checkboxes' => true, 'render' => '<input  type="checkbox">', 'target' => 0, 'selectRow' => true]
        ])
        ->selectStyle('multi')
        ->selectSelector('td:first-child')
        ->selectItemsRow()
        ->lengthMenu([
            [10, 25, 50, 100, 500, -1], // Show these options in the length menu, -1 will show all
            ['10', '25', '50', '100', '500', 'All'] // Label for each option
        ])
        ->languageSearchPlaceholder('Search...')
        ->language([
            'searchPlaceholder' => "Search All Columns..."
        ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('checkbox')
                ->exportable(true)
                ->printable(true)
                ->addClass('text-center')
                ->title('<input type="checkbox" onclick="checkAll(this)">'),
            Column::make('id'),
            Column::make('name')->title('Name'),
            Column::make('kid_name')->title('Kid Name'),
            Column::make('date')->title('Date'),
            Column::make('amount_spent')->title('Amount Spent'),
            Column::make('phone')->title('Phone'),
            Column::make('updated_at')->title('Updated At'),
            Column::make('branch')->title('Branch'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Customer_' . date('YmdHis');
    }
}
