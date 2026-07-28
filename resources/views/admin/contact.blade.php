@extends('layouts.admin')

@section('content')

<style>
    /* Toast Notification Styles */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }
    
    .toast {
        min-width: 300px;
        max-width: 450px;
        background: white;
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        transform: translateX(120%);
        transition: transform 0.5s ease-in-out;
        border-left: 5px solid;
    }
    
    .toast.show {
        transform: translateX(0);
    }
    
    .toast-success {
        border-left-color: #28a745;
    }
    
    .toast-error {
        border-left-color: #dc3545;
    }
    
    .toast-warning {
        border-left-color: #ffc107;
    }
    
    .toast-info {
        border-left-color: #17a2b8;
    }
    
    .toast-icon {
        margin-right: 15px;
        font-size: 24px;
        flex-shrink: 0;
    }
    
    .toast-content {
        flex: 1;
    }
    
    .toast-title {
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 2px;
        color: #333;
    }
    
    .toast-message {
        font-size: 14px;
        color: #666;
        margin: 0;
    }
    
    .toast-close {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: #999;
        padding: 0;
        margin-left: 10px;
        transition: color 0.3s;
    }
    
    .toast-close:hover {
        color: #333;
    }

    /* Custom styles for bulk actions - RESPONSIVE */
    .bulk-actions {
        display: none;
        background: #f8f9fa;
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .bulk-actions.show {
        display: flex;
    }
    
    .bulk-actions .selected-info {
        font-size: 14px;
        font-weight: 500;
        color: #333;
        white-space: nowrap;
    }
    
    .bulk-actions .selected-count {
        font-weight: 700;
        color: #0d6efd;
    }
    
    .bulk-actions .btn-group-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .bulk-actions .btn {
        white-space: nowrap;
        font-size: 14px;
        padding: 6px 16px;
    }
    
    .select-all-wrapper {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .select-all-wrapper input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    
    .select-all-wrapper label {
        margin: 0;
        cursor: pointer;
        font-weight: 500;
        font-size: 14px;
    }
    
    .contact-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    
    /* Responsive styles for bulk actions */
    @media (max-width: 768px) {
        .bulk-actions {
            padding: 10px 12px;
            gap: 8px;
            flex-direction: column;
            align-items: stretch;
        }
        
        .bulk-actions .selected-info {
            text-align: center;
            font-size: 13px;
        }
        
        .bulk-actions .btn-group-actions {
            justify-content: center;
            gap: 6px;
            flex-wrap: wrap;
        }
        
        .bulk-actions .btn {
            font-size: 12px;
            padding: 5px 12px;
            flex: 1;
            min-width: 80px;
        }
        
        .bulk-actions .btn i {
            margin-right: 4px;
        }
    }
    
    @media (max-width: 480px) {
        .bulk-actions {
            padding: 8px 10px;
            gap: 6px;
        }
        
        .bulk-actions .selected-info {
            font-size: 12px;
        }
        
        .bulk-actions .btn-group-actions {
            gap: 5px;
            flex-direction: column;
            width: 100%;
        }
        
        .bulk-actions .btn {
            font-size: 11px;
            padding: 4px 10px;
            width: 100%;
            justify-content: center;
        }
        
        .bulk-actions .btn i {
            font-size: 12px;
        }
    }
</style>

<div class="container-fluid px-2 px-sm-3 px-md-4 py-4">

    <div class="row mb-3">
        <div class="col">
            <h4 class="mb-0 h5 h4-sm fw-bold text-gray-800">
                <i class="bi bi-envelope me-2"></i>Contact Messages
            </h4>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            <!-- Bulk Actions Bar - Fully Responsive -->
            @if (hasPermission('contact.delete'))
            <div class="bulk-actions" id="bulkActions">
                <span class="selected-info">
                    <i class="bi bi-check2-square me-1"></i>
                    <span class="selected-count" id="selectedCount">0</span> 
                    contact(s) selected
                </span>
                <div class="btn-group-actions">
                    <button type="button" class="btn btn-danger btn-sm" id="deleteSelected">
                        <i class="bi bi-trash me-1"></i> Delete Selected
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="clearSelection">
                        <i class="bi bi-x-circle me-1"></i> Clear
                    </button>
                </div>
            </div>
            @endif

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            @if (hasPermission('contact.delete'))
                            <th style="width: 50px;">
                                <div class="select-all-wrapper">
                                    <input type="checkbox" id="selectAll">
                                    <label for="selectAll" class="d-none d-sm-inline">All</label>
                                </div>
                            </th>
                            @endif
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th class="d-none d-md-table-cell">Subject</th>
                            <th class="d-none d-lg-table-cell">Message</th>
                            @if (hasPermission('contact.edit'))
                            <th>Status</th>
                            @endif
                            @if (hasPermission('contact.delete'))
                            <th class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($contacts as $contact)

                        <tr id="contact-row-{{ $contact->id }}">

                            @if (hasPermission('contact.delete'))
                            <td class="text-center">
                                <input type="checkbox" class="contact-checkbox" data-id="{{ $contact->id }}">
                            </td>
                            @endif
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="fw-semibold">{{ $contact->full_name }}</span>
                                <div class="d-md-none text-muted small">
                                    {{ Str::limit($contact->subject, 20) }}
                                </div>
                            </td>
                            <td>
                                <a href="mailto:{{ $contact->email }}" class="text-decoration-none">
                                    {{ $contact->email }}
                                </a>
                            </td>
                            <td class="d-none d-md-table-cell">{{ Str::limit($contact->subject, 30) }}</td>
                            <td class="d-none d-lg-table-cell">{{ Str::limit($contact->message, 40) }}</td>
                            @if (hasPermission('contact.edit'))
                            <td>
                                @if($contact->status != 'resolved')
                                    <div class="d-flex align-items-center gap-2">
                                        <select
                                            id="status{{ $contact->id }}"
                                            name="status"
                                            class="form-select form-select-sm status-select"
                                            style="min-width: 130px; max-width: 160px; width: 100%;"
                                            data-contact-id="{{ $contact->id }}">

                                            @if($contact->status=='new')
                                                <option value="new" selected>New</option>
                                                <option value="in_progress">In Progress</option>
                                            @elseif($contact->status=='in_progress')
                                                <option value="in_progress" selected>In Progress</option>
                                                <option value="resolved">Resolved</option>
                                            @endif

                                        </select>
                                    </div>
                                @else
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Resolved
                                    </span>
                                @endif
                            </td>
                            @endif
                            @if (hasPermission('contact.delete'))
                            <td class="text-center">
                                <form
                                    action="{{ route('admin.contacts.destroy',$contact->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Delete this contact?')">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Delete Contact">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>
                            </td>
                            @endif
                        </tr>

                    @empty

                        <tr>
                            <td colspan="{{ (hasPermission('contact.delete') ? (hasPermission('contact.edit') ? 9 : 8) : (hasPermission('contact.edit') ? 7 : 6)) }}" class="text-center py-5">
                                <i class="bi bi-inbox fa-3x text-muted d-block mb-2"></i>
                                <h5 class="text-muted">No Records Found</h5>
                                <p class="text-muted small">No contact messages available.</p>
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="card-footer bg-white py-2 py-sm-3">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                    <small class="text-muted order-2 order-sm-1">
                        Showing {{ $contacts->firstItem() ?? 0 }} to {{ $contacts->lastItem() ?? 0 }}
                        of {{ $contacts->total() }} entries
                    </small>
                    <div class="order-1 order-sm-2 w-100 w-sm-auto">
                        {{ $contacts->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- Resolution Modal -->

<div class="modal fade" id="resolutionModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    <i class="bi bi-check-circle text-success me-2"></i>Resolve Contact Request
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <label class="form-label fw-semibold">
                    Resolution Message
                </label>

                <textarea
                    id="resolutionText"
                    class="form-control"
                    rows="5"
                    placeholder="Write resolution here..."></textarea>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    <i class="bi bi-x-circle me-1"></i> Cancel

                </button>

                <button
                    type="button"
                    class="btn btn-success"
                    id="submitResolution">

                    <i class="bi bi-check-circle me-1"></i> Resolve

                </button>

            </div>

        </div>

    </div>

</div>

<!-- Bulk Delete Confirmation Modal -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>Confirm Bulk Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="bulkDeleteCount">0</strong> selected contact(s)?</p>
                <p class="text-danger"><i class="bi bi-exclamation-circle me-1"></i>This action cannot be undone!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmBulkDelete">
                    <i class="bi bi-trash me-1"></i>Delete All
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    
    // Initialize tooltips
    var tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltips.map(function(el) {
        return new bootstrap.Tooltip(el);
    });
    
    let modal = new bootstrap.Modal(document.getElementById('resolutionModal'));
    let currentId = null;

    // Handle status change
    $(document).on('change', '.status-select', function() {
        let contactId = $(this).data('contact-id');
        let status = $(this).val();
        
        if(status === 'resolved') {
            currentId = contactId;
            $('#resolutionText').val('');
            modal.show();
        } else {
            updateStatus(contactId, status, null);
        }
    });

    // Handle resolution submission
    $('#submitResolution').on('click', function() {
        let resolution = $('#resolutionText').val().trim();
        
        if(resolution === '') {
            showToast('warning', 'Validation Error', 'Please enter resolution message.');
            return;
        }
        
        updateStatus(currentId, 'resolved', resolution);
        modal.hide();
    });

    // Function to update status via AJAX
    function updateStatus(contactId, status, resolution) {
        $.ajax({
            url: '/qatar-esports/admin/contacts/' + contactId + '/status',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'PATCH',
                status: status,
                resolution: resolution
            },
            success: function(response) {
                if(response.success) {
                    // Update the status cell
                    let row = $('#contact-row-' + contactId);
                    
                    if(status === 'resolved') {
                        // Find the status cell
                        let statusCell = row.find('td:eq(5)');
                        statusCell.html('<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Resolved</span>');
                        
                        // Show success toast for resolved
                        showToast('success', 'Status Updated', 'Contact request has been resolved successfully.');
                    } else {
                        // Update the select dropdown with new status
                        let statusCell = row.find('td:eq(5)');
                        let options = '';
                        let statusText = '';
                        
                        if(status === 'in_progress') {
                            options = `
                                <option value="in_progress" selected>In Progress</option>
                                <option value="resolved">Resolved</option>
                            `;
                            statusText = 'In Progress';
                        } else if(status === 'new') {
                            options = `
                                <option value="new" selected>New</option>
                                <option value="in_progress">In Progress</option>
                            `;
                            statusText = 'New';
                        }
                        
                        statusCell.html(`
                            <div class="d-flex align-items-center gap-2">
                                <select class="form-select form-select-sm status-select" style="min-width:130px; max-width:160px; width:100%;" data-contact-id="${contactId}">
                                    ${options}
                                </select>
                            </div>
                        `);
                        
                        // Show success toast for status change
                        showToast('success', 'Status Updated', `Contact status changed to ${statusText}.`);
                    }
                    
                    // Also show alert for backward compatibility
                    showAlert('success', response.message);
                }
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred while updating status.';
                if(xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                // Show error toast
                showToast('error', 'Error', errorMessage);
                showAlert('error', errorMessage);
                
                // Reset the select to previous value - reload page to revert
                location.reload();
            }
        });
    }

    // Function to show alerts (backward compatibility)
    function showAlert(type, message) {
        let alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        let alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Remove existing alerts
        $('.alert').remove();
        
        // Insert new alert at the top of the content
        $('.container-fluid').prepend(alertHtml);
        
        // Auto dismiss after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    }

    // Toast Notification System
    function showToast(type, title, message) {
        const toastContainer = document.getElementById('toastContainer');
        
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        // Set icon based on type
        let icon = '';
        switch(type) {
            case 'success':
                icon = '✅';
                break;
            case 'error':
                icon = '❌';
                break;
            case 'warning':
                icon = '⚠️';
                break;
            case 'info':
                icon = 'ℹ️';
                break;
            default:
                icon = '📢';
        }
        
        toast.innerHTML = `
            <div class="toast-icon">${icon}</div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <p class="toast-message">${message}</p>
            </div>
            <button class="toast-close" onclick="closeToast(this)">×</button>
        `;
        
        // Add to container
        toastContainer.appendChild(toast);
        
        // Show with animation
        setTimeout(() => {
            toast.classList.add('show');
        }, 100);
        
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            closeToast(toast);
        }, 5000);
    }

    // Close toast function
    window.closeToast = function(element) {
        const toast = element.closest ? element.closest('.toast') : element;
        if (toast) {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 500);
        }
    };

    // Close toast on click
    $(document).on('click', '.toast', function(e) {
        // Don't close if clicking the close button
        if (!$(e.target).hasClass('toast-close')) {
            closeToast(this);
        }
    });

    // ===== BULK DELETE FUNCTIONALITY =====
    
    let selectedContacts = new Set();
    const bulkActions = $('#bulkActions');
    const selectedCount = $('#selectedCount');
    const selectAllCheckbox = $('#selectAll');
    const deleteSelectedBtn = $('#deleteSelected');
    const clearSelectionBtn = $('#clearSelection');
    let bulkDeleteModal = new bootstrap.Modal(document.getElementById('bulkDeleteModal'));

    // Function to update bulk actions visibility and count
    function updateBulkActions() {
        const count = selectedContacts.size;
        selectedCount.text(count);
        
        if (count > 0) {
            bulkActions.addClass('show');
        } else {
            bulkActions.removeClass('show');
            // Uncheck select all if no items selected
            selectAllCheckbox.prop('checked', false);
        }
        
        // Update select all checkbox state
        const totalCheckboxes = $('.contact-checkbox').length;
        const checkedCheckboxes = $('.contact-checkbox:checked').length;
        
        if (totalCheckboxes > 0 && checkedCheckboxes === totalCheckboxes) {
            selectAllCheckbox.prop('checked', true);
        } else {
            selectAllCheckbox.prop('checked', false);
        }
    }

    // Handle individual checkbox change
    $(document).on('change', '.contact-checkbox', function() {
        const id = $(this).data('id');
        
        if ($(this).is(':checked')) {
            selectedContacts.add(id);
        } else {
            selectedContacts.delete(id);
        }
        
        updateBulkActions();
    });

    // Handle select all
    selectAllCheckbox.on('change', function() {
        const isChecked = $(this).is(':checked');
        
        $('.contact-checkbox').each(function() {
            const id = $(this).data('id');
            $(this).prop('checked', isChecked);
            
            if (isChecked) {
                selectedContacts.add(id);
            } else {
                selectedContacts.delete(id);
            }
        });
        
        updateBulkActions();
    });

    // Handle clear selection
    clearSelectionBtn.on('click', function() {
        selectedContacts.clear();
        $('.contact-checkbox').prop('checked', false);
        updateBulkActions();
    });

    // Handle delete selected button click
    deleteSelectedBtn.on('click', function() {
        const count = selectedContacts.size;
        if (count === 0) {
            showToast('warning', 'No Selection', 'Please select at least one contact to delete.');
            return;
        }
        
        // Show confirmation modal
        $('#bulkDeleteCount').text(count);
        bulkDeleteModal.show();
    });

    // Handle confirm bulk delete
    $('#confirmBulkDelete').on('click', function() {
        const contactIds = Array.from(selectedContacts);
        
        if (contactIds.length === 0) {
            showToast('warning', 'No Selection', 'Please select at least one contact to delete.');
            return;
        }
        
        // Send bulk delete request
        $.ajax({
            url: '{{ route("admin.contacts.bulk-delete") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ids: contactIds
            },
            success: function(response) {
                if (response.success) {
                    // Remove deleted rows from table
                    contactIds.forEach(id => {
                        $('#contact-row-' + id).fadeOut(300, function() {
                            $(this).remove();
                        });
                    });
                    
                    // Clear selection
                    selectedContacts.clear();
                    updateBulkActions();
                    
                    // Show success message
                    showToast('success', 'Success', response.message);
                    
                    // Check if table is empty and show no records message
                    if ($('.contact-checkbox').length === 0) {
                        location.reload(); // Reload to show empty state
                    }
                }
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred while deleting contacts.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showToast('error', 'Error', errorMessage);
            }
        });
        
        bulkDeleteModal.hide();
    });

    // Reset selection when modal is closed
    $('#bulkDeleteModal').on('hidden.bs.modal', function() {
        // Don't clear selection, user might want to try again
    });

});
</script>

@endsection