const generateErrorElements = (response, target_div_selector = '#create-modal .modal-body') => {
    $('.response-error-wrapper').remove();

    let errorElement = document.createElement('div');
    errorElement.classList.add('alert', 'alert-danger', 'response-error-wrapper');
    let errorList = document.createElement('ol');
    errorList.classList.add('p-0');
    for (let key in response.response.data.errors) {
        let errorItem = document.createElement('li');
        errorItem.innerText = response.response.data.errors[key][0];
        errorList.appendChild(errorItem);
    }
    errorElement.appendChild(errorList);
    $(`${target_div_selector}`).prepend(errorElement);
};

const customSwal = ({
                        route = '',
                        method = 'post',
                        data = {},
                        successFunction = null,
                        errorFunction = null,
                        type = 'delete',
                        title = 'Are you sure?',
                        text = "You won't be able to revert this!",
                        icon = 'warning',
                        confirmButtonText = 'Yes, Delete!',
                        cancelButtonText = 'Discard!',
                    }) => {
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33333',
        confirmButtonText: confirmButtonText,
        cancelButtonText: cancelButtonText,
    }).then((result) => {
        if (result.isConfirmed) {
            if (method.toLowerCase() === 'post') {
                axios.post(route, data)
                    .then(successFunction)
                    .catch(errorFunction);
            } else {
                axios.get(route)
                    .then(successFunction)
                    .catch(errorFunction);
            }
        }
    });
};

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

const savingButton = (selector) => {
    selector.text('Saving...').attr('disabled', true);
};

const savedButton = (selector) => {
    selector.text('Confirm').attr('disabled', false);
};

const updatingButton = (selector) => {
    selector.text('Updating...').attr('disabled', true);
};

const updatedButton = (selector) => {
    selector.text('Update').attr('disabled', false);
};

const deletingButton = (selector) => {
    selector.text('Deleting...').attr('disabled', true);
}

const deletedButton = (selector) => {
    selector.text('Delete').attr('disabled', false);
}

const toastCustom = (icon = 'success', title = 'Successfull') => {
    return Toast.fire({
        icon: icon,
        title: title
    });
}

const toastError = (title = 'Error') => {
    return Toast.fire({
        icon: 'error',
        title: title
    });
}

const toastSuccess = (title = 'Successfull') => {
    return Toast.fire({
        icon: 'success',
        title: title
    });
}

const reloadThisPage = (time = 1500) => {
    setTimeout(() => {
        location.reload();
    }, time);
};

const removeCurrentTr = (selector) => {
    setTimeout(() => {
        selector.closest('tr').remove();
    }, 1000);
}

const selectableTagsList = (list) => {
    let input = document.querySelector('.tagify--custom-dropdown'),
        tagify = new Tagify(input, {
            whitelist: list,
            maxTags: 5,
            dropdown: {
                maxItems: 20,           // <- mixumum allowed rendered suggestions
                classname: 'tags-look', // <- custom classname for this dropdown, so it could be targeted
                enabled: 0,             // <- show suggestions on focus
                closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
            }
        })
}
