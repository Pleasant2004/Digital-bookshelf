const bookList = document.getElementById("book-list");
const booksTable = document.getElementById("books-table");
const tableBody = document.getElementById("table-body");

let allBooks = [];
let viewMode = 'grid'; // 'grid' or 'table'

window.onload = () => {
    fetchBooks();
    // If grid/table toggle exists (from newer UI), wire it. Otherwise ignore.
    const gridBtn = document.getElementById('gridViewBtn');
    const tableBtn = document.getElementById('tableViewBtn');
    if (gridBtn && tableBtn) {
        gridBtn.addEventListener('click', () => setView('grid'));
        tableBtn.addEventListener('click', () => setView('table'));
        setView('grid');
    }
};

// FETCH BOOKS
function fetchBooks(){

    fetch("get_books.php")

    .then(response => response.json())

    .then(data => {

        allBooks = data;

        displayBooks(data);

    })

    .catch(error => {

        console.error("Fetch Error:", error);

    });
}

// DISPLAY BOOKS
function displayBooks(data){
    if(viewMode === 'grid'){
        renderGrid(data);
    } else {
        renderTable(data);
    }
}

function renderGrid(data){
    bookList.style.display = '';
    booksTable.style.display = 'none';
    bookList.innerHTML = '';

    if(data.length === 0){
        bookList.innerHTML = `<div class="empty">No books added yet.</div>`;
        return;
    }

    data.forEach(book => {
        const card = document.createElement('div');
        card.className = 'book-card';

        const title = document.createElement('h4');
        title.textContent = book.title;
        title.style.cursor = 'pointer';
        title.onclick = () => openBook(book.id);

        const meta = document.createElement('div');
        meta.className = 'book-meta';
        meta.innerHTML = `${book.author} • ${book.genre || ''}`;

        const status = document.createElement('div');
        status.className = 'book-meta';
        status.style.fontWeight = '600';
        status.textContent = book.is_read == 1 ? 'Read' : 'Unread';

        const actions = document.createElement('div');
        actions.className = 'card-actions';

        if(book.file_name){
            const open = document.createElement('button');
            open.className = 'icon-btn open-btn';
            open.innerHTML = '<i class="fa-solid fa-file-arrow-up"></i> Open';
            open.onclick = (e) => { e.stopPropagation(); window.open(`preview.php?name=${encodeURIComponent(book.file_name)}`, '_blank'); };
            actions.appendChild(open);

            const dl = document.createElement('button');
            dl.className = 'icon-btn download-btn';
            dl.innerHTML = '<i class="fa-solid fa-download"></i>';
            dl.title = 'Download';
            dl.onclick = (e) => { e.stopPropagation(); window.open(`file.php?name=${encodeURIComponent(book.file_name)}&download=1`, '_blank'); };
            actions.appendChild(dl);
        }

        const readBtn = document.createElement('button');
        readBtn.className = 'icon-btn read-btn';
        readBtn.innerHTML = '<i class="fa-solid fa-book"></i>';
        readBtn.onclick = (e) => { e.stopPropagation(); toggleRead(book.id); };
        actions.appendChild(readBtn);

        const delBtn = document.createElement('button');
        delBtn.className = 'icon-btn delete-btn';
        delBtn.innerHTML = '<i class="fa-solid fa-trash"></i>';
        delBtn.onclick = (e) => { e.stopPropagation(); deleteBook(book.id); };
        actions.appendChild(delBtn);

        card.appendChild(title);
        card.appendChild(meta);
        card.appendChild(status);
        card.appendChild(actions);

        bookList.appendChild(card);
    });
}

