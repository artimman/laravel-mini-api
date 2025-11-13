/** 
 * Author: (C) Design by Malina
 */
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('formPet').addEventListener('submit', addPet);
    document.getElementById('getPetBtn').addEventListener('click', getPet);
    document.getElementById('updatePetBtn').addEventListener('click', updatePet);
    document.getElementById('deletePetBtn').addEventListener('click', deletePet);
    document.getElementById('clearFormBtn').addEventListener('click', clearForm);
});

/** Wyświetla komunikaty */
let messageTimeout;

function showMessage(message, type) {
    const formMessage = document.getElementById('formMessage');
    formMessage.innerHTML = message;

    const alertClasses = {
        success: 'bg-green-100 border border-green-400 text-green-700',
        error: 'bg-red-100 border border-red-400 text-red-700',
        warning: 'bg-yellow-100 border border-yellow-400 text-yellow-700',
        info: 'bg-blue-100 border border-blue-400 text-blue-700'
    };

    formMessage.className = `p-2 mb-2 rounded break-words ${alertClasses[type] || alertClasses.error}`;
    formMessage.classList.remove('hidden');

    if (messageTimeout) clearTimeout(messageTimeout);

    messageTimeout = setTimeout(() => {
        formMessage.classList.add('hidden');
    }, 10000);
}

/** Pobiera Pet ID */
function getPetId() {
    let id = document.getElementById('fieldPetId').value.trim();

    if (!id) {
        id = prompt('Enter Pet ID');
        if (id) {
            document.getElementById('fieldPetId').value = id;
        }
    }

    return id;
}

/** Pobiera dane z formularza */
function getFormData(includeId = false) {
    const data = {
        category_id: document.getElementById('fieldCategoryId').value.trim(),
        category_name: document.getElementById('fieldCategoryName').value.trim(),
        photo_urls: document.getElementById('fieldPhotoUrls').value.trim(),
        tags: document.getElementById('fieldTags').value.trim(),
        status: document.getElementById('fieldStatus').value
    };

    if (document.getElementById('fieldName').value.trim()) {
        data.name = document.getElementById('fieldName').value.trim();
    }

    if (includeId) {
        data.id = getPetId();
    }

    return data;
}

/** Wypełnia formularz danymi zwierzęcia */
function fillForm(pet) {
    document.getElementById('fieldPetId').value = pet.id || '';
    document.getElementById('fieldName').value = pet.name || '';
    document.getElementById('fieldCategoryId').value = pet.category?.id || '';
    document.getElementById('fieldCategoryName').value = pet.category?.name || '';
    document.getElementById('fieldPhotoUrls').value = pet.photoUrls?.join(', ') || '';
    document.getElementById('fieldTags').value = pet.tags?.map(tag => tag.name).join(', ') || '';
    document.getElementById('fieldStatus').value = pet.status || 'available';
}

/** Czyści wszystkie pola formularza */
function clearForm() {
    const formMessage = document.getElementById('formMessage');
    formMessage.innerHTML = '';
    formMessage.classList.add('hidden');

    document.getElementById('fieldPetId').value = '';
    document.getElementById('fieldName').value = '';
    document.getElementById('fieldCategoryId').value = '';
    document.getElementById('fieldCategoryName').value = '';
    document.getElementById('fieldPhotoUrls').value = '';
    document.getElementById('fieldTags').value = '';
    document.getElementById('fieldStatus').value = 'available';
    updatePetIdDisplay(null);
}

/** Aktualizuje wyświetlanie ID w nagłówku */
function updatePetIdDisplay(id) {
    const petIdContainer = document.getElementById('currentPetContainer');
    const petIdSpan = document.getElementById('currentPetId');

    if (id) {
        petIdSpan.innerText = id;
        petIdContainer.classList.remove('hidden');
    } else {
        petIdSpan.innerText = '';
        petIdContainer.classList.add('hidden');
    }
}

/** Dodaje zwierzę */
async function addPet(event) {
    event.preventDefault();
    const formData = getFormData();

    if (!formData.name) {
        showMessage('Pet name is required!', 'error');
        return;
    }

    try {
        const response = await fetch('/api/pet/create', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });

        const result = await response.json();
        document.getElementById('fieldPetId').value = result.id;
        showMessage(`<b>Pet added successfully! ID: ${result.id}.</b><br>Details: ` + JSON.stringify(result) + '.', 'success');
    } catch (error) {
        showMessage('Error: Unable to add pet.', 'error');
    }
}

/** Pobiera zwierzę i uzupełnia formularz */
async function getPet() {
    const id = getPetId();
    if (!id) {
        showMessage('Please enter Pet ID.', 'error');
        return;
    }

    try {
        const response = await fetch(`/api/pet/${id}`);
        const result = await response.json();

        if (result.id) {
            fillForm(result);
            updatePetIdDisplay(result.id);
            showMessage(`<b>Pet found. Name: ${result.name}, status: ${result.status}.</b><br>Details: ` + JSON.stringify(result) + '.', 'success');
        } else {
            showMessage('Pet not found!', 'error');
        }
    } catch (error) {
        showMessage('Error: Unable to fetch pet.', 'error');
    }
}

/** Aktualizuje zwierzę */
async function updatePet() {
    const formData = getFormData(true);
    
    if (!formData.id) {
        showMessage('Please enter Pet ID.', 'error');
        return;
    }

    const petExists = await checkPetExists(formData.id);

    if (!petExists) {
        showMessage('Pet not found. Please enter a valid Pet ID.', 'error');
        return;
    }

    if (!formData.name || !formData.status) {
        await getPet();

        const formData = getFormData(true);
        if (!formData.name || !formData.status) {
            showMessage('Complete the required fields (Name, Status).', 'warning');
            return;
        }

        showMessage('The required data has been filled in. Now you can proceed to editing.', 'info');
        return;
    } 

    try {
        const response = await fetch(`/api/pet/${formData.id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });

        const result = await response.json();
        showMessage(`<b>Pet updated! Name: ${result.name}, status: ${result.status}.</b><br>Details: ` + JSON.stringify(result) + '.', 'success');
    } catch (error) {
        showMessage('Error: Unable to update pet.', 'error');
    }
}

/** Usuwa zwierzę */
async function deletePet() {
    const id = getPetId();
    if (!id) {
        showMessage('Please enter Pet ID.', 'error');
        return;
    }

    try {
        const response = await fetch(`/api/pet/${id}`, { method: 'DELETE' });
        clearForm();
        showMessage('Pet deleted successfully.', 'success');
    } catch (error) {
        showMessage('Error: Unable to delete pet.', 'error');
    }
}

/** Sprawdza Pet ID */
async function checkPetExists(id) {
    try {
        const response = await fetch(`/api/pet/${id}`);
        const pet = await response.json();
        return pet.id ? true : false;
    } catch (error) {
        return false;
    }
}
