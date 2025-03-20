<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1500px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: rgb(156, 7, 7);
            color: white;
        }
        input[type="file"], input[type="text"], input[type="date"], select {
            border: none;
            background: transparent;
            outline: none;
        }
        .btn {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .edit-btn {
            background-color: #28a745;
            color: white;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Accounts</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Profile Picture</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Department</th>
                        <th>Date Added</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><img src="uploads/" alt=""></td>
                        <td><input type="text" placeholder="First Name"></td>
                        <td><input type="text" placeholder="Last Name"></td>
                        <td>
                            <select>
                                <option>Admin</option>
                                <option>User</option>
                                <option>Accountant</option>
                                <option>GSO</option>
                                <option>GSO Director</option>
                                <option>Immediate Head</option>
                            </select>
                        </td>
                        <td><input type="date"></td>
                        <td>
                            <button class="btn edit-btn">Edit</button>
                            <button class="btn delete-btn">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
