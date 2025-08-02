<main id="main" class="main">
    <div class="pagetitle">
        <h1>Data Log Activity</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Log Data</a></li>
                <li class="breadcrumb-item active">Data Log Activity</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Log Activity</h5>
                    <ul class="list-unstyled">
                        <li>
                            <ul class="list-unstyled">
                                <li>
                                    <div class="alert alert-primary alert-dismissible fade show" role="alert"
                                        style="padding: 8px 12px; margin-bottom: 5px; font-size: 14px;">
                                        <h5 class="alert-heading" style="margin-bottom: 4px; font-size: 16px;">
                                            User Activity</h5>
                                        <ul style="margin-left: 15px; padding-left: 20px;">
                                            <li>Semua data aktivitas user akan di rekam disini.</li>
                                        </ul>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>

                    <!-- Table to Display Log Data -->
                    <div class="table-responsive">
                        <table id="logdataTable" class="table table-borderless" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Action</th>
                                    <th>Description</th>
                                    <th>IP Address</th>
                                    <th>User Agent</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>