<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

                <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect">
                        <i class="bx bxs-dashboard"></i>
                        <span key="t-chat">{{ Auth()->user()->role == 'admin' ? Auth()->user()->company->co_name : 'Dashboard'}}</span>
                    </a>
                </li>

                <li class="menu-title" key="t-menu">Profile</li>

                @if(Auth()->user()->company->setup('profile')->status == 'Completed')
                <li>
                    <a href="{{route('profiling.profile.index')}}" class="waves-effect">
                        <i class="bx bxs-user"></i>
                        <span key="t-chat">{{Auth()->user()->fullname()}}</span>
                    </a>
                </li>
                @endif

                @if(Auth()->user()->role == 'admin')
                <li class="menu-title" key="t-menu">Emplopyees</li>

                @if(Auth()->user()->company->setup('profile')->status == 'Completed')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" aria-expanded="true">
                        <i class="bx bxs-user-pin"></i>
                        <span key="t-multi-level">Employees</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="true">
                        <li><a href="{{ route('profiling.employees.create') }}" key="t-level-1-1">Create</a></li>
                        <li><a href="{{ route('profiling.employees.index') }}" key="t-level-1-2">List</a></li>
                    </ul>
                </li>
                @endif
                @endif

                <li class="menu-title" key="t-menu">Attendance</li>

                @if(Auth()->user()->company->setup('profile')->status == 'Completed')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" aria-expanded="true">
                        <i class="bx bxs-time"></i>
                        <span key="t-multi-level">Attendance</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="true">
                        <li><a href="{{route('attendance.attendance.index')}}" key="t-level-1-1">Time In</a></li>
                        <li><a href="{{route('attendance.attendance.history')}}" key="t-level-1-2">Attendance History</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" aria-expanded="true">
                        <i class="bx bxs-envelope"></i>
                        <span key="t-multi-level">Leave</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="true">
                        <li><a href="{{route('attendance.leave.index')}}" key="t-level-1-1">Apply for Leave</a></li>
                        @if(Auth()->user()->company->policies->enable_overtime)
                        <li><a href="{{route('attendance.request.overtime')}}" key="t-level-1-2">Request for Overtime</a></li>
                        @endif
                        <li><a href="{{ route('profiling.profile.index') }}#credit-standing" key="t-level-1-3">My Credit Standing</a></li>
                    </ul>
                </li>
                @if(Auth()->user()->role == 'admin')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" aria-expanded="true">
                        <i class="bx bxs-file-find"></i>
                        <span key="t-multi-level">Applications</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="true">
                        <li><a href="{{route('attendance.applications.leave')}}" key="t-level-1-1">Leave Applications</a></li>
                        <li><a href="{{route('attendance.applications.overtime')}}" key="t-level-1-2">Overtime Requests</a></li>
                        <li><a href="{{route('attendance.leave_credits')}}" key="t-level-1-3">Employee Leave Credit Standing</a></li>
                    </ul>
                </li>
                @endif
                @endif

                <li class="menu-title" key="t-menu">Payroll</li>

                @if(Auth()->user()->company->setup('profile')->status == 'Completed')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" aria-expanded="true">
                        <i class="bx bx-money"></i>
                        <span key="t-multi-level">Payroll</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="true">
                        <li><a href="{{route('payroll.slips.employee', ['id' => Auth()->user()->id])}}" key="t-level-1-1">My Payslips</a></li>
                        @if(Auth()->user()->role == 'admin')
                        <li><a href="{{route('payroll.create')}}" key="t-level-1-2">Process Payroll</a></li>
                        <li><a href="{{route('payroll.index')}}" key="t-level-1-3">Payroll Runs</a></li>
                        <li><a href="{{route('payroll.slips')}}" key="t-level-1-4">Payment Slips</a></li>
                        @endif
                    </ul>
                </li>
                @endif

                <li class="menu-title" key="t-menu">Company</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" aria-expanded="true">
                        <i class="mdi mdi-office-building"></i>
                        <span key="t-multi-level">Departments</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="true">
                        @if(Auth()->user()->role == 'admin')
                        <li><a href="{{ route('company.departments.create') }}" key="t-level-1-1">Create</a></li>
                        @endif
                        <li><a href="{{ route('company.departments.index') }}" key="t-level-1-2">List</a></li>
                    </ul>
                </li>

                @if(Auth()->user()->role == 'admin')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" aria-expanded="true">
                        <i class="bx bxs-briefcase-alt-2"></i>
                        <span key="t-multi-level">Job Types</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="true">
                        <li><a href="{{ route('company.jobtypes.create') }}" key="t-level-1-1">Create</a></li>
                        <li><a href="{{ route('company.jobtypes.index') }}" key="t-level-1-2">List</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" aria-expanded="true">
                        <i class="bx bxs-file"></i>
                        <span key="t-multi-level">Documents</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="true">
                        <li><a href="{{ route('company.documents.index') }}" key="t-level-1-2">List</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" aria-expanded="true">
                        <i class="bx bxs-exit"></i>
                        <span key="t-multi-level">Types of Leave</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="true">
                        <li><a href="{{ route('company.leavetypes.create') }}" key="t-level-1-1">Create</a></li>
                        <li><a href="{{ route('company.leavetypes.index') }}" key="t-level-1-2">List</a></li>
                    </ul>
                </li>
                @endif

                <li>
                    <a href="{{route('company.policies.index')}}" class="waves-effect">
                        <i class="bx bxs-calendar-check"></i>
                        <span key="t-chat">Attendance Policies</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('company.deductions.index')}}" class="waves-effect">
                        <i class="bx bxs-minus-circle"></i>
                        <span key="t-chat">Deductions</span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->