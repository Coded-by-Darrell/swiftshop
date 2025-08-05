// Define functions first
async function handleAddAddress(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    try {
        const response = await fetch('/test-account/address', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        alert('Error adding address');
    }
}

async function handleAddAddress(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    // Explicitly handle checkbox for is_default
    const isDefaultCheckbox = e.target.querySelector('input[name="is_default"]');
    if (isDefaultCheckbox && isDefaultCheckbox.checked) {
        formData.set('is_default', '1');
    } else {
        formData.set('is_default', '0');
    }
    
    try {
        const response = await fetch('/test-account/address', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Close modal before reload to avoid focus warning
            const modal = bootstrap.Modal.getInstance(document.getElementById('addAddressModal'));
            if (modal) {
                modal.hide();
            }
            setTimeout(() => location.reload(), 300);
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        alert('Error adding address');
    }
}

// Then initialize when DOM loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Address Book JS loaded');
    
    const addForm = document.getElementById('addAddressForm');
    if (addForm) {
        addForm.addEventListener('submit', handleAddAddress);
    }
    
    const editForm = document.getElementById('editAddressForm');
    if (editForm) {
        editForm.addEventListener('submit', handleEditAddress);
    }
});

async function editAddress(addressId) {
    try {
        const response = await fetch(`/test-account/address/${addressId}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            const address = result.address;
            
            // Fill edit form
            document.getElementById('editAddressId').value = address.id;
            document.getElementById('editLabel').value = address.label;
            document.getElementById('editFullName').value = address.full_name;
            document.getElementById('editPhone').value = address.phone;
            document.getElementById('editCity').value = address.city;
            document.getElementById('editAddressLine1').value = address.address_line_1;
            document.getElementById('editAddressLine2').value = address.address_line_2 || '';
            document.getElementById('editPostalCode').value = address.postal_code;
            document.getElementById('editSetAsDefault').checked = address.is_default;
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('editAddressModal'));
            modal.show();
        }
    } catch (error) {
        alert('Error loading address');
    }
}

function deleteAddress(addressId) {
    if (confirm('Are you sure you want to delete this address?')) {
        performDeleteAddress(addressId);
    }
}

async function performDeleteAddress(addressId) {
    try {
        const response = await fetch(`/test-account/address/${addressId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        alert('Error deleting address');
    }
}