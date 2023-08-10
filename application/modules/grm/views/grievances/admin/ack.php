

<div class="content-wrapper" style="min-height: 1376.4px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Acknowledgement</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Grievance Registration Acknowledgement</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
        <div class="container my-2">
            <div class="card shadow-sm">
                <div class="card-header bg-info">
                    <span class="h5 text-white">Grievance Submitted Successfully</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-success text-justify">Public Grievance submitted successfully. Grievance ID is <?=$this->session->userdata('grievanceId')?>. Applicant will be notified about further processing of the grievance in applicant's registered mobile number.</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>