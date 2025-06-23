@extends('layouts.admin')

@section('title', 'API Monitoring Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line"></i>
                        API Monitoring Dashboard
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-primary" onclick="refreshData()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Overall Status -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div id="overall-status" class="alert alert-info">
                                <i class="fas fa-spinner fa-spin"></i> Loading overall status...
                            </div>
                        </div>
                    </div>

                    <!-- Service Status Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info" id="database-icon">
                                    <i class="fas fa-database"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Database</span>
                                    <span class="info-box-number" id="database-status">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success" id="cache-icon">
                                    <i class="fas fa-memory"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Cache</span>
                                    <span class="info-box-number" id="cache-status">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning" id="queue-icon">
                                    <i class="fas fa-list"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Queue</span>
                                    <span class="info-box-number" id="queue-status">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary" id="storage-icon">
                                    <i class="fas fa-hdd"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Storage</span>
                                    <span class="info-box-number" id="storage-status">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Endpoints Status -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Public Endpoints</h3>
                                </div>
                                <div class="card-body">
                                    <div id="public-endpoints">Loading...</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Protected Endpoints</h3>
                                </div>
                                <div class="card-body">
                                    <div id="protected-endpoints">Loading...</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Resources -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">System Resources</h3>
                                </div>
                                <div class="card-body">
                                    <div id="system-resources">Loading...</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Usage Statistics</h3>
                                </div>
                                <div class="card-body">
                                    <div id="usage-statistics">Loading...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Endpoint Test Modal -->
<div class="modal fade" id="endpointTestModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Test Endpoint</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="endpointTestForm">
                    <div class="form-group">
                        <label for="endpoint">Endpoint</label>
                        <input type="text" class="form-control" id="endpoint" name="endpoint" required>
                    </div>
                    <div class="form-group">
                        <label for="method">Method</label>
                        <select class="form-control" id="method" name="method" required>
                            <option value="GET">GET</option>
                            <option value="POST">POST</option>
                            <option value="PUT">PUT</option>
                            <option value="DELETE">DELETE</option>
                            <option value="PATCH">PATCH</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="data">Data (JSON)</label>
                        <textarea class="form-control" id="data" name="data" rows="4" placeholder='{"key": "value"}'></textarea>
                    </div>
                </form>
                <div id="testResult" class="mt-3" style="display: none;">
                    <h6>Test Result:</h6>
                    <pre id="testResultContent"></pre>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="testEndpoint()">Test Endpoint</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let refreshInterval;

$(document).ready(function() {
    loadAllData();
    // Refresh every 30 seconds
    refreshInterval = setInterval(loadAllData, 30000);
});

function loadAllData() {
    loadOverallStatus();
    loadEndpointsStatus();
    loadSystemResources();
    loadUsageStatistics();
}

function loadOverallStatus() {
    $.get('/api/monitoring/status')
        .done(function(response) {
            if (response.success) {
                updateOverallStatus(response.data);
                updateServiceStatus(response.data.services);
            }
        })
        .fail(function() {
            $('#overall-status').removeClass('alert-info').addClass('alert-danger')
                .html('<i class="fas fa-exclamation-triangle"></i> Failed to load status');
        });
}

function loadEndpointsStatus() {
    $.get('/api/monitoring/endpoints')
        .done(function(response) {
            if (response.success) {
                updateEndpointsStatus(response.data);
            }
        })
        .fail(function() {
            $('#public-endpoints, #protected-endpoints').html('<div class="alert alert-danger">Failed to load endpoints</div>');
        });
}

function loadSystemResources() {
    $.get('/api/monitoring/resources')
        .done(function(response) {
            if (response.success) {
                updateSystemResources(response.data);
            }
        })
        .fail(function() {
            $('#system-resources').html('<div class="alert alert-danger">Failed to load system resources</div>');
        });
}

function loadUsageStatistics() {
    $.get('/api/monitoring/usage')
        .done(function(response) {
            if (response.success) {
                updateUsageStatistics(response.data);
            }
        })
        .fail(function() {
            $('#usage-statistics').html('<div class="alert alert-danger">Failed to load usage statistics</div>');
        });
}

function updateOverallStatus(data) {
    let statusClass = 'alert-info';
    let statusIcon = 'fas fa-info-circle';
    
    if (data.overall_status === 'healthy') {
        statusClass = 'alert-success';
        statusIcon = 'fas fa-check-circle';
    } else if (data.overall_status === 'degraded') {
        statusClass = 'alert-warning';
        statusIcon = 'fas fa-exclamation-triangle';
    } else if (data.overall_status === 'unhealthy') {
        statusClass = 'alert-danger';
        statusIcon = 'fas fa-times-circle';
    }
    
    $('#overall-status')
        .removeClass('alert-info alert-success alert-warning alert-danger')
        .addClass(statusClass)
        .html(`
            <i class="${statusIcon}"></i>
            <strong>Overall Status: ${data.overall_status.toUpperCase()}</strong>
            <br>
            <small>
                Response Time: ${data.summary.response_time}ms | 
                Endpoints: ${data.summary.healthy_endpoints}/${data.summary.total_endpoints} Healthy
            </small>
        `);
}

function updateServiceStatus(services) {
    Object.keys(services).forEach(service => {
        const serviceData = services[service];
        const status = serviceData.status;
        
        let iconClass = 'bg-success';
        let statusText = 'Healthy';
        
        if (status === 'unhealthy') {
            iconClass = 'bg-danger';
            statusText = 'Unhealthy';
        } else if (status === 'warning') {
            iconClass = 'bg-warning';
            statusText = 'Warning';
        }
        
        $(`#${service}-icon`).removeClass('bg-success bg-danger bg-warning').addClass(iconClass);
        $(`#${service}-status`).text(statusText);
    });
}

function updateEndpointsStatus(data) {
    let publicHtml = '';
    let protectedHtml = '';
    
    // Public endpoints
    Object.keys(data.public).forEach(endpoint => {
        const endpointData = data.public[endpoint];
        publicHtml += createEndpointCard(endpoint, endpointData);
    });
    
    // Protected endpoints
    Object.keys(data.protected).forEach(endpoint => {
        const endpointData = data.protected[endpoint];
        protectedHtml += createEndpointCard(endpoint, endpointData);
    });
    
    $('#public-endpoints').html(publicHtml || '<p class="text-muted">No public endpoints found</p>');
    $('#protected-endpoints').html(protectedHtml || '<p class="text-muted">No protected endpoints found</p>');
}

function createEndpointCard(name, data) {
    const status = data.status;
    let badgeClass = 'badge-success';
    let statusText = 'Healthy';
    
    if (status === 'unhealthy') {
        badgeClass = 'badge-danger';
        statusText = 'Unhealthy';
    } else if (status === 'warning') {
        badgeClass = 'badge-warning';
        statusText = 'Warning';
    }
    
    return `
        <div class="endpoint-item mb-2 p-2 border rounded">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>${name.replace(/_/g, ' ').toUpperCase()}</strong>
                    <br>
                    <small class="text-muted">${data.method} ${data.endpoint}</small>
                </div>
                <div class="text-right">
                    <span class="badge ${badgeClass}">${statusText}</span>
                    ${data.response_time ? `<br><small>${data.response_time}ms</small>` : ''}
                </div>
            </div>
            ${data.requires_auth ? '<small class="text-info">Requires Authentication</small>' : ''}
            ${data.requires_admin ? '<small class="text-warning">Requires Admin</small>' : ''}
        </div>
    `;
}

function updateSystemResources(data) {
    const memory = data.memory;
    const disk = data.disk;
    
    let html = `
        <div class="row">
            <div class="col-6">
                <h6>Memory Usage</h6>
                <div class="progress mb-2">
                    <div class="progress-bar ${getProgressBarClass(memory.percentage)}" 
                         style="width: ${memory.percentage}%">
                        ${memory.percentage}%
                    </div>
                </div>
                <small class="text-muted">
                    ${formatBytes(memory.usage)} / ${memory.limit}
                </small>
            </div>
            <div class="col-6">
                <h6>Disk Usage</h6>
                <div class="progress mb-2">
                    <div class="progress-bar ${getProgressBarClass(disk.usage_percentage)}" 
                         style="width: ${disk.usage_percentage}%">
                        ${disk.usage_percentage}%
                    </div>
                </div>
                <small class="text-muted">
                    ${formatBytes(disk.total_space - disk.free_space)} / ${formatBytes(disk.total_space)}
                </small>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12">
                <h6>System Info</h6>
                <small class="text-muted">
                    PHP Version: ${data.php.version}<br>
                    Server: ${data.server.software}<br>
                    Time: ${data.server.server_time}
                </small>
            </div>
        </div>
    `;
    
    $('#system-resources').html(html);
}

function updateUsageStatistics(data) {
    let html = `
        <div class="row">
            <div class="col-6">
                <h6>Users</h6>
                <p class="mb-1">Total: ${data.users.total}</p>
                <p class="mb-1">Active: ${data.users.active}</p>
                <p class="mb-1">Pending: ${data.users.pending_verification}</p>
            </div>
            <div class="col-6">
                <h6>Auctions</h6>
                <p class="mb-1">Total: ${data.auctions.total}</p>
                <p class="mb-1">Active: ${data.auctions.active}</p>
                <p class="mb-1">Completed: ${data.auctions.completed}</p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-6">
                <h6>Collaterals</h6>
                <p class="mb-1">Total: ${data.collaterals.total}</p>
                <p class="mb-1">Active: ${data.collaterals.active}</p>
                <p class="mb-1">Pending: ${data.collaterals.pending_approval}</p>
            </div>
            <div class="col-6">
                <h6>Bids</h6>
                <p class="mb-1">Total: ${data.bids.total}</p>
                <p class="mb-1">Today: ${data.bids.today}</p>
                <p class="mb-1">This Week: ${data.bids.this_week}</p>
            </div>
        </div>
    `;
    
    $('#usage-statistics').html(html);
}

function getProgressBarClass(percentage) {
    if (percentage >= 90) return 'bg-danger';
    if (percentage >= 75) return 'bg-warning';
    return 'bg-success';
}

function formatBytes(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function refreshData() {
    loadAllData();
}

function testEndpoint() {
    const formData = {
        endpoint: $('#endpoint').val(),
        method: $('#method').val(),
        data: $('#data').val() ? JSON.parse($('#data').val()) : {}
    };
    
    $.post('/api/monitoring/test-endpoint', formData)
        .done(function(response) {
            if (response.success) {
                $('#testResult').show();
                $('#testResultContent').text(JSON.stringify(response.data, null, 2));
            }
        })
        .fail(function(xhr) {
            $('#testResult').show();
            $('#testResultContent').text('Error: ' + xhr.responseText);
        });
}

// Clean up interval on page unload
$(window).on('beforeunload', function() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
});
</script>
@endpush

@push('styles')
<style>
.endpoint-item {
    transition: all 0.3s ease;
}

.endpoint-item:hover {
    background-color: #f8f9fa;
}

.info-box {
    min-height: 80px;
}

.progress {
    height: 20px;
}

.progress-bar {
    line-height: 20px;
    font-size: 12px;
}
</style>
@endpush 