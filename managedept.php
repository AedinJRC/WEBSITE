<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Department</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
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
        .btn {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .add-btn {
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
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Department</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        <select name="department">
                            <option>Preschool</option>
                            <option>Grade School</option>
                            <option>Junior High School</option>
                            <option>Senior High School</option>
                            <option>College</option>
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn add-btn">Add</button>
                        <button type="button" class="btn delete-btn">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
