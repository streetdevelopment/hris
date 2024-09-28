@extends('layouts.layout')
@section('title', 'HRIS - Payslip')
@section('more_links')
<style>
    .signature-line {
        border-bottom: 1px solid black;
        /* Adjust thickness and color as needed */
        width: 100px;
        /* Adjust width as needed */
    }

    .borderless td {
        border-bottom: none;
    }

    .borderless td:first-child {
        font-weight: bold;
    }
</style>
@endsection
@section('content')
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h1>Attendance</h1>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Attendance</a></li>
                                <li class="breadcrumb-item active">DTR</li>
                            </ol>
                        </div>

                    </div>

                </div>
            </div>
            <!-- end page title -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="col-4">
                                    <table class="table borderless">
                                        <tr>
                                            <td class="font-weight-bold">Employee Name:</td>
                                            <td>John Doe</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Manager Name:</td>
                                            <td>John Doe</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-3">
                                    <table class="table borderless">
                                        <tr>
                                            <td class="font-weight-bold">Week Starting:</td>
                                            <td>2/17/2020</td>
                                    </table>
                                </div>
                            </div>
                            <div class="dtr-table">
                                <table id="datatable-buttons"
                                    class="mt-4 table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Day</th>
                                            <th>Time In</th>
                                            <th>Time Out</th>
                                            <th>Time In</th>
                                            <th>Time Out</th>
                                            <th>Work hours</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>2024/04/24</td>
                                            <td>Wednesday</td>
                                            <td>9:00 AM</td>
                                            <td>12:00PM</td>
                                            <td>1:00PM</td>
                                            <td>5:00 PM</td>
                                            <td>8</td>
                                        </tr>
                                        <tr>
                                            <td>2024/04/24</td>
                                            <td>Wednesday</td>
                                            <td>9:00 AM</td>
                                            <td>12:00PM</td>
                                            <td>1:00PM</td>
                                            <td>5:00 PM</td>
                                            <td>8</td>
                                        </tr>
                                        <tr>
                                            <td>2024/04/24</td>
                                            <td>Wednesday</td>
                                            <td>9:00 AM</td>
                                            <td>12:00PM</td>
                                            <td>1:00PM</td>
                                            <td>5:00 PM</td>
                                            <td>8</td>
                                        </tr>
                                        <tr>
                                            <td>2024/04/24</td>
                                            <td>Thursday</td>
                                            <td>9:00 AM</td>
                                            <td>12:00PM</td>
                                            <td>1:00PM</td>
                                            <td>5:00 PM</td>
                                            <td>8</td>
                                        </tr>
                                </table>
                                <h6 class="text-sm-end me-5">Total Hours: <span class="me-5 ms-5">16</span></h6>
                            </div>
                            <div class="dtr-table">
                                <table id="datatable-buttons"
                                    class="mt-4 table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Day</th>
                                            <th>Time In</th>
                                            <th>Time Out</th>
                                            <th>Time In</th>
                                            <th>Time Out</th>
                                            <th>Work hours</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>2024/04/24</td>
                                            <td>Wednesday</td>
                                            <td>9:00 AM</td>
                                            <td>12:00PM</td>
                                            <td>1:00PM</td>
                                            <td>5:00 PM</td>
                                            <td>8</td>
                                        </tr>
                                        <tr>
                                            <td>2024/04/24</td>
                                            <td>Wednesday</td>
                                            <td>9:00 AM</td>
                                            <td>12:00PM</td>
                                            <td>1:00PM</td>
                                            <td>5:00 PM</td>
                                            <td>8</td>
                                        </tr>
                                        <tr>
                                            <td>2024/04/24</td>
                                            <td>Wednesday</td>
                                            <td>9:00 AM</td>
                                            <td>12:00PM</td>
                                            <td>1:00PM</td>
                                            <td>5:00 PM</td>
                                            <td>8</td>
                                        </tr>
                                        <tr>
                                            <td>2024/04/24</td>
                                            <td>Thursday</td>
                                            <td>9:00 AM</td>
                                            <td>12:00PM</td>
                                            <td>1:00PM</td>
                                            <td>5:00 PM</td>
                                            <td>8</td>
                                        </tr>
                                </table>
                                <h6 class="text-sm-end me-5">Total Hours: <span class="me-5 ms-5">16</span></h6>
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <div class="d-flex flex-column">
                                        <div class="mt-5 d-flex flex-row justify-content-start">
                                            <div class="col-3">
                                                <h6>Employee's Signature:</h6>
                                            </div>
                                            <div class="signature-line"></div>
                                        </div>
                                        <div class="mt-5 row">
                                            <div class="col-3">
                                                <h6>Manager's Signature:</h6>
                                            </div>
                                            <div class="signature-line"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <table class="table borderless mt-5">
                                        <tr>
                                            <td>Total Hours:</td>
                                            <td>16</td>
                                        </tr>
                                        <tr>
                                            <td>Rate per hour:</td>
                                            <td>$25</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Total Pay:</td>
                                            <td>$11111</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection