<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
// use Illuminate\Support\Facades\Log;

use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $branchId = auth('admin')->user()->branch_id ?? null;
        $users = User::query();
        if ($branchId) {
            $users->where('branch_id', $branchId);
        }
        $userData = $users->with('paymentInfo', 'event', 'userDetails', 'firstKid')->latest();
        return DataTables::of($userData)
            ->addColumn('checkbox', function ($user) {
                return '<input type="checkbox" name="users[]" class="users_checkbox" value="' . $user->id . '" data-item-id="' . $user->id . '" data-item-paymentId="' . (isset($user->paymentInfo->id) ? $user->paymentInfo->id : '') . '" data-item-date="' . (isset($user->event->booking_date) ? $user->event->booking_date : '') . '" data-item-userdetail="'.(isset($user->userDetails->id) ? $user->userDetails->id : '').'">';
            })
            ->editColumn('name', function ($user) {
                if (isset($user->paymentInfo) && $user->paymentInfo->payment_method == "Card") {
                    return '<img src="assets/images/Vector.png" alt="logo"> &nbsp;' . $user->name;
                } else {
                    return '<span class="text-capitalize">' . $user->name . '</span>';
                }
            })
            ->editColumn('first_kid', function ($user) {
                return isset($user->firstKid->name) ? '<span class="text-capitalize">' . $user->firstKid->name . '</span>' : '--';
            })
            ->addColumn('first_kid', function ($user) {
                return isset($user->firstKid->name) ? '<span class="text-capitalize">' . $user->firstKid->name . '</span>' : '--';
            })
            ->editColumn('date', function ($user) {
                if (isset($user->event->booking_date)) {
                    if (Carbon::parse($user->event->booking_date, 'Asia/Kolkata')->isToday()) {
                        return 'Today';
                    } elseif (Carbon::parse($user->event->booking_date, 'Asia/Kolkata')->isYesterday()) {
                        return 'Yesterday';
                    } else {
                        return Carbon::parse($user->event->booking_date)->format('d M, Y');
                    }
                } else {
                    return isset($user->membership->date) ? $user->membership->date : '';
                }
            })
            ->addColumn('date', function ($user) {
                if (isset($user->event->booking_date)) {
                    if (Carbon::parse($user->event->booking_date, 'Asia/Kolkata')->isToday()) {
                        return 'Today';
                    } elseif (Carbon::parse($user->event->booking_date, 'Asia/Kolkata')->isYesterday()) {
                        return 'Yesterday';
                    } else {
                        return Carbon::parse($user->event->booking_date)->format('d M, Y');
                    }
                } else {
                    return isset($user->membership->date) ? $user->membership->date : '';
                }
            })
            ->filterColumn('date', function ($query, $keyword) {
                // Assuming keyword is in format 'start_date - end_date'
                $dates = explode(' - ', $keyword);
                if (count($dates) == 2) {
                    $startDate = Carbon::parse($dates[0])->startOfDay();
                    $endDate = Carbon::parse($dates[1])->endOfDay();
                    $query->where(function ($q) use ($startDate, $endDate) {
                        $q->whereHas('event', function ($q) use ($startDate, $endDate) {
                            $q->whereBetween('booking_date', [$startDate, $endDate]);
                        })->orWhereHas('membership', function ($q) use ($startDate, $endDate) {
                            $q->whereBetween('date', [$startDate, $endDate]);
                        });
                    });
                }
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
            ->editColumn('amount', function ($user) {
                if (isset($user->paymentInfo) && $user->paymentInfo->payment_method == "Card") {
                    return '0.00';
                } else {
                    return isset($user->paymentInfo->amounts) ? $user->paymentInfo->amounts : '';
                }
            })
            ->addColumn('amount', function ($user) {
                if (isset($user->paymentInfo) && $user->paymentInfo->payment_method == "Card") {
                    return '0.00';
                } else {
                    return isset($user->paymentInfo->amounts) ? $user->paymentInfo->amounts : '';
                }
            })
            ->filterColumn('payment_method', function ($query, $keyword) {
                $query->whereHas('paymentInfo', function ($query) use ($keyword) {
                    $query->where('payment_method', 'like', "%$keyword%");
                });
            })
            ->editColumn('remaining_time', function ($user) {
                date_default_timezone_set('Asia/Kolkata');

                $end_time = isset($user['firstKid']->end_time) ? $user['firstKid']->end_time : null;
                $booking_date = isset($user['event']->booking_date) ? $user['event']->booking_date : null;

                $end_datetime_unix = strtotime($booking_date . ' ' . $end_time);

                $remaining_seconds = $end_datetime_unix - time();
                if ($remaining_seconds <= 0) {
                    $remaining_seconds = 0;
                }

                $remaining_hours = floor($remaining_seconds / 3600);
                $remaining_minutes = floor(($remaining_seconds % 3600) / 60);
                $remaining_seconds = $remaining_seconds % 60;

                return $remaining_hours . " : " . $remaining_minutes . " : " . $remaining_seconds;
            })
            ->addColumn('remaining_time', function ($user) {
                date_default_timezone_set('Asia/Kolkata');

                $end_time = isset($user['firstKid']->end_time) ? $user['firstKid']->end_time : null;
                $booking_date = isset($user['event']->booking_date) ? $user['event']->booking_date : null;

                $end_datetime_unix = strtotime($booking_date . ' ' . $end_time);

                $remaining_seconds = $end_datetime_unix - time();
                if ($remaining_seconds <= 0) {
                    $remaining_seconds = 0;
                }

                $remaining_hours = floor($remaining_seconds / 3600);
                $remaining_minutes = floor(($remaining_seconds % 3600) / 60);
                $remaining_seconds = $remaining_seconds % 60;

                return $remaining_hours . " : " . $remaining_minutes . " : " . $remaining_seconds;
            })
            ->editColumn('kids', function ($user) {
                return isset($user->userDetails->number_of_kids) ? '<td class="gree_c">' . $user->userDetails->number_of_kids . '</td>' : '';
            })
            ->addColumn('kids', function ($user) {
                return isset($user->userDetails->number_of_kids) ? '<td class="gree_c">' . $user->userDetails->number_of_kids . '</td>' : '';
            })
            ->editColumn('hours', function ($user) {
                $hours = (isset($user->firstKid->hours) ? (float)$user->firstKid->hours : 0) + (isset($user->firstKid->extra_hour) ? (float)$user->firstKid->extra_hour : 0);
                return $hours == 0 ? 'Unlimited' : $hours;
            })
            ->addColumn('hours', function ($user) {
                $hours = (isset($user->firstKid->hours) ? (float)$user->firstKid->hours : 0) + (isset($user->firstKid->extra_hour) ? (float)$user->firstKid->extra_hour : 0);
                return $hours;
            })
            ->editColumn('payment_method', function ($user) {
                return isset($user->paymentInfo->payment_method) ? $user->paymentInfo->payment_method : '';
            })
            ->addColumn('payment_method', function ($user) {
                return isset($user->paymentInfo->payment_method) ? $user->paymentInfo->payment_method : '';
            })
            ->addColumn('branch', function ($user) {
                return isset($user->branch->name) ? $user->branch->name : '';
            })
            ->editColumn('action', function ($user) {
                $actions = '';
                if (can('tickets-edit')) {
                    $actions .= '<a style="border-radius: 50%; width: 30px;height: 30px;" class="btn btn-sm btn-primary" href="' . url('/tickets-edit-view/' . $user->id) . '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }
                // $actions .= '<i class="fa fa-eye" aria-hidden="true"></i>';
                $actions .= '<a style="border-radius: 50%; width: 30px;height: 30px;" class="btn btn-sm btn-dark"  href="' . url('/invoice-pdf/' . $user->id) . '" target="_blank"><i class="fa fa-file" aria-hidden="true"></i></a>';
                return $actions;
            })
            ->escapeColumns([]);
        // ->rawColumns(['checkbox', 'name', 'kid_name', 'date', 'amount', 'remaining_time', 'kids', 'hours', 'payment_method', 'action']);
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
        $model = $model->with('membership', 'firstKid', 'userDetails', 'event', 'paymentInfo', 'branch')->where('role', '!=', 0);
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
            ->dom(
                "<'row mb-2 align-items-center'" .
                    "<'col-md-auto d-flex align-items-center gap-2'B>" .
                    "<'col-md d-flex justify-content-end align-items-center gap-2'f<'#custom-toolbar'>>" .
                    ">" .
                    "<'row'<'col-sm-12'tr>>" .
                    "<'row mt-2'<'col-sm-4'i><'col-sm-4 text-center'l><'col-sm-4 text-end'p>>"
            )
            ->orderBy(1)
            ->buttons([
                // 'extend' => 'reload',
                ['extend' => 'csv', 'className' => 'btn btn-sm btn-primary'],
                ['extend' => 'copy', 'className' => 'btn btn-sm btn-primary'],
                ['extend' => 'excel', 'className' => 'btn btn-sm btn-primary'],
                ['extend' => 'print', 'className' => 'btn btn-sm btn-primary'],
                ['extend' => 'pdf', 'className' => 'btn btn-sm btn-primary'],
                ['extend' => 'colvis', 'className' => 'btn btn-sm btn-primary'],
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
                'searchPlaceholder' => "Search All Columns...",
                'search' => '',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('checkbox')
                ->exportable(true)
                ->printable(true)
                ->addClass('text-center')
                ->title('<input type="checkbox" onclick="checkAll(this)">'),
            Column::make('id'),
            Column::make('name'),
            Column::make('first_kid'),
            Column::make('date'),
            Column::make('phone'),
            Column::make('amount'),
            Column::make('remaining_time'),
            Column::make('kids'),
            Column::make('hours'),
            Column::make('payment_method'),
            Column::make('branch'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename()
    {
        return 'Customer_' . date('YmdHis');
    }
    /**
     * Get columns.
     *
     * @return array
     */
}
