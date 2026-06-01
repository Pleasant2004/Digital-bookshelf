<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Bookshelf</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="app">

    <div class="header">
        <div class="brand">
            <div class="logo">DB</div>
            <div>
                <div class="title">Digital Bookshelf</div>
                <div style="font-size:12px;color:#64748b">Manage and open your local files</div>
            </div>
        </div>

        <div class="search-box">
            <input type="text" id="search" placeholder="Search by title, author, or genre" onkeyup="searchBooks()">
        </div>
    </div>

    <div class="layout">

        <aside class="panel">
            <h3 style="margin-bottom:12px">Add New Book</h3>

            <div class="book-form">
                <label for="title">Title</label>
                <input type="text" id="title" placeholder="Book Title">

                <label for="author">Author</label>
                <input type="text" id="author" placeholder="Author Name">

                <label for="description">Description</label>
                <textarea id="description" placeholder="Short description"></textarea>

                <label for="genre">Genre</label>
                <select id="genre">
                    <option>Fantasy</option>
                    <option>Education</option>
                    <option>Romance</option>
                    <option>Science Fiction</option>
                    <option>Thriller</option>
                </select>

                <label for="bookFile">Attach file</label>
                <div class="file-input">
                    <input type="file" id="bookFile" accept=".pdf,.doc,.docx,.txt">
                </div>

                <div style="margin-top:8px">
                    <button class="primary-btn" onclick="addBook()"><i class="fa-solid fa-plus"></i> Add Book</button>
                </div>
            </div>
        </aside>

        <main>
            <div class="panel">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                    <h3 style="margin:0">My Books</h3>
                    <div style="display:flex;gap:8px;align-items:center">
                        <button id="gridViewBtn" class="primary-btn" style="background:#0ea5a4;padding:8px 10px;font-size:13px">Grid</button>
                        <button id="tableViewBtn" class="primary-btn" style="background:#2563eb;padding:8px 10px;font-size:13px">Table</button>
                    </div>
                </div>

                <div id="book-list" class="books-grid"></div>

                <table id="books-table" class="books-table" style="display:none;margin-top:8px">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Genre</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="table-body"></tbody>
                </table>

            </div>
        </main>

    </div>

</div>

<script src="script.js"></script>
</body>
</html>