function renderTable(data){
    bookList.style.display = 'none';
    booksTable.style.display = '';
    tableBody.innerHTML = '';

    if(data.length === 0){
        tableBody.innerHTML = '<tr><td colspan="5" style="padding:20px;text-align:center;color:#64748b">No books added yet.</td></tr>';
        return;
    }

    data.forEach(book => {
        const tr = document.createElement('tr');

        const titleTd = document.createElement('td');
        titleTd.setAttribute('data-label','Title');
        const a = document.createElement('a');
        a.href = `book.php?id=${book.id}`;
        a.textContent = book.title;
        titleTd.appendChild(a);

        const authorTd = document.createElement('td');
        authorTd.setAttribute('data-label','Author');
        authorTd.textContent = book.author;

        const genreTd = document.createElement('td');
        genreTd.setAttribute('data-label','Genre');
        genreTd.textContent = book.genre || '';

        const statusTd = document.createElement('td');
        statusTd.setAttribute('data-label','Status');
        statusTd.textContent = book.is_read == 1 ? 'Read' : 'Unread';

        const actionsTd = document.createElement('td');
        actionsTd.setAttribute('data-label','Actions');
        actionsTd.className = 'action-cell';

        if(book.file_name){
            const open = document.createElement('button');
            open.className = 'icon-btn open-btn';
            open.style.padding = '6px 8px';
            open.innerHTML = '<i class="fa-solid fa-file-arrow-up"></i>';
            open.onclick = () => window.open(`preview.php?name=${encodeURIComponent(book.file_name)}`, '_blank');
            actionsTd.appendChild(open);

            // Download button — force attachment via file.php?download=1
            const dl = document.createElement('button');
            dl.className = 'icon-btn';
            dl.style.padding = '6px 8px';
            dl.style.background = '#64748b';
            dl.style.color = '#fff';
            dl.title = 'Download';
            dl.innerHTML = '<i class="fa-solid fa-download"></i>';
            dl.onclick = () => window.open(`file.php?name=${encodeURIComponent(book.file_name)}&download=1`, '_blank');
            actionsTd.appendChild(dl);
        }

        const readBtn = document.createElement('button');
        readBtn.className = 'icon-btn read-btn';
        readBtn.style.padding = '6px 8px';
        readBtn.innerHTML = '<i class="fa-solid fa-book"></i>';
        readBtn.onclick = () => toggleRead(book.id);
        actionsTd.appendChild(readBtn);

        const delBtn = document.createElement('button');
        delBtn.className = 'icon-btn delete-btn';
        delBtn.style.padding = '6px 8px';
        delBtn.innerHTML = '<i class="fa-solid fa-trash"></i>';
        delBtn.onclick = () => deleteBook(book.id);
        actionsTd.appendChild(delBtn);

        tr.appendChild(titleTd);
        tr.appendChild(authorTd);
        tr.appendChild(genreTd);
        tr.appendChild(statusTd);
        tr.appendChild(actionsTd);

        // Append to whichever table body exists (legacy index.php uses #book-list tbody)
        const targetBody = tableBody || bookList;
        targetBody.appendChild(tr);
    });
}

function setView(mode){
    viewMode = mode;
    document.getElementById('gridViewBtn').classList.toggle('active', mode === 'grid');
    document.getElementById('tableViewBtn').classList.toggle('active', mode === 'table');
    displayBooks(allBooks);
}

// SEARCH
function searchBooks(){

    const searchValue =
        document.getElementById("search")
        .value
        .toLowerCase();

    const filteredBooks = allBooks.filter(book => {

        return (

            book.title.toLowerCase()
            .includes(searchValue)

            ||

            book.author.toLowerCase()
            .includes(searchValue)

            ||

            book.genre.toLowerCase()
            .includes(searchValue)

        );

    });

    displayBooks(filteredBooks);
}

// ADD BOOK
function addBook(){

    const title =
        document.getElementById("title")
        .value
        .trim();

    const author =
        document.getElementById("author")
        .value
        .trim();

    const description =
        document.getElementById("description")
        .value
        .trim();

    const genre =
        document.getElementById("genre")
        .value;

    const file =
        document.getElementById("bookFile")
        .files[0];

    // VALIDATION
    if(title === "" || author === ""){

        alert("Please fill all fields");

        return;
    }

    // FORM DATA
    const formData = new FormData();

    formData.append("title", title);

    formData.append("author", author);

    formData.append("description", description);

    formData.append("genre", genre);

    formData.append("bookFile", file);

    fetch("add_book.php", {

        method: "POST",

        body: formData

    })

    .then(response => response.text())

    .then(data => {

        console.log(data);

        // CLEAR FORM
        document.getElementById("title").value = "";

        document.getElementById("author").value = "";

        document.getElementById("description").value = "";

        document.getElementById("bookFile").value = "";

        fetchBooks();

    })

    .catch(error => {

        console.error("Add Error:", error);

    });
}

// TOGGLE READ
function toggleRead(id){

    fetch("update_book.php", {

        method: "POST",

        headers: {
            "Content-Type":
            "application/x-www-form-urlencoded"
        },

        body: `id=${id}`
    })

    .then(response => response.text())

    .then(data => {

        console.log(data);

        fetchBooks();

    });
}

// DELETE BOOK
function deleteBook(id){

    const confirmDelete =
        confirm("Delete this book?");

    if(!confirmDelete){

        return;
    }

    fetch("delete_book.php", {

        method: "POST",

        headers: {
            "Content-Type":
            "application/x-www-form-urlencoded"
        },

        body: `id=${id}`
    })

    .then(response => response.text())

    .then(data => {

        console.log(data);

        fetchBooks();

    });
}

// OPEN BOOK DETAILS
function openBook(id){

    window.location.href =
    `book.php?id=${id}`;
}