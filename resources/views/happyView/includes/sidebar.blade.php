<!--sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div class="toggle-icon ms-auto">
            <!-- <img src="assets/images/menu.svg" alt="menu"> -->
            ...
        </div>
        <div>
            <img src="{{ asset('assets/images/happyon_logo.png') }}" class="logo-icon" alt="logo">
        </div>
        <div class="mail-admin">
            <a href="mailto:{{ auth('admin')->user()->email ?? 'contact@happyonplay.com' }}" class="text-white">
                {{ auth('admin')->user()->email ?? 'contact@happyonplay.com' }}</a>
        </div>
        <span class="text-white" style="display: block; text-align: center;">
            {{ auth('admin')->user()->name ?? 'No name' }}
        </span>
        <span class="text-white" style="display: block; text-align: center;">
            ({{ auth('admin')->user()->branch ? auth('admin')->user()->branch->name : 'Main SuperAdmin' }})
        </span>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu" style="min-height: 80vh !important;">
        <li>
            <a href="{{ url('/') }}" class="{{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}">
                <div class="parent-icon">
                    <svg width="21" height="21" viewBox="0 0 21 21" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M6.60595 11.2329C7.78176 11.2329 8.72408 12.1844 8.72408 13.3686V16.2114C8.72408 17.3872 7.78176 18.3462 6.60595 18.3462H3.78733C2.61985 18.3462 1.66919 17.3872 1.66919 16.2114L1.66919 13.3686C1.66919 12.1844 2.61985 11.2329 3.78733 11.2329L6.60595 11.2329ZM16.2294 11.2329C17.3969 11.2329 18.3475 12.1844 18.3475 13.3686V16.2114C18.3475 17.3872 17.3969 18.3462 16.2294 18.3462H13.4108C12.2349 18.3462 11.2926 17.3872 11.2926 16.2114L11.2926 13.3686C11.2926 12.1844 12.2349 11.2329 13.4108 11.2329H16.2294ZM6.60595 1.66821C7.78176 1.66821 8.72408 2.62721 8.72408 3.80386V6.64667C8.72408 7.83082 7.78176 8.78148 6.60595 8.78148H3.78733C2.61985 8.78148 1.66919 7.83082 1.66919 6.64667L1.66919 3.80386C1.66919 2.62721 2.61985 1.66821 3.78733 1.66821L6.60595 1.66821ZM16.2294 1.66821C17.3969 1.66821 18.3475 2.62721 18.3475 3.80386V6.64667C18.3475 7.83082 17.3969 8.78148 16.2294 8.78148H13.4108C12.2349 8.78148 11.2926 7.83082 11.2926 6.64667L11.2926 3.80386C11.2926 2.62721 12.2349 1.66821 13.4108 1.66821L16.2294 1.66821Z"
                            fill="#FDCF6F" />
                    </svg>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        @if (auth('admin')->user()->branch_id === null && auth('admin')->user()->isSuperAdmin == 1)
            <li>
                <a href="{{ url('/branch') }}"
                    class="{{ in_array(Route::currentRouteName(), ['branch', 'create-branch']) ? 'active' : '' }}">
                    <div class="parent-icon">

                        <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M6 3C4.34315 3 3 4.34315 3 6C3 7.65685 4.34315 9 6 9C7.65685 9 9 7.65685 9 6C9 4.34315 7.65685 3 6 3ZM6 15C4.34315 15 3 16.3431 3 18C3 19.6569 4.34315 21 6 21C7.65685 21 9 19.6569 9 18C9 16.3431 7.65685 15 6 15ZM18 3C16.3431 3 15 4.34315 15 6C15 7.65685 16.3431 9 18 9C19.6569 9 21 7.65685 21 6C21 4.34315 19.6569 3 18 3ZM15 12.5V10.5H13V13C13 14.1046 13.8954 15 15 15H17V17H13V21H11V17H7V15H11C12.1046 15 13 14.1046 13 13V10.5H11V12.5H9V9H15V12.5H15Z"
                                fill="#FDCF6F" />
                        </svg>

                    </div>
                    <div class="menu-title">Branches</div>
                </a>
            </li>
        @endif
        @php
            $isMembershipRoute = request()->is('memberships*') || request()->is('membership-history*');
        @endphp
        @if (can('membership') || can('member-history'))
            <li onclick="showMembershipSystemMenu()" class="{{ $isMembershipRoute ? 'mm-active' : '' }}">

                <a href="javascript:void(0);" aria-expanded="{{ $isMembershipRoute ? 'true' : 'false' }}"
                    style="justify-content: space-between;">
                    <div class="parent-icon">
                        <svg width="15" height="16" viewBox="0 0 15 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M13.5 0H1.5C0.6675 0 0 0.712 0 1.6V10.4C0 11.288 0.6675 12 1.5 12H4.5V16L7.5 14.4L10.5 16V12H13.5C14.3325 12 15 11.288 15 10.4V1.6C15 0.712 14.3325 0 13.5 0ZM13.5 10.4H1.5V8.8H13.5V10.4ZM13.5 6.4H1.5V1.6H13.5V6.4Z"
                                fill="#FACB6A" />
                        </svg>
                    </div>Member Services<span class="fa arrow"></span>
                </a>

                <ul aria-expanded="false" id="membershipMenu" class="ml-3 {{ $isMembershipRoute ? '' : 'd-none' }}"
                    style="border:none; background:none;">
                    @if (can('membership'))
                        <li>
                            <a href="{{ url('/memberships') }}"
                                class="{{ Route::currentRouteName() == 'memberships' ? 'active' : '' }}">
                                Memberships
                            </a>
                        </li>
                    @endif
                    @if (can('member-history'))
                        <li>
                            <a href="{{ url('/membership-history') }}"
                                class="{{ Route::currentRouteName() == 'membershipHistory' ? 'active' : '' }}">
                                Member History
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        @if (can('tickets'))
            <li>
                <a href="{{ url('/tickets') }}" class="{{ Route::currentRouteName() == 'tickets' ? 'active' : '' }}">
                    <div class="parent-icon">

                        <svg width="22" height="20" viewBox="0 0 22 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.52637 7.05263L16.1414 2L18.0001 7.05263" stroke="#FDCF6F" stroke-width="2.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M2 7.05261H20.5263V9.78945C19.1368 9.78945 17.7474 10.7017 17.7474 12.2982C17.7474 13.8947 19.1368 15.2631 20.5263 15.2631V18H2V15.2631C3.38955 15.2631 4.77895 14.3509 4.77895 12.5263C4.77895 10.7017 3.38947 9.78945 2 9.78945V7.05261Z"
                                stroke="#FDCF6F" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="menu-title">Tickets</div>
                </a>
            </li>
        @endif
        @if (can('tickets-manage'))
            <li>
                <a href="{{ url('/customers') }}"
                    class="{{ Route::currentRouteName() == 'customers' ? 'active' : '' }}">
                    <div class="parent-icon">
                        <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 18" width="15"
                            height="18">
                            <title>user Customers (1)-svg</title>
                            <style>
                                .s0 {
                                    fill: #fdcf6f
                                }

                                .s1 {
                                    fill: #dfebe9
                                }
                            </style>
                            <path id="Layer" fill-rule="evenodd" class="s0"
                                d="m14.9 14.7v0.8c0 0.9-0.8 1.6-1.6 1.6h-11.7c-0.9 0-1.6-0.7-1.6-1.6v-0.8c0-2.5 2-4.5 4.4-4.5 1 0 1.4 0.6 3 0.6 1.6 0 2-0.6 3-0.6 2.4 0 4.5 2 4.5 4.5zm-1.6 0c0-1.6-1.3-2.9-2.9-2.9-0.5 0-1.3 0.5-3 0.5-1.7 0-2.5-0.5-3-0.5-1.5 0-2.8 1.3-2.8 2.9v0.8h11.7zm-10.6-9.8c0-2.6 2.1-4.7 4.7-4.7 2.7 0 4.8 2.1 4.8 4.7 0 2.7-2.1 4.8-4.8 4.8-2.6 0-4.7-2.1-4.7-4.8zm1.5 0c0 1.8 1.5 3.2 3.2 3.2 1.8 0 3.2-1.4 3.2-3.2 0-1.7-1.4-3.2-3.2-3.2-1.7 0-3.2 1.5-3.2 3.2z" />
                            <path id="Layer" fill-rule="evenodd" class="s1"
                                d="m46.4 9.1q0-1.8 0.8-3.1 0.8-1.4 2.2-2.2 1.4-0.8 3.1-0.8 1.8 0 3.3 1 1.5 0.9 2.1 2.6h-2.3q-0.4-0.9-1.3-1.4-0.8-0.5-1.8-0.5-1.2 0-2.1 0.6-1 0.5-1.5 1.5-0.5 1-0.5 2.3 0 1.3 0.5 2.3 0.5 0.9 1.5 1.5 0.9 0.5 2.1 0.5 1 0 1.8-0.4 0.9-0.5 1.3-1.4h2.3q-0.6 1.7-2.1 2.6-1.5 0.9-3.3 0.9-1.7 0-3.1-0.8-1.4-0.7-2.2-2.1-0.8-1.4-0.8-3.1zm22.3-3.5v9.4h-1.9v-1.1q-0.5 0.6-1.3 0.9-0.7 0.3-1.5 0.3-1.1 0-2-0.4-0.9-0.5-1.4-1.4-0.5-0.9-0.5-2.2v-5.5h1.9v5.3q0 1.2 0.7 1.9 0.6 0.7 1.7 0.7 1.1 0 1.7-0.7 0.7-0.7 0.7-1.9v-5.3zm5.9 9.6q-1.1 0-2-0.4-0.8-0.4-1.4-1.1-0.5-0.7-0.5-1.6h2q0.1 0.6 0.6 1 0.5 0.4 1.3 0.4 0.8 0 1.2-0.3 0.5-0.3 0.5-0.8 0-0.5-0.5-0.8-0.5-0.2-1.6-0.5-1-0.3-1.7-0.6-0.7-0.3-1.2-0.8-0.4-0.6-0.4-1.5 0-0.8 0.4-1.4 0.4-0.6 1.3-1 0.8-0.3 1.9-0.3 1.6 0 2.5 0.8 1 0.8 1.1 2.2h-2q0-0.6-0.5-1-0.4-0.4-1.2-0.4-0.8 0-1.2 0.3-0.4 0.3-0.4 0.8 0 0.3 0.3 0.6 0.3 0.3 0.6 0.4 0.4 0.1 1.2 0.4 1 0.2 1.7 0.5 0.6 0.3 1.1 0.8 0.5 0.6 0.5 1.5 0 0.8-0.4 1.4-0.5 0.6-1.3 1-0.8 0.4-1.9 0.4zm8-8v5.2q0 0.5 0.2 0.8 0.2 0.2 0.8 0.2h1.2v1.6h-1.5q-1.3 0-2-0.6-0.7-0.6-0.7-2v-5.2h-1.1v-1.6h1.1v-2.3h2v2.3h2.2v1.6zm8.2 8q-1.4 0-2.4-0.6-1.1-0.7-1.7-1.7-0.6-1.1-0.6-2.6 0-1.4 0.6-2.5 0.6-1.1 1.7-1.7 1.1-0.6 2.4-0.6 1.4 0 2.5 0.6 1.1 0.6 1.7 1.7 0.6 1.1 0.6 2.5 0 1.4-0.6 2.5-0.7 1.1-1.8 1.8-1.1 0.6-2.4 0.6zm0-1.7q0.7 0 1.4-0.4 0.6-0.3 1-1.1 0.4-0.7 0.4-1.7 0-1-0.4-1.7-0.3-0.7-1-1.1-0.6-0.3-1.4-0.3-0.7 0-1.4 0.3-0.6 0.4-1 1.1-0.4 0.7-0.4 1.7 0 1.5 0.8 2.4 0.8 0.8 2 0.8zm18.1-8q1.1 0 1.9 0.4 0.9 0.5 1.4 1.4 0.5 0.9 0.5 2.2v5.5h-1.9v-5.2q0-1.3-0.6-2-0.6-0.6-1.7-0.6-1.1 0-1.8 0.6-0.6 0.7-0.6 2v5.2h-1.9v-5.2q0-1.3-0.6-2-0.7-0.6-1.8-0.6-1.1 0-1.7 0.6-0.6 0.7-0.6 2v5.2h-2v-9.4h2v1.1q0.5-0.6 1.2-0.9 0.7-0.3 1.5-0.3 1.2 0 2.1 0.5 0.8 0.4 1.3 1.3 0.4-0.8 1.3-1.3 0.9-0.5 2-0.5zm14.9 4.6q0 0.5-0.1 0.9h-7.1q0 1.2 0.8 1.8 0.7 0.7 1.8 0.7 1.6 0 2.2-1.3h2.1q-0.4 1.3-1.5 2.1-1.1 0.9-2.8 0.9-1.3 0-2.4-0.6-1-0.7-1.6-1.7-0.6-1.1-0.6-2.6 0-1.4 0.5-2.5 0.6-1.1 1.7-1.7 1.1-0.6 2.4-0.6 1.4 0 2.4 0.6 1 0.5 1.6 1.6 0.6 1 0.6 2.4zm-2-0.6q-0.1-1.1-0.8-1.7-0.7-0.7-1.9-0.7-1 0-1.7 0.7-0.7 0.6-0.8 1.7zm5.8-2.5q0.5-0.7 1.2-1.1 0.7-0.4 1.7-0.4v2h-0.5q-1.2 0-1.8 0.6-0.6 0.5-0.6 2v4.9h-1.9v-9.4h1.9zm8 8.2q-1.1 0-2-0.4-0.8-0.4-1.3-1.1-0.5-0.7-0.6-1.5h2q0.1 0.6 0.6 1 0.5 0.4 1.3 0.4 0.8 0 1.3-0.3 0.4-0.4 0.4-0.9 0-0.5-0.5-0.7-0.5-0.3-1.6-0.6-1-0.3-1.7-0.6-0.7-0.2-1.1-0.8-0.5-0.6-0.5-1.5 0-0.7 0.4-1.3 0.5-0.7 1.3-1 0.8-0.4 1.9-0.4 1.6 0 2.5 0.8 1 0.8 1.1 2.2h-1.9q-0.1-0.6-0.5-1-0.5-0.3-1.3-0.3-0.8 0-1.2 0.2-0.4 0.3-0.4 0.8 0 0.4 0.3 0.6 0.3 0.3 0.7 0.4 0.4 0.2 1.1 0.4 1 0.3 1.7 0.6 0.7 0.2 1.1 0.8 0.5 0.5 0.5 1.4 0 0.8-0.4 1.5-0.5 0.6-1.3 0.9-0.8 0.4-1.9 0.4z" />
                        </svg>
                    </div>
                    <div class="menu-title">Manage Ticket</div>
                </a>
            </li>
        @endif
        @if (can('customers'))
            <li>

                <a href="{{ url('/all-customers') }}"
                    class="{{ Route::currentRouteName() == 'allCustomers' ? 'active' : '' }}">
                    <div class="parent-icon">


                        <svg width="20" height="25" viewBox="0 0 20 25" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_347_6520)">
                                <path
                                    d="M6.77228 5.76636H5.16153C4.73433 5.76636 4.32463 5.97495 4.02256 6.34626C3.72048 6.71757 3.55078 7.22116 3.55078 7.74627V19.6257C3.55078 20.1509 3.72048 20.6545 4.02256 21.0258C4.32463 21.3971 4.73433 21.6057 5.16153 21.6057H9.74975"
                                    stroke="#FDCF6F" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M14.826 12.6961V7.74627C14.826 7.22116 14.6563 6.71757 14.3542 6.34626C14.0521 5.97495 13.6424 5.76636 13.2152 5.76636H11.6045"
                                    stroke="#FDCF6F" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M9.99347 3.78638H8.38272C7.49313 3.78638 6.77197 4.67281 6.77197 5.76629C6.77197 6.85977 7.49313 7.7462 8.38272 7.7462H9.99347C10.8831 7.7462 11.6042 6.85977 11.6042 5.76629C11.6042 4.67281 10.8831 3.78638 9.99347 3.78638Z"
                                    stroke="#FDCF6F" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M6.77197 11.7061H9.99347" stroke="#FDCF6F" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M6.77197 15.6659H9.1881" stroke="#FDCF6F" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M13.6179 20.6157C14.7299 20.6157 15.6314 19.5076 15.6314 18.1408C15.6314 16.7739 14.7299 15.6659 13.6179 15.6659C12.5059 15.6659 11.6045 16.7739 11.6045 18.1408C11.6045 19.5076 12.5059 20.6157 13.6179 20.6157Z"
                                    stroke="#FDCF6F" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M15.2285 20.1207L17.242 22.5956" stroke="#FDCF6F" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_347_6520">
                                    <rect width="19.329" height="23.759" fill="white"
                                        transform="translate(0.329102 0.816528)" />
                                </clipPath>
                            </defs>
                        </svg>

                    </div>
                    <div class="menu-title">All Customers</div>
                </a>
            </li>
        @endif
        @if (can('event-bookings'))
            <li>
                <a href="{{ url('/events') }}" class="{{ Route::currentRouteName() == 'events' ? 'active' : '' }}">
                    <div class="parent-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_153_3370)">
                                <path
                                    d="M2.5 16.5C2.0875 16.5 1.73425 16.3533 1.44025 16.0598C1.14675 15.7658 1 15.4125 1 15L1 4.5C1 4.0875 1.14675 3.73425 1.44025 3.44025C1.73425 3.14675 2.0875 3 2.5 3L13 3C13.4125 3 13.7658 3.14675 14.0598 3.44025C14.3533 3.73425 14.5 4.0875 14.5 4.5L14.5 8.7375C14.2625 8.625 14.0187 8.53425 13.7687 8.46525C13.5187 8.39675 13.2625 8.34375 13 8.30625V4.5L2.5 4.5L2.5 11.25H5.65C5.7625 11.5875 5.95 11.8813 6.2125 12.1313C6.475 12.3813 6.76875 12.5562 7.09375 12.6563C7.05625 12.9063 7.03125 13.1593 7.01875 13.4153C7.00625 13.6718 7.01875 13.925 7.05625 14.175C6.60625 14.0875 6.18425 13.922 5.79025 13.6785C5.39675 13.4345 5.05 13.125 4.75 12.75H2.5V15L7.24375 15C7.33125 15.275 7.43125 15.5375 7.54375 15.7875C7.65625 16.0375 7.79375 16.275 7.95625 16.5H2.5ZM2.5 15L7.24375 15L2.5 15ZM11.5187 17.25L11.2938 16.125C11.1438 16.0625 11.0033 15.997 10.8723 15.9285C10.7408 15.8595 10.6063 15.775 10.4687 15.675L9.38125 16.0125L8.63125 14.7375L9.49375 13.9875C9.46875 13.8375 9.45625 13.675 9.45625 13.5C9.45625 13.325 9.46875 13.1625 9.49375 13.0125L8.63125 12.2625L9.38125 10.9875L10.4687 11.325C10.6063 11.225 10.7408 11.1405 10.8723 11.0715C11.0033 11.003 11.1438 10.9375 11.2938 10.875L11.5187 9.75H13.0187L13.2438 10.875C13.3938 10.9375 13.5345 11.003 13.666 11.0715C13.797 11.1405 13.9312 11.225 14.0687 11.325L15.1562 10.9875L15.9062 12.2625L15.0438 13.0125C15.0688 13.1625 15.0812 13.325 15.0812 13.5C15.0812 13.675 15.0688 13.8375 15.0438 13.9875L15.9062 14.7375L15.1562 16.0125L14.0687 15.675C13.9312 15.775 13.797 15.8595 13.666 15.9285C13.5345 15.997 13.3938 16.0625 13.2438 16.125L13.0187 17.25H11.5187ZM12.2687 15C12.6812 15 13.0345 14.8533 13.3285 14.5598C13.622 14.2658 13.7687 13.9125 13.7687 13.5C13.7687 13.0875 13.622 12.7342 13.3285 12.4402C13.0345 12.1467 12.6812 12 12.2687 12C11.8562 12 11.5033 12.1467 11.2098 12.4402C10.9158 12.7342 10.7687 13.0875 10.7687 13.5C10.7687 13.9125 10.9158 14.2658 11.2098 14.5598C11.5033 14.8533 11.8562 15 12.2687 15Z"
                                    fill="#FDCF6F" />
                            </g>
                            <defs>
                                <clipPath id="clip0_153_3370">
                                    <rect width="20" height="20" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </div>
                    <div class="menu-title">Event Bookings</div>
                </a>
            </li>
        @endif
        @if (can('marketing'))
            <li>
                <a href="{{ url('/customers') }}" class="">
                    <div class="parent-icon">
                        <svg width="17" height="16" viewBox="0 0 17 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_285_4427)">
                                <path
                                    d="M14.9388 0L8.5 6L6.375 4L0 10.06L2.125 12.06L6.375 8L8.5 10L17 2L14.9388 0ZM0 14V16H17V14H0Z"
                                    fill="#FDCF6F" />
                            </g>
                            <defs>
                                <clipPath id="clip0_285_4427">
                                    <rect width="17" height="16" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </div>
                    <div class="menu-title">Marketing</div>
                </a>
            </li>
        @endif
        {{-- @if (can('membership'))
            <li>
                <a href="{{ url('/memberships') }}"
                    class="{{ Route::currentRouteName() == 'memberships' ? 'active' : '' }}">
                    <div class="parent-icon">
                        <svg width="15" height="16" viewBox="0 0 15 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M13.5 0H1.5C0.6675 0 0 0.712 0 1.6V10.4C0 11.288 0.6675 12 1.5 12H4.5V16L7.5 14.4L10.5 16V12H13.5C14.3325 12 15 11.288 15 10.4V1.6C15 0.712 14.3325 0 13.5 0ZM13.5 10.4H1.5V8.8H13.5V10.4ZM13.5 6.4H1.5V1.6H13.5V6.4Z"
                                fill="#FACB6A" />
                        </svg>
                    </div>
                    <div class="menu-title">Memberships</div>
                </a>
            </li>
        @endif --}}
        {{-- @if (can('member-history'))
            <li>
                <a href="{{ url('/membership-history') }}"
                    class="{{ Route::currentRouteName() == 'membershipHistory' ? 'active' : '' }}">
                    <div class="parent-icon">
                        <svg width="15" height="16" viewBox="0 0 15 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M13.5 0H1.5C0.6675 0 0 0.712 0 1.6V10.4C0 11.288 0.6675 12 1.5 12H4.5V16L7.5 14.4L10.5 16V12H13.5C14.3325 12 15 11.288 15 10.4V1.6C15 0.712 14.3325 0 13.5 0ZM13.5 10.4H1.5V8.8H13.5V10.4ZM13.5 6.4H1.5V1.6H13.5V6.4Z"
                                fill="#FACB6A" />
                        </svg>
                    </div>
                    <div class="menu-title">Member History</div>
                </a>
            </li>
        @endif --}}

        @php
            $isReportRoute =
                request('key') === 'Ticket' ||
                request('key') === 'Membership' ||
                request('key') === 'MembershipUsage' ||
                request('key') === 'Event' ||
                request('key') === 'Billing';
        @endphp
        @if (can('reports-ticket') ||
                can('reports-membership') ||
                can('reports-event') ||
                can('reports-openbilling') ||
                can('report-membership-user'))
            <li onclick="showReportMenu()" class="{{ $isReportRoute ? 'mm-active' : '' }}">

                <a href="javascript:void(0);" aria-expanded="{{ $isReportRoute ? 'true' : 'false' }}"
                    style="justify-content: space-between;">
                    <div class="parent-icon">
                        <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 4H6V20H4V4ZM10 10H12V20H10V10ZM16 6H18V20H16V6Z" fill="#FDCF6F" />
                        </svg>

                    </div>

                    Reports <span class="fa arrow"></span>

                </a>
                <ul aria-expanded="false" id="reportMenu" class="ml-3 {{ $isReportRoute ? '' : 'd-none' }}"
                    style="border:none; background:none;  ">
                    @if (can('reports-ticket'))
                        <li><a href="{{ url('/reports?key=Ticket') }}"
                                class="menu-title {{ request('key') === 'Ticket' ? 'active' : '' }}">Ticket Report</a>
                        </li>
                    @endif
                    @if (can('reports-membership'))
                        <li><a href="{{ url('/reports?key=Membership') }}"
                                class="menu-title {{ request('key') === 'Membership' ? 'active' : '' }}">Membership
                                Report</a>
                        </li>
                    @endif
                    @if (can('report-membership-user'))
                        <li><a href="{{ url('/reports?key=MembershipUsage') }}"
                                class="menu-title {{ request('key') === 'MembershipUsage' ? 'active' : '' }}">Membership
                                Usage</a>
                        </li>
                    @endif
                    @if (can('reports-event'))
                        <li><a href="{{ url('/reports?key=Event') }}"
                                class="menu-title {{ request('key') === 'Event' ? 'active' : '' }}">Event Report</a>
                        </li>
                    @endif
                    @if (can('reports-openbilling'))
                        <li><a href="{{ url('/reports?key=Billing') }}"
                                class="menu-title {{ request('key') === 'Billing' ? 'active' : '' }}">Open Billing
                                Report</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        
        @if (can('openbilling'))
            <li>
                <a href="{{ url('/open-billing') }}"
                    class="{{ Route::currentRouteName() == 'openBilling' ? 'active' : '' }}">
                    <div class="parent-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_153_3370)">
                                <path
                                    d="M2.5 16.5C2.0875 16.5 1.73425 16.3533 1.44025 16.0598C1.14675 15.7658 1 15.4125 1 15L1 4.5C1 4.0875 1.14675 3.73425 1.44025 3.44025C1.73425 3.14675 2.0875 3 2.5 3L13 3C13.4125 3 13.7658 3.14675 14.0598 3.44025C14.3533 3.73425 14.5 4.0875 14.5 4.5L14.5 8.7375C14.2625 8.625 14.0187 8.53425 13.7687 8.46525C13.5187 8.39675 13.2625 8.34375 13 8.30625V4.5L2.5 4.5L2.5 11.25H5.65C5.7625 11.5875 5.95 11.8813 6.2125 12.1313C6.475 12.3813 6.76875 12.5562 7.09375 12.6563C7.05625 12.9063 7.03125 13.1593 7.01875 13.4153C7.00625 13.6718 7.01875 13.925 7.05625 14.175C6.60625 14.0875 6.18425 13.922 5.79025 13.6785C5.39675 13.4345 5.05 13.125 4.75 12.75H2.5V15L7.24375 15C7.33125 15.275 7.43125 15.5375 7.54375 15.7875C7.65625 16.0375 7.79375 16.275 7.95625 16.5H2.5ZM2.5 15L7.24375 15L2.5 15ZM11.5187 17.25L11.2938 16.125C11.1438 16.0625 11.0033 15.997 10.8723 15.9285C10.7408 15.8595 10.6063 15.775 10.4687 15.675L9.38125 16.0125L8.63125 14.7375L9.49375 13.9875C9.46875 13.8375 9.45625 13.675 9.45625 13.5C9.45625 13.325 9.46875 13.1625 9.49375 13.0125L8.63125 12.2625L9.38125 10.9875L10.4687 11.325C10.6063 11.225 10.7408 11.1405 10.8723 11.0715C11.0033 11.003 11.1438 10.9375 11.2938 10.875L11.5187 9.75H13.0187L13.2438 10.875C13.3938 10.9375 13.5345 11.003 13.666 11.0715C13.797 11.1405 13.9312 11.225 14.0687 11.325L15.1562 10.9875L15.9062 12.2625L15.0438 13.0125C15.0688 13.1625 15.0812 13.325 15.0812 13.5C15.0812 13.675 15.0688 13.8375 15.0438 13.9875L15.9062 14.7375L15.1562 16.0125L14.0687 15.675C13.9312 15.775 13.797 15.8595 13.666 15.9285C13.5345 15.997 13.3938 16.0625 13.2438 16.125L13.0187 17.25H11.5187ZM12.2687 15C12.6812 15 13.0345 14.8533 13.3285 14.5598C13.622 14.2658 13.7687 13.9125 13.7687 13.5C13.7687 13.0875 13.622 12.7342 13.3285 12.4402C13.0345 12.1467 12.6812 12 12.2687 12C11.8562 12 11.5033 12.1467 11.2098 12.4402C10.9158 12.7342 10.7687 13.0875 10.7687 13.5C10.7687 13.9125 10.9158 14.2658 11.2098 14.5598C11.5033 14.8533 11.8562 15 12.2687 15Z"
                                    fill="#FDCF6F" />
                            </g>
                            <defs>
                                <clipPath id="clip0_153_3370">
                                    <rect width="20" height="20" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </div>
                    <div class="menu-title">Open Billing</div>
                </a>
            </li>
        @endif
        
        {{-- @if (can('admin'))
            <li>
                <a href="{{ url('/admin') }}" class="{{ Route::currentRouteName() == 'admin' ? 'active' : '' }}">
                    <div class="parent-icon">
                        <svg width="16" height="18" viewBox="0 0 16 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M6.85714 11.1429V18H0C0 16.1814 0.722447 14.4372 2.00841 13.1513C3.29437 11.8653 5.03852 11.1429 6.85714 11.1429ZM6.85714 10.2857C4.01571 10.2857 1.71429 7.98429 1.71429 5.14286C1.71429 2.30143 4.01571 0 6.85714 0C9.69857 0 12 2.30143 12 5.14286C12 7.98429 9.69857 10.2857 6.85714 10.2857ZM14.5714 13.7143H15.4286V18H8.57143V13.7143H9.42857V12.8571C9.42857 12.1752 9.69949 11.5211 10.1817 11.0389C10.664 10.5566 11.318 10.2857 12 10.2857C12.682 10.2857 13.336 10.5566 13.8183 11.0389C14.3005 11.5211 14.5714 12.1752 14.5714 12.8571V13.7143ZM12.8571 13.7143V12.8571C12.8571 12.6298 12.7668 12.4118 12.6061 12.2511C12.4453 12.0903 12.2273 12 12 12C11.7727 12 11.5547 12.0903 11.3939 12.2511C11.2332 12.4118 11.1429 12.6298 11.1429 12.8571V13.7143H12.8571Z"
                                fill="#FDCF6F" />
                        </svg>
                    </div>
                    <div class="menu-title">Admin</div>
                </a>
            </li>
        @endif --}}
        {{-- <li>
            <a href="{{ url('/staffs') }}" class="{{ Route::currentRouteName() == 'staffs' ? 'active' : '' }}">
                <div class="parent-icon">
                    <svg width="16" height="18" viewBox="0 0 16 18" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M6.85714 11.1429V18H0C0 16.1814 0.722447 14.4372 2.00841 13.1513C3.29437 11.8653 5.03852 11.1429 6.85714 11.1429ZM6.85714 10.2857C4.01571 10.2857 1.71429 7.98429 1.71429 5.14286C1.71429 2.30143 4.01571 0 6.85714 0C9.69857 0 12 2.30143 12 5.14286C12 7.98429 9.69857 10.2857 6.85714 10.2857ZM14.5714 13.7143H15.4286V18H8.57143V13.7143H9.42857V12.8571C9.42857 12.1752 9.69949 11.5211 10.1817 11.0389C10.664 10.5566 11.318 10.2857 12 10.2857C12.682 10.2857 13.336 10.5566 13.8183 11.0389C14.3005 11.5211 14.5714 12.1752 14.5714 12.8571V13.7143ZM12.8571 13.7143V12.8571C12.8571 12.6298 12.7668 12.4118 12.6061 12.2511C12.4453 12.0903 12.2273 12 12 12C11.7727 12 11.5547 12.0903 11.3939 12.2511C11.2332 12.4118 11.1429 12.6298 11.1429 12.8571V13.7143H12.8571Z"
                            fill="#FDCF6F" />
                    </svg>
                </div>
                <div class="menu-title">Staffs</div>
            </a>
        </li> --}}
        {{-- @if (auth('admin')->user()->isSuperAdmin)
        <li><a href="{{ route('trash') }}" class="nav-link"><i class="fa fa-list me-3"></i>Trash Items</a>
        </li>
        @endif --}}
        {{-- @if (can('permission-manage'))
            <li><a href="{{ route('permission.index') }}" class="nav-link"><i class="fa fa-lock me-3"></i>Add
                    Permission</a>
            </li>
            <li><a href="{{ route('admin.admin.role') }}" class="nav-link"><i class="fa fa-lock me-3"></i>Manage
                    Role</a>
            </li>
            <li><a href="{{ route('admin.admin.permission') }}" class="nav-link"><i
                        class="fa fa-users me-3"></i>Manage
                    Admin</a>
            </li>
        @endif --}}
        @php
            $isSystemRoute =
                request()->is('permission*') ||
                request()->is('users/role*') ||
                request()->is('users/admin*') ||
                request()->is('admin*');
        @endphp


        @if (can('permission-manage') || auth('admin')->user()->isSuperAdmin || can('admin'))
            <li onclick="showReportSystemMenu()" class="{{ $isSystemRoute ? 'mm-active' : '' }}">

                <a href="javascript:void(0);" aria-expanded="{{ $isSystemRoute ? 'true' : 'false' }}"
                    style="justify-content: space-between;">
                    <div class="parent-icon">
                        <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M4 4H8V8H4V4ZM10 4H20V6H10V4ZM10 8H20V10H10V8ZM4 10H8V14H4V10ZM10 12H20V14H10V12ZM4 16H8V20H4V16ZM10 16H20V18H10V16Z"
                                fill="#FDCF6F" />
                        </svg>

                    </div>System <span class="fa arrow"></span>

                </a>

                <ul aria-expanded="false" id="reportSystemMenu" class="ml-3 {{ $isSystemRoute ? '' : 'd-none' }}"
                    style="border:none; background:none;">
                    @if (can('permission-manage'))
                        <li><a href="{{ route('permission.index') }}"
                                class="nav-link {{ request()->is('permission') ? 'active' : '' }}"><i
                                    class="fa fa-lock me-3"></i>Add Permission</a>
                        </li>
                        <li><a href="{{ route('admin.admin.role') }}"
                                class="nav-link {{ request()->is('users/role') ? 'active' : '' }}"><i
                                    class="fa fa-lock me-3"></i>Manage Role</a>
                        </li>
                        <li><a href="{{ route('admin.admin.permission') }}"
                                class="nav-link {{ request()->is('users/admin') ? 'active' : '' }}"><i
                                    class="fa fa-users me-3"></i>Manage Admin</a>
                        </li>
                    @endif
                    @if (can('admin'))
                        <li>
                            <a href="{{ url('/admin') }}"
                                class="{{ Route::currentRouteName() == 'admin' ? 'active' : '' }}">
                                <i class="fa fa-user me-3"></i>Admin
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        <li>
            <a href="{{ route('logoutA') }}" class="{{ Route::currentRouteName() == 'logout' ? 'active' : '' }}">
                <div class="parent-icon">
                    <svg width="16" height="18" viewBox="0 0 16 18" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M6.85714 11.1429V18H0C0 16.1814 0.722447 14.4372 2.00841 13.1513C3.29437 11.8653 5.03852 11.1429 6.85714 11.1429ZM6.85714 10.2857C4.01571 10.2857 1.71429 7.98429 1.71429 5.14286C1.71429 2.30143 4.01571 0 6.85714 0C9.69857 0 12 2.30143 12 5.14286C12 7.98429 9.69857 10.2857 6.85714 10.2857ZM14.5714 13.7143H15.4286V18H8.57143V13.7143H9.42857V12.8571C9.42857 12.1752 9.69949 11.5211 10.1817 11.0389C10.664 10.5566 11.318 10.2857 12 10.2857C12.682 10.2857 13.336 10.5566 13.8183 11.0389C14.3005 11.5211 14.5714 12.1752 14.5714 12.8571V13.7143ZM12.8571 13.7143V12.8571C12.8571 12.6298 12.7668 12.4118 12.6061 12.2511C12.4453 12.0903 12.2273 12 12 12C11.7727 12 11.5547 12.0903 11.3939 12.2511C11.2332 12.4118 11.1429 12.6298 11.1429 12.8571V13.7143H12.8571Z"
                            fill="#FDCF6F" />
                    </svg>
                </div>
                <div class="menu-title">Logout</div>
            </a>
        </li>
    </ul>
    <!--end navigation-->
</div>
<!--end sidebar wrapper -->
<script>
    function showReportMenu() {
        const menu = document.querySelector('#reportMenu');
        menu?.classList.toggle('d-none');
    }

    function showReportSystemMenu() {
        const menu = document.querySelector('#reportSystemMenu');
        menu?.classList.toggle('d-none');
    }

    function showMembershipSystemMenu() {
        const menu = document.querySelector('#membershipMenu');
        menu?.classList.toggle('d-none');
    }
</script>
