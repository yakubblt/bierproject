
const table = document.getElementById('bierTable');
const addBtn = document.getElementById('addBtn');
const deleteBtn = document.getElementById('deleteBtn');


addBtn.addEventListener('click', () => {
    location.href = 'add.html';
});

deleteBtn.addEventListener('click', () => {
    location.href = 'delete.html';
});

// API functie ophalen
async function fetchBier() {
    try {
        const response = await axios.get('http://localhost/bierproject/bier.php');
        renderTable(response.data);
    } catch (error) {
        console.error('Error fetching bier:', error);
    }
}

async function likeBier(id) {
    try {
        await axios.post('http://localhost/bierproject/bier.php', {
            actie: 'like',
            id: id
        });
        fetchBier(); 
    } catch (error) {
        console.error('Error liking bier:', error);
    }
}


function renderTable(bieren) {
    table.innerHTML = '';

    if (!Array.isArray(bieren) || bieren.length === 0) {
        return;
    }

    // HEADER
    const headerRow = document.createElement('tr');

    Object.keys(bieren[0]).forEach(key => {
        const th = document.createElement('th');
        th.textContent = key;
        headerRow.appendChild(th);
    });

    const likeHeader = document.createElement('th');
    likeHeader.textContent = 'Like';
    headerRow.appendChild(likeHeader);

    const deleteHeader = document.createElement('th');
    deleteHeader.textContent = 'Delete';    
    headerRow.appendChild(deleteHeader);

    table.appendChild(headerRow);

    // DATA RIJEN
    bieren.forEach(bier => {
        const tr = document.createElement('tr');

        Object.values(bier).forEach(value => {
            const td = document.createElement('td');
            td.textContent = value;
            tr.appendChild(td);
        });

        // LIKE BUTTON
        const likeTd = document.createElement('td');
        const likeBtn = document.createElement('button');
        likeBtn.textContent = `Like ${bier.likes}`;
        likeBtn.addEventListener('click', () => {
            likeBier(bier.id);
        });
        likeTd.appendChild(likeBtn);
        tr.appendChild(likeTd);

        // DELETE BUTTON
        const deleteTd = document.createElement('td');
        const deleteBtn = document.createElement('button');
        deleteBtn.textContent = "Delete";
        deleteBtn.addEventListener('click', () => {
            deleteBier(bier.id);
        });
        deleteTd.appendChild(deleteBtn);
        tr.appendChild(deleteTd);

        table.appendChild(tr);
    });
}

async function deleteBier(id) {
    try {
        await axios.delete(`http://localhost/bierproject/bier.php?id=${id}`);
        fetchBier(); // tabel opnieuw laden
    } catch (error) {
        console.error('Error deleting bier:', error);
    }
}



fetchBier();
