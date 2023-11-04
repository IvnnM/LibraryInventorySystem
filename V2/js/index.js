function showRoleSelection() {
    Swal.fire({
        title: 'Select a Role',
        icon: 'info',
        input: 'select',
        inputOptions: {
            'Admin': 'Admin',
            'Client': 'Client',
            'Librarian': 'Librarian'
        },
        inputPlaceholder: 'Select a role',
        showCancelButton: true,
        inputValidator: (value) => {
            if (!value) {
                return 'You need to select a role';
            }
            showAlertAndLogin(value);
        }
    });
}

function showAlertAndLogin(role) {
    Swal.fire(`You selected the '${role}' role.`);
    redirectToLogin(role);
}

function redirectToLogin(role) {
    let loginUrls = {
        'Admin': 'adminlogin.html',
        'Client': 'clientlogin.html',
        'Librarian': 'librarianlogin.html'
    };

    if (role in loginUrls) {
        window.location.href = loginUrls[role];
    } else {
        Swal.fire('Role not found');
    }
}